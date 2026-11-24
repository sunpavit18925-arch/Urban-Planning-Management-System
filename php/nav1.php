<?php
error_reporting(0);
include 'js/function_db.php';
session_name('tutorial');
session_start();
clear_cp_session();
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
	if($a[0]['user_status']=='9'){
		$_SESSION['data_access'] = $a[0]['user_status'];
		$_SESSION['status'] = 1;		?>
    <section class="sidebar">
     <ul class="sidebar-menu tree" data-widget="tree">	  
        <!-- <li class="header" style="font-size:16px;background-color:#007ed3;color:white;display:flex;flex-direction:row;justify-content:space-between;" >ผู้ดูแลระบบ : <a target="_blank" class="u-manual"style="margin:0px -17px 0px 0px;padding:0;color:white;font-weight:500;background:transparent"href="pdf/UPMS_SuperAdmin_Manual.pdf">คู่มือใช้งานระบบ <i class="fa fa-file-pdf-o"></i> </a></li> -->		
		<!-- <li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li> -->
		<!-- <li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li> -->
		
		<!-- <li class="treeview t-kana">
			<a class="pointer">
				<i class="fa fa-line-chart"></i>
				<span > คณะติดตามผลการดำเนินการ</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li onclick="kana(1)"><a class="pointer"><span> - คณะติดตามฯ 1</span></a></li>
				<li onclick="kana(2)"><a class="pointer"><span> - คณะติดตามฯ 2</span></a></li>
				<li onclick="kana(3)"><a class="pointer"><span> - คณะติดตามฯ 3</span></a></li>
				<li onclick="kana(4)"><a class="pointer"><span> - คณะติดตามฯ 4</span></a></li>
				<li onclick="kana(5)"><a class="pointer"><span> - คณะติดตามฯ 5</span></a></li>
				<li onclick="kana(6)"><a class="pointer"><span> - คณะติดตามฯ 6</span></a></li>
				<li onclick="kana(7)"><a class="pointer"><span> - คณะติดตามฯ 7</span></a></li>
				
			</ul>
		</li> -->
		
		<li onclick="goto('s4');"><a class="pointer"><i class="fa fa-map"></i> <span>ผังตามขอบเขตการปกครอง</span></a></li>		
		<li onclick="goto('s5');"><a class="pointer"><i class="fa fa-map-o"></i> <span>ผังตามเขตตรวจราชการ</span></a></li>		
		<!-- <li onclick="goto('s1');"><a class="pointer"><i class="fa fa-newspaper-o"></i><span>สรุปสถานภาพผังเมืองปัจจุบัน</span></a></li> -->
		<!-- <li onclick="goto('search')"><a class="pointer"><i class="fa fa-search"></i><span>ค้นหาผังเมือง</span></a></li> -->		
		<!-- <li onclick="goto('plan');"><a class="pointer"><i class="fa fa-calendar"></i> <span>*Plan</span></a></li> -->
		<!-- <li onclick="goto('step');"><a class="pointer"><i class="fa fa-wrench"></i> <span>ขั้นตอน</span></a></li> -->
		<!-- <li onclick="goto('export');"><a class="pointer"><i class="fa fa-download"></i> <span>Export</span></a></li> -->
		<!-- <li><a class="pointer" href="sun.php"><i class="fa fa-download"></i> <span>Export</span></a></li> -->
		<!-- <li onclick="goto('sun');"><a class="pointer"><i class="fa fa-home"></i> <span>sun</span></a></li> -->
		
		
		<!-- <li onclick="goto('rp_cs');"><a class="pointer"><i class="fa fa-report"></i> <span>รายงาน*</span></a></li> -->
		<!-- <hr style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		<!-- <li class="treeview t-contact">
			<a class="pointer">
				<i class="fa fa-phone "></i><span >ติดต่อประสานงาน</span>
				<span class="pull-right-container" >
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li><a class="pointer" href="tel:000000001"><span> - สผม.ส่วน 1 (00 000 0001) </span></a></li>
				<li><a class="pointer" href="tel:000000002"><span> - สผม.ส่วน 2 (00 000 0002) </span></a></li>
				<li><a class="pointer" href="tel:000000003"><span> - สผม.ส่วน 3 (00 000 0003) </span></a></li>
				<li><a class="pointer" href="tel:000000004"><span> - สผม.ส่วน 4 (00 000 0004) </span></a></li>
				<li><a class="pointer" href="tel:000000005"><span> - ศทส.1 (00 000 0005) </span></a></li>
				<li><a class="pointer" href="tel:000000006"><span> - ศทส.2 (00 000 0006) </span></a></li>
			</ul>
		</li> -->
		<!-- <hr style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		
		
		<!-- <li class="treeview t-manage">
			<a class="pointer">
				<i class="fa fa-pencil-square-o "></i> <span >จัดการข้อมูลผังเมืองรวม</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li onclick="clear_cp();"><a class="pointer"><span> - ผังเมืองรวมเมือง/ชุมชน</span></a></li>
				<li onclick="goto('m35x')"><a class="pointer"><span> - ผังเมืองในขั้นตอน ม.35</span></a></li>
				<li hidden onclick="goto('est')"><a class="pointer"><span> - ผังเมืองในขั้นตอนการประเมิน</span></a></li>
				<li onclick="goto('stg1');"><a class="pointer"><span> - ขั้นตอนการประเมิน</span></a></li>
				<li onclick="goto('a2');"><a class="pointer"><span> - จังหวัดและส่วนผังเมืองรวม</span></a></li>
				<li onclick="goto('a7m');"><a class="pointer"><span> - รายการหมวดหมู่หมายเหตุ</span></a></li>
				<li onclick="goto('a9');"><a class="pointer"><span> - ผู้ดูแลระบบ</span></a></li>
				<li onclick="goto('a12');"><a class="pointer"><span> - เหตุผลผังพิจารณาดำเนินการ</span></a></li>
			
				<li class="treeview t-status">
					<a class="pointer"><span > - สถานภาพผังเมืองปัจจุบัน</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">	  
						<li onclick="extrash();"><a class="pointer"><span> -- ระบุวันของข้อมูล</span></a></li>
						<li onclick="goto('s2');"><a class="pointer"><span> -- ผังดำเนินการ</span></a></li>
						<li onclick="goto('s3');"><a class="pointer"><span> -- ผังยกร่างกฏหมาย</span></a></li>
						
					</ul>
				</li>
			</ul>
		</li> -->
		<!-- <hr  style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		<!-- <li ><a href="https://webs-apps.dpt.go.th/DPT_UPMX/cityplan/country/1" target="_blank" class="pointer"><i class="fa fa-pencil-square-o"></i><span>จัดการข้อมูลผังนโยบาย</span></a></li> -->	
		<!-- <hr  style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		<!-- <li class="treeview t-mode">
			<a class="pointer">
				<i class="fa fa-users"></i> <span >สลับมุมมองผู้ใช้งาน</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li class="super-admin" onclick="" ><a class="pointer" style="color:#939393"><i class="fa fa-caret-right"></i> <span>Super admin</span></a></li>
				<li class="admin" onclick="changeViewMode(2)"><a class="pointer"><i class="fa fa-caret-right"></i> <span></span>Admin</a></li>
				<li class="guest" onclick="changeViewMode()"><a class="pointer"><i class="fa fa-caret-right"></i> <span>Guest</span></a></li>
			</ul>
		</li> -->		
		<!-- <hr hidden style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		<?php if(false && ($_SESSION['intra_czid']=='demo-user-1' OR $_SESSION['intra_czid']=='demo-user-3')){  ?>
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li onclick="goto('plan');"><a class="pointer"><i class="fa fa-calendar"></i> <span>Plan</span></a></li>
		<li  onclick="goto('a8');"><a class="pointer"><i class="fa fa-book"></i> <span>Log</span></a></li>
		<li  onclick="checkSession();"><a class="pointer"><i class="fa fa-bug"></i> <span>Session</span></a></li>
		<li  hidden onclick="goto('map');"><a class="pointer"><i class="fa fa-globe"></i> <span>map-leaflet</span></a></li>
		<?php } ?>
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
         <!-- <li class="header" style="font-size:16px;background-color:#007ed3;color:white;display:flex;flex-direction:row;justify-content:space-between;" >ผู้ดูแลระบบ : <a target="_blank" class="u-manual"style="margin:0px -17px 0px 0px;padding:0;color:white;font-weight:500;background:transparent"href="pdf/UPMS_Admin_Manual_t0712.pdf">คู่มือใช้งานระบบ <i class="fa fa-file-pdf-o"></i> </a></li> -->	
		<!-- <li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li> -->
		<!-- <li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li> -->
		<li onclick="goto('s4');"><a class="pointer"><i class="fa  fa-map"></i> <span>ผังตามขอบเขตการปกครอง</span></a></li>		
		<li onclick="goto('s5');"><a class="pointer"><i class="fa  fa-map-o"></i> <span>ผังตามเขตตรวจราชการ</span></a></li>			
		<!-- <li onclick="goto('s1');"><a class="pointer"><i class="fa fa-newspaper-o"></i><span>สรุปสถานภาพผังเมืองปัจจุบัน</span></a></li> -->
		<!-- <li onclick="goto('search')"><a class="pointer"><i class="fa fa-search"></i><span>ค้นหาผังเมือง</span></a></li> -->
		<!-- <li onclick="goto('step');"><a class="pointer"><i class="fa  fa-wrench"></i> <span>ขั้นตอน</span></a></li> -->
		<!-- <li onclick="goto('export');"><a class="pointer"><i class="fa fa-download"></i> <span>Export</span></a></li> -->
		<!-- <li onclick="goto('sun');"><a class="pointer"><i class="fa fa-home"></i> <span>sun</span></a></li> -->
		
		<!-- <hr style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		<!-- <li class="treeview t-contact">
			<a class="pointer">
				<i class="fa fa-phone "></i><span >ติดต่อประสานงาน</span>
				<span class="pull-right-container" >
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li><a class="pointer" href="tel:000000001"><span> - สผม.ส่วน 1 (00 000 0001) </span></a></li>
				<li><a class="pointer" href="tel:000000002"><span> - สผม.ส่วน 2 (00 000 0002) </span></a></li>
				<li><a class="pointer" href="tel:000000003"><span> - สผม.ส่วน 3 (00 000 0003) </span></a></li>
				<li><a class="pointer" href="tel:000000004"><span> - สผม.ส่วน 4 (00 000 0004) </span></a></li>
				<li><a class="pointer" href="tel:000000005"><span> - ศทส.1 (00 000 0005) </span></a></li>
				<li><a class="pointer" href="tel:000000006"><span> - ศทส.2 (00 000 0006) </span></a></li>
			</ul>
		</li> -->
		<!-- <hr style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->		
		<!-- <li class="treeview t-manage">
			<a class="pointer">
				<i class="fa fa-pencil-square-o "></i> <span >จัดการข้อมูล</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li onclick="goto('a1');"><a class="pointer"><i class="fa fa-building"></i> <span>ผังเมืองรวมเมือง/ชุมชน</span></a></li>
				<?php if($a[0]['user_affiliation']=='071201' OR $a[0]['user_affiliation']=='071202' OR $a[0]['user_affiliation']=='071203' OR $a[0]['user_affiliation']=='071204'){ ?>
					<li onclick="goto('m35x')"><a class="pointer"><span> - ผังเมืองในขั้นตอน ม.35</span></a></li>				
				<?php } ?>
			</ul>
		</li> -->
		<?php 
		if(false && $_SESSION['view_mode']=='9'){ ?>
		<hr  style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li class="treeview t-mode">
			<a class="pointer">
				<i class="fa fa-users"></i> <span >สลับมุมมองผู้ใช้งาน</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li class="super-admin" onclick="changeViewMode(9)"><a class="pointer"><i class="fa fa-caret-right"></i> <span>Super admin</span></a></li>
				<li class="admin" onclick="" ><a class="pointer" style="color:#939393"><i class="fa fa-caret-right" ></i> <span ></span>Admin</a></li>
				<li class="guest" onclick="changeViewMode()"><a class="pointer"><i class="fa fa-caret-right"></i> <span>Guest</span></a></li>
			</ul>
		</li>	
		<?php } ?>
		<?php if(false && ($_SESSION['intra_czid']=='demo-user-1' OR $_SESSION['intra_czid']=='demo-user-3')){  ?>
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li  onclick="checkSession();"><a class="pointer"><i class="fa fa-bug"></i> <span>Session</span></a></li>
		<?php } ?>
      </ul>
    </section>
	<?php
	}elseif($a[0]['user_status']=='1'){
	$_SESSION['data_access'] = $a[0]['user_status'];
	$_SESSION['status'] = 0;		?>
	<section class="sidebar">
     <ul class="sidebar-menu tree" data-widget="tree">	  
          <!-- <li class="header" style="font-size:16px;background-color:#007ed3;color:white;display:flex;flex-direction:row;justify-content:space-between;" >ผู้ดูแลระบบ : <a target="_blank" class="u-manual"style="margin:0px -17px 0px 0px;padding:0;color:white;font-weight:500;background:transparent"href="pdf/UPMS_User_Manual-v4.pdf">คู่มือใช้งานระบบ <i class="fa fa-file-pdf-o"></i> </a></li> -->	
		<!-- <li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li> -->
		<!-- <li onclick="goto('a5');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li> -->	
		<li onclick="goto('s4');"><a class="pointer"><i class="fa  fa-map"></i> <span>ผังตามขอบเขตการปกครอง</span></a></li>		
		<li onclick="goto('s5');"><a class="pointer"><i class="fa  fa-map-o"></i> <span>ผังตามเขตตรวจราชการ</span></a></li>	
		<!-- <li onclick="goto('s1');"><a class="pointer"><i class="fa fa-newspaper-o"></i><span>สรุปสถานภาพผังเมืองปัจจุบัน</span></a></li> -->
		<!-- <li onclick="goto('search')"><a class="pointer"><i class="fa fa-search"></i><span>ค้นหาผังเมือง</span></a></li> -->	
		<!-- <li onclick="goto('step');"><a class="pointer"><i class="fa  fa-wrench"></i> <span>ขั้นตอน</span></a></li> -->
		<!-- <li onclick="goto('export');"><a class="pointer"><i class="fa fa-download"></i> <span>Export</span></a></li> -->
		<!-- <li onclick="goto('sun');"><a class="pointer"><i class="fa fa-home"></i> <span>sun</span></a></li> -->
		
		<!-- <hr style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
	
		<!-- <li class="treeview t-contact">
			<a class="pointer">
				<i class="fa fa-phone "></i><span >ติดต่อประสานงาน</span>
				<span class="pull-right-container" >
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li><a class="pointer" href="tel:000000001"><span> - สผม.ส่วน 1 (00 000 0001) </span></a></li>
				<li><a class="pointer" href="tel:000000002"><span> - สผม.ส่วน 2 (00 000 0002) </span></a></li>
				<li><a class="pointer" href="tel:000000003"><span> - สผม.ส่วน 3 (00 000 0003) </span></a></li>
				<li><a class="pointer" href="tel:000000004"><span> - สผม.ส่วน 4 (00 000 0004) </span></a></li>
				<li><a class="pointer" href="tel:000000005"><span> - ศทส.1 (00 000 0005) </span></a></li>
				<li><a class="pointer" href="tel:000000006"><span> - ศทส.2 (00 000 0006) </span></a></li>
			</ul>
		</li> -->	
		<!-- <hr style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		<!-- <li class="treeview t-manage">
			<a class="pointer">
				<i class="fa fa-pencil-square-o "></i> <span >จัดการข้อมูล</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">					
				<li onclick="goto('stg1');"><a class="pointer"><i class="fa fa-wrench"></i> <span>ขั้นตอนการประเมิน</span></a></li>				
			</ul>
		</li> -->
		<?php 
		if(false && $_SESSION['view_mode']=='9'){ ?>
		<hr  style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li class="treeview t-mode">
			<a class="pointer">
				<i class="fa fa-users"></i> <span >สลับมุมมองผู้ใช้งาน</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li class="super-admin" onclick="changeViewMode(9)"><a class="pointer"><i class="fa fa-caret-right"></i> <span>Super admin</span></a></li>
				<li class="admin" onclick="changeViewMode(2)"><a class="pointer"><i class="fa fa-caret-right"></i> <span></span>Admin</a></li>
				<li class="guest" onclick="changeViewMode()"><a class="pointer"><i class="fa fa-caret-right"></i> <span>Guest</span></a></li>
			</ul>
		</li>			
		<?php } ?>
		<?php if(false && ($_SESSION['intra_czid']=='demo-user-1' OR $_SESSION['intra_czid']=='demo-user-3')){  ?>
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li  onclick="checkSession();"><a class="pointer"><i class="fa fa-bug"></i> <span>Session</span></a></li>
		<?php } ?>
      </ul>
    </section>
	<?php 
	}elseif($a[0]['user']=='0'){
	$_SESSION['status'] = 0;		?>

	<section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">	  
         <!-- <li class="header" style="font-size:16px;background-color:#007ed3;color:white;display:flex;flex-direction:row;justify-content:space-between;" >ผู้ใช้งานระบบ : <a target="_blank" class="u-manual"style="margin:0px -17px 0px 0px;padding:0;color:white;font-weight:500;background:transparent"href="pdf/UPMS_User_Manual-v4.pdf">คู่มือใช้งานระบบ <i class="fa fa-file-pdf-o"></i> </a></li> -->
		<!-- <li onclick="goto('a6m');"><a class="pointer"><i class="fa fa-home"></i> <span>รายงานข้อมูลผังเมือง</span></a></li> -->
		<!-- <li onclick="goto('u3');"><a class="pointer"><i class="fa fa-bar-chart"></i> <span>รายงานสถานภาพผังเมือง</span></a></li> -->	
		<li onclick="goto('s4');"><a class="pointer"><i class="fa  fa-map"></i> <span>ผังตามขอบเขตการปกครอง</span></a></li>		
		<li onclick="goto('s5');"><a class="pointer"><i class="fa  fa-map-o"></i> <span>ผังตามเขตตรวจราชการ</span></a></li>	
		<!-- <li onclick="goto('s1');"><a class="pointer"><i class="fa fa-newspaper-o"></i><span>สรุปสถานภาพผังเมืองปัจจุบัน</span></a></li> -->
		<!-- <li onclick="goto('search')"><a class="pointer"><i class="fa fa-search"></i><span>ค้นหาผังเมือง</span></a></li> -->
		<!-- <li onclick="goto('step');"><a class="pointer"><i class="fa  fa-wrench"></i> <span>ขั้นตอน</span></a></li> -->
		<!-- <li onclick="goto('export');"><a class="pointer"><i class="fa fa-download"></i> <span>Export</span></a></li> -->
		<!-- <li onclick="goto('sun');"><a class="pointer"><i class="fa fa-home"></i> <span>sun</span></a></li> -->
		
		<!-- <hr style="margin:0px;border-top:solid #D9D9D9 1.25px"> -->
		<!-- <li class="treeview t-contact">
			<a class="pointer">
				<i class="fa fa-phone "></i><span >ติดต่อประสานงาน</span>
				<span class="pull-right-container" >
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li><a class="pointer" href="tel:000000001"><span> - สผม.ส่วน 1 (00 000 0001) </span></a></li>
				<li><a class="pointer" href="tel:000000002"><span> - สผม.ส่วน 2 (00 000 0002) </span></a></li>
				<li><a class="pointer" href="tel:000000003"><span> - สผม.ส่วน 3 (00 000 0003) </span></a></li>
				<li><a class="pointer" href="tel:000000004"><span> - สผม.ส่วน 4 (00 000 0004) </span></a></li>
				<li><a class="pointer" href="tel:000000005"><span> - ศทส.1 (00 000 0005) </span></a></li>
				<li><a class="pointer" href="tel:000000006"><span> - ศทส.2 (00 000 0006) </span></a></li>
			</ul>
		</li> -->
		<?php 
		if(false && $_SESSION['view_mode']=='9'){ ?>
		<hr  style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li class="treeview t-mode">
			<a class="pointer">
				<i class="fa fa-users"></i> <span >สลับมุมมองผู้ใช้งาน</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">	  
				<li class="super-admin" onclick="changeViewMode(9)"><a class="pointer"><i class="fa fa-caret-right"></i> <span>Super admin</span></a></li>
				<li class="admin" onclick="changeViewMode(2)"><a class="pointer"><i class="fa fa-caret-right"></i> <span></span>Admin</a></li>
				<li class="guest" onclick=""><a class="pointer" style="color:#939393"><i class="fa fa-caret-right"></i> <span>Guest</span></a></li>
			</ul>
		</li>			
		<?php } ?>
		<?php if(false && ($_SESSION['intra_czid']=='demo-user-1' OR $_SESSION['intra_czid']=='demo-user-3')){  ?>
		<hr style="margin:0px;border-top:solid #D9D9D9 1.25px">
		<li  onclick="checkSession();"><a class="pointer"><i class="fa fa-bug"></i> <span>Session</span></a></li>
		<?php } ?>
      </ul>
    </section>

	<?php }	else{
		//echo "else";
		//session_destroy();
		//echo "<script>window.location.replace('#')</script>";
}?>

