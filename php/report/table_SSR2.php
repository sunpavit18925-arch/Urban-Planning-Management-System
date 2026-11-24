<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  //echo $_SESSION['sql'];
  //echo $_POST['name'];
  //echo $_POST['affiliation'];
  //echo $_POST['province'];
  //echo $_POST['aff_id'];
 /* if($isset($_POST['name'])){$_SESSION['name'] = $_POST['name'];}
  if($isset($_POST['province'])){$_SESSION['province'] = $_POST['province'];}
  if($isset($_POST['aff_id'])){$_SESSION['aff_id'] = $_POST['aff_id'];}
  if($isset($_POST['affiliation'])){$_SESSION['affiliation'] = $_POST['affiliation'];}*/
 ?>
<table id="table_show_step_result" class="table table-bordered" style="border-width:1px;border-style:solid;border-color:black">
	<thead>
		<tr style="background-color: #0AAAFA;color:white">
			<td style="text-align:center" rowspan="2">ลำดับ </td>
			<td style="text-align:center" rowspan="2">จังหวัด</td>
			<td style="text-align:center" rowspan="2">รายชื่อผังเมืองรวม</td>
			<td style="text-align:center" colspan="2" rowspan="2">ผู้ดำเนินการ</td>
			<td style="text-align:center" rowspan="2">สถานะผัง</td>
			<td style="text-align:center" colspan="2">ขั้นตอนดำเนิการ</td>
			<td style="text-align:center" rowspan="2">ผลดำเนินการ</td>
			<?php if($_SESSION['status']!=0){ ?><td style="text-align:center" rowspan="2">หมายเหตุ</td><?php } ?>
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
			<td style="text-align:center">ขั้นตอนรอง</td>
		</tr>
	</thead>
	<tbody style="background-color:white">
		<?php
		/*if(isset($_SESSION['sql1']) AND !isset($_SESSION['sql2']){
			$sql = $_SESSION['sql1'];
		}
		if(isset($_SESSION['sql1']) AND !isset($_SESSION['sql2']){
			$sql = $_SESSION['sql1'];
		}*/
		if($_POST['aff_id']==null OR $_POST['province']==null OR $_POST['name']==null){
			$sql = $_SESSION['sql'];
			unset($_SESSION['sql2']);
			//echo "null";
		}
		if($_POST['aff_id']!=null OR $_POST['province']!=null OR $_POST['name']!=null OR $_POST['affiliation']){
			//unset($_SESSION['sql1']);
			unset($_SESSION['sql1']);
			//echo "a";
		}
		
		if(isset($_SESSION['sql1']) OR isset($_SESSION['sql2'])){
			if(isset($_SESSION['sql2'])){
				$sql = $_SESSION['sql2'] ;
				//echo '2 -'.$sql;
			}else{
				$sql = $_SESSION['sql1'] ;
				//echo '1 -'.$sql;
			}
		}

		else{
			if($_POST['event']=='cityplan'){
				if($_POST['affiliation']!=NULL){
					//$sql ="SELECT * FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' AND_name LIKE '%".$_POST['province']."%' ORDER BY pvn_name ASC";
				}
				elseif($_POST['province']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' AND pvn_name LIKE '%".$_POST['province']."%' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_type, ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				else{
					$sql ="SELECT * FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' ORDER BY pvn_name ASC"; 
					$_SESSION['sql1'] = $sql;
				}
			}
			elseif($_POST['event']=='type'){
				if($_POST['affiliation']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_type, ref_province,ref_affiliation WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_type = type_id AND type_id = '".$_POST['type_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_type, ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_type = type_id AND type_id = '".$_POST['type_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_type, ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_type = type_id AND type_id = '".$_POST['type_id']."' ORDER BY pvn_name ASC"; 
					$_SESSION['sql2'] = $sql;
				}
				else{
					$sql ="SELECT * FROM tb_cityplan , ref_type, ref_province,ref_affiliation WHERE pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_type = type_id AND type_id = '".$_POST['type_id']."' ORDER BY pvn_name ASC"; 
					$_SESSION['sql1'] = $sql;
				}

			}
			elseif($_POST['event']=='step'){
				if($_POST['affiliation']!=NULL){
					$sql = "SELECT tb_cityplan.cp_name ,ref_province.pvn_name,ref_operate_fst.opt_fst_name,ref_operate_snd.opt_snd_name , ref_status.status_name ,ref_up_fst_step.step_id,ref_up_fst_step.step_name,ref_up_snd_step.sub_no,ref_up_snd_step.sub_name,COUNT(tb_note.note_cp) 
							FROM tb_cityplan 
							LEFT JOIN tb_note ON tb_cityplan.cp_no = tb_note.note_cp 
							LEFT JOIN ref_up_fst_step ON tb_cityplan.cp_step = ref_up_fst_step.step_id  
							LEFT JOIN ref_up_snd_step ON tb_cityplan.cp_step_1 = ref_up_snd_step.sub_no AND ref_up_snd_step.sub_step =  ref_up_fst_step.step_id  
							LEFT JOIN ref_province ON ref_province.pvn_id = tb_cityplan.cp_province 
							LEFT JOIN ref_operate_fst ON ref_operate_fst.opt_fst_id = tb_cityplan.cp_owner
							LEFT JOIN ref_operate_snd ON ref_operate_snd.opt_snd_id = tb_cityplan.cp_operator
							LEFT JOIN ref_status ON ref_status.status_id = tb_cityplan.cp_status
							LEFT JOIN  ref_affiliation ON ref_affiliation.aff_id = ref_province.pvn_affiliation 
							WHERE ref_affiliation.aff_id = '".$_POST['affiliation']."' AND tb_cityplan.cp_step = '".$_POST['step_id']."'
							GROUP BY tb_cityplan.cp_no";
					//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_status = status_id ORDER BY cp_no ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql = "SELECT tb_cityplan.cp_name ,ref_province.pvn_name,ref_operate_fst.opt_fst_name,ref_operate_snd.opt_snd_name , ref_status.status_name ,ref_up_fst_step.step_id,ref_up_fst_step.step_name,ref_up_snd_step.sub_no,ref_up_snd_step.sub_name,COUNT(tb_note.note_cp) 
							FROM tb_cityplan 
							LEFT JOIN tb_note ON tb_cityplan.cp_no = tb_note.note_cp 
							LEFT JOIN ref_up_fst_step ON tb_cityplan.cp_step = ref_up_fst_step.step_id  
							LEFT JOIN ref_up_snd_step ON tb_cityplan.cp_step_1 = ref_up_snd_step.sub_no AND ref_up_snd_step.sub_step =  ref_up_fst_step.step_id  
							LEFT JOIN ref_province ON ref_province.pvn_id = tb_cityplan.cp_province
							LEFT JOIN ref_operate_fst ON ref_operate_fst.opt_fst_id = tb_cityplan.cp_owner
							LEFT JOIN ref_operate_snd ON ref_operate_snd.opt_snd_id = tb_cityplan.cp_operator
							LEFT JOIN ref_status ON ref_status.status_id = tb_cityplan.cp_status
							WHERE ref_province.pvn_name LIKE '%".$_POST['province']."%' AND tb_cityplan.cp_step = '".$_POST['step_id']."'
							GROUP BY tb_cityplan.cp_no";
					//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_status = status_id ORDER BY cp_no ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
					$sql = "SELECT tb_cityplan.cp_name ,ref_province.pvn_name,ref_operate_fst.opt_fst_name,ref_operate_snd.opt_snd_name , ref_status.status_name ,ref_up_fst_step.step_id,ref_up_fst_step.step_name,ref_up_snd_step.sub_no,ref_up_snd_step.sub_name,COUNT(tb_note.note_cp) 
							FROM tb_cityplan 
							LEFT JOIN tb_note ON tb_cityplan.cp_no = tb_note.note_cp 
							LEFT JOIN ref_up_fst_step ON tb_cityplan.cp_step = ref_up_fst_step.step_id  
							LEFT JOIN ref_up_snd_step ON tb_cityplan.cp_step_1 = ref_up_snd_step.sub_no AND ref_up_snd_step.sub_step =  ref_up_fst_step.step_id  
							LEFT JOIN ref_province ON ref_province.pvn_id = tb_cityplan.cp_province
							LEFT JOIN ref_operate_fst ON ref_operate_fst.opt_fst_id = tb_cityplan.cp_owner
							LEFT JOIN ref_operate_snd ON ref_operate_snd.opt_snd_id = tb_cityplan.cp_operator
							LEFT JOIN ref_status ON ref_status.status_id = tb_cityplan.cp_status
							WHERE tb_cityplan.cp_name LIKE '%".$_POST['name']."%' AND tb_cityplan.cp_step = '".$_POST['step_id']."'
							GROUP BY tb_cityplan.cp_no";
					$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_status = status_id ORDER BY cp_no ASC"; 
					$_SESSION['sql2'] = $sql;
				}
				else{
					$sql = "SELECT tb_cityplan.cp_name ,ref_province.pvn_name,ref_operate_fst.opt_fst_name,ref_operate_snd.opt_snd_name , ref_status.status_name ,ref_up_fst_step.step_id,ref_up_fst_step.step_name,ref_up_snd_step.sub_no,ref_up_snd_step.sub_name,COUNT(tb_note.note_cp) 
							FROM tb_cityplan 
							LEFT JOIN tb_note ON tb_cityplan.cp_no = tb_note.note_cp 
							LEFT JOIN ref_up_fst_step ON tb_cityplan.cp_step = ref_up_fst_step.step_id  
							LEFT JOIN ref_up_snd_step ON tb_cityplan.cp_step_1 = ref_up_snd_step.sub_no AND ref_up_snd_step.sub_step =  ref_up_fst_step.step_id  
							LEFT JOIN ref_province ON ref_province.pvn_id = tb_cityplan.cp_province
							LEFT JOIN ref_operate_fst ON ref_operate_fst.opt_fst_id = tb_cityplan.cp_owner
							LEFT JOIN ref_operate_snd ON ref_operate_snd.opt_snd_id = tb_cityplan.cp_operator
							LEFT JOIN ref_status ON ref_status.status_id = tb_cityplan.cp_status
							WHERE tb_cityplan.cp_step = '".$_POST['step_id']."'
							GROUP BY tb_cityplan.cp_no";
					//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_status = status_id ORDER BY cp_no ASC"; 
					$_SESSION['sql1'] = $sql;
				}
			}elseif($_POST['event']=='status'){
				if($_POST['affiliation']!=NULL){
					$sql ="SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status ,ref_affiliation WHERE pvn_affiliation = aff_id AND aff_id = '".$_POST['affiliation']."' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
					//$sql = "SELECT * FROM tb_cityplan , ref_status , ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_status = status_id AND status_id = '".$_POST['status_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
					$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
					//$sql = "SELECT * FROM tb_cityplan , ref_status , ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_status = status_id AND status_id = '".$_POST['status_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				else{
					
					/*$sql = "SELECT tb_cityplan.cp_name ,ref_province.pvn_name,ref_operate_fst.opt_fst_name,ref_operate_snd.opt_snd_name , ref_status.status_name ,ref_up_fst_step.step_id,ref_up_fst_step.step_name,ref_up_snd_step.sub_no,ref_up_snd_step.sub_name,COUNT(tb_note.note_cp) 
							FROM tb_cityplan 
							LEFT JOIN tb_note ON tb_cityplan.cp_no = tb_note.note_cp 
							LEFT JOIN ref_up_fst_step ON tb_cityplan.cp_step = ref_up_fst_step.step_id  
							LEFT JOIN ref_up_snd_step ON tb_cityplan.cp_step_1 = ref_up_snd_step.sub_no AND ref_up_snd_step.sub_step =  ref_up_fst_step.step_id  
							LEFT JOIN ref_province ON ref_province.pvn_id = tb_cityplan.cp_province
							LEFT JOIN ref_operate_fst ON ref_operate_fst.opt_fst_id = tb_cityplan.cp_owner
							LEFT JOIN ref_operate_snd ON ref_operate_snd.opt_snd_id = tb_cityplan.cp_operator
							LEFT JOIN ref_status ON ref_status.status_id = tb_cityplan.cp_status
							WHERE tb_cityplan.cp_step = '".$_POST['step_id']."'
							GROUP BY tb_cityplan.cp_no";*/
					//$sql ="SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name ASC"; 
					$_SESSION['sql1'] = $sql;
				}
			}
			elseif($_POST['event']=='stage'){
				if($_POST['affiliation']!=NULL){
					$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage , ref_affiliation WHERE pvn_affiliation = aff_id AND aff_id = '".$_POST['affiliation']."' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					//$sql ="SELECT * FROM tb_cityplan , ref_stage ,ref_province,ref_affiliation WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE pvn_name LIKE '%".$_POST['province']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					//$sql ="SELECT * FROM tb_cityplan , ref_stage , ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
				  $sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
				  //$sql ="SELECT * FROM tb_cityplan , ref_stage , ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id  AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				else{
					$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
				  //$sql ="SELECT * FROM tb_cityplan , ref_stage , ref_province,ref_affiliation WHERE pvn_affiliation = aff_id AND cp_province = pvn_id  AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql1'] = $sql;
				}
			}
			elseif($_POST['event']=='effective'){
				if($_POST['affiliation']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_effective ,ref_province,ref_affiliation WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_effective = eff_id AND eff_id = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_effective , ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_effective = eff_id AND eff_id = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;	
				}
				elseif($_POST['name']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_effective , ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_effective = eff_id AND eff_id = '".$_POST['eff_id']."' ORDER BY pvn_name ASC"; 
					$_SESSION['sql2'] = $sql;
				}
				else{
					$sql ="SELECT * FROM tb_cityplan , ref_effective , ref_province,ref_affiliation WHERE pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_effective = eff_id AND eff_id = '".$_POST['eff_id']."' ORDER BY pvn_name ASC"; 
					$_SESSION['sql1'] = $sql;
				}
			}
			elseif($_POST['event']=='operate'){
				if($_POST['affiliation']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_operate_fst , ref_province,ref_affiliation WHERE aff_id = '".$_POST['affiliation']."'AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_operate_fst , ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
					$sql ="SELECT * FROM tb_cityplan , ref_operate_fst , ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no ASC"; 
					$_SESSION['sql2'] = $sql;
				}
				else{
					$sql ="SELECT * FROM tb_cityplan , ref_operate_fst , ref_province,ref_affiliation WHERE cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no ASC"; 
					$_SESSION['sql1'] = $sql;
				}
			}
			elseif($_POST['event']=='status_effective'){
				if($_POST['affiliation']!=NULL){
					$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status , ref_affiliation WHERE pvn_affiliation = aff_id AND aff_id = '".$_POST['affiliation']."' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = 2 AND cp_effective = 0 ";
					//$sql ="SELECT * FROM tb_cityplan , ref_operate_fst , ref_province,ref_affiliation WHERE aff_id = '".$_POST['affiliation']."'AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = 2 AND cp_effective = 0 ";
					//$sql ="SELECT * FROM tb_cityplan , ref_operate_fst , ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
					$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = 2 AND cp_effective = 0";
					//$sql = "SELECT * FROM tb_cityplan , ref_operate_fst , ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no ASC"; 
					$_SESSION['sql2'] = $sql;
				}
				else{
					$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = 2 AND cp_effective = 0 ";
					$_SESSION['sql1'] = $sql;
				}
			}
			if(isset($_SESSION['sql1'])){
				$_SESSION['sql'] = $_SESSION['sql1'];
			}
		}
		
		/*
		event = stage	
		
		
			$sql = "SELECT cp_no , pvn_name , cp_name , opt_fst_name,opt_snd_name, status_name, step_name , sub_name ,cp_note FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step";
		
		*/			
		echo $sql;
		$result = selectSql($sql); ?>
		<div class="row">
			<div class="col-xs-12 col-md-3">
				<?php echo '<h3 style="font-family:Kanit" id="search_value">จำนวนที่ค้นพบ  : ',count($result),'</h3>'; ?>
			</div>
			<div class="col-xs-12 col-md-9">
				<div class="row" style="margin-top:22px">
					<div class="col-xs-1 col-md-1" style="background-color:green;height:25px"></div>
					<div class="col-xs-5 col-md-2" style="margin-top:4px;margin-left:-10px">อยู่ในระยะดำเนินการ</div>
					<div class="col-xs-1 col-md-1" style="background-color:#FFE841 ;height:25px"></div>
					<div class="col-xs-5 col-md-2" style="margin-top:4px;margin-left:-10px">ล่าช้าเล็กน้อย</div>
					<div class="col-xs-1 col-md-1" style="background-color:orange;height:25px"></div>
					<div class="col-xs-5 col-md-2" style="margin-top:4px;margin-left:-10px">ล่าช้าปานกลาง</div>
					<div class="col-xs-1 col-md-1" style="background-color:red;height:25px"></div>
					<div class="col-xs-5 col-md-2" style="margin-top:4px;margin-left:-10px">ล่าช้ามาก</div>
				</div>
			</div>
		</div>
		<?php 
		if(count($result)==0){
			echo "<h2 style='text-align:center;fon-family:Kanit'>ไม่พบข้อมูลที่ค้นหา</h2>";
			echo "<script>$('#table_show_step_result').hide();</script>";
		}
		$i = 0;
		foreach($result as $row){ $i++;
		$color="";
		$cp_note =NULL;
		$todays = date("Y");
		$toYear = date("Y")+543;
		$toDayMonth = date("-m-d");
		$now = $toYear.$toDayMonth;
		/*if($row['cp_step_stop']!="" OR $row['cp_step_stop']!=null){
		//echo $row['step_ot1']," ",$row['step_ot2']," ",$row['step_ot3']," ",$row['step_ot4'];
		
			$todays = date("Y");
			$toYear = date("Y")+543;
			$toDayMonth = date("-m-d");
			$now = $toYear.$toDayMonth;
			//echo $now," - ",$row['cp_step_stop'];
			$overtime=date_create($row['cp_step_stop']);
			$today=date_create($now);
			$diff=date_diff($overtime,$today);
			$dayst = $diff->format("%R%a");
			$dayst = intval($dayst);
			//echo 'Dayst = ',$dayst,'<br>';
			//$dayst = 250;		
			if($dayst <= 0){
				$color = "background-color:#2D882D;color:white";
				$cp_note = "เหลือเวลา ".$daynd." วัน";
			}
			elseif($dayst > 0 AND $dayst <= $row['step_ot1']){
				//$color = "background-color:#CEFFAE";
				$color = "background-color:#FFC107";
				$cp_note = "ล่าช้า ".$dayst." วัน";
			}
			elseif($dayst > $row['step_ot1'] AND $dayst <= $row['step_ot2']){
				$color = "background-color:orange";
				$cp_note = "ล่าช้า ".$dayst." วัน";
			}
			else{
				$color = "background-color:red;color:white";
				$cp_note = "ล่าช้า ".$dayst." วัน";
			}
		}*/
		?>
		<tr >
			<td style="text-align:center"><?php echo $i;?></td>
			<td style="text-align:center"><?php echo $row['pvn_name'];?></td>
			<td style="text-align:center"><?php echo $row['cp_name'];?></td>
			<td style="text-align:center"><?php echo $row['opt_fst_name'];?></td>
			<td style="text-align:center"><?php echo $row['opt_snd_name'];?></td>
			<td style="text-align:center"><?php echo $row['status_name'];?></td>
			<td style="text-align:center;<?php //echo $color;?>">
				<?php 
					if($row['cp_step']==0){
						echo '-';
					}else{
						echo '<b>'.$row['cp_step'].'</b>';
					}
				?>
			</td>
			<?php 
				if($row['cp_step_1']!=0 AND $row['cp_step_1_stop']!=NULL /*AND $color=="background-color:#2D882D;color:white"*/){
					if($row['sub_ot1']!=null AND $row['sub_ot2']!=null){
						//echo $row['cp_step_1_stop']," - ",$now," <br>";
						$date1=date_create($row['cp_step_1_stop']);
						$date2=date_create($now);
						//echo date_format($date1,"Y-m-d");
						//echo date_format($date2,"Y-m-d");
						$diff2 = date_diff($date1,$date2);
						$daynd = $diff2->format("%R%a");
						$daynd = intval($daynd);
						//echo 'daynd = ',$daynd,'<br>';
						if($daynd <= 0){
							$color = "background-color:#2D882D;color:white";
							//echo $color;
							$cp_note = "เหลือเวลา ".($daynd*-1)." วัน";
						}
						elseif($daynd > 0 AND $daynd <= $row['sub_ot1']){
							//$color = "background-color:#CEFFAE";
							$color = "background-color:#FFE841 ";
							//echo $color;
							$cp_note = "ล่าช้า ".$daynd." วัน";
						}

						elseif($daynd > $row['sub_ot1'] AND $daynd <= $row['sub_ot2']){
							$color = "background-color:orange";
							//echo $color;
							$cp_note = "ล่าช้า ".$daynd." วัน";							
						}
						else{
							$color = "background-color:red;color:white";
							//echo $color;
							$cp_note = "ล่าช้า ".$daynd." วัน";
							//$row['cp_note'] = $cp_note;
						}
					}
				}

				else{
					
				}
			?>
			<td style="text-align:center;<?php echo $color;?>">
				<?php
					if($row['cp_step_1']==0){
						echo '-';
					}else{
						echo $row['cp_step'],'.',$row['cp_step_1'];
					}
				?>
			</td>
			<td style="text-align:center;">-<?php //echo $cp_note;?></td>
			
			<?php
				if($_SESSION['status']!=0){ ?>
					<td style="text-align:center;color:black"> <?php 
					echo $row['COUNT(tb_note.note_cp)'];
					//echo $result[0]['COUNT(*)'];
					if($row['COUNT(note_cp)']=="0" OR $row['COUNT(note_cp)']==null){ ?>					
							<span id="Addbtn_<?php echo $row['cp_no'];?>" class="plus"><a onclick="Addnote(<?php echo $row['cp_step'];?>,<?php echo $row['cp_step_1'];?>,<?php echo $row['cp_no'];?>);"><i class="fa fa-plus"></i></a></span> 
							<span hidden id="loading_<?php echo $row['cp_no'];?>"><i class="fa fa-refresh fa-spin"></i></span>
							
							<?php
					}
					else{ ?> 
							<span class="list-alt"><a onclick="Readstepnote(<?php echo $row['cp_no']; ?>);"><i class="fa fa-list-alt"></i></a></span> <?php	
					} ?> 
					</td> <?php
				} ?>
		</tr> 
		<?php } ?>
	</tbody>	
