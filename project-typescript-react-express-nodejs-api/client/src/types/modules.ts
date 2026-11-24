export type FieldValue = string | number | boolean | number[];
export interface ModuleRecord {
  id: number;
  version: number;
  createdAt: string;
  updatedAt: string;
  data: Record<string, FieldValue>;
}
export interface FieldOption { value: string | number; label: string }
export interface ModuleField {
  key: string;
  label: string;
  type: 'text' | 'textarea' | 'number' | 'date' | 'boolean' | 'select' | 'relation' | 'multi';
  required?: boolean;
  min?: number;
  max?: number;
  integer?: boolean;
  options?: FieldOption[];
  relation?: string;
  default?: FieldValue;
}
export interface ModuleDefinition {
  key: string;
  title: string;
  description: string;
  fields: ModuleField[];
  columns: string[];
  sources: string[];
}
export interface WorkflowStatus {
  deadline: string | null;
  differenceDays: number | null;
  delayDays: number;
  health: 'on_track' | 'slight_delay' | 'moderate_delay' | 'severe_delay' | 'unscheduled';
  completed: boolean;
  firstThreshold: number;
  secondThreshold: number;
}
export interface ScheduleRow { definitionId: number; title: string; step: number; subStep: number; thirdStep: number; start: string; end: string; days: number }
export interface MaintenanceCell { year: number; status: 'not_purchased' | 'warranty' | 'maintenance' | 'replacement'; amount: number }
export interface MaintenanceReport { years: number[]; rows: Array<{ equipmentId: number; name: string; cells: MaintenanceCell[] }>; totals: number[]; total: number }
export interface ReportGroup { key: string; label: string; count: number; planIds: number[] }
