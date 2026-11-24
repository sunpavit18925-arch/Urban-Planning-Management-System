import ExcelJS from 'exceljs';
import { randomBytes } from 'node:crypto';
import { Readable } from 'node:stream';
import { UrbanPlanDatabase, PROVINCES } from '../data/database';
import { ImportIssue, ImportPreview } from '../types/operations';
import { UrbanPlan } from '../types/plan';
import { createFields, planRules } from './planValidation';

type CreatePlan = Parameters<UrbanPlanDatabase['createPlan']>[0];
interface PendingImport { expires: number; inputs: CreatePlan[]; issues: ImportIssue[] }
const MAX_ROWS = 1000;
const TTL = 10 * 60 * 1000;
const headers: Record<string, string> = {
  ...Object.fromEntries(createFields.map(field => [field, field])),
  name: 'name', cp_name: 'name', 'ชื่อผังเมือง': 'name',
  provinceId: 'provinceId', cp_province: 'provinceId', 'รหัสจังหวัด': 'provinceId',
  province: 'province', 'จังหวัด': 'province',
  budgetYear: 'budgetYear', 'ปีงบประมาณ': 'budgetYear',
  stage: 'stage', 'ลักษณะงาน': 'stage', category: 'category', 'หมวดหมู่': 'category',
  owner: 'owner', 'เจ้าภาพ': 'owner', operator: 'operator', 'ผู้ดำเนินการ': 'operator',
  currentStep: 'currentStep', 'ขั้นตอน': 'currentStep', health: 'health', 'สถานะความก้าวหน้า': 'health',
  isEec: 'isEec', cp_eec: 'isEec', EEC: 'isEec',
};
const duplicateKey = (plan: { provinceId: number; name: string }) => `${plan.provinceId}:${plan.name.trim().normalize('NFC').toLocaleLowerCase('th-TH')}`;

export class SpreadsheetError extends Error {
  constructor(message: string, readonly status = 400) { super(message); }
}

/** Bound XLSX expansion before ExcelJS opens the ZIP container. */
function checkWorkbookSize(buffer: Buffer): void {
  let end = -1;
  for (let offset = buffer.length - 22; offset >= Math.max(0, buffer.length - 65557); offset--) {
    if (buffer.readUInt32LE(offset) === 0x06054b50 && offset + 22 + buffer.readUInt16LE(offset + 20) === buffer.length) { end = offset; break; }
  }
  if (end < 0) throw new SpreadsheetError('ไฟล์ Excel ไม่ถูกต้อง กรุณาใช้ .xlsx');
  const entries = buffer.readUInt16LE(end + 10);
  let offset = buffer.readUInt32LE(end + 16);
  let expanded = 0;
  if (entries > 250 || buffer.readUInt16LE(end + 4) !== 0 || buffer.readUInt16LE(end + 6) !== 0) throw new SpreadsheetError('ไฟล์ Excel มีขนาดหรือโครงสร้างเกินกำหนด');
  for (let index = 0; index < entries; index++) {
    if (offset + 46 > end || buffer.readUInt32LE(offset) !== 0x02014b50) throw new SpreadsheetError('โครงสร้างไฟล์ Excel ไม่ถูกต้อง');
    expanded += buffer.readUInt32LE(offset + 24);
    if (expanded > 30 * 1024 * 1024 || buffer.readUInt16LE(offset + 8) & 1) throw new SpreadsheetError('ไฟล์ Excel ขยายใหญ่เกิน 30 MB หรือมีการเข้ารหัส');
    offset += 46 + buffer.readUInt16LE(offset + 28) + buffer.readUInt16LE(offset + 30) + buffer.readUInt16LE(offset + 32);
  }
}

function cellText(cell: ExcelJS.Cell): string {
  if (cell.value === null || cell.value === undefined) return '';
  if (typeof cell.value === 'object') throw new SpreadsheetError(`แถว ${cell.row}: กรุณาใช้ค่าธรรมดา ไม่ใช้สูตร ลิงก์ หรือวัตถุในเซลล์`);
  return String(cell.value).trim().replace(/^\uFEFF/, '');
}

export class SpreadsheetService {
  private readonly pending = new Map<string, PendingImport>();
  constructor(private readonly database: UrbanPlanDatabase) {}

