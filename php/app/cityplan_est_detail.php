<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
//$title= "ผังเมืองรวม/ชุมชน ม.35";
$sql = "
SELECT est_cp , est_edit , est_csd
FROM tb_cityplan_est as t1
LEFT JOIN tb_cityplan_main as t2 ON t1.est_cp = t2.cp_no
WHERE est_seq = '".$_POST['est_seq']."'";
//echo $sql;
$resulti = selectSql($sql);
$sql = "SELECt cp_name , cp_owner FROM tb_cityplan_main WHERE cp_no = '".$resulti[0]['est_cp']."'";
//echo $sql;
$result = selectSql($sql);
$title = $result[0]['cp_name'].'123';
$subtitle = "ประเมินผล ครั้งที่ ".$resulti[0]['est_edit'];
$operator = $result[0]['cp_owner'];
$subtitlex = $operator == 1?"กรมฯ":"อปท.";
$years = date('Y')+543;
$months = date('m');
if($months == 1 ){
	$months = 'ม.ค.';
}elseif($months == 2 ){
	$months = 'ก.พ.';
}elseif($months == 3 ){
	$months = 'มี.ค.';
}elseif($months == 4 ){
	$months = 'เม.ย.';
}elseif($months == 5 ){
	$months = 'พ.ค.';
}elseif($months == 6 ){
	$months = 'มิ.ย.';
}elseif($months == 7 ){
	$months = 'ก.ค.';
}elseif($months == 8 ){
	$months = 'ส.ค.';
}elseif($months == 9 ){
	$months = 'ก.ย.';
}elseif($months == 10 ){
	$months = 'ต.ค.';
}elseif($months == 11 ){
	$months = 'พ.ย.';
}else{
	$months = 'ธ.ค.';
}
$days = intval(date('d'));
$dates = $days.' '.$months.' '.$years;	
	
//print_r($_POST);

$from = "ref_cityplan_step_detail_m35";
$join = "csd35_cp = '".$_POST['est_seq']."'";

//$from = " ref_cityplan_step_detail_m35 , tb_cityplan , ref_province ";
//$join = " csd35_status = 1 AND csd35_cp = cp_no AND cp_province = pvn_id ORDER BY csd35_seq DESC";

$sql = "SELECT * FROM ".$from." WHERE ".$join;
//echo $sql;
$result = selectSql($sql);
$all_data = count($result);


?>
	<style>
		@media screen and (max-width:767px){
			.my-tb{
				margin:0 15px;
				padding: 0;
			}
			
		}
		
		.sub-select-cp-input{
			 margin:0px;
			 border-top:solid #dfdfdf 1px;
			 padding: 4px 5px 4px 10px;
		 }
		 
		.sub-select-cp-input:first-child{
			border:none!important;
		 }
		 
		.div-sub-select-cp-input:hover{
			 background:blue;
			 color:white;
		 }
		.table > tbody > tr > td{
			 border-top:solid #dfdfdf 1px;
		}
		.clickable:hover{
			 border-bottom:solid black 1px;
			 cursor:pointer;
		}
	</style>
	<div style="display:inline;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
	<div id="title_page" class="col-12">
		<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"><?=$title?></h2>
		<h3 class="hidden-md hidden-lg GHfont-mini unprintable" style="font-family: Kanit;text-align:center"><?=$title?></h3>
		<h3 class="printable" style="font-family: Kanit;text-align:center;margin:-8px 0 16px 0;"><?=$subtitle?></h3>
		<h3 class="printable" style="font-family: Kanit;text-align:center;margin:-8px 0 16px 0;">ดำเนินการโดย <?=$subtitlex?></h3>
		<div class="row" id="info_bar">
			<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_value">จำนวน  ...  ขั้นตอน </h3>
		</div>
		
		<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
	</div>	
	<br>
	<div class="container-fluid">
		<div class="row unprintable" style="margin-bottom:8px;">
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_backy" onclick="goto('m35x');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn-2" class="col-xs-6 col-md-6">
				<button id="btn_m35" onclick="addStepM35()" class="pull-right btn btn-primary btn-md" style="margin-top:6px;">เพิ่มขั้นตอน</button>
			</div>
			<!--
			<div id="div-select" class="col-xs-7 col-md-6">
				<select id="select-aff" onchange="searchtype();" style="margin-top:6px;height:34px;width:100%;border-radius:4px">
					<option value="">เลือกส่วนผังเมืองรวม</option>
				</select>
			</div>-->
			<!--
			<div id="div-search_pvn" class="col-xs-6 col-md-6">
				<input id="search-pvn-x" onkeyup="checkInputText(this);searchpvn();" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยจังหวัด">
			</div>
			<div id="div-search_name" class="col-xs-6 col-md-6">
				<input id="search-name-x" onkeyup="checkInputText(this);searchname();" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยชื่อผัง">
			</div>
			-->
		</div>
		<div class="row" id="csd35_show">
			
		</div>
	</div>

	
