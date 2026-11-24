<?php
include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  
   $date = date('Y-m-d');
	
	$todays = date("Y");
	$toYear = date("Y");
	$toDayMonth = date("-m-d");
	$now = $toYear.$toDayMonth;
	$current = date_create($now);
	$value_white = 0;
	$value_green = 0;
	$value_yellow = 0;	
	$value_orange = 0;
	$value_red = 0;

	$value_white_2 = 0;
	$value_green_2 = 0;
	$value_yellow_2 = 0;
	$value_orange_2 = 0;
	$value_red_2 = 0;



	/* ---------------------------*/
	$a_xs = '';
	$a_xs = array();
	
	$a_xs_1 = '';
	$a_xs_1 = array();
	
	$a_xs_2 = '';
	$a_xs_2 = array();
	
	/* ---------------------------*/
	$white_cp_1 = '';
	$white_cp_1 = array();
	
	$green_cp_1 = '';	
	$green_cp_1 = array();
	
	$yellow_cp_1 = '';
	$yellow_cp_1 = array();
	
	$orange_cp_1 = '';
	$orange_cp_1 = array();
	
	$red_cp_1 = '';
	$red_cp_1 = array();
	/* ---------------------------*/
	$white_cp_2 = '';
	$white_cp_2 = array();
	
	$green_cp_2 = '';
	$green_cp_2 = array();
	
	$yellow_cp_2 = '';
	$yellow_cp_2 = array();
	
	$orange_cp_2 = '';
	$orange_cp_2 = array();
	
	$red_cp_2 = '';
	$red_cp_2 = array();
	/* --------------------------*/
	
	//---------------------- colorbar status :  new table -----------------------//
	$a_white_step = '';
	$a_white_step = array();
	
	$a_white_step_1 = '';
	$a_white_step_1 = array();
	
	$a_white_step_2 = '';
	$a_white_step_2 = array();
	
	$a_white_date_1 = '';
	$a_white_date_1 = array();
	
	$a_white_date_2 = '';
	$a_white_date_2 = array();
	
	$a_external_1 = '';
	$a_external_1 = array();
	
	$a_external_2 = '';
	$a_external_2 = array();
	
	$a_green_1 = '';
	$a_green_1 = array();
	
	$a_green_2 = '';
	$a_green_2 = array();
	
	$a_yellow_1 = '';
	$a_yellow_1 = array();
	
	$a_yellow_2 = '';
	$a_yellow_2 = array();
	
	$a_orange_1 = '';
	$a_orange_1 = array();
	
	$a_orange_2 = '';
	$a_orange_2 = array();
	
	$a_red_1 = '';
	$a_red_1 = array();
	
	$a_red_2 = '';
	$a_red_2 = array();
	///----------------------------------------------------------------------------------//

	
		$select = "*";
		$from = "tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_scope , ref_status , ref_up_fst_step , ref_up_snd_step , ref_cityplan_step_detail";
		$join_tb = "cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_scope = scope_id AND cp_status = status_id AND cp_step = step_id AND step_id = sub_step AND cp_step_1 = sub_no AND cp_csd = csd_seq";
		if(FALSE){$order = "pvn_asc ASC ";}else{$order = "pvn_asc ASC ";}
		
		$cond1 = "";
		if($_POST['sub_type']=='park'){
			$cond1 = "pvn_park = '".$_POST['park']."'";
		}
		elseif($_POST['sub_type']=='keth'){
			$cond1 = "pvn_keth = '".$_POST['keth']."'";
		}
		else{
			$cond1 = "pvn_id = '".$_POST['pvn']."'";
		}
		

		if($_POST['stype']=='aff' OR $_POST['stype']=='pvn' OR $_POST['stype']=='cp'){
			/*if($_POST['cp_name']!='' AND $_POST['pvn_name']!=''){
				$cond2 = "cp_name LIKE '%".$_POST['cp_name']."%' AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
				$_SESSION['pvn_name'] = $_POST['pvn_name'];
				$_SESSION['cp_name'] = $_POST['cp_name'];
				//$print_search = $_POST['pvn_name']." , ".$_POST['cp_name'];
				//echo $_POST['cp_name'].'<br>';
				//echo $_POST['pvn_name'];
			}*/
			/*if($_POST['aff_id']!=''){
				$cond2 = "pvn_affiliation = '".$_POST['aff_id']."'";
				$_SESSION['aff_id'] = $_POST['aff_id'];
				unset($_SESSION['pvn_name']);
				unset($_SESSION['cp_name']);
			}
			elseif($_POST['cp_name']!=''){
				$cond2 = "cp_name LIKE '%".$_POST['cp_name']."%'";
				$_SESSION['cp_name'] = $_POST['cp_name'];
				unset($_SESSION['aff_id']);
				unset($_SESSION['pvn_name']);
				//$print_search = $_POST['cp_name'];
				//echo $_POST['cp_name'];
			}
			elseif($_POST['pvn_name']!=''){
				$cond2 = "pvn_name LIKE '%".$_POST['pvn_name']."%'";
				$_SESSION['pvn_name'] = $_POST['pvn_name'];
				unset($_SESSION['aff_id']);
				unset($_SESSION['cp_name']);
				//$print_search = $_POST['pvn_name'];
				//echo $_POST['pvn_name'];
			}*/
			if($_POST['stype']=='aff'){
				$cond2 = 'pvn_affiliation = "'.$_POST['data'].'"';
			}elseif($_POST['stype']=='pvn'){
				$cond2 = 'pvn_name LIKE "%'.$_POST['data'].'%"';
			}else{
				$cond2 = 'cp_name LIKE "%'.$_POST['data'].'%"';
			}
			
		}else{
			$cond2 = "";
		}
		
		if($cond2 == ""){
			//$sql = "SELECT ".$select." FROM ".$from." WHERE (".$join_tb.") AND ".$cond1." ORDER BY ".$order;
			$sql = "SELECT ".$select." FROM ".$from." WHERE (".$join_tb.") AND ".$cond1;
			$_SESSION['sql'] = $sql;
			//echo "111";
		}else{
			//$sql = "SELECT ".$select." FROM ".$from." WHERE (".$join_tb.") AND ".$cond1." AND ".$cond2." ORDER BY ".$order;
			$sql = "SELECT ".$select." FROM ".$from." WHERE (".$join_tb.") AND ".$cond1." AND ".$cond2;
			$_SESSION['sql2'] = $sql;
			//echo "222";
		}
		if(isset($_SESSION['sql1'])){
			$_SESSION['sql'] = $_SESSION['sql1'];
		}
			
