import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { ModuleRecord, ReportGroup } from '../types/modules';
import { UrbanPlan } from '../types/plan';
import { downloadCsv } from '../utils/export';

const dimensions = { budget: 'ปีงบประมาณ', type: 'ประเภทผัง', scope: 'ขอบเขต', stage: 'ลักษณะงาน', effective: 'ผลบังคับใช้', owner: 'เจ้าภาพ', operator: 'ผู้ดำเนินการ', province: 'จังหวัด', region: 'ภาค', step: 'ขั้นตอน', health: 'ความก้าวหน้า', eec: 'EEC', category: 'ประเภทงาน' };
export function ReportCatalogPage({ onOpenPlan }: { onOpenPlan: (id: number) => void }) {
  const [dimension, setDimension] = useState('budget');
  const [snapshot, setSnapshot] = useState('');
  const [snapshots, setSnapshots] = useState<ModuleRecord[]>([]);
  const [groups, setGroups] = useState<ReportGroup[]>([]);
  const [plans, setPlans] = useState<UrbanPlan[]>([]);
  const [selected, setSelected] = useState<string | null>(null);
  const [search, setSearch] = useState('');
  const [name, setName] = useState('');
  const [asOf, setAsOf] = useState(new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Bangkok', year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date()));
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [chart, setChart] = useState(false);
  useEffect(() => { let active = true; setLoading(true); setError(''); setSelected(null);
    Promise.all([api.getReportCatalog(dimension, snapshot ? Number(snapshot) : undefined, search), api.getSnapshots()]).then(([report, stored]) => {
      if (active) { setGroups(report.groups); setPlans(report.plans); setSnapshots(stored); }
    }).catch(error => { if (active) setError(error.message); }).finally(() => { if (active) setLoading(false); });
    return () => { active = false; };
  }, [dimension, snapshot, search]);
  async function capture(event: React.FormEvent) {
    event.preventDefault(); setSaving(true); setError('');
    try { const result = await api.createSnapshot(name, asOf); setSnapshots(await api.getSnapshots()); setSnapshot(String(result.id)); setName(''); }
    catch (error) { setError(error instanceof Error ? error.message : 'บันทึกชุดข้อมูลไม่ได้'); }
    finally { setSaving(false); }
  }
  const visible = selected === null ? [] : plans.filter(plan => groups.find(group => group.key === selected)?.planIds.includes(plan.id));
  return <div className="panel report-catalog"><div className="panel-header"><div><h2>รายงานแยกประเภทและชุดข้อมูลสถานภาพ</h2><p className="muted-sub">รายงานจากข้อมูลปัจจุบันหรือชุดข้อมูลที่บันทึกไว้ พร้อมเปิดรายการผังและพิมพ์</p></div><button className="btn-secondary" onClick={() => window.print()}>พิมพ์รายงาน</button></div>
    <div className="filter-bar"><label>จำแนกตาม<select aria-label="ประเภทรายงาน" value={dimension} onChange={event => setDimension(event.target.value)}>{Object.entries(dimensions).map(([key, label]) => <option key={key} value={key}>{label}</option>)}</select></label><label>ชุดข้อมูล<select aria-label="ชุดข้อมูลสถานภาพ" value={snapshot} onChange={event => setSnapshot(event.target.value)}><option value="">ข้อมูลปัจจุบัน</option>{snapshots.map(record => <option key={record.id} value={record.id}>{String(record.data.name)} · {String(record.data.asOf)}</option>)}</select></label><input aria-label="ค้นหาในรายงาน" placeholder="ค้นหาชื่อผัง รหัส หรือจังหวัด" value={search} onChange={event => setSearch(event.target.value)} /><button className="btn-secondary" onClick={() => setChart(value => !value)}>{chart ? 'แสดงตาราง' : 'แสดงกราฟ'}</button><button className="btn-secondary" onClick={() => downloadCsv('UPMS-report.csv', [['รายการ', 'จำนวน'], ...groups.map(group => [group.label, group.count])])}>ส่งออก CSV</button></div>
    {error && <div className="alert-error" role="alert">{error}</div>}
    {loading ? <div className="loading-box">กำลังสร้างรายงาน...</div> : <><p>รวม {plans.length} ผัง</p>{chart ? <div className="catalog-chart">{groups.map(group => <button key={group.key} onClick={() => setSelected(group.key)}><span>{group.label}</span><span className="catalog-bar" style={{ width: `${100 * group.count / Math.max(1, ...groups.map(item => item.count))}%` }}>{group.count}</span></button>)}</div> : <div className="table-wrap"><table className="data-table"><thead><tr><th>{dimensions[dimension as keyof typeof dimensions]}</th><th>จำนวนผัง</th></tr></thead><tbody>{groups.map(group => <tr key={group.key}><td>{group.label}</td><td><button className="link-btn" onClick={() => setSelected(group.key)}>{group.count}</button></td></tr>)}</tbody><tfoot><tr><th>รวม</th><th>{plans.length}</th></tr></tfoot></table></div>}
      {selected !== null && <section className="section-box"><h3>{groups.find(group => group.key === selected)?.label} · {visible.length} ผัง</h3><div className="table-wrap"><table className="data-table"><thead><tr><th>รหัส</th><th>ผังเมือง</th><th>จังหวัด</th><th>ขั้นตอน</th></tr></thead><tbody>{visible.map(plan => <tr key={plan.id}><td>{plan.code}</td><td>{snapshot ? plan.name : <button className="link-btn" onClick={() => onOpenPlan(plan.id)}>{plan.name}</button>}</td><td>{plan.provinceName}</td><td>{plan.currentStep}.{plan.subStep}</td></tr>)}</tbody></table></div>{snapshot && <p>รายการนี้แสดงค่าที่เก็บไว้ในชุดข้อมูล โดยไม่แทนที่ข้อมูลปัจจุบัน</p>}</section>}</>}
    <details className="section-box unprintable"><summary>บันทึกชุดข้อมูลสถานภาพ</summary><p>เก็บข้อมูลปัจจุบันพร้อมวันที่อ้างอิงและเวลาที่บันทึกจริง ใช้เปรียบเทียบรายงานภายหลัง</p><form className="inline-controls" onSubmit={capture}><input aria-label="ชื่อชุดข้อมูล" required maxLength={250} value={name} onChange={event => setName(event.target.value)} placeholder="ชื่อชุดข้อมูล" /><input aria-label="วันที่อ้างอิงข้อมูล" type="date" required value={asOf} onChange={event => setAsOf(event.target.value)} /><button className="btn-primary" disabled={saving}>บันทึกชุดข้อมูลปัจจุบัน</button></form></details>
  </div>;
}
