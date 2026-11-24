import { WorkflowStatus } from '../types/modules';

const DAY = 86400000;
export class CalendarError extends Error {}
/** Parse a Gregorian or Buddhist civil date without depending on the host timezone. */
export function civilDate(value: string): Date {
  const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value);
  if (!match) throw new CalendarError('วันที่ต้องอยู่ในรูปแบบ ปี-เดือน-วัน');
  let year = Number(match[1]);
  if (year > 2400) year -= 543;
  const month = Number(match[2]);
  const day = Number(match[3]);
  const date = new Date(Date.UTC(year, month - 1, day));
  if (year < 1900 || year > 2300 || date.getUTCFullYear() !== year || date.getUTCMonth() !== month - 1 || date.getUTCDate() !== day) throw new CalendarError('วันที่ไม่มีอยู่จริง');
  return date;
}
export const isoDate = (value: Date): string => value.toISOString().slice(0, 10);
export const todayBangkok = (): string => new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Bangkok', year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date());
export function addDays(value: string, days: number): string { return isoDate(new Date(civilDate(value).getTime() + days * DAY)); }
export function buddhistDate(value: string): string {
  const date = civilDate(value);
  return `${date.getUTCFullYear() + 543}${isoDate(date).slice(4)}`;
}
export function inclusiveDeadline(start: string, durationDays: number): string {
  if (!Number.isInteger(durationDays) || durationDays < 1 || durationDays > 36500) throw new Error('ระยะเวลาต้องอยู่ระหว่าง 1–36500 วัน');
  return addDays(start, durationDays - 1);
}

/** Port of status_snd/status_trd in PHP, with explicit dates and thresholds. */
export function workflowStatus(start: string, stop: string, firstThreshold: number, secondThreshold: number, asOf = todayBangkok()): WorkflowStatus {
  if (!start) return { deadline: null, differenceDays: null, delayDays: 0, health: 'unscheduled', completed: false, firstThreshold, secondThreshold };
  if (firstThreshold < 1 || secondThreshold < firstThreshold) throw new Error('เกณฑ์ความล่าช้าไม่ถูกต้อง');
  const deadline = inclusiveDeadline(start, firstThreshold);
  const ending = civilDate(stop || asOf);
  if (stop && ending < civilDate(start)) throw new CalendarError('วันสิ้นสุดต้องไม่อยู่ก่อนวันเริ่มต้น');
  const differenceDays = Math.round((ending.getTime() - civilDate(deadline).getTime()) / DAY);
  const health = differenceDays <= 0 ? 'on_track' : differenceDays <= firstThreshold ? 'slight_delay' : differenceDays <= secondThreshold ? 'moderate_delay' : 'severe_delay';
  return { deadline, differenceDays, delayDays: Math.max(0, differenceDays), health, completed: Boolean(stop), firstThreshold, secondThreshold };
}
