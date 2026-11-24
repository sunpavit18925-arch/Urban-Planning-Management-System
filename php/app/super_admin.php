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
  <title>ระบบติดตามผังเมือง</title>
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
  <script src="js/jquery-3.5.1.min.js" charset="utf-8"></script>
      <script type="text/javascript">
      $(document).ready(function(){
        $('#mainshow').load('app/report.php');
        $('#sideNav').load('navSA.php');


	
      });
    </script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<style>
		
		.alias {cursor: alias;}
		.all-scroll {cursor: all-scroll;}
		.auto {cursor: auto;}
		.cell {cursor: cell;}
		.col-resize {cursor: col-resize;}
		.context-menu {cursor: context-menu;}
		.copy {cursor: copy;}
		.crosshair {cursor: crosshair;}
		.default {cursor: default;}
		.e-resize {cursor: e-resize;}
		.ew-resize {cursor: ew-resize;}
		.grab {cursor: -webkit-grab; cursor: grab;}
		.grabbing {cursor: -webkit-grabbing; cursor: grabbing;}
		.help {cursor: help;}
		.move {cursor: move;}
		.n-resize {cursor: n-resize;}
		.ne-resize {cursor: ne-resize;}
		.nesw-resize {cursor: nesw-resize;}
		.ns-resize {cursor: ns-resize;}
		.nw-resize {cursor: nw-resize;}
		.nwse-resize {cursor: nwse-resize;}
		.no-drop {cursor: no-drop;}
		.none {cursor: none;}
		.not-allowed {cursor: not-allowed;}
		.pointer {cursor: pointer;}
		.progress {cursor: progress;}
		.row-resize {cursor: row-resize;}
		.s-resize {cursor: s-resize;}
		.se-resize {cursor: se-resize;}
		.sw-resize {cursor: sw-resize;}
		.text {cursor: text;}
		.url {cursor: url(myBall.cur),auto;}
		.w-resize {cursor: w-resize;}
		.wait {cursor: wait;}
		.zoom-in {cursor: zoom-in;}
		.zoom-out {cursor: zoom-out;}
		
		.fluidm {
		  width: 100%;
		  height: auto;
		}
		.btn-fluid {
		width: 100%;
		}
		input,button,select,optgroup,textarea {
		  margin: 1;
		  font-family: inherit;
		  font-size: inherit;
		  line-height: inherit;
		}
		.fluidt {
		  width: 100%;
		  
		}
		#myBtn {
		  display: none;
		  position: fixed;
		  bottom: 7px;
		  right: 7px;
		  z-index: 99;
		 
		  


		}
		.myBtn{ font-size: 18px;
		  border: none;
		  outline: none;
		  color: white;
			cursor: pointer;
		  padding: 15px;
		  border-radius: 50px;
		  opacity: 0.4;
			background-color:#2CCD22;
		}
		.myBtn2{
			 font-size: 18px;
		  border: none;
		  outline: none;
		  color: white;
			cursor: pointer;
		  padding: 15px;
		  border-radius: 50px;
		  opacity: 0.4;
			background-color:red;
		}
		.myBtn:hover {
			opacity: 1;
			background-color: #2CCD22;
		 
		}		
		.myBtn2:hover {
			opacity: 1;
		  background-color: red;
		 
		}
		
		.centre {
		   padding: 70px 0;
		}
		

		.rate {
			float: left;
			height: 46px;
			padding: 0 10px;
		}
		.rate:not(:checked) > input {
			position:absolute;
			top:-9999px;
		}
		.rate:not(:checked) > label {
			float:right;
			width:1em;
			overflow:hidden;
			white-space:nowrap;
			cursor:pointer;
			font-size:30px;
			color:#ccc;
		}
		.rate:not(:checked) > label:before {
			content: '★ ';
		}
		.rate > input:checked ~ label {
			color: #ffc700;    
		}
		.rate:not(:checked) > label:hover,
		.rate:not(:checked) > label:hover ~ label {
			color: #deb217;  
		}
		.rate > input:checked + label:hover,
		.rate > input:checked + label:hover ~ label,
		.rate > input:checked ~ label:hover,
		.rate > input:checked ~ label:hover ~ label,
		.rate > label:hover ~ input:checked ~ label {
			color: #c59b08;
		}
		#data1, #data2, #data3, #data4{
			display : none;		
		}
		hr.mhr {
		  border-top: 2px solid #B1B1B1;
		  opadity: 0.1;
		}
		hr.hrli{
			padding : -20px -20px -20px -20px;
			border-top: 10px solid #B1B1B1;
			opadity: 0.1;
		}
		.Gfont{
			text-shadow: 0 0 6px aqua;
			border-style: solid;
			border-color: transparent  transparent #D9D9D9 transparent; 
			border-width: 1px;
		}
		
		.GHfont{
			color:#0AAAFA;
			<!--text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;-->
		}
		
		.GHfont-mini{
			color: #0AAAFA;
			<!--text-shadow: 1px 0 black, 0  1px black, -1px 0 black, 0 -1px black;-->
		}
		
		.img {
			position: center;
		}
		
		.hover_img {
			position: center;
			display: none;
		}
		
		.trcolor:hover .hover_img {
			display:inline;			
		}
		
		.trcolor:hover .ihover {
			display:none;
		}
		
		.mform{
			background-color:transparent;
			border: none;
		}
		
		.list-alt:hover > a{
			color:black;
		}
		
		.gear:hover > a{
			color:black;
			
		}
		.trash:hover > a {
			color : black;
			
		}
		.plus:hover > a{
			color:black;
		}
		
		.desktop:hover > a{
			color:black;
		}

		.list-alt > a{
			color:#251BB8;
		}
		
		.gear > a{
			color:orange;
		}
		
		.trash > a {
			color:red;
		}
		
		.plus > a{
			color:#3ADE0E;
		}
		
		.desktop > a{
			color:	#22A0DE;
		}
		
		.list-alt >a{
			color: #CAEFE;
		}

		.form-control{
			border-radius: 8px;
		}
		
		.modal-content{
			border-radius :8px;
		}
		
		tr:hover{
			background-color: #DCF3FF;
			
		}
		
		.btn-1 {
			border: solid;
			border-color: #00a65a;
			border-radius: 50px;
		    background-image: radial-gradient(circle , white , orange);
		}
		
		.btnx {			
			border-radius: 50px;
			border:solid #0B5773 2px;
		}
		
		.ssearch {
		  padding: 9px 9px;
		  margin-top: 7px;
		  margin-bottom: 7px;
		  margin-left:8px;
		  border:solid;
		  border-color:#757575;
		  border-width:1.5px;
		  border-radius: 12.5px;
		}
		
		#ssico{
			position:absolute;
		  padding: 11px 7px;
		  margin-top: 7px;
		  margin-bottom: 7px;
		  margin-left:-31px
		}		
		.ibody {
			-webkit-user-select: none;
		    -ms-user-select: none; 
			user-select: none; 
		  }
		  
		@media print {
		  .hidden-print {
		    display: none !important;
	      }
		}
		
		.br-sql {
			white-space: pre-wrap;       /* Since CSS 2.1 */
			white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
			white-space: -pre-wrap;      /* Opera 4-6 */
			white-space: -o-pre-wrap;    /* Opera 7 */
			word-wrap: break-word;       /* Internet Explorer 5.5+ */
		}
		
		.iline{
			display:inline;
		}
		
		tbody > tr {
			background-color : white;
		}
		
		.center {
			display: block;
			margin-left: auto;
			margin-right: auto;
			margin-top: 75px;
			width : 30%;
		}
		
		.div-center {
			display: block;
			margin-left: auto;
			margin-right: auto;
			margin-top: 0px;
		}

		#red { background-color: #A43741; }
		#blue { background-color: #2F4073; }
		#green { background-color: #2D882D; }
		#yellow { background-color: #FFC107; }

		.rating{
		  unicode-bidi: bidi-override;
		  direction: rtl;
		  text-align: center;
		}
		
		.rating > span {
		  display: inline-block;
		  position: relative;
		  width: 20px;
		  height: 20px;
		}
		
		.rating > span:hover{
		  cursor:pointer;
		}
		
		/* .rating > span:hover:before, */
		.rating > span#red:hover ~ span:before,
		.rating > span#blue:hover ~ span:before,
		.rating > span#green:hover ~ span:before,
		.rating > span#yellow:hover ~ span:before, {
		  content: "";
		  position: absolute;
		  left: 0;
		  background-color: white;
		  opacity: .25;
		  width: 20px;
		  height: 20px;
		}

		.pagin > a{	
			padding: 8px 16px;
			border-style:solid;
			border-color:black;
			
			background-color:white;
		}

		.marquee {
		  position: relative;
		  width: 100vw;
		  max-width: 100%;
		  height: 200px;
		  overflow-x: hidden;
		}

		.track {
		  position: absolute;
		  white-space: nowrap;
		  will-change: transform;
		  animation: marquee 60s linear infinite;
		}

		@keyframes marquee {
		  from { transform: translateX(0); }
		  to { transform: translateX(-50%); }
		}
				

	</style>

