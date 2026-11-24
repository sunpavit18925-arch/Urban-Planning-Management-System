<?php 
	error_reporting(0);
	ini_set('display_errors', 0);

	session_name('tutorial');
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	// Auto-login on all hosts/servers with full Intranet Super Admin privileges (No intranet login required)
	$_SESSION['sessionID'] = session_id();
	$_SESSION['intra_czid'] = 'demo-user-1';
	$_SESSION['timeLasetdActive'] = time() + 86400;
	if (!isset($_SESSION['data_access']) || $_SESSION['data_access'] == '') {
		$_SESSION['data_access'] = '9';
	}
	$_SESSION['status'] = 1;

	unset($_SESSION['view_mode']);
	unset($_SESSION['sql']);
	unset($_SESSION['sql1']);
	unset($_SESSION['sql2']);
	unset($_SESSION['event']);
	unset($_SESSION['status_id']);
	unset($_SESSION['title']);
	unset($_SESSION['eff_id']);
		
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>ระบบการติดตามงานวางและจัดทำผังเมือง</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.css?<?php echo date('Ydmhis')?>">
		<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.css?<?php echo date('Ydmhis')?>">
		<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="dist/style.css?<?php echo date('Ydmhis')?>">
		<link rel="stylesheet" href="dist/skin-green-light.css?<?php echo date('Ydmhis')?>">
		<link rel="stylesheet" href="bower_components/morris.js/morris.css">
		<link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
		<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
		<link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
		<link rel="stylesheet" href="js/jquery.datetimepicker.css?<?php echo date('Ydmhis')?>">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="     crossorigin=""/>
		
		
		<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
		<script type="text/javascript" src="js/provinces.js"></script>
		<script src="js/jquery-3.5.1.min.js" charset="utf-8"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#mainshow').load('app/show_extra.php');
				$('#sideNav').load('nav1.php');
			});
		</script>
	</head>
	<style>
	html, body {
		margin: 0 !important;
		padding: 0 !important;
	}
	.wrapper {
		margin: 0 !important;
		padding: 0 !important;
		margin-top: 0 !important;
		padding-top: 0 !important;
	}
	.main-header {
		margin: 0 !important;
		padding: 0 !important;
		margin-top: 0 !important;
		padding-top: 0 !important;
		top: 0 !important;
		position: relative;
	}
	.main-header .navbar {
		margin-top: 0 !important;
		margin-bottom: 0 !important;
	}
	.main-header .logo {
		margin-top: 0 !important;
	}
	@media screen and (max-width:912px){
		.mainname{
			display:none!important;
			
		}
	}
	#mainshow{
		/*background:red;*/
		-webkit-print-color-adjust:exact !important;
		print-color-adjust:exact !important;
	}
	/**/
	.navbar ,.logo{
		background-color:#3d444a!important;
	}
	
	#rp_cs_dflaws_id::-webkit-inner-spin-button,
	#rp_cs_operate_id::-webkit-inner-spin-button{
	  -webkit-appearance: none;
	  margin: 0;
	}
	table > tbody > tr > td > .text_cp_name:hover{
		border-bottom:solid black 1px;
		font-weight: 600;
		cursor:pointer;
	}
	</style>
	<body class="hold-transition skin-green-light sidebar-mini ">
		<div class="wrapper" style="font-family: Kanit;overflow: hidden;margin: 0;padding: 0;">
			<header class="main-header" style="margin-top: 0;padding-top: 0;top: 0;">
				<a class="logo pointer" style="font-family: Kanit;" onclick="goto('s4');">
					<span title="Urban Planning Monitoring System" class="logo-mini" ><b id="demo2"></b></span>
					<span title="ระบบการติดตามงานวางและจัดทำผังเมือง" class="logo-lg"><b id="demo" ></b></span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<!--<a hidden id="exportPdfBtn" class="sidebar-toggle5" role="button" onclick="window.print()" title="พิมพ์/บันทึกไฟล์ PDF">
						<span class="sr-only">Toggle navigation</span>
					</a>-->
					<!-- <a id="isearch" class="sidebar-toggle2" onclick="search_data();" role="button">
						<span class="sr-only">Toggle navigation</span>	  
					</a> -->
						<span class="mainname" style="left:50%;position: absolute;top: 0;height: 50px;line-height: 50px;transform: translateX(-50%);font-size: 20px;color:white;font-weight:600;white-space: nowrap;" class="logo-lg">ระบบการติดตามงานวางและจัดทำผังเมือง</span>					
					<div class="navbar-custom-menu" >
						<ul class="nav navbar-nav">	
							<li class="dropdown user user-menu">
								<a class="dropdown-toggle" style="cursor:default;"> 
									<i class="fa fa-database"></i> <span>โหมด Mockup Database (เข้าถึงข้อมูลโดยตรง)</span>
								</a>
							</li>     
						</ul>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar" id="sideNav"></aside>
			<div class="content-wrapper" style="    ">
				<section class="content" >	
					<section id="mainshow" class="content" >
						<!--<img src="img/loading3.gif" style="width:30%;margin-top:20%;margin-left:40%">-->
					</section>
					<section id="extrashow" class=""></section>
				</section>
			</div>
			<div id="myBtn">
				<button style="margin-bottom:10px"onclick="goto('s4')" class="myBtn2" title="กลับหน้าหลัก"> &nbsp;<i class="fa fa-home"></i>&nbsp; </button><br style="margin-bottom:18px">
				<button onclick="topFunction()"  class="myBtn" title="กลับขึ้นด้านบน"> &nbsp;▲&nbsp; </button>
			</div>
		</div>
		
		<div class="modal fade" id="modal-readtimesnd">
          <div class="modal-dialog">
            <div class="modal-content" style="border-radius:8px">
              <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true"></span></button>
                <h4 id="title-rtsnd" class="modal-title" style="font-family:Kanit;color:black"></h4>
              </div>
              <div class="modal-body" id = "modal-readtimesnd-content">
								
				
			
				
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"></button>
              </div>
            </div>
          </div>
		</div>
		
		<div class="modal fade" id="modal-readtimetrd">
          <div class="modal-dialog">
            <div class="modal-content" style="border-radius:8px">
              <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true"></span></button>
                <h4 id="title-rttrd" class="modal-title" style="font-family:Kanit;color:black"></h4>
              </div>
              <div class="modal-body" id="modal-readtimetrd-content">
								
				
			
				
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"></button>
              </div>
            </div>
          </div>
		</div>
		
		<div class="modal fade" id="modal-rcp">
			<div class="modal-dialog">
				<div class="modal-content" style="border-radius:8px">
				  <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
						<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"></span>
						</button>
						<h4 id="title-rcp" class="modal-title" style="font-family:Kanit;color:black"></h4>
				  </div>
				  <div class="modal-body" id="modal-rcp-content">
									
					
				
					
				  </div>
				  <div class="modal-footer">
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal"></button>
				  </div>
				</div>
			</div>
		</div>
					
