<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$title= "ผังเมืองรวม/ชุมชน ม.35";
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
	
	


$from = " ref_cityplan_step_detail_m35 , tb_cityplan , ref_province ";
$join = " csd35_status = 1 AND csd35_cp = cp_no AND cp_province = pvn_id ORDER BY csd35_seq DESC";

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
		<div class="row" id="info_bar">
			<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_value">จำนวน   <?=$all_data?>  ผัง </h3>
		</div>
		
		<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
	</div>	
	<br>
	<div class="container-fluid">
		<div class="row unprintable" style="margin-bottom:8px;">
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_backy" onclick="goto('a6m');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn-2" class="col-xs-6 col-md-6">
				<button id="btn_m35" onclick="addM35()" class="pull-right btn btn-primary btn-md" style="margin-top:6px;">เพิ่มผังเมือง</button>
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
		<div class="row">
				<?php
					$row = 20;
					
					
					for($n=0,$page=1;$page<=ceil($all_data/$row);$page++){
				?>
			<div class="table-responsive col-xs-12 my-tb">
				<table id="table_<?=$page?>" class="table table-bordered">
					<thead>
						<tr>
							<td style="text-align:center">ลำดับ</td>
							<td style="text-align:center">จังหวัด</td>
							<td style="text-align:center">ชื่อผังรวม</td>
							<td style="text-align:center">ขั้นตอนหลัก</td>
							<td style="text-align:center">ขั้นตอนย่อย 1</td>
							<td style="text-align:center">ขั้นตอนย่อย 2</td>
							<td style="text-align:center">หมายเหตุ</td>
							<td style="text-align:center">จัดการ</td>
						</tr>
					</thead>
					<tbody>
					<?php 
					//$n = 0;
					for($i=0;$i<$row;$i++){ 
						if($n>=$all_data){
							break;
						}
						$status_snd = status_snd('m35',$result[$n]['csd35_step_fst'],$result[$n]['csd35_step_snd'],$result[$n]['csd35_step_trd'],$result[$n]['csd35_petition'],$result[$n]['csd35_start'],$result[$n]['csd35_stop']);
						$status_trd = status_trd('m35',$result[$n]['csd35_step_fst'],$result[$n]['csd35_step_snd'],$result[$n]['csd35_step_trd'],$result[$n]['csd35_petition'],$result[$n]['csd35_start_2'],$result[$n]['csd35_stop_2']);
						/*
						if($_SESSION['intra_czid']=='demo-user-1'){
							print_r($status_trd);echo '<br>';
						}
						*/
						
					?>
						<tr>
							<td style="text-align:center"><?=$n+1;?></td>
							<td><?=$result[$n]['pvn_name']?></td>
							<td><?=$result[$n]['cp_name']?></td>
							<?php 
							if($result[$n]['csd35_update']==0){ 
								echo '<td colspan="3" style="text-align:center;color:red">โปรดระบุขั้นตอน</td>';
							}else{?>
							<td style="text-align:center">
							<?php 
								$step_fst = $result[$n]['csd35_step_fst']<1 ? '-': $result[$n]['csd35_step_fst'];
								echo $step_fst;
							?>
							</td>
							<td style="text-align:center;<?=$status_snd['bg-style']?>" title="<?=$status_snd['title']?>">
							<?php 
								$step_snd = $result[$n]['csd35_step_snd']<1 ? '-': $result[$n]['csd35_step_fst'].'.'.$result[$n]['csd35_step_snd'];
								echo $step_snd;
							?>
							</td>
							<td style="text-align:center;<?=$status_trd['bg-style']?>" title="<?=$status_trd['title']?>">
							<?php 
								$step_trd = $result[$n]['csd35_step_trd']<1 ? '-': $result[$n]['csd35_step_fst'].'.'.$result[$n]['csd35_step_snd'].'.'.$result[$n]['csd35_step_trd'];
								echo $step_trd;
							?>
							</td>
							<?php } ?>
							<td style="text-align:center">
								<?php 
								if($result[$n]['csd35_update']>0){
									if($result[$n]['csd35_note']>0){?>
										<span class="note" onclick="readNoteM35(<?=$result[$n]['csd35_step_fst']?>,<?=$result[$n]['csd35_step_snd']?>,<?=$result[$n]['csd35_step_trd']?>,<?=$result[$n]['cp_no']?>,<?=$result[$n]['csd35_seq']?>)"><a><i class="fa fa-list-alt"></i></a></span>
									<?php }else{ ?>
										<span class="plus" onclick="addNoteM35(<?=$result[$n]['csd35_step_fst']?>,<?=$result[$n]['csd35_step_snd']?>,<?=$result[$n]['csd35_step_trd']?>,<?=$result[$n]['cp_no']?>,<?=$result[$n]['csd35_seq']?>)"><a><i class="fa fa-plus"></i></a></span>
									<?php }
								}else{ echo '-';}?>
							</td>
							<td style="text-align:center">
								<?php 
								if($result[$n]['csd35_update']==0){
									?>
									<span class="trash" onclick="setStepM35(<?=$result[$n]['csd35_seq']?>,'true')"><a><i class="fa fa-list-ul"></i></a></span>
									<?php
								}else{
									?>
									<span class="list-alt" onclick="setStepM35(<?=$result[$n]['csd35_seq']?>)"><a><i class="fa fa-list-ul"></i></a></span>
									<?php
								}
								?>
							</td>
							
						</tr>
						<?php 
							$n++;
							$step_fst = '';
							$step_snd = '';
							$step_trd = '';
						} ?>
					</tbody>
				</table>
			</div>
			<?php }
				//pagin($row,$all_data);
				pagin($row,$all_data);
			?>
		</div>
	</div>