<div class="modal fade" id="modal-note">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:8px">
			<div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
				<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 id="modal-note_title" class="modal-title" style="font-family:Kanit;color:black"></h4>
			</div>
            <div class="modal-body">
				<form>	
					<div class="form-group" id="note_step_show">
						<span id="note_step"></span>	
					</div>
					
					<div class="form-group" id="note_step_1_show">
						<span id="note_step_1"></span>	
					</div>
					

				
					<div class="form-group" id="show_note_remark"></div>
					<div class="form-group" id="edit_note_remark"></div>
				<?php
				/*<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>*/
				?>
				
					<input type="hidden" id="note_cp" value="">
					<input type="hidden" id="note_csd" value="">
					<input type="hidden" name="note_id" id="note_id" value="" >
					<input type="hidden" id="note_uper" value="<?php echo $_SESSION['intra_czid']; ?>">				
					<input type="hidden" id="note_event" value="">
					<input type="hidden" id="note_step_hidden" value="">
					<input type="hidden" id="note_step_1_hidden" value="">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
				<button id="addBtnM" type="button" class="btn btn-success pull-right" onclick="addNote2();">เพิ่มหมายเหตุ</button>
				<button id="editBtn" type="button" class="btn btn-success pull-right" onclick="saveNote();">บันทึก</button>
				<button id="cancleBtn" type="button" class="btn btn-warning pull-right" onclick="cancleNote();">ยกเลิก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="delNote();">ลบหมายเหตุ</button>
			</div>
		</div>
	</div>
