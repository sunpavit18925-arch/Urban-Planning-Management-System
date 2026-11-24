import { createHash, randomBytes, scryptSync, timingSafeEqual } from 'node:crypto';
import { Request, Response, RequestHandler, Router } from 'express';
import { UrbanPlanDatabase } from '../data/database';
import { DomainError } from './modules';
import { requestContext, UserRole } from './requestContext';
import { ModuleRecord } from '../types/modules';

interface PublicUser { id: number; username: string; name: string; role: UserRole; active: boolean }
interface Session { user: PublicUser; csrfToken: string; expires: number; record: ModuleRecord }
const roles: UserRole[] = ['superadmin', 'admin', 'guest'];
const digest = (value: string) => createHash('sha256').update(value).digest('hex');
function hashPassword(password: string): string { const salt = randomBytes(16).toString('hex'); return `${salt}:${scryptSync(password, salt, 64).toString('hex')}`; }
function matchesPassword(password: string, saved: string): boolean {
  const [salt, expected] = saved.split(':');
  if (!salt || !expected || !/^[a-f0-9]{128}$/.test(expected)) return false;
  return timingSafeEqual(scryptSync(password, salt, 64), Buffer.from(expected, 'hex'));
}
const publicUser = (item: ModuleRecord): PublicUser => ({ id: item.id, username: String(item.data.username), name: String(item.data.name), role: item.data.role as UserRole, active: Boolean(item.data.active) });

