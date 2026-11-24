<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
    if ($_POST['event']=='c'){
		/*$byear =  $_POST['disc_year']-543;
		$bmonth = $_POST['disc_month'];
		$bday = $_POST['disc_day'];
		$bdate = $byear.'-'.$bmonth.'-'.$bday;*/
        $sql = "INSERT INTO ref_company(campany_name, campany_phone) VALUES (
                '".$_POST['campany_name']."',
                '".$_POST['campany_phone']."')";

        in_up_delSql($sql);

      

	  $sql = "SELECT * FROM ref_company WHERE  campany_name = '".$_POST['campany_name']."'";
        $result = selectSql($sql);
        foreach ($result as $row){}


        $logs = "INSERT INTO tb_log(log_user_id, log_action, log_type, log_target) VALUES (
			  '".$_POST['user_id']."',
			  'a','cmp','".$row['camp_id']."')";
		    in_up_delSql($logs);

       
		echo "เพิ่มข้อมูล";
    }elseif ($_POST['event']=='d'){
        

        $sql ="DELETE FROM ref_company WHERE company_id = '".$_POST['id']."'";
        in_up_delSql($sql);

        
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."','d','cmp','".$_POST['id']."')";
		  in_up_delSql($logs);

        echo "ลบข้อมูลสำเร็จ";

    }
    elseif($_POST['event']=='ru'){
        $sql = "SELECT * FROM ref_company WHERE company_id = '".$_POST['company_id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
		     'company_id' => $row['company_id'],
             'campany_name' => $row['campany_name'],
             'campany_phone' => $row['campany_phone']
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    elseif($_POST['event']=='u'){
		/*$byear =  $_POST['disc_year']-543;
		$bmonth = $_POST['disc_month'];
		$bday = $_POST['disc_day'];
		$bdate = $byear.'-'.$bmonth.'-'.$bday;*/
        $sql = "UPDATE ref_company SET
            campany_name = '".$_POST['campany_name']."',
            campany_phone = '".$_POST['campany_phone']."'            
        WHERE company_id = '".$_POST['company_id']."'";
        in_up_delSql($sql);
		echo $sql;


        //$disctitle =  "id : ".$_POST['disc_id']."(".$_POST['disc_title'].")";
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."',
			'e','cmp','".$_POST['company_id']."')";
		  in_up_delSql($logs);

        echo "แก้ไขสำเร็จ";
      }

 
  ?>
