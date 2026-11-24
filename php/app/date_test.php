<?php 
include '../js/function_php.php';
$step = 1;
$sql = "SELECT * FROM ref_up_fst_step WHERE step_id = '".$step."'";
$result = selectSql($sql);
$day =  ($result[0]['step_time']*7);



$start = '2566-10-30';
$days = $day; 

$date = date_create($start);
date_add($date, date_interval_create_from_date_string($days . " days"));
echo 'ขั้นตอนที่ :: '.$step.'<br>';
echo 'จำนวนวัน   :: ' . $days .' ('.$result[0]['step_time'].' สัปดาห์)'. '<br>';
echo 'วันที่เริ่มต้น :: ' . $start . '<br>';
echo 'วันที่สิ้นสุด  :: '.date_format($date, "Y-m-d");
echo "<hr>";

$sql = 'SELECT * FROM ref_up_fst_step , ref_up_snd_step WHERE step_id = sub_step AND sub_no != 0 AND step_id = "'.$step.'"';
$result = selectSql($sql);
//$start = '2566-04-30';
foreach($result as $row){	
	$days = $row['sub_time']*7;
	$date = date_create($start);
	date_add($date, date_interval_create_from_date_string($days . " days"));
	echo 'ขั้นตอนที่ :: '.$step.'.'.$row['sub_no'].'<br>';
	echo 'จำนวนวัน   :: ' . $days .' ('.$row['sub_time'].' สัปดาห์)'. '<br>';
	echo 'วันที่เริ่มต้น :: ' . $start . '<br>';
	echo 'วันที่สิ้นสุด  :: '.date_format($date, "Y-m-d").'<br>';
	$start = date_format($date, "Y-m-d");
	echo "<hr>";
	
}


?>