<div class="modal fade" id="modal-note">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:8px">
			<div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
				<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 id="modal_title" class="modal-title" style="font-family:Kanit;color:black"></h4>
			</div>
            <div class="modal-body">
				<form>	
					<div class="form-group" id="note_step_show">
						<span id="note_step"></span>	
					</div>
					
					<div class="form-group" id="note_step_1_show">
						<span id="note_step_1"></span>	
					</div>
					
					<div class="form-group" id="note_step_2_show">
						<span id="note_step_2"></span>	
					</div>
				
					<div class="form-group" id="show_note_remark"></div>
					<div class="form-group" id="edit_note_remark">.</div>
				<?php
				/*<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>*/
				?>
				
					<input type="hidden" id="note_cp" value="">
					<input type="hidden" id="note_csd" value="">
					<input type="hidden" name="note_id" class="form-control" id="note_id" value="" >
					<input type="hidden" id="note_uper" value="<?php echo $_SESSION['intra_czid']; ?>">				
					<input type="hidden" id="note_event" value="">
					<input type="hidden" id="note_step_hidden" value="">
					<input type="hidden" id="note_step_1_hidden" value="">
					<input type="hidden" id="note_step_2_hidden" value="">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
				<button id="addBtnM" type="button" class="btn btn-success pull-right" onclick="addNote2M35();">เพิ่มหมายเหตุ</button>
				<button id="editBtn" type="button" class="btn btn-success pull-right" onclick="saveNoteM35();">บันทึก</button>
				<button id="cancleBtn" type="button" class="btn btn-warning pull-right" onclick="cancleNote();">ยกเลิก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="delNoteM35();">ลบหมายเหตุ</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-csd35" style="">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มผังเมืองที่แก้ไขตาม ม.35</h4>
            </div>
            <div class="modal-body">
				<form>
					<div class="row" >
						<div class="form-group col-xs-12 col-md-12">
							<label for="select_cp_name">ระชื่อผังเมืองที่ต้องการแก้ไขตาม ม.35</label>
						
							
							<input id="select_cp_name" class="form-control"  onfocus="changeCP()" onkeyup="changeCP()"  placeholder="เลือก/ระบุชื่อผังเมือง" autocomplete="off">
							<div id="sub_select_cp" style="    display: flex;flex-direction: column;border: solid #dfdfdf 1px;max-height: 300px;overflow: auto;"></div>
						</div>
					</div>
				<input type="hidden" id="m35_event" value="c_m35">				
			</form>
			</div>
			<div  class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
				<button id="btn_add35" type="button" class="btn btn-success pull-right" onclick="saveM35()">บันทึก</button>				
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
									$sql ="SELECT * FROM ref_m35_fst_step WHERE m35_fst_id > 0";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['m35_fst_id'];?>"><?php echo 'ขั้นตอนที่ ',$row['m35_fst_id'],' ',$row['m35_fst_name'];?></option>									
								<?php } ?>
							</select>
						</div>
						<div id="div-petition" class="form-group col-xs-12 col-md-12">
							<label for="csd35_petition">คำร้อง : </label>
							<select class="form-control" id="csd35_petition" onchange="changePetition()">
								<option selected value="0">ระบุคำร้อง</option>
								<option value="1">มีคำร้อง</option>
								<option value="2">ไม่มีคำร้อง</option>
							</select>					
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
							<label for="csd35_start">วันที่เริ่มต้นขั้นตอนย่อย 1 (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd35_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="" >
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="csd35_stop">วันที่สิ้นสุดขั้นตอนย่อย 1 (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd35_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					<div class="row" id="div-step-2">
						<hr style="border:solid 1px #dbdbdb;max-width:100%;margin: 4px 0px 12px;border-top:transparent">
						<div class="form-group col-xs-12 col-md-12">
							<label for="csd35_step_trd">ขั้นตอนดำเนินการย่อย2</label>
							<select class="form-control" id="csd35_step_trd" onchange="changeTrdstepX()">
								<option value="0">ไม่ระบุขั้นตอน</option>
							</select>
						</div>
					</div>
					<div  class="row" id="div-step-2-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="csd35_start_2">วันที่เริ่มต้นขั้นตอนย่อย 2 (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd35_start_2" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" onchange="">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="csd35_stop_2">วันที่สิ้นสุดขั้นตอนย่อย 2 (ตามผลดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd35_stop_2" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
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

	function addNote2M35(){
		var fst = $('#note_step_hidden').val();
		var snd = $('#note_step_1_hidden').val();
		var trd = $('#note_step_2_hidden').val();
		var cp = $('#note_cp').val();
		var csd = $('#note_csd').val();
		addNoteM35(fst,snd,trd,cp,csd);
		/*console.log('fst : '+fst);
		console.log('snd : '+snd);
		console.log('trd : '+trd);
		console.log('csd : '+csd);
		console.log('cp : '+cp);*/
	}
	
	function addNoteM35(fst,snd,trd,cp,csd){	
	//alert(trd);
		$.post('app/manage.php',{
			event :'ru-c_csd35_note',
			step_fst : fst,			
			step_snd : snd,
			step_trd : trd
		},function(data){
			$('#addBtnM').hide();
			$('#delBtn').hide();
			$('#cancleBtn').hide();
			$('#editBtn').show();
			$('#note_step_hidden').val(fst);
			$('#note_step_1_hidden').val(snd);
			$('#note_step_2_hidden').val(trd);
			$('#note_cp').val(cp);
			$('#note_csd').val(csd);
			$('#modal-note').modal('show');
			$('#note_event').val('c_note+u_csd35');
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();
			$('#edit_note_remark').html('');
			$('#edit_note_remark').show();
			$('#modal_title').html("เพิ่มหมายเหตุ");
			$('#note_remark').val("");
			$('#note_id').val("");
		});	
	}
	
	function readNoteM35(fst,snd,trd,cp,csd){
		//alert('step : '+x+', sub : '+y+'cp_no : '+z);
		$.post('app/manage.php',{
			event : 'ru_csd35_note',
			step_id :  fst,
			sub_no : snd,
			trd_step : trd,
			cp_no : cp,
			csd_seq : csd
		},function(data){
			$('#addBtnM').show();
			$('#delBtn').hide();
			$('#editBtn').hide();
			$('#cancleBtn').hide();
			//console.log(data);
			$('#note_csd').val(csd);
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();
			$('#edit_note_remark').hide();
			$('#note_event').val('u_note+u_csd');
			$('#note_id').val("");
			$('#modal_title').html("หมายเหตุ");
			$('#modal-note').modal('show');
		});
	}
	function editNote(x,note_id,cp_no){
		var a = $('#div_note_'+x).html();
		$('#show_note_remark').hide();
		$.post('app/manage.php',{
			event : 'ru_selectnote-csd',
			note_id : note_id,
			cp_no : cp_no
		},function(data){
			$('#addBtnM').hide();
			$('#edit_note_remark').show();
			$('#edit_note_remark').html(data);
			$('#note_remark').val(a);
			$('#note_id').val(note_id);
			$('#note_cp').val(cp_no);
			$('#editBtn').show();
			$('#cancleBtn').show();
			$('#note_event').val('u_note+u_csd');
		});
	}
	
	function delNote(note_id,cp_no,csd_seq){
		if(confirm("คุณต้องการลบหมายเหตุใช่หรือไม่ ? ")){
			$.post('app/manage.php',{
				event : 'd_note+u_csd35',
				note_id : note_id,
				cp_no : cp_no,
				m35_seq : $('#note_csd').val()
				//cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				//showData();
				setTimeout(()=>{
					goto('m35');
				},'50');
			});
		}
	}
	function cancleNote(){
		$('.input_note').hide();
		$('.div_note').show();
		$('#editBtn').hide();
		$('#cancleBtn').hide();
		$('#addBtnM').show();
		$('#show_note_remark').show();
		$('#edit_note_remark').hide();
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
				note_step_2 : $('#note_step_2_hidden').val(),
				note_cp : $('#note_cp').val(),
				note_m35 : $('#note_csd').val(),
				note_id : $('#note_id').val(),
			},
			success : function(data , status, xhr){
				//alert('Post data : '+status+'\n'+data);
				//console.log(data);
				$('#modal-note').modal('hide');
				alert(data);
				//confirm(data);
				setTimeout(() => {
					goto('m35');
				},'50');
			},
			error : function(xhr, status, data){
				alert('Post data : '+status+'\n'+data);
			}
		});
	}





	function addM35(){
		$('#modal-csd35').modal('show');
		$('#sub_select_cp').hide();
		$('#btn_add35').attr('disabled','true');
		$('#select_cp_name').val('');
	}
	function saveM35(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data :{
				cp_name : $('#select_cp_name').val(),
				event : 'c_m35'
			},
			success:function(data, status, xhr,){
				alert(data);
				$('#modal-csd35').modal('hide');
				setTimeout(() => {
					goto('m35');
				}, "250");
			},
			error:function(xhr, status, data){
				alert('Status : '+status);
			}
		});
	}
	function sub_select_cp(cp_name){
		$('#select_cp_name').val(cp_name);
		$('#sub_select_cp').hide();
		$('#btn_add35').removeAttr('disabled');
	}	
	$("#select_status").change(function(){
		$('#select_cp').val('');
		$('#select_operate').val('0');
		$('#select_dflaw').val('0');
		
		$('#sub_select_cp').hide();
		$('#modal_status').val('0');
	});
	$("#select_cp_name").focusin(function(){
	  changeCP();
	});	
	function changeCP(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				event : 'select-cp-input(m35)',
				cp_name : $('#select_cp_name').val(),
				type : $('#select_status').val()
			},success : function(data, status, xhr){
				//alert(data);
				$('#sub_select_cp').show();
				$('#sub_select_cp').html(data);
			},error : function(xhr, status ,data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	
	function setStepM35(x,y){
		$.ajax({
			url : 'app/manage.php',
			type : 'POST',
			async : true, 
			dataType : 'json',
			data : {
				csd35_seq : x,
				event : 'r_stepM35'
			},
			success : function(data, status, xhr){
				
				var csd35_step_fst = data.csd35_step_fst;
				var csd35_step_snd = data.csd35_step_snd;
				var csd35_step_trd = data.csd35_step_trd;
				var csd35_petition = data.csd35_petition;
				$('#csd35_step_fst').val(csd35_step_fst);
				changeFststepX();
				//load_snd_step();
				setTimeout(() => {
					$('#csd35_step_snd').val(csd35_step_snd);
					changeSndstepX();
					//load_trd_step();
					setTimeout(() => {
						$('#csd35_step_trd').val(csd35_step_trd);
						changeTrdstepX();
					},'80');
				},'80');

				
				setTimeout(() => {
					//alert('test');
					$('#csd35_step_fst').val(csd35_step_fst);
					$('#csd35_step_snd').val(csd35_step_snd);
					$('#csd35_step_trd').val(csd35_step_trd);
					
					$('#csd35_seq').val(x);
					$('#csd35_start').val(data.csd35_start);
					$('#csd35_stop').val(data.csd35_stop);
					$('#csd35_start_2').val(data.csd35_start_2);
					$('#csd35_stop_2').val(data.csd35_stop_2);
					//$('#csd35_petition').val(data.csd35_petition);
					
					
					if(y!='true'){
						$('#csd35_event').val('u_csd35-date_only');
						$('#csd35_step_fst , #csd35_step_snd , #csd35_step_trd').attr('disabled','true');
						if(data.csd35_petition>0){
							$('#csd35_petition').attr('disabled','true');
						}else{
							$('#csd35_petition').removeAttr('disabled');
						}
					}
					else{
						$('#csd35_event').val('u_csd35-step_date');
						$('#csd35_step_fst , #csd35_step_snd , #csd35_step_trd').removeAttr('disabled');
					}
					
					$('#csd35_petition').val(csd35_petition);
					
					
					if($('#csd35_step_fst').val()!=csd35_step_fst || $('#csd35_step_snd').val()!=csd35_step_snd || $('#csd35_step_trd').val()!=csd35_step_trd ){
						/*
						alert('เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้ง')
						$('#modal-stepM35').modal('show');
						*/
					}else{
						$('#modal-stepM35').modal('show');
					}
					
					
				},'170');
				
			},error : function(xhr, status, data){
				alert('Status : '+status);
			}
		});
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
					event : 'load_snd_stepM35X'
				},
				success : function(data, status, xhr){
					$('#csd35_step_snd').html(data);
					//alert('finish_fst');
				},
				error : function(xhr, status, data){
					alert('Status : '+status);
				}
			});
		}
	}
	
	function load_trd_step(){
		if($('#csd35_step_fst').val()<1){
			$('#csd35_step_snd').html('<option value="0">ไม่ระบุขั้นตอน</option>');
		}
		else{
			if($('#csd35_step_snd').val()<1){
				$('#csd35_step_trd').html('<option value="0">ไม่ระบุขั้นตอน</option>');
			}
			else{
				
				$.ajax({
					url : 'app/manage.php',
					type : 'POST',
					async : true,
					data : {
						csd35_step_fst : $('#csd35_step_fst').val(),
						csd35_step_snd : $('#csd35_step_snd').val(),
						event : 'load_trd_stepM35X'
					},
					success : function(data, status, xhr){
						$('#csd35_step_trd').html(data);
						//alert('finish_snd');
					},
					error : function(xhr, status, data){
						alert('Status : '+status);
					}
				});
			}
		}
	}
	
	
	function changeFststepX(){
		$('#div-step-1').hide();
		$('#div-step-2').hide();
		$('#div-step-1-date').hide();
		$('#div-step-2-date').hide();
		$('#div-petition').hide();
		$('#cp_step_start').val("");
		$('#cp_step_stop').val("");
		if($('#csd35_step_fst').val()>0){
			if($('#csd35_step_fst').val()==6){
				$('#csd35_step_snd').val(0);
				$('#csd35_petition').val(0);
				$('#div-petition').show();
			}else{
				$('#csd35_petition').val(0);
				$('#div-petition').hide();
			}
			$('#div-step-1').show();
		}
		load_snd_step();
		//alert('finish_fst');
	}
	
	function changeSndstepX(){
		$('#div-step-2-date').hide();
		if($('#csd35_step_fst').val()>0){
			if($('#csd35_step_snd').val()>0){
				//$('#div-step-2').show();
				$('#div-step-1-date').show();
			}else{
				$('#div-step-2').hide();
				$('#div-step-1-date').hide();
			}
		}
		load_trd_step();
	}
	
	function changeTrdstepX(){
		if($('#csd35_step_trd').val()<1){
			$('#div-step-2-date').hide();
		}else{
			$('#div-step-2-date').show();
			
		}
	}
	
	function saveStepM35(){
		$.ajax({
			url : 'app/manage.php',
			type : 'POST',
			async : true,
			data : {
				csd35_seq : $('#csd35_seq').val(),
				csd35_cp : $('#csd35_cp').val(),
				csd35_step_fst : $('#csd35_step_fst').val(),
				csd35_step_snd : $('#csd35_step_snd').val(),
				csd35_step_trd : $('#csd35_step_trd').val(),
				csd35_start : $('#csd35_start').val(),
				csd35_stop : $('#csd35_stop').val(),
				csd35_start_2 : $('#csd35_start_2').val(),
				csd35_stop_2 : $('#csd35_stop_2').val(),
				csd35_petition : $('#csd35_petition').val(),
				event : $('#csd35_event').val()
			},
			success : function(data, status, xhr){
				alert(data);
				$('#modal-stepM35').modal('hide');
				setTimeout(() => {
					goto('m35');
				}, "250");
			},
			error : function(xhr ,status ,data){
				alert('Status : '+status);
			}
		});
	}
</script>
	
	