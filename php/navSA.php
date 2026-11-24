
<?php

include 'js/function_db.php';
session_name('tutorial');
session_start();

$_SESSION['intra_czid'] = 'demo-user-4';
$a[0]['user_status'] = '9';


	$t1 = date('Ymd');
	$t2 = date('H');
	$tn = $t2-3;	
	$sli = "SELECT COUNT(log_seq) FROM tb_log WHERE log_action = 'cn' AND log_user = '".$_SESSION['intra_czid']."' AND Date(log_date) = '$t1' AND Hour(log_date) > '$tn'";
	$restulti = selectSql($sli);
	$sqp = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUES ('".$_SESSION['intra_czid']."','cn','-','-') ";	
	if($restulti[0]['COUNT(log_seq)'] == 0 ){
		in_up_delSql($sqp);
		unset($_SESSION['modal-swipe']);
	}else{
		
	}
	//echo $_SESSION['modal-swipe'];
	//echo  $_SESSION['intra_czid'];
	$sesID = $_SESSION['intra_czid'];
	//echo $_SESSION['intra_czid'];
	//if(TRUE){
	//$_SESSION['intra_czid']=='1709901225';
	//if($_SESSION['intra_czid']=='demo-user-1' OR $_SESSION['intra_czid']=='demo-user-3'  OR $_SESSION['intra_czid']=='demo-user-2'){
	if($a[0]['user_status']=='9'){
	$_SESSION['data_access'] = $a[0]['user_status'];
	$_SESSION['status'] = 1;		?>
    <section class="sidebar">
     <ul class="sidebar-menu tree" data-widget="tree">	  
        <li class="header Gfont" style="font-size:16px;color:black" >ผู้ดูแลระบบ</li>		
		<li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li>
		<li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li>		
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li class="treeview">
			<a class="pointer">
				<i class="fa fa-pencil-square-o "></i> <span >จัดการข้อมูล</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li onclick="goto('a1');"><a class="pointer"><i class="fa fa-building"></i> <span>ผังเมืองรวมเมือง/ชุมชน</span></a></li>
				<li onclick="goto('a2');"><a class="pointer"><i class="fa fa-bank"></i> <span>จังหวัดและส่วนผังเมืองรวม</span></a></li>
				<li onclick="goto('stg1');"><a class="pointer"><i class="fa fa-wrench"></i> <span>ขั้นตอนการประเมิน</span></a></li>
				<li onclick="goto('a7m');"><a class="pointer"><i class="fa  fa-commenting"></i> <span>รายการหมายเหตุ</span></a></li>
				<li onclick="goto('a9');"><a class="pointer"><i class="fa fa-users"></i> <span>ผู้ดูแลระบบ</span></a></li>
			</ul>
		</li>		
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li onclick="goto('a8');"><a class="pointer"><i class="fa fa-book"></i> <span>Log</span></a></li>
		<li hidden onclick="goto('map');"><a class="pointer"><i class="fa fa-globe"></i> <span>map-ทดสอบ</span></a></li>
		<!--
			<li onclick="goto('a3');"><a class="pointer"><i class="fa fa-list "></i> <span>ขั้นตอนดำเนินการ</span></a></li>
			<li onclick="goto('a4');"><a class="pointer"><i class="fa fa-list-alt "></i> <span>จัดการหมายเหตุ</span></a></li>
			<li onclick="goto('a6');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานติดตาม</span></a></li>
			<li onclick="goto('a10');"><a class="pointer"><i class="fa fa-edit"></i> <span>รายงานข้อมูล<i style="color:red">*</i></span></a></li>		
			<li onclick="goto('a11');"><a class="pointer"><i class="fa fa-file-text"></i> <span>History</span></a></li>
			<li onclick="goto('a1-ver2');"><a class="pointer"><i class="fa fa-building"></i> <span>จัดข้อมูลผังเมือง (ทดสอบ)</span></a></li>
			<li onclick="goto('a4new');"><a class="pointer"><i class="fa fa-wifi"></i> <span>ทดสอบ</span></a></li>
			<li onclick="goto('a5new');"><a class="pointer"><i class="fa fa-info-circle"></i> <span>ทดสอบ2</span></a></li>
		-->
      </ul>
    </section>

	<?php
	}elseif ($a[0]['user_status']=='2'){
	$_SESSION['data_access'] = $a[0]['user_status'];
	$_SESSION['status'] = 1;		?>
    <section class="sidebar">
       <ul class="sidebar-menu" data-widget="tree">	  
        <li class="header Gfont" style="font-size:16px;color:black" >ผู้ดูแลระบบ</li>		
		<li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li>
		<li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li>	
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">		
		<li class="treeview">
			<a class="pointer">
				<i class="fa fa-pencil-square-o "></i> <span >จัดการข้อมูล</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li onclick="goto('a1');"><a class="pointer"><i class="fa fa-building"></i> <span>ผังเมืองรวมเมือง/ชุมชน</span></a></li>				
				<li onclick="goto('stg1');"><a class="pointer"><i class="fa fa-wrench"></i> <span>ขั้นตอนการประเมิน</span></a></li>
				<li onclick="goto('a7m');"><a class="pointer"><i class="fa  fa-commenting"></i> <span>รายการหมายเหตุ</span></a></li>				
			</ul>
		</li>
      </ul>
    </section>
	<?php
	}elseif($a[0]['user_status']=='1'){
	$_SESSION['data_access'] = $a[0]['user_status'];
	$_SESSION['status'] = 0;		?>
	<section class="sidebar">
     <ul class="sidebar-menu tree" data-widget="tree">	  
        <li class="header Gfont" style="font-size:16px;color:black" >ผู้ดูแลระบบ</li>		
		<li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li>
		<li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li>		
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li class="treeview">
			<a class="pointer">
				<i class="fa fa-pencil-square-o "></i> <span >จัดการข้อมูล</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">					
				<li onclick="goto('stg1');"><a class="pointer"><i class="fa fa-wrench"></i> <span>ขั้นตอนการประเมิน</span></a></li>				
			</ul>
		</li>
      </ul>
    </section>
	<?php 
	}elseif($a[0]['user']=='0'){
	$_SESSION['status'] = 0;		?>

	<section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">	  
       <li class="header Gfont" style="font-size:16px;color:black">ผู้ใช้งานทั่วไป</li>
		<li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li>
		<li onclick="goto('u3');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li>	
      </ul>
    </section>
	<?php  } ?>

