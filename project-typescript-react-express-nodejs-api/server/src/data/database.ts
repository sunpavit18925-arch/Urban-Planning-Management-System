import { PROVINCES } from './provinces';
import path from 'node:path';
import { randomUUID } from 'node:crypto';
import { DatabaseState, SqliteStore } from '../storage/sqlite';
import { PlanAttachment, PlanHistory } from '../types/operations';
import { ModuleRecord } from '../types/modules';
import { requestContext } from '../services/requestContext';
import { createDirectories, DirectoryKind, DirectoryRecord } from './directories';
import {
  ActivityLog,
  DashboardSummary,
  PlanCategory,
  PlanHealth,
  PlanNote,
  PlanStage,
  ProvinceInfo,
  RegionId,
  RegionInfo,
  StepDefinition,
  UrbanPlan,
} from '../types/plan';

export const STEP_DEFINITIONS: StepDefinition[] = [
  { step: 0, title: 'ยังไม่เริ่มขั้นตอน / มีผลบังคับใช้แล้ว', targetWeeks: 0 },
  { step: 1, title: 'การสำรวจ กำหนดเขตวางผัง วิเคราะห์และจัดทำร่างผังเมืองรวม', targetWeeks: 10 },
  { step: 2, title: 'การประชุมพิจารณาร่างผังเมืองรวม', targetWeeks: 6 },
  { step: 3, title: 'การประชุมรับฟังความคิดเห็นของประชาชน', targetWeeks: 8 },
  { step: 4, title: 'การประชุมคณะกรรมการผังเมือง (มาตรา 26)', targetWeeks: 4 },
  { step: 5, title: 'การปิดประกาศพร้อมข้อกำหนด 90 วัน (มาตรา 29)', targetWeeks: 13 },
  { step: 6, title: 'การรวบรวม ตรวจสอบ และพิจารณาคำร้อง (มาตรา 30-31)', targetWeeks: 16 },
  { step: 7, title: 'การจัดทำเอกสารประกอบการยกร่างประกาศกระทรวงมหาดไทย', targetWeeks: 6 },
  { step: 8, title: 'การเสนอคณะรัฐมนตรีให้ความเห็นชอบ และประกาศในราชกิจจานุเบกษา', targetWeeks: 10 },
];

export const REGIONS: Array<{ id: RegionId; name: string; color: string }> = [
  { id: 1, name: 'ภาคเหนือ', color: '#979728' },
  { id: 2, name: 'ภาคตะวันออกเฉียงเหนือ', color: '#9b487a' },
  { id: 3, name: 'ภาคกลาง', color: '#3e6633' },
  { id: 4, name: 'ภาคตะวันออก', color: '#33a1ab' },
  { id: 5, name: 'ภาคใต้', color: '#316aa5' },
];

export { PROVINCES } from './provinces';

const HEALTH_LABELS: Record<PlanHealth, string> = {
  on_track: 'มีความก้าวหน้า',
  slight_delay: 'ล่าช้าเล็กน้อย',
  moderate_delay: 'ล่าช้าปานกลาง',
  severe_delay: 'ล่าช้ามาก',
  unscheduled: 'ไม่ระบุวันดำเนินการ',
};

