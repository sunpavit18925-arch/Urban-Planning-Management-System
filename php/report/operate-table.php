<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
$sql = "SELECT opt_fst_name , COUNT(*) FROM tb_cityplan,ref_operate_fst WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = opt_fst_id GROUP BY opt_fst_name";
$value = selectSql($sql);
$row = 0;
/*-----------------------------------------------------------------------------------------------*/
/*											 อปท.  												*/
//สว่น 1
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 1";
	$a1x = selectSql($sql);
	$row+=1;
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 7";
	$a1y = selectSql($sql);
	$row+=1;

//สว่น 2 
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 2";
	$a2x = selectSql($sql);
	$row+=1;
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 8";
	$a2y = selectSql($sql);
	$row+=1;
	
//สว่น 3 
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 3";
	$a3x = selectSql($sql);
	$row+=1;
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 9";
	$a3y = selectSql($sql);
	$row+=1;

//สว่น 4 
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 4";
	$a4x = selectSql($sql);
	$row+=1;
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 10";
	$a4y = selectSql($sql);	
	$row+=1;

//สยผจ. 
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 5";
	$a5x = selectSql($sql);
	$row+=1;

//สยผจ. 
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 6";
	$a6x = selectSql($sql);
	if($a6x[0]['COUNT(*)'] > 0){
		$row+=1;
	}
	
//กรมฯ (ไม่ระบุ)
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 1 AND cp_operator = 0";
	$a0x = selectSql($sql);
	if($a0x[0]['COUNT(*)'] > 0){
		$row+=1;
	}
/*-----------------------------------------------------------------------------------------------*/
/*											 อปท.  												*/
	$rowa = 0;
//สยผจ.
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 2 AND cp_operator = 5";
	$b5x = selectSql($sql);
	if($b5x[0]['COUNT(*)'] >= 0){
		$rowa+=1;
	}
	
//จัดจ้าง
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 2 AND cp_operator = 6";
	$b6x = selectSql($sql);
			$rowa+=1;
	
	
//ไม่ระบุ
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 2 AND cp_operator = 0";
	$b0x = selectSql($sql);
	if($b0x[0]['COUNT(*)'] > 0){
		$rowa+=1;
	}
	
//อปท. ขอกรมฯ ดำเนินการ
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1 AND cp_owner = 2 AND (cp_operator = 1 OR cp_operator = 2 OR cp_operator = 3 OR cp_operator = 4 OR cp_operator = 7 OR cp_operator =  8 OR cp_operator = 9 OR cp_operator = 10)";
	$bax = selectSql($sql);
			$rowa+=1;
		

