import React, { useEffect, useState } from 'react';
import { menuItems, NavTab } from '../data/navigation';

export function Sidebar({ tab, navigate }: { tab: NavTab; navigate: (tab: NavTab) => void }) {
  const [expanded, setExpanded] = useState<string[]>(['manage']);
  const manageTabs = ['plans', 'import', 'm35', 'evaluation', 'evaluationSteps', 'offices', 'notes', 'noteCategories', 'administrators', 'reasons'];
  const reportTabs = ['progress', 'operating', 'legislation', 'schedule', 'logs', 'catalog', 'map', 'accounts', 'system', 'history'];
  const moduleTabs = menuItems.filter(item => item.id.startsWith('module-')).map(item => item.id);
  useEffect(() => {
    const group = tab.startsWith('module-') ? 'modules' : tab.startsWith('committee') ? 'committee' : manageTabs.includes(tab) ? 'manage' : reportTabs.includes(tab) ? 'reports' : tab === 'contacts' ? 'contacts' : undefined;
    if (group) setExpanded(current => current.includes(group) ? current : [...current, group]);
  }, [tab]);
  const leaf = (id: string, nested = false) => {
    const item = menuItems.find(item => item.id === id)!;
    return <li key={id} className={tab === id ? 'active' : ''}>
      <a href={`#${id}`} data-nav-tab={id} onClick={() => navigate(id)} title={item.label} aria-current={tab === id ? 'page' : undefined}>
        {!nested && <i className={`fa fa-${id === 'reports' ? 'home' : id === 'dashboard' ? 'bar-chart' : id === 'steps' ? 'wrench' : item.icon}`} aria-hidden="true" />}
        <span>{nested ? ' - ' : ' '}{id === 'dashboard' ? 'รายงานสถานภาพผังเมือง' : item.label}</span>
      </a>
    </li>;
  };
  const group = (id: string, title: string, icon: string, ids: string[]) => {
    const open = expanded.includes(id);
    return <li className={`treeview${open ? ' menu-open' : ''}`} data-menu-group={id}>
      <a href={`#menu-${id}`} className="menu-section-toggle" aria-expanded={open} onClick={event => {
        event.preventDefault(); setExpanded(current => current.includes(id) ? current.filter(value => value !== id) : [...current, id]);
      }}><i className={`fa fa-${icon}`} aria-hidden="true" /> <span>{title}</span><span className="pull-right-container"><i className="fa fa-angle-left pull-right" aria-hidden="true" /></span></a>
      <ul className="treeview-menu menu-section-items" hidden={!open} style={{ display: open ? 'block' : 'none' }}>{ids.map(id => leaf(id, true))}</ul>
    </li>;
  };
  return <aside className="main-sidebar" id="main-navigation" aria-label="เมนูหลัก">
    <nav className="sidebar">
      <ul className="sidebar-menu tree dpt-sidebar-menu">
        <li className="header dpt-sidebar-role">ผู้ดูแลระบบ : <a className="u-manual" target="_blank" rel="noopener noreferrer" href="/php/pdf/UPMS_SuperAdmin_Manual.pdf">คู่มือใช้งานระบบ <i className="fa fa-file-pdf-o" aria-hidden="true" /></a></li>
        {leaf('reports')}{leaf('dashboard')}
        {group('committee', 'คณะติดตามผลการดำเนินการ', 'line-chart', Array.from({ length: 7 }, (_, i) => `committee${i + 1}`))}
        {['regions', 'keths', 'status', 'search', 'steps', 'export'].map(id => leaf(id))}
        <li className="dpt-menu-divider" role="separator" />
        {group('contacts', 'ติดต่อประสานงาน', 'phone', ['contacts'])}
        <li className="dpt-menu-divider" role="separator" />
        {group('manage', 'จัดการข้อมูลผังเมืองรวม', 'pencil-square-o', manageTabs)}
        <li className="dpt-menu-divider" role="separator" />
        {group('reports', 'รายงานและเครื่องมือ', 'bar-chart', reportTabs)}
        {group('modules', 'งานขั้นตอนและระบบอุปกรณ์', 'cubes', moduleTabs)}
      </ul>
    </nav>
  </aside>;
}
