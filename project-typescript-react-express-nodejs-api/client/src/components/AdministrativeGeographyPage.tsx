import React, { useCallback, useEffect, useState } from 'react';
import { api } from '../api/client';
import { geographyHash, geographyStatuses, GeographyRoute, getGeographyRoute, matchesGeographyStatus } from '../data/geography';
import { PHP_KETHS, PHP_REGIONS } from '../data/phpReference';
import type { StepDefinition, UrbanPlan } from '../types/plan';
import { PlanDetailModal } from './PlanDetailModal';

const darkColors = ['#5d5d13', '#79345e', '#2a4722', '#1c7179', '#1c3e61'];

function StatusLegend() {
  return <div className="dpt-status-legend">
    <div className="dpt-legend-title"><h3>คำอธิบายข้อมูล</h3></div>
    <div className="table-responsive"><table id="table_detail_status" className="table"><tbody>
      {geographyStatuses.map(status => <tr key={status.id}>
        <td>{status.id}</td><td className={`tds-color dpt-status-${status.id}`} style={{ background: status.background, border: `solid 2px ${status.border}` }}>
          {status.id === 7 && <i className="fa fa-exclamation" aria-hidden="true" />}</td><td>{status.label}</td>
      </tr>)}
    </tbody></table></div>
  </div>;
}

function StatusBar({ plans, onSelect, label }: { plans: UrbanPlan[] | null; onSelect: (status: number) => void; label: string }) {
  return <div className="status_bar dpt-status-bar" aria-label={`สถานะผัง${label}`}>
    {geographyStatuses.map(status => <button key={status.id} type="button" className={`dpt-status-count dpt-status-${status.id}`}
      style={{ background: status.background, borderColor: status.border }} data-status={status.id}
      title={`${label} · ${status.label}`} aria-label={`${label} · ${status.label} ${plans ? plans.filter(p => matchesGeographyStatus(p, status.id)).length : 'กำลังโหลด'} ผัง`}
      disabled={!plans} onClick={() => onSelect(status.id)}>
      {status.id === 7 && <i className="fa fa-exclamation" aria-hidden="true" />}
      {plans ? plans.filter(p => matchesGeographyStatus(p, status.id)).length : '—'}
    </button>)}
  </div>;
}