?>
<table id="opt_data_tb" class='tablex table-bordered' style='width:100%'>
	<thead>
		<tr style='background-color: #295DBD;color:white'>
			<th style="text-align:center">หน่วยงาน</th>
			<th style="text-align:center">ผู้ดำเนินการ</th>						
			<th style="text-align:center">การดำเนินการ</th>				
			<th style="text-align:center">จำนวนผังเมือง</th>	
		</tr>
	</thead>
	<tbody style="background-color:white;color:black">
		<tr style="background-color:white;color:black">
			<td style="text-align:center" rowspan="<?php echo $row;?>">กรมฯ<br><a onclick="showOperate(1);"><?php echo '('.$value[0]['COUNT(*)'].' ผัง)';?></a></td>
				<td style="text-align:center" rowspan="2">ส่วน 1</td>
				<td style="text-align:center">ดำเนินการเอง</td>
				<td style="text-align:center;<?php if($a1x[0]['COUNT(*)']==0){echo 'background-color:gray;';}?>"><a onclick="ShowOperate1_sub(1,1)"><?php if($a1x[0]['COUNT(*)']>0){echo $a1x[0]['COUNT(*)'];}?></a></td>
				<tr style="background-color:white;color:black">
					<td style="text-align:center">จัดจ้าง</td>					
					<td style="text-align:center;<?php if($a1y[0]['COUNT(*)']==0){ echo 'background-color:gray';} ?>"><a onclick="ShowOperate1_sub(1,7)"><?php if($a1y[0]['COUNT(*)']>0){echo $a1y[0]['COUNT(*)'];}?></a></td>
				</tr>
			
			<tr style="background-color:white;color:black">
				<td style="text-align:center" rowspan="2">ส่วน 2</td>
				<td style="text-align:center">ดำเนินการเอง</td>
				<td style="text-align:center;<?php if($a2x[0]['COUNT(*)']==0){echo 'background-color:gray;';}?>"><a onclick="ShowOperate1_sub(1,2)"><?php if($a2x[0]['COUNT(*)']>0){echo $a2x[0]['COUNT(*)'];}?></a></td>
				<tr style="background-color:white;color:black">
					<td style="text-align:center">จัดจ้าง</td>
					<td style="text-align:center;<?php if($a2y[0]['COUNT(*)']==0){ echo 'background-color:gray';} ?>"><a onclick="ShowOperate1_sub(1,8)"><?php if($a2y[0]['COUNT(*)']>0){echo $a2y[0]['COUNT(*)'];}?></a></td>
				</tr>
			</tr>
			
			<tr style="background-color:white;color:black">
				<td style="text-align:center" rowspan="2">ส่วน 3</td>
				<td style="text-align:center">ดำเนินการเอง</td>
				<td style="text-align:center;<?php if($a3x[0]['COUNT(*)']==0){echo 'background-color:gray;';}?>"><a onclick="ShowOperate1_sub(1,3)"><?php if($a3x[0]['COUNT(*)']>0){echo $a3x[0]['COUNT(*)'];}?></a></td>
				<tr style="background-color:white;color:black">
					<td style="text-align:center">จัดจ้าง</td>
					<td style="text-align:center;<?php if($a3y[0]['COUNT(*)']==0){ echo 'background-color:gray';} ?>"><a onclick="ShowOperate1_sub(1,9)"><?php if($a3y[0]['COUNT(*)']>0){echo $a3y[0]['COUNT(*)'];}?></a></td>
				</tr>
			</tr>
			
			<tr style="background-color:white;color:black">
				<td style="text-align:center" rowspan="2">ส่วน 4</td>
				<td style="text-align:center">ดำเนินการเอง</td>
				<td style="text-align:center;<?php if($a4x[0]['COUNT(*)']==0){echo 'background-color:gray;';}?>"><a onclick="ShowOperate1_sub(1,4)"><?php if($a4x[0]['COUNT(*)']>0){echo $a4x[0]['COUNT(*)'];}?></a></td>
				<tr style="background-color:white;color:black">
					<td style="text-align:center">จัดจ้าง</td>
					<td style="text-align:center;<?php if($a4y[0]['COUNT(*)']==0){ echo 'background-color:gray';} ?>"><a onclick="ShowOperate1_sub(1,10)"><?php if($a4y[0]['COUNT(*)']>0){echo $a4y[0]['COUNT(*)'];}?></a></td>
				</tr>
			</tr>
			
			<tr style="background-color:white;color:black">
				<td style="text-align:center" >สยผจ.</td>
				<td style="text-align:center">ดำเนินการเอง</td>
				<td style="text-align:center;<?php if($a5x[0]['COUNT(*)']==0){ echo 'background-color:gray';} ?>"><a onclick="ShowOperate1_sub(1,5)"><?php if($a5x[0]['COUNT(*)']>0){echo $a5x[0]['COUNT(*)'];}?></a></td>
			</tr>
		<?php if($a6x[0]['COUNT(*)']>0 AND $a6x[0]['COUNT(*)']!=""){ ?>
			<tr style="background-color:white;color:black">
				<td style="text-align:center" colspan="2">จัดจ้าง</td>
				<td style="text-align:center;<?php if($a6x[0]['COUNT(*)']==0){ echo 'background-color:gray';} ?>"><a onclick="ShowOperate1_sub(1,6)"><?php if($a6x[0]['COUNT(*)']>0){echo $a6x[0]['COUNT(*)'];}?></a></td>
			</tr>
		<?php }
			if($a0x[0]['COUNT(*)']>0 AND $a0x[0]['COUNT(*)']!=""){	?>
			<tr style="background-color:white;color:black">
				<td style="text-align:center" colspan="2">ไม่ระบุ</td>
				<td style="text-align:center;<?php if($a0x[0]['COUNT(*)']==0){ echo 'background-color:gray';} ?>"><a onclick="ShowOperate1_sub(1,0)"><?php if($a0x[0]['COUNT(*)']>0){echo $a0x[0]['COUNT(*)'];}?></a></td>
			</tr>
		<?php } ?>
		</tr>
		<tr style="background-color:white;color:black">
			<td style="text-align:center" rowspan="<?php echo $rowa;?>">อปท.<br><a onclick="showOperate(2);"><?php echo '('.$value[1]['COUNT(*)'].' ผัง)';?></a></td>
		<?php 
			if(TRUE){
			//if($b5x[0]['COUNT(*)']>0 AND $b5x[0]['COUNT(*)']!=""){	?>
				<td style="text-align:center" >สยผจ.</td>
				<td style="text-align:center">ดำเนินการเอง</td>
				<td style="text-align:center;<?php if($b5x[0]['COUNT(*)']==0){ echo 'background-color:gray';}?>"><a onclick="ShowOperate1_sub(2,5)"><?php if($b5x[0]['COUNT(*)']>0){echo $b5x[0]['COUNT(*)'];}?></a></td>
			<tr style="background-color:white;color:black">
		<?php } ?>
				<td style="text-align:center" colspan="2">จัดจ้าง</td>
				<td style="text-align:center;<?php if($b6x[0]['COUNT(*)']==0){ echo 'background-color:gray';}?>"><a onclick="ShowOperate1_sub(2,6)"><?php if($b6x[0]['COUNT(*)']>0){echo $b6x[0]['COUNT(*)'];}?></a></td>
			</tr>
		<?php if($a0x[0]['COUNT(*)']>0 AND $a0x[0]['COUNT(*)']!=""){	?>	
		<?php } ?>
			<tr style="background-color:white;color:black">
				<td style="text-align:center" colspan="2">ขอกรมฯ ดำเนินการ</td>
				<td style="text-align:center ;<?php if($bax[0]['COUNT(*)']==0){ echo 'background-color:gray';}?>"><?php if($bax[0]['COUNT(*)']>0){echo $bax[0]['COUNT(*)'];}?></td>
			</tr>
			<tr style="background-color:white;color:black">
				<td style="text-align:center" colspan="2">ไม่ระบุ</td>
				<td style="text-align:center ;<?php if($b0x[0]['COUNT(*)']==0){ echo 'background-color:gray';}?>"><a onclick="ShowOperate1_sub(2,0)"><?php if($b0x[0]['COUNT(*)']>0){echo $b0x[0]['COUNT(*)'];}?></a></td>
			</tr>
		</tr>
	
	</tbody>
</table>


<script>
	function showOperate(x){
		$.post('report/show_result.php',{
			opt_id : x ,
			event :'operate'
		},function(data){
			$('#mainshow').html(data);
			$('#div-select').hide();
			$('#div-btn').removeClass("col-xs-5 col-md-6");
			$('#div-btn').addClass("col-xs-12 col-md-12");
		})
	}
	
	function ShowOperate1_sub(y,x){
		$.post('report/show_result.php',{
			own_id : y,
			opt_id : x ,
			event : 'operate1_sub'
		},function(data){
			$('#mainshow').html(data);
			$('#div-select').hide();
			$('#div-btn').removeClass("col-xs-5 col-md-6");
			$('#div-btn').addClass("col-xs-12 col-md-12");
		});
	}
</script>