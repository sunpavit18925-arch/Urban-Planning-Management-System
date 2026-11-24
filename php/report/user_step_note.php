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
					<th style="text-align:center">หมายเหตุ</th>
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
										echo "<span title='ไม่มีหมายเหตุ'> - </span>";
									}
									else{
										?> <span title="แสดงหมายเหตุ" class="list-alt"><a onclick="Readnote(<?php echo $row['step_id'];?>,<?php echo $row['sub_no'];?>,<?php echo $_POST['cp_no'];?>);"><i class="fa fa-list-alt"></i></a></span><?php 
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
						<div class="form-group" id="show_note_remark">
						</div>				
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
				</div>
            </div>
        </div>
    </div>
	
	
	<script>
	function BackSSR(){
		$('#mainshow').load('report/show_result.php');
	}	
	
	function Readnote(x,y,z){
		//alert('step : '+x+', sub : '+y+'cp_no : '+z);
		$.post('app/manage.php',{
			event : 'ru_stepnotex',
			step_id :  x,
			sub_no : y,
			cp_no : z
		},function(data){
			//console.log(data);
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();			
			$('.trash').hide();
			$('.gear').hide();
			$('label').hide();
			$('#modal_title').html("หมายเหตุ");
			$('#modal-note').modal('show');
		});
	}
	</script>