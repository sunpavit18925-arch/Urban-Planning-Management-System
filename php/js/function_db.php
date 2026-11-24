<?php
require_once __DIR__ . '/../mock_db_setup.php';

function connect(){#ติดต่อ Mockup Database (SQLite) — งดเชื่อมต่อฐานข้อมูลจริง
	try {
		$dbPath = getMockSqlitePath();
		if (!file_exists($dbPath) || filesize($dbPath) === 0) {
			setupMockDatabase();
		}
		return openMockSqlitePdo();
	} catch (Exception $e) {
		return null;
	}
}
function selectSql($sql){#แสดงข้อมูลจาก Mockup Database
	$conn = connect();
	if (!$conn) {
		return [];
	}
	try {
		$sth = $conn->prepare($sql);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		$conn = null;
		return $rows;
	} catch (PDOException $e) {
		return [];
	}
}
function in_up_delSql($sql){#แทรก แก้ไข ลบ ข้อมูลใน Mockup Database
	$conn = connect();
	if (!$conn) {
		return false;
	}
	try {
		// Normalize MySQL singular VALUE(...) syntax for SQLite compatibility
		$normalizedSql = preg_replace('/\bVALUE\s*\(/i', 'VALUES(', $sql);
		$sth = $conn->prepare($normalizedSql);
		$sth->execute();
		$conn = null;
		return true;
	} catch (PDOException $e) {
		return false;
	}
}

function m35_color($seq,$opr){
	if($opr==1 OR $opr==2){
		$sql = "SELECT * FROM ref_cityplan_step_detail_m35 WHERE csd35_seq = ".$seq;
		$csd = selectSql($sql);
		$ot = null;
		if($csd[0]['csd35_step_fst']==6){
			if($csd[0]['csd35_petition']==0){
				$ot = "m35_snd_time1*7 as ot1, m35_snd_time1*14 as ot2";	
			}else{
				$ot = "m35_snd_time2*7 as ot1, m35_snd_time2*14 as ot2";		
			}	
		}else{
			$ot = "m35_snd_time*7 as ot1, m35_snd_time*14 as ot2";	
		}
		$sql = "SELECt ".$ot." FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$csd[0]['csd35_step_fst']."' ANd m35_snd_step_sub = '".$csd[0]['csd35_step_snd']."' AND m35_operate = '".$opr."'";
		$step = selectSql($sql);
		$title = $bgstyle = $day = $diff = null;
		$return = array();
		$current = date_create(date('Y-m-d'));
		if($csd[0]['csd35_snd_start']!= null AND $csd[0]['csd35_snd_start']!=''){
			$start = date_create((substr($csd[0]['csd35_snd_start'],0,4)-543).substr($csd[0]['csd35_snd_start'],4,6));
			$deadline =  date_add($start,date_interval_create_from_date_string(($step[0]['ot1']-1)." day"));
			/*if($start > $current){
				$return = [
					'title' => 'ยังไม่เริ่มดำเนินการ',
					'bgstyle' => '',
					'day' => ''
				];
				return $return;
			}*/
			if($csd[0]['csd35_snd_stop'] == null OR $csd[0]['csd35_snd_stop'] ==''){
				$day = date_diff($deadline,$current);
			}else{
				$stop = date_create((substr($csd[0]['csd35_snd_stop'],0,4)-543).substr($csd[0]['csd35_snd_stop'],4,6));
				$day = date_diff($deadline,$stop);
			}
			$day =  intval($day->format("%R%a"));
			if($day <= 0){
				$title = "มีความก้าวหน้า";
				$bgstyle = "background:#2D882D!important;color:white!important";
			}elseif($day > 0 AND $day<=$step[0]['ot1']){
				$title = "ล่าช้าเล็กน้อย ".$day." วัน";
				$bgstyle = "background:#FFE841!important;color:black!important";
				
			}elseif($day > $step[0]['ot1'] AND $day<=$step[0]['ot2']){
				$title = "ล่าช้าปานกลาง ".$day." วัน";
				$bgstyle = "background:orange!important;color:black!important";
			}else{
				$title = "ล่าช้ามาก ".$day." วัน";
				$bgstyle = "background:red!important;color:white!important";
			}
			
		}else{
			$title = "ไม่ระบุวัน/เวลาดำเนินการ";
			//$bgstyle = "background:white!important;color:black!important";
			$day = "-";
		}
		
		$return = [
			'title' => $title,
			'bgstyle' => $bgstyle,
			'day' => $day
		];
		return $return;
	}else{
		return "unidentify -> opr";
	}
}

