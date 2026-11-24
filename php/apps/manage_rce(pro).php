<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
    if ($_POST['event']=='c'){
		/*$byear =  $_POST['disc_year']-543;
		$bmonth = $_POST['disc_month'];
		$bday = $_POST['disc_day'];
		$bdate = $byear.'-'.$bmonth.'-'.$bday;*/
        $sql = "INSERT INTO ref_reportcheck(rce_pro_id, rce_contactor, rce_phone, rce_date, rce_result1, rce_working, rce_note, rce_suggestion) VALUES ('".$_POST['rce_pro_id']."','".$_POST['rce_contactor']."','".$_POST['rce_phone']."','".$_POST['rce_date']."','".$_POST['rce_result1']."','".$_POST['rce_working']."','".$_POST['rce_note']."','".$_POST['rce_suggestion']."')";
		
        in_up_delSql($sql);

      
	
	  $sql = "SELECT * FROM ref_reportcheck WHERE  rce_pro_id = '".$_POST['rce_pro_id']."'";
        $result = selectSql($sql);
        foreach ($result as $row){}


        $logs = "INSERT INTO tb_log(log_user_id, log_action, log_type, log_target) VALUES (
			  '".$_POST['user_id']."',
			  'a','rce','".$row['rce_id']."')";
		    in_up_delSql($logs);

      
		echo "เพิ่มข้อมูล";
    }elseif ($_POST['event']=='d'){
        

        $sql ="DELETE FROM ref_reportcheck WHERE rce_id = '".$_POST['id']."'";
        in_up_delSql($sql);

        
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."','d','rce','".$_POST['id']."')";
		  in_up_delSql($logs);

        echo "ลบข้อมูลสำเร็จ";

    }
    elseif($_POST['event']=='oru'){
        $sql = "SELECT * FROM tb_project, ref_company WHERE pro_company = company_id AND pro_id = '".$_POST['id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
		     'rce_contact' => $row['pro_contact'],
			 'rce_company' => $row['campany_name']
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		
    }
	elseif($_POST['event']=='ru'){
        $sql = "SELECT * FROM ref_reportcheck , tb_project , ref_company WHERE rce_pro_id = pro_id AND pro_company = company_id AND rce_id = '".$_POST['id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
		     'rce_pro_id' => $row['rce_pro_id'],
			 'rce_contactor' => $row['rce_contactor'],
			 'rce_phone' => $row['rce_phone'],
			 'rce_date' => $row['rce_date'],
			 'rce_working' => $row['rce_working'],
			 'rce_result1' => $row['rce_result1'],
			 'rce_note' => $row['rce_note'],
			 'rce_suggestion' => $row['rce_suggestion'],
			 'rce_id' => $row['rce_id'],
			 'rce_contact' => $row['pro_contact'],
			 'rce_company' => $row['campany_name']
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		
		
    }
    elseif($_POST['event']=='u'){
		/*$byear =  $_POST['disc_year']-543;
		$bmonth = $_POST['disc_month'];
		$bday = $_POST['disc_day'];
		$bdate = $byear.'-'.$bmonth.'-'.$bday;*/
        $sql = "UPDATE ref_reportcheck SET
           rce_pro_id = '".$_POST['rce_pro_id']."',
           rce_contactor = '".$_POST['rce_contactor']."',  
	       rce_phone = '".$_POST['rce_phone']."', 			
		   rce_date = '".$_POST['rce_date']."', 
		   rce_working = '".$_POST['rce_working']."',
		   rce_result1 = '".$_POST['rce_result1']."',
		   rce_note = '".$_POST['rce_note']."',
		   rce_suggestion = '".$_POST['rce_suggestion']."'
        WHERE rce_id = '".$_POST['rce_id']."'";
        in_up_delSql($sql);
		


        //$disctitle =  "id : ".$_POST['disc_id']."(".$_POST['disc_title'].")";
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."',
			'e','rce','".$_POST['rce_id']."')";
		  in_up_delSql($logs);

        echo "แก้ไขสำเร็จ";
      }

 
  ?>