</table>

	<div class="modal fade" id="modal-note">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่ม/แก้ไขหมายเหตุ</h4>
              </div>
              <div class="modal-body">
				<form>	
				<div class="form-group" id="note_step_show">
					<span id="note_step">step......</span>	
				</div>
				
				<div class="form-group" id="note_step_1_show">
					<span id="note_step_1">step_1......</span>	
				</div>
				
				

				<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>
				
				<input type="hidden" id="note_id" value="">
				<input type="hidden" id="note_cp" value="">
				<input type="hidden" name="note_id" class="form-control" id="note_id" value="" >
                <input type="hidden" id="note_uper" value="<?php echo $_SESSION['intra_czid']; ?>">				
                <input type="hidden" id="note_event" value="">
				<input type="hidden" id="note_step_hidden" value="">
				<input type="hidden" id="note_step_1_hidden" value="">
				</form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="Savenote();">บันทึก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="Delnote();">ลบหมายเหตุ</button>
              </div>
            </div>

          </div>

        </div>
<script>
	//$('#search_value').hide();
	function Addnote(x,y,z){	
		//alert('x : '+x+' , y : '+y);
		$.post('app/manage.php',{
			event :'ru_step+sub',
			step_id : x,
			sub_no : y
		},function(data){
			$('#delBtn').hide();
			$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_step_hidden').val(x);
			$('#note_step_1_hidden').val(y);
			$('#note_cp').val(z);
			$('#modal-note').modal('show');
			$('#note_event').val('c_note+u_cp');
			$('#note_remark').val("");
			$('#note_id').val("");
		},'json');	
	}
	
	
	function Savenote(){
		if($('#note_remark').val()=="" || $('#note_remark').val()==null){
			alert("กรุณาระบุหมายเหตุ");
		}else{
			let x = $('#note_cp').val();
			$.post('app/manage.php',{
				event : $('#note_event').val(),
				note_remark : $('#note_remark').val(),
				note_step : $('#note_step_hidden').val(),
				note_step_1 : $('#note_step_1_hidden').val(),
				note_cp : $('#note_cp').val(),
				note_id : $('#note_id').val()
			},function(data){
				$('#modal-note').modal('hide');
				$('#Addbtn_'+x).hide();
				$('#loading_'+x).show();
				alert(data);
				$('#subshow').load('report/table_SSR2.php');
				console.log(data);
			});
		}
	}
	
	function Readnote(x){
		$.post('app/manage.php',{
			event : 'ru_note2',
			cp_no : x
		},function(data){
			$('#delBtn').show();
			$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_remark').val(data.note_remark);
			$('#note_cp').val(data.cp_no);
			$('#note_event').val('u_note+u_cp');
			$('#note_id').val(data.note_id);
			//alert(data);
			//console.log(data);
			$('#modal-note').modal('show');
		},'json');
	}
	
	function Readstepnote(x){
		$.post('report/table_step_note.php',{
			cp_no : x
		},function(data){
			//console.log(data);
			$('#mainshow').html(data);
		});
	}
	
	function Delnote(){
		if(confirm("คุณต้องการลบหมายเหตุใช่หรือไม่ ? ")){
			$.post('app/manage.php',{
				event : 'd_note+u_cp',
				note_id : $('#note_id').val(),
				cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				$('#subshow').load('report/table_SSR2.php');
			});
		}
	}

</script>
