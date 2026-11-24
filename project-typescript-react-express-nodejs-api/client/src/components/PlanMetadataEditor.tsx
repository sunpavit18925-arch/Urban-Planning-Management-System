import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { UrbanPlan, ProvinceInfo } from '../types/plan';
import { DirectoryRecord } from '../data/navigation';

const inputDate = (value: string) => { const year = Number(value.slice(0, 4)); return year > 2400 ? `${year - 543}${value.slice(4)}` : value; };
export function PlanMetadataEditor({ plan, onSaved }: { plan: UrbanPlan; onSaved: () => Promise<void> }) {
  const [values, setValues] = useState(() => ({ name: plan.name, provinceId: plan.provinceId, planType: plan.planType, scope: plan.scope, owner: plan.owner, operator: plan.operator, budgetYear: plan.budgetYear, stage: plan.stage, category: plan.category, effectiveStatus: plan.effectiveStatus, isEec: plan.isEec, petition: plan.petition ?? false, nextStepPending: plan.nextStepPending ?? false, startDate: inputDate(plan.startDate), publishedOn: inputDate(plan.publishedOn ?? ''), expiredOn: inputDate(plan.expiredOn ?? ''), externalCode: plan.externalCode ?? '', considerId: plan.considerId ?? 0 }));
  const [provinces, setProvinces] = useState<ProvinceInfo[]>([]);
  const [reasons, setReasons] = useState<DirectoryRecord[]>([]);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  useEffect(() => { Promise.all([api.getMeta(), api.getDirectory('reasons')]).then(([meta, items]) => { setProvinces(meta.provinces); setReasons(items); }).catch(error => setError(error.message)); }, []);
  async function save(event: React.FormEvent) {
    event.preventDefault(); setSaving(true); setError('');
    try { await api.updatePlan(plan.id, values); await onSaved(); }
    catch (error) { setError(error instanceof Error ? error.message : 'บันทึกข้อมูลไม่ได้'); }
    finally { setSaving(false); }
  }
  const select = (key: 'planType' | 'scope' | 'owner' | 'operator' | 'stage' | 'category' | 'effectiveStatus', label: string, choices: string[]) => <label>{label}<select value={values[key]} onChange={event => setValues(previous => ({ ...previous, [key]: event.target.value }))}>{choices.map(value => <option key={value} value={value}>{value}</option>)}</select></label>;
  return <details className="section-box"><summary>แก้ไขข้อมูลทะเบียนผังเมือง</summary>{error && <div className="alert-error" role="alert">{error}</div>}<form onSubmit={save} aria-label="แก้ไขทะเบียนผังเมือง"><div className="module-form-grid">
    <label>ชื่อผังเมือง<input required maxLength={250} value={values.name} onChange={event => setValues(previous => ({ ...previous, name: event.target.value }))} /></label>
    <label>จังหวัด<select value={values.provinceId} onChange={event => setValues(previous => ({ ...previous, provinceId: Number(event.target.value) }))}>{provinces.map(province => <option key={province.id} value={province.id}>{province.name}</option>)}</select></label>
    <label>รหัสอ้างอิงเดิม<input maxLength={100} value={values.externalCode} onChange={event => setValues(previous => ({ ...previous, externalCode: event.target.value }))} /></label>
    <label>ปีงบประมาณ<input pattern="[0-9]{4}" required value={values.budgetYear} onChange={event => setValues(previous => ({ ...previous, budgetYear: event.target.value }))} /></label>
    {select('planType', 'ประเภทผัง', ['ผังเมืองรวมจังหวัด', 'ผังเมืองรวมเมือง/ชุมชน'])}{select('scope', 'ขอบเขต', ['ทั่วทั้งอำเภอ', 'เฉพาะพื้นที่'])}{select('owner', 'เจ้าภาพ', ['กรม', 'อปท.'])}{select('operator', 'ผู้ดำเนินการ', ['กรม', 'อปท.', 'จ้างที่ปรึกษา', 'กรมร่วมอปท.', 'ร่วมดำเนินการ', 'อื่นๆ', 'ไม่ระบุ'])}{select('stage', 'ลักษณะงาน', ['เปิดใหม่', 'ปรับปรุง', 'ประเมิน 5 ปี', 'ประเมิน 10 ปี'])}{select('category', 'ประเภทงาน', ['general', 'm35', 'evaluation'])}{select('effectiveStatus', 'ผลบังคับใช้', ['มีผลบังคับใช้', 'ยังไม่มีผลบังคับใช้', 'สิ้นสุดบังคับใช้'])}
    <label>เหตุผลพิจารณาดำเนินการ<select value={values.considerId} onChange={event => setValues(previous => ({ ...previous, considerId: Number(event.target.value) }))}><option value="0">ไม่ระบุ</option>{reasons.filter(item => item.active || item.id === values.considerId).map(item => <option key={item.id} value={item.id}>{item.name}</option>)}</select></label>
    {([['startDate', 'วันเริ่มดำเนินการ'], ['publishedOn', 'วันประกาศใช้'], ['expiredOn', 'วันสิ้นสุดใช้บังคับ']] as const).map(([key, label]) => <label key={key}>{label}<input type="date" value={values[key]} onChange={event => setValues(previous => ({ ...previous, [key]: event.target.value }))} /></label>)}
    {([['isEec', 'พื้นที่ EEC'], ['petition', 'มีคำร้อง'], ['nextStepPending', 'รอดำเนินการขั้นตอนถัดไป']] as const).map(([key, label]) => <label key={key}><input type="checkbox" checked={values[key]} onChange={event => setValues(previous => ({ ...previous, [key]: event.target.checked }))} /> {label}</label>)}
    </div><button className="btn-secondary" disabled={saving} type="submit">บันทึกทะเบียนผัง</button></form></details>;
}
