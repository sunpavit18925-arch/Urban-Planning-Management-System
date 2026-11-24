<?php 
	include '../js/function_php.php';
	session_name('tutorial');
	session_start();
	
	
	
	if($_POST['type']=='cityplan'){
		//echo "cityplan";
		$sql = "SELECT * FROM tb_cityplan,ref_province,ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_name LIKE '%".$_POST['key']."%' ORDER BY pvn_name";
		$result = selectSql($sql);
		$carray = count($result);
		echo $carry;
		if($result == null){
			echo '<h1 class="hidden-xs hidden-sm" style ="text-align:center;font-family:Kanit;color:red;">';
				echo "ไม่พบข้อมูล กรุณาค้นหาใหม่";	
			echo '</h1>';
			echo '<h2 class="hidden-lg hidden-md" style ="text-align:center;font-family:Kanit;color:red;">';
				echo "ไม่พบข้อมูล<br>กรุณาค้นหาใหม่";	
			echo '</h2>';
		}else{
			echo "<table class='tablex table-bordered' style='width:100%'>";
				echo "<thead>";
					echo "<tr style='background-color: #0AAAFA;color:white' >";
						echo "<td style='text-align:center'>","id","</td>";
						echo "<td style='text-align:center'>","name","</td>";
						echo "<td style='text-align:center' >","province","</td>";
						echo "<td style='text-align:center'>","affiliation","</td>";
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				foreach($result as $row){
					echo "<tr>";
						echo "<td style='text-align:center'>",$row['cp_no'],"</td>";
						echo "<td>",$row['cp_name'],"</td>";
						echo "<td style='text-align:center'>",$row['pvn_name'],"</td>";
						echo "<td style='text-align:center'>",$row['aff_name'],"</td>";
					echo "</tr>";
				}
				echo "</tbody>";
			echo "</table>";
		}
	}
	elseif($_POST['type']=='eec'){
		echo "eec";
	}
	elseif($_POST['type']=='admin'){
		echo "admin";
	}
	else{
		if($_POST['name']=="" OR $_POST['name']== NULL ){
			$sql = "SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_type , ref_scope , ref_operate_fst , ref_operate_snd , ref_status , ref_stage ,ref_effective , ref_up_fst_step WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_type = type_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_effective = eff_id AND cp_step = step_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id ORDER BY cp_no ASC";
		}else{
			$sql = "SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_type , ref_scope , ref_operate_fst , ref_operate_snd , ref_status , ref_stage , ref_effective ,ref_up_fst_step WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_type = type_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_effective = eff_id AND cp_step = step_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id ORDER BY cp_no ASC";
		}
		$result = selectSql($sql);	
		//echo $sql;
		$count_result = count($result);
		//echo $count_result;
		if($count_result==0){
			echo "<h2 style='font-family:Kanit;text-align:center'>ไม่พบข้อมูล </h2>";
			echo "<script> $('#tableshow').hide();</script>";		 
		}
		?>
		<script> $('#value_row').html('จำนวน : '+ <?php echo $count_result; ?> +' ผัง'); </script>
		<table id="tableshow" class="table table-bordered">	
		<thead>
			<tr style='background-color: #0AAAFA;color:white'>
				<td style='text-align:center'>รหัสผังเมือง</td>
				<!--echo "<th style='text-align:center'>รหัสผังเมือง</th>-->
				<!-- //echo "<th style='text-align:center'>ปีงบประมาณ</th>-->
				<td style='text-align:center;'>จังหวัด</td>
				<td style='text-align:center;'>ชื่อผังเมืองรวม</td>
				<!-- echo "<th style='text-align:center;width:'>ประเภทผัง</th>-->
				<!-- echo "<th style='text-align:center'>ผัง EEC</th>-->
				<td style='text-align:center'>ขอบเขต</td>
				<td style='text-align:center'>สถานะผัง</td>
				<td style='text-align:center'>ขั้นตอน</td>
				<td style='text-align:center'>เจ้าของผัง</td>
				<td style='text-align:center'>ผู้ดำเนินการ</td>
				<td style='text-align:center'>มีผลบังคับใช้</td>
				<td style='text-align:center'>ขั้นตอนดำเนินการหลัก</td>
				<td style='text-align:center'>จัดการ </td>
			</tr>
		</thead>	
		<tbody style='background-color:white'>
		<?php 	foreach($result as $row) { ?>
			<tr>
				<td style="text-align:center"><?php echo $row['cp_no'];?></td>
				<td style="text-align:center"><?php echo $row['pvn_name'];?></td>
				<td style="text-align:center"><?php echo $row['cp_name'];?></td>
				<td style="text-align:center"><?php echo $row['scope_name'];?></td>
				<td style="text-align:center"><?php echo $row['status_name'];?></td>
				<td style="text-align:center"><?php echo $row['stage_name'];?></td>
				<td style="text-align:center"><?php echo $row['opt_fst_name'];?></td>
				<td style="text-align:center"><?php echo $row['opt_snd_name'];?></td>
				<td style="text-align:center"><?php echo $row['eff_name'];?></td>
				<td style="text-align:center"><?php if($row['cp_step']==0){echo '-';}else{echo 'ขั้นที่ ',$row['cp_step'];}?></td>
				<td style="text-align:center">
					<span title="แก้ไข" class="gear" onclick="cpEdit(<?php echo $row['cp_no'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear access_<?php echo $row['pvn_affiliation'];?>" >  </i>&nbsp;</a></span>
					<span hidden title="ลบ" class="trash" onclick="cpDel(<?php echo $row['cp_no'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>
					
				</td>
			</tr>
		<?php } ?> 
			</tbody>
		</table>
	<?php } 
	
	//accessdata(TRUE);
	?>
	
	
	
	
