import React, { useState } from 'react';
import { api } from '../api/client';
import { ImportPreview } from '../types/operations';

export function DataTransferPage({ onImported }: { onImported: () => Promise<void> }) {
  const [file, setFile] = useState<File | null>(null);
  const [fileInputKey, setFileInputKey] = useState(0);
  const [preview, setPreview] = useState<ImportPreview | null>(null);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState('');
  const [message, setMessage] = useState('');

  async function inspect(event: React.FormEvent) {
    event.preventDefault();
    if (!file) return;
    setBusy(true); setError(''); setMessage(''); setPreview(null);
    try {
      if (file.size > 5 * 1024 * 1024) throw new Error('ไฟล์ต้องมีขนาดไม่เกิน 5 MB');
      setPreview(await api.previewImport(file));
    } catch (error) { setError(error instanceof Error ? error.message : 'ตรวจไฟล์ไม่ได้'); }
    finally { setBusy(false); }
  }

  async function commit() {
    if (!preview || preview.issues.length || !preview.validRows) return;
    setBusy(true); setError('');
    try {
      const result = await api.commitImport(preview.token);
      setPreview(null); setFile(null); setFileInputKey(key => key + 1);
      setMessage(`นำเข้าผังเมือง ${result.imported} รายการเรียบร้อยแล้ว`);
      await onImported();
    } catch (error) { setError(error instanceof Error ? error.message : 'นำเข้าไม่ได้'); }
    finally { setBusy(false); }
  }

  return <div className="panel data-transfer">
    <div className="panel-header"><div><h2>นำเข้าข้อมูลผังเมือง</h2><p className="muted-sub">ตรวจข้อมูลทุกแถวก่อนยืนยันบันทึก รองรับ Excel (.xlsx) และ CSV UTF-8</p></div>
      <a className="btn-secondary" href="/api/imports/template.xlsx" download>ดาวน์โหลดแม่แบบ Excel</a>
    </div>
    <p>นำเข้าได้ครั้งละ 1,000 รายการ ไฟล์ไม่เกิน 5 MB ต้องระบุชื่อผังเมืองและจังหวัด ชื่อผังเมืองในจังหวัดเดียวกันต้องไม่ซ้ำ</p>
    <form onSubmit={inspect} className="inline-controls">
      <label>ไฟล์ข้อมูล <input key={fileInputKey} aria-label="ไฟล์นำเข้าผังเมือง" type="file" accept=".xlsx,.csv" disabled={busy} onChange={event => { setFile(event.target.files?.[0] ?? null); setPreview(null); setError(''); setMessage(''); }} /></label>
      <button className="btn-primary" disabled={busy || !file} type="submit">{busy ? 'กำลังดำเนินการ...' : 'ตรวจข้อมูลก่อนนำเข้า'}</button>
    </form>
    {error && <div role="alert" className="alert-error">{error}</div>}
    {message && <p role="status">{message} <a href="#plans">ดูรายการผังเมือง</a></p>}
    {preview && <section aria-label="ผลตรวจไฟล์นำเข้า">
      <h3>ผลตรวจไฟล์: ผ่าน {preview.validRows} จาก {preview.totalRows} รายการ</h3>
      <p>ยืนยันได้ภายใน {new Date(preview.expiresAt).toLocaleTimeString('th-TH')} ระบบจะบันทึกทุกแถวพร้อมกันเมื่อไม่มีข้อผิดพลาด</p>
      {preview.issues.length > 0 && <div className="alert-error" role="alert"><strong>กรุณาแก้ไขไฟล์แล้วตรวจใหม่ (แสดงข้อผิดพลาดสูงสุด 200 ข้อ)</strong><ul>{preview.issues.map((issue, index) => <li key={index}>แถว {issue.row} · {issue.field}: {issue.message}</li>)}</ul></div>}
      <div className="table-wrap"><table className="data-table"><thead><tr><th>แถว</th><th>ชื่อผังเมือง</th><th>จังหวัด</th><th>ปีงบประมาณ</th></tr></thead><tbody>{preview.rows.slice(0, 50).map(row => <tr key={row.row}><td>{row.row}</td><td>{row.name}</td><td>{row.provinceName}</td><td>{row.budgetYear}</td></tr>)}</tbody></table></div>
      {preview.rows.length > 50 && <p>แสดงตัวอย่าง 50 แถวแรกจาก {preview.rows.length} แถวที่ผ่าน</p>}
      <button className="btn-primary" type="button" disabled={busy || preview.issues.length > 0 || !preview.validRows} onClick={() => void commit()}>ยืนยันนำเข้า {preview.validRows} รายการ</button>
    </section>}
    <div className="section-box"><h3>ส่งออกข้อมูล Excel</h3><p>ดาวน์โหลดข้อมูลผังเมืองทั้งหมด พร้อมสถานภาพ ขั้นตอน และวันที่อัปเดตล่าสุด</p><a className="btn-secondary" href="/api/exports/plans.xlsx" download>ดาวน์โหลดข้อมูลทั้งหมด (.xlsx)</a></div>
  </div>;
}
