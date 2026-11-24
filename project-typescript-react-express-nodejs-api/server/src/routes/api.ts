import { Router, Request, Response } from 'express';
import { database, PROVINCES, STEP_DEFINITIONS } from '../data/database';
import { PlanCategory, PlanHealth, PlanStage } from '../types/plan';
import { directoryKinds, DirectoryKind } from '../data/directories';
import { createTreeReport, TREE_GROUPS } from '../data/treeReport';
import { createStatusReport } from '../data/statusReport';
import operationsRouter, { apiErrorHandler } from './operations';
import { createFields, updateFields, nonNegativeInteger, validPlanBody } from '../services/planValidation';
import modulesRouter, { modules } from './modules';
import { validateNote } from '../services/notes';
import { DomainError } from '../services/modules';

const router = Router();
router.use(operationsRouter);
router.use(modulesRouter);

router.get('/health', (_req: Request, res: Response) => {
  res.json({
    status: 'ok',
    mode: 'sqlite',
    timestamp: new Date().toISOString(),
  });
});

router.get('/meta', (_req: Request, res: Response) => {
  res.json({
    steps: database.getSteps(),
    provinces: database.getProvinces(),
  });
});

router.get('/dashboard/summary', (_req: Request, res: Response) => {
  res.json(database.getDashboardSummary());
});

router.get('/reports/tree', (_req, res) => {
  res.json(createTreeReport(database.listPlans({})));
});
router.get('/reports/status', (_req, res) => {
  res.json(createStatusReport(database.listPlans({})));
});
router.get('/reports/tree/:group', (req, res) => {
  const key = String(req.params.group);
  if (!Object.hasOwn(TREE_GROUPS, key)) { res.status(404).json({ error: 'ไม่พบประเภทรายงาน' }); return; }
  const group = TREE_GROUPS[key];
  const items = database.listPlans({}).filter(plan => plan.planType === 'ผังเมืองรวมเมือง/ชุมชน' && group.matches(plan));
  res.json({ title: group.title, total: items.length, items });
});

router.get('/regions', (_req: Request, res: Response) => {
  res.json(database.getRegions());
});

router.get('/keths', (_req: Request, res: Response) => {
  res.json(database.getKeths());
});

router.get('/plans', (req: Request, res: Response) => {
  const { region, keth, provinceId, health, stage, category, search } = req.query;
  let items = database.listPlans({
    region: region ? Number(region) : undefined,
    keth: keth ? Number(keth) : undefined,
    provinceId: provinceId ? Number(provinceId) : undefined,
    health: health ? (String(health) as PlanHealth) : undefined,
    stage: stage ? (String(stage) as PlanStage) : undefined,
    category: category ? (String(category) as PlanCategory) : undefined,
    search: search ? String(search) : undefined,
  });
  const exactFields = ['owner', 'operator', 'planType', 'scope', 'budgetYear', 'effectiveStatus'] as const;
  items = items.filter(plan => exactFields.every(field => req.query[field] === undefined || String(plan[field]) === String(req.query[field])) && (req.query.isEec === undefined || plan.isEec === (req.query.isEec === 'true')));
  if (req.query.page !== undefined) {
    const page = Number(req.query.page); const pageSize = Number(req.query.pageSize ?? 25);
    if (!Number.isSafeInteger(page) || page < 1 || !Number.isSafeInteger(pageSize) || pageSize < 1 || pageSize > 100) throw new DomainError('เลขหน้าหรือจำนวนรายการต่อหน้าไม่ถูกต้อง');
    res.json({ total: items.length, items: items.slice((page - 1) * pageSize, page * pageSize), page, pageSize, pageCount: Math.ceil(items.length / pageSize) }); return;
  }
  res.json({ total: items.length, items });
});

router.get('/plans/:id', (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const result = database.getPlanById(id);
  if (!result) {
    res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' });
    return;
  }
  res.json(result);
});

router.post('/plans', (req: Request, res: Response) => {
  if (!validPlanBody(req.body, createFields)) {
    res.status(400).json({ error: 'ข้อมูลผังเมืองไม่ถูกต้อง กรุณาตรวจสอบจังหวัด ขั้นตอน และสถานะ' });
    return;
  }
  const { name, provinceId, stage, category, owner, operator, budgetYear, currentStep, health, isEec } = req.body ?? {};
  if (!name || typeof name !== 'string' || !name.trim()) {
    res.status(400).json({ error: 'กรุณาระบุชื่อผังเมือง' });
    return;
  }
  if (!provinceId) {
    res.status(400).json({ error: 'กรุณาเลือกจังหวัด' });
    return;
  }
  const created = database.createPlan(req.body);
  res.status(201).json(created);
});

