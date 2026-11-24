<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();

	$sql ="SELECT * FROM ref_up_fst_step WHERE step_id = '".$_POST['step_id']."'";
	$result = selectSql($sql);
	
	//echo $sql;
?>
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center"><?php echo $result[0]['step_name'];?></h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center"><?php echo $result[0]['step_name'];?></h4>
	</div>	
	<br>
	<div class="container-fluid">
	<button id="btn_back" onclick="goBack();" class="pull-left btn btn-danger btn-md">ย้อนกลับ</button>
	<button id="btn_edit" onclick="edit();" class="pull-right btn btn-info btn-md">แก้ไข</button>
	<button id="btn_note_save" onclick="save();" style="margin-left:6px" class="pull-right btn btn-success btn-md">บันทึก</button>
	<button id="btn_note_cancel" onclick="closeedit();" class="pull-right btn btn-warning btn-md">ยกเลิก</button>
	<br>&nbsp;	
		<div class="table-responsive" style="border-color:black">
			<table id="main_step" class="table table-bordered" >
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center">#</td>						
						<td style="text-align:center">หมายเหตุ</td>
						<td style="text-align:center" class="manage">จัดการ</td>
					</tr>
				</thead>
				<tbody style="background-color:white"><?php 
					$note = explode(",",$result[0]['step_note']);
					$count_note = count($note);
					$i = 0;
					for($x=0 ; $x<$count_note ; $x++){
						$sql = "SELECT * FROM ref_note WHERE note_id = '$note[$x]'";
						$result = selectSql($sql);
						foreach($result as $row){ $i+=1; ?>
					<tr>
						<td style="text-align:center"><?php echo $i;?></td>
						<td style="text-align:left"><?php echo $row['note_name'];?></td>
						<td style="text-align:center" class="manage">
							<span class="trash">
								<a id="trash">
									<i class="fa fa-trash"></i>
								</a>
							<span>						
						</td>
					</tr>
					<?php }} ?>
				</tbody>
			</table>
		</div>
		
		<div id="note_add" class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td style="text-align:center">#</td>
						<td style="text-align:left">หมายเหตุ</td>
						<td style="text-align:cneter">จัดการ</td>
					</tr>
				</thead>
				<tbody style="background-color:white">
				</tbody>
			</table>
		</div>
	</div>
	
	<script>
	closeedit();
		function goBack(){
			$('#mainshow').load('app/manage_step.php');
		}
		function edit(){
			$('#btn_edit').hide();
			$('.manage').show();
			$('#btn_note_save').show();
			$('#btn_note_cancel').show();
		}
		function closeedit(){
			$('#btn_edit').show();
			$('.manage').hide();
			$('#btn_note_save').hide();
			$('#btn_note_cancel').hide();
		}
	
	
	</script>