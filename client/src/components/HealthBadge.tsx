import React from 'react';
import { PlanHealth } from '../types/plan';

const HEALTH_MAP: Record<PlanHealth, { label: string; className: string }> = {
  on_track: { label: 'มีความก้าวหน้า', className: 'badge badge-green' },
  slight_delay: { label: 'ล่าช้าเล็กน้อย', className: 'badge badge-yellow' },
  moderate_delay: { label: 'ล่าช้าปานกลาง', className: 'badge badge-orange' },
  severe_delay: { label: 'ล่าช้ามาก', className: 'badge badge-red' },
  unscheduled: { label: 'ไม่ระบุวันดำเนินการ', className: 'badge badge-gray' },
};

export const HealthBadge: React.FC<{ health: PlanHealth; delayDays?: number }> = ({
  health,
  delayDays = 0,
}) => {
  const info = HEALTH_MAP[health];
  return (
    <span className={info.className}>
      {info.label}
      {delayDays > 0 ? ` (${delayDays} วัน)` : ''}
    </span>
  );
};