<script>
	treeviewM()
	$('.treeview > a').click(treeviewM);
	function treeviewM(){
		$('.treeview-menu').slideToggle();
		$('.treeview').toggleClass('menu-open');
		//$('.pull-right-container > i').toggle();
		//$('.treeview-menu').toggleAttr('style','display:block')
	}




function goto(x) {
	$('.myBtn2').show();
    if (x=='0') {
        $('#mainshow').load('app/home.php');    
    }
	else if (x=='u1') {
		$('#mainshow').load('app/cityplan.php');
    }
	else if (x=='u2') {
		$('#mainshow').load('app/step.php');	
    }
	else if (x=='u3') {
		$('#mainshow').load('app/report.php');	
    }
	else if (x=='u4') {
      $('#mainshow').load('embed.html');	
    }
	else if (x=='a1') {
		$('#mainshow').load('report/manage_cityplanX-ver2.php');
    }
    else if (x=='a2') { 
		$('#mainshow').load('app/manage_province.php');
    }
	else if (x=='a3') {
		$('#mainshow').load('app/manage_step.php');
    }
    else if (x=='a4') {
		$('#mainshow').load('app/manage_note.php'); 
    }
	else if (x=='a5') {
		$('#mainshow').load('app/report.php');  
    }
	else if (x=='a6') {
		$('#mainshow').load('embed.html');
    }
	else if (x=='a6m') {
		$('#mainshow').load('report/tree-chart.php');
    }
	else if (x=='a7') {
		$('#mainshow').load('app/manage_note(new).php');
    }
	else if (x=='a7m') {
		$('#mainshow').load('app/manage_group_note.php');
    }
	else if (x =='a8'){
		$('#mainshow').load('app/show_log.php');
	}
	else if (x =='a9'){
		$('#mainshow').load('app/manage_admin.php');
	}
	else if(x =='a10'){
		$('#mainshow').load('app/unset_data.php');
	}
	else if(x =='a11'){
		$('#mainshow').load('app/history_data.php');
	}
	else if (x=='a40') {
		$('#mainshow').load('app/import_excel.php');
    }
    else if (x=='u5') {
		$('#mainshow').load('apps/show_plan.php');   
    }
    else if (x=='u6') {
		$('#mainshow').load('apps/show_rce.php'); 
    }
	else if(x=='a4new'){
		$('#mainshow').load('app/manage_cityplan(pagination).php');
	}
	else if(x=='a5new'){
		$('#mainshow').load('app/mytest.php');
	}
	else if(x=='map'){
		 $('#mainshow').load('map.php');
	}
    else if (x==5) {
      $('#mainshow').load('apps/manage_hardware.php');
    }
    else if (x==6) {
      $('#mainshow').load('apps/manage_company.php');
    }
    else if (x==7) { 
      $('#mainshow').load('apps/manage_project.php');
    }
    else if (x==8) { 
      $('#mainshow').load('apps/manage_rce.php');
    }
	else if (x==9) {
      $('#mainshow').load('apps/manage_plan.php');   
    }
	else if (x==999) { 
		window.location.replace('app/destroy.php');   
    }
	else if (x=='d1') {
		$('#mainshow').load('app/report_search.php');
    }
	else if (x=='d2') {
		$('#mainshow').load('app/report_search2.php');
    }
	else if (x=='stg1') {
		$('#mainshow').load('report/manage_cityplanX-stage.php');
    }
	else if (x==9999) {
		$('#mainshow').load('app/test.php'); 
    }else if(x =='a1-ver2'){
		$('#mainshow').load('report/manage_cityplanX-ver2.php');
	}
    else {
		alert('Error!!');
    }
	$('body').removeClass('sidebar-open');
  }
  
  
</script>
	