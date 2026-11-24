<?php
include '../js/function_db.php';
  session_name('tutorial');
  session_start();
/*
if($_POST['cp_name']!= null OR $_POST['cp_name']!=""){
	$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%'";
}

elseif($_POST['pvn_name']!=null){
	$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
}
elseif($_POST['aff_id']!=null OR $_POST['aff_id']!=""){
	$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."'";
}else{
	$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND  cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no";
}
*/

// test //
$a = "SELECT * FROM tb_user WHERE user_id = '".$_SESSION['intra_czid']."'";
$result = selectSql($a);
if (empty($result) || empty($result[0]['user_status'])) {
    $result = [['user_status' => 9, 'user_affiliation' => '071201', 'user_id' => 'demo-user-1']];
}
if($result[0]['user_affiliation']=='071201'){$x = 1;}
elseif($result[0]['user_affiliation']=='071202'){$x = 2;}
elseif($result[0]['user_affiliation']=='071203'){$x = 3;}
elseif($result[0]['user_affiliation']=='071204'){$x = 4;}
else{ $x=0;}
//echo $a;
	if($result[0]['user_status']==9 OR $x==0){
		if($_POST['cp_name']!= "" AND $_POST['pvn_name']!=""){
			//echo 'a';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%' AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['cp_name']!= null){
			//echo 'b';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%'";
		}
		elseif($_POST['pvn_name']!=null){
			//echo 'c';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['aff_id']!=null OR $_POST['aff_id']!=""){
			//echo 'd';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."'";
		}else{
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND  cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no";
		}
		echo '<script> $("#aff_ids").show()</script>';
	}
	else{
		if($_POST['cp_name']!= "" AND $_POST['pvn_name']!=""){
			//echo 'a';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%' AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['cp_name']!= null){
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE aff_id = '".$x."' AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%'";
		}

		elseif($_POST['pvn_name']!=null){
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE aff_id = '".$x."' AND  cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['aff_id']!=null OR $_POST['aff_id']!=""){
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE aff_id = '".$x."' AND  cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."'";
		}else{
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective WHERE aff_id = '".$x."' AND  cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no";
		}
		
	}

// end //
//echo $sql;
$result = selectSql($sql);
$row  = 20;
$all_data = count($result);
$all_table = ceil($all_data/$row);
?>
<div class="table-responsive" style="border-color:transparent;margin-top:8px">
<?php
	if($all_data > 0){?>
	<table hidden class="table table-bordereds printx">
		<thead>
			<tr style="background-color: #295DBD;color:white">
				<th style="text-align:center">ลำดับ</th>
				<th style="text-align:center">จังหวัด</th>
				<th style="text-align:center">ชื่อผังเมืองรวม</th>
				<th style="text-align:center">ขอบเขต</th>
				<th style="text-align:center">สถานะผัง</th>
				<th style="text-align:center">ขั้นตอน</th>
				<th style="text-align:center">เจ้าของ</th>
				<th style="text-align:center">ผู้ดำเนินการ</th>
				<th style="text-align:center">มีผลบังคับใช้</th>				
			</tr>
		</thead>
		<tbody>
			<?php 
			$z = 0;
			foreach($result as $bow){
				$z++; 
			?>
			<tr>
				<td style="text-align:center"><?= $z;?></td>
				<td style="text-align:center"><?= $bow['pvn_name'];?></td>
				<td style="text-align:center"><?= $bow['cp_name'];?></td>
				<td style="text-align:center"><?= $bow['scope_name'];?></td>
				<td style="text-align:center"><?= $bow['status_name'];?></td>
				<td style="text-align:center"><?= $bow['stage_name'];?></td>
				<td style="text-align:center"><?= $bow['opt_fst_name'];?></td>
				<td style="text-align:center"><?= $bow['opt_snd_name'];?></td>
				<td style="text-align:center"><?= $bow['eff_name'];?></td>	
			</tr>
			<?php } ?>
		</tbody>
	</table>
	


	
	<?php
		for($i = 0 , $table = 1 ; $table <= $all_table ; $table++){ ?>
		<table id="table_<?php echo $table;?>" class="table table-bordered unprintable">
			<thead>
				<tr style="background-color: #295DBD;color:white">
					<td style="text-align:center">ลำดับ</td>
					<td style="text-align:center">จังหวัด</td>
					<td style="text-align:center">ชื่อผังเมืองรวม</td>
					<td style="text-align:center">ขอบเขต</td>
					<td style="text-align:center">สถานะผัง</td>
					<td style="text-align:center">ขั้นตอน</td>
					<td style="text-align:center">เจ้าของ</td>
					<td style="text-align:center">ผู้ดำเนินการ</td>
					<td style="text-align:center">มีผลบังคับใช้</td>
					<td style="text-align:center">จัดการ</td>
					<td style="text-align:center">ขั้นตอน</td>
					<td style="text-align:center">-</td>

					
				</tr>
			</thead>
			<tbody>
				<?php 
					for($i , $j=1 ; $j <=$row ; $j++ , $i++){
						$color = "";
						$cp_note ="";
						if($i == $all_data){
							break;
						}
				?>
				<tr>
					<td style="text-align:center"><?php echo $result[$i]['cp_no'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['pvn_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['cp_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['scope_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['status_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['stage_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_fst_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['eff_name'];?></td>

					<td style="text-align:center">
						<!--<span title="แก้ไข" class="gear" onclick="cpEdit(<?php echo $result[$i]['cp_no'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear access_<?php echo $row['pvn_affiliation'];?>" >  </i>&nbsp;</a></span>-->					
						<span title="แก้ไขข้อมูลผังเมือง" class="gear" onclick="cpEditx(<?php echo $result[$i]['cp_no'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear access_<?php echo $row['pvn_affiliation'];?>" >  </i>&nbsp;</a></span>
						<span title="แก้ไขขั้นตอน" class="step" onclick="cityplan_step(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-list-ul"></i></a></span> 
						<span hidden title="ลบผังเมือง" class="trash" onclick="cpDel(<?php echo $result[$i]['cp_no'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>	
					</td>				
					<td style="text-align:center">								
						<span title="แสดงขั้นตอน" class="list" onclick="openStep(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-list"></i></a></span>
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
								}else{
									$value_white+=1;
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
								}else{
									$value_white+=1;
									$cp_note = 'ไม่ระบุวันที่';
									//$color = "background-color:#FFFFFF!important;color:black!important ";
								}
							}
					
					
					?>									
					<td style="text-align:center;<?= $color; ?>;" title="<?= $cp_note;?>"><?= $result[$i]['cp_step'];?></td>
				</tr>
					<?php } ?>
			</tbody>
		</table><?php
		}
	}else{}
?>
</div>
<?php 
	pagin($row,$all_data);
	if($_POST['cp_name']!= null AND $_POST['pvn_name']!= null){
		$print_search = "(ข้อความที่สืบค้น ".$_POST['cp_name']." , ".$_POST['pvn_name'].")";
	}elseif($_POST['cp_name']!= null){
		$print_search = "(ข้อความที่สืบค้น ".$_POST['cp_name'].")";
	}elseif($_POST['pvn_name']!= null){
		$print_search = "(ข้อความที่สืบค้น ".$_POST['pvn_name'].")";
	}elseif($_POST['aff_id']!= null){
		$print_search = '(ส่วนผังเมืองรวมที่ '.$_POST['aff_id'].")";
	}else{
		$print_search ="";
	}
	
	
?>

<script>
	$('#cp_value').html('<?php echo $all_data;?>');
	$('#print_search').html('<?php echo $print_search;?>')
	
</script>