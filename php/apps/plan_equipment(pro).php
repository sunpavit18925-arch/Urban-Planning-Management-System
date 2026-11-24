<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
    if ($_POST['event']=='c'){
		/*$byear =  $_POST['disc_year']-543;
		$bmonth = $_POST['disc_month'];
		$bday = $_POST['disc_day'];
		$bdate = $byear.'-'.$bmonth.'-'.$bday;*/
        $sql = "INSERT INTO tb_plan(plan_name, plan_year, plan_start, plan_end, plan_note) VALUES (
		'".$_POST['plan_name']."',
		'".$_POST['plan_year']."',
		'".$_POST['plan_start']."',
		'".$_POST['plan_end']."',
		'".$_POST['plan_note']."')";		
        in_up_delSql($sql);
		
      
	
	  $sql = "SELECT * FROM tb_plan ORDER BY plan_id DESC LIMIT 1";
        $result = selectSql($sql);
        foreach ($result as $row){}


        $logs = "INSERT INTO tb_log(log_user_id, log_action, log_type, log_target) VALUES (
			  '".$_POST['user_id']."',
			  'a','plan','".$row['plan_id']."')";
		    in_up_delSql($logs);      
		echo "เพิ่มข้อมูล";
		$plan = "plan".$row['plan_id'];
		$sql="ALTER TABLE tb_equipment ADD $plan BOOLEAN NULL DEFAULT '0'  AFTER eqm_guarantee;";
		in_up_delSql($sql);
		
    }
	elseif ($_POST['event']=='d'){
        

        $sql ="DELETE FROM tb_plan WHERE plan_id = '".$_POST['id']."'";
        in_up_delSql($sql);

        
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."','d','plan','".$_POST['id']."')";
		in_up_delSql($logs);
		  
		$plans = "plan".$_POST['id'];
		$sql = "ALTER TABLE tb_equipment DROP $plans;";
		in_up_delSql($sql);

        echo "ลบข้อมูลสำเร็จ";

    }
		
	elseif ($_POST['event']=='ru'){
		$sql = "SELECT * FROM tb_plan WHERE plan_id = '".$_POST['id']."'";
        $result = selectSql($sql);
		
        $json = array();
        foreach ($result as $row) {
          $json = [
		     'plan_id' => $row['plan_id'],
			 'plan_name' => $row['plan_name'],
			 'plan_year' => $row['plan_year'],
			 'plan_start' => $row['plan_start'],
			 'plan_end' => $row['plan_end'],
			 'plan_note' => $row['plan_note'] 
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
    }
	
	elseif ($_POST['event']=='seqm'){
		$column_name = $_POST['column_name'];
		$sql = "UPDATE tb_equipment SET $column_name = '".$_POST['eqm_status']."' WHERE eqm_id = '".$_POST['id']."'";
			$result = in_up_delSql($sql);
			
		    /*$logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."','e','plan','".$_POST['id']."')";
		    in_up_delSql($logs);*/
			//echo $sql;

		echo "แก้ไขสำเร็จ";	
		
	}
	 
  ?>
