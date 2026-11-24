import { UrbanPlanDatabase, STEP_DEFINITIONS, PROVINCES } from '../data/database';
import { moduleDefinition, moduleDefinitions } from '../data/moduleDefinitions';
import { ModuleDefinition, ModuleRecord, FieldValue, ScheduleRow, MaintenanceReport, WorkflowStatus, ReportGroup } from '../types/modules';
import { UrbanPlan } from '../types/plan';
import { addDays, civilDate, inclusiveDeadline, todayBangkok, workflowStatus } from './calendar';

export class DomainError extends Error {
  constructor(message: string, readonly status = 400) { super(message); }
}
const sameKey = (value: unknown) => String(value ?? '').trim().normalize('NFC').toLocaleLowerCase('th-TH');
const numeric = (record: ModuleRecord, key: string): number => Number(record.data[key] ?? 0);
const string = (record: ModuleRecord, key: string): string => String(record.data[key] ?? '');

export class ModuleService {
  constructor(readonly database: UrbanPlanDatabase) { this.initialize(); }

  definition(kind: string): ModuleDefinition {
    const definition = moduleDefinition(kind);
    if (!definition) throw new DomainError('ไม่พบประเภทข้อมูล', 404);
    return definition;
  }
  list(kind: string): ModuleRecord[] { this.definition(kind); return this.database.listModules(kind); }
  get(kind: string, id: number): ModuleRecord {
    const record = this.list(kind).find(item => item.id === id);
    if (!record) throw new DomainError('ไม่พบรายการ', 404);
    return record;
  }

  initialize(): void {
    if (this.database.listModules('_moduleState').length) return;
    this.database.transaction(() => {
      for (const profile of ['general', 'm35', 'evaluation']) {
        for (let step = 1; step <= 8; step++) {
          const base = { profile, step, subStep: 0, thirdStep: 0, operator: 'ทั้งหมด', active: true, durationDays: STEP_DEFINITIONS[step].targetWeeks * 7 || 28, noPetitionDays: step === 6 ? 112 : 28, petitionDays: step === 6 ? 182 : 42, firstThreshold: 30, secondThreshold: 60 };
          this.database.putModule('workflowSteps', { ...base, name: profile === 'general' ? STEP_DEFINITIONS[step].title : `ขั้นตอน${profile === 'm35' ? ' ม.35' : 'ประเมิน'} ${step}` });
          for (let subStep = 1; subStep <= 8; subStep++) {
            const first = profile === 'general' ? step === 5 && subStep === 2 ? 90 : 30 : 28;
            this.database.putModule('workflowSteps', { ...base, name: `ขั้นตอนย่อย ${step}.${subStep}`, subStep, durationDays: 28, noPetitionDays: 28, petitionDays: 42, firstThreshold: first, secondThreshold: first * 2 });
            for (let thirdStep = 1; thirdStep <= 3; thirdStep++) this.database.putModule('workflowSteps', { ...base, name: `ขั้นตอนย่อย ${step}.${subStep}.${thirdStep}`, subStep, thirdStep, durationDays: 14, noPetitionDays: 14, petitionDays: 14, firstThreshold: 14, secondThreshold: 28 });
          }
        }
      }
      for (const province of PROVINCES) this.database.putModule('provinceAssignments', { name: province.name, provinceId: province.id, regionId: province.regionId, kethId: province.kethId, affiliationId: province.affiliationId ?? 1, sortOrder: province.id });
      for (let kethId = 1; kethId <= 18; kethId++) this.database.putModule('committees', { name: `คณะติดตามฯ ${Math.ceil(kethId / 3.6)}`, committee: Math.ceil(kethId / 3.6), kethId, orderNumber: '', assignedOn: '' });
      this.database.putModule('_moduleState', { name: 'initialized', version: 1 });
    });
  }

