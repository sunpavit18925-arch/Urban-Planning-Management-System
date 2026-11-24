import React, { useState } from 'react';
import { PlanCategory, PlanHealth, PlanStage, ProvinceInfo, UrbanPlan } from '../types/plan';

interface CreatePlanModalProps {
  provinces: ProvinceInfo[];
  initialCategory?: PlanCategory;
  onClose: () => void;
  onCreate: (payload: {
    name: string;
    provinceId: number;
    stage: PlanStage;
    category: PlanCategory;
    owner: UrbanPlan['owner'];
    operator: UrbanPlan['operator'];
    budgetYear: string;
    currentStep: number;
    health: PlanHealth;
    isEec: boolean;
  }) => Promise<void>;
}

export const CreatePlanModal: React.FC<CreatePlanModalProps> = ({
  provinces,
  initialCategory = 'general',
  onClose,
  onCreate,
}) => {
  const [name, setName] = useState('');
  const [provinceId, setProvinceId] = useState<number>(provinces[0]?.id ?? 24);
  const [stage, setStage] = useState<PlanStage>(initialCategory === 'evaluation' ? 'ประเมิน 5 ปี' : 'เปิดใหม่');
  const [category, setCategory] = useState<PlanCategory>(initialCategory);
  const [owner, setOwner] = useState<UrbanPlan['owner']>('กรม');
  const [operator, setOperator] = useState<UrbanPlan['operator']>('กรม');
  const [budgetYear, setBudgetYear] = useState('2568');
  const [currentStep, setCurrentStep] = useState(1);
  const [health, setHealth] = useState<PlanHealth>('on_track');
  const [isEec, setIsEec] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim()) {
      setError('กรุณาระบุชื่อผังเมือง');
      return;
    }
    setSubmitting(true);
    setError('');
    try {
      await onCreate({
        name,
        provinceId,
        stage,
        category,
        owner,
        operator,
        budgetYear,
        currentStep,
        health,
        isEec,
      });
      onClose();
    } catch (err) {
      setError(err instanceof Error ? err.message : 'เกิดข้อผิดพลาด');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="modal-backdrop" onClick={onClose}>
      <div className="modal-card" onClick={(e) => e.stopPropagation()}>
        <div className="modal-header">
          <h3>เพิ่มข้อมูลผังเมือง</h3>
          <button className="btn-icon" onClick={onClose} aria-label="ปิด">
            ✕
          </button>
        </div>
        <form onSubmit={handleSubmit} className="modal-body form-grid">
          {error && <div className="alert-error">{error}</div>}

          <label className="form-field full">
            <span>ชื่อผังเมือง *</span>
            <input
              type="text"
              placeholder="เช่น เมืองหัวหิน"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
            />
          </label>

          <label className="form-field">
            <span>จังหวัด</span>
            <select
              value={provinceId}
              onChange={(e) => setProvinceId(Number(e.target.value))}
            >
              {provinces.map((p) => (
                <option key={p.id} value={p.id}>
                  {p.name} (เขตฯ {p.kethId})
                </option>
              ))}
            </select>
          </label>

          <label className="form-field">
            <span>ปีงบประมาณ</span>
            <input
              type="text"
              value={budgetYear}
              onChange={(e) => setBudgetYear(e.target.value)}
            />
          </label>

          <label className="form-field">
            <span>ลักษณะงาน (Stage)</span>
            <select value={stage} onChange={(e) => setStage(e.target.value as PlanStage)}>
              <option value="เปิดใหม่">เปิดใหม่</option>
              <option value="ปรับปรุง">ปรับปรุง</option>
              <option value="ประเมิน 5 ปี">ประเมิน 5 ปี</option>
              <option value="ประเมิน 10 ปี">ประเมิน 10 ปี</option>
            </select>
          </label>

          <label className="form-field">
            <span>หมวดหมู่</span>
            <select
              value={category}
              onChange={(e) => setCategory(e.target.value as PlanCategory)}
            >
              <option value="general">ผังเมืองรวมทั่วไป</option>
              <option value="m35">ผังเมืองขั้นตอน ม.35</option>
              <option value="evaluation">ผังเมืองงานประเมินผล</option>
            </select>
          </label>

          <label className="form-field">
            <span>ขั้นตอนปัจจุบัน (1–8)</span>
            <select
              value={currentStep}
              onChange={(e) => setCurrentStep(Number(e.target.value))}
            >
              {[1, 2, 3, 4, 5, 6, 7, 8].map((s) => (
                <option key={s} value={s}>
                  ขั้นตอนที่ {s}
                </option>
              ))}
            </select>
          </label>

          <label className="form-field">
            <span>สถานะความก้าวหน้า</span>
            <select value={health} onChange={(e) => setHealth(e.target.value as PlanHealth)}>
              <option value="on_track">มีความก้าวหน้า</option>
              <option value="slight_delay">ล่าช้าเล็กน้อย</option>
              <option value="moderate_delay">ล่าช้าปานกลาง</option>
              <option value="severe_delay">ล่าช้ามาก</option>
              <option value="unscheduled">ไม่ระบุวันดำเนินการ</option>
            </select>
          </label>

          <label className="form-field">
            <span>หน่วยงานเจ้าภาพ</span>
            <select value={owner} onChange={(e) => setOwner(e.target.value as UrbanPlan['owner'])}>
              <option value="กรม">กรม</option>
              <option value="อปท.">อปท.</option>
            </select>
          </label>

          <label className="form-field">
            <span>ผู้ดำเนินการ</span>
            <select
              value={operator}
              onChange={(e) => setOperator(e.target.value as UrbanPlan['operator'])}
            >
              <option value="กรม">กรม</option>
              <option value="อปท.">อปท.</option>
              <option value="จ้างที่ปรึกษา">จ้างที่ปรึกษา</option>
              <option value="กรมร่วมอปท.">กรมร่วมอปท.</option>
            </select>
          </label>

          <label className="checkbox-field full">
            <input
              type="checkbox"
              checked={isEec}
              onChange={(e) => setIsEec(e.target.checked)}
            />
            <span>อยู่ในเขตพัฒนาพิเศษภาคตะวันออก (EEC)</span>
          </label>

          <div className="modal-footer full">
            <button type="button" className="btn-secondary" onClick={onClose}>
              ยกเลิก
            </button>
            <button type="submit" className="btn-primary" disabled={submitting}>
              {submitting ? 'กำลังบันทึก...' : 'บันทึกผังเมือง'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
