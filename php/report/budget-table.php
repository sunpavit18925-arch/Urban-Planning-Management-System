<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();

	$sql = "SELECT cp_budget , 
			count(*) AS total, 
			count(case cp_stage when 0 then 1 else null end) AS stage_0, 
			count(case cp_stage when 1 then 1 else null end) AS stage_1, 
			count(case cp_stage when 2 then 1 else null end) AS stage_2, 
			count(case cp_stage when 3 then 1 else null end) AS stage_3,
			count(case cp_stage when 4 then 1 else null end) AS stage_4 
			FROM tb_cityplan 
			GROUP BY cp_budget DESC
		";
	/*
	$sql = "SELECT MIN(cp_budget) AS min_year , MAX(cp_budget) AS max_year, 
			count(*) AS total, 
			count(case cp_stage when 0 then 1 else null end) AS stage_0, 
			count(case cp_stage when 1 then 1 else null end) AS stage_1, 
			count(case cp_stage when 2 then 1 else null end) AS stage_2, 
			count(case cp_stage when 3 then 1 else null end) AS stage_3, 
			count(case cp_stage when 4 then 1 else null end) AS stage_4 
			FROM tb_cityplan 
			GROUP BY floor(cp_budget/10) DESC
		";
			*/
	$result = selectSql($sql);
	
		$sumT = 0;
		$sum0 = 0;
		$sum1 = 0;
		$sum3 = 0;
		$sum2 = 0;
		$sum4 = 0;

?>
<table id="opt_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #295DBD;color:white'>
			<th style="text-align:center" rowspan="2">ปีงบประมาณ</th>
			<th style="text-align:center" rowspan="2">รวม</th>						
			<th style="text-align:center" rowspan="2">พื้นที่<br>เปิดใหม่</th>				
			<th style="text-align:center" rowspan="2">ปรับปรุง</th>	
			<th style="text-align:center" colspan="2">ประเมิน</th>
			<th style="text-align:center" rowspan="2">ไม่ระบุ</th>
		</tr>
		<tr style='background-color: #295DBD;color:white'>
			<th style="text-align:center">5 ปี</th>
			<th style="text-align:center">10 ปี</th>
		</tr>
	</thead>
	<tbody style="background-color:white;color:black">
		<?php foreach($result as $row){ ?>
		<tr>		
		<td style="text-align:center"><?php if($row['cp_budget']=='-'){echo "ไม่ระบุปีงบประมาณ";}else{/*echo $row['min_year'].' - '.$row['max_year'];*/echo $row['cp_budget'];}?></td>
			<td style="text-align:center"><?php echo  $row['total'];?></td>
			<td style="text-align:center"><?php echo  $row['stage_1'];?></td>
			<td style="text-align:center"><?php echo  $row['stage_3'];?></td>
			<td style="text-align:center"><?php echo  $row['stage_2'];?></td>
			<td style="text-align:center"><?php echo  $row['stage_4'];?></td>
			<td style="text-align:center"><?php echo  $row['stage_0'];?></td>
		</tr>
		<?php 
		
		$sumT += $row['total'];
		$sum0 += $row['stage_0'];
		$sum1 += $row['stage_1'];
		$sum3 += $row['stage_3'];
		$sum2 += $row['stage_2'];
		$sum4 += $row['stage_4'];
		
		
		
		} ?> 
		<tr> 
			<td style="text-align:center"><?php echo 'รวม';?></td>
			<td style="text-align:center"><?php echo $sumT;?></td>
			<td style="text-align:center"><?php echo $sum1;?></td>
			<td style="text-align:center"><?php echo $sum3;?></td>
			<td style="text-align:center"><?php echo $sum2;?></td>
			<td style="text-align:center"><?php echo $sum4;?></td>
			<td style="text-align:center"><?php echo $sum0;?></td>
		
		</tr>
	
	</tbody>
</table>


<script>
/*
	function showOperate2(x){
		$.post('report/show_result.php',{
			opt_id : x ,
			event :'operate2'
		},function(data){
			$('#mainshow').html(data);
			$('#div-select').hide();
			$('#div-btn').removeClass("col-xs-5 col-md-6");
			$('#div-btn').addClass("col-xs-12 col-md-12");
		})
	}
	
	function ShowOperate2_sub(y,x){
		$.post('report/show_result.php',{
			own_id : y,
			opt_id : x ,
			event : 'operate2_sub'
		},function(data){
			$('#mainshow').html(data);
			$('#div-select').hide();
			$('#div-btn').removeClass("col-xs-5 col-md-6");
			$('#div-btn').addClass("col-xs-12 col-md-12");
		});
	}*/
</script>