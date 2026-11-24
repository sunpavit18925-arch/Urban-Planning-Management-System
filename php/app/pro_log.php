<?php 
	include '../js/function_db.php';
	if($_POST['type']==1){
		?>
			<style>
				@page{
					size:A4 portrait;
					margin:10mm 4mm;
				}
			</style>
		<?php
		$sql = "SELECT user_id , CASE WHEN user_status = 9 THEN 'Super Admin' ELSE 'Admin' END AS user_type FROM tb_user ";
		$result = selectSql($sql);
		$sql = "SELECT * , CASE ";		
		foreach($result as $row){
			$sql = $sql." WHEN log_user = '".$row['user_id']."' THEN '".$row['user_type']."'";
		}
		$sql = $sql." ELSE 'User' END AS user_type FROM tb_log WHERE log_action = 'cn' AND log_user != 'demo-user-1' ";
		//echo $sql;
		
		if($_POST['month'] == 'null' AND $_POST['year'] == 'null'){
			$sql = $sql."ORDER BY log_date DESC";
		}
		elseif($_POST['month'] != 'null' AND $_POST['year'] == 'null'){
			$sql = $sql."AND '".$_POST['month']."' = Month(log_date) ORDER BY log_date DESC";
		}
		elseif($_POST['month'] == 'null' AND $_POST['year'] != 'null'){
			$sql = $sql."AND '".$_POST['year']."' = Year(log_date) ORDER BY log_date DESC";
		}
		else{
			$sql = $sql."AND '".$_POST['month']."' = Month(log_date) AND '".$_POST['year']."' = Year(log_date) ORDER BY log_date DESC";
		}
	}elseif($_POST['type']==2){
		?>
			<style>
				@page{
					size:A4 landscape;
					margin:10mm 4mm;
				}
			</style>
		<?php
		$sql = "SELECT * FROM tb_log,tb_user,tb_cityplan,ref_user_affiliation WHERE user_affiliation = use_aff_id AND log_action != 'cn' AND log_user = user_id AND log_user != 'demo-user-1' AND cp_no = log_target AND log_type = 'cp'";
		$orde = " ORDER BY log_date DESC";
		if($_POST['month'] == 'null' AND $_POST['year'] == 'null'){
			$cond = " ";
		}elseif($_POST['month'] != 'null' AND $_POST['year'] == 'null'){
			$cond = " AND '".$_POST['month']."' = Month(log_date)";
		}elseif($_POST['month'] == 'null' AND $_POST['year'] != 'null'){
			$cond = " AND '".$_POST['year']."' = Year(log_date)";
		}else{
			$cond = " AND '".$_POST['month']."' = Month(log_date) AND '".$_POST['year']."' = Year(log_date)";
		}
		$sql = $sql.$cond.$orde;
		//echo $sql;
		/*if($_POST['month'] == 'null' AND $_POST['year'] == 'null'){
			$sql = "SELECT * FROM tb_log,tb_user,ref_user_affiliation,tb_cityplan WHERE user_affiliation = use_aff_id AND  log_action != 'cn' AND log_user = user_id AND log_user !='demo-user-1' AND cp_no = log_target AND log_type = 'cp' ORDER BY log_date DESC"; 
		}
		elseif($_POST['month'] != 'null' AND $_POST['year'] == 'null'){
			$sql = "SELECT * FROM tb_log,tb_user,ref_user_affiliation  WHERE user_affiliation = use_aff_id AND  log_action != 'cn' AND log_user = user_id AND '".$_POST['month']."' = Month(log_date) AND log_user !='demo-user-1' ORDER BY log_date DESC";
		}
		elseif($_POST['month'] == 'null' AND $_POST['year'] != 'null'){
			$sql = "SELECT * FROM tb_log,tb_user,ref_user_affiliation  WHERE user_affiliation = use_aff_id AND  log_action != 'cn' AND log_user = user_id AND '".$_POST['year']."' = Year(log_date) AND log_user !='demo-user-1' ORDER BY log_date DESC";
		}
		else{
			$sql = "SELECT * FROM tb_log,tb_user,ref_user_affiliation  WHERE user_affiliation = use_aff_id AND log_action != 'cn' AND log_user = user_id AND '".$_POST['month']."' = Month(log_date) AND '".$_POST['year']."' = Year(log_date) AND log_user !='demo-user-1' ORDER BY log_date DESC";
		}*/
	}
	//echo $sql;
	$result = selectSql($sql);
	$data = count($result);
	$row = 50;
	//echo $data;
	if($data > 0){ ?>
		<table hidden class="table table-bordereds printx">
				<thead>
					<tr style="background-color: gray;color:white">
						<td style="text-align:center">ลำดับ</td>
						<td hidden style="text-align:center">รหัสผู้ใช้งาน</td>
						<?php 
							if($_POST['type']==2){?>
								<td style="text-align:center">ชื่อ - นามสกุล</td>
								<td style="text-align:center">สังกัด</td>
								<td style="text-align:center">เวลา</td><?php
							}else{
								?><td style="text-align:center">เวลา</td>
								<td style="text-align:center">ประเภทผู้ใช้งาน</td><?php
							}
						?>
						<?php
							if($_POST['type']==2){ ?>
								<td style="text-align:center">จัดการข้อมูล</td>
								<td style="text-align:center">ข้อมูล</td>
								<td style="text-align:center">รหัส</td>
								<td style="text-align:center">ชื่อผังเมือง</td>
					  <?php } ?>
					</tr>
				</thead>
				<tbody> <?php
					$z=0;
					foreach($result as $bow){ 
						$z++;
					?>
						<tr>
							<td style="text-align:center"><?php echo $z;?></td>
							<td hidden style="text-align:center"><?php echo $bow['log_user'];?></td>
							<?php 
						if($_POST['type']==2){?>
							<td style="text-align:center"><?php echo $bow['user_fname'].'  '.$bow['user_lname'];?></td>
							<td style="text-align:center"><?php echo $bow['use_aff_name']; ?>
							</td><?php
						}?>							
							<td style="text-align:center"><?php echo $bow['log_date'];?></td>
							<?php
								if($_POST['type']==2){ ?>
									<td style="text-align:center;">
										<?php 
											if($bow['log_action']=='add'){
												echo 'เพิ่ม';
											}
											elseif($bow['log_action']=='edit'){
												echo 'แก้ไข';
											}
											elseif($bow['log_action']=='del'){
												echo 'ลบ';
											}
											else{
												echo $bow['log_action'];
											}
										?>
									</td>
									<td style="text-align:center">
										<?php 
											if($bow['log_type']=='cp'){
												echo 'ผังเมือง';
											}
											elseif($bow['log_type']=='note'){
												echo 'หมายเหตุ';
											}
											elseif($bow['log_type']=='user'){
												echo 'ผู้ดูแลระบบ';
											}											
											else{
												echo $row['log_type'];
											}
										?>
									</td>
									<td style="text-align:center"><?php echo $bow['log_target'];?></td>
									<td style="text-align:left"><?php echo $bow['cp_name'];?></td>
						  <?php }
								else{ ?>
									<td style="text-align:center"><?php echo $bow['user_type'];?></td><?php
								} ?>
						</tr><?php
					} ?>
				</tbody>
			</table>
		<div class="table-responsive unprintable">		
		<?php
		for($t=1,$i=0 ; $t<=ceil($data/$row) ; $t++){ ?>
			<table id="table_<?php echo $t;?>" class="table table-bordered unprintable">
				<thead>
					<tr style="background-color: gray;color:white">
						<td style="text-align:center">ลำดับ</td>
						<td hidden style="text-align:center">รหัสผู้ใช้งาน</td>
						<?php 
							if($_POST['type']==2){?>
								<td style="text-align:center">ชื่อ - นามสกุล</td>
								<td style="text-align:center">สังกัด</td>
								<td style="text-align:center">เวลา</td><?php
							}else{
								?><td style="text-align:center">เวลา</td>
								<td style="text-align:center">ประเภทผู้ใช้งาน</td><?php
							}
						?>
						<?php
							if($_POST['type']==2){ ?>
								<td style="text-align:center">จัดการข้อมูล</td>
								<td style="text-align:center">ข้อมูล</td>
								<td style="text-align:center">รหัส</td>
								<td style="text-align:center">ชื่อผังเมือง</td>
					  <?php } ?>
					</tr>
				</thead>
				<tbody> <?php
					for($i,$arow = 1 ; $arow<=$row ; $arow++,$i++){ 
						if($i == $data){break; } ?>
						<tr>
							<td style="text-align:center"><?php echo $i+1;?></td>
							<td hidden style="text-align:center"><?php echo $result[$i]['log_user'];?></td>
							<?php 
						if($_POST['type']==2){?>
							<td style="text-align:center"><?php echo $result[$i]['user_fname'].'  '.$result[$i]['user_lname'];?></td>
							<td style="text-align:center"><?php echo $result[$i]['use_aff_name']; ?>
							</td><?php
						}?>							
							<td style="text-align:center"><?php echo $result[$i]['log_date'];?></td>
							<?php
								if($_POST['type']==2){ ?>
									<td style="text-align:center;">
										<?php 
											if($result[$i]['log_action']=='add'){
												echo 'เพิ่ม';
											}
											elseif($result[$i]['log_action']=='edit'){
												echo 'แก้ไข';
											}
											elseif($result[$i]['log_action']=='del'){
												echo 'ลบ';
											}
											else{
												echo $result[$i]['log_action'];
											}
										?>
									</td>
									<td style="text-align:center">
										<?php 
											if($result[$i]['log_type']=='cp'){
												echo 'ผังเมือง';
											}
											elseif($result[$i]['log_type']=='note'){
												echo 'หมายเหตุ';
											}
											elseif($result[$i]['log_type']=='user'){
												echo 'ผู้ดูแลระบบ';
											}											
											else{
												echo $result[$i]['log_type'];
											}
										?>
									</td>
									<td style="text-align:center"><?php echo $result[$i]['log_target'];?></td>
									<td style="text-align:left"><?php echo $result[$i]['cp_name'];?></td>
						  <?php }
								else{ ?>
									<td style="text-align:center"><?php echo $result[$i]['user_type'];?></td><?php
								} ?>
						</tr><?php
					} ?>
				</tbody>
			</table> <?php
		} ?>
		</div>
		 <?php	
		pagin($row,$data);
	}
	else{ 
		?>
			<h1 style="font-family:Kanit;text-align:center">ไม่มีข้อมูล</h1>
		<?php
	}
?>
<script>
$('#log_value').html('<?php echo $data;?>');
</script>
	