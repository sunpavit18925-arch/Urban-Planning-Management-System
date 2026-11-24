<?php 
include '../js/function_db.php';
$operate = ['สิ้นสุดใช้บังคับ','มีผลบังคับใช้','เปิดพื้นที่ใหม่'];
$operatey = ['ยกร่างกฎหมาย','ย้อนดำเนินการ','ตามขั้นตอนของพรบ.การผังเมือง 2562','เร่งรัดปรับปรุง','ไม่ระบุ'];
$condopt = [' AND tb_cityplan.cp_status = 2 AND tb_cityplan.cp_stage = 3 AND tb_cityplan.cp_effective != 1 ORDER BY tb_cityplan.cp_name ASC',' AND tb_cityplan.cp_status = 2 AND tb_cityplan.cp_stage = 3 AND tb_cityplan.cp_effective = 1 ORDER BY tb_cityplan.cp_name ASC',' AND tb_cityplan.cp_status = 1 AND tb_cityplan.cp_stage = 1 ORDER BY tb_cityplan.cp_name ASC'];
$optx = [1.1,1.2,2,3];

// SELECT * FROM tb_cityplan WHERE (cp_status = 1) OR (cp_status = 2 AND cp_stage = 3 AND cp_effective = 1) OR (cp_status = 2 AND cp_stage = 3 AND cp_effective = 2)

$sql = 'SELECT COUNT(tb1.cp_no) as cp FROM tref_rp_cs_operate as tb2 LEFT JOIN tb_cityplan as tb1 on tb1.cp_current_status = tb2.rp_cs_op_id AND tb1.cp_status = 1 GROUP BY tb2.rp_cs_op_id ASC';
$new = selectSql($sql);
//$sql = 'SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 1';
$sql = 'SELECT COUNT(tb1.cp_no) as cp FROM tref_rp_cs_operate as tb2 LEFT JOIN tb_cityplan as tb1 on tb1.cp_current_status = tb2.rp_cs_op_id AND tb1.cp_status = 2 AND tb1.cp_stage = 3 AND tb1.cp_effective = 1 GROUP BY tb2.rp_cs_op_id ASC';
$bbf = selectSql($sql);
//$sql = 'SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 2';
$sql = 'SELECT COUNT(tb1.cp_no) as cp FROM tref_rp_cs_operate as tb2 LEFT JOIN tb_cityplan as tb1 on tb1.cp_current_status = tb2.rp_cs_op_id AND tb1.cp_status = 2 AND tb1.cp_stage = 3 AND tb1.cp_effective = 2 GROUP BY tb2.rp_cs_op_id ASC';
$exp = selectSql($sql);



