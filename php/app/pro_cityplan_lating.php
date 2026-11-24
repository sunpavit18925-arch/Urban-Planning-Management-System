<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$date = date('Y-m-d');
$all_cp = count($_POST['cp']);
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
	$from = "tb_cityplan ,ref_province , ref_affiliation, ref_scope , ref_status , ref_stage , ref_operate_fst , ref_operate_snd , ref_effective ,ref_cityplan_step_detail";
	$join_tb = "cp_csd = csd_seq AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id AND cp_province = pvn_id AND pvn_affiliation = aff_id";
	$sql = 'SELECT '.$select.' FROM '.$from.' WHERE '.$join_tb.' AND ('.$cond_post.') ORDER BY cp_no ASC';
	$result = selectSql($sql);
	//$from = "tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_scope, ref_status , ref_up_fst_step , ref_up_snd_step , ref_cityplan_step_detail";
	//$join_tb = "cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_scope = scope_id AND cp_status = status_id AND cp_step = step_id AND step_id = sub_step AND cp_step_1 = sub_no AND cp_csd = csd_seq";
	//echo $sql;
	$row  = 20;
	$all_data = count($result);
	$all_table = ceil($all_data/$row);
	$lating = '';
	$lating = array();
	
	?>
	
<div style="margin:9px 0 -8px;display:flex;justify-content: space-between;align-items: flex-end;">
	<div style="display:flex;margin:8px 0 0">
		<span style="margin:0 4px;font-size:16px;">
			<a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป">
				<i class="fa fa-exclamation"></i>
			</a>
		</span>
		<p style="font-size:14px;margin:0">ผังที่ดำเนินการขั้นตอนเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป จำนวน <b id="lating-value"></b> ผัง</p>
	</div>
	<div style="margin-bottom:0px;/*display: flex;align-items: flex-end;justify-content: space-between;*/">
		<button class="btn btn-primary" style="font-family:Kanit;float:right;cursor:pointer;margin-bottom:4px;color:white" onclick="$('#pvn_names').val('');searchCP();";>แสดงจำนวนผังทั้งหมด</button>
	</div>
</div>
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
		<table id="table_<?php echo $table;?>" class="table table-bordered unprintable" style="border:transparent">
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
					for($i , $j=1 ; $j <=$row ; $j++ , $i++){
						if($i == $all_data){
							break;
						}
						$warning = '';
						if($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']>0){
							//echo $result[$i]['cp_no'].'<br>';
							if($result[$i]['csd_stop_2']!='' AND $result[$i]['csd_stop_2']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop_2'],0,4)-543).substr($result[$i]['csd_stop_2'],4);
								if($csd_stop_ad < $date){
									$warning = '<span style="margin:0 4px;font-size:16px;"><a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป"><i class="fa fa-exclamation"></i></a></span>';
									array_push($lating,$result[$i]['cp_no']);
								}
							}
						}elseif($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']<1){
							if($result[$i]['csd_stop']!='' AND $result[$i]['csd_stop']!=null){
								$csd_stop_ad = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
								if($csd_stop_ad < $date){
									$warning = '<span style="margin:0 4px;font-size:16px;"><a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป"><i class="fa fa-exclamation"></i></a></span>';
									array_push($lating,$result[$i]['cp_no']);
								}
							}
						}else{
						}
				?>
				<tr style="">
					<td style="text-align:center"><?php echo $i+1;?></td>
					<td style="text-align:left"><?php echo $result[$i]['pvn_name'];?></td>
					<td style="text-align:left">
						<?php 
							if($warning!=''){
								echo $warning;
							}
							echo $result[$i]['cp_name'];
						?>
					</td>
					<td style="text-align:center"><?php echo $result[$i]['scope_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['status_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['stage_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_fst_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['eff_name'];?></td>

					<td style="text-align:center">
						<!--<span title="แก้ไข" class="gear" onclick="cpEdit(<?php echo $result[$i]['cp_no'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear access_<?php echo $row['pvn_affiliation'];?>" >  </i>&nbsp;</a></span>-->					
						<?php if($_SESSION['data_access']=='9'){ ?>
							<span title="แก้ไขข้อมูลผังเมือง" class="gear" onclick="cpEditx(<?php echo $result[$i]['cp_no'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear access_<?php echo $row['pvn_affiliation'];?>" >  </i>&nbsp;</a></span>
						<?php } ?>
						<span title="แก้ไขขั้นตอน" class="step" onclick="cityplan_step(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-list-ul"></i></a></span> 
						<span hidden title="ลบผังเมือง" class="trash" onclick="cpDel(<?php echo $result[$i]['cp_no'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>	
					</td>				
					<td style="text-align:center">		
					<?php 
					$sql = "SELECT COUNT(*) as note FROM tb_note WHERE note_cp = '".$result[$i]['cp_no']."' ANd note_status = 1";
					$noter = selectSql($sql);
					if($noter[0]['note']>0){
						echo '<span  class="note" title="'.$noter[0]['note'].' หมายเหตุ" onclick="readNote('.$result[$i]['cp_no'].')"><a><i class="fa fa-list-alt"></i></a></span>';
					}else{
						
					}
					?>
						<span title="แสดงขั้นตอน" class="list" onclick="openStep(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-list"></i></a></span>
					</td>
					<td hidden style="text-align:center">
						<span title="แผนดำเนินการ" class="calendar" onclick="OperatePlan(<?php echo $result[$i]['cp_no'];?>)"><a><i class="fa fa-calendar"></i></a></span>
					</td>
				</tr>
					<?php } ?>
			</tbody>
		</table><?php
		}
	}else{
		echo "<h3 style='font-family: Kanit;text-align: center;margin:15px 0px;'>ไม่มีข้อมูล</h3>";	
	}
?>
</div>
<?php 
	pagin($row,$all_data);
	$count_lating =  count($lating);
	$latingx = json_encode($lating);
	
}else{
	echo "<h3 style='font-family: Kanit;text-align: center;margin:15px 0px;'>ไม่มีข้อมูล</h3>";	
}

?>
<div class="row unprintable">
	<div id="div-btn" class="col-xs-5 col-md-6">
		<button id="btn_goback2" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
	</div>
</div>
<script>
	$('#lating-value').html('<?= $count_lating;?>').attr('style','cursor:pointer').attr('onclick','latingCP(<?= $latingx;?>)');

</script>