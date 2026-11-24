<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
//echo $_POST['cp_csd'];
//$sql = "SELECT * FROM ref_cityplan_step_detail WHERE csd_cp = '".$_POST['cp_no']."' ORDER BY csd_plan_start DESC ";
$sql = "SELECT * FROM ref_cityplan_step_detail WHERE csd_cp = '".$_POST['cp_no']."' ORDER BY csd_plan_start DESC,csd_seq DESC ";
//echo $sql;
$result = selectSql($sql);
$all_data = count($result);
$row = 20;
$all_table = ceil($all_data/$row);
if($all_data > 0){
	?>
	<div class="table-responsive">
		<?php 
		for($table=1 , $i=0 ; $table <= $all_table ;$table++){ ?>
		<table id="table_<?php echo $table;?>" class="table table-bordered">
			<thead>
				<tr class="table-header">
					<td style="text-align :center" rowspan="2"><b>ลำดับ</b></td>
					<td style="text-align :center" colspan="3"><b>ขั้นตอนดำเนินการ</b></td>
					<td style="text-align :center" rowspan="2"><b>เริ่มต้น</b></td>
					<td style="text-align :center" rowspan="2"><b>สิ้นสุด</b></td>
					<td style="text-align :center" rowspan="2" width="150px"><b>ผลดำเนินการ</b></td>
					<td style="text-align :center" rowspan="2"><b>หมายเหตุ</b></td>
				</tr>
				<tr class="table-header">
					<td style="text-align :center" ><b>ขั้นตอนหลัก</b></td>
					<td style="text-align :center" ><b>ขั้นตอนรอง</b></td>
					<td style="text-align :center" ><b>ขั้นตอนรอง2</b></td>
				</tr>
			</thead>
			<tbody>	 <?php 
			for($i, $j=1 ; $j<=$row ; $j++ , $i++){ 
				$color = 'white';
				if($i==$all_data){ break;} ?>
				<tr>
					<td style="text-align:center;<?php if($result[$i]['csd_seq']==$_POST['cp_csd']){ /*echo "background-color:#D5FFA9"*/;}?>"><?php echo $i+1; if($result[$i]['csd_seq']==$_POST['cp_csd']){ echo " (ขั้นตอนปัจจุบัน)";}?></td>
					<td style="text-align:center"><b><?php echo $result[$i]['csd_step_fst'];?></b></td>
					<td style="text-align:center"><?php if($result[$i]['csd_step_snd']!=0 AND $result[$i]['csd_step_snd'] != "" AND $result[$i]['csd_step_snd']!=NULL){echo $result[$i]['csd_step_fst'].'.'.$result[$i]['csd_step_snd'];}else{echo '-';}?></td>
					<td style="text-align:center"><?php if($result[$i]['csd_step_trd']!=0 AND $result[$i]['csd_step_trd'] != "" AND $result[$i]['csd_step_trd']!=NULL){echo $result[$i]['csd_step_fst'].'.'.$result[$i]['csd_step_snd'].'.'.$result[$i]['csd_step_trd'];}else{echo '-';}?></td>
					<td style="text-align:center"><?php if($result[$i]['csd_start']!=null){echo $result[$i]['csd_start'];}else{echo '-';}?></td>
					<td style="text-align:center"><?php if($result[$i]['csd_stop']!=null){echo $result[$i]['csd_stop'];}else{echo '-';}?></td>
					<?php 
					//
					if($result[$i]['csd_step_fst']==0 OR $result[$i]['csd_step_fst'] == "" OR $result[$i]['csd_step_fst']==null){
						$day = "-";
						$ot1 = 0; 
						$ot2 = 0;
						$color = "transparent";
						$font = "black";
						$text = "ไม่มีการดำเนินการ";
						$title = "";
					}else{
						//fst 
						if($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd'] == 0 AND $result[$i]['csd_step_trd'] == 0){
							if($result[$i]['csd_step_fst']==6){
								if($result[$i]['csd_petition']==0){
									$sql = "SELECT (step_time1*7) as ot1 , (step_time1*14) as ot2 FROM ref_up_fst_step WHERE step_id ='".$result[$i]['csd_step_fst']."'";
									//echo '1111111111111111111111';
								}else{
									$sql = "SELECT (step_time2*7) as ot1 , (step_time2*14) as ot2 FROM ref_up_fst_step WHERE step_id ='".$result[$i]['csd_step_fst']."'";
									//echo '0000000000000000000000';
								}
							}else{
								$sql = "SELECT step_ot1 as ot1 , step_ot2 as ot2 FROM ref_up_fst_step WHERE step_id ='".$result[$i]['csd_step_fst']."'";
							}
							//$sql = "SELECT step_ot1 as ot1 , step_ot2 as ot2 FROM ref_up_fst_step WHERE step_id ='".$result[$i]['csd_step_fst']."'";
							$ot = selectSql($sql);
						}
						//snd
						elseif($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd'] > 0 AND $result[$i]['csd_step_trd'] == 0){
							if($result[$i]['csd_step_fst']==6){
								if($result[$i]['csd_petition']==0){
									$sql = "SELECT (sub_time1*7) as ot1 , (sub_time1*14) as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['csd_step_fst']."' AND sub_no ='".$result[$i]['csd_step_snd']."'";
								}else{
									$sql = "SELECT (sub_time2*7) as ot1 , (sub_time2*14)  as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['csd_step_fst']."' AND sub_no ='".$result[$i]['csd_step_snd']."'";
								}
							}else{
								$sql = "SELECT sub_ot1 as ot1 , sub_ot2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['csd_step_fst']."' AND sub_no ='".$result[$i]['csd_step_snd']."'";
							}
							$ot = selectSql($sql);
						}
						//trd
						elseif($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd'] > 0 AND $result[$i]['csd_step_trd'] > 0){
							$sql = "SELECT trd_ot1 as ot1 , trd_ot2 as ot2 FROM ref_up_trd_step WHERE trd_fst = '".$result[$i]['csd_step_fst']."' AND trd_snd = '".$result[$i]['csd_step_snd']."' AND trd_step ='".$result[$i]['csd_step_trd']."'";
							$ot = selectSql($sql);
						}
						$ot1 = $ot[0]['ot1'];
						$ot2 = $ot[0]['ot2'];					
						
						if($result[$i]['csd_stop']=="" OR $result[$i]['csd_stop']==null){
							$toYear = date("Y")+543;
							$toDayMonth = date("-m-d");
							$now = $toYear.$toDayMonth;
							$finish = date_create($now);
							$text = "อยู่ระหว่างดำเนินการ";
						}
						else{
							$finish = date_create($result[$i]['csd_stop']);
							$text = "ดำเนินการเสร็จสิ้น";
						}						
						$plan = date_create($result[$i]['csd_plan_stop']);
						 
						$diff = date_diff($plan,$finish);
						$day = intval($diff->format("%R%a"));	
						if($day <= 0){
							$color = '#2D882D';
							$font = 'white';
							$title = "ดำเนินการเสร็จสิ้นก่อนกำหนด : ".$diff->format('%a')." วัน";
							if($result[$i]['csd_stop']=="" OR $result[$i]['csd_stop']==null){
								$title = "เหลือเวลา : ".$diff->format('%a')." วัน";
							}
						}elseif($day > 0 AND $day <= $ot1){
							$color = 'yellow';
							$font = 'black';
							$title = "ล่าช้า : ".$diff->format('%a')." วัน";
						}
						elseif($day > $ot1 AND $day <= $ot2){
							$color = 'orange';
							$font = 'white';
							$title = "ล่าช้า : ".$diff->format('%a')." วัน";
						}
						else{
							$color = 'red';
							$font = 'white';
							$title = "ล่าช้า : ".$diff->format('%a')." วัน";
						}
					}
					?>
					<td title="<?php echo $title; ?>" style="text-align :center;<?php echo 'color:'.$font.';background-color:'.$color;?>"><?php echo $text;/*.'-'.$ot1.'+'.$ot2;*/?></td>
					<td style="text-align :center" >
						<?php 	if($result[$i]['csd_note']==0) {
									echo '<span title="ไม่มีหมายเหตุ">- </span>';
								}else{?>
							<span class="list-alt"><a onclick="ureadNote(<?php echo $result[$i]['csd_step_fst'];?>,<?php echo $result[$i]['csd_step_snd'];?>,<?php echo $result[$i]['csd_step_trd'];?>,<?php echo $result[$i]['csd_cp'];?>,<?php echo $result[$i]['csd_seq'];?> )"><i class="fa fa-list-alt"></i></a></span>
						<?php }?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</div>
	<?php pagin($row,$all_data)?>
<?php } 
else{
	echo '<div>';
	echo '<h1 class="hidden-xs hidden-sm" style="text-align:center ;font-family:Kanit; margin-top:150px">ไม่มีข้อมูลกรุณาเพิ่มขั้นตอน</h1>';
	echo '<h2 class="hidden-lg hidden-md" style="text-align:center ;font-family:Kanit; margin-top:50px">ไม่มีข้อมูล</h2><h2 class="hidden-lg hidden-md" style="text-align:center ;font-family:Kanit; margin-top:5px">กรุณาเพิ่มขั้นตอน</h2>';
	echo '</div>';
}