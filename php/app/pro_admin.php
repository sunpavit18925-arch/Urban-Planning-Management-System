<?php 
	include '../js/function_db.php';


	//$sql = "SELECT * FROM tb_user ORDER BY user_status DESC, user_fname ASC, user_lname ASC";
	$sql = "SELECT *, CASE WHEN user_status = 9 THEN 'Super admin' ELSE 'Admin' END AS status FROM tb_user, ref_user_affiliation WHERE user_affiliation = use_aff_id AND user_status != 0 ORDER BY user_status DESC, user_fname ASC, user_lname ASC";
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
							<th style="text-align:center">เลขประจำตัวประชาชน</th>
							<!--<th style="text-align:left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;ชื่อ - นามสกุล</th>-->
							<th style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;ชื่อ</th>
							<th style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;นามสกุล</th>
							<th style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;หน่วยงาน</th>		
							<th style="text-align:center">สถานะ</th>						
							<th style="text-align:center" class="unprintable">จัดการ</th>
						</tr>
					</thead>
					<tbody> <?php 						
						for($x = 1 ; $x <= $row ; $x++,$i++){ 
							if($i==$data){
								break;
							} ?>
						<tr <?php if($result[$i]['user_status']==9){ echo 'style="background-color:#FFFBEB"';}?>>
							<td style="text-align:center"><?php echo $i+1;?></td>
							<td style="text-align:center"><?php echo  substr($result[$i]['user_id'],0,8).'XXXXX';?></td>
							<td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[$i]['user_fname'];?></td>
							<td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[$i]['user_lname'];?></td>
							<td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[$i]['use_aff_name'];?></td>
							<td style="text-align:center"><?php echo $result[$i]['status'];?></td>
							<td style="text-align:center" class="unprintable">
								<?php 
									if($result[$i]['user_status']==9){ ?>
										<span class="gear" onclick="adminEdit(<?php echo $result[$i]['user_id'];?>)"><a><i class="fa fa-gear" style="margin-right:5px"></i></a></span> <?php 
									}else{ ?>
										<span class="gear" onclick="adminEdit(<?php echo $result[$i]['user_id'];?>)"><a><i class="fa fa-gear" style="margin-right:5px"></i></a></span>
										<span class="trash" onclick="adminDel(<?php echo $result[$i]['user_id'];?>)"><a><i class="fa fa-trash"></i></a></span> <?php
									} ?>
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
					<th style="text-align:center">เลขประจำตัวประชาชน</th>
					<!--<th style="text-align:left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;ชื่อ - นามสกุล</th>-->
					<th style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;ชื่อ</th>
					<th style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;นามสกุล</th>
					<th style="text-align:center">สถานะ</th>						
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
					<td style="text-align:center"><?= substr($row['user_id'],0,8).'XXXXX';?></td>
					<td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;<?= $row['user_fname'];?></td>
					<td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;<?= $row['user_lname'];?></td>
					<td style="text-align:center"><?= $row['status'];?></td>
				</tr>				
			<?php } ?>
			</tbody>
		</table>
	<?php 
			
	}
	else{ 
		?>
			<h2 style="margin-top:15%;text-align:left;font-family:Kanit;color:red!important">ไม่มีข้อมูล</h2>
		<?php
	}
?>
	
<script>
$('#count_admin').html('<?php echo $data;?>');
</script>