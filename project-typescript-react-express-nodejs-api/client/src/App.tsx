import React, { useCallback, useEffect, useRef, useState } from 'react';
import { api } from './api/client';
import { CreatePlanModal } from './components/CreatePlanModal';
import { AdministrativeGeographyPage } from './components/AdministrativeGeographyPage';
import { TreeReportPage } from './components/TreeReportPage';
import { StatusReportPage } from './components/StatusReportPage';
import { HealthBadge } from './components/HealthBadge';
import { PlanDetailModal } from './components/PlanDetailModal';
import { Sidebar } from './components/Sidebar';
import { DirectoryPage } from './components/DirectoryPage';
import { StepsPage } from './components/StepsPage';
import { WorkspacePage } from './components/WorkspacePage';
import { DataTransferPage } from './components/DataTransferPage';
import { ModulePage } from './components/ModulePage';
import { ReportCatalogPage } from './components/ReportCatalogPage';
import { InteractiveMap } from './components/InteractiveMap';
import { AccountsPage, AuthPanel } from './components/AuthPanel';
import { HistoryPage, SystemPage } from './components/SystemPages';
import { directoryTabs, DirectoryKind, getTab, menuItems, NavTab } from './data/navigation';
import {
  ActivityLog,
  KethInfo,
  PlanCategory,
  PlanHealth,
  PlanStage,
  ProvinceInfo,
  RegionInfo,
  StepDefinition,
  UrbanPlan,
} from './types/plan';

