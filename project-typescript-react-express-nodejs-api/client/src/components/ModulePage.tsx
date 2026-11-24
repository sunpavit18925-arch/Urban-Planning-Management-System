import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { FieldOption, FieldValue, MaintenanceReport, ModuleDefinition, ModuleField, ModuleRecord, ScheduleRow } from '../types/modules';

const labels: Record<string, string> = { general: 'ทั่วไป', m35: 'มาตรา 35', evaluation: 'ประเมิน', active: 'ดำเนินการ', completed: 'เสร็จสิ้น', cancelled: 'ยกเลิก', normal: 'ปกติ', needs_repair: 'ต้องซ่อม', out_of_service: 'ใช้งานไม่ได้', superadmin: 'ผู้ดูแลสูงสุด', admin: 'ผู้ดูแล', guest: 'ผู้เยี่ยมชม' };
const maintenanceLabels = { not_purchased: 'ยังไม่จัดหา', warranty: 'อยู่ในประกัน', maintenance: 'บำรุงรักษา', replacement: 'จัดหาทดแทน' };
export function ModulePage({ kind, onUpdated }: { kind: string; onUpdated: () => Promise<void> }) {
  const [definition, setDefinition] = useState<ModuleDefinition | null>(null);
  const [records, setRecords] = useState<ModuleRecord[]>([]);
  const [options, setOptions] = useState<Record<string, FieldOption[]>>({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [search, setSearch] = useState('');
  const [editing, setEditing] = useState<ModuleRecord | 'new' | null>(null);
  const [values, setValues] = useState<Record<string, FieldValue>>({});
  const [saving, setSaving] = useState(false);
  const [report, setReport] = useState<MaintenanceReport | null>(null);
  const [schedule, setSchedule] = useState<ScheduleRow[] | null>(null);
  const readPlanId = () => { const match = /\/plan\/(\d+)/.exec(window.location.hash); return match ? Number(match[1]) : undefined; };
  const [planId, setPlanId] = useState(readPlanId);
  useEffect(() => { const update = () => setPlanId(readPlanId()); window.addEventListener('hashchange', update); return () => window.removeEventListener('hashchange', update); }, []);

  async function load() {
    const definitions = await api.getModuleDefinitions();
    const selected = definitions.find(item => item.key === kind);
    if (!selected) throw new Error('ไม่พบหน้าข้อมูล');
    const references = [...new Set(selected.fields.flatMap(field => field.relation ? [field.relation] : []))];
    const [items, ...referenceValues] = await Promise.all([api.getModuleRecords(kind, planId), ...references.map(async target => {
      const items = target === 'plans' ? (await api.getPlans()).items.map(plan => ({ value: plan.id, label: `${plan.code} · ${plan.name}` })) : (await api.getModuleRecords(target)).map(record => ({ value: record.id, label: `${record.id} · ${String(record.data.name ?? '')}${record.data.profile ? ` (${labels[String(record.data.profile)] ?? record.data.profile})` : ''}` }));
      return { target, items };
    })]);
    setDefinition(selected); setRecords(items as ModuleRecord[]);
    const referencesMap: Record<string, FieldOption[]> = {};
    for (const reference of referenceValues as Array<{ target: string; items: FieldOption[] }>) referencesMap[reference.target] = reference.items;
    setOptions(referencesMap);
  }
  useEffect(() => { setLoading(true); setError(''); void load().catch(error => setError(error.message)).finally(() => setLoading(false)); }, [kind, planId]);
  const display = (field: ModuleField, value: FieldValue | undefined): string => {
    if (Array.isArray(value)) return value.map(id => options[field.relation ?? '']?.find(option => option.value === id)?.label ?? id).join(', ') || '—';
    if (typeof value === 'boolean') return value ? 'ใช่' : 'ไม่ใช่';
    if (field.relation) return options[field.relation]?.find(option => option.value === value)?.label ?? '—';
    return labels[String(value)] ?? String(value ?? '—');
  };
  const visible = records.filter(record => Object.values(record.data).some(value => String(value).toLowerCase().includes(search.toLowerCase())));
  function open(record: ModuleRecord | 'new') {
    if (!definition) return;
    setValues(Object.fromEntries(definition.fields.map(field => [field.key, record === 'new' ? field.key === 'planId' && planId ? planId : field.default ?? '' : record.data[field.key] ?? field.default ?? ''])));
    setEditing(record); setError('');
  }
  async function save(event: React.FormEvent) {
    event.preventDefault(); setSaving(true); setError('');
    try { await api.saveModuleRecord(kind, values, editing === 'new' || !editing ? undefined : editing); setEditing(null); await load(); await onUpdated(); }
    catch (error) { setError(error instanceof Error ? error.message : 'บันทึกไม่ได้'); }
    finally { setSaving(false); }
  }
  async function remove(record: ModuleRecord) {
    if (!window.confirm(`ลบ “${String(record.data.name)}” หรือไม่?`)) return;
    setSaving(true); setError('');
    try { await api.deleteModuleRecord(kind, record); await load(); await onUpdated(); }
    catch (error) { setError(error instanceof Error ? error.message : 'ลบไม่ได้'); }
    finally { setSaving(false); }
  }
  function fieldInput(field: ModuleField) {
    const value = values[field.key];
    const change = (next: FieldValue) => setValues(previous => ({ ...previous, [field.key]: next }));
    const common = { id: `module-${field.key}`, name: field.key, required: field.required, disabled: saving };
    if (field.type === 'boolean') return <input {...common} type="checkbox" checked={Boolean(value)} onChange={event => change(event.target.checked)} />;
    if (field.type === 'textarea') return <textarea {...common} maxLength={field.max} value={String(value ?? '')} onChange={event => change(event.target.value)} />;
    if (field.type === 'select' || field.type === 'relation' || field.type === 'multi') {
      const choices = field.options ?? options[field.relation ?? ''] ?? [];
      return <select {...common} multiple={field.type === 'multi'} value={Array.isArray(value) ? value.map(String) : String(value ?? '')} onChange={event => change(field.type === 'multi' ? Array.from(event.target.selectedOptions).map(option => Number(option.value)) : field.type === 'relation' ? Number(event.target.value) : event.target.value)}>
        {field.type === 'relation' && <option value="0">{field.required ? 'กรุณาเลือก' : 'ไม่ระบุ'}</option>}{choices.map(option => <option key={option.value} value={option.value}>{labels[option.label] ?? option.label}</option>)}
      </select>;
    }
    return <input {...common} type={field.type === 'number' ? 'number' : field.type === 'date' ? 'date' : 'text'} min={field.type === 'number' ? field.min : undefined} max={field.type === 'number' ? field.max : undefined} step={field.integer ? 1 : 'any'} maxLength={field.type === 'text' ? field.max : undefined} value={String(value ?? '')} onChange={event => change(field.type === 'number' ? Number(event.target.value) : event.target.value)} />;
  }
  return <div className="panel module-page" data-module={kind}>
    <div className="panel-header"><div><h2>{definition?.title ?? 'กำลังโหลด...'}</h2><p className="muted-sub">{definition?.description}</p></div><button className="btn-primary" disabled={loading || saving} onClick={() => open('new')}>เพิ่มข้อมูล</button></div>
    {error && <div className="alert-error" role="alert">{error} {!editing && <button className="btn-secondary" onClick={() => void load().catch(error => setError(error.message))}>โหลดข้อมูลใหม่</button>}</div>}
    <div className="inline-controls"><input aria-label="ค้นหารายการ" placeholder="ค้นหาในรายการ..." value={search} onChange={event => setSearch(event.target.value)} /><span>{visible.length} จาก {records.length} รายการ</span><a href={`/api/reports/modules/${encodeURIComponent(kind)}.xlsx`} className="btn-secondary" download>ส่งออก Excel</a><button className="btn-secondary" onClick={() => window.print()}>พิมพ์</button></div>
    {loading ? <div className="loading-box">กำลังโหลด...</div> : definition && <div className="table-wrap"><table className="data-table"><thead><tr>{definition.columns.map(key => <th key={key}>{definition.fields.find(field => field.key === key)?.label}</th>)}<th>จัดการ</th></tr></thead><tbody>{visible.map(record => <tr key={record.id}>{definition.columns.map(key => <td key={key}>{display(definition.fields.find(field => field.key === key)!, record.data[key])}</td>)}<td className="actions-cell"><button className="btn-sm" onClick={() => open(record)}>แก้ไข</button><button className="btn-sm btn-danger" disabled={saving} onClick={() => void remove(record)}>ลบ</button>{kind === 'maintenancePlans' && <button className="btn-sm" onClick={() => void api.getMaintenance(record.id).then(setReport).catch(error => setError(error.message))}>ตารางรายปี</button>}{kind === 'planSchedules' && <button className="btn-sm" onClick={() => void api.getSchedule(record.id).then(result => setSchedule(result.rows)).catch(error => setError(error.message))}>ดูแผน</button>}</td></tr>)}{!visible.length && <tr><td colSpan={definition.columns.length + 1} className="empty-cell">ยังไม่มีข้อมูลตามเงื่อนไข</td></tr>}</tbody></table></div>}
    {report && <section className="section-box"><h3>งบประมาณบำรุงรักษารายปี</h3><div className="table-wrap"><table className="data-table"><thead><tr><th>อุปกรณ์</th>{report.years.map(year => <th key={year}>{year}</th>)}</tr></thead><tbody>{report.rows.map(row => <tr key={row.equipmentId}><th>{row.name}</th>{row.cells.map(cell => <td key={cell.year} className={`maintenance-${cell.status}`}>{maintenanceLabels[cell.status]}<br />{cell.amount.toLocaleString('th-TH', { minimumFractionDigits: 2 })}</td>)}</tr>)}<tr><th>รวม (บาท)</th>{report.totals.map((value, index) => <td key={index}>{value.toLocaleString('th-TH', { minimumFractionDigits: 2 })}</td>)}</tr></tbody></table></div><p>รวมทั้งแผน {report.total.toLocaleString('th-TH')} บาท</p></section>}
    {schedule && <section className="section-box"><h3>แผนวันเริ่ม–สิ้นสุด</h3><div className="table-wrap"><table className="data-table"><thead><tr><th>ขั้นตอน</th><th>เริ่ม</th><th>สิ้นสุด</th><th>จำนวนวัน</th></tr></thead><tbody>{schedule.map(row => <tr key={row.definitionId}><td>{'　'.repeat(row.thirdStep ? 2 : row.subStep ? 1 : 0)}{row.step}{row.subStep ? `.${row.subStep}` : ''}{row.thirdStep ? `.${row.thirdStep}` : ''} · {row.title}</td><td>{row.start}</td><td>{row.end}</td><td>{row.days}</td></tr>)}</tbody></table></div></section>}
    {editing && definition && <div className="modal-backdrop"><div className="modal-card modal-wide" role="dialog" aria-modal="true" aria-label={definition.title}><div className="modal-header"><h3>{editing === 'new' ? 'เพิ่ม' : 'แก้ไข'} {definition.title}</h3><button className="btn-icon" disabled={saving} onClick={() => setEditing(null)} aria-label="ปิด">✕</button></div><form onSubmit={save}><div className="modal-body">{error && <div className="alert-error" role="alert">{error}</div>}<div className="module-form-grid">{definition.fields.map(field => <label key={field.key} htmlFor={`module-${field.key}`}>{field.label}{field.required ? ' *' : ''}{fieldInput(field)}</label>)}</div></div><div className="modal-footer"><button type="button" className="btn-secondary" disabled={saving} onClick={() => setEditing(null)}>ยกเลิก</button><button className="btn-primary" disabled={saving} type="submit">{saving ? 'กำลังบันทึก...' : 'บันทึกข้อมูล'}</button></div></form></div></div>}
  </div>;
}