function est_color($seq,$opr){
	if($opr==1 OR $opr==2){
		$sql = "SELECT * FROM ref_cityplan_step_detail_est WHERE csd_est_seq = ".$seq;
		$csd = selectSql($sql);
		$ot = null;
		if(FALSE){
			if($csd[0]['csd_est_petition']==0){
				$ot = "est_snd_time1*7 as ot1, est_snd_time1*14 as ot2";	
			}else{
				$ot = "est_snd_time2*7 as ot1, est_snd_time2*14 as ot2";		
			}	
		}else{
			$ot = "est_snd_time*7 as ot1, est_snd_time*14 as ot2";	
		}
		$sql = "SELECt ".$ot." FROM ref_est_snd_step WHERE est_snd_step_main = '".$csd[0]['csd_est_step_fst']."' ANd est_snd_step_sub = '".$csd[0]['csd_est_step_snd']."' AND est_operate = '".$opr."'";
		$step = selectSql($sql);
		$title = $bgstyle = $day = $diff = null;
		$return = array();
		$current = date_create(date('Y-m-d'));
		if($csd[0]['csd_est_snd_start']!= null AND $csd[0]['csd_est_snd_start']!=''){
			$start = date_create((substr($csd[0]['csd_est_snd_start'],0,4)-543).substr($csd[0]['csd_est_snd_start'],4,6));
			$deadline =  date_add($start,date_interval_create_from_date_string(($step[0]['ot1']-1)." day"));
			if($csd[0]['csd_est_snd_stop'] == null OR $csd[0]['csd_est_snd_stop'] ==''){
				$day = date_diff($deadline,$current);
			}else{
				$stop = date_create((substr($csd[0]['csd_est_snd_stop'],0,4)-543).substr($csd[0]['csd_est_snd_stop'],4,6));
				$day = date_diff($deadline,$stop);
			}
			$day =  intval($day->format("%R%a"));
			if($day <= 0){
				$title = "มีความก้าวหน้า";
				$bgstyle = "background:#2D882D!important;color:white!important";
			}elseif($day > 0 AND $day<=$step[0]['ot1']){
				$title = "ล่าช้าเล็กน้อย ".$day." วัน";
				$bgstyle = "background:#FFE841!important;color:black!important";
				
			}elseif($day > $step[0]['ot1'] AND $day<=$step[0]['ot2']){
				$title = "ล่าช้าปานกลาง ".$day." วัน";
				$bgstyle = "background:orange!important;color:black!important";
			}else{
				$title = "ล่าช้ามาก ".$day." วัน";
				$bgstyle = "background:red!important;color:white!important";
			}
			
		}else{
			$title = "ไม่ระบุวัน/เวลาดำเนินการ";
			//$bgstyle = "background:white!important;color:black!important";
			$day = "-";
		}
		
		$return = [
			'title' => $title,
			'bgstyle' => $bgstyle,
			'day' => $day
		];
		return $return;
	}else{
		return "unidentify -> opr";
	}
}

