<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	clear_cp_session();
	$sql = "SELECT COUNT(cp_no) FROM tb_cityplan";
	$result = selectSql($sql);
	$years = date('Y')+543;
	$months = date('m');
	if($months == 1 ){
		$months = 'ม.ค.';
	}elseif($months == 2 ){
		$months = 'ก.พ.';
	}elseif($months == 3 ){
		$months = 'มี.ค.';
	}elseif($months == 4 ){
		$months = 'เม.ย.';
	}elseif($months == 5 ){
		$months = 'พ.ค.';
	}elseif($months == 6 ){
		$months = 'มิ.ย.';
	}elseif($months == 7 ){
		$months = 'ก.ค.';
	}elseif($months == 8 ){
		$months = 'ส.ค.';
	}elseif($months == 9 ){
		$months = 'ก.ย.';
	}elseif($months == 10 ){
		$months = 'ต.ค.';
	}elseif($months == 11 ){
		$months = 'พ.ย.';
	}else{
		$months = 'ธ.ค.';
	}
	$days = intval(date('d'));
	$dates = $days.' '.$months.' '.$years;	
	?>
	<style >
		@media print{
			@page{
				size:A4 portrait;
				margin:10mm 15mm !important;
			}
			#A4{
				margin-top:10pt!important;
			}
			#date_top{
				position:fixed;
				top:0;
				right:0;
				color:red!important;
			}
			
		}	
		
	</style>
	<div id="date_top" style="display:inline;z-index:1;text-align:right;float:right;" ><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
	<div id="A4" class="container-fluid" style="margin-top:5px;background-color:whitse;border-radius:28px">
		<div class="col-12">
			<h1 class="GHfont hidden-xs printable" style="font-family: Kanit;text-align:center">รายงานสถานภาพผังเมืองรวม</h1>
			<h2 class="GHfont hidden-sm  hidden-md hidden-lg unprintable" style="font-family: Kanit;text-align:center">รายงาน<br>สถานภาพผังเมืองรวม</h2>
			<h3 class="GHfont printable" style="font-family: Kanit; text-align:center;margin-top:0px;">จำนวน <?= $result[0]['COUNT(cp_no)'];?> ผัง</h3>
			<!--<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">รายงานผล</h4>-->
		</div>	
		<br>
		
		<input hidden id="divshow" value="">
		<!--
			<div id="st1" class="col-xs-3 hidden-xs bg-gray" onclick="loadReport(1);">&nbsp;</div> 
			<div id="st2" class="col-xs-3 hidden-xs bg-gray" onclick="loadReport(2);">&nbsp;</div> 
			<div id="st3" class="col-xs-3 hidden-xs bg-gray" onclick="loadReport(3);">&nbsp;</div> 
			<div id="st4" class="col-xs-3 hidden-xs bg-gray" onclick="loadReport(4);">&nbsp;</div> 
		-->
		

		<div id="divload" style="background-color:transparent;"></div>
	</div>
	<script>

	$('.myBtn2').show();
	$('#exportPdfBtn').show();

//$('#exportPdfBtn').attr('onclick','exportPDF("A4")');
$('#divload').load('app/t2.php');
	$('#divshow').val(1);
	//testtime(0);
	function testtime(x){
		x++;
		if(x>4){
			x=1;
		}
		if($('#divshow').val()==1){
			$('#st4').removeClass('bg-red');
			$('#divload').load('app/t1.php');
			$('#st1').addClass('bg-aqua');
		}
		else if($('#divshow').val()==2){
			$('#divload').load('app/t2.php');
			$('#st1').removeClass('bg-aqua');
			$('#st2').addClass('bg-green');
		}
		else if($('#divshow').val()==3){
			$('#st2').removeClass('bg-green');
			$('#divload').load('app/t3.php');
			$('#st3').addClass('bg-yellow');
		}
		else if($('#divshow').val()==4){
			$('#st3').removeClass('bg-yellow');
			$('#divload').load('app/t4.php');
			$('#st4').addClass('bg-red');
		}
		setTimeout(function(){
			$('#divshow').val(x);
			testtime(x);
		}, 90000);
	}
	
	
	
	
	function loadReport(x){
		if(x=='1'){
			$('#divload').load('app/t1.php');
		}
		else if(x=='2'){
			$('#divload').load('app/t2.php');
		}
		else if(x=='3'){
			$('#divload').load('app/t3.php');
		}
		else if(x=='4'){
			$('#divload').load('app/t4.php');
		}
		
	}
	
	</script>