</head>
<body class="hold-transition skin-green-light sidebar-mini ">
<div class="wrapper" style="font-family: Kanit;">
  <header class="main-header">
    <a class="logo pointer" style="font-family: Kanit;" onclick="goto(0);">
      <span class="logo-mini" ><b id="demo2"></b></span>
      <span  class="logo-lg"><b id="demo"></b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

	  <a id="isearch" class="sidebar-toggle2" onclick="search_data();" role="button">
	    <span class="sr-only">Toggle navigation</span>	  
	  </a>
	  

		
	

	  

	  
	  
	  
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
  <aside class="main-sidebar" id="sideNav">  
 
  
	
	

  </aside>


  <div class="content-wrapper">
    <section class="content" >	
		<section id="mainshow" class="content" >
		 <!--<img src="img/loading3.gif" style="width:30%;margin-top:20%;margin-left:40%">-->
		
		</section>
	</section>
	<div id="myBtn">
		<button style="margin-bottom:10px"onclick="goto('a5')" class="myBtn2" title="กลับหน้าหลัก"> &nbsp;<i class="fa fa-home"></i>&nbsp; </button><br style="margin-bottom:18px">
		<button onclick="topFunction()"  class="myBtn" title="กลับขึ้นด้านบน"> &nbsp;&nbsp;▲&nbsp;&nbsp; </button>
	</div>
 
  </div>
