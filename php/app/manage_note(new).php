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
					<th style="text-align:center">จำนวนหมายเหตุ</th>
					<th style="text-align:center">จัดการ</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$sql ="SELECT step_id , sub_no , step_name , sub_name FROM ref_up_fst_step,ref_up_snd_step WHERE step_id = sub_step ORDER BY step_id, sub_no ASC";
					
					$result = selectSql($sql);
					foreach($result as $row){ ?>
						<tr style="<?php if($row['sub_no']==0){echo 'background-color:#BBEFFF ;';}?>">
							<td style="text-align:left"><?php if($row['sub_no']!=0){echo "<b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - ขั้นตอนรองที่ ".$row['step_id'].".".$row['sub_no']."</b> ".$row['sub_name'];}elseif($row['step_id']!=0){ echo  "<b>ขั้นตอนหลักที่ ".$row['step_id'].".".$row['sub_no']."</b> ".$row['step_name'];}else{ echo  "<b>ยังไม่ได้กำหนดขั้นตอน</b> ";}?></td>
							<td style="text-align:center">
								<?php 	
									$sql = "SELECT note_remark FROM tb_note WHERE note_step = '".$row['step_id']."' AND note_step1 = '".$row['sub_no']."' GROUP BY note_remark";									
									$result = selectSql($sql);
									$my_note = count($result);
									echo count($result);
									//echo $sql;
								?>
							</td>
							<td style="text-align:center">	
								<?php 
									if($my_note == 0){
										?> <span class="plus"><a onclick="Addnote(<?php echo $row['step_id'];?>,<?php echo $row['sub_no'];?>);"><i class="fa fa-plus"></i></a></span> <?php
									}
									else{
										?> <span class="plus"><a onclick="Addnote(<?php echo $row['step_id'];?>,<?php echo $row['sub_no'];?>);"><i class="fa fa-plus"></i></a></span> <?php
										?> <span class="list-alt"><a onclick="Readnote(<?php echo $row['step_id'];?>,<?php echo $row['sub_no'];?>);"><i class="fa fa-list-alt"></i></a></span><?php 
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
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่ม/แก้ไขหมายเหตุ</h4>
              </div>
              <div class="modal-body">
				<form>	
				<div class="form-group" id="note_step_show">
					<span id="note_step">step ......</span>	
				</div>
				
				<div class="form-group" id="note_step_1_show">
					<span id="note_step_1">step_1......</span>	
				</div>
				
				
				<div id="mainnote_show">
				</div>
				<!--<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>-->
				
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
                <button type="button" class="btn btn-success pull-right" onclick="Savenote();">บันทึก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="Delnote();">ลบหมายเหตุ</button>
              </div>
            </div>

          </div>

        </div>
	<script>
	function BackSSR(){
		$('#mainshow').load('app/t2.php');
	}	
	function Addnote(x,y){	
		//alert('x : '+x+' , y : '+y);
		$.post('app/manage.php',{
			event :'ru-c_mainnote',
			step_id : x,
			sub_no : y
		},function(data){
			$('#delBtn').hide();
			$('#note_step_hidden').val(x);
			$('#note_step_1_hidden').val(y);
			$('#note_cp').val("0");
			$('#note_event').val('c_mainnote');
			$('#modal-note').modal('show');
			$('#mainnote_show').html(data);
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
				//$('#mainshow').load('report/table_step_note.php');
				console.log(data);
			});
		}
	}
	
	/*function Savenote(){
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
	}*/
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
	
	function Readnote(x,y,z){
		$.post('app/manage.php',{
			event : 'ru_mainnote',
			step_id : x,
			sub_no : y
		},function(data){
			$('#delBtn').hide();
			/*$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_remark').val(data.note_remark);
			$('#note_cp').val(data.note_cp);
			$('#note_event').val('u_note+u_cp');
			$('#note_id').val(data.note_id);*/
			//alert(data);
			//console.log(data);
			$('#modal-note').modal('show');
			$('#mainnote_show').html(data);
		});
	}
	
	/*function Readstepnote(x){
		$.post('report/table_step_note.php',{
			cp_no : x
		},function(data){
			//console.log(data);
			$('#mainshow').html(data);
		});
	}*/
	function Delnote(x){
		if(confirm("คุณต้องการลบหมายเหตุนี้ใช่หรือไม่")){
			$.post("app/manage.php",{
				event : 'rd_note',
				note_id : x
			},function(data){
				if(confirm(data)){
					alert("true");
				}
			});
			
		}
	}
	/*function Delnote(){
		if(confirm("คุณต้องการลบหมายเหตุใช่หรือไม่ ? ")){
			$.post('app/manage.php',{
				event : 'd_stepnote',
				note_id : $('#note_id').val(),
				cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				$('#mainshow').load('report/table_step_note.php');
			});
		}
	}*/
	</script>