import React, { useCallback, useEffect, useRef, useState } from 'react';
import { api } from '../api/client';
import type { ProvinceInfo, StepDefinition } from '../types/plan';
import { TreeReportList } from './TreeReportList';
import { PlanDetailModal } from './PlanDetailModal';

type Report = Awaited<ReturnType<typeof api.getStatusReport>>;
type Entry = Report['panels'][string][number];
type ChartInstance = { destroy: () => void };
type ChartConstructor = { new (canvas: HTMLCanvasElement, config: unknown): ChartInstance; defaults: { font: { family: string; size: number; weight: string } } };
let chartLoader: Promise<ChartConstructor> | undefined;
function loadChart() {
  if (!chartLoader) chartLoader = new Promise<ChartConstructor>((resolve, reject) => {
    const existing = (window as unknown as { Chart?: ChartConstructor }).Chart;
    if (existing) { resolve(existing); return; }
    const script = document.createElement('script'); script.src = '/php/ChartJs/ChartJs.js';
    script.onload = () => resolve((window as unknown as { Chart: ChartConstructor }).Chart);
    script.onerror = () => { chartLoader = undefined; script.remove(); reject(new Error('ไม่สามารถโหลดกราฟได้')); };
    document.head.appendChild(script);
  });
  return chartLoader;
}

function BarChart({ entries, onSelect, label, borderColor }: { entries: Entry[]; onSelect: (entry: Entry) => void; label: string; borderColor: string }) {
  const canvas = useRef<HTMLCanvasElement>(null);
  const select = useRef(onSelect); select.current = onSelect;
  const [error, setError] = useState('');
  useEffect(() => {
    let active = true; let instance: ChartInstance | undefined;
    Promise.all([loadChart(), document.fonts.ready]).then(([Chart]) => {
      if (!active || !canvas.current) return;
      Chart.defaults.font.family = 'Kanit'; Chart.defaults.font.size = 15; Chart.defaults.font.weight = '500';
      instance = new Chart(canvas.current, { type: 'bar', data: { labels: entries.map(entry => entry.label), datasets: [{ label: 'จำนวน', data: entries.map(entry => entry.count), backgroundColor: entries.map(entry => entry.color), borderColor: entries.map(entry => entry.color), borderWidth: 1 }] },
        options: { responsive: true, maintainAspectRatio: false, animation: false, layout: { padding: { top: 22 } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'จำนวน' } }, x: { ticks: { autoSkip: false, maxRotation: 50 } } }, plugins: { legend: { display: false } },
          onClick: (_event: unknown, elements: Array<{ index: number }>) => { if (elements[0]) select.current(entries[elements[0].index]); } },
        plugins: [{ id: 'upmsValues', afterDatasetsDraw: (chart: { ctx: CanvasRenderingContext2D; getDatasetMeta: (index: number) => { data: Array<{ x: number; y: number }> } }) => {
          const { ctx } = chart; ctx.save(); ctx.font = '17px Kanit'; ctx.textAlign = 'center'; ctx.fillStyle = '#111';
          chart.getDatasetMeta(0).data.forEach((bar, index) => ctx.fillText(String(entries[index].count), bar.x, bar.y - 6)); ctx.restore();
        } }],
      });
      canvas.current.dataset.chartReady = 'true';
    }).catch(err => { if (active) setError(err.message); });
    return () => { active = false; instance?.destroy(); };
  }, [entries]);
  return <div className="box native-chart-box" style={{ borderTopColor: borderColor }}>
    {error && <p role="alert">{error}</p>}<div className="native-chart-surface"><canvas ref={canvas} role="img" aria-label={`${label}: ${entries.map(entry => `${entry.label} ${entry.count} ผัง`).join(', ')}`} /></div>
  </div>;
}

function ChartPanel({ title, name, entries, borderColor = 'gray', onSelect, initialTable = false }: { title: string; name: string; entries: Entry[]; borderColor?: string; onSelect: (entry: Entry) => void; initialTable?: boolean }) {
  const [table, setTable] = useState(initialTable);
  return <section className="native-chart-panel" data-chart-panel={name}>
    <div className="native-chart-heading"><span>{title}</span><button className="pull-right btn btn-md btn-default unprintable" aria-label={`${table ? 'แสดงกราฟ' : 'แสดงตาราง'} ${title}`} onClick={() => setTable(value => !value)}><i className={`fa fa-${table ? 'pie-chart' : 'list'}`} aria-hidden="true" /></button></div>
    {table ? <div className="table-responsive"><table className="table table-bordereds native-chart-table"><thead><tr><th>รายการ</th><th>จำนวนผัง</th></tr></thead><tbody>{entries.map(entry => <tr key={entry.key}><td><button className="native-report-link" onClick={() => onSelect(entry)}>{entry.label}</button></td><td><button className="native-report-link" data-chart-key={entry.key} onClick={() => onSelect(entry)}>{entry.count}</button></td></tr>)}<tr><th>รวม</th><th>{entries.reduce((total, entry) => total + entry.count, 0)}</th></tr></tbody></table></div> : <BarChart entries={entries} onSelect={onSelect} label={title} borderColor={borderColor} />}
  </section>;
}

