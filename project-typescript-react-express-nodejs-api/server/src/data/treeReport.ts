import type { UrbanPlan } from '../types/plan';

// DPT_UPMS2/report/tree-chart.php keeps these two totals outside tb_cityplan.
// They are reference values; the city/community branches use the live database.
export const TREE_REFERENCE_TOTALS = { province: 70, regionalEec: 1 };
const nonEec = (plan: UrbanPlan) => !plan.isEec;
const announced = (plan: UrbanPlan) => plan.effectiveStatus !== 'ยังไม่มีผลบังคับใช้';
const evaluation = (plan: UrbanPlan) => nonEec(plan) && ['ประเมิน 5 ปี', 'ประเมิน 10 ปี'].includes(plan.stage);
const improving = (plan: UrbanPlan) => nonEec(plan) && plan.stage === 'ปรับปรุง' && announced(plan);
const active = (plan: UrbanPlan) => nonEec(plan) && plan.stage === 'ปรับปรุง' && plan.effectiveStatus === 'มีผลบังคับใช้';
const expired = (plan: UrbanPlan) => nonEec(plan) && plan.stage === 'ปรับปรุง' && plan.effectiveStatus === 'สิ้นสุดบังคับใช้';
const newPlan = (plan: UrbanPlan) => nonEec(plan) && plan.stage === 'เปิดใหม่';
const owner = (predicate: (plan: UrbanPlan) => boolean, value: UrbanPlan['owner']) => (plan: UrbanPlan) => predicate(plan) && plan.owner === value;

export const TREE_GROUPS: Record<string, { title: string; matches: (plan: UrbanPlan) => boolean }> = {
  cityplan: { title: 'ผังเมืองรวมเมือง/ชุมชน', matches: () => true },
  notice: { title: 'ผังเมืองรวมเมือง/ชุมชนประกาศใช้บังคับ', matches: p => nonEec(p) && p.stage !== 'เปิดใหม่' && announced(p) },
  new: { title: 'ผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่', matches: newPlan },
  eec: { title: 'ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ EEC', matches: p => p.isEec },
  eva: { title: 'ประเมินผลผัง', matches: evaluation },
  eva5: { title: 'ประเมินผล (ครบ 5 ปี)', matches: p => nonEec(p) && p.stage === 'ประเมิน 5 ปี' },
  eva10: { title: 'ประเมินผล (ครบ 10 ปี)', matches: p => nonEec(p) && p.stage === 'ประเมิน 10 ปี' },
  edit: { title: 'ปรับปรุงผัง', matches: improving },
  bbf: { title: 'มีผลบังคับใช้', matches: active },
  exp: { title: 'สิ้นสุดใช้บังคับ', matches: expired },
  editY: { title: 'ดำเนินการอยู่ในขั้นตอนการปรับปรุงผังฯ', matches: improving },
  bbfY: { title: 'กฎกระทรวงผังเมืองรวมมีผลบังคับใช้', matches: active },
  'bbfY-1': { title: 'กฎกระทรวงผังเมืองรวมมีผลบังคับใช้ (ผังของกรมฯ)', matches: owner(active, 'กรม') },
  'bbfY-2': { title: 'กฎกระทรวงผังเมืองรวมมีผลบังคับใช้ (ผังของอปท.)', matches: owner(active, 'อปท.') },
  expY: { title: 'กฎกระทรวงผังเมืองรวมสิ้นสุดใช้บังคับ', matches: expired },
  'expY-1': { title: 'กฎกระทรวงผังเมืองรวมสิ้นสุดใช้บังคับ (ผังของกรมฯ)', matches: owner(expired, 'กรม') },
  'expY-2': { title: 'กฎกระทรวงผังเมืองรวมสิ้นสุดใช้บังคับ (ผังของอปท.)', matches: owner(expired, 'อปท.') },
  'newY-1': { title: 'ผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่ (ผังของกรมฯ)', matches: owner(newPlan, 'กรม') },
  'newY-2': { title: 'ผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่ (ผังของอปท.)', matches: owner(newPlan, 'อปท.') },
  new_eec: { title: 'ดำเนินการอยู่ในขั้นตอนการวางและจัดทำ', matches: p => p.stage === 'เปิดใหม่' || p.isEec },
  eecY: { title: 'ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ EEC', matches: p => p.isEec },
  'eecY-1': { title: 'ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ EEC (ผังของกรมฯ)', matches: owner(p => p.isEec, 'กรม') },
  'eecY-2': { title: 'ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ EEC (ผังของอปท.)', matches: owner(p => p.isEec, 'อปท.') },
};

export function createTreeReport(plans: UrbanPlan[]) {
  const communityPlans = plans.filter(plan => plan.planType === 'ผังเมืองรวมเมือง/ชุมชน');
  const counts = Object.fromEntries(Object.entries(TREE_GROUPS).map(([key, group]) => [key, communityPlans.filter(group.matches).length]));
  return {
    counts: { ...counts, ...TREE_REFERENCE_TOTALS, all: communityPlans.length + TREE_REFERENCE_TOTALS.province + TREE_REFERENCE_TOTALS.regionalEec },
    groups: Object.entries(TREE_GROUPS).map(([key, group]) => ({ key, title: group.title, count: counts[key] })),
    asOf: new Date().toISOString(),
  };
}
