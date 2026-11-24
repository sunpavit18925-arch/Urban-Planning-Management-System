export type RegionId = 1 | 2 | 3 | 4 | 5;

export type PlanHealth =
  | 'on_track'
  | 'slight_delay'
  | 'moderate_delay'
  | 'severe_delay'
  | 'unscheduled';

export type PlanStage = 'เปิดใหม่' | 'ประเมิน 5 ปี' | 'ปรับปรุง' | 'ประเมิน 10 ปี';

export type PlanCategory = 'general' | 'm35' | 'evaluation';

export interface ProvinceInfo {
  id: number;
  name: string;
  regionId: RegionId;
  kethId: number;
  affiliationId?: number;
}

export interface RegionInfo {
  id: RegionId;
  name: string;
  color: string;
  provinces: ProvinceInfo[];
  planCount: number;
}

export interface KethInfo {
  kethId: number;
  name: string;
  regionName: string;
  color: string;
  provinces: ProvinceInfo[];
  planCount: number;
}

export interface StepDefinition {
  step: number;
  title: string;
  targetWeeks: number;
}

export interface PlanNote {
  id: number;
  planId: number;
  author: string;
  content: string;
  createdAt: string;
  updatedAt?: string;
  categoryId?: number;
  step?: number;
  subStep?: number;
  thirdStep?: number;
  caseId?: number;
  active?: boolean;
}

export interface UrbanPlan {
  id: number;
  code: string;
  name: string;
  provinceId: number;
  provinceName: string;
  regionId: RegionId;
  regionName: string;
  kethId: number;
  planType: 'ผังเมืองรวมจังหวัด' | 'ผังเมืองรวมเมือง/ชุมชน';
  scope: 'ทั่วทั้งอำเภอ' | 'เฉพาะพื้นที่';
  owner: 'กรม' | 'อปท.';
  operator: 'กรม' | 'อปท.' | 'จ้างที่ปรึกษา' | 'กรมร่วมอปท.' | 'ร่วมดำเนินการ' | 'อื่นๆ' | 'ไม่ระบุ';
  budgetYear: string;
  stage: PlanStage;
  category: PlanCategory;
  effectiveStatus: 'มีผลบังคับใช้' | 'ยังไม่มีผลบังคับใช้' | 'สิ้นสุดบังคับใช้';
  currentStep: number;
  subStep: number;
  health: PlanHealth;
  delayDays: number;
  startDate: string;
  updatedAt: string;
  isEec: boolean;
  nextStepPending?: boolean;
  trackingState?: 'unspecified' | 'draft_law' | 'revisit' | 'act' | 'expedite';
  thirdStep?: number;
  petition?: boolean;
  considerId?: number;
  publishedOn?: string;
  expiredOn?: string;
  externalCode?: string;
}

export interface ActivityLog {
  id: number;
  actor: string;
  action: 'CREATE' | 'UPDATE' | 'DELETE' | 'NOTE' | 'RESET' | 'IMPORT' | 'ATTACHMENT';
  target: string;
  detail: string;
  createdAt: string;
}

export interface DashboardSummary {
  totalPlans: number;
  onTrackCount: number;
  delayedCount: number;
  effectiveCount: number;
  m35Count: number;
  evaluationCount: number;
  byRegion: Array<{
    regionId: RegionId;
    regionName: string;
    color: string;
    count: number;
    onTrack: number;
    delayed: number;
  }>;
  byHealth: Array<{
    health: PlanHealth;
    label: string;
    count: number;
  }>;
  byStage: Array<{
    stage: PlanStage;
    count: number;
  }>;
}
