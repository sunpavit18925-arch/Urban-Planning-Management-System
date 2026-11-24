<?php 
	//include 'js/function_db.php';
	include 'js/function_php.php';
	session_name('tutorial');
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
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
		<link rel="stylesheet" href="js/jquery.datetimepicker.css">
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
				$('#mainshow').load('report/tree-chart.php');
				$('#sideNav').load('navA1.php');
			});
		</script>
	</head>
	<body class="hold-transition skin-green-light sidebar-mini ">
		<div class="wrapper" style="font-family: Kanit;">
			<header class="main-header">
				<a class="logo pointer" style="font-family: Kanit;" onclick="goto('a6m');">
					<span title="Urban Planning Monitoring System" class="logo-mini" ><b id="demo2"></b></span>
					<span title="ระบบการติดตามงานวางและจัดทำผังเมือง" class="logo-lg"><b id="demo" ></b></span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<!-- <a id="isearch" class="sidebar-toggle2" onclick="search_data();" role="button">
						<span class="sr-only">Toggle navigation</span>	  
					</a> -->
					<div class="navbar-custom-menu" >	
						<ul class="nav navbar-nav">	
							<li class="dropdown user user-menu"  onclick="goto(999);">
								<a class="dropdown-toggle pointer" data-toggle="dropdown"> 
									<span class="">ออกจากระบบ</span> <i class="fa fa-sign-out"></i>
								</a>
							</li>     
						</ul>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar" id="sideNav"></aside>
			<div class="content-wrapper">
				<section class="content" >	
					<section id="mainshow" class="content" >
						<!--<img src="img/loading3.gif" style="width:30%;margin-top:20%;margin-left:40%">-->
					</section>
				</section>
				<div id="myBtn">
					<button style="margin-bottom:10px"onclick="goto('a6m')" class="myBtn2" title="กลับหน้าหลัก"> &nbsp;<i class="fa fa-home"></i>&nbsp; </button><br style="margin-bottom:18px">
					<button onclick="topFunction()"  class="myBtn" title="กลับขึ้นด้านบน"> &nbsp;▲&nbsp; </button>
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
<script src="js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="function_js.js?<?php echo date('Ydmhis')?>"></script>
<script type="text/javascript"></script>