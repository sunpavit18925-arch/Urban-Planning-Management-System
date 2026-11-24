import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { menuItems } from '../data/navigation';
import { PlanNote, RegionInfo, StepDefinition, UrbanPlan } from '../types/plan';
import { downloadCsv } from '../utils/export';
import { HealthBadge } from './HealthBadge';

type Note = PlanNote & { planName: string; planCode: string };
const healthLabels: Record<UrbanPlan['health'], string> = { on_track: 'มีความก้าวหน้า', slight_delay: 'ล่าช้าเล็กน้อย', moderate_delay: 'ล่าช้าปานกลาง', severe_delay: 'ล่าช้ามาก', unscheduled: 'ไม่ระบุวันดำเนินการ' };
const dateLabel = (value: string) => new Date(value).toLocaleDateString('th-TH');
function targetDate(plan: UrbanPlan, steps: StepDefinition[]) {
  if (!plan.currentStep) return '—';
  const [year, month, day] = plan.startDate.split('-').map(Number);
  const date = new Date(year > 2400 ? year - 543 : year, month - 1, day);
  date.setDate(date.getDate() + steps.filter(step => step.step <= plan.currentStep).reduce((sum, step) => sum + step.targetWeeks * 7, 0));
  return date.toLocaleDateString('th-TH');
}

