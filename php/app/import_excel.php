<?php

//*** Get Document Path ***//
	$strPath = realpath(basename(getenv($_SERVER["SCRIPT_NAME"]))); // C:/AppServ/www/myphp
	$OpenFile = "MyXls/MyCustomer.xls";

//*** Create Exce.Application ***//
	$xlApp = new COM("Excel.Application");
	$xlBook = $xlApp->Workbooks->Open($strPath."/".$OpenFile);

//$xlBook = $xlApp->Workbooks->Open(realpath($OpenFile));
	$xlSheet1 = $xlBook->Worksheets(1); 

//*** Insert to MySQL Database ***//
// Disabled real intranet DB connection (use Mockup Database instead)
// $objConnect = mysql_connect("192.168.100.50","root","Dp!1bcDB") or die("Error Connect to Database");
die("Import Excel is disabled in Mockup Database mode.");

	for($i=2;$i<=5;$i++){
		If(trim($xlSheet1->Cells->Item($i,1)) != ""){
			$strSQL = "";
			$strSQL .= "INSERT INTO tb_cityplan ";
			$strSQL .= "(cp_no,cp_id,cp_type,cp_eec,cp_province,cp_name) ";
			$strSQL .= "VALUES ";
			$strSQL .= "('".$xlSheet1->Cells->Item($i,1)."','".$xlSheet1->Cells->Item($i,2)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,3)."','".$xlSheet1->Cells->Item($i,4)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,5)."','".$xlSheet1->Cells->Item($i,6)."') ";
			mysql_query($strSQL);
		}
	}
//*** Close MySQL ***//
	@mysql_close($objConnect);
//*** Close & Quit ***//
	$xlApp->Application->Quit();
	$xlApp = null;
	$xlBook = null;
	$xlSheet1 = null;
?>
Data Import/Inserted.