<script>
	/*
	treeviewM()
	$('.treeview > a').click(treeviewM);
	function treeviewM(){
		$('.treeview-menu').slideToggle();
		$('.treeview').toggleClass('menu-open');
		//$('.pull-right-container > i').toggle();
		//$('.treeview-menu').toggleAttr('style','display:block')
	}*/
	//treeviewC()
	$('.t-contact > a').click(treeviewC);
	function treeviewC(){
		$('.t-contact > .treeview-menu').slideToggle();
		$('.t-contact > .treeview').toggleClass('menu-open');
	}
	
	
	$('.t-kana > a').click(treeviewK);
	function treeviewK(){
		$('.t-kana > .treeview-menu').slideToggle();
		$('.t-kana > .treeview').toggleClass('menu-open');
	}
	
	
	treeviewM()
	$('.t-manage > a').click(treeviewM);
	function treeviewM(){
		$('.t-manage > .treeview-menu').slideToggle();
		$('.t-manage > .treeview').toggleClass('menu-open');
	}
	
	//treeviewS()
	$('.t-status > a').click(treeviewS);
	function treeviewS(){
		$('.t-status > .treeview-menu').slideToggle();
		$('.t-status > .treeview').toggleClass('menu-open');
	}
	
	//treeviewX()
	$('.t-mode > a').click(treeviewX);
	function treeviewX(){
		$('.t-mode > .treeview-menu').slideToggle();
		$('.t-mode > .treeview').toggleClass('menu-open');
	}



