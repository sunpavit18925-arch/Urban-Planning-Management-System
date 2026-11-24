import { readFileSync, writeFileSync, mkdirSync, copyFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const project = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const php = resolve(project, '../DPT_UPMS2');
const require = createRequire(resolve(project, 'client/package.json'));
const postcss = require('postcss');
const source = readFileSync(resolve(php, 'report/tree-chart.php'), 'utf8');
const stylesheet = source.match(/<style>([\s\S]*?)<\/style>/)[1];
const css = postcss.parse(stylesheet);
css.walkRules(rule => {
  if (rule.parent.type === 'atrule' && /keyframes$/.test(rule.parent.name)) return;
  rule.selector = rule.selector.split(',').map(selector => {
    selector = selector.trim();
    if (selector === '#mainshow') return '#mainshow.tree-report-main';
    if (selector === 'body') return '.php-tree-report';
    return `.php-tree-report ${selector}`;
  }).join(', ');
});

const start = source.indexOf('<div id="data_date"');
const end = source.indexOf('<div id="old-tree"');
if (start < 0 || end < start) throw new Error('Review the PHP report structure before synchronizing.');
let html = source.slice(start, end).replace(/<!--[\s\S]*?-->/g, '');
const condition = html.indexOf('<?php if($eecY_2');
const firstStart = html.indexOf('?>', condition) + 2;
const alternative = html.indexOf('<?php }else{ ?>', firstStart);
const last = html.indexOf('<?php } ?>', alternative);
if ([condition, alternative, last].some(index => index < 0)) throw new Error('Review the PHP EEC owner branches.');
const first = html.slice(firstStart, alternative);
const second = html.slice(alternative + '<?php }else{ ?>'.length, last);
const makeTemplate = branch => {
  let result = html.slice(0, condition) + branch + html.slice(last + '<?php } ?>'.length);
  result = result.replace(/<text\b([^>]*?)onclick="(?:showCityplanX|showEvaX)\('([^']+)'\)"([^>]*)>[\s\S]*?<\/text>/g,
    (_match, before, key, after) => `<text${before}data-tree-filter="${key}" role="link" tabindex="0"${after}>{{${key}}}</text>`);
  result = result.replace(/onclick\s*=\s*"MyHead\(\);"/g, 'data-tree-help="true" role="button" tabindex="0"');
  result = result.replace(/<\?(?:php|=)([\s\S]*?)\?>/g, (_match, code) => {
    if (code.includes('$mainbox')) return '{{all}}';
    if (code.includes('$sub_province')) return '{{province}}';
    if (code.includes('$sub_eec')) return '{{regionalEec}}';
    if (code.includes('$dates')) return '<b>ข้อมูล ณ วันที่ {{date}}</b>';
    throw new Error(`Unmapped PHP report expression: ${code.slice(0, 100)}`);
  });
  if (/<\?|\bon\w+\s*=|<script/i.test(result)) throw new Error('Unexpected executable code in generated report HTML.');
  return result;
};
const output = '// Generated from DPT_UPMS2/report/tree-chart.php by scripts/sync-tree-report.mjs.\n' +
  `export const TREE_REPORT_CSS = ${JSON.stringify(css.toString())};\n` +
  `export const TREE_REPORT_SINGLE_OWNER_HTML = ${JSON.stringify(makeTemplate(first))};\n` +
  `export const TREE_REPORT_BOTH_OWNERS_HTML = ${JSON.stringify(makeTemplate(second))};\n`;
mkdirSync(resolve(project, 'client/src/data'), { recursive: true });
writeFileSync(resolve(project, 'client/src/data/treeReportTemplate.ts'), output);
for (const file of ['swipe-1.gif', 'clickX.gif']) {
  const destination = resolve(project, 'client/public/php/img', file);
  mkdirSync(dirname(destination), { recursive: true }); copyFileSync(resolve(php, 'img', file), destination);
}
console.log('Synchronized the original PHP tree report layout, scoped stylesheet and guide images.');
