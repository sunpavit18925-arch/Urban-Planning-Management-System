import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { PlanHealth, PlanNote, StepDefinition, UrbanPlan } from '../types/plan';
import { HealthBadge } from './HealthBadge';

interface PlanDetailModalProps {
  planId: number;
  steps: StepDefinition[];
  onClose: () => void;
  onUpdated: () => Promise<void>;
}

export const PlanDetailModal: React.FC<PlanDetailModalProps> = ({
  planId,
  steps,
  onClose,
  onUpdated,
}) => {
  const [plan, setPlan] = useState<UrbanPlan | null>(null);
  const [notes, setNotes] = useState<PlanNote[]>([]);
  const [loading, setLoading] = useState(true);
  const [step, setStep] = useState(1);
  const [health, setHealth] = useState<PlanHealth>('on_track');
  const [trackingState, setTrackingState] = useState<NonNullable<UrbanPlan['trackingState']>>('unspecified');
  const [noteText, setNoteText] = useState('');
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  const loadDetail = async () => {
    setLoading(true);
    setError('');
    try {
      const res = await api.getPlanDetail(planId);
      setPlan(res.plan);
      setNotes(res.notes);
      setStep(res.plan.currentStep);
      setHealth(res.plan.health);
      setTrackingState(res.plan.trackingState ?? 'unspecified');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'ไม่สามารถโหลดรายละเอียดผังเมืองได้');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadDetail();
  }, [planId]);

  const handleSaveProgress = async () => {
    if (!plan) return;
    setSaving(true);
    try {
      const delayDays =
        health === 'slight_delay'
          ? 12
          : health === 'moderate_delay'
            ? 36
            : health === 'severe_delay'
              ? 72
              : 0;
      await api.updatePlan(plan.id, {
        currentStep: step,
        health,
        delayDays,
        trackingState,
      });
      await loadDetail();
      await onUpdated();
    } catch (err) {
      setError(err instanceof Error ? err.message : 'ไม่สามารถบันทึกสถานะได้');
    } finally {
      setSaving(false);
    }
  };

  const handleAddNote = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!plan || !noteText.trim()) return;
    setSaving(true);
    try {
      await api.addNote(plan.id, 'เจ้าหน้าที่ผังเมือง', noteText);
      setNoteText('');
      await loadDetail();
      await onUpdated();
    } catch (err) {
      setError(err instanceof Error ? err.message : 'ไม่สามารถเพิ่มหมายเหตุได้');
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="modal-backdrop" onClick={onClose}>
      <div className="modal-card modal-wide" onClick={(e) => e.stopPropagation()}>
        <div className="modal-header">
          <div>
            <h3>{plan ? `${plan.code} — ${plan.name}` : 'กำลังโหลด...'}</h3>
            {plan && (
              <p className="muted-sub">
                จังหวัด{plan.provinceName} • {plan.regionName} • เขตตรวจราชการที่ {plan.kethId}
              </p>
            )}
          </div>
          <button className="btn-icon" onClick={onClose} aria-label="ปิด">
            ✕
          </button>
        </div>

        <div className="modal-body">
          {error && <div className="alert-error" role="alert">{error}</div>}
          {loading ? (
            <div className="loading-box">กำลังโหลดรายละเอียดผังเมือง...</div>
          ) : !plan ? null : (
            <>
              <div className="detail-meta-grid">
                <div>
                  <span className="meta-label">สถานะปัจจุบัน</span>
                  <div>
                    <HealthBadge health={plan.health} delayDays={plan.delayDays} />
                  </div>
                </div>
                <div>
                  <span className="meta-label">ลักษณะงาน / ปีงบ</span>
                  <strong>
                    {plan.stage} ({plan.budgetYear})
                  </strong>
                </div>
                <div>
                  <span className="meta-label">เจ้าภาพ / ผู้ดำเนินการ</span>
                  <strong>
                    {plan.owner} / {plan.operator}
                  </strong>
                </div>
                <div>
                  <span className="meta-label">ผลบังคับใช้</span>
                  <strong>{plan.effectiveStatus}</strong>
                </div>
              </div>

              <div className="section-box">
                <h4>อัปเดตความก้าวหน้า</h4>
                <div className="inline-controls">
                  <select value={step} onChange={(e) => setStep(Number(e.target.value))}>
                    {steps.map((s) => (
                      <option key={s.step} value={s.step}>
                        ขั้นที่ {s.step}: {s.title}
                      </option>
                    ))}
                  </select>
                  <select
                    value={health}
                    onChange={(e) => setHealth(e.target.value as PlanHealth)}
                  >
                    <option value="on_track">มีความก้าวหน้า</option>
                    <option value="slight_delay">ล่าช้าเล็กน้อย</option>
                    <option value="moderate_delay">ล่าช้าปานกลาง</option>
                    <option value="severe_delay">ล่าช้ามาก</option>
                    <option value="unscheduled">ไม่ระบุวันดำเนินการ</option>
                  </select>
                  <select aria-label="สถานภาพการดำเนินงาน" value={trackingState} onChange={e => setTrackingState(e.target.value as NonNullable<UrbanPlan['trackingState']>)}>
                    <option value="unspecified">ไม่ระบุสถานภาพการดำเนินงาน</option><option value="draft_law">ยกร่างกฎหมาย</option><option value="revisit">ย้อนดำเนินการ</option><option value="act">ตามขั้นตอนของ พ.ร.บ. การผังเมือง พ.ศ. 2562</option><option value="expedite">เร่งรัดปรับปรุง</option>
                  </select>
                  <button
                    className="btn-primary"
                    onClick={handleSaveProgress}
                    disabled={saving}
                  >
                    บันทึกสถานะ
                  </button>
                </div>
              </div>

              <div className="section-box">
                <h4>ไทม์ไลน์ขั้นตอนดำเนินการ (8 ขั้นตอน)</h4>
                <div className="step-timeline">
                  {steps
                    .filter((s) => s.step > 0)
                    .map((s) => {
                      const state =
                        s.step < plan.currentStep
                          ? 'done'
                          : s.step === plan.currentStep
                            ? 'active'
                            : 'pending';
                      return (
                        <div key={s.step} className={`step-pill ${state}`}>
                          <span className="step-num">{s.step}</span>
                          <span className="step-title">{s.title}</span>
                        </div>
                      );
                    })}
                </div>
              </div>

              <div className="section-box">
                <h4>หมายเหตุ / บันทึกการติดตาม ({notes.length})</h4>
                <form onSubmit={handleAddNote} className="inline-controls">
                  <input
                    type="text"
                    placeholder="เพิ่มหมายเหตุใหม่..."
                    value={noteText}
                    onChange={(e) => setNoteText(e.target.value)}
                  />
                  <button type="submit" className="btn-secondary" disabled={saving}>
                    เพิ่มหมายเหตุ
                  </button>
                </form>
                <div className="notes-list">
                  {notes.length === 0 ? (
                    <p className="muted-sub">ยังไม่มีหมายเหตุสำหรับผังเมืองนี้</p>
                  ) : (
                    notes.map((n) => (
                      <div key={n.id} className="note-item">
                        <div className="note-top">
                          <strong>{n.author}</strong>
                          <span>{new Date(n.createdAt).toLocaleString('th-TH')}</span>
                        </div>
                        <p>{n.content}</p>
                      </div>
                    ))
                  )}
                </div>
              </div>
            </>
          )}
        </div>
      </div>
    </div>
  );
};