export class AuthService {
  private readonly attempts = new Map<string, { failures: number; expires: number }>();
  readonly router = Router();
  get enforced(): boolean { return this.database.listModules('_accounts').length > 0; }
  constructor(private readonly database: UrbanPlanDatabase, initialPassword = process.env.UPMS_ADMIN_PASSWORD, username = process.env.UPMS_ADMIN_USERNAME ?? 'admin') {
    if (initialPassword && !this.enforced) this.createAccount({ username, name: 'ผู้ดูแลระบบ', role: 'superadmin', password: initialPassword });
    this.router.get('/auth/session', (req, res) => {
      const session = this.session(req);
      res.set('Cache-Control', 'no-store').json({ enforced: this.enforced, user: session?.user ?? (this.enforced ? null : { id: 0, username: '', name: 'เจ้าหน้าที่ผังเมือง', role: 'superadmin', active: true }), csrfToken: session?.csrfToken ?? '' });
    });
    this.router.post('/auth/login', (req, res) => {
      const { username, password } = req.body ?? {};
      if (typeof username !== 'string' || typeof password !== 'string' || password.length > 200 || username.length > 80) throw new DomainError('ข้อมูลเข้าสู่ระบบไม่ถูกต้อง');
      const key = `${req.ip}:${username.toLowerCase()}`;
      const previous = this.attempts.get(key);
      if (previous && previous.expires > Date.now() && previous.failures >= 5) throw new DomainError('ลองเข้าสู่ระบบหลายครั้งเกินไป กรุณารอ 15 นาที', 429);
      const user = this.database.listModules('_accounts').find(item => String(item.data.username) === username.trim().toLowerCase() && item.data.active);
      if (!user || !matchesPassword(password, String(user.data.passwordHash))) {
        if (!user) scryptSync(password, 'unknown-account', 64);
        for (const [entry, value] of this.attempts) if (value.expires <= Date.now()) this.attempts.delete(entry);
        if (this.attempts.size >= 1000) this.attempts.delete(this.attempts.keys().next().value!);
        this.attempts.set(key, { failures: previous && previous.expires > Date.now() ? previous.failures + 1 : 1, expires: Date.now() + 900000 });
        throw new DomainError('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 401);
      }
      this.attempts.delete(key);
      this.issueSession(req, res, publicUser(user));
    });
    this.router.post('/auth/view', (req, res) => {
      if (this.enforced) throw new DomainError('การเปลี่ยนโหมดดูข้อมูลใช้ได้เฉพาะระบบสาธิต', 403);
      const role = req.body?.role as UserRole;
      if (!roles.includes(role)) throw new DomainError('ไม่พบโหมดผู้ใช้');
      this.issueSession(req, res, { id: 0, username: '', name: role === 'guest' ? 'ผู้เยี่ยมชม' : role === 'admin' ? 'ผู้ดูแลระบบ' : 'เจ้าหน้าที่ผังเมือง', role, active: true });
    });
    this.router.post('/auth/logout', this.middleware, (req, res) => {
      const session = this.session(req);
      if (session) this.database.transaction(() => { this.database.deleteModule('_sessions', session.record.id); this.database.recordActivity('UPDATE', 'session', 'ออกจากระบบ'); });
      res.clearCookie('upms_session', { path: '/', sameSite: 'strict', httpOnly: true }); res.json({ loggedOut: true });
    });
    this.router.use('/auth/users', this.middleware, (req, _res, next) => { if (this.currentUser(req)?.role !== 'superadmin') throw new DomainError('ต้องใช้สิทธิ์ผู้ดูแลสูงสุด', 403); next(); });
    this.router.get('/auth/users', (_req, res) => { res.json(this.database.listModules('_accounts').map(publicUser)); });
    this.router.post('/auth/users', (req, res) => { res.status(201).json(this.createAccount(req.body)); });
    this.router.patch('/auth/users/:id', (req, res) => {
      const user = this.database.listModules('_accounts').find(item => item.id === Number(req.params.id));
      if (!user) throw new DomainError('ไม่พบบัญชีผู้ใช้', 404);
      const body = req.body;
      if (!body || typeof body.name !== 'string' || !body.name.trim() || body.name.length > 250 || !roles.includes(body.role) || typeof body.active !== 'boolean' || Object.keys(body).some(key => !['name', 'role', 'active', 'password'].includes(key))) throw new DomainError('ข้อมูลบัญชีไม่ถูกต้อง');
      if (user.data.role === 'superadmin' && (!body.active || body.role !== 'superadmin') && !this.database.listModules('_accounts').some(item => item.id !== user.id && item.data.active && item.data.role === 'superadmin')) throw new DomainError('ต้องเหลือบัญชีผู้ดูแลสูงสุดที่ใช้งานได้อย่างน้อยหนึ่งบัญชี', 409);
      if (body.password !== undefined && (typeof body.password !== 'string' || body.password.length < 12 || body.password.length > 200)) throw new DomainError('รหัสผ่านต้องยาว 12–200 ตัวอักษร');
      const updated = this.database.transaction(() => {
        const saved = this.database.putModule('_accounts', { ...user.data, name: body.name.trim(), role: body.role, active: body.active, ...(body.password ? { passwordHash: hashPassword(body.password) } : {}) }, user.id);
        if (body.password || !body.active) for (const session of this.database.listModules('_sessions').filter(session => session.data.accountId === user.id)) this.database.deleteModule('_sessions', session.id);
        this.database.recordActivity('UPDATE', `account/${user.id}`, 'แก้ไขบัญชีผู้ใช้'); return saved;
      });
      res.json(publicUser(updated));
    });
  }

  createAccount(input: unknown): PublicUser {
    if (!input || typeof input !== 'object' || Array.isArray(input)) throw new DomainError('ข้อมูลบัญชีไม่ถูกต้อง');
    const body = input as Record<string, unknown>;
    if (typeof body.username !== 'string' || !/^[a-zA-Z0-9_.-]{3,50}$/.test(body.username) || typeof body.name !== 'string' || !body.name.trim() || body.name.length > 250 || typeof body.password !== 'string' || body.password.length < 12 || body.password.length > 200 || !roles.includes(body.role as UserRole) || Object.keys(body).some(key => !['username', 'name', 'password', 'role'].includes(key))) throw new DomainError('กรุณาระบุชื่อผู้ใช้ 3–50 ตัวอักษร และรหัสผ่าน 12–200 ตัวอักษร');
    if (!this.enforced && body.role !== 'superadmin') throw new DomainError('บัญชีแรกต้องเป็นผู้ดูแลสูงสุด');
    const username = body.username.toLowerCase();
    if (this.database.listModules('_accounts').some(item => item.data.username === username)) throw new DomainError('มีชื่อผู้ใช้นี้แล้ว', 409);
    return this.database.transaction(() => {
      const user = this.database.putModule('_accounts', { username, name: String(body.name).trim(), role: body.role as string, active: true, passwordHash: hashPassword(body.password as string) });
      this.database.recordActivity('CREATE', `account/${user.id}`, 'สร้างบัญชีผู้ใช้'); return publicUser(user);
    });
  }

  private session(req: Request): Session | null {
    const value = /(?:^|;\s*)upms_session=([a-f0-9]{64})(?:;|$)/.exec(req.headers.cookie ?? '')?.[1];
    if (!value) return null;
    const record = this.database.listModules('_sessions').find(item => item.data.tokenHash === digest(value) && Number(item.data.expires) > Date.now());
    if (!record) return null;
    const user = record.data.accountId ? this.database.listModules('_accounts').find(item => item.id === record.data.accountId && item.data.active) : undefined;
    if (this.enforced && !user) return null;
    return { user: user ? publicUser(user) : { id: 0, username: '', name: String(record.data.name), role: record.data.role as UserRole, active: true }, csrfToken: String(record.data.csrfToken), expires: Number(record.data.expires), record };
  }
  private currentUser(req: Request): PublicUser | null { return this.session(req)?.user ?? (this.enforced ? null : { id: 0, username: '', name: 'เจ้าหน้าที่ผังเมือง', role: 'superadmin', active: true }); }
  private issueSession(req: Request, res: Response, user: PublicUser): void {
    const token = randomBytes(32).toString('hex');
    const csrfToken = randomBytes(24).toString('hex');
    requestContext.run({ actor: user.name, role: user.role }, () => this.database.transaction(() => {
      const old = this.session(req);
      if (old) this.database.deleteModule('_sessions', old.record.id);
      for (const session of this.database.listModules('_sessions')) if (Number(session.data.expires) <= Date.now()) this.database.deleteModule('_sessions', session.id);
      if (this.database.listModules('_sessions').length >= 1000) throw new DomainError('จำนวนเซสชันเต็ม กรุณาลองภายหลัง', 429);
      this.database.putModule('_sessions', { tokenHash: digest(token), accountId: user.id, name: user.name, role: user.role, expires: Date.now() + 8 * 3600000, csrfToken });
      this.database.recordActivity('UPDATE', 'session', 'เข้าสู่ระบบหรือเปลี่ยนโหมดดูข้อมูล');
    }));
    res.cookie('upms_session', token, { httpOnly: true, sameSite: 'strict', secure: req.secure, maxAge: 8 * 3600000, path: '/' });
    res.set('Cache-Control', 'no-store').json({ enforced: this.enforced, user, csrfToken });
  }

  readonly middleware: RequestHandler = (req, res, next) => {
    const route = req.originalUrl.split('?')[0].replace(/^\/api/, '');
    if (route === '/health' || route === '/auth/session') { next(); return; }
    const user = this.currentUser(req);
    if (!user) { res.status(401).json({ error: 'กรุณาเข้าสู่ระบบ' }); return; }
    const write = !['GET', 'HEAD', 'OPTIONS'].includes(req.method);
    if (write) {
      const session = this.session(req);
      if (session && req.headers['x-upms-csrf'] !== session.csrfToken) { res.status(403).json({ error: 'กรุณาโหลดหน้าใหม่ก่อนบันทึกข้อมูล' }); return; }
      const configuration = /^\/(?:auth\/users|database\/reset|mock\/reset|steps|directories|modules\/(?:workflowSteps|provinceAssignments|committees|userDirectory))\b/.test(route);
      if (route !== '/auth/logout' && (user.role === 'guest' || configuration && user.role !== 'superadmin')) { res.status(403).json({ error: 'สิทธิ์ของบัญชีนี้ไม่อนุญาตให้แก้ไขข้อมูลส่วนนี้' }); return; }
    }
    requestContext.run({ actor: user.name, role: user.role }, next);
  };
}
