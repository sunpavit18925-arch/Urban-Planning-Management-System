<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<table id="CP_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #295DBD;color:white'  rowspan="2">
			<th style="text-align:center" rowspan="2">ขั้นตอน</th>	
			<th style="text-align:center" colspan="2">ผู้ดำเนินการ</th>
			<th style="text-align:center"  rowspan="2">จำนวน<br>ผังเมือง</th>				
		</tr>
		<tr style='background-color: #295DBD;color:white'>
			
			<th style="text-align:center">กรม.</th>
			<th style="text-align:center">อปท.</th>
		</tr>
	</thead>
	<tbody>
		<?php 		
		$sql = "SELECT * FROM ref_up_fst_step WHERE step_id != 0";
		$result = selectSql($sql);
		$sumDpt =0;
		$sumOpt =0;
		foreach($result as $step){ 
			$sql = "SELECT COUNT(*) FROM tb_cityplan , ref_up_fst_step WHERE cp_step = step_id AND cp_step = '".$step['step_id']."' AND cp_step_1 != 0 AND  cp_status = 2 AND cp_stage = 3 AND cp_owner = 1 AND cp_consider = 0";
			$dpt = selectSql($sql);
			
			$sql = "SELECT COUNT(*) FROM tb_cityplan , ref_up_fst_step WHERE cp_step = step_id AND cp_step = '".$step['step_id']."' AND cp_step_1 != 0  AND  cp_status = 2 AND cp_stage = 3 AND cp_owner = 2 AND cp_consider = 0";
			$opt = selectSql($sql);
			
			$count_step =$dpt[0]['COUNT(*)'] + $opt[0]['COUNT(*)'];
			?>
			<tr>
				<td><?php echo '<b>ขั้นตอนที่ ',$step["step_id"],'</b> ',$step["step_name"];?><a/></td>
				<td style="text-align:center"><?php echo $dpt[0]['COUNT(*)'];?></td>
				<td style="text-align:center"><?php echo $opt[0]['COUNT(*)'];?></td>
				<td style="text-align:center;background-color:#E6E6E6 "><a onclick="showStep2(<?php echo $step['step_id'];?>)"><?php echo $count_step;?></a></td>
			</tr>
		<?php 
			$sumDpt += $dpt[0]['COUNT(*)'];
			$sumOpt += $opt[0]['COUNT(*)'];
		} 
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))";
		$undef = selectSql($sql);
		
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0)) AND cp_owner = 1";
		$udpt = selectSql($sql);
		$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0)) AND cp_owner = 2";
		$uopt = selectSql($sql);
		if($undef[0]['COUNT(*)'] > 0){?>
			<tr style="">
				<td>อื่นๆ</td>
				<td style="text-align:center"><?php echo $udpt[0]['COUNT(*)'];?></td>
				<td style="text-align:center"><?php echo $uopt[0]['COUNT(*)'];?></td>
				<td style="text-align:center;background-color:#E6E6E6 "><a onclick="showStep2(0)"><?php echo $undef[0]['COUNT(*)'];?><a/></td>
			</tr><?php
			$sumDpt += $udpt[0]['COUNT(*)'];
			$sumOpt += $uopt[0]['COUNT(*)'];
		}?>
		<tr style="background-color:#E6E6E6">
			<?php 
				$sql = "SELECT COUNT(cp_no) FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3";
				$result = selectSql($sql);
			?>
			<td style="text-align:center" rowspan="2"><b>รวม</b></td>
			<td style="text-align:center"><?php echo $sumDpt;?></td>
			<td style="text-align:center"><?php echo $sumOpt;?></td>
			<td style="text-align:center; background-color:#767676"></td>
		</tr>
		<tr style="background-color:white">
			<td style="text-align:center" colspan="3"><b><?php echo $result[0]['COUNT(cp_no)'];?></b></td>
		</tr>		
	</tbody>
</table>


<script>
	function showStep2(x){
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