  async preview(name: string, content: Buffer): Promise<ImportPreview> {
    const workbook = new ExcelJS.Workbook();
    try {
      if (/\.xlsx$/i.test(name)) { checkWorkbookSize(content); await workbook.xlsx.load(Uint8Array.from(content).buffer); }
      else if (/\.csv$/i.test(name)) await workbook.csv.read(Readable.from([content]), { parserOptions: { maxRows: MAX_ROWS + 2 } });
      else throw new SpreadsheetError('รองรับเฉพาะไฟล์ .xlsx และ .csv');
    } catch (error) {
      if (error instanceof SpreadsheetError) throw error;
      throw new SpreadsheetError('อ่านไฟล์ไม่ได้ กรุณาตรวจรูปแบบ Excel หรือ CSV UTF-8');
    }
    const sheet = workbook.worksheets[0];
    if (!sheet || sheet.rowCount < 2) throw new SpreadsheetError('ไฟล์ต้องมีหัวตารางและข้อมูลอย่างน้อย 1 แถว');
    if (sheet.rowCount > MAX_ROWS + 1 || sheet.columnCount > 30) throw new SpreadsheetError(`นำเข้าได้ไม่เกิน ${MAX_ROWS} แถว และ 30 คอลัมน์ต่อครั้ง`);
    const issues: ImportIssue[] = [];
    const columns: Array<{ index: number; field: string }> = [];
    const seenHeaders = new Set<string>();
    sheet.getRow(1).eachCell((cell, index) => {
      const label = cellText(cell);
      const field = Object.hasOwn(headers, label) ? headers[label] : undefined;
      if (!field) issues.push({ row: 1, field: label, message: 'ไม่รู้จักหัวคอลัมน์ กรุณาใช้ไฟล์แม่แบบ' });
      else if (seenHeaders.has(field)) issues.push({ row: 1, field, message: 'หัวคอลัมน์ซ้ำ' });
      else { columns.push({ index, field }); seenHeaders.add(field); }
    });
    if (!seenHeaders.has('name') || !seenHeaders.has('provinceId') && !seenHeaders.has('province')) {
      issues.push({ row: 1, field: 'name / provinceId', message: 'ต้องมีคอลัมน์ชื่อผังเมือง และรหัสจังหวัดหรือชื่อจังหวัด' });
    }
    const existing = new Set(this.database.listPlans({}).map(duplicateKey));
    const inputs: CreatePlan[] = [];
    const rows: ImportPreview['rows'] = [];
    let totalRows = 0;
    for (let number = 2; number <= sheet.rowCount; number++) {
      const row = sheet.getRow(number);
      if (!row.hasValues) continue;
      totalRows++;
      const initialIssues = issues.length;
      const values: Record<string, unknown> = {};
      for (const column of columns) {
        let text: string;
        try { text = cellText(row.getCell(column.index)); }
        catch (error) { issues.push({ row: number, field: column.field, message: (error as Error).message }); continue; }
        if (!text) continue;
        if (/^[=+@]/.test(text)) { issues.push({ row: number, field: column.field, message: 'ไม่รองรับสูตรในข้อมูลนำเข้า' }); continue; }
        values[column.field] = ['provinceId', 'currentStep', 'considerId'].includes(column.field) ? Number(text) :
          ['isEec', 'petition'].includes(column.field) ? ['true', '1', 'ใช่'].includes(text.toLowerCase()) ? true : ['false', '0', 'ไม่ใช่'].includes(text.toLowerCase()) ? false : text : text;
      }
      if (typeof values.province === 'string') {
        const province = PROVINCES.find(item => item.name === (values.province as string).replace(/^จังหวัด\s*/, ''));
        if (!province || values.provinceId !== undefined && values.provinceId !== province.id) issues.push({ row: number, field: 'province', message: 'ชื่อจังหวัดไม่ถูกต้อง หรือไม่ตรงกับรหัสจังหวัด' });
        else values.provinceId = province.id;
      }
      delete values.province;
      for (const required of ['name', 'provinceId']) if (values[required] === undefined) issues.push({ row: number, field: required, message: 'กรุณาระบุข้อมูล' });
      for (const [field, value] of Object.entries(values)) if (!createFields.includes(field) || !planRules[field]?.(value)) issues.push({ row: number, field, message: 'ค่าไม่ถูกต้อง กรุณาตรวจชนิดข้อมูลและค่าที่อนุญาตในคู่มือ' });
      if (initialIssues !== issues.length || !values.name || !values.provinceId) continue;
      const input = values as CreatePlan;
      const key = duplicateKey(input);
      if (existing.has(key)) { issues.push({ row: number, field: 'name', message: 'ชื่อผังเมืองและจังหวัดซ้ำกับข้อมูลเดิมหรือแถวก่อนหน้า' }); continue; }
      existing.add(key);
      inputs.push(input);
      rows.push({ row: number, name: input.name, provinceName: PROVINCES.find(p => p.id === input.provinceId)!.name, budgetYear: input.budgetYear ?? '2568' });
    }
    if (!totalRows) throw new SpreadsheetError('ไม่พบแถวข้อมูล');
    for (const [token, entry] of this.pending) if (entry.expires <= Date.now()) this.pending.delete(token);
    if (this.pending.size >= 20) throw new SpreadsheetError('มีรายการรอยืนยันครบจำนวน กรุณายืนยันหรือรอ 10 นาที', 429);
    const token = randomBytes(24).toString('hex');
    const expires = Date.now() + TTL;
    this.pending.set(token, { expires, inputs, issues });
    return { token, expiresAt: new Date(expires).toISOString(), totalRows, validRows: inputs.length, issues: issues.slice(0, 200), rows };
  }

