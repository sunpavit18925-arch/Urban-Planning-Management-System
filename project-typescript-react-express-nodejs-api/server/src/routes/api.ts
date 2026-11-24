import { Router, Request, Response } from 'express';
import { mockDb, PROVINCES, STEP_DEFINITIONS } from '../data/mockDb';
import { PlanCategory, PlanHealth, PlanStage } from '../types/plan';
import { directoryKinds, DirectoryKind } from '../data/directories';
import { createTreeReport, TREE_GROUPS } from '../data/treeReport';
import { createStatusReport } from '../data/statusReport';

const router = Router();

const oneOf = (values: unknown[]) => (value: unknown) => values.includes(value);
const nonNegativeInteger = (value: unknown) => typeof value === 'number' && Number.isInteger(value) && value >= 0;
const planRules: Record<string, (value: unknown) => boolean> = {
  name: value => typeof value === 'string' && Boolean(value.trim()),
  provinceId: value => typeof value === 'number' && PROVINCES.some(province => province.id === value),
  stage: oneOf(['เปิดใหม่', 'ปรับปรุง', 'ประเมิน 5 ปี', 'ประเมิน 10 ปี']),
  category: oneOf(['general', 'm35', 'evaluation']),
  owner: oneOf(['กรม', 'อปท.']),
  operator: oneOf(['กรม', 'อปท.', 'จ้างที่ปรึกษา', 'กรมร่วมอปท.']),
  budgetYear: value => typeof value === 'string' && /^\d{4}$/.test(value),
  currentStep: value => STEP_DEFINITIONS.some(step => step.step === value),
  subStep: nonNegativeInteger,
  delayDays: nonNegativeInteger,
  health: oneOf(['on_track', 'slight_delay', 'moderate_delay', 'severe_delay', 'unscheduled']),
  effectiveStatus: oneOf(['มีผลบังคับใช้', 'ยังไม่มีผลบังคับใช้', 'สิ้นสุดบังคับใช้']),
  isEec: value => typeof value === 'boolean',
  trackingState: oneOf(['unspecified', 'draft_law', 'revisit', 'act', 'expedite']),
};
const createFields = ['name', 'provinceId', 'stage', 'category', 'owner', 'operator', 'budgetYear', 'currentStep', 'health', 'isEec'];
const updateFields = ['name', 'currentStep', 'subStep', 'health', 'delayDays', 'stage', 'effectiveStatus', 'category', 'trackingState'];

function validPlanBody(body: unknown, allowedFields: string[]): boolean {
  return Boolean(body && typeof body === 'object' && !Array.isArray(body)) &&
    Object.entries(body as Record<string, unknown>).every(([key, value]) =>
      allowedFields.includes(key) && planRules[key](value));
}

router.get('/health', (_req: Request, res: Response) => {
  res.json({
    status: 'ok',
    mode: 'in-memory',
    timestamp: new Date().toISOString(),
  });
});

router.get('/meta', (_req: Request, res: Response) => {
  res.json({
    steps: mockDb.getSteps(),
    provinces: PROVINCES,
  });
});

router.get('/dashboard/summary', (_req: Request, res: Response) => {
  res.json(mockDb.getDashboardSummary());
});

router.get('/reports/tree', (_req, res) => {
  res.json(createTreeReport(mockDb.listPlans({})));
});
router.get('/reports/status', (_req, res) => {
  res.json(createStatusReport(mockDb.listPlans({})));
});
router.get('/reports/tree/:group', (req, res) => {
  const key = String(req.params.group);
  if (!Object.hasOwn(TREE_GROUPS, key)) { res.status(404).json({ error: 'ไม่พบประเภทรายงาน' }); return; }
  const group = TREE_GROUPS[key];
  const items = mockDb.listPlans({}).filter(plan => plan.planType === 'ผังเมืองรวมเมือง/ชุมชน' && group.matches(plan));
  res.json({ title: group.title, total: items.length, items });
});

router.get('/regions', (_req: Request, res: Response) => {
  res.json(mockDb.getRegions());
});

