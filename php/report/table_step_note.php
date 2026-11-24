<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
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
	$name = $result[0]['cp_name'];
	$step = $result[0]['step_name'];
	$pvn = $result[0]['pvn_name'];
	$current = $result[0]['step_id'].".".$result[0]['sub_no']." ".$result[0]['sub_name'];
	$status = $result[0]['status_name'];
 ?>
 <div class="col-12">
		<h2 class="hidden-sm hidden-xs GHfont" style="font-family: Kanit;text-align:center">
			<?php echo $title;?>
		</h2>
		<h3 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center"><?php echo $title;	?></h3>
	</div>	
	<br>
	<div class="container-fluid">
		<div class="row">
			<div id="div-btn" class="col-xs-12 col-md-12">
				<button id="btn_back" onclick="BackSSR();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-name_cp" class="col-xs-12 col-md-7" style="margin-top:8px;font-size:17px">
				<span><?php echo '<b>ชื่อผังเมือง : </b>'.$name; ?></span>
			</div>
			<div id="div-province_cp" class="col-xs-6 col-md-5" style="margin-top:8px;font-size:17px">
				<span class="pull-right"><?php echo '<b>จังหวัด : </b>'.$pvn; ?></span>
			</div>
			<div class="hidden-xs" hidden id="div-province_cp" class="col-xs-6 col-md-4" style="margin-top:8px;font-size:17px">
				<span class="pull-right"><?php echo '<b>สถานะผัง : </b>'.$status; ?></span>
			</div>
			<!--<div id="div-step_cp" class="col-xs-12 col-md-12" style="margin-top:8px;font-size:18px">
				<span class="pull-left"><?php //echo "<b>ขั้นตอนปัจจุบัน : </b>".$current; ?></span>
			</div>-->
		</div>
		<div id="noteshow" class="table-responsive" style="border-color:transparent;margin-top:6px">
			<!-- <img src="img/loading3.gif" class="center">-->
			
			<table class="table table-bordered">
			<thead style="background-color:">
				<tr style="background-color: #0AAAFA;color:white">
					<th style="text-align:center">ขั้นตอน</th>
					<th style="text-align:center">จัดการหมายเหตุ</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if($result[0]['cp_step']!=0){
						$sql ="SELECT * FROM ref_up_fst_step,ref_up_snd_step WHERE step_id = sub_step AND step_id !=0 ORDER BY step_id, sub_no ASC";
					}else{ 
						$sql ="SELECT * FROM ref_up_fst_step,ref_up_snd_step WHERE step_id = sub_step AND step_id = 0 ORDER BY step_id, sub_no ASC";
					}
					$result = selectSql($sql);
					foreach($result as $row){ ?>
						<tr style="<?php if($row['sub_no']==0){echo 'background-color:#BBEFFF ;';}?>">
							<td style="text-align:left">
								<?php 	if($row['step_id']!=0 AND $row['sub_no']==0){
											echo "<b>ขั้นตอนหลักที่ ".$row['step_id'].".".$row['sub_no']."</b> ".$row['step_name'];
										}
										elseif($row['step_id']!=0 AND $row['sub_no']!=0){
											echo "<b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - ขั้นตอนรองที่ ".$row['step_id'].".".$row['sub_no']."</b> ".$row['sub_name'];
										}										
										else{ 
											echo  "<b>ขั้นตอนที่  0 </b> ไม่มีการดำเนินการ";
										}
								?>
							</td>
							<td style="text-align:center">
								<?php 	
									$sql = "SELECT * FROM tb_note WHERE note_cp = '".$_POST['cp_no']."' AND note_step = '".$row['step_id']."' AND note_step1 = '".$row['sub_no']."' AND note_status != 0";
									
									$result = selectSql($sql);
									//echo $result[0]['note_id'];
									//echo $sql;
									
									if($result[0]['note_id']== "" OR $result[0]['note_id']==Null){
										?> <span class="plus"><a onclick="Addnote(<?php echo $row['step_id'];?>,<?php echo $row['sub_no'];?>,<?php echo $_POST['cp_no'];?>);"><i class="fa fa-plus"></i></a></span> <?php
									}
									else{
										?> <span title="เพิ่มหมายเหตุ" class="plus"><a onclick="Addnote(<?php echo $row['step_id'];?>,<?php echo $row['sub_no'];?>,<?php echo $_POST['cp_no'];?>);"><i class="fa fa-plus"></i></a></span> <?php
										?> <span title="แสดงหมายเหตุ" class="list-alt"><a onclick="Readnote(<?php echo $row['step_id'];?>,<?php echo $row['sub_no'];?>,<?php echo $_POST['cp_no'];?>);"><i class="fa fa-list-alt" style="padding-left:8px"></i></a></span><?php 
									} 
								?>
							</td>
						</tr>
					<?php } ?>
				
				
			</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="modal-note">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
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
				
				<div class="form-group" id="show_note_remark"></div>
				<div class="form-group" id="edit_note_remark">.</div>
				<?php
				/*<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>*/
				?>
				<input type="hidden" id="note_id" value="">
				<input type="hidden" id="note_cp" value="">
				<input type="hidden" name="note_id" class="form-control" id="note_id" value="" >
                <input type="hidden" id="note_uper" value="<?php echo $_SESSION['intra_czid']; ?>">				
                <input type="hidden" id="note_event" value="">
				<input type="hidden" id="note_step_hidden" value="">
				<input type="hidden" id="note_step_1_hidden" value="">
				</form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button id="editBtn" type="button" class="btn btn-success pull-right" onclick="Savenote();">บันทึก</button>
				<button id="cancleBtn" type="button" class="btn btn-warning pull-right" onclick="Canclenote();">ยกเลิก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="Delnote();">ลบหมายเหตุ</button>
              </div>
            </div>

          </div>

    </div>
	<script>
	function BackSSR(){
		$('#mainshow').load('report/show_result.php');
	}	
	function Addnote(x,y,z){	
		$.post('app/manage.php',{
			event :'ru-c_stepnote',
			step_id : x,
			sub_no : y
		},function(data){
			$('#delBtn').hide();
			$('#cancleBtn').hide();
			$('#editBtn').show();
			$('#note_step_hidden').val(x);
			$('#note_step_1_hidden').val(y);
			$('#note_cp').val(z);
			$('#modal-note').modal('show');
			$('#note_event').val('c_note+u_cp');
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();
			$('#edit_note_remark').html('');
			$('#edit_note_remark').show();
			$('#modal_title').html("เพิ่มหมายเหตุ");
			$('#note_remark').val("");
			$('#note_id').val("");
		});	
	}
	
	
	function Savenote(){
		if($('#note_remark').val()=="" || $('#note_remark').val()==null){
			alert("กรุณาระบุหมายเหตุ");
		}else{
			$.post('app/manage.php',{
				event : $('#note_event').val(),
				note_remark : $('#note_remark').val(),
				note_step : $('#note_step_hidden').val(),
				note_step_1 : $('#note_step_1_hidden').val(),
				note_cp : $('#note_cp').val(),
				note_id : $('#note_id').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				$('#mainshow').load('report/table_step_note.php');
				console.log(data);
			});
		}
	}
	
	function Readnote(x,y,z){
		//alert('step : '+x+', sub : '+y+'cp_no : '+z);
		$.post('app/manage.php',{
			event : 'ru_stepnotex',
			step_id :  x,
			sub_no : y,
			cp_no : z
		},function(data){
			$('#delBtn').hide();
			$('#editBtn').hide();
			$('#cancleBtn').hide();
			//console.log(data);
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();
			$('#edit_note_remark').hide();
			$('#note_event').val('u_note+u_cp');
			$('#modal_title').html("แก้ไข/ลบหมายเหตุ");
			$('#modal-note').modal('show');
		});
	}
	function Editnote(x,y,z){
		var a = $('#div_note_'+x).html();
		$('#show_note_remark').hide();
		$.post('app/manage.php',{
			event : 'ru_selectnote',
			note_id : y,
			cp_no : z
		},function(data){
			$('#edit_note_remark').show();
			$('#edit_note_remark').html(data);
			$('#note_remark').val(a);
			$('#note_id').val(y);
			$('#note_cp').val(z);
			$('#editBtn').show();
			$('#cancleBtn').show();
			$('#note_event').val('u_note+u_cp');
		});
		/*$('.input_note').hide();
		$('.div_note').show();
		$('#div_note_'+x).hide();
		var a = $('#div_note_'+x).html();
		$('#note_'+x).show();
		$('#note_'+x).val(a);
		$('#editBtn').show();
		$('#cancleBtn').show();*/
		//alert(a);
	}
	
	function Canclenote(){
		$('.input_note').hide();
		$('.div_note').show();
		$('#editBtn').hide();
		$('#cancleBtn').hide();
		$('#show_note_remark').show();
		$('#edit_note_remark').hide();
	}
	/*
	function Readnote(x){
		$.post('app/manage.php',{
			event : 'ru_note2',
			cp_no : x
		},function(data){
			$('#delBtn').show();
			$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_remark').val(data.note_remark);
			$('#note_cp').val(data.cp_no);
			$('#note_event').val('u_note+u_cp');
			$('#note_id').val(data.note_id);
			//alert(data);
			//console.log(data);
			$('#modal-note').modal('show');
		},'json');
	}
	*/
	/* //Origin read edit note// 
	function Readnote(x){
		$.post('app/manage.php',{
			event : 'ru_stepnotex',
			note_id : x
		},function(data){
			$('#delBtn').show();
			$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_remark').val(data.note_remark);
			$('#note_cp').val(data.note_cp);
			$('#note_event').val('u_note+u_cp');
			$('#note_id').val(data.note_id);
			//alert(data);
			//console.log(data);
			$('#modal-note').modal('show');
		},'json');
	}
	*/

	
	function Delnote(x,y){
		if(confirm("คุณต้องการลบหมายเหตุใช่หรือไม่ ? ")){
			$.post('app/manage.php',{
				event : 'd_stepnote',
				note_id : x,
				cp_no : y
				//cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				$('#mainshow').load('report/table_step_note.php');
			});
		}
	}
	</script>