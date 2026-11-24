<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  
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


  
  if(isset($_POST['cp_no'])){
	 $_SESSION['cp_no'] =  $_POST['cp_no'];
  }else{
	 $_POST['cp_no'] = $_SESSION['cp_no'];
  }
  //unset($_SESSION['sql2']);
  //echo $_SESSION['sql1'];
  //echo $_SESSION['sql'];
  //echo $_POST['name'];
  //echo $_POST['affiliation'];
  //echo $_POST['province'];
  //echo $_POST['aff_id'];
 
	$title = "หมายเหตุ";
	$sql ="SELECT * 
FROM tb_cityplan,ref_up_fst_step,ref_up_snd_step,ref_province,ref_status WHERE cp_no = '".$_POST['cp_no']."' AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_province = pvn_id AND status_id = cp_status";
	//echo $sql;
	$result = selectSql($sql);
	$csd = $result[0]['cp_csd'];
	$name = $result[0]['cp_name'];
	$step = $result[0]['step_name'];
	$pvn = $result[0]['pvn_name'];
	$current = $result[0]['step_id'].".".$result[0]['sub_no']." ".$result[0]['sub_name'];
	$status = $result[0]['status_name'];
 ?>
 <style>
 	noteshow{
		-webkit-print-color-adjust:exact !important;
	}
	.tablex > thead >tr>td{
		min-width:100px;
		
	}
@media print{
	
	#div-btn ,.br-xs {
		display:none!important;
	}
	.r-float{
		position:absolute;
		right:0;
		margin-right:15px;
	}
	tr{
		background : #295DBD !important;		
		-webkit-print-color-adjust:exact !important;
	}
	
	body:after{
		position:fixed;
		display:inline!important;
		text-align:center!important;
		font-size:50pt!important;
		color:red!important;
		content:'ไม่สามารถพิมพ์ได้';
		top:50%!important;
		left:50%!important;
		transform:translate(-50%,-50%);
		margin:0px!important;
		background:ref!important;
	}


	
	@page{
		size:A4 landscape!important;
		margin:0mm 0mm;
		
	}
	
	
 }
 </style>
 <div style="display:inline;z-index:1;text-align:right;" class="pull-right unprintable"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
 <div class="col-12 unprintable">
	<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"><?php echo $title;?></h2>
	<h3 class="hidden-md hidden-lg GHfont-mini unprintable" style="font-family: Kanit;text-align:center"><?php echo $title;	?></h3>
</div>	
<br class="unprintable">
<div class="container-fluid unprintable">
	<div class="row">
		<div id="div-btn" class="col-xs-12 col-md-12">
			<button id="btn_back2" onclick="BackSSR();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			<?php if($_SESSION['status']!=0){?> <button style="margin-top:6px;float:right" class="btn btn-primary" onclick="addCsd()"><i class="fa fa-plus" style="padding-right:2px"></i> เพิ่มขั้นตอน</button> <?php } ?>
		</div>
		<div id="div-name_cp" class="col-xs-12 " style="margin-top:8px;font-size:17px">
			<span><?php echo '<b>ชื่อผังเมือง : </b>'.$name; ?></span>
			<br class="br-xs">
			<span class="r-float"><?php echo '<b>จังหวัด : </b>'.$pvn; ?></span>
		</div>
		<div class="hidden-xs" hidden id="div-province_cp" class="col-xs-6 col-md-4" style="margin-top:8px;font-size:17px">
			<span class="pull-right"><?php echo '<b>สถานะผัง : </b>'.$status; ?></span>
		</div>
			<!--<div id="div-step_cp" class="col-xs-12 col-md-12" style="margin-top:8px;font-size:18px">
				<span class="pull-left"><?php //echo "<b>ขั้นตอนปัจจุบัน : </b>".$current; ?></span>
			</div>-->
	</div>
	<div id="noteshow" class="table-responsive" style="border-color:transparent;margin-top:6px"></div>
</div>