export function WorkspacePage({ tab, plans, regions, steps, onOpenPlan }: {
  tab: string; plans: UrbanPlan[]; regions: RegionInfo[]; steps: StepDefinition[]; onOpenPlan: (id: number) => void;
}) {
  const [search, setSearch] = useState('');
  const [region, setRegion] = useState('');
  const [health, setHealth] = useState('');
  const [effective, setEffective] = useState('');
  const [category, setCategory] = useState('');
  const [notes, setNotes] = useState<Note[]>([]);
  const [error, setError] = useState('');
  const [notesLoading, setNotesLoading] = useState(false);
  useEffect(() => {
    if (tab !== 'notes') return;
    let active = true;
    setNotesLoading(true);
    api.getNotes().then(data => { if (active) { setNotes(data); setError(''); } })
      .catch(err => { if (active) setError(err.message); }).finally(() => { if (active) setNotesLoading(false); });
    return () => { active = false; };
  }, [tab, plans]);
  const committee = tab.startsWith('committee') ? Number(tab.replace('committee', '')) : 0;
  const zones = Array.from({ length: 18 }, (_, i) => i + 1).filter(id => (id - 1) % 7 + 1 === committee);
  const items = plans.filter(plan => {
    if (region && plan.regionId !== Number(region)) return false;
    if (health && plan.health !== health) return false;
    if (category && plan.category !== category) return false;
    if (effective && plan.effectiveStatus !== effective) return false;
    if (committee && !zones.includes(plan.kethId)) return false;
    if (tab === 'operating' && (plan.currentStep < 1 || plan.currentStep > 6 || plan.effectiveStatus !== 'ยังไม่มีผลบังคับใช้')) return false;
    if (tab === 'legislation' && (plan.currentStep < 7 || plan.effectiveStatus !== 'ยังไม่มีผลบังคับใช้')) return false;
    return `${plan.name} ${plan.code} ${plan.provinceName}`.toLowerCase().includes(search.trim().toLowerCase());
  });
  const exportPlans = () => downloadCsv('UPMS-plans.csv', [
    ['รหัส', 'ชื่อผังเมือง', 'จังหวัด', 'ภาค', 'เขตตรวจราชการ', 'ปีงบประมาณ', 'ลักษณะงาน', 'ขั้นตอน', 'สถานะความก้าวหน้า', 'ล่าช้า (วัน)', 'สถานะบังคับใช้', 'ผู้ดำเนินการ'],
    ...items.map(p => [p.code, p.name, p.provinceName, p.regionName, p.kethId, p.budgetYear, p.stage, `${p.currentStep}.${p.subStep}`, healthLabels[p.health], p.delayDays, p.effectiveStatus, p.operator]),
  ]);
  const title = menuItems.find(item => item.id === tab)?.label;
  if (tab === 'contacts') return <div className="panel"><h2>ติดต่อประสานงาน</h2><p className="muted-sub">หน่วยงานประสานข้อมูลผังเมือง</p><div className="contact-grid">
    {[
      ['สผม. ส่วน 1', '00 000 0001'], ['สผม. ส่วน 2', '00 000 0002'],
      ['สผม. ส่วน 3', '00 000 0003'], ['สผม. ส่วน 4', '00 000 0004'],
      ['ศทส. 1', '00 000 0005'], ['ศทส. 2', '00 000 0006'],
    ].map(([name, phone]) => <div className="contact-card" key={name}><h3>{name}</h3><a href={`tel:${phone.replace(/ /g, '')}`}><i className="fa fa-phone" aria-hidden="true" /> {phone}</a></div>)}
  </div></div>;
  if (tab === 'notes') {
    const visible = notes.filter(note => `${note.planName} ${note.planCode} ${note.content} ${note.author}`.toLowerCase().includes(search.trim().toLowerCase()));
    return <div className="panel"><h2>รายการหมายเหตุ</h2><p className="muted-sub">เลือกผังเมืองเพื่ออ่านหรือเพิ่มหมายเหตุ</p>
      <div className="filter-bar"><input placeholder="ค้นหาผังเมือง ผู้บันทึก หรือข้อความ..." aria-label="ค้นหาหมายเหตุ" value={search} onChange={e => setSearch(e.target.value)} /></div>
      {error && <div className="alert-error" role="alert">{error}</div>}
      {notesLoading ? <div className="loading-box">กำลังโหลดข้อมูล...</div> : <div className="table-wrap"><table className="data-table"><thead><tr><th>วันที่</th><th>ผังเมือง</th><th>หมายเหตุ</th><th>ผู้บันทึก</th></tr></thead>
        <tbody>{visible.map(note => <tr key={note.id}><td>{dateLabel(note.createdAt)}</td><td><button className="link-btn" onClick={() => onOpenPlan(note.planId)}>{note.planCode} · {note.planName}</button></td><td>{note.content}</td><td>{note.author}</td></tr>)}
          {!visible.length && <tr><td colSpan={4} className="empty-cell">ไม่พบหมายเหตุ</td></tr>}</tbody></table></div>}
    </div>;
  }
  return <div className="panel">
    <div className="panel-header"><div><h2>{title}</h2><p className="muted-sub">{items.length} ผัง{committee ? ` · รับผิดชอบเขตตรวจราชการ ${zones.join(', ')}` : ' · ข้อมูลสถานะล่าสุด'}</p></div>
      <div className="inline-controls unprintable"><button className="btn-secondary" onClick={() => window.print()}>พิมพ์รายงาน</button><button className="btn-primary" disabled={!items.length} onClick={exportPlans}>ส่งออก CSV</button></div></div>
    <div className="filter-bar unprintable">
      <input aria-label="ค้นหาผังเมือง" placeholder="ค้นหาชื่อผัง รหัส หรือจังหวัด..." value={search} onChange={e => setSearch(e.target.value)} />
      <select aria-label="ภูมิภาค" value={region} onChange={e => setRegion(e.target.value)}><option value="">ทุกภูมิภาค</option>{regions.map(r => <option key={r.id} value={r.id}>{r.name}</option>)}</select>
      <select aria-label="สถานะความก้าวหน้า" value={health} onChange={e => setHealth(e.target.value)}><option value="">ทุกสถานะความก้าวหน้า</option>{Object.entries(healthLabels).map(([key, label]) => <option key={key} value={key}>{label}</option>)}</select>
      <select aria-label="ประเภทงาน" value={category} onChange={e => setCategory(e.target.value)}><option value="">ทุกประเภทงาน</option><option value="general">งานผังเมืองทั่วไป</option><option value="m35">มาตรา 35</option><option value="evaluation">งานประเมินผล</option></select>
      <select aria-label="สถานะบังคับใช้" value={effective} onChange={e => setEffective(e.target.value)}><option value="">ทุกสถานะบังคับใช้</option>{['มีผลบังคับใช้', 'ยังไม่มีผลบังคับใช้', 'สิ้นสุดบังคับใช้'].map(label => <option key={label}>{label}</option>)}</select>
      <button className="btn-secondary" onClick={() => { setSearch(''); setRegion(''); setHealth(''); setEffective(''); setCategory(''); }}>ล้างตัวกรอง</button>
    </div>
    {error && <div className="alert-error" role="alert">{error}</div>}
    {tab === 'export' && <p className="export-description">ดาวน์โหลดข้อมูลตามตัวกรองเป็นไฟล์ CSV สำหรับเปิดใน Excel รองรับข้อความภาษาไทย</p>}
    {['status', 'progress'].includes(tab) && <div className="report-summary">
      <div><strong>{items.length}</strong><span>ผังเมืองทั้งหมด</span></div><div><strong>{items.filter(p => p.health === 'on_track').length}</strong><span>มีความก้าวหน้า</span></div>
      <div><strong>{items.filter(p => p.delayDays > 0).length}</strong><span>ต้องติดตามเร่งรัด</span></div><div><strong>{items.filter(p => p.effectiveStatus === 'มีผลบังคับใช้').length}</strong><span>มีผลบังคับใช้</span></div>
    </div>}
    {tab === 'progress' && <div className="step-timeline report-breakdown">{steps.map(step => <div className="step-pill" key={step.step}><span className="step-num">{step.step}</span><div>{step.title}<br /><strong>{items.filter(p => p.currentStep === step.step).length} ผัง</strong></div></div>)}</div>}
    {tab === 'schedule' && <p className="muted-sub">วันเป้าหมายคำนวณจากวันเริ่มงานและระยะเวลาสะสมของขั้นตอนที่กำหนดในระบบ</p>}
    <div className="table-wrap"><table className="data-table"><thead><tr><th>รหัส / ชื่อผังเมือง</th><th>จังหวัด / ภาค</th><th>ลักษณะงาน</th><th>ขั้นตอนปัจจุบัน</th><th>ความก้าวหน้า</th><th>{tab === 'schedule' ? 'เป้าหมายสิ้นสุดขั้นตอน' : 'สถานะบังคับใช้'}</th><th>ปรับปรุงล่าสุด</th></tr></thead>
      <tbody>{items.map(p => <tr key={p.id}><td><span className="code-pill">{p.code}</span><br /><button className="link-btn" onClick={() => onOpenPlan(p.id)}>{p.name}</button></td><td>{p.provinceName}<br /><small>{p.regionName} · เขต {p.kethId}</small></td><td>{p.stage}</td><td title={steps.find(step => step.step === p.currentStep)?.title}>ขั้น {p.currentStep}.{p.subStep}</td><td><HealthBadge health={p.health} delayDays={p.delayDays} /></td><td>{tab === 'schedule' ? targetDate(p, steps) : p.effectiveStatus}</td><td>{dateLabel(p.updatedAt)}</td></tr>)}
        {!items.length && <tr><td colSpan={7} className="empty-cell">ไม่พบข้อมูลตามเงื่อนไขที่เลือก</td></tr>}</tbody></table></div>
  </div>;
}