  private validate(kind: string, input: unknown, previous?: ModuleRecord): Record<string, FieldValue> {
    const definition = this.definition(kind);
    if (!input || typeof input !== 'object' || Array.isArray(input)) throw new DomainError('รูปแบบข้อมูลไม่ถูกต้อง');
    const submitted = input as Record<string, unknown>;
    if (Object.keys(submitted).some(key => !definition.fields.some(field => field.key === key))) throw new DomainError('มีฟิลด์ที่ไม่อนุญาตให้แก้ไข');
    const output: Record<string, FieldValue> = {};
    for (const field of definition.fields) {
      let value = submitted[field.key] ?? previous?.data[field.key] ?? field.default;
      if (typeof value === 'string') value = value.trim();
      if (field.required && (value === '' || value === undefined || value === 0 || Array.isArray(value) && !value.length)) throw new DomainError(`กรุณาระบุ${field.label}`);
      if (field.type === 'boolean') { if (typeof value !== 'boolean') throw new DomainError(`${field.label}ต้องเป็นจริงหรือเท็จ`); }
      else if (field.type === 'number') {
        if (typeof value !== 'number' || !Number.isFinite(value) || field.integer && !Number.isSafeInteger(value) || field.min !== undefined && value < field.min || field.max !== undefined && value > field.max) throw new DomainError(`${field.label}อยู่นอกช่วงที่อนุญาต`);
      } else if (field.type === 'relation' || field.type === 'multi') {
        const ids = field.type === 'multi' ? value : [value];
        if (!Array.isArray(ids) || ids.length > 1000 || ids.some(id => typeof id !== 'number' || !Number.isSafeInteger(id) || id < 0) || new Set(ids).size !== ids.length) throw new DomainError(`${field.label}ไม่ถูกต้อง`);
        for (const id of ids as number[]) {
          if (!id && !field.required && field.type === 'relation') continue;
          if (field.relation === 'plans' ? !this.database.getPlanById(id) : !this.database.listModules(field.relation!).some(item => item.id === id)) throw new DomainError(`ไม่พบ${field.label}ที่เลือก`);
        }
      } else {
        if (typeof value !== 'string' || value.length > (field.max ?? 1000)) throw new DomainError(`${field.label}ยาวเกินกำหนดหรือชนิดไม่ถูกต้อง`);
        if (field.type === 'select' && !field.options?.some(option => option.value === value)) throw new DomainError(`${field.label}ไม่อยู่ในตัวเลือก`);
        if (field.type === 'date' && value) { try { civilDate(value); } catch { throw new DomainError(`${field.label}ไม่ถูกต้อง`); } }
      }
      output[field.key] = value as FieldValue;
    }
    for (const [start, stop] of [['start', 'stop'], ['plannedStart', 'plannedStop']]) {
      if (output[stop] && !output[start]) throw new DomainError('ต้องระบุวันเริ่มต้นก่อนวันสิ้นสุด');
      if (output[start] && output[stop] && civilDate(String(output[stop])) < civilDate(String(output[start]))) throw new DomainError('วันสิ้นสุดต้องไม่อยู่ก่อนวันเริ่มต้น');
    }
    const uniqueness: Record<string, string[]> = { workflowSteps: ['profile', 'step', 'subStep', 'thirdStep', 'operator'], committees: ['kethId'], provinceAssignments: ['provinceId'], userDirectory: ['userCode'], equipment: ['assetNumber'] };
    const keys = uniqueness[kind];
    if (keys && keys.every(key => output[key] !== '') && this.list(kind).some(item => item.id !== previous?.id && keys.every(key => sameKey(item.data[key]) === sameKey(output[key])))) throw new DomainError('มีข้อมูลรหัสหรือขั้นตอนนี้อยู่แล้ว', 409);
    if (kind === 'workflowSteps') {
      if (Number(output.thirdStep) > 0 && Number(output.subStep) === 0) throw new DomainError('ขั้นตอนย่อย 2 ต้องมีขั้นตอนย่อย 1');
      if (Number(output.secondThreshold) < Number(output.firstThreshold)) throw new DomainError('เกณฑ์ระดับ 2 ต้องไม่น้อยกว่าระดับ 1');
    }
    if (kind === 'equipment') {
      const purchased = Number(output.purchasedYear);
      if (Number(output.replacementYear) && Number(output.replacementYear) < purchased || Number(output.firstMaintenanceYear) && Number(output.firstMaintenanceYear) < purchased) throw new DomainError('ปีบำรุงรักษา/ทดแทนต้องไม่ก่อนปีจัดหา');
    }
    if (kind === 'progressEntries') {
      const workflow = this.get('workflowSteps', Number(output.definitionId));
      if (output.caseId) {
        const record = this.get('workflowCases', Number(output.caseId));
        if (record.data.planId !== output.planId || record.data.profile !== workflow.data.profile) throw new DomainError('งานและขั้นตอนต้องเป็นของผังเมืองและประเภทงานเดียวกัน');
      } else if (workflow.data.profile !== 'general') throw new DomainError('ขั้นตอน ม.35 หรือประเมินต้องระบุงานที่เกี่ยวข้อง');
    }
    if (kind === 'provinceAssignments' && !PROVINCES.some(province => province.id === output.provinceId)) throw new DomainError('ไม่พบรหัสจังหวัด');
    return output;
  }

