<?php
include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  $date = date('Y-m-d');
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
	
	if(TRUE){
		$_SESSION['aff_id'] = $_POST['aff_id'];
		$_SESSION['cp_name'] = $_POST['cp_name'];
		$_SESSION['pvn_name'] = $_POST['pvn_name'];
	}
	if($result[0]['user_status']==9 OR $x==0){
		if($_POST['cp_name']!= "" AND $_POST['pvn_name']!=""){
			//echo 'a';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%' AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['cp_name']!= null){
			//echo 'b';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%'";
		}
		elseif($_POST['pvn_name']!=null){
			//echo 'c';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['aff_id']!=null OR $_POST['aff_id']!=""){
			//echo 'd';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."'";
		}else{
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND  cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no";
		}
		echo '<script> $("#search_aff_ids").show()</script>';
	}
	else{
		if($_POST['cp_name']!= "" AND $_POST['pvn_name']!=""){
			//echo 'a';
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%' AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['cp_name']!= null){
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND aff_id = '".$x."' AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['cp_name']."%'";
		}

		elseif($_POST['pvn_name']!=null){
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND aff_id = '".$x."' AND  cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		}
		elseif($_POST['aff_id']!=null OR $_POST['aff_id']!=""){
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND aff_id = '".$x."' AND  cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '".$_POST['aff_id']."'";
		}else{
			$sql = "SELECT * FROM tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail WHERE cp_csd = csd_seq AND aff_id = '".$x."' AND  cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id ORDER BY cp_no";
		}
		
	}

// end //
//echo $sql;
$result = selectSql($sql);
$row  = 20;
$all_data = count($result);
$all_table = ceil($all_data/$row);
$lating = '';
$lating = array();
$elsar = 1;
?>
<div id="lating-bar" style="margin:9px 0 -8px;display:flex;justify-content: space-between;align-items: flex-end;">
	<div style="display:flex;margin:8px 0 0">
		<span style="margin:0 4px;font-size:16px;">
			<a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป">
				<i class="fa fa-exclamation"></i>
			</a>
		</span>
		<p style="font-size:14px;margin:0">ผังที่ดำเนินการขั้นตอนปัจจุบันเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป จำนวน <b id="lating-value"></b> ผัง  ในจำนวน <b id="lating-all"></b> ผัง (◄ คลิกที่หมายเลขจำนวนผัง)</p>
	</div>
</div>
<div class="table-responsive" style="border-color:transparent;margin-top:8px">
<?php
	if($all_data > 0){ ?>
		<table id="table_show" class="table table-bordered unprintable" style="border:transparent">
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
					<td hidden style="text-align:center">แผน</td>
	
				</tr>
			</thead>
			<tbody>
			<?php 
				if($_POST['page']=='' OR $_POST['page']==null){$_POST['page']=1;}
				$page = $_POST['page'];
				//echo $page;
				for($i=0;$i<$row;$i++){ 
				$n = ($page-1)*$row+$i;
				if($n==$all_data){
					break;
				}
				
				
			?>
				<tr>
					<td style="text-align:center"><?php echo $n+1;?></td>
					<td style="text-align:left"><?php echo $result[$n]['pvn_name'];?></td>
					<td class="" style="text-align:left">
						<?php 
							if($warning!=''){
								echo $warning;
							}
							echo '<text class="text_cp_name" onclick="gotoCityplanDetail('.$result[$n]['cp_no'].')">'.$result[$n]['cp_name'].'</text>';
						?>
					</td>
					<td style="text-align:center"><?php echo $result[$n]['scope_name'];?></td>
					<td style="text-align:center"><?php echo $result[$n]['status_name'];?></td>
					<td style="text-align:center"><?php echo $result[$n]['stage_name'];?></td>
					<?php $owner =$result[$n]['cp_owner']==2? "color:red;":"";?>
					<td style="text-align:center;<?=$owner?>"><?php echo $result[$n]['opt_fst_name'];?></td>
					<td style="text-align:center"><?php echo $result[$n]['opt_snd_name'];?></td>
					<td style="text-align:center"><?php echo $result[$n]['eff_name'];?></td>

					<td style="text-align:center">
						<?php if($_SESSION['data_access']=='9'){ ?>
							<span title="แก้ไขข้อมูลผังเมือง" class="gear" onclick="cpEditx(<?php echo $result[$n]['cp_no'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear access_<?php echo $row['pvn_affiliation'];?>" >  </i>&nbsp;</a></span>
						<?php } ?>
						<span title="แก้ไขขั้นตอน" class="step" onclick="cityplan_step(<?php echo $result[$n]['cp_no'];?>)"><a><i class="fa fa-list-ul"></i></a></span> 
						<?php if($_SESSION['data_access']=='9'){ ?>
							<span title="ลบผังเมือง" class="trash" onclick="cpDel(<?php echo $result[$n]['cp_no'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>	
						<?php } ?>
						
					</td>				
					<td style="text-align:center">		
					<?php 
					$sql = "SELECT COUNT(*) as note FROM tb_note WHERE note_cp = '".$result[$n]['cp_no']."' ANd note_status = 1";
					$noter = selectSql($sql);
					if($noter[0]['note']>0){
						echo '<span  class="note" title="'.$noter[0]['note'].' หมายเหตุ" onclick="readNote('.$result[$n]['cp_no'].')"><a><i class="fa fa-list-alt"></i></a></span>';
					}else{
						
					}
					?>
						<span title="แสดงขั้นตอน" class="list" onclick="openStep(<?php echo $result[$n]['cp_no'];?>)"><a><i class="fa fa-list"></i></a></span>
					</td>
					<td hidden style="text-align:center">
						<span title="แผนดำเนินการ" class="calendar" onclick="OperatePlan(<?php echo $result[$n]['cp_no'];?>)"><a><i class="fa fa-calendar"></i></a></span>
					</td>
				
				
				
				
				</tr>
				<?php }  ?>	
			</tbody>
		</table>
		<?php
	}
	else{
		echo "<h3 style='font-family: Kanit;text-align: center;margin:15px 0px;'>ไม่มีข้อมูล</h3>";	
	}
?>
</div>
<div id="pagecontrol_pro_frame">
	<div id="pagecontrol_pro" style="border:transparent">
		<?php paginx($row,$all_data);?>
	</div>
</div>

<script>
$('#pagecontrol').replaceWith($("#pagecontrol_pro_frame").html());
$('#pagecontrol_pro_frame').remove();
window.location = '#cityplanshow';
</script>

