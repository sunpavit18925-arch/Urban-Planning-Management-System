import {
  ActivityLog,
  DashboardSummary,
  KethInfo,
  PlanCategory,
  PlanHealth,
  PlanNote,
  PlanStage,
  ProvinceInfo,
  RegionInfo,
  StepDefinition,
  UrbanPlan,
} from '../types/plan';
import { DirectoryKind, DirectoryRecord } from '../data/navigation';
import { ImportPreview, PlanAttachment, PlanHistory } from '../types/operations';
import { ModuleDefinition, ModuleRecord, FieldValue, MaintenanceReport, ScheduleRow, WorkflowStatus, ReportGroup } from '../types/modules';

const BASE = '/api';
let csrfToken = '';
let sessionReady = false;
export interface Account { id: number; username: string; name: string; role: 'superadmin' | 'admin' | 'guest'; active: boolean }
export interface SessionInfo { enforced: boolean; user: Account | null; csrfToken: string }
function rememberSession(session: SessionInfo): SessionInfo { csrfToken = session.csrfToken; sessionReady = true; return session; }

async function request<T>(path: string, init?: RequestInit): Promise<T> {
  if (!sessionReady && init?.method && !['GET', 'HEAD'].includes(init.method.toUpperCase()) && !['/auth/login', '/auth/view'].includes(path)) await request<SessionInfo>('/auth/session').then(rememberSession);
  const headers = new Headers(init?.headers);
  if (csrfToken && init?.method && !['GET', 'HEAD'].includes(init.method.toUpperCase())) headers.set('X-UPMS-CSRF', csrfToken);
  if (!(init?.body instanceof FormData) && !headers.has('Content-Type')) headers.set('Content-Type', 'application/json');
  const res = await fetch(`${BASE}${path}`, {
    ...init,
    headers,
  });
  if (!res.ok) {
    const body = await res.json().catch(() => ({}));
    throw new Error(body.error || `Request failed (${res.status})`);
  }
  return res.json() as Promise<T>;
}

