import { readFileSync, existsSync, mkdirSync } from 'node:fs';
import { execFileSync } from 'node:child_process';
import path from 'node:path';

export function createGeographyReference(root, plans, mode = 'geography') {
  const php = process.env.PHP_PATH || 'C:/xampp/php/php.exe';
  const source = path.resolve(root, '../DPT_UPMS2');
  if (!existsSync(php) || !existsSync(source)) return null;
  mkdirSync(path.join(root, '.tmp'), { recursive: true });
  const rendered = JSON.parse(execFileSync(php, [path.join(root, 'scripts/reference-geography.php'), source, mode], {
    input: JSON.stringify(plans), encoding: 'utf8', windowsHide: true,
  }));
  const index = readFileSync(path.join(source, 'index.php'), 'utf8');
  const header = index.match(/<header class="main-header">[\s\S]*?<\/header>/)[0];
  const inlineStyle = index.match(/<style>([\s\S]*?)<\/style>/)[1];
  const counts = Array.from({ length: 5 }, (_, i) => {
    const scoped = plans.filter(p => p.regionId === i + 1);
    return [1, 2, 3, 4, 5, 6, 7, 8].map(status => scoped.filter(p => {
      if (status === 7) return Boolean(p.nextStepPending);
      if (status === 8) return p.currentStep === 8 && [2, 4, 5, 6, 8].includes(p.subStep);
      if (!p.currentStep || !p.subStep) return status === 1;
      return ['on_track', 'slight_delay', 'moderate_delay', 'severe_delay', 'unscheduled'][status - 2] === p.health;
    }).length);
  });
  return `<!doctype html><html lang="th"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="icon" href="data:,"><base href="/php/">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css"><link rel="stylesheet" href="font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="dist/style.css"><link rel="stylesheet" href="dist/skin-green-light.css">
    <style>@font-face { font-family: Kanit; font-style: normal; font-weight: 300; src: url('/fonts/kanit/Kanit-Light.ttf') format('truetype'); } ${inlineStyle}</style>
    </head><body class="hold-transition skin-green-light sidebar-mini"><div class="wrapper" style="font-family:Kanit;overflow:hidden">
    ${header}<aside class="main-sidebar" id="sideNav">${rendered.sidebar.replace(/<link\b[^>]*>/g, '')}</aside>
    <div class="content-wrapper"><section class="content"><section id="mainshow" class="content">${rendered.content.replaceAll('src="img/', 'src="/php/img/')}</section><section id="extrashow"></section></section></div>
    </div><script src="/__reference/jquery.js"></script><script src="/__reference/adminlte.js"></script><script>
      document.querySelector('#demo').textContent = 'UPMS'; document.querySelector('#demo2').textContent = 'UPMS';
      document.querySelector('.t-manage > .treeview-menu').style.display = 'block';
      ${mode === 'geography' ? `const counts = ${JSON.stringify(counts)};
      counts.forEach((statuses, i) => statuses.forEach((count, j) => { document.getElementById('s'+(j+1)+'p'+(i+1)).textContent=count; }));` : ''}
    </script></body></html>`;
}

export const createTreeReference = (root, plans) => createGeographyReference(root, plans, 'tree');