export function StatusReportPage({ current = false, onBack }: { current?: boolean; onBack: () => void }) {
  const root = current ? 'status' : 'dashboard';
  const [data, setData] = useState<Report | null>(null);
  const [steps, setSteps] = useState<StepDefinition[]>([]);
  const [provinces, setProvinces] = useState<ProvinceInfo[]>([]);
  const [error, setError] = useState('');
  const [retry, setRetry] = useState(0);
  const [selection, setSelection] = useState(() => window.location.hash.slice(1).split('/').slice(1));
  const [planId, setPlanId] = useState<number | null>(null);
  const refresh = useCallback(async () => {
    const [report, meta] = await Promise.all([api.getStatusReport(), api.getMeta()]);
    setData(report); setSteps(meta.steps); setProvinces(meta.provinces); setError('');
  }, []);
  useEffect(() => {
    let active = true;
    Promise.all([api.getStatusReport(), api.getMeta()]).then(([report, meta]) => { if (active) { setData(report); setSteps(meta.steps); setProvinces(meta.provinces); setError(''); } }).catch(err => { if (active) setError(err.message); });
    return () => { active = false; };
  }, [retry]);
  useEffect(() => {
    const handle = () => { setSelection(window.location.hash.slice(1).split('/').slice(1)); setPlanId(null); };
    window.addEventListener('hashchange', handle); return () => window.removeEventListener('hashchange', handle);
  }, []);
  const go = (parts: string[]) => { window.location.hash = [root, ...parts].join('/'); setSelection(parts); };
  if (error) return <div className="alert-error" role="alert">{error} <button className="btn-sm" onClick={() => setRetry(value => value + 1)}>ลองอีกครั้ง</button></div>;
  if (!data) return <p className="loading-box">กำลังโหลดรายงาน...</p>;
  let selectedIds: number[] | undefined; let selectedTitle = '';
  if (selection[0] === 'list' || selection[0] === 'details') {
    if (current) {
      const columns = selection[1] === 'all' ? data.matrix : data.matrix.filter(column => column.key === selection[1]);
      const rows = columns.flatMap(column => column.rows.filter(row => row.key === selection[2]));
      selectedIds = selection[2] === 'all' ? columns.flatMap(column => column.planIds) : rows.flatMap(row => row.planIds);
      selectedTitle = selection[2] === 'all' ? columns[0]?.label ?? '' : rows[0]?.label ?? '';
    } else {
      const entry = data.panels[selection[1]]?.find(entry => entry.key === selection[2]); selectedIds = entry?.planIds; selectedTitle = entry?.label ?? '';
    }
  }
  const items = data.plans.filter(plan => selectedIds?.includes(plan.id));
  if (selectedIds && (!current || selection[0] === 'details')) return <TreeReportList group="analytics" sourceData={{ title: selectedTitle, total: items.length, items }} steps={steps} provinces={provinces} onBack={() => { go([]); window.scrollTo({ top: 0 }); }} onUpdated={refresh} />;
  const date = new Date(data.asOf).toLocaleDateString('th-TH', { timeZone: 'Asia/Bangkok', day: 'numeric', month: 'short', year: 'numeric' });
  const open = (panel: string) => (entry: Entry) => { go(['list', panel, entry.key]); window.scrollTo({ top: 0 }); };
  if (!current) return <div className="native-status-report">
    <div id="date_top" className="pull-right"><b>ข้อมูล ณ วันที่ {date}</b></div>
    <div id="A4" className="container-fluid"><div className="col-12"><h1 className="GHfont hidden-xs printable">รายงานสถานภาพผังเมืองรวม</h1><h2 className="GHfont hidden-sm hidden-md hidden-lg unprintable">รายงาน<br />สถานภาพผังเมืองรวม</h2><h3 className="GHfont printable">จำนวน {data.total} ผัง</h3></div><br />
      <div className="row native-report-row"><div className="col-lg-6"><ChartPanel name="status" title="สถานภาพผังเมืองรวม" entries={data.panels.status} onSelect={open('status')} /></div><div className="col-lg-6"><ChartPanel name="stage" title="ผังที่ดำเนินการในขั้นตอน" entries={data.panels.stage} onSelect={open('stage')} /></div></div>
      <div className="row native-report-row"><div className="col-xs-12 col-md-6 native-report-separator"><ChartPanel name="newSteps" title={`ผังที่อยู่ในขั้นตอนการวางและทำผัง จำนวน ${data.panels.newSteps.reduce((sum, entry) => sum + entry.count, 0)} ผัง`} entries={data.panels.newSteps} borderColor="#ff6384" onSelect={open('newSteps')} /></div><div className="col-xs-12 col-md-6"><ChartPanel name="improvementSteps" title={`ผังที่อยู่ในขั้นตอนการปรับปรุงผัง จำนวน ${data.panels.improvementSteps.reduce((sum, entry) => sum + entry.count, 0)} ผัง`} entries={data.panels.improvementSteps} borderColor="#006cff" onSelect={open('improvementSteps')} /></div></div>
      <div className="row"><div className="col-xs-12"><ChartPanel name="affiliation" title="ส่วนรับผิดชอบผัง" entries={data.panels.affiliation} initialTable onSelect={open('affiliation')} /></div></div>
    </div>
  </div>;
  const chooseCell = (column: string, row: string) => go(['list', column, row]);
  const cell = (columnIndex: number, row: string) => {
    const column = data.matrix[columnIndex]; const value = row === 'all' ? column.count : column.rows.find(entry => entry.key === row)?.count ?? 0;
    return <td key={column.key} className={`current-${column.key}`}><button className="native-report-link" data-matrix-cell={`${column.key}/${row}`} onClick={() => chooseCell(column.key, row)}>{value}</button></td>;
  };
  return <div className="geography-page dpt-geography native-current-status">
    <div id="title_page" className="col-12"><h2 className="hidden-sm hidden-xs GHfont printable">สถานภาพผังเมืองปัจจุบัน</h2><h3 className="hidden-md hidden-lg GHfont-mini unprintable">สถานภาพผังเมืองปัจจุบัน</h3><div className="row" id="info_bar"><h3 id="search_value" /></div><h4 id="print_search" /></div><br />
    <div className="container-fluid"><div className="row unprintable"><div className="col-xs-6"><button id="btn_backy" className="btn btn-danger btn-md" onClick={onBack}>ย้อนกลับ</button></div></div>
      <div className="table-responsive current-matrix-wrap"><table className="table current-matrix"><thead><tr><th>ผังเมืองรวมที่ดำเนินงาน</th><th className="current-head-expired"><button className="native-report-link" onClick={() => chooseCell('expired', 'all')}>กฎกระทรวงผังเมืองรวม<br /><span>สิ้นสุดใช้บังคับ</span><br />(ดำเนินการปรับปรุงผัง)</button></th><th className="current-head-active"><button className="native-report-link" onClick={() => chooseCell('active', 'all')}>กฎกระทรวงผังเมืองรวม<br /><span>มีผลบังคับใช้</span><br />(ดำเนินการปรับปรุงผัง)</button></th><th className="current-head-new"><button className="native-report-link" onClick={() => chooseCell('new', 'all')}>ผังเมืองรวม/ชุมชน<br /><span>เปิดพื้นที่ใหม่</span></button></th></tr></thead>
        <tbody><tr className="current-group-heading"><td>1. ตามมติคณะกรรมการ<br />&nbsp;&nbsp; ผังเมือง (ม. 110)</td>{data.matrix.map(column => <td key={column.key} className={`current-${column.key}`} />)}</tr>
          {['draft_law', 'revisit', 'act', 'expedite', 'unspecified'].map((row, index) => <tr key={row} className={`${index < 2 ? 'current-subrow' : 'current-mainrow'} ${row === 'unspecified' ? 'current-unspecified' : ''}`}><td><button className="native-report-link" onClick={() => chooseCell('all', row)}>{row === 'act' ? <>2. ตามขั้นตอนของ<br /><small>&nbsp;&nbsp; พระราชบัญญัติการผังเมือง พ.ศ. 2562</small></> : data.matrix[0].rows.find(entry => entry.key === row)?.label}</button></td>{data.matrix.map((_column, i) => cell(i, row))}</tr>)}
          <tr className="current-total"><td>รวม</td>{data.matrix.map((_column, i) => cell(i, 'all'))}</tr></tbody></table></div>
      {selectedIds && <div className="current-status-selection"><h4>{selectedTitle} จำนวน {items.length} ผัง</h4><div className="row">{items.map((plan, index) => <div className="col-xs-12 col-sm-6 col-md-3" key={plan.id}><button className="native-report-link" onClick={() => setPlanId(plan.id)}>({index + 1}) {plan.name}</button></div>)}{!items.length && <p>ไม่พบข้อมูลผังเมือง</p>}</div><div className="current-selection-actions"><button className="btn btn-success" onClick={() => go(['details', selection[1], selection[2]])}>แสดงข้อมูลเพิ่มเติม</button><button className="btn btn-danger" onClick={() => go([])}>ปิด</button></div></div>}
    </div>
    {planId !== null && <PlanDetailModal planId={planId} steps={steps} onClose={() => setPlanId(null)} onUpdated={refresh} />}
  </div>;
}
