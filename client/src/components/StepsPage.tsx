import React, { useState } from 'react';
import { api } from '../api/client';
import { StepDefinition, UrbanPlan } from '../types/plan';

export function StepsPage({ steps, plans, onUpdated }: { steps: StepDefinition[]; plans: UrbanPlan[]; onUpdated: () => Promise<void> }) {
  const [editing, setEditing] = useState<StepDefinition | null>(null);
  const [error, setError] = useState('');
  const [saving, setSaving] = useState(false);
  const save = async (e: React.FormEvent) => {
    e.preventDefault(); if (!editing) return;
    setSaving(true); setError('');
    try { await api.updateStep(editing.step, editing.title, editing.targetWeeks); setEditing(null); await onUpdated(); }
    catch (err) { setError(err instanceof Error ? err.message : 'บันทึกไม่สำเร็จ'); }
    finally { setSaving(false); }
  };
  return <div className="panel"><div className="panel-header"><div><h2>ขั้นตอนดำเนินการ</h2><p className="muted-sub">กำหนดชื่อขั้นตอนและระยะเวลาเป้าหมายสำหรับติดตามงาน</p></div></div>
    {error && <div className="alert-error" role="alert">{error}</div>}
    <div className="table-wrap"><table className="data-table"><thead><tr><th>ขั้นตอน</th><th>รายละเอียด</th><th>เป้าหมาย (สัปดาห์)</th><th>ผังในขั้นตอนนี้</th><th>จัดการ</th></tr></thead>
      <tbody>{steps.map(step => <tr key={step.step}><td>{step.step}</td><td>{step.title}</td><td>{step.targetWeeks}</td><td>{plans.filter(p => p.currentStep === step.step).length}</td>
        <td><button className="btn-sm" onClick={() => { setError(''); setEditing({ ...step }); }}>แก้ไข</button></td></tr>)}</tbody></table></div>
    {editing && <div className="modal-backdrop"><div className="modal-card" role="dialog" aria-modal="true" aria-labelledby="step-title">
      <div className="modal-header"><h3 id="step-title">แก้ไขขั้นตอนที่ {editing.step}</h3></div><form className="modal-body form-grid" onSubmit={save}>
        {error && <div className="alert-error form-field full" role="alert">{error}</div>}
        <label className="form-field full"><span>ชื่อขั้นตอน *</span><input required value={editing.title} onChange={e => setEditing({ ...editing, title: e.target.value })} /></label>
        <label className="form-field full"><span>ระยะเวลาเป้าหมาย (สัปดาห์)</span><input required type="number" min="0" max="520" value={editing.targetWeeks} onChange={e => setEditing({ ...editing, targetWeeks: Number(e.target.value) })} /></label>
        <div className="modal-footer full"><button className="btn-secondary" type="button" disabled={saving} onClick={() => setEditing(null)}>ยกเลิก</button><button className="btn-primary" disabled={saving}>{saving ? 'กำลังบันทึก...' : 'บันทึก'}</button></div>
      </form></div></div>}
  </div>;
}
