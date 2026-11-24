<div id="showTable">
<?php 
include 'js/function_db.php';
session_name('tutorial');
session_start();
$date = date('Y-m-d');
$datex = date('Y-m-d');
$datex = date_create($datex);
$all_cp = count($_POST['cp']);
if($_POST['color']==1){
	$cpstatus = 'ไม่ระบุวัน/เวลา';
}elseif($_POST['color']==2){
	$cpstatus = 'เป็นไปตามแผน';
}elseif($_POST['color']==3){
	$cpstatus = 'ล่าช้าเล็กน้อย';
}elseif($_POST['color']==4){
	$cpstatus = 'ล่าช้าปานกลาง';
}else{
	$cpstatus = 'ล่าช้ามาก';
}
$sub_title = "";
if($_POST['step']==0){
	$sub_title_step = "ขั้นตอนหลัก";
}elseif($_POST['step']==1){
	$sub_title_step = "ขั้นตอนย่อยที่ 1";
}elseif($_POST['step']==2){
	$sub_title_step = "ขั้นตอนย่อยที่ 2";
}else{
	echo "";
}
if($_POST['color']=='1'){
	$sub_title_color = "ไม่ระบุขั้นตอน";
}elseif($_POST['color']=='2'){
	$sub_title_color = "ผังที่ระบุขั้นตอน";
}elseif($_POST['color']=='3'){
	$sub_title_color = "เป็นไปตามแผน";
}elseif($_POST['color']=='4'){
	$sub_title_color = "ล่าช้าเล็กน้อย";
}elseif($_POST['color']=='5'){
	$sub_title_color = "ล่าช้าปานกลาง";
}elseif($_POST['color']=='6'){
	$sub_title_color = "ล่าช้ามาก";
}elseif($_POST['color']=='7'){
	$sub_title_color = "ไม่ระบุวัน/เวลา";
}elseif($_POST['color']=='8'){
	$sub_title_color = "รวม";
}elseif($_POST['color']=='9'){
	$sub_title_color = "เสนอหน่วยงานที่เกี่ยวข้อง";
}
else{
	echo "";
}