function goto(x) {
	$('.myBtn2').show();
	$('.xdsoft_datetimepicker').remove();
	$('#mainshow').html('<img src="img/loading3.gif" class="center">');
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
	else if (x=='sun') {
		$('#mainshow').load('report/tree-chart2.php');
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
	else if(x =='a12'){
		$('#mainshow').load('app/manage_consider.php');
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
	else if(x=='search'){
		$('#mainshow').load('app/search_cityplan.php');
	}
	else if(x=='ss'){
		
	}
	else if(x=='s1'){
		$('#mainshow').load('app/status_currentx.php');
	}
	else if(x=='s2'){
		$('#mainshow').load('app/status_operate.php');
	}
	else if(x=='s3'){
		$('#mainshow').load('app/status_law.php');
	}
	else if(x=='s4'){
		
		$('#mainshow').load('app/show_extra.php');
	}
	else if(x=='s5'){
		
		$('#mainshow').load('app/show_extra_keth.php');
	}
	else if(x=='plan'){
		$('#mainshow').load('app/manage_plan.php');
	}
	else if(x=='step'){
		$('#mainshow').load('app/show_step.php');
	}
	else if(x=='m35'){
		$('#mainshow').load('app/cityplan_m35.php');
	}
	else if(x=='m35x'){
		$('#mainshow').load('app/cityplan_m35x.php');
	}
	else if(x=='est'){
		$('#mainshow').load('app/cityplan_est.php');
	}
	else if(x=='export'){
		$('#mainshow').load('app/export_page.php');
	}
	else if(x=='rp_cs'){
		$('#mainshow').load('app/report_a.php');
	}
    else {
		alert('Error!!');
    }
	$('body').removeClass('sidebar-open');
	$('#exportPdfBtn').hide();
	
	
  }
  
  function kana(x){
	  $.ajax({
		  url : 'report/dashboard_kana.php',
		  type : 'post',
		  data : {
			  kana : x
		  },
		  success : function(data, status, xhr){
			  $('#mainshow').html(data);
		  },
		  error : function(xhr, status, data){
			  alert("เกิดข้อผิดพลาด");
		  }
	  });    
  }
  
  function extrash(){
	  $('#extrashow').load('app/status_date.php');
  }
  function report_another(x){
		$.post('report/report_another.php',{
			type : x
		},function(data){
			$('#mainshow').html(data);
		});
	}
	
	
	function checkSession(){
		$.post('app/manage.php',{
			event:'r_session'
		},function(data){
			alert(data);
		});
	}
	<?php 
	if($_SESSION['intra_czid']=='demo-user-1'){
		//echo 'report_another(2)';
	}
	
	?>

	function clear_cp(){			
		$.post('app/manage.php',{
			event: 'clear_cp_session'
		});
		goto('a1');
		//alert('123');
	}
	function changeViewMode(x){
		$.post('app/manage.php',{
			event : 'changeViewMode',
			data_access : x			
		},function(data){
			$('#sideNav').load('nav1.php');
			$('#mainshow').load('report/tree-chart.php')
		});
	}
	
</script>
	