function pagin($row,$all_data){
	$all_table = ceil($all_data/$row);	
	if($all_data>0){
	?>
	<div id="paginshow" style="text-align:center" class="unprintable">
		<button title="หน้าแรก" style="margin-top:-8px;color:white;background-color:#262626" id="btn_first" onclick="con_table('first')" class="btn " >«</button>
		<button title="ย้อนกลับ" style="margin-top:-8px;color:white;background-color:#696969" id="btn_back" onclick="con_table('back')" class="btn " ><</button>
		<select id="select_table" onchange="change_table()" style="font-size:20px;text-align:center;border-radius:8px">
		<?php for($table = 1 ; $table <= $all_table ; $table++){ ?>
				<option value="<?php echo $table; ?>"><?php echo $table;?></option>
		<?php } ?>
		</select>
		<button title="ถัดไป" style="margin-top:-8px;color:white;background-color:#696969" id="btn_next" onclick="con_table('next')" class="btn" >></button>
		<button title="หน้าสุดท้าย" style="margin-top:-8px;color:white;background-color:#262626" id="btn_last" onclick="con_table('last')" class="btn">»</button>
	</div>
	<div style="text-align:center" id="paginpage" class="unprintable">
		<h5 style="font-family:Kanit;margin-top:10px">แสดงหน้าที่ <text id="current_table" style="font-weight:700">...</text> จาก <text style="font-weight:700"><?= $all_table; ?></text> หน้า</h5>
	</div>
	<script>
	change_table();
	function change_table(){
		$('#btn_first').removeClass('disabled');
		$('#btn_back').removeClass('disabled');
		$('#btn_next').removeClass('disabled');
		$('#btn_last').removeClass('disabled');
		$('#btn_first').attr('onclick','con_table("first")');
		$('#btn_back').attr('onclick','con_table("back")');
		$('#btn_next').attr('onclick','con_table("next")');
		$('#btn_last').attr('onclick','con_table("last")');
		if($('#select_table').val()==1){
			$('#btn_first').removeAttr('onclick');
			$('#btn_first').addClass('disabled');
			$('#btn_back').removeAttr('onclick');
			$('#btn_back').addClass('disabled');
		}
		if($('#select_table').val()=='<?php echo $all_table;?>'){
			$('#btn_next').removeAttr('onclick');
			$('#btn_next').addClass('disabled');
			$('#btn_last').removeAttr('onclick');
			$('#btn_last').addClass('disabled');
		}
		for(let table = 1 ; table <= '<?php echo $all_table;?>' ; table++){
			$('#table_'+table).hide();
		}
		$('#table_'+$('#select_table').val()).show();
		$('#current_table').html($('#select_table').val());
	}
	
	function con_table(events){
		if(events == 'first'){
			$('#select_table').val(1);
		}else if(events == 'last'){
			$('#select_table').val('<?php echo $all_table; ?>');
		}else if(events == 'back'){
			$('#select_table').val($('#select_table').val()-1);
		}else if(events == 'next'){
			$('#select_table').val(parseInt($('#select_table').val())+1);
		}
		change_table();		
	}
	<?php 
		if($all_table == 1){
			echo "$('#paginshow').hide();";
			echo "$('#paginpage').hide();";
		}else{
			echo "$('#paginshow').show();";
			echo "$('#paginpage').show();";
		}
	?>
	
	</script>
	<?php
	}	
}


