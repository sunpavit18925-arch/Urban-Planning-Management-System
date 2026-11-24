import React, { useEffect, useMemo, useRef, useState } from 'react';
import { UrbanPlan } from '../types/plan';
import { HealthBadge } from './HealthBadge';
import { PHP_REGIONS } from '../data/phpReference';

type Ring = number[][];
interface Feature { type: 'Feature'; properties: { pro_th: string; pro_code: string }; geometry: { type: 'MultiPolygon'; coordinates: Ring[][] } | { type: 'Polygon'; coordinates: Ring[] } }
interface GeoData { type: 'FeatureCollection'; features: Feature[] }
const healthColors = { on_track: '#2d882d', slight_delay: '#ffe841', moderate_delay: '#ffa500', severe_delay: '#e84b4b', unscheduled: '#e2e8f0' };
const healthOrder = ['on_track', 'slight_delay', 'moderate_delay', 'severe_delay', 'unscheduled'];
const provinceIds = new Map(PHP_REGIONS.flatMap(region => region.provinces).map(province => [province.name, province.id]));
function provincePath(feature: Feature): string {
  const polygons = feature.geometry.type === 'MultiPolygon' ? feature.geometry.coordinates : [feature.geometry.coordinates];
  return polygons.map(polygon => polygon.map(ring => ring.map((point, index) => `${index ? 'L' : 'M'}${point[0].toFixed(4)},${(24 - point[1]).toFixed(4)}`).join(' ') + 'Z').join(' ')).join(' ');
}

export function InteractiveMap({ plans, onOpenPlan }: { plans: UrbanPlan[]; onOpenPlan: (id: number) => void }) {
  const [geo, setGeo] = useState<GeoData | null>(null);
  const [error, setError] = useState('');
  const [selected, setSelected] = useState('');
  const [search, setSearch] = useState('');
  const [box, setBox] = useState({ x: 97, y: 3, width: 10, height: 17 });
  const drag = useRef<{ x: number; y: number; box: typeof box } | null>(null);
  const moved = useRef(false);
  useEffect(() => { const controller = new AbortController(); fetch('/data/provinces.geojson', { signal: controller.signal }).then(async response => { if (!response.ok) throw new Error('โหลดข้อมูลแผนที่ไม่ได้'); const data = await response.json() as GeoData; if (data.type !== 'FeatureCollection') throw new Error('รูปแบบแผนที่ไม่ถูกต้อง'); setGeo(data); }).catch(error => { if (error.name !== 'AbortError') setError(error.message); }); return () => controller.abort(); }, []);
  const shapes = useMemo(() => geo?.features.map(feature => ({ feature, path: provincePath(feature) })) ?? [], [geo]);
  const provincial = useMemo(() => {
    const result = new Map<number, UrbanPlan[]>();
    for (const plan of plans) { const entries = result.get(plan.provinceId) ?? []; entries.push(plan); result.set(plan.provinceId, entries); }
    return result;
  }, [plans]);
  function zoom(factor: number) { setBox(previous => { const width = Math.max(1, Math.min(20, previous.width * factor)); const height = width * 1.7; return { x: previous.x + (previous.width - width) / 2, y: previous.y + (previous.height - height) / 2, width, height }; }); }
  const entries = provincial.get(provinceIds.get(selected) ?? -1) ?? [];
  return <div className="panel interactive-map"><div className="panel-header"><div><h2>แผนที่ผังเมืองรายจังหวัด</h2><p className="muted-sub">เลือกจังหวัดเพื่อดูผัง ลากแผนที่เพื่อเลื่อน และใช้ปุ่มเพื่อขยายหรือย่อ</p></div><button className="btn-secondary" onClick={() => window.print()}>พิมพ์แผนที่</button></div>
    {error && <p className="alert-error" role="alert">{error}</p>}
    <div className="inline-controls"><label>ค้นหาจังหวัด <input value={search} onChange={event => setSearch(event.target.value)} /></label><button className="btn-secondary" aria-label="ขยายแผนที่" onClick={() => zoom(0.7)}>＋</button><button className="btn-secondary" aria-label="ย่อแผนที่" onClick={() => zoom(1.4)}>−</button><button className="btn-secondary" onClick={() => setBox({ x: 97, y: 3, width: 10, height: 17 })}>มุมมองเริ่มต้น</button></div>
    <div className="map-layout"><div>{!geo ? <p>กำลังโหลดแผนที่...</p> : <svg aria-label="แผนที่จังหวัดประเทศไทย" role="img" viewBox={`${box.x} ${box.y} ${box.width} ${box.height}`} onPointerDown={event => { moved.current = false; drag.current = { x: event.clientX, y: event.clientY, box }; }} onPointerMove={event => { if (!drag.current) return; if (event.buttons === 0) { drag.current = null; return; } const dx = event.clientX - drag.current.x; const dy = event.clientY - drag.current.y; if (Math.abs(dx) + Math.abs(dy) > 4) { moved.current = true; if (!event.currentTarget.hasPointerCapture(event.pointerId)) event.currentTarget.setPointerCapture(event.pointerId); } const bounds = event.currentTarget.getBoundingClientRect(); setBox({ ...drag.current.box, x: drag.current.box.x - dx * drag.current.box.width / bounds.width, y: drag.current.box.y - dy * drag.current.box.height / bounds.height }); }} onPointerUp={event => { drag.current = null; if (event.currentTarget.hasPointerCapture(event.pointerId)) event.currentTarget.releasePointerCapture(event.pointerId); }} onPointerCancel={() => { drag.current = null; }}>
      {shapes.map(({ feature, path }) => { const items = provincial.get(provinceIds.get(feature.properties.pro_th) ?? -1) ?? []; const worst = items.reduce((health, plan) => healthOrder.indexOf(plan.health) > healthOrder.indexOf(health) ? plan.health : health, 'on_track' as UrbanPlan['health']); const matches = !search || feature.properties.pro_th.includes(search); return <path key={feature.properties.pro_code} d={path} fill={!items.length ? '#f1f5f9' : healthColors[worst]} stroke={selected === feature.properties.pro_th ? '#111827' : '#64748b'} strokeWidth={selected === feature.properties.pro_th ? 3 : 0.7} vectorEffect="non-scaling-stroke" fillRule="evenodd" opacity={matches ? 1 : 0.18} tabIndex={0} role="button" aria-label={`${feature.properties.pro_th} ${items.length} ผัง`} onClick={() => { if (!moved.current) setSelected(feature.properties.pro_th); }} onKeyDown={event => { if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); setSelected(feature.properties.pro_th); } }}><title>{feature.properties.pro_th}: {items.length} ผัง</title></path>; })}
    </svg>}<p className="muted-sub">สีแสดงสถานะที่ต้องติดตามมากที่สุดในแต่ละจังหวัด · เขียว: มีความก้าวหน้า · เหลือง/ส้ม/แดง: ล่าช้าตามลำดับ · เทา: ไม่ระบุหรือไม่มีข้อมูล</p></div>
    <section aria-label="รายการผังในจังหวัด"><h3>{selected || 'เลือกจังหวัดบนแผนที่'}</h3>{selected && <p>{entries.length} ผัง</p>}{entries.map(plan => <article key={plan.id} className="note-item"><button className="link-btn" onClick={() => onOpenPlan(plan.id)}>{plan.code} · {plan.name}</button><p>ขั้นตอน {plan.currentStep}.{plan.subStep}</p><HealthBadge health={plan.health} delayDays={plan.delayDays} /></article>)}</section></div>
  </div>;
}
