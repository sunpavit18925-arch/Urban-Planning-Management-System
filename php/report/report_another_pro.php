<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
if($_POST['consider']==0){
	$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status , ref_affiliation WHERE cp_consider = 0 AND (cp_status = '".$_POST['status']."' AND cp_stage = '".$_POST['stage']."' ) AND (cp_step = 0 OR (cp_step != 0 AND cp_step = 0) OR cp_consider != 0 OR ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))) AND (cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND pvn_affiliation = aff_id)";
}else{
	$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status , ref_affiliation WHERE cp_consider != 0 AND (cp_status = '".$_POST['status']."' AND cp_stage = '".$_POST['stage']."' ) AND (cp_step = 0 OR (cp_step != 0 AND cp_step = 0) OR cp_consider != 0 OR ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))) AND (cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND pvn_affiliation = aff_id)";

}
$result = selectSql($sql);
//echo $sql;
?>
<div class="table-responsive" style="border-color:transparent;margin-top:8px">
		<?php 
		$result = selectSql($sql);
		$row = 50;
		$all_data = count($result);
		$all_table = ceil($all_data/$row);
		if($all_data > 0){
		?>
			<table hidden id="table_print" class="table table-bordereds printx" style="border-width:1px;border-style:solid;border-color:black">
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center" rowspan="2">ลำดับ </td>
						<td style="text-align:center" rowspan="2">จังหวัด</td>
						<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
						<td style="text-align:center" colspan="2" rowspan="2">ผู้ดำเนินการ</td>
						<td style="text-align:center" rowspan="2">สถานะผัง</td>
						<td style="text-align:center" colspan="2">ขั้นตอนดำเนิการ</td>
						<td style="text-align:center" rowspan="2">ผลดำเนินการ</td>
						
					</tr>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center">ขั้นตอนหลัก</td>
						<td style="text-align:center">ขั้นตอนย่อย</td>
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
				?>
					<tr >
						<td style="text-align:center"><?php echo $i+1;?></td>
						<td style="text-align:center"><?php echo $result[$i]['pvn_name'];?></td>
						<td style="text-align:center"><?php echo $result[$i]['cp_name'];?></td>
						<td style="text-align:center"><?php echo $result[$i]['opt_fst_name'];?></td>
						<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
						<td style="text-align:center"><?php echo $result[$i]['status_name'];?></td>
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
								
								if($result[$i]['cp_step_1']!=0 AND $result[$i]['cp_step_1_stop']!=NULL /*AND $color=="background-color:#2D882D;color:white"*/){
									//if($result[$i]['sub_ot1']!=null AND $result[$i]['sub_ot2']!=null){
									if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
										$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
										$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7/2);
									}
									//echo $result[$i]['cp_step_1_stop']," - ",$now," <br>";
									$date1=date_create($result[$i]['cp_step_1_stop']);
									$date2=date_create($now);
									//echo date_format($date1,"Y-m-d");
									//echo date_format($date2,"Y-m-d");
									$diff2 = date_diff($date1,$date2);
									$daynd = $diff2->format("%R%a");
									$daynd = intval($daynd);
									//echo 'daynd = ',$daynd,'<br>';
									if($daynd <= 0){
										$color = "background-color:#2D882D!important;color:white!important";
										//echo $color;
										$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
										$value_green+=1;
									}
									elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
										//$color = "background-color:#CEFFAE";
										$color = "background-color:#FFE841!important;color:black!important ";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_yellow+=1;
									}
									elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
										$color = "background-color:orange!important;color:black!important";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";			
										$value_orange+=1;
									}
									else{
										$color = "background-color:red!important;color:white!important";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_red+=1;
										//$result[$i]['cp_note'] = $cp_note;
									}
								}
							}
							elseif($result[$i]['cp_step']!=6){
								if($result[$i]['cp_step_1']!=0 AND $result[$i]['cp_step_1_stop']!=NULL /*AND $color=="background-color:#2D882D;color:white"*/){
									//if($result[$i]['sub_ot1']!=null AND $result[$i]['sub_ot2']!=null){
									if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
										$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
										$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7/2);
									}
									//echo $result[$i]['cp_step_1_stop']," - ",$now," <br>";
									$date1=date_create($result[$i]['cp_step_1_stop']);
									$date2=date_create($now);
									//echo date_format($date1,"Y-m-d");
									//echo date_format($date2,"Y-m-d");
									$diff2 = date_diff($date1,$date2);
									$daynd = $diff2->format("%R%a");
									$daynd = intval($daynd);
									//echo 'daynd = ',$daynd,'<br>';
									if($daynd <= 0){
										$color = "background-color:#2D882D!important;color:white!important";
										//echo $color;
										$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
										$value_green+=1;
									}
									elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
										//$color = "background-color:#CEFFAE";
										$color = "background-color:#FFE841!important;color:black!important ";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_yellow+=1;
									}
									elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
										$color = "background-color:orange!important;color:black!important";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";	
										$value_orange+=1;									
									}
									else{
										$color = "background-color:red!important;color:white!important";
										//$color  = "background: linear-gradient(163deg,red 0%,red 50%,blue 50%,blue 100%);color:white";
										//$color  = "background: linear-gradient(163deg,white 48%,blue 50%,blue 50%,white 52%);color:black";
										//echo $color;
										$cp_note = "ล่าช้า ".$daynd." วัน";
										$value_red+=1;
										//$result[$i]['cp_note'] = $cp_note;
									}
								}
							}	
							/*else{
								
								
								
							}*/
							
							
							 /*$color  = "background: linear-gradient(163deg,red 0%,red 50%,green 50%,green 100%);"; //<------------------ [td 2 color] */
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
						<td style="text-align:center;"><?php //echo $result[$i]['sub_ot1'].'----'.$result[$i]['sub_ot2'];$cp_note;?></td>
					</tr> 
					<?php } ?>
				</tbody>	
			</table>
		
			<?php
			for($i=0,$t=1 ; $t<= $all_table ; $t++ ){ ?>
				<table id="table_<?= $t;?>" class="tablex table-bordereds unprintable" style="border-width:1px;border-style:solid;border-color:black">
					<thead>
						<tr style="background-color: #0AAAFA;color:white">
							<td style="text-align:center" rowspan="2">ลำดับ </td>
							<td style="text-align:center" rowspan="2">จังหวัด</td>
							<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
							<td style="text-align:center" colspan="2" rowspan="2">ผู้ดำเนินการ</td>
							<td style="text-align:center" rowspan="2">สถานะผัง</td>
							<td style="text-align:center" colspan="2">ขั้นตอนดำเนิการ</td>
							<td style="text-align:center" rowspan="2">ผลดำเนินการ</td>
							<td class="unprintable" style="text-align:center" rowspan="2">หมายเหตุ</td>
							<!--<td style="text-align:center">ขอบเขต</td>
							<td style="text-align:center">สถานะผัง</td>
							<td style="text-align:center">ขั้นตอน</td>
							<td style="text-align:center">เจ้าของ</td>
							<td style="text-align:center">ผู้ดำเนินการ</td>
							<td style="text-align:center">มีผลบังคับใช้</td>
							<td style="text-align:center">--</td>-->
						</tr>
						<tr style="background-color: #0AAAFA;color:white">
							<td style="text-align:center">ขั้นตอนหลัก</td>
							<td style="text-align:center">ขั้นตอนย่อย</td>
						</tr>
					</thead>
					<tbody style="background-color:white">
					<?php 
						for($i,$j=0 ; $j<$row ; $i++,$j++){
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
					?>
						<tr >
							<td style="text-align:center"><?php echo $i+1;?></td>
							<td style="text-align:center"><?php echo $result[$i]['pvn_name'];?></td>
							<td style="text-align:center"><?php echo $result[$i]['cp_name'];?></td>
							<td style="text-align:center"><?php echo $result[$i]['opt_fst_name'];?></td>
							<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
							<td style="text-align:center"><?php echo $result[$i]['status_name'];?></td>
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
									
									if($result[$i]['cp_step_1']!=0 AND $result[$i]['cp_step_1_stop']!=NULL /*AND $color=="background-color:#2D882D;color:white"*/){
										//if($result[$i]['sub_ot1']!=null AND $result[$i]['sub_ot2']!=null){
										if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
											$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
											$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7/2);
										}
										//echo $result[$i]['cp_step_1_stop']," - ",$now," <br>";
										$date1=date_create($result[$i]['cp_step_1_stop']);
										$date2=date_create($now);
										//echo date_format($date1,"Y-m-d");
										//echo date_format($date2,"Y-m-d");
										$diff2 = date_diff($date1,$date2);
										$daynd = $diff2->format("%R%a");
										$daynd = intval($daynd);
										//echo 'daynd = ',$daynd,'<br>';
										if($daynd <= 0){
											$color = "background-color:#2D882D!important;color:white!important";
											//echo $color;
											$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
											$value_green+=1;
										}
										elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
											//$color = "background-color:#CEFFAE";
											$color = "background-color:#FFE841!important;color:black!important ";
											//echo $color;
											$cp_note = "ล่าช้า ".$daynd." วัน";
											$value_yellow+=1;
										}
										elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
											$color = "background-color:orange!important;color:black!important";
											//echo $color;
											$cp_note = "ล่าช้า ".$daynd." วัน";			
											$value_orange+=1;
										}
										else{
											$color = "background-color:red!important;color:white!important";
											//echo $color;
											$cp_note = "ล่าช้า ".$daynd." วัน";
											$value_red+=1;
											//$result[$i]['cp_note'] = $cp_note;
										}
									}
								}
								elseif($result[$i]['cp_step']!=6){
									if($result[$i]['cp_step_1']!=0 AND $result[$i]['cp_step_1_stop']!=NULL /*AND $color=="background-color:#2D882D;color:white"*/){
										//if($result[$i]['sub_ot1']!=null AND $result[$i]['sub_ot2']!=null){
										if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
											$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
											$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7/2);
										}
										//echo $result[$i]['cp_step_1_stop']," - ",$now," <br>";
										$date1=date_create($result[$i]['cp_step_1_stop']);
										$date2=date_create($now);
										//echo date_format($date1,"Y-m-d");
										//echo date_format($date2,"Y-m-d");
										$diff2 = date_diff($date1,$date2);
										$daynd = $diff2->format("%R%a");
										$daynd = intval($daynd);
										//echo 'daynd = ',$daynd,'<br>';
										if($daynd <= 0){
											$color = "background-color:#2D882D!important;color:white!important";
											//echo $color;
											$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
											$value_green+=1;
										}
										elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
											//$color = "background-color:#CEFFAE";
											$color = "background-color:#FFE841!important;color:black!important ";
											//echo $color;
											$cp_note = "ล่าช้า ".$daynd." วัน";
											$value_yellow+=1;
										}
										elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
											$color = "background-color:orange!important;color:black!important";
											//echo $color;
											$cp_note = "ล่าช้า ".$daynd." วัน";	
											$value_orange+=1;									
										}
										else{
											$color = "background-color:red!important;color:white!important";
											//$color  = "background: linear-gradient(163deg,red 0%,red 50%,blue 50%,blue 100%);color:white";
											//$color  = "background: linear-gradient(163deg,white 48%,blue 50%,blue 50%,white 52%);color:black";
											//echo $color;
											$cp_note = "ล่าช้า ".$daynd." วัน";
											$value_red+=1;
											//$result[$i]['cp_note'] = $cp_note;
										}
									}
								}	
								/*else{
									
									
									
								}*/
								
								
								 /*$color  = "background: linear-gradient(163deg,red 0%,red 50%,green 50%,green 100%);"; //<------------------ [td 2 color] */
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
							<td style="text-align:center;"><?php //echo $result[$i]['sub_ot1'].'----'.$result[$i]['sub_ot2'];$cp_note;?></td>
							<td style="text-align:center;color:black" class="unprintable"> 
								<span hidden id="loading_<?php echo $result[$i]['cp_no'];?>"><i class="fa fa-refresh fa-spin"></i></span>			
								<span title="แสดงขั้นตอน" class="step" onclick="XopenStep(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-list"></i></a></span>
								<span hidden id="reader_<?php echo $result[$i]['cp_no'];?>" title="แสดงรายละเอียด" class="reorder"><a onclick="Readstepnote(<?php echo $result[$i]['cp_no']; ?>);"><i class="fa fa-reorder"></i></a></span> 
								<span hidden id="loading_<?php echo $result[$i]['cp_no'];?>"><i class="fa fa-refresh fa-spin"></i></span>
							</td> 
						</tr> 
						<?php } ?>
					</tbody>	
				</table>
		<?php 
			} 
		}
		pagin($row,$all_data)
	?>
	</div>