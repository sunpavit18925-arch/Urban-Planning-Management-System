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
			
		}
		@page{
			size:A4 landscape;
			margin:10mm 4mm;
		}
		


		
	}
</style>
<?php data_date(TRUE);?>
<div class="col-12">
	<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"> ปรับปรุงขั้นตอนผังเมืองรวมเมือง/ชุมชน</h2>
	<h3 class="hidden-md hidden-lg GHfont unprintable" style="font-family: Kanit;text-align:center">ปรับปรุงขั้นตอน<br>ผังเมืองรวมเมือง/ชุมชน</h3>
	<div class="row" id="info_bar">
		<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_value">จำนวน  <b id="cp_value"></b> ผัง</h3>
	</div>
</div>
<div class="container-fluid">
	<div class="row unprintable">
		<div id="div-btn" class="col-xs-12 col-md-12">
				<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
		</div>

	</div>
	
	<div class="unprintable row search-frame">
			<text class="search-title">ค้นหาข้อมูล</text>
			<div class="search-content-area">
				<div class="search-row">
					<div>ส่วนผังเมืองรวมที่รับผิดชอบ :  </div>
					<select id="search_aff" onchange="searchData('aff')">
						<option value="" selected>เลือกส่วนผังเมืองรวม</option>
						<option value="1">สำนักผังเมืองรวมส่วนที่ 1</option>
						<option value="2">สำนักผังเมืองรวมส่วนที่ 2</option>
						<option value="3">สำนักผังเมืองรวมส่วนที่ 3</option>
						<option value="4">สำนักผังเมืองรวมส่วนที่ 4</option>
					</select>
				</div>				
				<div class="search-row">
					<div style="">จังหวัด :  </div>
					<select id="search_pvn" onchange="searchData('pvn');" >
						<option value="" selected>เลือกจังหวัด</option>
						<?php 
						$sql = "SELECT * FROM ref_province ORDER BY pvn_name ASC";
						$result = selectSql($sql);
						foreach($result as $row){
							echo '<option value="'.$row['pvn_id'].'">'.$row['pvn_name'].'</option>';
						}
						?>
					</select>
				</div>
				<div class="search-row">
					<div>ชื่อผัง :  </div>
					<input id="search_name" onkeyup="checkInputText(this);searchData('name')" placeholder="ระบุชื่อผังเมือง . . . . . . .">
					
				</div>
				<div class="search-note" style="">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น </div>
			</div>
		</div>
	
	
	
	
	
	
	<div id="cityplanshow" class="" style="border-color:transparent;">
		<img src="img/loading3.gif" class="center">
	</div>
</div>