  save(kind: string, input: unknown, id?: number, version?: number): ModuleRecord {
    const previous = id === undefined ? undefined : this.get(kind, id);
    if (previous && previous.version !== version) throw new DomainError('รายการถูกแก้ไขแล้ว กรุณาโหลดข้อมูลใหม่', 409);
    const data = this.validate(kind, input, previous);
    return this.database.transaction(() => {
      if (kind === 'planSchedules') data.scheduleJson = JSON.stringify(this.generateSchedule(String(data.profile), String(data.start), Boolean(data.petition), this.database.getPlanById(Number(data.planId))!.plan.owner));
      const record = this.database.putModule(kind, data, id);
      if (kind === 'provinceAssignments') for (const plan of this.database.listPlans({}).filter(plan => plan.provinceId === data.provinceId)) this.database.updatePlan(plan.id, { provinceId: plan.provinceId });
      if (kind === 'progressEntries' && data.makeCurrent) {
        const definition = this.get('workflowSteps', Number(data.definitionId));
        const status = this.status(record);
        this.database.updatePlan(Number(data.planId), { currentStep: numeric(definition, 'step'), subStep: numeric(definition, 'subStep'), thirdStep: numeric(definition, 'thirdStep'), health: status.health, delayDays: status.delayDays, petition: Boolean(data.petition) });
      }
      this.database.recordActivity(id === undefined ? 'CREATE' : 'UPDATE', `${kind}/${record.id}`, `${id === undefined ? 'เพิ่ม' : 'แก้ไข'}${this.definition(kind).title}: ${String(data.name ?? '')}`);
      return record;
    });
  }

  remove(kind: string, id: number, version: number): void {
    const record = this.get(kind, id);
    if (record.version !== version) throw new DomainError('รายการถูกแก้ไขแล้ว กรุณาโหลดข้อมูลใหม่', 409);
    if (kind === 'provinceAssignments' && this.database.listPlans({}).some(plan => plan.provinceId === record.data.provinceId)) throw new DomainError('ยังมีผังเมืองอยู่ในจังหวัดนี้', 409);
    for (const definition of moduleDefinitions) {
      const references = definition.fields.filter(field => field.relation === kind);
      if (!references.length) continue;
      for (const item of this.list(definition.key)) if (references.some(field => field.type === 'multi' ? (item.data[field.key] as number[]).includes(id) : item.data[field.key] === id)) throw new DomainError(`ยังมีข้อมูลใน${definition.title}อ้างถึงรายการนี้`, 409);
    }
    this.database.transaction(() => {
      this.database.deleteModule(kind, id);
      this.database.recordActivity('DELETE', `${kind}/${id}`, `ลบ${this.definition(kind).title}: ${String(record.data.name)}`);
    });
  }