<div class="modal fade" id="modal-csd">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:8px">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มขั้นตอน</h4>
            </div>
            <div class="modal-body">
				<form>
					<div id="div_csd_step_fst" class="row">
						<div id="div_inner_step_fst" class="form-group col-xs-12 ">
						<label  for="csd_step_fst">ขั้นตอนหลัก</label>
						<select id="csd_step_fst" class="form-control" onchange="fst_step_change(),change_csd_step()">
							<option value="0" selected>ไม่ระบุขั้นตอน</option>
							<?php 	
								$sql = "SELECT * FROM ref_up_fst_step WHERE step_id != 0 ORDER BY step_id ASC";
								$result = selectSql($sql);
								foreach($result as $row){ ?>
										<option value="<?php echo $row['step_id'];?>"><?php echo 'ขั้นตอนที่  '.$row['step_id'].' : '.$row['step_name'];?></option>
							<?php 	} ?>
						</select>
						</div>
						<div id="div_csd_petition" class="form-group col-xs-12 col-md-4">
							<label for="csd_petition">คำร้อง</label>
							<select id="csd_petition" class="form-control" onchange="petition()">
								<option value="0">ไม่มีคำร้อง</option>
								<option value="1">มีคำร้อง</option>
							</select>					
						</div>
					</div>
					
					
					
					<div id="div_csd_step_snd" class="row">
						<div class="form-group col-xs-12">
							<label  for="csd_step_snd">ขั้นตอนรอง</label>
							<select id="csd_step_snd" class="form-control" onchange="snd_step_change(),change_csd_step()">
								<option value="0" selected>ไม่ระบุขั้นตอน</option>
							</select>	
						</div>
					</div>
					
					<div id="div_csd_step_trd" class="row">
						<div class="form-group col-xs-12">
							<label  for="csd_step_trd">ขั้นตอนรอง2</label>
							<select id="csd_step_trd" class="form-control" onchange="change_csd_step()">
								<option value="0" selected>ไม่ระบุขั้นตอน</option>
							</select>	
						</div>
					</div>
					<div id="div_csd_date_plan" class="row">
						<div class="form-group col-xs-6">
							<label for="csd_start_plan">วันที่เริ่มต้น (ตามแผนดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd_start_plan" value="" placeholder="ปี-เดือน-วัน"   onchange="csd_plan_auto_date()" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						<div class="form-group col-xs-6">
							<label for="csd_stop_plan">วันที่สิ้นสุด (ตามแผนดำเนินการ)</label>
							<input class="form-control " type="text" id="csd_stop_plan" value="" placeholder="ปี-เดือน-วัน" autocomplete="off" readonly>
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					<div id="div_csd_date" class="row">
						<div class="form-group col-xs-6">
							<label for="csd_start">วันที่เริ่มต้น (วันที่เริ่มดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						<div class="form-group col-xs-6">
							<label for="csd_stop">วันที่สิ้นสุด (วันที่สิ้นสุดดำเนินการ)</label>
							<input class="form-control cp_date" type="text" id="csd_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					
					<input type="hidden" name="csd_seq" class="form-control" id="csd_seq" value="">
					<input type="hidden" name="cp_no" class="form-control" id="cp_no" value="" >
					<input type="hidden" name="cp_id" class="form-control" id="cp_id" value="" >
					<input type="hidden" id="event" value="">
					<input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
				</form>
			</div>
            <div style="margin-top:-10px" class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="saveCsd();">บันทึก</button>
            </div>
		</div>
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
				<button id="addBtnM" type="button" class="btn btn-success pull-right" onclick="addNote2();">เพิ่มหมายเหตุ</button>
				<button id="editBtn" type="button" class="btn btn-success pull-right" onclick="saveNote();">บันทึก</button>
				<button id="cancleBtn" type="button" class="btn btn-warning pull-right" onclick="cancleNote();">ยกเลิก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="delNote();">ลบหมายเหตุ</button>
			</div>
		</div>
	</div>
</div>
	<?php 
	if($_SESSION['status']!=0){?>
		<script>
			function noteshow(){
				$.post('report/pro_table_step_noteX.php',{
					cp_no : '<?php echo $_POST["cp_no"];?>',
					cp_csd : '<?php echo $csd;?>'
				},function(data){
					$('#noteshow').html(data);
				});	
			}
		</script>
	<?php }
	else{ ?>
		<script>
			function noteshow(){
				$.post('report/pro_table_step_noteXU.php',{
					cp_no : '<?php echo $_POST["cp_no"];?>',
					cp_csd : '<?php echo $csd;?>'
				},function(data){
					$('#noteshow').html(data);
				});	
			}
		</script>	
	<?php } ?>
	<script>
	$('#exportPdfBtn').hide();
	function BackSSR(){
		$('#mainshow').load('report/show_result.php');
	}
	function checkA(){
		alert('<?php echo $csd;?>');
	}
	noteshow();
	
	
	function ureadNote(fst,snd,trd,cp,csd){
		//alert('step : '+x+', sub : '+y+'cp_no : '+z);
		$.post('app/manage.php',{
			event : 'ru_csd_note',
			step_id :  fst,
			sub_no : snd,
			trd_step : trd,
			cp_no : cp,
			csd_seq : csd
		},function(data){
			$('#addBtnM').hide();
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
			$('#btnGear').hide();
			$('#btnTrash').hide();
		});
	}
	/*
	function addNote(x){
		$.post('app/manage.php',{
			event : 'xxxxx',
			csd_seq : x
		},function(data){
			$('#div_note_xxx').html(data);
			
			function addNote(){
			$('#modal-note').modal('show');
			$('#event').val('c_note_csd'); 
			$('#note_remark').val('');
			$('#note_remark').val('<?php echo $_POST["cp_no"]?>');
		}
		});
		//$('#modal-note').modal('show');
		//$('#event').val('c_note_csd'); 
		//$('#note_remark').val('');
		//$('#note_remark').val('<?php echo $_POST["cp_no"]?>');
	}*/
	function addNote2(){
		var fst = $('#note_step_hidden').val();
		var snd = $('#note_step_1_hidden').val();
		var trd = $('#note_step_2_hidden').val();
		var cp = $('#note_cp').val();
		var csd = $('#note_csd').val();
		addNote(fst,snd,trd,cp,csd);
		/*console.log('fst : '+fst);
		console.log('snd : '+snd);
		console.log('trd : '+trd);
		console.log('csd : '+csd);
		console.log('cp : '+cp);*/
	}
	
	function addNote(fst,snd,trd,cp,csd){	
	//alert(trd);
		$.post('app/manage.php',{
			event :'ru-c_csd_note',
			step_id : fst,			
			sub_no : snd,
			trd_step : trd,
			cp_no : cp
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
			$('#note_event').val('c_note+u_csd');
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();
			$('#edit_note_remark').html('');
			$('#edit_note_remark').show();
			$('#modal_title').html("เพิ่มหมายเหตุ");
			$('#note_remark').val("");
			$('#note_id').val("");
		});	
	}
	
	function readNote(fst,snd,trd,cp,csd){
		//alert('step : '+x+', sub : '+y+'cp_no : '+z);
		$.post('app/manage.php',{
			event : 'ru_csd_note',
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
			$('#modal_title').html("แก้ไข/ลบหมายเหตุ");
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
				event : 'd_note+u_csd',
				note_id : note_id,
				cp_no : cp_no,
				csd_seq : $('#note_csd').val()
				//cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				noteshow();
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
	
	function saveNote(){
		var note_remark_text = "";
		var new_myhead = 0;
		if($('#select-note-remark').val()!=""){
			note_remark_text = $('#select-note-remark').val();
		}else{
			note_remark_text = $('#note_remark').val();
			new_myhead = 1;
			
		}
		$.post('app/manage.php',{
			event : $('#note_event').val(),
			note_remark : note_remark_text,
			note_step : $('#note_step_hidden').val(),
			note_step_1 : $('#note_step_1_hidden').val(),
			note_step_2 : $('#note_step_2_hidden').val(),
			note_cp : $('#note_cp').val(),
			note_csd : $('#note_csd').val(),
			note_id : $('#note_id').val(),
			new_myhead : new_myhead
		},function(data){
			//console.log(data);	
			$('#modal-note').modal('hide');
			alert(data);
			noteshow();
		});
	}
	
	function change_csd_step(){
		 $('#csd_start_plan').val(''),
		 $('#csd_stop_plan').val(''),
		 $('#csd_start').val(''),
		 $('#csd_stop').val('')		 
	}
	function csd_plan_auto_date(){
		$.post('app/manage.php',{
			event : 'csd_plan_auto_date',
			csd_step_fst : $('#csd_step_fst').val(),
			csd_step_snd : $('#csd_step_snd').val(),
			csd_step_trd : $('#csd_step_trd').val(),
			csd_plan_start : $('#csd_start_plan').val(),
			csd_petition : $('#csd_petition').val()
		},function(data){
			$('#csd_stop_plan').val(data);
			console.log(data);
		});
	}
	function addCsd(){
		$('#event').val('c_csd');
		$('#modal-csd').modal('show');
		$('#div_csd_petition').hide();
		$('#div_csd_date_plan').hide();
		$('#div_csd_date').hide();
		$('#csd_petition').val(0);
		$('#csd_start_plan').val('');
		$('#csd_stop_plan').val('');
		$('#csd_start').val('');
		$('#csd_stop').val('');	
		$('#csd_step_fst').val(0);		
		fst_step_change();
		$('#csd_step_fst').removeAttr('disabled');
		$('#csd_step_snd').removeAttr('disabled');
		$('#csd_step_trd').removeAttr('disabled');
		/*if($('#csd_step_fst').val()==0){
			$('#div_csd_step_snd').hide();
			$('#csd_step_snd').val(0)
		}
		if($('#csd_step_snd').val()==0){
			$('#div_csd_step_trd').hide();
			$('#csd_step_trd').val(0)
		}*/
	}
	
	function saveCsd(){
		$.post('app/manage.php',{
			csd_seq : $('#csd_seq').val(),
			csd_cp : '<?php echo $_POST["cp_no"];?>',
			csd_step_fst : $('#csd_step_fst').val(), 
			csd_step_snd : $('#csd_step_snd').val(),
			csd_step_trd : $('#csd_step_trd').val(),
			csd_petition : $('#csd_petition').val(),
			csd_plan_start : $('#csd_start_plan').val(),
			csd_plan_stop  : $('#csd_stop_plan').val(),
			csd_start : $('#csd_start').val(),
			csd_stop  : $('#csd_stop').val(),
			event : $('#event').val()
		},function(data){
			$('#modal-csd').modal('hide');
			alert(data);
			//console.log(data);
			noteshow();
		});
	}
	
	function delCsd(x){
		if(confirm("คุณต้องการลบขั้นตอนนี้ใช่หรือไม่")){
			$.post('app/manage.php',{
				event : 'd_csd',
				csd_seq : x 			
			},function(data){
				alert(data);
				noteshow();
			});
		}
	}
	
	function readCsd(x){
		$.post('app/manage.php',{
			event : 'ru_csd',
			csd_seq : x
		},function(data){
			$('#event').val('u_csd')
			var csd_step_fst = data.csd_step_fst;
			var csd_step_snd = data.csd_step_snd;
			var csd_step_trd = data.csd_step_trd;
			var csd_start = data.csd_start;
			var csd_stop = data.csd_stop;
			var csd_plan_start = data.csd_plan_start;
			var csd_plan_stop = data.csd_plan_stop;
			var csd_petition = data.csd_petition;					
			
			//alert(csd_step_snd);
			$('#modal-csd').modal('show');
			
			$('#csd_step_fst').val(csd_step_fst);
			re_fst_step_change(csd_step_snd,csd_step_trd);
			$('#csd_step_trd').val(csd_step_trd);
			//snd_step_change();
			//alert(csd_step_trd);

			$('#csd_start').val(csd_start);
			$('#csd_stop').val(csd_stop);
			$('#csd_start_plan').val(csd_plan_start);
			$('#csd_stop_plan').val(csd_plan_stop);
			$('#csd_petition').val(csd_petition);
			$('#csd_seq').val(x);			
			$('#csd_step_fst').attr('disabled',true);
			$('#csd_step_snd').attr('disabled',true);
			$('#csd_step_trd').attr('disabled',true);
			
		},'json');
	}
	
		
	
	function re_fst_step_change(x,y){		
		if($('#csd_step_fst').val() == 6){
			$('#div_inner_step_fst').removeClass('col-md-12');
			$('#div_inner_step_fst').addClass('col-md-8');
			$('#div_csd_petition').show();
		}
		else{
			$('#div_inner_step_fst').removeClass('col-md-8');
			$('#div_inner_step_fst').addClass('col-md-12');
			$('#div_csd_petition').hide();
		}
		$.post('app/manage.php',{
			event : 'r_csd_fst',
			step_id : $('#csd_step_fst').val()
		},function(data){
			$('#csd_step_snd').html(data);
			$('#csd_step_snd').val(x);
			$('#div_csd_step_snd').show();	
			re_snd_step_change(y)			
			//console.log(data);
			if($('#csd_step_fst').val()==0){
				$('#div_csd_step_snd').hide();
				$('#csd_step_snd').val(0)
			}
		});
	}
	
	function re_snd_step_change(x){
		//alert( $('#csd_step_fst').val()+"---"+ $('#csd_step_snd').val());
		$.post('app/manage.php',{
			event : 'r_csd_snd',
			step_id : $('#csd_step_fst').val(),
			sub_no : $('#csd_step_snd').val()
		},function(data){
			$('#csd_step_trd').html(data);
			$('#csd_step_trd').val(x);
			//$('#div_csd_step_trd').show();
			if($('#csd_step_snd').val()==0){
				$('#div_csd_step_trd').hide();
				$('#csd_step_trd').val(0)
			}
		});
		
	}
	
	function fst_step_change(){
		if($('#csd_step_fst').val() == 6){
			$('#div_inner_step_fst').removeClass('col-md-12');
			$('#div_inner_step_fst').addClass('col-md-8');
			$('#div_csd_petition').show();
		}
		else{
			$('#div_inner_step_fst').removeClass('col-md-8');
			$('#div_inner_step_fst').addClass('col-md-12');
			$('#div_csd_petition').hide();
		}
		$.post('app/manage.php',{
			event : 'r_csd_fst',
			step_id : $('#csd_step_fst').val()
		},function(data){
			$('#csd_step_snd').html(data);
			$('#div_csd_step_snd').show();
			snd_step_change()
			//console.log(data);
			if($('#csd_step_fst').val()==0){
				$('#div_csd_step_snd').hide();
				$('#csd_step_snd').val(0)
			}
			if($('#csd_step_fst').val()==6){
				petition();
			}
		});
		
	}
	
	function petition(){
		if($('#csd_petition').val()!=1){ 
			$('#csd_step_snd option[value="1"]').hide();
			$('#csd_step_snd option[value="2"]').hide();
			$('#csd_step_snd option[value="3"]').hide();
			$('#csd_step_snd option[value="6"]').hide();
		}else{
			$('#csd_step_snd option[value="1"]').show();
			$('#csd_step_snd option[value="2"]').show();
			$('#csd_step_snd option[value="3"]').show();
			$('#csd_step_snd option[value="6"]').show();			
		}
	}
	
	function snd_step_change(y){
		$.post('app/manage.php',{
			event : 'r_csd_snd',
			step_id : $('#csd_step_fst').val(),
			sub_no : $('#csd_step_snd').val()
		},function(data){
			$('#csd_step_trd').html(data);
			if($('#csd_step_snd').val()==0){
				$('#div_csd_step_trd').hide();
				$('#csd_step_trd').val(0);
			}
		});
		
	}
	
	
	$(function(){  
		var thaiYear = function (ct) {
			var leap=3;  
			var dayWeek=["พฤ.", "ศ.", "ส.", "อา.","จ.", "อ.", "พ."];  
			if(ct){  
				var yearL=new Date(ct).getFullYear()-543;  
				leap=(((yearL % 4 == 0) && (yearL % 100 != 0)) || (yearL % 400 == 0))?2:3;  
				if(leap==2){  
					dayWeek=["ศ.", "ส.", "อา.", "จ.","อ.", "พ.", "พฤ."];  
				}  
			}              
			this.setOptions({  
				i18n:{ th:{dayOfWeek:dayWeek}},dayOfWeekStart:leap,  
			})                
		}; 
		$(".cp_date").datetimepicker({
			timepicker:false,
			format:'Y-m-d',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
			lang:'th',  // แสดงภาษาไทย
			onChangeMonth:thaiYear,          
			onShow:thaiYear,                  
			yearOffset:543,  // ใช้ปี พ.ศ. บวก 543 เพิ่มเข้าไปในปี ค.ศ
			closeOnDateSelect:true,
		});       
	   
	});
	
	</script>
	
	
	
	
	
	
	
	
	
	
	
	