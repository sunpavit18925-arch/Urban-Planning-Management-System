<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();


if($_SESSION['aff_id']!=''){
	if($_SESSION['aff_id']>0){
		echo "<script>$('#aff_ids').val('".$_SESSION['aff_id']."');</script>";
		unset($_SESSION['aff_id']);
	}else{
		
	}
}else{
	if(isset($_SESSION['cp_name'])){
		echo "<script>$('#cp_names').val('".$_SESSION['cp_name']."');</script>";
		unset($_SESSION['cp_name']);
	}
	else{
		echo "<script>$('#cp_names').val('');</script>";
	}
	if(isset($_SESSION['pvn_name'])){
		echo "<script>$('#pvn_names').val('".$_SESSION['pvn_name']."');</script>";
		unset($_SESSION['pvn_name']);
	}
	else{
		echo "<script>$('#pvn_names').val('');</script>";
	}
}

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
	<h2 class="hidden-sm hidden-xs GHfont unprintable" style="font-family: Kanit;text-align:center"> จัดการข้อมูลผังเมืองรวมเมือง/ชุมชน</h2>
	<h2 hidden class="GHfont printable" style="font-family: Kanit;text-align:center">ผังเมืองรวมเมือง/ชุมชน</h2>
	<h3 class="hidden-md hidden-lg GHfont unprintable" style="font-family: Kanit;text-align:center"> จัดการข้อมูล<br>ผังเมืองรวมเมือง/ชุมชน</h3>
	<div hidden class="row" id="info_bar">
		<h3 style="font-family:Kanit;text-align: center;margin: 0px;" >จำนวน  <b id="cp_value"></b> ผัง </h3>
	</div>
	<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
</div>
<div class="container-fluid">
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_goback" onclick="goto('a6m');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn2" class="col-xs-6 col-md-6">
				<?php if($_SESSION['data_access']=='9'){?>
					<button onclick="cpAdd()" class="btn btn-primary pull-right " style="margin-top:6px;">เพิ่มผัง</button>
				<?php } ?>
			</div>
			<div class="col-xs-12">
				<div class="unprintable row search-frame">
					<text class="search-title">ค้นหาข้อมูล</text>
					<div class="search-content-area">
						<div class="search-row" id="search_aff_ids">
							<div>ส่วนผังเมืองรวมที่รับผิดชอบ :  </div>
							<select id="aff_ids" onchange="$('#pvn_names').val('');$('#cp_names').val('');searchCP();">
								<option value="" selected>เลือกส่วนผังเมืองรวม</option>
								<option value="1">สำนักผังเมืองรวมส่วนที่ 1</option>
								<option value="2">สำนักผังเมืองรวมส่วนที่ 2</option>
								<option value="3">สำนักผังเมืองรวมส่วนที่ 3</option>
								<option value="4">สำนักผังเมืองรวมส่วนที่ 4</option>
							</select>
						</div>				
						<div class="search-row">
							<div style="">จังหวัด :  </div>
							<select id="pvn_names" onchange="$('#aff_ids').val('');$('#cp_names').val('');searchCP();">
								<option value="" selected>เลือกจังหวัด</option>
								<?php 
								$sql = "SELECT * FROM ref_province ORDER BY pvn_name ASC";
								$result = selectSql($sql);
								foreach($result as $row){
									echo '<option value="'.$row['pvn_name'].'">'.$row['pvn_name'].'</option>';
								}
								?>
							</select>
						</div>
						<div class="search-row">
							<div>ชื่อผัง :  </div>
							<input id="cp_names" onkeyup="$('#aff_ids').val('');$('#pvn_names').val('');checkInputText(this);searchCP();" placeholder="ระบุชื่อผังเมือง . . . . . . .">
							
						</div>
						<div class="search-note" style="">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น </div>
					</div>
				</div>
			</div>
			<!--
			<div id="div-select" class="col-xs-6 col-md-6">
				<select id="aff_ids" onchange="$('#cp_names').val('');$('#pvn_names').val('');searchCP();" style="margin-top:6px;height:34px;width:100%;border-radius:4px">
					<option value="">เลือกส่วนผังเมืองรวม</option>
				</select>
			</div>
			</div>
			<div class="row unprintable">
			<div id="div-search_pvn" class="col-xs-6 col-md-6">
				<input id="pvn_names" onkeyup="checkInputText(this);$('#aff_ids').val('');searchCP();" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยจังหวัด">
			</div>
			<div id="div-search_name" class="col-xs-6 col-md-6">
				<input id="cp_names" onkeyup="checkInputText(this);$('#aff_ids').val('');searchCP();" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยชื่อผัง">
			</div>
			-->
		</div>
		
		<div id="cityplanshow" class="" style="border-color:transparent;">
			<img src="img/loading3.gif" class="center">
		</div>
</div>


<div class="modal fade" id="modal-cityplan" style="">
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
								<?php 
								$sql = "SELECT * FROM ref_consider WHERE consider_status = 1 ";
								$result = selectSql($sql);
								$i = count($result);
								if($i == 0){
									echo '<option value="0" selected>ไม่ใช่</option>';
									echo '<option value="1">ใช่</option>';
								}else{
									echo '<option value="0" selected>ไม่ระบุ</option>';
									foreach($result as $row){
										echo '<option value="'.$row['consider_id'].'">'.$row["consider_name"].'</option>';	
									}
								}						
								?>
							</select>
						</div>
						
						<div class="form-group col-xs-12 col-md-6">
							<label for="cp_date_notice">วันที่ประกาศบังคับใช้</label>
							<input type="text" class="form-control cp_date" id="cp_date_notice" value="">
						</div>
						<div class="form-group col-xs-12 col-md-6">
							<label for="cp_date_exp">วันที่สิ้นสุดบังคับใช้</label>
							<input type="text" class="form-control cp_date" id="cp_date_exp" value="">
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