  status(entry: ModuleRecord, asOf = todayBangkok()): WorkflowStatus {
    const definition = this.get('workflowSteps', numeric(entry, 'definitionId'));
    const tertiary = numeric(definition, 'thirdStep') > 0;
    const special = numeric(definition, 'step') === 6 && !tertiary && definition.data.profile !== 'evaluation';
    const usesDuration = tertiary || definition.data.profile !== 'general';
    const first = special ? numeric(definition, entry.data.petition ? 'petitionDays' : 'noPetitionDays') : numeric(definition, usesDuration ? 'durationDays' : 'firstThreshold');
    return workflowStatus(string(entry, 'start'), string(entry, 'stop'), first, special || usesDuration ? first * 2 : numeric(definition, 'secondThreshold'), asOf);
  }

  generateSchedule(profile: string, start: string, petition: boolean, operator = 'กรม'): ScheduleRow[] {
    civilDate(start);
    let cursor = addDays(start, 0);
    const matching = this.list('workflowSteps').filter(item => item.data.profile === profile && item.data.active && ['ทั้งหมด', operator].includes(String(item.data.operator)));
    const preferred = new Map<string, ModuleRecord>();
    for (const definition of matching) {
      const key = `${definition.data.step}.${definition.data.subStep}.${definition.data.thirdStep}`;
      if (!preferred.has(key) || definition.data.operator === operator) preferred.set(key, definition);
    }
    const definitions = [...preferred.values()];
    if (!definitions.length) throw new DomainError('ไม่พบขั้นตอนสำหรับแผนนี้');
    const rows: ScheduleRow[] = [];
    for (const main of definitions.filter(item => !item.data.subStep).sort((a, b) => numeric(a, 'step') - numeric(b, 'step'))) {
      const mainStart = cursor;
      let subs = definitions.filter(item => item.data.step === main.data.step && Number(item.data.subStep) > 0 && !item.data.thirdStep);
      if (main.data.step === 6 && !petition) subs = subs.filter(item => [4, 5].includes(Number(item.data.subStep)));
      subs.sort((a, b) => numeric(a, 'subStep') - numeric(b, 'subStep'));
      const scheduleLeaf = (item: ModuleRecord): ScheduleRow => {
        const days = numeric(item, main.data.step === 6 ? petition ? 'petitionDays' : 'noPetitionDays' : 'durationDays');
        const result = { definitionId: item.id, title: string(item, 'name'), step: numeric(item, 'step'), subStep: numeric(item, 'subStep'), thirdStep: numeric(item, 'thirdStep'), start: cursor, end: inclusiveDeadline(cursor, days), days };
        cursor = addDays(result.end, 1);
        return result;
      };
      if (!subs.length) { rows.push(scheduleLeaf(main)); continue; }
      const mainRows: ScheduleRow[] = [];
      for (const sub of subs) {
        const leaves = definitions.filter(item => item.data.step === sub.data.step && item.data.subStep === sub.data.subStep && Number(item.data.thirdStep) > 0).sort((a, b) => numeric(a, 'thirdStep') - numeric(b, 'thirdStep'));
        if (!leaves.length) mainRows.push(scheduleLeaf(sub));
        else {
          const subStart = cursor;
          const childRows = leaves.map(scheduleLeaf);
          mainRows.push({ definitionId: sub.id, title: string(sub, 'name'), step: numeric(sub, 'step'), subStep: numeric(sub, 'subStep'), thirdStep: 0, start: subStart, end: childRows[childRows.length - 1].end, days: childRows.reduce((sum, item) => sum + item.days, 0) }, ...childRows);
        }
      }
      const end = addDays(cursor, -1);
      const days = Math.round((civilDate(end).getTime() - civilDate(mainStart).getTime()) / 86400000) + 1;
      rows.push({ definitionId: main.id, title: string(main, 'name'), step: numeric(main, 'step'), subStep: 0, thirdStep: 0, start: mainStart, end, days }, ...mainRows);
    }
    return rows;
  }

