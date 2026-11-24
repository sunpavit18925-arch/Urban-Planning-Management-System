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

const BASE = '/api';

async function request<T>(path: string, init?: RequestInit): Promise<T> {
  const res = await fetch(`${BASE}${path}`, {
    headers: { 'Content-Type': 'application/json' },
    ...init,
  });
  if (!res.ok) {
    const body = await res.json().catch(() => ({}));
    throw new Error(body.error || `Request failed (${res.status})`);
  }
  return res.json() as Promise<T>;
}

export const api = {
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
    patch: Partial<Pick<UrbanPlan, 'currentStep' | 'subStep' | 'health' | 'delayDays' | 'stage' | 'effectiveStatus' | 'trackingState'>>
  ) =>
    request<UrbanPlan>(`/plans/${id}`, {
      method: 'PATCH',
      body: JSON.stringify(patch),
    }),
  deletePlan: (id: number) =>
    request<{ deleted: boolean; id: number }>(`/plans/${id}`, {
      method: 'DELETE',
    }),
  addNote: (id: number, author: string, content: string) =>
    request<PlanNote>(`/plans/${id}/notes`, {
      method: 'POST',
      body: JSON.stringify({ author, content }),
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
  resetMockDb: () =>
    request<{ reset: boolean; message: string }>('/mock/reset', {
      method: 'POST',
    }),
};
