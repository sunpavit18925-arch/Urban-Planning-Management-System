<?php 
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

	$sql = "SELECT pro_test FROM tb_project WHERE pro_id =0";
	$result = selectSql($sql);
	$arramy = explode(',',$result[0]['pro_test']);
	$Carramy = count($arramy);
	
	if($result[0]['pro_test']==null OR $result[0]['pro_test']==""){
		echo "null";
	}
	else{
		for($i=0 ; $i < $Carramy ; $i++ ){
			$sql2 = "SELECT eqm_name FROM tb_equipment WHERE eqm_id = '$arramy[$i]'";
			$result2 = selectSql($sql2);
			echo $i+1,'. ',$result2[0]['eqm_name'],'<br>';
		}
	}



?>