<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
?>	
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการรอง</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการรอง</h4>
	</div>	
	<br>
	<div class="container-fluid">
	<div class="row">
		<div class="col-xs-3 col-md-6">
			<button onclick="goto('a3');" class="pull-left btn btn-danger btn-md">ย้อนกลับ</button>
		</div>
		<div class="col-xs-9 col-md-6">
			<select id="select-main" onchange="changesub();" class="pull-right" style="height:34px;width:90%;border-radius:4px">
				<?php 
				$sql ="SELECT * FROM ref_up_fst_step WHERE step_id > 0";
				$result = selectSql($sql);
				foreach($result as $row){ ?>
					<option value="<?php echo $row['step_id'];?>"><?php echo $row['step_id'],'. ',$row['step_name'];?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="table-responsive" style="border-color:black;margin-top:6px">
		<table class="table table-bordered">
			<thead>
				<tr style="background-color: #0AAAFA;color:white">
					<td style="text-align:center">ขั้นตอน</td>
					<td style="text-align:center" colspan="2">ระยะเวลา <br>(สัปดาห์)</td>
					<td style="text-align:center">วันที่เริ่มต้น</td>
					<td style="text-align:center">วันที่สิ้นสุด</td>
					<td style="text-align:center">หมายเหตุ</td>
					
				</tr>
			</thead>
			<tbody>
		<?php 
			$sql ="SELECT * FROM ref_up_snd_step,ref_up_fst_step,ref_note WHERE sub_step = step_id AND sub_step = '".$_POST['x']."' AND sub_note = note_id";
			//echo $sql;
			$result = selectSql($sql);
			foreach ($result as $row){ ?>
				<tr>
					<td style="text-align:left"><?php echo $row['sub_no'],' ',$row['sub_name'];?></td>
					<?php if($row['sub_time']==0){ ?>
							<td style="text-align:center"><?php	echo $row['sub_time1'];?></td>
							<td style="text-align:center"><?php	echo $row['sub_time2'];?></td>							
					<?php }else{ ?>
							<td style="text-align:center" colspan="2"><?php	echo $row['sub_time'];?></td>
					<?php } ?>
					<td style="text-align:center">
						<?php if($row['sub_start']==''){echo '-';}else{echo $row['sub_start'];}?>
					</td>
					<td style="text-align:center">
						<?php if($row['sub_end']==''){echo '-';}else{echo $row['sub_end'];}?>
					</td>
					<td style="text-align:center"><?php echo $row['note_id'];?></td>
					
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	
	<script>
		$('#select-main').val('<?php echo $_POST["x"];?>');
		
		function changesub(){
			var x = $('#select-main').val();
			$.post('app/substep.php',{
				x : x
			},function(data){
				$('#mainshow').html(data);
			});
		}
		
		
	
	
	</script>