  maintenance(id: number): MaintenanceReport {
    const plan = this.get('maintenancePlans', id);
    const years = Array.from({ length: numeric(plan, 'years') }, (_, index) => numeric(plan, 'startYear') + index);
    const selected = plan.data.equipmentIds as number[];
    const rows = this.list('equipment').filter(item => selected.includes(item.id)).map(item => {
      const purchased = numeric(item, 'purchasedYear');
      const replacement = numeric(item, 'replacementYear');
      const maintenanceStart = numeric(item, 'firstMaintenanceYear') || purchased + numeric(item, 'warrantyYears') + 1;
      return { equipmentId: item.id, name: string(item, 'name'), cells: years.map(year => {
        if (year < purchased) return { year, status: 'not_purchased' as const, amount: 0 };
        if (replacement && year >= replacement) return { year, status: 'replacement' as const, amount: year === replacement ? numeric(item, 'price') : 0 };
        if (year < maintenanceStart) return { year, status: 'warranty' as const, amount: 0 };
        return { year, status: 'maintenance' as const, amount: numeric(item, 'maintenanceAmount') };
      }) };
    });
    const totals = years.map((_, index) => rows.reduce((sum, row) => sum + row.cells[index].amount, 0));
    return { years, rows, totals, total: totals.reduce((sum, value) => sum + value, 0) };
  }

  snapshot(name: string, asOf: string): ModuleRecord {
    civilDate(asOf);
    const timestamp = new Date().toISOString();
    return this.database.transaction(() => {
      const record = this.database.putModule('_snapshots', { name, asOf, capturedAt: timestamp, snapshotJson: JSON.stringify(this.database.listPlans({})) });
      this.database.recordActivity('CREATE', `snapshot/${record.id}`, `บันทึกสถานภาพข้อมูล ณ วันที่ ${asOf}`);
      return record;
    });
  }
  snapshots(): ModuleRecord[] { return this.database.listModules('_snapshots').map(record => ({ ...record, data: { name: record.data.name, asOf: record.data.asOf, capturedAt: record.data.capturedAt } })); }
  snapshotPlans(id: number): UrbanPlan[] {
    const record = this.database.listModules('_snapshots').find(record => record.id === id);
    if (!record) throw new DomainError('ไม่พบชุดข้อมูลสถานภาพ', 404);
    return JSON.parse(String(record.data.snapshotJson));
  }

  report(plans: UrbanPlan[], dimension: string): ReportGroup[] {
    const dimensions: Record<string, (plan: UrbanPlan) => string> = {
      budget: plan => plan.budgetYear, type: plan => plan.planType, scope: plan => plan.scope,
      stage: plan => plan.stage, effective: plan => plan.effectiveStatus, operator: plan => plan.operator,
      owner: plan => plan.owner, province: plan => plan.provinceName, region: plan => plan.regionName,
      step: plan => String(plan.currentStep), health: plan => plan.health, eec: plan => plan.isEec ? 'EEC' : 'นอก EEC',
      category: plan => plan.category,
    };
    if (!Object.hasOwn(dimensions, dimension)) throw new DomainError('ไม่พบประเภทรายงาน', 404);
    const groups = new Map<string, number[]>();
    for (const plan of plans) { const key = dimensions[dimension](plan); const ids = groups.get(key) ?? []; ids.push(plan.id); groups.set(key, ids); }
    return [...groups.entries()].sort(([a], [b]) => a.localeCompare(b, 'th', { numeric: true })).map(([key, planIds]) => ({ key, label: key, count: planIds.length, planIds }));
  }
}