<div class="modal fade" id="modal-cityplan" style="pointer-events: none;">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มข้อมูล</h4>
            </div>
            <div class="modal-body">
				<form>
					<div class="form-group">
						<label for="cp_province" >จังหวัด<span style="color:red;"> *</span></label>
						<select onchange="checkData()" class="form-control" id="cp_province" required>
							<option selected value="">เลือกจังหวัด</option>
							<?php 
							$sql = "SELECT * FROM ref_province ORDER BY pvn_name ASC";
							$result = selectSql($sql);
							foreach($result as $row){ ?>
							<option value="<?php echo $row['pvn_id'];?>"><?php echo $row['pvn_name'];?></option>
							<?php }?>
						</select>
					</div>
		
					<div class="form-group">
						<label for="cp_name">ชื่อผังเมืองรวม<span style="color:red;"> *</span></label>
						<input onkeyup="checkData()" type="text" class="form-control" id="cp_name" value="">
					</div>
				
					<div class="row">
					
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp_consider">ผังพิจารณาการดำเนินการ</label>
							<select class="form-control" id="cp_consider" onchange="changeConsider()">		
								<option value="0" selected>ไม่ใช่</option>
								<option value="1">ใช่</option>								
							</select>
						</div>
					
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_budget">ปีงบประมาณ</label>
							<input type="text" class="form-control" id="cp_budget" value="">
						</div>
						<div class="form-group col-xs-12 col-md-5">
							<label for="cp_type">ประเภทผังเมือง</label>
							<select class="form-control" id="cp_type" >
								<?php
									$sql ="SELECT * FROM ref_type";
									$result_type = selectSql($sql);
									foreach($result_type as $row){
								?>
								<option value="<?php echo $row['type_id']; ?>"><?php echo $row['type_name']; ?></option>
									<?php } ?>
							</select>
						</div>
			
						<div class="form-group col-xs-12 col-md-3">
							<label for="cp_eec">ผัง กลุ่ม EEC</label>
							<select class="form-control" id="cp_eec" >
								<option selected value="N">ไม่ใช่</option>
								<option value="Y">ใช่</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_scope">ขอบเขตผัง<span style="color:red;"> *</span></label>
							<select onchange="checkData()" class="form-control" id="cp_scope" >
								<option selected value="">เลือกขอบเขตผัง</option>
								<?php 
									$sql ="SELECT * FROM ref_scope";
									$result = selectSql($sql);
									foreach($result as $row ){ 
								?>
								<option value="<?php echo $row['scope_id'];?>"><?php echo $row['scope_name'];?></option>
								<?php } ?>
							</select>
						</div>
					
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_status">สถานภาพผัง<span style="color:red;"> *</span></label>
							<select onchange="checkData()" class="form-control" id="cp_status" >
								<option selected value="">เลือกสถานภาพผัง</option>
								<?php 
									$sql ="SELECT * FROM ref_status";
									$result = selectSql($sql);
									foreach($result as $row ){ 
								?>
								<option value="<?php echo $row['status_id'];?>"><?php echo $row['status_name'];?></option>
								<?php } ?>
							</select>
						</div>
				
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_stage">ขั้นตอน<span style="color:red;"> *</span></label>
							<select onchange="checkData()" class="form-control" id="cp_stage" >
								<option selected value="">เลือกขั้นตอน</option>
								<?php 
									$sql ="SELECT * FROM ref_stage ORDER BY stage_asc";
									$result = selectSql($sql);
									foreach($result as $row ){ 
								?>
								<option value="<?php echo $row['stage_id'];?>"><?php echo $row['stage_name'];?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_owner">เจ้าของผัง</label>
							<select class="form-control" id="cp_owner">
								<?php 
									$sql ="SELECT * FROM ref_operate_fst";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['opt_fst_id'];?>"><?php echo $row['opt_fst_name'];?></option>									
								<?php } ?>
		
							</select>
						</div>					
					
					
					
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_operator">ผู้ดำเนินการ</label>
							<select class="form-control" id="cp_operator">
								<?php 
									$sql ="SELECT * FROM ref_operate_snd ORDER BY opt_snd_asc ASC";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['opt_snd_id'];?>"><?php echo $row['opt_snd_name'];?></option>									
								<?php } ?>
		
							</select>
						</div>
						
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_effective">มีผลบังคับใช้</label>
							<select class="form-control" id="cp_effective">
								<?php 
									$sql ="SELECT * FROM ref_effective";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['eff_id'];?>"><?php echo $row['eff_name'];?></option>									
								<?php } ?>
		
							</select>
						</div>			
					</div>
					<input type="hidden" name="cp_no" class="form-control" id="cp_no" value="" >
					<input type="hidden" name="cp_id" class="form-control" id="cp_id" value="" >
					<input type="hidden" id="event" value="">
					<input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
				</form>
			</div>
            <div style="margin-top:-10px" class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
                <button id="btnSave" type="button" class="btn btn-success pull-right" onclick="cpSave();">บันทึก</button>
            </div>
		</div>
    </div>
</div>


