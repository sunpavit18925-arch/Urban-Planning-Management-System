import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { once } from 'node:events';

// Use a separate in-memory database and ephemeral port, preserving the
// running application's data. Compile the server before invoking this file.
process.env.UPMS_DB_PATH = ':memory:';
const require = createRequire(new URL('../server/package.json', import.meta.url));
const express = require('express');
const router = require('./dist/routes/api.js').default;
const app = express();
app.use(express.json());
app.use('/api', router);
const server = app.listen(0, '127.0.0.1');
await once(server, 'listening');
const base = `http://127.0.0.1:${server.address().port}/api`;
const request = async (route, method = 'GET', body, status = 200) => {
  const response = await fetch(base + route, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: body === undefined ? undefined : JSON.stringify(body),
  });
  assert.equal(response.status, status, `${method} ${route}`);
  return response.json();
};
try {
  assert.equal((await request('/health')).status, 'ok');
  const meta = await request('/meta');
  assert.equal(meta.provinces.length, 77);
  assert.equal(new Set(meta.provinces.map(p => p.id)).size, 77);
  const regions = await request('/regions');
  assert.equal(regions.length, 5);
  assert.equal(regions.flatMap(r => r.provinces).length, 77);
  const keths = await request('/keths');
  assert.deepEqual(keths.map(k => k.kethId), Array.from({ length: 18 }, (_, i) => i + 1));
  assert.equal(keths.flatMap(k => k.provinces).length, 76);
  assert.deepEqual(keths[0].provinces.map(p => p.name), ['ชัยนาท', 'พระนครศรีอยุธยา', 'ลพบุรี', 'สระบุรี', 'สิงห์บุรี', 'อ่างทอง']);
  assert.equal(meta.provinces.find(p => p.name === 'กรุงเทพมหานคร').kethId, 0);
  const initial = await request('/plans');
  const statusReport = await request('/reports/status');
  assert.equal(statusReport.total, initial.total);
  for (const [name, entries] of Object.entries(statusReport.panels)) {
    const expected = name === 'newSteps' ? initial.items.filter(p => p.stage === 'เปิดใหม่').length : name === 'improvementSteps' ? initial.items.filter(p => p.stage === 'ปรับปรุง').length : initial.total;
    assert.equal(entries.reduce((sum, entry) => sum + entry.count, 0), expected);
    for (const entry of entries) assert.equal(entry.count, entry.planIds.length);
  }
  for (const column of statusReport.matrix) {
    assert.equal(column.count, column.planIds.length);
    assert.equal(column.rows.reduce((sum, row) => sum + row.count, 0), column.count);
  }
  assert.equal(new Set(initial.items.map(p => p.provinceId)).size, 77);
  assert.equal(new Set(initial.items.map(p => p.code)).size, initial.total);
  assert.ok(initial.items.every(p => !/demo|mock|จำลอง|ตัวอย่าง/i.test(`${p.code} ${p.name}`)));
  const summary = await request('/dashboard/summary');
  assert.equal(initial.total, summary.totalPlans);
  const tree = await request('/reports/tree');
  assert.equal(tree.counts.cityplan, initial.total);
  assert.equal(tree.counts.all, tree.counts.cityplan + tree.counts.province + tree.counts.regionalEec);
  assert.equal(tree.counts.eva, tree.counts.eva5 + tree.counts.eva10);
  for (const group of tree.groups) {
    const detail = await request(`/reports/tree/${group.key}`);
    assert.equal(detail.total, tree.counts[group.key], group.key);
    assert.equal(detail.items.length, detail.total, group.key);
    assert.equal(new Set(detail.items.map(p => p.id)).size, detail.total, group.key);
  }
  for (const key of ['bbfY', 'expY', 'eecY']) assert.equal(tree.counts[key], tree.counts[key + '-1'] + tree.counts[key + '-2']);
  assert.ok((await request('/reports/tree/new')).items.every(p => p.stage === 'เปิดใหม่' && !p.isEec));
  assert.ok((await request('/reports/tree/eva5')).items.every(p => p.stage === 'ประเมิน 5 ปี' && !p.isEec));
  assert.ok((await request('/reports/tree/eecY-2')).items.every(p => p.isEec && p.owner === 'อปท.'));
  await request('/reports/tree/unknown', 'GET', undefined, 404);
  await request('/reports/tree/__proto__', 'GET', undefined, 404);
  assert.equal(summary.byRegion.reduce((sum, r) => sum + r.count, 0), summary.totalPlans);
  const filters = [
    ['region=1', p => p.regionId === 1],
    ['keth=15', p => p.kethId === 15],
    ['provinceId=24', p => p.provinceId === 24],
    ['health=on_track', p => p.health === 'on_track'],
    ['category=m35', p => p.category === 'm35'],
    ['category=evaluation', p => p.category === 'evaluation'],
    ['search=UPMS-001', p => p.code === 'UPMS-001'],
  ];
  for (const [query, predicate] of filters) {
    const result = await request(`/plans?${query}`);
    assert.ok(result.items.length > 0 && result.items.every(predicate), query);
  }
  await request('/plans', 'POST', { name: 'Invalid province', provinceId: 999 }, 400);
  await request('/plans', 'POST', { name: 'Invalid step', provinceId: 22, currentStep: 99 }, 400);
  await request('/plans', 'POST', { name: ' ', provinceId: 22 }, 400);
  const created = await request('/plans', 'POST', { name: 'API verification', provinceId: 22 }, 201);
  assert.equal((await request('/reports/tree')).counts.new, tree.counts.new + 1);
  assert.equal((await request('/reports/tree/new')).total, tree.counts.new + 1);
  assert.equal(created.provinceId, 22);
  const detail = await request(`/plans/${created.id}`);
  assert.equal(detail.plan.name, created.name);
  const updated = await request(`/plans/${created.id}`, 'PATCH', { currentStep: 3, health: 'slight_delay', delayDays: 12, trackingState: 'act' });
  assert.equal(updated.currentStep, 3);
  assert.equal(updated.health, 'slight_delay');
  assert.equal(updated.trackingState, 'act');
  const matrixAfter = (await request('/reports/status')).matrix.find(column => column.key === 'new');
  assert.equal(matrixAfter.rows.find(row => row.key === 'act').count, statusReport.matrix.find(column => column.key === 'new').rows.find(row => row.key === 'act').count + 1);
  await request(`/plans/${created.id}`, 'PATCH', { trackingState: 'invalid' }, 400);
  await request(`/plans/${created.id}`, 'PATCH', { id: 123456 }, 400);
  await request(`/plans/${created.id}`, 'PATCH', { health: 'invalid' }, 400);
  await request(`/plans/${created.id}`, 'PATCH', { currentStep: -1 }, 400);
  const note = await request(`/plans/${created.id}/notes`, 'POST', { author: 'API check', content: 'Verification note' }, 201);
  assert.equal(note.planId, created.id);
  assert.equal((await request(`/plans/${created.id}`)).notes.length, 1);
  assert.ok((await request('/logs')).some(log => log.target === created.code && log.action === 'NOTE'));
  await request(`/plans/${created.id}`, 'DELETE');
  await request(`/plans/${created.id}`, 'GET', undefined, 404);
  assert.equal((await request('/plans')).total, initial.total);
  const notes = await request('/notes');
  assert.ok(notes.length > 0 && notes.every(note => note.planName && note.planCode));
  assert.ok(!/demo|mock|จำลอง|ทดสอบ|portfolio/i.test(JSON.stringify(await request('/logs'))));
  for (const kind of ['offices', 'evaluationSteps', 'noteCategories', 'reasons', 'administrators']) {
    const items = await request(`/directories/${kind}`);
    assert.ok(items.length > 0, kind);
    if (kind === 'offices') assert.equal(items.length, 77);
    await request(`/directories/${kind}`, 'POST', { name: ' ', detail: '', active: true }, 400);
    await request(`/directories/${kind}`, 'POST', { name: 'invalid', detail: '', active: 'yes' }, 400);
    const createdItem = await request(`/directories/${kind}`, 'POST', { name: 'ตรวจสอบรายการ', detail: 'รายละเอียด', active: true }, 201);
    const updatedItem = await request(`/directories/${kind}/${createdItem.id}`, 'PATCH', { name: 'แก้ไขรายการ', detail: 'ปรับปรุง', active: false });
    assert.equal(updatedItem.active, false);
    assert.equal((await request(`/directories/${kind}`)).find(item => item.id === createdItem.id).name, 'แก้ไขรายการ');
    await request(`/directories/${kind}/${createdItem.id}`, 'DELETE');
    await request(`/directories/${kind}/${createdItem.id}`, 'DELETE', undefined, 404);
    assert.equal((await request(`/directories/${kind}`)).length, items.length);
  }
  for (const method of ['GET', 'POST', 'PATCH', 'DELETE']) {
    await request(`/directories/unknown${['PATCH', 'DELETE'].includes(method) ? '/1' : ''}`, method, method === 'POST' || method === 'PATCH' ? {} : undefined, 404);
  }
  const step = meta.steps[1];
  await request(`/steps/${step.step}`, 'PATCH', { title: ' ', targetWeeks: 5 }, 400);
  await request(`/steps/${step.step}`, 'PATCH', { title: 'สำรวจพื้นที่', targetWeeks: -1 }, 400);
  await request('/steps/999', 'PATCH', { title: 'สำรวจพื้นที่', targetWeeks: 5 }, 404);
  await request(`/steps/${step.step}`, 'PATCH', { title: 'สำรวจพื้นที่', targetWeeks: 12 });
  assert.equal((await request('/meta')).steps[1].targetWeeks, 12);
  await request('/mock/reset', 'POST');
  assert.equal((await request('/plans')).total, initial.total);
  assert.deepEqual((await request('/meta')).steps, meta.steps);
  assert.equal((await request('/directories/offices')).length, 77);
  console.log('PASS: 77 provinces / 5 regions / 18 inspectorates, seeded plans, summary, filters, CRUD, notes, directories, steps, logs, reset and invalid requests.');
} finally {
  await new Promise((resolve, reject) => server.close(error => error ? reject(error) : resolve()));
}
