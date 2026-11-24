import assert from 'node:assert/strict';
import { after, before, test } from 'node:test';
import { once } from 'node:events';
import { mkdtempSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import path from 'node:path';
import { spawnSync } from 'node:child_process';
import type { Server } from 'node:http';
import ExcelJS from 'exceljs';
import type { ImportPreview, PlanAttachment } from '../types/operations';

// Set before loading app modules: no test can reset the user's disk database.
process.env.UPMS_DB_PATH = ':memory:';
process.env.UPMS_ADMIN_PASSWORD = '';
const { createApp } = require('../app') as typeof import('../app');
const { database, UrbanPlanDatabase } = require('../data/database') as typeof import('../data/database');
let server: Server;
let base: string;
const pdf = Buffer.from('%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\n%%EOF');

before(async () => {
  server = createApp().listen(0, '127.0.0.1');
  await once(server, 'listening');
  const address = server.address();
  assert.ok(address && typeof address !== 'string');
  base = `http://127.0.0.1:${address.port}/api`;
});
after(async () => {
  await new Promise<void>((resolve, reject) => server.close(error => error ? reject(error) : resolve()));
  database.close();
});

async function json<T = Record<string, unknown>>(route: string, method = 'GET', body?: unknown, expected = 200): Promise<T> {
  const response = await fetch(base + route, { method, headers: { 'Content-Type': 'application/json' }, body: body === undefined ? undefined : JSON.stringify(body) });
  assert.equal(response.status, expected, `${method} ${route}: ${response.status}`);
  return response.json() as Promise<T>;
}
async function upload<T>(route: string, name: string, content: Buffer, status = 200): Promise<T> {
  const form = new FormData();
  form.set('file', new Blob([Uint8Array.from(content)]), name);
  const response = await fetch(base + route, { method: 'POST', body: form });
  assert.equal(response.status, status, `${name} -> ${route}: ${response.status}`);
  return response.json() as Promise<T>;
}
async function workbook(rows: unknown[][]): Promise<Buffer> {
  const book = new ExcelJS.Workbook();
  const sheet = book.addWorksheet('ผังเมือง');
  rows.forEach(row => sheet.addRow(row));
  return Buffer.from(await book.xlsx.writeBuffer());
}

test('SQLite preserves plans, counters, notes, directories, steps, history and file bytes in another process', () => {
  const directory = mkdtempSync(path.join(tmpdir(), 'upms-sqlite-'));
  const filename = path.join(directory, 'verification.sqlite');
  const disk = new UrbanPlanDatabase(filename);
  try {
    const created = disk.createPlan({ name: 'ข้อมูลถาวร', provinceId: 24 });
    disk.updatePlan(created.id, { currentStep: 3, health: 'slight_delay' });
    disk.addNote(created.id, 'ผู้บันทึก', 'หมายเหตุที่คงอยู่หลังรีสตาร์ต');
    disk.updateStep(1, 'สำรวจที่บันทึกไว้', 15);
    disk.saveDirectory('reasons', { name: 'เหตุผลเพิ่มเติม', detail: 'บันทึกถาวร', active: true });
    const file = disk.addAttachment(created.id, 'เอกสาร.pdf', 'application/pdf', pdf);
    const removed = disk.createPlan({ name: 'รายการที่จะลบ', provinceId: 24 });
    disk.deletePlan(removed.id);
    disk.close();
    const child = spawnSync(process.execPath, ['-e', `
      const assert = require('node:assert/strict');
      const { UrbanPlanDatabase, database } = require(process.argv[1]);
      const disk = new UrbanPlanDatabase(process.argv[2]);
      const id = Number(process.argv[3]);
      assert.equal(disk.getPlanById(id).plan.currentStep, 3);
      assert.equal(disk.getPlanById(id).notes[0].content, 'หมายเหตุที่คงอยู่หลังรีสตาร์ต');
      assert.equal(disk.getSteps()[1].targetWeeks, 15);
      assert.ok(disk.getDirectory('reasons').some(item => item.name === 'เหตุผลเพิ่มเติม'));
      assert.equal(disk.getHistory(id)[0].before.currentStep, 1);
      assert.equal(disk.getHistory(id)[0].after.currentStep, 3);
      assert.equal(disk.listAttachments(id)[0].name, 'เอกสาร.pdf');
      assert.equal(Buffer.from(disk.getAttachment(id, process.argv[4]).content).toString(), process.argv[5]);
      assert.equal(disk.createPlan({ name: 'หลังรีสตาร์ต', provinceId: 24 }).id, id + 2);
      disk.close(); database.close();
    `, require.resolve('../data/database'), filename, String(created.id), file.id, pdf.toString()], { windowsHide: true, encoding: 'utf8' });
    assert.equal(child.status, 0, child.stderr);
  } finally {
    // directory is the exact mkdtemp result under the operating system temp root.
    assert.equal(path.dirname(path.resolve(directory)), path.resolve(tmpdir()));
    rmSync(directory, { recursive: true, force: true });
  }
});

test('failed transactions restore memory, SQL records and ID counters', () => {
  const previous = database.listPlans({}).length;
  let abortedId = 0;
  assert.throws(() => database.transaction(() => {
    abortedId = database.createPlan({ name: 'ห้ามบันทึก', provinceId: 24 }).id;
    database.addNote(abortedId, 'ผู้บันทึก', 'ห้ามบันทึกหมายเหตุ');
    throw new Error('transaction cancelled');
  }), /cancelled/);
  assert.equal(database.listPlans({}).length, previous);
  assert.equal(database.getPlanById(abortedId), null);
  const next = database.createPlan({ name: 'หลังย้อนรายการ', provinceId: 24 });
  assert.equal(next.id, abortedId);
  database.deletePlan(next.id);
});

test('two database instances reject stale writes instead of overwriting committed changes', () => {
  const directory = mkdtempSync(path.join(tmpdir(), 'upms-conflict-'));
  const filename = path.join(directory, 'verification.sqlite');
  const first = new UrbanPlanDatabase(filename);
  const second = new UrbanPlanDatabase(filename);
  try {
    const saved = first.createPlan({ name: 'รายการจากโปรเซสแรก', provinceId: 24 });
    assert.throws(() => second.createPlan({ name: 'รายการจากข้อมูลเก่า', provinceId: 24 }), /อีกโปรเซส/);
    assert.equal(second.getPlanById(saved.id)!.plan.name, saved.name);
    const retry = second.createPlan({ name: 'รายการหลังโหลดข้อมูลล่าสุด', provinceId: 24 });
    assert.equal(retry.id, saved.id + 1);
  } finally {
    first.close(); second.close();
    assert.equal(path.dirname(path.resolve(directory)), path.resolve(tmpdir()));
    rmSync(directory, { recursive: true, force: true });
  }
});

test('XLSX template can be previewed without writing, committed once, and exported as real Excel', async () => {
  const before = database.listPlans({}).length;
  const template = await fetch(base + '/imports/template.xlsx');
  assert.equal(template.status, 200);
  assert.match(template.headers.get('content-type')!, /spreadsheetml/);
  const preview = await upload<ImportPreview>('/imports/preview', 'แม่แบบ.xlsx', Buffer.from(await template.arrayBuffer()));
  assert.equal(preview.validRows, 1);
  assert.equal(preview.issues.length, 0);
  assert.equal(database.listPlans({}).length, before);
  const committed = await json<{ imported: number; ids: number[] }>(`/imports/${preview.token}/commit`, 'POST', undefined, 201);
  assert.equal(committed.imported, 1);
  assert.equal(database.listPlans({}).length, before + 1);
  await json(`/imports/${preview.token}/commit`, 'POST', undefined, 410);
  const response = await fetch(base + '/exports/plans.xlsx');
  assert.equal(response.status, 200);
  const book = new ExcelJS.Workbook();
  await book.xlsx.load(await response.arrayBuffer());
  assert.equal(book.worksheets[0].rowCount, before + 2);
  assert.ok(book.worksheets[0].getRows(2, before + 1)?.some(row => row.getCell(2).text === 'ผังเมืองใหม่'));
  assert.ok(database.getLogs().some(entry => entry.action === 'IMPORT'));
});

test('CSV supports Thai names and legacy headers; any bad row prevents the entire commit', async () => {
  const before = database.listPlans({}).length;
  const preview = await upload<ImportPreview>('/imports/preview', 'ผังเมือง.csv', Buffer.from('\uFEFFcp_name,cp_province,budgetYear,cp_eec\r\n"ชุมชน, ริมน้ำ",24,2569,1\r\nจังหวัดผิด,999,2569,0'));
  assert.equal(preview.validRows, 1);
  assert.ok(preview.issues.some(issue => issue.row === 3 && issue.field === 'provinceId'));
  await json(`/imports/${preview.token}/commit`, 'POST', undefined, 422);
  assert.equal(database.listPlans({}).length, before);
  const valid = await upload<ImportPreview>('/imports/preview', 'ผังเมือง.csv', Buffer.from('ชื่อผังเมือง,จังหวัด,ปีงบประมาณ,EEC\nชุมชนริมน้ำ,เชียงใหม่,2569,ใช่'));
  assert.equal(valid.validRows, 1);
  const result = await json<{ ids: number[] }>(`/imports/${valid.token}/commit`, 'POST', undefined, 201);
  assert.equal(database.getPlanById(result.ids[0])!.plan.isEec, true);
});

test('duplicate rows and concurrent changes are rejected without partial imports', async () => {
  const duplicate = await upload<ImportPreview>('/imports/preview', 'duplicate.csv', Buffer.from('name,provinceId\nซ้ำในไฟล์,24\nซ้ำในไฟล์,24'));
  assert.ok(duplicate.issues.some(issue => issue.row === 3));
  await json(`/imports/${duplicate.token}/commit`, 'POST', undefined, 422);
  const preview = await upload<ImportPreview>('/imports/preview', 'conflict.csv', Buffer.from('name,provinceId\nข้อมูลแทรกระหว่างตรวจ,24'));
  database.createPlan({ name: 'ข้อมูลแทรกระหว่างตรวจ', provinceId: 24 });
  await json(`/imports/${preview.token}/commit`, 'POST', undefined, 409);
  await json('/imports/unknown/commit', 'POST', undefined, 410);
});

test('invalid sheets, formulas, files, columns, row limits and oversized uploads produce JSON errors', async () => {
  await upload('/imports/preview', 'wrong.xlsx', Buffer.from('not a zip file'), 400);
  await upload('/imports/preview', 'wrong.xls', Buffer.from('old format'), 400);
  const formula = await upload<ImportPreview>('/imports/preview', 'formula.xlsx', await workbook([['name', 'provinceId'], [{ formula: '1+1' }, 24]]));
  assert.equal(formula.validRows, 0);
  assert.equal(formula.issues[0].row, 2);
  await json(`/imports/${formula.token}/commit`, 'POST', undefined, 422);
  const columns = await upload<ImportPreview>('/imports/preview', 'columns.csv', Buffer.from('name,provinceId,constructor\nไม่มีฟิลด์นี้,24,invalid'));
  assert.ok(columns.issues.some(issue => issue.row === 1));
  const tooMany = Buffer.from('name,provinceId\n' + Array.from({ length: 1001 }, (_, index) => `ผัง${index},24`).join('\n'));
  await upload('/imports/preview', 'rows.csv', tooMany, 400);
  await upload('/imports/preview', 'large.csv', Buffer.alloc(5 * 1024 * 1024 + 1, 65), 413);
  await json('/plans', 'POST', { name: 'x'.repeat(251), provinceId: 24 }, 400);
});

test('history preserves before/after values and excludes no-op edits', async () => {
  const plan = database.createPlan({ name: 'ประวัติแก้ไข', provinceId: 24 });
  await json(`/plans/${plan.id}`, 'PATCH', { currentStep: 4, health: 'moderate_delay', delayDays: 36 });
  const history = await json<Array<{ before: { currentStep: number }; after: { currentStep: number }; changedFields: string[] }>>(`/plans/${plan.id}/history`);
  assert.equal(history.length, 1);
  assert.equal(history[0].before.currentStep, 1);
  assert.equal(history[0].after.currentStep, 4);
  assert.deepEqual(history[0].changedFields, ['currentStep', 'health', 'delayDays']);
  await json(`/plans/${plan.id}`, 'PATCH', { currentStep: 4 });
  assert.equal(database.getHistory(plan.id).length, 1);
  await json('/plans/999999/history', 'GET', undefined, 404);
});

test('attachments validate signatures, preserve Unicode filenames and bytes, and cascade on plan deletion', async () => {
  const plan = database.createPlan({ name: 'เอกสารประกอบ', provinceId: 24 });
  await upload(`/plans/${plan.id}/attachments`, 'payload.php', Buffer.from('<?php echo 1;'), 400);
  await upload(`/plans/${plan.id}/attachments`, 'payload.png', pdf, 400);
  const file = await upload<PlanAttachment>(`/plans/${plan.id}/attachments`, 'แผนที่.pdf', pdf, 201);
  assert.equal(file.name, 'แผนที่.pdf');
  const download = await fetch(base + `/plans/${plan.id}/attachments/${file.id}`);
  assert.equal(download.headers.get('x-content-type-options'), 'nosniff');
  assert.match(download.headers.get('content-disposition')!, /^attachment;/);
  assert.deepEqual(Buffer.from(await download.arrayBuffer()), pdf);
  const other = database.createPlan({ name: 'อีกผังเมือง', provinceId: 24 });
  await json(`/plans/${other.id}/attachments/${file.id}`, 'GET', undefined, 404);
  await json(`/plans/${plan.id}/attachments/${file.id}`, 'DELETE');
  await json(`/plans/${plan.id}/attachments/${file.id}`, 'DELETE', undefined, 404);
  const retained = await upload<PlanAttachment>(`/plans/${plan.id}/attachments`, 'map.pdf', pdf, 201);
  await json(`/plans/${plan.id}`, 'DELETE');
  assert.equal(database.getAttachment(plan.id, retained.id), undefined);
  await json(`/plans/${plan.id}/attachments`, 'GET', undefined, 404);
  await upload('/plans/999999/attachments', 'map.pdf', pdf, 404);
});

test('reset removes persisted history and attachments together with plan changes', async () => {
  const file = database.addAttachment(1, 'reset.pdf', 'application/pdf', pdf);
  database.updatePlan(1, { currentStep: 7 });
  await json('/database/reset', 'POST');
  assert.equal(database.getHistory(1).length, 0);
  assert.equal(database.getAttachment(1, file.id), undefined);
  assert.equal((await json<{ mode: string }>('/health')).mode, 'sqlite');
});
