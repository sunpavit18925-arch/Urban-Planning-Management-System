import { AsyncLocalStorage } from 'node:async_hooks';
export type UserRole = 'superadmin' | 'admin' | 'guest';
export const requestContext = new AsyncLocalStorage<{ actor: string; role: UserRole }>();
