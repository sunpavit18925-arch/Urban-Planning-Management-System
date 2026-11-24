<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<table id="CP_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #295DBD;color:white'>
			<th style="text-align:center">สถานภาพ</th>						
			<th style="text-align:center">จำนวนผังเมือง</th>				
		</tr>
	</thead>
	<tbody>
		<?php 
		
		$sql ="SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 ";
		$sta1 = selectSql($sql);
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_effective = 1";
		$sta2e1 = selectSql($sql);
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_effective = 2";
		$sta2e2= selectSql($sql);
		//ไม่ได้กำหนดผลบังคับใช้
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_effective = 0";
		$undef_eff = selectSql($sql);
		//ไม่ได้กำหนดสถานะผัง
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 0 ";
		$undef_sta = selectSql($sql);
		
		$sum = $sta1[0]['COUNT(*)']+$sta2e1[0]['COUNT(*)']+$sta2e2[0]['COUNT(*)']+$undef_sta[0]['COUNT(*)']+$undef_eff[0]['COUNT(*)'];
			
		?>
		<tr>
			<td><?php echo "ผังเมืองรวมที่ประกาศบังคับใช้"; ?></td>
			<td style="text-align:center"><a onclick="showStatus(2,1)"><?php echo $sta2e1[0]['COUNT(*)']; ?></a></td>
		</tr>
		
		<tr>
			<td><?php echo "ผังเมืองรวมที่สิ้นสุดการบังคับใช้"; ?></td>
			<td style="text-align:center"><a onclick="showStatus(2,2)"><?php echo $sta2e2[0]['COUNT(*)']; ?></a></td>
		</tr>
		
		<tr>
			<td><?php echo "ผังเมืองรวมที่อยู่ในขั้นตอนดำเนินการ"; ?></td>
			<td style="text-align:center"><a onclick="showStatus(1,0)"><?php echo $sta1[0]['COUNT(*)']; ?></a></td>
		</tr>
		<?php if($undef_sta[0]['COUNT(*)'] > 0){
		?>
		<tr>
			<td style="color:red"><?php echo "ยังไม่ได้กำหนดสถานภาพผัง"; ?></td>
			<td style="text-align:center;color:red"><a onclick="showStatus(0,0)"><?php echo $undef_sta[0]['COUNT(*)']; ?></a></td>
		</tr>
		<?php } 	
		
		if($undef_eff[0]['COUNT(*)'] > 0){
		?>
		<tr>
			<td style="color:red"><?php echo "ยังไม่ได้กำหนดผลบังคับใช้"; ?></td>
			<td style="text-align:center;color:red"><a onclick="showStatusEffective()"><?php echo $undef_eff[0]['COUNT(*)']; ?></a></td>
		</tr>
		<?php } ?>
		<tr>
			<td style="text-align:center"><b>รวม</b></td>
			<td style="text-align:center"><b><?php echo $sum; ?></b></td>
		</tr>		
	</tbody>
</table>


<script>
	function showStatus(x,y){
		$.post('report/show_result.php',{
			status_id :x ,
			eff_id : y,
			event :'status'
		},function(data){
			$('#mainshow').html(data);
		})
	}
	function showStatusEffective(){
		$.post('report/show_result.php',{
			status_id :2 ,
			eff_id : 0,
			event :'status_effective'
		},function(data){
			$('#mainshow').html(data);
		})
	}
</script>