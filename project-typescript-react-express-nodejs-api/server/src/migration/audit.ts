import { execFileSync } from 'node:child_process';
import { createHash } from 'node:crypto';
import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import path from 'node:path';

interface Replacement { id: string; source: RegExp; targets: string[]; behavior: string }
const replacements: Replacement[] = [
  { id: 'shell-and-session', source: /^(?:index|c-admin1?|c-super_admin|nav[^/]*|app\/(?:admin|super_admin|user|home|destroy)|apps\/home)\.php$/i, targets: ['client/src/App.tsx', 'client/src/components/Sidebar.tsx', 'client/src/components/AuthPanel.tsx', 'server/src/services/auth.ts'], behavior: 'Application navigation, view modes, login, logout, account roles and session inspection' },
  { id: 'plan-register', source: /^(?:app\/(?:cityplan|manage(?:X)?|manage_cityplan[^/]*|pro_cityplan[^/]*|ptt?|mytest|search[^/]*|unset_data|pro_unset)|appx\/show_cityplan|statusCityplan|report\/(?:manage_cityplan[^/]*|pro_cityplanX-stage|pro_result[^/]*|show_result|show_optx|show_detail_x[^/]*|report_another[^/]*|tree-chart-pro_cityplan[^/]*))\.php$/i, targets: ['server/src/routes/api.ts', 'server/src/services/planValidation.ts', 'client/src/App.tsx', 'client/src/components/PlanMetadataEditor.tsx'], behavior: 'Plan CRUD, complete editable registration fields, filtered search, pagination, detail and lifecycle updates' },
  { id: 'workflows-and-dates', source: /^(?:app\/(?:manage(?:X)?|cityplan_(?:detail|step|m35[^/]*|est[^/]*)|pro_csd|show_csd|manage_stepsub|manage_substep|manage_step|step|substep|show_step|manage_plan[^/]*|lating|date_test|pro_cityplan_lating)|report\/(?:operate_plan_show|m35[^/]*|tree-chart-cityplan|tree-chart-eva))\.php$/i, targets: ['server/src/services/modules.ts', 'server/src/services/calendar.ts', 'server/src/data/moduleDefinitions.ts', 'client/src/components/ModulePage.tsx', 'client/src/components/PlanWorkflow.tsx'], behavior: 'Multiple M.35/evaluation cases, nested steps, real/planned dates, petition timing, thresholds, schedules and progress' },
  { id: 'notes-and-categories', source: /^(?:app\/(?:manage(?:X)?|manage_note[^/]*|manage_group_note[^/]*|note)|report\/(?:table_step_note[^/]*|pro_step_note[^/]*|pro_table_step_note[^/]*|user_step_note|convertnote))\.php$/i, targets: ['server/src/services/notes.ts', 'server/src/routes/modules.ts', 'client/src/components/PlanDetailModal.tsx', 'client/src/components/WorkspacePage.tsx'], behavior: 'Categorized step/case notes, editing, soft deletion and notes reports' },
  { id: 'provinces-and-committees', source: /^(?:app\/(?:manage_province|pro_province|show_extra[^/]*|show_page_park[^/]*|show_park|show_keth)|report\/dashboard_kana)\.php$/i, targets: ['server/src/services/modules.ts', 'client/src/components/AdministrativeGeographyPage.tsx', 'client/src/components/ModulePage.tsx'], behavior: 'Province, region, inspectorate and affiliation assignments, region drilldowns and persisted committee assignments' },
  { id: 'directories-and-accounts', source: /^app\/(?:manage_admin|pro_admin|manage_consider|pro_consider)\.php$/i, targets: ['server/src/data/directories.ts', 'server/src/services/auth.ts', 'client/src/components/DirectoryPage.tsx', 'client/src/components/AuthPanel.tsx', 'client/src/components/ModulePage.tsx'], behavior: 'Reference directories, reason selection, user register and separately authenticated accounts' },
  { id: 'history-and-logs', source: /^app\/(?:history_data|pro_history|show_log|pro_log)\.php$/i, targets: ['server/src/data/database.ts', 'client/src/components/PlanHistory.tsx', 'client/src/components/SystemPages.tsx', 'client/src/App.tsx'], behavior: 'Before/after values, actor and time, historical progress entries, global history and activity logs' },
  { id: 'reports-and-snapshots', source: /^(?:report\/|app\/(?:report[^/]*|t[12]|status_))/i, targets: ['server/src/data/treeReport.ts', 'server/src/data/statusReport.ts', 'server/src/services/modules.ts', 'client/src/components/TreeReportPage.tsx', 'client/src/components/StatusReportPage.tsx', 'client/src/components/ReportCatalogPage.tsx', 'client/src/components/SystemPages.tsx'], behavior: 'Tree/status reports plus budget, type, scope, owner, operator, stage, step, EEC, province and health grouping; charts, details, printing and saved datasets' },
  { id: 'spreadsheet-transfer', source: /^app\/(?:import_excel|export_page[^/]*)\.php$/i, targets: ['server/src/services/spreadsheets.ts', 'client/src/components/DataTransferPage.tsx', 'client/src/utils/export.ts'], behavior: 'XLSX/CSV preview and atomic import, Excel export and filtered CSV export' },
  { id: 'attachments', source: /^app\/uploadcityplanimgx?\.php$/i, targets: ['server/src/routes/operations.ts', 'client/src/components/PlanFiles.tsx'], behavior: 'Image/PDF upload, file signatures, downloads, deletions and plan ownership' },
  { id: 'asset-management', source: /^apps\/(?!home\.php)/i, targets: ['server/src/data/moduleDefinitions.ts', 'server/src/services/modules.ts', 'server/src/routes/modules.ts', 'client/src/components/ModulePage.tsx'], behavior: 'Companies, projects, equipment, project/equipment selection, inspections, multi-year maintenance plans and yearly budgets' },
  { id: 'interactive-map', source: /^(?:map(?:_pro)?|map\/map_province(?:_pro)?)\.php$/i, targets: ['client/src/components/InteractiveMap.tsx', 'client/public/data/provinces.geojson'], behavior: 'Local province geometries, interactive zoom/pan, province selection and plan details' },
  { id: 'tree-layout-variants', source: /^tree_chart2\//i, targets: ['client/src/components/TreeReportPage.tsx', 'server/src/data/treeReport.ts'], behavior: 'Consolidated tree report variants with live data' },
  { id: 'shared-services', source: /^(?:js\/function_(?:db|php)|mock_db_setup|app\/function_pagin)\.php$/i, targets: ['server/src/storage/sqlite.ts', 'server/src/data/database.ts', 'server/src/services/calendar.ts', 'server/src/services/modules.ts', 'server/src/services/auth.ts', 'client/src/App.tsx'], behavior: 'SQLite storage, prepared statements, dates, deadlines, delay colors, logging, sessions and pagination' },
];
const exclusions: Record<string, { classification: string; reason: string }> = {
  'chatbot.php': { classification: 'disabled-in-source', reason: 'Source returns HTTP 204 and explicitly sends no LINE notifications; no external integration exists to port.' },
  'sun.php': { classification: 'no-business-behavior', reason: 'Hello World sample.' },
  'app/t3.php': { classification: 'no-business-behavior', reason: 'Only a report heading; search reports are provided by the report catalog.' },
  'app/t4.php': { classification: 'no-business-behavior', reason: 'Only a heading for issues/suggestions; no storage or handler exists.' },
  'map/function_external.php': { classification: 'no-business-behavior', reason: 'JavaScript alert/console diagnostic only.' },
  'js/provinces.php': { classification: 'geographic-data', reason: 'Coordinate dataset, reused as client/public/data/provinces.geojson; not application source.' },
  'js/script.php': { classification: 'asset-loader', reason: 'CDN script includes replaced by bundled/local dependencies.' },
  'img/loading-bar-test.php': { classification: 'ui-demonstration', reason: 'Loading indicator demonstration; React loading states cover this behavior.' },
};

export function auditMigration(repoDirectory = path.resolve(__dirname, '../../../..')) {
  const project = path.join(repoDirectory, 'project-typescript-react-express-nodejs-api');
  const published = execFileSync('git', ['ls-files', '--', 'php/*.php', 'php/**/*.php'], { cwd: repoDirectory, encoding: 'utf8', windowsHide: true }).trim().split(/\r?\n/).filter(Boolean);
  const manifestPath = path.join(repoDirectory, 'php/docs/runtime-files.json');
  const archive: { sourceCommit: string; archived: { path: string; sha256: string; legacyEvents: string[] }[] } = existsSync(manifestPath)
    ? JSON.parse(readFileSync(manifestPath, 'utf8')) : { sourceCommit: '', archived: [] };
  const archived = new Map(archive.archived.filter(file => !published.includes(file.path)).map(file => [file.path, file]));
  const sources = [...new Set([...published, ...archived.keys()])].sort();
  const files = sources.map(source => {
    const relative = source.slice(4);
    const historical = archived.get(source);
    // Archived files need not exist in a fresh clone. Use the recorded source
    // fingerprint/events, not an ignored local copy that could drift over time.
    const content = historical ? '' : readFileSync(path.join(repoDirectory, source), 'utf8').replace(/\r\n/g, '\n');
    const excluded = exclusions[relative];
    const features = excluded ? [] : replacements.filter(replacement => replacement.source.test(relative));
    const events = historical?.legacyEvents ?? [...new Set([...content.matchAll(/\$_POST\[['"]event['"]\]\s*={2,3}\s*(['"])([^'"]+)\1/g)].map(match => match[2]))];
    return { source, sourceStatus: historical ? 'archived-unused' : 'published', sha256: historical?.sha256 ?? createHash('sha256').update(content).digest('hex'), classification: excluded?.classification ?? (features.length ? 'functional-replacement' : 'unmapped'), featureIds: features.map(feature => feature.id), legacyEvents: events, ...(excluded ? { reason: excluded.reason } : {}) };
  });
  const missingTargets = replacements.flatMap(feature => feature.targets.filter(target => !existsSync(path.join(project, target))).map(target => `${feature.id}: ${target}`));
  const report = { schemaVersion: 2, scope: 'Published PHP sources plus historical sources explicitly archived in php/docs/runtime-files.json. Source status distinguishes current files from historical fingerprints; alternate layouts share functional replacements. Behavior is verified separately by API, domain and browser tests.', archivedSourceCommit: archive.sourceCommit, publishedPhpFiles: published.length, archivedPhpFiles: archived.size, totalPhpFiles: files.length,
    functionalFiles: files.filter(file => file.classification === 'functional-replacement').length, referenceOrNonfunctionalFiles: files.filter(file => file.classification !== 'functional-replacement' && file.classification !== 'unmapped').length,
    legacyEvents: files.reduce((sum, file) => sum + file.legacyEvents.length, 0), unmapped: files.filter(file => file.classification === 'unmapped').map(file => file.source), missingTargets,
    features: replacements.map(({ source: _pattern, ...feature }) => feature), files };
  return report;
}

if (require.main === module) {
  const report = auditMigration();
  if (process.argv.includes('--write')) {
    const directory = path.resolve(__dirname, '../../../docs'); mkdirSync(directory, { recursive: true });
    writeFileSync(path.join(directory, 'migration-inventory.json'), JSON.stringify(report, null, 2) + '\n');
  }
  console.log(JSON.stringify({ totalPhpFiles: report.totalPhpFiles, publishedPhpFiles: report.publishedPhpFiles, archivedPhpFiles: report.archivedPhpFiles, functionalFiles: report.functionalFiles, referenceOrNonfunctionalFiles: report.referenceOrNonfunctionalFiles, legacyEvents: report.legacyEvents, unmapped: report.unmapped, missingTargets: report.missingTargets }, null, 2));
  if (report.unmapped.length || report.missingTargets.length) process.exitCode = 1;
}