//echo $sql;
?>
<div class="table-responsive" style="margin-top:15px;padding:0;">
<table class="table">
	<thead>
		<tr>
			<th style="width:22%;text-align:center;vertical-align:middle;font-size:18px;color:white!important;background-color:#295DBD!important">ผังเมืองรวมที่ดำเนินงาน</th>
			<th style="width:26%;text-align:center;vertical-align:middle;color: #343434!important;background: #bebebe!important;"><text class="clickable" onclick="showexplandata(5)">กฎกระทรวงผังเมืองรวม<br><text style="font-size:18px;color: #343434!important;">สิ้นสุดใช้บังคับ</text><br>(ดำเนินการปรับปรุงผัง)</text></th>
			<th style="width:26%;text-align:center;vertical-align:middle;color:white!important;background:#3e6e0f!important"><text class="clickable" onclick="showexplandata(6)">กฎกระทรวงผังเมืองรวม<br><text style="font-size:18px;color: white!important;">มีผลบังคับใช้</text><br>(ดำเนินการปรับปรุงผัง)</text></th>
			<th style="width:26%;text-align:center;vertical-align:middle;color: #082f50!important;background: #5191e9!important;"><text class="clickable" onclick="showexplandata(7)">ผังเมืองรวม/ชุมชน<br><text style="font-size:18px;color: #082f50!important;">เปิดพื้นที่ใหม่</text></text></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="background:white;font-size:17px;font-weight:600">1. ตามมติคณะกรรมการ<br>&nbsp;&nbsp;&nbsp;ผังเมือง (ม. 110)</td>
			<td  style="background:white;text-align:center;color:#343434!important;background: #f0f0f0!important;"> </td>
			<td  style="background:white;text-align:center;color:#343434!important;background: #97b17d!important;"> </td>
			<td  style="background:white;text-align:center;color:#000000!important;background: #d3e5ff!important;"> </td>
		</tr>
		
		<tr>
			<td  style="background:white;border-top:none;"> &nbsp;&nbsp;&nbsp;&nbsp;<text class="clickable" onclick="showexplandata(1)">1) ยกร่างกฎหมาย</text></td>
			<td  style="background:white;border-top:none;text-align:center;color:#343434!important;background: #f0f0f0!important;"><?= $exp[1]['cp']?></td>
			<td  style="background:white;border-top:none;text-align:center;color:#343434!important;background: #97b17d!important;"><?= $bbf[1]['cp']?></td>
			<td  style="background:white;border-top:none;text-align:center;color:#000000!important;background: #d3e5ff!important;"><?= $new[1]['cp']?></td>
		</tr>
		<tr>
			<td  style="background:white;border-top:none;"> &nbsp;&nbsp;&nbsp;&nbsp;<text class="clickable" onclick="showexplandata(2)">2) ย้อนดำเนินการ</text></td>
			<td  style="background:white;border-top:none;text-align:center;color:#343434!important;background: #f0f0f0!important;"><?= $exp[2]['cp']?></td>
			<td  style="background:white;border-top:none;text-align:center;color:#343434!important;background: #97b17d!important;"><?= $bbf[2]['cp']?></td>
			<td  style="background:white;border-top:none;text-align:center;color:#000000!important;background: #d3e5ff!important;"><?= $new[2]['cp']?></td>
		</tr>
		<tr>
			<td  style="background:white;"><text class="clickable" onclick="showexplandata(3)" style="font-size:17px;font-weight:600">2. ตามขั้นตอนของ</text><br>&nbsp;&nbsp;&nbsp;&nbsp;พระราชบัญญัติการผังเมือง พ.ศ. 2562</td>
			<td  style="background:white;text-align:center;color:#343434!important;background: #f0f0f0!important;"><?= $exp[3]['cp'];?></td>
			<td  style="background:white;text-align:center;color:#343434!important;background: #97b17d!important;"><?= $bbf[3]['cp']?></td>
			<td  style="background:white;text-align:center;color:#000000!important;background: #d3e5ff!important;"><?= $new[3]['cp']?></td>
		</tr>
		<tr>
			<td  style="background:white;font-size:17px;font-weight:600"><text class="clickable" onclick="showexplandata(4)">3. เร่งรัดปรับปรุง</text></td>
			<td  style="background:white;text-align:center;color:#343434!important;background: #f0f0f0!important;"><?= $exp[4]['cp']?></td>
			<td  style="background:white;text-align:center;color:#343434!important;background: #97b17d!important;"><?= $bbf[4]['cp']?></td>
			<td  style="background:white;text-align:center;color:#000000!important;background: #d3e5ff!important;"><?= $new[4]['cp']?></td>
		</tr>
		<?php 
		if(($exp[0]['cp']+$bbf[0]['cp']+$new[0]['cp']) > 0){ ?>
		<tr>
			<td  style="background:white;font-size:17px;font-weight:600;color:red!important;">ไม่ระบุ</td>
			<td  style="background:white;text-align:center;color:red!important;background: #f0f0f0!important;"><?= $exp[0]['cp']?></td>
			<td  style="background:white;text-align:center;color:red!important;background: #97b17d!important;"><?= $bbf[0]['cp']?></td>
			<td  style="background:white;text-align:center;color:red!important;background: #d3e5ff!important;"><?= $new[0]['cp']?></td>
		</tr>
		<?php } ?>
		<tr>
			<td  style="background:white;text-align:center;font-size:17px;font-weight:600">รวม</td>
			<td  style="background:white;text-align:center;font-size:15px;font-weight:600;color:#343434!important;background: #f0f0f0!important;"><?= $exp[0]['cp']+$exp[1]['cp']+$exp[2]['cp']+$exp[3]['cp']+$exp[4]['cp']?></td>
			<td  style="background:white;text-align:center;font-size:15px;font-weight:600;color:#343434!important;background: #97b17d!important;"><?= $bbf[0]['cp']+$bbf[1]['cp']+$bbf[2]['cp']+$bbf[3]['cp']+$bbf[4]['cp']?></td>
			<td  style="background:white;text-align:center;font-size:15px;font-weight:600;color:#000000!important;background: #d3e5ff!important;"><?= $new[0]['cp']+$new[1]['cp']+$new[2]['cp']+$new[3]['cp']+$new[4]['cp']?></td>
		</tr>
	</tbody>
