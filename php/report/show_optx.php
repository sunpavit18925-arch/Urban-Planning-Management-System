<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$datex = date('Y-m-d');
$datex = date_create($datex);


	if($_POST['event']=='step'){
		$condx = '((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = 1 AND cp_stage = 1';
	}
	else{
		$condx = '((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = 2 AND cp_stage = 3';
	}
	if($_POST['opt']==1){
		$owner = 'cp_owner = 1';
	}
	else{
		$owner = 'cp_owner = 2';
	}
	$cond_post = '';
	$select = "*";
	$from = "tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_scope, ref_status , ref_up_fst_step , ref_up_snd_step , ref_cityplan_step_detail";
	$join_tb = "cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_scope = scope_id AND cp_status = status_id AND cp_step = step_id AND step_id = sub_step AND cp_step_1 = sub_no AND cp_csd = csd_seq";
	$sql = 'SELECT '.$select.' FROM '.$from.' WHERE '.$join_tb.' AND '.$owner.' AND '.$condx.' ORDER BY pvn_name ASC';
	//echo $sql;
	$result = selectSql($sql);
	$row = 20;
	$all_data = count($result);
	$all_table = ceil($all_data/$row);
	if($all_data > 0){
	?>
	<div>
	<table hidden id="table_print" class="table table-bordereds printx" style="border-width:1px;border-style:solid;border-color:black">
			<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center" rowspan="2">ลำดับ </td>
						<td style="text-align:center" rowspan="2">จังหวัด</td>
						<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
						<td style="text-align:center" colspan="2" rowspan="2">ผู้ดำเนินการ</td>
						<td style="text-align:center" rowspan="2">ขอบเขต</td>
						<td style="text-align:center" rowspan="2">สถานะผัง</td>
						<td style="text-align:center" rowspan="2">ขั้นตอน</td>
					</tr>
					<tr style="background-color: #0AAAFA;color:white">
						
					</tr>
				</thead>
			<tbody style="background-color:white">
			<?php 
				for($i=0,$j=0 ; $j<$all_data ; $i++,$j++){
					if($i==$all_data){
						break;
					}
					//foreach($result as $row){ $i++;
					$daynd =0;
					$color="";
					$cp_note =NULL;
					$todays = date("Y");
					$toYear = date("Y")+543;
					$toDayMonth = date("-m-d");
					$now = $toYear.$toDayMonth;
					$eight = '';
					$feight = "";
			?>
				<tr >
					<td style="text-align:center"><?php echo $i+1;?></td>
					<td style="text-align:left"><?php echo $result[$i]['pvn_name'];?></td>
					<td style="text-align:left"><?php echo $result[$i]['cp_name'];?></td>
					<td style="text-align:center;min-width:50px">
					<?php 
						if($result[$i]['cp_owner']!=1){
							echo '<color style="color:red">'.$result[$i]['opt_fst_name'].'</color>';
						}else{
							echo $result[$i]['opt_fst_name'];
						}
					?>
					</td>
					<td style="text-align:center;min-width:50px"><?php echo $result[$i]['opt_snd_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['scope_name'];?></td>
					<td style="text-align:center">
						<?php 
							if($result[$i]['cp_status']==1 AND $result[$i]['cp_effective']==0){
								echo "เปิดพื้นที่ใหม่";
							}elseif($result[$i]['cp_status']==2 AND $result[$i]['cp_effective']==1){
								echo "มีผลบังคับใช้";
							}
							elseif($result[$i]['cp_status']==2 AND $result[$i]['cp_effective']==2){
								echo "สิ้นสุดบังคับใช้";
							}
							else{
								echo "-";
							}
						?>
					</td>
					<td style="text-align:center;">
						<?php 
							if($result[$i]['cp_step']==0){
								echo '-';
							}else{
								echo '<b>'.$result[$i]['cp_step'].'</b>';
							}
						?>
					</td>
					<?php 
						if($result[$i]['cp_step']==6){
								if($result[$i]['cp_petition']==1){
									$sql = "SELECT (sub_time2*7) as ot1,(sub_time2*7*2) as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['cp_step']."' AND sub_no = '".$result[$i]['cp_step_1']."'";
								}
								else{
									$sql = "SELECT (sub_time1*7) as ot1,(sub_time1*7*2) as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['cp_step']."' AND sub_no = '".$result[$i]['cp_step_1']."'";
								}
								$resultx = selectSql($sql);
								$result[$i]['sub_ot1'] = $resultx[0]['ot1'];
								$result[$i]['sub_ot2'] = $resultx[0]['ot2'];
								
								if($result[$i]['cp_step_1']!=0 AND $result[$i]['csd_start']!=NULL){
									if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
										$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
										$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7*2);
									}
									$date0 = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
									$date1=date_create($date0);
									$deadline = date_add($date1,date_interval_create_from_date_string(($result[$i]['sub_ot1']-1)." day"));
									$date2=date_create($now);
									if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
										$diff2 = date_diff($deadline,$datex);
										}else{
										$csd_stop = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
										$csd_stop = date_create($csd_stop);
										$diff2 = date_diff($deadline,$csd_stop);
										}
									$daynd = $diff2->format("%R%a");
									$daynd = intval($daynd);
									if($daynd <= 0){
										$color = "background-color:#2D882D!important;color:white!important";
										$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
										}
									elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
										$color = "background-color:#FFE841!important;color:black!important ";
										$cp_note = "ล่าช้า ".$daynd." วัน";
										}
									elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
										$color = "background-color:orange!important;color:black!important";
										$cp_note = "ล่าช้า ".$daynd." วัน";			
										}
									else{
										$color = "background-color:red!important;color:white!important";
										$cp_note = "ล่าช้า ".$daynd." วัน";
									}
								}else{
									
								}
							}
							elseif($result[$i]['cp_step']!=6){
								if($result[$i]['cp_step_1']!=0 AND $result[$i]['csd_start']!=NULL){
									if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
										$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
										$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7/2);
									}
									$date0 = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
									$date1=date_create($date0);
									$deadline = date_add($date1,date_interval_create_from_date_string(($result[$i]['sub_ot1']-1)." day"));
									$date2=date_create($now);
									if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
										$diff2 = date_diff($deadline,$datex);
									}else{
										$csd_stop = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
										$csd_stop = date_create($csd_stop);
										$diff2 = date_diff($deadline,$csd_stop);
									}
									$daynd = $diff2->format("%R%a");
									$daynd = intval($daynd);
									
									if($daynd <= 0){
										$color = "background-color:#2D882D!important;color:white!important";
										$eight = '#2D882D';
										$feight = 'white';
										$cp_note = "เหลือเวลา ".($daynd*(-1))." วัน";
									}
									elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
										$color = "background-color:#FFE841!important;color:black!important ";
										$eight = "#FFE841";
										$feight = "black";
										$cp_note = "ล่าช้า ".$daynd." วัน";
									}
									elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
										$color = "background-color:orange!important;color:black!important";
										$eight = "orange";
										$feight = "black";
										$cp_note = "ล่าช้า ".$daynd." วัน";	
									}
									else{
										$color = "background-color:red!important;color:white!important";
										$eight = "red";
										$feight = "white";
										$cp_note = "ล่าช้า ".$daynd." วัน";
									}
								}else{
										
									}
							}	
							
							if($result[$i]['cp_step']==8){
								if($result[$i]['cp_step_1']==2 OR $result[$i]['cp_step_1']==4 OR $result[$i]['cp_step_1']==5 OR $result[$i]['cp_step_1']==6 OR $result[$i]['cp_step_1']==8){
									if($eight==''){
										$eight = 'transparent';
										$feight = "black";
									}
									$color  = "background: linear-gradient(90deg,#0000ff 0%,#0000ff 25%,".$eight." 25%,".$eight." 100%)!important;color:".$feight.'!important';
}
							}
					?>
					<td style="text-align:center;<?php echo $color;?>;-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;" title="<?php echo 'ขั้นตอน : '.$result[$i]['sub_name']?>&#10;<?php echo $cp_note;?>">
						<?php
							if($result[$i]['cp_step_1']==0){
								echo '-';
							}else{
								echo $result[$i]['cp_step'],'.',$result[$i]['cp_step_1'];
							}
						?>
					</td>
					<?php 
						$trd = '';
						$trd_ot1 = '';
						$trd_ot2 = '';
						if($result[$i]['cp_step']>0 AND $result[$i]['cp_step_1']>0 AND $result[$i]['cp_step_2']>0){
							$sql = "SELECT trd_name , trd_ot1 , trd_ot2 FROM ref_up_trd_step WHERE trd_fst = '".$result[$i]['cp_step']."' AND trd_snd = '".$result[$i]['cp_step_1']."' AND trd_step = '".$result[$i]['cp_step_2']."'";
							$resultrd = selectSql($sql);
							$result[$i]['trd_name'] = $resultrd[0]['trd_name'];
							$result[$i]['trd_ot1'] = $resultrd[0]['trd_ot1'];
							$result[$i]['trd_ot2'] = $resultrd[0]['trd_ot2'];
							
							if($result[$i]['csd_start_2']!='' AND $result[$i]['csd_start_2']!=null){
								$deadline2 = dead_line($result[$i]['csd_start_2'],$result[$i]['trd_ot1'],'th');
								//echo date_format($deadline2,'Y-m-d');
								//echo $result[$i]['cp_no'].' : '.$result[$i]['csd_start_2'].' - '.date_format($deadline2,'Y-m-d').'<br>';								
								if($result[$i]['csd_stop_2']=='' OR $result[$i]['csd_stop_2']==' ' OR $result[$i]['csd_stop_2']==NULL){
									$diff3 = date_diff($deadline2,$datex);
								}else{
									$csd_stop_2 = (substr($result[$i]['csd_stop_2'],0,4)-543).substr($result[$i]['csd_stop_2'],4);
									$csd_stop_2 = date_create($csd_stop_2);
									$diff3 = date_diff($deadline2,$csd_stop_2);
								}							
								$dayrd = $diff3->format("%R%a");
								$dayrd = intval($dayrd);
								if($dayrd <= 0){
									$color_2 = "background-color:#2D882D!important;color:white!important;";
									$cp_note_2 = "เหลือเวลา ".($dayrd*-1)." วัน";
								}
								elseif($dayrd <= $result[$i]['trd_ot1']){
									$color_2 = "background-color:#FFE841!important;color:black!important;";
									$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
								}
								elseif($dayrd <= $result[$i]['trd_ot2']){
									$color_2 = "background-color:orange!important;color:black!important;";
									$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
								}
								else{
									$color_2 ="background-color:red!important;color:white!important;";
									$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
								}
							}else{
								$color_2 = "";
								
							}						
						}else{
							$color_2 = "";
						}
					
					?>
					<td style="text-align:center;<?php echo $color_2;?>;-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;">
							<?php 
							if($result[$i]['cp_step_2']==0){
								echo "-";
							}else{
								echo $result[$i]['cp_step'],'.',$result[$i]['cp_step_1'].'.'.$result[$i]['cp_step_2'];
							}
							
							
							?>
					</td>
					<td style="text-align:center;"><?php //echo $result[$i]['sub_ot1'].'----'.$result[$i]['sub_ot2'];$cp_note;?></td>
				</tr> 
				<?php } ?>
			</tbody>	
		</table>
	
	<?php
		$all=0;
		$aff_1=0;$aff_2=0;$aff_3=0;$aff_4=0;
		$opt=0;$dpt=0;
		$full=0;$half=0;
		for($i=0,$t=1 ; $t<= $all_table ; $t++ ){ 
		?>
			<table id="table_<?= $t;?>" class="table table-bordered unprintable" style="border-width:1px;border-style:solid;border-color:black">
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center" rowspan="2">ลำดับ </td>
						<td style="text-align:center" rowspan="2">จังหวัด</td>
						<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
						<td style="text-align:center" colspan="2" rowspan="2">ผู้ดำเนินการ</td>
						<td style="text-align:center" rowspan="2">ขอบเขต</td>
						<td style="text-align:center" rowspan="2">สถานะผัง</td>
						<td style="text-align:center" rowspan="2">ขั้นตอน</td>
						<td class="unprintable" style="text-align:center" rowspan="2" colspan="2">ระบุหมายเหตุการดำเนินการ</td>
						</tr>
					<tr style="background-color: #0AAAFA;color:white"></tr>
				</thead>
				<tbody style="background-color:white">
				<?php 
					for($i,$j=0 ; $j<$row ; $i++,$j++){
						if($i==$all_data){
							break;
						}
						
						$daynd =0;
						$color="";
						$eight="";
						$feight = "";
						$cp_note =NULL;
						$cp_note_2 = '';
						$todays = date("Y");
						$toYear = date("Y")+543;
						$toDayMonth = date("-m-d");
						$now = $toYear.$toDayMonth;
				?>
					<tr >
						<td style="text-align:center"><?php echo $i+1;?></td>
						<td style="text-align:left"><?php echo $result[$i]['pvn_name'];?></td>
						<td style="text-align:left">
							<?php if($result[$i]['cp_consider']!=0){?>
								<span style="margin-right:0px;cursor: pointer;" onclick="readConsider(<?= $result[$i]['cp_no'];?>)" class="comment"><a><i style="transform: scaleX(-1);" class="fa fa-comment"></i></a></span>
							<?php }elseif($result[$i]['cp_consider']==0 AND ($result[$i]['cp_step']==0 OR ($result[$i]['cp_step']!=0 AND $result[$i]['cp_step_1']==0))){ ?>
								<span style="margin-right:0px;cursor: not-allowed;" title="ไม่ระบุเหตุผลผังพิจารณาดำเนินการ" ><a style="color:#ff4040"><i style="transform: scaleX(-1);" class="fa fa-comment"></i></a></span>
							<?php }echo $result[$i]['cp_name'];?>
						</td>
						<td style="text-align:center">
							<?php 
								if($result[$i]['cp_owner']==1){
									$dpt+=1;
									echo $result[$i]['opt_fst_name'];
								}else{
									$opt+=1;
									echo '<color style="color:red">'.$result[$i]['opt_fst_name'].'</color>';
								}
							?>
						</td>
						<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
						<td style="text-align:center">
							<?php 
								if($result[$i]['cp_scope']==1){
									$half+=1;
									echo $result[$i]['scope_name'];
								}else{
									$full+=1;
									echo '<b>เต็ม</b>อำเภอ';
								}
							?>
						</td>
						<td style="text-align:center"><?php 
							if($result[$i]['cp_status']==1 AND $result[$i]['cp_effective']==0){
								echo "เปิดพื้นที่ใหม่";
							}elseif($result[$i]['cp_status']==2 AND $result[$i]['cp_effective']==1){
								echo "มีผลบังคับใช้";
							}
							elseif($result[$i]['cp_status']==2 AND $result[$i]['cp_effective']==2){
								echo "<color style='color:red'>"."สิ้นสุดบังคับใช้".'</color>';
							}
							else{
								echo "-";
							}
							?>
						</td>
						<td style="text-align:center">
						<?php
							if($result[$i]['cp_stage']=='1'){
								echo "เปิดใหม่";
							}
							elseif($result[$i]['cp_stage']=='2'){
								echo "ประเมิน 5 ปี";
							}
							elseif($result[$i]['cp_stage']=='3'){
								echo "ปรับปรุง";
							}
							elseif($result[$i]['cp_stage']=='4'){
								echo "ประเมิน 10 ปี";
							}
							else{
								echo "-";
							}
						?>
					</td>
						
						<td style="text-align:center;color:black" class="unprintable"> 
							<?php 
							$sql = "SELECT COUNT(*) as note FROM tb_note WHERE note_status = 1 AND note_cp = '".$result[$i]['cp_no']."'";
							$noter = selectSql($sql);
							if($noter[0]['note']>0){
								//echo $noter[0]['note'];
								?>
								<span  onclick="readNoter(<?= $result[$i]['cp_no'];?>)" title="<?= $noter[0]['note'].' หมายเหตุ';?>">
									<a>
										<i class="fa fa-list-alt"></i>
									</a>
								</span>
								<?php
							}else{
								echo '<span title="ไม่มีหมายเหตุ">-</span>';
							}
							?>
						</td>
						<td style="text-align:center;color:black" class="unprintable"> 
							<?php if($_SESSION['status']=='1' AND ($_POST['event']=='step' OR $_POST['event']=='step2') AND ($result[$i]['cp_step']=="0" OR ($result[$i]['cp_step']!="0" AND $result[$i]['cp_step_1']=="0"))){
							?><span onclick="setConsider(<?= $result[$i]['cp_no']; ?> , <?= $result[$i]['cp_consider']; ?>)" style="margin-right:10px;" title="เพิ่มเหตุผลพิจารณาดำเนินการ" class="gear"id="pin_<?php echo $result[$i]['cp_no'];?>">
								<a><i class="fa fa-comments-o"></i></a>
							  </span><?php } ?>
							<span hidden id="loading_<?php echo $result[$i]['cp_no'];?>"><i class="fa fa-refresh fa-spin"></i></span>			
							<span style="cursor:pointer;" title="แสดงขั้นตอน" class="step" onclick="XopenStep(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-list"></i></a></span>
							<span hidden id="reader_<?php echo $result[$i]['cp_no'];?>" title="แสดงรายละเอียด" class="reorder"><a onclick="Readstepnote(<?php echo $result[$i]['cp_no']; ?>);"><i class="fa fa-reorder"></i></a></span> 
							<span hidden id="loading_<?php echo $result[$i]['cp_no'];?>"><i class="fa fa-refresh fa-spin"></i></span>
						</td> 
					</tr> 
					<?php 
					if($result[$i]['aff_id']==1){$aff_1+=1;}
					elseif($result[$i]['aff_id']==2){$aff_2+=1;}
					elseif($result[$i]['aff_id']==3){$aff_3+=1;}
					else{$aff_4+=1;}
					
					$all +=1;
					} ?>
				</tbody>	
			</table>
	<?php 
		}
		pagin($row,$all_data);
		if($_SESSION['report_page']>1){
			echo "<script>$('#select_table').val('".$_SESSION['report_page']."');change_table();</script>";
			unset($_SESSION['report_page']);
		}
		else{
			echo "<script>$('#select_table').val('1');</script>";
		}
	?>
	</div>
	
	
	<?php }else{
		echo "zero";
	} ?>
	
	
	
	
	
<script>
$('#returnSSR').show();
function returnSSR(){
	$('#showTable').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
	<?php 
		if($_POST['report']=='tree'){
			echo "searchType('cityplan');";
		}else{
			echo "searchpvn();";
		}
	?>
	$('#returnSSR').hide();
	
}
</script>
	
	
	
	