<div class="modal fade" id="modal-step" style="pointer-events: none;">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มขั้นตอน</h4>
            </div>
            <div class="modal-body">
				<form>
					<div class="row" >
						<div id="div-fst-step" class="form-group col-xs-12 col-md-12">
							<label for="cp_step">ขั้นตอนดำเนินการหลัก</label>
							<select class="form-control" id="cp_step" onchange="changeFststep();">
								<option value="0">ไม่ระบุขั้นตอน</option>
								<?php 
									$sql ="SELECT * FROM ref_up_fst_step WHERE step_id > 0";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['step_id'];?>"><?php echo 'ขั้นตอนที่ ',$row['step_id'],' ',$row['step_name'];?></option>									
								<?php } ?>
							</select>
						</div>
						
						<div id="div-petition" class="form-group col-xs-12 col-md-12">
							<label for="cp_petition">คำร้อง : </label>
							<select class="form-control" id="cp_petition" onchange="changeFstdate()">
								<option selected value="0">ระบุคำร้อง</option>
								<option value="1">มีคำร้อง</option>
								<option value="2">ไม่มีคำร้อง</option>
							</select>
							<!--
								<input type="radio" style="font-size: 2em;"name="cp_petition" id="cp_petition_no">
								<label for="cp_petition">ไม่มีคำร้อง </label>
								<input type="radio" name="cp_petition" id="cp_petition_yes">
								<label for="cp_petition">มีคำร้อง </label>	
							-->						
						</div>
						
					</div>
					<div class="row" id="div-step-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_start">วันที่เริ่มต้น(ขั้นตอนหลัก)</label>
							<input class="form-control cp_date" type="text" id="cp_step_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="changeFstdate()">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_stop">วันที่สิ้นสุด (ขั้นตอนหลัก)</label>
							<input class="form-control " type="text" id="cp_step_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					
					</div>
					<div class="row" id="div-step-1">
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp_step_1">ขั้นตอนดำเนินการรอง</label>
							<select class="form-control" id="cp_step_1" onchange="changeSndstep()">
								<option value="0">ไม่ระบุขั้นตอน</option>
							</select>
						</div>
					</div>
					<div class="row" id="div-step-1-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_start">วันที่เริ่มต้น (ขั้นตอนรอง 1)</label>
							<input class="form-control cp_date" type="text" id="cp_step_1_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="changeSnddate()">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_stop">วันที่สิ้นสุด (ขั้นตอนรอง 1)</label>
							<input class="form-control " type="text" id="cp_step_1_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					
					
				<div class="row" id="div-step-2">
					<div class="form-group col-xs-12 col-md-12">
						<label for="cp_step_2">ขั้นตอนดำเนินการรอง2</label>
						<select class="form-control" id="cp_step_2" onchange="changeTrdstep()">
							<option value="0">ไม่ระบุขั้นตอน</option>
						</select>
					</div>
				</div>
				<div class="row" id="div-step-2-date">
					<div class="form-group col-xs-6 col-md-6">
						<label for="cp_step_2_start">วันที่เริ่มต้น ขั้นตอนรอง 2)</label>
						<input class="form-control cp_date" type="text" id="cp_step_2_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="changeTrddate()">
						<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
					</div>
						
					<div class="form-group col-xs-6 col-md-6">
						<label for="cp_step_2_stop">วันที่สิ้นสุด ขั้นตอนรอง 2)</label>
						<input class="form-control cp_date" type="text" id="cp_step_2_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
						<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
					</div>
				</div>					
				<input type="hidden" name="cp_no" class="form-control" id="cp_no" value="" >
				<input type="hidden" name="cp_id" class="form-control" id="cp_id" value="" >
				<input type="hidden" id="event" value="">
				<input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
			</form>
			</div>
        <div  class="modal-footer">
			<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
            <button type="button" class="btn btn-success pull-right" onclick="stepSavex()">บันทึก</button>
        </div>
		</div>
	</div>
</div>


