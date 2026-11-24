<style>
	table > thead> tr > td{
		text-align:center;
		font-weight:600;
	}
	table > tbody> tr > td{
		text-align:center;
	}
</style>
<?php 
include '../js/function_db.php';
session_name("tutorial");
session_start();
if($_POST['x']!=''){
	if($_POST['type']=='name'){
		$sql = "SELECT * FROM tb_cityplan, ref_province, ref_scope, ref_status, ref_stage, ref_operate_fst, ref_operate_snd, ref_effective WHERE cp_name LIKE '%".$_POST['x']."%' AND (cp_stage = 2 OR cp_stage = 4) AND cp_province = pvn_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id";
	}elseif($_POST['type']=='pvn'){
		$sql = "SELECT * FROM tb_cityplan, ref_province, ref_scope, ref_status, ref_stage, ref_operate_fst, ref_operate_snd, ref_effective WHERE pvn_id = '".$_POST['x']."' AND (cp_stage = 2 OR cp_stage = 4) AND cp_province = pvn_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id";
	}else{
		$sql = "SELECT * FROM tb_cityplan, ref_province, ref_scope, ref_status, ref_stage, ref_operate_fst, ref_operate_snd, ref_effective WHERE pvn_affiliation = '".$_POST['x']."' AND (cp_stage = 2 OR cp_stage = 4) AND cp_province = pvn_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id";
	}
}else{
	$sql = "SELECT * FROM tb_cityplan, ref_province, ref_scope, ref_status, ref_stage, ref_operate_fst, ref_operate_snd, ref_effective WHERE (cp_stage = 2 OR cp_stage = 4) AND cp_province = pvn_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id";
}
//echo $sql;
//$sql = "SELECT * FROM tb_cityplan, ref_province, ref_scope, ref_status, ref_stage, ref_operate_fst, ref_operate_snd, ref_effective WHERE (cp_stage = 2 OR cp_stage = 4) AND cp_province = pvn_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_effective = eff_id";
$result = selectSql($sql);
$row = 20;
$all_data = count($result);
$all_table = ceil($all_data/$row);
?>
<div class="table-responsive" style="margin-top:8px">
<?php
	if($all_data > 0){ ?>
		<table hidden class="table table-bordereds printx">
			<thead>
				<tr style="background-color: #295DBD;color:white">
					<td>ลำดับ</td>
					<td>จังหวัด</td>
					<td>ชื่อผังเมืองรวม</td>
					<td>ขอบเขต</td>
					<td>สถานะผัง</td>
					<td>ขั้นตอน</td>
					<td>เจ้าของ</td>
					<td>ผู้ดำเนินการ</td>
					<td>มีผลบังคับใช้</td>				
				</tr>
			</thead>
			<tbody>
			<?php 
			$z = 0;
			foreach($result as $bow){
				$z++; ?>
				<tr>
					<td><?= $z; ?> </td>
					<td><?= $bow['pvn_name']; ?> </td>
					<td><?= $bow['cp_name'];?></td>
					<td><?= $bow['scope_name'];?></td>
					<td><?= $bow['status_name'];?></td>
					<td><?= $bow['stage_name'];?></td>
					<td><?= $bow['opt_fst_name'];?></td>
					<td><?= $bow['opt_snd_name'];?></td>
					<td><?= $bow['eff_name'];?></td>		
				</tr>
				<?php 
			} ?>
			</tbody>
		</table> 
	<?php
	}
	for($i=0 ,$t=1 ; $t<=$all_table ; $t++){ ?>
		<table id="table_<?= $t; ?>" class="table table-bordereds unprintable">
			<thead>
				<tr style="background-color: #295DBD;color:white">
					<td>ลำดับ</td>
					<td>จังหวัด</td>
					<td>ชื่อผังเมืองรวม</td>
					<td>ขอบเขต</td>
					<td>สถานะผัง</td>
					<td>ขั้นตอน</td>
					<td>เจ้าของ</td>
					<td>ผู้ดำเนินการ</td>
					<td>มีผลบังคับใช้</td>
					<td>จัดการ</td>
					
				</tr>
			</thead>
			<tbody>
			<?php 
			for($i , $j=1 ; $j<=$row ;$j++,$i++){
				if($i==$all_data){
					break;
				} ?>
				<tr>
					<td><?= $i+1; ?> </td>
					<td><?= $result[$i]['pvn_name']; ?> </td>
					<td><?= $result[$i]['cp_name'];?></td>
					<td><?= $result[$i]['scope_name'];?></td>
					<td><?= $result[$i]['status_name'];?></td>
					<td><?= $result[$i]['stage_name'];?></td>
					<td><?= $result[$i]['opt_fst_name'];?></td>
					<td><?= $result[$i]['opt_snd_name'];?></td>
					<td><?= $result[$i]['eff_name'];?></td>
					<td><span onclick="cpEditx(<?= $result[$i]['cp_no']?>)" class="gear"><a id="gear"><i class="fa fa-gear"></i></a></span></td>
					
					
				
				</tr>
				<?php 
			} ?>
			</tbody>
		</table><?php 
	} ?>
</div>
<?php 
	pagin($row,$all_data);
?>
<script>
	$('#cp_value').html(<?=$all_data;?>);
</script>


