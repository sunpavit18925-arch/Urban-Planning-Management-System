<?php
session_name('tutorial');
session_start();

if(isset($_FILES['file']['name'])){

	/* Getting file name */
	$filename = $_FILES['file']['name'];

	/* Location */
	$temp = explode(".", $filename);
	$newfilename =  $_SESSION['cp_no'].'.' . end($temp);
	unset($_SESSION['cp_no']);
	$location = "../img/cityplan/".$newfilename;
	$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	$imageFileType = strtolower($imageFileType);
	
	

	/* Valid extensions */
	$valid_extensions = array("jpg","jpeg","png");

	$response = 0;
	/* Check file extension */
	if(in_array(strtolower($imageFileType), $valid_extensions)) {
	   	/* Upload file */
	   	if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
	     	$response = $location;
	   	}
	}

	//echo $response;
	echo "อัปโหลดรูปภาพสำเร็จ";
	exit;
}

echo 0;
?>