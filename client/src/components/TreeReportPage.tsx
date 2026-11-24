import React, { useCallback, useEffect, useState } from 'react';
import { api } from '../api/client';
import { TREE_REPORT_BOTH_OWNERS_HTML, TREE_REPORT_CSS, TREE_REPORT_SINGLE_OWNER_HTML } from '../data/treeReportTemplate';
import type { ProvinceInfo, StepDefinition } from '../types/plan';
import { TreeReportList } from './TreeReportList';

type TreeReport = Awaited<ReturnType<typeof api.getTreeReport>>;
const getGroup = () => {
  const parts = window.location.hash.slice(1).split('/');
  return parts[0] === 'reports' && parts[1] === 'list' && /^[\w-]+$/.test(parts[2] ?? '') ? parts[2] : '';
};

export function TreeReportPage() {
  const [report, setReport] = useState<TreeReport | null>(null);
  const [steps, setSteps] = useState<StepDefinition[]>([]);
  const [provinces, setProvinces] = useState<ProvinceInfo[]>([]);
  const [group, setGroup] = useState(getGroup);
  const [error, setError] = useState('');
  const [revision, setRevision] = useState(0);
  const [helpPage, setHelpPage] = useState(0);
  const refresh = useCallback(async () => {
    const [data, meta] = await Promise.all([api.getTreeReport(), api.getMeta()]);
    setReport(data); setSteps(meta.steps); setProvinces(meta.provinces); setError('');
  }, []);
  useEffect(() => {
    let active = true;
    Promise.all([api.getTreeReport(), api.getMeta()]).then(([data, meta]) => {
      if (active) { setReport(data); setSteps(meta.steps); setProvinces(meta.provinces); setError(''); }
    }).catch(err => { if (active) setError(err instanceof Error ? err.message : 'ไม่สามารถโหลดรายงานได้'); });
    return () => { active = false; };
  }, [revision]);
  useEffect(() => {
    const onHash = () => { setGroup(getGroup()); setHelpPage(0); };
    window.addEventListener('hashchange', onHash);
    return () => window.removeEventListener('hashchange', onHash);
  }, []);
  useEffect(() => {
    if (!report || group) return;
    try {
      if (sessionStorage.getItem('upms-tree-guide')) return;
      const timer = window.setTimeout(() => {
        try { sessionStorage.setItem('upms-tree-guide', 'seen'); } catch { /* Storage can be disabled. */ }
        setHelpPage(1);
      }, 1000);
      return () => window.clearTimeout(timer);
    } catch { /* The guide remains available from the information icon. */ }
  }, [report, group]);
  const openGroup = (key: string) => {
    if (!report?.groups.some(group => group.key === key)) return;
    setGroup(key); setHelpPage(0); window.location.hash = `reports/list/${key}`; window.scrollTo({ top: 0 });
  };
  const activate = (target: EventTarget) => {
    if (!(target instanceof Element)) return;
    const key = target.closest<HTMLElement>('[data-tree-filter]')?.dataset.treeFilter;
    if (key) openGroup(key);
    else if (target.closest('[data-tree-help]')) setHelpPage(2);
  };
  if (error) return <div className="alert-error" role="alert">{error} <button className="btn-sm" onClick={() => setRevision(value => value + 1)}>ลองอีกครั้ง</button></div>;
  if (!report) return <div className="loading-box" role="status">กำลังโหลดรายงานข้อมูลผังเมือง...</div>;
  if (group) return <TreeReportList key={group} group={group} steps={steps} provinces={provinces}
    onBack={() => { window.location.hash = 'reports'; setGroup(''); window.scrollTo({ top: 0 }); }} onUpdated={refresh} />;
  const date = new Date(report.asOf).toLocaleDateString('th-TH', { timeZone: 'Asia/Bangkok', day: 'numeric', month: 'short', year: 'numeric' });
  const template = report.counts['eecY-2'] === 0 ? TREE_REPORT_SINGLE_OWNER_HTML : TREE_REPORT_BOTH_OWNERS_HTML;
  const html = template.replace(/\{\{([^}]+)\}\}/g, (_match, key: string) => key === 'date' ? date : Number.isFinite(report.counts[key]) ? String(report.counts[key]) : '—');
  return <div className="php-tree-report">
    <style>{TREE_REPORT_CSS}</style>
    <div className="php-tree-content" onClick={event => activate(event.target)} onKeyDown={event => {
      if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); activate(event.target); }
    }} dangerouslySetInnerHTML={{ __html: html }} />
    {helpPage > 0 && <div className="modal-backdrop"><div className="modal-card" role="dialog" aria-modal="true" aria-labelledby="tree-guide-title">
      <div className="modal-header"><h4 id="tree-guide-title" style={{ color: '#000' }}>แนะนำการใช้งาน ({helpPage}/2)</h4><button className="btn-icon" aria-label="ปิดคำแนะนำ" onClick={() => setHelpPage(0)}>×</button></div>
      <div className="modal-body" style={{ textAlign: 'center' }}>
        <h4 style={{ color: '#000' }}>{helpPage === 1 ? 'กรุณาเลื่อนหน้าจอเพื่อแสดงรายละเอียด' : 'คลิกที่จำนวนผัง เพื่อเปิดเข้าไปสู่หน้ารายการแสดงผลผัง'}</h4>
        <img src={`/php/img/${helpPage === 1 ? 'swipe-1.gif' : 'clickX.gif'}`} alt={helpPage === 1 ? 'เลื่อนแผนผังในแนวนอน' : 'เลือกตัวเลขจำนวนผัง'} style={{ display: 'block', width: helpPage === 1 ? 220 : 80, maxWidth: '100%', margin: '25px auto' }} />
        <div className="modal-footer"><button className="btn btn-default" onClick={() => setHelpPage(0)}>ปิด</button>{helpPage === 1 && <button className="btn btn-success" onClick={() => setHelpPage(2)}>ถัดไป</button>}</div>
      </div></div></div>}
  </div>;
}
