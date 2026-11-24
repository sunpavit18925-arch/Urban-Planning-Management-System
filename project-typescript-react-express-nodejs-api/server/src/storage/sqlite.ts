import { mkdirSync } from 'node:fs';
import path from 'node:path';
import { DatabaseSync } from 'node:sqlite';
import { ActivityLog, PlanNote, StepDefinition, UrbanPlan } from '../types/plan';
import { PlanAttachment, PlanHistory } from '../types/operations';
import { DirectoryKind, DirectoryRecord } from '../data/directories';
import { ModuleRecord } from '../types/modules';

export interface DatabaseState {
  plans: UrbanPlan[];
  notes: PlanNote[];
  logs: ActivityLog[];
  steps: StepDefinition[];
  directories: Record<DirectoryKind, DirectoryRecord[]>;
  history: PlanHistory[];
  nextPlanId: number;
  nextNoteId: number;
  nextLogId: number;
  nextHistoryId: number;
}

export class DatabaseConflictError extends Error {
  constructor() { super('ข้อมูลถูกแก้ไขจากอีกโปรเซส กรุณาลองใหม่เพื่อใช้ข้อมูลล่าสุด'); }
}

/** A transactional disk store for the domain model; no user-supplied SQL. */
export class SqliteStore {
  private readonly connection: DatabaseSync;
  private transactionDepth = 0;
  private revision = 0;

  constructor(readonly filename: string) {
    if (filename !== ':memory:') mkdirSync(path.dirname(path.resolve(filename)), { recursive: true });
    this.connection = new DatabaseSync(filename);
    this.connection.exec('PRAGMA foreign_keys=ON; PRAGMA busy_timeout=5000; PRAGMA journal_mode=WAL;');
    const version = this.connection.prepare('PRAGMA user_version').get() as { user_version: number };
    if (version.user_version > 2) throw new Error('Database schema is newer than this application');
    this.connection.exec(`
      CREATE TABLE IF NOT EXISTS metadata (key TEXT PRIMARY KEY, value TEXT NOT NULL CHECK(json_valid(value)));
      CREATE TABLE IF NOT EXISTS plans (
        id INTEGER PRIMARY KEY, code TEXT NOT NULL UNIQUE, position INTEGER NOT NULL,
        payload TEXT NOT NULL CHECK(json_valid(payload))
      );
      CREATE TABLE IF NOT EXISTS notes (
        id INTEGER PRIMARY KEY, plan_id INTEGER NOT NULL REFERENCES plans(id) ON DELETE CASCADE,
        position INTEGER NOT NULL, payload TEXT NOT NULL CHECK(json_valid(payload))
      );
      CREATE TABLE IF NOT EXISTS logs (id INTEGER PRIMARY KEY, position INTEGER NOT NULL, payload TEXT NOT NULL CHECK(json_valid(payload)));
      CREATE TABLE IF NOT EXISTS steps (id INTEGER PRIMARY KEY, position INTEGER NOT NULL, payload TEXT NOT NULL CHECK(json_valid(payload)));
      CREATE TABLE IF NOT EXISTS directories (
        kind TEXT NOT NULL, id INTEGER NOT NULL, position INTEGER NOT NULL,
        payload TEXT NOT NULL CHECK(json_valid(payload)), PRIMARY KEY(kind, id)
      );
      CREATE TABLE IF NOT EXISTS plan_history (
        id INTEGER PRIMARY KEY, plan_id INTEGER NOT NULL REFERENCES plans(id) ON DELETE CASCADE,
        position INTEGER NOT NULL, payload TEXT NOT NULL CHECK(json_valid(payload))
      );
      CREATE TABLE IF NOT EXISTS attachments (
        id TEXT PRIMARY KEY, plan_id INTEGER NOT NULL REFERENCES plans(id) ON DELETE CASCADE,
        name TEXT NOT NULL, mime_type TEXT NOT NULL, size INTEGER NOT NULL CHECK(size > 0 AND size <= 5242880),
        created_at TEXT NOT NULL, content BLOB NOT NULL
      );
      CREATE INDEX IF NOT EXISTS attachments_plan ON attachments(plan_id);
      CREATE TABLE IF NOT EXISTS module_records (
        kind TEXT NOT NULL, id INTEGER NOT NULL, plan_id INTEGER REFERENCES plans(id) ON DELETE CASCADE,
        payload TEXT NOT NULL CHECK(json_valid(payload)), PRIMARY KEY(kind, id)
      );
      CREATE INDEX IF NOT EXISTS module_plan ON module_records(plan_id);
      CREATE TABLE IF NOT EXISTS module_counters (kind TEXT PRIMARY KEY, next_id INTEGER NOT NULL);
      PRAGMA user_version=2;
    `);
  }

