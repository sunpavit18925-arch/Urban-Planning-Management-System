<?php 
include '../js/function_php.php';

//$sql = "SELECT * FROM tb_cityplan WHERE NOT cp_step_1_stop IS NULL AND cp_step_1_stop != ''";
if($_POST['type']==1){
	$sql = "SELECT * FROM tb_cityplan , ref_province WHERE cp_province = pvn_id AND NOT cp_step_1_stop IS NULL AND cp_step_1_stop != ''";
}
elseif($_POST['type']==2){
	$sql = "SELECT * FROM tb_cityplan ,ref_province WHERE cp_province = pvn_id AND (cp_step_1_stop IS NULL OR cp_step_1_stop = '') AND cp_step != 0 AND cp_step_1 != 0";
}
elseif($_POST['type']==3){
	$sql = "SELECT * FROM tb_cityplan ,ref_province WHERE cp_province = pvn_id AND cp_step != 0 AND cp_step_1 = 0";
}
elseif($_POST['type']==4){
	$sql = "SELECT * FROM tb_cityplan ,ref_province WHERE cp_province = pvn_id AND cp_step = 0 AND cp_step_1 = 0";
}

$result = selectSql($sql);
$row = 20;
$all_data = COUNT($result);
$all_table = ceil($all_data/$row);
//echo 'data : '.$all_data.'<br>';
//echo 'table : '.$all_table.'<br>';
?>
<div class="table-responsive">
<?php
for($data = 0 , $table = 1 ; $table <= $all_table ; $table++){ ?>
		<table id="table_<?php echo $table;?>" class="table table-bordered" style="margin-bottom:0px">
			<thead>
				<tr style="background-color: #0AAAFA;color:white">
					<th style="text-align:center">ลำดับ</th>
					<th style="text-align:center">รหัสผัง</th>
					<th style="text-align:center">จังหวัด</th>	
					<th style="text-align:center">ชื่อผังเมือง</th>					
				<?php if($_POST['type']==2){?>
					<th style="text-align:center">ปรับปรุงข้อมูล</th>
				<?php }?>
				</tr>
			</thead>
			<tbody>
				<?php for($data , $i = 1 ; $i <= $row ; $data++ , $i++){ 
					if($data == $all_data){
						break;
					}
				?>
				<tr>
					<td style="text-align:center"><?php echo $data+1;?></td>
					<td style="text-align:center"><?php echo $result[$data]['cp_no'];?></td>
					<td style="text-align:center"><?php echo $result[$data]['pvn_name'];?></td>
					<td style="text-align:center"><?php echo $result[$data]['cp_name'];?></td>
				<?php if($_POST['type']==2){?>
					<td style="text-align:center">
						<span class="plus"><a onclick="cpEdit(<?php echo $result[$data]['cp_no'];?>)"><i class="fa fa-plus"></i></a></span>
					</td>
				<?php } ?>
				</tr>
				<?php } ?>
			</tbody>		
		</table>
	<?php
}
?>
</div>

<script>
$('#count_data').html('<?php echo $all_data;?>');
$('#select_table').html('<?php for($option = 1 ; $option <= $all_table ; $option++){ ?> <option value="<?php echo $option;?>"><?php echo $option;?></option><?php } ?>');
table_change();
function table_change(){
	var all_table = '<?php echo $all_table; ?>';	
	$('#btn_first').attr('onclick','table_con("first")');
	$('#btn_first').removeClass('disabled');
	$('#btn_back').attr('onclick','table_con("back")');
	$('#btn_back').removeClass('disabled');
	$('#btn_next').attr('onclick','table_con("next")');
	$('#btn_next').removeClass('disabled');
	$('#btn_last').attr('onclick','table_con("last")');
	$('#btn_last').removeClass('disabled');
	if($('#select_table').val()==1){
		$('#btn_first').removeAttr("onclick");
		$('#btn_first').addClass('disabled');
		$('#btn_back').removeAttr("onclick");
		$('#btn_back').addClass('disabled');
	}
	else if($('#select_table').val()==all_table){
		$('#btn_last').removeAttr("onclick");
		$('#btn_last').addClass('disabled');
		$('#btn_next').removeAttr("onclick");
		$('#btn_next').addClass('disabled');		
	}
	for(let t=1 ; t <= all_table ; t++){
		$('#table_'+t).hide();
	}
	$('#table_'+$('#select_table').val()).show();
}


function table_con(x){
	if(x=='first' || x=='last'){
		if(x=='first'){
			$('#select_table').val(1);
		}else{
			$('#select_table').val('<?php echo $all_table;?>');
		}
	}else{
		if(x=='back'){
			$('#select_table').val($('#select_table').val()-1);
		}else{
			$('#select_table').val(parseInt($('#select_table').val())+1)
		}
	}
	table_change();
}
</script>