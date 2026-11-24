import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { once } from 'node:events';
import { spawn } from 'node:child_process';
import { existsSync } from 'node:fs';
import { mkdir, mkdtemp, rm, writeFile } from 'node:fs/promises';
import path from 'node:path';
import os from 'node:os';
import { fileURLToPath } from 'node:url';
import { createGeographyReference, createTreeReference } from './geography-reference.mjs';

// Own server, database, and browser profile: the user's running app is untouched.
const root = fileURLToPath(new URL('../', import.meta.url));
process.env.UPMS_DB_PATH = ':memory:';
process.env.UPMS_ADMIN_PASSWORD = '';
const require = createRequire(new URL('../server/package.json', import.meta.url));
const express = require('express');
const app = express();
app.use(express.json());
const auth = new (require('./dist/services/auth.js').AuthService)(require('./dist/data/database.js').database);
app.use('/api', auth.router);
app.use('/api', auth.middleware);
app.use('/api', require('./dist/routes/api.js').default);
const reference = createGeographyReference(root, require('./dist/data/database.js').database.listPlans({}));
const treeReference = createTreeReference(root, require('./dist/data/database.js').database.listPlans({}));
if (reference) {
  app.get('/__reference/geography', (_req, res) => res.type('html').send(reference));
  app.get('/__reference/jquery.js', (_req, res) => res.sendFile(path.resolve(root, '../DPT_UPMS2/bower_components/jquery/dist/jquery.min.js')));
  app.get('/__reference/adminlte.js', (_req, res) => res.sendFile(path.resolve(root, '../DPT_UPMS2/dist/js/adminlte.js')));
  app.get('/__reference/tree', (_req, res) => res.type('html').send(treeReference));
}
app.use(express.static(path.join(root, 'client/dist')));
const server = app.listen(0, '127.0.0.1');
await once(server, 'listening');
const base = `http://127.0.0.1:${server.address().port}`;
const executable = [process.env.CHROME_PATH,
  'C:/Program Files/Google/Chrome/Application/chrome.exe',
  'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
  '/usr/bin/google-chrome', '/usr/bin/chromium',
].find(candidate => candidate && existsSync(candidate));
if (!executable) { server.close(); throw new Error('Set CHROME_PATH to a Chrome or Edge executable.'); }
const profile = await mkdtemp(path.join(os.tmpdir(), 'upms-browser-'));
const browser = spawn(executable, ['--headless=new', '--no-sandbox', '--disable-gpu', '--disable-extensions', '--disable-background-networking', '--no-first-run', '--no-default-browser-check', '--remote-debugging-port=0', `--user-data-dir=${profile}`, 'about:blank'], { windowsHide: true, stdio: ['ignore', 'ignore', 'pipe'] });
let socket;
let sessionId;
let sequence = 0;
const pending = new Map();
const exceptions = [];
const failedRequests = [];
async function send(method, params = {}, session = sessionId) {
  const id = ++sequence;
  return new Promise((resolve, reject) => {
    const timer = setTimeout(() => { pending.delete(id); reject(new Error(`CDP timeout: ${method}`)); }, 15000);
    pending.set(id, { resolve, reject, timer });
    socket.send(JSON.stringify({ id, method, params, ...(session ? { sessionId: session } : {}) }));
  });
}
async function evaluate(expression) {
  const result = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
  if (result.exceptionDetails) throw new Error(result.exceptionDetails.text);
  return result.result.value;
}
async function until(expression, label = expression) {
  const end = Date.now() + 12000;
  while (Date.now() < end) {
    if (await evaluate(`Boolean(${expression})`)) return;
    await new Promise(resolve => setTimeout(resolve, 100));
  }
  throw new Error(`UI timeout: ${label}`);
}
const click = selector => evaluate(`document.querySelector(${JSON.stringify(selector)}).click()`);
const fill = (selector, value) => evaluate(`(() => {
  const element = document.querySelector(${JSON.stringify(selector)});
  Object.getOwnPropertyDescriptor(element instanceof HTMLTextAreaElement ? HTMLTextAreaElement.prototype : HTMLInputElement.prototype, 'value').set.call(element, ${JSON.stringify(value)});
  element.dispatchEvent(new Event('input', { bubbles: true }));
})()`);
async function navigate(tab) {
  await click(`a[data-nav-tab][href="#${tab}"]`);
  const ready = tab === 'reports' ? "document.querySelector('.php-tree-report .vmainbox')" : tab === 'dashboard' ? "document.querySelectorAll('[data-chart-ready=true]').length === 4" : tab === 'status' ? "document.querySelector('.current-matrix')" : "!document.querySelector('.legacy-tools .loading-box') && document.querySelector('.legacy-tools .panel')";
  await until(`location.hash === '#${tab}' && ${ready}`, tab);
}
try {
  const wsUrl = await new Promise((resolve, reject) => {
    let stderr = '';
    const timer = setTimeout(() => reject(new Error(`Chrome startup timeout: ${stderr.slice(-400)}`)), 20000);
    browser.once('error', error => { clearTimeout(timer); reject(error); });
    browser.once('exit', code => { clearTimeout(timer); reject(new Error(`Chrome exited: ${code}`)); });
    browser.stderr.on('data', chunk => { stderr += chunk; const match = stderr.match(/DevTools listening on (ws:\/\/\S+)/); if (match) { clearTimeout(timer); resolve(match[1]); } });
  });
  socket = new WebSocket(wsUrl);
  await once(socket, 'open');
  socket.addEventListener('message', event => {
    const message = JSON.parse(event.data);
    if (message.id && pending.has(message.id)) {
      const task = pending.get(message.id); pending.delete(message.id); clearTimeout(task.timer);
      message.error ? task.reject(new Error(message.error.message)) : task.resolve(message.result);
    }
    if (message.method === 'Runtime.exceptionThrown') exceptions.push(message.params.exceptionDetails);
    if (message.method === 'Network.responseReceived' && message.params.response.url.startsWith(base) && message.params.response.status >= 400) failedRequests.push(message.params.response.url);
  });
  const target = await send('Target.createTarget', { url: 'about:blank' }, null);
  sessionId = (await send('Target.attachToTarget', { targetId: target.targetId, flatten: true }, null)).sessionId;
  await send('Page.enable'); await send('Runtime.enable'); await send('Network.enable');
  await send('Network.setBlockedURLs', { urls: ['https://fonts.googleapis.com/*', 'https://fonts.gstatic.com/*'] });
  await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 1000, deviceScaleFactor: 1, mobile: false });
  await send('Page.navigate', { url: base });
  await until(`document.querySelectorAll('.province-link').length > 0`);
  await evaluate(`sessionStorage.setItem('upms-tree-guide', 'seen')`);
  if (reference) {
    const measure = `(() => {
      const selectors = { header: '.main-header', siteTitle: '.mainname', title: '#title_page h2', back: '#btn_backy', map: '.map-container', legend: '#table_detail_status', card: '.extra-sideshow-inner', cardTitle: '.extra-sideshow-inner > h3', regionLabel: '.park-name', cardSubtitle: '.extra-sideshow-inner > h4', province: '.extra-sideshow-inner .set-td', provinceLabel: '.set-td > text, .set-td > button', legendRow: '#table_detail_status tr', sidebar: '.main-sidebar', role: '.sidebar-menu > li.header' };
      return Object.fromEntries(Object.entries(selectors).map(([key, selector]) => {
        const element = document.querySelector(selector), css = getComputedStyle(element);
        let rect = element.getBoundingClientRect();
        if (key.endsWith('Label')) {
          const node = Array.from(element.childNodes).find(node => node.nodeType === Node.TEXT_NODE && node.textContent.trim());
          const range = document.createRange(); range.setStart(node, node.textContent.length - node.textContent.trimStart().length); range.setEnd(node, node.textContent.trimEnd().length);
          rect = range.getBoundingClientRect();
        }
        return [key, { x: rect.x, y: rect.y, width: rect.width, height: rect.height, fontFamily: css.fontFamily, fontSize: css.fontSize, fontWeight: css.fontWeight, lineHeight: css.lineHeight, color: css.color, background: css.backgroundColor, display: css.display }];
      }));
    })()`;
    await mkdir(path.join(root, '.tmp'), { recursive: true });
    await evaluate('document.fonts.ready');
    const actual = await evaluate(measure);
    await writeFile(path.join(root, '.tmp/design-actual.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png', clip: { x: 0, y: 0, width: 1440, height: 1000, scale: 1 } })).data, 'base64'));
    await send('Page.navigate', { url: base + '/__reference/geography' });
    await until(`document.querySelectorAll('.set-td').length === 77 && document.querySelector('.map-container img').complete`);
    await evaluate('document.fonts.ready');
    const original = await evaluate(measure);
    await writeFile(path.join(root, '.tmp/design-original.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png', clip: { x: 0, y: 0, width: 1440, height: 1000, scale: 1 } })).data, 'base64'));
    await writeFile(path.join(root, '.tmp/design-metrics.json'), JSON.stringify({ actual, original }, null, 2));
    for (const key of Object.keys(actual)) {
      for (const property of ['x', 'y', 'width', 'height']) {
        assert.ok(Math.abs(actual[key][property] - original[key][property]) <= 1, `DPT_UPMS2 ${key}.${property}: ${actual[key][property]} vs ${original[key][property]}`);
      }
      for (const property of ['fontSize', 'fontWeight', 'lineHeight', 'color', 'background', 'display']) {
        if (property === 'display' && key.endsWith('Label')) continue;
        assert.equal(actual[key][property], original[key][property], `DPT_UPMS2 ${key}.${property}`);
      }
    }
    console.log('PASS: desktop geography geometry, typography, colors and legend match the original PHP template (within 1px).');
    await send('Page.navigate', { url: base });
    await until(`document.querySelectorAll('.province-link').length === 77`);
    assert.equal(await evaluate(`Array.from(document.fonts).some(font => font.family === 'Kanit' && font.status === 'loaded')`), true, 'local Kanit loaded');
    await send('Emulation.setEmulatedMedia', { media: 'print' });
    assert.equal(await evaluate(`getComputedStyle(document.querySelector('.province-link')).display !== 'none' && getComputedStyle(document.querySelector('.dpt-status-count')).display !== 'none'`), true, 'print preserves province names and counters');
    await send('Emulation.setEmulatedMedia', { media: 'screen' });
    await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
    await until(`document.querySelector('.content-wrapper').getBoundingClientRect().x === 0`);
    await evaluate('document.fonts.ready');
    const actualMobile = await evaluate(measure);
    await writeFile(path.join(root, '.tmp/design-mobile-actual.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
    await send('Page.navigate', { url: base + '/__reference/geography' });
    await until(`document.querySelectorAll('.set-td').length === 77 && document.querySelector('.map-container img').complete`);
    await evaluate('document.fonts.ready');
    const originalMobile = await evaluate(measure);
    await writeFile(path.join(root, '.tmp/design-mobile-original.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
    await writeFile(path.join(root, '.tmp/design-mobile-metrics.json'), JSON.stringify({ actual: actualMobile, original: originalMobile }, null, 2));
    for (const key of Object.keys(actualMobile)) {
      for (const property of ['x', 'y', 'width', 'height']) {
        assert.ok(Math.abs(actualMobile[key][property] - originalMobile[key][property]) <= 1, `DPT_UPMS2 mobile ${key}.${property}: ${actualMobile[key][property]} vs ${originalMobile[key][property]}`);
      }
    }
    console.log('PASS: mobile geography geometry matches the original PHP template (within 1px).');
    await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 1000, deviceScaleFactor: 1, mobile: false });
    await until(`document.querySelector('.content-wrapper').getBoundingClientRect().x === 250`);
    await send('Page.navigate', { url: base });
    await until(`document.querySelectorAll('.province-link').length === 77`);
  }
  if (treeReference) {
    const measureTree = `(() => {
      const selectors = { heading: '#new-tree > .box-header', root: '#new-tree .vmainbox', province: '#new-tree .seq-l > .verbox', community: '#new-tree .seq-c > .verbox', eec: '#new-tree .seq-r > .verbox', tracking: '#new-tree .box-3' };
      return Object.fromEntries(Object.entries(selectors).map(([key, selector]) => {
        const element = document.querySelector(selector), rect = element.getBoundingClientRect(), css = getComputedStyle(element);
        return [key, { x: rect.x, y: rect.y, width: rect.width, height: rect.height, background: css.backgroundColor, border: css.borderTopWidth, fontSize: css.fontSize }];
      }));
    })()`;
    for (const width of [1440, 390]) {
      await send('Emulation.setDeviceMetricsOverride', { width, height: width === 390 ? 844 : 1000, deviceScaleFactor: 1, mobile: width === 390 });
      await send('Page.navigate', { url: base + '/#reports' });
      await until(`document.querySelector('.php-tree-report .vmainbox') && document.querySelector('.content-wrapper').getBoundingClientRect().x === ${width === 390 ? 0 : 250}`);
      await evaluate('document.fonts.ready');
      const actual = await evaluate(measureTree);
      await writeFile(path.join(root, `.tmp/tree-report-actual-${width}.png`), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
      await send('Page.navigate', { url: base + '/__reference/tree' });
      await until(`document.querySelector('#new-tree .vmainbox')`); await evaluate('document.fonts.ready');
      const original = await evaluate(measureTree);
      await writeFile(path.join(root, `.tmp/tree-report-original-${width}.png`), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
      await writeFile(path.join(root, `.tmp/tree-report-metrics-${width}.json`), JSON.stringify({ actual, original }, null, 2));
      for (const key of Object.keys(actual)) {
        for (const property of ['x', 'y', 'width', 'height']) assert.ok(Math.abs(actual[key][property] - original[key][property]) <= 1, `PHP tree ${width}px ${key}.${property}: ${actual[key][property]} vs ${original[key][property]}`);
        for (const property of ['background', 'border', 'fontSize']) assert.equal(actual[key][property], original[key][property], `PHP tree ${width}px ${key}.${property}`);
      }
    }
    console.log('PASS: tree report boxes, connectors layout, colors and sizes match report/tree-chart.php on desktop and mobile.');
    await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 1000, deviceScaleFactor: 1, mobile: false });
    await send('Page.navigate', { url: base + '/#reports' });
    await until(`document.querySelector('.php-tree-report .vmainbox')`);
  }
  const tabs = await evaluate(`Array.from(document.querySelectorAll('a[data-nav-tab]')).map(a => a.hash.slice(1))`);
  assert.equal(tabs.length, 48);
  for (const tab of tabs) {
    if (['regions', 'keths'].includes(tab)) {
      await click(`a[data-nav-tab][href="#${tab}"]`);
      await until(`location.hash === '#${tab}' && document.querySelectorAll('.province-link').length === ${tab === 'keths' ? 76 : 77} && Boolean(document.querySelector('.inspectorates')) === ${tab === 'keths'}`);
    } else await navigate(tab);
    assert.equal(await evaluate(`document.querySelectorAll('a[data-nav-tab][aria-current="page"]').length`), 1, tab);
    assert.equal(await evaluate(`/demo|mockup|จำลอง|portfolio/i.test(document.body.innerText)`), false, `${tab}: visible copy`);
    assert.equal(await evaluate(`Boolean(document.querySelector('.alert-error'))`), false, `${tab}: errors`);
  }
  const statusReport = await (await fetch(base + '/api/reports/status')).json();
  await navigate('dashboard');
  await writeFile(path.join(root, '.tmp/status-overview-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await click('[data-chart-panel="status"] .native-chart-heading button');
  await until(`document.querySelector('[data-chart-panel="status"] .native-chart-table')`);
  for (const entry of statusReport.panels.status) assert.equal(Number(await evaluate(`document.querySelector('[data-chart-panel="status"] [data-chart-key="${entry.key}"]').innerText`)), entry.count);
  await click('[data-chart-panel="status"] [data-chart-key="active"]');
  await until(`document.querySelector('.php-tree-list #cityplanshow')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow .text_cp_name').length`), statusReport.panels.status[0].count);
  await click('#btn_goback'); await until(`document.querySelectorAll('[data-chart-ready=true]').length === 4`);
  const chartPoint = await evaluate(`(() => { const canvas = document.querySelector('[data-chart-panel="stage"] canvas'); const chart = Chart.getChart(canvas); const point = chart.getDatasetMeta(0).data[2].getCenterPoint(); const rect = canvas.getBoundingClientRect(); return { x: rect.x + point.x, y: rect.y + point.y }; })()`);
  await send('Input.dispatchMouseEvent', { type: 'mouseMoved', ...chartPoint });
  await send('Input.dispatchMouseEvent', { type: 'mousePressed', button: 'left', clickCount: 1, ...chartPoint });
  await send('Input.dispatchMouseEvent', { type: 'mouseReleased', button: 'left', clickCount: 1, ...chartPoint });
  await until(`document.querySelector('.php-tree-list #cityplanshow')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow .text_cp_name').length`), statusReport.panels.stage[2].count);
  await navigate('status');
  await writeFile(path.join(root, '.tmp/current-status-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  for (const column of statusReport.matrix) {
    for (const row of column.rows) assert.equal(Number(await evaluate(`document.querySelector('[data-matrix-cell="${column.key}/${row.key}"]').innerText`)), row.count);
    assert.equal(Number(await evaluate(`document.querySelector('[data-matrix-cell="${column.key}/all"]').innerText`)), column.count);
  }
  const matrixCell = statusReport.matrix.flatMap(column => column.rows.map(row => ({ ...row, column: column.key }))).find(row => row.count > 0);
  await click(`[data-matrix-cell="${matrixCell.column}/${matrixCell.key}"]`);
  await until(`document.querySelector('.current-status-selection')`);
  assert.equal(await evaluate(`document.querySelectorAll('.current-status-selection .row .native-report-link').length`), matrixCell.count);
  await click('.current-selection-actions .btn-success');
  await until(`document.querySelector('.php-tree-list #cityplanshow')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow .text_cp_name').length`), matrixCell.count);
  await click('#btn_goback'); await until(`document.querySelector('.current-matrix')`);
  await click('a[data-nav-tab][href="#keths"]');
  await until(`document.querySelectorAll('.inspectorates .province-link').length === 76`);
  assert.equal(await evaluate(`document.querySelectorAll('.inspectorates .dpt-status-count').length`), 18 * 8);
  assert.deepEqual(await evaluate(`Array.from(document.querySelectorAll('[data-keth="1"] .province-link')).map(element => element.innerText.split(' (')[0].trim())`), ['ชัยนาท', 'พระนครศรีอยุธยา', 'ลพบุรี', 'สระบุรี', 'สิงห์บุรี', 'อ่างทอง']);
  await writeFile(path.join(root, '.tmp/inspectorates-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await click('[data-keth="1"] .park-name');
  await until(`location.hash === '#keths/keth/1' && document.querySelectorAll('.dpt-province-card').length === 6`);
  assert.ok((await evaluate(`document.querySelector('.map-container img').src`)).endsWith('/php/img/keth/1.png'));
  await click('.dpt-province-card .province-link');
  await until(`location.hash.includes('/province/') && document.querySelector('#cityplanshow .text_cp_name')`);
  await click('#btn_backy'); await until(`location.hash === '#keths/keth/1'`);
  await click('#btn_backy'); await until(`location.hash === '#keths' && document.querySelectorAll('.province-link').length === 76`);
  await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
  for (const tab of ['dashboard', 'status', 'keths']) {
    if (tab === 'keths') { await click('a[data-nav-tab][href="#keths"]'); await until(`document.querySelectorAll('.province-link').length === 76`); }
    else await navigate(tab);
    await until(`document.querySelector('.content-wrapper').getBoundingClientRect().x === 0`);
    if (tab === 'dashboard') await until(`Array.from(document.querySelectorAll('.native-chart-surface canvas')).every(canvas => canvas.getBoundingClientRect().height > 100 && Math.abs(canvas.width - canvas.getBoundingClientRect().width) < 2)`);
    assert.equal(await evaluate(`document.documentElement.scrollWidth <= innerWidth`), true, `${tab} mobile overflow`);
    await writeFile(path.join(root, `.tmp/${tab}-mobile.png`), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  }
  await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 1000, deviceScaleFactor: 1, mobile: false });
  await navigate('reports');
  console.log('PASS: inspectorate memberships/drill-down, status chart/table values and bar clicks, tracking matrix totals/details, and all three mobile layouts.');
  const treeData = await (await fetch(base + '/api/reports/tree')).json();
  const chartValues = await evaluate(`Array.from(document.querySelectorAll('[data-tree-filter]')).map(element => ({ key: element.dataset.treeFilter, count: Number(element.innerText) }))`);
  assert.ok(chartValues.length > 20);
  for (const value of chartValues) assert.equal(value.count, treeData.counts[value.key], value.key);
  await click('[data-tree-help]');
  await until(`document.querySelector('[role=dialog]')?.innerText.includes('แนะนำการใช้งาน')`);
  await click('[aria-label="ปิดคำแนะนำ"]');
  for (const group of treeData.groups.filter(group => chartValues.some(value => value.key === group.key))) {
    await navigate('reports');
    await click(`[data-tree-filter="${group.key}"]`);
    await until(`location.hash === '#reports/list/${group.key}' && document.querySelector('.php-tree-list #cityplanshow')`);
    assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow .text_cp_name').length`), group.count, group.key);
    await click('#btn_goback');
    await until(`location.hash === '#reports' && document.querySelector('.php-tree-report .vmainbox')`);
  }
  await send('Page.navigate', { url: base + '/#reports/list/eva5' });
  await until(`location.hash === '#reports/list/eva5' && document.querySelector('.php-tree-list #cityplanshow')`);
  await send('Page.reload');
  await until(`document.querySelector('.php-tree-list #cityplanshow')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow .text_cp_name').length`), treeData.counts.eva5);
  const additionalEec = await (await fetch(base + '/api/plans', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ name: 'ผังตรวจสอบกลุ่มผู้ดำเนินการ', provinceId: 4, owner: 'อปท.', isEec: true }) })).json();
  await navigate('dashboard'); await navigate('reports');
  await until(`document.querySelector('[data-tree-filter="eecY-2"]')`);
  assert.equal(await evaluate(`Number(document.querySelector('[data-tree-filter="eecY-1"]').innerText)`), treeData.counts['eecY-1']);
  assert.equal(await evaluate(`Number(document.querySelector('[data-tree-filter="eecY-2"]').innerText)`), treeData.counts['eecY-2'] + 1);
  await click('[data-tree-filter="eecY-2"]');
  await until(`document.querySelector('#cityplanshow .text_cp_name')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow .text_cp_name').length`), treeData.counts['eecY-2'] + 1);
  await fetch(base + `/api/plans/${additionalEec.id}`, { method: 'DELETE' });
  await mkdir(path.join(root, '.tmp'), { recursive: true });
  await click('a[data-nav-tab][href="#regions"]');
  await until(`document.querySelectorAll('.dpt-province-table .province-link').length === 77`);
  assert.equal(await evaluate(`document.querySelectorAll('#table_detail_status tbody tr').length`), 8);
  assert.equal(await evaluate(`document.querySelectorAll('.dpt-status-count').length`), 40);
  const seedPlans = (await (await fetch(base + '/api/plans')).json()).items;
  const expectedStatus = (plan, status) => {
    if (status === 7) return Boolean(plan.nextStepPending);
    if (status === 8) return plan.currentStep === 8 && [2, 4, 5, 6, 8].includes(plan.subStep);
    if (plan.currentStep < 1 || plan.subStep < 1) return status === 1;
    return ['on_track', 'slight_delay', 'moderate_delay', 'severe_delay', 'unscheduled'][status - 2] === plan.health;
  };
  for (let region = 1; region <= 5; region++) {
    const scoped = seedPlans.filter(p => p.regionId === region);
    assert.equal(Number(await evaluate(`document.querySelector('[data-region="${region}"] .park-value').innerText`)), scoped.length);
    for (let status = 1; status <= 8; status++) {
      assert.equal(Number(await evaluate(`document.querySelector('[data-region="${region}"] [data-status="${status}"]').innerText`)), scoped.filter(p => expectedStatus(p, status)).length);
    }
  }
  await writeFile(path.join(root, '.tmp/geography-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: true })).data, 'base64'));
  await click('[data-region="1"] .park-name');
  await until(`location.hash === '#regions/region/1' && document.querySelectorAll('.dpt-province-card').length === 17 && document.querySelector('.map-container img').complete`);
  assert.ok((await evaluate(`document.querySelector('.map-container img').src`)).endsWith('/php/img/map/1.jpg'));
  await click('.dpt-province-card .province-link');
  await until(`location.hash.includes('/province/') && document.querySelector('#cityplanshow tbody tr')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow tbody tr').length`), 1);
  await click('#cityplanshow .text_cp_name');
  await until(`document.querySelector('.modal-card .detail-meta-grid')`);
  await click('.modal-header .btn-icon');
  await click('#btn_backy');
  await until(`location.hash === '#regions/region/1' && document.querySelector('.dpt-province-card')`);
  await click('[data-region="1"] [data-status="3"]');
  await until(`location.hash === '#regions/region/1/report/status/3' && document.querySelector('#cityplanshow tbody tr')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow tbody tr').length`), seedPlans.filter(p => p.regionId === 1 && expectedStatus(p, 3)).length);
  assert.equal(await evaluate(`getComputedStyle(document.querySelector('#status_table')).borderTopWidth`), '2px');
  assert.equal(await evaluate(`getComputedStyle(document.querySelector('.dpt-plan-report th')).backgroundColor`), 'rgb(41, 93, 189)');
  await click('#status_x td .dpt-link');
  await until(`location.hash === '#regions/region/1/report/status/9' && document.querySelector('#cityplanshow tbody tr')`);
  assert.equal(await evaluate(`document.querySelectorAll('#cityplanshow tbody tr').length`), seedPlans.filter(p => p.regionId === 1 && p.currentStep > 0 && p.subStep > 0).length);
  await writeFile(path.join(root, '.tmp/design-report.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await click('#btn_backy');
  await until(`location.hash === '#regions/region/1/report/status/3'`);
  await click('#btn_backy');
  await until(`location.hash === '#regions/region/1' && document.querySelector('.dpt-province-card')`);
  await click('#btn_backy');
  await until(`location.hash === '#regions' && document.querySelectorAll('.dpt-province-table .province-link').length === 77`);
  await send('Page.navigate', { url: base + '/#regions/region/1/province/24/status/2' });
  await until(`document.querySelector('#cityplanshow') && document.querySelector('#title_page').innerText.includes('เชียงใหม่')`);
  await send('Page.reload');
  await until(`document.querySelector('#cityplanshow .text_cp_name') && document.querySelector('#title_page').innerText.includes('เชียงใหม่')`);
  assert.equal(await evaluate(`document.querySelector('a[data-nav-tab][aria-current="page"]').hash`), '#regions');
  await click('a[data-nav-tab][href="#regions"]');
  await until(`document.querySelectorAll('.dpt-province-table .province-link').length === 77`);
  await send('Network.setBlockedURLs', { urls: ['https://fonts.googleapis.com/*', 'https://fonts.gstatic.com/*', base + '/api/plans'] });
  await send('Page.reload');
  await until(`document.querySelector('.dpt-geography-error')`);
  assert.equal(await evaluate(`document.querySelector('[data-region="1"] .park-value').innerText`), '—');
  await send('Network.setBlockedURLs', { urls: ['https://fonts.googleapis.com/*', 'https://fonts.gstatic.com/*'] });
  await click('.dpt-geography-error button');
  await until(`document.querySelectorAll('.dpt-province-table .province-link').length === 77 && !document.querySelector('.dpt-geography-error')`);
  await navigate('noteCategories');
  await until(`document.querySelectorAll('.data-table tbody tr').length === 4`);
  await click('.panel-header .btn-primary');
  await fill('.modal-card input:not([type="checkbox"])', 'หมวดตรวจสอบหน้าจอ');
  await fill('.modal-card textarea', 'รายละเอียดหมวดใหม่');
  await evaluate(`document.querySelector('.modal-card form').requestSubmit()`);
  await until(`!document.querySelector('.modal-card') && document.querySelector('.data-table')?.innerText.includes('หมวดตรวจสอบหน้าจอ')`);
  await evaluate(`Array.from(document.querySelectorAll('.data-table tbody tr')).find(tr => tr.innerText.includes('หมวดตรวจสอบหน้าจอ')).querySelector('button').click()`);
  await fill('.modal-card textarea', 'แก้ไขรายละเอียดสำเร็จ');
  await evaluate(`document.querySelector('.modal-card form').requestSubmit()`);
  await until(`!document.querySelector('.modal-card') && document.querySelector('.data-table')?.innerText.includes('แก้ไขรายละเอียดสำเร็จ')`);
  await send('Page.setInterceptFileChooserDialog', { enabled: false });
  await evaluate(`window.confirm = () => true`);
  await evaluate(`Array.from(document.querySelectorAll('.data-table tbody tr')).find(tr => tr.innerText.includes('หมวดตรวจสอบหน้าจอ')).querySelector('.btn-danger').click()`);
  await until(`document.querySelectorAll('.data-table tbody tr').length === 4`);
  await navigate('plans');
  await until(`document.querySelectorAll('.data-table tbody tr').length === 77`);
  await fill('.filter-bar input', 'เชียงใหม่');
  await until(`document.querySelectorAll('.data-table tbody tr').length === 1 && document.querySelector('.data-table tbody').innerText.includes('เชียงใหม่')`);
  await click('.data-table tbody .link-btn');
  await until(`document.querySelector('.modal-card .detail-meta-grid')`);
  await fill('.modal-card input[placeholder]', 'บันทึกติดตามจากหน้าจอ');
  await evaluate(`document.querySelector('form[aria-label="เพิ่มหมายเหตุ"]').requestSubmit()`);
  await until(`document.querySelector('.notes-list').innerText.includes('บันทึกติดตามจากหน้าจอ')`);
  await click('.modal-header .btn-icon');
  await navigate('notes');
  await until(`document.querySelector('.data-table').innerText.includes('บันทึกติดตามจากหน้าจอ')`);
  await navigate('m35');
  await click('.panel-header .btn-primary');
  assert.equal(await evaluate(`document.querySelectorAll('.modal-card select')[2].value`), 'm35');
  await fill('.modal-card input[placeholder]', 'ผังเมืองตรวจสอบหน้าจอ');
  await evaluate(`document.querySelector('.modal-card form').requestSubmit()`);
  await until(`!document.querySelector('.modal-card') && document.querySelector('.data-table').innerText.includes('ผังเมืองตรวจสอบหน้าจอ')`);
  await evaluate(`Array.from(document.querySelectorAll('.data-table tbody tr')).find(tr => tr.innerText.includes('ผังเมืองตรวจสอบหน้าจอ')).querySelector('.btn-danger').click()`);
  await until(`!document.querySelector('.data-table').innerText.includes('ผังเมืองตรวจสอบหน้าจอ')`);
  await navigate('steps');
  await click('.data-table tbody tr:nth-child(2) .btn-sm');
  await fill('.modal-card input[type="number"]', '12');
  await evaluate(`document.querySelector('.modal-card form').requestSubmit()`);
  await until(`!document.querySelector('.modal-card') && document.querySelector('.data-table tbody tr:nth-child(2) td:nth-child(3)').innerText === '12'`);
  await navigate('export');
  await evaluate(`(() => { const select = document.querySelector('select[aria-label="ภูมิภาค"]'); select.value = '1'; select.dispatchEvent(new Event('change', { bubbles: true })); })()`);
  await until(`document.querySelectorAll('.data-table tbody tr').length === 17`);
  await send('Browser.setDownloadBehavior', { behavior: 'deny' }, null);
  await evaluate(`(() => { const create = URL.createObjectURL; URL.createObjectURL = blob => { window.__csvText = blob.text(); return create(blob); }; })()`);
  await click('.panel-header .btn-primary');
  const csv = await evaluate('window.__csvText');
  assert.ok(csv.includes('เชียงใหม่') && !csv.includes('ภูเก็ต'));
  assert.equal(csv.split('\r\n').length, 18, 'export honors region filter');
  await navigate('import');
  await mkdir(path.join(root, '.tmp'), { recursive: true });
  const invalidImport = path.join(root, '.tmp/import-invalid.csv');
  const validImport = path.join(root, '.tmp/import-valid.csv');
  const attachmentFile = path.join(root, '.tmp/แผนที่.pdf');
  await writeFile(invalidImport, 'name,provinceId\nจังหวัดผิด,999');
  await writeFile(validImport, 'name,provinceId,budgetYear\nผังเมืองนำเข้าจากหน้าจอ,24,2569');
  await writeFile(attachmentFile, '%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\n%%EOF');
  async function selectFile(selector, filename) {
    const document = await send('DOM.getDocument');
    const { nodeId } = await send('DOM.querySelector', { nodeId: document.root.nodeId, selector });
    await send('DOM.setFileInputFiles', { nodeId, files: [filename] });
  }
  await selectFile('input[aria-label="ไฟล์นำเข้าผังเมือง"]', invalidImport);
  await evaluate(`document.querySelector('.data-transfer form').requestSubmit()`);
  await until(`document.querySelector('section[aria-label="ผลตรวจไฟล์นำเข้า"] .alert-error')`);
  assert.equal(await evaluate(`document.querySelector('section[aria-label="ผลตรวจไฟล์นำเข้า"] > button').disabled`), true);
  await selectFile('input[aria-label="ไฟล์นำเข้าผังเมือง"]', validImport);
  await evaluate(`document.querySelector('.data-transfer form').requestSubmit()`);
  await until(`document.querySelector('section[aria-label="ผลตรวจไฟล์นำเข้า"] > button') && !document.querySelector('section[aria-label="ผลตรวจไฟล์นำเข้า"] > button').disabled`);
  await writeFile(path.join(root, '.tmp/import-preview-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await click('section[aria-label="ผลตรวจไฟล์นำเข้า"] > button');
  await until(`document.querySelector('.data-transfer [role="status"]')?.innerText.includes('1 รายการ')`);
  await navigate('plans');
  await fill('.filter-bar input', 'ผังเมืองนำเข้าจากหน้าจอ');
  await until(`document.querySelectorAll('.data-table tbody tr').length === 1 && document.querySelector('.data-table tbody').innerText.includes('ผังเมืองนำเข้าจากหน้าจอ')`);
  await click('.data-table tbody .link-btn');
  await until(`document.querySelector('input[aria-label="เลือกไฟล์แนบ"]:not(:disabled)')`);
  await selectFile('input[aria-label="เลือกไฟล์แนบ"]', attachmentFile);
  await evaluate(`document.querySelector('section[aria-label="ไฟล์แนบผังเมือง"] form').requestSubmit()`);
  await until(`document.querySelector('.attachment-list')?.innerText.includes('แผนที่.pdf')`);
  await evaluate(`(() => { const select = document.querySelector('.modal-card .inline-controls select'); select.value = '3'; select.dispatchEvent(new Event('change', { bubbles: true })); })()`);
  await click('.modal-card .inline-controls .btn-primary');
  await until(`document.querySelector('section[aria-label="ประวัติการแก้ไขผังเมือง"] details')`);
  await writeFile(path.join(root, '.tmp/plan-history-files-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await click('.attachment-list button');
  await until(`!document.querySelector('.attachment-list')`);
  await click('.modal-header .btn-icon');
  await evaluate(`document.querySelector('.data-table tbody .btn-danger').click()`);
  await until(`document.querySelector('.empty-cell')`);
  async function choose(selector, text) {
    await evaluate(`(() => { const select = document.querySelector(${JSON.stringify(selector)}); const option = Array.from(select.options).find(option => option.textContent.includes(${JSON.stringify(text)})); if (!option) throw new Error('Option missing'); select.value = option.value; select.dispatchEvent(new Event('change', { bubbles: true })); })()`);
  }
  async function addModule(kind, title, fields = {}, references = {}) {
    await navigate(`module-${kind}`); await click('.panel-header .btn-primary');
    await fill('.modal-card input[name="name"]', title);
    for (const [key, value] of Object.entries(fields)) await fill(`.modal-card [name="${key}"]`, String(value));
    for (const [key, value] of Object.entries(references)) await choose(`.modal-card [name="${key}"]`, value);
    await evaluate(`document.querySelector('.module-page .modal-card form').requestSubmit()`);
    await until(`!document.querySelector('.modal-card') && document.querySelector('.module-page .data-table').innerText.includes(${JSON.stringify(title)})`, `${kind} created`);
  }
  await addModule('companies', 'บริษัทจากหน้าจอ', { phone: '02 123 4567' });
  await addModule('projects', 'โครงการจากหน้าจอ', { budget: 100000, price: 90000 }, { companyId: 'บริษัทจากหน้าจอ' });
  await addModule('equipment', 'อุปกรณ์จากหน้าจอ', { price: 50000, maintenanceAmount: 5000, warrantyYears: 1, replacementYear: 2573 }, { projectId: 'โครงการจากหน้าจอ' });
  await addModule('maintenancePlans', 'แผนบำรุงรักษาจากหน้าจอ', { startYear: 2569, years: 5 }, { equipmentIds: 'อุปกรณ์จากหน้าจอ' });
  await evaluate(`Array.from(document.querySelectorAll('.module-page button')).find(button => button.textContent === 'ตารางรายปี').click()`);
  await until(`document.querySelector('.maintenance-replacement') && document.querySelector('.maintenance-warranty')`);
  await writeFile(path.join(root, '.tmp/maintenance-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await addModule('workflowCases', 'งานแก้ไขจากหน้าจอ', {}, { planId: 'เมืองเชียงใหม่' });
  const definitions = await (await fetch(base + '/api/modules/workflowSteps')).json();
  const workflowDefinition = definitions.find(item => item.data.profile === 'm35' && item.data.step === 2 && item.data.subStep === 1 && item.data.thirdStep === 0);
  await navigate('module-progressEntries'); await click('.panel-header .btn-primary');
  await fill('.modal-card [name="name"]', 'ความก้าวหน้าจากหน้าจอ');
  await choose('.modal-card [name="planId"]', 'เมืองเชียงใหม่'); await choose('.modal-card [name="caseId"]', 'งานแก้ไขจากหน้าจอ');
  await evaluate(`(() => { const select = document.querySelector('.modal-card [name="definitionId"]'); select.value = '${workflowDefinition.id}'; select.dispatchEvent(new Event('change', { bubbles: true })); })()`);
  await fill('.modal-card [name="start"]', '2026-01-01'); await fill('.modal-card [name="stop"]', '2026-01-28');
  await click('.modal-card [name="makeCurrent"]');
  await evaluate(`document.querySelector('.module-page .modal-card form').requestSubmit()`);
  await until(`!document.querySelector('.modal-card') && document.querySelector('.module-page').innerText.includes('ความก้าวหน้าจากหน้าจอ')`);
  assert.equal((await (await fetch(base + '/api/plans/1')).json()).plan.currentStep, 2);
  await addModule('planSchedules', 'แผนดำเนินงานจากหน้าจอ', { start: '2026-01-01' }, { planId: 'เมืองเชียงใหม่' });
  await evaluate(`Array.from(document.querySelectorAll('.module-page button')).find(button => button.textContent === 'ดูแผน').click()`);
  await until(`document.querySelector('.section-box .data-table tbody tr')`);
  await navigate('catalog'); await click('.report-catalog details summary');
  await fill('input[aria-label="ชื่อชุดข้อมูล"]', 'ชุดข้อมูลจากหน้าจอ');
  await evaluate(`document.querySelector('.report-catalog form').requestSubmit()`);
  await until(`document.querySelector('select[aria-label="ชุดข้อมูลสถานภาพ"]').value !== '' && !document.querySelector('.report-catalog .loading-box')`);
  await click('.report-catalog .data-table tbody .link-btn');
  await until(`document.querySelector('.report-catalog section .data-table tbody tr')`);
  await navigate('map'); await until(`document.querySelectorAll('.interactive-map path[role="button"]').length === 77`);
  const bangkokPoint = await evaluate(`(() => { const svg = document.querySelector('.interactive-map svg'); const point = new DOMPoint(100.5, 10.25).matrixTransform(svg.getScreenCTM()); return { x: point.x, y: point.y }; })()`);
  await send('Input.dispatchMouseEvent', { type: 'mouseMoved', ...bangkokPoint });
  await send('Input.dispatchMouseEvent', { type: 'mousePressed', button: 'left', clickCount: 1, ...bangkokPoint });
  await send('Input.dispatchMouseEvent', { type: 'mouseReleased', button: 'left', clickCount: 1, ...bangkokPoint });
  await until(`document.querySelector('section[aria-label="รายการผังในจังหวัด"]').innerText.includes('กรุงเทพมหานคร')`, 'province selected with the mouse');
  const viewBox = await evaluate(`document.querySelector('.interactive-map svg').getAttribute('viewBox')`);
  await click('button[aria-label="ขยายแผนที่"]');
  await until(`document.querySelector('.interactive-map svg').getAttribute('viewBox') !== ${JSON.stringify(viewBox)}`);
  await evaluate(`document.querySelector('.interactive-map path[aria-label^="เชียงใหม่ "]').dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }))`);
  await until(`document.querySelector('section[aria-label="รายการผังในจังหวัด"]').innerText.includes('เมืองเชียงใหม่')`);
  await writeFile(path.join(root, '.tmp/interactive-map-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await navigate('system'); await until(`document.querySelector('.report-summary strong')`);
  await navigate('history'); await until(`document.querySelector('.note-item summary')`);
  await navigate('accounts'); await click('.panel-header .btn-primary');
  await fill('.modal-card input[pattern]', 'portfolio.owner');
  await fill('.modal-card input[maxlength="250"]', 'ผู้ดูแลทดสอบ');
  await fill('.modal-card input[type="password"]', 'portfolio-test-password');
  await evaluate(`document.querySelector('.modal-card form').requestSubmit()`);
  await until(`!document.querySelector('.modal-card') && document.querySelector('.data-table').innerText.includes('portfolio.owner')`);
  await send('Page.reload'); await until(`document.querySelector('.auth-menu-button')?.innerText.includes('ผู้ดูแลทดสอบ') && document.querySelector('.data-table')?.innerText.includes('portfolio.owner')`);
  const committeeWasHidden = await evaluate(`document.querySelector('[data-menu-group=committee] > .menu-section-items').hidden`);
  await click('[data-menu-group=committee] > .menu-section-toggle');
  assert.equal(await evaluate(`document.querySelector('[data-menu-group=committee] > .menu-section-items').hidden`), !committeeWasHidden);
  await click('[data-menu-group=committee] > .menu-section-toggle');
  await navigate('dashboard');
  await click('.sidebar-toggle');
  await until(`document.querySelector('.main-sidebar').getBoundingClientRect().width === 50`);
  assert.equal(await evaluate(`document.querySelector('.main-sidebar').getBoundingClientRect().width`), 50);
  await click('.sidebar-toggle');
  await mkdir(path.join(root, '.tmp'), { recursive: true });
  await writeFile(path.join(root, '.tmp/ui-desktop.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, deviceScaleFactor: 1, mobile: true });
  await click('a[data-nav-tab][href="#regions"]');
  await until(`document.querySelectorAll('.dpt-province-table .province-link').length === 77 && document.querySelector('.map-container img').complete`);
  assert.equal(await evaluate(`document.documentElement.scrollWidth <= window.innerWidth`), true, 'geography mobile overflow');
  await writeFile(path.join(root, '.tmp/geography-mobile.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await click('.sidebar-toggle');
  await until(`document.querySelector('.php-shell').classList.contains('sidebar-open')`);
  await evaluate(`document.querySelector('.main-sidebar').scrollTop = 0`);
  await writeFile(path.join(root, '.tmp/ui-mobile-menu.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  await navigate('plans');
  assert.equal(await evaluate(`document.querySelector('.php-shell').classList.contains('sidebar-open')`), false);
  await until(`document.querySelectorAll('.data-table tbody tr').length === 77`);
  assert.equal(await evaluate(`document.documentElement.scrollWidth <= window.innerWidth`), true, 'mobile page overflow');
  assert.deepEqual(exceptions, [], 'browser exceptions');
  assert.deepEqual(failedRequests, [], 'failed local HTTP requests');
  await navigate('import');
  await until(`!document.querySelector('.php-shell').classList.contains('sidebar-open') && document.querySelector('.main-sidebar').getBoundingClientRect().right <= 0`, 'mobile menu closed after navigation');
  assert.equal(await evaluate(`document.documentElement.scrollWidth <= window.innerWidth`), true, 'import mobile overflow');
  await writeFile(path.join(root, '.tmp/import-mobile.png'), Buffer.from((await send('Page.captureScreenshot', { format: 'png' })).data, 'base64'));
  console.log('PASS: all 48 menus, geography/reports, CRUD, spreadsheets, attachments, nested workflows, procurement/maintenance, snapshots, interactive map, account setup and desktop/mobile layouts.');
} catch (error) {
  if (socket?.readyState === WebSocket.OPEN && sessionId) {
    console.error(await evaluate(`JSON.stringify({ url: location.href, text: document.body?.innerText.slice(0, 1400) })`).catch(() => 'Page unavailable'));
    console.error(JSON.stringify({ exceptions, failedRequests }));
  }
  throw error;
} finally {
  if (socket?.readyState === WebSocket.OPEN) { await send('Browser.close', {}, null).catch(() => {}); socket.close(); }
  if (browser.exitCode === null) browser.kill();
  await new Promise(resolve => server.close(resolve));
  // Verify the generated browser profile is inside the intended temp directory.
  if (path.dirname(path.resolve(profile)) === path.resolve(os.tmpdir()) && path.basename(profile).startsWith('upms-browser-')) {
    await rm(profile, { recursive: true, force: true, maxRetries: 4, retryDelay: 250 }).catch(() => {});
  }
}
