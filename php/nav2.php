<?php

include 'js/function_db.php';
session_name('tutorial');
session_start();
//session_destroy();

// Auto-login bypass intranet on all hosts/servers
if (!isset($_SESSION['sessionID']) || $_SESSION['sessionID'] == '' || !isset($_SESSION['intra_czid']) || $_SESSION['intra_czid'] == '') {
    $_SESSION['sessionID'] = session_id();
    $_SESSION['intra_czid'] = 'demo-user-1';
    $_SESSION['timeLasetdActive'] = time() + 86400;
    if (!isset($_SESSION['data_access']) || $_SESSION['data_access'] == '') {
        $_SESSION['data_access'] = '9';
    }
    $_SESSION['status'] = 1;
}
$_SESSION['timeLasetdActive'] = time() + 86400;
//echo $_SESSION['sessionID'];


	
	$a = [
		0 => [
			'user' => '1',
			'user_status' => '9',
			'user_affiliation' => '071201'
		]
	];
	$_SESSION['data_access'] = '9';
	$_SESSION['status'] = 1;
	if(true){
	$_SESSION['status'] = 1;		?>
    <section class="sidebar">
     <ul class="sidebar-menu" data-widget="tree">	  
        <li class="header Gfont" style="font-size:16px;color:black" >ผู้ดูแลระบบ</li>		
		<li onclick="goto(0);"><a class="pointer"><i class="fa fa-home"></i> <span> หน้าหลัก</span></a></li>		
		<li onclick="goto('a1');"><a class="pointer"><i class="fa fa-building"></i> <span>จัดการข้อมูลผังเมือง</span></a></li>
		<li onclick="goto('a2');"><a class="pointer"><i class="fa fa-bank"></i> <span>จัดการข้อมูลจังหวัด</span></a></li>
		<!--
		<li onclick="goto('a3');"><a class="pointer"><i class="fa fa-list "></i> <span>ขั้นตอนดำเนินการ</span></a></li>--
		<li onclick="goto('a4');"><a class="pointer"><i class="fa fa-list-alt "></i> <span>จัดการหมายเหตุ</span></a></li>-->
		
		<li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานรวม</span></a></li>
		<li onclick="goto('a6');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานติดตาม</span></a></li>
		<li onclick="goto('a10');"><a class="pointer"><i class="fa fa-edit"></i> <span>รายงานข้อมูล<i style="color:red">*</i></span></a></li>
		<li onclick="goto('a8');"><a class="pointer"><i class="fa fa-book"></i> <span>Log</span></a></li>
		<li onclick="goto('a9');"><a class="pointer"><i class="fa fa-users"></i> <span>จัดการผู้ดูแลระบบ</span></a></li>
		<!--<li onclick="goto('a7');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>หมายเหตุ</span></a></li>-->
		<!--<li onclick="goto('a4new');"><a class="pointer"><i class="fa fa-wifi"></i> <span>ทดสอบ</span></a></li>-->
		<!--<li onclick="goto('a5new');"><a class="pointer"><i class="fa fa-info-circle"></i> <span>ทดสอบ2</span></a></li>-->
      </ul>
	  <!--
	  <ul class="sidebar-menu" data-widget="tree">	  
       <li class="header Gfont" style="font-size:16px;color:black"> ผู้ใช้งานทั่วไป</li>-->
		<!--
		<li onclick="goto(0);"><a class="pointer"><i class="fa fa-home"></i> <span>หน้าหลัก</span></a></li>
		<li onclick="goto('u1');"><a class="pointer"><i class="fa fa-building"></i> <span>ข้อมูลผังเมือง</span></a></li>
		<li onclick="goto('u2');"><a class="pointer"><i class="fa fa-list"></i> <span>ขั้นตอนดำเนินการ</span></a></li>
		-->
		<!--
		<li onclick="goto('u3');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานผล</span></a></li>
		<li onclick="goto('u4');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานติดตาม</span></a></li>
      </ul>
	  -->
    </section>

	<?php
	}elseif ($a[0]['user']=='1'){
	$_SESSION['status'] = 1;		?>
    <section class="sidebar">
       <ul class="sidebar-menu" data-widget="tree">	  
        <li class="header Gfont" style="font-size:16px;color:black" >ผู้ดูแลระบบ</li>		
		<li onclick="goto(0);"><a class="pointer"><i class="fa fa-home"></i> <span> หน้าหลัก</span></a></li>		
		<li onclick="goto('a1');"><a class="pointer"><i class="fa fa-building"></i> <span>จัดการข้อมูลผังเมือง</span></a></li>
		<li onclick="goto('a2');"><a class="pointer"><i class="fa fa-bank"></i> <span>จัดการข้อมูลจังหวัด</span></a></li>
		<!--
		<li onclick="goto('a3');"><a class="pointer"><i class="fa fa-list "></i> <span>ขั้นตอนดำเนินการ</span></a></li>
		<li onclick="goto('a4');"><a class="pointer"><i class="fa fa-list-alt "></i> <span>จัดการหมายเหตุ</span></a></li>
		-->
		<li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานรวม</span></a></li>
		<li onclick="goto('a6');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานติดตาม</span></a></li>
		<li onclick="goto('a10');"><a class="pointer"><i class="fa fa-edit"></i> <span>รายงานข้อมูล<i style="color:red">*</i></span></a></li>
      </ul>
    </section>
	<?php 
	}elseif($a[0]['user']=='0'){
	$_SESSION['status'] = 0;		?>

	<section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">	  
       <li class="header Gfont" style="font-size:16px;color:black"> </li>
		<li onclick="goto('u3');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานผล</span></a></li>
		<li onclick="goto('u4');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานติดตาม</span></a></li>
      </ul>
    </section>

	<?php }	else{
		//echo "else";
		//session_destroy();
		//echo "<script>window.location.replace('#')</script>";
}?>