router.patch('/plans/:id', (req: Request, res: Response) => {
  if (!validPlanBody(req.body, updateFields) || Object.keys(req.body).length === 0) {
    res.status(400).json({ error: 'ข้อมูลที่แก้ไขไม่ถูกต้อง หรือมีฟิลด์ที่ไม่อนุญาตให้แก้ไข' });
    return;
  }
  const id = Number(req.params.id);
  const updated = database.updatePlan(id, req.body ?? {});
  if (!updated) {
    res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' });
    return;
  }
  res.json(updated);
});

router.delete('/plans/:id', (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const deleted = database.deletePlan(id);
  if (!deleted) {
    res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' });
    return;
  }
  res.json({ deleted: true, id });
});

router.post('/plans/:id/notes', (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const { author, content } = req.body ?? {};
  if (!content || typeof content !== 'string' || !content.trim()) {
    res.status(400).json({ error: 'กรุณาระบุข้อความหมายเหตุ' });
    return;
  }
  const metadata = validateNote(database, req.body, id);
  const { categoryId, step, subStep, thirdStep, caseId } = metadata;
  const note = database.addNote(id, String(author ?? 'เจ้าหน้าที่ผังเมือง'), content, { categoryId, step, subStep, thirdStep, caseId });
  if (!note) {
    res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' });
    return;
  }
  res.status(201).json(note);
});

router.get('/logs', (_req: Request, res: Response) => {
  res.json(database.getLogs());
});

router.get('/notes', (_req, res) => { res.json(database.getNotes()); });

router.patch('/steps/:step', (req, res) => {
  const body = req.body;
  if (!body || typeof body.title !== 'string' || !body.title.trim() ||
    !nonNegativeInteger(body.targetWeeks) || body.targetWeeks > 520 ||
    Object.keys(body).some(key => !['title', 'targetWeeks'].includes(key))) {
    res.status(400).json({ error: 'กรุณาระบุชื่อขั้นตอน และระยะเวลา 0–520 สัปดาห์' });
    return;
  }
  const result = database.updateStep(Number(req.params.step), body.title, body.targetWeeks);
  if (!result) { res.status(404).json({ error: 'ไม่พบขั้นตอน' }); return; }
  res.json(result);
});

router.use('/directories/:kind', (req, res, next) => {
  if (!directoryKinds.includes(String(req.params.kind) as DirectoryKind)) {
    res.status(404).json({ error: 'ไม่พบรายการข้อมูล' }); return;
  }
  next();
});
router.get('/directories/:kind', (req, res) => {
  res.json(database.getDirectory(String(req.params.kind) as DirectoryKind));
});
const validDirectoryBody = (body: unknown): body is { name: string; detail: string; active: boolean } => {
  if (!body || typeof body !== 'object' || Array.isArray(body)) return false;
  const value = body as Record<string, unknown>;
  return typeof value.name === 'string' && Boolean(value.name.trim()) && value.name.length <= 250 &&
    typeof value.detail === 'string' && value.detail.length <= 2000 && typeof value.active === 'boolean' &&
    Object.keys(value).every(key => ['name', 'detail', 'active'].includes(key));
};
router.post('/directories/:kind', (req, res) => {
  if (!validDirectoryBody(req.body)) { res.status(400).json({ error: 'กรุณาตรวจสอบชื่อ รายละเอียด และสถานะ' }); return; }
  res.status(201).json(database.saveDirectory(String(req.params.kind) as DirectoryKind, req.body));
});
router.patch('/directories/:kind/:id', (req, res) => {
  if (!validDirectoryBody(req.body)) { res.status(400).json({ error: 'กรุณาตรวจสอบชื่อ รายละเอียด และสถานะ' }); return; }
  const result = database.saveDirectory(String(req.params.kind) as DirectoryKind, req.body, Number(req.params.id));
  if (!result) { res.status(404).json({ error: 'ไม่พบรายการข้อมูล' }); return; }
  res.json(result);
});
router.delete('/directories/:kind/:id', (req, res) => {
  const kind = String(req.params.kind); const id = Number(req.params.id);
  if (kind === 'noteCategories' && database.getNotes().some(note => note.categoryId === id) || kind === 'reasons' && database.listPlans({}).some(plan => plan.considerId === id)) throw new DomainError('ยังมีข้อมูลอ้างถึงรายการนี้', 409);
  if (!database.deleteDirectory(String(req.params.kind) as DirectoryKind, Number(req.params.id))) {
    res.status(404).json({ error: 'ไม่พบรายการข้อมูล' }); return;
  }
  res.json({ deleted: true });
});

router.post(['/database/reset', '/mock/reset'], (_req: Request, res: Response) => {
  database.transaction(() => { database.reset(); modules.initialize(); });
  res.json({ reset: true, message: 'คืนค่าข้อมูลเริ่มต้นเรียบร้อยแล้ว' });
});

router.use(apiErrorHandler);

export default router;