function paginx($row,$all_data){
	$all_table = ceil($all_data/$row);	
	if($all_data>0){
	?>
	<div id="paginshow" style="text-align:center" class="unprintable">
		<button title="หน้าแรก" style="margin-top:-8px;color:white;background-color:#262626" id="btn_first" onclick="con_table('first')" class="btn " >«</button>
		<button title="ย้อนกลับ" style="margin-top:-8px;color:white;background-color:#696969" id="btn_back" onclick="con_table('back')" class="btn " ><</button>
		<select id="select_table" onchange="change_table()" style="font-size:20px;text-align:center;border-radius:8px">
		<?php for($table = 1 ; $table <= $all_table ; $table++){ ?>
				<option value="<?php echo $table; ?>"><?php echo $table;?></option>
		<?php } ?>
		</select>
		<button title="ถัดไป" style="margin-top:-8px;color:white;background-color:#696969" id="btn_next" onclick="con_table('next')" class="btn" >></button>
		<button title="หน้าสุดท้าย" style="margin-top:-8px;color:white;background-color:#262626" id="btn_last" onclick="con_table('last')" class="btn">»</button>
	</div>
	<div style="text-align:center" id="paginpage" class="unprintable">
		<h5 style="font-family:Kanit;margin-top:10px">แสดงหน้าที่ <text id="current_table" style="font-weight:700">1</text> จาก <text style="font-weight:700"><?= $all_table; ?></text> หน้า</h5>
	</div>
	<script>
	//change_table();
	function change_table(){
		$('#btn_first').removeClass('disabled');
		$('#btn_back').removeClass('disabled');
		$('#btn_next').removeClass('disabled');
		$('#btn_last').removeClass('disabled');
		$('#btn_first').attr('onclick','con_table("first")');
		$('#btn_back').attr('onclick','con_table("back")');
		$('#btn_next').attr('onclick','con_table("next")');
		$('#btn_last').attr('onclick','con_table("last")');
		if($('#select_table').val()==1){
			$('#btn_first').removeAttr('onclick');
			$('#btn_first').addClass('disabled');
			$('#btn_back').removeAttr('onclick');
			$('#btn_back').addClass('disabled');
		}
		if($('#select_table').val()=='<?php echo $all_table;?>'){
			$('#btn_next').removeAttr('onclick');
			$('#btn_next').addClass('disabled');
			$('#btn_last').removeAttr('onclick');
			$('#btn_last').addClass('disabled');
		}
		for(let table = 1 ; table <= '<?php echo $all_table;?>' ; table++){
			$('#table_'+table).hide();
		}
		$('#table_'+$('#select_table').val()).show();
		$('#current_table').html($('#select_table').val());
		changePage();
	}
	
	function con_table(events){
		if(events == 'first'){
			$('#select_table').val(1);
		}else if(events == 'last'){
			$('#select_table').val('<?php echo $all_table; ?>');
		}else if(events == 'back'){
			$('#select_table').val($('#select_table').val()-1);
		}else if(events == 'next'){
			$('#select_table').val(parseInt($('#select_table').val())+1);
		}
		change_table();		
	}
	<?php 
		if($all_table == 1){
			echo "$('#paginshow').hide();";
			echo "$('#paginpage').hide();";
		}else{
			echo "$('#paginshow').show();";
			echo "$('#paginpage').show();";
		}
	?>
	
	</script>
	<?php
	}	
}

function data_date($type){	//type --> true :print-only , false  : print + display
	$years = date('Y')+543;
	$months = date('m');
	if($months == 1 ){
		$months = 'ม.ค.';
	}elseif($months == 2 ){
		$months = 'ก.พ.';
	}elseif($months == 3 ){
		$months = 'มี.ค.';
	}elseif($months == 4 ){
		$months = 'เม.ย.';
	}elseif($months == 5 ){
		$months = 'พ.ค.';
	}elseif($months == 6 ){
		$months = 'มิ.ย.';
	}elseif($months == 7 ){
		$months = 'ก.ค.';
	}elseif($months == 8 ){
		$months = 'ส.ค.';
	}elseif($months == 9 ){
		$months = 'ก.ย.';
	}elseif($months == 10 ){
		$months = 'ต.ค.';
	}elseif($months == 11 ){
		$months = 'พ.ย.';
	}else{
		$months = 'ธ.ค.';
	}
	$days = intval(date('d'));
	$dates = $days.' '.$months.' '.$years;	
	
	if($type == TRUE){
		echo '<div style="display:inline;z-index:1;text-align:right;" class="pull-right data_date">';
	}else{
		echo '<div style="display:inline;z-index:1;text-align:right;" class="pull-right ">';
	}
	echo '<b>ข้อมูล ณ วันที่ '.$dates.'</b>';
	echo '</div>';
}

function clear_cp_session(){
	unset($_SESSION['aff_id']);
	unset($_SESSION['cp_name']);
	unset($_SESSION['pvn_name']);
	unset($_SESSION['page']);
	unset($_SESSION['report_cp']);
	unset($_SESSION['report_pvn']);
	unset($_SESSION['report_aff']);
	unset($_SESSION['report_page']);
	unset($_SESSION['typeX']);
	unset($_SESSION['title']);
	unset($_SESSION['sql']);
	unset($_SESSION['sql1']);
	unset($_SESSION['sql2']);
}

