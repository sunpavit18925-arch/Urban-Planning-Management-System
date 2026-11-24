<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  
  
  if($_POST['event']=='ru_cp'){
	  $sql = "SELECT * FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
	  $result = selectSql($sql);
	  
	  /*' csd_seq = "'.$resu[0]['cp_csd'].'" '*/
	  $sql = 'SELECT csd_start,  csd_stop, csd_start_2, csd_stop_2 FROM ref_cityplan_step_detail WHERE csd_seq = "'.$result[0]['cp_csd'].'" ';
	  $resu = selectSql($sql);
	  

	  
	  
	   $json = array();
        foreach ($result as $row) {
			if($row['cp_step']=="" OR $row['cp_step'] == null){ $row['cp_step']='0'; $row['cp_step_1']=="0"; $row['cp_step_2']=="0";}
			if($row['cp_step_1']=="" OR $row['cp_step_1'] == null){ $row['cp_step_1']="0"; $row['cp_step_2']=="0";}
			if($row['cp_step_2']=="" OR $row['cp_step_2'] == null){ $row['cp_step_2']="0";}
          $json = [
			'cp_no' => $row['cp_no'],	
			'cp_step' => $row['cp_step'],
			'cp_step_start' => $row['cp_step_start'],
			'cp_step_stop' => $row['cp_step_stop'],			
			'cp_step_1' => $row['cp_step_1'],
			'cp_step_1_start' => $row['cp_step_1_start'],
			'cp_step_1_stop' => $row['cp_step_1_stop'],			
			'cp_step_2' => $row['cp_step_2'],
			'cp_step_2_start' => $row['cp_step_2_start'],
			'cp_step_2_stop' => $row['cp_step_2_stop'],
			'cp_petition' => $row['cp_petition'],
			'cp_step_1_start_opt' => $resu[0]['csd_start'],
			'cp_step_1_stop_opt' => $resu[0]['csd_stop'],
			'cp_step_2_start_opt' => $resu[0]['csd_start_2'],
			'cp_step_2_stop_opt' => $resu[0]['csd_stop_2'],
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }elseif($_POST['event']=='load_snd_step'){
		$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['cp_step']."' AND sub_no != 0";
		$result = selectSql($sql);
		if(count($result)==0){
			echo '<option value="0">ไม่มีขั้นตอน</option>';
			?>
			<script>
				$("#div-step-date").hide();
				$("#div-step-1").hide();
				$("#div-step-1-date").hide();
				$("#div-step-2").hide();
				$("#div-step-2-date").hide();
				$('#cp_step_1').val(0);
				$('#cp_step_2').val(0);
			</script>
			<?php
		}else{
			echo '<option value="0">ไม่ระบุขั้นตอน</option>';
		}
		foreach($result as $row){
			echo '<option value="'.$row['sub_no'].'">ขั้นตอนที่  '.$row['sub_step'].'.'.$row['sub_no'].' '.$row['sub_name'].'</option>';
		}
	}
	elseif($_POST['event']=='load_trd_step'){
		$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['cp_step']."' AND trd_snd = '".$_POST['cp_step_1']."' AND trd_step != 0";
		$result = selectSql($sql);
		if(count($result)==0){
			echo '<option value="0">ไม่มีขั้นตอน</option>';
			?>
			<script>
				$("#div-step-2").hide();
				$("#div-step-2-date").hide();
			</script>
			<?php
		}else{
			echo "<script>$('#div-step-2').show();</script>";
			echo '<option value="0">ไม่ระบุขั้นตอน</option>';
		}
		foreach($result as $row){
			echo '<option value="'.$row['trd_step'].'">ขั้นตอนที่  '.$row['trd_fst'].'.'.$row['trd_snd'].'.'.$row['trd_step'].' '.$row['trd_name'].'</option>';
		}
	}
	elseif($_POST['event']=='change_step_fst'){
		/*$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['cp_step']."' AND sub_no != 0";
		$result = selectSql($sql);
		if(count($result)==0){
			echo '<option value="0">ไม่มีขั้นตอน</option>';
			?>
			<script>
				$("#div-step-1").hide();
				$("#div-step-1-date").hide();
				$("#div-step-2").hide();
				$("#div-step-2-date").hide();
				$('#cp_step_1').val(0);
				$('#cp_step_2').val(0);
			</script>
			<?php
		}else{
			echo '<option value="0">ไม่ระบุขั้นตอน</option>';
			?>
			<script>
				$("#div-step-1").show();
				$("#div-step-1-date").hide();
				$("#div-step-2").hide();
				$("#div-step-2-date").hide();
			</script>
			<?php
		}
		foreach($result as $row){
			echo '<option value="'.$row['sub_no'].'">ขั้นตอนที่  '.$row['sub_step'].'.'.$row['sub_no'].' '.$row['sub_name'].'</option>';
		}
	}
	elseif($_POST['event']=='change_step_snd'){
		$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['cp_step']."' AND trd_snd = '".$_POST['cp_step_1']."' AND trd_step != 0";
		$result = selectSql($sql);
		if(count($result)==0){
			echo '<option value="0">ไม่มีขั้นตอน</option>';
			?>
			<script>
				$("#div-step-2").hide();
				$("#div-step-2-date").hide();
				$('#cp_step_2').val(0);
			</script>
			<?php
		}else{
			echo '<option value="0">ไม่ระบุขั้นตอน</option>';
			?>
			<script>
				$("#div-step-2").show();
				$("#div-step-2-date").show();
			</script>
			<?php
		}
		foreach($result as $row){
			echo '<option value="'.$row['trd_step'].'">ขั้นตอนที่  '.$row['trd_fst'].'.'.$row['trd_snd'].'.'.$row['trd_step'].' '.$row['trd_name'].'</option>';
		}*/
	}/*elseif($_POST['event']=='uonly_step_uu'){
		$sql = "SELECT * FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		$step_fst = $result[0]['cp_step'];
		$step_snd = $result[0]['cp_step_1'];
		$step_trd = $result[0]['cp_step_2'];
		
		if($_POST['step']){
			
		}
	}*/
	
	elseif($_POST['event']=='uonly_step_uu'){
		if($_POST['cp_step']!=6){
			$_POST['cp_petition'] = 0;
		}
		if($_POST['cp_step']==0){
			$_POST['cp_step'] = 0;
			
			$_POST['cp_step_1'] = 0;
			$_POST['cp_step_1_start_opt'] = '';
			$_POST['cp_step_1_stop_opt'] = '';
			
			$_POST['cp_step_2'] = 0;
			$_POST['cp_step_2_start_opt'] = '';
			$_POST['cp_step_2_stop_opt'] = '';
		}elseif($_POST['cp_step_1']==0){
			$_POST['cp_step_1'] = 0;
			$_POST['cp_step_1_start_opt'] = '';
			$_POST['cp_step_1_stop_opt'] = '';
			
			$_POST['cp_step_2'] = 0;
			$_POST['cp_step_2_start_opt'] = '';
			$_POST['cp_step_2_stop_opt'] = '';
		}elseif($_POST['cp_step_2']==0){
			$_POST['cp_step_2'] = 0;
			$_POST['cp_step_2_start_opt'] = '';
			$_POST['cp_step_2_stop_opt'] = '';
		}
		$sql = "SELECT * FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		if($result[0]['cp_step'] == $_POST['cp_step'] AND $result[0]['cp_step_1'] == $_POST['cp_step_1'] AND 	$result[0]['cp_step_2'] == $_POST['cp_step_2']){
			$sql = "UPDATE ref_cityplan_step_detail SET
					csd_start  = '".$_POST['cp_step_1_start_opt']."',
					csd_stop  = '".$_POST['cp_step_1_stop_opt']."',
					csd_start_2  = '".$_POST['cp_step_2_start_opt']."',
					csd_stop_2  = '".$_POST['cp_step_2_stop_opt']."',
					csd_petition = '".$_POST['cp_petition']."'
					WHERE  csd_seq  = '".$result[0]['cp_csd']."'";
			//echo $sql;
			in_up_delSql($sql);
			
			$sql = "UPDATE tb_cityplan SET
					cp_petition = '".$_POST['cp_petition']."'
					WHERE  cp_no  = '".$_POST['cp_no']."'";
			in_up_delSql($sql);
			
			/* log edit cp , edit csd */
			$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','cp','".$_POST['cp_no']."')";
			in_up_delSql($sql);
			$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','csd','".$result[0]['csd_seq']."')";
			in_up_delSql($sql);
			
			$uonly_step_uu = "แก้ไขข้อมูล";			
		}else{
			$sql = "INSERT INTO ref_cityplan_step_detail(csd_cp, csd_step_fst, csd_step_snd, csd_step_trd, csd_petition, csd_start , csd_stop , csd_start_2 , csd_stop_2) 
					VALUES('".$_POST['cp_no']."','".$_POST['cp_step']."','".$_POST['cp_step_1']."','".$_POST['cp_step_2']."','".$_POST['cp_petition']."','".$_POST['cp_step_1_start_opt']."','".$_POST['cp_step_1_stop_opt']."','".$_POST['cp_step_2_start_opt']."','".$_POST['cp_step_2_stop_opt']."')";  
			//echo $sql;
			in_up_delSql($sql);
			//echo "";
			$sql = "SELECT * FROM ref_cityplan_step_detail WHERE csd_cp = '".$_POST['cp_no']."' ORDER BY csd_seq DESC LIMIT 1";
			$csd = selectSql($sql);
			$sql = "UPDATE tb_cityplan SET
					cp_step  = '".$_POST['cp_step']."',
					cp_step_1  = '".$_POST['cp_step_1']."',
					cp_step_2  = '".$_POST['cp_step_2']."',
					cp_petition = '".$_POST['cp_petition']."',
					cp_csd = '".$csd[0]['csd_seq']."'
					WHERE  cp_no  = '".$_POST['cp_no']."'";
			//echo $sql;
			in_up_delSql($sql);
			
			/* log edit cp , add csd */
			$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','cp','".$_POST['cp_no']."')";
			in_up_delSql($sql);
			$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','csd','".$result[0]['csd_seq']."')";
			in_up_delSql($sql);
			
			$uonly_step_uu = "แก้ไขขั้นตอน";
		}
		echo $uonly_step_uu.'สำเร็จ';
	}
	/*
	elseif($_POST['event']=='uonly_step'){
		
		if($_POST['cp_step']==0){$_POST['cp_step_start'] = "";$_POST['cp_step_stop'] = "";$_POST['cp_step_1'] = 0;}
		if($_POST['cp_step_1']==0){$_POST['cp_step_1_start'] = "";$_POST['cp_step_1_stop'] = "";$_POST['cp_step_2'] = 0;}
		if($_POST['cp_step_2']==0){$_POST['cp_step_2_start'] = "";$_POST['cp_step_2_stop'] = "";}
		
		$sql = "SELECT cp_step , cp_step_start , cp_step_stop ,cp_step_1 , cp_step_1_start , cp_step_1_stop , cp_step_2 , cp_step_2_start , cp_step_2_stop FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
		//echo $sql;
		$result = selectSql($sql);
		
		if($result[0]['cp_step'] != $_POST['cp_step'] OR $result[0]['cp_step_start'] != $_POST['cp_step_start'] OR $result[0]['cp_step'] != $_POST['cp_step']){
			$sql = "INSERT INTO ref_history(his_cp,his_step,his_step_start,his_step_stop,his_step_1,his_step_1_start,his_step_1_stop,his_step_2,his_step_2_start,his_step_2_stop,his_user) VALUE('".$_POST['cp_no']."',
			'".$_POST['cp_step']."','".$_POST['cp_step_start']."','".$_POST['cp_step_stop']."',
			'".$_POST['cp_step_1']."','".$_POST['cp_step_1_start']."','".$_POST['cp_step_1_stop']."',
			'".$_POST['cp_step_2']."','".$_POST['cp_step_2_start']."','".$_POST['cp_step_2_stop']."',
			'".$_SESSION['intra_czid']."')";
			in_up_delSql($sql);
			//$text = "แก้ไขขั้นตอนหลัก";
		}
		elseif($result[0]['cp_step_1'] != $_POST['cp_step_1'] OR $result[0]['cp_step_1_start'] != $_POST['cp_step_1_start'] OR $result[0]['cp_step_1'] != $_POST['cp_step_1']){
			$sql = "INSERT INTO ref_history(his_cp,his_step,his_step_start,his_step_stop,his_step_1,his_step_1_start,his_step_1_stop,his_step_2,his_step_2_start,his_step_2_stop,his_user) VALUE('".$_POST['cp_no']."',
			'".$_POST['cp_step']."','".$_POST['cp_step_start']."','".$_POST['cp_step_stop']."',
			'".$_POST['cp_step_1']."','".$_POST['cp_step_1_start']."','".$_POST['cp_step_1_stop']."',
			'".$_POST['cp_step_2']."','".$_POST['cp_step_2_start']."','".$_POST['cp_step_2_stop']."',
			'".$_SESSION['intra_czid']."')";
			in_up_delSql($sql);
			//$text = "แก้ไขขั้นตอนรอง";
		}
		elseif($result[0]['cp_step_2'] != $_POST['cp_step_2'] OR $result[0]['cp_step_2_start'] != $_POST['cp_step_2_start'] OR $result[0]['cp_step_2'] != $_POST['cp_step_2']){
			$sql = "INSERT INTO ref_history(his_cp,his_step,his_step_start,his_step_stop,his_step_1,his_step_1_start,his_step_1_stop,his_step_2,his_step_2_start,his_step_2_stop,his_user) VALUE('".$_POST['cp_no']."',
			'".$_POST['cp_step']."','".$_POST['cp_step_start']."','".$_POST['cp_step_stop']."',
			'".$_POST['cp_step_1']."','".$_POST['cp_step_1_start']."','".$_POST['cp_step_1_stop']."',
			'".$_POST['cp_step_2']."','".$_POST['cp_step_2_start']."','".$_POST['cp_step_2_stop']."',
			'".$_SESSION['intra_czid']."')";
			in_up_delSql($sql);
			//$text = "แก้ไขขั้นตอนรอง2";
		}
		
		$sql = "UPDATE tb_cityplan SET
				cp_petition = '".$_POST['cp_petition']."',
				cp_step = '".$_POST['cp_step']."',
				cp_step_start = '".$_POST['cp_step_start']."',
				cp_step_stop = '".$_POST['cp_step_stop']."',
				cp_step_1 = '".$_POST['cp_step_1']."',
				cp_step_1_start = '".$_POST['cp_step_1_start']."',
				cp_step_1_stop = '".$_POST['cp_step_1_stop']."',
				cp_step_2 = '".$_POST['cp_step_2']."',
				cp_step_2_start = '".$_POST['cp_step_2_start']."',
				cp_step_2_stop = '".$_POST['cp_step_2_stop']."'			
		WHERE cp_no = '".$_POST['cp_no']."'";
		in_up_delSql($sql);
		//echo $sql;
		$csd_plan_start = "";
		$csd_plan_stop = "";
		
		if($_POST['cp_step']!=0){
			if($_POST['cp_step_1']!=0){
				if($_POST['cp_step_2']!=0){
					$csd_plan_start = $_POST['cp_step_2_start'];
					$csd_plan_stop = $_POST['cp_step_2_stop'];
					$text ="date = trd";
				}else{
					$csd_plan_start = $_POST['cp_step_1_start'];
					$csd_plan_stop = $_POST['cp_step_1_stop'];
					$text ="date = snd";
				}
			}else{
				$csd_plan_start = $_POST['cp_step_start'];
				$csd_plan_stop = $_POST['cp_step_stop'];
				$text ="date = fst";
			}
		}else{
			$csd_plan_start = "";
			$csd_plan_stop = "";
			$text ="date = fst(null)";
		}
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','cp','".$_POST['cp_no']."')";
		in_up_delSql($sql);		
		//$sql = "INSERT INTO ref_cityplan_step_detail(csd_cp, csd_step_fst, csd_step_snd, csd_step_trd, csd_petition, csd_plan_start,csd_plan_stop) VALUES('".$_POST['cp_no']."','".$_POST['cp_step']."','".$_POST['cp_step_1']."','".$_POST['cp_step_2']."', '".$_POST['cp_petition']."','".$csd_plan_start."','".$csd_plan_stop."')";
		$sql = "INSERT INTO ref_cityplan_step_detail(csd_cp, csd_step_fst, csd_step_snd, csd_step_trd, csd_petition, csd_plan_fst_start, csd_plan_fst_stop, csd_plan_snd_start, csd_plan_snd_stop, csd_plan_trd_start, csd_plan_trd_stop) 
		VALUES('".$_POST['cp_no']."','".$_POST['cp_step']."','".$_POST['cp_step_1']."','".$_POST['cp_step_2']."','".$_POST['cp_petition']."','".$_POST['cp_step_start']."','".$_POST['cp_step_stop']."','".$_POST['cp_step_1_start']."','".$_POST['cp_step_1_stop']."','".$_POST['cp_step_2_start']."','".$_POST['cp_step_2_stop']."')";
		
		in_up_delSql($sql);		
		$sql = "SELECT csd_seq FROM ref_cityplan_step_detail WHERE csd_cp = '".$_POST['cp_no']."'  ORDER BY csd_seq DESC LIMIT 1";
		$result = selectSql($sql);		
		$sql = "UPDATE tb_cityplan SET cp_csd = '".$result[0]['csd_seq']."' WHERE cp_no = '".$_POST['cp_no']."'";
		in_up_delSql($sql);
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','csd','".$result[0]['csd_seq']."')";
		in_up_delSql($sql);
		//echo $sql;
		echo "แก้ไขสำเร็จ";
	}*/
	elseif($_POST['event']=="c_cp+c_csd"){
		 if($_POST['cp_step']==0){
			 $_POST['cp_step_1'] = 0;
			 $_POST['cp_step_2'] = 0;
		 }
        $sql = "INSERT INTO tb_cityplan(cp_type, cp_eec, cp_province, cp_name, cp_budget, cp_owner, cp_operator, cp_scope, cp_status, cp_stage, cp_effective, cp_consider) VALUES(
		'".$_POST['cp_type']."',
		'".$_POST['cp_eec']."',
		'".$_POST['cp_province']."',
		'".$_POST['cp_name']."',
		'".$_POST['cp_budget']."',
		'".$_POST['cp_owner']."',
		'".$_POST['cp_operator']."',
		'".$_POST['cp_scope']."',
		'".$_POST['cp_status']."',
		'".$_POST['cp_stage']."',
		'".$_POST['cp_effective']."',
		'".$_POST['cp_consider']."'
		)";
        in_up_delSql($sql);
		
		$sql = "SELECT * FROM tb_cityplan ORDER BY cp_no DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','cp','".$result[0]['cp_no']."')";
		in_up_delSql($sql);
		
		$sql = "INSERT INTO ref_cityplan_step_detail(csd_cp, csd_step_fst, csd_step_snd, csd_step_trd, csd_petition) VALUES('".$result[0]['cp_no']."','0','0','0', '0')";
		in_up_delSql($sql);	
		
		$sql = "SELECT csd_seq FROM ref_cityplan_step_detail WHERE csd_cp = '".$result[0]['cp_no']."'  ORDER BY csd_seq DESC LIMIT 1";
		$ende = selectSql($sql);
		
		$sql = "UPDATE tb_cityplan SET cp_csd = '".$ende[0]['csd_seq']."' WHERE cp_no = '".$result[0]['cp_no']."'";
		in_up_delSql($sql);
		
		//echo $sql;
		echo "เพิ่มข้อมูลสำเร็จ";
		
	}
	elseif($_POST['event']=='r_note'){
		$sql = "SELECT * FROM tb_note,ref_cityplan_step_detail WHERE note_csd = csd_seq AND note_status = 1 AND note_cp = '".$_POST['cp_no']."' ORDER BY note_timestamp DESC";
		//echo $sql;
		$result = selectSql($sql);
		$i=1;
		foreach($result as $row){
			if($i>1){
				echo '<hr style=";margin:12.5px 0">';
			}
			//echo ''.$i;?>
			<div style="display:flex;justify-content: space-between">
				<div><b>วันที่เพิ่มหมายเหตุ : </b><?= substr($row['note_timestamp'],8,2).' '.IntToMonth(substr($row['note_timestamp'],5,2),'th').' '.(substr($row['note_timestamp'],0,4)+543);?></div>
				<div><b>เวลา : </b><?= substr($row['note_timestamp'],11);?> น.</div>
			</div>
			<div style="margin:10px 0 0 0"><?= $row['note_remark'];?></div>
			<?php
			$i++;
		}
		
		
	}
	else{
		echo 'Error!';
	}
	  
	  
	  
	  
  
  

  
  
?>