import assert from 'node:assert/strict';
import { after, before, test } from 'node:test';
import { once } from 'node:events';
import { mkdtempSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import path from 'node:path';
import type { Server } from 'node:http';
import type { ModuleDefinition, ModuleRecord, MaintenanceReport, ScheduleRow } from '../types/modules';
import { addDays, buddhistDate, civilDate, inclusiveDeadline, workflowStatus } from '../services/calendar';

process.env.UPMS_DB_PATH = ':memory:';
process.env.UPMS_ADMIN_PASSWORD = '';
const { createApp } = require('../app') as typeof import('../app');
const { database, UrbanPlanDatabase } = require('../data/database') as typeof import('../data/database');
const { ModuleService } = require('../services/modules') as typeof import('../services/modules');
const { modules } = require('../routes/modules') as typeof import('../routes/modules');
let server: Server; let base: string;
before(async () => { server = createApp().listen(0, '127.0.0.1'); await once(server, 'listening'); const address = server.address(); assert.ok(address && typeof address !== 'string'); base = `http://127.0.0.1:${address.port}/api`; });
after(async () => { await new Promise<void>(resolve => server.close(() => resolve())); database.close(); });
async function request<T = Record<string, unknown>>(route: string, method = 'GET', body?: unknown, status = 200): Promise<T> {
  const response = await fetch(base + route, { method, headers: { 'Content-Type': 'application/json' }, body: body === undefined ? undefined : JSON.stringify(body) });
  const data = await response.json(); assert.equal(response.status, status, `${method} ${route}: ${JSON.stringify(data)}`); return data as T;
}
const create = (kind: string, data: Record<string, unknown>) => request<ModuleRecord>(`/modules/${kind}`, 'POST', { data }, 201);
const update = (kind: string, record: ModuleRecord, data: Record<string, unknown>, status = 200) => request<ModuleRecord>(`/modules/${kind}/${record.id}`, 'PATCH', { data, version: record.version }, status);
const remove = (kind: string, record: ModuleRecord, status = 200) => request(`/modules/${kind}/${record.id}`, 'DELETE', { version: record.version }, status);

test('module catalog exposes all twelve legacy business modules and rejects private/unknown collections', async () => {
  const definitions = await request<ModuleDefinition[]>('/modules/meta');
  assert.equal(definitions.length, 12);
  for (const definition of definitions) { assert.ok(definition.sources.length); assert.ok(definition.fields.some(field => field.key === 'name')); assert.ok(Array.isArray(await request(`/modules/${definition.key}`))); }
  await request('/modules/_accounts', 'GET', undefined, 404);
  await request('/modules/__proto__', 'GET', undefined, 404);
  const steps = await request<ModuleRecord[]>('/modules/workflowSteps');
  assert.equal(steps.length, 792);
  assert.ok(steps.some(step => step.data.profile === 'general' && step.data.step === 5 && step.data.subStep === 2 && step.data.firstThreshold === 90));
});

test('civil dates port Buddhist years, leap years and inclusive PHP deadline boundaries', () => {
  assert.equal(civilDate('2567-02-29').toISOString().slice(0, 10), '2024-02-29');
  assert.equal(buddhistDate('2026-11-24'), '2569-11-24');
  assert.throws(() => civilDate('2568-02-29'));
  assert.throws(() => civilDate('2026-13-01'));
  assert.equal(inclusiveDeadline('2569-01-01', 30), '2026-01-30');
  for (const [delay, health] of [[0, 'on_track'], [1, 'slight_delay'], [30, 'slight_delay'], [31, 'moderate_delay'], [60, 'moderate_delay'], [61, 'severe_delay']] as const) {
    const status = workflowStatus('2569-01-01', addDays('2026-01-30', delay), 30, 60);
    assert.equal(status.health, health); assert.equal(status.delayDays, delay);
  }
  assert.equal(workflowStatus('', '', 30, 60).health, 'unscheduled');
  assert.equal(workflowStatus('2027-01-01', '', 30, 60, '2026-11-24').health, 'on_track');
  assert.throws(() => workflowStatus('2026-02-01', '2026-01-01', 30, 60));
});

test('procurement lifecycle preserves company/project/equipment links, inspections and optimistic versions', async () => {
  const company = await create('companies', { name: 'บริษัททดสอบ', phone: '02 000 0000' });
  let project = await create('projects', { name: 'โครงการระบบสารสนเทศ', companyId: company.id, budget: 250000, price: 200000, year: 2569 });
  const equipment = await create('equipment', { name: 'เครื่องแม่ข่าย', projectId: project.id, assetNumber: 'SERVER-001', price: 100000, purchasedYear: 2569, warrantyYears: 2, replacementYear: 2573, maintenanceAmount: 10000 });
  project = await update('projects', project, { equipmentIds: [equipment.id] });
  const inspection = await create('inspections', { name: 'ตรวจประจำปี', projectId: project.id, equipmentIds: [equipment.id], inspectedOn: '2569-11-24', result: 'normal', suggestion: 'ติดตามประจำปี' });
  const changed = await update('companies', company, { phone: '02 111 1111' });
  assert.equal(changed.version, 2);
  await update('companies', company, { phone: 'ข้อมูลเก่า' }, 409);
  await remove('companies', changed, 409);
  await remove('equipment', equipment, 409);
  await remove('inspections', inspection);
  project = await update('projects', project, { equipmentIds: [] });
  await remove('equipment', equipment); await remove('projects', project); await remove('companies', changed);
});

test('maintenance budgets distinguish purchase, warranty, maintenance and replacement without double charging', async () => {
  const equipment = await create('equipment', { name: 'เครื่องทดสอบงบประมาณ', price: 100000, purchasedYear: 2569, warrantyYears: 1, replacementYear: 2573, maintenanceAmount: 10000 });
  const plan = await create('maintenancePlans', { name: 'แผน 6 ปี', startYear: 2568, years: 6, equipmentIds: [equipment.id] });
  const report = await request<MaintenanceReport>(`/maintenance/${plan.id}`);
  assert.deepEqual(report.years, [2568, 2569, 2570, 2571, 2572, 2573]);
  assert.deepEqual(report.rows[0].cells.map(cell => cell.status), ['not_purchased', 'warranty', 'warranty', 'maintenance', 'maintenance', 'replacement']);
  assert.deepEqual(report.totals, [0, 0, 0, 10000, 10000, 100000]); assert.equal(report.total, 120000);
  await remove('equipment', equipment, 409); await remove('maintenancePlans', plan); await remove('equipment', equipment);
});

test('typed module validation rejects invalid relations, dates, coordinates, unknown fields and duplicate asset numbers', async () => {
  for (const [kind, data] of [
    ['companies', { name: '' }], ['projects', { name: 'x', companyId: 999999 }], ['equipment', { name: 'x', price: -1 }],
    ['equipment', { name: 'x', purchasedYear: 2569, replacementYear: 2568 }], ['maintenancePlans', { name: 'x', years: 0 }],
    ['inspections', { name: 'x', projectId: 0, inspectedOn: '2025-02-29' }], ['companies', { name: 'x', id: 999 }],
    ['workflowSteps', { name: 'x', thirdStep: 1, subStep: 0 }],
  ] as const) await request(`/modules/${kind}`, 'POST', { data }, 400);
  const one = await create('equipment', { name: 'ครุภัณฑ์ไม่ซ้ำ', assetNumber: 'UNIQUE-001' });
  await request('/modules/equipment', 'POST', { data: { name: 'ซ้ำ', assetNumber: 'UNIQUE-001' } }, 409);
  await remove('equipment', one);
});

test('amendment/evaluation cases support multiple jobs per plan and enforce correct workflow relations', async () => {
  const first = database.createPlan({ name: 'งานแก้ไขและประเมิน', provinceId: 24 });
  const second = database.createPlan({ name: 'อีกผังหนึ่ง', provinceId: 24 });
  const amendment = await create('workflowCases', { name: 'แก้ไขครั้งที่ 1', planId: first.id, profile: 'm35', start: '2026-01-01' });
  const evaluation = await create('workflowCases', { name: 'ประเมินรอบ 5 ปี', planId: first.id, profile: 'evaluation' });
  const definitions = await request<ModuleRecord[]>('/modules/workflowSteps');
  const definition = definitions.find(item => item.data.profile === 'm35' && item.data.step === 2 && item.data.subStep === 1 && item.data.thirdStep === 0)!;
  await request('/modules/progressEntries', 'POST', { data: { name: 'อ้างงานผิดผัง', planId: second.id, caseId: amendment.id, definitionId: definition.id } }, 400);
  await request('/modules/progressEntries', 'POST', { data: { name: 'อ้างประเภทผิด', planId: first.id, caseId: evaluation.id, definitionId: definition.id } }, 400);
  const entry = await create('progressEntries', { name: 'ผลดำเนินการ', planId: first.id, caseId: amendment.id, definitionId: definition.id, start: '2569-01-01', stop: '2569-01-28', makeCurrent: true, note: 'หมายเหตุพร้อมการเปลี่ยนขั้นตอน' });
  assert.equal(database.getPlanById(first.id)!.plan.currentStep, 2);
  const result = await request<{ entries: Array<{ status: { health: string; deadline: string } }>; cases: ModuleRecord[] }>(`/plans/${first.id}/workflow`);
  assert.equal(result.cases.length, 2); assert.equal(result.entries[0].status.deadline, '2026-01-28'); assert.equal(result.entries[0].status.health, 'on_track');
  await remove('workflowCases', amendment, 409);
  await remove('progressEntries', entry); await remove('workflowCases', amendment); await remove('workflowCases', evaluation);
  database.deletePlan(first.id); database.deletePlan(second.id);
});

test('automatic schedules use inclusive leaf dates, petition branches and immutable saved rows', async () => {
  const rows = modules.generateSchedule('general', '2569-01-01', false);
  assert.equal(rows.filter(row => !row.subStep).length, 8);
  assert.deepEqual([...new Set(rows.filter(row => row.step === 6 && row.subStep).map(row => row.subStep))], [4, 5]);
  const leaves = rows.filter(row => row.thirdStep > 0);
  assert.equal(leaves[0].start, '2026-01-01');
  for (let index = 1; index < leaves.length; index++) assert.equal(leaves[index].start, addDays(leaves[index - 1].end, 1));
  const withPetition = modules.generateSchedule('general', '2026-01-01', true);
  assert.equal(new Set(withPetition.filter(row => row.step === 6 && row.subStep).map(row => row.subStep)).size, 8);
  const plan = database.createPlan({ name: 'แผนที่บันทึก', provinceId: 24 });
  const saved = await create('planSchedules', { name: 'แผนเริ่มปี', planId: plan.id, start: '2026-01-01', profile: 'general' });
  const stored = await request<{ rows: ScheduleRow[] }>(`/schedules/${saved.id}`);
  assert.deepEqual(stored.rows, rows);
  database.deletePlan(plan.id);
  await request(`/modules/planSchedules/${saved.id}`, 'GET', undefined, 404);
});

test('province configuration updates existing plans, region/inspectorate lists and newly created plans', async () => {
  const assignments = await request<ModuleRecord[]>('/modules/provinceAssignments');
  const original = assignments.find(item => item.data.provinceId === 24)!;
  const saved = await update('provinceAssignments', original, { regionId: 2, kethId: 12, affiliationId: 3 });
  assert.ok(database.listPlans({ provinceId: 24 }).every(plan => plan.regionId === 2 && plan.kethId === 12));
  assert.ok(database.getRegions().find(region => region.id === 2)!.provinces.some(province => province.id === 24));
  const created = database.createPlan({ name: 'ใช้จังหวัดที่แก้ไข', provinceId: 24 }); assert.equal(created.kethId, 12);
  await remove('provinceAssignments', saved, 409);
  await update('provinceAssignments', saved, original.data); database.deletePlan(created.id);
});

test('committee reports use stored assignments rather than modulo arithmetic', async () => {
  const assignments = await request<ModuleRecord[]>('/modules/committees');
  const first = assignments.find(item => item.data.kethId === 1)!;
  const updated = await update('committees', first, { committee: 7, orderNumber: 'คำสั่ง 1/2569' });
  const report = await request<{ plans: Array<{ kethId: number }>; assignments: ModuleRecord[] }>('/reports/committee/7');
  assert.ok(report.assignments.some(item => item.id === first.id));
  assert.ok(report.plans.every(plan => report.assignments.some(item => item.data.kethId === plan.kethId)));
  await update('committees', updated, first.data);
});

test('plan metadata, categorized notes, soft deletion and pagination work through REST endpoints', async () => {
  const plan = await request<{ id: number }>('/plans', 'POST', { name: 'ทะเบียนครบ', provinceId: 24, scope: 'เฉพาะพื้นที่', planType: 'ผังเมืองรวมจังหวัด', externalCode: 'OLD-01', publishedOn: '2569-01-01' }, 201);
  await request(`/plans/${plan.id}`, 'PATCH', { owner: 'อปท.', operator: 'อื่นๆ', budgetYear: '2570', petition: true, thirdStep: 2 });
  assert.equal(database.getPlanById(plan.id)!.plan.externalCode, 'OLD-01');
  await request(`/plans/${plan.id}`, 'PATCH', { nextStepPending: true });
  assert.equal(database.getPlanById(plan.id)!.plan.nextStepPending, true);
  await request(`/plans/${plan.id}`, 'PATCH', { currentStep: 0 });
  assert.equal(database.getPlanById(plan.id)!.plan.subStep, 0); assert.equal(database.getPlanById(plan.id)!.plan.thirdStep, 0);
  await request(`/plans/${plan.id}`, 'PATCH', { considerId: 999999 }, 400);
  const category = database.getDirectory('noteCategories')[0];
  const note = await request<{ id: number }>(`/plans/${plan.id}/notes`, 'POST', { content: 'หมายเหตุมีหมวดหมู่', categoryId: category.id, step: 2, subStep: 3 }, 201);
  await request(`/notes/${note.id}`, 'PATCH', { content: 'แก้ไขข้อความ', step: 3 });
  assert.equal(database.getPlanById(plan.id)!.notes[0].step, 3);
  await request(`/directories/noteCategories/${category.id}`, 'DELETE', undefined, 409);
  await request(`/notes/${note.id}`, 'DELETE'); assert.equal(database.getPlanById(plan.id)!.notes.length, 0);
  const page = await request<{ total: number; items: unknown[]; pageCount: number }>('/plans?page=1&pageSize=10'); assert.equal(page.items.length, 10); assert.equal(page.pageCount, Math.ceil(page.total / 10));
  await request('/plans?page=-1', 'GET', undefined, 400);
  const exact = await request<{ items: Array<{ owner: string }> }>('/plans?owner=อปท.&planType=ผังเมืองรวมจังหวัด'); assert.ok(exact.items.every(plan => plan.owner === 'อปท.'));
  await request(`/plans/${plan.id}`, 'DELETE');
});

test('snapshots preserve captured values and every report dimension partitions exactly once', async () => {
  const snapshot = await request<{ id: number }>('/snapshots', 'POST', { name: 'ข้อมูลก่อนเปลี่ยน', asOf: '2569-11-24' }, 201);
  const previous = database.getPlanById(1)!.plan.name;
  database.updatePlan(1, { name: 'เปลี่ยนหลังบันทึก' });
  const stored = await request<Array<{ id: number; name: string }>>(`/snapshots/${snapshot.id}/plans`); assert.equal(stored.find(plan => plan.id === 1)!.name, previous);
  for (const dimension of ['budget', 'type', 'scope', 'stage', 'effective', 'owner', 'operator', 'province', 'region', 'step', 'health', 'eec', 'category']) {
    const report = await request<{ total: number; groups: Array<{ count: number; planIds: number[] }> }>(`/reports/catalog?dimension=${dimension}&snapshot=${snapshot.id}`);
    assert.equal(report.groups.reduce((sum, group) => sum + group.count, 0), report.total);
    assert.equal(new Set(report.groups.flatMap(group => group.planIds)).size, report.total);
  }
  await request('/reports/catalog?dimension=constructor', 'GET', undefined, 404);
  const list = await request<ModuleRecord[]>('/snapshots'); assert.ok(!('snapshotJson' in list[0].data));
  database.updatePlan(1, { name: previous });
});

test('module records, revisions and counters survive closing and reopening SQLite', () => {
  const directory = mkdtempSync(path.join(tmpdir(), 'upms-module-test-'));
  const filename = path.join(directory, 'data.sqlite');
  let disk = new UrbanPlanDatabase(filename);
  try {
    let service = new ModuleService(disk);
    const company = service.save('companies', { name: 'บริษัทถาวร' });
    disk.close(); disk = new UrbanPlanDatabase(filename); service = new ModuleService(disk);
    assert.equal(service.get('companies', company.id).data.name, 'บริษัทถาวร');
    assert.equal(service.get('companies', company.id).version, 1);
    assert.equal(service.save('companies', { name: 'บริษัทถัดไป' }).id, company.id + 1);
    assert.equal(service.list('workflowSteps').length, 792);
  } finally { disk.close(); assert.equal(path.dirname(path.resolve(directory)), path.resolve(tmpdir())); rmSync(directory, { recursive: true, force: true }); }
});
