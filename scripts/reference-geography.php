<?php
// Read-only visual fixture: render the real PHP templates with local seed data.
// Database includes are removed before evaluation; no database is contacted.
error_reporting(0);
ini_set('session.use_cookies', '0');
session_save_path(__DIR__ . '/../.tmp');
session_id('upms-visual-reference');
session_start();
$_SESSION['sessionID'] = session_id();
$_SESSION['intra_czid'] = 'visual-reference';
$_SESSION['timeLasetdActive'] = time();
$seedPlans = json_decode(stream_get_contents(STDIN), true);
$treeDatabase = new PDO('sqlite::memory:');
$treeDatabase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$treeDatabase->exec('CREATE TABLE tb_cityplan (cp_no INTEGER, cp_stage INTEGER, cp_status INTEGER, cp_effective INTEGER, cp_eec TEXT, cp_owner INTEGER)');
$insertPlan = $treeDatabase->prepare('INSERT INTO tb_cityplan VALUES (?, ?, ?, ?, ?, ?)');
foreach ($seedPlans as $plan) {
    $stages = ['เปิดใหม่' => 1, 'ประเมิน 5 ปี' => 2, 'ปรับปรุง' => 3, 'ประเมิน 10 ปี' => 4];
    $effective = $plan['effectiveStatus'] === 'มีผลบังคับใช้' ? 1 : ($plan['effectiveStatus'] === 'สิ้นสุดบังคับใช้' ? 2 : 0);
    $insertPlan->execute([$plan['id'], $stages[$plan['stage']], $effective ? 2 : 1, $effective, $plan['isEec'] ? 'Y' : 'N', $plan['owner'] === 'กรม' ? 1 : 2]);
}
function clear_cp_session() {}
function in_up_delSql($sql) {}
function selectSql($sql) {
    global $seedPlans, $treeDatabase;
    if (str_contains($sql, 'tb_log')) return [['COUNT(log_seq)' => 1]];
    if (str_contains($sql, 'tb_user')) return [['user' => 1, 'user_status' => '9', 'user_affiliation' => '071201']];
    if (str_contains($sql, 'tb_cityplan') && !str_contains($sql, 'ref_province')) {
        $sql = preg_replace('/GROUP BY cp_owner ASC/i', 'GROUP BY cp_owner ORDER BY cp_owner ASC', $sql);
        return $treeDatabase->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    if (!preg_match("/pvn_park\s*=\s*'(\d+)'/", $sql, $match)) throw new Exception('Unexpected reference query');
    $plans = array_values(array_filter($seedPlans, fn($p) => $p['regionId'] == (int)$match[1]));
    if (!str_contains($sql, 'GROUP BY')) return [['COUNT(*)' => count($plans)]];
    $provinces = [];
    foreach ($plans as $plan) {
        $id = $plan['provinceId'];
        if (!isset($provinces[$id])) $provinces[$id] = ['pvn_id' => $id, 'pvn_name' => $plan['provinceName'], 'COUNT(*)' => 0];
        $provinces[$id]['COUNT(*)']++;
    }
    ksort($provinces);
    return array_values($provinces);
}
function renderTemplate($filename) {
    $source = file_get_contents($filename);
    // XMLHttpRequest decodes and removes the response's leading UTF-8 BOM.
    $source = preg_replace('/^\xEF\xBB\xBF/', '', $source);
    $source = preg_replace('/\binclude\s+[\'\"][^\'\"]*function_db\.php[\'\"]\s*;/', '', $source);
    ob_start();
    eval('?>' . $source);
    $html = ob_get_clean();
    return preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
}
$sourceRoot = $argv[1];
echo json_encode([
    'sidebar' => renderTemplate($sourceRoot . '/nav1.php'),
    'content' => renderTemplate($sourceRoot . (($argv[2] ?? '') === 'tree' ? '/report/tree-chart.php' : '/app/show_extra.php')),
], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
