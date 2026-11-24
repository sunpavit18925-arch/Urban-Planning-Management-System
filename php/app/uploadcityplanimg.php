<?php
include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$_SESSION['intra_czid'];
	if(isset($_FILES["cp_img"])){

		foreach($_FILES['cp_img']['tmp_name'] as $key => $val){
			$iplus = $i++;
			$name_file =  $_FILES['cp_img']['name'[$key]];
			$tmp_name =  $_FILES['cp_img']['tmp_name'][$key];
			$temp = explode(".", $_FILES["cp_img"]["name"][$key]);
			$newfilename =  $_POST['cp_no'].'.' . end($temp);
			$catg_id = $_POST['cp_no'];

			$locate_img ='../img/cityplan/';
			move_uploaded_file($tmp_name,$locate_img.$newfilename);
		}
		
		/*
		$sql = "UPDATE ref_category SET catg_ico = 1 WHERE catg_id = '$catg_id'";
		in_up_delSql($sql);

		$logs = "INSERT INTO tb_log(log_user_id,log_action,log_discatg_type,log_discatg_id) VALUES ('".$_SESSION['intra_czid']."','ul','i','".$_POST['cp_no']."')";
		in_up_delSql($logs);
		*/
		
		echo "<SCRIPT LANGUAGE='JavaScript'>";
		echo " alert('อัพโหลดเสร็จ');";
		
		echo "</script>";

		echo "<SCRIPT LANGUAGE='JavaScript'>";
		echo "window.location = '../index.php'";
		//echo "$('#mainshow').load('discuss_manage.php');";
		echo "</script>";

		exit();
	}

?>