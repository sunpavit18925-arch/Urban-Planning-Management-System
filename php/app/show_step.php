<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	clear_cp_session();
	?>
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
			text-align:center;
			 
		}
		@page{
			size:A4 portrait;
			margin:10mm 4mm;
		}
				
	}
	

	</style>
	<?php data_date(TRUE);?>
	<div class="container-fluid">
		<h2 class="GHfont unprintable" style="text-align:center;font-family:Kanit">ขั้นตอน</h2>
		<h2 hidden class="GHfont printable" style="text-align:center;font-family:Kanit">ขั้นตอน</h2>
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-12 ">
				<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>

		</div>	
		
		<div style="display:none" class="row" id="info_bar">
			<div class="col-xs-12 col-md-12"  style="display:inline;padding-top:0px;margin-bottom:-15px">
				<h3 style="font-family:Kanit" >จำนวน  : <b id="pvn_value"></b> จังหวัด </h3>
			</div>
		</div>
	
		<div id="stepshow" style="padding-top:8px;margin-bottom:0px;overflow:hidden">
			<div class="table-responsive" style="overflow:hidden;overflow-x:auto">
			<table class="table table-bordereds">
				<thead>
					<tr>
						<th style="background-color: #295DBD!important;color:white!important;text-align:center">ขั้นตอน</th>
						<th style="background-color: #295DBD!important;color:white!important;text-align:center">รายละเอียดการดำเนินการ</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sql = "SELECT * FROM ref_up_fst_step WHERE step_id > 0";
					$result = selectSql($sql);
					foreach($result as $row){?>
					<tr style="font-weight:600">
						<td style="background:#b7dbff!important;text-align:center"><?= $row['step_id'];?></td>
						<td style="background:#b7dbff!important;text-align:left"><?= $row['step_name'];?></td>
					</tr>
					<?php 
					$sql = "SELECT * FROM ref_up_snd_step WHERE sub_step = '".$row['step_id']."' AND sub_no != 0";
					$result = selectSql($sql);
					foreach($result as $tow){ ?>
					<tr>
						<td style="text-align:center"><?= $tow['sub_step'].'.'.$tow['sub_no'];?></td>
						<td style="text-align:left"><?= $tow['sub_name'];?></td>
					</tr>
					<?php } ?>
					
					
					<?php } ?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
	



<script>

</script>