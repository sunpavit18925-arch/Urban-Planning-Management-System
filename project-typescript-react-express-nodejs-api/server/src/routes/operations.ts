import { Router, ErrorRequestHandler } from 'express';
import multer from 'multer';
import { database } from '../data/database';
import { SpreadsheetError, SpreadsheetService } from '../services/spreadsheets';
import { PlanAttachment } from '../types/operations';
import { DatabaseConflictError } from '../storage/sqlite';
import { DomainError } from '../services/modules';
import { CalendarError } from '../services/calendar';

const router = Router();
const spreadsheets = new SpreadsheetService(database);
const upload = multer({ storage: multer.memoryStorage(), limits: { fileSize: 5 * 1024 * 1024, files: 1, fields: 0, parts: 1 } });
const excelType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

router.get('/imports/template.xlsx', async (_req, res, next) => {
  try { res.attachment('UPMS-import-template.xlsx').type(excelType).send(await spreadsheets.template()); } catch (error) { next(error); }
});
router.post('/imports/preview', upload.single('file'), async (req, res, next) => {
  try {
    if (!req.file) throw new SpreadsheetError('กรุณาเลือกไฟล์ข้อมูล');
    res.json(await spreadsheets.preview(req.file.originalname, req.file.buffer));
  } catch (error) { next(error); }
});
router.post('/imports/:token/commit', (req, res, next) => {
  try { res.status(201).json(spreadsheets.commit(String(req.params.token))); } catch (error) { next(error); }
});
router.get('/exports/plans.xlsx', async (_req, res, next) => {
  try { res.attachment('UPMS-plans.xlsx').type(excelType).send(await spreadsheets.export(database.listPlans({}))); } catch (error) { next(error); }
});

router.use('/plans/:id/attachments', (req, res, next) => {
  if (!Number.isSafeInteger(Number(req.params.id)) || !database.getPlanById(Number(req.params.id))) { res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' }); return; }
  next();
});
router.get('/plans/:id/history', (req, res) => {
  if (!database.getPlanById(Number(req.params.id))) { res.status(404).json({ error: 'ไม่พบข้อมูลผังเมือง' }); return; }
  res.json(database.getHistory(Number(req.params.id)));
});
router.get('/plans/:id/attachments', (req, res) => { res.json(database.listAttachments(Number(req.params.id))); });
router.post('/plans/:id/attachments', upload.single('file'), (req, res, next) => {
  try {
    const file = req.file;
    if (!file || !file.buffer.length) throw new SpreadsheetError('กรุณาเลือกไฟล์ภาพหรือ PDF');
    const data = file.buffer;
    let mimeType: PlanAttachment['mimeType'];
    let extension: RegExp;
    if (data.subarray(0, 8).equals(Buffer.from([137, 80, 78, 71, 13, 10, 26, 10]))) { mimeType = 'image/png'; extension = /\.png$/i; }
    else if (data[0] === 255 && data[1] === 216 && data[2] === 255) { mimeType = 'image/jpeg'; extension = /\.jpe?g$/i; }
    else if (data.subarray(0, 5).toString('ascii') === '%PDF-') { mimeType = 'application/pdf'; extension = /\.pdf$/i; }
    else throw new SpreadsheetError('รองรับเฉพาะภาพ PNG, JPEG และเอกสาร PDF');
    if (!extension.test(file.originalname)) throw new SpreadsheetError('นามสกุลไฟล์ไม่ตรงกับเนื้อหา');
    if (database.listAttachments(Number(req.params.id)).length >= 20) throw new SpreadsheetError('แนบได้ไม่เกิน 20 ไฟล์ต่อผังเมือง');
    // Multipart filenames from browsers arrive as UTF-8 bytes decoded as Latin-1.
    const decodedName = Buffer.from(file.originalname, 'latin1').toString('utf8');
    const originalName = !decodedName.includes('\uFFFD') && /^[\x00-\xff]*$/.test(file.originalname) ? decodedName : file.originalname;
    const name = Array.from(originalName.replace(/[/\\\x00-\x1f\x7f]/g, '_')).slice(-180).join('');
    res.status(201).json(database.addAttachment(Number(req.params.id), name, mimeType, data));
  } catch (error) { next(error); }
});
router.get('/plans/:id/attachments/:fileId', (req, res) => {
  const file = database.getAttachment(Number(req.params.id), String(req.params.fileId));
  if (!file) { res.status(404).json({ error: 'ไม่พบไฟล์แนบ' }); return; }
  res.set('X-Content-Type-Options', 'nosniff');
  res.set('Cache-Control', 'no-store');
  res.attachment(file.name).type(file.mimeType).send(Buffer.from(file.content));
});
router.delete('/plans/:id/attachments/:fileId', (req, res) => {
  if (!database.deleteAttachment(Number(req.params.id), String(req.params.fileId))) { res.status(404).json({ error: 'ไม่พบไฟล์แนบ' }); return; }
  res.json({ deleted: true });
});

export const apiErrorHandler: ErrorRequestHandler = (error: unknown, _req, res, _next) => {
  if (error instanceof SpreadsheetError) { res.status(error.status).json({ error: error.message }); return; }
  if (error instanceof DomainError) { res.status(error.status).json({ error: error.message }); return; }
  if (error instanceof CalendarError) { res.status(400).json({ error: error.message }); return; }
  if (error instanceof DatabaseConflictError) { res.status(409).json({ error: error.message }); return; }
  if (error instanceof multer.MulterError) { res.status(error.code === 'LIMIT_FILE_SIZE' ? 413 : 400).json({ error: error.code === 'LIMIT_FILE_SIZE' ? 'ไฟล์ต้องมีขนาดไม่เกิน 5 MB' : 'กรุณาส่งไฟล์เดียวผ่านช่อง file' }); return; }
  if (error instanceof SyntaxError && 'body' in error) { res.status(400).json({ error: 'รูปแบบ JSON ไม่ถูกต้อง' }); return; }
  if (error instanceof Error && 'status' in error && error.status === 413) { res.status(413).json({ error: 'ข้อมูลคำขอมีขนาดเกินกำหนด' }); return; }
  console.error('[UPMS API]', error);
  res.status(500).json({ error: 'ไม่สามารถดำเนินการได้ กรุณาลองใหม่' });
};

export default router;
