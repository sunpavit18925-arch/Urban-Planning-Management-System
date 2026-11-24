import React, { useEffect, useState } from 'react';
import { Account, api, SessionInfo } from '../api/client';
const roleLabels = { superadmin: 'ผู้ดูแลสูงสุด', admin: 'ผู้ดูแลระบบ', guest: 'ผู้เยี่ยมชม' };
export function AuthPanel() {
  const [session, setSession] = useState<SessionInfo | null>(null);
  const [open, setOpen] = useState(false);
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [busy, setBusy] = useState(false);
  useEffect(() => { api.getSession().then(setSession).catch(error => setError(error.message)); }, []);
  async function login(event: React.FormEvent) {
    event.preventDefault(); setBusy(true); setError('');
    try { await api.login(username, password); setPassword(''); window.location.reload(); }
    catch (error) { setError(error instanceof Error ? error.message : 'เข้าสู่ระบบไม่ได้'); }
    finally { setBusy(false); }
  }
  async function changeRole(role: Account['role']) { setBusy(true); setError(''); try { await api.changeView(role); window.location.reload(); } catch (error) { setError(error instanceof Error ? error.message : 'เปลี่ยนโหมดไม่ได้'); setBusy(false); } }
  return <><button className="auth-menu-button" onClick={() => setOpen(true)}><i className="fa fa-user" aria-hidden="true" /> {session?.user?.name ?? (session?.enforced ? 'เข้าสู่ระบบ' : 'เจ้าหน้าที่ผังเมือง')}</button>
    {open && <div className="modal-backdrop"><div className="modal-card" role="dialog" aria-modal="true" aria-label="บัญชีและสิทธิ์ใช้งาน"><div className="modal-header"><h3>บัญชีและสิทธิ์ใช้งาน</h3><button className="btn-icon" disabled={busy} onClick={() => setOpen(false)} aria-label="ปิด">✕</button></div><div className="modal-body">
      {error && <p role="alert" className="alert-error">{error}</p>}
      {session?.user && <p>{session.user.name} · {roleLabels[session.user.role]}</p>}
      {!session?.enforced ? <><p>โหมดสาธิต เลือกสิทธิ์เพื่อดูการทำงานของระบบได้ บัญชีจริงตั้งค่าได้ในเมนูบัญชีและสิทธิ์ใช้งาน</p><div className="inline-controls">{Object.entries(roleLabels).map(([role, label]) => <button key={role} className="btn-secondary" disabled={busy} onClick={() => void changeRole(role as Account['role'])}>{label}</button>)}</div></> : !session.user ? <form onSubmit={login} className="module-form-grid"><label>ชื่อผู้ใช้<input autoComplete="username" required value={username} onChange={event => setUsername(event.target.value)} /></label><label>รหัสผ่าน<input autoComplete="current-password" type="password" required value={password} onChange={event => setPassword(event.target.value)} /></label><button className="btn-primary" disabled={busy}>เข้าสู่ระบบ</button></form> : <button className="btn-secondary" disabled={busy} onClick={() => { setBusy(true); void api.logout().then(() => window.location.reload()).catch(error => { setError(error.message); setBusy(false); }); }}>ออกจากระบบ</button>}
      {session?.user?.role === 'superadmin' && <p><a href="#accounts" onClick={() => setOpen(false)}>จัดการบัญชีและสิทธิ์ใช้งาน</a></p>}
    </div></div></div>}
  </>;
}

