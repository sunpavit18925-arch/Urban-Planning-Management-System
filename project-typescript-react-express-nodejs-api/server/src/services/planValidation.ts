import { database, PROVINCES, STEP_DEFINITIONS } from '../data/database';
import { civilDate } from './calendar';

const oneOf = (values: unknown[]) => (value: unknown) => values.includes(value);
export const nonNegativeInteger = (value: unknown): boolean => typeof value === 'number' && Number.isSafeInteger(value) && value >= 0;
export const planRules: Record<string, (value: unknown) => boolean> = {
  name: value => typeof value === 'string' && Boolean(value.trim()) && value.length <= 250,
  provinceId: value => typeof value === 'number' && PROVINCES.some(province => province.id === value),
  stage: oneOf(['เปิดใหม่', 'ปรับปรุง', 'ประเมิน 5 ปี', 'ประเมิน 10 ปี']),
  category: oneOf(['general', 'm35', 'evaluation']),
  owner: oneOf(['กรม', 'อปท.']),
  operator: oneOf(['กรม', 'อปท.', 'จ้างที่ปรึกษา', 'กรมร่วมอปท.', 'ร่วมดำเนินการ', 'อื่นๆ', 'ไม่ระบุ']),
  budgetYear: value => typeof value === 'string' && /^\d{4}$/.test(value),
  currentStep: value => STEP_DEFINITIONS.some(step => step.step === value),
  subStep: nonNegativeInteger,
  delayDays: nonNegativeInteger,
  health: oneOf(['on_track', 'slight_delay', 'moderate_delay', 'severe_delay', 'unscheduled']),
  effectiveStatus: oneOf(['มีผลบังคับใช้', 'ยังไม่มีผลบังคับใช้', 'สิ้นสุดบังคับใช้']),
  isEec: value => typeof value === 'boolean',
  trackingState: oneOf(['unspecified', 'draft_law', 'revisit', 'act', 'expedite']),
  planType: oneOf(['ผังเมืองรวมจังหวัด', 'ผังเมืองรวมเมือง/ชุมชน']),
  scope: oneOf(['ทั่วทั้งอำเภอ', 'เฉพาะพื้นที่']),
  thirdStep: nonNegativeInteger,
  petition: value => typeof value === 'boolean',
  nextStepPending: value => typeof value === 'boolean',
  considerId: value => nonNegativeInteger(value) && (value === 0 || database.getDirectory('reasons').some(reason => reason.id === value)),
  externalCode: value => typeof value === 'string' && value.length <= 100,
  startDate: validDate,
  publishedOn: validDate,
  expiredOn: validDate,
};
function validDate(value: unknown): boolean { if (typeof value !== 'string') return false; if (!value) return true; try { civilDate(value); return true; } catch { return false; } }
export const createFields = ['name', 'provinceId', 'stage', 'category', 'owner', 'operator', 'budgetYear', 'currentStep', 'health', 'isEec', 'planType', 'scope', 'effectiveStatus', 'startDate', 'externalCode', 'publishedOn', 'expiredOn', 'petition', 'considerId'];
export const updateFields = Object.keys(planRules);

export function validPlanBody(body: unknown, allowedFields: string[]): boolean {
  return Boolean(body && typeof body === 'object' && !Array.isArray(body)) &&
    Object.entries(body as Record<string, unknown>).every(([key, value]) =>
      allowedFields.includes(key) && Boolean(planRules[key]?.(value)));
}
