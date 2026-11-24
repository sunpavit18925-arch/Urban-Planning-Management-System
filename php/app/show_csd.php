<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$sql = "SELECT * FROM tb_cityplan, ref_province WHERE cp_province = pvn_id AND cp_no = '".$_POST['cp_no']."'";
$name = selectSql($sql);
$csd = $name[0]['cp_csd'];
//echo $csd;
$_SESSION['page'] = $_POST['page'];
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
</style>
<h2 class="GHfont unprintable" style="text-align:center;font-family:Kanit"><?php echo 'ขั้นตอนดำเนินการทั้งหมด';//$name[0]['cp_name']; ?></h2>
<div class="container-fluid unprintable">
		<div class="row">
			<div id="record_bar" class="col-xs-12">
				<button style="float:left" class="btn btn-danger" onclick="goto('a1-ver2')">ย้อนกลับ</button>
				<button style="float:right" class="btn btn-primary" onclick="addCsd()"><i class="fa fa-plus"></i>เพิ่มขั้นตอน</button>
				<?php /*<h3 style="font-family: Kanit;margin:0px;margin-top:7px">ขั้นตอนปัจจุบัน  : <b id="count_data">... </b></h3>*/ ?>
			</div>
			<div id="div-name_cp" class="col-xs-12 " style="margin-top:8px;font-size:17px">
				<span><?php echo '<b>ชื่อผังเมือง : </b>'.$name[0]['cp_name']; ?></span>
				<br class="br-xs">
				<span class="r-float"><?php echo '<b>จังหวัด : </b>'.$name[0]['pvn_name']; ?></span>
			</div>
			<?php /*
			<div class="col-xs-6" style="text-align:right">
				<input style="margin-top:14px;border-style:solid;border-color:black;border-width:1px;border-radius:8px;width:100%;font-size:18px;padding-left:4px" placeholder="ค้นหาผังเมือง">
			</div> 
			*/ ?>
		</div>		
		<div id="csd_show" class="" style="border-color:transparent;margin-top:6px">
			<img src="img/loading3.gif" class="center">
		</div>
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
						<select id="csd_step_fst" class="form-control" onchange="fst_step_change(),change_csd_step_fst()">
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
							<select id="csd_petition" class="form-control" onchange="petitionX()">
								<option value="0">ไม่มีคำร้อง</option>
								<option value="1">มีคำร้อง</option>
							</select>					
						</div>
					</div>
					
					<div id="div_csd_step_fst_date" class="row">
						<div class="form-group col-xs-12 col-sm-6">
							<label>วันที่เริ่มต้นขั้นตอนหลัก (ตามแผน)</label>
							<input type="text "class="form-control cp_date" id="csd_step_fst_start" onchange="changeCsdFstDate()" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
						<div class="form-group col-xs-12 col-sm-6">
							<label>วันที่สิ้นสุดขั้นตอนหลัก (ตามแผน)</label>
							<input class="form-control disabled" id="csd_step_fst_stop" placeholder="ปี-เดือน-วัน"  autocomplete="off" readonly>
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
					</div>
					
					
					
					<div id="div_csd_step_snd" class="row">
					<hr  style="border:solid #dfdfdf 1px;margin:0px 0px 8px 0px;border-top:transparent">
						<div class="form-group col-xs-12">
							<label  for="csd_step_snd">ขั้นตอนย่อย</label>
							<select id="csd_step_snd" class="form-control" onchange="snd_step_change(),change_csd_step_snd()">
								<option value="0" selected>ไม่ระบุขั้นตอน</option>
							</select>	
						</div>
					</div>
					<div id="div_csd_step_snd_date" class="row">
						<div class="form-group col-xs-12 col-sm-6">
							<label>วันที่เริ่มต้นขั้นตอนย่อย 1 (ตามแผน)</label>
							<input type="text "class="form-control cp_date" id="csd_step_snd_start" onchange="changeCsdSndDate()" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
						<div class="form-group col-xs-12 col-sm-6">
							<label>วันที่สิ้นสุดขั้นตอนย่อย 1 (ตามแผน)</label>
							<input class="form-control disabled" id="csd_step_snd_stop" placeholder="ปี-เดือน-วัน"  autocomplete="off" readonly>
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
						<div class="form-group col-xs-12 col-sm-6">
							<label for="csd_start">วันที่เริ่มดำเนินการ (ตามผล)</label>
							<input class="form-control cp_date" type="text" id="csd_start" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						<div class="form-group col-xs-12 col-sm-6">
							<label for="csd_stop">วันที่สิ้นสุดดำเนินการ (ตามผล)</label>
							<input class="form-control cp_date" type="text" id="csd_stop" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					
					<div id="div_csd_step_trd" class="row">
					<hr  style="border:solid #dfdfdf 1px;margin:0px 0px 8px 0px;border-top:transparent">
						<div class="form-group col-xs-12">
							<label  for="csd_step_trd">ขั้นตอนย่อย2</label>
							<select id="csd_step_trd" class="form-control" onchange="change_csd_step_trd()">
								<option value="0" selected>ไม่ระบุขั้นตอน</option>
							</select>	
						</div>
					</div>
					<div id="div_csd_step_trd_date" class="row">					
						<div class="form-group col-xs-12 col-sm-6">
							<label>วันที่เริ่มต้นขั้นตอนย่อย 2 (ตามแผน)</label>
							<input type="text "class="form-control cp_date" id="csd_step_trd_start" onchange="changeCsdTrdDate()" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
						<div class="form-group col-xs-12 col-sm-6">
							<label>วันที่สิ้นสุดขั้นตอนย่อย 2 (ตามแผน)</label>
							<input class="form-control disabled" id="csd_step_trd_stop" placeholder="ปี-เดือน-วัน"  autocomplete="off" readonly>
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
						<div class="form-group col-xs-12 col-sm-6">
							<label for="csd_start_2">วันที่เริ่มดำเนินการ (ตามผล)</label>
							<input class="form-control cp_date" type="text" id="csd_start_2" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						<div class="form-group col-xs-12 col-sm-6">
							<label for="csd_stop_2">วันที่สิ้นสุดดำเนินการ (ตามผล)</label>
							<input class="form-control cp_date" type="text" id="csd_stop_2" value="" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					
					<hr hidden style="border:solid #dfdfdf 1px;margin:0px 0px 8px 0px;border-top:transparent">
					<div id="div_csd_date" class="row">
						
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

