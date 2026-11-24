<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$sql = "SELECT COUNT(cp_no) FROM tb_cityplan";
	$result = selectSql($sql);
	?>
	
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">จัดการข้อมูลผังเมือง</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">จัดการข้อมูลผังเมือง</h4>
	</div>	
	<div class="col-12">
		<div class="col-xs-12" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวน : <?php echo $result[0]['COUNT(cp_no)'];?> ผังเมือง</h5></div>

	</div>
	&nbsp;

	
	<?php
	$sql = "SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_type , ref_scope ,ref_status ,ref_stage ,ref_effective , ref_up_fst_step WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_type = type_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_effective = eff_id AND cp_step = step_id ORDER BY cp_no";
	$result = selectSql($sql);
	echo "<div class='table-responsive' style='border-color:black;margin-top:-10px'>";
	echo "<table class='table table-bordered' >";	
	echo "<thead>";
	echo "<tr style='background-color: #0AAAFA;color:white'>";
	echo "<th style='text-align:center'>ลำดับ</th>";
	//echo "<th style='text-align:center'>รหัสผังเมือง</th>";
	//echo "<th style='text-align:center'>ปีงบประมาณ</th>";
	echo "<th style='text-align:center;'>จังหวัด</th>";
	echo "<th style='text-align:center;'>ชื่อผังเมืองรวม</th>";
	//echo "<th style='text-align:center;width:'>ประเภทผัง</th>";
	//echo "<th style='text-align:center'>ผัง EEC</th>";
	echo "<th style='text-align:center'>ขอบเขต</th>";
	echo "<th style='text-align:center'>สถานะผัง</th>";
	echo "<th style='text-align:center'>ขั้นตอน</th>";
	echo "<th style='text-align:center'>เจ้าของผัง</th>";
	echo "<th style='text-align:center'>ผู้ดำเนินการ</th>";
	echo "<th style='text-align:center'>มีผลบังคับใช้</th>";
	echo "<th style='text-align:center'>ขั้นตอนดำเนินการหลัก</th>";
	echo "</tr>";
	echo "</thead>";	
	echo "<tbody style='background-color:white'>";
	foreach($result as $row) {
		echo "<tr>";
		echo "<td style='text-align:center'>",$row['cp_no'],"</td>";
		//echo "<td style='text-align:center'>",$row['cp_id'],"</td>";
		//echo "<td style='text-align:center'>",$row['cp_budget'],"</td>";
		echo "<td style='text-align:center'>",$row['pvn_name'],"</td>";
		echo "<td style='text-align:center'>",$row['cp_name'],"</td>";
		//echo "<td style='text-align:center'>",$row['cp_type'],"</td>";
		//echo "<td style='text-align:center'>",$row['cp_eec'],"</td>";
		echo "<td style='text-align:center'>",$row['scope_name'],"</td>";
		echo "<td style='text-align:center'>",$row['status_name'],"</td>";
		echo "<td style='text-align:center'>",$row['stage_name'],"</td>";
		echo "<td style='text-align:center'>",$row['cp_owner'],"</td>";
		echo "<td style='text-align:center'>",$row['cp_operator'],"</td>";
		echo "<td style='text-align:center'>",$row['eff_name'],"</td>";
		if($row['step_id']==0){echo "<td style='text-align:center;color:red'> - </td>";}
		else{echo "<td style='text-align:center'>",$row['step_name'],"</td>";}
		echo "</tr>";
	}
	
	
	echo "</tbody>";
	echo "</table>";
	echo "</div>";
?>