</div>	
<div class="modal fade" id="modal-stepM35" style="">
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
							<label for="csd35_step_fst">ขั้นตอนดำเนินการหลัก</label>
							<select class="form-control" id="csd35_step_fst" onchange="changeFststepX()">
								<option value="0">ไม่ระบุขั้นตอน</option>
								<?php 
									$sql ="SELECT * FROM ref_m35_fst_step WHERE m35_fst_id > 0 AND m35_operate = '".$operator."'";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['m35_fst_id'];?>"><?php echo 'ขั้นตอนที่ ',$row['m35_fst_id'],' ',$row['m35_fst_name'];?></option>									
								<?php } ?>
							</select>					
						</div>
						<div id="div-petition" class="form-group col-xs-12 col-md-12">
							<label for="csd35_petition">คำร้อง : </label>
							<select class="form-control" id="csd35_petition" onchange="changePetition()">
								<option selected value="0">ไม่มีคำร้อง</option>
								<option value="1">มีคำร้อง</option>
							</select>					
						</div>
						<div id="div-step-date">
							<div  class="form-group col-xs-6 col-md-6">			
								<label for="csd35_plan_fst_start">วันที่เริ่มต้นขั้นตอนหลัก (ตามแผน)</label>
								<input class="form-control cp_date" type="text" id="csd35_plan_fst_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="SetTimeFstPlan35()" >
								<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
							</div>
							
							<div id="div-step-date" class="form-group col-xs-6 col-md-6">
								<label for="csd35_plan_fst_stop">วันที่สิ้นสุดขั้นตอนหลัก (ตามแผน)</label>
								<input class="form-control disabled" type="text" id="csd35_plan_fst_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
								<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
							</div>
						</div>
					</div>
					<div class="row" id="div-step-1">
						<hr style="border:solid 1px #dbdbdb;max-width:100%;margin: 4px 0px 12px;border-top:transparent">
						<div class="form-group col-xs-12 col-md-12">
							<label for="csd35_step_snd">ขั้นตอนดำเนินการย่อย 1</label>
							<select class="form-control" id="csd35_step_snd" onchange="changeSndstepX()">
								<option value="0">ไม่ระบุขั้นตอน</option>
							</select>
						</div>
					</div>			
					<div class="row" id="div-step-1-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="csd35_plan_snd_start">วันที่เริ่มต้นขั้นตอนย่อย (ตามแผน)</label>
							<input class="form-control cp_date" type="text" id="csd35_plan_snd_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="SetTimeSndPlan35()" >
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="csd35_plan_snd_stop">วันที่สิ้นสุดขั้นตอนย่อย (ตามแผน)</label>
							<input class="form-control disabled" type="text" id="csd35_plan_snd_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
						<div class="form-group col-xs-6 col-md-6">
							<label for="csd35_start">วันที่เริ่มต้นขั้นตอนย่อย  (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd35_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="" >
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="csd35_stop">วันที่สิ้นสุดขั้นตอนย่อย (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd35_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
	
					<input type="hidden" name="csd35_seq" class="form-control" id="csd35_seq" value="" >
					<input type="hidden" name="csd35_cp" class="form-control" id="csd35_cp" value="" >
					<input type="hidden" id="csd35_event" value="">
					<input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
				</form>
			</div>
			<div  class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-success pull-right" onclick="saveStepM35()">บันทึก</button>				
			</div>
		</div>
	</div>
</div>

 
<script>
	loadEST();
	function loadEST(){
		$.ajax({
			url : 'app/cityplan_est_detail_pro.php',
			async : true,
			type : 'POST',
			data :
			{
				csd35_cp : '<?=$_POST["est_seq"]?>',
				cp35_csd : '<?=$resulti[0]["est_csd"]?>',
				operator : '<?=$operator?>'
				
			},
			success : function(data, status, xhr){
				$('#csd35_show').html(data);
			},
			error : function(xhr,status,data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	function SetTimeFstPlan35(){
		if($('#csd35_plan_fst_start').val()!=''){
			$.ajax({
				url : 'app/manage.php',
				type : 'post',
				data : {
					event : 'SetTimeFstPlan35',
					fst : $('#csd35_step_fst').val(),
					petition : $('#csd35_petition').val(),
					start : $('#csd35_plan_fst_start').val(),
					operator : '<?=$operator?>'
				},
				success : function(data, status, xhr){
					$('#csd35_plan_fst_stop').val(data)
				},
				error : function(xhr, status,data){
					alert('เกิดข้อผิดพลาด');
				}
			});
		}
		else{
			$('#csd35_plan_fst_stop').val('');
		}
	}
	
	function SetTimeSndPlan35(){
		if($('#csd35_plan_snd_start').val()!=''){
			$.ajax({
				url : 'app/manage.php',
				type : 'post',
				data : {
					event : 'SetTimeSndPlan35',
					fst : $('#csd35_step_fst').val(),
					snd : $('#csd35_step_snd').val(),
					petition : $('#csd35_petition').val(),
					start : $('#csd35_plan_snd_start').val(),
					operator : '<?=$operator?>'
				},
				success : function(data, status, xhr){
					$('#csd35_plan_snd_stop').val(data);
				},
				error : function(xhr, status,data){
					alert('เกิดข้อผิดพลาด');
				}
			});
		}
		else{
			$('#csd35_plan_snd_stop').val('');
		}
	}
	
	
	
	function changeFststepX(){
		$('#csd35_petition').val(0);
		$('#csd35_plan_fst_start').val('');
		$('#csd35_plan_fst_stop').val('');
					
		$('#csd35_step_snd').val();
		$('#csd35_plan_snd_start').val('');
		$('#csd35_plan_snd_stop').val('');
		$('#csd35_start').val('');
		$('#csd35_stop').val('');
		
		if($('#csd35_step_fst').val()>0){
			if($('#csd35_step_fst').val()==6){
				$('#div-petition').show();
			}else{
				$('#div-petition').hide();
			}
			$('#div-step-date').show();
			$('#div-step-1').show();
		}
		else{
			$('#div-petition').hide();
			$('#div-step-1').hide();
			$('#div-step-date').hide();
		}
		load_snd_step();
	} 
	
	function changeSndstepX(){
		//alert("asd");
		$('#csd35_plan_snd_start').val('');
		$('#csd35_plan_snd_stop').val('');
		$('#csd35_start').val('');
		$('#csd35_stop').val('');
		if($('#csd35_step_snd').val()>0){
			$('#div-step-1-date').show();
		}
		else{
			$('#div-step-1-date').hide();
		}
	} 
	
	function changePetition(){
		load_snd_step();
		$('#csd35_plan_fst_start').val('');$('#csd35_plan_fst_stop').val('');
		//$('#csd35_plan_snd_start').val('');$('#csd35_plan_snd_stop').val('');$('#csd35_start').val('');$('#csd35_stop').val('');
	}
	
	function load_snd_step(){
		if($('#csd35_step_fst').val()<1){
			$('#csd35_step_snd').html('<option value="0">ไม่ระบุขั้นตอน</option>');
		}
		else{
			
			$.ajax({
				url : 'app/manage.php',
				type : 'POST',
				async : true,
				data : {
					csd35_step_fst : $('#csd35_step_fst').val(),
					operator:'<?=$operator?>',
					petition:$('#csd35_petition').val(),
					event : 'load_snd_stepM35X'
				},
				success : function(data, status, xhr){
					$('#csd35_step_snd').html(data);
					changeSndstepX();
				},
				error : function(xhr, status, data){
					alert('Status : '+status);
				}
			});
		}
		
	}
	
	function addStepM35(){
		$('#csd35_step_fst').val(0).removeAttr('disabled');
		$('#csd35_step_snd').val(0).removeAttr('disabled');
		changeFststepX();
		changeSndstepX();
		$('#csd35_event').val('c_m35a');
		$('#modal-stepM35').modal('show');
	}
	
	function setStepM35(x,y){
		if(y=='true'){
			y = 'u_m35a';
		}else{
			y = 'u_m35b';
		}
		$.ajax({
			url : 'app/manage.php',
			type : 'post',
			dataType : 'json',
			async : true,
			data : {
				event : 'r_stepM35A',
				csd35_seq : x,
			},
			success : function(data, status, xhr){
				var csd35_step_fst = data.csd35_step_fst;
				var csd35_step_snd = data.csd35_step_snd;
				var csd35_petition = data.csd35_petition;
				$('#csd35_seq').val(data.csd35_seq);
				$('#csd35_cp').val(data.csd35_cp);
				$('#csd35_step_fst').val(csd35_step_fst).attr('disabled','true');
				changeFststepX();
				setTimeout(() => {
					if(csd35_step_fst>0){
						$('#csd35_petition').val(data.csd35_petition);
						changePetition()
						$('#csd35_plan_fst_start').val(data.csd35_plan_fst_start);
						$('#csd35_plan_fst_stop').val(data.csd35_plan_fst_stop);
					}else{
						$('#csd35_petition').val(0);
						$('#csd35_plan_fst_start').val('');
						$('#csd35_plan_fst_stop').val('');
					}
					setTimeout(() => {
						$('#csd35_step_snd').val(csd35_step_snd).attr('disabled','true');
						changeSndstepX();
						if(csd35_step_snd>0){
							$('#csd35_plan_snd_start').val(data.csd35_plan_snd_start);
							$('#csd35_plan_snd_stop').val(data.csd35_plan_snd_stop);
							$('#csd35_start').val(data.csd35_snd_start);
							$('#csd35_stop').val(data.csd35_snd_stop);
						}else{
							$('#csd35_plan_snd_start').val('');
							$('#csd35_plan_snd_stop').val('');
							$('#csd35_start').val('');
							$('#csd35_stop').val('');						
						}
						$('#csd35_event').val(y);
						$('#modal-stepM35').modal('show')
					},'75');
				},'75');
				
				
				
			},
			error : function(xhr, status, data){
				alert('Status : '+status);
			}
		});
	}
	
	function saveStepM35(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'post',
			data : {
				csd35_seq : $('#csd35_seq').val(),
				csd35_step_fst : $('#csd35_step_fst').val(),
				csd35_petition : $('#csd35_petition').val(),
				csd35_plan_fst_start : $('#csd35_plan_fst_start').val(),
				csd35_plan_fst_stop : $('#csd35_plan_fst_stop').val(),
				csd35_step_snd : $('#csd35_step_snd').val(),
				csd35_plan_snd_start : $('#csd35_plan_snd_start').val(),
				csd35_plan_snd_stop : $('#csd35_plan_snd_stop').val(),
				csd35_snd_start : $('#csd35_start').val(),
				csd35_snd_stop : $('#csd35_stop').val(),
				csd35_cp : '<?=$_POST["est_seq"]?>',
				event : $('#csd35_event').val(),
			},
			success: function(data, status, xhr){
				console.log(data);
				$('#modal-stepM35').modal('hide');
				setTimeout(() => {
					readCsd35('<?=$_POST["est_seq"]?>');
				}, "250");
			},
			error : function(){
				alert("เกิดข้อผิดพลาด");
			}
		});
	}
	
	
	function delM35(x){
		if(confirm("คุณต้องการใช้เป็นขั้นตอนปัจจุบันใช่หรือไม่")){
			$.post('app/manage.php',{
				event : 'd_m35a',
				csd35_seq : x
			},function(data){
				alert(data);
				loadEST();
			});
		}
	}
	
	function setCurrent(x){
		if(confirm("คุณต้องการใช้เป็นขั้นตอนปัจจุบันใช่หรือไม่")){
			$.post('app/manage.php',{
				event : 're_m35a',
				est_seq : '<?=$_POST["est_seq"]?>',
				cp35_csd : x 
			},function(data){
				alert(data);
				readCsd35('<?=$_POST["est_seq"]?>')
			});
		}
	}
	
	
	function addNoteM35(x){
		$.ajax({
			url : 'app/manage.php',
			type : 'post',
			dataType : 'json',
			data : {
				event : 'r_noteM35',
				csd35_seq : x
			},
			success : function(data, status, xhr){
				$('#modal-note').modal('show');
				$('#note_step').html(data.fst);
				$('#note_step_1').html(data.snd);
				$('#modal-note_title').html("เพิ่มหมายเหตุ");
				$('#show_note_remark').html("");
				$('#edit_note_remark').html("");
				
				
				
				$('#note_cp').val(data.cp_no);
				$('#note_event').val('c_note35+u_csd35');
				$('#note_csd').val(x);
				$('#note_step_hidden').val(data.step_fst);
				$('#note_step_1_hidden').val(data.step_snd);
				
				$('#delBtn, #cancleBtn, #addBtnM').hide();
				//$('#note_cp ,#note_csd ,#note_id ,#note_uper ,#note_event ,#note_step_hidden, #note_step_1_hidden ').removeAttr("type");
				loadNoteForm(x);
			},
			error : function(xhr, status, data){
				console.log(status+'\n'+data);
			}
			
			
		});
	}
	function loadNoteForm(x){
		$.ajax({
			url : 'app/manage.php',
			type : 'post',
			data : {
				event : 'load-nfM35'
				,csd35_seq : x
			},
			success : function(data, status, xhr){
				$('#addBtnM').hide();
				$('#show_note_remark').html(data);
			},
			error : function(xhr, status, data){
				alert("เกิดข้อผิดพลาด");
			}
		});
	}
	
	function loadNoteData(x){
		$.ajax({
			url : 'app/manage.php',
			type : 'post',
			data : {
				event : 'load_csd35_noteM35'
				,csd35_seq : x
			},
			success : function(data, status, xhr){
				$('#show_note_remark').html(data);
			},
			error : function(xhr, status, data){
				alert("เกิดข้อผิดพลาด");
			}
		});
	}
	function saveNoteM35(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				event : $('#note_event').val(),
				note_mh : $('#select-note-remark').val(),
				note_remark : $('#note_remark').val(),
				note_step : $('#note_step_hidden').val(),
				note_step_1 : $('#note_step_1_hidden').val(),
				note_cp : $('#note_cp').val(),
				note_csd : $('#note_csd').val(),
				note_id : $('#note_id').val(),
			},
			success : function(data , status, xhr){
				//alert('Post data : '+status+'\n'+data);
				//console.log(data);
				$('#modal-note').modal('hide');
				alert(data);
				loadEST();
			},
			error : function(xhr, status, data){
				alert('Post data : '+status+'\n'+data);
			}
		});
	}
	
	function setNoteM35(x){
		$.ajax({
			url : 'app/manage.php',
			type : 'post',
			dataType : 'json',
			data : {
				event : 'r_noteM35',
				csd35_seq : x
			},
			success : function(data, status, xhr){
				$('#modal-note').modal('show');
				$('#note_step').html(data.fst);
				$('#note_step_1').html(data.snd);
				$('#modal-note_title').html("เพิ่มหมายเหตุ");
				$('#show_note_remark').html("");
				$('#edit_note_remark').html("");
				
				
				
				$('#note_cp').val(data.cp_no);
				$('#note_event').val('c_note35+u_csd35');
				$('#note_csd').val(x);
				$('#note_step_hidden').val(data.step_fst);
				$('#note_step_1_hidden').val(data.step_snd);
				$('#addBtnM').show().attr("onclick","loadNoteForm("+x+")");
				$('#delBtn, #cancleBtn, #editBtn').hide();
				//$('#note_cp ,#note_csd ,#note_id ,#note_uper ,#note_event ,#note_step_hidden, #note_step_1_hidden ').removeAttr("type");
				loadNoteData(x);
			},
			error : function(xhr, status, data){
				console.log(status+'\n'+data);
			}
			
			
		});
	}
	
	function delNoteM35(x){
		if(confirm("คุณต้องกการลบหมายเหตุใช่หรือไม่ ?")){
			$.ajax({
				url : 'app/manage.php',
				async : true,
				type : 'POST',
				data : {
					event : 'd_note35+u_csd35',
					note_id : x,
					csd35_seq : $('#note_csd').val()
				},
				success : function(data , status, xhr){
					$('#modal-note').modal('hide');
					alert(data);
					loadEST();
				},
				error : function(xhr, status, data){
					alert('Post data : '+status+'\n'+data);
				}
			});
		}
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
</script>
	
	