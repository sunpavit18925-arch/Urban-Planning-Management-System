import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import { PHP_KETHS, PHP_REGIONS } from '../data/phpReference';
import type { ProvinceInfo } from '../types/plan';

interface GeographyPageProps {
  inspectorates?: boolean;
  onOpenGroup: (id: number) => void;
}

// Mirrors app/show_extra.php and app/show_extra_keth.php, including the
// original five-cell rows and province links to regional Drive folders.
export function GeographyPage({ inspectorates = false, onOpenGroup }: GeographyPageProps) {
  const [activeProvinceIds, setActiveProvinceIds] = useState<Set<number> | null>(null);
  useEffect(() => {
    let cancelled = false;
    // PHP lists provinces with plans; an empty group falls back to its full
    // reference list. Keep the same behavior without making the maps depend
    // on API availability.
    api.getPlans().then(result => {
      if (!cancelled) setActiveProvinceIds(new Set(result.items.map(plan => plan.provinceId)));
    }).catch(() => { /* The PHP reference list is the offline fallback. */ });
    return () => { cancelled = true; };
  }, []);
  const groups = inspectorates
    ? PHP_KETHS.map(k => ({ ...k, id: k.kethId }))
    : PHP_REGIONS;

  const displayedProvinces = (provinces: ProvinceInfo[]) => {
    const active = provinces.filter(province => activeProvinceIds?.has(province.id));
    return active.length ? active.sort((a, b) => a.id - b.id) : provinces;
  };

  const provinceRows = (provinces: ProvinceInfo[]) => {
    const rows: ProvinceInfo[][] = [];
    for (let i = 0; i < provinces.length; i += 5) rows.push(provinces.slice(i, i + 5));
    return rows;
  };

  return (
    <div className={`geography-page${inspectorates ? ' inspectorates' : ''}`}>
      <div id="title_page" className={inspectorates ? 'col-xs-12' : 'col-12'}>
        <h2 className="hidden-sm hidden-xs GHfont printable">
          {inspectorates ? 'ผังตามเขตตรวจราชการ' : 'ผังตามขอบเขตการปกครอง'}
        </h2>
        <h3 className="hidden-md hidden-lg GHfont-mini unprintable">
          {inspectorates ? 'ผังตามเขตตรวจราชการ' : 'ผังตามขอบเขตการปกครอง'}
        </h3>
        <div className="row" id="info_bar"><h3 id="search_value" /></div>
        <h4 id="print_search" />
      </div>
      <br />
      <div className="container-fluid">
        <div className="row unprintable" />
        <div id="extra-content-area">
          <div id="extra-content-area-r1" className="col-xs-12 col-sm-5 col-md-4">
            <div className={`map-container${inspectorates ? ' inspectorate-map' : ''}`}>
              <img className="a-img" src={inspectorates ? './php/img/keth/main.png' : './php/img/map/main.jpg'}
                alt={inspectorates ? 'แผนที่เขตตรวจราชการ 18 เขต' : 'แผนที่ประเทศไทยแบ่งตามขอบเขตการปกครอง 5 ภาค'} />
            </div>
          </div>
          <div id="extra-sideshow" className="col-xs-12 col-sm-7 col-md-8">
            {groups.map(group => (
              <div key={group.id} className="extra-sideshow-inner" style={{ borderColor: group.color }}>
                <h3 className="div-h" style={{ color: group.color }}>
                  <button className="geography-heading" onClick={() => onOpenGroup(group.id)}>{group.name}</button>
                </h3>
                <div className="table-responsive">
                  <table className="table">
                    <tbody>
                      {provinceRows(displayedProvinces(group.provinces)).map(row => (
                        <tr key={row[0].id} style={{ background: 'transparent' }}>
                          {row.map(province => {
                            const region = PHP_REGIONS.find(r => r.id === province.regionId)!;
                            return (
                              <td key={province.id} className="set-td" style={{ border: `solid ${group.color} 1px` }}>
                                <a href={region.driveUrl} target="_blank" rel="noopener noreferrer"
                                  className="province-link" style={{ color: region.color }}>{province.name}</a>
                              </td>
                            );
                          })}
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
