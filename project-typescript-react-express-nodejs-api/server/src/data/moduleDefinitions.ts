import { ModuleDefinition, ModuleField } from '../types/modules';

const text = (key: string, label: string, required = false): ModuleField => ({ key, label, type: 'text', required, max: 250, default: '' });
const memo = (key: string, label: string): ModuleField => ({ key, label, type: 'textarea', max: 8000, default: '' });
const number = (key: string, label: string, min = 0, max = 1000000000000, initial = 0): ModuleField => ({ key, label, type: 'number', min, max, default: initial });
const integer = (key: string, label: string, min = 0, max = 10000, initial = 0): ModuleField => ({ ...number(key, label, min, max, initial), integer: true });
const flag = (key: string, label: string, initial = false): ModuleField => ({ key, label, type: 'boolean', default: initial });
const date = (key: string, label: string, required = false): ModuleField => ({ key, label, type: 'date', required, default: '' });
const relation = (key: string, label: string, target: string, required = false): ModuleField => ({ key, label, type: 'relation', relation: target, required, default: 0 });
const select = (key: string, label: string, choices: string[], initial = choices[0]): ModuleField => ({ key, label, type: 'select', options: choices.map(value => ({ value, label: value })), default: initial });
const multi = (key: string, label: string, target: string): ModuleField => ({ key, label, type: 'multi', relation: target, default: [] });
const profile = (): ModuleField => select('profile', 'กระบวนงาน', ['general', 'm35', 'evaluation']);

