import { UrbanPlanDatabase } from '../data/database';
import { DomainError } from './modules';
import { PlanNote } from '../types/plan';

export function validateNote(database: UrbanPlanDatabase, body: unknown, planId: number): Partial<PlanNote> {
  if (!body || typeof body !== 'object' || Array.isArray(body)) throw new DomainError('ข้อมูลหมายเหตุไม่ถูกต้อง');
  const input = body as Record<string, unknown>;
  const fields = ['author', 'content', 'categoryId', 'step', 'subStep', 'thirdStep', 'caseId', 'active'];
  if (!Object.keys(input).length || Object.keys(input).some(key => !fields.includes(key))) throw new DomainError('ฟิลด์หมายเหตุไม่ถูกต้อง');
  for (const [key, value] of Object.entries(input)) {
    if (key === 'content' || key === 'author') { if (typeof value !== 'string' || !value.trim() || value.length > (key === 'content' ? 8000 : 250)) throw new DomainError('ข้อความหมายเหตุหรือชื่อผู้บันทึกไม่ถูกต้อง'); }
    else if (key === 'active') { if (typeof value !== 'boolean') throw new DomainError('สถานะหมายเหตุไม่ถูกต้อง'); }
    else if (typeof value !== 'number' || !Number.isSafeInteger(value) || value < 0) throw new DomainError('ขั้นตอนหรือรหัสอ้างอิงไม่ถูกต้อง');
  }
  if (Number(input.step ?? 0) > 8 || Number(input.subStep ?? 0) > 99 || Number(input.thirdStep ?? 0) > 99) throw new DomainError('ขั้นตอนหมายเหตุไม่ถูกต้อง');
  if (input.categoryId && !database.getDirectory('noteCategories').some(category => category.id === input.categoryId)) throw new DomainError('ไม่พบหมวดหมู่หมายเหตุ');
  if (input.caseId && !database.listModules('workflowCases').some(record => record.id === input.caseId && record.data.planId === planId)) throw new DomainError('งานแก้ไข/ประเมินไม่ตรงกับผังเมือง');
  return input as Partial<PlanNote>;
}
