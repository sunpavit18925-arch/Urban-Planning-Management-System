import { Router } from 'express';
import ExcelJS from 'exceljs';
import { database } from '../data/database';
import { moduleDefinitions } from '../data/moduleDefinitions';
import { DomainError, ModuleService } from '../services/modules';
import { civilDate, todayBangkok } from '../services/calendar';
import { validateNote } from '../services/notes';

export const modules = new ModuleService(database);
const router = Router();
router.get('/modules/meta', (_req, res) => { res.json(moduleDefinitions); });
router.get('/modules/:kind', (req, res) => {
  const rows = modules.list(String(req.params.kind));
  const planId = req.query.planId === undefined ? undefined : Number(req.query.planId);
  res.json(rows.filter(row => planId === undefined || row.data.planId === planId));
});
router.get('/modules/:kind/:id', (req, res) => { res.json(modules.get(String(req.params.kind), Number(req.params.id))); });
router.post('/modules/:kind', (req, res) => {
  if (!req.body || Object.keys(req.body).some(key => key !== 'data')) throw new DomainError('รูปแบบข้อมูลไม่ถูกต้อง');
  res.status(201).json(modules.save(String(req.params.kind), req.body.data));
});
router.patch('/modules/:kind/:id', (req, res) => {
  if (!req.body || Object.keys(req.body).some(key => !['data', 'version'].includes(key))) throw new DomainError('รูปแบบข้อมูลไม่ถูกต้อง');
  res.json(modules.save(String(req.params.kind), req.body.data, Number(req.params.id), req.body.version));
});
router.delete('/modules/:kind/:id', (req, res) => {
  modules.remove(String(req.params.kind), Number(req.params.id), Number(req.body?.version));
  res.json({ deleted: true });
});
router.get('/plans/:id/workflow', (req, res) => {
  const id = Number(req.params.id);
  if (!database.getPlanById(id)) throw new DomainError('ไม่พบผังเมือง', 404);
  const asOf = String(req.query.asOf ?? todayBangkok());
  civilDate(asOf);
  res.json({ cases: modules.list('workflowCases').filter(item => item.data.planId === id), entries: modules.list('progressEntries').filter(item => item.data.planId === id).map(item => ({ ...item, status: modules.status(item, asOf) })), schedules: modules.list('planSchedules').filter(item => item.data.planId === id) });
});
router.get('/schedules/:id', (req, res) => {
  const record = modules.get('planSchedules', Number(req.params.id));
  res.json({ plan: record, rows: JSON.parse(String(record.data.scheduleJson)) });
});
router.post('/schedules/preview', (req, res) => {
  if (!req.body || typeof req.body.profile !== 'string' || typeof req.body.start !== 'string' || typeof req.body.petition !== 'boolean') throw new DomainError('ข้อมูลแผนไม่ถูกต้อง');
  res.json(modules.generateSchedule(req.body.profile, req.body.start, req.body.petition));
});
router.get('/maintenance/:id', (req, res) => { res.json(modules.maintenance(Number(req.params.id))); });
router.get('/snapshots', (_req, res) => { res.json(modules.snapshots()); });
router.post('/snapshots', (req, res) => {
  if (!req.body || typeof req.body.name !== 'string' || !req.body.name.trim() || req.body.name.length > 250 || typeof req.body.asOf !== 'string') throw new DomainError('กรุณาระบุชื่อชุดข้อมูลและวันที่');
  const snapshot = modules.snapshot(req.body.name.trim(), req.body.asOf);
  res.status(201).json({ id: snapshot.id, name: snapshot.data.name, asOf: snapshot.data.asOf });
});
router.get('/snapshots/:id/plans', (req, res) => { res.json(modules.snapshotPlans(Number(req.params.id))); });
router.get('/history', (_req, res) => { res.json(database.getAllHistory()); });
router.get('/system/summary', (_req, res) => {
  const today = todayBangkok();
  const logins = database.getAllLogs().filter(log => log.target === 'session' && log.detail.startsWith('เข้าสู่ระบบ') && new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Bangkok', year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date(log.createdAt)) === today);
  res.json({ today, totalPlans: database.listPlans({}).length, totalNotes: database.getNotes().length, activeAccounts: database.listModules('_accounts').filter(user => user.data.active).length, activeSessions: database.listModules('_sessions').filter(session => Number(session.data.expires) > Date.now()).length, visitsToday: logins.length, usersToday: new Set(logins.map(log => log.actor)).size, historyEntries: database.getAllHistory().length, database: 'SQLite' });
});

router.get('/reports/catalog', (req, res) => {
  let plans = req.query.snapshot ? modules.snapshotPlans(Number(req.query.snapshot)) : database.listPlans({});
  const search = String(req.query.search ?? '').trim().toLowerCase();
  plans = plans.filter(plan => (!search || `${plan.name} ${plan.code} ${plan.provinceName}`.toLowerCase().includes(search)) && (!req.query.provinceId || plan.provinceId === Number(req.query.provinceId)) && (!req.query.owner || plan.owner === req.query.owner) && (!req.query.stage || plan.stage === req.query.stage));
  const dimension = String(req.query.dimension ?? 'budget');
  res.json({ total: plans.length, groups: modules.report(plans, dimension), plans });
});
router.get('/reports/committee/:id', (req, res) => {
  const committee = Number(req.params.id);
  if (!Number.isInteger(committee) || committee < 1 || committee > 7) throw new DomainError('ไม่พบคณะติดตาม', 404);
  const assignments = modules.list('committees').filter(item => item.data.committee === committee);
  const zones = assignments.map(item => Number(item.data.kethId));
  res.json({ committee, assignments, plans: database.listPlans({}).filter(plan => zones.includes(plan.kethId)) });
});
router.get('/reports/modules/:kind.xlsx', async (req, res, next) => {
  try {
    const kind = String(req.params.kind);
    const definition = modules.definition(kind);
    const book = new ExcelJS.Workbook();
    const sheet = book.addWorksheet('ข้อมูล');
    sheet.addRow(['รหัส', ...definition.fields.map(field => field.label)]);
    for (const item of modules.list(kind)) sheet.addRow([item.id, ...definition.fields.map(field => Array.isArray(item.data[field.key]) ? (item.data[field.key] as number[]).join(', ') : item.data[field.key])]);
    sheet.getRow(1).font = { bold: true }; sheet.views = [{ state: 'frozen', ySplit: 1 }]; sheet.columns.forEach(column => { column.width = 24; });
    res.attachment(`UPMS-${kind}.xlsx`).type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet').send(Buffer.from(await book.xlsx.writeBuffer()));
  } catch (error) { next(error); }
});

router.patch('/notes/:id', (req, res) => {
  const existing = database.getNotes().find(note => note.id === Number(req.params.id));
  if (!existing) throw new DomainError('ไม่พบหมายเหตุ', 404);
  const note = database.updateNote(existing.id, validateNote(database, req.body, existing.planId));
  if (!note) throw new DomainError('ไม่พบหมายเหตุ', 404);
  res.json(note);
});
router.delete('/notes/:id', (req, res) => {
  if (!database.deleteNote(Number(req.params.id))) throw new DomainError('ไม่พบหมายเหตุ', 404);
  res.json({ deleted: true });
});

export default router;