<div class="modal fade" id="modal-step" style="">
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
							<select class="form-control" id="cp_step" onchange="changeFststepX()">
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
							<select class="form-control" id="cp_petition" onchange="changePetition()">
								<option selected value="0">ระบุคำร้อง</option>
								<option value="1">มีคำร้อง</option>
								<option value="2">ไม่มีคำร้อง</option>
							</select>					
						</div>
						
					</div>
					
					<!--
					<div class="row" id="div-step-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_start">วันที่เริ่มต้นขั้นตอนหลัก (ตามแผน)</label>
							<input class="form-control cp_date" type="text" id="cp_step_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="changeFstdate()">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_stop">วันที่สิ้นสุด ขั้นตอนหลัก (ตามแผน)</label>
							<input class="form-control " type="text" id="cp_step_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					
					</div>
					-->
					<div class="row" id="div-step-1">
					<hr style="border:solid 1px #dbdbdb;max-width:100%;margin: 4px 0px 12px;border-top:transparent">
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp_step_1">ขั้นตอนดำเนินการย่อย 1</label>
							<select class="form-control" id="cp_step_1" onchange="changeSndstepX()">
								<option value="0">ไม่ระบุขั้นตอน</option>
							</select>
						</div>
					</div>
					<!--
					<div hidden class="row" id="div-step-1-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_start">วันที่เริ่มต้นขั้นตอนย่อย 1 (ตามแผน)</label>
							<input class="form-control cp_date" type="text" id="cp_step_1_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="changeSnddate()">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_stop">วันที่สิ้นสุดขั้นตอนย่อย 1 (ตามแผน)</label>
							<input class="form-control " type="text" id="cp_step_1_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>-->
					<div class="row" id="div-step-1-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_start_opt">วันที่เริ่มต้นขั้นตอนย่อย 1 (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="cp_step_1_start_opt" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="" >
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_stop_opt">วันที่สิ้นสุดขั้นตอนย่อย 1 (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="cp_step_1_stop_opt" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					
				<div class="row" id="div-step-2">
					<hr style="border:solid 1px #dbdbdb;max-width:100%;margin: 4px 0px 12px;border-top:transparent">
					<div class="form-group col-xs-12 col-md-12">
						<label for="cp_step_2">ขั้นตอนดำเนินการย่อย2</label>
						<select class="form-control" id="cp_step_2" onchange="changeTrdstepX()">
							<option value="0">ไม่ระบุขั้นตอน</option>
						</select>
					</div>
				</div>
				<!--
				<div class="row" id="div-step-2-date">
					<div class="form-group col-xs-6 col-md-6">
						<label for="cp_step_2_start">วันที่เริ่มต้นขั้นตอนย่อย 2(ตามแผน)</label>
						<input class="form-control cp_date" type="text" id="cp_step_2_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="changeTrddate()">
						<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
					</div>
						
					<div class="form-group col-xs-6 col-md-6">
						<label for="cp_step_2_stop">วันที่สิ้นสุดขั้นตอนย่อย 2 (ตามแผน)</label>
						<input class="form-control " type="text" id="cp_step_2_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
						<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
					</div>
				</div>
				-->
				<div  class="row" id="div-step-2-date">
					<div class="form-group col-xs-6 col-md-6">
						<label for="cp_step_2_start_opt">วันที่เริ่มต้นขั้นตอนย่อย 2 (ตามผลดำเนินการ)</label>
						<input class="form-control cp_date" type="text" id="cp_step_2_start_opt" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="">
						<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
					</div>
					
					<div class="form-group col-xs-6 col-md-6">
						<label for="cp_step_2_stop_opt">วันที่สิ้นสุดขั้นตอนย่อย 2 (ตามผลดำเนินการ)</label>
						<input class="form-control cp_date" type="text" id="cp_step_2_stop_opt" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
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
				<button type="button" class="btn btn-success pull-right" onclick="stepSaveUU()">บันทึก</button>				
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal-note" style="">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">หมายเหตุรวม</h4>
            </div>
            <div class="modal-body">
				<form id="note_area">
				</form>
			</div>
			<div  class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>			
			</div>
		</div>
	</div>
</div>


<script>
	$('#exportPdfBtn').show();
	function openStep(x){
		$.post('app/show_csd.php',{
			cp_no : x,
			page : $('#select_table').val()
		},function(data){
			$('#mainshow').html(data);
		});
	}
	
	
	function X_cityplan_step(x){
		$('#div-step-date').hide();
		$('#div-step-1-date').hide();
		$('#div-step-2-date').hide();
		$('#cp_no').val(x);				
		$.post('app/manage.php',{
		  event:'ru_cp',
		  cp_no: x
		  },function(data) {			
			var cp_no = data.cp_no;
			var cp_petition = data.cp_petition;
			var cp_consider = data.cp_consider;			
			var cp_step = data.cp_step;
			var cp_step_start = data.cp_step_start;
			var cp_step_stop = data.cp_step_stop;			
			var cp_step_1 = data.cp_step_1;
			if(cp_step_1 == "" || cp_step_1 == null){cp_step_1 = 0;}
			var cp_step_1_start = data.cp_step_1_start;
			var cp_step_1_stop = data.cp_step_1_stop;			
			var cp_step_2 = data.cp_step_2;
			if(cp_step_2 == "" || cp_step_2 == null){cp_step_2 = 0;}
			
			
			var cp_step_2_start = data.cp_step_2_start;
			var cp_step_2_stop = data.cp_step_2_stop;
			
			console.log("cp_no : "+cp_no);
			console.log("cp_petition : "+cp_petition);
			console.log("cp_consider : "+cp_consider);
			console.log("cp_step_start : "+cp_step);
			console.log("cp_step_start : "+cp_step_start);
			console.log("cp_step_stop : "+cp_step_stop);			
	
			console.log("cp_step_1 : "+cp_step_1);
			console.log("cp_step_1_start : "+cp_step_1_start);
			console.log("cp_step_1_stop : "+cp_step_1_stop);
			
			console.log("cp_step_2 : "+cp_step_2);
			console.log("cp_step_2_start : "+cp_step_2_start);
			console.log("cp_step_2_stop : "+cp_step_2_stop);
			
			$('#cp_no').val(cp_no);

			$('#cp_step').val(cp_step);
			if(cp_step > 0){
				$.post('app/manage.php',{
					event : 'r_step_1',
					step_id : cp_step
				},function(data){
					$('#cp_step_1').html(data).val(cp_step_1);	
					$('#div-step-1').show();					
				});
			}else{
				$('#div-step-1').hide();
				$('#cp_step_1').val(0);
			}

			if(cp_step > 0 && cp_step_1 > 0){
				$.post('app/manage.php',{
					event : 'r_step_2',
					step_id : cp_step,
					sub_id : cp_step_1
				},function(data){
					$('#cp_step_2').html(data).val(cp_step_2);	
					//$('#div-step-2').show();					
				});
			}else{
				$('#div-step-2').hide();	
				$('#cp_step_2').val(0);
			}
			
			
			$('#cp_step_start').val(cp_step_start);
			$('#cp_step_1_start').val(cp_step_1_start);
			$('#cp_step_2_start').val(cp_step_2_start);			
			$('#cp_step_stop').val(cp_step_stop);
			$('#cp_step_1_stop').val(cp_step_1_stop);
			$('#cp_step_2_stop').val(cp_step_2_stop);	
			if(cp_step == 0){ $('#div-step-date').hide();}else {$('#div-step-date').show();}
			if(cp_step_1 == 0){	$('#div-step-1-date').hide();}else{$('#div-step-1-date').show();}
			if(cp_step_2 == 0){ $('#div-step-2-date').hide();}else{$('#div-step-2-date').show();}
			if(cp_step == 6){
				$('#div-petition').show();
				$('#cp_petition').val(cp_petition);
			}else{
				$('#div-petition').hide();
				$('#cp_petition').val(0);
			}	
		  $('#event').val('uonly_step');
		  $('.modal-title').html("แก้ไขขั้นตอน");
		  $('#modal-step').modal('show');
		},'json');
	}
	
	function stepSavex(){
		$.post('app/manage.php',{
			event : 'uonly_step',
			cp_no : $('#cp_no').val(),
			cp_step : $('#cp_step').val(),
			cp_step_start : $('#cp_step_start').val(),
			cp_step_stop : $('#cp_step_stop').val(),
			cp_step_1 : $('#cp_step_1').val(),
			cp_step_1_start : $('#cp_step_1_start').val(),
			cp_step_1_stop : $('#cp_step_1_stop').val(),
			cp_step_2 : $('#cp_step_2').val(),
			cp_step_2_start : $('#cp_step_2_start').val(),
			cp_step_2_stop : $('#cp_step_2_stop').val(),
			cp_petition : $('#cp_petition').val()
		},function(data){
			$('#modal-step').modal('hide');
			alert(data);
			//console.log(data);
		});
		
	}

	function changeConsider(){
		if($('#cp_consider').val()==1){
			$('#cp_step').val(0);
			changeFststep();
			$('#div-fst-step').hide();
		}else{
			$('#div-fst-step').show();
		}
	}
	function changeFstdate(){
		$.post('app/manage.php',{
			event : 'fst_date',		//fst_date ,snd_date , trd_date ...
			cp_step_start: $('#cp_step_start').val(),
			cp_step : $('#cp_step').val(),
			cp_petition : $('#cp_petition').val()
		},function(data){
			$('#cp_step_stop').val(data);
		});
	}
	
	function changeSnddate(){
		$.post('app/manage.php',{
			event : 'snd_date',		//fst_date ,snd_date , trd_date ...
			cp_step : $('#cp_step').val(),
			cp_step_1 : $('#cp_step_1').val(),
			cp_step_1_start: $('#cp_step_1_start').val(),
			cp_petition : $('#cp_petition').val()
		},function(data){
			$('#cp_step_1_stop').val(data);
		});
	}
	
	function changeTrddate(){
		$.post('app/manage.php',{
			event : 'trd_date',		//fst_date ,snd_date , trd_date ...
			cp_step : $('#cp_step').val(),
			cp_step_1 : $('#cp_step_1').val(),
			cp_step_2 : $('#cp_step_2').val(),
			cp_step_2_start: $('#cp_step_2_start').val(),
			cp_petition : $('#cp_petition').val()
		},function(data){
			$('#cp_step_2_stop').val(data);
		});
	}

	function changeFststep(){
		$('#div-petition').hide();
		if($('#cp_step').val()==6){
			$('#div-petition').show();
		}
		if($('#cp_step').val()==0){
			dateFststep('hide');
			changeSndstep();		
		}else{
			$.post('app/manage.php',{
				event : 'r_step_1',
				step_id : $('#cp_step').val()
			},function(data){
				$('#cp_step_1').html(data);
				dateFststep('show');
				changeSndstep();
			});
		}
	}
	function changeSndstep(){
		if($('#cp_step').val()==0){
			$('#div-step-1').hide();
			$('#cp_step_1').val(0);
			dateSndstep('hide');
			changeTrdstep();
		}else{
			$.post('app/manage.php',{
				event : 'r_step_2',
				step_id : $('#cp_step').val(),
				sub_id : $('#cp_step_1').val()
			},function(data){
				$('#cp_step_2').html(data);			
				$('#div-step-1').show();
				if($('#cp_step_1').val()==0){
					dateSndstep('hide');
					changeTrdstep();
				}
				else{
					dateSndstep('show');
					changeTrdstep();
				}
			});		
		}
	}
	function changeTrdstep(){
		if($('#cp_step_1').val()==0 || $('#cp_step_2').val()=="" || $('#cp_step_2').val()==null){
			$('#div-step-2').hide();
			$('#cp_step_2').val("");
			$('#cp_step_2_start').val("");
			$('#cp_step_2_stop').val("");
			dateTrdstep('hide');
			//alert('a');
		}else{
			$('#div-step-2').show();
			if($('#cp_step_2').val()>0){
				dateTrdstep('show');
			}else{
				dateTrdstep('hide');
			}
			//alert('b');
		}
	}

	function dateFststep(x){
		$('#cp_step_start').val("");
		$('#cp_step_stop').val("");
		if(x=="hide"){
			$('#div-step-date').hide();
		}else if(x=="show"){
			$('#div-step-date').show();
		}
	}

	function dateSndstep(x){
		$('#cp_step_1_start').val("");
		$('#cp_step_1_stop').val("");
		if(x=="hide"){
			$('#div-step-1-date').hide();
		}else if(x=="show"){
			$('#div-step-1-date').show();
		}
	}

	function dateTrdstep(x){
		$('#cp_step_2_start').val("");
		$('#cp_step_2_stop').val("");
		if(x=="hide"){
			$('#div-step-2-date').hide();
		}else if(x=="show"){
			$('#div-step-2-date').show();
		}
	}

	$('#search_aff_ids').hide();

	searchCP();
	function searchCP(){
		$('#cityplanshow').html('<img src="img/loading3.gif" class="center">');
		$.post('<?php if($_SESSION["intra_czid"] =="demo-user-5"){ echo "app/pro_cityplan.php";}else{echo "app/pro_cityplan.php";} ?>',{
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
	

	
	function cpAdd(){
		$('.modal-title').html('เพิ่มข้อมูล');
		$('#cp_province').val("");
		$('#cp_id').val("");
		$('#cp_name').val("");
		$('#cp_consider').val(0);
		$('#cp_budget').val("");
		$('#cp_type').val("0");
		$('#cp_eec').val("N");
		$('#cp_scope').val("");
		$('#cp_status').val("");		
		$('#cp_stage').val("");
		$('#cp_owner').val(1);
		$('#cp_operator').val(0);
		$('#cp_effective').val(0);
		$('#cp_date_notice').val('');
		$('#cp_date_exp').val('');
		
		
		$('#cp_petition').val(0);
		$('#event').val("c_cp+c_csd");
		$('#div-step-date').hide();
		$('#div-step-1-date').hide();
		$('#div-step-2-date').hide();
		$('#div-step-1').hide();
		$('#div-step-2').hide();
		$('#div-petition').hide();
		$('input[name=cp_petition]').prop('checked',false);

		$("#cp_province").attr("disabled",false);
		$("#cp_name").attr("disabled",false);
		$("#cp_consider").attr("disabled",false);
		$("#cp_stage").attr("disabled",false);
		$('#modal-cityplan').modal('show');
		checkData();
	}
	
	function checkData(){		
		if($('#cp_name').val() == "" || $('#cp_province').val() == "" || $('#cp_scope').val() == "" || $('#cp_status').val() == "" || $('#cp_stage').val() == "" ){
			$('#btnSave').removeAttr('onclick');
			$('#btnSave').addClass('disabled');
		}else{
			$('#btnSave').attr('onclick','cpSave()');
			$('#btnSave').removeClass('disabled');
		}
	}
	function cpSave(){
		if ( $('#cp_name').val()=='' || $('#cp_province').val()=='' || $('#cp_scope').val()==''|| $('#cp_status').val()=='' || $('#cp_stage').val()==''){
				alert('กรุณากรอกข้อมูลให้ครบ');			
		}
		/*else if($('#cp_step').val()>0){if($('#cp_step_start').val() =="" || $('#cp_step_stop').val()==""){alert('กรุณากรอกวันที่เริ่มต้นและวันที่สิ้นสุด');}
		}
		else if($('#cp_step_1').val()>0){if($('#cp_step_1_start').val() =="" || $('#cp_step_1_stop').val()==""){alert('กรุณากรอกวันที่เริ่มต้นและวันที่สิ้นสุด');}
		}
		else if($('#cp_step_2').val()>0){if($('#cp_step_2_start').val() =="" || $('#cp_step_2_stop').val()==""){alert('กรุณากรอกวันที่เริ่มต้นและวันที่สิ้นสุด');}
		}	*/			
		else{
			$.post('app/manage.php',{
			cp_no:$('#cp_no').val(),
			cp_id:$('#cp_id').val(),
			cp_type:$('#cp_type').val(),
			cp_eec:$('#cp_eec').val(),
			cp_province:$('#cp_province').val(),	
			cp_name:$('#cp_name').val(),
			cp_budget:$('#cp_budget').val(),
			cp_owner:$('#cp_owner').val(),
			cp_operator:$('#cp_operator').val(),
			cp_scope:$('#cp_scope').val(),
			cp_status:$('#cp_status').val(),
			cp_stage:$('#cp_stage').val(),
			cp_effective:$('#cp_effective').val(),
			cp_step:$('#cp_step').val(),
			cp_step_start:$('#cp_step_start').val(),
			cp_step_stop:$('#cp_step_stop').val(),
			cp_step_1:$('#cp_step_1').val(),
			cp_step_1_start:$('#cp_step_1_start').val(),
			cp_step_1_stop:$('#cp_step_1_stop').val(),
			cp_step_2:$('#cp_step_2').val(),
			cp_step_2_start:$('#cp_step_2_start').val(),
			cp_step_2_stop:$('#cp_step_2_stop').val(),
			cp_consider:$('#cp_consider').val(),
			cp_date_notice:$('#cp_date_notice').val(),
			cp_date_exp:$('#cp_date_exp').val(),
			cp_petition:$('#cp_petition').val(),
			event:$('#event').val(),
			user_id:$('#user_id').val()
			 },function(data) {
				$('#modal-cityplan').modal('hide');
				alert(data);
				$('#mainshow').load('report/manage_cityplanX-ver2.php');
			 //console.log(data);
			 //$('#mainshow').html(data);
			 });
		}
	}
	
	function cpSavex(){
		if ( $('#cp_name').val()=='' || $('#cp_province').val()=='' || $('#cp_scope').val()==''|| $('#cp_status').val()=='' || $('#cp_stage').val()==''){
				alert('กรุณากรอกข้อมูลให้ครบ');			
		}		
		else{
			$.post('app/manage.php',{
			cp_no:$('#cp_no').val(),
			cp_id:$('#cp_id').val(),
			cp_type:$('#cp_type').val(),
			cp_eec:$('#cp_eec').val(),
			cp_province:$('#cp_province').val(),	
			cp_name:$('#cp_name').val(),
			cp_budget:$('#cp_budget').val(),
			cp_owner:$('#cp_owner').val(),
			cp_operator:$('#cp_operator').val(),
			cp_scope:$('#cp_scope').val(),
			cp_status:$('#cp_status').val(),
			cp_stage:$('#cp_stage').val(),
			cp_effective:$('#cp_effective').val(),
			/*cp_step:$('#cp_step').val(),
			cp_step_start:$('#cp_step_start').val(),
			cp_step_stop:$('#cp_step_stop').val(),
			cp_step_1:$('#cp_step_1').val(),
			cp_step_1_start:$('#cp_step_1_start').val(),
			cp_step_1_stop:$('#cp_step_1_stop').val(),
			cp_step_2:$('#cp_step_2').val(),
			cp_step_2_start:$('#cp_step_2_start').val(),
			cp_step_2_stop:$('#cp_step_2_stop').val(),
			cp_petition:$('#cp_petition').val(),*/
			cp_consider:$('#cp_consider').val(),
			event:$('#event').val(),
			user_id:$('#user_id').val()
			 },function(data) {
				$('#modal-cityplan').modal('hide');
				alert(data);
				$('#mainshow').load('report/manage_cityplanX-ver2.php');
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
		  $('#event').val('u_cp-v2');
		  $('#cp_consider').val(data.cp_consider);		 	  
		 
		 $('#cp_date_notice').val(data.cp_date_notice);		 	  
		 $('#cp_date_exp').val(data.cp_date_exp);		 	  
		  $('#user_id').val(data.user_id);
		  $('.modal-title').html("แก้ไขข้อมูล");
		  <?php 
		  if($_SESSION['data_access']!=9){
			  echo '$("#cp_province").attr("disabled",true);';
			  echo '$("#cp_name").attr("disabled",true);';
			  echo '$("#cp_consider").attr("disabled",true);';
			  echo '$("#cp_stage").attr("disabled",true);';
		  }		  
		  ?>
		  $('#modal-cityplan').modal('show');
		  checkData();
		},'json');
	}
	
	function cpEdit(x){
		$.post('app/manage.php',{
		  event:'ru_cp',
		  cp_no: x
		  },function(data) {
			  //alert(data);
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
		  $('#cp_step').val(data.cp_step);
		  $('#cp_step_start').val(data.cp_step_start);
		  $('#cp_step_stop').val(data.cp_step_stop); 
		  $('#cp_step_1').val(data.cp_step_1);
  		  $('#cp_step_1_start').val(data.cp_step_1_start);
		  $('#cp_step_1_stop').val(data.cp_step_1_stop);
		  $('#cp_step_2').val(data.cp_step_2);
		  $('#cp_step_2_start').val(data.cp_step_2_start);
		  $('#cp_step_2_stop').val(data.cp_step_2_stop);	
		  $('#event').val('u_cp-v2');
		  $('#user_id').val(data.user_id);
		  $('#step').val(data.cp_step);
		  $('#step_1').val(data.cp_step_1);
		  $('#step_2').val(data.cp_step_2);
		  $('#cp_consider').val(data.cp_consider);
		  $('#cp_petition').val(data.cp_petition);
		 
		 $('#div-petition').hide();		  
		  if($('#cp_step').val()==6){
			$('#div-petition').show();
		}
		  var w = $('#step').val();
		  var x = $('#step_1').val();
		  //alert(x);
		  if($('#cp_step').val()!=0){
			  $('#div-step-date').show();
			  $('#div-step-1').show();
			  $.post('app/manage.php',{
				  event : 'r_step_1',
				  step_id:$('#cp_step').val()
			  },function(data){
				  $('#cp_step_1').html(data).val(x);  
			  });
		  }
		  var y = $('#step_2').val();
		  //alert(y);
		  if($('#step_1').val()!=0){
			  //alert(y);
			  $('#div-step-1-date').show();
			  $('#div-step-2').show();
			  $('#div-step-2-date').show();
			  $.post('app/manage.php',{
				  event : 'r_step_2',
				  step_id : w,
				  sub_id : x
			  },function(data){
				  //alert("w : "+w+" , x : "+x);
				  $('#cp_step_2').html(data).val(y);
			  }); 
		  }	
			if($('#cp_step').val()==0){
				$('#div-step').hide();
				$('#div-step-date').hide();
				$('#div-step-1').hide();
				$('#div-step-1-date').hide();
				$('#div-step-2').hide();
				$('#div-step-2-date').hide();
			}
			
			if($('#step_1').val()==0){
				$('#div-step-1-date').hide();
				$('#div-step-2').hide();
				$('#div-step-2-date').hide();
			}	
			//alert($('#cp_step_2').val());
			if($('#step_2').val()==0 && ($('#cp_step_2').val()=="" || $('#cp_step_2').val()==null || $('#cp_step_2').val()==0)){ 
				$('#div-step-2').hide();
				//alert('hi');
				//changeTrdstep();
			}
			if($('#step_2').val()==0){
				$('#div-step-2-date').hide();
			}
		  //changeFstdate();
		  //changeSnddate();
		  $('.modal-title').html("แก้ไขข้อมูล");
		  $('#modal-cityplan').modal('show');
		},'json');
	}
	function cpDel(x){
		if(confirm('คุณต้องการจะลบข้อมูลนี้ ใช่หรือไม่ ?')){
			$.post('app/manage.php',{
				cp_no:x,
				event:'d_cp',
				user_id:$('#user_id').val()
			},function(data){
				$('#modal-cityplan').modal('hide');
				alert(data);
				$('#mainshow').load('report/manage_cityplanX-ver2.php');
			});
		}
	}
	
	
	function Datacheck(){
		console.log("cp_no : "+$('#cp_no').val());
		console.log("cp_petition : "+$('#cp_petition').val());
		console.log("cp_consider : "+$('#cp_consider').val());
		console.log("----------------------");
		console.log("cp_step : "+$('#cp_step').val());
		console.log("cp_step_start : "+$('#cp_step_start').val());
		console.log("cp_step_stop : "+$('#cp_step_stop').val());
		console.log("----------------------");	
		console.log("cp_step_1 : "+$('#cp_step_1').val());
		console.log("cp_step_1_start : "+$('#cp_step_1_start').val());
		console.log("cp_step_1_stop : "+$('#cp_step_1_stop').val());
		console.log("----------------------");
		console.log("cp_step_2 : "+$('#cp_step_2').val());
		console.log("cp_step_2_start : "+$('#cp_step_2_start').val());
		console.log("cp_step_2_stop : "+$('#cp_step_2_stop').val());
		console.log("||||||||||||||||||||||||||||||||||||||");
	}
	
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
		$(".cp_date").on("mouseenter mouseleave ",function(e){
			var dateValue=$(this).val();
			if(dateValue!=""){
					var arr_date=dateValue.split("-"); // ถ้าใช้ตัวแบ่งรูปแบบอื่น ให้เปลี่ยนเป็นตามรูปแบบนั้น
					// ในที่นี้อยู่ในรูปแบบ 00-00-0000 เป็น d-m-Y  แบ่งด่วย - ดังนั้น ตัวแปรที่เป็นปี จะอยู่ใน array
					//  ตัวที่สอง arr_date[2] โดยเริ่มนับจาก 0 	
					//alert(e.type);
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

	
	function cityplan_step(x){
		$.post('app/manageX.php',{
		  event:'ru_cp',
		  cp_no: x
		  },function(data) {
			var cp_no = data.cp_no;
			var cp_petition = data.cp_petition;			
			var cp_step = data.cp_step;
			var cp_step_start = data.cp_step_start;
			var cp_step_stop = data.cp_step_stop;	
			if(cp_step == '0'){
				cp_step = '0' ; 
				cp_step_start = '';
				cp_step_stop = '';
			}
				
			var cp_step_1 = data.cp_step_1;
			var cp_step_1_start = data.cp_step_1_start;
			var cp_step_1_stop = data.cp_step_1_stop;
			if(cp_step_1 == '0'){
				cp_step_1_start = '';
				cp_step_1_stop = '';
			}
			var cp_step_1_start_opt = data.cp_step_1_start_opt;
			var cp_step_1_stop_opt = data.cp_step_1_stop_opt;
			var cp_step_2_start_opt = data.cp_step_2_start_opt;
			var cp_step_2_stop_opt = data.cp_step_2_stop_opt;
			
			var cp_step_2 = data.cp_step_2;
			var cp_step_2_start = data.cp_step_2_start;
			var cp_step_2_stop = data.cp_step_2_stop;		
			if(cp_step_2 == '0'){ 
				cp_step_2_start = '';
				cp_step_2_stop = '';
			}
			/*
			console.log("cp_no : "+cp_no);
			console.log("cp_petition : "+cp_petition);
			console.log("cp_step : "+cp_step);
			console.log("cp_step_start : "+cp_step_start);
			console.log("cp_step_stop : "+cp_step_stop);		
			console.log("cp_step_1 : "+cp_step_1);
			console.log("cp_step_1_start : "+cp_step_1_start);
			console.log("cp_step_1_stop : "+cp_step_1_stop);			
			console.log("cp_step_2 : "+cp_step_2);
			console.log("cp_step_2_start : "+cp_step_2_start);
			console.log("cp_step_2_stop : "+cp_step_2_stop);
			console.log("-----------------------------------------------------");
			*/
			
			$('#cp_no').val(cp_no);
			$('#cp_step').val(cp_step);
			$('#cp_step_start').val(cp_step_start);
			$('#cp_step_stop').val(cp_step_stop);
			
			if($('#cp_step').val()==6){
				$('#cp_petition').val(cp_petition);
				$('#div-petition').show();
			}else{
				$('#cp_petition').val(0);
				$('#div-petition').hide();
			}
			
			load_snd_step(cp_step,cp_step_1);
			//$('#cp_step_1').val(cp_step_1);
			$('#cp_step_1_start').val(cp_step_1_start);
			$('#cp_step_1_stop').val(cp_step_1_stop);
			
			$('#cp_step_1_start_opt').val(cp_step_1_start_opt);
			$('#cp_step_1_stop_opt').val(cp_step_1_stop_opt);
			
			load_trd_step(cp_step,cp_step_1,cp_step_2);
			//$('#cp_step_2').val(cp_step_2);
			$('#cp_step_2_start').val(cp_step_2_start);
			$('#cp_step_2_stop').val(cp_step_2_stop);
			
			$('#cp_step_2_start_opt').val(cp_step_2_start_opt);
			$('#cp_step_2_stop_opt').val(cp_step_2_stop_opt);
			
			if($('#cp_step').val()==0){
				$('#div-step-date').hide();
				$('#div-step-1').hide();
				$('#div-step-2').hide();
				$('#div-step-1-date').hide();
				$('#div-step-2-date').hide();
			}else{
				$('#div-step').show();
				$('#div-step-date').show();
				$('#div-step-1').show();
			}
			
			if($('#cp_step_1').val()==0){
				$('#div-step-1-date').hide();
			}
			else{
				$('#div-step-1').show();
				$('#div-step-1-date').show();
			}
			
			if($('#cp_step_2').val()==0){
				$('#div-step-2').hide();
			}
			else{				
				$('#div-step-1').show();
				$('#div-step-2').show();
				$('#div-step-2-date').show();
			}
			$('#modal-step').modal('show');
			$('.modal-title').html('แก้ไขขั้นตอน');
		},'json');
	}
	
	function load_snd_step(fst,snd){
		if(fst==null){
			if($('#cp_step').val()=='' || $('#cp_step').val() == null){ 
				fst = 0;
			}
			else{
				fst = $('#cp_step').val();
			}
		}
		if(snd == null){
			if($('#cp_step_1').val()=='' || $('#cp_step_1').val() == null){ 
				snd = 0;
			}
			else{
				snd = $('#cp_step_1').val();
			}
		}
		$.post('app/manageX.php',{
			event:'load_snd_step',
			cp_step : fst,
		},function(data){
			$('#cp_step_1').html(data);
			$('#cp_step_1').val(snd);
			if($('#cp_step_1').val()==0){
				$('#div-step-1-date').hide();
			}else{
				$('#div-step-1-date').show();
			}
		});
	}
	
	function load_trd_step(fst,snd,trd){
		if(fst==null){
			if($('#cp_step').val()=='' || $('#cp_step').val() == null){ 
				fst = 0;
			}
			else{
				fst = $('#cp_step').val();
			}
		}
		if(snd == null){
			if($('#cp_step_1').val()=='' || $('#cp_step_1').val() == null){ 
				snd = 0;
			}
			else{
				snd = $('#cp_step_1').val();
			}
		}
		if(trd == null){
			if($('#cp_step_2').val()=='' || $('#cp_step_2').val() == null){ 
				trd = 0;
			}
			else{
				trd = $('#cp_step_2').val();
			}
		}		
		$.post('app/manageX.php',{
			event:'load_trd_step',
			cp_step:fst,
			cp_step_1:snd
		},function(data){
			$('#cp_step_2').html(data);
			$('#cp_step_2').val(trd);
			if($('#cp_step_2').val()==0){
				$('#div-step-2-date').hide();
			}else{
				$('#div-step-2').show();
				$('#div-step-2-date').show();
			}
		});
	}
	
	
	
	
	
	function changeFststepX(){
		$('#div-step-1').hide();
		$('#div-step-2').hide();
		$('#div-step-2-date').hide();
		//$('#cp_step_start').val("");
		//$('#cp_step_stop').val("");
			
		if($('#cp_step').val()==6){
			$('#cp_petition').val(0);
			$('#div-petition').show();
		}else{
			$('#cp_petition').val(0);
			$('#div-petition').hide();
		}
		load_snd_step(null,0);
		if($('#cp_step').val()==0){
			$('#div-step-date').hide();
		}else{
			$('#div-step-date').show();
			$('#div-step-1').show();
		}
	}
	
	function changeSndstepX(){
		//$('#cp_step_1_start').val("");
		//$('#cp_step_1_stop').val("");
		$('#cp_step_1_start_opt').val('');
		$('#cp_step_1_stop_opt').val('');
		load_trd_step(null,null,0);
		if($('#cp_step_1').val()==0){
			$('#div-step-1-date').hide();
		}else{
			$('#div-step-1-date').show();
		}
	}
	
	function changeTrdstepX(){
		//$('#cp_step_2_start').val("");
		//$('#cp_step_2_stop').val("");
		$('#cp_step_2_start_opt').val('');
		$('#cp_step_2_stop_opt').val('');
		if($('#cp_step_2').val()==0){
			$('#div-step-2-date').hide();
		}else{
			$('#div-step-2-date').show();
		}
	}
	/*
	function stepSaveY(){
		$.post('app/manageX.php',{
			event : 'uonly_step',
			cp_no : $('#cp_no').val(),
			cp_step : $('#cp_step').val(),
			//cp_step_start : $('#cp_step_start').val(),
			//cp_step_stop : $('#cp_step_stop').val(),
			cp_step_1 : $('#cp_step_1').val(),
			//cp_step_1_start : $('#cp_step_1_start').val(),
			//cp_step_1_stop : $('#cp_step_1_stop').val(),
			cp_step_2 : $('#cp_step_2').val(),
			//cp_step_2_start : $('#cp_step_2_start').val(),
			//cp_step_2_stop : $('#cp_step_2_stop').val(),
			
			cp_step_1_start_opt : $('#cp_step_1_start_opt').val(),
			cp_step_1_stop_opt : $('#cp_step_1_stop_opt').val(),
			cp_step_2_start_opt : $('#cp_step_2_start_opt').val(),
			cp_step_2_stop_opt : $('#cp_step_2_stop_opt').val(),
			
			cp_petition : $('#cp_petition').val()
		},function(data){
			$('#modal-step').modal('hide');
			alert(data);
			//console.log(data);
		});		
	}*/
	
	function stepSaveUU(){
		$.post('app/manageX.php',{
			event : 'uonly_step_uu',
			cp_no : $('#cp_no').val(),
			cp_step : $('#cp_step').val(),
			cp_step_1 : $('#cp_step_1').val(),
			cp_step_2 : $('#cp_step_2').val(),
			
			cp_step_1_start_opt : $('#cp_step_1_start_opt').val(),
			cp_step_1_stop_opt : $('#cp_step_1_stop_opt').val(),
			cp_step_2_start_opt : $('#cp_step_2_start_opt').val(),
			cp_step_2_stop_opt : $('#cp_step_2_stop_opt').val(),
			
			cp_petition : $('#cp_petition').val()
		},function(data){
			$('#modal-step').modal('hide');
			alert(data);
			$('#mainshow').load('report/manage_cityplanX-ver2.php');
			//console.log(data);
		});		
	}
	
	function changePetition(){
		/*$('#cp_step_start').val('');
		$('#cp_step_stop').val('');
		$('#cp_step_1_start').val('');
		$('#cp_step_1_stop').val('');
		$('#cp_step_2_start').val('');
		$('#cp_step_2_stop').val('');*/
		$('#cp_step_1_start_opt').val('');
		$('#cp_step_1_stop_opt').val('');
		$('#cp_step_2_start_opt').val('');
		$('#cp_step_2_stop_opts').val('');
	}
	
	function OperatePlan(cp_no){
		$.post('report/operate_plan_show.php',{
			cp_no : cp_no,
			page : $('#select_table').val()
		},function(data){
			$('#mainshow').html(data)
		});
	}
	function readNote(cp_no){
		$.post('app/manageX.php',{
			event : 'r_note',
			cp_no : cp_no
		},function(data){
			$('#note_area').html(data);
			$('#modal-note').modal('show');
		});
	}
	function latingCP(x){
		$('#cityplanshow').html('<img src="img/loading3.gif" class="center">');	
		$.ajax({
			url : 'app/pro_cityplan_lating.php',
			async : true,
			type : 'POST',
			data:{
				cp : x
			},
			success : function(data , status , xhr){
				//alert('Loading : '+status);
				$('#cityplanshow').html(data);
			},
			error : function(xhr, status, error){
				alert('Loading : '+status+'\n'+error);
				
			}
		});
		
	}
</script>
	
	