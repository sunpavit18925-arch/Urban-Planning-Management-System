<?php 
	include '../js/function_php.php';
	session_name('tutorial');
	session_start();
	clear_cp_session();
	$x = 0;
	if($x==0){ ?>
	<style>
	@media print{
			.printx > tbody> tr > td{
				font-size:11px!important;
				padding:2.25px!important;
			}
			.printx > thead> tr > td{
				font-size:12.5px!important;
				padding:3px!important;
			}
			.printx > thead> tr > th{
				font-size:12.5px!important;
				padding:3px!important;
				color:white!important;
				background-color:#295DBD!important;
				
			}
			
			
		}

	</style>
	<?php data_date(TRUE);?>
	<div class="container-fluid">
		<div class="row">
			<h2 class="GHfont" style="text-align:center;font-family:Kanit">Log</h2>
			<!--<h4 class="hidden-md hidden-lg GHfont" style="text-align:center;font-family:Kanit">Log</h4>-->
		</div>
		
		<div class="row unprintable">
			<div class="col-xs-12 col-md-8">
				<div id="div-btn" class="col-xs-4 col-sm-6 col-md-8">
					<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
				</div>
				<div style="margin-top:8px" class="col-xs-8 col-sm-6 col-md-4">
					<select id="log_type" onchange="showLog()" class="pull-right" style="border-radius:8px;font-size:18px;text-align:center;width:100%">
						<option value="2">การจัดการข้อมูล</option>
						<option value="1">การเข้าสู่ระบบ</option>				
					</select>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-4">
				<div style="margin-top:8px" class="col-xs-6 col-sm-6 col-md-6">
					<select id="log_month" class="pull-right" style="border-radius:8px;font-size:18px;text-align:center;padding:0px 3px 0px 3px;width:100%" onchange="showLog()">
						<option selected value="null">เดือน</option>
						<option value="1">ม.ค.</option>
						<option value="2">ก.พ.</option>
						<option value="3">มี.ค.</option>
						<option value="4">เม.ย.</option>
						<option value="5">พ.ค.</option>
						<option value="6">มิ.ย.</option>
						<option value="7">ก.ค.</option>
						<option value="8">ส.ค.</option>
						<option value="9">ก.ย.</option>
						<option value="10">ต.ค.</option>
						<option value="11">พ.ย.</option>
						<option value="12">ธ.ค.</option>
					</select>
				</div>
				<div style="margin-top:8px" class="col-xs-6 col-sm-6 col-md-6">
					<select id="log_year" class="pull-right" style="border-radius:8px;font-size:18px;text-align:center;margin-left:0px;padding:0px 0px 0px 0px ;width:100%" onchange="showLog()">
						<option selected value="null">ปี (พ.ศ.)</option>
						<option value="2023">2566</option>
						<option value="2024">2567</option>
						<option value="2025">2568</option>
					</select>
			</div>	
			</div>
		</div>
		<div class="row" id="info_bar">
			<div class="col-xs-12"  style="display:inline;padding-top:0px;margin-bottom:-15px">
				<h3 style="font-family:Kanit" >จำนวน  : <b id="log_value"></b> เรคคอร์ด</h3>
			</div>
		</div>
		<div style="background-color:transparent!important;margin-top:8px;border:solid red 0px!important" id="logshow">
			<img src="img/loading3.gif" class="center">
		</div>
	</div>


		<script>
		showLog();
			function showLog(){
				$.post('app/pro_log.php',{
					type : $('#log_type').val(),
					year : $('#log_year').val(),
					month : $('#log_month').val()
				},function(data){
					//alert($('#log_type').val());
					$('#logshow').html(data);
				});
			}
		</script>
		<?php
	}else{ ?>
		<script>
			$('#modal-alert').modal('show');
		</script> <?php
	} ?>
	
	
<div class="modal fade" id="modal-alert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
				<img src="img/cancel.png" class="center" style="margin-top:5px">
					<div>
						<h2 style="font-family:Kanit;text-align:center">คุณไม่มีสิทธิ์ในการเข้าถึงข้อมูล</h2>
					</div>				
				
			</div>
            <div class="modal-footer">
				<button type="button" class="btn btn-danger pull-right" data-dismiss="modal" onclick="errorAccess()">ปิด</button>
				<button type="button" class="btn btn-warning pull-right" style="margin-right:8px" data-dismiss="modal" onclick="requestAccess()">ขอสิทธ์ในการเข้าถึงข้อมูล</button>
            </div>
		</div>
    </div>
</div>


<script>
$('#exportPdfBtn').show();

</script>