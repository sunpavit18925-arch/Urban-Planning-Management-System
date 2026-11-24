import type { UrbanPlan } from '../types/plan';

// DPT_UPMS2/app/show_extra.php: order, labels and colors of the eight counters.
export const geographyStatuses = [
  { id: 1, label: 'ผังไม่ระบุขั้นตอน', background: '#fff', border: 'red' },
  { id: 2, label: 'ผังมีความก้าวหน้า', background: '#2D882D', border: '#979797' },
  { id: 3, label: 'ผังล่าช้าเล็กน้อย', background: 'yellow', border: '#979797' },
  { id: 4, label: 'ผังล่าช้าปานกลาง', background: 'orange', border: '#979797' },
  { id: 5, label: 'ผังล่าช้ามาก', background: 'red', border: '#979797' },
  { id: 6, label: 'ผังไม่ระบุวัน/เวลาดำเนินการ', background: '#fff', border: '#000' },
  { id: 7, label: 'ผังไม่ระบุขั้นตอนถัดไป', background: '#fff', border: '#979797' },
  { id: 8, label: 'ผังเสนอหน่วยงานที่เกี่ยวข้อง', background: 'linear-gradient(90deg,#0000ff 0%,#0000ff 25%,#ffffff 25%,#ffffff 100%)', border: '#979797' },
] as const;

export function matchesGeographyStatus(plan: UrbanPlan, status: number): boolean {
  if (status === 9) return plan.currentStep > 0 && plan.subStep > 0;
  if (status === 7) return Boolean(plan.nextStepPending);
  if (status === 8) return plan.currentStep === 8 && [2, 4, 5, 6, 8].includes(plan.subStep);
  if (plan.currentStep < 1 || plan.subStep < 1) return status === 1;
  return ({ on_track: 2, slight_delay: 3, moderate_delay: 4, severe_delay: 5, unscheduled: 6 })[plan.health] === status;
}

export interface GeographyRoute { regionId?: number; kethId?: number; provinceId?: number; report: boolean; status?: number }
export function getGeographyRoute(): GeographyRoute {
  const parts = window.location.hash.slice(1).split('/');
  const readId = (key: string, max: number) => {
    const index = parts.indexOf(key);
    const id = index < 0 ? 0 : Number(parts[index + 1]);
    return Number.isInteger(id) && id > 0 && id <= max ? id : undefined;
  };
  const provinceId = readId('province', 77);
  return { regionId: readId('region', 5), kethId: readId('keth', 18), provinceId, report: parts.includes('report') || Boolean(provinceId), status: readId('status', 9) };
}

export function geographyHash(route: GeographyRoute, inspectorates = false): string {
  return (inspectorates ? '#keths' : '#regions') + (route.kethId ? `/keth/${route.kethId}` : route.regionId ? `/region/${route.regionId}` : '') +
    (route.provinceId ? `/province/${route.provinceId}` : route.report ? '/report' : '') + (route.status ? `/status/${route.status}` : '');
}
