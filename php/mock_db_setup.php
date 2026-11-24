<?php
/**
 * Standalone SQLite Mockup Database Setup for DPT_UPMS
 * Uses local SQLite file (mock_db.sqlite) with 100% synthetic mockup data.
 * No real MySQL/Intranet database connection or real SQL dump is used.
 */

function getMockSqlitePath() {
    return __DIR__ . '/mock_db.sqlite';
}

function registerSqliteMysqlCompat(PDO $pdo) {
    $pdo->sqliteCreateFunction('CONCAT', function (...$args) {
        $out = '';
        foreach ($args as $a) {
            if ($a === null) {
                return null;
            }
            $out .= (string)$a;
        }
        return $out;
    });
    $pdo->sqliteCreateFunction('IF', function ($cond, $t, $f) {
        return $cond ? $t : $f;
    }, 3);
    $pdo->sqliteCreateFunction('NOW', function () {
        return date('Y-m-d H:i:s');
    }, 0);
    $pdo->sqliteCreateFunction('CURDATE', function () {
        return date('Y-m-d');
    }, 0);
    $pdo->sqliteCreateFunction('YEAR', function ($d) {
        return $d ? (int)substr((string)$d, 0, 4) : null;
    }, 1);
    $pdo->sqliteCreateFunction('MONTH', function ($d) {
        return $d ? (int)substr((string)$d, 5, 2) : null;
    }, 1);
    $pdo->sqliteCreateFunction('DAY', function ($d) {
        return $d ? (int)substr((string)$d, 8, 2) : null;
    }, 1);
}

function openMockSqlitePdo() {
    $dbPath = getMockSqlitePath();
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA synchronous = NORMAL');
    registerSqliteMysqlCompat($pdo);
    return $pdo;
}

