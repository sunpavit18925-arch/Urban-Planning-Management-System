import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { PlanAttachment } from '../types/operations';

export function PlanFiles({ planId }: { planId: number }) {
  const [files, setFiles] = useState<PlanAttachment[]>([]);
  const [selected, setSelected] = useState<File | null>(null);
  const [inputKey, setInputKey] = useState(0);
  const [busy, setBusy] = useState(false);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  useEffect(() => {
    let active = true;
    api.getAttachments(planId).then(value => { if (active) setFiles(value); })
      .catch(error => { if (active) setError(error.message); }).finally(() => { if (active) setLoading(false); });
    return () => { active = false; };
  }, [planId]);

  async function upload(event: React.FormEvent) {
    event.preventDefault();
    if (!selected) return;
    setBusy(true); setError('');
    try {
      if (selected.size > 5 * 1024 * 1024) throw new Error('ไฟล์ต้องมีขนาดไม่เกิน 5 MB');
      await api.uploadAttachment(planId, selected);
      setSelected(null); setInputKey(key => key + 1);
      setFiles(await api.getAttachments(planId));
    } catch (error) { setError(error instanceof Error ? error.message : 'อัปโหลดไม่ได้'); }
    finally { setBusy(false); }
  }

  async function remove(file: PlanAttachment) {
    if (!window.confirm(`ลบไฟล์ “${file.name}” หรือไม่?`)) return;
    setBusy(true); setError('');
    try { await api.deleteAttachment(planId, file.id); setFiles(await api.getAttachments(planId)); }
    catch (error) { setError(error instanceof Error ? error.message : 'ลบไฟล์ไม่ได้'); }
    finally { setBusy(false); }
  }

  return <section className="section-box" aria-label="ไฟล์แนบผังเมือง">
    <h4>แผนที่และเอกสารแนบ ({files.length})</h4><p className="muted-sub">รองรับ PNG, JPEG และ PDF ไฟล์ละไม่เกิน 5 MB สูงสุด 20 ไฟล์</p>
    {error && <p role="alert" className="alert-error">{error}</p>}
    <form onSubmit={upload} className="inline-controls"><label>เลือกไฟล์แนบ <input key={inputKey} type="file" aria-label="เลือกไฟล์แนบ" accept=".png,.jpg,.jpeg,.pdf" disabled={busy || loading} onChange={event => setSelected(event.target.files?.[0] ?? null)} /></label><button type="submit" className="btn-secondary" disabled={busy || loading || !selected || files.length >= 20}>{busy ? 'กำลังบันทึก...' : 'อัปโหลดไฟล์'}</button></form>
    {loading ? <p>กำลังโหลดไฟล์แนบ...</p> : !files.length ? <p className="muted-sub">ยังไม่มีไฟล์แนบ</p> : <ul className="attachment-list">{files.map(file => <li key={file.id}><a href={`/api/plans/${planId}/attachments/${encodeURIComponent(file.id)}`} download={file.name}>{file.name}</a><span> {(file.size / 1024).toFixed(1)} KB · {new Date(file.createdAt).toLocaleDateString('th-TH')} </span><button className="btn-secondary" type="button" disabled={busy} onClick={() => void remove(file)} aria-label={`ลบไฟล์ ${file.name}`}>ลบ</button></li>)}</ul>}
  </section>;
}