if($all_cp>0){
	$cond_post = '';
	for($i=0;$i<$all_cp;$i++){
		if($i==0){
			$cond_post = $cond_post.'cp_no = '.$_POST['cp'][$i];
		}else{
			$cond_post = $cond_post.' OR cp_no = '.$_POST['cp'][$i];
		}
	}
	$select = "*";
	$from = "tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_scope, ref_status , ref_up_fst_step , ref_up_snd_step , ref_cityplan_step_detail";
	$join_tb = "cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_scope = scope_id AND cp_status = status_id AND cp_step = step_id AND step_id = sub_step AND cp_step_1 = sub_no AND cp_csd = csd_seq";
	$sql = 'SELECT '.$select.' FROM '.$from.' WHERE '.$join_tb.' AND ('.$cond_post.') ORDER BY pvn_asc ASC';
	$result = selectSql($sql);
	$row = 20;
	$all_data = count($result);
	$all_table = ceil($all_data/$row);
	if($_POST['lating']=='true'){
		$sub_title = '<b>ตารางแสดงข้อมูลผัง : </b> ผังที่ดำเนินการขั้นตอนปัจจุบันเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป  จำนวน<b> '.$all_data.' </b>ผัง';
	}else{
		$sub_title = '<b>ตารางแสดงข้อมูลผัง : </b>'.$sub_title_step.' '.$sub_title_color.' จำนวน<b> '.$all_data.' </b>ผัง';
	}
	?>

	<div style="margin-bottom:0px;/*display: flex;align-items: flex-end;justify-content: space-between;*/">
		
			<h5  style="font-family:Kanit;float:left;margin-bottom:5px;margin-top:15px"><?= $sub_title;?></h5>
		
			<button class="btn btn-primary" style="font-family:Kanit;float:right;cursor:pointer;margin-bottom:4px;color:white" onclick="returnSSR()";>แสดงจำนวนผังทั้งหมด</button>
		
	</div>
	<div class="table-responsive" style="width:100%">
	<table hidden id="table_print" class="table table-bordereds printx" style="border-width:1px;border-style:solid;border-color:black">
			<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center;width:40px;" rowspan="2">ลำดับ</td>
						<td style="text-align:center;width:100px;" rowspan="2">จังหวัด</td>
						<td style="text-align:center;width:174px;" rowspan="2">รายชื่อผังเมืองรวม</td>
						<td style="text-align:center;width:144px;" rowspan="2" colspan="2">ผู้ดำเนินการ</td>
						<td style="text-align:center;width:75px;" rowspan="2">ขอบเขต</td>
						<td style="text-align:center;width:80px;" rowspan="2">สถานะผัง</td>
						<td style="text-align:center;width:70px;" rowspan="2">ขั้นตอน</td>
						<td style="text-align:center;" colspan="3">ขั้นตอนดำเนินการ</td>
						<td style="text-align:center;width:80px;" rowspan="2">ผลดำเนินการ</td>
					</tr>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center;width:80.53px;">ขั้นตอนหลัก</td>
						<td style="text-align:center;width:80.53px;">ขั้นตอนย่อย 1</td>
						<td style="text-align:center;width:80.53px;">ขั้นตอนย่อย 2</td>
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
					<td style="text-align:center;">
					<?php 
						$warning = '<span style="margin:0 4px;font-size:11px;">-</span>';
						if($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']>0){
							//echo $result[$i]['cp_no'].'<br>';
							if($result[$i]['csd_stop_2']!='' AND $result[$i]['csd_stop_2']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop_2'],0,4)-543).substr($result[$i]['csd_stop_2'],4);
								if($csd_stop_ad < $date){
									$warning =  '<span style="margin:0 4px;font-size:11px;"><a class="warning">!</a></span>';
									//array_push($lating,$result[$i]['cp_no']);
								}
							}
						}elseif($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']<1){
							if($result[$i]['csd_stop']!='' AND $result[$i]['csd_stop']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
								if($csd_stop_ad < $date){
									$warning =  '<span style="margin:0 4px;font-size:11px;"><a class="warning">!</a></span>';
									//array_push($lating,$result[$i]['cp_no']);
								}
							}
						}else{
							
						}
						echo $warning;
					
					
					?>
					</td>
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
			<table id="table_<?= $t;?>" class="table table-bordereds unprintable" style="border-width:1px;border-style:solid;border-color:black">
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center" rowspan="2">ลำดับ </td>
						<td style="text-align:center" rowspan="2">จังหวัด</td>
						<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
						<td style="text-align:center" colspan="2" rowspan="2">ผู้ดำเนินการ</td>
						<td style="text-align:center" rowspan="2">ขอบเขต</td>
						<td style="text-align:center" rowspan="2">สถานะผัง</td>
						<td style="text-align:center" rowspan="2">ขั้นตอน</td>
						<td style="text-align:center" colspan="3">ขั้นตอนดำเนินการ</td>
						<td style="text-align:center" rowspan="2">ผลดำเนินการ</td>
						<td class="unprintable" style="text-align:center" rowspan="2" colspan="2">ระบุหมายเหตุการดำเนินการ</td>
						</tr>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center;width:107.53px">ขั้นตอนหลัก</td>
						<td style="text-align:center;width:107.53px">ขั้นตอนย่อย 1</td>
						<td style="text-align:center;width:107.53px">ขั้นตอนย่อย 2</td>
					</tr>
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
						
						$warning = '-';
						if($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']>0){
							//echo $result[$i]['cp_no'].'<br>';
							if($result[$i]['csd_stop_2']!='' AND $result[$i]['csd_stop_2']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop_2'],0,4)-543).substr($result[$i]['csd_stop_2'],4);
								if($csd_stop_ad < $date){
									$warning = '<span style="margin:0 4px;font-size:16px;"><a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป"><i class="fa fa-exclamation"></i></a></span>';
									//array_push($lating,$result[$i]['cp_no']);
								}
							}
						}elseif($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']<1){
							if($result[$i]['csd_stop']!='' AND $result[$i]['csd_stop']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
								if($csd_stop_ad < $date){
									$warning = '<span style="margin:0 4px;font-size:16px;"><a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป"><i class="fa fa-exclamation"></i></a></span>';
									//array_push($lating,$result[$i]['cp_no']);
								}
							}
						}else{
							
						}
				?>
					<tr >
						<td style="text-align:center"><?php echo $i+1;?></td>
						<td style="text-align:left"><?php echo $result[$i]['pvn_name'];?></td>
						<td style="text-align:left">
							<?php if($result[$i]['cp_consider']!=0){?>
								<svg hidden width="20" height="20">
								  <image href="img/chat.png" height="30" width="20" />
								</svg>
								<span style="margin-right:0px;cursor: pointer;" onclick="readConsider(<?= $result[$i]['cp_no'];?>)" class="comment"><a><i style="transform: scaleX(-1);" class="fa fa-comment"></i></a></span>
							<?php }
								echo '<text class="text_cp_name" onclick="gotoCityplanDetail('.$result[$i]['cp_no'].')">'.$result[$i]['cp_name'].'</text>';
							?>
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
						<td style="text-align:center;<?php //echo $color;?>">
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
								
								if($result[$i]['cp_step_1']!=0 AND $result[$i]['csd_start']!=NULL /*AND $color=="background-color:#2D882D;color:white"*/){
									//if($result[$i]['sub_ot1']!=null AND $result[$i]['sub_ot2']!=null){
									if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
										$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
										$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7*2);
									}
									//echo $result[$i]['cp_step_1_stop']," - ",$now," <br>";
									$date0 = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
									$date1=date_create($date0);
									$deadline = date_add($date1,date_interval_create_from_date_string(($result[$i]['sub_ot1']-1)." day"));
									$date2=date_create($now);
									//echo date_format($date1,"Y-m-d");
									//echo date_format($date2,"Y-m-d");
									if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
										$diff2 = date_diff($deadline,$datex);
										//echo date_format($deadline,'Y-m-d').' '.date_format($datex,'Y-m-d').' a<br>';
									}else{
										$csd_stop = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
										$csd_stop = date_create($csd_stop);
										$diff2 = date_diff($deadline,$csd_stop);
										//echo date_format($deadline,'Y-m-d').' '.date_format($csd_stop,'Y-m-d').' b<br>';
									}
									$daynd = $diff2->format("%R%a");
									$daynd = intval($daynd);
									//echo 'daynd = ',$daynd,'<br>';
									if($daynd <= 0){
										$color = "background-color:#2D882D!important;color:white!important";
										//echo $color;
										$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
										array_push($green_cp_1,$result[$i]['cp_no']);
										$value_green+=1;
										if($result[$i]['aff_id']==1){
											$g1+=1;
										}elseif($result[$i]['aff_id']==2){
											$g2+=1;
										}elseif($result[$i]['aff_id']==3){
											$g3+=1;
										}else{
											$g4+1;
										}
									}
									elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
										//$color = "background-color:#CEFFAE";
										$color = "background-color:#FFE841!important;color:black!important ";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_yellow+=1;
										array_push($yellow_cp_1,$result[$i]['cp_no']);
										if($result[$i]['aff_id']==1){
											$y1+=1;
										}elseif($result[$i]['aff_id']==2){
											$y2+=1;
										}elseif($result[$i]['aff_id']==3){
											$y3+=1;
										}else{
											$y4+1;
										}
									}
									elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
										$color = "background-color:orange!important;color:black!important";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";			
										$value_orange+=1;
										array_push($orange_cp_1,$result[$i]['cp_no']);
										if($result[$i]['aff_id']==1){
											$o1+=1;
										}elseif($result[$i]['aff_id']==2){
											$o2+=1;
										}elseif($result[$i]['aff_id']==3){
											$o3+=1;
										}else{
											$o4+1;
										}
									}
									else{
										$color = "background-color:red!important;color:white!important";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_red+=1;
										array_push($red_cp_1,$result[$i]['cp_no']);
										if($result[$i]['aff_id']==1){
											$r1+=1;
										}elseif($result[$i]['aff_id']==2){
											$r2+=1;
										}elseif($result[$i]['aff_id']==3){
											$r3+=1;
										}else{
											$r4+1;
										}
										//$result[$i]['cp_note'] = $cp_note;
									}
								}else{
									array_push($white_cp_1,$result[$i]['cp_no']);
									$value_white+=1;
								}
							}
							elseif($result[$i]['cp_step']!=6){
								if($result[$i]['cp_step_1']!=0 AND $result[$i]['csd_start']!=NULL /*AND $color=="background-color:#2D882D;color:white"*/){
									//if($result[$i]['sub_ot1']!=null AND $result[$i]['sub_ot2']!=null){
									if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
										$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
										$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7/2);
									}
									//echo $result[$i]['cp_step_1_stop']," - ",$now," <br>";
									$date0 = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
									$date1=date_create($date0);
									$deadline = date_add($date1,date_interval_create_from_date_string(($result[$i]['sub_ot1']-1)." day"));
									$date2=date_create($now);
									//echo date_format($date1,"Y-m-d");
									//echo date_format($date2,"Y-m-d");
									if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
										$diff2 = date_diff($deadline,$datex);
										//echo date_format($deadline,'Y-m-d').' '.date_format($datex,'Y-m-d').' a<br>';
									}else{
										$csd_stop = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
										$csd_stop = date_create($csd_stop);
										$diff2 = date_diff($deadline,$csd_stop);
										//echo date_format($deadline,'Y-m-d').' '.date_format($csd_stop,'Y-m-d').' b<br>';
									}
										
									//$diff2 = date_diff($deadline,$date2);
									$daynd = $diff2->format("%R%a");
									$daynd = intval($daynd);
									//echo 'daynd = ',$daynd,'<br>';
									if($daynd <= 0){
										$color = "background-color:#2D882D!important;color:white!important";
										$eight = '#2D882D';
										$feight = 'white';
										//echo $color;
										$cp_note = "เหลือเวลา ".($daynd*(-1))." วัน";
										$value_green+=1;
										array_push($green_cp_1,$result[$i]['cp_no']);
										
									}
									elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
										//$color = "background-color:#CEFFAE";
										$color = "background-color:#FFE841!important;color:black!important ";
										$eight = "#FFE841";
										$feight = "black";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_yellow+=1;
										array_push($yellow_cp_1,$result[$i]['cp_no']);
										
									}
									elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
										$color = "background-color:orange!important;color:black!important";
										$eight = "orange";
										$feight = "black";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";	
										$value_orange+=1;
										array_push($orange_cp_1,$result[$i]['cp_no']);										
										
									}
									else{
										$color = "background-color:red!important;color:white!important";
										$eight = "red";
										$feight = "white";
										//$color  = "background: linear-gradient(163deg,red 0%,red 50%,blue 50%,blue 100%);color:white";
										//$color  = "background: linear-gradient(163deg,white 48%,blue 50%,blue 50%,white 52%);color:black";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_red+=1;
										array_push($red_cp_1,$result[$i]['cp_no']);
										
										//$result[$i]['cp_note'] = $cp_note;
									}
								}else{
									array_push($white_cp_1,$result[$i]['cp_no']);
									$value_white+=1;
									
									//$color = "background-color:#FFFFFF!important;color:black!important ";
								}
							}	
							
							if($result[$i]['cp_step']==8){
								if($result[$i]['cp_step_1']==2 OR $result[$i]['cp_step_1']==4 OR $result[$i]['cp_step_1']==5 OR $result[$i]['cp_step_1']==6 OR $result[$i]['cp_step_1']==8){
								//if($eight!="#2D882D" OR $eight!="#FFE841" OR $eight!="orange" OR $eight!="red"){
								if($eight==''){
									$eight = 'transparent';
									$feight = "black";
								}
								$color  = "background: linear-gradient(90deg,#0000ff 0%,#0000ff 25%,".$eight." 25%,".$eight." 100%);color:".$feight;
								//echo $color.'<br>';
								}
							}
						?>
						<td <?php if($result[$i]['cp_step_1']>0){ echo 'onclick="readTimeSnd('.$result[$i]['cp_no'].','.$daynd.')"';}?> style="<?php if($result[$i]['cp_step_1']>0){echo 'cursor: pointer;';}else{}?>text-align:center;<?php echo $color;?>;-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;" title="<?php echo 'ขั้นตอน : '.$result[$i]['sub_name']?>&#10;<?php echo $cp_note;?>">
							<?php
								if($result[$i]['cp_step_1']==0 OR $result[$i]['cp_step_1']==null){
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
									$color_2 ="background-color:red;color:white;";
									$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
								}
							}else{
								$color_2 = "";
								
							}						
						}else{
							$color_2 = "";
						}
						
						
						?>						
						<td <?php if($result[$i]['cp_step_2']>0){ echo 'onclick="readTimeTrd('.$result[$i]['cp_no'].','.$dayrd.')"';}?>  title="<?php if($result[$i]['cp_step']>0 AND $result[$i]['cp_step_1']>0 AND $result[$i]['cp_step_2']>0){ echo 'ขั้นตอน : '.$result[$i]['trd_name'].'&#10;';}?><?= $cp_note_2;?>" style="<?php if($result[$i]['cp_step_2']>0){echo 'cursor: pointer;';}else{}?>text-align:center;<?php echo $color_2;?>;-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;">
							<?php 
							if($result[$i]['cp_step_2']==0){
								echo "-";
							}else{
								echo $result[$i]['cp_step'],'.',$result[$i]['cp_step_1'].'.'.$result[$i]['cp_step_2'];
							}
							
							
							?>
						</td>
						<td style="text-align:center;"><?php echo $warning;?></td>
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
								<a><i class="fa fa-thumb-tack"></i></a>
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
<?php 
}
else{
	echo "<h3 style='font-family: Kanit;text-align: center;margin:15px 0px;'>ไม่มีข้อมูล</h3>";	
}
//echo $_POST['report'];
?>
<script>
function returnSSR(){
	$('#showTable').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
	<?php 
		if($_POST['report']=='tree'){
			echo "$('#aff_ids').val('');$('#pvn_names').val('');$('#cp_names').val('');searchType('".$_SESSION['typeX']."');";
		}elseif($_POST['report']=='park' OR $_POST['report']=='keth'){
			echo 'loadData("false");';
		}elseif($_POST['report']=='province'){
			echo 'loadData2("false");';
		}else{
			echo "searchpvn();";
		}
	?>
	
}
</script>

</div>