<script  type="text/javascript">
	$('#exportPdfBtn').hide();
	showData();
	function showData(){
		$.post('app/pro_csd.php',{
			cp_no : '<?php echo $_POST["cp_no"]?>',
			cp_csd : '<?php echo $csd;?>'
		},function(data){
			$('#csd_show').html(data)
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
			trd_step : trd
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
				event : 'd_note+u_csd',
				note_id : note_id,
				cp_no : cp_no,
				csd_seq : $('#note_csd').val()
				//cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				showData();
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
	/*
	function saveNote(){
		var new_myhead = 1;
		if($('#note_remark').val()=='' || $('#note_remark').val() ==null){
			//alert('null');
			$('#note_remark').val($('#select-note-remark').val())
			new_myhead = 0;
		}
		$.post('app/manage.php',{
			event : $('#note_event').val(),
			note_remark : $('#note_remark').val(),
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
			showData();
		});
	}
	*/
	function saveNote(){
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
				note_csd : $('#note_csd').val(),
				note_id : $('#note_id').val(),
			},
			success : function(data , status, xhr){
				//alert('Post data : '+status+'\n'+data);
				//console.log(data);
				$('#modal-note').modal('hide');
				showData();
			},
			error : function(xhr, status, data){
				alert('Post data : '+status+'\n'+data);
			}
		});
	}
	function setCurrent(x){
		if(confirm("คุณต้องการใช้เป็นขั้นตอนปัจจุบันใช่หรือไม่")){
			$.post('app/manage.php',{
				event : 're_csd',
				csd_seq : x ,
				cp_no : '<?php echo $_POST["cp_no"]?>'
			},function(data){
				//alert(data);
				openStep('<?=$_POST["cp_no"]?>')
			});
		}
	}
	/*
	function saveNote(){
		var new_myhead = 0;
		if($('#select-note-remark').val()=='0'){
			if($('#myhead_name').val()!=''){
				new_myhead = 1;
				//alert($('#myhead_name').val());
			}else{
				new_myhead = 0;
				$('#select-note-remark').val(0);
				//alert($('#myhead_name'+'else').val());
			}
		}else if($('#select-note-remark').val()=='select-myhead'){
			$('#select-note-remark').val(0);
		}
		$.post('app/manage.php',{
			event : $('#note_event').val(),
			note_mh : $('#select-note-remark').val(),
			note_mh_new : $('#myhead_name').val(),
			note_remark : $('#note_remark').val(),
			note_step : $('#note_step_hidden').val(),
			note_step_1 : $('#note_step_1_hidden').val(),
			note_step_2 : $('#note_step_2_hidden').val(),
			note_cp : $('#note_cp').val(),
			note_csd : $('#note_csd').val(),
			note_id : $('#note_id').val(),
			new_myhead : new_myhead
		},function(data){
			$('#modal-note').modal('hide');
			alert(data);
			showData();
		});
	}
	*/
	function change_csd_step(){
		
		if($('#csd_step_fst').val()>0){
			$('#div_csd_step_fst_date').show();
			if($('#csd_step_snd').val()>0){
				$('#div_csd_step_snd_date').show();
				if($('#csd_step_trd').val()>0){
					$('#div_csd_step_trd_date').show();					
				}else{
					$('#div_csd_step_trd_date').hide();
				}
			}else{
				$('#div_csd_step_snd_date').hide();
				$('#div_csd_step_trd_date').hide();
			}
		}else{
			$('#div_csd_step_fst_date').hide();
			$('#csd_step_fst_start').val('');
			$('#csd_step_fst_stop').val('');
			 
			
			$('#div_csd_step_snd_date').hide();
			$('#csd_step_snd_start').val('');
			$('#csd_step_snd_stop').val('');
			
			
			$('#div_csd_step_trd_date').hide();
			$('#csd_step_trd_start').val('');
			$('#csd_step_trd_stop').val('');
		}
	
		$('#csd_start').val('');
		$('#csd_stop').val('');
	}
	
	
	function change_csd_step_fst(){		
		if($('#csd_step_fst').val()==0){
			$('#div_csd_step_fst_date').hide();
		}else{
			$('#div_csd_step_fst_date').show();
		}
		$('#csd_step_fst_start').val('');
		$('#csd_step_fst_stop').val('');
		$('#csd_step_snd').val(0);
		change_csd_step_snd();	
	}
	
	function change_csd_step_snd(){		
		if($('#csd_step_snd').val()==0){
			$('#div_csd_step_snd_date').hide();
		}else{
			$('#div_csd_step_snd_date').show();
		}
		$('#csd_step_snd_start').val('');
		$('#csd_step_snd_stop').val('');
		$('#csd_step_trd').val(0);
		change_csd_step_trd();	
	}
	
	function change_csd_step_trd(){		
		if($('#csd_step_trd').val()==0){
			$('#div_csd_step_trd_date').hide();
		}else{
			$('#div_csd_step_trd_date').show();
		}
		$('#csd_step_trd_start').val('');
		$('#csd_step_trd_stop').val('');
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
	
	function changeCsdFstDate(){
		$.post('app/manage.php',{
			event : 'fst_date',
			cp_step : $('#csd_step_fst').val(),
			cp_step_start : $('#csd_step_fst_start').val(),
			cp_petition : $('#csd_petition').val()
		},function(data){
			$('#csd_step_fst_stop').val(data);
		});
	}
	
	
	function changeCsdSndDate(){
		$.post('app/manage.php',{
			event : 'snd_date',
			cp_step : $('#csd_step_fst').val(),
			cp_step_1 : $('#csd_step_snd').val(),
			cp_step_1_start : $('#csd_step_snd_start').val(),
			cp_petition : $('#csd_petition').val()
		},function(data){
			$('#csd_step_snd_stop').val(data);
		});
	}
	function changeCsdTrdDate(){
		$.post('app/manage.php',{
			event : 'trd_date',
			cp_step : $('#csd_step_fst').val(),
			cp_step_1 : $('#csd_step_snd').val(),
			cp_step_2 : $('#csd_step_trd').val(),
			cp_step_2_start : $('#csd_step_trd_start').val(),
			cp_petition : $('#csd_petition').val()
		},function(data){
			$('#csd_step_trd_stop').val(data);
		});
	}
	
	
	
	
	
	
	function addCsd(){
		$('#event').val('c_csd');
		$('#modal-csd').modal('show');
		$('.modal-title').html('เพิ่มขั้นตอน');
		$('#div_csd_petition').hide();
		$('#div_csd_date_plan').hide();
		$('#div_csd_date').hide();
		$('#csd_petition').val(0);
		$('#csd_start_plan').val('');
		$('#csd_stop_plan').val('');
		$('#csd_start').val('');
		$('#csd_stop').val('');	
		
		$('#csd_start_2').val('');
		$('#csd_stop_2').val('');
		
		$('#csd_step_fst').val(0);		
		fst_step_change();
		$('#div_csd_step_fst_date').hide();
		$('#csd_step_fst_start').val('');
		$('#csd_step_fst_stop').val('');
		
		$('#div_csd_step_snd_date').hide();
		$('#csd_step_snd_start').val('');
		$('#csd_step_snd_stop').val('');
		
		$('#div_csd_step_trd_date').hide();
		$('#csd_step_trd_start').val('');
		$('#csd_step_trd_stop').val('');
		
		
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
			csd_plan_fst_start : $('#csd_step_fst_start').val(),
			csd_plan_fst_stop  : $('#csd_step_fst_stop').val(),
			csd_plan_snd_start : $('#csd_step_snd_start').val(),
			csd_plan_snd_stop  : $('#csd_step_snd_stop').val(),
			csd_plan_trd_start : $('#csd_step_trd_start').val(),
			csd_plan_trd_stop  : $('#csd_step_trd_stop').val(),
			csd_start : $('#csd_start').val(),
			csd_stop  : $('#csd_stop').val(),
			csd_start_2 : $('#csd_start_2').val(),
			csd_stop_2  : $('#csd_stop_2').val(),
			event : $('#event').val()
		},function(data){
			$('#modal-csd').modal('hide');
			alert(data);
			//console.log(data);
			openStep('<?=$_POST["cp_no"]?>');
		});
	}
	
	function delCsd(x){
		if(confirm("คุณต้องการลบขั้นตอนนี้ใช่หรือไม่")){
			$.post('app/manage.php',{
				event : 'd_csd',
				csd_seq : x 			
			},function(data){
				alert(data);
				showData();
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
			var csd_start_2 = data.csd_start_2;
			var csd_stop_2 = data.csd_stop_2;
			
			var csd_plan_start = data.csd_plan_start;
			var csd_plan_stop = data.csd_plan_stop;
			
			var csd_plan_fst_start = data.csd_plan_fst_start;
			var csd_plan_fst_stop = data.csd_plan_fst_stop;
			var csd_plan_snd_start = data.csd_plan_snd_start;
			var csd_plan_snd_stop = data.csd_plan_snd_stop;
			var csd_plan_trd_start = data.csd_plan_trd_start;
			var csd_plan_trd_stop = data.csd_plan_trd_stop;
			var csd_petition = data.csd_petition;					
			
			//alert(csd_step_snd);
			$('#modal-csd').modal('show');
			$('.modal-title').html('แก้ไขขั้นตอน');
			$('#csd_step_fst').val(csd_step_fst);			
			re_fst_step_change(csd_step_snd,csd_step_trd);
			$('#csd_step_trd').val(csd_step_trd);
			//snd_step_change();
			//alert(csd_step_trd);
			
			if(csd_step_fst > 0){
				$('#div_csd_step_fst').show();
				$('#div_csd_step_fst_date').show();
			}else{				
				$('#div_csd_step_fst_date').hide();
			}
			if(csd_step_snd > 0){
				$('#div_csd_step_snd').show();
				$('#div_csd_step_snd_date').show();
			}else{
				$('#div_csd_step_sndtrd').hide();
				$('#div_csd_step_snd_date').hide();
			}
			if(csd_step_trd > 0){
				$('#div_csd_step_trd').show();
				$('#div_csd_step_trd_date').show();
			}else{
				$('#div_csd_step_trd').hide();
				$('#div_csd_step_trd_date').hide();
			}
			
			$('#csd_step_fst_start').val(csd_plan_fst_start);
			$('#csd_step_fst_stop').val(csd_plan_fst_stop);
			$('#csd_step_snd_start').val(csd_plan_snd_start);
			$('#csd_step_snd_stop').val(csd_plan_snd_stop);
			$('#csd_step_trd_start').val(csd_plan_trd_start);
			$('#csd_step_trd_stop').val(csd_plan_trd_stop);
			
			$('#csd_start').val(csd_start);
			$('#csd_stop').val(csd_stop);			
			$('#csd_start_2').val(data.csd_start_2);
			$('#csd_stop_2').val(csd_stop_2);
			
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
		});
		
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