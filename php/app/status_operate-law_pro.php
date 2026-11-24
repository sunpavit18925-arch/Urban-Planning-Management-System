<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
if($_POST['type']=='operate'){
	$x = 'op';
	$from = 'tref_rp_cs_operate';
	
}else{
	$x = 'df';
	$from = 'tref_rp_cs_dflaws';
}
$select = 'rp_cs_'.$x.'_no as no , rp_cs_'.$x.'_id as id , rp_cs_'.$x.'_title as title , rp_cs_'.$x.'_asc as sort';
$order = 'rp_cs_'.$x.'_asc';
$sql = 'SELECT '.$select.' FROM '.$from.' ORDER BY '.$order.' ASC';

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
				<td style="text-align:center">หมายเลขข้อ</td>
				<td style="text-align:center">รายละเอียดการดำเนินการ</td>
				<td style="text-align:center">หมายเลขลำดับแสดงผล</td>			
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
				<td style="text-align:center"><?= $bow['id'];?></td>
				<td style="text-align:center"><?= $bow['title'];?></td>
				<td style="text-align:center"><?= $bow['sort'];?></td>
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
					<td style="text-align:center">หมายเลขข้อ</td>
					<td style="text-align:center">รายละเอียดการดำเนินการ</td>
					<td style="text-align:center">หมายเลขลำดับแสดงผล</td>
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
					<td style="text-align:center"><?php echo $result[$i]['id'];?></td>
					<td style="text-align:left"><?php echo $result[$i]['title'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['sort'];?></td>
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