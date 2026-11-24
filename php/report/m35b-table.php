<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  $owner = 2;
?>
<table id="CP_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #295DBD;color:white'  rowspan="2">
			<th style="text-align:center" rowspan="2">ขั้นตอน</th>	
			<th style="text-align:center"  rowspan="2">จำนวน<br>ผังเมือง</th>				
		</tr>
	</thead>
	<tbody>
		<?php 		
		$sql = "SELECT * FROM ref_m35_fst_step WHERE m35_fst_id != 0 AND m35_operate = '".$owner."'";	
		$result = selectSql($sql);
		$cp = 0;
		foreach($result as $step){ 
			$sql = "SELECT COUNT(t1.cp35_no) AS cp FROM tb_cityplan35 AS t1 JOIN ref_cityplan_step_detail_m35 AS t2 ON t1.cp35_csd = t2.csd35_seq JOIN tb_cityplan_main as t3 ON t1.cp35_no = t3.cp_no WHERE t2.csd35_step_fst = '".$step['m35_fst_id']."' AND t1.cp35_current = 1 and t3.cp_owner = '".$owner."'";
			$result = selectSql($sql);
			?>
			<tr>
				<td><?php echo '<b>ขั้นตอนที่ ',$step["m35_fst_id"],'</b> ',$step["m35_fst_name"];?><a/></td>
				<td style="text-align:center;background-color:#E6E6E6 "><a onclick="showStep2M35B(<?php echo $step['step_id'];?>)"><?=$result[0]['cp']?></a></td>
			</tr>
		<?php 
			$cp += $result[0]['cp'];
		} 
		$sql = "SELECT COUNT(t1.cp35_no) AS cp FROM tb_cityplan35 AS t1 JOIN ref_cityplan_step_detail_m35 AS t2 ON t1.cp35_csd = t2.csd35_seq JOIN tb_cityplan_main as t3 ON t1.cp35_no = t3.cp_no WHERE t2.csd35_step_fst = '0' AND t1.cp35_current = 1 and t3.cp_owner = '".$owner."'";
		$undef = selectSql($sql);
		if($undef[0]['cp'] > 0){?>
			<tr style="">
				<td>อื่นๆ</td>
				<td style="text-align:center;background-color:#E6E6E6 "><a onclick="showStep2M35B(0)"><?php echo $undef[0]['cp'];?><a/></td>
			</tr><?php
			$cp += $undef[0]['cp'];
		}?>
		<tr style="background-color:#E6E6E6">
			<td style="text-align:center" ><b>รวม</b></td>
			<td style="text-align:center; background-color:#767676"><?=$cp?></td>
		</tr>
		
	</tbody>
</table>


<script>
	function showStep2M35B(x){
		$.post('report/show_result.php',{
			step_id :x ,
			event :'step2'
		},function(data){
			$('#mainshow').html(data);
		})
	}
	
	function showStep2_opt(x,y){
		$.post('report/show_result.php',{
			step_id :x ,
			own_id :y,
			event :'step2_opt'
		},function(data){
			$('#mainshow').html(data);
		});
	}
</script>