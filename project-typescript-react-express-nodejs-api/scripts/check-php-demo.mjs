import assert from 'node:assert/strict';
import { execFileSync, spawn } from 'node:child_process';
import { once } from 'node:events';
import { existsSync } from 'node:fs';
import { copyFile, mkdir, mkdtemp, rm } from 'node:fs/promises';
import net from 'node:net';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { auditPhpRuntime } from './audit-php-runtime.mjs';

const project = fileURLToPath(new URL('../', import.meta.url));
const repo = path.resolve(project, '..');
const php = process.env.PHP_PATH || (process.platform === 'win32' ? 'C:/xampp/php/php.exe' : 'php');
const chrome = [process.env.CHROME_PATH, 'C:/Program Files/Google/Chrome/Application/chrome.exe',
  'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe', '/usr/bin/google-chrome', '/usr/bin/chromium',
].find(candidate => candidate && existsSync(candidate));
assert.ok(chrome, 'Set CHROME_PATH to Chrome or Edge.');
const { report } = auditPhpRuntime();
const archived = new Set(report.archived.map(file => file.path));
const baseline = process.argv.includes('--baseline');
const published = execFileSync('git', ['ls-files', '-z', '--', 'php'], { cwd: repo, encoding: 'utf8', windowsHide: true }).split('\0').filter(Boolean);
const tracked = baseline ? [...new Set([...published, ...archived])].sort() : published.filter(file => !archived.has(file));
for (const file of tracked) assert.ok(existsSync(path.join(repo, file)), `Baseline needs the local archive copy: ${file}`);
for (const file of tracked.filter(file => file.endsWith('.php'))) {
  execFileSync(php, ['-l', path.join(repo, file)], { windowsHide: true, stdio: 'pipe' });
}
await mkdir(path.join(project, '.tmp'), { recursive: true });
const temporary = await mkdtemp(path.join(project, '.tmp/php-runtime-'));
const docroot = path.join(temporary, 'public');
let server; let browser; let socket; let sessionId; let sequence = 0;
let serverLog = '';
const pending = new Map(); const exceptions = []; const failures = [];
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
  if (result.exceptionDetails) throw new Error(JSON.stringify(result.exceptionDetails));
  return result.result.value;
}
async function until(expression) {
  const deadline = Date.now() + 15000;
  while (Date.now() < deadline) {
    if (await evaluate(`Boolean(${expression})`)) return;
    await new Promise(resolve => setTimeout(resolve, 100));
  }
  throw new Error(`PHP UI timeout: ${expression}; exceptions: ${JSON.stringify(exceptions)}; HTTP failures: ${JSON.stringify(failures)}; PHP log: ${serverLog.slice(-2000)}`);
}
async function stop(child) {
  if (!child || child.exitCode !== null) return;
  const ended = once(child, 'exit'); child.kill();
  await Promise.race([ended, new Promise(resolve => setTimeout(resolve, 5000))]);
}
try {
  // Copy the published file list, never the whole local tree: ignored legacy
  // copies and the user's database cannot conceal missing dependencies.
  for (const file of tracked) {
    const target = path.join(docroot, file.slice(4));
    await mkdir(path.dirname(target), { recursive: true });
    await copyFile(path.join(repo, file), target);
  }
  for (const directory of ['sessions', 'uploads', 'profile']) await mkdir(path.join(temporary, directory));
  if (!baseline) for (const file of archived) assert.equal(existsSync(path.join(docroot, file.slice(4))), false, file);
  const reservation = net.createServer().listen(0, '127.0.0.1'); await once(reservation, 'listening');
  const port = reservation.address().port; await new Promise(resolve => reservation.close(resolve));
  const base = `http://127.0.0.1:${port}`;
  // Keep existing PHP notices in the server log rather than inside HTML attributes.
  server = spawn(php, ['-d', 'display_errors=0', '-d', 'log_errors=1', '-d', `session.save_path=${path.join(temporary, 'sessions')}`, '-d', `upload_tmp_dir=${path.join(temporary, 'uploads')}`, '-S', `127.0.0.1:${port}`, '-t', docroot], { cwd: docroot, windowsHide: true, stdio: ['ignore', 'pipe', 'pipe'] });
  server.stdout.on('data', data => { serverLog += data; }); server.stderr.on('data', data => { serverLog += data; });
  server.on('error', error => { serverLog += error.message; });
  let ready = false;
  for (let attempt = 0; attempt < 100; attempt++) {
    try { const response = await fetch(`${base}/mock_db_setup.php`); assert.equal(response.status, 200); ready = true; break; } catch {}
    await new Promise(resolve => setTimeout(resolve, 100));
  }
  assert.ok(ready, serverLog);
  browser = spawn(chrome, ['--headless=new', '--no-sandbox', '--disable-gpu', '--disable-extensions', '--disable-background-networking', '--no-first-run', '--no-default-browser-check', '--remote-debugging-port=0', `--user-data-dir=${path.join(temporary, 'profile')}`, 'about:blank'], { windowsHide: true, stdio: ['ignore', 'ignore', 'pipe'] });
  const address = await new Promise((resolve, reject) => {
    let output = ''; const timer = setTimeout(() => reject(new Error(output)), 20000);
    browser.once('error', error => { clearTimeout(timer); reject(error); });
    browser.stderr.on('data', data => { output += data; const match = output.match(/DevTools listening on (ws:\/\/\S+)/); if (match) { clearTimeout(timer); resolve(match[1]); } });
  });
  socket = new WebSocket(address); await once(socket, 'open');
  socket.addEventListener('message', event => {
    const message = JSON.parse(event.data);
    if (message.id && pending.has(message.id)) {
      const task = pending.get(message.id); pending.delete(message.id); clearTimeout(task.timer);
      message.error ? task.reject(new Error(message.error.message)) : task.resolve(message.result);
    }
    if (message.method === 'Runtime.exceptionThrown') exceptions.push(message.params.exceptionDetails);
    if (message.method === 'Network.responseReceived' && message.params.response.url.startsWith(base) && message.params.response.status >= 400) failures.push(message.params.response);
    if (message.method === 'Page.javascriptDialogOpening') void send('Page.handleJavaScriptDialog', { accept: true });
  });
  const target = await send('Target.createTarget', { url: 'about:blank' }, null);
  sessionId = (await send('Target.attachToTarget', { targetId: target.targetId, flatten: true }, null)).sessionId;
  await send('Page.enable'); await send('Runtime.enable'); await send('Network.enable');
  await send('Network.setBlockedURLs', { urls: ['https://*', 'http://fonts.*'] });
  await send('Page.navigate', { url: `${base}/` });
  await until("document.querySelectorAll('#mainshow .park-name').length === 5 && document.querySelectorAll('#sideNav li[onclick]').length >= 2 && window.jQuery.active === 0");
  assert.equal(await evaluate("document.querySelectorAll('#sideNav li[onclick]').length"), 2);
  await evaluate("document.querySelector('#mainshow .park-name').click()");
  await until("document.querySelector('#park_all_cp') && window.jQuery.active === 0");
  await evaluate("document.querySelector('#btn_backy').click()");
  await until("document.querySelectorAll('#mainshow .park-name').length === 5 && window.jQuery.active === 0");
  await evaluate("goto('s5')");
  await until("document.querySelectorAll('#mainshow .keth-name').length === 18 && window.jQuery.active === 0");
  await evaluate("document.querySelector('#mainshow .keth-name').click()");
  await until("document.querySelector('#park_all_cp') && window.jQuery.active === 0");
  await evaluate("document.querySelector('#btn_backy').click()");
  await until("document.querySelectorAll('#mainshow .keth-name').length === 18 && window.jQuery.active === 0");
  await evaluate("goto('a6m')");
  await until("document.querySelector('#mainshow .vmainbox') && window.jQuery.active === 0");
  await evaluate('readCityplanDetail(1)');
  await until("document.querySelector('#modal-rcp-content').textContent.trim().length > 50 && window.jQuery.active === 0");
  // PHP warnings in legacy pages are separate from missing/failed endpoints.
  // The unchanged AdminLTE sample dashboard tries to render a missing sample
  // chart on the demo's index. Baseline verification records this same error.
  const knownExceptions = exceptions.filter(error => error.exception?.description?.startsWith('Error: Graph container element not found') && error.stackTrace?.callFrames?.some(frame => frame.url.endsWith('/dist/js/pages/dashboard.js')));
  const favicon404 = failures.filter(response => response.url === `${base}/favicon.ico` && response.status === 404);
  assert.deepEqual(failures.filter(response => !favicon404.includes(response)), []);
  assert.deepEqual(exceptions.filter(error => !knownExceptions.includes(error)), []);
  assert.doesNotMatch(serverLog, /PHP (?:Fatal|Parse) error|Failed opening required|Failed to open stream: No such file/i);
  console.log(JSON.stringify({ mode: baseline ? 'before-cleanup' : 'published-only', phpLint: tracked.filter(file => file.endsWith('.php')).length, archivedFilesAbsent: baseline ? 0 : archived.size, isolatedFiles: tracked.length, passed: ['setup', 'index', 'region drilldown/back', 'inspectorate drilldown/back', 'tree report', 'plan detail modal'], unexpectedHttpFailures: failures.length - favicon404.length, unexpectedJavascriptExceptions: exceptions.length - knownExceptions.length, existingDemoIssues: { adminlteSampleChart: knownExceptions.length, favicon404: favicon404.length, phpWarnings: (serverLog.match(/PHP Warning:/g) ?? []).length } }, null, 2));
} finally {
  socket?.close();
  for (const task of pending.values()) clearTimeout(task.timer);
  await stop(browser); await stop(server);
  // The only recursive cleanup is our verified mkdtemp directory.
  const parent = path.resolve(project, '.tmp');
  assert.ok(path.resolve(temporary).startsWith(parent + path.sep));
  await rm(temporary, { recursive: true, force: true, maxRetries: 5, retryDelay: 200 });
}
