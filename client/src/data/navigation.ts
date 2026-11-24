export const navigation = [
  { title: 'ติดตามผังเมือง', items: [
    { id: 'dashboard', label: 'รายงานสถานภาพผังเมือง', icon: 'bar-chart' },
    { id: 'regions', label: 'ผังตามขอบเขตการปกครอง', icon: 'map' },
    { id: 'keths', label: 'ผังตามเขตตรวจราชการ', icon: 'map-o' },
    { id: 'status', label: 'สรุปสถานภาพผังเมืองปัจจุบัน', icon: 'newspaper-o' },
    { id: 'search', label: 'ค้นหาผังเมือง', icon: 'search' },
    { id: 'schedule', label: 'แผนดำเนินงาน', icon: 'calendar' },
  ] },
  { title: 'จัดการข้อมูลผังเมืองรวม', items: [
    { id: 'plans', label: 'ผังเมืองรวมเมือง/ชุมชน', icon: 'building' },
    { id: 'm35', label: 'ผังเมืองในขั้นตอน ม.35', icon: 'legal' },
    { id: 'evaluation', label: 'ผังเมืองในขั้นตอนการประเมิน', icon: 'check-square-o' },
    { id: 'steps', label: 'ขั้นตอนดำเนินการ', icon: 'list-ol' },
    { id: 'evaluationSteps', label: 'ขั้นตอนการประเมิน', icon: 'wrench' },
    { id: 'offices', label: 'จังหวัดและส่วนผังเมืองรวม', icon: 'bank' },
    { id: 'notes', label: 'รายการหมายเหตุ', icon: 'commenting-o' },
    { id: 'noteCategories', label: 'หมวดหมู่หมายเหตุ', icon: 'tags' },
    { id: 'reasons', label: 'เหตุผลผังพิจารณาดำเนินการ', icon: 'list-alt' },
  ] },
  { title: 'รายงาน', items: [
    { id: 'reports', label: 'รายงานข้อมูลผังเมือง', icon: 'file-text-o' },
    { id: 'progress', label: 'รายงานติดตามความก้าวหน้า', icon: 'bar-chart' },
    { id: 'operating', label: 'ผังดำเนินการ', icon: 'tasks' },
    { id: 'legislation', label: 'ผังยกร่างกฎหมาย', icon: 'gavel' },
    { id: 'export', label: 'ส่งออกข้อมูล', icon: 'download' },
  ] },
  { title: 'คณะติดตามผลการดำเนินการ', items: Array.from({ length: 7 }, (_, i) => (
    { id: `committee${i + 1}`, label: `คณะติดตามฯ ${i + 1}`, icon: 'line-chart' }
  )) },
  { title: 'ระบบและประสานงาน', items: [
    { id: 'administrators', label: 'ผู้ดูแลระบบ', icon: 'users' },
    { id: 'logs', label: 'บันทึกกิจกรรม', icon: 'book' },
    { id: 'contacts', label: 'ติดต่อประสานงาน', icon: 'phone' },
  ] },
];

export type NavTab = string;
export const menuItems = navigation.flatMap(group => group.items);
export const getTab = (): NavTab => {
  const value = window.location.hash.slice(1).split('/')[0];
  return menuItems.some(item => item.id === value) ? value : 'regions';
};

export const directoryTabs = ['offices', 'evaluationSteps', 'noteCategories', 'reasons', 'administrators'] as const;
export type DirectoryKind = typeof directoryTabs[number];
export interface DirectoryRecord { id: number; name: string; detail: string; active: boolean }