export const api = {
  getSession: () => request<SessionInfo>('/auth/session').then(rememberSession),
  login: (username: string, password: string) => request<SessionInfo>('/auth/login', { method: 'POST', body: JSON.stringify({ username, password }) }).then(rememberSession),
  changeView: (role: Account['role']) => request<SessionInfo>('/auth/view', { method: 'POST', body: JSON.stringify({ role }) }).then(rememberSession),
  logout: () => request<{ loggedOut: boolean }>('/auth/logout', { method: 'POST' }).then(result => { csrfToken = ''; sessionReady = false; return result; }),
  getAccounts: () => request<Account[]>('/auth/users'),
  createAccount: (username: string, name: string, role: Account['role'], password: string) => request<Account>('/auth/users', { method: 'POST', body: JSON.stringify({ username, name, role, password }) }),
  updateAccount: (id: number, name: string, role: Account['role'], active: boolean, password?: string) => request<Account>(`/auth/users/${id}`, { method: 'PATCH', body: JSON.stringify({ name, role, active, ...(password ? { password } : {}) }) }),
  getModuleDefinitions: () => request<ModuleDefinition[]>('/modules/meta'),
  getModuleRecords: (kind: string, planId?: number) => request<ModuleRecord[]>(`/modules/${encodeURIComponent(kind)}${planId ? `?planId=${planId}` : ''}`),
  saveModuleRecord: (kind: string, data: Record<string, FieldValue>, record?: ModuleRecord) => request<ModuleRecord>(`/modules/${encodeURIComponent(kind)}${record ? `/${record.id}` : ''}`, { method: record ? 'PATCH' : 'POST', body: JSON.stringify({ data, ...(record ? { version: record.version } : {}) }) }),
  deleteModuleRecord: (kind: string, record: ModuleRecord) => request<{ deleted: boolean }>(`/modules/${encodeURIComponent(kind)}/${record.id}`, { method: 'DELETE', body: JSON.stringify({ version: record.version }) }),
  getMaintenance: (id: number) => request<MaintenanceReport>(`/maintenance/${id}`),
  getSchedule: (id: number) => request<{ plan: ModuleRecord; rows: ScheduleRow[] }>(`/schedules/${id}`),
  getWorkflow: (id: number) => request<{ cases: ModuleRecord[]; entries: Array<ModuleRecord & { status: WorkflowStatus }>; schedules: ModuleRecord[] }>(`/plans/${id}/workflow`),
  getSnapshots: () => request<ModuleRecord[]>('/snapshots'),
  createSnapshot: (name: string, asOf: string) => request<{ id: number }>('/snapshots', { method: 'POST', body: JSON.stringify({ name, asOf }) }),
  getReportCatalog: (dimension: string, snapshot?: number, search?: string) => request<{ total: number; plans: UrbanPlan[]; groups: ReportGroup[] }>(`/reports/catalog?${new URLSearchParams({ dimension, ...(snapshot ? { snapshot: String(snapshot) } : {}), ...(search ? { search } : {}) })}`),
  getCommittee: (id: number) => request<{ committee: number; assignments: ModuleRecord[]; plans: UrbanPlan[] }>(`/reports/committee/${id}`),
  getAllHistory: () => request<PlanHistory[]>('/history'),
  getSystemSummary: () => request<{ today: string; totalPlans: number; totalNotes: number; activeAccounts: number; activeSessions: number; visitsToday: number; usersToday: number; historyEntries: number; database: string }>('/system/summary'),
  editNote: (id: number, content: string) => request<PlanNote>(`/notes/${id}`, { method: 'PATCH', body: JSON.stringify({ content }) }),
  deleteNote: (id: number) => request<{ deleted: boolean }>(`/notes/${id}`, { method: 'DELETE' }),
  previewImport: (file: File) => {
    const body = new FormData(); body.set('file', file);
    return request<ImportPreview>('/imports/preview', { method: 'POST', body });
  },
  commitImport: (token: string) => request<{ imported: number; ids: number[] }>(`/imports/${encodeURIComponent(token)}/commit`, { method: 'POST' }),
  getPlanHistory: (id: number) => request<PlanHistory[]>(`/plans/${id}/history`),
  getAttachments: (id: number) => request<PlanAttachment[]>(`/plans/${id}/attachments`),
  uploadAttachment: (id: number, file: File) => {
    const body = new FormData(); body.set('file', file);
    return request<PlanAttachment>(`/plans/${id}/attachments`, { method: 'POST', body });
  },
  deleteAttachment: (id: number, fileId: string) => request<{ deleted: boolean }>(`/plans/${id}/attachments/${encodeURIComponent(fileId)}`, { method: 'DELETE' }),
  getStatusReport: () => request<{
    total: number; asOf: string; plans: UrbanPlan[];
    panels: Record<string, Array<{ key: string; label: string; color: string; count: number; planIds: number[] }>>;
    matrix: Array<{ key: string; label: string; count: number; planIds: number[]; rows: Array<{ key: string; label: string; count: number; planIds: number[] }> }>;
  }>('/reports/status'),
  getTreeReport: () => request<{ counts: Record<string, number>; groups: Array<{ key: string; title: string; count: number }>; asOf: string }>('/reports/tree'),
  getTreeReportPlans: (group: string) => request<{ title: string; total: number; items: UrbanPlan[] }>(`/reports/tree/${encodeURIComponent(group)}`),
  getMeta: () => request<{ steps: StepDefinition[]; provinces: ProvinceInfo[] }>('/meta'),
  getSummary: () => request<DashboardSummary>('/dashboard/summary'),
  getRegions: () => request<RegionInfo[]>('/regions'),
  getKeths: () => request<KethInfo[]>('/keths'),
  getPlans: (params?: {
    region?: number;
    keth?: number;
    provinceId?: number;
    health?: PlanHealth;
    stage?: PlanStage;
    category?: PlanCategory;
    search?: string;
  }) => {
    const qs = new URLSearchParams();
    if (params) {
      Object.entries(params).forEach(([k, v]) => {
        if (v !== undefined && v !== '') qs.set(k, String(v));
      });
    }
    const q = qs.toString();
    return request<{ total: number; items: UrbanPlan[] }>(`/plans${q ? `?${q}` : ''}`);
  },
  getPlanDetail: (id: number) => request<{ plan: UrbanPlan; notes: PlanNote[] }>(`/plans/${id}`),
  createPlan: (payload: {
    name: string;
    provinceId: number;
    stage: PlanStage;
    category: PlanCategory;
    owner: UrbanPlan['owner'];
    operator: UrbanPlan['operator'];
    budgetYear: string;
    currentStep: number;
    health: PlanHealth;
    isEec: boolean;
  }) =>
    request<UrbanPlan>('/plans', {
      method: 'POST',
      body: JSON.stringify(payload),
    }),
  updatePlan: (
    id: number,
    patch: Partial<Omit<UrbanPlan, 'id' | 'code' | 'provinceName' | 'regionId' | 'regionName' | 'kethId' | 'updatedAt'>>
  ) =>
    request<UrbanPlan>(`/plans/${id}`, {
      method: 'PATCH',
      body: JSON.stringify(patch),
    }),
  deletePlan: (id: number) =>
    request<{ deleted: boolean; id: number }>(`/plans/${id}`, {
      method: 'DELETE',
    }),
  addNote: (id: number, author: string, content: string, categoryId = 0) =>
    request<PlanNote>(`/plans/${id}/notes`, {
      method: 'POST',
      body: JSON.stringify({ author, content, categoryId }),
    }),
  getLogs: () => request<ActivityLog[]>('/logs'),
  getNotes: () => request<Array<PlanNote & { planName: string; planCode: string }>>('/notes'),
  updateStep: (step: number, title: string, targetWeeks: number) =>
    request<StepDefinition>(`/steps/${step}`, { method: 'PATCH', body: JSON.stringify({ title, targetWeeks }) }),
  getDirectory: (kind: DirectoryKind) => request<DirectoryRecord[]>(`/directories/${kind}`),
  saveDirectory: (kind: DirectoryKind, input: Omit<DirectoryRecord, 'id'>, id?: number) =>
    request<DirectoryRecord>(`/directories/${kind}${id === undefined ? '' : `/${id}`}`, {
      method: id === undefined ? 'POST' : 'PATCH', body: JSON.stringify(input),
    }),
  deleteDirectory: (kind: DirectoryKind, id: number) =>
    request<{ deleted: boolean }>(`/directories/${kind}/${id}`, { method: 'DELETE' }),
  resetDatabase: () =>
    request<{ reset: boolean; message: string }>('/database/reset', {
      method: 'POST',
    }),
};
