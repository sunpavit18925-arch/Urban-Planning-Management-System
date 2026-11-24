import { execFileSync } from 'node:child_process';
import { createHash } from 'node:crypto';
import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const project = fileURLToPath(new URL('../', import.meta.url));
const repo = path.resolve(project, '..');
const manifestPath = path.join(repo, 'php/docs/runtime-files.json');
const extensions = /\.(?:php|js|mjs|html|css)$/i;
// Include the report explicitly documented as a standalone PHP demonstration.
const entries = ['php/index.php', 'php/mock_db_setup.php', 'php/report/tree-chart.php'];

/** Remove comments without erasing URLs, quoted strings or PHP string payloads. */
function uncomment(source) {
  let output = ''; let quote = ''; let index = 0;
  while (index < source.length) {
    const ch = source[index];
    if (quote) {
      output += ch;
      if (ch === '\\' && index + 1 < source.length) { output += source[++index]; }
      else if (ch === quote) quote = '';
      index++; continue;
    }
    if (ch === '"' || ch === "'" || ch === '`') { quote = ch; output += ch; index++; continue; }
    if (source.startsWith('<!--', index)) { const end = source.indexOf('-->', index + 4); index = end < 0 ? source.length : end + 3; output += ' '; continue; }
    if (source.startsWith('/*', index)) { const end = source.indexOf('*/', index + 2); index = end < 0 ? source.length : end + 2; output += ' '; continue; }
    if (source.startsWith('//', index) || ch === '#') { const end = source.indexOf('\n', index); index = end < 0 ? source.length : end; output += ' '; continue; }
    output += ch; index++;
  }
  return output;
}

export function auditPhpRuntime() {
  const tracked = execFileSync('git', ['ls-files', '-z', '--', 'php'], { cwd: repo, encoding: 'utf8', windowsHide: true }).split('\0').filter(Boolean);
  const trackedSet = new Set(tracked);
  const caseMap = new Map(tracked.map(name => [name.toLowerCase(), name]));
  const edges = new Map(); const mismatches = []; const dynamic = [];
  const resolve = (caller, literal) => {
    if (/^(?:https?:|data:|javascript:|#|mailto:|tel:)/i.test(literal)) return [];
    let clean = literal.split(/[?#]/)[0].replace(/\\/g, '/').trim();
    if (!clean || /[<>\r\n]/.test(clean)) return [];
    try { clean = decodeURIComponent(clean); } catch {}
    const choices = [path.posix.normalize(path.posix.join(path.posix.dirname(caller), clean)), path.posix.normalize(path.posix.join('php', clean.replace(/^\//, '')))];
    return [...new Set(choices.flatMap(candidate => {
      const actual = caseMap.get(candidate.toLowerCase());
      if (actual && actual !== candidate) mismatches.push({ caller, literal, actual });
      return actual ? [actual] : [];
    }))];
  };
  for (const caller of tracked.filter(name => extensions.test(name))) {
    const source = uncomment(readFileSync(path.join(repo, caller), 'utf8'));
    const references = new Set();
    // Both PHP include paths and browser AJAX paths are resolved conservatively.
    for (const match of source.matchAll(/["'`]([^"'`\r\n]*?\.(?:php|js|mjs|html|css)(?:\?[^"'`\r\n]*)?)["'`]/gi)) for (const target of resolve(caller, match[1])) references.add(target);
    // Retain every matching target of a simple variable-built PHP URL.
    for (const match of source.matchAll(/(['"])([^'"\r\n]*)\1\s*(?:\+|\.)\s*[$\w.[\]'"-]+\s*(?:\+|\.)\s*(['"])([^'"\r\n]*\.php)\3/g)) {
      const suffix = match[4]; const prefix = match[2];
      const escaped = text => text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      const patterns = [path.posix.join('php', prefix), path.posix.join(path.posix.dirname(caller), prefix)].map(start => new RegExp(`^${escaped(start)}[^/]*${escaped(suffix)}$`, 'i'));
      const targets = tracked.filter(candidate => patterns.some(pattern => pattern.test(candidate)));
      targets.forEach(target => references.add(target));
      if (targets.length) dynamic.push({ caller, expression: `${prefix}*${suffix}`, targets });
    }
    edges.set(caller, [...references]);
  }
  const reached = new Set(); const via = new Map(entries.map(name => [name, 'documented-entry'])); const queue = [...entries];
  while (queue.length) {
    const caller = queue.shift(); if (reached.has(caller)) continue;
    if (!trackedSet.has(caller)) throw new Error(`Missing supported PHP entry: ${caller}`);
    reached.add(caller);
    for (const target of edges.get(caller) ?? []) if (!reached.has(target)) { if (!via.has(target)) via.set(target, caller); queue.push(target); }
  }
  const old = existsSync(manifestPath) ? JSON.parse(readFileSync(manifestPath, 'utf8')) : null;
  const alreadyArchived = new Map((old?.archived ?? []).map(item => [item.path, item]));
  // If a file is deliberately reintroduced and gains a caller, publish it again.
  for (const name of reached) alreadyArchived.delete(name);
  const unused = tracked.filter(name => /\.php$/i.test(name) && !reached.has(name)).map(name => {
    const content = readFileSync(path.join(repo, name), 'utf8').replace(/\r\n/g, '\n');
    return { path: name, bytes: Buffer.byteLength(content), sha256: createHash('sha256').update(content).digest('hex'), blob: execFileSync('git', ['rev-parse', `HEAD:${name}`], { cwd: repo, encoding: 'utf8', windowsHide: true }).trim(),
      reason: 'No reference path from documented entries, including conservative traversal of dormant JS dispatchers.',
      legacyEvents: [...new Set([...content.matchAll(/\$_POST\[['"]event['"]\]\s*={2,3}\s*(['"])([^'"]+)\1/g)].map(match => match[2]))] };
  });
  unused.forEach(item => alreadyArchived.set(item.path, item));
  const report = {
    schemaVersion: 1, sourceCommit: old?.sourceCommit ?? execFileSync('git', ['rev-parse', 'HEAD'], { cwd: repo, encoding: 'utf8', windowsHide: true }).trim(),
    scope: 'PHP demo reachable from documented index/setup/tree-report entries. Literal includes, AJAX URLs and dormant dispatcher targets are conservatively retained; external bookmarks are outside this analysis.',
    entries, retainedPhp: tracked.filter(name => /\.php$/i.test(name) && reached.has(name)).map(name => ({ path: name, via: via.get(name) })),
    archived: [...alreadyArchived.values()].sort((a, b) => a.path.localeCompare(b.path)), dynamicReferences: dynamic.filter(item => reached.has(item.caller)),
    caseMismatches: mismatches.filter(item => reached.has(item.caller)),
  };
  return { report, unused, trackedPhp: tracked.filter(name => /\.php$/i.test(name)).length };
}

if (process.argv[1] && path.resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const { report, unused, trackedPhp } = auditPhpRuntime();
  if (process.argv.includes('--write')) { mkdirSync(path.dirname(manifestPath), { recursive: true }); writeFileSync(manifestPath, JSON.stringify(report, null, 2) + '\n'); }
  console.log(JSON.stringify({ trackedPhp, retained: report.retainedPhp.length, newlyUnused: unused.length, bytes: unused.reduce((sum, item) => sum + item.bytes, 0), unused: unused.map(item => item.path), dynamicReferences: report.dynamicReferences, caseMismatches: report.caseMismatches }, null, 2));
}
