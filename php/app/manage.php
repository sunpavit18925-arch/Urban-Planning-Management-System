<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  $czid_len = strlen($_SESSION['intra_czid']);
  if($czid_len!=13){
	echo "เซสชั่นหมดของคุณอายุ 
โปรดลงชื่อเข้าสู่ระบบผ่านระบบใหม่อีกครั้ง";
}else{
    if ($_POST['event']=='c_pvn'){
        $sql = "INSERT INTO ref_province(pvn_name, pvn_affiliation) VALUES(
                '".$_POST['pvn_name']."','".$_POST['pvn_affiliation']."')";
        in_up_delSql($sql);
		echo "เพิ่มข้อมูลสำเร็จ";
		
		$sql = "SELECT pvn_id FROM ref_province ORDER BY pvn_id DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','pvn','".$result[0]['pvn_id']."')";
		in_up_delSql($sql);
    }
	elseif ($_POST['event']=='d_pvn'){
       $sql ="DELETE FROM ref_province WHERE pvn_id = '".$_POST['pvn_id']."'";
       in_up_delSql($sql);   
	   
	   $sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','del','pvn','".$_POST['pvn_id']."')";
		in_up_delSql($sql);
	   
        echo "ลบข้อมูลสำเร็จ";
    }
    elseif($_POST['event']=='ru_pvn'){
        $sql = "SELECT * FROM ref_province WHERE pvn_id = '".$_POST['pvn_id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
			'pvn_id' => $row['pvn_id'],
            'pvn_name' => $row['pvn_name'],
            'pvn_affiliation' => $row['pvn_affiliation']
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    elseif($_POST['event']=='u_pvn'){
        $sql = "UPDATE ref_province SET
            pvn_name = '".$_POST['pvn_name']."',
            pvn_affiliation = '".$_POST['pvn_affiliation']."'
			WHERE pvn_id = '".$_POST['pvn_id']."'";
        in_up_delSql($sql);		
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','pvn','".$_POST['pvn_id']."')";
		in_up_delSql($sql);
        echo "แก้ไขสำเร็จ";
    }
	 elseif ($_POST['event']=='c_cp'){
		 if($_POST['cp_step']==0){
			 $_POST['cp_step_1'] = 0;
			 $_POST['cp_step_2'] = 0;
		 }
        $sql = "INSERT INTO tb_cityplan(cp_type,cp_eec,cp_province,cp_name,cp_budget,cp_owner,cp_operator,cp_scope,cp_status,cp_stage,cp_effective,cp_step,cp_step_start,cp_step_stop,cp_step_1,cp_step_1_start,cp_step_1_stop,cp_step_2,cp_step_2_start,cp_step_2_stop,cp_consider) VALUES(
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
		'".$_POST['cp_step']."','".$_POST['cp_step_start']."','".$_POST['cp_step_stop']."',
		'".$_POST['cp_step_1']."','".$_POST['cp_step_1_start']."','".$_POST['cp_step_1_stop']."',
		'".$_POST['cp_step_2']."','".$_POST['cp_step_2_start']."','".$_POST['cp_step_2_stop']."',
		'".$_POST['cp_consider']."'
		)";
        in_up_delSql($sql);
		
		$sql = "SELECT cp_no FROM tb_cityplan ORDER BY cp_no DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','cp','".$result[0]['cp_no']."')";
		in_up_delSql($sql);
		
		
		//echo $sql;
		echo "เพิ่มข้อมูลสำเร็จ";
    }
	elseif ($_POST['event']=='d_cp'){
		$sql =  "DELETE FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
		in_up_delSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','del','cp','".$_POST['cp_no']."')";
		in_up_delSql($sql);
		echo "ลบข้อมูลสำเร็จ";
	}
    elseif($_POST['event']=='ru_cp'){
        $sql = "SELECT * FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
			'cp_no' => $row['cp_no'],
			'cp_id' => $row['cp_id'],
			'cp_type' => $row['cp_type'],
			'cp_eec' => $row['cp_eec'],
			'cp_province' => $row['cp_province'],
			'cp_name' => $row['cp_name'],
			'cp_budget' => $row['cp_budget'],
			'cp_owner' => $row['cp_owner'],
			'cp_operator' => $row['cp_operator'],
			'cp_scope' => $row['cp_scope'],
			'cp_status' => $row['cp_status'],
			'cp_stage' => $row['cp_stage'],
            'cp_effective' => $row['cp_effective'],
			'cp_step' => $row['cp_step'],
			'cp_step_start' => $row['cp_step_start'],
			'cp_step_stop' => $row['cp_step_stop'],
			'cp_step_1' => $row['cp_step_1'],
			'cp_step_1_start' => $row['cp_step_1_start'],
			'cp_step_1_stop' => $row['cp_step_1_stop'],
			'cp_step_2' => $row['cp_step_2'],
			'cp_step_2_start' => $row['cp_step_2_start'],
			'cp_step_2_stop' => $row['cp_step_2_stop'],
			'cp_consider' => $row['cp_consider'],
			'cp_petition' => $row['cp_petition'],
			'cp_date_notice' => $row['cp_date_notice'],
			'cp_date_exp' => $row['cp_date_exp'],
			'cp_current_status' => $row['cp_current_status'],
			'cp_dflaw' => $row['cp_dflaw'],
            ];
        }
		//$json['cp_step'];
		//$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$row['cp_step']."'";
		//$result = selectSql($sql);
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		//echo $sql;
    }
	elseif($_POST['event']=='u_cp'){
		$sql = "SELECT cp_step , cp_step_start , cp_step_stop ,cp_step_1 , cp_step_1_start , cp_step_1_stop , cp_step_2 , cp_step_2_start , cp_step_2_stop FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
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
            cp_id = '".$_POST['cp_id']."',
			cp_type = '".$_POST['cp_type']."',
			cp_eec = '".$_POST['cp_eec']."',
			cp_province = '".$_POST['cp_province']."',
			cp_name = '".$_POST['cp_name']."',
			cp_budget = '".$_POST['cp_budget']."',
			cp_owner = '".$_POST['cp_owner']."',
			cp_operator = '".$_POST['cp_operator']."',
			cp_scope = '".$_POST['cp_scope']."',
			cp_status = '".$_POST['cp_status']."',
			cp_stage = '".$_POST['cp_stage']."',
            cp_effective = '".$_POST['cp_effective']."',
			cp_step = '".$_POST['cp_step']."',
			cp_step_start = '".$_POST['cp_step_start']."',
			cp_step_stop = '".$_POST['cp_step_stop']."',
			cp_step_1 = '".$_POST['cp_step_1']."',
			cp_step_1_start = '".$_POST['cp_step_1_start']."', 
			cp_step_1_stop = '".$_POST['cp_step_1_stop']."',
			cp_step_2 = '".$_POST['cp_step_2']."',
			cp_step_2_start = '".$_POST['cp_step_2_start']."',
			cp_step_2_stop = '".$_POST['cp_step_2_stop']."',
			cp_consider = '".$_POST['cp_consider']."'
			WHERE cp_no = '".$_POST['cp_no']."'";
        in_up_delSql($sql);
        //echo $sql;
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','cp','".$_POST['cp_no']."')";
		in_up_delSql($sql);
		
		echo "แก้ไขสำเร็จ";
    }
	 elseif ($_POST['event']=='c_cp-v2'){
		 if($_POST['cp_step']==0){
			 $_POST['cp_step_1'] = 0;
			 $_POST['cp_step_2'] = 0;
		 }
        $sql = "INSERT INTO tb_cityplan(cp_type,cp_eec,cp_province,cp_name,cp_budget,cp_owner,cp_operator,cp_scope,cp_status,cp_stage,cp_effective,cp_step,cp_step_start,cp_step_stop,cp_step_1,cp_step_1_start,cp_step_1_stop,cp_step_2,cp_step_2_start,cp_step_2_stop,cp_consider,cp_petition) VALUES(
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
		'".$_POST['cp_step']."','".$_POST['cp_step_start']."','".$_POST['cp_step_stop']."',
		'".$_POST['cp_step_1']."','".$_POST['cp_step_1_start']."','".$_POST['cp_step_1_stop']."',
		'".$_POST['cp_step_2']."','".$_POST['cp_step_2_start']."','".$_POST['cp_step_2_stop']."',
		'".$_POST['cp_consider']."',
		'".$_POST['cp_petition']."'
		)";
        in_up_delSql($sql);
		
		$sql = "SELECT cp_no FROM tb_cityplan ORDER BY cp_no DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','cp','".$result[0]['cp_no']."')";
		in_up_delSql($sql);
		
		
		//echo $sql;
		echo "เพิ่มข้อมูลสำเร็จ";
    }
	elseif ($_POST['event']=='c_cp-v2x'){
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
		
		$sql = "SELECT cp_no FROM tb_cityplan ORDER BY cp_no DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','cp','".$result[0]['cp_no']."')";
		in_up_delSql($sql);
		
		
		//echo $sql;
		echo "เพิ่มข้อมูลสำเร็จ";
    }
	elseif($_POST['event']=='u_cp-v2'){
		$sql = "SELECT cp_step , cp_step_start , cp_step_stop ,cp_step_1 , cp_step_1_start , cp_step_1_stop , cp_step_2 , cp_step_2_start , cp_step_2_stop FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		/*
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
		*/
		if($_POST['cp_current_status']!='1.1' OR $_POST['cp_dflaw']==''){
			$_POST['cp_dflaw'] = "NULL";
		 }
		$sql = "UPDATE tb_cityplan SET
            cp_id = '".$_POST['cp_id']."',
			cp_type = '".$_POST['cp_type']."',
			cp_eec = '".$_POST['cp_eec']."',
			cp_province = '".$_POST['cp_province']."',
			cp_name = '".$_POST['cp_name']."',
			cp_budget = '".$_POST['cp_budget']."',
			cp_owner = '".$_POST['cp_owner']."',
			cp_operator = '".$_POST['cp_operator']."',
			cp_scope = '".$_POST['cp_scope']."',
			cp_status = '".$_POST['cp_status']."',
			cp_stage = '".$_POST['cp_stage']."',
            cp_effective = '".$_POST['cp_effective']."',
			cp_consider = '".$_POST['cp_consider']."',
			cp_date_notice = '".$_POST['cp_date_notice']."',
			cp_date_exp = '".$_POST['cp_date_exp']."',
			cp_current_status = '".$_POST['cp_current_status']."',
			cp_dflaw = ".$_POST['cp_dflaw']."
			
			WHERE cp_no = '".$_POST['cp_no']."'";
		//echo $sql;
        /*$sql = "UPDATE tb_cityplan SET
            cp_id = '".$_POST['cp_id']."',
			cp_type = '".$_POST['cp_type']."',
			cp_eec = '".$_POST['cp_eec']."',
			cp_province = '".$_POST['cp_province']."',
			cp_name = '".$_POST['cp_name']."',
			cp_budget = '".$_POST['cp_budget']."',
			cp_owner = '".$_POST['cp_owner']."',
			cp_operator = '".$_POST['cp_operator']."',
			cp_scope = '".$_POST['cp_scope']."',
			cp_status = '".$_POST['cp_status']."',
			cp_stage = '".$_POST['cp_stage']."',
            cp_effective = '".$_POST['cp_effective']."',
			cp_step = '".$_POST['cp_step']."',
			cp_step_start = '".$_POST['cp_step_start']."',
			cp_step_stop = '".$_POST['cp_step_stop']."',
			cp_step_1 = '".$_POST['cp_step_1']."',
			cp_step_1_start = '".$_POST['cp_step_1_start']."', 
			cp_step_1_stop = '".$_POST['cp_step_1_stop']."',
			cp_step_2 = '".$_POST['cp_step_2']."',
			cp_step_2_start = '".$_POST['cp_step_2_start']."',
			cp_step_2_stop = '".$_POST['cp_step_2_stop']."',
			cp_consider = '".$_POST['cp_consider']."',
			cp_petition = '".$_POST['cp_petition']."'
			WHERE cp_no = '".$_POST['cp_no']."'";
		*/
        in_up_delSql($sql);
       // echo $sql;
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','cp','".$_POST['cp_no']."')";
		in_up_delSql($sql);
		//echo '<script>botcast("edit","cp","demo-user-1")</script>';
		echo "แก้ไขสำเร็จ";
    }
	elseif($_POST['event']=='ru_step'){
		$sql = "SELECT * FROM ref_up_fst_step WHERE step_id = '".$_POST['step_id']."'";
		$result = selectSql($sql);	
		$json = array();
		foreach($result as $row){
		  $json = [
			'step_id' => $row['step_id'],
			'step_name' => $row['step_name'],
			'step_time' => $row['step_time'],
			'step_time1' => $row['step_time1'],
			'step_time2' => $row['step_time2'],
			'step_start' => $row['step_start'],
			'step_end' => $row['step_end'],
			'step_note' => $row['step_note']
			];
		}
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='u_step'){	
		if($_POST['step_start']=='' AND $_POST['step_end']!=''){
			$sql ="UPDATE ref_up_fst_step SET
				step_time = '".$_POST['step_time']."',
				step_time1 = '".$_POST['step_time1']."',
				step_time2 = '".$_POST['step_time2']."',
				step_start = NULL,
				step_end =  '".$_POST['step_end']."',
				step_note = '".$_POST['step_note']."'
				WHERE step_id = '".$_POST['step_id']."'";
		}
		elseif($_POST['step_start']!='' AND $_POST['step_end']==''){
			$sql ="UPDATE ref_up_fst_step SET
				step_time = '".$_POST['step_time']."',
				step_time1 = '".$_POST['step_time1']."',
				step_time2 = '".$_POST['step_time2']."',
				step_start = '".$_POST['step_start']."',
				step_end = NULL,
				step_note = '".$_POST['step_note']."'
				WHERE step_id = '".$_POST['step_id']."'";
		}
		elseif($_POST['step_start']=='' AND $_POST['step_end']==''){
			$sql ="UPDATE ref_up_fst_step SET
				step_time = '".$_POST['step_time']."',
				step_time1 = '".$_POST['step_time1']."',
				step_time2 = '".$_POST['step_time2']."',
				step_start = NULL,
				step_end = NULL,
				step_note = '".$_POST['step_note']."'
				WHERE step_id = '".$_POST['step_id']."'";
			
		}
		else{
			$sql ="UPDATE ref_up_fst_step SET
				step_time = '".$_POST['step_time']."',
				step_time1 = '".$_POST['step_time1']."',
				step_time2 = '".$_POST['step_time2']."',
				step_start = '".$_POST['step_start']."',
				step_end =  '".$_POST['step_end']."',
				step_note = '".$_POST['step_note']."'
				WHERE step_id = '".$_POST['step_id']."'";
		}
		in_up_delSql($sql);
		//echo $sql;
		echo "แก้ไขสำเร็จ";
		
	}
	elseif($_POST['event']=='ru_sub'){
		$sql = "SELECT * FROM ref_up_snd_step WHERE sub_id = '".$_POST['sub_id']."'";
		//echo $sql;
		$result = selectSql($sql);	
		$json = array();
		foreach($result as $row){
		  $json = [
			'sub_id' => $row['sub_id'],
			'sub_no' => $row['sub_no'],
			'sub_name' => $row['sub_name'],
			'sub_time' => $row['sub_time'],
			'sub_time1' => $row['sub_time1'],
			'sub_time2' => $row['sub_time2'],
			'sub_start' => $row['sub_start'],
			'sub_end' => $row['sub_end'],
			'sub_note' => $row['sub_note']
			];
		}
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='u_sub'){	
		if($_POST['sub_start']=='' AND $_POST['sub_end']!=''){
			$sql ="UPDATE ref_up_snd_step SET
				sub_time = '".$_POST['sub_time']."',
				sub_time1 = '".$_POST['sub_time1']."',
				sub_time2 = '".$_POST['sub_time2']."',
				sub_start = NULL,
				sub_end =  '".$_POST['sub_end']."',
				sub_note = '".$_POST['sub_note']."'
				WHERE sub_id = '".$_POST['sub_id']."'";
		}
		elseif($_POST['sub_start']!='' AND $_POST['sub_end']==''){
			$sql ="UPDATE ref_up_snd_step SET
				sub_time = '".$_POST['sub_time']."',
				sub_time1 = '".$_POST['sub_time1']."',
				sub_time2 = '".$_POST['sub_time2']."',
				sub_start = '".$_POST['sub_start']."',
				sub_end = NULL,
				sub_note = '".$_POST['sub_note']."'
				WHERE sub_id = '".$_POST['sub_id']."'";
		}
		elseif($_POST['sub_start']=='' AND $_POST['sub_end']==''){
			$sql ="UPDATE ref_up_snd_step SET
				sub_time = '".$_POST['sub_time']."',
				sub_time1 = '".$_POST['sub_time1']."',
				sub_time2 = '".$_POST['sub_time2']."',
				sub_start = NULL,
				sub_end = NULL,
				sub_note = '".$_POST['sub_note']."'
				WHERE sub_id = '".$_POST['sub_id']."'";
		}
		else{
			$sql ="UPDATE ref_up_snd_step SET
				sub_time = '".$_POST['sub_time']."',
				sub_time1 = '".$_POST['sub_time1']."',
				sub_time2 = '".$_POST['sub_time2']."',
				sub_start = '".$_POST['sub_start']."',
				sub_end =  '".$_POST['sub_end']."',
				sub_note = '".$_POST['sub_note']."'
				WHERE sub_id = '".$_POST['sub_id']."'";
		}
		in_up_delSql($sql);
		//echo $sql;
		echo "แก้ไขสำเร็จ";	
	}
	elseif($_POST['event']=='c_note'){
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_remark,note_uper) VALUES ('".$_POST['note_step']."','".$_POST['note_step_1']."','".$_POST['note_remark']."','".$_SESSION['intra_czid']."')";
		//echo $sql;
		in_up_delSql($sql);
		echo "เพิ่มข้อมูลสำเร็จ";
	}
	elseif($_POST['event']=='ru_note'){
		$sql = "SELECT * FROM tb_note WHERE note_id = '".$_POST['note_id']."' ";
		//echo $sql;
		$result = selectSql($sql);
		$json = array();
		foreach($result as $row){
			$json = [
				'note_id' => $row['note_id'], 
				'note_remark' => $row['note_remark']			
			];
		}
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);		
	}
	elseif($_POST['event']=='u_note'){
		$sql = "UPDATE tb_note SET 
				note_remark = '".$_POST['note_remark']."'
				WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		echo "แก้ไขสำเร็จ";
	}
	elseif($_POST['event']=='d_note'){
		$sql ="DELETE FROM tb_note WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		echo "ลบสำเร็จ";
	}
	elseif($_POST['event']=='r_step_1'){ 
		$sql ="SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['step_id']."' AND sub_no != 0";
		$result = selectSql($sql);
		?>
		<option value="0">ไม่ระบุขั้นตอน</option> <?php
		foreach($result as $row){?>
			<option value="<?php echo $row['sub_no'];?>"><?php echo 'ขั้นตอนที่ ',$row['sub_step'],'.',$row['sub_no'],' ',$row['sub_name'];?></option>									
	<?php } 
	}
	elseif($_POST['event']=='r_step_2'){ 
		$sql ="SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['step_id']."' AND trd_snd = '".$_POST['sub_id']."'";
		$result = selectSql($sql);
		$count_row = count($result);
		if($count_row > 0){
			?><option value="0">ไม่ระบุขั้นตอน</option> <?php
			foreach($result as $row){?>
				<option value="<?php echo $row['trd_step'];?>"><?php echo 'ขั้นตอนที่ ',$row['trd_fst'],'.',$row['trd_snd'],'.',$row['trd_step'],' ',$row['trd_name'];?></option>									
			<?php } ?>
			<script>
				$('#div-step-2').show();
			</script><?php
		}else{
			?>
			<option value="">ไม่มีขั้นตอน</option> 
			<script>
				$('#div-step-2').hide();
				$('#cp_step_2').val(0);
			</script>
			<?php
		}
		//echo $sql;
	}
	elseif($_POST['event']=="ru_step+sub"){
		$sql = "SELECT * FROM ref_up_fst_step , ref_up_snd_step WHERE step_id  = sub_step AND step_id = '".$_POST['step_id']."' AND sub_no = '".$_POST['sub_no']."'";
		$result = selectSql($sql);
		$json = array();
		foreach($result as $row){
			$json = [
				'step_name' => '<b>ขั้นตอนหลักที่ '.$row['step_id'].'</b> '.$row['step_name'], 
				'sub_name' => '<b>ขั้นตอนรองที่ '.$row['sub_no'].'</b> '.$row['sub_name']			
			];
		}
		//echo $sql;
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}elseif($_POST['event']=="c_note(his)"){
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_remark,note_uper,note_cp) VALUES ('".$_POST['note_step']."','".$_POST['note_step_1']."','".$_POST['note_remark']."','".$_SESSION['intra_czid']."', '".$_POST['note_cp']."')";
		in_up_delSql($sql);
		
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_cp = '".$_POST['note_cp']."' AND note_status != 0";
		$result = selectSql($sql);
		
		$sql = "UPDATE tb_cityplan SET cp_note = '".$result[0]['COUNT(*)']."' WHERE cp_no ='".$_POST['note_cp']."' ";
		in_up_delSql($sql);
		
		
		$sql = "SELECT note_id FROM tb_note ORDER BY note_id DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','note','".$result[0]['note_id']."')";
		in_up_delSql($sql);
		
		echo "เพิ่มหมายเหตุสำเร็จ";
	}
	elseif($_POST['event']=="c_note+u_cp"){
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_remark,note_uper,note_cp) VALUES ('".$_POST['note_step']."','".$_POST['note_step_1']."','".$_POST['note_remark']."','".$_SESSION['intra_czid']."', '".$_POST['note_cp']."')";
		in_up_delSql($sql);
				
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_cp = '".$_POST['note_cp']."' AND note_status != 0";
		$result = selectSql($sql);
		
		$sql = "UPDATE tb_cityplan SET cp_note = '".$result[0]['COUNT(*)']."' WHERE cp_no ='".$_POST['note_cp']."' ";
		in_up_delSql($sql);
		
		$sql = "SELECT note_id FROM tb_note ORDER BY note_id DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','note','".$result[0]['note_id']."')";
		in_up_delSql($sql);
		
		echo "เพิ่มหมายเหตุสำเร็จ2";
	}
	elseif($_POST['event']=='ru_note2'){
		$sql ="SELECT * FROM tb_note,tb_cityplan ,ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_note = note_id AND cp_no = '".$_POST['cp_no']."'";
		//$sql = "SELECT * FROM tb_note,tb_cityplan WHERE cp_note = note_id AND cp_no = '".$_POST['cp_no']."'";
		//echo $sql;
		$result = selectSql($sql);
		$json = array();
		foreach($result as $row){
			$json = [
				'step_name' => '<b>ขั้นตอนหลักที่ '.$row['step_id'].'</b> '.$row['step_name'], 
				'sub_name' => '<b>ขั้นตอนรองที่ '.$row['sub_no'].'</b> '.$row['sub_name'],
				'note_remark' => $row['note_remark'],
				'cp_no' => $row['cp_no'],
				'note_id' => $row['note_id']
			];
		}
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='u_note+u_cp'){
		$sql = "UPDATE tb_note SET note_remark = '".$_POST['note_remark']."' , note_uper = '".$_SESSION['intra_czid']."' WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','note','".$_POST['note_id']."')";
		in_up_delSql($sql);
		echo "แก้ไขสำเร็จ";
	}
	elseif($_POST['event']=='d_note+u_cp'){
		$sql ="DELETE FROM tb_note WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		//echo $sql;
		$sql = "UPDATE tb_cityplan SET cp_note = 0 WHERE cp_no = '".$_POST['cp_no']."'";
		in_up_delSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','del','note','".$_POST['note_id']."')";
		in_up_delSql($sql);
		//echo $sql;
		 echo "ลบหมายเหตุสำเร็จ";
	}
	elseif($_POST['event']=='ru_stepnote'){
		//$sql ="SELECT * FROM tb_note,tb_cityplan ,ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_note = note_id AND cp_no = '".$_POST['cp_no']."'";
		$sql ="SELECT * FROM tb_note,ref_up_fst_step,ref_up_snd_step WHERE note_id = '".$_POST['note_id']."' AND step_id = note_step AND sub_no = note_step1 AND step_id = sub_step";
		//$sql = "SELECT * FROM tb_note WHERE note_id = '".$_POST['note_id']."'";
		//echo $sql;
		$result = selectSql($sql);
		$json = array();
		foreach($result as $row){
			$json = [
				'step_name' => '<b>ขั้นตอนหลักที่ '.$row['step_id'].'</b> '.$row['step_name'], 
				'sub_name' => '<b>ขั้นตอนรองที่ '.$row['sub_no'].'</b> '.$row['sub_name'],
				'note_remark' => $row['note_remark'],
				'note_cp' => $row['note_cp'],
				'note_id' => $row['note_id']			
			];
		}
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=="c_stepnote"){
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_remark,note_uper,note_cp) VALUES ('".$_POST['note_step']."','".$_POST['note_step_1']."','".$_POST['note_remark']."','".$_SESSION['intra_czid']."', '".$_POST['note_cp']."')";
		//echo $sql;
		in_up_delSql($sql);		
		echo "เพิ่มหมายเหตุสำเร็จ";
	}
	elseif($_POST['event']=='d_stepnote'){
		//$sql ="DELETE FROM tb_note WHERE note_id = '".$_POST['note_id']."'";
		$sql = "UPDATE tb_note SET note_uper = '".$_SESSION['intra_czid']."', note_status = 0 WHERE note_id  = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_cp = '".$_POST['cp_no']."' AND note_status != 0";
		$result = selectSql($sql);
		
		$sql = "UPDATE tb_cityplan SET cp_note = '".$result[0]['COUNT(*)']."' WHERE cp_no ='".$_POST['cp_no']."' ";
		in_up_delSql($sql);
		
		
		
		//echo $sql;
		
		 echo "ลบหมายเหตุสำเร็จ";
	}
	elseif($_POST['event']=='ru_mainnote'){
		$sql = "SELECT note_id, note_remark FROM tb_note WHERE note_step = '".$_POST['step_id']."' AND note_step1 = '".$_POST['sub_no']."' GROUP BY note_remark";
		$result = selectSql($sql);
		$x = 0;
		foreach($result as $row){ 
		$x++; ?>
		<div class="form-group">
			<label for="<?php echo 'note_'.$x; ?>"><?php echo 'หมายเหตุที่ '.$x;?></label>
			<span style="position:absolute;margin-left:8px" class="trash" onclick="Delnote(<?php echo $row['note_id']?>)"><a><i class="fa fa-trash"></i></a></span>
			<input id="<?php echo 'note_'.$x; ?>"value="<?php echo $row['note_remark']?>" class="form-control">
		</div>
		<?php } 
		$sql = "SELECT step_name , sub_name FROM ref_up_fst_step , ref_up_snd_step WHERE step_id = sub_step AND step_id = '".$_POST['step_id']."' AND sub_no = '".$_POST['sub_no']."'";
		$result = selectSql($sql);
		if($_POST["step_id"]!=0){ 
			$step = '<b>ขั้นตอนหลักที่ '.$_POST['step_id'].' : </b>'.$result[0]['step_name'];
			$step1 = '<b>ขั้นตอนรองที่  '.$_POST['step_id'].'.'.$_POST['sub_no'].' : </b>'.$result[0]['sub_name'];
			?>
			<script>
				$('#note_step').html('<?php echo $step; ?>');
				$('#note_step_1').html('<?php echo $step1; ?>');
				$('#note_step_1').show();
			</script>
			<?php
		}
		else{
			$step = '<b>ยังไม่ได้กำหนดขั้นตอน</b>';
			?>
			<script>
				$('#note_step').html('<?php echo $step; ?>');
				$('#note_step_1').hide();
			</script>
			<?php
		}
	}
	elseif($_POST['event']=='ru-c_mainnote'){
		?>
		<div class="form-group">
			<label for="note_remark">หมายเหตุ</label>
			<textarea id="note_remark" class="form-control" value="" placeholder="ระบุหมายเหตุ............."></textarea>
		</div>
		<?php 
		$sql = "SELECT step_name , sub_name FROM ref_up_fst_step , ref_up_snd_step WHERE step_id = sub_step AND step_id = '".$_POST['step_id']."' AND sub_no = '".$_POST['sub_no']."'";
		$result = selectSql($sql);	
		if($_POST["step_id"]!=0){ 
			$step = '<b>ขั้นตอนหลักที่ '.$_POST['step_id'].' : </b>'.$result[0]['step_name'];
			$step1 = '<b>ขั้นตอนรองที่  '.$_POST['step_id'].'.'.$_POST['sub_no'].' : </b>'.$result[0]['sub_name'];
			?>
			<script>
				$('#note_step').html('<?php echo $step; ?>');
				$('#note_step_1').html('<?php echo $step1; ?>');
				$('#note_step_1').show();
			</script>
			<?php
		}
		else{
			$step = '<b>ยังไม่ได้กำหนดขั้นตอน</b>';
			?>
			<script>
				$('#note_step').html('<?php echo $step; ?>');
				$('#note_step_1').hide();
			</script>
			<?php
		}
	}
	elseif($_POST['event']=='rd_note'){
		$sql = "SELECT note_remark FROM tb_note WHERE note_id = '".$_POST['note_id']."'";
		$result = selectSql($sql);
		echo $result[0]['note_remark'];
	}
	elseif($_POST['event']=='c_mainnote'){
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_remark,note_uper,note_cp) VALUES('".$_POST['note_step']."','".$_POST['note_step_1']."','".$_POST['note_remark']."','".$_SESSION['intra_czid']."','".$_POST['note_cp']."')";
		//$result = in_up_delSql($sql);
		echo $sql;
		echo "เพิ่มหมายเหตุสำเร็จ";
	}
	elseif($_POST['event']=='ru-c_stepnote'){
		?>
		
			<label for="note_remark">หมายเหตุ</label>
			<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
		
		<?php
		$sql = "SELECT step_name , sub_name FROM ref_up_fst_step , ref_up_snd_step WHERE step_id = sub_step AND step_id = '".$_POST['step_id']."' AND sub_no = '".$_POST['sub_no']."'";
		$result = selectSql($sql);	
		if($_POST["step_id"]!=0){ 
			$step = '<b>ขั้นตอนหลักที่ '.$_POST['step_id'].' : </b>'.$result[0]['step_name'];
			$step1 = '<b>ขั้นตอนรองที่  '.$_POST['step_id'].'.'.$_POST['sub_no'].' : </b>'.$result[0]['sub_name'];
			?>
			<script>
				$('#note_step').html('<?php echo $step; ?>');
				$('#note_step_1').html('<?php echo $step1; ?>');
				$('#note_step_1').show();
			</script>
			<?php
		}
	}
	elseif($_POST['event']=='ru_stepnotex'){
		$sql = "SELECT * FROM tb_note WHERE note_step = '".$_POST['step_id']."' AND note_step1 = '".$_POST['sub_no']."' AND note_cp = '".$_POST['cp_no']."' AND note_status != 0 ORDER BY note_id DESC";
		//echo $sql;
		$result = selectSql($sql);
		$x = 0;
		foreach($result as $row){
		$x++; 
		$years = substr($row['note_timestamp'],0,4)+543;
		$months = substr($row['note_timestamp'],5,2);
		$days = substr($row['note_timestamp'],8,2);
		//$daten = '<b>วันที่เพิ่มหมายเหตุ : </b>'.$row['note_timestamp'];
		if($months==1){$months = "ม.ค.";}
		elseif($months==2){$months = "ก.พ.";}
		elseif($months==3){	$months = "มี.ค.";	}
		elseif($months==4){	$months = "เม.ย.";	}
		elseif($months==5){	$months = "พ.ค.";	}
		elseif($months==6){	$months = "มิ.ย.";	}
		elseif($months==7){	$months = "ก.ค.";	}
		elseif($months==8){	$months = "ส.ค.";	}
		elseif($months==9){	$months = "ก.ย.";	}
		elseif($months==10){$months = "ต.ค.";	}
		elseif($months==11){$months = "พ.ย.";}
		else{$months = "ธ.ค.";}
		$times = substr($row['note_timestamp'],11,8);
		//$daten = "<b>วันที่เพิ่มหมายเหตุ : </b>".$days." ".$months." ".$years.' <b> เวลา : </b>'.$times." น.";
		$daten = "<b>วันที่เพิ่มหมายเหตุ : </b>".$days." ".$months." ".$years;
		$timen = '<b> เวลา : </b>'.$times." น.";
		if($x>1){
			echo '<hr style="border-color:#989899">';
			
		}
		?>
		<div class="form-group">
			<div id="<?php echo 'date_note_'.$x; ?>" class="pull-left"></div>
			<div id="<?php echo 'date_time_'.$x; ?>" class="pull-right"></div><br>
			<label for="<?php echo 'note_'.$x; ?>"><?php //echo 'หมายเหตุที่ '.$x;?></label>
			<span title="ลบหมายเหตุ" style="margin-right:8px" class="trash pull-right" onclick="Delnote(<?php echo $row['note_id'];?>,<?php echo $_POST['cp_no'];?>)"><a><i class="fa fa-trash"></i></a></span>
			<span title="แก้ไขหมายเหตุ" style="margin-right:8px" class="gear pull-right" onclick="Editnote(<?php echo $x;?>,<?php echo $row['note_id'];?>,<?php echo $_POST['cp_no'];?>)"><a><i class="fa fa-gear"></i></a></span>
			<div class="div_note" id="<?php echo 'div_note_'.$x; ?>"><?php echo $row['note_remark']?></div>
			<input class="form-control input_note" id="<?php echo 'note_'.$x; ?>"value="<?php echo $row['note_remark']?>">
		</div>
		<script>
			$('<?php echo "#note_".$x; ?>').hide();
			$('<?php echo "#date_note_".$x; ?>').html('<?php echo $daten; ?>');
			$('<?php echo "#date_time_".$x; ?>').html('<?php echo $timen; ?>');
			$('#note_step_hidden').val('<?php echo $_POST["step_id"]; ?>');
			$('#note_step_1_hidden').val('<?php echo $_POST["sub_no"];?>');
			$('#note_cp').val('<?php echo $_POST["cp_no"];?>');
		</script>
		<?php }
		
		$sql = "SELECT step_name , sub_name FROM ref_up_fst_step , ref_up_snd_step WHERE step_id = sub_step AND step_id = '".$_POST['step_id']."' AND sub_no = '".$_POST['sub_no']."'";
		$result = selectSql($sql);	
		if($_POST["step_id"]!=0){ 
			$step = '<b>ขั้นตอนหลักที่ '.$_POST['step_id'].' : </b>'.$result[0]['step_name'];
			$step1 = '<b>ขั้นตอนรองที่  '.$_POST['step_id'].'.'.$_POST['sub_no'].' : </b>'.$result[0]['sub_name'];
			?>
			<script>
				$('#note_step').html('<?php echo $step; ?>');
				$('#note_step_1').html('<?php echo $step1; ?>');
				$('#note_step_1').show();
			</script>
			<?php
		}
		else{
			$step = '<b>ยังไม่ได้กำหนดขั้นตอน</b>';
			?>
			<script>
				$('#note_step').html('<?php echo $step; ?>');
				$('#note_step_1').hide();
			</script>
			<?php
		}
		
	}
	elseif($_POST['event']=="ru_selectnote"){
		
		?>
		<label for="note_remark">หมายเหตุ</label>
		<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
		<script>
			$('#note_id').val(<?php echo $_POST['note_id'];?>);
		</script>
		<?php 
	}
	elseif($_POST['event']=='c_user'){
		$sql = "INSERT INTO tb_user(user_id,user_fname,user_lname,user_affiliation,user_pmacess,user_status) VALUES('".$_POST['user_id']."','".$_POST['user_fname']."','".$_POST['user_lname']."','".$_POST['user_affiliation']."','".$_POST['user_pmaccess']."','".$_POST['user_status']."')";
		in_up_delSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','user','".$_POST['user_id']."')";
		in_up_delSql($sql);
		//echo $sql;
		echo "เพิ่มข้อมูลสำเร็จ";
	}
	elseif($_POST['event']=='d_user'){
		$sql = "DELETE FROM tb_user WHERE user_id = '".$_POST['user_id']."'";
		//echo $sql;
		in_up_delSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','del','user','".$_POST['user_id']."')";
		in_up_delSql($sql);
		echo "ลบข้อมูลสำเร็จ";
	}
	elseif($_POST['event']=='ru_user'){
		$sql = "SELECT * FROM tb_user WHERE user_id = '".$_POST['user_id']."'";
		$result  = selectSql($sql);
		
		$user_id = $result[0]['user_id'];
		$user_fname = $result[0]['user_fname'];
		$user_lname = $result[0]['user_lname'];
		$user_affiliation = $result[0]['user_affiliation'];
		echo $sql;
		?>
		<script>
			$('#user_id').val(<?php echo $user_id; ?>);
			$('#user_id').attr('readonly',true);
			$('#user_fname').val('<?php echo $user_fname; ?>');
			$('#user_lname').val('<?php echo $user_lname; ?>');
			$('#user_affiliation').val('<?php echo $user_affiliation; ?>');
		</script>
		<?php
		
	}
	elseif($_POST['event']=='u_user'){
		$sql = "UPDATE tb_user SET user_fname = '".$_POST['user_fname']."' , user_lname = '".$_POST['user_lname']."' , user_affiliation =  '".$_POST['user_affiliation']."' WHERE user_id = '".$_POST['user_id']."'";
		in_up_delSql($sql);
		echo "แก้ไขสำเร็จ";
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','user','".$_POST['user_id']."')";
		in_up_delSql($sql);
		
	}
	elseif($_POST['event']=='fst_date'){
		$_POST['cp_step_start'] = str_replace(' ','',$_POST['cp_step_start']);
		if($_POST['cp_step_start']!=''){
			if($_POST['cp_step']!=6){
				$sql = "SELECT * FROM ref_up_fst_step WHERE step_id = '".$_POST['cp_step']."'";
				$result = selectSql($sql);		
				$start = (substr($_POST['cp_step_start'],0,4)-543).substr($_POST['cp_step_start'],4);
				$days = $result[0]['step_time']*7-1; 
				$date = date_create($start);
				date_add($date, date_interval_create_from_date_string($days . " days"));
				echo (date_format($date, "Y")+543).date_format($date,'-m-d');
				
			}
			elseif($_POST['cp_step']==6){
				$sql = "SELECT * FROM ref_up_fst_step WHERE step_id = '".$_POST['cp_step']."'";
				$result = selectSql($sql);		
				$start = (substr($_POST['cp_step_start'],0,4)-543).substr($_POST['cp_step_start'],4);
				if($_POST['cp_petition']==1){
					$days = $result[0]['step_time2']*7-1; 
				}
				else{
					$days = $result[0]['step_time1']*7-1; 
				}
				$date = date_create($start);
				date_add($date, date_interval_create_from_date_string($days . " days"));
				echo (date_format($date, "Y")+543).date_format($date,'-m-d');
			}
		}else{
			echo "";
		}
	}
	elseif($_POST['event']=='snd_date'){
		$_POST['cp_step_1_start'] = str_replace(' ','',$_POST['cp_step_1_start']);
		if($_POST['cp_step_1_start']!=''){
			if($_POST['cp_step']!=6){
				$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['cp_step']."' AND sub_no = '".$_POST['cp_step_1']."'";
				$result = selectSql($sql);
				$start = (substr($_POST['cp_step_1_start'],0,4)-543).substr($_POST['cp_step_1_start'],4);
				$days = $result[0]['sub_time']*7-1; 
				$date = date_create($start);				
				date_add($date, date_interval_create_from_date_string($days . " days"));
				echo (date_format($date, "Y")+543).date_format($date,'-m-d');;

			}
			elseif($_POST['cp_step']==6){
				$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['cp_step']."' AND sub_no = '".$_POST['cp_step_1']."'";
				$result = selectSql($sql);		
				$start = (substr($_POST['cp_step_1_start'],0,4)-543).substr($_POST['cp_step_1_start'],4);
				if($_POST['cp_petition']==1){
					$days = $result[0]['sub_time2']*7-1; 
				}
				else{
					$days = $result[0]['sub_time1']*7-1; 
				}
				$date = date_create($start);
				date_add($date, date_interval_create_from_date_string($days . " days"));
				echo (date_format($date, "Y")+543).date_format($date,'-m-d');
			}
		}else{
			echo "";
		}
	}
	elseif($_POST['event']=='trd_date'){
		$_POST['cp_step_2_start'] = str_replace(' ','',$_POST['cp_step_2_start']);
		if($_POST['cp_step_2_start']!=''){
			$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['cp_step']."' AND trd_snd = '".$_POST['cp_step_1']."' AND trd_step = '".$_POST['cp_step_2']."'";
			$result = selectSql($sql);
			$start = (substr($_POST['cp_step_2_start'],0,4)-543).substr($_POST['cp_step_2_start'],4);
			$days = $result[0]['trd_time']*7-1; 
			$date = date_create($start);
			date_add($date, date_interval_create_from_date_string($days . " days"));
			echo (date_format($date, "Y")+543).date_format($date,'-m-d');
		}else{
			echo "";
		}
	}
	elseif($_POST['event']=="r_csd_fst"){
		if($_POST['step_id']==0){
			echo '<option value="0" selected>ไม่มีขั้นตอน</option> ';
			?> 
			<script>
				$('#csd_petition').val(0);
				$('#csd_start_plan').val('');
				$('#csd_stop_plan').val('');
				$('#csd_start').val('');
				$('#csd_stop').val('');
				$('#div_csd_date_plan').hide();
				$('#div_csd_date').hide();
			</script>
			<?php
		}
		else{
			$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['step_id']."' AND sub_no != 0 ";
			$result = selectSql($sql);
			echo '<option value="0" selected>ไม่ระบุขั้นตอน</option> ';
			foreach($result as $row){ ?>
				<option value="<?php echo $row['sub_no'];?>"><?php echo 'ขั้นตอนที่ '.$row['sub_step'].'.'.$row['sub_no'].' : '.$row['sub_name']; ?> </option> <?php  
			}
			?> 
			<script>
				$('#div_csd_date_plan').show();
				$('#div_csd_date').show();
			</script>
			<?php			
		}	
	}
	elseif($_POST['event']=="r_csd_snd"){
		if($_POST['sub_no']>0){			
			$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['step_id']."' AND trd_snd = '".$_POST['sub_no']."'";
			$result = selectSql($sql);
			$trd_check = count($result);
			if($trd_check > 0 ){
				echo '<option value="0" selected>ไม่ระบุขั้นตอน</option> ';
				foreach($result as $row){ ?>
					<option value="<?php echo $row['trd_step'];?>"><?php echo 'ขั้นตอนที่ '.$row['trd_fst'].'.'.$row['trd_snd'].'.'.$row['trd_step'].' : '.$row['trd_name']; ?> </option> <?php  
				}
				?><script>$('#div_csd_step_trd').show();</script><?php 
			}
			else{
				echo '<option value="0" selected>ไม่มีขั้นตอน</option>';
				?><script>$('#div_csd_step_trd').hide();</script><?php 
			}
		}
		else{
			echo '<option value="0" selected>ไม่มีขั้นตอน</option>';
			?><script>$('#div_csd_step_trd').hide();</script><?php 
		}	
	}
	elseif($_POST['event']=='c_csd'){
		/*if($_POST['csd_step_trd']=='' OR $_POST['csd_step_trd']==NULL){ 
			$_POST['csd_step_trd'] = 0; 
		}*/
		$sql = "INSERT INTO ref_cityplan_step_detail(csd_cp, csd_step_fst, csd_step_snd, csd_step_trd, csd_plan_fst_start, csd_plan_fst_stop, csd_plan_snd_start, csd_plan_snd_stop, csd_plan_trd_start, csd_plan_trd_stop, csd_start, csd_stop, csd_start_2, csd_stop_2, csd_petition) VALUE(
		'".$_POST['csd_cp']."' , 
		'".$_POST['csd_step_fst']."' ,
		'".$_POST['csd_step_snd']."' ,
		'".$_POST['csd_step_trd']."' ,		
		'".$_POST['csd_plan_fst_start']."' ,
		'".$_POST['csd_plan_fst_stop']."' ,
		'".$_POST['csd_plan_snd_start']."' ,
		'".$_POST['csd_plan_snd_stop']."' ,		
		'".$_POST['csd_plan_trd_start']."' ,
		'".$_POST['csd_plan_trd_stop']."' ,
		'".$_POST['csd_start']."' ,
		'".$_POST['csd_stop']."',
		'".$_POST['csd_start_2']."' ,
		'".$_POST['csd_stop_2']."',
		'".$_POST['csd_petition']."')";
		//echo $sql;
		in_up_delSql($sql);
		echo "เพิ่มขั้นตอนสำเร็จ";
		
		$sql = "SELECT csd_seq FROM ref_cityplan_step_detail ORDER BY csd_seq DESC LIMIT 1";
		$result = selectSql($sql);
		
		$sql = "UPDATE tb_cityplan SET cp_csd = '".$result[0]['csd_seq']."' WHERE cp_no = '".$_POST['csd_cp']."'";
		in_up_delSql($sql);
		//echo $sql ;
		
		$sql = "INSERT INTO tb_log(log_user, log_action, log_type, log_target) VALUE('".$_SESSION['intra_czid']."', 'add', 'csd','".$result[0]['csd_seq']."')";
		//in_up_delSql($sql);
	}
	elseif($_POST['event']=='d_m35a'){
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET csd35_status = 0 WHERE csd35_seq = '".$_POST['csd35_seq']."'";
		in_up_delSql($sql);
		
		logs("del","csd35",$_POST['csd35_seq']);
		echo 'ลบขั้นตอนสำเร็จ';
	}
	elseif($_POST['event']=='re_m35a'){
		$sql = "UPDATE tb_cityplan35 SET cp35_csd = '".$_POST['cp35_csd']."' WHERE cp35_seq = '".$_POST['cp35_seq']."'";
		in_up_delSql($sql);
		
		logs("edit","cp35",$_POST['cp35_seq']);
		echo "ดำเนินการเสร็จสิ้น";
	}
	elseif($_POST['event']=='re_csd'){
		//$sql = "SELECT csd_step_fst ,csd_step_snd ,csd_step_trd FROM ref_cityplan_step_detail WHERE csd_seq = '".$_POST['csd_seq']."'";
		
		$sql = "UPDATE tb_cityplan SET cp_csd = '".$_POST['csd_seq']."' WHERE cp_no = '".$_POST['cp_no']."'";
		in_up_delSql($sql);
		
		//echo $sql;
		$sql = "INSERT INTO tb_log(log_user, log_action, log_type, log_target) VALUE('".$_SESSION['intra_czid']."', 'edit', 'cp','".$_POST['cp_no']."')";
		in_up_delSql($sql);
		echo "ดำเนินการเสร็จสิ้น";
	}
	elseif($_POST['event']=='d_csd'){
		//$sql = "DELETE FROM ref_cityplan_step_detail WHERE csd_seq =  '".$_POST['csd_seq']."'";
		$sql = "UPDATE ref_cityplan_step_detail SET csd_status = 0 WHERE csd_seq = '".$_POST['csd_seq']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		
		$sql = "INSERT INTO tb_log(log_user, log_action, log_type, log_target) VALUE('".$_SESSION['intra_czid']."', 'del', 'csd','".$_POST['csd_seq']."')";
		in_up_delSql($sql);
		echo 'ลบขั้นตอนสำเร็จ';
		
	}
	elseif($_POST['event']=='ru_csd'){
		$sql = "SELECT * FROM ref_cityplan_step_detail WHERE csd_seq = '".$_POST['csd_seq']."'";
		$result = selectSql($sql);
		$json = array();
		foreach($result as $row){
			$json = [
				'csd_seq' => $row['csd_seq'],
				'csd_step_fst' => $row['csd_step_fst'],
				'csd_step_snd' => $row['csd_step_snd'],
				'csd_step_trd' => $row['csd_step_trd'],
				'csd_start' => $row['csd_start'],
				'csd_stop' => $row['csd_stop'],
				'csd_start_2' => $row['csd_start_2'],
				'csd_stop_2' => $row['csd_stop_2'],
				'csd_plan_start' => $row['csd_plan_start'],
				'csd_plan_stop' => $row['csd_plan_stop'],
				
				'csd_plan_fst_start' => $row['csd_plan_fst_start'],
				'csd_plan_fst_stop' => $row['csd_plan_fst_stop'],
				
				'csd_plan_snd_start' => $row['csd_plan_snd_start'],
				'csd_plan_snd_stop' => $row['csd_plan_snd_stop'],
				
				'csd_plan_trd_start' => $row['csd_plan_trd_start'],
				'csd_plan_trd_stop' => $row['csd_plan_trd_stop'],
				
				'csd_petition' => $row['csd_petition']
			];
		}
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='u_csd'){
		/*$sql = "UPDATE ref_cityplan_step_detail SET
			csd_step_fst = '".$_POST['csd_step_fst']."',
			csd_step_snd = '".$_POST['csd_step_snd']."',
			csd_step_trd = '".$_POST['csd_step_trd']."',
			csd_plan_start = '".$_POST['csd_plan_start']."',
			csd_plan_stop = '".$_POST['csd_plan_stop']."',
			csd_start = '".$_POST['csd_start']."',
			csd_stop = '".$_POST['csd_stop']."',
			csd_petition = '".$_POST['csd_petition']."'
			WHERE csd_seq = '".$_POST['csd_seq']."'
		";*/
		$sql = "UPDATE ref_cityplan_step_detail SET
			csd_step_fst = '".$_POST['csd_step_fst']."',
			csd_step_snd = '".$_POST['csd_step_snd']."',
			csd_step_trd = '".$_POST['csd_step_trd']."',
			csd_plan_fst_start = '".$_POST['csd_plan_fst_start']."',
			csd_plan_fst_stop = '".$_POST['csd_plan_fst_stop']."',
			csd_plan_snd_start = '".$_POST['csd_plan_snd_start']."',
			csd_plan_snd_stop = '".$_POST['csd_plan_snd_stop']."',
			csd_plan_trd_start = '".$_POST['csd_plan_trd_start']."',
			csd_plan_trd_stop = '".$_POST['csd_plan_trd_stop']."',
			csd_start = '".$_POST['csd_start']."',
			csd_stop = '".$_POST['csd_stop']."',
			csd_start_2 = '".$_POST['csd_start_2']."',
			csd_stop_2 = '".$_POST['csd_stop_2']."',
			csd_petition = '".$_POST['csd_petition']."'	
			WHERE csd_seq = '".$_POST['csd_seq']."'
		";
		
		in_up_delSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user, log_action, log_type, log_target) VALUE('".$_SESSION['intra_czid']."', 'edit', 'csd','".$_POST['csd_seq']."')";
		in_up_delSql($sql);
		
		if($_POST['csd_step_fst']!=0){
			$sql = "UPDATE tb_cityplan SET 
			cp_step_start = '".$_POST['csd_plan_fst_start']."' , cp_step_stop = '".$_POST['csd_plan_fst_stop']."' , 
			cp_step_1_start = '".$_POST['csd_plan_snd_start']."' , cp_step_1_stop = '".$_POST['csd_plan_snd_stop']."',
			cp_step_2_start = '".$_POST['csd_plan_trd_start']."' , cp_step_2_stop = '".$_POST['csd_plan_trd_stop']."'
			WHERE cp_csd = '".$_POST['csd_seq']."' AND cp_no = '".$_POST['csd_cp']."'";
			/*if($_POST['csd_step_snd']!=0){
				if($_POST['csd_step_trd']!=0){
					$sql = "UPDATE tb_cityplan SET cp_step_2_start = '".$_POST['csd_plan_start']."' , cp_step_2_stop  = '".$_POST['csd_plan_stop']."' WHERE cp_csd = '".$_POST['csd_seq']."' AND cp_no = '".$_POST['csd_cp']."'";
				}else{
					$sql = "UPDATE tb_cityplan SET cp_step_1_start = '".$_POST['csd_plan_start']."' , cp_step_1_stop  = '".$_POST['csd_plan_stop']."' WHERE cp_csd = '".$_POST['csd_seq']."' AND cp_no = '".$_POST['csd_cp']."'";
				}
			}else{
				$sql = "UPDATE tb_cityplan SET cp_step_start = '".$_POST['csd_plan_start']."' , cp_step_stop  = '".$_POST['csd_plan_stop']."' WHERE cp_csd = '".$_POST['csd_seq']."' AND cp_no = '".$_POST['csd_cp']."'";
			}*/
			in_up_delSql($sql);
		}
		//echo $sql;
		echo "แก้ไข้ขั้นตอนสำเร็จ";
		//echo $sql;
	}
	elseif($_POST['event']=='csd_plan_auto_date'){
		if($_POST['csd_step_fst']!=0 AND $_POST['csd_step_snd']==0){
			$sql = "SELECT * FROM ref_up_fst_step WHERE step_id = '".$_POST['csd_step_fst']."'";
			$result = selectSql($sql);		
			$start = $_POST['csd_plan_start'];			
			if($_POST['csd_step_fst']==6){
				if($_POST['csd_petition']==0){
					$days = $result[0]['step_time1']*7-1;
				}else{
					$days = $result[0]['step_time2']*7-1;
				}
			}else{
				$days = $result[0]['step_time']*7-1;
			}
			$date = date_create($start);
			date_sub($date,date_interval_create_from_date_string("543 year"));
			date_add($date, date_interval_create_from_date_string($days . " days"));
			date_sub($date,date_interval_create_from_date_string("-543 year"));
			echo date_format($date, "Y-m-d");		
			//echo date_format($date, "Y-m-d");
		}
		elseif($_POST['csd_step_fst']!=0 AND $_POST['csd_step_snd']!=0 AND $_POST['csd_step_trd']==0){
			$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['csd_step_fst']."' AND sub_no = '".$_POST['csd_step_snd']."'";
			$result = selectSql($sql);		
			$start = $_POST['csd_plan_start'];			
			if($_POST['csd_step_fst']==6){
				if($_POST['csd_petition']==0){
					$days = $result[0]['sub_time1']*7-1;
				}else{
					$days = $result[0]['sub_time2']*7-1;
				}
			}else{	
				$days = $result[0]['sub_time']*7-1; 
			}
			$date = date_create($start);
			date_sub($date,date_interval_create_from_date_string("543 year"));
			date_add($date, date_interval_create_from_date_string($days . " days"));
			date_sub($date,date_interval_create_from_date_string("-543 year"));
			echo date_format($date, "Y-m-d");
			//echo "fst != 0 AND snd != 0 AND trd = 0";
			//echo $sql;
		}
		elseif($_POST['csd_step_fst']!=0 AND $_POST['csd_step_snd']!=0 AND $_POST['csd_step_trd']!=0){
			$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['csd_step_fst']."' AND trd_snd = '".$_POST['csd_step_snd']."' AND trd_step = '".$_POST['csd_step_trd']."'";
			$result = selectSql($sql);		
			$start = $_POST['csd_plan_start'];
			$days = $result[0]['trd_time']*7-1; 
			$date = date_create($start);
			date_sub($date,date_interval_create_from_date_string("543 year"));
			date_add($date, date_interval_create_from_date_string($days . " days"));
			date_sub($date,date_interval_create_from_date_string("-543 year"));
			echo date_format($date, "Y-m-d");
			//echo "fst != 0 AND snd != 0 AND trd != 0";
			//echo $days;
			//echo $sql;
		}elseif($_POST['csd_step_fst']==0){
			echo "";
		}
		//echo $sql ;
	}
	elseif($_POST['event']=='ru-c_csd_note'){ ?>
		<div class="form-group">
			<label for="select-note-remark">หมวดหมู่หมายเหตุ</label>
			<select  id="select-note-remark" class="form-control" onchange="changeMyhead()">
				<option value="select-myhead">เลือกหมวดหมู่หมายเหตุ</option>
				<?php 
					$sql = "SELECT * FROM tb_cityplan WHERE cp_no = '".$_POST['cp_no']."'";
					$result = selectSql($sql);
					$sql = "SELECT * FROM ref_myhead WHERE myhead_type = 'gen' AND myhead_id != 33 AND myhead_id > 0 AND  myhead_status = 1 AND (myhead_step = '".$_POST['step_id']."' OR myhead_step = 'all')";
					$result = selectSql($sql);
					foreach($result as $row){ ?>
						<option value="<?php echo $row['myhead_id']; ?>"><?php echo $row['myhead_name'];?></option>
					<?php } ?>
				<option value="33">อื่นๆ ระบุ ......................</option>
			</select>
		</div>
		<div class="form-group">
			<label for="note_remark">ระบุหมายเหตุ</label>
			<textarea onkeyup="" style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark" placeholder="ระบุหมายเหตุ . . . ."></textarea>
		</div>
		<script>
			/*changeText();
			function changeText(){
				$('#editBtn').show();
				if($('#note_remark').val()==""){
					$('#editBtn').attr('disabled',true);
					$('#editBtn').removeAttr('onclick');
				}else{
					$('#editBtn').attr('disabled',false);
					$('#editBtn').attr('onclick','saveNote()');
				}
			}*/
			changeMyhead();
			function changeMyhead(){
				$('#editBtn').show();
				if($('#select-note-remark').val()=="select-myhead"){
					$('#editBtn').attr('disabled',true);
					$('#editBtn').removeAttr('onclick');
				}else{
					$('#editBtn').attr('disabled',false);
					$('#editBtn').attr('onclick','saveNote()');
				}
			}
			</script>
		<?php
		if($_POST['step_id']!=0){
			$sql = "SELECT * FROM ref_up_fst_step WHERE step_id = '".$_POST['step_id']."'";
			$result = selectSql($sql);
			$step = '<b>ขั้นตอนหลัก '.$_POST['step_id'].' : </b>'.$result[0]['step_name'];
			?> 
			
				<script>
					//changeNote();
					//$('#note_remark').hide();
					//alert('error');
					$('#note_step').html('<?php echo $step; ?>');
					
					/*function changeNote(){
						$('#editBtn').show();
						if($('#select-note-remark').val()=="select-myhead"){
							$('#note_remark').hide();
							$('#editBtn').attr('disabled',true);
							$('#editBtn').removeAttr('onclick');
							//alert('1');
							
						}else if($('#select-note-remark').val()==""){
							$('#note_remark').val("");
							$('#note_remark').show();
							$('#editBtn').attr('disabled',true);
							$('#editBtn').removeAttr('onclick');
							//alert('2');
						}else{
							$('#note_remark').val("");
							$('#note_remark').hide();
							$('#editBtn').attr('disabled',false);
							$('#editBtn').attr('onclick','saveNote()');
							//alert('3');
						}
					}
					
					function changeText(){
						$('#editBtn').show();
						if($('#note_remark').val()==""){
							$('#editBtn').attr('disabled',true);
							$('#editBtn').removeAttr('onclick');
						}else{
							$('#editBtn').attr('disabled',false);
							$('#editBtn').attr('onclick','saveNote()');
						}
					}*/
				</script>
			<?php
		}
		else{ 	
			?>
				<script>
					//changeNote();
					$('#note_step').html('<b>ไม่มีการดำเนินการ</b>');
					/*function changeNote(){
						if($('#select-note-remark').val()=="select-myhead"){
							$('#note_remark').hide();
							$('#editBtn').attr('disabled',true);
							$('#editBtn').removeAttr('onclick');
							//alert('1');
							
						}else if($('#select-note-remark').val()==""){
							$('#note_remark').val("");
							$('#note_remark').show();
							$('#editBtn').attr('disabled',true);
							$('#editBtn').removeAttr('onclick');
							//alert('2');
						}else{
							$('#note_remark').val("");
							$('#note_remark').hide();
							$('#editBtn').attr('disabled',false);
							$('#editBtn').attr('onclick','saveNote()');
							//alert('3');
						}
					}
					
					function changeText(){
						if($('#note_remark').val()==""){
							$('#editBtn').attr('disabled',true);
							$('#editBtn').removeAttr('onclick');
						}else{
							$('#editBtn').attr('disabled',false);
							$('#editBtn').attr('onclick','saveNote()');
						}
					}	*/
				</script>
			<?php
		}
		if($_POST['sub_no']!=0){
			$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['step_id']."' AND sub_no = '".$_POST['sub_no']."'";
			$result1 = selectSql($sql);
			$step1 = '<b>ขั้นตอนรอง  '.$_POST['step_id'].'.'.$_POST['sub_no'].' : </b>'.$result1[0]['sub_name'];
			?> 
				<script>
					$('#note_step_1').html('<?php echo $step1; ?>');
					$('#note_step_1').show();
				</script>
			<?php
		}
		else{
			?>
				<script>
					$('#note_step_1').html('');
					$('#note_step_1').hide();					
				</script>
			<?php
		}
		if($_POST['trd_step']!=0){
			$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['step_id']."' AND trd_snd = '".$_POST['sub_no']."' AND trd_step = '".$_POST['trd_step']."'";
			$result2 = selectSql($sql);
			$step2 = '<b>ขั้นตอนย่อย  '.$_POST['step_id'].'.'.$_POST['sub_no'].'.'.$_POST['trd_step'].' : </b>'.$result2[0]['trd_name'];
			?> 
				<script>
					$('#note_step_2').html('<?php echo $step2; ?>');
					$('#note_step_2').show();					
				</script>
			<?php
		}
		else{
			?>
				<script>
					$('#note_step_2').html('');
					$('#note_step_2').hide();					
				</script>
			<?php
		}
	}
	elseif($_POST['event']=='c_note+u_csd'){
		/*------------- INSERT Note to tb_note ----------------*/
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_step2,note_remark,note_uper,note_cp,note_csd,note_mh) VALUES('".$_POST['note_step']."' , '".$_POST['note_step_1']."' , '".$_POST['note_step_2']."' , '".$_POST['note_remark']."' , '".$_SESSION['intra_czid']."' , '".$_POST['note_cp']."' , '".$_POST['note_csd']."' , '".$_POST['note_mh']."')";
		in_up_delSql($sql);
		//echo $sql."\n";
		/*------------------  UPDATE csd_note  -----------------------*/
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_csd = '".$_POST['note_csd']."' AND note_status = 1";
		$result = selectSql($sql);
		//echo $sql."\n";
		$sql = "UPDATE ref_cityplan_step_detail SET	csd_note = '".$result[0]['COUNT(*)']."' WHERE csd_seq = '".$_POST['note_csd']."'";
		in_up_delSql($sql);
		//echo $sql."\n";
		
		/*----------- Log --------------*/
		$sql = "SELECT note_id FROM tb_note ORDER BY note_id DESC LIMIT 1";
		$result = selectSql($sql);
		$sql = "INSERT INTO tb_log(log_user, log_action, log_type, log_target) VALUE('".$_SESSION['intra_czid']."', 'add', 'note','".$result[0]['note_id']."')";
		in_up_delSql($sql);
		//echo $sql."\n";
		
		echo 'เพิ่มหมายเหตุสำเร็จ';
	}
	
	elseif($_POST['event']=='c_note+u_csd-x'){
		if($_POST['new_myhead']==1){
			$sql = "INSERT INTO ref_myhead(myhead_name, myhead_upper, myhead_step) VALUES('".$_POST['note_mh_new']."','".$_SESSION['intra_czid']."','".$_POST['note_step']."')";
			in_up_delSql($sql);
			
			$sql = "SELECT myhead_id FROM ref_myhead ORDER BY myhead_id DESC LIMIT 1";
			$result = selectSql($sql);
			
			$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','list','".$result[0]['myhead_id']."')";
			in_up_delSql($sql);
			
			$_POST['note_myhead'] = $result[0]['myhead_id'];
			//echo "TRUE";
		}
		/*------------- INSERT Note to tb_note ----------------*/
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_step2,note_remark,note_uper,note_cp,note_csd,note_mh) VALUE('".$_POST['note_step']."' , '".$_POST['note_step_1']."' , '".$_POST['note_step_2']."' , '".$_POST['note_remark']."' , '".$_SESSION['intra_czid']."' , '".$_POST['note_cp']."' , '".$_POST['note_csd']."','".$_POST['note_mh']."')";
		in_up_delSql($sql);
		//echo $sql;
		
		/*------------------  UPDATE csd_note  -----------------------*/
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_csd = '".$_POST['note_csd']."' AND note_status = 1";
		$result = selectSql($sql);
		//echo $sql;
		$sql = "UPDATE ref_cityplan_step_detail SET	csd_note = '".$result[0]['COUNT(*)']."' WHERE csd_seq = '".$_POST['note_csd']."'";
		//echo $sql;
		in_up_delSql($sql);
		
		/*----------- Log --------------*/
		$sql = "SELECT note_id FROM tb_note ORDER BY note_id DESC LIMIT 1";
		$result = selectSql($sql);
		$sql = "INSERT INTO tb_log(log_user, log_action, log_type, log_target) VALUE('".$_SESSION['intra_czid']."', 'add', 'note','".$result[0]['note_id']."')";
		in_up_delSql($sql);
		//echo $sql;
		
		
		echo 'เพิ่มหมายเหตุสำเร็จ';
	}
	elseif($_POST['event']=='c_note+u_csd-old'){
		/*------------- INSERT myhead to ref_myhead ----------------*/
		/*if($_POST['new_myhead'] == "1"){
			$sql = "SELECT cp_owner FROM tb_cityplan WHERE cp_no = '".$_POST['note_cp']."' ";
			$result = selectSql($sql);
			
			$sql = "INSERT INTO ref_myhead(myhead_name, myhead_upper, myhead_step) VALUES('".$_POST['note_remark']."','".$_SESSION['intra_czid']."','".$_POST['note_step']."')";
			//echo $sql;
			in_up_delSql($sql);
			
			$sql = "SELECT myhead_id FROM ref_myhead ORDER BY myhead_id DESC LIMIT 1";
			$result = selectSql($sql);
			
			$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','list','".$result[0]['myhead_id']."')";
			in_up_delSql($sql);

		}*/
		
		/*------------- INSERT Note to tb_note ----------------*/
		$sql = "INSERT INTO tb_note(note_step,note_step1,note_step2,note_remark,note_uper,note_cp,note_csd) VALUE('".$_POST['note_step']."' ,	'".$_POST['note_step_1']."' , '".$_POST['note_step_2']."' , '".$_POST['note_remark']."' , '".$_SESSION['intra_czid']."' , '".$_POST['note_cp']."' , '".$_POST['note_csd']."')";
		in_up_delSql($sql);
		//echo $sql;
		
		/*------------------  UPDATE csd_note  -----------------------*/
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_csd = '".$_POST['note_csd']."' AND note_status = 1";
		$result = selectSql($sql);
		//echo $sql;
		$sql = "UPDATE ref_cityplan_step_detail SET	csd_note = '".$result[0]['COUNT(*)']."' WHERE csd_seq = '".$_POST['note_csd']."'";
		//echo $sql;
		in_up_delSql($sql);
		
		
		
		/*----------- Log --------------*/
		$sql = "SELECT note_id FROM tb_note ORDER BY note_id DESC LIMIT 1";
		$result = selectSql($sql);
		$sql = "INSERT INTO tb_log(log_user, log_action, log_type, log_target) VALUE('".$_SESSION['intra_czid']."', 'add', 'note','".$result[0]['note_id']."')";
		in_up_delSql($sql);
		//echo $sql;
		
		echo 'เพิ่มหมายเหตุสำเร็จ';
	}
	elseif($_POST['event']=='ru_csd_note'){
		$sql = "SELECT * FROM tb_note WHERE note_csd =  '".$_POST['csd_seq']."' AND note_cp  = '".$_POST['cp_no']."' AND note_status != 0 ";
		//echo $sql;
		$result = selectSql($sql);
		$x = 0;
		foreach($result as $row){
			$x++; 
			$years = substr($row['note_timestamp'],0,4)+543;
			$months = substr($row['note_timestamp'],5,2);
			$days = substr($row['note_timestamp'],8,2);
			//$daten = '<b>วันที่เพิ่มหมายเหตุ : </b>'.$row['note_timestamp'];
			if($months==1){$months = "ม.ค.";}
			elseif($months==2){$months = "ก.พ.";}
			elseif($months==3){	$months = "มี.ค.";	}
			elseif($months==4){	$months = "เม.ย.";	}
			elseif($months==5){	$months = "พ.ค.";	}
			elseif($months==6){	$months = "มิ.ย.";	}
			elseif($months==7){	$months = "ก.ค.";	}
			elseif($months==8){	$months = "ส.ค.";	}
			elseif($months==9){	$months = "ก.ย.";	}
			elseif($months==10){$months = "ต.ค.";	}
			elseif($months==11){$months = "พ.ย.";}
			else{$months = "ธ.ค.";}
			$times = substr($row['note_timestamp'],11,8);
			$daten = "<b>วันที่เพิ่มหมายเหตุ : </b>".$days." ".$months." ".$years;
			$timen = '<b> เวลา : </b>'.$times." น.";
			if($x>1){
				echo '<hr style="border-color:#989899">';
			} ?>
			<div class="form-group">
				<div id="<?php echo 'date_note_'.$x; ?>" class="pull-left"></div>
				<div id="<?php echo 'date_time_'.$x; ?>" class="pull-right"></div><br>
				<label for="<?php echo 'note_'.$x; ?>"><?php //echo 'หมายเหตุที่ '.$x;?></label>
			<?php
/*				<span id="btnTrash" title="แก้ไขหมายเหตุ2" style="margin-right:8px" class="gear pull-right" onclick="editNote(<?php echo $x;?>,<?php echo $row['note_id'];?>,<?php echo $_POST['cp_no'];?>)"><a><i class="fa fa-gear"></i></a></span>*/
			if($_SESSION['status']==1){	?>
				<span id="btnGear" title="ลบหมายเหตุ" style="margin-right:8px" class="trash pull-right" onclick="delNote(<?php echo $row['note_id'];?>,<?php echo $_POST['cp_no'];?>)"><a><i class="fa fa-trash"></i></a></span>
			<?php } ?>
				<div class="div_note" id="<?php echo 'div_note_'.$x; ?>"><?php echo $row['note_remark']?></div>
				<input class="form-control input_note" id="<?php echo 'note_'.$x; ?>"value="<?php echo $row['note_remark']?>">
			</div>
			<script>
				$('<?php echo "#note_".$x; ?>').hide();
				$('<?php echo "#date_note_".$x; ?>').html('<?php echo $daten; ?>');
				$('<?php echo "#date_time_".$x; ?>').html('<?php echo $timen; ?>');
				$('#note_step_hidden').val('<?php echo $_POST["step_id"]; ?>');
				$('#note_step_1_hidden').val('<?php echo $_POST["sub_no"];?>');
				$('#note_step_2_hidden').val('<?php echo $_POST["trd_step"];?>');
				$('#note_cp').val('<?php echo $_POST["cp_no"];?>');
			</script>
			<?php 
		}
		if($_POST['step_id']!=0){
			$sql = "SELECT * FROM ref_up_fst_step WHERE step_id = '".$_POST['step_id']."'";
			$result = selectSql($sql);
			$step = '<b>ขั้นตอนหลัก '.$_POST['step_id'].' : </b>'.$result[0]['step_name'];
			?> 
				<script>
					$('#note_step').html('<?php echo $step; ?>');
				</script>
			<?php
		}
		else{ 	
			?>
				<script>
					$('#note_step').html('<b>ไม่มีการดำเนินการ</b>');					
				</script>
			<?php
		}
		if($_POST['sub_no']!=0){
			$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$_POST['step_id']."' AND sub_no = '".$_POST['sub_no']."'";
			$result1 = selectSql($sql);
			$step1 = '<b>ขั้นตอนรอง  '.$_POST['step_id'].'.'.$_POST['sub_no'].' : </b>'.$result1[0]['sub_name'];
			?> 
				<script>
					$('#note_step_1').html('<?php echo $step1; ?>');
					$('#note_step_1').show();
				</script>
			<?php
		}
		else{
			?>
				<script>
					$('#note_step_1').html('');
					$('#note_step_1').hide();					
				</script>
			<?php
		}
		if($_POST['trd_step']!=0){
			$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['step_id']."' AND trd_snd = '".$_POST['sub_no']."' AND trd_step = '".$_POST['trd_step']."'";
			$result2 = selectSql($sql);
			$step2 = '<b>ขั้นตอนย่อย  '.$_POST['step_id'].'.'.$_POST['sub_no'].'.'.$_POST['trd_step'].' : </b>'.$result2[0]['trd_name'];
			?> 
				<script>
					$('#note_step_2').html('<?php echo $step2; ?>');
					$('#note_step_2').show();					
				</script>
			<?php
		}
		else{
			?>
				<script>
					$('#note_step_2').html('');
					$('#note_step_2').hide();					
				</script>
			<?php
		}
		
		
		
	}
	elseif($_POST['event']=='ru_selectnote-csd'){
		?>
		<label for="note_remark">หมายเหตุ</label>
		<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark" onkeyup="changeText();"></textarea>
		<script>
			$('#note_id').val(<?php echo $_POST['note_id'];?>);
			changeText();
			function changeText(){
				$('#editBtn').show();
				if($('#note_remark').val()==""){
					$('#editBtn').attr('disabled',true);
					$('#editBtn').removeAttr('onclick');
				}else{
					$('#editBtn').attr('disabled',false);
					$('#editBtn').attr('onclick','saveNote()');
				}
			}
		</script>
		<?php 
	}
	elseif($_POST['event']=='u_note+u_csd'){
		$sql = "UPDATE tb_note SET note_remark = '".$_POST['note_remark']."' WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_status != 0 AND note_csd = '".$_POST['note_csd']."'";
		$result = selectSql($sql);
		//echo $sql;
		
		$sql = "UPDATE ref_cityplan_step_detail SET csd_note = '".$result[0]['COUNT(*)']."' WHERE csd_seq = '".$_POST['note_csd']."'";
		in_up_delSql($sql);
		//echo $sql; 
		
		
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUE('".$_SESSION['intra_czid']."','edit','note','".$_POST['note_id']."')";
		in_up_delSql($sql);
		//echo $sql;
		
		echo "แก้ไขหมายเหตุสำเร็จ";
	}
	elseif($_POST['event']=='d_note+u_csd'){
		$sql = "UPDATE tb_note SET note_status = 0 WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_status != 0 AND note_csd = '".$_POST['csd_seq']."'";
		$result = selectSql($sql);
		//echo $sql;
		
		$sql = "UPDATE ref_cityplan_step_detail SET csd_note = '".$result[0]['COUNT(*)']."' WHERE csd_seq = '".$_POST['csd_seq']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUE('".$_SESSION['intra_czid']."','del','note','".$_POST['note_id']."')";
		in_up_delSql($sql);
		//echo $sql;
		
		echo "ลบหมายเหตุสำเร็จ";
	}
	elseif($_POST['event']=='c_myhead'){
		$sql = "INSERT INTO ref_myhead(myhead_name, myhead_upper, myhead_step) VALUES('".$_POST['myhead_name']."','".$_SESSION['intra_czid']."','".$_POST['myhead_step']."')";
		//echo $sql;
		in_up_delSql($sql);
		
		$sql = "SELECT myhead_id FROM ref_myhead ORDER BY myhead_id DESC LIMIT 1";
		$result = selectSql($sql);			
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','list','".$result[0]['myhead_id']."')";
		in_up_delSql($sql);
		
		echo "เพิ่มรายการสำเร็จ";
	}
	elseif($_POST['event']=='ru_myhead'){
        $sql = "SELECT * FROM ref_myhead WHERE myhead_id = '".$_POST['myhead_id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
			'myhead_id' => $row['myhead_id'],
            'myhead_name' => $row['myhead_name'],
			'myhead_step' => $row['myhead_step']
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
	elseif($_POST['event']=='u_myhead'){
        $sql = "UPDATE ref_myhead SET myhead_name = '".$_POST['myhead_name']."' , myhead_step = '".$_POST['myhead_step']."' WHERE myhead_id = '".$_POST['myhead_id']."'";
		in_up_delSql($sql);
		//echo $sql;  

		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','list','".$_POST['myhead_id']."')";
		in_up_delSql($sql);		
		echo "แก้ไขรายการสำเร็จ";
    }
	elseif($_POST['event']=='d_myhead'){
        $sql = "UPDATE ref_myhead SET myhead_status = 0  WHERE myhead_id = '".$_POST['myhead_id']."'";
		in_up_delSql($sql);
		//echo $sql;        
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','del','list','".$_POST['myhead_id']."')";
		in_up_delSql($sql);
		echo "ลบรายการสำเร็จ";      
    }
	elseif($_POST['event']=='u_cp-update_stage'){
		$sql = "UPDATE tb_cityplan SET cp_stage = '".$_POST['cp_stage']."' , cp_stage_update = NULL WHERE cp_no = '".$_POST['cp_no']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','cp','".$_POST['cp_no']."')";
		in_up_delSql($sql);
		//echo $sql;
		
		echo "ปรับปรุงขั้นตอนสำเร็จ";
	}
	elseif($_POST['event']=='c_consider'){
		$sql = "INSERT INTO ref_consider(consider_name,consider_type) VALUES('".$_POST['consider_name']."','".$_POST['consider_type']."')";
		in_up_delSql($sql);
		
		$sql ="SELECT * FROM ref_consider ORDER BY consider_id DESC LIMIT 1";
		$result = selectSql($sql);
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','con','".$result[0]['consider_id']."')"; 
		//echo $sql;
		//in_up_delSql($sql);
		
		echo "เพิ่มข้อมูลสำเร็จ";
	}
	elseif($_POST['event']=='ru_consider'){
		$sql = "SELECT * FROM ref_consider WHERE consider_id = '".$_POST['consider_id']."'";
		$result = selectSql($sql);
		?>
			<script>
				$('#consider_name').val("<?= $result[0]['consider_name'];?>");
				$('#consider_type').val("<?= $result[0]['consider_type'];?>");
				$('#consider_id').val("<?= $result[0]['consider_id'];?>");
			
			</script>
		<?php
	}
	elseif($_POST['event']=='u_consider'){
		$sql = "UPDATE ref_consider SET consider_name = '".$_POST['consider_name']."' , consider_type = '".$_POST['consider_type']."' WHERE consider_id = '".$_POST['consider_id']."'";
		in_up_delSql($sql);
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','con','".$_POST['consider_id']."')"; 
		//echo $sql;
		//in_up_delSql($sql);
		echo "แก้ไขข้อมูลสำเร็จ";
	}
	elseif($_POST['event']=='d_consider'){
		$sql = "UPDATE ref_consider SET consider_status = 0 WHERE consider_id = '".$_POST['consider_id']."'";
		in_up_delSql($sql);
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','del','con','".$_POST['consider_id']."')"; 
		//echo $sql;
		//in_up_delSql($sql);
		echo "ลบข้อมูลสำเร็จ";
	}
	elseif($_POST['event']=='u_cp-consider'){
		$sql = "UPDATE tb_cityplan SET cp_consider = '".$_POST['consider']."' WHERE cp_no = '".$_POST['cp_no']."'";
		in_up_delSql($sql);
		//echo $sql;
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','edit','cp','".$_POST['cp_no']."')";
		in_up_delSql($sql);
		//echo $sql;
		echo "แก้ไขสำเร็จ";
	}
	elseif($_POST['event']=='ru_note-consider'){
		$sql = "SELECT cp_name , consider_name FROM tb_cityplan , ref_consider WHERE cp_consider = consider_id AND cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		echo $result[0]['consider_name'];
		echo '<script> $("#title-note-consider").html("'.$result[0]["cp_name"].'");</script>';
	}
	elseif($_POST['event']=="c_cp+c_csd"){
		 if($_POST['cp_step']==0){
			 $_POST['cp_step_1'] = 0;
			 $_POST['cp_step_2'] = 0;
		 }
		 if($_POST['cp_current_status']!='1.1' OR  $_POST['cp_dflaw']==''){
			 $_POST['cp_dflaw'] = "NULL";
		 }
		 
        $sql = "INSERT INTO tb_cityplan(cp_type, cp_eec, cp_province, cp_name, cp_budget, cp_owner, cp_operator, cp_scope, cp_status, cp_stage, cp_effective, cp_consider, cp_date_notice, cp_date_exp,cp_current_status,cp_dflaw) VALUES(
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
		'".$_POST['cp_consider']."',
		'".$_POST['cp_date_notice']."',
		'".$_POST['cp_date_exp']."',
		'".$_POST['cp_current_status']."',
		".$_POST['cp_dflaw']."
		)";
		//echo $sql;
        in_up_delSql($sql);
		
		$sql = "SELECT * FROM tb_cityplan ORDER BY cp_no DESC LIMIT 1"; 
		$result = selectSql($sql);
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','cp','".$result[0]['cp_no']."')";
		in_up_delSql($sql);
		
		$sql = "INSERT INTO ref_cityplan_step_detail(csd_cp, csd_step_fst, csd_step_snd, csd_step_trd, csd_petition) VALUES('".$result[0]['cp_no']."','0','0','0','0')";
		in_up_delSql($sql);	
		
		$sql = "SELECT csd_seq FROM ref_cityplan_step_detail WHERE csd_cp = '".$result[0]['cp_no']."'  ORDER BY csd_seq DESC LIMIT 1";
		$ende = selectSql($sql);
		
		$sql = "UPDATE tb_cityplan SET cp_csd = '".$ende[0]['csd_seq']."' WHERE cp_no = '".$result[0]['cp_no']."'";
		in_up_delSql($sql);
		
		//echo $sql;
		echo "เพิ่มข้อมูลสำเร็จ";
		
	}
	elseif($_POST['event']=='r_session'){
		print_r($_SESSION);
	}
	elseif($_POST['event']=='clear_cp_session'){
		clear_cp_session();
	}
	elseif($_POST['event']=='changeViewMode'){
		$_SESSION['view_mode'] = '9';
		$_SESSION['data_access'] = $_POST['data_access'];
	}
	
	elseif($_POST['event']=='r_timesnd-extra'){
		$sql = "SELECT * FROm tb_cityplan, ref_up_snd_step, ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_step = sub_step AND cp_step_1 = sub_no AND cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		$cp_name = $result[0]['cp_name'];
		
		//$sql = "SELECT * FROM ref_up_snd_step WHERE ";
		$stepnd = $result[0]['csd_step_fst'].'.'.$result[0]['csd_step_snd'].' '.$result[0]['sub_name'];
		$csd_start = $result[0]['csd_start'];
		$csd_stop = $result[0]['csd_stop'];
		$status  = $_POST['day'];
		if($result[0]['cp_step']==6){
			if($result[0]['csd_petition']==1){
				$sub_ot = $result[0]['sub_time2'];
				$stepnd = $stepnd.' (กรณีที่มีคำร้อง)';
			}else{
				$sub_ot = $result[0]['sub_time1'];
			}
		}else{
			$sub_ot = $result[0]['sub_time'];
		}
		$ot = $sub_ot.' สัปดาห์ (จำนวน '.($sub_ot*7).' วัน)';
		
		  
		$start_ad = (substr($csd_start,0,4)-543).substr($csd_start,4);
		$deadline = date_create($start_ad);
		$deadline = date_add($deadline,date_interval_create_from_date_string(($sub_ot*7-1)." days"));
		$deadlinex = date_format($deadline , 'd').' '.IntToMonth(date_format($deadline , 'm'),'th').' '.(date_format($deadline , 'Y')+543);
		
		
		
		if($_POST['day']<=0){
			$status = 'แล้วเสร็จ';
		}elseif($_POST['day']<=($sub_ot*7)){
			$status = 'แล้วเสร็จ (ล่าช้าเล็กน้อย)';
		}elseif($_POST['day']<=($sub_ot*7*2)){
			$status = 'แล้วเสร็จ (ล่าช้าปานกลาง)';
		}else{
			$status = 'แล้วเสร็จ (ล่าช้ามาก)';
		}
		$warning = '';
		$current = date('Y-m-d');
		$csd_stopx = (substr($csd_stop,0,4)-543).substr($csd_stop,4);
		if($csd_stopx<$current){
			$warning = '(<span style="color:red"><a style="color:red;margin:right:3px;"><i class="fa fa-exclamation"></i></a> ไม่ระบุขั้นตอนถัดไป</span>)';
		}else{
			$warning = '';
		}
		?>
		<div style="margin:0px 0px;font-size:16px;" id="rt_snd_cp_name">ชื่อผัง : <b><?= $cp_name;?></b></div>	
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%"> ขั้นตอนการดำเนินการปัจจุบัน
			<div style="margin-left:16px" id="rt_snd_sub_name">ขั้นตอนที่ : <b><?= $stepnd?></b></div>
		</div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_snd_day">ระยะเวลาดำเนินการ : <b><?= $ot;?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_snd_csd_start">เริ่มดำเนินการในวันที่ : <b><?php if($csd_start!='' AND $csd_start!=null){ echo substr($csd_start,8,2).' '.IntToMonth(substr($csd_start,5,2),'th').' '.substr($csd_start,0,4);}else{ echo "-";}?></b></div>
		<?php if($csd_start!='' AND $csd_start!=null){ ?>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_snd_csd_deadline">วันที่กำหนดแล้วเสร็จ : <b><?php  if($csd_start!='' AND $csd_start!=null){ echo $deadlinex;}else{ echo "-";}?></b></div>
		<?php } ?>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_snd_csd_stop">ดำเนินการแล้วเสร็จในวันที่ : <b><?php if($csd_stop!='' AND $csd_stop!=null){ echo substr($csd_stop,8,2).' '.IntToMonth(substr($csd_stop,5,2),'th').' '.substr($csd_stop,0,4);}else{echo "-";}?></b></div>
		<?php if($csd_stop!='' AND $csd_stop!=null){ ?>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_snd_status">สถานะดำเนินการ : <b><?= $status.' ';//.$warning;//$_POST['day'];?></b></div>
		<?php }
		
	}
	elseif($_POST['event']=='r_timesnd'){
		$sql = "SELECT * FROm tb_cityplan, ref_up_snd_step, ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_step = sub_step AND cp_step_1 = sub_no AND cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		$json = array();
		if($result[0]['csd_start']!=null AND $result[0]['csd_start']!='' AND $result[0]['csd_start']!=' '){
			if($_POST['day']<=0){
				$day = '<b>สถานะดำเนินการ </b>ครบกำหนดใน '.($_POST['day']*-1).' วัน';
				/*
				if($result[0]['csd_stop']!=null AND $result[0]['csd_stop']!='' AND $result[0]['csd_stop']!=' '){
					$day = '<b>สถานะดำเนินการ </b>ดำเนินการเสร็จสิ้น ';
				}else{
					$day = '<b>สถานะดำเนินการ </b>ครบกำหนดใน '.($_POST['day']*-1).' วัน';
				}
				*/
			}else{
				$day = '<b>สถานะดำเนินการ </b>ล่าช้า '.$_POST['day'].' วัน';
				/*
				if($result[0]['csd_stop']!=null AND $result[0]['csd_stop']!='' AND $result[0]['csd_stop']!=' '){
					$day = '<b>สถานะดำเนินการ </b>ดำเนินการเสร็จสิ้น ('.'ล่าช้า '.$_POST['day'].' วัน)';
				}else{
					$day = '<b>สถานะดำเนินการ </b>ล่าช้า '.$_POST['day'].' วัน';
				}
				*/
			}
		}else{
			$day = "";
		}
		$start = (substr($result[0]['csd_start'],0,4)-543).substr($result[0]['csd_start'],4);
		$deadline = date_create($start);
		if($result[0]['cp_sstep']==6){
			if($result[0]['cp_petition']!=1){
				$sub_ot = $result[0]['sub_time1']*7;
			}else{
				$sub_ot = $result[0]['sub_time2']*7;
			}
		}else{
			$sub_ot = $result[0]['sub_ot1'];
		}
		$deadline = date_add($deadline,date_interval_create_from_date_string(($sub_ot-1)." days"));
		
		
		foreach($result as $row){
			if($row['cp_step_1']>0){
				$sub_name = '<b>ขั้นตอนที่ '.$row['cp_step'].'.'.$row['cp_step_1'].' </b> '.$row['sub_name'];
			}else{
					$sub_name = '<b>ไม่มีการดำเนินการในขั้นตอนย่อย 1</b> ';
			}
			//if($row['csd_start']=='' OR $row['csd_start']==' ' OR $row['csd_start']==null){$row['csd_start']=' ไม่ระบุวันที่เริ่มต้น';}
			//if($row['csd_stop']=='' OR $row['csd_stop']==' ' OR $row['csd_stop']==null){$row['csd_stop']=' ไม่ระบุวันที่สิ้นสุด';}
			if($row['csd_start']=='' OR $row['csd_start']==' ' OR $row['csd_start']==null){
				$deadline = '<b>ระบะเวลาดำเนินการ</b> ไม่ระบุวันที่';
				$day = "<b>สถานะดำเนินการ</b> -";
			}else{
				//$deadline = '<b>ระยะเวลาดำเนินการ </b>'.$row['csd_start'].' ถึง '.(date_format($deadline,'Y')+543).date_format($deadline,'-m-d');
				$deadline = '<b>ระยะเวลาดำเนินการ </b>'.substr($row['csd_start'],8,2).' '.IntToMonth(substr($row['csd_start'],5,2),'th').' '.substr($row['csd_start'],0,4).' ถึง '.date_format($deadline,'d').' '.IntToMonth(date_format($deadline,'m'),'th').' '.(date_format($deadline,'Y')+543);
			
			}
			
			$json = [
				'cp_name' => '<b>'.$row['cp_name'].'</b>',
				'sub_name' => $sub_name,
				'csd_start' => $deadline,
				'day' => $day,
			];
		}		
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='r_timetrd-extra'){
		$sql = "SELECT * FROm tb_cityplan, ref_up_trd_step, ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_step = trd_fst AND cp_step_1 = trd_snd AND cp_step_2 = trd_step AND cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		$cp_name = $result[0]['cp_name'];

		$steprd = $result[0]['csd_step_fst'].'.'.$result[0]['csd_step_snd'].' '.$result[0]['csd_step_trd'].' '.$result[0]['trd_name'];
		$csd_start = $result[0]['csd_start_2'];
		$csd_stop = $result[0]['csd_stop_2'];
		$status  = $_POST['day'];
		$sub_ot = $result[0]['trd_time'];
		$ot = $sub_ot.' สัปดาห์ (จำนวน '.($sub_ot*7).' วัน)';
		
		  
		$start_ad = (substr($csd_start,0,4)-543).substr($csd_start,4);
		$deadline = date_create($start_ad);
		$deadline = date_add($deadline,date_interval_create_from_date_string(($sub_ot*7-1)." days"));
		$deadlinex = date_format($deadline , 'd').' '.IntToMonth(date_format($deadline , 'm'),'th').' '.(date_format($deadline , 'Y')+543);
		
		
		
		if($_POST['day']<=0){
			$status = 'แล้วเสร็จ';
		}elseif($_POST['day']<=($sub_ot*7)){
			$status = 'แล้วเสร็จ (ล่าช้าเล็กน้อย)';
		}elseif($_POST['day']<=($sub_ot*7*2)){
			$status = 'แล้วเสร็จ (ล่าช้าปานกลาง)';
		}else{
			$status = 'แล้วเสร็จ (ล่าช้ามาก)';
		}
		$warning = '';
		$current = date('Y-m-d');
		$csd_stopx = (substr($csd_stop_2,0,4)-543).substr($csd_stop_2,4);
		if($csd_stopx<$current){
			$warning = '(<span style="color:red"><a style="color:red;margin:right:3px;"><i class="fa fa-exclamation"></i></a> ไม่ระบุขั้นตอนถัดไป</span>)';
		}else{
			$warning = '';
		}
		?>
		<div style="margin:0px 0px;font-size:16px;" id="rt_trd_cp_name">ชื่อผัง : <b><?= $cp_name;?></b></div>	
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%"> ขั้นตอนการดำเนินการปัจจุบัน
			<div style="margin-left:16px" id="rt_trd_sub_name">ขั้นตอนที่ : <b><?= $steprd?></b></div>
		</div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_trd_day">ระยะเวลาดำเนินการ : <b><?= $ot;?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_trd_csd_start">เริ่มดำเนินการในวันที่ : <b><?php if($csd_start!='' AND $csd_start!=null){ echo substr($csd_start,8,2).' '.IntToMonth(substr($csd_start,5,2),'th').' '.substr($csd_start,0,4);}else{ echo "-";}?></b></div>
		<?php if($csd_start!='' AND $csd_start!=null){ ?>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_trd_csd_deadline">วันที่กำหนดแล้วเสร็จ : <b><?php  if($csd_start!='' AND $csd_start!=null){ echo $deadlinex;}else{ echo "-";}?></b></div>
		<?php } ?>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_trd_csd_stop">ดำเนินการแล้วเสร็จในวันที่ : <b><?php if($csd_stop!='' AND $csd_stop!=null){ echo substr($csd_stop,8,2).' '.IntToMonth(substr($csd_stop,5,2),'th').' '.substr($csd_stop,0,4);}else{echo "-";}?></b></div>
		<?php if($csd_stop!='' AND $csd_stop!=null){ ?>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="rt_trd_status">สถานะดำเนินการ : <b><?= $status.' ';//.$warning;//$_POST['day'];?></b></div>
		<?php }
		
	}
	elseif($_POST['event']=='r_timetrd'){
		$sql = "SELECT * FROm tb_cityplan, ref_up_trd_step, ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_step = trd_fst AND cp_step_1 = trd_snd AND cp_step_2 = trd_step AND cp_no = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		$json = array();
		if($result[0]['csd_start_2']!=null AND $result[0]['csd_start_2']!='' AND $result[0]['csd_start_2']!=' '){
			if($_POST['day']<=0){
				$day = '<b>สถานะดำเนินการ </b>ครบกำหนดใน '.($_POST['day']*-1).' วัน';
				/*
				if($result[0]['csd_stop_2']!=null AND $result[0]['csd_stop_2']!='' AND $result[0]['csd_stop_2']!=' '){
					$day = '<b>สถานะดำเนินการ </b>ดำเนินการเสร็จสิ้น ';
				}else{
					$day = '<b>สถานะดำเนินการ </b>ครบกำหนดใน '.($_POST['day']*-1).' วัน';
				}
				*/
			}else{
				$day = '<b>สถานะดำเนินการ </b>ล่าช้า '.$_POST['day'].' วัน';
				/*
				if($result[0]['csd_stop_2']!=null AND $result[0]['csd_stop_2']!='' AND $result[0]['csd_stop_2']!=' '){
					$day = '<b>สถานะดำเนินการ </b>ดำเนินการเสร็จสิ้น ('.'ล่าช้า '.$_POST['day'].' วัน)';
				}else{
					$day = 'ล่าช้า '.$_POST['day'].' วัน';
				}
				*/
			}
		}else{
			$day = "";
		}
		$start = (substr($result[0]['csd_start_2'],0,4)-543).substr($result[0]['csd_start_2'],4);
		$deadline = date_create($start);
		$trd_ot = $result[0]['trd_ot1'];
		$deadline = date_add($deadline,date_interval_create_from_date_string(($trd_ot-1)." days"));
		
		foreach($result as $row){
			if($row['cp_step_2']>0){
				$sub_name = '<b>ขั้นตอนที่ '.$row['cp_step'].'.'.$row['cp_step_1'].'.'.$row['cp_step_2'].' </b> '.$row['trd_name'];
			}else{
				$sub_name = '<b>ไม่มีการดำเนินการในขั้นตอนย่อย 1</b> ';
			}
			//if($row['csd_start']=='' OR $row['csd_start']==' ' OR $row['csd_start']==null){$row['csd_start']=' ไม่ระบุวันที่เริ่มต้น';}
			//if($row['csd_stop']=='' OR $row['csd_stop']==' ' OR $row['csd_stop']==null){$row['csd_stop']=' ไม่ระบุวันที่สิ้นสุด';}
			if($row['csd_start_2']=='' OR $row['csd_start_2']==' ' OR $row['csd_start_2']==null){
				$deadline = '<b>ระบะเวลาดำเนินการ</b> ไม่ระบุวันที่';
				$day = "<b>สถานะดำเนินการ</b> -";
			}else{
				$deadline = '<b>ระยะเวลาดำเนินการ </b>'.substr($row['csd_start_2'],8,2).' '.IntToMonth(substr($row['csd_start_2'],5,2),'th').' '.substr($row['csd_start_2'],0,4).' ถึง '.date_format($deadline,'d').' '.IntToMonth(date_format($deadline,'m'),'th').' '.(date_format($deadline,'Y')+543);
			}
			$json = [
				'cp_name' => '<b>'.$row['cp_name'].'</b>',
				'trd_name' => $sub_name,
				'csd_start' => $deadline,
				'day' => $day,
			];
		}		
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='ru_noteX'){
		$sql = "SELECT * FROM tb_note WHERE note_status = 1 AND note_cp = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		$i = 0;
		foreach($result as $row){
			$i++;
			echo $i.'. '.$row['note_remark'];
			echo '<br>';
		}
		
	}
	elseif($_POST['event']=='ru_noteX'){
		$sql = "SELECT * FROM tb_note WHERE note_status = 1 AND note_cp = '".$_POST['cp_no']."'";
		$result = selectSql($sql);
		$i = 0;
		foreach($result as $row){
			$i++;
			echo $i.'. '.$row['note_remark'];
			echo '<br>';
		}
		
	}
	elseif($_POST['event']=='c_statusdate'){
		$sql = "SELECT * FROM tref_rp_cs_date";
		$result = selectSql($sql);
		if(count($result)==0){
			$sql = "INSERT INTO tref_rp_cs_date(rp_cs_date) VALUES('".$_POST['status_datetime']."')";
		}else{
			$sql = "UPDATE tref_rp_cs_date  SET rp_cs_date = '".$_POST['status_datetime']."' WHERE rp_cs_date_no = 1";
		}
		in_up_delSql($sql);
		
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','rp_cs','date : ".$_POST['status_datetime']."')";
		//in_up_delSql($sql);
		//echo $sql;
		
	 echo "เพิ่มวันที่สำเร็จ";
	}
	elseif($_POST['event']=='c_rp_cs_dflaws'){
		$sql = "INSERT INTO tref_rp_cs_dflaws(rp_cs_df_id, rp_cs_df_title, rp_cs_df_asc) VALUES('".$_POST['id']."','".$_POST['title']."','".$_POST['asc']."')";
		in_up_delSql($sql);
		
		//$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','dflaw','".$_POST['status_datetime']."')";
		//echo $sql;
		//in_up_delSql($sql);
	}
	elseif($_POST['event']=='c_rp_cs_operate'){
		$sql = "INSERT INTO tref_rp_cs_operate(rp_cs_op_id, rp_cs_op_title, rp_cs_op_asc) VALUES('".$_POST['id']."','".$_POST['title']."','".$_POST['asc']."')";
		in_up_delSql($sql);
		
		//$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','dflaw','".$_POST['status_datetime']."')";
		//echo $sql;
		//in_up_delSql($sql);
	}elseif($_POST['event']=='ronly_cp'){
		$sql = "SELECt * FROM tb_cityplan,ref_province, ref_type, ref_scope, ref_status , ref_stage, ref_operate_fst ,ref_operate_snd, ref_effective
		WHERE (cp_province = pvn_id AND cp_type = type_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id)AND cp_no = '".$_POST['x']."'";
		$result = selectSql($sql);
		//echo count($result);
		?>
		<div style="padding:7px 0px 0px;margin:-6px 0px 4px;width:100%;font-size:16px;" id="read_cp_name">ชื่อผัง : <b><?= $result[0]['cp_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_ pvn">จังหวัด : <b><?= $result[0]['pvn_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_budget">ปีงบประมาณ : <b><?= $result[0]['cp_budget'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_type">ประเภทผังเมือง : <b><?= $result[0]['type_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_eec">ผังกลุ่ม EEC : <b><?php if($result[0]['cp_eec']=='Y'){echo "ใช่";}else{echo"ไม่ใช่";}?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_scope">ขอบเขต : <b><?= $result[0]['scope_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_status">สถานภาพผัง : <b><?= $result[0]['status_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_stage">ขั้นตอน : <b><?= $result[0]['stage_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_owner">เจ้าของ : <b><?= $result[0]['opt_fst_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_operator">ผู้ดำเนินการ : <b><?= $result[0]['opt_snd_name'];?></b></div>
		<div style="padding:7px 0px 0px;margin:4px 0px;border-top:solid #dcdcdc 1px;width:100%" id="read_cp_effective">มีผลบังคับใช้ : <b><?= $result[0]['eff_name'];?></b></div>
		
		<?php 
	}
	elseif($_POST['event']=='select-cp'){
		if($_POST['y']==1){
			$cond = ' cp_status = 1 AND cp_stage = 1';
		}elseif($_POST['y']==2){
			$cond = ' cp_status = 2 AND cp_stage = 3 AND cp_effective = 1';
		}elseif($_POST['y']==3){
			$cond = ' cp_status = 2 AND cp_stage = 3 AND cp_effective != 1';
		}
		
		?>
			<input id="select_cp_name" class="form-control"  onfocus="changeCP()" onkeyup="changeCP()"  placeholder="เลือก/ระบุชื่อผังเมือง" autocomplete="off">
			<div id="sub_select_cp" style="    display: flex;flex-direction: column;border: solid #dfdfdf 1px;max-height: 300px;overflow: auto;"></div>
		<?php
		
	}
	elseif($_POST['event']=='select-cp-input'){
		if($_POST['type']>0){
			if($_POST['type']==3){
				$cond = ' tb_cityplan.cp_status = 2 AND tb_cityplan.cp_stage = 3 AND tb_cityplan.cp_effective != 1';
			}
			elseif($_POST['type']==2){
				$cond = ' tb_cityplan.cp_status = 2 AND tb_cityplan.cp_stage = 3 AND tb_cityplan.cp_effective = 1';
			}
			elseif($_POST['type']==1){
				$cond = ' tb_cityplan.cp_status = 1 AND tb_cityplan.cp_stage = 1 AND tb_cityplan.cp_effective = 0';
			}
			//$sql = "SELECT cp_no , cp_name FROM tb_cityplan WHERE cp_name LIKE '%".$_POST['cp_name']."%' AND ".$cond." ORDER BY cp_name";
			$sql = "SELECT tb_cityplan.cp_name as cp_name FROM tb_cityplan LEFT JOIN tb_rp_current_status ON tb_cityplan.cp_no = tb_rp_current_status.cp_no WHERE tb_rp_current_status.cp_no IS NULL AND tb_cityplan.cp_name LIKE '%".$_POST['cp_name']."%' AND ".$cond." ORDER BY tb_cityplan.cp_name ASC";
			//echo $sql;
			$result = selectSql($sql);
			$all_data = count($result);
			$i=0;
			if($all_data>0){
				foreach($result as $row){
					$i++;
					?><a class="sub-select-cp-input" onclick="sub_select_cp('<?= $row['cp_name']?>')"><?= $i.') '.$row["cp_name"];?></a><?php
				}
				?><a class="sub-select-cp-input" onclick="$('#sub_select_cp').hide()">ปิด</a><?php
			}
			else{
				?><div style="width:100;display: flex;justify-content: space-between;flex-direction: row;">
					<a style="color:red;margin:0px 10px;padding:4px 0px">ไม่พบชื่อผังที่ตรงกัน</a>
					<a style="margin:0px 10px;padding:4px 0px" onclick="$('#sub_select_cp').hide()">ปิด</a><?php
			}
			
		}
		//echo 'hi12';
	}
	elseif($_POST['event']=='c_cs_cp'){
		$sql = "SELECT cp_no, cp_name FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		//echo $sql;
		$result = selectSql($sql);
		$all_data= count($result);
		if($all_data!=1){
			echo "ไม่พบชื่อผังที่ตรงกัน โปรดลองใหม่อีกครั้ง";
		}else{
			if($_POST['operate']!='1.1'){
				$_POST['dflaw'] = 0;
			}
			$sql = "INSERT tb_rp_current_status(cp_no, cp_cs_operate, cp_cs_draft_laws,cp_cs_order_asc)VALUES('".$result[0]['cp_no']."','".$_POST['operate']."','".$_POST['dflaw']."',0)";
			//echo $sql;
			in_up_delSql($sql);
			echo "เพิ่มข้อมูลสำเร็จ";
			
		}
	}
	elseif($_POST['event']=='c_plan'){
		$plan = json_decode($_POST['plan'],true);
		$plan2 = json_decode($_POST['plan2'],true);
		$sql = "INSERT INTO ref_sum_plan(splan_cp) VALUES('".$_POST['cp_no']."')";
		//echo $sql;
		in_up_delSql($sql);
		
		
		$sql = "SELECT * FROM ref_sum_plan WHERE splan_cp = '".$_POST['cp_no']."' ORDER BY splan_id DESC LIMIT 1";
		$result = selectSql($sql);
		
		
		$all_data =  count($plan['step']);
		for($i=0;$i<$all_data;$i++){
			$sql = "INSERT INTO tb_plan(plan_id, plan_step, plan_start, plan_stop, plan_petition) VALUES('".$result[0]['splan_id']."','".$plan['step'][$i]."','".$plan['start'][$i]."','".$plan['stop'][$i]."',0)";
			in_up_delSql($sql);
			//echo $sql.'
			//';
		}
		
		$all_data =  count($plan2['step']);
		for($i=28;$i<$all_data;$i++){
			$sql = "INSERT INTO tb_plan(plan_id, plan_step, plan_start, plan_stop, plan_petition) VALUES('".$result[0]['splan_id']."','".$plan2['step'][$i]."','".$plan2['start'][$i]."','".$plan2['stop'][$i]."',1)";
			in_up_delSql($sql);
			//echo $sql.'
			//';
		}
		
		
		
		$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES('".$_SESSION['intra_czid']."','add','plan','".$result[0]['splan_id']."')";
		in_up_delSql($sql);
		echo "เพิ่มข้อมูลสำเร็จ";
		
		
		
		
		//$plan = json_decode($_POST['plan'] ,true);
		//echo $plan;
		//print_r($plan['step']);
	}
	elseif($_POST['event']=='select-cp-input(est)'){
		$aff = ($_POST['aff']>=1 AND $_POST['aff']<=4)?" AND tb2.pvn_affiliation = ".$_POST['aff']:"";
		
		$sql = "
		SELECt * 
		FROM tb_cityplan as tb1 
		LEFT JOIN ref_province as tb2 ON tb1.cp_province = tb2.pvn_id
		WHERE  tb1.cp_status = 2 AND (tb1.cp_stage =2 OR tb1.cp_stage = 4) AND tb1.cp_name LIKE '%".$_POST['cp_name']."%'".$aff;
		$result = selectSql($sql);
		$all_data = count($result);
		$i=0;
		if($all_data>0){
			foreach($result as $row){
				$i++;
				?><a class="sub-select-cp-input" onclick="sub_select_cp('<?= $row['cp_name']?>')"><?= $i.') '.$row["cp_name"];?></a><?php
			}
			?><a class="sub-select-cp-input" onclick="$('#sub_select_cp').hide()">ปิด</a><?php
		}
		else{
			?><div style="width:100;display: flex;justify-content: space-between;flex-direction: row;">
				<a style="color:red;margin:0px 10px;padding:4px 0px">ไม่พบชื่อผังที่ตรงกัน</a>
				<a style="margin:0px 10px;padding:4px 0px" onclick="$('#sub_select_cp').hide()">ปิด</a><?php
		}
	}
	elseif($_POST['event']=='check-est_name'){
		$sql = "SELECT cp_no FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		$result = selectSql($sql);
		$cp_no = $result[0]['cp_no'];
		
		$sql = "SELECt est_edit FROM tb_cityplan_est WHERE est_cp = '".$cp_no."' ORDER BY est_edit DESC LIMIT 1";
		$result = selectSql($sql);
		
		echo $result[0]['est_edit']+1;
	}
	elseif($_POST['event']=='select-cp-input(m35)'){
		//$sql = "SELECt * FROM tb_cityplan WHERE cp_status = 2 AND cp_name LIKE '%".$_POST['cp_name']."%'";
		//$sql = "SELECt * FROM tb_cityplan as tb1 WHERE  tb1.cp_status = 2 AND tb1.cp_name LIKE '%".$_POST['cp_name']."%' AND tb1.cp_owner = 2 ";
		$aff = ($_POST['aff']>=1 AND $_POST['aff']<=4)?" AND tb2.pvn_affiliation = ".$_POST['aff']:"";
		
		$sql = "
		SELECt * 
		FROM tb_cityplan as tb1 
		LEFT JOIN ref_province as tb2 ON tb1.cp_province = tb2.pvn_id
		WHERE  tb1.cp_status = 2 AND tb1.cp_stage = 3 AND tb1.cp_name LIKE '%".$_POST['cp_name']."%'".$aff;
		$result = selectSql($sql);
		$all_data = count($result);
		$i=0;
		if($all_data>0){
			foreach($result as $row){
				$i++;
				?><a class="sub-select-cp-input" onclick="sub_select_cp('<?= $row['cp_name']?>')"><?= $i.') '.$row["cp_name"];?></a><?php
			}
			?><a class="sub-select-cp-input" onclick="$('#sub_select_cp').hide()">ปิด</a><?php
		}
		else{
			?><div style="width:100;display: flex;justify-content: space-between;flex-direction: row;">
				<a style="color:red;margin:0px 10px;padding:4px 0px">ไม่พบชื่อผังที่ตรงกัน</a>
				<a style="margin:0px 10px;padding:4px 0px" onclick="$('#sub_select_cp').hide()">ปิด</a><?php
		}
	}
	elseif($_POST['event']=='check-cp35_name'){
		$sql = "SELECT cp_no FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		$result = selectSql($sql);
		$cp_no = $result[0]['cp_no'];
		
		$sql = "SELECt cp35_name FROM tb_cityplan35 WHERE cp35_no = '".$cp_no."' ORDER BY cp35_name DESC LIMIT 1";
		$result = selectSql($sql);
		
		echo $result[0]['cp35_name']+1;
		
	}
	elseif($_POST['event']=='loadNote_cp35'){
		$sql = "SELECT * FROM tb_cityplan35 WHERE cp35_seq = '".$_POST['cp35_seq']."'";
		$result = selectSql($sql);
		echo $result[0]['cp35_detail'];
	}
	elseif($_POST['event']=='loadNote_est'){
		$sql = "SELECT * FROM tb_cityplan_est WHERE est_seq = '".$_POST['est_seq']."'";
		$result = selectSql($sql);
		echo $result[0]['est_detail'];
	}
	elseif($_POST['event']=='c_m35-new'){
		$sql = "SELECT cp_no FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		$result = selectSql($sql);
		$cp_no = $result[0]['cp_no'];
		$cp35_detail = str_replace(array('"', "'"), ' ', $_POST['cp35_detail']);

		if($cp_no>0){
			$sql = "INSERT INTO tb_cityplan35(cp35_name, cp35_no, cp35_detail) VALUES('".$_POST['cp35_name']."','".$cp_no."', '".$cp35_detail."')";
			in_up_delSql($sql);
			
			$sql = "SELECT cp35_seq FROM tb_cityplan35 WHERE cp35_no = '".$cp_no."' ORDER BY cp35_seq DESC";
			$result = selectSql($sql);
			
			$sql = "UPDATE tb_cityplan35 SET cp35_current = 0 WHERE cp35_no = '".$cp_no."' AND cp35_seq != '".$result[0]['cp35_seq']."'";
			in_up_delSql($sql);

			logs("add","cp35",$result[0]['cp35_seq']);

			
			$sql = "INSERT INTO ref_cityplan_step_detail_m35(csd35_cp) VALUES('".$result[0]['cp35_seq']."')";
			in_up_delSql($sql);
			
			$sql = "SELECT csd35_seq FROM ref_cityplan_step_detail_m35 WHERE csd35_cp = '".$result[0]['cp35_seq']."' ORDER BY csd35_seq DESC LIMIT 1 ";
			$csd_seq = selectSql($sql);
			
			
			$sql = "UPDATE tb_cityplan35 SET cp35_csd = '".$csd_seq[0]['csd35_seq']."' WHERE cp35_seq = '".$result[0]['cp35_seq']."'";
			in_up_delSql($sql);
			logs("add","csd35",$csd_seq[0]['csd35_seq']);
			//echo $sql;
			
			
			
			
			/*
			$sql = "INSERT INTO ref_cityplan_step_detail_m35(csd35_cp) VALUES(".$cp_no.")";
			in_up_delSql($sql);
			
			$sql = "SELECT csd35_seq FROM ref_cityplan_step_detail_m35 WHERE csd35_cp = '".$cp_no."' ORDER BY csd35_seq DESC";
			$result = selectSql($sql);
			logs("add","csd35",$result[0]['csd35_seq']);
			//echo $sql;
			*/
			//echo $sql;
			echo 'เพิ่มข้อมูลสำเร็จ';
		}else{
			echo "กรุณาระบุชื่อผังเมืองให้ถูกต้อง";
		}
	}
	elseif($_POST['event']=='c_est-new'){
		$sql = "SELECT cp_no FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		$result = selectSql($sql);
		$est_cp = $result[0]['cp_no'];
		$est_detail = str_replace(array('"', "'"), ' ', $_POST['est_detail']);

		if($est_cp>0){
			$sql = "INSERT INTO tb_cityplan_est(est_edit, est_cp, est_detail) VALUES('".$_POST['est_edit']."','".$est_cp."', '".$est_detail."')";
			in_up_delSql($sql);
			
			$sql = "SELECT est_seq FROM tb_cityplan_est WHERE est_cp = '".$est_cp."' ORDER BY est_seq DESC";
			$result = selectSql($sql);
			
			$sql = "UPDATE tb_cityplan_est SET est_current = 0 WHERE est_cp = '".$est_cp."' AND est_seq != '".$result[0]['est_seq']."'";
			in_up_delSql($sql);

			//logs("add","cp_est",$result[0]['est_seq']);

			
			$sql = "INSERT INTO ref_cityplan_step_detail_est(csd_est_cp) VALUES('".$result[0]['est_seq']."')";
			in_up_delSql($sql);
			
			$sql = "SELECT csd_est_seq FROM ref_cityplan_step_detail_est WHERE csd_est_cp = '".$result[0]['est_seq']."' ORDER BY csd_est_seq DESC LIMIT 1 ";
			$csd_seq = selectSql($sql);
			
			
			$sql = "UPDATE tb_cityplan_est SET est_csd = '".$csd_seq[0]['csd_est_seq']."' WHERE est_seq = '".$result[0]['est_seq']."'";
			in_up_delSql($sql);
			//logs("add","csd_est",$csd_seq[0]['csd35_seq']);
			//echo $sql;
			echo 'เพิ่มข้อมูลสำเร็จ';
		}else{
			echo " กรุณาระบุชื่อผังเมืองให้ถูกต้อง";
		}
	}
	elseif($_POST['event']=='c_m35'){
		$sql = "SELECT cp_no FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		$result = selectSql($sql);
		$cp_no = $result[0]['cp_no'];
		
		$sql = "INSERT INTO ref_cityplan_step_detail_m35(csd35_cp) VALUES(".$cp_no.")";
		in_up_delSql($sql);
		
		$sql = "SELECT csd35_seq FROM ref_cityplan_step_detail_m35 WHERE csd35_cp = '".$cp_no."' ORDER BY csd35_seq DESC";
		$result = selectSql($sql);
		logs("add","m35",$result[0]['csd35_seq']);
		//echo $sql;
		echo 'เพิ่มข้อมูลสำเร็จ';
	}
	elseif($_POST['event']=='CheckEstimate'){
		$sql = "SELECT cp_no FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		$result = selectSql($sql);
		$cp_no = $result[0]['cp_no'];
		if($cp_no>0){
			$sql = "SELECT CASE WHEN SUM(CASE WHEN est_edit = '".$_POST['cp_estimate']."' THEN 1 ELSE 0 END) > 0 THEN CONCAT('มีการประเมินครั้งที่ ', '".$_POST['cp_estimate']."', ' อยู่แล้ว') ELSE 'true' END AS checker FROM tb_cityplan_est WHERE est_cp = '".$cp_no."' GROUP BY est_cp";
			$result = selectSql($sql);
			//echo $sql;
			if($result[0]['checker']=='true'){
				echo '';
			}
			else{
				echo $result[0]['checker'];
			}
		}else{
			echo "ชื่อผังเมืองไม่ถูกต้อง";
		}
	}
	elseif($_POST['event']=='checkEditM35'){
		$sql = "SELECT cp_no FROM tb_cityplan WHERE cp_name LIKE '".$_POST['cp_name']."'";
		$result = selectSql($sql);
		$cp_no = $result[0]['cp_no'];
		if($cp_no>0){
			$sql = "SELECT CASE WHEN SUM(CASE WHEN cp35_name = '".$_POST['cp_edit']."' THEN 1 ELSE 0 END) > 0 THEN CONCAT('มีการแก้ไขครั้งที่ ', '".$_POST['cp_edit']."', ' อยู่แล้ว') ELSE 'true' END AS checker FROM tb_cityplan35 WHERE cp35_no = '".$cp_no."' GROUP BY cp35_no";
			$result = selectSql($sql);
			//echo $sql;
			if($result[0]['checker']=='true'){
				echo '';
			}
			else{
				echo $result[0]['checker'];
			}
		}else{
			echo "ชื่อผังเมืองไม่ถูกต้อง";
		}
	}
	elseif($_POST['event']=='c_m35a'){
		$_POST['csd35_snd_start'] = strlen($_POST['csd35_snd_start'])<10 ? "NULL" : "'".$_POST['csd35_snd_start']."'";
		$_POST['csd35_snd_stop'] = strlen($_POST['csd35_snd_stop'])<10 ? "NULL" : "'".$_POST['csd35_snd_stop']."'";
		$_POST['csd35_plan_fst_start'] = strlen($_POST['csd35_plan_fst_start'])<10 ? "NULL" : "'".$_POST['csd35_plan_fst_start']."'";
		$_POST['csd35_plan_snd_start'] = strlen($_POST['csd35_plan_snd_start'])<10 ? "NULL" : "'".$_POST['csd35_plan_snd_start']."'";
		$_POST['csd35_plan_fst_stop'] = strlen($_POST['csd35_plan_fst_stop'])<10 ? "NULL" : "'".$_POST['csd35_plan_fst_stop']."'";
		$_POST['csd35_plan_snd_stop'] = strlen($_POST['csd35_plan_snd_stop'])<10 ? "NULL" : "'".$_POST['csd35_plan_snd_stop']."'";
		$updeto = ($_POST['csd35_step_fst']==0)? 0:1;
		
		if($_POST['csd35_step_fst']<1){
			$_POST['csd35_snd_start'] = $_POST['csd35_snd_stop'] = $_POST['csd35_petition'] = $updeto = 0;
			$_POST['csd35_snd_start'] = $_POST['csd35_snd_stop'] = $_POST['csd35_plan_fst_start'] = $_POST['csd35_plan_fst_stop'] = $_POST['csd35_plan_snd_start'] = $_POST['csd35_plan_snd_stop'] = "NULL";
		}
		
		$sql = "INSERT INTO ref_cityplan_step_detail_m35(csd35_cp, csd35_step_fst, csd35_step_snd, csd35_snd_start, csd35_snd_stop, csd35_petition, csd35_update, csd35_plan_fst_start, csd35_plan_fst_stop, csd35_plan_snd_start, csd35_plan_snd_stop) VALUES(
			".$_POST['csd35_cp'].",".$_POST['csd35_step_fst'].",".$_POST['csd35_step_snd'].",
			".$_POST['csd35_snd_start'].",".$_POST['csd35_snd_stop'].",".$_POST['csd35_petition'].",".$updeto.",
			".$_POST['csd35_plan_fst_start'].",".$_POST['csd35_plan_fst_stop'].",".$_POST['csd35_plan_snd_start'].",".$_POST['csd35_plan_snd_stop']."
		)";
		//echo $sql;
		in_up_delSql($sql);
				
		$sql = "SELECt csd35_seq FROM ref_cityplan_step_detail_m35 WHERE csd35_cp = '".$_POST['csd35_cp']."' ORDER BY csd35_seq DESC LIMIT 1";
		$csd_seq = selectSql($sql);
		
		$sql = "UPDATE tb_cityplan35 SET cp35_csd = '".$csd_seq[0]['csd35_seq']."' WHERE cp35_seq = '".$_POST['csd35_cp']."'";
		in_up_delSql($sql);
		
		logs("add","csd35",$csd_seq[0]['csd35_seq']);
		
		echo "เพิ่มข้อมูลสำเร็จ";
	}
	elseif($_POST['event']=='u_m35a'){
		$_POST['csd35_snd_start'] = strlen($_POST['csd35_snd_start'])<10 ? "NULL" : "'".$_POST['csd35_snd_start']."'";
		$_POST['csd35_snd_stop'] = strlen($_POST['csd35_snd_stop'])<10 ? "NULL" : "'".$_POST['csd35_snd_stop']."'";
		$_POST['csd35_plan_fst_start'] = strlen($_POST['csd35_plan_fst_start'])<10 ? "NULL" : "'".$_POST['csd35_plan_fst_start']."'";
		$_POST['csd35_plan_snd_start'] = strlen($_POST['csd35_plan_snd_start'])<10 ? "NULL" : "'".$_POST['csd35_plan_snd_start']."'";
		$_POST['csd35_plan_fst_stop'] = strlen($_POST['csd35_plan_fst_stop'])<10 ? "NULL" : "'".$_POST['csd35_plan_fst_stop']."'";
		$_POST['csd35_plan_snd_stop'] = strlen($_POST['csd35_plan_snd_stop'])<10 ? "NULL" : "'".$_POST['csd35_plan_snd_stop']."'";
		
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET 
			csd35_snd_start = ".$_POST['csd35_snd_start'].",
			csd35_snd_stop = ".$_POST['csd35_snd_stop'].",
			csd35_plan_fst_start = ".$_POST['csd35_plan_fst_start'].",
			csd35_plan_fst_stop = ".$_POST['csd35_plan_fst_stop'].",
			csd35_plan_snd_start = ".$_POST['csd35_plan_snd_start'].",
			csd35_plan_snd_stop = ".$_POST['csd35_plan_snd_stop']."
		WHERE csd35_seq = '".$_POST['csd35_seq']."'";
		in_up_delSql($sql);
		//echo $sql;
		logs("edit","csd35",$_POST['csd35_seq']);
		echo "แก้ไขข้อมูลสำเร็จ";		
	}
	elseif($_POST['event']=='u_m35b'){
		$_POST['csd35_snd_start'] = strlen($_POST['csd35_snd_start'])<10 ? "NULL" : "'".$_POST['csd35_snd_start']."'";
		$_POST['csd35_snd_stop'] = strlen($_POST['csd35_snd_stop'])<10 ? "NULL" : "'".$_POST['csd35_snd_stop']."'";
		$_POST['csd35_plan_fst_start'] = strlen($_POST['csd35_plan_fst_start'])<10 ? "NULL" : "'".$_POST['csd35_plan_fst_start']."'";
		$_POST['csd35_plan_snd_start'] = strlen($_POST['csd35_plan_snd_start'])<10 ? "NULL" : "'".$_POST['csd35_plan_snd_start']."'";
		$_POST['csd35_plan_fst_stop'] = strlen($_POST['csd35_plan_fst_stop'])<10 ? "NULL" : "'".$_POST['csd35_plan_fst_stop']."'";
		$_POST['csd35_plan_snd_stop'] = strlen($_POST['csd35_plan_snd_stop'])<10 ? "NULL" : "'".$_POST['csd35_plan_snd_stop']."'";
		
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET 
			csd35_step_fst = ".$_POST ['csd35_step_fst'].",
			csd35_petition = ".$_POST ['csd35_petition'].",
			csd35_plan_fst_start = ".$_POST['csd35_plan_fst_start'].",
			csd35_plan_fst_stop = ".$_POST['csd35_plan_fst_stop'].",
			csd35_step_snd = ".$_POST ['csd35_step_snd'].",
			csd35_plan_snd_start = ".$_POST['csd35_plan_snd_start'].",
			csd35_plan_snd_stop = ".$_POST['csd35_plan_snd_stop'].",
			csd35_snd_start = ".$_POST['csd35_snd_start'].",
			csd35_snd_stop = ".$_POST['csd35_snd_stop'].",
			csd35_update = 1
		WHERE csd35_seq = '".$_POST['csd35_seq']."'";
		in_up_delSql($sql);
		//echo $sql;
		logs("edit","csd35",$_POST['csd35_seq']);
		echo "แก้ไขข้อมูลสำเร็จ";	
	}
	elseif($_POST['event']=='load-nfM35'){
		$sql = "SELECT * FROM ref_cityplan_step_detail_m35 WHERE csd35_seq = '".$_POST['csd35_seq']."'";
		$result = selectSql($sql);
		?>
		<div class="form-group">
			<label for="select-note-remark">หมวดหมู่หมายเหตุ</label>
			<select  id="select-note-remark" class="form-control" onchange="changeMyhead()">
				<option value="select-myhead">เลือกหมวดหมู่หมายเหตุ</option>
				<?php 
					$sql = "SELECT * FROM ref_myhead WHERE myhead_type = 'm35' AND myhead_id != 33 AND myhead_id > 0 AND  myhead_status = 1 AND (myhead_step = '".$result[0]['csd35_step_fst']."' OR myhead_step = 'all')";
					$result = selectSql($sql);
					foreach($result as $row){ ?>
						<option value="<?php echo $row['myhead_id']; ?>"><?php echo $row['myhead_name'];?></option>
					<?php } ?>
				<option value="33">อื่นๆ ระบุ ......................</option>
			</select>
		</div>
		<div class="form-group">
			<label for="note_remark">ระบุหมายเหตุ</label>
			<textarea onkeyup="" style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark" placeholder="ระบุหมายเหตุ . . . ."></textarea>
		</div>
		<script>
			/*changeText();
			function changeText(){
				$('#editBtn').show();
				if($('#note_remark').val()==""){
					$('#editBtn').attr('disabled',true);
					$('#editBtn').removeAttr('onclick');
				}else{
					$('#editBtn').attr('disabled',false);
					$('#editBtn').attr('onclick','saveNote()');
				}
			}*/
			changeMyhead();
			function changeMyhead(){
				$('#editBtn').show();
				if($('#select-note-remark').val()=="select-myhead"){
					$('#editBtn').attr('disabled',true);
					$('#editBtn').removeAttr('onclick');
				}else{
					$('#editBtn').attr('disabled',false);
					$('#editBtn').attr('onclick','saveNoteM35()');
				}
			}
			</script>
			<?php
	}
	elseif($_POST['event']=='r_noteM35'){
		$json = array();
		$sql = "SELECT cp35_no , cp_owner ,csd35_step_fst, csd35_step_snd FROM tb_cityplan35 as t1 LEFT JOIN ref_cityplan_step_detail_m35 as t2 ON t2.csd35_cp = t1.cp35_seq LEFT JOIN tb_cityplan_main as t3 ON t3.cp_no = t1.cp35_no WHERE t2.csd35_seq = '".$_POST['csd35_seq']."'";
		//$sql = "SELECT * FROM ref_cityplan_step_detail_m35 WHHERE csd35_seq = '".$_POST['csd35_seq']."'";
		$result = selectSql($sql);
		
	
		
		
		$sql = "SELECT m35_fst_name FROM ref_m35_fst_step WHERE m35_fst_id = '".$result[0]['csd35_step_fst']."' AND m35_operate = '".$result[0]['cp_owner']."'";
		$step_fst = selectSql($sql);
		
		$sql = "SELECT m35_snd_name FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$result[0]['csd35_step_fst']."' AND m35_snd_step_sub = '".$result[0]['csd35_step_snd']."' AND m35_operate = '".$result[0]['cp_owner']."'";
		$step_snd = selectSql($sql);
		
		
		
		$fst = $result[0]['csd35_step_fst']>0?"<b>ขั้นตอนหลัก ".$result[0]['csd35_step_fst']." : </b>".$step_fst[0]['m35_fst_name']:"<b>ไม่ระบุขั้นตอนหลัก</b>";
		$snd = ($result[0]['csd35_step_fst']>0 AND $result[0]['csd35_step_snd']>0)?"<b>ขั้นตอนย่อย ".$result[0]['csd35_step_fst'].".".$result[0]['csd35_step_snd']." : </b>".$step_snd[0]['m35_snd_name']:"";
		//$snd = ($result[0]['csd35_step_fst']>0 AND $result[0]['csd35_step_snd']==0)?$snd:"";
		
		
		$json = [
			 'fst' => $fst
			,'snd' => $snd
			,'step_fst' => $step[0]['csd35_step_fst']
			,'step_snd' => $step[0]['csd35_step_snd']
			,'cp_no' => $result[0]['cp35_no']
		];
		
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='c_note35+u_csd35'){
		$sql = "INSERT INTO tb_note35(note_step_fst, note_step_snd, note_remark, note_uper, note_cp35, note_csd35, note_mh) VALUES(
			'".$_POST['note_step']."',
			'".$_POST['note_step_1']."',
			'".$_POST['note_remark']."',
			'".$_SESSION['intra_czid']."',
			'".$_POST['note_cp']."',
			'".$_POST['note_csd']."',
			'".$_POST['note_mh']."'
		)";
		in_up_delSql($sql);
		
		$sql = "SELECT note_id FROM tb_note35 WHERE note_csd35 = '".$_POST['note_csd']."'";
		$note_id = selectSql($sql);
		logs("add","note35",$note_id[0]['note_id']);
		
		
		$sql = "SELECT COUNT(*) as note FROM tb_note35 WHERE note_csd35 = '".$_POST['note_csd']."' AND note_status = 1";
		$result = selectSql($sql);
		
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET csd35_note = '".$result[0]['note']."' WHERE csd35_seq = '".$_POST['note_csd']."'";
		in_up_delSql($sql);
		
		echo "เพิ่มหมายเหตุสำเร็จ";
		//echo $sql;
	}
	elseif($_POST['event']=='SetTimeFstPlan35'){
		if($_POST['fst'] >= 1 AND $_POST['fst']<=8){
			unset($start);
			unset($stop);
			$petition = $_POST['petition']==0?"m35_fst_time1*7":"m35_fst_time2*7";
			$sql = "SELECT CASE WHEN m35_fst_id != 6 THEN m35_fst_time*7 WHEN m35_fst_id = 6 THEN ".$petition." END as fst_time FROM ref_m35_fst_step WHERE m35_fst_id = '".$_POST['fst']."' AND m35_operate = '".$_POST['operator']."'";
			$result = selectSql($sql);
			$start = (substr($_POST['start'],0,4)-543).substr($_POST['start'],4,6);
			$start = date_create($start);
			$stop = date_add($start,date_interval_create_from_date_string(($result[0]['fst_time']-1)." days"));
			$stop = date_format($stop,'Y-m-d');
			echo (substr($stop,0,4)+543).substr($stop,4,6);
		}
		else{
			echo '';
		}
		
		//echo $sql;
	}
	elseif($_POST['event']=='SetTimeSndPlan35'){
		if($_POST['fst'] >= 1 AND $_POST['fst']<=8){
			if($_POST['snd']>0){
				unset($start);
				unset($stop);
				$petition = $_POST['petition']==0?"m35_snd_time1*7":"m35_snd_time2*7";
				$sql = "SELECT CASE WHEN m35_snd_step_main != 6 THEN m35_snd_time*7 WHEN m35_snd_step_main = 6 THEN ".$petition." END as fst_time FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$_POST['fst']."' AND m35_snd_step_sub = '".$_POST['snd']."' AND m35_operate = '".$_POST['operator']."'";
				$result = selectSql($sql);
				$start = (substr($_POST['start'],0,4)-543).substr($_POST['start'],4,6);
				$start = date_create($start);
				$stop = date_add($start,date_interval_create_from_date_string(($result[0]['fst_time']-1)." days"));
				$stop = date_format($stop,'Y-m-d');
				echo (substr($stop,0,4)+543).substr($stop,4,6);
			}
			else{
				echo '';
			}
		}
		else{
			echo '';
		}
	}
	elseif($_POST['event']=='load_csd35_noteM35'){
		$sql = "SELECT * FROM tb_note35 WHERE note_status = 1 AND note_csd35 = '".$_POST['csd35_seq']."'";
		$result = selectSql($sql);
		$x = 0;
		foreach($result as $row){ 
		$datetime = explode(" ",$row['note_timestamp']);
		$x++; ?>
		<div class="form-group">
			<div id="<?php echo 'date_note_'.$x; ?>" class="pull-left"><b>วันที่เพิ่มหมายเหตุ : </b><?=(substr($datetime[0], 8, 2)+0).' '.IntToMonth(substr($datetime[0], 5, 2),'th').' ' .(substr($datetime[0], 0, 4)+543) ?></div>
			<div id="<?php echo 'date_time_'.$x; ?>" class="pull-right"><b> เวลา : </b> <?=$datetime[1]?> น.</div><br>
			<label for="<?php echo 'note_'.$x; ?>"><?php //echo 'หมายเหตุที่ '.$x;?></label>
			<span title="ลบหมายเหตุ" style="margin-right:8px" class="trash pull-right" onclick="delNoteM35(<?=$row['note_id']?>)"><a><i class="fa fa-trash"></i></a></span>
			<?php /* <span title="แก้ไขหมายเหตุ" style="margin-right:8px" class="gear pull-right" onclick="Editnote()"><a><i class="fa fa-gear"></i></a></span>*/ ?>
			<div class="div_note" id="<?php echo 'div_note_'.$x; ?>"><?php echo $row['note_remark']?></div>
			<?php /*<input class="form-control input_note" id="<?php echo 'note_'.$x; ?>"value="<?php echo $row['note_remark']?>"> */ ?>
		</div>
		<?php }
	}
	elseif($_POST['event']=='d_note35+u_csd35'){
		$sql = "UPDATE tb_note35 SET note_status = 0 WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		
		$sql = "SELECT COUNT(*) as note FROM tb_note35 WHERE note_csd35 = '".$_POST['csd35_seq']."' AND note_status = 1";
		$result = selectSql($sql);
		
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET csd35_note = '".$result[0]['note']."' WHERE csd35_seq = '".$_POST['csd35_seq']."'";
		in_up_delSql($sql);
		
		logs("del","note35",$_POST['note_id']);
		
		echo "ลบหมายเหตุสำเร็จ";
	}	
	elseif($_POST['event']=='c_m35x'){
		$_POST['csd35_start'] = strlen($_POST['csd35_start'])<10 ? "NULL" : "'".$_POST['csd35_start']."'";
		$_POST['csd35_stop'] = strlen($_POST['csd35_stop'])<10 ? "NULL" : "'".$_POST['csd35_stop']."'";
		$_POST['csd35_start_2'] = strlen($_POST['csd35_start_2'])<10 ? "NULL" : "'".$_POST['csd35_start_2']."'";
		$_POST['csd35_stop_2'] = strlen($_POST['csd35_stop_2'])<10 ? "NULL" : "'".$_POST['csd35_stop_2']."'";
		$updeto = ($_POST['csd35_step_fst']==0)? 0:1;
		
		$sql = "INSERT INTO ref_cityplan_step_detail_m35(csd35_cp,csd35_step_fst,csd35_step_snd,csd35_step_trd,csd35_start,csd35_stop,csd35_start_2,csd35_stop_2,csd35_petition,csd35_update) VALUES(
		".$_POST['csd35_cp'].",
		".$_POST['csd35_step_fst'].",
		".$_POST['csd35_step_snd'].",
		".$_POST['csd35_step_trd'].",
		".$_POST['csd35_start'].",
		".$_POST['csd35_stop'].",
		".$_POST['csd35_start_2'].",
		".$_POST['csd35_stop_2'].",
		".$_POST['csd35_petition'].",
		".$updeto.")";
		in_up_delSql($sql);
		
		$sql = "SELECT csd35_seq FROM ref_cityplan_step_detail_m35 WHERE csd35_cp = '".$_POST['csd35_cp']."' ORDER BY csd35_seq DESC";
		$result = selectSql($sql);
		logs("add","m35",$result[0]['csd35_seq']);
		//echo $sql;
		echo 'เพิ่มข้อมูลสำเร็จ';
	}
	elseif($_POST['event']=='d_m35x'){
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET csd35_status = 0 WHERE csd35_seq = '".$_POST['csd35_seq']."'";
		in_up_delSql($sql);
		//echo $sql;
		echo "ลบขั้นตอนสำเร็จ";
	}
	elseif($_POST['event']=='r_stepM35A'){
		$sql = "SELECT * FROM ref_cityplan_step_detail_m35 WHERE csd35_seq = ".$_POST['csd35_seq'];
		$result = selectSql($sql);
		//$json = $sql;
		$json = array();
		foreach($result as $row){
			$json = [
				'csd35_seq'=>$row['csd35_seq'],
				'csd35_step_fst'=>$row['csd35_step_fst'],
				'csd35_petition'=>$row['csd35_petition'],
				'csd35_plan_fst_start'=>$row['csd35_plan_fst_start'],
				'csd35_plan_fst_stop'=>$row['csd35_plan_fst_stop'],
				'csd35_step_snd'=>$row['csd35_step_snd'],
				'csd35_snd_start'=>$row['csd35_snd_start'],
				'csd35_snd_stop'=>$row['csd35_snd_stop'],
				'csd35_plan_snd_start'=>$row['csd35_plan_snd_start'],
				'csd35_plan_snd_stop'=>$row['csd35_plan_snd_stop'],
				
			
			];
		}
		
		
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='r_stepM35'){
		$sql = "SELECT * FROM ref_cityplan_step_detail_m35 WHERE csd35_seq = ".$_POST['csd35_seq'];
		$result = selectSql($sql);
		//$json = $sql;
		$json = array();
		foreach($result as $row){
			$json = [
				'csd35_seq'=>$row['csd35_seq'],
				'csd35_cp'=>$row['csd35_cp'],
				'csd35_step_fst'=>$row['csd35_step_fst'],
				'csd35_step_snd'=>$row['csd35_step_snd'],
				'csd35_step_trd'=>$row['csd35_step_trd'],
				'csd35_start'=>$row['csd35_start'],
				'csd35_stop'=>$row['csd35_stop'],
				'csd35_start_2'=>$row['csd35_start_2'],
				'csd35_stop_2'=>$row['csd35_stop_2'],
				'csd35_petition'=>$row['csd35_petition']
			
			];
		}
		
		
		echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	elseif($_POST['event']=='load_snd_stepM35'){
		$sql  = "SELECT * FROM ref_m35_snd_step WHERE m35_snd_step_main = ".$_POST['csd35_step_fst'];
		//echo $sql;
		$result = selectSql($sql);
		?><option value="0">ไม่ระบุขั้นตอน</option><?php
		foreach($result as $row){ ?>
			<option value="<?=$row['m35_snd_step_sub']?>">ขั้นตอนที่ <?=$row['m35_snd_step_main']?>.<?=$row['m35_snd_step_sub']?> <?=$row['m35_snd_name']?></option><?php
		}
	}
	elseif($_POST['event']=='load_trd_stepM35'){
		$sql  = "SELECT * FROM ref_m35_trd_step WHERE m35_trd_step_fst = ".$_POST['csd35_step_fst']." AND m35_trd_step_snd = ".$_POST['csd35_step_snd'];
		//echo $sql;
		$result = selectSql($sql);
		?><option value="0">ไม่ระบุขั้นตอน</option><?php
		foreach($result as $row){ ?>
			<option value="<?=$row['m35_trd_step_trd']?>">ขั้นตอนที่ <?=$row['m35_trd_step_fst']?>.<?=$row['m35_trd_step_snd']?>.<?=$row['m35_trd_step_trd']?> <?=$row['m35_trd_name']?></option><?php
		}
	}
	elseif($_POST['event']=='load_snd_stepM35X'){
		if($_POST['csd35_step_fst']==6){
			if($_POST['petition']==0){
				$sql  = "SELECT * FROM ref_m35_snd_step WHERE m35_snd_step_main = '6' AND m35_operate = '".$_POST['operator']."' AND m35_snd_time1 > 0";
			}
			else{
				$sql  = "SELECT * FROM ref_m35_snd_step WHERE m35_snd_step_main = '6' AND m35_operate = '".$_POST['operator']."' AND m35_snd_time2 > 0";
			}
			$result = selectSql($sql);
			?><option value="0">ไม่ระบุขั้นตอน</option><?php
			foreach($result as $row){ ?>
				<option value="<?=$row['m35_snd_step_sub']?>">ขั้นตอนที่ <?=$row['m35_snd_step_main']?>.<?=$row['m35_snd_step_sub']?> <?=$row['m35_snd_name']?></option><?php	
			}
		}
		else{
			$sql  = "SELECT * FROM ref_m35_snd_step WHERE m35_snd_step_main = ".$_POST['csd35_step_fst']." AND m35_operate = '".$_POST['operator']."'";
			$result = selectSql($sql);
			if(count($result)>0){
				?><option value="0">ไม่ระบุขั้นตอน</option><?php
				foreach($result as $row){ ?>
					<option value="<?=$row['m35_snd_step_sub']?>">ขั้นตอนที่ <?=$row['m35_snd_step_main']?>.<?=$row['m35_snd_step_sub']?> <?=$row['m35_snd_name']?></option><?php
				}
			}
			else{
				?><option value="0">ไม่มีขั้นตอน</option><?php	
			}
		}
	}
	elseif($_POST['event']=='load_trd_stepM35X'){
		/*
		$sql  = "SELECT * FROM ref_m35_trd_step WHERE m35_trd_step_fst = ".$_POST['csd35_step_fst']." AND m35_trd_step_snd = ".$_POST['csd35_step_snd'];
		$result = selectSql($sql);
		if(count($result)>0){
			?><option value="0">ไม่ระบุขั้นตอน</option><?php
			foreach($result as $row){ ?>
						<option value="<?=$row['m35_trd_step_trd']?>">ขั้นตอนที่ <?=$row['m35_trd_step_fst']?>.<?=$row['m35_trd_step_snd']?>.<?=$row['m35_trd_step_trd']?> <?=$row['m35_trd_name']?></option><?php
			}
			?>
			<script>
				$('#div-step-2').show();
			</script>
			<?php
		}
		else{
			?>
			<option value="0">ไม่มีขั้นตอน</option>
			<script>
				$('#div-step-2').hide();
			</script>
			<?php
			
		}
		
		*/

	}
	elseif($_POST['event']=='u_csd35-step_date'){
		$_POST['csd35_start'] = strlen($_POST['csd35_start'])<10 ? "NULL" : "'".$_POST['csd35_start']."'";
		$_POST['csd35_stop'] = strlen($_POST['csd35_stop'])<10 ? "NULL" : "'".$_POST['csd35_stop']."'";
		$_POST['csd35_start_2'] = strlen($_POST['csd35_start_2'])<10 ? "NULL" : "'".$_POST['csd35_start_2']."'";
		$_POST['csd35_stop_2'] = strlen($_POST['csd35_stop_2'])<10 ? "NULL" : "'".$_POST['csd35_stop_2']."'";
		//if(strlen($_POST['csd35_stop'])<10) $_POST['csd35_stop'] = "NULL";
		/*
		$sql = "
			UPDATE ref_cityplan_step_detail_m35 SET 
			csd35_step_fst = '".$_POST['csd35_step_fst']."',
			csd35_petition = '".$_POST['csd35_petition']."',
			csd35_step_snd = '".$_POST['csd35_step_snd']."',
			csd35_start = ".$_POST['csd35_start'].",
			csd35_stop = ".$_POST['csd35_stop'].",
			csd35_step_trd = '".$_POST['csd35_step_trd']."',
			csd35_start_2 = ".$_POST['csd35_start_2'].",
			csd35_stop_2 = ".$_POST['csd35_stop_2'].",
			csd35_update = 1
			WHERE csd35_seq = ".$_POST['csd35_seq'];
		*/
		$sql = "
			UPDATE ref_cityplan_step_detail_m35 SET 
			csd35_step_fst = '".$_POST['csd35_step_fst']."',
			csd35_petition = '".$_POST['csd35_petition']."',
			csd35_step_snd = '".$_POST['csd35_step_snd']."',
			csd35_start = ".$_POST['csd35_start'].",
			csd35_stop = ".$_POST['csd35_stop'].",
			csd35_update = 1
			WHERE csd35_seq = ".$_POST['csd35_seq'];
		echo $sql;
		in_up_delSql($sql);
		//logs("edit","m35",$_POST['csd35_seq']);
		echo 'แก้ไขขั้นตอนสำเร็จ';
		//echo $sql;
	}
	elseif($_POST['event']=='u_csd35-date_only'){
		$_POST['csd35_start'] = strlen($_POST['csd35_start'])<10 ? "NULL" : "'".$_POST['csd35_start']."'";
		$_POST['csd35_stop'] = strlen($_POST['csd35_stop'])<10 ? "NULL" : "'".$_POST['csd35_stop']."'";
		$_POST['csd35_start_2'] = strlen($_POST['csd35_start_2'])<10 ? "NULL" : "'".$_POST['csd35_start_2']."'";
		$_POST['csd35_stop_2'] = strlen($_POST['csd35_stop_2'])<10 ? "NULL" : "'".$_POST['csd35_stop_2']."'";
		if($_POST['csd35_seq']>0){
			$sql = "
				UPDATE ref_cityplan_step_detail_m35 SET 
				csd35_petition = '".$_POST['csd35_petition']."',
				csd35_start = ".$_POST['csd35_start'].",
				csd35_stop = ".$_POST['csd35_stop'].",
				csd35_start_2 = ".$_POST['csd35_start_2'].",
				csd35_stop_2 = ".$_POST['csd35_stop_2']."
				WHERE csd35_seq = ".$_POST['csd35_seq'];
		
			//echo $sql;
			in_up_delSql($sql);echo 'แก้ไขขั้นตอนสำเร็จ';
			logs("edit","m35",$_POST['csd35_seq']);
		}else{
			echo 'Error!';
		}
	}
	elseif($_POST['event']=='ru-c_csd35_note'){
			$fst = '';
			$snd = '';
			$trd = '';
			if($_POST['step_fst']>0){
				$sql = "SELECT * FROM ref_m35_fst_step WHERE m35_fst_id = '".$_POST['step_fst']."'"; 
				$fst_result = selectSql($sql);
				$fst = '<b>ขั้นตอนหลัก '.$fst_result[0]["m35_fst_id"]. ' : </b>'.$fst_result[0]["m35_fst_name"];
				if($_POST['step_snd']>0){
					$sql = "SELECT * FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$_POST['step_fst']."' AND m35_snd_step_sub = '".$_POST['step_snd']."'"; 
					$snd_result = selectSql($sql);
					$snd = '<b>ขั้นตอนรอง '.$snd_result[0]["m35_snd_step_main"]. '.'.$snd_result[0]["m35_snd_step_sub"].' : </b>'.$snd_result[0]["m35_snd_name"];
					if($_POST['step_trd']>0){
						$sql  ="SELECT * FROM ref_m35_trd_step WHERE m35_trd_step_fst = '".$_POST['step_fst']."' AND m35_trd_step_snd = '".$_POST['step_snd']."' AND m35_trd_step_trd = '".$_POST['step_trd']."'";
						$trd_result = selectSql($sql);
						$trd = '<b>ขั้นตอนย่อย '.$trd_result[0]['m35_trd_step_fst'].'.'.$trd_result[0]['m35_trd_step_snd'].'.'.$trd_result[0]['m35_trd_step_trd'].' </b> : '.$trd_result[0]['m35_trd_name'];
					}	
				}
			}else{
				$fst = '<b>ไม่มีการดำเนินการ</b>';
			}

		echo '<div class="form-group">
				<label for="note_remark">ระบุหมายเหตุ</label>
				<textarea onkeyup="" style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark" placeholder="ระบุหมายเหตุ . . . ."></textarea>
			</div>';
		?>
		<script>
			$('#note_step').html('<?=$fst?>');
			$('#note_step_1').html('<?=$snd?>');
			$('#note_step_2').html('<?=$trd?>');
			//$('').html();
		</script>
		<?php
	}
	elseif($_POST['event']=='c_note+u_csd35'){
		$sql = "INSERT INTO tb_note (note_step, note_step1, note_step2, note_remark, note_uper, note_cp, note_m35) VALUES('".$_POST['note_step']."','".$_POST['note_step_1']."','".$_POST['note_step_2']."','".$_POST['note_remark']."','".$_SESSION['intra_czid']."','".$_POST['note_cp']."','".$_POST['note_m35']."')";
		in_up_delSql($sql);
		
		$sql  = "SELECT COUNT(*) FROM tb_note WHERE note_m35 = '".$_POST['note_m35']."' AND note_status = 1";
		$result  = selectSql($sql);
		//print_r($result[0]);
		
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET csd35_note = '".$result[0]['COUNT(*)']."' WHERE csd35_seq = '".$_POST['note_m35']."'";
		in_up_delSql($sql);
		

		$sql  = "SELECT note_id FROM tb_note WHERE note_m35 = '".$_POST['note_m35']."' AND note_cp = '".$_POST['note_cp']."' AND note_uper = '".$_SESSION['intra_czid']."' AND note_step = '".$_POST['note_step']."' AND note_step1 = '".$_POST['note_step_1']."' AND note_step2 = '".$_POST['note_step_2']."' ORDER BY note_id DESC LIMIT 1"; 
		$result = selectSql($sql);
		logs("add","note",$result[0]['note_id']);
		echo 'เพิ่มหมายเหตุสำเร็จ';
	}
	elseif($_POST['event']=='ru_csd35_note'){
		$sql = "SELECT * FROM tb_note WHERE note_m35 =  '".$_POST['csd_seq']."' AND note_cp  = '".$_POST['cp_no']."' AND note_status != 0 ";
		//echo $sql;
		$result = selectSql($sql);
		$x = 0;
		foreach($result as $row){
			$x++; 
			$years = substr($row['note_timestamp'],0,4)+543;
			$months = substr($row['note_timestamp'],5,2);
			$days = substr($row['note_timestamp'],8,2);
			//$daten = '<b>วันที่เพิ่มหมายเหตุ : </b>'.$row['note_timestamp'];
			if($months==1){$months = "ม.ค.";}
			elseif($months==2){$months = "ก.พ.";}
			elseif($months==3){	$months = "มี.ค.";	}
			elseif($months==4){	$months = "เม.ย.";	}
			elseif($months==5){	$months = "พ.ค.";	}
			elseif($months==6){	$months = "มิ.ย.";	}
			elseif($months==7){	$months = "ก.ค.";	}
			elseif($months==8){	$months = "ส.ค.";	}
			elseif($months==9){	$months = "ก.ย.";	}
			elseif($months==10){$months = "ต.ค.";	}
			elseif($months==11){$months = "พ.ย.";	}
			else{$months = "ธ.ค.";}
			$times = substr($row['note_timestamp'],11,8);
			$daten = "<b>วันที่เพิ่มหมายเหตุ : </b>".$days." ".$months." ".$years;
			$timen = '<b> เวลา : </b>'.$times." น.";
			if($x>1){
				echo '<hr style="border-color:#989899">';
			} ?>
			<div class="form-group">
				<div id="<?php echo 'date_note_'.$x; ?>" class="pull-left"></div>
				<div id="<?php echo 'date_time_'.$x; ?>" class="pull-right"></div><br>
				<label for="<?php echo 'note_'.$x; ?>"><?php //echo 'หมายเหตุที่ '.$x;?></label>
			<?php
/*				<span id="btnTrash" title="แก้ไขหมายเหตุ2" style="margin-right:8px" class="gear pull-right" onclick="editNote(<?php echo $x;?>,<?php echo $row['note_id'];?>,<?php echo $_POST['cp_no'];?>)"><a><i class="fa fa-gear"></i></a></span>*/
			if($_SESSION['status']==1){	?>
				<span id="btnGear" title="ลบหมายเหตุ" style="margin-right:8px" class="trash pull-right" onclick="delNote(<?php echo $row['note_id'];?>,<?php echo $_POST['cp_no'];?>)"><a><i class="fa fa-trash"></i></a></span>
			<?php } ?>
				<div class="div_note" id="<?php echo 'div_note_'.$x; ?>"><?php echo $row['note_remark']?></div>
				<input class="form-control input_note" id="<?php echo 'note_'.$x; ?>"value="<?php echo $row['note_remark']?>">
			</div>
			<script>
				$('<?php echo "#note_".$x; ?>').hide();
				$('<?php echo "#date_note_".$x; ?>').html('<?php echo $daten; ?>');
				$('<?php echo "#date_time_".$x; ?>').html('<?php echo $timen; ?>');
				$('#note_step_hidden').val('<?php echo $_POST["step_id"]; ?>');
				$('#note_step_1_hidden').val('<?php echo $_POST["sub_no"];?>');
				$('#note_step_2_hidden').val('<?php echo $_POST["trd_step"];?>');
				$('#note_cp').val('<?php echo $_POST["cp_no"];?>');
			</script>
			<?php 
		}
		if($_POST['step_id']!=0){
			$sql = "SELECT * FROM ref_m35_fst_step WHERE m35_fst_id = '".$_POST['step_id']."'";
			$result = selectSql($sql);
			$step = '<b>ขั้นตอนหลัก '.$_POST['step_id'].' : </b>'.$result[0]['m35_fst_name'];
			?> 
				<script>
					$('#note_step').html('<?php echo $step; ?>');
				</script>
			<?php
		}
		else{ 	
			?>
				<script>
					$('#note_step').html('<b>ไม่มีการดำเนินการ</b>');					
				</script>
			<?php
		}
		if($_POST['sub_no']!=0){
			$sql = "SELECT * FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$_POST['step_id']."' AND m35_snd_step_sub = '".$_POST['sub_no']."'";
			$result1 = selectSql($sql);
			$step1 = '<b>ขั้นตอนรอง  '.$_POST['m35_snd_step_main'].'.'.$_POST['m35_snd_step_sub'].' : </b>'.$result1[0]['m35_snd_name'];
			?> 
				<script>
					$('#note_step_1').html('<?php echo $step1; ?>');
					$('#note_step_1').show();
				</script>
			<?php
		}
		else{
			?>
				<script>
					$('#note_step_1').html('');
					$('#note_step_1').hide();					
				</script>
			<?php
		}
		if($_POST['trd_step']!=0){
			$sql = "SELECT * FROM ref_up_trd_step WHERE trd_fst = '".$_POST['step_id']."' AND trd_snd = '".$_POST['sub_no']."' AND trd_step = '".$_POST['trd_step']."'";
			$result2 = selectSql($sql);
			$step2 = '<b>ขั้นตอนย่อย  '.$_POST['step_id'].'.'.$_POST['sub_no'].'.'.$_POST['trd_step'].' : </b>'.$result2[0]['trd_name'];
			?> 
				<script>
					$('#note_step_2').html('<?php echo $step2; ?>');
					$('#note_step_2').show();					
				</script>
			<?php
		}
		else{
			?>
				<script>
					$('#note_step_2').html('');
					$('#note_step_2').hide();					
				</script>
			<?php
		}
		
		
		
	}
	elseif($_POST['event']=='d_note+u_csd35'){
		$sql = "UPDATE tb_note SET note_status = 0 WHERE note_id = '".$_POST['note_id']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		$sql = "SELECT COUNT(*) FROM tb_note WHERE note_status != 0 AND note_m35 = '".$_POST['m35_seq']."'";
		$result = selectSql($sql);
		//echo $sql;
		
		$sql = "UPDATE ref_cityplan_step_detail_m35 SET csd35_note = '".$result[0]['COUNT(*)']."' WHERE csd35_seq = '".$_POST['m35_seq']."'";
		in_up_delSql($sql);
		//echo $sql;
		
		
		logs("del","note",$_POST['note_id']);
		//echo $sql;
		
		echo "ลบหมายเหตุสำเร็จ";
	}

	else{
		echo "Error";
	}
  }
  ?>
