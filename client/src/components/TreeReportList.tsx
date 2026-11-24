import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { geographyStatuses, matchesGeographyStatus } from '../data/geography';
import type { ProvinceInfo, StepDefinition, UrbanPlan } from '../types/plan';
import { PlanDetailModal } from './PlanDetailModal';

export function TreeReportList({ group, steps, provinces, onBack, onUpdated, sourceData }: {
  group: string; steps: StepDefinition[]; provinces: ProvinceInfo[]; onBack: () => void; onUpdated: () => Promise<void>;
  sourceData?: { title: string; total: number; items: UrbanPlan[] };
}) {
  const [data, setData] = useState<Awaited<ReturnType<typeof api.getTreeReportPlans>> | null>(null);
  const [error, setError] = useState('');
  const [revision, setRevision] = useState(0);
  const [province, setProvince] = useState('');
  const [search, setSearch] = useState('');
  const [status, setStatus] = useState<number>();
  const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null);
  useEffect(() => {
    if (sourceData) { setData(sourceData); setError(''); return; }
    let active = true;
    api.getTreeReportPlans(group).then(result => { if (active) { setData(result); setError(''); } })
      .catch(err => { if (active) setError(err.message); });
    return () => { active = false; };
  }, [group, revision, sourceData]);
  const items = data?.items.filter(plan => (!province || plan.provinceId === Number(province)) &&
    (!status || matchesGeographyStatus(plan, status)) && `${plan.name} ${plan.code}`.toLowerCase().includes(search.trim().toLowerCase())) ?? [];
  const isEvaluation = ['eva', 'eva5', 'eva10'].includes(group);
  const rowIds = ['status_white_step', 'status_green', 'status_yellow', 'status_orange', 'status_red', 'status_white_date'];
  const count = (key: number) => data?.items.filter(plan => matchesGeographyStatus(plan, key)).length ?? 0;
  const counters = (value: number, key?: number) => <>{[0, 1].map(column => <td key={column}><button className="dpt-link" onClick={() => setStatus(key)}>{value}</button></td>)}<td>—</td></>;
  return <div className="geography-page dpt-geography dpt-geography-report php-tree-list">
    <style>{'@media print { @page { size: A4 landscape; margin: 10mm 4mm; } }'}</style>
    <div id="title_page" className="col-12"><h2 className="hidden-sm hidden-xs GHfont printable">{data?.title ?? 'รายงานข้อมูลผังเมือง'}</h2><h3 className="hidden-md hidden-lg GHfont unprintable">{data?.title ?? 'รายงานข้อมูลผังเมือง'}</h3>
      <div className="row" id="info_bar"><h3 id="cp_value">{data ? `จำนวน ${data.total} ผัง` : ''}</h3></div><h4 id="print_search" /></div>
    <div className="container-fluid"><div className="row unprintable"><div id="div-btn" className="col-xs-5 col-md-6"><button className="btn btn-danger btn-md" id="btn_goback" style={{ marginTop: 6 }} onClick={onBack}>ย้อนกลับ</button></div></div>
      {error && <div className="alert-error" role="alert">{error} <button className="btn-sm" onClick={() => setRevision(value => value + 1)}>ลองอีกครั้ง</button></div>}
      {!data && !error && <p className="loading-box">กำลังโหลดรายการผังเมือง...</p>}
      {data && <>
        {!isEvaluation && <section id="status_table" className="row info-frame"><span className="info-title">สรุปจำนวนผังตามสถานะขั้นตอนดำเนินการ</span><div><div className="table-responsive">
          <table className="table table-bordereds dpt-summary-table"><thead><tr><th>สถานะ</th><th>ขั้นตอนหลัก</th><th>ขั้นตอนย่อย 1</th><th>ขั้นตอนย่อย 2</th></tr></thead>
            <tbody>{geographyStatuses.filter(row => row.id <= 6).map(row => <React.Fragment key={row.id}><tr id={rowIds[row.id - 1]}><th>{row.label.replace(/^ผัง/, '')}</th>{counters(count(row.id), row.id)}</tr>
              {row.id === 1 && <tr id="status_x"><th>ผังที่ระบุขั้นตอน</th>{counters(count(9), 9)}</tr>}</React.Fragment>)}
              <tr id="status_all"><th>รวม</th>{counters(data.total)}</tr><tr style={{ height: 14, background: 'transparent' }}><td colSpan={4} style={{ border: 0, padding: 0 }} /></tr>
              <tr id="status_external"><th>เสนอหน่วยงานที่เกี่ยวข้อง</th>{counters(count(8), 8)}</tr></tbody></table>
        </div></div><p className="search-note">หมายเหตุ : คลิกที่จำนวนเพื่อแสดงรายการผัง</p></section>}
        <section className="row search-frame unprintable"><span className="search-title">ค้นหาข้อมูล</span><div className="search-content-area">
          <label className="search-row"><span>จังหวัด :</span><select aria-label="จังหวัดในรายงานผังเมือง" value={province} onChange={e => { setProvince(e.target.value); setSearch(''); }}><option value="">เลือกจังหวัด</option>{provinces.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}</select></label>
          <label className="search-row"><span>ชื่อผัง :</span><input aria-label="ชื่อผังในรายงานผังเมือง" placeholder="ระบุชื่อผังเมือง . . . . . . ." value={search} onChange={e => { setSearch(e.target.value); setProvince(''); }} /></label>
          <p className="search-note">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น</p>
        </div></section>
        <div className="dpt-report-caption"><span>{items.length} ผัง</span>{status && <button className="dpt-link unprintable" onClick={() => setStatus(undefined)}>แสดงทุกสถานะ</button>}<button className="btn btn-default unprintable" onClick={() => window.print()}>พิมพ์รายงาน</button></div>
        <div id="cityplanshow" className="table-responsive"><table className="table table-bordereds dpt-plan-report"><thead><tr><th>ลำดับ</th><th>จังหวัด</th><th>รายชื่อผังเมืองรวม</th><th>ผู้ดำเนินการ</th><th>ขอบเขต</th><th>สถานะผัง</th><th>ขั้นตอนหลัก</th><th>ขั้นตอนย่อย 1</th><th>ผลดำเนินการ</th><th>หมายเหตุการดำเนินการ</th></tr></thead>
          <tbody>{items.map((plan: UrbanPlan, index) => { const health = geographyStatuses.find(row => row.id < 7 && matchesGeographyStatus(plan, row.id))!;
            return <tr key={plan.id}><td>{index + 1}</td><td>{plan.provinceName}</td><td><button className="dpt-link text_cp_name" onClick={() => setSelectedPlanId(plan.id)}>{plan.name}</button></td><td>{plan.operator}</td><td>{plan.scope}</td><td>{plan.stage}</td><td>{plan.currentStep}</td><td>{plan.subStep}</td>
              <td style={{ background: health.background, color: health.id === 2 || health.id === 5 ? '#fff' : '#111' }}>{health.label}</td><td><button className="dpt-link" onClick={() => setSelectedPlanId(plan.id)}>รายละเอียด / หมายเหตุ</button></td></tr>;
          })}{!items.length && <tr><td className="empty-cell" colSpan={10}>ไม่พบข้อมูลผังเมืองตามเงื่อนไขที่เลือก</td></tr>}</tbody></table></div>
      </>}
    </div>
    {selectedPlanId !== null && <PlanDetailModal planId={selectedPlanId} steps={steps} onClose={() => setSelectedPlanId(null)} onUpdated={async () => { await onUpdated(); setRevision(value => value + 1); }} />}
  </div>;
}
