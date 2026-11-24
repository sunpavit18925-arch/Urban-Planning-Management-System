<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();

$from = "ref_cityplan_step_detail_m35";
$join = "csd35_status = 1 AND csd35_cp = '".$_POST['csd35_cp']."'";

//$from = " ref_cityplan_step_detail_m35 , tb_cityplan , ref_province ";
//$join = " csd35_status = 1 AND csd35_cp = cp_no AND cp_province = pvn_id ORDER BY csd35_seq DESC";

$sql = "SELECT * FROM ".$from." WHERE ".$join." ORDER BY csd35_seq DESC";
//echo $sql;
$result = selectSql($sql);
$all_data = count($result);
?><script>$("#search_value").html("จำนวน <?= $all_data?> ขั้นตอน");</script><?php
//print_r($_POST);

?>

	<?php
					$row = 20;
					
					
					for($n=0,$page=1;$page<=ceil($all_data/$row);$page++){
				?>
			<div class="table-responsive col-xs-12 my-tb">
				<table id="table_<?=$page?>" class="table table-bordered">
					<thead>
						<tr>
							<td style="text-align:center" rowspan="2">ลำดับ</td>
							<td style="text-align:center" colspan="2">ขั้นตอนดำเนินการ (ม.35)</td>
							<td style="text-align:center" colspan="2">เวลาดำเนินการตามแผน</td>
							<td style="text-align:center" colspan="2">เวลาดำเนินการตามผล</td>
							<td style="text-align:center" rowspan="2">หมายเหตุ</td>
							<?php if($_SESSION['status']==1){ ?>
							<td style="text-align:center" rowspan="2">จัดการ</td>
							<?php } ?>
						</tr>
						<tr>
							<td style="text-align:center">ขั้นตอนหลัก</td>
							<td style="text-align:center">ขั้นตอนย่อย</td>
							<td style="text-align:center">วันที่เริ่มดำเนินการ</td>
							<td style="text-align:center">วันที่สิ้นสุดดำเนินการ</td>
							<td style="text-align:center">วันที่เริ่มดำเนินการ</td>
							<td style="text-align:center">วันที่สิ้นสุดดำเนินการ</td>
						</tr>
						
						
					</thead>
					<tbody>
					<?php 
					//$n = 0;
					for($i=0;$i<$row;$i++){ 
						if($n>=$all_data){
							break;
						}
						//$status_snd = status_snd('m35',$result[$n]['csd35_step_fst'],$result[$n]['csd35_step_snd'],$result[$n]['csd35_step_trd'],$result[$n]['csd35_petition'],$result[$n]['csd35_start'],$result[$n]['csd35_stop']);
						
						$plan_snd_start = $result[$n]['csd35_plan_snd_start'] == null?"-":$result[$n]['csd35_plan_snd_start'];
						$plan_snd_stop = $result[$n]['csd35_plan_snd_stop'] == null?"-":$result[$n]['csd35_plan_snd_stop'];
						$snd_start = $result[$n]['csd35_snd_start'] == null?"-":$result[$n]['csd35_snd_start'];
						$snd_stop = $result[$n]['csd35_snd_stop'] == null?"-":$result[$n]['csd35_snd_stop'];
						$csd35 =  m35_color($result[$n]['csd35_seq'],$_POST['operator']);
						//print_r($csd35);
					?>
						<tr>
							<td style="text-align:center"><?=$n+1;?><?php if($result[$n]['csd35_seq']==$_POST['cp35_csd']){echo " (ขั้นตอนปัจจุบัน)";}?></td>
						<?php 
						if($result[$n]['csd35_update']==0){ 
							echo '<td colspan="6" style="text-align:center;color:red">โปรดระบุขั้นตอน</td>';
						}else{
							$step_fst = $result[$n]['csd35_step_fst']<1 ? '-': $result[$n]['csd35_step_fst'];
							$step_snd = $result[$n]['csd35_step_snd']<1 ? '-': $result[$n]['csd35_step_fst'].'.'.$result[$n]['csd35_step_snd'];
							$step_fst = $result[$n]['csd35_petition']==1? $step_fst.'**':$step_fst;
							$step_snd = $result[$n]['csd35_petition']==1? $step_snd.'**':$step_snd;
						?>
							<td style="text-align:center"><?=$step_fst?></td>
							<td style="text-align:center;<?=$csd35['bgstyle']?>" title="<?=$csd35['title']?>"><?=$step_snd?></td>
							<td style="text-align:center;"><?=$plan_snd_start?></td>
							<td style="text-align:center"><?=$plan_snd_stop?></td>
							<td style="text-align:center"><?=$snd_start?></td>
							<td style="text-align:center"><?=$snd_stop?></td>
						<?php } ?>
							
							<td style="text-align:center">
								<?php 
								if($result[$n]['csd35_update']>0){
									if($result[$n]['csd35_note']>0){?>
										<span class="note" onclick="setNoteM35(<?=$result[$n]['csd35_seq']?>)"><a><i class="fa fa-list-alt"></i></a></span>
									<?php }else{ ?>
										<span class="plus" onclick="addNoteM35(<?=$result[$n]['csd35_seq']?>)"><a><i class="fa fa-plus"></i></a></span>
									<?php }
								}else{ echo '-';}?>
							</td>
							<?php if($_SESSION['status']==1){ 
									$y = $result[$n]['csd35_update']==0?'false':'true';
							?>
								<td style="text-align:center">
									<?php if($_SESSION['data_access']==2 ){ ?>
										<span title="อัปเดตคขั้นตอน" class="gear"><a onclick="setStepM35(<?=$result[$n]['csd35_seq']?>,'<?=$y?>')"><i class="fa fa-gear"></i></a></span>
									<?php }elseif($_SESSION['data_access']==9){ ?>
										<span title="อัปเดตคขั้นตอน" class="gear"><a onclick="setStepM35(<?=$result[$n]['csd35_seq']?>,'<?=$y?>')"><i class="fa fa-gear"></i></a></span>
										<?php if($result[$n]['csd35_seq']!=$_POST['cp35_csd']){ ?> 
											<span title="ลบขั้นตอน" style="margin:0px 0px 0px 5px" class="trash"><a onclick="delM35(<?=$result[$n]['csd35_seq']?>)"><i class="fa fa-trash"></i></a></span>
											<span title="ใช้เป็นขั้นตอนปัจจุบัน" style="margin: 0 0 0 5px" class="bookmark"><a onclick="setCurrent(<?=$result[$n]['csd35_seq']?>)"><i class="fa fa-bookmark"></i></a></span>
										<?php } ?>
									<?php } ?>	
								</td>
							<?php } ?>
						</tr>
						<?php 
							$n++;
							$step_fst = '';
							$step_snd = '';
							$step_trd = '';
						} ?>
					</tbody>
				</table>
			</div>
			<?php }
				//pagin($row,$all_data);
				pagin($row,$all_data);
			?>