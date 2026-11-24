import { UrbanPlan } from './plan';

export interface PlanHistory {
  id: number;
  planId: number;
  changedAt: string;
  before: UrbanPlan;
  after: UrbanPlan;
  changedFields: string[];
  actor?: string;
}

export interface PlanAttachment {
  id: string;
  planId: number;
  name: string;
  mimeType: 'image/png' | 'image/jpeg' | 'application/pdf';
  size: number;
  createdAt: string;
}

export interface ImportIssue { row: number; field: string; message: string }
export interface ImportPreview {
  token: string;
  expiresAt: string;
  totalRows: number;
  validRows: number;
  issues: ImportIssue[];
  rows: Array<{ row: number; name: string; provinceName: string; budgetYear: string }>;
}