  transaction<T>(work: () => T): T {
    if (this.transactionDepth) return work();
    this.connection.exec('BEGIN IMMEDIATE');
    this.transactionDepth++;
    try {
      const result = work();
      this.connection.exec('COMMIT');
      return result;
    } catch (error) {
      this.connection.exec('ROLLBACK');
      throw error;
    } finally { this.transactionDepth--; }
  }

  load(): DatabaseState | null {
    const metadata = this.connection.prepare("SELECT value FROM metadata WHERE key='state'").get() as { value: string } | undefined;
    if (!metadata) return null;
    const records = <T>(table: 'plans' | 'notes' | 'logs' | 'steps' | 'plan_history'): T[] =>
      (this.connection.prepare(`SELECT payload FROM ${table} ORDER BY position`).all() as Array<{ payload: string }>).map(row => JSON.parse(row.payload) as T);
    const directoryRows = this.connection.prepare('SELECT kind, payload FROM directories ORDER BY kind, position').all() as Array<{ kind: DirectoryKind; payload: string }>;
    const directories = {} as DatabaseState['directories'];
    for (const row of directoryRows) (directories[row.kind] ??= []).push(JSON.parse(row.payload));
    // Empty directories must survive a restart too.
    const counters = JSON.parse(metadata.value) as Pick<DatabaseState, 'nextPlanId' | 'nextNoteId' | 'nextLogId' | 'nextHistoryId'> & { directoryKinds: DirectoryKind[]; revision?: number };
    this.revision = counters.revision ?? 0;
    for (const kind of counters.directoryKinds) directories[kind] ??= [];
    return {
      plans: records<UrbanPlan>('plans'), notes: records<PlanNote>('notes'),
      logs: records<ActivityLog>('logs'), steps: records<StepDefinition>('steps'),
      history: records<PlanHistory>('plan_history'), directories,
      nextPlanId: counters.nextPlanId, nextNoteId: counters.nextNoteId,
      nextLogId: counters.nextLogId, nextHistoryId: counters.nextHistoryId,
    };
  }

  save(state: DatabaseState): void {
    const nextRevision = this.revision + 1;
    this.transaction(() => {
      const current = this.connection.prepare("SELECT value FROM metadata WHERE key='state'").get() as { value: string } | undefined;
      if ((current ? JSON.parse(current.value).revision ?? 0 : 0) !== this.revision) throw new DatabaseConflictError();
      const plan = this.connection.prepare('INSERT INTO plans(id, code, position, payload) VALUES(?, ?, ?, ?) ON CONFLICT(id) DO UPDATE SET code=excluded.code, position=excluded.position, payload=excluded.payload');
      state.plans.forEach((item, index) => plan.run(item.id, item.code, index, JSON.stringify(item)));
      this.connection.prepare('DELETE FROM plans WHERE id NOT IN (SELECT value FROM json_each(?))').run(JSON.stringify(state.plans.map(item => item.id)));
      this.connection.exec('DELETE FROM notes; DELETE FROM logs; DELETE FROM steps; DELETE FROM directories; DELETE FROM plan_history;');
      const note = this.connection.prepare('INSERT INTO notes VALUES(?, ?, ?, ?)');
      state.notes.forEach((item, index) => note.run(item.id, item.planId, index, JSON.stringify(item)));
      const log = this.connection.prepare('INSERT INTO logs VALUES(?, ?, ?)');
      state.logs.forEach((item, index) => log.run(item.id, index, JSON.stringify(item)));
      const step = this.connection.prepare('INSERT INTO steps VALUES(?, ?, ?)');
      state.steps.forEach((item, index) => step.run(item.step, index, JSON.stringify(item)));
      const directory = this.connection.prepare('INSERT INTO directories VALUES(?, ?, ?, ?)');
      for (const [kind, items] of Object.entries(state.directories)) items.forEach((item, index) => directory.run(kind, item.id, index, JSON.stringify(item)));
      const history = this.connection.prepare('INSERT INTO plan_history VALUES(?, ?, ?, ?)');
      state.history.forEach((item, index) => history.run(item.id, item.planId, index, JSON.stringify(item)));
      this.connection.prepare("INSERT INTO metadata VALUES('state', ?) ON CONFLICT(key) DO UPDATE SET value=excluded.value").run(JSON.stringify({
        nextPlanId: state.nextPlanId, nextNoteId: state.nextNoteId, nextLogId: state.nextLogId,
        nextHistoryId: state.nextHistoryId, directoryKinds: Object.keys(state.directories), revision: nextRevision,
      }));
    });
    this.revision = nextRevision;
  }

