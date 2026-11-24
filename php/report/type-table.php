<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<table id="CP_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #0AAAFA;color:white'>
			<th style="text-align:center">ประเภทผังเมือง</th>						
			<th style="text-align:center">จำนวนผังเมือง</th>				
		</tr>
	</thead>
	<tbody>
		<?php 
			$sql = "SELECT COUNT(*) FROM tb_cityplan";
			$countAll = selectSql($sql);
			$sql = "SELECT cp_type FROM tb_cityplan WHERE cp_type IS NOT NULL GROUP BY cp_type";
			$type =selectSql($sql);
			$row = COUNT($type);
			$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_type IS NULL OR cp_type = 0 GROUP BY cp_type";
			$undef = selectSql($sql);
			if($undef[0]['COUNT(*)'] > 0){ ?>
				<tr style="color:red">
					<td style="color:red">ยังไม่ได้กำหนดประเภท</td>
					<td style="text-align:center"><?php echo $undef[0]['COUNT(*)']; ?></td>
				</tr>
		<?php  } 
			$sql = "SELECT * FROM ref_type WHERE type_id > 0";
			$type_name = selectSql($sql);
			foreach($type_name as $rows){ 
				$sql = "SELECT COUNT(cp_type) FROM tb_cityplan WHERE cp_type = '".$rows['type_id']."'";
				$result = selectSql($sql);
			?>
				<tr>
					<td style="text-align:left"><?php echo $rows['type_name'] ;?></td>
					<td style="text-align:center"><?php echo $result[0]['COUNT(cp_type)']; ?></td>
				</tr>
				
			<?php  } ?>
			
			
		
			
			
		<tr>
			<td style="text-align:center"><b>รวม</b></td>
			<td style="text-align:center"><b><?php echo $countAll[0]['COUNT(*)'];?></b></td>
		</tr>		
	</tbody>
</table>


<script>
	function showType(x){
		$.post('report/show_result.php',{
			type_id :x ,
			event :'type'
		},function(data){
			$('#mainshow').html(data);
		})
	}
</script>