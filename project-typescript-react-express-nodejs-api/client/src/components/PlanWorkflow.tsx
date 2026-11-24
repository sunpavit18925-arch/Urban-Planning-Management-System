import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { ModuleRecord, WorkflowStatus } from '../types/modules';
import { HealthBadge } from './HealthBadge';

export function PlanWorkflow({ planId, onNavigate }: { planId: number; onNavigate: (kind: string) => void }) {
  const [data, setData] = useState<{ cases: ModuleRecord[]; entries: Array<ModuleRecord & { status: WorkflowStatus }>; schedules: ModuleRecord[] } | null>(null);
  const [error, setError] = useState('');
  useEffect(() => { let active = true; api.getWorkflow(planId).then(result => { if (active) setData(result); }).catch(error => { if (active) setError(error.message); }); return () => { active = false; }; }, [planId]);
  return <details className="section-box"><summary>งานแก้ไข/ประเมิน ขั้นตอนย่อย และแผนดำเนินงาน</summary>{error && <p className="alert-error" role="alert">{error}</p>}
    <div className="inline-controls">{[['workflowCases', 'จัดการงาน ม.35 / ประเมิน'], ['progressEntries', 'บันทึกขั้นตอนและวันที่'], ['planSchedules', 'จัดทำแผนดำเนินงาน']].map(([kind, label]) => <button key={kind} className="btn-secondary" onClick={() => onNavigate(kind)}>{label}</button>)}</div>
    {data && <><p>งานแก้ไข/ประเมิน {data.cases.length} รายการ · บันทึกขั้นตอน {data.entries.length} รายการ · แผนดำเนินงาน {data.schedules.length} แผน</p>
      {data.entries.map(entry => <div className="note-item" key={entry.id}><strong>{String(entry.data.name)}</strong><p>{String(entry.data.start || 'ไม่ระบุวันเริ่ม')} → {String(entry.data.stop || 'อยู่ระหว่างดำเนินการ')} · เป้าหมาย {entry.status.deadline ?? '—'}</p><HealthBadge health={entry.status.health} delayDays={entry.status.delayDays} /><p>{String(entry.data.note)}</p></div>)}</>}
  </details>;
}
