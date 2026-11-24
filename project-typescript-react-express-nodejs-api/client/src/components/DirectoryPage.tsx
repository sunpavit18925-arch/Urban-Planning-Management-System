import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { DirectoryKind, DirectoryRecord, menuItems } from '../data/navigation';

export function DirectoryPage({ kind, onUpdated }: { kind: DirectoryKind; onUpdated: () => Promise<void> }) {
  const [items, setItems] = useState<DirectoryRecord[]>([]);
  const [search, setSearch] = useState('');
  const [editing, setEditing] = useState<DirectoryRecord | null>(null);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [revision, setRevision] = useState(0);
  useEffect(() => {
    let active = true;
    setLoading(true);
    api.getDirectory(kind).then(data => { if (active) { setItems(data); setError(''); } })
      .catch(err => { if (active) setError(err.message); }).finally(() => { if (active) setLoading(false); });
    return () => { active = false; };
  }, [kind, revision]);
  const title = menuItems.find(item => item.id === kind)?.label;
  const visible = items.filter(item => `${item.name} ${item.detail}`.toLowerCase().includes(search.trim().toLowerCase()));
  const save = async (event: React.FormEvent) => {
    event.preventDefault();
    if (!editing) return;
    setSaving(true); setError('');
    try {
      await api.saveDirectory(kind, { name: editing.name, detail: editing.detail, active: editing.active }, editing.id || undefined);
      setEditing(null); setRevision(value => value + 1); await onUpdated();
    } catch (err) { setError(err instanceof Error ? err.message : 'บันทึกไม่สำเร็จ'); }
    finally { setSaving(false); }
  };
  const remove = async (item: DirectoryRecord) => {
    if (!window.confirm(`ลบรายการ “${item.name}” หรือไม่?`)) return;
    setSaving(true); setError('');
    try { await api.deleteDirectory(kind, item.id); setRevision(value => value + 1); await onUpdated(); }
    catch (err) { setError(err instanceof Error ? err.message : 'ลบไม่สำเร็จ'); }
    finally { setSaving(false); }
  };
  return <div className="panel">
    <div className="panel-header"><div><h2>{title}</h2><p className="muted-sub">ทั้งหมด {items.length} รายการ</p></div>
      <button className="btn-primary" onClick={() => { setError(''); setEditing({ id: 0, name: '', detail: '', active: true }); }}>+ เพิ่มรายการ</button></div>
    {error && !editing && <div className="alert-error" role="alert">{error}</div>}
    <div className="filter-bar"><input aria-label={`ค้นหา${title}`} placeholder="ค้นหาชื่อหรือหน่วยงาน..." value={search} onChange={e => setSearch(e.target.value)} /></div>
    {kind === 'administrators' && <p className="muted-sub">ทะเบียนผู้ประสานงานดูแลข้อมูลของแต่ละหน่วยงาน</p>}
    {loading ? <div className="loading-box">กำลังโหลดข้อมูล...</div> : <div className="table-wrap"><table className="data-table">
      <thead><tr><th>ชื่อรายการ</th><th>รายละเอียด / หน่วยงาน</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
      <tbody>{visible.map(item => <tr key={item.id}><td>{item.name}</td><td>{item.detail || '—'}</td>
        <td><span className={`badge badge-${item.active ? 'green' : 'gray'}`}>{item.active ? 'ใช้งาน' : 'พักการใช้งาน'}</span></td>
        <td><div className="actions-cell"><button className="btn-sm" onClick={() => { setError(''); setEditing({ ...item }); }}>แก้ไข</button>
          <button className="btn-sm btn-danger" disabled={saving} onClick={() => void remove(item)}>ลบ</button></div></td></tr>)}
        {!visible.length && <tr><td className="empty-cell" colSpan={4}>ไม่พบข้อมูล</td></tr>}</tbody>
    </table></div>}
    {editing && <div className="modal-backdrop"><div className="modal-card" role="dialog" aria-modal="true" aria-labelledby="directory-title">
      <div className="modal-header"><h3 id="directory-title">{editing.id ? 'แก้ไข' : 'เพิ่ม'}{title}</h3><button className="btn-icon" disabled={saving} onClick={() => setEditing(null)} aria-label="ปิด">✕</button></div>
      <form className="modal-body form-grid" onSubmit={save}>
        {error && <div className="alert-error form-field full" role="alert">{error}</div>}
        <label className="form-field full"><span>ชื่อรายการ *</span><input autoFocus required maxLength={250} value={editing.name} onChange={e => setEditing({ ...editing, name: e.target.value })} /></label>
        <label className="form-field full"><span>รายละเอียด / หน่วยงาน</span><textarea rows={3} maxLength={2000} value={editing.detail} onChange={e => setEditing({ ...editing, detail: e.target.value })} /></label>
        <label className="checkbox-field full"><input type="checkbox" checked={editing.active} onChange={e => setEditing({ ...editing, active: e.target.checked })} />ใช้งาน</label>
        <div className="modal-footer full"><button type="button" className="btn-secondary" disabled={saving} onClick={() => setEditing(null)}>ยกเลิก</button><button className="btn-primary" disabled={saving}>{saving ? 'กำลังบันทึก...' : 'บันทึก'}</button></div>
      </form></div></div>}
  </div>;
}