export function AccountsPage() {
  const [accounts, setAccounts] = useState<Account[]>([]);
  const [enforced, setEnforced] = useState(false);
  const [error, setError] = useState('');
  const [editing, setEditing] = useState<Account | 'new' | null>(null);
  const [username, setUsername] = useState(''); const [name, setName] = useState('');
  const [password, setPassword] = useState(''); const [role, setRole] = useState<Account['role']>('superadmin');
  const [active, setActive] = useState(true); const [busy, setBusy] = useState(false);
  async function load() { const session = await api.getSession(); setEnforced(session.enforced); setAccounts(await api.getAccounts()); }
  useEffect(() => { void load().catch(error => setError(error.message)); }, []);
  function open(account: Account | 'new') { setEditing(account); setUsername(account === 'new' ? '' : account.username); setName(account === 'new' ? '' : account.name); setRole(account === 'new' ? accounts.length ? 'guest' : 'superadmin' : account.role); setActive(account === 'new' || account.active); setPassword(''); setError(''); }
  async function save(event: React.FormEvent) {
    event.preventDefault(); setBusy(true); setError('');
    try {
      if (editing === 'new') { await api.createAccount(username, name, role, password); if (!enforced) await api.login(username, password); }
      else if (editing) await api.updateAccount(editing.id, name, role, active, password || undefined);
      setEditing(null); setPassword(''); await load();
    } catch (error) { setError(error instanceof Error ? error.message : 'บันทึกไม่ได้'); }
    finally { setBusy(false); }
  }
  return <div className="panel"><div className="panel-header"><div><h2>บัญชีและสิทธิ์ใช้งาน</h2><p className="muted-sub">บัญชีเข้าสู่ระบบแยกจากทะเบียนผู้ประสานงาน รหัสผ่านจัดเก็บเป็นค่าแฮช</p></div><button className="btn-primary" onClick={() => open('new')}>เพิ่มบัญชี</button></div>
    {!enforced && <p>ยังเป็นโหมดสาธิต การสร้างบัญชีผู้ดูแลสูงสุดบัญชีแรกจะเปิดการเข้าสู่ระบบและปิดการเลือกโหมดสิทธิ์อย่างอิสระ</p>}
    {error && <p className="alert-error" role="alert">{error}</p>}
    <div className="table-wrap"><table className="data-table"><thead><tr><th>ชื่อผู้ใช้</th><th>ชื่อ</th><th>สิทธิ์</th><th>สถานะ</th><th>จัดการ</th></tr></thead><tbody>{accounts.map(account => <tr key={account.id}><td>{account.username}</td><td>{account.name}</td><td>{roleLabels[account.role]}</td><td>{account.active ? 'ใช้งาน' : 'ปิดใช้งาน'}</td><td><button className="btn-sm" onClick={() => open(account)}>แก้ไข</button></td></tr>)}</tbody></table></div>
    {editing && <div className="modal-backdrop"><div className="modal-card" role="dialog" aria-modal="true" aria-label="แก้ไขบัญชีผู้ใช้"><div className="modal-header"><h3>{editing === 'new' ? 'เพิ่มบัญชี' : 'แก้ไขบัญชี'}</h3><button className="btn-icon" onClick={() => setEditing(null)} aria-label="ปิด">✕</button></div><form onSubmit={save}><div className="modal-body module-form-grid">{error && <p role="alert" className="alert-error">{error}</p>}<label>ชื่อผู้ใช้<input required disabled={editing !== 'new'} pattern="[A-Za-z0-9_.-]{3,50}" value={username} onChange={event => setUsername(event.target.value)} /></label><label>ชื่อ–นามสกุล<input required maxLength={250} value={name} onChange={event => setName(event.target.value)} /></label><label>{editing === 'new' ? 'รหัสผ่าน' : 'รหัสผ่านใหม่ (เว้นว่างหากไม่เปลี่ยน)'}<input type="password" autoComplete="new-password" required={editing === 'new'} minLength={12} maxLength={200} value={password} onChange={event => setPassword(event.target.value)} /></label><label>สิทธิ์<select value={role} onChange={event => setRole(event.target.value as Account['role'])}>{Object.entries(roleLabels).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select></label>{editing !== 'new' && <label><input type="checkbox" checked={active} onChange={event => setActive(event.target.checked)} /> ใช้งาน</label>}</div><div className="modal-footer"><button className="btn-primary" disabled={busy}>บันทึกบัญชี</button></div></form></div></div>}
  </div>;
}