router.get('/keths', (_req: Request, res: Response) => {
  res.json(mockDb.getKeths());
});

router.get('/plans', (req: Request, res: Response) => {
  const { region, keth, provinceId, health, stage, category, search } = req.query;
  const items = mockDb.listPlans({
    region: region ? Number(region) : undefined,
    keth: keth ? Number(keth) : undefined,
    provinceId: provinceId ? Number(provinceId) : undefined,
    health: health ? (String(health) as PlanHealth) : undefined,
    stage: stage ? (String(stage) as PlanStage) : undefined,
    category: category ? (String(category) as PlanCategory) : undefined,
    search: search ? String(search) : undefined,
  });
  res.json({ total: items.length, items });
});

router.get('/plans/:id', (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const result = mockDb.getPlanById(id);
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
  const created = mockDb.createPlan({
    name,
    provinceId: Number(provinceId),
    stage,
    category,
    owner,
    operator,
    budgetYear,
    currentStep: currentStep !== undefined ? Number(currentStep) : undefined,
    health,
    isEec,
  });
  res.status(201).json(created);
});

router.patch('/plans/:id', (req: Request, res: Response) => {
  if (!validPlanBody(req.body, updateFields) || Object.keys(req.body).length === 0) {
    res.status(400).json({ error: 'ข้อมูลที่แก้ไขไม่ถูกต้อง หรือมีฟิลด์ที่ไม่อนุญาตให้แก้ไข' });
    return;
  }
  const id = Number(req.params.id);
  const updated = mockDb.updatePlan(id, req.body ?? {});
  if (!updated) {
    res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' });
    return;
  }
  res.json(updated);
});

router.delete('/plans/:id', (req: Request, res: Response) => {
  const id = Number(req.params.id);
  const deleted = mockDb.deletePlan(id);
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
  const note = mockDb.addNote(id, String(author ?? 'เจ้าหน้าที่ผังเมือง'), content);
  if (!note) {
    res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' });
    return;
  }
  res.status(201).json(note);
});

router.get('/logs', (_req: Request, res: Response) => {
  res.json(mockDb.getLogs());
});

router.get('/notes', (_req, res) => { res.json(mockDb.getNotes()); });

router.patch('/steps/:step', (req, res) => {
  const body = req.body;
  if (!body || typeof body.title !== 'string' || !body.title.trim() ||
    !nonNegativeInteger(body.targetWeeks) || body.targetWeeks > 520 ||
    Object.keys(body).some(key => !['title', 'targetWeeks'].includes(key))) {
    res.status(400).json({ error: 'กรุณาระบุชื่อขั้นตอน และระยะเวลา 0–520 สัปดาห์' });
    return;
  }
  const result = mockDb.updateStep(Number(req.params.step), body.title, body.targetWeeks);
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
  res.json(mockDb.getDirectory(String(req.params.kind) as DirectoryKind));
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
  res.status(201).json(mockDb.saveDirectory(String(req.params.kind) as DirectoryKind, req.body));
});
router.patch('/directories/:kind/:id', (req, res) => {
  if (!validDirectoryBody(req.body)) { res.status(400).json({ error: 'กรุณาตรวจสอบชื่อ รายละเอียด และสถานะ' }); return; }
  const result = mockDb.saveDirectory(String(req.params.kind) as DirectoryKind, req.body, Number(req.params.id));
  if (!result) { res.status(404).json({ error: 'ไม่พบรายการข้อมูล' }); return; }
  res.json(result);
});
router.delete('/directories/:kind/:id', (req, res) => {
  if (!mockDb.deleteDirectory(String(req.params.kind) as DirectoryKind, Number(req.params.id))) {
    res.status(404).json({ error: 'ไม่พบรายการข้อมูล' }); return;
  }
  res.json({ deleted: true });
});

router.post('/mock/reset', (_req: Request, res: Response) => {
  mockDb.reset();
  res.json({ reset: true, message: 'คืนค่าข้อมูลเริ่มต้นเรียบร้อยแล้ว' });
});

export default router;