function dead_line($start,$day,$type){
	if($type == null OR $type ==''){
		$type = 'th';
	}
	if($type == 'th'){
		$ad_year = substr($start,0,4)-543;
		$start = $ad_year.substr($start,4);
	}
	$dead_line = date_create($start);
	$dead_line = date_add($dead_line, date_interval_create_from_date_string(' + '.($day-1).' days'));
	
	
	return $dead_line;
}

function IntToMonth($num,$language){
	$month = array();
	$month['th'] = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
	$month['en'] = ['Jan.','Feb.','Mar.','Apr.','May.','Jun.','Jul.','Aug.','Sep.','Oct.','Nov.','Dec.'];
	$month['th-full'] = ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภ่คม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
	$month['en-full'] = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	if($num<1 OR $num>12){
		return 'Error!';
	}
	else{
		if($language!='th' OR $language!='en' OR $language!='th-full' OR $language!='en-full'){
			return $month[$language][$num-1];
		}
		else{
			return $month['th'][$num-1];
		}
	}
}
function status_snd($type, $fst_step, $snd_step, $trd_step, $petition ,$start, $stop){
	$title = '';	
	$color = 'transparent';	
	$font = 'black';	
	$day = $sql = $ot1 = $ot2 = '-';
	if($fst_step > 0){
		if($snd_step > 0){
			if(strlen($start)==10){
				if($fst_step != 6){
					if($type=='m35'){
						$sql = "SELECT m35_snd_time*7 as ot1 , m35_snd_time*7*2 as ot2  FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$fst_step."' AND m35_snd_step_sub = '".$snd_step."'";
					}else{
						$sql = "SELECT sub_ot1 as ot1 , sub_ot2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$fst_step."' AND sub_no = '".$snd_step."'";
					}
				}else{
					if($petition != 1){
						if($type=='m35'){
							$sql = "SELECT m35_snd_time1*7 as ot1 , m35_snd_time1*7*2 as ot2  FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$fst_step."' AND m35_snd_step_sub = '".$snd_step."'";
						}else{
							$sql = "SELECT sub_time1*7 as ot1 , sub_time1*7*2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$fst_step."' AND sub_no = '".$snd_step."'";
						}
					}else{
						if($type=='m35'){
							$sql = "SELECT m35_snd_time2*7 as ot1 , m35_snd_time2*7*2 as ot2  FROM ref_m35_snd_step WHERE m35_snd_step_main = '".$fst_step."' AND m35_snd_step_sub = '".$snd_step."'";		
						}else{
							$sql = "SELECT sub_time2*7 as ot1 , sub_time2*7*2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$fst_step."' AND sub_no = '".$snd_step."'";
						}
					}
				}
				$result = selectSql($sql);
				$ot1 = $result[0]['ot1'];
				$ot2 = $result[0]['ot2'];
				$start = convert_year('b2c',$start);
				$date_start = date_create($start);
				$deadline = date_add($date_start,date_interval_create_from_date_string(($ot1-1)." days"));
				if(strlen($stop)==10){ //stop - stop
					$stop = convert_year('b2c',$stop);
					$date_stop = date_create($stop);
					$diff=date_diff($deadline,$date_stop);
				}else{ //deadline - current
					$current = date_create();
					$diff=date_diff($deadline,$current);
				}
				$day = intval($diff->format("%R%a"));
				
				if($day<=0){
					$color = '#2D882D !important';
					$font = 'white';
					$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"เหลือเวลา : ".$day." วัน";
					$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
				}elseif($day<=$ot1){
					$color = '#FFE841!important';
					$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น(ล่าช้าเล็กน้อย)":"ล่าช้า : ".$day." วัน";
					$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
				}elseif($day<=$ot2){
					$color = 'orange';
					$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น(ล่าช้าปานกลาง)":"ล่าช้า : ".$day." วัน";
					$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
				}else{
					$color = 'red';
					$font = 'white';
					$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น(ล่าช้ามาก)":"ล่าช้า : ".$day." วัน";
					$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
				}
				//$a = ['start' => $start , 'stop'=> $stop , 'deadline'=>date_format($deadline,'Y-m-d'),'day'=>$day];
			}else{
				$title = 'ไม่ระบุวันที่เริ่มต้น';
				$sub_title = 'ไม่ระบุวันที่เริ่มต้น';
			}	
		}
	}
	$a = ['bg-style'=> 'background:'.$color.';color:'.$font.';' , 'day'=>$day ,/*'sql'=>$sql ,*/'ot1'=>$ot1, 'ot2'=>$ot2,'type','title'=>$title,'sub_title'=>$sub_title];
	return $a;
}