</body>
	
	
</html>
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<script>//$.widget.bridge('uibutton', $.ui.button);</script>
<script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.js"></script>
<script src="dist/js/pages/dashboard.js"></script> 
<script src="js/jquery.datetimepicker.js?<?php echo date('Ydmhis')?>"></script>
<script type="text/javascript" src="function_js.js?<?php echo date('Ydmhis')?>"></script>
<script type="text/javascript" >
	function checkInputText(elem){
		var new_txt = $(elem).val();
		new_txt = new_txt.replace(/[^ก-๙]/, '');
		$(elem).val(new_txt);
		//alert(new_txt);
	}
	function readTimeSnd(cp_no,day){
		$.ajax({
			url : 'app/manage.php',
			async : true, 
			type : 'POST',
			data : {
				event: 'r_timesnd-extra',
				cp_no : cp_no,
				day : day
			},
			success : function(data, status, xhr){
				//alert('Post data : '+status);
				$('#title-rtsnd').html('รายละเอียดขั้นตอนย่อย 1');
				$('#modal-readtimesnd > .modal-dialog > .modal-content > .modal-header > button > span').html('&times;');
				$('#modal-readtimesnd > .modal-dialog > .modal-content > .modal-footer > button').html('ปิด');
				
				$('#modal-readtimesnd-content').html(data);
				$('#modal-readtimesnd').modal('show');
			},
			error : function(xhr, status, data){
				alert('Post data : '+status+'\n'+data);
				
			}
		});
	}
	function readTimeTrd(cp_no,day){
		$.ajax({
			url : 'app/manage.php',
			async : true, 
			type : 'POST',
			data : {
				event: 'r_timetrd-extra',
				cp_no : cp_no,
				day : day
			},
			success : function(data, status, xhr){
				//alert('Post data : '+status);
				$('#title-rttrd').html('รายละเอียดขั้นตอนย่อย 2');
				$('#modal-readtimetrd > .modal-dialog > .modal-content > .modal-header > button > span').html('&times;');
				$('#modal-readtimetrd > .modal-dialog > .modal-content > .modal-footer > button').html('ปิด');
				
				$('#modal-readtimetrd-content').html(data);
				$('#modal-readtimetrd').modal('show');
				},
			error : function(xhr, status, data){
				alert('Post data : '+status+'\n'+data);
				
			}
		});
	}
	/*
	function readTimeSnd(cp_no,day){
		$.post('app/manage.php',{
			event:'r_timesnd',
			cp_no : cp_no,
			day : day
		},function(data){	
			//$('#title-rtsnd').html(data.cp_name);
			$('#rt_snd_cp_name').html(data.cp_name);
			$('#rt_snd_sub_name').html(data.sub_name);
			$('#rt_snd_day').html(data.day);
			$('#rt_snd_csd_start').html(data.csd_start);
			$('#rt_snd_csd_stop').html(data.csd_stop);
			$('#modal-readtimesnd').modal('show');
			
		},'json');
	}
	
	function readTimeTrd(cp_no,day){
		$.post('app/manage.php',{
			event:'r_timetrd',
			cp_no : cp_no,
			day : day
		},function(data){	
			//$('#title-rtsnd').html(data.cp_name);
			$('#rt_trd_cp_name').html(data.cp_name);
			$('#rt_trd_sub_name').html(data.trd_name);
			$('#rt_trd_day').html(data.day);
			$('#rt_trd_csd_start').html(data.csd_start);
			$('#rt_trd_csd_stop').html(data.csd_stop);
			$('#modal-readtimetrd').modal('show');			
		},'json');
	}
	*/
	
	$(function(){		
		$.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.		
		$(".cp_date").datetimepicker({
			timepicker:false,
			format:'Y-m-d',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000			
			lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
			onSelectDate:function(dp,$input){
				var yearT=new Date(dp).getFullYear()-0;  
				var yearTH=yearT+543;
				var fulldate=$input.val();
				var fulldateTH=fulldate.replace(yearT,yearTH);
				$input.val(fulldateTH);
			},
		});       
		// กรณีใช้กับ input ต้องกำหนดส่วนนี้ด้วยเสมอ เพื่อปรับปีให้เป็น ค.ศ. ก่อนแสดงปฏิทิน
		$(".cp_date").on("mouseenter mouseleave",function(e){
			var dateValue=$(this).val();
			if(dateValue!=""){
					var arr_date=dateValue.split("-"); // ถ้าใช้ตัวแบ่งรูปแบบอื่น ให้เปลี่ยนเป็นตามรูปแบบนั้น
					// ในที่นี้อยู่ในรูปแบบ 00-00-0000 เป็น d-m-Y  แบ่งด่วย - ดังนั้น ตัวแปรที่เป็นปี จะอยู่ใน array
					//  ตัวที่สอง arr_date[2] โดยเริ่มนับจาก 0 
					if(e.type=="mouseenter"){
						var yearT=arr_date[0]-543;
					}		
					if(e.type=="mouseleave"){
						var yearT=parseInt(arr_date[0])+543;
					}	
					dateValue=dateValue.replace(arr_date[0],yearT);
					$(this).val(dateValue);													
			}		
		});  
	});
	function readCityplanDetail(x){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				event : 'ronly_cp',
				x : x
			},success : function(data, status, xhr){
				$('#title-rcp').html('รายละเอียดข้อมูลผังเมือง');
				$('#modal-rcp > .modal-dialog > .modal-content > .modal-header > button > span').html('&times;');
				$('#modal-rcp > .modal-dialog > .modal-content > .modal-footer > button').html('ปิด');
				$('#modal-rcp').modal('show');
				$('#modal-rcp-content').html(data);
			},error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	 // gotoCityplanDetail(1);
	function gotoCityplanDetail(x){
		$.ajax({
			url : 'app/cityplan_detail.php',
			async : true,
			type : 'POST',
			data : {
				cp_no : x,
			},
			success:function(data, status, xhr){
				$('#mainshow').html(data);
			},
			error:function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	function botcast(event, type, user){$.ajax({
			url: 'chatbot.php',
			async : true,
			type : 'POST',
			data :{
				event: event,
				type : type,
				user : user
			},
			success: function(data ,status, xhr){
				//alert(status+data);
			},error:function(xhr , status , data){
				//alert(status);
			}
		});
	}
</script>