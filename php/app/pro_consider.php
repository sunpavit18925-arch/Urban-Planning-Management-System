<?php 
	include '../js/function_db.php';


	$sql = "SELECT * FROM ref_consider WHERE consider_status = 1";
	$result = selectSql($sql);
	$data = count($result);
	$row = 20;
	//echo $data;
	if($data > 0){?>
		<div class="table-responsive" style="border-color:transparent;margin-top:8px">
		<?php for($table = 1 , $i = 0; $table <= ceil($data/$row) ; $table++){ ?>
				<table id="table_<?php echo $table;?>" class="table table-bordered unprintable">
					<thead>
						<tr style="background-color: #295DBD;color:white">
							<th style="text-align:center">ลำดับ</th>
							<th style="text-align:center">เหตุผลผังพิจารณาการดำเนินการ</th>
							<th style="text-align:center">วันที่เพิ่มข้อมูล</th>						
							<th style="text-align:center" class="unprintable">จัดการ</th>
						</tr>
					</thead>
					<tbody> <?php 						
						for($x = 1 ; $x <= $row ; $x++,$i++){ 
							if($i==$data){
								break;
							} ?>
						<tr>
							<td style="text-align:center"><?php echo $i+1;?></td>
							<td style="text-align:center"><?php echo $result[$i]['consider_name'];?></td>
							<td style="text-align:center"><?php echo $result[$i]['consider_date'];?></td>


							<td style="text-align:center" class="unprintable">
										<span class="gear" onclick="considerEdit(<?php echo $result[$i]['consider_id'];?>)"><a><i class="fa fa-gear" style="margin-right:5px"></i></a></span>
										<span class="trash" onclick="considerDel(<?php echo $result[$i]['consider_id'];?>)"><a><i class="fa fa-trash"></i></a></span>
							</td>
						</tr> <?php 
						} ?>
					</tbody>
				</table>
		<?php } ?>
			</div>
		
		<?php	
			pagin($row,$data);
		?>
		<table class="table table-bordereds printx" hidden>
			<thead>
				<tr style="background-color: #295DBD;color:white">
					<th style="text-align:center">ลำดับ</th>
					<th style="text-align:center">หมายเหตุผังพิจารณาการดำเนินการ</th>
					<th style="text-align:center">วันที่เพิ่ม</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$x = 0;
				foreach($result as $row){
					$x++; 
			?>
				<tr>
					<td style="text-align:center"><?= $x;?></td>
					<td style="text-align:center"><?= $row['consider_name'];?></td>
					<td style="text-align:center"><?= $row['consider_date'];?></td>

				</tr>				
			<?php } ?>
			</tbody>
		</table>
	<?php 
			
	}
	else{ 
		?>
			<h2 style="margin-top:15%;text-align:center;font-family:Kanit;color:red!important">ไม่มีข้อมูล</h2>
		<?php
	}
?>
	
<script>
$('#count_consider').html('<?php echo $data;?>');
</script>