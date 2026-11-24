<?php 
include '../js/function_db.php';


//$sql = "SELECT * , COUNT(*) AS x  FROM `tb_note` WHERE note_status = 1  AND note_cp = '".$_POST['cp_no']."' GROUP BY note_cp, note_step, note_step1 ,note_step2  ORDER BY x  DESC";
$sql = "SELECT * , COUNT(*) AS x  FROM `tb_note` WHERE note_status = 1 GROUP BY note_cp, note_step, note_step1 ,note_step2  ORDER BY x  DESC";
$result = selectSql($sql);

foreach($result as $row){
	echo '<br>';
	if($row['note_step']== null){$row['note_step']=0;}
	if($row['note_step1']== null){$row['note_step1']=0;}
	if($row['note_step2']== null){$row['note_step2']=0;}
	if($row['note_csd'] == null){
		echo 'null<br>';
		if($row['x']==1){
			
		}
		elseif($row['x']==2){			
			$sql = "INSERT INTO ref_cityplan_step_detail (csd_cp, csd_step_fst, csd_step_snd, csd_step_trd, csd_note) VALUE(
				'".$row['note_cp']."',
				'".$row['note_step']."',
				'".$row['note_step1']."',
				'".$row['note_step2']."',
				'".$row['x']."'
			)";
			in_up_delSql($sql);
			echo $sql;
			
			$sql = "SELECT csd_seq FROM ref_cityplan_step_detail ORDER BY csd_seq DESC LIMIT 1";
			$csd_seq = selectSql($sql);
			
	
			$sql = "UPDATE tb_note SET note_csd = '".$csd_seq[0]['csd_seq']."' WHERE note_status = 1 AND note_csd IS NULL AND note_cp =  '".$row['note_cp']."'";
			in_up_delSql($sql);
			//echo $sql;
			
			echo $row['note_id'].'<br>';
		}
		echo "success";
	}else{
		echo "<br>not null";
	}
	echo "___________________________________________________________________________________________________________";
}
	echo "<br><h1>END</h>";
?>