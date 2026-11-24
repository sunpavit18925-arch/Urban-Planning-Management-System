import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { PlanHistory as HistoryEntry } from '../types/operations';
import { UrbanPlan } from '../types/plan';

export const fieldLabels: Record<string, string> = { name: 'ชื่อผังเมือง', currentStep: 'ขั้นตอน', subStep: 'ขั้นตอนย่อย', thirdStep: 'ขั้นตอนย่อย 2', health: 'ความก้าวหน้า', delayDays: 'จำนวนวันที่ล่าช้า', stage: 'ลักษณะงาน', effectiveStatus: 'ผลบังคับใช้', category: 'หมวดหมู่', trackingState: 'สถานภาพการดำเนินงาน', provinceId: 'จังหวัด', planType: 'ประเภทผัง', scope: 'ขอบเขต', owner: 'เจ้าภาพ', operator: 'ผู้ดำเนินการ', budgetYear: 'ปีงบประมาณ', isEec: 'พื้นที่ EEC', petition: 'คำร้อง', considerId: 'เหตุผลพิจารณาดำเนินการ', externalCode: 'รหัสอ้างอิง', publishedOn: 'วันประกาศใช้', expiredOn: 'วันสิ้นสุดใช้บังคับ', startDate: 'วันเริ่มดำเนินการ', nextStepPending: 'รอขั้นตอนถัดไป' };
Object.assign(fieldLabels, { provinceName: 'ชื่อจังหวัด', regionId: 'รหัสภาค', regionName: 'ชื่อภาค', kethId: 'เขตตรวจราชการ' });
const valueLabels: Record<string, string> = { on_track: 'มีความก้าวหน้า', slight_delay: 'ล่าช้าเล็กน้อย', moderate_delay: 'ล่าช้าปานกลาง', severe_delay: 'ล่าช้ามาก', unscheduled: 'ไม่ระบุวันดำเนินการ', general: 'ทั่วไป', m35: 'มาตรา 35', evaluation: 'การประเมิน', unspecified: 'ไม่ระบุ', draft_law: 'ยกร่างกฎหมาย', revisit: 'ย้อนดำเนินการ', act: 'ตามขั้นตอน พ.ร.บ.', expedite: 'เร่งรัดปรับปรุง' };
export function valueText(value: unknown) { return value === undefined || value === null ? '—' : typeof value === 'boolean' ? value ? 'ใช่' : 'ไม่ใช่' : valueLabels[String(value)] ?? String(value); }

export function PlanHistory({ planId, updatedAt }: { planId: number; updatedAt: string }) {
  const [history, setHistory] = useState<HistoryEntry[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  useEffect(() => {
    let active = true;
    setLoading(true); setError('');
    api.getPlanHistory(planId).then(value => { if (active) setHistory(value); })
      .catch(error => { if (active) setError(error.message); }).finally(() => { if (active) setLoading(false); });
    return () => { active = false; };
  }, [planId, updatedAt]);
  return <section className="section-box" aria-label="ประวัติการแก้ไขผังเมือง"><h4>ประวัติการเปลี่ยนแปลง ({history.length})</h4>
    {error && <p className="alert-error" role="alert">{error}</p>}
    {loading ? <p>กำลังโหลดประวัติ...</p> : !history.length ? <p className="muted-sub">ยังไม่มีประวัติการแก้ไขหลังเปิดใช้ระบบบันทึกประวัติ</p> : <div className="notes-list">{history.map(entry => <details key={entry.id} className="note-item"><summary>{new Date(entry.changedAt).toLocaleString('th-TH')} · {entry.changedFields.map(field => fieldLabels[field] ?? field).join(', ')}</summary><ul>{entry.changedFields.map(field => <li key={field}>{fieldLabels[field] ?? field}: {valueText(entry.before[field as keyof UrbanPlan])} → {valueText(entry.after[field as keyof UrbanPlan])}</li>)}</ul></details>)}</div>}
  </section>;
}