function status_trd($type, $fst_step, $snd_step, $trd_step, $petition ,$start, $stop){
	$title = '';	
	$color = 'transparent';	
	$font = 'black';	
	$day = $sql = $ot1 = $ot2 = '-';
	if($fst_step>0){
		if($snd_step>0){
			if($trd_step>0){
				if(strlen($start)==10){
					if($type=='m35'){
						$sql = "SELECT m35_trd_time*7 as ot1 , m35_trd_time*7*2 as ot2 FROM ref_m35_trd_step WHERE m35_trd_step_fst = '".$fst_step."' AND m35_trd_step_snd = '".$snd_step."' AND m35_trd_step_trd = '".$trd_step."'";
					}else{
						$sql = "SELECT trd_time*7 as ot1 , trd_time*7*2 as ot2 FROM ref_up_trd_step WHERE trd_fst = '".$fst_step."' AND trd_snd = '".$snd_step."' AND trd_step = '".$trd_step."'";
					}
					$result = selectSql($sql);
					$ot1 = $result[0]['ot1'];
					$ot2 = $result[0]['ot2'];
					$start = convert_year('b2c',$start);
					$date_start = date_create($start);
					$deadline = date_add($date_start,date_interval_create_from_date_string(($ot1-1)." days"));
					if(strlen($stop)==10){ //stop - stop
						$stop = convert_year('b2c',$stop);
						$date_stop = date_create($stop);
						$diff=date_diff($deadline,$date_stop);
					}else{ //deadline - current
						$current = date_create();
						$diff=date_diff($deadline,$current);
					}
					$day = intval($diff->format("%R%a"));
					
					if($day<=0){
						$color = '#2D882D !important';
						$font = 'white';
						$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"เหลือเวลา : ".$day." วัน";
						$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
					}elseif($day<=$ot1){
						$color = '#FFE841!important';
						$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น(ล่าช้าเล็กน้อย)":"ล่าช้า : ".$day." วัน";
						$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
					}elseif($day<=$ot2){
						$color = 'orange';
						$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น(ล่าช้าปานกลาง)":"ล่าช้า : ".$day." วัน";
						$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
					}else{
						$color = 'red';
						$font = 'white';
						$title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น(ล่าช้ามาก)":"ล่าช้า : ".$day." วัน";
						$sub_title = ($date_stop!=null)? "ดำเนินการเสร็จสิ้น":"อยู่ระหว่างดำเนินการ";
					}
				}else{
					$title = "ไม่ระบุวันที่เริ่มต้น";
					$sub_title = "ไม่ระบุวันที่เริ่มต้น";
				}
			}
		}	
	}
	$a = ['bg-style'=> 'background:'.$color.';color:'.$font.';' , 'day'=>$day ,/*'sql'=>$sql ,*/'ot1'=>$ot1, 'ot2'=>$ot2,'type','title'=>$title,'sub_title'=>$sub_title];
	return $a;
}



function convert_year($type,$date){
	$date = date_create($date);
	$year = date_format($date,'Y');
	$month = date_format($date,'m');
	$day = date_format($date,'d');
	if($type=='b2c' OR $type=='B2C' OR $type=='B2c' OR $type=='b2C' ){
		$year = $year-543;
	}
	elseif($type=='c2b' OR $type=='C2B' OR $type=='C2b' OR $type=='c2B'){
		$year = $year+543;
	}
	return $year.'-'.$month.'-'.$day;
}


function logs($act,$type,$target){
	$sql = "INSERT INTO tb_log(log_user,log_action,log_type,log_target) VALUE('".$_SESSION['intra_czid']."','".$act."','".$type."',".$target.")";
	in_up_delSql($sql);
	//echo $sql;
}
?>
