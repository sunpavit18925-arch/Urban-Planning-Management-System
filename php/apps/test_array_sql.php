<?php 
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

	$sql = "SELECT pro_test FROM tb_project WHERE pro_id = 1";
	$result = selectSql($sql);
	$arramy = explode(',',$result[0]['pro_test']);
	$Carramy = count($arramy);
	echo $Carramy,'<br>';
	for($i=0 ; $i <= $Carramy ; $i++ ){
		echo $arramy[$i];
	}



?>