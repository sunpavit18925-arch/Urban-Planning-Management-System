import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { PlanHistory } from '../types/operations';
import { UrbanPlan } from '../types/plan';
import { fieldLabels, valueText } from './PlanHistory';
import { downloadCsv } from '../utils/export';

export function SystemPage() {
  const [summary, setSummary] = useState<Awaited<ReturnType<typeof api.getSystemSummary>> | null>(null);
  const [error, setError] = useState('');
  useEffect(() => { api.getSystemSummary().then(setSummary).catch(error => setError(error.message)); }, []);
  return <div className="panel"><h2>สรุปการใช้งานระบบ</h2>{error && <div role="alert" className="alert-error">{error}</div>}{summary ? <><p>ข้อมูลวันที่ {summary.today} · ฐานข้อมูล {summary.database}</p><div className="report-summary">{[['ผังเมือง', summary.totalPlans], ['หมายเหตุ', summary.totalNotes], ['บัญชีที่ใช้งาน', summary.activeAccounts], ['เซสชันที่ใช้งาน', summary.activeSessions], ['เข้าสู่ระบบวันนี้', summary.visitsToday], ['ผู้ใช้งานวันนี้', summary.usersToday], ['รายการประวัติ', summary.historyEntries]].map(([label, value]) => <div key={String(label)}><strong>{value}</strong><span>{label}</span></div>)}</div><p>สถิติผู้ใช้นับจากการเข้าสู่ระบบหรือเปลี่ยนโหมดที่มีบันทึกกิจกรรมในวันนี้</p></> : <p>กำลังโหลด...</p>}</div>;
}
export function HistoryPage({ onOpenPlan }: { onOpenPlan: (id: number) => void }) {
  const [history, setHistory] = useState<PlanHistory[]>([]);
  const [search, setSearch] = useState(''); const [error, setError] = useState('');
  useEffect(() => { api.getAllHistory().then(setHistory).catch(error => setError(error.message)); }, []);
  const visible = history.filter(entry => `${entry.after.name} ${entry.after.code} ${entry.actor ?? ''}`.toLowerCase().includes(search.toLowerCase()));
  return <div className="panel"><div className="panel-header"><h2>ประวัติการเปลี่ยนแปลงทุกผัง</h2><div className="inline-controls"><button className="btn-secondary" onClick={() => window.print()}>พิมพ์</button><button className="btn-secondary" onClick={() => downloadCsv('UPMS-history.csv', [['เวลา', 'รหัสผัง', 'ชื่อผัง', 'ผู้บันทึก', 'ข้อมูลที่เปลี่ยน'], ...visible.map(entry => [entry.changedAt, entry.after.code, entry.after.name, entry.actor ?? '', entry.changedFields.map(field => fieldLabels[field] ?? field).join(', ')])])}>ส่งออก CSV</button></div></div>
    <input aria-label="ค้นหาประวัติ" placeholder="ค้นหาผังเมืองหรือผู้บันทึก" value={search} onChange={event => setSearch(event.target.value)} />{error && <div role="alert" className="alert-error">{error}</div>}
    {visible.map(entry => <details className="note-item" key={entry.id}><summary>{new Date(entry.changedAt).toLocaleString('th-TH')} · {entry.after.code} · {entry.after.name} · {entry.actor ?? 'เจ้าหน้าที่ผังเมือง'}</summary><button className="link-btn" onClick={() => onOpenPlan(entry.planId)}>เปิดผังเมือง</button><ul>{entry.changedFields.map(field => <li key={field}>{fieldLabels[field] ?? field}: {field === 'provinceId' ? entry.before.provinceName : valueText(entry.before[field as keyof UrbanPlan])} → {field === 'provinceId' ? entry.after.provinceName : valueText(entry.after[field as keyof UrbanPlan])}</li>)}</ul></details>)}
    {!visible.length && <p>ยังไม่มีประวัติตามเงื่อนไข</p>}
  </div>;
}
