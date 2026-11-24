<?php 
include '../js/function_db.php';
$select = " * ";
$from = " tb_cityplan , ref_cityplan_step_detail , tref_rp_cs_operate";
$join = " cp_csd = csd_seq AND cp_current_status = rp_cs_op_id";
if($_POST['event']==1){
	$cond = "cp_current_status = 1.1 ";
}elseif($_POST['event']==2){
	$cond = "cp_current_status = 1.2 ";
}elseif($_POST['event']==3){
	$cond = "cp_current_status = 2 ";
}elseif($_POST['event']==4){
	$cond = "cp_current_status = 3 ";
}elseif($_POST['event']==5){
	$cond = "cp_status = 2 AND cp_stage = 3 AND cp_effective = 2 ";
}elseif($_POST['event']==6){
	$cond = "cp_status = 2 AND cp_stage = 3 AND cp_effective = 1 ";
}elseif($_POST['event']==7){
	$cond = "cp_status = 1 ";
}
$orde = ($_POST['event']<5)? " ORDER BY cp_status ASC , cp_stage DESC , cp_effective ASC " : " ORDER BY rp_cs_op_no ASC ";
$td = ($_POST['event']<5)? "สถานะผัง":"การดำเนินงาน";

$sql = "SELECT ".$select." FROM ".$from." WHERE ".$cond." AND ".$join.$orde;
//echo $sql;
$result = selectSql($sql);
//echo count($result);
$all_data = count($result);

if($all_data>0){ ?>
	<style>
		table > thead > tr > th{
			color:white;
			text-align:center;
		}
	</style>
	<?php 
		$row = 20;
		$i = 0;
		for($t=1;$t<=ceil($all_data/$row);$t++){
	?>
	<table id="table_<?=$t?>" class="table table-bordered">
		<thead>
			<tr>
				<th>ชื่อผังเมือง</th>
				<th><?=$td?></th>
				<th>ขั้นตอนหลัก</th>
				<th>ขั้นตอนย่อย 1</th>
				<th>ขั้นตอนย่อย 2</th>
				<th>สถานะ</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				for($n=0;$n<$row;$i++,$n++){
					if($i==$all_data){break;}
					$step = '';
					$status = '';
				?>
				<tr>
					<td><?=$result[$i]['cp_name'];?></td>
					<?php 
						$cs_color = ($result[$i]['cp_current_status']==0)? 'color:red' : '';
					?>
					<td style="text-align:<?php if($_POST['event']<5){echo 'center';}else{echo 'left';}?>;">
					<?php 
						if($_POST['event']<5){
							if($result[$i]['cp_status']==1){
								echo 'เปิดพื้นที่ใหม่';
							}
							elseif($result[$i]['cp_status']==2 AND $result[$i]['cp_stage']==3 ){
								if($result[$i]['cp_effective']==1){
									echo 'มีผลบังคับใช้';
								}else{
									echo '<text style="color:red">สิ้นสุดบังคับใช้</text>';
								}
							}else{
								echo '-';
							}
						}else{
							echo $result[$i]['rp_cs_op_title'];
						}
					?>
					</td>
					<td style="text-align:center">
						<?php 
						if($result[$i]['csd_step_fst']>0){
							echo $result[$i]['csd_step_fst'];
						}else{
							echo '-';
						}
						?>
					</td>
					<td style="text-align:center">
						<?php 
						if($result[$i]['csd_step_fst']>0){
							if($result[$i]['csd_step_snd']>0){
								echo $result[$i]['csd_step_fst'].'.'.$result[$i]['csd_step_snd'];
								$step = 'snd';
							}else{
								echo '-';
							}
						}else{
							echo '-';
						}
						?>
					</td>
					<td style="text-align:center">
						<?php 
						if($result[$i]['csd_step_fst']>0){
							if($result[$i]['csd_step_snd']>0){
								if($result[$i]['csd_step_trd']>0){
									echo $result[$i]['csd_step_fst'].'.'.$result[$i]['csd_step_snd'].'.'.$result[$i]['csd_step_trd'];
									$step = 'trd';
								}else{
									echo '-';
								}
							}else{
								echo '-';
							}
						}else{
							echo '-';
						}
						?>
					</td>
					<?php 
					
					
					if($step=='snd'){
						$status = status_snd('-',$result[$i]['csd_step_fst'],$result[$i]['csd_step_snd'],$result[$i]['csd_step_trd'],$result[$i]['csd_petition'],$result[$i]['csd_start'],$result[$i]['csd_stop']);
					}elseif($step=='trd'){
						$status = status_trd('-',$result[$i]['csd_step_fst'],$result[$i]['csd_step_snd'],$result[$i]['csd_step_trd'],$result[$i]['csd_petition'],$result[$i]['csd_start'],$result[$i]['csd_stop']);
					}else{
						$status = '';
					}
					
					
					if($status['sub_title']=='ดำเนินการเสร็จสิ้น'){
					?>
						<td style="text-align:center;background:#2D882D !important;color:white">ดำเนินการเสร็จสิ้น</td>
					<?php
					}else{ ?>
						<td style="text-align:center;<?=$status['bg-style']?>" title="<?=$status['sub_title']?>">
							<?php 
							//print_r($status);
							if($step == 'snd' OR $step == 'trd'){
								if($status['sub_title'] == 'ไม่ระบุวันที่เริ่มต้น'){
									echo $status['sub_title'];
								}else{
									if($status['day']<=0){
										$status['day'] = ($status['day']<0)? $status['day']*(-1) : $status['day']*1;
										echo 'เหลือเวลา : '.$status['day'].' วัน';
									}elseif($status['day']<=$status['ot1']){
										echo 'ล่าช้าเล็กน้อย : '.$status['day'].' วัน';
									}elseif($status['day']<=$status['ot2']){
										echo 'ล่าช้าปานกลาง : '.($status['day']-$status['ot1']).' วัน';
									}else{
										echo 'ล่าช้ามาก : '.($status['day']-$status['ot2']).' วัน';
									}
								}
							}else{
								echo '-';
							}
							?>
						</td>
					<?php } ?>
					
				</tr>
				<?php
				}
			?>
		</tbody>
	</table>
	<?php }
		pagin($row,$all_data);
	?>
	
	
	
	
	
	
	
<?php
}else{
	echo "<h2 style='text-align:center;font-family:Kanit'>ไม่มีข้อมูล</h2>";
}
?>