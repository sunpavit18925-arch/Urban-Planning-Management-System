import assert from 'node:assert/strict';
import { after, before, test } from 'node:test';
import { once } from 'node:events';
import type { Server } from 'node:http';

process.env.UPMS_DB_PATH = ':memory:';
process.env.UPMS_ADMIN_PASSWORD = '';
const express = require('express') as typeof import('express');
const { UrbanPlanDatabase, database } = require('../data/database') as typeof import('../data/database');
const { AuthService } = require('../services/auth') as typeof import('../services/auth');
const { apiErrorHandler } = require('../routes/operations') as typeof import('../routes/operations');
const disk = new UrbanPlanDatabase(':memory:');
const auth = new AuthService(disk, 'a-strong-initial-password', 'owner');
let server: Server; let base: string;
interface Login { user: { id: number; role: string }; csrfToken: string }
interface Jar { cookie: string; csrf: string }
before(async () => {
  const app = express(); app.use(express.json()); app.use(auth.router); app.use(auth.middleware);
  app.get('/plans', (_req, res) => res.json({ allowed: true }));
  app.post('/plans', (_req, res) => { disk.recordActivity('CREATE', 'test', 'บันทึกโดยผู้ใช้'); res.json({ allowed: true }); });
  app.post('/steps', (_req, res) => res.json({ allowed: true }));
  app.use(apiErrorHandler);
  server = app.listen(0, '127.0.0.1'); await once(server, 'listening'); const address = server.address(); assert.ok(address && typeof address !== 'string'); base = `http://127.0.0.1:${address.port}`;
});
after(async () => { await new Promise<void>(resolve => server.close(() => resolve())); disk.close(); database.close(); });
async function call<T = Record<string, unknown>>(route: string, method = 'GET', body?: unknown, jar?: Jar, status = 200): Promise<{ data: T; response: Response }> {
  const response = await fetch(base + route, { method, headers: { 'Content-Type': 'application/json', ...(jar ? { Cookie: jar.cookie, 'X-UPMS-CSRF': jar.csrf } : {}) }, body: body === undefined ? undefined : JSON.stringify(body) });
  const data = await response.json(); assert.equal(response.status, status, `${method} ${route}: ${JSON.stringify(data)}`); return { data, response };
}
async function login(username: string, password: string): Promise<Jar> { const { data, response } = await call<Login>('/auth/login', 'POST', { username, password }); return { cookie: response.headers.get('set-cookie')!.split(';')[0], csrf: data.csrfToken }; }

test('configured accounts require login; passwords and hashes never appear in session/user responses', async () => {
  await call('/plans', 'GET', undefined, undefined, 401);
  await call('/auth/users', 'GET', undefined, undefined, 401);
  const session = await call('/auth/session'); assert.equal(session.data.user, null); assert.equal(session.data.enforced, true);
  await call('/auth/login', 'POST', { username: 'owner', password: 'wrong' }, undefined, 401);
  const jar = await login('owner', 'a-strong-initial-password');
  const users = await call<Array<Record<string, unknown>>>('/auth/users', 'GET', undefined, jar);
  assert.ok(!JSON.stringify(users.data).includes('password')); assert.equal(users.data[0].username, 'owner');
  assert.ok(!JSON.stringify((await call('/auth/session', 'GET', undefined, jar)).data).includes('passwordHash'));
  assert.match(jar.cookie, /^upms_session=[a-f0-9]{64}$/);
});

test('server enforces guest/admin/superadmin roles and CSRF independently of the UI', async () => {
  const owner = await login('owner', 'a-strong-initial-password');
  await call('/auth/users', 'POST', { username: 'editor', name: 'ผู้แก้ไข', role: 'admin', password: 'editor-strong-password' }, owner, 201);
  await call('/auth/users', 'POST', { username: 'reader', name: 'ผู้อ่าน', role: 'guest', password: 'reader-strong-password' }, owner, 201);
  const editor = await login('editor', 'editor-strong-password'); const reader = await login('reader', 'reader-strong-password');
  await call('/plans', 'GET', undefined, reader);
  await call('/plans', 'POST', {}, reader, 403);
  await call('/plans', 'POST', {}, editor);
  assert.equal(disk.getLogs()[0].actor, 'ผู้แก้ไข');
  await call('/steps', 'POST', {}, editor, 403);
  await call('/auth/users', 'GET', undefined, editor, 403);
  await call('/plans', 'POST', {}, { cookie: editor.cookie, csrf: '' }, 403);
  await call('/steps', 'POST', {}, owner);
  await call('/auth/view', 'POST', { role: 'superadmin' }, reader, 403);
  await call('/auth/logout', 'POST', undefined, reader);
  await call('/plans', 'GET', undefined, reader, 401);
});

test('last superadmin cannot be disabled; password changes revoke old sessions and survive data reset', async () => {
  const owner = await login('owner', 'a-strong-initial-password');
  const users = (await call<Array<{ id: number; username: string }>>('/auth/users', 'GET', undefined, owner)).data;
  const admin = users.find(user => user.username === 'owner')!; const reader = users.find(user => user.username === 'reader')!;
  await call(`/auth/users/${admin.id}`, 'PATCH', { name: 'ผู้ดูแล', role: 'guest', active: true }, owner, 409);
  const old = await login('reader', 'reader-strong-password');
  await call(`/auth/users/${reader.id}`, 'PATCH', { name: 'ผู้อ่านใหม่', role: 'guest', active: true, password: 'changed-reader-password' }, owner);
  await call('/plans', 'GET', undefined, old, 401);
  await call('/auth/login', 'POST', { username: 'reader', password: 'reader-strong-password' }, undefined, 401);
  const current = await login('reader', 'changed-reader-password');
  disk.reset();
  await call('/plans', 'GET', undefined, current);
  await call('/plans', 'GET', undefined, owner);
});

test('session rotation revokes prior tokens and login throttling rejects repeated failures', async () => {
  const first = await login('owner', 'a-strong-initial-password');
  const rotated = await call<Login>('/auth/login', 'POST', { username: 'owner', password: 'a-strong-initial-password' }, first);
  assert.notEqual(rotated.response.headers.get('set-cookie')!.split(';')[0], first.cookie);
  await call('/plans', 'GET', undefined, first, 401);
  for (let index = 0; index < 5; index++) await call('/auth/login', 'POST', { username: 'unknown', password: 'wrong-password' }, undefined, 401);
  await call('/auth/login', 'POST', { username: 'unknown', password: 'wrong-password' }, undefined, 429);
});

test('demo mode allows view selection until a real superadmin account is configured', async () => {
  const demo = new UrbanPlanDatabase(':memory:');
  try {
    const service = new AuthService(demo, ''); assert.equal(service.enforced, false);
    assert.throws(() => service.createAccount({ username: 'guest', name: 'ผู้เยี่ยมชม', role: 'guest', password: 'a-strong-password' }));
    service.createAccount({ username: 'owner', name: 'ผู้ดูแล', role: 'superadmin', password: 'a-strong-password' });
    assert.equal(service.enforced, true);
    assert.throws(() => service.createAccount({ username: 'owner', name: 'ซ้ำ', role: 'guest', password: 'a-strong-password' }));
  } finally { demo.close(); }
});
