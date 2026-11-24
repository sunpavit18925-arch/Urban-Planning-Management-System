<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();


$select = "cp_no, cp_name, cp_status, cp_stage, cp_effective , pvn_name , opt_fst_name , opt_snd_name ,cp_scope";
$from = "tb_cityplan , ref_province , ref_operate_fst , ref_operate_snd";
$join = 'cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id';
$order = "pvn_name";

if($_POST['search_type']>0 AND $_POST['cp_name']!=''){
	if($_POST['search_type']==1){
		$cond = "cp_status = 1 AND cp_effective = 0 AND cp_name LIKE '%".$_POST['cp_name']."%'";
	}elseif($_POST['search_type']==2){
		$cond = "cp_status = 2 AND cp_stage = 3 AND cp_effective = 1 AND cp_name LIKE '%".$_POST['cp_name']."%'";
	}else{
		$cond = "cp_status = 2 AND cp_stage = 3 AND cp_effective = 2 AND cp_name LIKE '%".$_POST['cp_name']."%'";
	}
	$sql = 'SELECT '.$select.' FROM '.$from.' WHERE '.$join.' AND '.$cond.' ORDER BY '.$order.' ASC';
}elseif($_POST['search_type']>0){
	if($_POST['search_type']==1){
		$cond = "cp_status = 1 AND cp_effective = 0";
	}elseif($_POST['search_type']==2){
		$cond = "cp_status = 2 AND cp_stage = 3 AND cp_effective = 1";
	}else{
		$cond = "cp_status = 2 AND cp_stage = 3 AND cp_effective = 2";
	}
	$sql = 'SELECT '.$select.' FROM '.$from.' WHERE '.$join.' AND '.$cond.' ORDER BY '.$order.' ASC';
}elseif($_POST['cp_name']!=''){
	$sql = 'SELECT '.$select.' FROM '.$from.' WHERE '.$join.' AND cp_name LIKE "%'.$_POST['cp_name'].'%" ORDER BY '.$order.' ASC';
}else{
	$sql = 'SELECT '.$select.' FROM '.$from.' WHERE '.$join.' AND ((cp_status = 1 AND cp_effective = 0) OR (cp_status = 2 AND cp_stage = 3 AND cp_effective = 1) OR (cp_status = 2 AND cp_stage = 3 AND cp_effective = 2)) ORDER BY '.$order.' ASC';
}

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
				<td style="text-align:center">ลำดับ</td>
					<td style="text-align:left">จังหวัด</td>
					<td style="text-align:left">ชื่อผังเมืองรวม</td>
					<td style="text-align:center" colspan="2">ผู้ดำเนินรายการ</td>	
					<td style="text-align:center;">ขอบเขต</td>			
					<td style="text-align:center">สถานะผัง</td>			
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
				<td style="text-align:left"><?= $bow['pvn_name'];?></td>
				<td style="text-align:left"><?= $bow['cp_name'];?></td>
				<td style="text-align:center;width:75px"><?= $bow['opt_fst_name'];?></td>
				<td style="text-align:center;width:75px"><?= $bow['opt_snd_name'];?></td>
				<td style="text-align:center">
					<?php
						if($bow[$i]['cp_scope']==1){
							$half+=1;
							echo "ไม่เต็มอำเภอ";
						}else{
							$full+=1;
							echo "เต็มอำเภอ";
						}
					?>
					</td>
					<td style="text-align:center">
					<?php 
						if($bow['cp_status']==1 AND $bow['cp_effective']==0){
								echo "เปิดพื้นที่ใหม่";
							}elseif($bow['cp_status']==2 AND $bow['cp_effective']==1){
								echo "มีผลบังคับใช้";
							}
							elseif($bow['cp_status']==2 AND $bow['cp_effective']==2){
								echo "สิ้นสุดบังคับใช้";
							}
							else{
								echo "-";
							}
					?>
					</td>
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
					<td style="text-align:left">จังหวัด</td>
					<td style="text-align:left">ชื่อผังเมืองรวม</td>
					<td style="text-align:center" colspan="2">ผู้ดำเนินรายการ</td>	
					<td style="text-align:center">ขอบเขต</td>			
					<td style="text-align:center">สถานะผัง</td>			
				</tr>
			</thead>
			<tbody>
				<?php 
					for($i , $j=1 ; $j <=$row ; $j++ , $i++){
						if($i == $all_data){
							break;
						}
				?>
				<tr style="">
					<td style="text-align:center"><?php echo $i+1;?></td>
					<td style="text-align:left"><?php echo $result[$i]['pvn_name'];?></td>
					<td style="text-align:left"><?php echo $result[$i]['cp_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_fst_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
					<td style="text-align:center">
					<?php
						if($result[$i]['cp_scope']==1){
							$half+=1;
							echo "ไม่เต็มอำเภอ";
						}else{
							$full+=1;
							echo "เต็มอำเภอ";
						}
					?>
					</td>
					<td style="text-align:center">
					<?php 
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
?>
<script>
$('#search_value').html('จำนวน <?= $all_data;?> ผัง');
</script>