//echo $sql;
$result = selectSql($sql);
$bow  = 20;
$all_data = count($result);
$all_table = ceil($all_data/$bow);
$lating = '';
$lating = array();

?>
<?php if($_POST['stype']!=''){ ?>
<div style="margin-bottom:0px;display: flex;align-items: flex-end;justify-content: space-between;">
		<h5 style="font-family:Kanit;float:left;margin-bottom:-10px;margin-top:20px"></h5>
<button class="btn btn-primary" style="font-family:Kanit;float:right;cursor:pointer;margin-bottom:-6px;color:white" onclick="<?php if($_POST['sub_type']=='park' OR $_POST['sub_type']=='keth'){echo 'loadData();';}else{echo 'loadData2();';}?>">แสดงจำนวนผังทั้งหมด</button>
</div>
<?php } ?>
<div id="showTable" class="table-responsive" style="border-color:transparent;margin-top:8px">
<?php
	if($all_data>0){
		for($i = 0 , $table = 1 ; $table <= $all_table ; $table++){ ?>
		<table id="table_<?php echo $table;?>" class="table table-bordereds unprintable">
			<thead>
				<tr style="background-color: #295DBD;color:white">
					<td style="text-align:center" rowspan="2">ลำดับ</td>
					<td style="text-align:center" rowspan="2">จังหวัด</td>
					<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
					<td style="text-align:center" rowspan="2" colspan="2">ผู้ดำเนินการ</td>
					<td style="text-align:center" rowspan="2">ขอบเขต</td>
					<td style="text-align:center" rowspan="2">สถานะผัง</td>
					<td style="text-align:center" rowspan="2">ขั้นตอน</td>
					<?php if($_POST['typeX']=='eva' OR $_POST['typeX']=='eva5' OR $_POST['typeX']=='eva10'){}else{?>
					<td style="text-align:center" colspan="3">ขั้นตอนดำเนินการ</td>
					<td style="text-align:center" rowspan="2">ผลดำเนินการ</td>
					<?php } ?>
					<td style="text-align:center" rowspan="2" colspan="2">ระบุหมายเหตุการดำเนินการ</td>
				</tr>
				<tr>
					<?php if($_POST['typeX']=='eva' OR $_POST['typeX']=='eva5' OR $_POST['typeX']=='eva10'){}else{?>
					<td style="text-align:center">ขั้นตอนหลัก</td>
					<td style="text-align:center">ขั้นตอนย่อย 1</td>
					<td style="text-align:center">ขั้นตอนย่อย 2</td>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php 				
					for($i , $j=1 ; $j <=$bow ; $j++ , $i++){
						if($i == $all_data){
							break;
						}
						$daynd =0;
						$color="";
						$color_2 = "";
						$eight = '';
						$feight = "";
						$cp_note_2 = NULL;
						$cp_note =NULL;		
				?>
				<tr>
					<td style="text-align:center"><?php echo $i+1;?></td>
					<td style="text-align:left"><?php echo $result[$i]['pvn_name'];?></td>
					<?php 
						$warning = '-';
						if($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']>0){
							//echo $result[$i]['cp_no'].'<br>';
							if($result[$i]['csd_stop_2']!='' AND $result[$i]['csd_stop_2']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop_2'],0,4)-543).substr($result[$i]['csd_stop_2'],4);
								if($csd_stop_ad < $date){
									$warning = '<span style="margin:0 4px;font-size:16px;"><a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป"><i class="fa fa-exclamation"></i></a></span>';
									array_push($lating,$result[$i]['cp_no']);
								}
							}
						}elseif($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']<1){
							if($result[$i]['csd_stop']!='' AND $result[$i]['csd_stop']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
								if($csd_stop_ad < $date){
									$warning = '<span style="margin:0 4px;font-size:16px;"><a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป"><i class="fa fa-exclamation"></i></a></span>';
									array_push($lating,$result[$i]['cp_no']);
								}
							}
						}else{
							
						}
						?>
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
					<td style="text-align:center;<?php if($result[$i]['cp_owner']!=1){ echo 'color:red';}?>"><?php echo $result[$i]['opt_fst_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
					<td style="text-align:center;">
					<?php 
						if($result[$i]['cp_scope']==1){
							$half+=1;
							echo "ไม่เต็มอำเภอ";
						}else{
							$full+=1;
							echo "<b>เต็ม</b>อำเภอ";
						}
					
					?>
					</td>
					</td>
					<td style="text-align:center">
						<?php
							if($result[$i]['cp_status']==1 AND $result[$i]['cp_effective']==0){
								echo "เปิดพื้นที่ใหม่";
							}elseif($result[$i]['cp_status']==2 AND $result[$i]['cp_effective']==1){
								echo "มีผลบังคับใช้";
							}
							elseif($result[$i]['cp_status']==2 AND $result[$i]['cp_effective']==2){
								echo '<color style="color:red">'."สิ้นสุดบังคับใช้".'</color>';
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
					<?php if($_POST['typeX']=='eva' OR $_POST['typeX']=='eva5' OR $_POST['typeX']=='eva10'){}else{?>	
					<td style="text-align:center;">
						<?php 
							if($result[$i]['cp_step']>0){
								echo '<b>'.$result[$i]['cp_step'].'</b>';
							}else{
								echo "-";
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
						}
						if($result[$i]['cp_step_1']>0 AND $result[$i]['csd_start']!=NULL){
							$date0 = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
							$date1=date_create($date0);
							$deadline = date_add($date1,date_interval_create_from_date_string(($result[$i]['sub_ot1']-1)." day"));
							$date2=date_create($now);
							if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
								$diff2 = date_diff($deadline,$current);
								//echo date_format($deadline,'Y-m-d').' , '.date_format($current,'Y-m-d').' csd_stop = null';
							}else{
								$csd_stop = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
								$csd_stop = date_create($csd_stop);
								$diff2 = date_diff($deadline,$csd_stop);
								//echo date_format($deadline,'Y-m-d').' , '.date_format($csd_stop,'Y-m-d').' csd_stop !=null';
								
								
							}
							$daynd = $diff2->format("%R%a");
							$daynd = intval($daynd);							
							//echo ' ---> '.$daynd.'<br>';							
							if($daynd <= 0){
								$color = "background-color:#2D882D!important;color:white!important;";
								$cp_note = "เหลือเวลา ".($daynd*(-1))." วัน";
								$value_green+=1;
								array_push($green_cp_1,$result[$i]['cp_no']);	
								$eight = '#2D882D';
								$feight = "white";
								array_push($a_green_1,$result[$i]['cp_no']);
							}elseif($daynd <= $result[$i]['sub_ot1']){
								$color = "background-color:#FFE841!important;color:black!important;";
								$cp_note = "ล่าช้า ".$daynd." วัน";
								$value_yellow+=1;
								array_push($yellow_cp_1,$result[$i]['cp_no']);
								$eight = '##FFE841';
								$feight = "black";
								array_push($a_yellow_1,$result[$i]['cp_no']);
							}elseif($daynd <= $result[$i]['sub_ot2']){
								$color = "background-color:orange!important;color:black!important;";
								$cp_note = "ล่าช้า ".$daynd." วัน";
								$value_orange+=1;
								array_push($orange_cp_1,$result[$i]['cp_no']);
								$eight = 'orange';
								$feight = "white";
								array_push($a_orange_1,$result[$i]['cp_no']);
							}else{
								$color ="background-color:red;color:white;";
								$cp_note = "ล่าช้า ".$daynd." วัน";
								$value_red+=1;
								array_push($red_cp_1,$result[$i]['cp_no']);
								$eight = 'red';
								$feight = "white";
								array_push($a_red_1,$result[$i]['cp_no']);
							}
							//echo $result[$i]['cp_no'].' '.$daynd.' <br>';
						}else{
							$color ="";
							$value_white+=1;
							array_push($white_cp_1,$result[$i]['cp_no']);
							$cp_note = "";
							//array_push($a_white_date_1,$result[$i]['cp_no']);
								
								if($result[$i]['cp_step']>0){
									if($result[$i]['cp_step_1']>0){
										array_push($a_white_date_1,$result[$i]['cp_no']);
									}else{
										array_push($a_white_step_1,$result[$i]['cp_no']);
									}
								}else{
									
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
							
								if($result[$i]['cp_step_1']>0){
									array_push($a_external_1,$result[$i]['cp_no']);
								}else{
									array_push($a_white_step_1,$result[$i]['cp_no']);
								}
							}
						}

						if($result[$i]['cp_step']==0){
							array_push($a_white_step,$result[$i]['cp_no']);
						}

					?>
					<td <?php if($result[$i]['cp_step_1']>0){ echo 'onclick="readTimeSnd('.$result[$i]['cp_no'].','.$daynd.')"';}?> style="text-align:center;<?php if($result[$i]['cp_step_1']>0){echo 'cursor: pointer;';}else{}?><?= $color;?>" title="<?php if($result[$i]['cp_step']>0 AND $result[$i]['cp_step_1'] > 0){ echo 'ขั้นตอน : '.$result[$i]['sub_name'].'&#10;';}?><?= $cp_note;?>">
					<?php 
						if($result[$i]['cp_step']>0){
							if($result[$i]['cp_step_1']>0){
								echo $result[$i]['cp_step'].".".$result[$i]['cp_step_1'];
							}else{
								echo "-";
							}
						}else{
							echo "-";
						}
					?>
					</td>
					<?php 
					if($result[$i]['cp_step']>0 AND $result[$i]['cp_step_1']>0 AND $result[$i]['cp_step_2']>0){
						$sql = "SELECT trd_name , trd_ot1 , trd_ot2 FROM ref_up_trd_step WHERE trd_fst = '".$result[$i]['cp_step']."' AND trd_snd = '".$result[$i]['cp_step_1']."' AND trd_step = '".$result[$i]['cp_step_2']."'";
						$resultrd = selectSql($sql);
						$result[$i]['trd_name'] = $resultrd[0]['trd_name'];
						$result[$i]['trd_ot1'] = $resultrd[0]['trd_ot1'];
						$result[$i]['trd_ot2'] = $resultrd[0]['trd_ot2'];
						
						if($result[$i]['csd_start_2']>0 AND $result[$i]['csd_start_2']!=null){
							$deadline2 = dead_line($result[$i]['csd_start_2'],$result[$i]['trd_ot1'],'th');
							//echo date_format($deadline2,'Y-m-d');
							//echo $result[$i]['cp_no'].' : '.$result[$i]['csd_start_2'].' - '.date_format($deadline2,'Y-m-d').'<br>';
							
							if($result[$i]['csd_stop_2']=='' OR $result[$i]['csd_stop_2']==' ' OR $result[$i]['csd_stop_2']==NULL){
								$diff3 = date_diff($deadline2,$current);
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
								$value_green_2 += 1;
								array_push($green_cp_2,$result[$i]['cp_no']);
								array_push($a_green_2,$result[$i]['cp_no']);
							}
							elseif($dayrd <= $result[$i]['trd_ot1']){
								$color_2 = "background-color:#FFE841!important;color:black!important;";
								$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
								$value_yellow_2 += 1;
								array_push($yellow_cp_2,$result[$i]['cp_no']);
								array_push($a_yellow_2,$result[$i]['cp_no']);
							}
							elseif($dayrd <= $result[$i]['trd_ot2']){
								$color_2 = "background-color:orange!important;color:black!important;";
								$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
								$value_orange_2 += 1;
								array_push($orange_cp_2,$result[$i]['cp_no']);
								array_push($a_orange_2,$result[$i]['cp_no']);
							}
							else{
								$color_2 ="background-color:red;color:white;";
								$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
								$value_red_2 += 1;
								array_push($red_cp_2,$result[$i]['cp_no']);
								array_push($a_red_2,$result[$i]['cp_no']);
							}
						}else{
							$color_2 = "";
							$value_white_2 += 1;
							array_push($white_cp_2,$result[$i]['cp_no']);
							array_push($a_white_date_2,$result[$i]['cp_no']);
							
						}						
					}else{
						$color_2 = "";
						if($result[$i]['cp_step']>0 AND $result[$i]['cp_step_1'] > 0 AND $result[$i]['cp_step_2']==0){
							array_push($a_white_step_2,$result[$i]['cp_no']);
						}
					}
					?>
					<td <?php if($result[$i]['cp_step_2']>0){ echo 'onclick="readTimeTrd('.$result[$i]['cp_no'].','.$dayrd.')"';}?> style="<?php if($result[$i]['cp_step_2']>0){echo 'cursor: pointer;';}else{}?>text-align:center;<?= $color_2;?>" title="<?php if($result[$i]['cp_step']>0 AND $result[$i]['cp_step_1']>0 AND $result[$i]['cp_step_2']>0){ echo 'ขั้นตอน : '.$result[$i]['trd_name'].'&#10;';}?><?= $cp_note_2;?>">
					<?php 
						if($result[$i]['cp_step']>0){
							if($result[$i]['cp_step_1']>0){
								if($result[$i]['cp_step_2']>0){
									echo $result[$i]['cp_step'].".".$result[$i]['cp_step_1'].".".$result[$i]['cp_step_2'];
								}else{
									echo "-";
								}
							}else{
								echo "-";
							}
						}else{
							echo "-";
						}
					?>
					</td>
					<td style="text-align:center">
					<?php 
						echo $warning;
					?>
					</td>
					<?php } ?>
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
						<span title="แสดงขั้นตอน" class="step" onclick="XopenStep(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-list"></i></a></span>
					</td>
				</tr>
				<?php 
					} 
				?>
			</tbody>
		</table>
	<?php } ?>		
		<table hidden id="table_printx" class="table table-bordereds printx">
			<thead>
				<tr style="background-color: #295DBD;color:white">
					<td style="text-align:center" rowspan="2">ลำดับ</td>
					<td style="text-align:center" rowspan="2">จังหวัด</td>
					<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
					<td style="text-align:center" rowspan="2" colspan="2">ผู้ดำเนินการ</td>
					<td style="text-align:center" rowspan="2">ขอบเขต</td>
					<td style="text-align:center" rowspan="2">สถานะผัง</td>
					<td style="text-align:center" colspan="3">ขั้นตอนดำเนินการ</td>
					<td style="text-align:center" rowspan="2">ผลดำเนินการ</td>
				</tr>
				<tr>
					<td style="text-align:center">ขั้นตอนหลัก</td>
					<td style="text-align:center">ขั้นตอนย่อย 1</td>
					<td style="text-align:center">ขั้นตอนย่อย 2</td>
				</tr>
			</thead>
			<tbody>
			<?php $x = 1;
				foreach($result as $row){
				$eight = "";
				$feight = "";
				?>
				<tr>
					<td style="text-align:center"><?= $x;?></td>
					<td style="text-align:center"><?= $row['pvn_name']?></td>
					<td style="text-align:center"><?= $row['cp_name']?></td>
					<td style="text-align:center"><?= $row['opt_fst_name']?></td>
					<td style="text-align:center"><?= $row['opt_snd_name']?></td>
					<td style="text-align:center"><?= $row['scope_name']?></td>
					<td style="text-align:center">
						<?php 
							if($row['cp_status']==1 AND $row['cp_effective']==0){
								echo "เปิดพื้นที่ใหม่";
							}elseif($row['cp_status']==2 AND $row['cp_effective']==1){
								echo "มีผลบังคับใช้";
							}
							elseif($row['cp_status']==2 AND $row['cp_effective']==2){
								echo "สิ้นสุดบังคับใช้";
							}
							else{
								echo "-";
							}
						?>
					</td>
					<td style="text-align:center;">
						<?php 
							if($row['cp_step']==0){
								echo '-';
							}else{
								echo '<b>'.$row['cp_step'].'</b>';
							}
						?>
					</td>
					<?php 
					if($row['cp_step']==6){
							if($row['cp_petition']==1){
								$sql = "SELECT (sub_time2*7) as ot1,(sub_time2*7*2) as ot2 FROM ref_up_snd_step WHERE sub_step = '".$row['cp_step']."' AND sub_no = '".$row['cp_step_1']."'";
							}
							else{
								$sql = "SELECT (sub_time1*7) as ot1,(sub_time1*7*2) as ot2 FROM ref_up_snd_step WHERE sub_step = '".$row['cp_step']."' AND sub_no = '".$row['cp_step_1']."'";
							}						
							$resultx = selectSql($sql);
							$row['sub_ot1'] = $resultx[0]['ot1'];
							$row['sub_ot2'] = $resultx[0]['ot2'];
						}
						if($row['cp_step_1']!=0 AND $row['csd_start']!=NULL){
							$date0 = (substr($row['csd_start'],0,4)-543).substr($row['csd_start'],4);
							$date1=date_create($date0);
							$deadline = date_add($date1,date_interval_create_from_date_string(($row['sub_ot1']-1)." day"));
							$date2=date_create($now);
							if($row['csd_stop']=='' OR $row['csd_stop']==' ' OR $row['csd_stop']==null){
								$diff2 = date_diff($deadline,$current);
								//echo date_format($deadline,'Y-m-d').' , '.date_format($current,'Y-m-d').' csd_stop = null';
							}else{
								$csd_stop = (substr($row['csd_stop'],0,4)-543).substr($row['csd_stop'],4);
								$csd_stop = date_create($csd_stop);
								$diff2 = date_diff($deadline,$csd_stop);
								//echo date_format($deadline,'Y-m-d').' , '.date_format($csd_stop,'Y-m-d').' csd_stop !=null';
								
								
							}
							$daynd = $diff2->format("%R%a");
							$daynd = intval($daynd);							
							//echo ' ---> '.$daynd.'<br>';							
							if($daynd <= 0){
								$color = "background-color:#2D882D!important;color:white!important;";
								$cp_note = "เหลือเวลา ".($daynd*(-1))." วัน";
								$eight = '#2D882D';
								$feight = 'white';
							}elseif($daynd <= $row['sub_ot1']){
								$color = "background-color:#FFE841!important;color:black!important;";
								$cp_note = "ล่าช้า ".$daynd." วัน";
								$eight = "#FFE841";
								$feight = "black";
							}elseif($daynd <= $row['sub_ot2']){
								$color = "background-color:orange!important;color:black!important;";
								$cp_note = "ล่าช้า ".$daynd." วัน";
								$eight = "#orange";
								$feight = "black";
							}else{
								$color ="background-color:red!important;color:white!important;";
								$cp_note = "ล่าช้า ".$daynd." วัน";
								$eight = 'red';
								$feight = 'white';
								}
							}else{
							$color ="";
							$cp_note = "";
						}
						if($row['cp_step']==8){
								if($row['cp_step_1']==2 OR $row['cp_step_1']==4 OR $row['cp_step_1']==5 OR $row['cp_step_1']==6 OR $row['cp_step_1']==8){
								//if($eight!="#2D882D" OR $eight!="#FFE841" OR $eight!="orange" OR $eight!="red"){
									if($eight==''){
										$eight = 'transparent';
										$feight = "black";
									}
									$color  = "background: linear-gradient(90deg,#0000ff 0%,#0000ff 25%,".$eight." 25%,".$eight." 100%)!important;color:".$feight.'!important';
								}
						}
						
						
					?>					
					<td <?php if($row['cp_step_1']>0){ echo 'onclick="readTimeSnd('.$row['cp_no'].','.$daynd.')"';}?> style="text-align:center;<?= $color;?>;-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;" title="<?php if($row['cp_step']>0 AND $row['cp_step_1'] > 0){ echo 'ขั้นตอน : '.$row['sub_name'].'&#10;';}?><?= $cp_note;?>">
						<?php
							if($row['cp_step_1']==0){
								echo '-';
							}else{
								echo $row['cp_step'],'.',$row['cp_step_1'];
							}
						?>
					</td>
					<?php 
					if($row['cp_step']>0 AND $row['cp_step_1']>0 AND $row['cp_step_2']>0){
						$sql = "SELECT trd_name , trd_ot1 , trd_ot2 FROM ref_up_trd_step WHERE trd_fst = '".$row['cp_step']."' AND trd_snd = '".$row['cp_step_1']."' AND trd_step = '".$row['cp_step_2']."'";
						$resultrd = selectSql($sql);
						$row['trd_name'] = $resultrd[0]['trd_name'];
						$row['trd_ot1'] = $resultrd[0]['trd_ot1'];
						$row['trd_ot2'] = $resultrd[0]['trd_ot2'];
						
						if($row['csd_start_2']!='' AND $row['csd_start_2']!=null){
							$deadline2 = dead_line($row['csd_start_2'],$row['trd_ot1'],'th');
							//echo date_format($deadline2,'Y-m-d');
							//echo $row['cp_no'].' : '.$row['csd_start_2'].' - '.date_format($deadline2,'Y-m-d').'<br>';
							
							if($row['csd_stop_2']=='' OR $row['csd_stop_2']==' ' OR $row['csd_stop_2']==NULL){
								$diff3 = date_diff($deadline2,$current);
							}else{
								$csd_stop_2 = (substr($row['csd_stop_2'],0,4)-543).substr($row['csd_stop_2'],4);
								$csd_stop_2 = date_create($csd_stop_2);
								$diff3 = date_diff($deadline2,$csd_stop_2);
							}							
							$dayrd = $diff3->format("%R%a");
							$dayrd = intval($dayrd);
							if($dayrd <= 0){
								$color_2 = "background-color:#2D882D!important;color:white!important;";
								$cp_note_2 = "เหลือเวลา ".($dayrd*-1)." วัน";
							}
							elseif($dayrd <= $row['trd_ot1']){
								$color_2 = "background-color:#FFE841!important;color:black!important;";
								$cp_note_2 = "ล่าช้า ".$dayrd." วัน";
							}
							elseif($dayrd <= $row['trd_ot2']){
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
					<td <?php if($row['cp_step_2']>0){ echo 'onclick="readTimeTrd('.$row['cp_no'].','.$dayrd.')"';}?> style="text-align:center;<?= $color_2;?>" title="<?php if($row['cp_step']>0 AND $row['cp_step_1']>0 AND $row['cp_step_2']>0){ echo 'ขั้นตอน : '.$row['trd_name'].'&#10;';}?><?= $cp_note_2;?>">
					<?php 
						if($row['cp_step']>0){
							if($row['cp_step_1']>0){
								if($row['cp_step_2']>0){
									echo $row['cp_step'].".".$row['cp_step_1'].".".$row['cp_step_2'];
								}else{
									echo "-";
								}
							}else{
								echo "-";
							}
						}else{
							echo "-";
						}
					?>
					</td>
					<td></td>
					
				</tr>
				<?php  $x++;
				} ?>
			</tbody>
		</table>		
		<?php
	}else{
		echo "<h3 style='font-family: Kanit;text-align: center;margin:15px 0px;'>ไม่มีข้อมูล</h3>";	
		
	}
?>

<?php 
	pagin($bow,$all_data);
	if($_POST['data']!=""){
		if($_POST['stype']=='aff'){
			$print_search = 'สำนักผังเมืองรวมส่วนที่ '.$_POST['data'];}
		}
		else{
			$print_search = $_POST['data'];}

	if($_SESSION['report_page']>1){
		echo "<script>$('#select_table').val('".$_SESSION['report_page']."');change_table();</script>";
		unset($_SESSION['report_page']);
	}
	else{
		echo "<script>$('#select_table').val('1');</script>";
	}
?>

</div>
<div id="showTable2" class="unprintable" style="margin-left: -15px;">
		<div id="div-btn" class="col-xs-5 col-md-6">
			<button hidden id="btn_backz" onclick="goback();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
		</div>
</div>
<?php
	$a_xs = array_merge($a_white_step_1,$a_green_1,$a_yellow_1,$a_orange_1,$a_red_1,$a_white_date_1);
	$a_xs_1 = array_merge($a_green_1,$a_yellow_1,$a_orange_1,$a_red_1,$a_white_date_1);
	$a_xs_2 = array_merge($a_green_2,$a_yellow_2,$a_orange_2,$a_red_2,$a_white_date_2);
	//echo count($a_xs_1);
	$xs = json_encode($a_xs);
	$xs1 = json_encode($a_xs_1);
	$xs2 = json_encode($a_xs_2);

	$white_1 = json_encode($white_cp_1);
	$green_1 = json_encode($green_cp_1);
	$yellow_1 = json_encode($yellow_cp_1);
	$orange_1 = json_encode($orange_cp_1);
	$red_1 = json_encode($red_cp_1);
	
	$white_2 = json_encode($white_cp_2);
	$green_2 = json_encode($green_cp_2);
	$yellow_2 = json_encode($yellow_cp_2);
	$orange_2 = json_encode($orange_cp_2);
	$red_2 = json_encode($red_cp_2);
	//echo $white_1;
	
	
	/**/
	$a_white_step_2 = array_merge($a_white_step,$a_white_step_1,$a_white_step_2);
	$a_white_step_1 = array_merge($a_white_step,$a_white_step_1);
	
	
	$ws = json_encode($a_white_step);
	$ws1 = json_encode($a_white_step_1);
	$wd1 = json_encode($a_white_date_1);
	$ex1 = json_encode($a_external_1);
	$gn1 = json_encode($a_green_1);
	$yl1 = json_encode($a_yellow_1);
	$or1 = json_encode($a_orange_1);
	$rd1 = json_encode($a_red_1);

	$ws2 = json_encode($a_white_step_2);
	$wd2 = json_encode($a_white_date_2);
	$ex2 = json_encode($a_external_2);
	$gn2 = json_encode($a_green_2);
	$yl2 = json_encode($a_yellow_2);
	$or2 = json_encode($a_orange_2);
	$rd2 = json_encode($a_red_2);


	$allx = array_merge($a_white_step,$a_xs);
	$all_1 = array_merge($a_white_step_1,$a_white_date_1,$a_green_1,$a_yellow_1,$a_orange_1,$a_red_1);
	$all_2 = array_merge($a_white_step_2,$a_white_date_2,$a_green_2,$a_yellow_2,$a_orange_2,$a_red_2);

	$al0 = json_encode($allx);
	$al1 = json_encode($all_1);
	$al2 = json_encode($all_2);

	$count_lating =  count($lating);
	$latingx = json_encode($lating);
	
	
	
	
	?>
<script>
	//$('#lating-value').html('<?= $count_lating;?>').attr('style','cursor:pointer').attr('onclick','statusLating(<?= $latingx;?>)');
	$('#lating-value').replaceWith("<b style='cursor:pointer' id='s7' class='lating-value' ><?= $count_lating;?></b>");
	$('.lating-value').attr('onclick','statusLating(<?= $latingx;?>)').css('text-decoration','underline');
	$('#cp_value').replaceWith('<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px">จำนวน <b><?php echo $all_data;?></b> ผัง</h3>');
	$('#lating-all').replaceWith("<b><?= $all_data;?></b>");
	$('#search_valuex').html('จำนวน <b><?= $all_data;?></b> ผัง');
	$('#search_valuex').show();
	<?php if($print_search!=""){ ?>
		$('#print_search').html("(ข้อความที่สืบค้น "+'<?php echo $print_search;?>'+")");
		$('#print_search').show()
	<?php }else{ ?>
		$('#print_search').html("");
		$('#print_search').hide();
	<?php }	?>
	$('#info_bar').show();
	/*
	$('.value_white').html(<?php echo $value_white; ?>);
	$('.value_green').html(<?php echo $value_green; ?>);
	$('.value_yellow').html(<?php echo $value_yellow; ?>);
	$('.value_orange').html(<?php echo $value_orange; ?>);
	$('.value_red').html(<?php echo $value_red; ?>);
	
	$('.value_white_2').html(<?php echo $value_white_2; ?>);
	$('.value_green_2').html(<?php echo $value_green_2; ?>);
	$('.value_yellow_2').html(<?php echo $value_yellow_2; ?>);
	$('.value_orange_2').html(<?php echo $value_orange_2; ?>);
	$('.value_red_2').html(<?php echo $value_red_2; ?>);
	*/
	<?php 
	/*
	if(count($white_cp_1)>0){
		echo "$('#white_1').attr('onclick',"."'statusCityplan(1,1,".$white_1.")');";
		echo "$('#white_1').css('cursor','pointer');";
	}
	if(count($green_cp_1)>0){
		echo "$('#green_1').attr('onclick',"."'statusCityplan(2,1,".$green_1.")');";
		echo "$('#green_1').css('cursor','pointer');";
	}
	if(count($yellow_cp_1)>0){
		echo "$('#yellow_1').attr('onclick',"."'statusCityplan(3,1,".$yellow_1.")');";
		echo "$('#yellow_1').css('cursor','pointer');";
	}
	if(count($orange_cp_1)>0){
		echo "$('#orange_1').attr('onclick',"."'statusCityplan(4,1,".$orange_1.")');";
		echo "$('#orange_1').css('cursor','pointer');";
	}
	if(count($red_cp_1)>0){
		echo "$('#red_1').attr('onclick',"."'statusCityplan(5,1,".$red_1.")');";
		echo "$('#red_1').css('cursor','pointer');";
	}
	
	if(count($white_cp_2)>0){
		echo "$('#white_2').attr('onclick',"."'statusCityplan(1,2,".$white_2.")');";
		echo "$('#white_2').css('cursor','pointer');";
	}
	if(count($green_cp_2)>0){
		echo "$('#green_2').attr('onclick',"."'statusCityplan(2,2,".$green_2.")');";
		echo "$('#green_2').css('cursor','pointer');";
	}
	if(count($yellow_cp_2)>0){
		echo "$('#yellow_2').attr('onclick',"."'statusCityplan(3,2,".$yellow_2.")');";
		echo "$('#yellow_2').css('cursor','pointer');";
	}
	if(count($orange_cp_2)>0){
		echo "$('#orange_2').attr('onclick',"."'statusCityplan(4,2,".$orange_2.")');";
		echo "$('#orange_2').css('cursor','pointer');";
	}
	if(count($red_cp_2)>0){
		echo "$('#red_2').attr('onclick',"."'statusCityplan(5,2,".$red_2.")');";
		echo "$('#red_2').css('cursor','pointer');";
	}*/
	
	
	/*-------------------------------------------          white_step_status -------------------------------------*/
	if(count($a_white_step)>0){
		?>$('.white_step').replaceWith('<td id="r1" style="cursor:pointer" class="white_step"><?= count($a_white_step); ?></td>');$('.white_step').attr('onclick',<?php echo "'statusCityplan(1,0,".$ws.")'";?>); <?php
	}else{
		?>$('.white_step').replaceWith('<td style="cursor:default" class="white_step">-</td>'); <?php
	}
	if(count($a_white_step_1)>0){
		?>$('.white_step_1').replaceWith('<td id="s1" style="cursor:pointer" class="white_step_1"><?= count($a_white_step_1); ?></td>'); $('.white_step_1').attr('onclick',<?php echo "'statusCityplan(1,1,".$ws1.")'";?>);<?php
	}else{
		?>$('.white_step_1').replaceWith('<td style="cursor:default" class="white_step_1">-</td>'); <?php
	}
	if(count($a_white_step_2)>0){
		?>$('.white_step_2').replaceWith('<td id="t1" style="cursor:pointer" class="white_step_2"><?= count($a_white_step_2); ?></td>'); $('.white_step_2').attr('onclick',<?php echo "'statusCityplan(1,2,".$ws2.")'";?>); <?php
	}else{
		?>$('.white_step_2').replaceWith('<td style="cursor:default" class="white_step_2">-</td>'); <?php
	}
	/*-------------------------------------------         x_status -------------------------------------*/
	if(count($a_xs)>0){
		?>$('.x_step').replaceWith('<td id="r" style="cursor:pointer" class="x_step"><?= count($a_xs); ?></td>');$('.x_step').attr('onclick',<?php echo "'statusCityplan(2,0,".$xs.")'";?>); <?php
	}else{
		?>$('.x_step').replaceWith('<td style="cursor:default" class="x_step">-</td>'); <?php
	}
	?>
	$('.x_step_1').replaceWith('<td style="cursor:default;background:#828385" class="x_step_1"></td>');
	$('.x_step_2').replaceWith('<td style="cursor:default;background:#828385" class="x_step_2"></td>');
	<?php
	/*
	if(count($a_xs_1)>0){
		?>$('.x_step_1').replaceWith('<td style="cursor:pointer" class="x_step_1"><?= count($a_xs_1); ?></td>'); $('.x_step_1').attr('onclick',<?php echo "'statusCityplan(2,1,".$xs1.")'";?>);<?php
	}else{
		?>$('.x_step_1').replaceWith('<td style="cursor:default" class="x_step_1">-</td>'); <?php
	}
	if(count($a_xs_2)>0){
		?>$('.x_step_2').replaceWith('<td style="cursor:pointer" class="x_step_2"><?= count($a_xs_2); ?></td>'); $('.x_step_2').attr('onclick',<?php echo "'statusCityplan(2,2,".$xs2.")'";?>); <?php
	}else{
		?>$('.x_step_2').replaceWith('<td style="cursor:default" class="x_step_2">-</td>'); <?php
	}*/
	/*-------------------------------------------          green_status -------------------------------------*/
	if(count($a_green_1)>0){
		?>$('.green_1').replaceWith('<td id="s2" style="cursor:pointer" class="green_1"><?= count($a_green_1); ?></td>');$('.green_1').attr('onclick',<?php echo "'statusCityplan(3,1,".$gn1.")'";?>);<?php
	}else{
		?>$('.green_1').replaceWith('<td style="cursor:default" class="green_1">-</td>');<?php
	}
	if(count($a_green_2)>0){
		?>$('.green_2').replaceWith('<td id="t2" style="cursor:pointer" class="green_2"><?= count($a_green_2); ?></td>');$('.green_2').attr('onclick',<?php echo "'statusCityplan(3,2,".$gn2.")'";?>);<?php
	}else{
		?>$('.green_2').replaceWith('<td style="cursor:default" class="green_2">-</td>');<?php
	}
	/*-------------------------------------------          yellow_status -------------------------------------*/
	if(count($a_yellow_1)>0){
		?>$('.yellow_1').replaceWith('<td id="s3" style="cursor:pointer" class="yellow_1"><?= count($a_yellow_1); ?></td>');$('.yellow_1').attr('onclick',<?php echo "'statusCityplan(4,1,".$yl1.")'";?>);<?php
	}else{
		?>$('.yellow_1').replaceWith('<td style="cursor:default" class="yellow_1">-</td>');<?php
	}
	if(count($a_yellow_2)>0){
		?>$('.yellow_2').replaceWith('<td id="t3" style="cursor:pointer" class="yellow_2"><?= count($a_yellow_2); ?></td>');$('.yellow_2').attr('onclick',<?php echo "'statusCityplan(4,2,".$yl2.")'";?>);<?php
	}else{
		?>$('.yellow_2').replaceWith('<td style="cursor:default" class="yellow_2">-</td>');<?php
	}
	/*-------------------------------------------          orange_status -------------------------------------*/
	if(count($a_orange_1)>0){
		?>$('.orange_1').replaceWith('<td id="s4" style="cursor:pointer" class="orange_1"><?= count($a_orange_1); ?></td>');$('.orange_1').attr('onclick',<?php echo "'statusCityplan(5,1,".$or1.")'";?>);<?php
	}else{
		?>$('.orange_1').replaceWith('<td style="cursor:default" class="orange_1">-</td>');<?php
	}
	if(count($a_orange_2)>0){
		?>$('.orange_2').replaceWith('<td id="t4" style="cursor:pointer" class="orange_2"><?= count($a_orange_2); ?></td>');$('.orange_2').attr('onclick',<?php echo "'statusCityplan(5,2,".$or2.")'";?>);<?php
	}else{
		?>$('.orange_2').replaceWith('<td style="cursor:default" class="orange_2">-</td>');<?php
	}
	/*-------------------------------------------          red_status -------------------------------------*/
	if(count($a_red_1)>0){
		?>$('.red_1').replaceWith('<td id="s5" style="cursor:pointer" class="red_1"><?= count($a_red_1); ?></td>');$('.red_1').attr('onclick',<?php echo "'statusCityplan(6,1,".$rd1.")'";?>);<?php
	}else{
		?>$('.red_1').replaceWith('<td style="cursor:default" class="red_1">-</td>');<?php
	}
	if(count($a_red_2)>0){
		?>$('.red_2').replaceWith('<td id="t5" style="cursor:pointer" class="red_2"><?= count($a_red_2); ?></td>');$('.red_2').attr('onclick',<?php echo "'statusCityplan(6,2,".$rd2.")'";?>);<?php
	}else{
		?>$('.red_2').replaceWith('<td style="cursor:default" class="red_2">-</td>');<?php
	}
	/*-------------------------------------------         white_date_status -------------------------------------*/
	if(count($a_white_date_1)>0){
		?>$('.white_date_1').replaceWith('<td id="s6" style="cursor:pointer" class="white_date_1"><?= count($a_white_date_1); ?></td>');$('.white_date_1').attr('onclick',<?php echo "'statusCityplan(7,1,".$wd1.")'";?>);<?php
	}else{
		?>$('.white_date_1').replaceWith('<td style="cursor:default" class="white_date_1">-</td>');<?php
	}
	if(count($a_white_date_2)>0){
		?>$('.white_date_2').replaceWith('<td id="t6" style="cursor:pointer" class="white_date_2"><?= count($a_white_date_2); ?></td>');$('.white_date_2').attr('onclick',<?php echo "'statusCityplan(7,2,".$wd2.")'";?>);<?php
	}else{
		?>$('.white_date_2').replaceWith('<td style="cursor:default" class="white_date_2">-</td>');<?php
	}
	/*-------------------------------------------          all_status -------------------------------------*/
	if(count($allx)>0){
		?>$('.all').replaceWith('<td style="cursor:pointer" class="all"><?= count($allx); ?></td>');$('.all').attr('onclick','returnSSR()');/*;$('.all').attr('onclick',<?php echo "'statusCityplan(8,0,".$al0.")'";?>);*/<?php
	}else{
		?>$('.all').replaceWith('<td style="cursor:default" class="all">-</td>');<?php
	}
	if(count($all_1)>0){
		?>$('.all_1').replaceWith('<td style="cursor:pointer" class="all_1"><?= count($all_1); ?></td>');$('.all_1').attr('onclick','returnSSR()');/*;$('.all_1').attr('onclick',<?php echo "'statusCityplan(8,1,".$al1.")'";?>);*/<?php
	}else{
		?>$('.all_1').replaceWith('<td style="cursor:default" class="all_1">-</td>');<?php
	}
	if(count($all_2)>0){
		?>$('.all_2').replaceWith('<td style="cursor:pointer" class="all_2"><?= count($all_2); ?></td>');$('.all_2').attr('onclick','returnSSR()');/*;$('.all_2').attr('onclick',<?php echo "'statusCityplan(8,2,".$al2.")'";?>);*/<?php
	}else{
		?>$('.all_2').replaceWith('<td style="cursor:default" class="all_2">-</td>');<?php
	}
	/*-------------------------------------------          external_status -------------------------------------*/
	if(count($a_external_1)>0){
		?>$('.external_1').replaceWith('<td id="s8" style="cursor:pointer" class="external_1"><?= count($a_external_1); ?></td>');$('.external_1').attr('onclick',<?php echo "'statusCityplan(9,1,".$ex1.")'";?>);<?php
	}else{
		?>$('.external_1').replaceWith('<td style="cursor:default" class="external_1">-</td>');<?php
	}
	if(count($a_external_2)>0){
		?>$('.external_2').replaceWith('<td id="t8" style="cursor:pointer" class="external_2"><?= count($a_external_2); ?></td>');$('.external_2').attr('onclick',<?php echo "'statusCityplan(9,2,".$ex2.")'";?>);<?php
	}else{
		?>$('.external_2').replaceWith('<td style="cursor:default" class="external_2">-</td>');<?php
	}
	?>
	$('.white_step').removeAttr("class");
	$('.white_step_1').removeAttr("class");
	$('.white_step_2').removeAttr("class");
	
	$('.green').removeAttr("class");
	$('.green_1').removeAttr("class");
	$('.green_2').removeAttr("class");
	
	$('.yellow').removeAttr("class");
	$('.yellow_1').removeAttr("class");
	$('.yellow_2').removeAttr("class");
	
	$('.orange').removeAttr("class");
	$('.orange_1').removeAttr("class");
	$('.orange_2').removeAttr("class");
	
	$('.red').removeAttr("class");
	$('.red_1').removeAttr("class");
	$('.red_2').removeAttr("class");
	
	
	$('.white_date').removeAttr("class");
	$('.white_date_1').removeAttr("class");
	$('.white_date_2').removeAttr("class");
	
	$('.all').removeAttr("class");
	$('.all_1').removeAttr("class");
	$('.all_2').removeAttr("class");
	
	$('.external').removeAttr("class");
	$('.external_1').removeAttr("class");
	$('.external_2').removeAttr("class");
	
	<?= $_POST['sub_script'];?>
</script>