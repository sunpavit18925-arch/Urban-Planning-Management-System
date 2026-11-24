<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	//$sql = "SELECT COUNT(cp_no) FROM tb_cityplan";
	//$result = selectSql($sql);
	?>
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการ</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการ</h4>
	</div>	
	<br>
	<div class="container-fluid">
	<button id="mainstep" onclick="switchtable(1);" class="pull-right btn btn-success btn-md">แสดงขั้นตอนหลัก</button>
	<button id="substep" onclick="switchtable(2);" class="pull-right btn btn-warning btn-md">แสดงขั้นตอนรอง</button>
	<br>&nbsp;
	<!--<button class="btn btn-warning btn-md">แสดงขั้นตอนรองและขั้นย่อย</button>-->
	
		<div class="table-responsive" style="border-color:black">
			<table id="main_step" class="table table-bordered" >
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center">ขั้นตอน</td>
						<td style="text-align:center" colspan="2">ระยะเวลา<br>(สัปดาห์)</td>
						<td style="text-align:center">วันที่เริ่ม</td>
						<td style="text-align:center">วันที่สิ้นสุด</td>
						<td style="text-align:center">หมายเหตุ</td>
					</tr>
				</thead>
				<tbody style="background-color:white">
					<?php 
						$sql ="SELECT * FROM ref_up_fst_step , ref_note WHERE step_id > 0 AND step_note = note_id";
						$result = selectSql($sql);
						foreach($result as $row){ ?>
						<tr>
						<td style="text-align:left"><a onclick="substep(<?php echo $row['step_id'];?>)"><b>[<?php echo $row['step_id']?>]</b> </a><?php echo $row['step_name'];?></td>
						<?php 
							if($row['step_time']==0){
								echo '<td style="text-align:center">',$row['step_time1'],'</td>';
								echo '<td style="text-align:center">',$row['step_time2'],'</td>';
							}else{
								echo '<td style="text-align:center" colspan="2">',$row['step_time'],'</td>';							
							}
						?>
						<td style="text-align:center"><?php if($row['step_start']==NULL){ echo "-";}else{echo $row['step_start'];}?></td>
							<td style="text-align:center"><?php if($row['step_start']==NULL){ echo "-";}else{echo $row['step_start'];}?></td>
							<td style="text-align:center">
								<?php 									
									if($row['step_note'] == NULL){
										echo '-';
									}
									else{
										$note_array = explode(",",$row['step_note']);								
										$count_array = count($note_array);
																		
										$note = "";
										for($i=0 ; $i<$count_array;$i++){
											$sql = "SELECT * FROM ref_note WHERE note_id = '$note_array[$i]'";
											$note_result =  selectSql($sql);
											//if($i>0){ echo '<br>';}
											//echo $note_result[0]['note_name'];
											if($i==0){
												$note = $note_result[0]['note_name'];
											}else{
												$note = $note." <br>".$note_result[0]['note_name'];
												}
										}	
										echo $note;
									}
								?>
							</td>
						</tr>
					<?php 
						/*$sql ="SELECT * FROM ref_up_snd_step,ref_up_fst_step,ref_note WHERE sub_step = step_id AND sub_step = '".$row['step_id']."' AND sub_note = note_id";
						//echo $sql,'<br>';
						$substep = selectSql($sql);
						foreach($substep as $subrow){
							echo '<tr>';
							echo '<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ',$subrow['sub_no'],' ',$subrow['sub_name'],'</td>';
							if($subrow['sub_time']==0){
								echo '<td style="text-align:center">';
								if($subrow['sub_time1']==0){echo'-';}else{echo $subrow['sub_time1'];}
								echo '</td>';
								
								echo '<td style="text-align:center">';
								if($subrow['sub_time2']==0){echo'-';}else{echo $subrow['sub_time2'];}
								echo '</td>';								
							}else{
							echo '<td style="text-align:center" colspan="2">',$subrow['sub_time'],'</td>';
							}
							echo '<td style="color:red;text-align:center">-</td>';
							echo '<td style="color:red;text-align:center">-</td>';	
							echo '<td style="text-align:center">',$subrow['note_name'],'</td>';;
							echo '</tr>';
						}*/	
					?>  
					<?php } ?>
				</tbody>	
			</table>
			
			<table id="mainsub_step" class="table table-bordered" >
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center">ขั้นตอน</td>
						<td style="text-align:center" colspan="2">ระยะเวลา<br>(สัปดาห์)</td>
						<td style="text-align:center">วันที่เริ่ม</td>
						<td style="text-align:center">วันที่สิ้นสุด</td>
						<td style="text-align:center">หมายเหตุ</td>
					</tr>
				</thead>
				<tbody style="background-color:white"> <?php 
					$sql ="SELECT * FROM ref_up_fst_step , ref_note WHERE step_id > 0 AND step_note = note_id";
					$result = selectSql($sql);
					foreach($result as $row){ ?>
						<tr style="background-color:#AAE0FF">
							<td style="text-align:left"><a onclick="substep(<?php echo $row['step_id'];?>)"><b>[<?php echo $row['step_id']?>]</b> </a><?php echo $row['step_name'];?></td>
							<?php 
								if($row['step_time']==0){
									echo '<td style="text-align:center">',$row['step_time1'],'</td>';
									echo '<td style="text-align:center">',$row['step_time2'],'</td>';
								}else{
									echo '<td style="text-align:center" colspan="2">',$row['step_time'],'</td>';							
								}
							?>
							<td style="text-align:center"><?php if($row['step_start']==NULL){ echo "-";}else{echo $row['step_start'];}?></td>
							<td style="text-align:center"><?php if($row['step_start']==NULL){ echo "-";}else{echo $row['step_start'];}?></td>
							<td style="text-align:center"><?php echo $row['note_name'];?></td>
						</tr>
					<?php 
						$sql ="SELECT * FROM ref_up_snd_step,ref_up_fst_step,ref_note WHERE sub_step = step_id AND sub_step = '".$row['step_id']."' AND sub_note = note_id";
						//echo $sql,'<br>';
						$substep = selectSql($sql);
						foreach($substep as $subrow){ ?>
							<tr>
								<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?php echo $subrow['sub_no'],' ',$subrow['sub_name']?></td>
								<?php if($subrow['sub_time']==0){ ?>
								<td style="text-align:center"> <?php 
									if($subrow['sub_time1']==0){echo'-';}
									else{echo $subrow['sub_time1'];} ?> 
								</td>
								<td style="text-align:center"><?php 
									if($subrow['sub_time2']==0){echo'-';}
									else{echo $subrow['sub_time2'];}?>
								</td> <?php 
								}else{ ?>
								<td style="text-align:center" colspan="2"><?php echo $subrow['sub_time']; ?></td>
								<?php } ?>
								<td style="text-align:center"><?php if($subrow['sub_start']==''){echo '-';}else{echo $subrow['sub_start'];}?></td>
								<td style="text-align:center"><?php if($subrow['sub_end']==''){echo '-';}else{echo $subrow['sub_end'];}?></td>
								<td style="text-align:center"> <?php echo $subrow['note_name'];?></td>
							</tr>
						<?php }	
					} ?>
				</tbody>	
			</table>
		</div>
	</div>
	<script>
		switchtable(1);
		function switchtable(x){
			if(x==1){
				$('#main_step').show();
				$('#mainsub_step').hide();
				$('#mainstep').hide();
				$('#substep').show();
			}
			else if(x==2){
				$('#main_step').hide();
				$('#mainsub_step').show();
				$('#mainstep').show();
				$('#substep').hide();
			}
			else{
				alert('Error!');
				$('#main_step').hide();
				$('#mainsub_step').hide();
				goto(0);
			}
		}
		
		function substep(x){
			$.post('app/substep.php',{
			x : x		
			},function(data) {
			  //alert(data);	  
			  $('#mainshow').html(data);
			});
		}
		
	
	
	</script>