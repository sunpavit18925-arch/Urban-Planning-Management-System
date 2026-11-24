<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<table id="CP_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #295DBD;color:white'>
			<th style="text-align:center">ขั้นตอน</th>						
			<th style="text-align:center">จำนวนผังเมือง</th>				
		</tr>
	</thead>
	<tbody>
		<?php 
			$sql = "SELECT COUNT(cp_stage) FROM tb_cityplan WHERE cp_stage = 1";
			$stg1 = selectSql($sql);
			$sql = "SELECT COUNT(cp_stage) FROM tb_cityplan WHERE cp_stage = 2 OR cp_stage = 4";
			$stg2 = selectSql($sql);
			$sql = "SELECT COUNT(cp_stage) FROM tb_cityplan WHERE cp_stage = 3";
			$stg3 = selectSql($sql);
			
			$sql = "SELECT COUNT(cp_stage) FROM tb_cityplan WHERE cp_stage = 1 OR cp_stage = 2 OR cp_stage = 3";
			$result = selectSql($sql);
			$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage IS NULL OR cp_stage = 0 ";
			$undef = selectSql($sql);
			
?>
		<tr>
			<td><?php echo "ผังที่ดำเนินการในขั้นตอนการวางและจัดทำ"; ?></td>
			<td style="text-align:center"><a onclick="showStage(1);"> <?php echo $stg1[0]['COUNT(cp_stage)']; ?></a></td>
		</tr>
		<tr>
			<td><?php echo "ผังที่ดำเนินการในขั้นตอนการประเมินผลผัง"; ?></td>
			<td style="text-align:center"><a onclick="showStage(2);"> <?php echo $stg2[0]['COUNT(cp_stage)']; ?></a></td>
		</tr>
		<tr>
			<td><?php echo "ผังที่ดำเนินการในขั้นตอนการปรับปรุงผัง"; ?></td>
			<td style="text-align:center"><a onclick="showStage(3);"> <?php echo $stg3[0]['COUNT(cp_stage)']; ?></a></td>
		</tr>
		<?php if($undef[0]['COUNT(*)']>0){ ?>
		<tr>
			<td style="color:red"><?php echo "ยังไม่ได้กำหนดขั้นตอน"; ?></td>
			<td style="text-align:center;color:red"><a onclick="showStage(0);"><?php echo $undef[0]['COUNT(*)']; ?></a></td>
		</tr>
		<?php } ?>
		<tr>
			<td style="text-align:center"><b>รวม</b></td>
			<td style="text-align:center"><b><?php echo $result[0]['COUNT(cp_stage)']+$undef[0]['COUNT(*)']; ?></b></td>
		</tr>		
	</tbody>
</table>

<script>
	function showStage(x){
		$.post('report/show_result.php',{
			stage_id :x ,
			event :'stage'
		},function(data){
			$('#mainshow').html(data);
		})
	}

</script>