function createInitialPlans(): UrbanPlan[] {
  const seed: Array<{
    id: number;
    name: string;
    provinceId: number;
    stage: PlanStage;
    category: PlanCategory;
    effective: UrbanPlan['effectiveStatus'];
    step: number;
    subStep: number;
    health: PlanHealth;
    delayDays: number;
    budgetYear: string;
    owner: UrbanPlan['owner'];
    operator: UrbanPlan['operator'];
    isEec?: boolean;
  }> = [
    { id: 1, name: 'เมืองเชียงใหม่', provinceId: 24, stage: 'เปิดใหม่', category: 'm35', effective: 'ยังไม่มีผลบังคับใช้', step: 1, subStep: 3, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรม' },
    { id: 2, name: 'ชุมชนเชียงราย', provinceId: 23, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 2, subStep: 2, health: 'slight_delay', delayDays: 12, budgetYear: '2568', owner: 'กรม', operator: 'กรมร่วมอปท.' },
    { id: 3, name: 'เมืองพิษณุโลก', provinceId: 30, stage: 'ปรับปรุง', category: 'general', effective: 'มีผลบังคับใช้', step: 4, subStep: 1, health: 'moderate_delay', delayDays: 38, budgetYear: '2567', owner: 'กรม', operator: 'จ้างที่ปรึกษา' },
    { id: 4, name: 'ชุมชนลำปาง', provinceId: 34, stage: 'ประเมิน 5 ปี', category: 'evaluation', effective: 'มีผลบังคับใช้', step: 0, subStep: 0, health: 'on_track', delayDays: 0, budgetYear: '2567', owner: 'อปท.', operator: 'อปท.' },
    { id: 5, name: 'เมืองนครสวรรค์', provinceId: 26, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 3, subStep: 4, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'จ้างที่ปรึกษา' },

    { id: 6, name: 'เมืองขอนแก่น', provinceId: 40, stage: 'เปิดใหม่', category: 'm35', effective: 'ยังไม่มีผลบังคับใช้', step: 1, subStep: 5, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรม' },
    { id: 7, name: 'เมืองนครราชสีมา', provinceId: 43, stage: 'ปรับปรุง', category: 'general', effective: 'มีผลบังคับใช้', step: 5, subStep: 2, health: 'slight_delay', delayDays: 14, budgetYear: '2567', owner: 'กรม', operator: 'กรมร่วมอปท.' },
    { id: 8, name: 'ชุมชนอุบลราชธานี', provinceId: 58, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 6, subStep: 1, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรม' },
    { id: 9, name: 'เมืองอุดรธานี', provinceId: 57, stage: 'ประเมิน 5 ปี', category: 'evaluation', effective: 'มีผลบังคับใช้', step: 0, subStep: 0, health: 'unscheduled', delayDays: 0, budgetYear: '2566', owner: 'อปท.', operator: 'อปท.' },
    { id: 10, name: 'ชุมชนบุรีรัมย์', provinceId: 45, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 2, subStep: 1, health: 'severe_delay', delayDays: 75, budgetYear: '2567', owner: 'กรม', operator: 'จ้างที่ปรึกษา' },

    { id: 11, name: 'กรุงเทพมหานคร', provinceId: 1, stage: 'ปรับปรุง', category: 'general', effective: 'มีผลบังคับใช้', step: 1, subStep: 2, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรม' },
    { id: 12, name: 'เมืองนนทบุรี', provinceId: 9, stage: 'เปิดใหม่', category: 'm35', effective: 'ยังไม่มีผลบังคับใช้', step: 3, subStep: 2, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรมร่วมอปท.' },
    { id: 13, name: 'เมืองอยุธยา', provinceId: 12, stage: 'ประเมิน 10 ปี', category: 'evaluation', effective: 'มีผลบังคับใช้', step: 7, subStep: 1, health: 'slight_delay', delayDays: 9, budgetYear: '2567', owner: 'กรม', operator: 'กรม' },
    { id: 14, name: 'ชุมชนกาญจนบุรี', provinceId: 60, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 4, subStep: 3, health: 'moderate_delay', delayDays: 42, budgetYear: '2568', owner: 'กรม', operator: 'จ้างที่ปรึกษา' },
    { id: 15, name: 'เมืองราชบุรี', provinceId: 73, stage: 'ประเมิน 5 ปี', category: 'general', effective: 'มีผลบังคับใช้', step: 0, subStep: 0, health: 'on_track', delayDays: 0, budgetYear: '2567', owner: 'อปท.', operator: 'อปท.' },

    { id: 16, name: 'เมืองชลบุรี', provinceId: 4, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 2, subStep: 4, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรม', isEec: true },
    { id: 17, name: 'เมืองระยอง', provinceId: 13, stage: 'ปรับปรุง', category: 'general', effective: 'มีผลบังคับใช้', step: 5, subStep: 1, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรมร่วมอปท.', isEec: true },
    { id: 18, name: 'ชุมชนฉะเชิงเทรา', provinceId: 3, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 8, subStep: 2, health: 'slight_delay', delayDays: 18, budgetYear: '2567', owner: 'กรม', operator: 'กรม', isEec: true },
    { id: 19, name: 'ชุมชนจันทบุรี', provinceId: 2, stage: 'ประเมิน 5 ปี', category: 'evaluation', effective: 'มีผลบังคับใช้', step: 0, subStep: 0, health: 'unscheduled', delayDays: 0, budgetYear: '2567', owner: 'กรม', operator: 'จ้างที่ปรึกษา' },

    { id: 20, name: 'เมืองภูเก็ต', provinceId: 70, stage: 'เปิดใหม่', category: 'm35', effective: 'ยังไม่มีผลบังคับใช้', step: 1, subStep: 4, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรม' },
    { id: 21, name: 'เมืองสงขลา', provinceId: 74, stage: 'ปรับปรุง', category: 'general', effective: 'มีผลบังคับใช้', step: 3, subStep: 1, health: 'on_track', delayDays: 0, budgetYear: '2568', owner: 'กรม', operator: 'กรมร่วมอปท.' },
    { id: 22, name: 'ชุมชนสุราษฎร์ธานี', provinceId: 77, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 6, subStep: 2, health: 'severe_delay', delayDays: 84, budgetYear: '2567', owner: 'กรม', operator: 'จ้างที่ปรึกษา' },
    { id: 23, name: 'ชุมชนกระบี่', provinceId: 59, stage: 'เปิดใหม่', category: 'general', effective: 'ยังไม่มีผลบังคับใช้', step: 2, subStep: 3, health: 'moderate_delay', delayDays: 35, budgetYear: '2568', owner: 'กรม', operator: 'กรม' },
    { id: 24, name: 'เมืองนครศรีธรรมราช', provinceId: 63, stage: 'ประเมิน 5 ปี', category: 'evaluation', effective: 'มีผลบังคับใช้', step: 0, subStep: 0, health: 'on_track', delayDays: 0, budgetYear: '2567', owner: 'อปท.', operator: 'อปท.' },
  ];

  // Seed at least one plan in each province and inspectorate.
  for (const province of PROVINCES) {
    if (seed.some(item => item.provinceId === province.id)) continue;
    const index = seed.length;
    const evaluation = index % 5 === 0;
    const health: PlanHealth = index % 4 === 0 ? 'slight_delay' : index % 7 === 0 ? 'moderate_delay' : 'on_track';
    seed.push({
      id: index + 1, name: `ผังเมืองรวมเมือง${province.name}`, provinceId: province.id,
      stage: evaluation ? 'ประเมิน 5 ปี' : index % 3 === 0 ? 'ปรับปรุง' : 'เปิดใหม่',
      category: evaluation ? 'evaluation' : index % 6 === 0 ? 'm35' : 'general',
      effective: evaluation || index % 3 === 0 ? 'มีผลบังคับใช้' : 'ยังไม่มีผลบังคับใช้',
      step: evaluation ? 0 : index % 8 + 1, subStep: evaluation ? 0 : 1, health,
      delayDays: health === 'slight_delay' ? 12 : health === 'moderate_delay' ? 36 : 0,
      budgetYear: '2569', owner: 'กรม', operator: index % 2 === 0 ? 'กรม' : 'กรมร่วมอปท.',
      isEec: [3, 4, 13].includes(province.id),
    });
  }
  return seed.map((item) => {
    const province = PROVINCES.find((p) => p.id === item.provinceId)!;
    const region = REGIONS.find((r) => r.id === province.regionId)!;
    return {
      id: item.id,
      code: `UPMS-${String(item.id).padStart(3, '0')}`,
      name: item.name,
      provinceId: province.id,
      provinceName: province.name,
      regionId: region.id,
      regionName: region.name,
      kethId: province.kethId,
      planType: 'ผังเมืองรวมเมือง/ชุมชน',
      scope: item.id % 2 === 0 ? 'เฉพาะพื้นที่' : 'ทั่วทั้งอำเภอ',
      owner: item.owner,
      operator: item.operator,
      budgetYear: item.budgetYear,
      stage: item.stage,
      category: item.category,
      effectiveStatus: [7, 21].includes(item.id) ? 'สิ้นสุดบังคับใช้' : item.effective,
      currentStep: item.step,
      subStep: item.subStep,
      health: item.health,
      delayDays: item.delayDays,
      startDate: '2568-01-15',
      updatedAt: new Date().toISOString(),
      isEec: Boolean(item.isEec),
      nextStepPending: item.step > 0 && item.id % 9 === 0,
      trackingState: (['unspecified', 'act', 'act', 'draft_law', 'revisit', 'expedite'] as const)[item.id % 6],
    };
  });
}

function createInitialNotes(): PlanNote[] {
  return [
    {
      id: 1,
      planId: 1,
      author: 'เจ้าหน้าที่ติดตามผังเมือง',
      content: 'อยู่ระหว่างสำรวจข้อมูลกายภาพและจัดทำแผนที่ฐานในพื้นที่',
      createdAt: new Date(Date.now() - 86400000 * 3).toISOString(),
    },
    {
      id: 2,
      planId: 10,
      author: 'หัวหน้ากลุ่มงาน',
      content: 'เร่งรัดการประชุมพิจารณาร่างผังเมืองรวมเพื่อให้ทันกรอบเวลา',
      createdAt: new Date(Date.now() - 86400000).toISOString(),
    },
  ];
}

function createInitialLogs(): ActivityLog[] {
  return [
    {
      id: 1,
      actor: 'ระบบ',
      action: 'RESET',
      target: 'ระบบข้อมูลผังเมือง',
      detail: 'เริ่มต้นระบบข้อมูลผังเมือง ครอบคลุม 77 จังหวัด',
      createdAt: new Date().toISOString(),
    },
  ];
}

export class UrbanPlanDatabase {
  private plans: UrbanPlan[] = createInitialPlans();
  private notes: PlanNote[] = createInitialNotes();
  private logs: ActivityLog[] = createInitialLogs();
  private nextPlanId = this.plans.length + 1;
  private nextNoteId = 3;
  private nextLogId = 2;
  private directories = createDirectories();
  private steps = STEP_DEFINITIONS.map(step => ({ ...step }));
  private history: PlanHistory[] = [];
  private nextHistoryId = 1;
  private readonly store: SqliteStore;
  private batchDepth = 0;

  constructor(filename = process.env.UPMS_DB_PATH ?? path.resolve(__dirname, '../../data/upms.sqlite')) {
    this.store = new SqliteStore(filename);
    const existing = this.store.load();
    if (existing) this.restore(existing);
    else this.persist();
  }

  private restore(state: DatabaseState): void {
    this.plans = state.plans; this.notes = state.notes; this.logs = state.logs;
    this.steps = state.steps; this.directories = state.directories; this.history = state.history;
    this.nextPlanId = state.nextPlanId; this.nextNoteId = state.nextNoteId;
    this.nextLogId = state.nextLogId; this.nextHistoryId = state.nextHistoryId;
  }

  private persist(): void {
    if (this.batchDepth) return;
    try {
      this.store.save({ plans: this.plans, notes: this.notes, logs: this.logs, steps: this.steps,
        directories: this.directories, history: this.history, nextPlanId: this.nextPlanId,
        nextNoteId: this.nextNoteId, nextLogId: this.nextLogId, nextHistoryId: this.nextHistoryId });
    } catch (error) {
      const state = this.store.load();
      if (state) this.restore(state);
      throw error;
    }
  }

  transaction<T>(work: () => T): T {
    try {
      return this.store.transaction(() => {
        this.batchDepth++;
        let result: T;
        try { result = work(); } finally { this.batchDepth--; }
        this.persist();
        return result;
      });
    } catch (error) {
      const state = this.store.load();
      if (state) this.restore(state);
      throw error;
    }
  }

  close(): void { this.store.close(); }

  reset(): void {
    this.transaction(() => {
      this.plans = createInitialPlans();
      this.notes = createInitialNotes();
      this.logs = createInitialLogs();
      this.nextPlanId = this.plans.length + 1;
      this.nextNoteId = 3;
      this.nextLogId = 2;
      this.directories = createDirectories();
      this.steps = STEP_DEFINITIONS.map(step => ({ ...step }));
      this.history = [];
      this.nextHistoryId = 1;
      this.store.clearAttachments();
      this.store.clearModules();
    });
  }

  getSteps(): StepDefinition[] { return this.steps; }

  updateStep(step: number, title: string, targetWeeks: number): StepDefinition | null {
    const item = this.steps.find(item => item.step === step);
    if (!item) return null;
    Object.assign(item, { title: title.trim(), targetWeeks });
    this.addLog('ผู้ดูแลระบบ', 'UPDATE', `STEP-${step}`, `แก้ไขขั้นตอน ${item.title}`);
    return item;
  }

  getNotes(): Array<PlanNote & { planName: string; planCode: string }> {
    return this.notes.filter(note => note.active !== false).map(note => {
      const plan = this.plans.find(plan => plan.id === note.planId)!;
      return { ...note, planName: plan.name, planCode: plan.code };
    }).sort((a, b) => b.id - a.id);
  }

  getDirectory(kind: DirectoryKind): DirectoryRecord[] { return this.directories[kind]; }

  saveDirectory(kind: DirectoryKind, input: Omit<DirectoryRecord, 'id'>, id?: number): DirectoryRecord | null {
    const items = this.directories[kind];
    if (id !== undefined) {
      const item = items.find(item => item.id === id);
      if (!item) return null;
      Object.assign(item, input, { name: input.name.trim(), detail: input.detail.trim() });
      this.addLog('ผู้ดูแลระบบ', 'UPDATE', `${kind}/${id}`, `แก้ไข ${item.name}`);
      return item;
    }
    const item = { ...input, name: input.name.trim(), detail: input.detail.trim(), id: Math.max(0, ...items.map(item => item.id)) + 1 };
    items.push(item);
    this.addLog('ผู้ดูแลระบบ', 'CREATE', `${kind}/${item.id}`, `เพิ่ม ${item.name}`);
    return item;
  }

  deleteDirectory(kind: DirectoryKind, id: number): boolean {
    const items = this.directories[kind];
    const index = items.findIndex(item => item.id === id);
    if (index === -1) return false;
    const [item] = items.splice(index, 1);
    this.addLog('ผู้ดูแลระบบ', 'DELETE', `${kind}/${id}`, `ลบ ${item.name}`);
    return true;
  }

  getRegions(): Array<RegionInfo & { planCount: number }> {
    return REGIONS.map((region) => {
      const provinces = this.getProvinces().filter((p) => p.regionId === region.id);
      const planCount = this.plans.filter((pl) => pl.regionId === region.id).length;
      return {
        ...region,
        provinces,
        planCount,
      };
    });
  }

  getKeths(): Array<{ kethId: number; name: string; regionName: string; color: string; provinces: ProvinceInfo[]; planCount: number }> {
    const kethIds = Array.from(new Set(this.getProvinces().map((p) => p.kethId))).filter(id => id > 0).sort((a, b) => a - b);
    return kethIds.map((kethId) => {
      const provinces = this.getProvinces().filter((p) => p.kethId === kethId);
      const firstRegion = REGIONS.find((r) => r.id === provinces[0]?.regionId) ?? REGIONS[0];
      const planCount = this.plans.filter((pl) => pl.kethId === kethId).length;
      return {
        kethId,
        name: `เขตตรวจราชการที่ ${kethId}`,
        regionName: firstRegion.name,
        color: firstRegion.color,
        provinces,
        planCount,
      };
    });
  }

  getProvinces(): ProvinceInfo[] {
    const assignments = this.listModules('provinceAssignments');
    return PROVINCES.map(province => {
      const assignment = assignments.find(item => item.data.provinceId === province.id);
      return assignment ? { ...province, name: String(assignment.data.name), regionId: Number(assignment.data.regionId) as RegionId, kethId: Number(assignment.data.kethId), affiliationId: Number(assignment.data.affiliationId) } : province;
    }).sort((a, b) => Number(assignments.find(item => item.data.provinceId === a.id)?.data.sortOrder ?? a.id) - Number(assignments.find(item => item.data.provinceId === b.id)?.data.sortOrder ?? b.id));
  }

  getDashboardSummary(): DashboardSummary {
    const totalPlans = this.plans.length;
    const onTrackCount = this.plans.filter((p) => p.health === 'on_track').length;
    const delayedCount = this.plans.filter((p) =>
      ['slight_delay', 'moderate_delay', 'severe_delay'].includes(p.health)
    ).length;
    const effectiveCount = this.plans.filter((p) => p.effectiveStatus === 'มีผลบังคับใช้').length;
    const m35Count = this.plans.filter((p) => p.category === 'm35').length;
    const evaluationCount = this.plans.filter((p) => p.category === 'evaluation').length;

    const byRegion = REGIONS.map((r) => {
      const rp = this.plans.filter((p) => p.regionId === r.id);
      return {
        regionId: r.id,
        regionName: r.name,
        color: r.color,
        count: rp.length,
        onTrack: rp.filter((p) => p.health === 'on_track').length,
        delayed: rp.filter((p) => ['slight_delay', 'moderate_delay', 'severe_delay'].includes(p.health)).length,
      };
    });

    const healthOrder: PlanHealth[] = ['on_track', 'slight_delay', 'moderate_delay', 'severe_delay', 'unscheduled'];
    const byHealth = healthOrder.map((health) => ({
      health,
      label: HEALTH_LABELS[health],
      count: this.plans.filter((p) => p.health === health).length,
    }));

    const stages: PlanStage[] = ['เปิดใหม่', 'ประเมิน 5 ปี', 'ปรับปรุง', 'ประเมิน 10 ปี'];
    const byStage = stages.map((stage) => ({
      stage,
      count: this.plans.filter((p) => p.stage === stage).length,
    }));

    return {
      totalPlans,
      onTrackCount,
      delayedCount,
      effectiveCount,
      m35Count,
      evaluationCount,
      byRegion,
      byHealth,
      byStage,
    };
  }

  listPlans(filters: {
    region?: number;
    keth?: number;
    provinceId?: number;
    health?: PlanHealth;
    stage?: PlanStage;
    category?: PlanCategory;
    search?: string;
  }): UrbanPlan[] {
    return this.plans.filter((plan) => {
      if (filters.region && plan.regionId !== filters.region) return false;
      if (filters.keth && plan.kethId !== filters.keth) return false;
      if (filters.provinceId && plan.provinceId !== filters.provinceId) return false;
      if (filters.health && plan.health !== filters.health) return false;
      if (filters.stage && plan.stage !== filters.stage) return false;
      if (filters.category && plan.category !== filters.category) return false;
      if (filters.search) {
        const q = filters.search.trim().toLowerCase();
        const hit =
          plan.name.toLowerCase().includes(q) ||
          plan.code.toLowerCase().includes(q) ||
          plan.provinceName.toLowerCase().includes(q) ||
          plan.regionName.toLowerCase().includes(q);
        if (!hit) return false;
      }
      return true;
    });
  }

  getPlanById(id: number): { plan: UrbanPlan; notes: PlanNote[] } | null {
    const plan = this.plans.find((p) => p.id === id);
    if (!plan) return null;
    const notes = this.notes.filter((n) => n.planId === id && n.active !== false).sort((a, b) => b.id - a.id);
    return { plan, notes };
  }

  createPlan(input: {
    name: string;
    provinceId: number;
    stage?: PlanStage;
    category?: PlanCategory;
    owner?: UrbanPlan['owner'];
    operator?: UrbanPlan['operator'];
    budgetYear?: string;
    currentStep?: number;
    health?: PlanHealth;
    isEec?: boolean;
    planType?: UrbanPlan['planType'];
    scope?: UrbanPlan['scope'];
    effectiveStatus?: UrbanPlan['effectiveStatus'];
    startDate?: string;
    publishedOn?: string;
    expiredOn?: string;
    externalCode?: string;
    petition?: boolean;
    considerId?: number;
  }): UrbanPlan {
    const province = this.getProvinces().find((p) => p.id === Number(input.provinceId)) ?? PROVINCES[0];
    const region = REGIONS.find((r) => r.id === province.regionId) ?? REGIONS[0];
    const id = this.nextPlanId++;
    const newPlan: UrbanPlan = {
      id,
      code: `UPMS-${String(id).padStart(3, '0')}`,
      name: input.name.trim(),
      provinceId: province.id,
      provinceName: province.name,
      regionId: region.id,
      regionName: region.name,
      kethId: province.kethId,
      planType: input.planType ?? 'ผังเมืองรวมเมือง/ชุมชน',
      scope: input.scope ?? 'ทั่วทั้งอำเภอ',
      owner: input.owner ?? 'กรม',
      operator: input.operator ?? 'กรม',
      budgetYear: input.budgetYear ?? '2568',
      stage: input.stage ?? 'เปิดใหม่',
      category: input.category ?? 'general',
      effectiveStatus: input.effectiveStatus ?? 'ยังไม่มีผลบังคับใช้',
      currentStep: input.currentStep ?? 1,
      subStep: input.currentStep === 0 ? 0 : 1,
      thirdStep: 0,
      health: input.health ?? 'on_track',
      delayDays: input.health === 'slight_delay' ? 10 : input.health === 'moderate_delay' ? 35 : input.health === 'severe_delay' ? 70 : 0,
      startDate: input.startDate ?? '2568-02-01',
      updatedAt: new Date().toISOString(),
      isEec: Boolean(input.isEec),
      nextStepPending: false,
      publishedOn: input.publishedOn ?? '', expiredOn: input.expiredOn ?? '',
      externalCode: input.externalCode ?? '', petition: Boolean(input.petition), considerId: input.considerId ?? 0,
    };
    this.plans.unshift(newPlan);
    this.addLog('เจ้าหน้าที่ผังเมือง', 'CREATE', newPlan.code, `เพิ่มผังเมือง "${newPlan.name}" (${newPlan.provinceName})`);
    return newPlan;
  }

  updatePlan(
    id: number,
    patch: Partial<Omit<UrbanPlan, 'id' | 'code' | 'provinceName' | 'regionId' | 'regionName' | 'kethId' | 'updatedAt'>>
  ): UrbanPlan | null {
    const idx = this.plans.findIndex((p) => p.id === id);
    if (idx === -1) return null;

    const current = this.plans[idx];
    const updated: UrbanPlan = {
      ...current,
      ...patch,
      nextStepPending: patch.nextStepPending ?? (patch.currentStep !== undefined && patch.currentStep !== current.currentStep ||
        patch.subStep !== undefined && patch.subStep !== current.subStep ? false : current.nextStepPending),
      updatedAt: new Date().toISOString(),
    };
    if (updated.currentStep === 0) { updated.subStep = 0; updated.thirdStep = 0; }
    else if (updated.subStep === 0) updated.thirdStep = 0;
    if (patch.provinceId !== undefined) {
      const province = PROVINCES.find(item => item.id === patch.provinceId);
      if (!province) throw new Error('ไม่พบจังหวัด');
      const assignment = this.listModules('provinceAssignments').find(item => item.data.provinceId === province.id);
      updated.provinceName = String(assignment?.data.name ?? province.name);
      updated.regionId = Number(assignment?.data.regionId ?? province.regionId) as RegionId;
      updated.regionName = REGIONS.find(item => item.id === updated.regionId)!.name;
      updated.kethId = Number(assignment?.data.kethId ?? province.kethId);
    }
    this.plans[idx] = updated;
    const changedFields = Object.keys(updated).filter(key => key !== 'updatedAt' && current[key as keyof UrbanPlan] !== updated[key as keyof UrbanPlan]);
    if (changedFields.length) this.history.unshift({ id: this.nextHistoryId++, planId: id,
      changedAt: updated.updatedAt, before: { ...current }, after: { ...updated }, changedFields, actor: requestContext.getStore()?.actor ?? 'เจ้าหน้าที่ผังเมือง' });
    this.addLog('เจ้าหน้าที่ผังเมือง', 'UPDATE', updated.code, `อัปเดตสถานะ "${updated.name}" เป็นขั้นตอนที่ ${updated.currentStep}.${updated.subStep}`);
    return updated;
  }

  deletePlan(id: number): boolean {
    const idx = this.plans.findIndex((p) => p.id === id);
    if (idx === -1) return false;
    const [removed] = this.plans.splice(idx, 1);
    this.notes = this.notes.filter((n) => n.planId !== id);
    this.history = this.history.filter(item => item.planId !== id);
    this.addLog('เจ้าหน้าที่ผังเมือง', 'DELETE', removed.code, `ลบผังเมือง "${removed.name}"`);
    return true;
  }

  addNote(planId: number, author: string, content: string, metadata: Partial<Pick<PlanNote, 'categoryId' | 'step' | 'subStep' | 'thirdStep' | 'caseId'>> = {}): PlanNote | null {
    const plan = this.plans.find((p) => p.id === planId);
    if (!plan) return null;
    const note: PlanNote = {
      id: this.nextNoteId++,
      planId,
      author: requestContext.getStore()?.actor ?? (author.trim() || 'เจ้าหน้าที่ผังเมือง'),
      content: content.trim(),
      createdAt: new Date().toISOString(),
      active: true, step: plan.currentStep, subStep: plan.subStep, thirdStep: plan.thirdStep ?? 0,
      ...Object.fromEntries(Object.entries(metadata).filter(([, value]) => value !== undefined)),
    };
    this.notes.unshift(note);
    this.addLog('เจ้าหน้าที่ผังเมือง', 'NOTE', plan.code, `เพิ่มหมายเหตุใน "${plan.name}"`);
    return note;
  }

  getLogs(): ActivityLog[] {
    return [...this.logs].slice(0, 50);
  }

  listModules(kind: string): ModuleRecord[] { return this.store.listModules(kind); }
  putModule(kind: string, data: ModuleRecord['data'], id?: number): ModuleRecord { return this.store.putModule(kind, data, id); }
  deleteModule(kind: string, id: number): boolean { return this.store.deleteModule(kind, id); }
  recordActivity(action: ActivityLog['action'], target: string, detail: string): void { this.addLog('เจ้าหน้าที่ผังเมือง', action, target, detail); }

  updateNote(id: number, patch: Partial<Omit<PlanNote, 'id' | 'planId' | 'createdAt'>>): PlanNote | null {
    const note = this.notes.find(item => item.id === id);
    if (!note) return null;
    Object.assign(note, patch, { updatedAt: new Date().toISOString() });
    note.content = note.content.trim(); note.author = note.author.trim();
    this.addLog('เจ้าหน้าที่ผังเมือง', 'NOTE', this.getPlanById(note.planId)!.plan.code, 'แก้ไขหมายเหตุ');
    return note;
  }
  deleteNote(id: number): boolean {
    const note = this.notes.find(item => item.id === id);
    if (!note) return false;
    note.active = false;
    note.updatedAt = new Date().toISOString();
    this.addLog('เจ้าหน้าที่ผังเมือง', 'NOTE', this.getPlanById(note.planId)!.plan.code, 'ลบหมายเหตุ');
    return true;
  }

  getHistory(planId: number): PlanHistory[] { return this.history.filter(item => item.planId === planId); }
  getAllHistory(): PlanHistory[] { return [...this.history]; }
  getAllLogs(): ActivityLog[] { return [...this.logs]; }

  listAttachments(planId: number): PlanAttachment[] { return this.store.listAttachments(planId); }
  getAttachment(planId: number, id: string) { return this.store.getAttachment(planId, id); }

  addAttachment(planId: number, name: string, mimeType: PlanAttachment['mimeType'], content: Buffer): PlanAttachment {
    const plan = this.getPlanById(planId)?.plan;
    if (!plan) throw new Error('ไม่พบข้อมูลผังเมือง');
    return this.transaction(() => {
      const file: PlanAttachment = { id: randomUUID(), planId, name, mimeType, size: content.length, createdAt: new Date().toISOString() };
      this.store.addAttachment(file, content);
      this.addLog('เจ้าหน้าที่ผังเมือง', 'ATTACHMENT', plan.code, `แนบไฟล์ ${name}`);
      return file;
    });
  }

  deleteAttachment(planId: number, id: string): boolean {
    return this.transaction(() => {
      const file = this.store.getAttachment(planId, id);
      if (!file) return false;
      this.store.deleteAttachment(planId, id);
      this.addLog('เจ้าหน้าที่ผังเมือง', 'ATTACHMENT', this.getPlanById(planId)!.plan.code, `ลบไฟล์ ${file.name}`);
      return true;
    });
  }

  recordImport(count: number): void { this.addLog('เจ้าหน้าที่ผังเมือง', 'IMPORT', 'ข้อมูลผังเมือง', `นำเข้าผังเมือง ${count} รายการ`); }

  private addLog(actor: string, action: ActivityLog['action'], target: string, detail: string): void {
    this.logs.unshift({
      id: this.nextLogId++,
      actor: requestContext.getStore()?.actor ?? actor,
      action,
      target,
      detail,
      createdAt: new Date().toISOString(),
    });
    this.persist();
  }
}

export const database = new UrbanPlanDatabase();