// Structure and interactions follow DPT_UPMS2/app/show_extra.php,
// app/show_page_park-keth.php and report/show_detail_x.php.
export function AdministrativeGeographyPage({ onBack, inspectorates = false }: { onBack: () => void; inspectorates?: boolean }) {
  const heading = inspectorates ? 'ผังตามเขตตรวจราชการ' : 'ผังตามขอบเขตการปกครอง';
  const rootHash = inspectorates ? '#keths' : '#regions';
  const [route, setRoute] = useState<GeographyRoute>(getGeographyRoute);
  const [plans, setPlans] = useState<UrbanPlan[] | null>(null);
  const [steps, setSteps] = useState<StepDefinition[]>([]);
  const [error, setError] = useState('');
  const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null);
  const [search, setSearch] = useState('');
  const [provinceFilter, setProvinceFilter] = useState('');
  const [retry, setRetry] = useState(0);
  const refresh = useCallback(async () => {
    const [result, meta] = await Promise.all([api.getPlans(), api.getMeta()]);
    setPlans(result.items); setSteps(meta.steps); setError('');
  }, []);
  useEffect(() => {
    let active = true;
    Promise.all([api.getPlans(), api.getMeta()]).then(([result, meta]) => {
      if (active) { setPlans(result.items); setSteps(meta.steps); setError(''); }
    }).catch(err => { if (active) setError(err instanceof Error ? err.message : 'โหลดข้อมูลไม่สำเร็จ'); });
    return () => { active = false; };
  }, [retry]);
  useEffect(() => {
    const handleHash = () => { setRoute(getGeographyRoute()); setSearch(''); setProvinceFilter(''); setSelectedPlanId(null); };
    window.addEventListener('hashchange', handleHash);
    return () => window.removeEventListener('hashchange', handleHash);
  }, []);
  const go = (next: GeographyRoute) => {
    const hash = geographyHash(next, inspectorates);
    if (hash !== window.location.hash) window.history.pushState({ upmsGeographyParent: window.location.hash }, '', hash);
    setRoute(next); setSearch(''); setProvinceFilter(''); setSelectedPlanId(null);
    window.scrollTo({ top: 0 });
  };
  const back = () => {
    if (window.history.state?.upmsGeographyParent?.startsWith(rootHash)) { window.history.back(); window.scrollTo({ top: 0 }); }
    else if (route.report) go({ regionId: route.provinceId ? route.regionId : undefined, kethId: route.provinceId ? route.kethId : undefined, report: false });
    else if (route.regionId || route.kethId) go({ report: false });
    else onBack();
  };
  const availableGroups = inspectorates ? PHP_KETHS.map(keth => ({ ...keth, id: keth.kethId, regionId: keth.provinces[0].regionId })) : PHP_REGIONS.map(region => ({ ...region, regionId: region.id }));
  const region = availableGroups.find(group => group.id === (inspectorates ? route.kethId : route.regionId));
  const groupRoute = (id: number, report: boolean, status?: number, provinceId?: number): GeographyRoute => ({ ...(inspectorates ? { kethId: id } : { regionId: id }), report, status, provinceId });
  const allProvinces = PHP_REGIONS.flatMap(group => group.provinces);
  const province = allProvinces.find(province => province.id === route.provinceId);
  const scopePlans = plans?.filter(p => (!region || (inspectorates ? p.kethId : p.regionId) === region.id) && (!province || p.provinceId === province.id)) ?? null;
  const reportPlans = scopePlans?.filter(p => (!route.status || matchesGeographyStatus(p, route.status)) &&
    (!provinceFilter || p.provinceId === Number(provinceFilter)) && `${p.name} ${p.code}`.toLowerCase().includes(search.trim().toLowerCase())) ?? [];
  const reportRoute = (status?: number): GeographyRoute => ({ ...route, report: true, status });
  const title = route.report ? province?.name ?? region?.name ?? heading : heading;
  const groups = region ? [region] : availableGroups;
  const count = (items: UrbanPlan[] | null) => items ? items.length : '—';
  const summaryRows = geographyStatuses.filter(status => status.id <= 6);
  const summaryRowIds = ['status_white_step', 'status_green', 'status_yellow', 'status_orange', 'status_red', 'status_white_date'];
  return <div className={`geography-page dpt-geography${inspectorates ? ' inspectorates' : ''}${route.report ? ' dpt-geography-report' : ''}`}>
    <div id="title_page" className="col-12">
      <h2 className="hidden-sm hidden-xs GHfont printable">{title}</h2>
      <h3 className="hidden-md hidden-lg GHfont-mini unprintable">{title}</h3>
      <div className="row" id="info_bar"><h3 id="search_value">{route.report ? `จำนวน ${count(scopePlans)} ผัง` : ''}</h3></div><h4 id="print_search" />
    </div><br />
    <div className="container-fluid">
      <div className="row unprintable"><div id="div-btn" className="col-xs-5 col-md-6">
        <button id="btn_backy" className="pull-left btn btn-danger btn-md" onClick={back}>ย้อนกลับ</button>
      </div></div>
      {error && <div className="alert-error dpt-geography-error unprintable" role="alert">{error} <button className="btn-sm" onClick={() => setRetry(value => value + 1)}>ลองอีกครั้ง</button></div>}
      {!plans && !error && <p role="status" className="dpt-loading">กำลังโหลดข้อมูล...</p>}
      {!route.report ? <div id="extra-content-area">
        <div id="extra-content-area-r1" className="col-xs-12 col-sm-5 col-md-4">
          <div className={`map-container${region || inspectorates ? ' dpt-region-map' : ''}`}><img className="a-img" src={inspectorates ? `/php/img/keth/${region ? region.id : 'main'}.png` : region ? `/php/img/map/${region.id}.jpg` : '/php/img/map/main.jpg'} alt={region ? `แผนที่${region.name}` : inspectorates ? 'แผนที่เขตตรวจราชการ 18 เขต' : 'แผนที่ประเทศไทยแบ่งตามขอบเขตการปกครอง 5 ภาค'} /></div>
          <StatusLegend />
        </div>
        <div id="extra-sideshow" className="col-xs-12 col-sm-7 col-md-8">
          {groups.map(group => {
            const items = plans?.filter(p => (inspectorates ? p.kethId : p.regionId) === group.id) ?? null;
            const provinces = group.provinces.filter(p => plans?.some(plan => plan.provinceId === p.id));
            const rows = Array.from({ length: Math.ceil(provinces.length / 5) }, (_, i) => provinces.slice(i * 5, i * 5 + 5));
            return <React.Fragment key={group.id}>
              <div className="extra-sideshow-inner" {...(inspectorates ? { 'data-keth': group.id } : { 'data-region': group.id })} style={{ borderColor: group.color }}>
                <h3 className="div-h" style={{ color: group.color }}>
                  <button className="dpt-link park-name" onClick={() => go(groupRoute(group.id, Boolean(region)))}>{group.name}</button>{' '}
                  (จำนวน <button className="dpt-link park-value" style={{ color: darkColors[group.regionId - 1] }} onClick={() => go(groupRoute(group.id, true))}>{count(items)}</button> ผัง)
                </h3>
                <h4 className="div-h">สถานะขั้นตอนดำเนินการ - ขั้นตอนย่อย 1</h4>
                <StatusBar plans={items} label={group.name} onSelect={status => go(groupRoute(group.id, true, status))} />
                {!region && <div className="table-responsive"><table className="table dpt-province-table"><tbody>
                  {rows.map(row => <tr key={row[0].id} style={{ background: 'transparent' }}>{row.map(p => <td key={p.id} className="set-td" style={{ border: `solid 1px ${group.color}` }}>
                    <button className="dpt-link province-link" style={{ color: group.color }} onClick={() => go({ provinceId: p.id, report: true })}>{p.name} <b className="dpt-province-count">({plans?.filter(plan => plan.provinceId === p.id).length ?? '—'})</b></button>
                  </td>)}</tr>)}
                  {plans && !provinces.length && <tr><td className="empty-cell">ไม่พบข้อมูลผังเมือง</td></tr>}
                </tbody></table></div>}
              </div>
              {region && provinces.map((p, index) => {
                const provincePlans = items?.filter(plan => plan.provinceId === p.id) ?? null;
                return <div id={`pk_${index + 1}`} key={p.id} className="extra-sideshow-inner dpt-province-card">
                  <h3 className="div-h"><button className="dpt-link province-link" onClick={() => go(groupRoute(group.id, true, undefined, p.id))}>{p.name} ({count(provincePlans)})</button></h3>
                  <StatusBar plans={provincePlans} label={p.name} onSelect={status => go(groupRoute(group.id, true, status, p.id))} />
                </div>;
              })}
            </React.Fragment>;
          })}
        </div>
      </div> : <>
        <section id="status_table" className="row info-frame">
          <span className="info-title">สรุปจำนวนผังตามสถานะขั้นตอนดำเนินการ</span>
          <div><div className="table-responsive"><table className="table table-bordereds dpt-summary-table"><thead><tr><th>สถานะ</th><th>ขั้นตอนหลัก</th><th>ขั้นตอนย่อย 1</th><th>ขั้นตอนย่อย 2</th></tr></thead>
            <tbody>{summaryRows.map(status => <React.Fragment key={status.id}><tr id={summaryRowIds[status.id - 1]}>
              <th>{status.label.replace(/^ผัง/, '')}</th>
              <td><button className="dpt-link" onClick={() => go(reportRoute(status.id))}>{scopePlans ? scopePlans.filter(p => matchesGeographyStatus(p, status.id)).length : '—'}</button></td>
              <td><button className="dpt-link" onClick={() => go(reportRoute(status.id))}>{scopePlans ? scopePlans.filter(p => matchesGeographyStatus(p, status.id)).length : '—'}</button></td><td>—</td>
            </tr>{status.id === 1 && <tr id="status_x"><th>ผังที่ระบุขั้นตอน</th>{[0, 1].map(column => <td key={column}><button className="dpt-link" onClick={() => go(reportRoute(9))}>{scopePlans?.filter(p => matchesGeographyStatus(p, 9)).length ?? '—'}</button></td>)}<td>—</td></tr>}</React.Fragment>)}
            <tr id="status_all"><th>รวม</th>{[0, 1].map(column => <td key={column}><button className="dpt-link" onClick={() => go(reportRoute())}>{count(scopePlans)}</button></td>)}<td>—</td></tr>
            <tr style={{ background: 'transparent', height: 14 }}><td colSpan={4} style={{ border: 'transparent', padding: 0 }} /></tr>
            <tr id="status_external"><th>เสนอหน่วยงานที่เกี่ยวข้อง</th>{[0, 1].map(column => <td key={column}><button className="dpt-link" onClick={() => go(reportRoute(8))}>{scopePlans?.filter(p => matchesGeographyStatus(p, 8)).length ?? '—'}</button></td>)}<td>—</td></tr></tbody>
          </table></div></div><p className="search-note">หมายเหตุ : คลิกที่จำนวนเพื่อแสดงรายการผัง</p>
        </section>
        <p className="dpt-pending-note"><i className="fa fa-exclamation" aria-hidden="true" /> ผังที่ดำเนินการขั้นตอนปัจจุบันเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป จำนวน{' '}
          <button className="dpt-link" onClick={() => go(reportRoute(7))}>{scopePlans?.filter(p => matchesGeographyStatus(p, 7)).length ?? '—'}</button> ผัง</p>
        <section className="row search-frame unprintable"><span className="search-title">ค้นหาข้อมูล</span><div className="search-content-area">
          {!province && <label className="search-row"><span>จังหวัด :</span><select aria-label="จังหวัดในรายงาน" value={provinceFilter} onChange={e => { setProvinceFilter(e.target.value); setSearch(''); }}><option value="">เลือกจังหวัด</option>
            {(region?.provinces ?? allProvinces).filter(p => scopePlans?.some(plan => plan.provinceId === p.id)).map(p => <option key={p.id} value={p.id}>{p.name}</option>)}</select></label>}
          <label className="search-row"><span>ชื่อผัง :</span><input aria-label="ชื่อผังในรายงาน" placeholder="ระบุชื่อผังเมือง . . . . . . ." value={search} onChange={e => { setSearch(e.target.value); setProvinceFilter(''); }} /></label>
          <p className="search-note">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น</p></div>
        </section>
        <div className="dpt-report-caption"><span>{route.status === 9 ? 'ผังที่ระบุขั้นตอน' : route.status ? geographyStatuses.find(status => status.id === route.status)?.label : 'รายการผังเมืองรวมทั้งหมด'} · {reportPlans.length} ผัง</span>
          {route.status && <button className="dpt-link unprintable" onClick={() => go(reportRoute())}>แสดงทุกสถานะ</button>}
          <button className="btn btn-default unprintable" onClick={() => window.print()}>พิมพ์รายงาน</button></div>
        <div className="table-responsive" id="cityplanshow"><table className="table table-bordereds dpt-plan-report">
          <thead><tr><th rowSpan={2}>ลำดับ</th><th rowSpan={2}>จังหวัด</th><th rowSpan={2}>รายชื่อผังเมืองรวม</th><th rowSpan={2}>ผู้ดำเนินการ</th><th rowSpan={2}>ขอบเขต</th><th rowSpan={2}>สถานะผัง</th><th colSpan={3}>ขั้นตอนดำเนินการ</th><th rowSpan={2}>ผลดำเนินการ</th><th rowSpan={2}>หมายเหตุการดำเนินการ</th></tr>
            <tr><th>ขั้นตอนหลัก</th><th>ขั้นตอนย่อย 1</th><th>ขั้นตอนย่อย 2</th></tr></thead>
          <tbody>{reportPlans.map((plan, i) => { const status = geographyStatuses.find(status => status.id < 7 && matchesGeographyStatus(plan, status.id))!;
            return <tr key={plan.id}><td>{i + 1}</td><td>{plan.provinceName}</td><td><button className="dpt-link text_cp_name" onClick={() => setSelectedPlanId(plan.id)}>{plan.name}</button></td>
              <td>{plan.operator}</td><td>{plan.scope}</td><td>{plan.stage}</td><td title={steps.find(step => step.step === plan.currentStep)?.title}>{plan.currentStep}</td><td>{plan.subStep}</td><td>—</td>
              <td style={{ background: status.background, color: status.id === 2 || status.id === 5 ? '#fff' : '#111' }}><span>{status.label}</span>{plan.delayDays > 0 && <small> ({plan.delayDays} วัน)</small>}</td>
              <td><button className="dpt-link" onClick={() => setSelectedPlanId(plan.id)}>รายละเอียด / หมายเหตุ</button></td></tr>;
          })}{!reportPlans.length && <tr><td colSpan={11} className="empty-cell">{plans ? 'ไม่พบข้อมูลผังเมืองตามเงื่อนไขที่เลือก' : 'กำลังโหลดข้อมูล...'}</td></tr>}</tbody>
        </table></div>
      </>}
    </div>
    {!route.report && !region && <div id="extra" className="table-responsive" aria-hidden="true" />}
    {selectedPlanId !== null && <PlanDetailModal planId={selectedPlanId} steps={steps} onClose={() => setSelectedPlanId(null)} onUpdated={refresh} />}
  </div>;
}
