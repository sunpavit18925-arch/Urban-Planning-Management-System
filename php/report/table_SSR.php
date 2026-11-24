<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	
	if(TRUE){
		$_SESSION['report_cp'] = $_POST['name'];
		$_SESSION['report_pvn'] = $_POST['province'];
		$_SESSION['report_aff'] = $_POST['affiliation'];
	}

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
	if($_POST['sub_event']=="consider"){
		unset($_SESSION['sql1']);
		unset($_SESSION['sql2']);
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
			/*if($_POST['affiliation']!=NULL){
				//$sql ="SELECT * FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' AND_name LIKE '%".$_POST['province']."%' ORDER BY pvn_name ASC";
			}*/
			if($_POST['province']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' AND pvn_name LIKE '%".$_POST['province']."%' ORDER BY pvn_name ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['name']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' ORDER BY pvn_name ASC";
				$_SESSION['sql2'] = $sql;
			}
			else{
				$sql ="SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."' ORDER BY pvn_name ASC"; 
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
			if($_POST['sub_event']=='consider'){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_status = 1  AND cp_consider = '".$_POST['consider']."' ORDER BY cp_no ASC"; 
				$_SESSION['sql1'] = $sql;
			}else{
				if($_POST['step_id']>0){
					if($_POST['affiliation']!=NULL){
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_step_1 > 0 AND cp_status = status_id AND cp_status = 1 ORDER BY cp_no ASC";
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['province']!=NULL){
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_step_1 > 0 AND cp_status = status_id AND cp_status = 1 ORDER BY cp_no ASC";
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['name']!=NULL){
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_step_1 > 0 AND cp_status = status_id AND cp_status = 1 ORDER BY cp_no ASC"; 
						$_SESSION['sql2'] = $sql;
					}
					else{
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."'  AND cp_step_1 > 0 AND cp_status = status_id AND cp_status = 1  ORDER BY cp_no ASC"; 
						$_SESSION['sql1'] = $sql;
					}
				}
				else{
					if($_POST['affiliation']!=NULL){
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_status = 1 ORDER BY cp_no ASC";
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['province']!=NULL){
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_status = 1 ORDER BY cp_no ASC";
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['name']!=NULL){
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_status = 1 ORDER BY cp_no ASC"; 
						$_SESSION['sql2'] = $sql;
					}
					else{
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_status = 1  ORDER BY cp_no ASC"; 
						$_SESSION['sql1'] = $sql;
					}
				}
			}
		}
		elseif($_POST['event']=='step2'){
			if($_POST['sub_event']=='consider'){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step = 0)  OR  (cp_step != 0 AND cp_step_1 = 0 )) AND cp_consider = '".$_POST['consider']."' ORDER BY cp_no ASC ";
			}
			else{
				if($_POST['step_id']>0){
					if($_POST['affiliation']!=NULL){
						//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_step_1 > 0 AND cp_status = status_id AND cp_stage = 3 ORDER BY cp_no ASC";
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step = '".$_POST['step_id']."' AND cp_step_1 != 0 AND cp_consider = 0)) ORDER BY cp_no ASC "; 
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['province']!=NULL){
						//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_step_1 > 0 AND cp_status = status_id AND cp_stage = 3 ORDER BY cp_no ASC";
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step = '".$_POST['step_id']."' AND cp_step_1 != 0 AND cp_consider = 0)) ORDER BY cp_no ASC "; 
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['name']!=NULL){
						//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_step_1 > 0 AND cp_status = status_id AND cp_stage = 3 ORDER BY cp_no ASC"; 
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step = '".$_POST['step_id']."' AND cp_step_1 != 0 AND cp_consider = 0)) ORDER BY cp_no ASC "; 
						$_SESSION['sql2'] = $sql;
					}
					else{
						//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_step = '".$_POST['step_id']."' AND cp_step_1 > 0 AND cp_status = status_id AND cp_stage = 3  ORDER BY cp_no ASC"; 
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step = '".$_POST['step_id']."' AND cp_step_1 != 0 AND cp_consider = 0)) ORDER BY cp_no ASC "; 
						$_SESSION['sql1'] = $sql;
					}
				}
				else{
					if($_POST['affiliation']!=NULL){
						//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_stage = 3 ORDER BY cp_no ASC";
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step = 0 AND cp_step = '".$_POST['step_id']."' OR  cp_step != 0 AND cp_step_1 = 0 OR cp_consider != 0)) ORDER BY cp_no ASC ";	
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['province']!=NULL){
						//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_stage = 3 ORDER BY cp_no ASC";
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_name LIKE '%".$_POST['province']."%' ANDpvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step = 0 AND cp_step = '".$_POST['step_id']."' OR  cp_step != 0 AND cp_step_1 = 0 OR cp_consider != 0)) ORDER BY cp_no ASC ";
						$_SESSION['sql2'] = $sql;
					}
					elseif($_POST['name']!=NULL){
						//$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND ((cp_step > 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND cp_status = status_id AND cp_stage = 3 ORDER BY cp_no ASC"; 
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step = 0 AND cp_step = '".$_POST['step_id']."' OR  cp_step != 0 AND cp_step_1 = 0 OR cp_consider != 0)) ORDER BY cp_no ASC ";
						$_SESSION['sql2'] = $sql;
					}
					else{						
						$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step, ref_up_snd_step, ref_province , ref_affiliation ,ref_operate_fst , ref_operate_snd ,ref_status WHERE pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_province = pvn_id AND cp_step = step_id AND sub_no = cp_step_1 AND sub_step = step_id AND cp_status = status_id  AND (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step = 0 AND cp_step = '".$_POST['step_id']."' OR  cp_step != 0 AND cp_step_1 = 0 OR cp_consider != 0)) ORDER BY cp_no ASC ";
						$_SESSION['sql1'] = $sql;
					}
				}
			}
		}
		elseif($_POST['event']=='status'){
			if($_POST['affiliation']!=NULL){
				$sql ="SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status ,ref_affiliation , ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND pvn_affiliation = aff_id AND aff_id = '".$_POST['affiliation']."' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['province']!=NULL){
				$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status, ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND pvn_name LIKE '%".$_POST['province']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
				//$sql = "SELECT * FROM tb_cityplan , ref_status , ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_status = status_id AND status_id = '".$_POST['status_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['name']!=NULL){
				$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status, ref_up_fst_step , ref_up_snd_step WHERE cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name ASC";
				 //$sql = "SELECT * FROM tb_cityplan , ref_status , ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_status = status_id AND status_id = '".$_POST['status_id']."' ORDER BY pvn_name ASC";
				$_SESSION['sql2'] = $sql;
			}
			else{
				$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_effective , ref_up_fst_step , ref_up_snd_step WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_effective = eff_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step ORDER BY cp_no ASC";
			  //$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_status = '".$_POST['status_id']."' AND cp_effective = '".$_POST['eff_id']."' ORDER BY pvn_name aSC"; 
					$_SESSION['sql1'] = $sql;
		}
			}
		elseif($_POST['event']=='stage'){
			if($_POST['stage_id']==2 OR $_POST['stage_id']==4){				
				if($_POST['affiliation']!=NULL){
				$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage , ref_affiliation WHERE pvn_affiliation = aff_id AND aff_id = '".$_POST['affiliation']."' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND (stage_id = '2' OR stage_id ='4') ORDER BY pvn_name ASC";
				//$sql ="SELECT * FROM tb_cityplan , ref_stage ,ref_province,ref_affiliation WHERE aff_id = '".$_POST['affiliation']."' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
				$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['province']!=NULL){
					$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE pvn_name LIKE '%".$_POST['province']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND (stage_id = '2' OR stage_id ='4') ORDER BY pvn_name ASC";
					//$sql ="SELECT * FROM tb_cityplan , ref_stage , ref_province,ref_affiliation WHERE pvn_name LIKE '%".$_POST['province']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				elseif($_POST['name']!=NULL){
				  $sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND (stage_id = '2' OR stage_id ='4') ORDER BY pvn_name ASC";
					  //$sql ="SELECT * FROM tb_cityplan , ref_stage , ref_province,ref_affiliation WHERE cp_name LIKE '%".$_POST['name']."%' AND pvn_affiliation = aff_id AND cp_province = pvn_id  AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql2'] = $sql;
				}
				else{
					//$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC"
					$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND (stage_id = '2' OR stage_id ='4') ORDER BY pvn_name ASC";
				  //$sql ="SELECT * FROM tb_cityplan , ref_stage , ref_province,ref_affiliation WHERE pvn_affiliation = aff_id AND cp_province = pvn_id  AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql1'] = $sql;
				}				
			}else{
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
					//$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC"
					$sql = "SELECT * FROM tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd , ref_status , ref_up_fst_step , ref_up_snd_step ,ref_stage WHERE cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND cp_step = step_id AND cp_step_1 = sub_no AND step_id = sub_step AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
				  //$sql ="SELECT * FROM tb_cityplan , ref_stage , ref_province,ref_affiliation WHERE pvn_affiliation = aff_id AND cp_province = pvn_id  AND cp_stage = stage_id AND stage_id = '".$_POST['stage_id']."' ORDER BY pvn_name ASC";
					$_SESSION['sql1'] = $sql;
				}
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
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND aff_id = '".$_POST['affiliation']."'AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['province']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND pvn_name LIKE '%".$_POST['province']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['name']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_name LIKE '%".$_POST['name']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
				$_SESSION['sql2'] = $sql;
			}
			else{
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
				$_SESSION['sql1'] = $sql;
			}
		}
		elseif($_POST['event']=='operate1_sub'){
			if($_POST['affiliation']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND aff_id = '".$_POST['affiliation']."'AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['province']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND pvn_name LIKE '%".$_POST['province']."%' AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['name']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_name LIKE '%".$_POST['name']."%' AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
				$_SESSION['sql2'] = $sql;
			}
			else{
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_status = 1 AND cp_stage = 1 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
				$_SESSION['sql1'] = $sql;
			}
		}
		elseif($_POST['event']=='operate2'){
			if($_POST['affiliation']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND aff_id = '".$_POST['affiliation']."'AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['province']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND pvn_name LIKE '%".$_POST['province']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['name']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_name LIKE '%".$_POST['name']."%' AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
				$_SESSION['sql2'] = $sql;
			}
			else{
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_owner = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
				$_SESSION['sql1'] = $sql;
			}
		}
		elseif($_POST['event']=='operate2_sub'){
			if($_POST['affiliation']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND aff_id = '".$_POST['affiliation']."'AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['province']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND pvn_name LIKE '%".$_POST['province']."%' AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC";
				$_SESSION['sql2'] = $sql;
			}
			elseif($_POST['name']!=NULL){
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_name LIKE '%".$_POST['name']."%' AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id  AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
				$_SESSION['sql2'] = $sql;
			}
			else{
				$sql ="SELECT * FROM tb_cityplan , ref_up_fst_step ,ref_up_snd_step , ref_operate_fst , ref_province,ref_affiliation, ref_operate_snd ,ref_status  WHERE cp_step = step_id AND cp_step_1 = sub_no AND sub_step = step_id AND cp_owner = '".$_POST['own_id']."' AND cp_operator = '".$_POST['opt_id']."' AND cp_owner = opt_fst_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_stage = 3 AND cp_operator = opt_snd_id AND cp_status = status_id ORDER BY cp_no ASC"; 
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
	$result = selectSql($sql); 
	$row = 50;
	$all_data = count($result);
	$all_table = ceil($all_data/$row);
	$value_white = 0;
	$value_green = 0;
	$value_yellow = 0;
	$value_orange = 0;
	$value_red = 0;
		
		//echo $_POST['affiliation'];
		//echo $_POST['province'];
		//echo $_POST['name'];
		if(count($result)==0){
			echo '<h2 style="margin-top:15%;text-align:center;font-family:Kanit;color:red!important">ไม่มีข้อมูล</h2>';
			?> 
			<script>
				$('table').hide();
				$('#search_value').html("จำนวน : "+'<?php echo count($result)." ผัง"; ?>');
				
			</script> 
			<?php
		}else{ 
			?> 
			<script>
				$('#consider_table').show();
				$('#search_value').html("จำนวน : "+'<?php echo count($result)." ผัง"; ?>');

			</script>
			<?php
		}
		if($_POST['province']!=NULL){
			$print_search = '(ข้อความที่สืบค้น '.$_POST['province'].')';
		}elseif($_POST['name']!=NULL){
			$print_search = '(ข้อความที่สืบค้น '.$_POST['name'].')';
		}elseif($_POST['affiliation']!=NULL){
			$print_search = '(ส่วนผังเมืองรวมที่ '.$_POST['affiliation'].')';
		}else{
			$print_search = "";
		}
	
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
					<td style="text-align:center"><?php 
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
									//$value_green+=1;
								}
								elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
									//$color = "background-color:#CEFFAE";
									$color = "background-color:#FFE841!important;color:black!important ";
									//echo $color;
									$cp_note = "ล่าช้า ".$daynd." วัน";
									//$value_yellow+=1;
								}
								elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
									$color = "background-color:orange!important;color:black!important";
									//echo $color;
									$cp_note = "ล่าช้า ".$daynd." วัน";			
									//$value_orange+=1;
								}
								else{
									$color = "background-color:red!important;color:white!important";
									//echo $color;
									$cp_note = "ล่าช้า ".$daynd." วัน";
									//$value_red+=1;
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
									//$value_green+=1;
								}
								elseif($daynd > 0 AND $daynd <= $result[$i]['sub_ot1']){
									//$color = "background-color:#CEFFAE";
									$color = "background-color:#FFE841!important;color:black!important ";
									//echo $color;
									$cp_note = "ล่าช้า ".$daynd." วัน";
									//$value_yellow+=1;
								}
								elseif($daynd > $result[$i]['sub_ot1'] AND $daynd <= $result[$i]['sub_ot2']){
									$color = "background-color:orange!important;color:black!important";
									//echo $color;
									$cp_note = "ล่าช้า ".$daynd." วัน";	
									//$value_orange+=1;									
								}
								else{
									$color = "background-color:red!important;color:white!important";
									//$color  = "background: linear-gradient(163deg,red 0%,red 50%,blue 50%,blue 100%);color:white";
									//$color  = "background: linear-gradient(163deg,white 48%,blue 50%,blue 50%,white 52%);color:black";
									//echo $color;
									$cp_note = "ล่าช้า ".$daynd." วัน";
									//$value_red+=1;
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
			<table id="table_<?= $t;?>" class="table table-bordered unprintable" style="border-width:1px;border-style:solid;border-color:black">
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
						<td style="text-align:center">
							<?php if($result[$i]['cp_consider']!=0){?>
								<svg hidden width="20" height="20">
								  <image href="img/chat.png" height="30" width="20" />
								</svg>
								<span style="margin-right:0px" onclick="readConsider(<?= $result[$i]['cp_no'];?>)" class="comment"><a><i style="transform: scaleX(-1);" class="fa fa-comment"></i></a></span>
							<?php }echo $result[$i]['cp_name'];?>
						</td>
						<td style="text-align:center"><?php echo $result[$i]['opt_fst_name'];?></td>
						<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
						<td style="text-align:center"><?php 
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
									//$color = "background-color:#FFFFFF!important;color:black!important ";
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
							<?php if($_SESSION['status']=='1' AND ($_POST['event']=='step' OR $_POST['event']=='step2') AND ($result[$i]['cp_step']=="0" OR ($result[$i]['cp_step']!="0" AND $result[$i]['cp_step_1']=="0"))){
							?><span onclick="setConsider(<?= $result[$i]['cp_no']; ?> , <?= $result[$i]['cp_consider']; ?>)" style="margin-right:10px;" title="เพิ่มเหตุผลพิจารณาดำเนินการ" class="gear"id="pin_<?php echo $result[$i]['cp_no'];?>">
								<a><i class="fa fa-thumb-tack"></i></a>
							  </span><?php } ?>
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
	pagin($row,$all_data);
	
	if($_SESSION['report_page']>1){
		echo "<script>$('#select_table').val('".$_SESSION['report_page']."');change_table();</script>";
		unset($_SESSION['report_page']);
	}
	else{
		echo "<script>$('#select_table').val('1');</script>";
	}
	
?>
	



<script>
	//alert('<?php //echo "event : ".$_POST["step"];?>');
	$('#print_search').html('<?php echo $print_search; ?>');
	$('#info_bar').show();
	$('.value_white').html(<?php echo $value_white; ?>);
	$('.value_green').html(<?php echo $value_green; ?>);
	$('.value_yellow').html(<?php echo $value_yellow; ?>);
	$('.value_orange').html(<?php echo $value_orange; ?>);
	$('.value_red').html(<?php echo $value_red; ?>);
</script>