<script>

	$('#aff_ids').hide();
	$('#exportPdfBtn').show();
	searchCP();
	function searchCP(){
		$('#cityplanshow').html('<img src="img/loading3.gif" class="center">');
		$.post('report/pro_cityplanX-stage.php',{
			type : $('#type').val(),
			aff_id :  $('#aff_ids').val(),
			pvn_name : $('#pvn_names').val(),
			cp_name : $('#cp_names').val()
		},function(data){	
			$('#cityplanshow').html(data);
			$('#cp_name').val('');
			$('#pvn_name').val('');
			$('#aff_id').val('');
		});
	}
	

	function checkData(){		
		if($('#cp_stage').val() ==""){
			$('#btnSave').removeAttr('onclick');
			$('#btnSave').addClass('disabled');
		}else{
			$('#btnSave').attr('onclick','cpSave()');
			$('#btnSave').removeClass('disabled');
		}
	}
	function cpSave(){
		if ($('#cp_stage').val()==''){
				alert('กรุณาระบุขั้นตอน');			
		}		
		else{
			$.post('app/manage.php',{
			cp_no:$('#cp_no').val(),
			cp_stage:$('#cp_stage').val(),
			event:$('#event').val(),
			user_id:$('#user_id').val()
			 },function(data) {
				$('#modal-cityplan').modal('hide');
				alert(data);
				$('#mainshow').load('report/manage_cityplanX-stage.php');
			 //console.log(data);
			 //$('#mainshow').html(data);
			 });
		}
	}
	

	function cpEditx(x){		
		$.post('app/manage.php',{
		  event:'ru_cp',
		  cp_no: x
		  },function(data) {
			  //alert(data);
		  $('select').attr('disabled',true);
		  $('#cp_stage').attr('disabled',false);
		  $('#cp_no').val(data.cp_no);
		  $('#cp_id').val(data.cp_id);
		  $('#cp_type').val(data.cp_type);
		  $('#cp_eec').val(data.cp_eec);
		  $('#cp_province').val(data.cp_province);
		  $('#cp_name').val(data.cp_name);
		  $('#cp_budget').val(data.cp_budget);
		  $('#cp_owner').val(data.cp_owner);
		  $('#cp_operator').val(data.cp_operator);
		  $('#cp_scope').val(data.cp_scope);
		  $('#cp_status').val(data.cp_status);
		  $('#cp_stage').val(data.cp_stage);
		  $('#cp_effective').val(data.cp_effective);		 
		  $('#event').val('u_cp-update_stage');
		  $('#cp_consider').val(data.cp_consider);		 	  
		  $('#user_id').val(data.user_id);
		  $('.modal-title').html("แก้ไขข้อมูล");
		  <?php 
		  if($_SESSION['data_access']!=2){
			  echo '$("#cp_province").attr("readonly",true);';
			  echo '$("#cp_name").attr("readonly",true);';
			  echo '$("#cp_consider").attr("readonly",true);';
			  echo '$("#cp_budget").attr("readonly",true);';
			  echo '$("#cp_type").attr("readonly",true);';
			  echo '$("#cp_eec").attr("readonly",true);';
			  echo '$("#cp_scope").attr("readonly",true);';
			  echo '$("#cp_status").attr("readonly",true);';
			  echo '$("#cp_owner").attr("readonly",true);';
			  echo '$("#cp_operator").attr("readonly",true);';
			  echo '$("#cp_effective").attr("readonly",true);';
		  }		  
		  ?>
		  $('#modal-cityplan').modal('show');
		  checkData();
		},'json');
	}
	
	function searchData(type){
		var x = '';
		if(type == 'name'){
			x = $('#search_name').val();
			$('#search_pvn').val('');
			$('#search_aff').val('');
		}else if(type == 'pvn'){
			x = $('#search_pvn').val();
			$('#search_name').val('');
			$('#search_aff').val('');
		}else if(type == 'aff'){
			x = $('#search_aff').val();
			$('#search_pvn').val('');
			$('#search_name').val('');
		}
		$.ajax({
			url : 'report/pro_cityplanX-stage.php',
			async :true,
			type : 'POST',
			data : {
				x : x,
				type : type
			},success : function(data, status, xhr){
				$('#cityplanshow').html(data);
			},error : function(xhr, status, data){
				alert('Search : '+status+'\n'+data);
			}
			
		});
	}
	
</script>
	
	