</div>





</body>
</html>
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<script>
	$.widget.bridge('uibutton', $.ui.button);
</script>
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
<script type="text/javascript">	
	$('.myBtn2').hide();
	let mybutton = document.getElementById("myBtn");
	window.onscroll = function() {scrollFunction()};
	function scrollFunction() {
	  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		mybutton.style.display = "block";
	  } else {
		mybutton.style.display = "none";
	  }
	}
	

	
	
	
	function topFunction() {
	  document.body.scrollTop = 0;
	  document.documentElement.scrollTop = 0;
	}
	function searchdata(){
		$.post('apps/search_eqm.php',{
            sdt: $('#ssearch').val()            
          },function(data){
            $('#mainshow').html(data);
          });
		
	}
	function search_data(x){
		//alert(x);
		$('#mainshow').load('app/search.php');
	}

	var i = 0;
	var txt = 'ระบบติดตามผังเมือง';
	var speed = 75;
	function typeWriter() {
	  if (i < txt.length) {
		//document.getElementById("demo").innerHTML += txt.charAt(i);
		$('#demo').html($('#demo').html()+txt.charAt(i));		
		//$('#demo').html(function(){	return $('#demo').html()+txt.charAt(i);	});
		
		i++;
		setTimeout(typeWriter, speed);
	  }
	}
	typeWriter()
	
	
	var j = 0;
	var txt2 = 'DPT';
	var speed2 = 150;
	function typeWriter2() {
	  if (j < txt2.length) {
		document.getElementById("demo2").innerHTML += txt2.charAt(j);
		
		j++;
		setTimeout(typeWriter2, speed2);
	  }
	}
	typeWriter2()
	
	
	

</script>