function setupMockDatabase($forceReset = false) {
    $messages = [];
    $dbPath = getMockSqlitePath();

    try {
        if ($forceReset && file_exists($dbPath)) {
            @unlink($dbPath);
        }

        $pdo = openMockSqlitePdo();
        $messages[] = "เชื่อมต่อ Mockup Database (SQLite) สำเร็จ";

        // 1. tb_user
        $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_user` (
            `user_id` TEXT PRIMARY KEY,
            `user_fname` TEXT DEFAULT '',
            `user_lname` TEXT DEFAULT '',
            `user_affiliation` TEXT DEFAULT '',
            `user_pmacess` INTEGER DEFAULT 1,
            `user_status` INTEGER DEFAULT 1
        )");

        // 2. ref_user_affiliation
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_user_affiliation` (
            `use_aff_id` TEXT PRIMARY KEY,
            `use_aff_name` TEXT DEFAULT ''
        )");

        // 3. tb_log
        $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_log` (
            `log_seq` INTEGER PRIMARY KEY AUTOINCREMENT,
            `log_user` TEXT DEFAULT '',
            `log_user_id` TEXT DEFAULT '',
            `log_action` TEXT DEFAULT '',
            `log_type` TEXT DEFAULT '',
            `log_target` TEXT DEFAULT '',
            `log_date` TEXT DEFAULT (datetime('now','localtime'))
        )");

        // 4. ref_province
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_province` (
            `pvn_id` INTEGER PRIMARY KEY,
            `pvn_name` TEXT NOT NULL,
            `pvn_affiliation` TEXT NOT NULL,
            `pvn_park` INTEGER NOT NULL,
            `pvn_keth` INTEGER NOT NULL,
            `pvn_asc` INTEGER NOT NULL
        )");

        // 5. Reference lookup tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_affiliation` (`aff_id` TEXT PRIMARY KEY, `aff_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_scope` (`scope_id` INTEGER PRIMARY KEY, `scope_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_status` (`status_id` INTEGER PRIMARY KEY, `status_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_stage` (`stage_id` INTEGER PRIMARY KEY, `stage_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_effective` (`eff_id` INTEGER PRIMARY KEY, `eff_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_operate_fst` (`opt_fst_id` INTEGER PRIMARY KEY, `opt_fst_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_operate_snd` (`opt_snd_id` INTEGER PRIMARY KEY, `opt_snd_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_type` (`type_id` INTEGER PRIMARY KEY, `type_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_note` (`note_id` INTEGER PRIMARY KEY, `note_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_keth` (`keth_id` INTEGER PRIMARY KEY AUTOINCREMENT, `keth_no` INTEGER UNIQUE NOT NULL, `keth_name` TEXT NOT NULL, `keth_kana` INTEGER NOT NULL DEFAULT 1)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_consider` (`csd_id` INTEGER PRIMARY KEY AUTOINCREMENT, `csd_name` TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_myhead` (`head_id` INTEGER PRIMARY KEY AUTOINCREMENT, `head_name` TEXT DEFAULT '', `myhead_name` TEXT DEFAULT '', `myhead_status` INTEGER DEFAULT 1)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_sum_plan` (`sp_id` INTEGER PRIMARY KEY AUTOINCREMENT, `sp_name` TEXT NOT NULL)");

        // 6. Step reference tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_up_fst_step` (
            `step_id` INTEGER PRIMARY KEY,
            `step_name` TEXT NOT NULL,
            `step_time` INTEGER DEFAULT 0,
            `step_time1` INTEGER DEFAULT 0,
            `step_time2` INTEGER DEFAULT 0,
            `step_start` TEXT DEFAULT NULL,
            `step_end` TEXT DEFAULT NULL,
            `step_note` TEXT DEFAULT '1'
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_up_snd_step` (
            `sub_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `sub_step` INTEGER NOT NULL,
            `sub_no` INTEGER NOT NULL,
            `sub_name` TEXT NOT NULL,
            `sub_time` INTEGER DEFAULT 0,
            `sub_time1` INTEGER DEFAULT 0,
            `sub_time2` INTEGER DEFAULT 0,
            `sub_ot1` INTEGER DEFAULT 30,
            `sub_ot2` INTEGER DEFAULT 60,
            `sub_start` TEXT DEFAULT NULL,
            `sub_end` TEXT DEFAULT NULL,
            `sub_note` INTEGER DEFAULT 1,
            UNIQUE(`sub_step`, `sub_no`)
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_up_trd_step` (
            `trd_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `trd_fst` INTEGER NOT NULL,
            `trd_snd` INTEGER NOT NULL,
            `trd_step` INTEGER NOT NULL,
            `trd_name` TEXT NOT NULL DEFAULT '',
            `trd_time` INTEGER DEFAULT 0,
            `trd_ot1` INTEGER DEFAULT 30,
            `trd_ot2` INTEGER DEFAULT 60
        )");

        // 7. Main cityplan & detail tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_cityplan` (
            `cp_no` INTEGER PRIMARY KEY AUTOINCREMENT,
            `cp_id` TEXT DEFAULT '',
            `cp_type` TEXT DEFAULT '2',
            `cp_eec` TEXT DEFAULT 'N',
            `cp_province` INTEGER NOT NULL,
            `cp_name` TEXT NOT NULL,
            `cp_csd` INTEGER NOT NULL DEFAULT 0,
            `cp_note` INTEGER DEFAULT 0,
            `cp_budget` TEXT DEFAULT '-',
            `cp_owner` TEXT DEFAULT '1',
            `cp_operator` TEXT DEFAULT '1',
            `cp_scope` TEXT DEFAULT '1',
            `cp_status` TEXT DEFAULT '1',
            `cp_stage` TEXT DEFAULT '1',
            `cp_effective` TEXT DEFAULT '0',
            `cp_step` TEXT DEFAULT '0',
            `cp_step_1` TEXT DEFAULT '0',
            `cp_step_2` TEXT DEFAULT '0',
            `cp_step_3` TEXT DEFAULT '0',
            `cp_step_start` TEXT DEFAULT NULL,
            `cp_step_stop` TEXT DEFAULT NULL,
            `cp_step_1_start` TEXT DEFAULT NULL,
            `cp_step_1_stop` TEXT DEFAULT NULL,
            `cp_step_2_start` TEXT DEFAULT NULL,
            `cp_step_2_stop` TEXT DEFAULT NULL,
            `cp_step_3_start` TEXT DEFAULT NULL,
            `cp_step_3_stop` TEXT DEFAULT NULL,
            `cp_consider` INTEGER DEFAULT 0,
            `cp_petition` INTEGER DEFAULT 0,
            `cp_stage_update` INTEGER DEFAULT NULL
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_cityplan_main` (
            `cp_no` INTEGER PRIMARY KEY,
            `cp_id` TEXT DEFAULT '',
            `cp_type` TEXT DEFAULT '2',
            `cp_eec` TEXT DEFAULT 'N',
            `cp_province` INTEGER NOT NULL,
            `cp_name` TEXT NOT NULL,
            `cp_owner` TEXT DEFAULT '1',
            `cp_operator` TEXT DEFAULT '1',
            `cp_scope` TEXT DEFAULT '1',
            `cp_status` TEXT DEFAULT '1',
            `cp_stage` TEXT DEFAULT '1',
            `cp_effective` TEXT DEFAULT '0'
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_cityplan_step_detail` (
            `csd_seq` INTEGER PRIMARY KEY AUTOINCREMENT,
            `csd_cp` INTEGER NOT NULL,
            `csd_step_fst` INTEGER DEFAULT 0,
            `csd_step_snd` INTEGER DEFAULT 0,
            `csd_step_trd` INTEGER DEFAULT 0,
            `csd_start` TEXT DEFAULT NULL,
            `csd_stop` TEXT DEFAULT NULL,
            `csd_start_2` TEXT DEFAULT NULL,
            `csd_stop_2` TEXT DEFAULT NULL,
            `csd_petition` INTEGER DEFAULT 0,
            `csd_note` TEXT DEFAULT '0'
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_note` (
            `note_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `note_cp` INTEGER DEFAULT 0,
            `note_csd` INTEGER DEFAULT NULL,
            `note_m35` INTEGER DEFAULT NULL,
            `note_step` INTEGER DEFAULT 0,
            `note_step1` INTEGER DEFAULT 0,
            `note_step2` INTEGER DEFAULT 0,
            `note_remark` TEXT DEFAULT NULL,
            `note_uper` TEXT DEFAULT NULL,
            `note_status` INTEGER DEFAULT 1,
            `note_date` TEXT DEFAULT (datetime('now','localtime'))
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_note35` (
            `note_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `note_cp35` INTEGER DEFAULT 0,
            `note_csd35` INTEGER DEFAULT NULL,
            `note_step_fst` INTEGER DEFAULT 0,
            `note_step_snd` INTEGER DEFAULT 0,
            `note_remark` TEXT DEFAULT NULL,
            `note_uper` TEXT DEFAULT NULL,
            `note_mh` TEXT DEFAULT NULL,
            `note_status` INTEGER DEFAULT 1,
            `note_date` TEXT DEFAULT (datetime('now','localtime'))
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_history` (
            `his_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `his_no` INTEGER DEFAULT 0,
            `his_cp` INTEGER DEFAULT 0,
            `his_step` INTEGER DEFAULT 0,
            `his_step_start` TEXT DEFAULT NULL,
            `his_step_stop` TEXT DEFAULT NULL,
            `his_step_1` INTEGER DEFAULT 0,
            `his_step_1_start` TEXT DEFAULT NULL,
            `his_step_1_stop` TEXT DEFAULT NULL,
            `his_step_2` INTEGER DEFAULT 0,
            `his_step_2_start` TEXT DEFAULT NULL,
            `his_step_2_stop` TEXT DEFAULT NULL,
            `his_user` TEXT DEFAULT NULL,
            `his_date` TEXT DEFAULT (datetime('now','localtime'))
        )");

        // 8. M35 tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_m35_fst_step` (
            `m35_fst_id` INTEGER PRIMARY KEY,
            `m35_fst_name` TEXT NOT NULL,
            `m35_fst_time` INTEGER DEFAULT 4,
            `m35_fst_time1` INTEGER DEFAULT 4,
            `m35_fst_time2` INTEGER DEFAULT 6
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_m35_snd_step` (
            `m35_snd_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `m35_snd_step_main` INTEGER NOT NULL,
            `m35_snd_step_sub` INTEGER NOT NULL,
            `m35_snd_name` TEXT NOT NULL,
            `m35_operate` INTEGER DEFAULT 1,
            `m35_snd_time` INTEGER DEFAULT 4,
            `m35_snd_time1` INTEGER DEFAULT 4,
            `m35_snd_time2` INTEGER DEFAULT 6
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_m35_trd_step` (
            `m35_trd_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `m35_trd_step_fst` INTEGER NOT NULL,
            `m35_trd_step_snd` INTEGER NOT NULL,
            `m35_trd_step_trd` INTEGER NOT NULL,
            `m35_trd_name` TEXT NOT NULL,
            `m35_trd_time` INTEGER DEFAULT 2
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_cityplan_step_detail_m35` (
            `csd35_seq` INTEGER PRIMARY KEY AUTOINCREMENT,
            `csd35_cp` INTEGER NOT NULL,
            `csd35_status` INTEGER DEFAULT 1,
            `csd35_step_fst` INTEGER DEFAULT 1,
            `csd35_step_snd` INTEGER DEFAULT 1,
            `csd35_step_trd` INTEGER DEFAULT 0,
            `csd35_start` TEXT DEFAULT NULL,
            `csd35_stop` TEXT DEFAULT NULL,
            `csd35_snd_start` TEXT DEFAULT NULL,
            `csd35_snd_stop` TEXT DEFAULT NULL,
            `csd35_start_2` TEXT DEFAULT NULL,
            `csd35_stop_2` TEXT DEFAULT NULL,
            `csd35_plan_fst_start` TEXT DEFAULT NULL,
            `csd35_plan_fst_stop` TEXT DEFAULT NULL,
            `csd35_plan_snd_start` TEXT DEFAULT NULL,
            `csd35_plan_snd_stop` TEXT DEFAULT NULL,
            `csd35_petition` INTEGER DEFAULT 0,
            `csd35_note` TEXT DEFAULT '0',
            `csd35_event` TEXT DEFAULT '',
            `csd35_show` INTEGER DEFAULT 1,
            `csd35_update` TEXT DEFAULT NULL
        )");

        // 9. Est tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_cityplan_est` (
            `est_seq` INTEGER PRIMARY KEY AUTOINCREMENT,
            `est_cp` INTEGER NOT NULL,
            `est_csd` INTEGER NOT NULL DEFAULT 0
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_est_snd_step` (
            `est_snd_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `est_snd_step_main` INTEGER NOT NULL,
            `est_snd_step_sub` INTEGER NOT NULL,
            `est_snd_name` TEXT NOT NULL,
            `est_operate` INTEGER DEFAULT 1,
            `est_snd_time` INTEGER DEFAULT 4,
            `est_snd_time1` INTEGER DEFAULT 4,
            `est_snd_time2` INTEGER DEFAULT 6
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `ref_cityplan_step_detail_est` (
            `csd_est_seq` INTEGER PRIMARY KEY AUTOINCREMENT,
            `csd_est_cp` INTEGER NOT NULL,
            `csd_est_step_fst` INTEGER DEFAULT 1,
            `csd_est_step_snd` INTEGER DEFAULT 1,
            `csd_est_snd_start` TEXT DEFAULT NULL,
            `csd_est_snd_stop` TEXT DEFAULT NULL,
            `csd_est_petition` INTEGER DEFAULT 0,
            `csd_est_update` TEXT DEFAULT NULL
        )");

        // Check if already seeded
        $alreadySeeded = (int)$pdo->query("SELECT COUNT(*) FROM `tb_cityplan`")->fetchColumn() > 0;
        if ($alreadySeeded && !$forceReset) {
            $messages[] = "Mockup Database พร้อมใช้งานแล้ว (พบข้อมูลจำลองอยู่แล้ว)";
            return ['success' => true, 'messages' => $messages];
        }

        $pdo->beginTransaction();

        // Seed ref_user_affiliation
        $stmtAff = $pdo->prepare("INSERT OR REPLACE INTO `ref_user_affiliation` (`use_aff_id`, `use_aff_name`) VALUES (?, ?)");
        foreach ([
            ['071200', 'สำนักผังเมืองรวม (จำลอง)'],
            ['071201', 'ส่วนผังเมืองรวม 1 (จำลอง)'],
            ['071202', 'ส่วนผังเมืองรวม 2 (จำลอง)'],
            ['071203', 'ส่วนผังเมืองรวม 3 (จำลอง)'],
            ['071204', 'ส่วนผังเมืองรวม 4 (จำลอง)'],
        ] as $aff) {
            $stmtAff->execute($aff);
        }

        // Seed mock users
        $stmtUser = $pdo->prepare("INSERT OR REPLACE INTO `tb_user` (`user_id`, `user_fname`, `user_lname`, `user_affiliation`, `user_pmacess`, `user_status`) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ([
            ['demo-user-1', 'ผู้ดูแลระบบ', 'สูงสุด (Mock Super Admin)', '071201', 9, 9],
            ['demo-user-2', 'เจ้าหน้าที่', 'สผม.ส่วน 1 (Mock Admin)', '071201', 2, 2],
            ['demo-user-3', 'ผู้ใช้งาน', 'ทั่วไป (Mock User)', '071202', 1, 1],
            ['demo-user-4', 'ผู้เยี่ยมชม', 'ระบบ (Mock Guest)', '071200', 0, 0],
        ] as $u) {
            $stmtUser->execute($u);
        }

        // Seed 77 provinces
        $provinces = [
            [1, 'กรุงเทพมหานคร', '1', 3, 1, 2], [2, 'จันทบุรี', '1', 4, 6, 9], [3, 'ฉะเชิงเทรา', '1', 4, 3, 10],
            [4, 'ชลบุรี', '1', 4, 6, 11], [5, 'ชัยนาท', '1', 3, 2, 12], [6, 'ตราด', '1', 4, 6, 16],
            [7, 'นครนายก', '1', 3, 3, 20], [8, 'นครปฐม', '1', 3, 4, 21], [9, 'นนทบุรี', '1', 3, 1, 27],
            [10, 'ปทุมธานี', '1', 3, 1, 33], [11, 'ปราจีนบุรี', '1', 4, 3, 35], [12, 'พระนครศรีอยุธยา', '1', 3, 1, 41],
            [13, 'ระยอง', '1', 4, 6, 52], [14, 'ลพบุรี', '1', 3, 2, 57], [15, 'สมุทรปราการ', '1', 3, 3, 64],
            [16, 'สมุทรสงคราม', '1', 3, 5, 65], [17, 'สมุทรสาคร', '1', 3, 5, 66], [18, 'สระแก้ว', '1', 4, 3, 67],
            [19, 'สระบุรี', '1', 3, 1, 68], [20, 'สิงห์บุรี', '1', 3, 2, 69], [21, 'อ่างทอง', '1', 3, 2, 75],
            [22, 'กำแพงเพชร', '2', 1, 18, 5], [23, 'เชียงราย', '2', 1, 16, 14], [24, 'เชียงใหม่', '2', 1, 15, 15],
            [25, 'ตาก', '2', 1, 17, 17], [26, 'นครสวรรค์', '2', 1, 18, 26], [27, 'น่าน', '2', 1, 16, 29],
            [28, 'พะเยา', '2', 1, 16, 39], [29, 'พิจิตร', '2', 1, 18, 43], [30, 'พิษณุโลก', '2', 1, 17, 44],
            [31, 'เพชรบูรณ์', '2', 1, 17, 46], [32, 'แพร่', '2', 1, 16, 47], [33, 'แม่ฮ่องสอน', '2', 1, 15, 50],
            [34, 'ลำปาง', '2', 1, 15, 55], [35, 'ลำพูน', '2', 1, 15, 56], [36, 'สุโขทัย', '2', 1, 17, 70],
            [37, 'อุตรดิตถ์', '2', 1, 17, 73], [38, 'อุทัยธานี', '2', 1, 18, 74],
            [39, 'กาฬสินธุ์', '3', 2, 12, 4], [40, 'ขอนแก่น', '3', 2, 12, 6], [41, 'ชัยภูมิ', '3', 2, 13, 13],
            [42, 'นครพนม', '3', 2, 11, 23], [43, 'นครราชสีมา', '3', 2, 13, 24], [44, 'บึงกาฬ', '3', 2, 10, 30],
            [45, 'บุรีรัมย์', '3', 2, 13, 31], [46, 'มหาสารคาม', '3', 2, 12, 48], [47, 'มุกดาหาร', '3', 2, 11, 49],
            [48, 'ยโสธร', '3', 2, 14, 51], [49, 'ร้อยเอ็ด', '3', 2, 12, 53], [50, 'เลย', '3', 2, 10, 54],
            [51, 'ศรีสะเกษ', '3', 2, 14, 60], [52, 'สกลนคร', '3', 2, 11, 61], [53, 'สุรินทร์', '3', 2, 13, 72],
            [54, 'หนองคาย', '3', 2, 10, 76], [55, 'หนองบัวลำภู', '3', 2, 10, 77], [56, 'อำนาจเจริญ', '3', 2, 14, 78],
            [57, 'อุดรธานี', '3', 2, 10, 72], [58, 'อุบลราชธานี', '3', 2, 14, 79],
            [59, 'กระบี่', '4', 5, 7, 1], [60, 'กาญจนบุรี', '4', 3, 4, 3], [61, 'ชุมพร', '4', 5, 7, 8],
            [62, 'ตรัง', '4', 5, 8, 18], [63, 'นครศรีธรรมราช', '4', 5, 8, 25], [64, 'นราธิวาส', '4', 5, 9, 28],
            [65, 'ประจวบคีรีขันธ์', '4', 3, 5, 34], [66, 'ปัตตานี', '4', 5, 9, 36], [67, 'พังงา', '4', 5, 7, 37],
            [68, 'พัทลุง', '4', 5, 8, 38], [69, 'เพชรบุรี', '4', 3, 5, 45], [70, 'ภูเก็ต', '4', 5, 7, 48],
            [71, 'ยะลา', '4', 5, 9, 50], [72, 'ระนอง', '4', 5, 7, 52], [73, 'ราชบุรี', '4', 3, 4, 53],
            [74, 'สงขลา', '4', 5, 9, 62], [75, 'สตูล', '4', 5, 9, 63], [76, 'สุพรรณบุรี', '4', 3, 4, 71],
            [77, 'สุราษฎร์ธานี', '4', 5, 8, 73]
        ];
        $stmtPvn = $pdo->prepare("INSERT OR REPLACE INTO `ref_province` (`pvn_id`, `pvn_name`, `pvn_affiliation`, `pvn_park`, `pvn_keth`, `pvn_asc`) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($provinces as $p) {
            $stmtPvn->execute($p);
        }

        // Seed lookup tables
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_affiliation` VALUES (?, ?)");
        foreach ([['1','ส่วนผังเมืองรวมที่ 1'],['2','ส่วนผังเมืองรวมที่ 2'],['3','ส่วนผังเมืองรวมที่ 3'],['4','ส่วนผังเมืองรวมที่ 4']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_scope` VALUES (?, ?)");
        foreach ([[1,'เฉพาะพื้นที่ (ไม่เต็มอำเภอ)'],[2,'ทั่วทั้งอำเภอ (เต็มอำเภอ)']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_status` VALUES (?, ?)");
        foreach ([[1,'เปิดพื้นที่ใหม่'],[2,'วางและจัดทำ / มีผลบังคับใช้']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_stage` VALUES (?, ?)");
        foreach ([[1,'เปิดใหม่'],[2,'ประเมิน 5 ปี'],[3,'ปรับปรุง'],[4,'ประเมิน 10 ปี']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_effective` VALUES (?, ?)");
        foreach ([[0,'ยังไม่มีผลบังคับใช้'],[1,'มีผลบังคับใช้'],[2,'สิ้นสุดบังคับใช้']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_operate_fst` VALUES (?, ?)");
        foreach ([[1,'กรม'],[2,'อปท.']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_operate_snd` VALUES (?, ?)");
        foreach ([[0,'ไม่ระบุ'],[1,'กรม'],[2,'อปท.'],[3,'จ้างที่ปรึกษา'],[4,'ร่วมดำเนินการ'],[5,'กรมร่วมอปท.'],[6,'อื่นๆ'],[7,'อื่นๆ'],[8,'อื่นๆ']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_type` VALUES (?, ?)");
        foreach ([[1,'ผังเมืองรวมจังหวัด'],[2,'ผังเมืองรวมเมือง/ชุมชน']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_note` VALUES (?, ?)");
        foreach ([[1,'ปกติ'],[2,'มีคำร้อง'],[3,'ไม่มีคำร้อง']] as $r) $stmt->execute($r);

        $stmt = $pdo->prepare("INSERT OR REPLACE INTO `ref_keth` (`keth_no`, `keth_name`, `keth_kana`) VALUES (?, ?, ?)");
        for ($k = 1; $k <= 18; $k++) {
            $stmt->execute([$k, "เขตตรวจราชการที่ {$k}", (int)ceil($k / 3.6)]);
        }

        // Seed steps (0..8)
        $stmtFst = $pdo->prepare("INSERT OR REPLACE INTO `ref_up_fst_step` VALUES (?, ?, ?, ?, ?, NULL, NULL, ?)");
        $fstSteps = [
            [0, 'ไม่ระบุขั้นตอน', 0, 0, 0, '1'],
            [1, 'การสำรวจ กำหนดเขตวางผัง วิเคราะห์และจัดทำร่างผังเมืองรวม', 10, 0, 0, '1'],
            [2, 'การประชุมพิจารณาร่างผังเมืองรวม', 6, 0, 0, '1'],
            [3, 'การประชุมรับฟังความคิดเห็นของประชาชน', 8, 0, 0, '1'],
            [4, 'การประชุมคณะกรรมการผังเมือง (มาตรา 26)', 4, 0, 0, '1'],
            [5, 'การปิดประกาศพร้อมข้อกำหนด 90 วัน (มาตรา 29)', 13, 0, 0, '1'],
            [6, 'การรวบรวม ตรวจสอบ พิจารณาคำร้อง และแจ้งผลการพิจารณาคำร้อง (มาตรา 30 และมาตรา 31)', 0, 16, 26, '1'],
            [7, 'การจัดทำเอกสารประกอบการยกร่างประกาศกระทรวงมหาดไทย', 6, 0, 0, '1'],
            [8, 'การเสนอคณะรัฐมนตรีให้ความเห็นชอบ และประกาศในราชกิจจานุเบกษา', 10, 0, 0, '1'],
        ];
        foreach ($fstSteps as $fs) $stmtFst->execute($fs);

        $stmtSnd = $pdo->prepare("INSERT OR REPLACE INTO `ref_up_snd_step` (`sub_step`, `sub_no`, `sub_name`, `sub_time`, `sub_time1`, `sub_time2`, `sub_ot1`, `sub_ot2`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        for ($s = 0; $s <= 8; $s++) {
            for ($n = 0; $n <= 8; $n++) {
                $name = ($n == 0) ? ($s == 0 ? 'ไม่ระบุขั้นตอน' : 'ไม่ระบุขั้นตอนย่อย') : "ขั้นตอนย่อย {$s}.{$n}";
                $ot1 = ($s == 5 && $n == 2) ? 90 : 30;
                $ot2 = ($s == 5 && $n == 2) ? 180 : 60;
                $stmtSnd->execute([$s, $n, $name, 4, 4, 6, $ot1, $ot2]);
            }
        }

        $stmtTrd = $pdo->prepare("INSERT INTO `ref_up_trd_step` (`trd_fst`, `trd_snd`, `trd_step`, `trd_name`, `trd_time`, `trd_ot1`, `trd_ot2`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        for ($f = 0; $f <= 8; $f++) {
            for ($s = 0; $s <= 8; $s++) {
                for ($t = 0; $t <= 8; $t++) {
                    $tName = ($t == 0) ? 'ไม่ระบุขั้นตอนย่อยที่ 2' : "ขั้นตอนย่อยที่ {$f}.{$s}.{$t}";
                    $stmtTrd->execute([$f, $s, $t, $tName, 2, 30, 60]);
                }
            }
        }

        // Seed M35 & Est step definitions
        $stmtM35Fst = $pdo->prepare("INSERT OR REPLACE INTO `ref_m35_fst_step` VALUES (?, ?, ?, ?, ?)");
        for ($f = 0; $f <= 8; $f++) {
            $stmtM35Fst->execute([$f, $f == 0 ? 'ไม่ระบุขั้นตอน ม.35' : "ขั้นตอน ม.35 ที่ {$f}", 4, 4, 6]);
        }
        $stmtM35Snd = $pdo->prepare("INSERT OR REPLACE INTO `ref_m35_snd_step` (`m35_snd_step_main`, `m35_snd_step_sub`, `m35_snd_name`, `m35_operate`, `m35_snd_time`, `m35_snd_time1`, `m35_snd_time2`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtEstSnd = $pdo->prepare("INSERT OR REPLACE INTO `ref_est_snd_step` (`est_snd_step_main`, `est_snd_step_sub`, `est_snd_name`, `est_operate`, `est_snd_time`, `est_snd_time1`, `est_snd_time2`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        for ($f = 0; $f <= 8; $f++) {
            for ($s = 0; $s <= 8; $s++) {
                foreach ([1, 2] as $opr) {
                    $stmtM35Snd->execute([$f, $s, "ขั้นตอนย่อย ม.35 {$f}.{$s}", $opr, 4, 4, 6]);
                    $stmtEstSnd->execute([$f, $s, "ขั้นตอนย่อยประเมิน {$f}.{$s}", $opr, 4, 4, 6]);
                }
            }
        }

        // Seed 24 SYNTHETIC MOCKUP cityplan records across all 5 regions (NO real database data)
        $todayBeYear = (int)date('Y') + 543;
        $recentStart = $todayBeYear . '-' . date('m-d');
        $oldStart = ($todayBeYear - 1) . '-01-15';
        $midStart = $todayBeYear . '-01-10';

        // [cp_no, cp_province, cp_name, cp_type, cp_eec, cp_budget, cp_owner, cp_operator, cp_scope, cp_status, cp_stage, cp_effective, step, step1, step2, start, stop]
        $mockPlans = [
            // ภาคเหนือ (park 1)
            [1,  24, 'เมืองตัวอย่างเชียงใหม่ (Mock)',     '2', 'N', '2568', '1', '1', '2', '1', '1', '0', '1', '3', '1', $recentStart, ''],
            [2,  23, 'ชุมชนตัวอย่างเชียงราย (Mock)',    '2', 'N', '2568', '1', '4', '1', '1', '1', '0', '2', '2', '0', $midStart,    ''],
            [3,  30, 'เมืองตัวอย่างพิษณุโลก (Mock)',    '2', 'N', '2567', '1', '5', '2', '2', '3', '1', '4', '1', '0', $oldStart,    ''],
            [4,  34, 'ชุมชนตัวอย่างลำปาง (Mock)',       '2', 'N', '2567', '2', '2', '1', '2', '2', '1', '0', '0', '0', '',           ''],
            [5,  26, 'เมืองตัวอย่างนครสวรรค์ (Mock)',   '2', 'N', '2568', '1', '3', '2', '1', '1', '0', '3', '4', '1', $recentStart, ''],

            // ภาคตะวันออกเฉียงเหนือ (park 2)
            [6,  40, 'เมืองตัวอย่างขอนแก่น (Mock)',     '2', 'N', '2568', '1', '1', '2', '1', '1', '0', '1', '5', '0', $recentStart, ''],
            [7,  43, 'เมืองตัวอย่างนครราชสีมา (Mock)',  '2', 'N', '2567', '1', '4', '2', '2', '3', '1', '5', '2', '0', $midStart,    ''],
            [8,  58, 'ชุมชนตัวอย่างอุบลราชธานี (Mock)', '2', 'N', '2568', '1', '5', '1', '1', '1', '0', '6', '1', '0', $recentStart, ''],
            [9,  57, 'เมืองตัวอย่างอุดรธานี (Mock)',    '2', 'N', '2566', '2', '2', '1', '2', '2', '1', '0', '0', '0', '',           ''],
            [10, 45, 'ชุมชนตัวอย่างบุรีรัมย์ (Mock)',   '2', 'N', '2568', '1', '3', '2', '1', '1', '0', '2', '1', '0', $oldStart,    ''],

            // ภาคกลาง (park 3)
            [11, 1,  'พื้นที่ตัวอย่างกรุงเทพฯ (Mock)',  '2', 'N', '2568', '1', '1', '2', '2', '3', '1', '1', '2', '0', $recentStart, ''],
            [12, 9,  'เมืองตัวอย่างนนทบุรี (Mock)',     '2', 'N', '2568', '1', '4', '2', '1', '1', '0', '3', '2', '1', $recentStart, ''],
            [13, 12, 'เมืองตัวอย่างอยุธยา (Mock)',     '2', 'N', '2567', '1', '5', '1', '2', '4', '1', '7', '1', '0', $midStart,    ''],
            [14, 60, 'ชุมชนตัวอย่างกาญจนบุรี (Mock)',  '2', 'N', '2568', '1', '3', '1', '1', '1', '0', '4', '3', '0', $oldStart,    ''],
            [15, 73, 'เมืองตัวอย่างราชบุรี (Mock)',     '2', 'N', '2567', '2', '2', '2', '2', '2', '1', '0', '0', '0', '',           ''],

            // ภาคตะวันออก (park 4)
            [16, 4,  'เมืองตัวอย่างชลบุรี (Mock)',     '2', 'Y', '2568', '1', '1', '2', '1', '1', '0', '2', '4', '1', $recentStart, ''],
            [17, 13, 'เมืองตัวอย่างระยอง (Mock)',      '2', 'Y', '2568', '1', '4', '2', '2', '3', '1', '5', '1', '0', $recentStart, ''],
            [18, 3,  'ชุมชนตัวอย่างฉะเชิงเทรา (Mock)', '2', 'Y', '2567', '1', '5', '1', '1', '1', '0', '8', '2', '0', $midStart,    ''],
            [19, 2,  'ชุมชนตัวอย่างจันทบุรี (Mock)',   '2', 'N', '2567', '1', '3', '1', '2', '2', '1', '0', '0', '0', '',           ''],

            // ภาคใต้ (park 5)
            [20, 70, 'เมืองตัวอย่างภูเก็ต (Mock)',     '2', 'N', '2568', '1', '1', '2', '1', '1', '0', '1', '4', '0', $recentStart, ''],
            [21, 74, 'เมืองตัวอย่างสงขลา (Mock)',      '2', 'N', '2568', '1', '4', '2', '2', '3', '1', '3', '1', '0', $recentStart, ''],
            [22, 77, 'ชุมชนตัวอย่างสุราษฎร์ธานี (Mock)','2', 'N', '2567', '1', '5', '1', '1', '1', '0', '6', '2', '0', $oldStart,    ''],
            [23, 59, 'ชุมชนตัวอย่างกระบี่ (Mock)',     '2', 'N', '2568', '1', '3', '2', '1', '1', '0', '2', '3', '0', $midStart,    ''],
            [24, 63, 'เมืองตัวอย่างนครศรีฯ (Mock)',    '2', 'N', '2567', '2', '2', '1', '2', '2', '1', '0', '0', '0', '',           ''],
        ];

        $stmtCp = $pdo->prepare("INSERT INTO `tb_cityplan`
            (`cp_no`, `cp_id`, `cp_type`, `cp_eec`, `cp_province`, `cp_name`, `cp_csd`, `cp_note`, `cp_budget`, `cp_owner`, `cp_operator`, `cp_scope`, `cp_status`, `cp_stage`, `cp_effective`, `cp_step`, `cp_step_1`, `cp_step_2`, `cp_step_3`, `cp_step_start`, `cp_step_stop`, `cp_step_1_start`, `cp_step_1_stop`, `cp_step_2_start`, `cp_step_2_stop`, `cp_consider`, `cp_petition`)
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0', ?, ?, ?, ?, ?, ?, 0, 0)");

        $stmtMain = $pdo->prepare("INSERT INTO `tb_cityplan_main`
            (`cp_no`, `cp_id`, `cp_type`, `cp_eec`, `cp_province`, `cp_name`, `cp_owner`, `cp_operator`, `cp_scope`, `cp_status`, `cp_stage`, `cp_effective`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmtCsd = $pdo->prepare("INSERT INTO `ref_cityplan_step_detail`
            (`csd_seq`, `csd_cp`, `csd_step_fst`, `csd_step_snd`, `csd_step_trd`, `csd_start`, `csd_stop`, `csd_start_2`, `csd_stop_2`, `csd_petition`, `csd_note`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, '1')");

        foreach ($mockPlans as $p) {
            list($no, $pvn, $name, $type, $eec, $budget, $owner, $oper, $scope, $status, $stage, $eff, $s1, $s2, $s3, $start, $stop) = $p;
            $cpCode = sprintf('MOCK-%03d', $no);
            $stmtCp->execute([$no, $cpCode, $type, $eec, $pvn, $name, $no, $budget, $owner, $oper, $scope, $status, $stage, $eff, $s1, $s2, $s3, $start, $stop, $start, $stop, $start, $stop]);
            $stmtMain->execute([$no, $cpCode, $type, $eec, $pvn, $name, $owner, $oper, $scope, $status, $stage, $eff]);
            $stmtCsd->execute([$no, $no, (int)$s1, (int)$s2, (int)$s3, $start, $stop, $start, $stop]);
        }

        // Seed 4 mock M35 records & 2 mock Est records
        $stmtM35 = $pdo->prepare("INSERT INTO `ref_cityplan_step_detail_m35`
            (`csd35_cp`, `csd35_status`, `csd35_step_fst`, `csd35_step_snd`, `csd35_step_trd`, `csd35_start`, `csd35_stop`, `csd35_snd_start`, `csd35_snd_stop`, `csd35_petition`, `csd35_note`)
            VALUES (?, 1, ?, ?, 0, ?, '', ?, '', 0, '1')");
        $stmtM35->execute([1, 1, 2, $recentStart, $recentStart]);
        $stmtM35->execute([6, 1, 3, $midStart, $midStart]);
        $stmtM35->execute([12, 2, 1, $recentStart, $recentStart]);
        $stmtM35->execute([20, 1, 4, $oldStart, $oldStart]);

        $stmtEst = $pdo->prepare("INSERT INTO `tb_cityplan_est` (`est_seq`, `est_cp`, `est_csd`) VALUES (?, ?, ?)");
        $stmtCsdEst = $pdo->prepare("INSERT INTO `ref_cityplan_step_detail_est` (`csd_est_seq`, `csd_est_cp`, `csd_est_step_fst`, `csd_est_step_snd`, `csd_est_snd_start`, `csd_est_snd_stop`, `csd_est_petition`) VALUES (?, ?, ?, ?, ?, '', 0)");
        $stmtEst->execute([1, 4, 1]);
        $stmtCsdEst->execute([1, 4, 2, 1, $recentStart]);
        $stmtEst->execute([2, 9, 2]);
        $stmtCsdEst->execute([2, 9, 3, 2, $midStart]);

        $pdo->commit();
        $messages[] = "สร้างข้อมูลจำลอง (Mockup Data) จำนวน " . count($mockPlans) . " ผัง เรียบร้อย (ไม่ได้ใช้ข้อมูลจริงจากฐานข้อมูล)";

        return ['success' => true, 'messages' => $messages];
    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return ['success' => false, 'messages' => array_merge($messages, ['Error: ' . $e->getMessage()])];
    }
}

// If accessed directly in browser
if (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) == 'mock_db_setup.php') {
    $force = isset($_GET['reset']) && $_GET['reset'] == '1';
    $res = setupMockDatabase($force);
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Setup Mockup Database</title>";
    echo "<link href='https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap' rel='stylesheet'>";
    echo "<style>body{font-family:'Kanit',sans-serif;background:#f4f6f9;padding:40px;text-align:center;} .box{max-width:600px;margin:0 auto;background:#fff;padding:30px;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.1);} .success{color:#28a745;} .fail{color:#dc3545;} ul{text-align:left;line-height:2;} a{display:inline-block;margin-top:20px;padding:10px 25px;background:#007bff;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;}</style>";
    echo "</head><body><div class='box'>";
    if ($res['success']) {
        echo "<h2 class='success'>✅ ติดตั้ง Mockup Database (SQLite) สำเร็จ</h2>";
    } else {
        echo "<h2 class='fail'>⚠️ เกิดข้อผิดพลาด</h2>";
    }
    echo "<ul>";
    foreach ($res['messages'] as $msg) {
        echo "<li>" . htmlspecialchars($msg) . "</li>";
    }
    echo "</ul>";
    echo "<a href='index.php'>เข้าสู่ระบบหลัก (ไม่ต้อง Login)</a>";
    echo "</div></body></html>";
}