export function App() {
  const [tab, setTab] = useState<NavTab>(getTab);
  const [sidebarCollapsed, setSidebarCollapsed] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [showScrollTop, setShowScrollTop] = useState(false);
  const navigate = (next: NavTab) => {
    if (next !== tab) {
      setFilterRegion(''); setFilterKeth(''); setFilterHealth(''); setFilterStage(''); setSearch('');
    }
    window.location.hash = next;
    setTab(next);
    setMobileMenuOpen(false);
  };

  useEffect(() => {
    document.body.classList.remove('hold-transition');
    const handleHash = () => { setTab(getTab()); setMobileMenuOpen(false); };
    const handleScroll = () => setShowScrollTop(window.scrollY > 20);
    const handleKey = (event: KeyboardEvent) => { if (event.key === 'Escape') setMobileMenuOpen(false); };
    window.addEventListener('hashchange', handleHash);
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('keydown', handleKey);
    return () => {
      window.removeEventListener('hashchange', handleHash);
      window.removeEventListener('scroll', handleScroll);
      window.removeEventListener('keydown', handleKey);
    };
  }, []);
  const [regions, setRegions] = useState<RegionInfo[]>([]);
  const [keths, setKeths] = useState<KethInfo[]>([]);
  const [plans, setPlans] = useState<UrbanPlan[]>([]);
  const [logs, setLogs] = useState<ActivityLog[]>([]);
  const [steps, setSteps] = useState<StepDefinition[]>([]);
  const [provinces, setProvinces] = useState<ProvinceInfo[]>([]);
  const [page, setPage] = useState(1);
  const [pageSize, setPageSize] = useState(100);
  useEffect(() => { setPage(1); }, [plans]);
  const pageCount = Math.max(1, Math.ceil(plans.length / pageSize));
  const visiblePlans = plans.slice((Math.min(page, pageCount) - 1) * pageSize, Math.min(page, pageCount) * pageSize);

  const [search, setSearch] = useState('');
  const [filterRegion, setFilterRegion] = useState<string>('');
  const [filterKeth, setFilterKeth] = useState<string>('');
  const [filterHealth, setFilterHealth] = useState<string>('');
  const [filterStage, setFilterStage] = useState<string>('');

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null);
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [refreshToken, setRefreshToken] = useState(0);
  const requestVersion = useRef(0);

  const loadAll = useCallback(async () => {
    const version = ++requestVersion.current;
    setError('');
    try {
      const categoryFilter: PlanCategory | undefined =
        tab === 'm35' ? 'm35' : tab === 'evaluation' ? 'evaluation' : undefined;
      const filterTable = ['plans', 'm35', 'evaluation'].includes(tab);

      const [metaRes, regRes, kethRes, plansRes, logsRes] = await Promise.all([
        api.getMeta(),
        api.getRegions(),
        api.getKeths(),
        api.getPlans(filterTable ? {
          region: filterRegion ? Number(filterRegion) : undefined,
          keth: filterKeth ? Number(filterKeth) : undefined,
          health: filterHealth ? (filterHealth as PlanHealth) : undefined,
          stage: filterStage ? (filterStage as PlanStage) : undefined,
          category: categoryFilter,
          search: search || undefined,
        } : undefined),
        api.getLogs(),
      ]);

      if (version !== requestVersion.current) return;
      setSteps(metaRes.steps);
      setProvinces(metaRes.provinces);
      setRegions(regRes);
      setKeths(kethRes);
      setPlans(plansRes.items);
      setLogs(logsRes);
    } catch (err) {
      if (version !== requestVersion.current) return;
      setError(
        err instanceof Error
          ? err.message
          : 'ไม่สามารถเชื่อมต่อ Express API ได้ กรุณาตรวจสอบว่า backend ทำงานอยู่'
      );
    } finally {
      if (version === requestVersion.current) setLoading(false);
    }
  }, [tab, filterRegion, filterKeth, filterHealth, filterStage, search]);

  useEffect(() => { setLoading(true); }, [tab]);

  useEffect(() => {
    if (!['regions', 'keths', 'reports', 'dashboard', 'status'].includes(tab)) void loadAll();
    return () => { requestVersion.current++; };
  }, [loadAll, tab, refreshToken]);

  const handleResetDatabase = async () => {
    if (!window.confirm('คืนค่าข้อมูลทั้งหมดเป็นค่าเริ่มต้นหรือไม่? การแก้ไขข้อมูลในระบบจะถูกล้าง')) return;
    try {
      await api.resetDatabase();
      setFilterRegion('');
      setFilterKeth('');
      setFilterHealth('');
      setFilterStage('');
      setSearch('');
      setRefreshToken(token => token + 1);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'ไม่สามารถรีเซ็ตข้อมูลได้');
    }
  };

  const handleDeletePlan = async (plan: UrbanPlan) => {
    if (!window.confirm(`ลบผังเมือง “${plan.name}” และหมายเหตุที่เกี่ยวข้องหรือไม่?`)) return;
    try {
      await api.deletePlan(plan.id);
      await loadAll();
    } catch (err) {
      setError(err instanceof Error ? err.message : 'ไม่สามารถลบข้อมูลได้');
    }
  };

  const renderPlanTable = (title: string, subtitle: string) => (
    <div className="panel">
      <div className="panel-header">
        <div>
          <h2>{title}</h2>
          <p className="muted-sub">{subtitle}</p>
        </div>
        <button className="btn-primary" onClick={() => setShowCreateModal(true)}>
          + เพิ่มผังเมือง
        </button>
      </div>

      <div className="filter-bar">
        <input
          type="text"
          placeholder="ค้นหาชื่อผัง, รหัส, จังหวัด..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
        <select value={filterRegion} onChange={(e) => setFilterRegion(e.target.value)}>
          <option value="">ทุกภาค (5 ภาค)</option>
          {regions.map((r) => (
            <option key={r.id} value={r.id}>
              {r.name}
            </option>
          ))}
        </select>
        <select value={filterKeth} onChange={(e) => setFilterKeth(e.target.value)}>
          <option value="">ทุกเขตตรวจราชการ</option>
          {keths.map((k) => (
            <option key={k.kethId} value={k.kethId}>
              {k.name}
            </option>
          ))}
        </select>
        <select value={filterHealth} onChange={(e) => setFilterHealth(e.target.value)}>
          <option value="">ทุกสถานะความก้าวหน้า</option>
          <option value="on_track">มีความก้าวหน้า</option>
          <option value="slight_delay">ล่าช้าเล็กน้อย</option>
          <option value="moderate_delay">ล่าช้าปานกลาง</option>
          <option value="severe_delay">ล่าช้ามาก</option>
          <option value="unscheduled">ไม่ระบุวันดำเนินการ</option>
        </select>
        <select value={filterStage} onChange={(e) => setFilterStage(e.target.value)}>
          <option value="">ทุกลักษณะงาน</option>
          <option value="เปิดใหม่">เปิดใหม่</option>
          <option value="ปรับปรุง">ปรับปรุง</option>
          <option value="ประเมิน 5 ปี">ประเมิน 5 ปี</option>
          <option value="ประเมิน 10 ปี">ประเมิน 10 ปี</option>
        </select>
      </div>

      <div className="inline-controls unprintable"><label>รายการต่อหน้า <select aria-label="จำนวนรายการต่อหน้า" value={pageSize} onChange={event => { setPageSize(Number(event.target.value)); setPage(1); }}>{[25, 50, 100].map(size => <option key={size} value={size}>{size}</option>)}</select></label><button className="btn-sm" disabled={page <= 1} onClick={() => setPage(value => value - 1)}>ก่อนหน้า</button><span>หน้า {Math.min(page, pageCount)} / {pageCount} · {plans.length} รายการ</span><button className="btn-sm" disabled={page >= pageCount} onClick={() => setPage(value => value + 1)}>ถัดไป</button></div>
      <div className="table-wrap">
        <table className="data-table">
          <thead>
            <tr>
              <th>รหัส</th>
              <th>ชื่อผังเมือง</th>
              <th>จังหวัด / ภาค</th>
              <th>เขตฯ</th>
              <th>ลักษณะงาน</th>
              <th>ขั้นตอน</th>
              <th>สถานะความก้าวหน้า</th>
              <th>จัดการ</th>
            </tr>
          </thead>
          <tbody>
            {plans.length === 0 ? (
              <tr>
                <td colSpan={8} className="empty-cell">
                  ไม่พบข้อมูลผังเมืองตามเงื่อนไขที่เลือก
                </td>
              </tr>
            ) : (
              visiblePlans.map((p) => (
                <tr key={p.id}>
                  <td>
                    <span className="code-pill">{p.code}</span>
                    {p.isEec && <span className="eec-pill">EEC</span>}
                  </td>
                  <td>
                    <button
                      className="link-btn"
                      onClick={() => setSelectedPlanId(p.id)}
                    >
                      {p.name}
                    </button>
                  </td>
                  <td>
                    <div>{p.provinceName}</div>
                    <small className="muted-sub">{p.regionName}</small>
                  </td>
                  <td>เขต {p.kethId}</td>
                  <td>{p.stage}</td>
                  <td>
                    ขั้น {p.currentStep}.{p.subStep}
                  </td>
                  <td>
                    <HealthBadge health={p.health} delayDays={p.delayDays} />
                  </td>
                  <td className="actions-cell">
                    <button
                      className="btn-sm"
                      onClick={() => setSelectedPlanId(p.id)}
                    >
                      รายละเอียด
                    </button>
                    <button
                      className="btn-sm btn-danger"
                      onClick={() => handleDeletePlan(p)}
                    >
                      ลบ
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );

  return (
    <div className={`wrapper php-shell sidebar-mini ${sidebarCollapsed ? 'sidebar-collapse' : ''} ${mobileMenuOpen ? 'sidebar-open' : ''}`}>
      <header className="main-header">
        <a href="#regions" className="logo" title="ระบบการติดตามงานวางและจัดทำผังเมือง" onClick={() => navigate('regions')}>
          <span className="logo-mini"><b>UPMS</b></span>
          <span className="logo-lg"><b>UPMS</b></span>
        </a>
        <nav className="navbar navbar-static-top">
          <button className="sidebar-toggle" aria-controls="main-navigation" aria-label="เปิดหรือย่อเมนู" aria-expanded={window.innerWidth < 768 ? mobileMenuOpen : !sidebarCollapsed}
            onClick={() => window.innerWidth < 768 ? setMobileMenuOpen(!mobileMenuOpen) : setSidebarCollapsed(!sidebarCollapsed)} />
          <span className="mainname">ระบบการติดตามงานวางและจัดทำผังเมือง</span>
          <div className="navbar-custom-menu">
            <ul className="nav navbar-nav"><li className="user user-menu">
              <AuthPanel />
            </li></ul>
          </div>
        </nav>
      </header>
      <Sidebar tab={tab} navigate={navigate} />
      {mobileMenuOpen && <button className="sidebar-scrim" aria-label="ปิดเมนู" onClick={() => setMobileMenuOpen(false)} />}
      <div className="content-wrapper">
        <section className="content">
          <main id="mainshow" className={`content${tab === 'reports' ? ' tree-report-main' : ''}`}>
            {tab === 'regions' ? <AdministrativeGeographyPage key="regions" onBack={() => navigate('reports')} /> :
              tab === 'reports' ? <TreeReportPage /> :
              tab === 'dashboard' ? <StatusReportPage key="dashboard" onBack={() => navigate('reports')} /> :
              tab === 'status' ? <StatusReportPage key="status" current onBack={() => navigate('reports')} /> :
              tab === 'keths' ? <AdministrativeGeographyPage key="keths" inspectorates onBack={() => navigate('reports')} /> : (
              <div className="legacy-tools">
          <div className="page-heading"><h1>{menuItems.find(item => item.id === tab)?.label}</h1><span>ระบบติดตามผังเมือง / {menuItems.find(item => item.id === tab)?.label}</span></div>
          {error && <div className="alert-error">{error}</div>}
          {loading ? (
            <div className="loading-box">กำลังโหลดข้อมูล...</div>
          ) : (
            <>
              {tab === 'plans' &&
                renderPlanTable(
                  'จัดการข้อมูลผังเมืองรวม',
                  'คลิกที่ชื่อผังเมืองเพื่อดูรายละเอียด อัปเดตขั้นตอน และเพิ่มหมายเหตุ'
                )}

              {tab === 'm35' &&
                renderPlanTable(
                  'ผังเมืองในขั้นตอน ม.35',
                  'รายการผังเมืองที่อยู่ระหว่างดำเนินการตามมาตรา 35'
                )}

              {tab === 'evaluation' &&
                renderPlanTable(
                  'ผังเมืองในขั้นตอนงานประเมินผล',
                  'รายการผังเมืองที่อยู่ในรอบประเมินผล 5 ปี / 10 ปี'
                )}

              {directoryTabs.includes(tab as DirectoryKind) && <DirectoryPage key={tab} kind={tab as DirectoryKind} onUpdated={loadAll} />}
              {tab === 'steps' && <StepsPage steps={steps} plans={plans} onUpdated={loadAll} />}
              {tab === 'import' && <DataTransferPage onImported={loadAll} />}
              {tab.startsWith('module-') && <ModulePage key={tab} kind={tab.slice(7)} onUpdated={loadAll} />}
              {tab === 'catalog' && <ReportCatalogPage onOpenPlan={setSelectedPlanId} />}
              {tab === 'map' && <InteractiveMap plans={plans} onOpenPlan={setSelectedPlanId} />}
              {tab === 'accounts' && <AccountsPage />}
              {tab === 'system' && <SystemPage />}
              {tab === 'history' && <HistoryPage onOpenPlan={setSelectedPlanId} />}
              {!tab.startsWith('module-') && !['dashboard', 'plans', 'm35', 'evaluation', 'logs', 'steps', 'import', 'catalog', 'map', 'accounts', 'system', 'history', ...directoryTabs].includes(tab) &&
                <WorkspacePage key={tab} tab={tab} plans={plans} regions={regions} steps={steps} onOpenPlan={setSelectedPlanId} />}

              {tab === 'logs' && (
                <div className="panel">
                  <div className="panel-header">
                    <div>
                      <h2>บันทึกกิจกรรม (Activity Logs)</h2>
                      <p className="muted-sub">
                        บันทึกการเพิ่ม แก้ไข ลบ และอัปเดตข้อมูลในระบบ
                      </p>
                    </div>
                    <button className="btn-secondary" onClick={handleResetDatabase}>คืนค่าข้อมูลเริ่มต้น</button>
                  </div>
                  <div className="table-wrap">
                    <table className="data-table">
                      <thead>
                        <tr>
                          <th>เวลา</th>
                          <th>ผู้ดำเนินการ</th>
                          <th>กิจกรรม</th>
                          <th>เป้าหมาย</th>
                          <th>รายละเอียด</th>
                        </tr>
                      </thead>
                      <tbody>
                        {logs.map((l) => (
                          <tr key={l.id}>
                            <td>{new Date(l.createdAt).toLocaleString('th-TH')}</td>
                            <td>{l.actor}</td>
                            <td>
                              <span className="code-pill">{l.action}</span>
                            </td>
                            <td>{l.target}</td>
                            <td>{l.detail}</td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              )}
            </>
          )}
              </div>
            )}
          </main>
        </section>
      </div>
      {showScrollTop && <div id="myBtn">
        {tab !== 'regions' && <><button className="myBtn2" title="กลับหน้าหลัก" onClick={() => navigate('regions')}><i className="fa fa-home" /></button><br /></>}
        <button className="myBtn" title="กลับขึ้นด้านบน" onClick={() => window.scrollTo({ top: 0 })}>▲</button>
      </div>}

      {selectedPlanId !== null && (
        <PlanDetailModal
          planId={selectedPlanId}
          steps={steps}
          onClose={() => setSelectedPlanId(null)}
          onUpdated={loadAll}
        />
      )}

      {showCreateModal && (
        <CreatePlanModal
          provinces={provinces}
          initialCategory={tab === 'm35' ? 'm35' : tab === 'evaluation' ? 'evaluation' : 'general'}
          onClose={() => setShowCreateModal(false)}
          onCreate={async (payload) => {
            await api.createPlan(payload);
            await loadAll();
          }}
        />
      )}
    </div>
  );
}

export default App;
