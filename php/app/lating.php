<?php 
include '../js/function_db.php';
$date = date('Y-m-d');
echo $date.'<br>';


$sql = "SELECT * FROM tb_cityplan, ref_cityplan_step_detail WHERE cp_csd = csd_seq";
$result = selectSql($sql);
$i  = 1;
$x = 0;
$lating = 0;
$planing =0;
foreach($result as $row){
	echo $i.'. '.$row['cp_name'].'<br>';
	if($row['csd_stop']!='' AND $row['csd_stop']!=null){
		$csd_stop_ad = (substr($row['csd_stop'],0,4)-543).substr($row['csd_stop'],4);
		echo 'csd_stop : '.$csd_stop_ad.'<Br>'; 
		if($csd_stop_ad < $date){
			echo 'TRUE';
			$lating++;
		}else{
			echo 'FALSE';
			$planing++;
		}
		$x++;
		
	}
	echo '<hr>';
	$i++;
}
echo "Lating : ".$lating.'<br>';
echo "Planing : ".$planing.'<br>';


?>