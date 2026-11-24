<?php
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
    if ($_POST['event']=='c'){
		if($_POST['eqm_rebuy'] =='-' OR  $_POST['eqm_rebuy'] == NULL){
			echo $_POST['eqm_rebuy'];
			$_POST['eqm_rebuy'] = 9999;
		}else{
			
		}
		/*$byear =  $_POST['disc_year']-543;
		$bmonth = $_POST['disc_month'];
		$bday = $_POST['disc_day'];
		$bdate = $byear.'-'.$bmonth.'-'.$bday;*/
        /*
		$sql = "INSERT INTO tb_equipment(eqm_name, eqm_krp, eqm_serial, eqm_value, eqm_price, eqm_buy, eqm_rebuy, eqm_ma) VALUES (
		'".$_POST['eqm_name']."',
		'".$_POST['eqm_krp']."',
		'".$_POST['eqm_serial']."',
		'".$_POST['eqm_value']."',
		'".$_POST['eqm_price']."',
		'".$_POST['eqm_buy']."',
		'".$_POST['eqm_rebuy']."',
		'".$_POST['eqm_ma']."')";
		*/
		
		
		$sql = "INSERT INTO tb_equipment(eqm_name, eqm_price, eqm_buy, eqm_rebuy, eqm_ma, eqm_guarantee, eqm_gend, eqm_fma, eqm_pro) VALUES (
		'".$_POST['eqm_name']."',		
		'".$_POST['eqm_price']."',
		'".$_POST['eqm_buy']."',
		'".$_POST['eqm_rebuy']."',
		'".$_POST['eqm_ma']."',
		'".$_POST['eqm_guarantee']."',
		'".$_POST['eqm_gend']."',
		'".$_POST['eqm_fma']."',
		'".$_POST['eqm_pro']."')";
		/*$sql = "INSERT INTO tb_equipment(eqm_name, eqm_price, eqm_buy, eqm_rebuy, eqm_ma) VALUES (
		'".$_POST['eqm_name']."',		
		'".$_POST['eqm_price']."',
		'".$_POST['eqm_buy']."',
		'".$_POST['eqm_rebuy']."',
		'".$_POST['eqm_ma']."')";*/
        in_up_delSql($sql);

      
/*
	  $sql = "SELECT * FROM tb_equipment WHERE  eqm_name = '".$_POST['eqm_name']."'";
        $result = selectSql($sql);
        foreach ($result as $row){}


        $logs = "INSERT INTO tb_log(log_user_id, log_action, log_type, log_target) VALUES (
			  '".$_POST['user_id']."',
			  'a','eqm','".$row['eqm_id']."')";
		    in_up_delSql($logs);*/

		//echo $sql;
		echo "เพิ่มข้อมูลสำเร็จ";
    }elseif ($_POST['event']=='d'){
        

        $sql ="DELETE FROM tb_equipment WHERE eqm_id = '".$_POST['id']."'";
        in_up_delSql($sql);

        
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."','d','eqm','".$_POST['id']."')";
		  in_up_delSql($logs);

        echo "ลบข้อมูลสำเร็จ";

    }
    elseif($_POST['event']=='ru'){
        $sql = "SELECT * FROM tb_equipment WHERE eqm_id = '".$_POST['id']."'";
        $result = selectSql($sql);
        $json = array();
        foreach ($result as $row) {
          $json = [
            'eqm_name' => $row['eqm_name'],
            'eqm_krp' => $row['eqm_krp'],
            'eqm_serial' => $row['eqm_serial'],
            'eqm_value' => $row['eqm_value'],
            'eqm_price' => $row['eqm_price'],
			'eqm_buy' => $row['eqm_buy'],
			'eqm_rebuy' => $row['eqm_rebuy'],
			'eqm_ma' => $row['eqm_ma'],
			'eqm_fma' => $row['eqm_fma'],
			'eqm_pro' => $row['eqm_pro'],
			'eqm_guarantee' => $row['eqm_guarantee'],
			'eqm_gend' => $row['eqm_gend'],
			'eqm_id' => $row['eqm_id']
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
        $sql = "UPDATE tb_equipment SET
            eqm_name = '".$_POST['eqm_name']."' ,
            eqm_pro = '".$_POST['eqm_pro']."' , 
            eqm_price = '".$_POST['eqm_price']."',
			eqm_buy = '".$_POST['eqm_buy']."',
			eqm_rebuy = '".$_POST['eqm_rebuy']."',
			eqm_ma = '".$_POST['eqm_ma']."'	,		
			eqm_fma = '".$_POST['eqm_fma']."',
			eqm_gend = '".$_POST['eqm_gend']."'	,
			eqm_guarantee = '".$_POST['eqm_guarantee']."'
        WHERE eqm_id = '".$_POST['eqm_id']."'";
        in_up_delSql($sql);


        //$disctitle =  "id : ".$_POST['disc_id']."(".$_POST['disc_title'].")";
        $logs = "INSERT INTO tb_log(log_user_id,log_action,log_type,log_target) VALUES (
			'".$_SESSION['intra_czid']."',
			'e','eqm','".$_POST['eqm_id']."')";
		  in_up_delSql($logs);

        echo "แก้ไขสำเร็จ";
      }
 
  ?>