export const moduleDefinitions: ModuleDefinition[] = [
  { key: 'workflowSteps', title: 'ขั้นตอนย่อยและเกณฑ์เวลา', description: 'กำหนดขั้นตอนหลัก/ย่อย 1/ย่อย 2 แยกงานทั่วไป ม.35 และประเมิน พร้อมกรณีมีคำร้อง',
    fields: [text('name', 'ชื่อขั้นตอน', true), profile(), integer('step', 'ขั้นตอนหลัก', 1, 8, 1), integer('subStep', 'ขั้นตอนย่อย 1', 0, 99), integer('thirdStep', 'ขั้นตอนย่อย 2', 0, 99),
      integer('durationDays', 'ระยะเวลา (วัน)', 1, 36500, 28), integer('noPetitionDays', 'ระยะเวลาไม่มีคำร้อง (วัน)', 1, 36500, 28), integer('petitionDays', 'ระยะเวลามีคำร้อง (วัน)', 1, 36500, 42),
      integer('firstThreshold', 'เกณฑ์ล่าช้าระดับ 1 (วัน)', 1, 36500, 30), integer('secondThreshold', 'เกณฑ์ล่าช้าระดับ 2 (วัน)', 1, 73000, 60), select('operator', 'ผู้ดำเนินการ', ['ทั้งหมด', 'กรม', 'อปท.']), flag('active', 'ใช้งาน', true)],
    columns: ['name', 'profile', 'step', 'subStep', 'thirdStep', 'durationDays'], sources: ['app/manage_stepsub.php', 'app/manage_substep.php', 'app/show_step.php', 'app/substep.php', 'js/function_db.php'] },
  { key: 'workflowCases', title: 'งานแก้ไข ม.35 และงานประเมิน', description: 'ผังหนึ่งมีงานแก้ไขหรือรอบประเมินได้หลายรายการ ไม่ต้องเขียนทับข้อมูลผังหลัก',
    fields: [text('name', 'ชื่องาน', true), relation('planId', 'ผังเมือง', 'plans', true), select('profile', 'ประเภทงาน', ['m35', 'evaluation']), memo('detail', 'รายละเอียด'), select('operator', 'ผู้ดำเนินการ', ['กรม', 'อปท.']), select('state', 'สถานะงาน', ['active', 'completed', 'cancelled']), date('start', 'วันเริ่ม'), date('stop', 'วันสิ้นสุด'), flag('petition', 'มีคำร้อง'), text('reason', 'เหตุผล')],
    columns: ['name', 'planId', 'profile', 'state', 'start', 'stop'], sources: ['app/cityplan_m35X.php', 'app/cityplan_m35X_detail.php', 'app/cityplan_est.php', 'app/cityplan_est_detail.php', 'app/manage.php'] },
  { key: 'progressEntries', title: 'บันทึกขั้นตอนดำเนินการ', description: 'เก็บวันที่จริงและวันที่แผน แยกขั้นตอนย่อยของผังและงานแก้ไข/ประเมิน',
    fields: [text('name', 'รายละเอียดรายการ', true), relation('planId', 'ผังเมือง', 'plans', true), relation('caseId', 'งานแก้ไข/ประเมิน', 'workflowCases'), relation('definitionId', 'ขั้นตอน', 'workflowSteps', true),
      date('start', 'วันเริ่มจริง'), date('stop', 'วันสิ้นสุดจริง'), date('plannedStart', 'วันเริ่มตามแผน'), date('plannedStop', 'วันสิ้นสุดตามแผน'), flag('petition', 'มีคำร้อง'), memo('note', 'หมายเหตุ'), flag('makeCurrent', 'กำหนดเป็นขั้นตอนปัจจุบัน')],
    columns: ['name', 'planId', 'definitionId', 'start', 'stop'], sources: ['app/show_csd.php', 'app/pro_csd.php', 'app/cityplan_detail.php', 'app/manage.php'] },
  { key: 'planSchedules', title: 'แผนดำเนินงานที่บันทึกไว้', description: 'สร้างแผนเริ่ม–สิ้นสุดจากเกณฑ์เวลา รวมกรณีมี/ไม่มีคำร้อง และปรับแผนได้',
    fields: [text('name', 'ชื่อแผน', true), relation('planId', 'ผังเมือง', 'plans', true), profile(), date('start', 'วันเริ่มแผน', true), flag('petition', 'มีคำร้อง'), memo('note', 'หมายเหตุ')],
    columns: ['name', 'planId', 'profile', 'start', 'petition'], sources: ['app/manage_plan.php', 'app/manage_plan_pro.php', 'app/manage_plan_proX.php', 'app/cityplan_detail.php'] },
  { key: 'committees', title: 'การมอบหมายคณะติดตาม', description: 'กำหนดคณะ 1–7 ตามเขตตรวจราชการแทนการคำนวณแบบวนคณะ',
    fields: [text('name', 'ชื่อคณะ', true), integer('committee', 'คณะที่', 1, 7, 1), integer('kethId', 'เขตตรวจราชการ', 1, 18, 1), text('orderNumber', 'เลขที่คำสั่ง'), date('assignedOn', 'วันที่มอบหมาย')],
    columns: ['name', 'committee', 'kethId', 'orderNumber'], sources: ['report/dashboard_kana.php', 'nav1.php'] },
  { key: 'companies', title: 'บริษัทและผู้รับจ้าง', description: 'ทะเบียนบริษัท เบอร์โทรศัพท์ และผู้ประสานงานของโครงการ',
    fields: [text('name', 'ชื่อบริษัท', true), text('phone', 'โทรศัพท์'), text('contact', 'ผู้ประสานงาน'), memo('address', 'ที่อยู่')], columns: ['name', 'phone', 'contact'], sources: ['apps/manage_company.php', 'apps/manage_company(pro).php', 'apps/show_company.php', 'apps/company_project.php'] },
  { key: 'projects', title: 'โครงการจัดหาและบำรุงรักษา', description: 'งบประมาณ ราคาสัญญา ปีงบประมาณ บริษัท และอุปกรณ์ที่เกี่ยวข้อง',
    fields: [text('name', 'ชื่อโครงการ', true), integer('year', 'ปีงบประมาณ พ.ศ.', 2400, 2800, 2569), number('budget', 'งบประมาณ (บาท)'), number('price', 'ราคาสัญญา (บาท)'), text('contract', 'เลขที่สัญญา'), relation('companyId', 'บริษัท', 'companies'), text('contact', 'ผู้ประสานงาน'), multi('equipmentIds', 'อุปกรณ์ในโครงการ', 'equipment'), memo('note', 'หมายเหตุ')],
    columns: ['name', 'year', 'companyId', 'budget', 'price'], sources: ['apps/manage_project.php', 'apps/manage_project(pro).php', 'apps/show_project.php', 'apps/project_equipment.php', 'apps/project_equipment(pro).php', 'apps/project_load_equipment.php', 'apps/project_report.php', 'apps/project_report2.php'] },
  { key: 'equipment', title: 'ระบบและอุปกรณ์', description: 'ทะเบียนอุปกรณ์ ราคาจัดหา อายุประกัน ปีทดแทน และค่าบำรุงรักษารายปี',
    fields: [text('name', 'ชื่อระบบ/อุปกรณ์', true), text('assetNumber', 'เลขครุภัณฑ์'), text('serial', 'หมายเลขเครื่อง'), integer('quantity', 'จำนวน', 1, 100000, 1), number('price', 'ราคาจัดหา (บาท)'), integer('purchasedYear', 'ปีจัดหา พ.ศ.', 2400, 2800, 2569), integer('replacementYear', 'ปีจัดหาทดแทน (0 = ยังไม่กำหนด)', 0, 2800), integer('warrantyYears', 'ระยะประกัน (ปี)', 0, 30), integer('firstMaintenanceYear', 'ปีเริ่มบำรุงรักษา (0 = หลังประกัน)', 0, 2800), number('maintenanceAmount', 'ค่าบำรุงรักษาต่อปี (บาท)'), relation('projectId', 'โครงการ', 'projects'), memo('note', 'หมายเหตุ')],
    columns: ['name', 'assetNumber', 'purchasedYear', 'replacementYear', 'maintenanceAmount'], sources: ['apps/manage_hardware.php', 'apps/manage_hardware(pro).php', 'apps/show_hardware.php', 'apps/search_eqm.php'] },
  { key: 'inspections', title: 'รายงานเข้าตรวจสอบอุปกรณ์', description: 'วันที่เข้าตรวจ ผู้ติดต่อ ผลการตรวจ การดำเนินงาน และข้อเสนอแนะ',
    fields: [text('name', 'ชื่อรายงาน', true), relation('projectId', 'โครงการ', 'projects', true), multi('equipmentIds', 'อุปกรณ์ที่ตรวจ', 'equipment'), date('inspectedOn', 'วันที่ตรวจ', true), text('contact', 'ผู้ติดต่อ'), text('phone', 'โทรศัพท์'), select('result', 'ผลตรวจ', ['normal', 'needs_repair', 'out_of_service']), memo('working', 'การดำเนินงาน'), memo('note', 'หมายเหตุ'), memo('suggestion', 'ข้อเสนอแนะ')],
    columns: ['name', 'projectId', 'inspectedOn', 'result'], sources: ['apps/manage_rce.php', 'apps/manage_rce(pro).php', 'apps/rce_data.php', 'apps/show_rce.php'] },
  { key: 'maintenancePlans', title: 'แผนบำรุงรักษาหลายปี', description: 'เลือกอุปกรณ์ สร้างตารางรายปี แยกอยู่ในประกัน บำรุงรักษา และจัดหาทดแทน',
    fields: [text('name', 'ชื่อแผน', true), integer('startYear', 'ปีเริ่มต้น พ.ศ.', 2400, 2800, 2569), integer('years', 'จำนวนปี', 1, 10, 5), multi('equipmentIds', 'อุปกรณ์ในแผน', 'equipment'), memo('note', 'หมายเหตุ')],
    columns: ['name', 'startYear', 'years', 'equipmentIds'], sources: ['apps/manage_plan.php', 'apps/plan_equipment.php', 'apps/plan_equipment(pro).php', 'apps/plan_show.php', 'apps/plan_show2.php', 'apps/index_x(pro).php', 'apps/show_plan.php', 'apps/demo_m1.php'] },
  { key: 'userDirectory', title: 'ทะเบียนผู้ใช้งานและหน่วยงาน', description: 'ข้อมูลผู้ประสานงาน สิทธิ์ที่ระบุในทะเบียน และสถานะการใช้งาน',
    fields: [text('name', 'ชื่อ–นามสกุล', true), text('userCode', 'รหัสผู้ใช้', true), text('affiliation', 'หน่วยงาน'), select('role', 'ระดับสิทธิ์ในทะเบียน', ['superadmin', 'admin', 'guest']), flag('active', 'ใช้งาน', true)],
    columns: ['name', 'userCode', 'affiliation', 'role', 'active'], sources: ['app/manage_admin.php', 'app/pro_admin.php'] },
  { key: 'provinceAssignments', title: 'จังหวัด ส่วนรับผิดชอบ และเขตตรวจ', description: 'แก้ไขการจัดกลุ่มจังหวัดและลำดับแสดงผล โดยใช้รหัสจังหวัดเดิม',
    fields: [text('name', 'ชื่อจังหวัด', true), integer('provinceId', 'รหัสจังหวัด', 1, 77, 1), integer('regionId', 'ภาค', 1, 5, 1), integer('kethId', 'เขตตรวจราชการ (กทม. = 0)', 0, 18), integer('affiliationId', 'ส่วนรับผิดชอบ', 1, 10, 1), integer('sortOrder', 'ลำดับแสดงผล', 0, 999)],
    columns: ['name', 'provinceId', 'regionId', 'kethId', 'affiliationId'], sources: ['app/manage_province.php', 'app/pro_province.php'] },
];

export const moduleDefinition = (key: string): ModuleDefinition | undefined => moduleDefinitions.find(item => item.key === key);
