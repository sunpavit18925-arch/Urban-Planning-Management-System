import { PROVINCES } from './provinces';
import type { UrbanPlan } from '../types/plan';

const stageLabels: Record<string, string> = { new: 'การวางและจัดทำผัง', evaluation: 'การประเมินผลผัง', improvement: 'การปรับปรุงผัง' };
export function createStatusReport(plans: UrbanPlan[]) {
  const entry = (key: string, label: string, color: string, matches: (p: UrbanPlan) => boolean) => {
    const planIds = plans.filter(matches).map(p => p.id);
    return { key, label, color, count: planIds.length, planIds };
  };
  const opened = (p: UrbanPlan) => p.stage === 'เปิดใหม่';
  const improving = (p: UrbanPlan) => p.stage === 'ปรับปรุง';
  const firstStep = (p: UrbanPlan, step: number) => step === 0 ? p.currentStep === 0 || p.subStep === 0 : p.currentStep === step && p.subStep > 0;
  const status = [
    entry('active', 'ผังที่มีผลบังคับใช้', '#5dbb48', p => p.effectiveStatus === 'มีผลบังคับใช้'),
    entry('expired', 'ผังที่สิ้นสุดบังคับใช้', '#b0b0b0', p => p.effectiveStatus === 'สิ้นสุดบังคับใช้'),
    entry('new', 'ผังเปิดพื้นที่ใหม่', '#ff6384', p => p.effectiveStatus === 'ยังไม่มีผลบังคับใช้'),
  ];
  const stage = [
    entry('new', stageLabels.new, '#ff6384', opened),
    entry('evaluation', stageLabels.evaluation, '#ff9957', p => ['ประเมิน 5 ปี', 'ประเมิน 10 ปี'].includes(p.stage)),
    entry('improvement', stageLabels.improvement, '#006cff', improving),
  ];
  const steps = (matches: (p: UrbanPlan) => boolean, colors: string[]) => [...Array.from({ length: 8 }, (_, i) => i + 1), 0].map((step, index) =>
    entry(String(step), step ? String(step) : 'อื่นๆ', colors[index], p => matches(p) && firstStep(p, step)));
  const newSteps = steps(opened, ['#ffdfec', '#ffb3cb', '#ff9aba', '#ff7ea6', '#ff6a99', '#ff548a', '#ff3877', '#ff1c64', '#ff0000']);
  const improvementSteps = steps(improving, ['#c2dcff', '#a4caff', '#86b9ff', '#68a7ff', '#4996ff', '#2b84ff', '#0d73ff', '#0060df', '#ff0000']);
  const affiliation = [1, 2, 3, 4].map(id => entry(String(id), `สำนักผังเมืองรวมส่วนที่ ${id}`, ['#5dbb48', '#ff9957', '#006cff', '#9b487a'][id - 1], p => PROVINCES.find(province => province.id === p.provinceId)?.affiliationId === id));
  const rowDefinitions = [
    { key: 'draft_law', label: '1) ยกร่างกฎหมาย' }, { key: 'revisit', label: '2) ย้อนดำเนินการ' },
    { key: 'act', label: '2. ตามขั้นตอนของพระราชบัญญัติการผังเมือง พ.ศ. 2562' },
    { key: 'expedite', label: '3. เร่งรัดปรับปรุง' }, { key: 'unspecified', label: 'ไม่ระบุ' },
  ];
  const columnDefinitions = [
    { key: 'expired', label: 'กฎกระทรวงผังเมืองรวมสิ้นสุดใช้บังคับ (ดำเนินการปรับปรุงผัง)', matches: (p: UrbanPlan) => improving(p) && p.effectiveStatus === 'สิ้นสุดบังคับใช้' },
    { key: 'active', label: 'กฎกระทรวงผังเมืองรวมมีผลบังคับใช้ (ดำเนินการปรับปรุงผัง)', matches: (p: UrbanPlan) => improving(p) && p.effectiveStatus === 'มีผลบังคับใช้' },
    { key: 'new', label: 'ผังเมืองรวม/ชุมชนเปิดพื้นที่ใหม่', matches: opened },
  ];
  const matrix = columnDefinitions.map(column => {
    const included = plans.filter(column.matches);
    return { key: column.key, label: column.label, count: included.length, planIds: included.map(p => p.id), rows: rowDefinitions.map(row => {
      const planIds = included.filter(p => (p.trackingState ?? 'unspecified') === row.key).map(p => p.id);
      return { ...row, count: planIds.length, planIds };
    }) };
  });
  return { total: plans.length, asOf: new Date().toISOString(), panels: { status, stage, newSteps, improvementSteps, affiliation }, matrix, plans };
}
