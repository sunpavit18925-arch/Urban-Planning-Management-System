<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();

//$sql = "SELECT * FROM ref_cityplan_step_detail WHERE csd_cp = '".$_POST['cp_no']."' ORDER BY csd_plan_start DESC ";
$sql = "SELECT * FROM ref_cityplan_step_detail WHERE csd_cp = '".$_POST['cp_no']."' AND csd_status = 1 ORDER BY csd_start DESC,csd_start_2 DESC ";
//echo $sql;
$result = selectSql($sql);
$all_data = count($result);
$row = 20;
$all_table = ceil($all_data/$row);
$current_ad = date('Y-m-d');
$current_ad  = date_create($current_ad);

$date3 = date('Y-m-d');
$date3 = date_create($date3);
								
								
								
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
					<td style="text-align :center" colspan="2"><b>เวลาดำเนินการตามแผน</b></td>
					<td style="text-align :center" colspan="2"><b>เวลาดำเนินการตามผล</b></td>
					<td hidden style="text-align :center" rowspan="2" width="150px"><b>ผลดำเนินการ</b></td>
					<td style="text-align :center" rowspan="2"><b>หมายเหตุ</b></td>
					<?php if($_SESSION['status']==1){ ?>
						<td style="text-align :center" rowspan="2"><b>จัดการ<b></td>
					<?php } ?>
				</tr>
				<tr class="table-header">
					<td style="text-align :center" ><b>ขั้นตอนหลัก</b></td>
					<td style="text-align :center" ><b>ขั้นตอนย่อย 1</b></td>
					<td style="text-align :center" ><b>ขั้นตอนย่อย 2</b></td>
					<td style="text-align :center" ><b>เริ่มต้น</b></td>
					<td style="text-align :center" ><b>สิ้นสุด</b></td>
					<td style="text-align :center" ><b>เริ่มต้น</b></td>
					<td style="text-align :center" ><b>สิ้นสุด</b></td>
				</tr>
			</thead>
			<tbody>	 <?php 
			for($i, $j=1 ; $j<=$row ; $j++ , $i++){ 
				$color = 'white';
				$eight = "";
				$feight = "";
				if($i==$all_data){ break;} ?>
				<tr>
					<td style="text-align:center"><?php echo $i+1; if($result[$i]['csd_seq']==$_POST['cp_csd']){ echo " (ขั้นตอนปัจจุบัน)";}?></td>
					<td style="text-align:center"><b><?php echo $result[$i]['csd_step_fst'];?></b></td>
					<?php 
						$cp_note ="";
						
						
						if($result[$i]['csd_step_fst']==6){
							if($result[$i]['csd_petition']==1){
								$sql = "SELECT (sub_time2*7) as ot1,(sub_time2*7*2) as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['csd_step_fst']."' AND sub_no = '".$result[$i]['csd_step_snd']."'";
							}
							else{
								$sql = "SELECT (sub_time1*7) as ot1,(sub_time1*7*2) as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['csd_step_fst']."' AND sub_no = '".$result[$i]['csd_step_snd']."'";
							}
							$resultx = selectSql($sql);
							$result[$i]['sub_ot1'] = $resultx[0]['ot1'];
							$result[$i]['sub_ot2'] = $resultx[0]['ot2'];
							if($result[$i]['csd_step_snd']>0 AND ($result[$i]['csd_start']!= null OR $result[$i]['csd_start']!='')){
								if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
									$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
									$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7*2);
								}
								$date0 = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
								$date1=date_create($date0);
								$deadline = date_add($date1,date_interval_create_from_date_string(($result[$i]['sub_ot1']-1)." day"));
								$date2=date_create($now);
								if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
									$diff2 = date_diff($deadline,$date3);
								}
								else{
									$csd_stop = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
									$csd_stop = date_create($csd_stop);
									$diff2 = date_diff($deadline,$csd_stop);
									//echo date_format($deadline,'Y-m-d')."...".date_format($csd_stop,'Y-m-d')."<br>";
								}
								$daynd = $diff2->format("%R%a");
								$daynd = intval($daynd);
								//echo date_format($deadline,'Y-m-d ')." - ".date_format($date2,'Y-m-d').' : '.$daynd.'<br>';
								if($daynd <= 0){
									$color = "background-color:#2D882D!important;color:white!important";
									$cp_note = "เหลือเวลา ".($daynd*(-1))." วัน";
									$value_green+=1;
								}
								elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
									$color = "background-color:#FFE841!important;color:black!important ";
									$cp_note = "ล่าช้า ".$daynd." วัน";
									$value_yellow+=1;
								}
								elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
									$color = "background-color:orange!important;color:black!important";
									$cp_note = "ล่าช้า ".$daynd." วัน";			
									$value_orange+=1;
								}
								else{
									$color = "background-color:red!important;color:white!important";
									$cp_note = "ล่าช้า ".$daynd." วัน";
									$value_red+=1;
								}	
								
								
							}else{
								
							}
							
							
							
						}else{
							$sql = "SELECT sub_ot1 as ot1 , sub_ot2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['csd_step_fst']."' AND sub_no = '".$result[$i]['csd_step_snd']."'";
							$resultx = selectSql($sql);
							$result[$i]['sub_ot1'] = $resultx[0]['ot1'];
							$result[$i]['sub_ot2'] = $resultx[0]['ot2'];
							if($result[$i]['csd_step_snd']>0 AND ($result[$i]['csd_start']!= null OR $result[$i]['csd_start']!='')){
								if($result[$i]['sub_ot1']==null AND $result[$i]['sub_ot2']==null){
									$result[$i]['sub_ot1'] = ceil($result[$i]['sub_time']*7);
									$result[$i]['sub_ot2'] = ceil($result[$i]['sub_time']*7*2);
								}
								$date0 = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
								$date1=date_create($date0);
								$deadline = date_add($date1,date_interval_create_from_date_string(($result[$i]['sub_ot1']-1)." day"));
								$date2=date_create($now);
								if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
									$diff2 = date_diff($deadline,$date3);
									//echo date_format($deadline,'Y-m-d').' '.date_format($date3,'Y-m-d').' a<br>';
								}
								else{
									$csd_stop = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
									$csd_stop = date_create($csd_stop);
									$diff2 = date_diff($deadline,$csd_stop);
									//echo date_format($deadline,'Y-m-d')."...".date_format($csd_stop,'Y-m-d')."<br>";
									//echo date_format($deadline,'Y-m-d').' '.date_format($csd_stop,'Y-m-d').' b<br>';
								}
								$daynd = $diff2->format("%R%a");
								$daynd = intval($daynd);
								//echo $daynd.'<br>';
								//echo date_format($date1,'Y-m-d').' '.$result[$i]['sub_ot1'].'<br>';
							
								if($daynd <= 0){
									$color = "background-color:#2D882D!important;color:white!important";
									$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
									$eight = "#2D882D";
									$feight = "white";
									$value_green+=1;
								}
								elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
									$color = "background-color:#FFE841!important;color:black!important ";
									$cp_note = "ล่าช้า ".$daynd." วัน";
									$eight = "#FFE841";
									$feight = "black";
									$value_yellow+=1;
								}
								elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
									$color = "background-color:orange!important;color:black!important";
									$cp_note = "ล่าช้า ".$daynd." วัน";			
									$eight = "orange";
									$feight = "black";
									$value_orange+=1;
								}
								else{
									$color = "background-color:red!important;color:white!important";
									$cp_note = "ล่าช้า ".$daynd." วัน";
									$eight = "red";
									$feight = "white";
									$value_red+=1;
								}	
								if($result[$i]['csd_step_fst']==8){
									if($result[$i]['csd_step_snd']==2 OR $result[$i]['csd_step_snd']==4 OR $result[$i]['csd_step_snd']==5 OR $result[$i]['csd_step_snd']==6 OR $result[$i]['csd_step_snd']==8){
										if($eight==''){
											$eight = 'transparent';
											$feight = "black";
										}
										$color  = "background: linear-gradient(90deg,#0000ff 0%,#0000ff 25%,".$eight." 25%,".$eight." 100%)!important;color:".$feight.'!important';
									}
								}
								
								
							}else{
								
							}
							
							
							
						}
					
					
					?>
					<td style="text-align:center;<?php echo $color;?>;" title="<?= $cp_note;?>"><?php if($result[$i]['csd_step_snd']!=0 AND $result[$i]['csd_step_snd'] != "" AND $result[$i]['csd_step_snd']!=NULL){echo $result[$i]['csd_step_fst'].'.'.$result[$i]['csd_step_snd'];}else{echo '-';}?></td>
					<?php 
						$trd = '';
						$trd_ot1 = '';
						$trd_ot2 = '';
						if($result[$i]['csd_step_snd']>0){
							$sql = "SELECT CONCAT('ขั้นตอนย่อยที่ ',trd_fst,'.',trd_snd,'.',trd_step,' ',trd_name) AS trd , trd_ot1 ,trd_ot2 FROM ref_up_trd_step WHERE trd_fst = '".$result[$i]['csd_step_fst']."' AND trd_snd = '".$result[$i]['csd_step_snd']."' AND trd_step = '".$result[$i]['csd_step_trd']."'";
							$result_step_2 = selectSql($sql);
							$trd = $result_step_2[0]['trd'];
							$trd_ot1 = $result_step_2[0]['trd_ot1'];
							$trd_ot2 = $result_step_2[0]['trd_ot2'];
							
							if($result[$i]['csd_start_2']!='' AND $result[$i]['csd_start_2']!=null){
								$dead_linex = dead_line($result[$i]['csd_start_2'],$trd_ot1,'th');
								//echo date_format($dead_linex,'Y-m-d').'<br>';
								
								//$diff3 = date_diff($dead_linex,$date3);
								if($result[$i]['csd_stop_2']=='' OR $result[$i]['csd_stop_2']==' ' OR $result[$i]['csd_stop_2']==null){
									$diff3 = date_diff($dead_linex,$date3);
								}
								else{
									$csd_stop_2 = (substr($result[$i]['csd_stop_2'],0,4)-543).substr($result[$i]['csd_stop_2'],4);
									$csd_stop_2 = date_create($csd_stop_2);
									$diff3 = date_diff($dead_linex,$csd_stop_2);
									//echo date_format($deadline,'Y-m-d')."...".date_format($csd_stop,'Y-m-d')."<br>";
								}
								
								$dayrd = $diff3->format('%R%a');
								$dayrd = intval($dayrd);
								//echo $dayrd;
								if($dayrd <= 0){
									$color_2 = "background:#2D882D;color:white";
									$trd_title="เหลือเวลา ".($dayrd*-1)." วัน";
									
								}
								elseif($dayrd <= $trd_ot1){
									$color_2 = "background:#FFE841;color:black";
									$trd_title="ล่าช้า ".$dayrd." วัน";
								}
								elseif($dayrd <= $trd_ot2){
									$color_2 = "background:orange;color:black";
									$trd_title="ล่าช้า ".$dayrd." วัน";
								}
								else{
									$color_2 = "background:red;color:white";
									$trd_title="ล่าช้า ".$dayrd." วัน";
								}
							}else{
								$color_2 = "";
								$trd_title="";
							}
						}else{
							$color_2 = '';
							$trd_title = "";
						}
						
						
													
							
						
					?>
					<td style="text-align:center;<?php echo $color_2;?>" title="<?= $trd = $result_step_2[0]['trd'].'&#10;'.$trd_title;?>">
					<?php 
						if($result[$i]['csd_step_trd']!=0 AND $result[$i]['csd_step_trd'] != "" AND $result[$i]['csd_step_trd']!=NULL){
							echo $result[$i]['csd_step_fst'].'.'.$result[$i]['csd_step_snd'].'.'.$result[$i]['csd_step_trd'];
						}
						else{
							echo '-';
					}?>
					</td>
					<?php 
					$csd_plan_startx = '';
					$csd_plan_stopx = '';
					
					$csd_startx = '';
					$csd_stopx = '';
					
						if($result[$i]['csd_step_fst']!=0){
							if($result[$i]['csd_step_snd']!=0){
								if($result[$i]['csd_step_trd']!=0){
									$csd_plan_startx = $result[$i]['csd_plan_trd_start'];
									$csd_plan_stopx = $result[$i]['csd_plan_trd_stop'];
									
									$csd_startx = $result[$i]['csd_start_2']."...";
									$csd_stopx = $result[$i]['csd_stop_2']."...";
									
								}else{
									$csd_plan_startx = $result[$i]['csd_plan_snd_start'];
									$csd_plan_stopx = $result[$i]['csd_plan_snd_stop'];
									
									$csd_startx = $result[$i]['csd_start'];
									$csd_stopx = $result[$i]['csd_stop'];
								}
							}else{
								$csd_plan_startx = $result[$i]['csd_plan_fst_start'];
								$csd_plan_stopx = $result[$i]['csd_plan_fst_stop'];
								
								$csd_startx = '-';
								$csd_stopx = '-';
							}
							
						}else{
							$csd_plan_startx = '-';
							$csd_plan_stopx = '-';
							$csd_startx = '-';
							$csd_stopx = '-';
						}
						if($csd_plan_startx == '' OR $csd_plan_startx ==' ' OR $csd_plan_startx ==null)	{ $csd_plan_startx = '-';}
						if($csd_plan_stopx == '' OR $csd_plan_stopx ==' ' OR $csd_plan_stopx ==null)	{ $csd_plan_stopx = '-';}
					
					?>
					<td style="text-align:center"><?php echo $csd_plan_startx;?></td>
					<td style="text-align:center"><?php echo $csd_plan_stopx;?></td>
					
			<td style="text-align:center"><?php if($csd_startx!=''){echo $csd_startx;}else{echo '-';}/*if($result[$i]['csd_start']!=null){echo $result[$i]['csd_start'];}else{echo '-';}*/?></td>
					<td style="text-align:center"><?php if($csd_stopx!=''){echo $csd_stopx;}else{echo '-';}/*if($result[$i]['csd_stop']!=null){echo $result[$i]['csd_stop'];}else{echo '-';}*/?></td>
					<?php 
					//
					if($result[$i]['csd_step_fst']==0 OR $result[$i]['csd_step_fst'] == "" OR $result[$i]['csd_step_fst']==null){
						$day = "-";
						$ot1 = 0; 
						$ot2 = 0;
						$color = "transparent";
						$font = "black";
						$text = "ไม่มีการดำเนินการ";
						$title = "ไม่มีการดำเนินการ";
					}else{
						$ot1 = 0; 
						$ot2 = 0;
						if($result[$i]['csd_plan_snd_stop']!='' OR $result[$i]['csd_plan_snd_stop']!=null){
							$year_be = substr($result[$i]['csd_plan_snd_stop'],0,4);
							$year_ad = $year_be-543;
							$date_ad = $year_ad.substr($result[$i]['csd_plan_snd_stop'],4);
							$date_ad_x = date_create($date_ad);						
							$diff = date_diff($date_ad_x,$current_ad);
							$day =  $diff->format("%R%a");						
							$day = intval($day);
							
							if($result[$i]['csd_step_fst']!=6){
								$sql = "SELECT sub_ot1 as ot1 , sub_ot2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['csd_step_fst']."' AND sub_no = '".$result[$i]['csd_step_snd']."'";
								$sub_sql = selectSql($sql);
								$ot1 = $sub_sql[0]['ot1'];
								$ot2 = $sub_sql[0]['ot2'];
							}else{
								if($result[$i]['csd_petition']==0){
									$sql = "SELECT sub_time1*7 as ot1 , sub_time1*7*2 as ot2 FROM ref_up_snd_step WHERE sub_step = 6 AND sub_no = '".$result[$i]['csd_step_snd']."'";									
								}else{
									$sql = "SELECT sub_time2*7 as ot1 , sub_time2*7*2 as ot2 FROM ref_up_snd_step WHERE sub_step = 6 AND sub_no = '".$result[$i]['csd_step_snd']."'";
								}
								$sub_sql = selectSql($sql);
								$ot1 = $sub_sql[0]['ot1'];
								$ot2 = $sub_sql[0]['ot2'];
							}
							$text = "อยู่ระหว่างดำเนินการ"; 
							if($day <= 0){
								//$text = "อยู่ระหว่งาดำเนินการ";
								$color = '#2D882D';
								$font = "white";
								$title = 'เหลือเวลา '.($day*(-1)).' วัน';
							}elseif($day > 0 AND $day <= $ot1){
								//$text = "ล่าช้าเล็กน้อย";
								$color = '#FFE841';
								$font = "black";
								$title = 'ล่าช้า '.$day.' วัน';
								
							}elseif($day > $ot1 AND $day <= $ot2){
								//$text = "ล่าช้าปานกลาง";
								$color = 'orange';
								$font = "black";
								$title = 'ล่าช้า '.$day.' วัน';
							}else{
								//$text = "ล่าช้าอย่างมาก";
								$color = 'red';
								$font = "white";
								$title = 'ล่าช้า '.$day.' วัน';
							}
							//$text = date_format($date_ad_x,'Y-m-d').' <br> '.date_format($current_ad,'Y-m-d');
							
						}else{
							if($result[0]['csd_step_fst']!=0 AND ($result[$i]['csd_step_snd']==0 OR $result[$i]['csd_step_snd']=='' OR $result[$i]['csd_step_snd']==' ' OR $result[$i]['csd_step_snd']==null)){
								$text = "-";
								$title = "ไม่มีการดำเนินการในขั้นตอนรอง";
								$color = "transparent";
								$font = "black";
							}else{
								$text = "-";
								$title = "ไม่ระบุวันที่";
								$color = "transparent";
								$font = "black";
							}
						}
						
						if($result[$i]['csd_stop']!=''){
							$csd_stop_ad  = (substr($result[$i]['csd_stop'],0,4)-543).(substr($result[$i]['csd_stop'],4));
							$csd_stop_ad = date_create($csd_stop_ad);
							$checkdate = date_diff($csd_stop_ad,$current_ad);
							$checkdate =  $checkdate->format("%R%a");						
							$checkdate = intval($checkdate);
							if($checkdate <= 0 ){
								$text = "อยู่ระหว่างดำเนินการ";
							}else{
								$text = "ดำเนินการเสร็จสิ้น";
								$title = "";
								$color = "transparent";
								$font = "black";
							}
							
							
							//$text = date_format($csd_stop_ad,'Y-m-d');
							//$text = gettype($csd_stop_ad);
							//$text = "ดำเนินการเส็จสิ้น";
						}
					}
					?>
					<td hidden title="<?php echo $title; ?>" style="text-align :center;<?php //echo 'color:'.$font.';background-color:'.$color;?>"><?php echo $text;//.'<br>'.$ot1.'-'.$ot2;?></td>
					<td style="text-align :center" >
						<?php
						if(TRUE){
						//if($result[$i]['csd_step_fst']==0 OR ($result[$i]['csd_step_fst']!=0 AND $result[$i]['csd_step_snd']==0)){
							if($result[$i]['csd_note']==0) {
								if($_SESSION['status']==1){ ?>
									<span class="plus"><a onclick="addNote(<?php echo $result[$i]['csd_step_fst'];?>,<?php echo $result[$i]['csd_step_snd'];?>,<?php echo $result[$i]['csd_step_trd'];?>,<?php echo $result[$i]['csd_cp'];?>,<?php echo $result[$i]['csd_seq'];?>)"><i class="fa fa-plus"></i></a></span>
								<?php 
								}else{
									echo '-';
								}
							}else{?>
								<span class="list-alt"><a onclick="readNote(<?php echo $result[$i]['csd_step_fst'];?>,<?php echo $result[$i]['csd_step_snd'];?>,<?php echo $result[$i]['csd_step_trd'];?>,<?php echo $result[$i]['csd_cp'];?>,<?php echo $result[$i]['csd_seq'];?> )"><i class="fa fa-list-alt"></i></a></span>
							<?php
							}
						}else{
							echo '-';
						}?>
					</td>
					<?php if($_SESSION['status']==1){ ?>
					<td style="text-align:center">
						<?php if($_SESSION['data_access']==2 ){ ?>
							<span title="อัปเดตคขั้นตอน" class="gear"><a onclick="readCsd(<?php echo$result[$i]['csd_seq']; ?>)"><i class="fa fa-gear"></i></a></span>
						<?php }elseif($_SESSION['data_access']==9){ ?>
							<span title="อัปเดตคขั้นตอน" class="gear"><a onclick="readCsd(<?php echo$result[$i]['csd_seq']; ?>)"><i class="fa fa-gear"></i></a></span>
							<?php if($result[$i]['csd_seq']!=$_POST['cp_csd']){ ?> 
								<span title="ลบขั้นตอน" style="margin:0px 0px 0px 5px" class="trash"><a onclick="delCsd(<?php echo $result[$i]['csd_seq'];?>)"><i class="fa fa-trash"></i></a></span>
								<span title="ใช้เป็นขั้นตอนปัจจุบัน" style="margin: 0 0 0 5px" class="bookmark"><a onclick="setCurrent(<?php echo $result[$i]['csd_seq'];?>)"><i class="fa fa-bookmark"></i></a></span>
							<?php } ?>
						<?php } ?>
					</td>
					<?php } ?>
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
	echo '<h1 class="hidden-xs hidden-sm" style="text-align:center ;font-family:Kanit; margin-top:150px">ไม่มีข้อมูล</h1>';
	echo '<h2 class="hidden-lg hidden-md" style="text-align:center ;font-family:Kanit; margin-top:50px">ไม่มีข้อมูล</h2>';
	echo '</div>';
}