</table>

</div>
<div class="row" style="padding:0 15px 8px 15px">
	<button id="btn_close_div_xpd" class="btn btn-danger pull-right" onclick="(closexplandata())">ปิด</button>
	<button id="btn_show_detail" class="btn btn-success pull-right" style="margin-right:8px;" onclick="">แสดงข้อมูลเพิ่มเติม</button>
</div>
		<?php
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_current_status = 1.1";
		$result_df = selectSql($sql);
		$all_dflaw = $result_df[0]['COUNT(*)'];
		?>
		<div id="div_xpd_1" class="div_explan_data"  style="border:dashed black 3px;padding:8px;border-radius:12px;    display: flex;flex-direction: column;overflow: auto;">
			<div style="padding:8px;font-size:20px;font-weight:600;background:#dedede;border-radius:8px;margin-bottom:8px;">กลุ่มผังเมืองรวมที่ดำเนินการยกร่างกฎหมาย จำนวน <?= $all_dflaw;?> ผัง</div>
			<?php 
			$sql = "SELECT * FROM tref_rp_cs_dflaws ORDER BY rp_cs_df_asc ASC";
			$result = selectSql($sql);
			$all_df = count($result);
			for($i=0;$i<$all_df;$i++){ 
			$sql  = "SELECT cp_name FROM tb_rp_current_status , tb_cityplan WHERE tb_cityplan.cp_no = tb_rp_current_status.cp_no AND cp_cs_draft_laws = '".($i+1)."'";
			$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_current_status = 1.1 AND cp_dflaw = ".($i+1);
			$result_df = selectSql($sql);
			$all_data = count($result_df);
			
			?>
			<div id="df_<?= $i+1;?>" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4"><?= ($i+1).') '.$result[$i]['rp_cs_df_title'];?> จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result_df as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';
					 }
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
					
				<?php } ?>
				
			</div>
			
			
			<?php } ?>
		</div>
		
		<?php
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_current_status = 1.2";
		$result_df = selectSql($sql);
		$all_dflaw = $result_df[0]['COUNT(*)'];
		?>
		<div id="div_xpd_2" class="div_explan_data"  style="border:dashed black 3px;padding:8px;border-radius:12px;    display: flex;flex-direction: column;overflow: auto;">
			<div style="padding:8px;font-size:20px;font-weight:600;background:#dedede;border-radius:8px;margin-bottom:8px;">กลุ่มผังเมืองรวมที่ย้อนดำเนินการ จำนวน <?= $all_dflaw;?> ผัง</div>
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 2 AND cp_current_status = 1.2";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op12" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">1) สิ้นสุดบังคับใช้ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 1 AND cp_current_status = 1.2";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op12" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">2) มีผลบังคับใช้ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 1 AND cp_current_status = 1.2";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op12" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">3) เปิดพื้นที่ใหม่ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
			
		</div>
		
		<?php
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_current_status = 2";
		$result_df = selectSql($sql);
		$all_dflaw = $result_df[0]['COUNT(*)'];
		?>
		<div id="div_xpd_3" class="div_explan_data" style="border:dashed black 3px;padding:8px;border-radius:12px;    display: flex;flex-direction: column;overflow: auto;">
			<div style="padding:8px;font-size:20px;font-weight:600;background:#dedede;border-radius:8px;margin-bottom:8px;">กลุ่มผังเมืองรวมดำเนินการตามขั้นตอนของ พรบ. การผังเมือง พ.ศ. 2562 จำนวน <?= $all_dflaw;?> ผัง</div>
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 2 AND cp_current_status = 2";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op2" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">1) สิ้นสุดบังคับใช้ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 1 AND cp_current_status = 2";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op2" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">2) มีผลบังคับใช้ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 1 AND cp_current_status = 2";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op2" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">3) เปิดพื้นที่ใหม่ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
		</div>
		
		<?php
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_current_status = 3";
		$result_op3 = selectSql($sql);
		$all_op3 = $result_op3[0]['COUNT(*)'];
		?>
		<div id="div_xpd_4" class="div_explan_data" style="border:dashed black 3px;padding:8px;border-radius:12px;    display: flex;flex-direction: column;overflow: auto;">
			<div style="padding:8px;font-size:20px;font-weight:600;background:#dedede;border-radius:8px;margin-bottom:8px;">กลุ่มผังเมืองรวมที่ดำเนินการเร่งรัดปรับปรุง จำนวน <?= $all_op3;?> ผัง</div>
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 2 AND cp_current_status = 3";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op3" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">1) สิ้นสุดบังคับใช้ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 1 AND cp_current_status = 3";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op3" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">2) มีผลบังคับใช้ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
			<?php
				$sql = "SELECt cp_name FROM tb_cityplan WHERE cp_status = 1 AND cp_current_status = 3";
				$result = selectSql($sql);
				$all_data = count($result); 
			?>
			<div id="op3" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4">3) เปิดพื้นที่ใหม่ จำนวน <?= $all_data;?> ผัง</div>
				<?php 
				$x = 0;
				if($all_data>0){
					foreach($result as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php 
				}
				?>
			</div>
			
		</div>
		
		
		<?php
		$sql = "SELECT tb2.rp_cs_op_no ,  tb2.rp_cs_op_id , tb2.rp_cs_op_title , COUNT(tb1.cp_no) as cp FROM tb_cityplan as tb1 RIGHT JOIN tref_rp_cs_operate as tb2 on tb1.cp_current_status = tb2.rp_cs_op_id AND tb1.cp_status = 2 AND tb1.cp_stage = 3 AND tb1.cp_effective = 2 GROUP BY tb2.rp_cs_op_no ASC";
		//echo $sql;
		$result = selectSql($sql);
		$all_data = 0;
		for($i=0;$i<COUNT($result);$i++){
			$all_data += $result[$i]['cp'];
		}
		?>
		<div id="div_xpd_5" class="div_explan_data" style="border:dashed black 3px;padding:8px;border-radius:12px;    display: flex;flex-direction: column;overflow: auto;">
			<div style="padding:8px;font-size:20px;font-weight:600;background:#dedede;border-radius:8px;margin-bottom:8px;">สิ้นสุดใช้บังคับ จำนวน <?= $all_data;?> ผัง</div>
			<?php 
			for($i=0;$i<COUNT($result);$i++){
				?>
				<div id="op4" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4"><?=$result[$i]['rp_cs_op_no'].') '.$result[$i]['rp_cs_op_title']?> จำนวน <?= $result[$i]['cp'];?> ผัง</div>
				<?php 
				$sql = "SELECT cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 2 AND cp_current_status = ".$result[$i]['rp_cs_op_id'];
				$result_2 = selectSql($sql);
				$x = 0;
				$all_data = count($result_2);
				if($all_data>0){
					foreach($result_2 as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php } ?>
				</div>
			<?php } ?>
		</div>
		
		<?php
		$sql = "SELECT tb2.rp_cs_op_no ,  tb2.rp_cs_op_id , tb2.rp_cs_op_title , COUNT(tb1.cp_no) as cp FROM tb_cityplan as tb1 RIGHT JOIN tref_rp_cs_operate as tb2 on tb1.cp_current_status = tb2.rp_cs_op_id AND tb1.cp_status = 2 AND tb1.cp_stage = 3 AND tb1.cp_effective = 1 GROUP BY tb2.rp_cs_op_no ASC";
		//echo $sql;
		$result = selectSql($sql);
		$all_data = 0;
		for($i=0;$i<COUNT($result);$i++){
			$all_data += $result[$i]['cp'];
		}
		?>
		<div id="div_xpd_6" class="div_explan_data" style="border:dashed black 3px;padding:8px;border-radius:12px;    display: flex;flex-direction: column;overflow: auto;">
			<div style="padding:8px;font-size:20px;font-weight:600;background:#dedede;border-radius:8px;margin-bottom:8px;">มีผลบังคับใช้ จำนวน <?= $all_data;?> ผัง</div>
			<?php 
			for($i=0;$i<COUNT($result);$i++){
				?>
				<div id="op4" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4"><?=$result[$i]['rp_cs_op_no'].') '.$result[$i]['rp_cs_op_title']?> จำนวน <?= $result[$i]['cp'];?> ผัง</div>
				<?php 
				$sql = "SELECT cp_name FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_effective = 1 AND cp_current_status = ".$result[$i]['rp_cs_op_id'];
				$result_2 = selectSql($sql);
				$x = 0;
				$all_data = count($result_2);
				if($all_data>0){
					foreach($result_2 as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php } ?>
				</div>
			<?php } ?>
			
		</div>
		
		
		<?php
		$sql = "SELECT tb2.rp_cs_op_no ,  tb2.rp_cs_op_id , tb2.rp_cs_op_title , COUNT(tb1.cp_no) as cp FROM tb_cityplan as tb1 RIGHT JOIN tref_rp_cs_operate as tb2 on tb1.cp_current_status = tb2.rp_cs_op_id AND tb1.cp_status = 1 GROUP BY tb2.rp_cs_op_no ASC";
		//echo $sql;
		$result = selectSql($sql);
		$all_data = 0;
		for($i=0;$i<COUNT($result);$i++){
			$all_data += $result[$i]['cp'];
		}
		?>
		<div id="div_xpd_7" class="div_explan_data" style="border:dashed black 3px;padding:8px;border-radius:12px;    display: flex;flex-direction: column;overflow: auto;">
			<div style="padding:8px;font-size:20px;font-weight:600;background:#dedede;border-radius:8px;margin-bottom:8px;">เปิดพื้นที่ใหม่ จำนวน <?= $all_data;?> ผัง</div>
			<?php 
			for($i=0;$i<COUNT($result);$i++){
				?>
				<div id="op4" style="padding:8px;min-width:750px;">
				<div style="font-size:16px;font-weight:600;padding:8px;margin-bottom:8px;background:#e4e4e4"><?=$result[$i]['rp_cs_op_no'].') '.$result[$i]['rp_cs_op_title']?> จำนวน <?= $result[$i]['cp'];?> ผัง</div>
				<?php 
				$sql = "SELECT cp_name FROM tb_cityplan WHERE cp_status = 1  AND cp_current_status = ".$result[$i]['rp_cs_op_id'];
				$result_2 = selectSql($sql);
				$x = 0;
				$all_data = count($result_2);
				if($all_data>0){
					foreach($result_2 as $row){
						$x++;
						echo '<div class="col-xs-4 col-md-3">('.$x.') '.$row['cp_name'].'</div>';	
					}
				}
				else{ ?>
					<div class="col-xs-12" style="color:red">ไม่มีข้อมูล</div>
				<?php } ?>
				</div>
			<?php } ?>
	
		</div>
		<div id="showdetail"></div>
		
		
		
			
		
		
		<script>
			$('.div_explan_data').hide();
			$('#btn_close_div_xpd').hide();
			$('#btn_show_detail').hide();
			
			function showdetail(x){
				$.ajax({
					url : 'app/report_a.php',
					type : 'POST',
					async : true,
					data : {
						event : x
					},
					success : function(data,status,xhr){
						//alert(data);
						$('#showdetail').html(data).show();
						$('.div_explan_data').hide();
						$('#btn_show_detail').removeAttr('onclick');
						$('#btn_show_detail').attr('onclick','showexplandata('+x+')');
						$('#btn_show_detail').html('แสดงข้อมูลก่อนหน้า');
					},
					error : function(xhr,status,data){
						alert('Loading : '+status);
					}
				});
			}
			
			function showexplandata(x){
				$('#btn_show_detail').html('แสดงข้อมูลเพิ่มเติม');
				$('#showdetail').html('').hide();
				$('#btn_show_detail').attr('onclick','showdetail('+x+')');
				/*if(x>=5 && x<=7){
					$('#btn_show_detail').attr('onclick','showdetail('+x+')');
				}else{
					$('#btn_show_detail').removeAttr('onclick');
				}*/
				$('.div_explan_data').hide();
				$('#div_xpd_'+x).show()
				$('#btn_close_div_xpd').show();
				$('#btn_show_detail').show();
			}
			function closexplandata(){
				$('#btn_close_div_xpd').hide();
				$('.div_explan_data').hide();
				$('#btn_show_detail').hide();
				$('#showdetail').html('').hide();
			}
		</script>
	