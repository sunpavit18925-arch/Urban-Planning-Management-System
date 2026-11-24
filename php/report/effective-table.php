<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<table id="CP_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #0AAAFA;color:white'>
			<th style="text-align:center">มีผลบังคับใช้</th>						
			<th style="text-align:center">จำนวนผังเมือง</th>				
		</tr>
	</thead>
	<tbody>
		<?php 
			$sql = "SELECT COUNT(cp_effective) FROM tb_cityplan WHERE cp_effective = 1";
			$eff1 = selectSql($sql);
			$sql = "SELECT COUNT(cp_effective) FROM tb_cityplan WHERE cp_effective = 2";
			$eff2 = selectSql($sql);

			
			$sql = "SELECT COUNT(cp_effective) FROM tb_cityplan WHERE cp_effective = 1 OR cp_effective = 2 ";
			$result = selectSql($sql);
			$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_effective = 0 ";
			$undef = selectSql($sql);
			
?>
		<tr>
			<td><?php echo "มีอายุ"; ?></td>
			<td style="text-align:center"><a onclick="showEffective(1);"><?php echo $eff1[0]['COUNT(cp_effective)']; ?></a></td>
		</tr>
		<tr>
			<td><?php echo "หมดอายุ"; ?></td>
			<td style="text-align:center"><a onclick="showEffective(2);"><?php echo $eff2[0]['COUNT(cp_effective)']; ?></a></td>
		</tr>
		<?php if($undef[0]['COUNT(*)'] > 0){ ?>
		<tr>
			<td style="color:red"><?php echo "ยังไม่ได้กำหนดผลบังคับใช้"; ?></td>
			<td style="text-align:center;color:red"><a onclick="showEffective(0);"><?php echo $undef[0]['COUNT(*)']; ?></a></td>
		</tr>
		<?php } ?>
		<tr>
			<td style="text-align:center"><b>รวม</b></td>
			<td style="text-align:center"><b><?php echo $result[0]['COUNT(cp_effective)']+$undef[0]['COUNT(*)']; ?></b></td>
		</tr>		
	</tbody>
</table>

<script>
	function showEffective(x){
		$.post('report/show_result.php',{
			eff_id :x,
			event :'effective'
		},function(data){
			$('#mainshow').html(data);
		})
	}
</script>