  commit(token: string): { imported: number; ids: number[] } {
    const entry = this.pending.get(token);
    if (!entry || entry.expires <= Date.now()) { this.pending.delete(token); throw new SpreadsheetError('รายการหมดอายุหรือถูกนำเข้าแล้ว กรุณาเลือกไฟล์ใหม่', 410); }
    if (entry.issues.length || !entry.inputs.length) throw new SpreadsheetError('กรุณาแก้ไขข้อผิดพลาดทุกแถวก่อนนำเข้า', 422);
    const existing = new Set(this.database.listPlans({}).map(duplicateKey));
    if (entry.inputs.some(input => existing.has(duplicateKey(input)))) throw new SpreadsheetError('ข้อมูลเปลี่ยนแปลงหลังตรวจไฟล์ กรุณาตรวจไฟล์ใหม่เพื่อป้องกันข้อมูลซ้ำ', 409);
    const ids = this.database.transaction(() => {
      const ids = entry.inputs.map(input => this.database.createPlan(input).id);
      this.database.recordImport(ids.length);
      return ids;
    });
    this.pending.delete(token);
    return { imported: ids.length, ids };
  }

  async template(): Promise<Buffer> {
    const workbook = new ExcelJS.Workbook();
    const sheet = workbook.addWorksheet('นำเข้าผังเมือง');
    sheet.addRow(createFields);
    sheet.addRow(['ผังเมืองใหม่', 24, 'เปิดใหม่', 'general', 'กรม', 'กรม', '2569', 1, 'on_track', false]);
    const provinces = workbook.addWorksheet('รหัสจังหวัด');
    provinces.addRow(['provinceId', 'จังหวัด']);
    PROVINCES.forEach(p => provinces.addRow([p.id, p.name]));
    for (const page of workbook.worksheets) { page.getRow(1).font = { bold: true }; page.columns.forEach(column => { column.width = 24; }); page.views = [{ state: 'frozen', ySplit: 1 }]; }
    return Buffer.from(await workbook.xlsx.writeBuffer());
  }

  async export(plans: UrbanPlan[]): Promise<Buffer> {
    const workbook = new ExcelJS.Workbook();
    const sheet = workbook.addWorksheet('ผังเมือง');
    sheet.addRow(['รหัส', 'ชื่อผังเมือง', 'จังหวัด', 'ภาค', 'เขตตรวจราชการ', 'ปีงบประมาณ', 'ลักษณะงาน', 'ขั้นตอน', 'สถานะความก้าวหน้า', 'ผลบังคับใช้', 'ล่าช้า (วัน)', 'อัปเดตล่าสุด']);
    for (const plan of plans) sheet.addRow([plan.code, plan.name, plan.provinceName, plan.regionName, plan.kethId, plan.budgetYear, plan.stage, `${plan.currentStep}.${plan.subStep}`, plan.health, plan.effectiveStatus, plan.delayDays, plan.updatedAt]);
    sheet.getRow(1).font = { bold: true, color: { argb: 'FFFFFFFF' } };
    sheet.getRow(1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF316AA5' } };
    sheet.columns.forEach(column => { column.width = 24; });
    sheet.views = [{ state: 'frozen', ySplit: 1 }];
    sheet.autoFilter = { from: 'A1', to: `L${sheet.rowCount}` };
    return Buffer.from(await workbook.xlsx.writeBuffer());
  }
}