<script>
function goto(x) {


    if (x=='0') {
		//alert("Hello World");
		//$('#footer').show();
        $('#mainshow').load('app/home.php');

      
    }else if (x=='u1') {
		// $('#footer').hide();
		//alert("Hello World!");
		$('#mainshow').load('app/cityplan.php');
	  
      
    }else if (x=='u2') {
		// $('#footer').hide();
		//alert("Hello World!");
		$('#mainshow').load('app/step.php');
	  
      
    }else if (x=='u3') {
		// $('#footer').hide();
		//alert("Hello World!");
		$('#mainshow').load('app/report.php');
	  
      
    }
	else if (x=='u4') {
	 // $('#footer').hide();
      $('#mainshow').load('embed.html');
     
    }
	else if (x=='a1') {
		// $('#footer').hide();
		//alert("Hello World!");
		$('#mainshow').load('app/manage_cityplan.php');
    }
    else if (x=='a2') {
	 // $('#footer').hide(); 
      $('#mainshow').load('app/manage_province.php');
   
    }
	else if (x=='a3') {
	 // $('#footer').hide();
      $('#mainshow').load('app/manage_step.php');
     
    }
    else if (x=='a4') {
	 // $('#footer').hide();
      $('#mainshow').load('app/manage_note.php');
     
    }
	else if (x=='a5') {
	 // $('#footer').hide();
      $('#mainshow').load('app/report.php');
     
    }
	else if (x=='a7') {
	 // $('#footer').hide();
	 //$('#mainshow').load();
      $('#mainshow').load('app/manage_note(new).php');
     
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
	else if (x=='a6') {
	 // $('#footer').hide();
      $('#mainshow').load('embed.html');
     
    }
	else if (x=='a40') {
	 // $('#footer').hide();
      $('#mainshow').load('app/import_excel.php');
     
    }
    else if (x=='u5') {
	 // $('#footer').hide();
      $('#mainshow').load('apps/show_plan.php');
     
    }
    else if (x=='u6') {
	 // $('#footer').hide();
      $('#mainshow').load('apps/show_rce.php');
     
    }
	else if(x=='a4new'){
		$('#mainshow').load('app/manage_cityplan(pagination).php');
	}
	else if(x=='a5new'){
		$('#mainshow').load('app/mytest.php');
	}
    else if (x==5) {
	 // $('#footer').hide();
      $('#mainshow').load('apps/manage_hardware.php');
	 

    }
    else if (x==6) {
	  //$('#footer').hide(); 
      $('#mainshow').load('apps/manage_company.php');
	 
    }
    else if (x==7) {
	  //$('#footer').hide();  
      $('#mainshow').load('apps/manage_project.php');
   
    }
    else if (x==8) {
	  //$('#footer').hide(); 
      $('#mainshow').load('apps/manage_rce.php');
     
    }
	else if (x==9) {
	  //$('#footer').hide(); 
      $('#mainshow').load('apps/manage_plan.php');
     
    }
	else if (x==999) {
	  
      window.location.replace('app/destroy.php');
      
    }
	else if (x=='d1') {
	  //$('#footer').hide(); 
      $('#mainshow').load('app/report_search.php');
     
    }
	else if (x=='d2') {
	  //$('#footer').hide(); 
      $('#mainshow').load('app/report_search2.php');
     
    }
	else if (x==9999) {
	  //$('#footer').hide(); 
      $('#mainshow').load('app/test3.php');
     
    }
    else {
      alert('Error!!');
    }
  }
  
  
</script>
	