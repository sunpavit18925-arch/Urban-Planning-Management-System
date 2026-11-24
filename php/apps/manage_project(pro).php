<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
    if ($_POST['event']=='c'){
        $sql = "INSERT INTO tb_project(pro_name, pro_year, pro_budget, pro_contact, pro_company, pro_price, pro_equipment) VALUES (
                '".$_POST['pro_name']."',
                '".$_POST['pro_year']."',
                '".$_POST['pro_budget']."',
                '".$_POST['pro_contact']."',
                '".$_POST['pro_company']."',
				'".$_POST['pro_price']."',
				'".$_POST['pro_equipment']."')";

        in_up_delSql($sql);

      
/*
	  $sql = "SELECT * FROM tb_project WHERE  pro_name = '".$_POST['pro_name']."'";
        $result = selectSql($sql);
        foreach ($result as $row){}


        $logs = "INSERT INTO tb_log(log_user_id, log_action, log_type, log_target) VALUES (
			  '".$_POST['user_id']."',
			  'a','prj','".$row['pro_id']."')";
		    in_up_delSql($logs);*/

       
		echo "เพิ่มข้อมูลสำเร็จ";
    }elseif ($_POST['event']=='d'){
        

        $sql ="DELETE FROM tb_project WHERE pro_id = '".$_POST['id']."'";
        in_up_delSql($sql);

        
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."','d','prj','".$_POST['id']."')";
		  in_up_delSql($logs);

        echo "ลบข้อมูลสำเร็จ";

    }
    elseif($_POST['event']=='ru'){
        $sql = "SELECT * FROM tb_project WHERE pro_id = '".$_POST['id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
             'pro_name' => $row['pro_name'],
             'pro_year' => $row['pro_year'],
             'pro_budget' => $row['pro_budget'],
             'pro_contact' => $row['pro_contact'],
             'pro_company' => $row['pro_company'],
			'pro_price' => $row['pro_price'],
			'pro_equipment' => $row['pro_equipment'],
			'pro_id' => $row['pro_id']
            ];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
//        echo $result;
    }
    elseif($_POST['event']=='u'){
		/*$byear =  $_POST['disc_year']-543;
		$bmonth = $_POST['disc_month'];
		$bday = $_POST['disc_day'];
		$bdate = $byear.'-'.$bmonth.'-'.$bday;*/
        $sql = "UPDATE tb_project SET
            pro_name = '".$_POST['pro_name']."' ,
            pro_year = '".$_POST['pro_year']."' ,
            pro_budget = '".$_POST['pro_budget']."' ,
            pro_contact = '".$_POST['pro_contact']."' ,
            pro_company = '".$_POST['pro_company']."',
			pro_price = '".$_POST['pro_price']."',
			pro_equipment = '".$_POST['pro_equipment']."'		
        WHERE pro_id = '".$_POST['pro_id']."'";
        in_up_delSql($sql);


        //$disctitle =  "id : ".$_POST['disc_id']."(".$_POST['disc_title'].")";
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."',
			'e','prj','".$_POST['pro_id']."')";
		  in_up_delSql($logs);

        echo "แก้ไขสำเร็จ";
      }
 
  ?>