  listAttachments(planId: number): PlanAttachment[] {
    return this.connection.prepare('SELECT id, plan_id AS planId, name, mime_type AS mimeType, size, created_at AS createdAt FROM attachments WHERE plan_id=? ORDER BY created_at DESC, id').all(planId) as unknown as PlanAttachment[];
  }

  addAttachment(file: PlanAttachment, content: Buffer): void {
    this.connection.prepare('INSERT INTO attachments VALUES(?, ?, ?, ?, ?, ?, ?)').run(file.id, file.planId, file.name, file.mimeType, file.size, file.createdAt, content);
  }

  getAttachment(planId: number, id: string): (PlanAttachment & { content: Uint8Array }) | undefined {
    return this.connection.prepare('SELECT id, plan_id AS planId, name, mime_type AS mimeType, size, created_at AS createdAt, content FROM attachments WHERE plan_id=? AND id=?').get(planId, id) as unknown as (PlanAttachment & { content: Uint8Array }) | undefined;
  }

  deleteAttachment(planId: number, id: string): boolean {
    return Number(this.connection.prepare('DELETE FROM attachments WHERE plan_id=? AND id=?').run(planId, id).changes) > 0;
  }

  clearAttachments(): void { this.connection.exec('DELETE FROM attachments'); }
  listModules(kind: string): ModuleRecord[] {
    return (this.connection.prepare('SELECT payload FROM module_records WHERE kind=? ORDER BY id').all(kind) as Array<{ payload: string }>).map(row => JSON.parse(row.payload));
  }
  putModule(kind: string, data: ModuleRecord['data'], id?: number): ModuleRecord {
    const items = this.listModules(kind);
    const old = id === undefined ? undefined : items.find(item => item.id === id);
    if (id !== undefined && !old) throw new Error('ไม่พบรายการ');
    if (id === undefined) {
      this.connection.prepare('INSERT INTO module_counters VALUES(?, 1) ON CONFLICT(kind) DO NOTHING').run(kind);
      id = Number((this.connection.prepare('SELECT next_id FROM module_counters WHERE kind=?').get(kind) as { next_id: number }).next_id);
      this.connection.prepare('UPDATE module_counters SET next_id=next_id+1 WHERE kind=?').run(kind);
    }
    const now = new Date().toISOString();
    const item: ModuleRecord = { id, version: (old?.version ?? 0) + 1, createdAt: old?.createdAt ?? now, updatedAt: now, data };
    this.connection.prepare('INSERT INTO module_records VALUES(?, ?, ?, ?) ON CONFLICT(kind,id) DO UPDATE SET plan_id=excluded.plan_id, payload=excluded.payload').run(kind, id, typeof data.planId === 'number' && data.planId > 0 ? data.planId : null, JSON.stringify(item));
    return item;
  }
  deleteModule(kind: string, id: number): boolean { return Number(this.connection.prepare('DELETE FROM module_records WHERE kind=? AND id=?').run(kind, id).changes) > 0; }
  clearModules(): void { this.connection.exec("DELETE FROM module_records WHERE kind NOT IN ('_accounts','_sessions'); DELETE FROM module_counters WHERE kind NOT IN ('_accounts','_sessions');"); }
  close(): void { this.connection.close(); }
}
