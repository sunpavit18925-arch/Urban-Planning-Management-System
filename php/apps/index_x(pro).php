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
		
		$sql="ALTER TABLE tb_equipment ADD $plan  TINYINT(1) NULL DEFAULT '0' AFTER eqm_guarantee;";
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
	
	elseif ($_POST['event']=='u'){
		$sql = "UPDATE tb_plan SET 
			plan_name = '".$_POST['plan_name']."', 
			plan_year = '".$_POST['plan_year']."',
			plan_start = '".$_POST['plan_start']."',
			plan_end = '".$_POST['plan_end']."',
			plan_note = '".$_POST['plan_note']."'
			WHERE plan_id = '".$_POST['plan_id']."'";
			$result = in_up_delSql($sql);
			
		    $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."','e','plan','".$_POST['plan_id']."')";
		    in_up_delSql($logs);
			
		echo "แก้ไขสำเร็จ";			
	}
	
	elseif ($_POST['event']=='pro_ru'){
		$sql = "SELECT * FROM tb_project,ref_company WHERE pro_company = company_id AND pro_id = '".$_POST['id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
		  $eqm_array = explode(',',$row['pro_test']);
		  $count_ea = count($eqm_array);
		  
		  $pro_test = '';
		  for($i = 0 ; $i < $count_ea ; $i++){
			$ea_sql = "SELECT eqm_name FROM tb_equipment WHERE eqm_id = '$eqm_array[$i]'";
			$result = selectSql($ea_sql);
			if($i == 0 ){
				$pro_test = '- '.$result[0]['eqm_name'];
			}
			else{
				$pro_test = $pro_test.'<br> - '.$result[0]['eqm_name'];
			}
		  }
		  
		  
		  
		  
          $json = [
		     'pro_id' => $row['pro_id'],
			 'pro_name' => '<b>'.$row['pro_name'].'</b>',
			 'pro_year' => '<b>ปีโครงการ  : </b>'.$row['pro_year'],
			 'pro_budget' => '<b>งบประมาณ  : </b>'.number_format($row['pro_budget']),
			 'pro_contact' => '<b>เลขสัญญา : </b>'.$row['pro_contact'],
			 'company_name' => '<b>บริษัท : </b>'.$row['campany_name'],
			 'pro_price' => '<b>ราคา : </b>'.number_format($row['pro_price']),
			 'pro_equipment' => '<b>เพิ่มเติม  </b><br>'.$row['pro_equipment'],
			 'pro_test' => '<b>ระบบ/อุปกรณ์  </b><br>'.$pro_test
			 
            ];
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

    }
	
		
		
		
	
	
	
    

 
  ?>
