<?php 
include '../js/function_php.php';

//$sql = "SELECT * FROM tb_cityplan WHERE NOT cp_step_1_stop IS NULL AND cp_step_1_stop != ''";

//$sql = "SELECT * FROM ref_history , tb_cityplan , ref_province  WHERE his_cp = cp_no AND cp_province = pvn_id AND his_step = note_step AND his_step_1 = note_step1 AND note_status = 1";
$sql = "SELECT * FROM ref_history , tb_cityplan , ref_province WHERE his_cp = cp_no AND cp_province = pvn_id ORDER BY his_no ASC";

$result = selectSql($sql);
$row = 20;
$all_data = COUNT($result);
$all_table = ceil($all_data/$row);
//echo 'data : '.$all_data.'<br>';
//echo 'table : '.$all_table.'<br>';
?>
<div class="table-responsive" style="border-color:transparent">
<?php
if($all_data>0){
	for($data = 0 , $table = 1 ; $table <= $all_table ; $table++){ ?>
			<table id="table_<?php echo $table;?>" class="table table-bordered" style="margin-bottom:0px">
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<th style="text-align:center">ลำดับ</th>
						<th style="text-align:center">รหัสผัง</th>
						<th style="text-align:center">จังหวัด</th>	
						<th style="text-align:center">ชื่อผังเมือง</th>					
						<th style="text-align:center">ขั้นตอน</th>	
						<th style="text-align:center">เริ่ม</th>	
						<th style="text-align:center">สิ้นสุด</th>
						<th style="text-align:center">หมายเหตุ</th>	
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
						<td style="text-align:center"><?php echo $result[$data]['his_step'].".".$result[$data]['his_step_1'];?></td>
						<td style="text-align:center"><?php if($result[$data]['his_step_start'] != Null OR $result[$data]['his_step_start'] != ""){ echo $result[$data]['his_step_start'];}else{ echo "-";}?></td>
						<td style="text-align:center"><?php if($result[$data]['his_step_stop'] != NULL OR $result[$data]['his_step_stop'] != ""){ echo $result[$data]['his_step_stop'];}else{ echo "-";}?></td>
					<?php if($_POST['type']==1){?>
						<td style="text-align:center">
							<?php 
							$sql = "SELECT COUNT(*) FROM tb_note WHERE note_cp = '".$result[$data]['his_cp']."' AND note_step = '".$result[$data]['his_step']."' AND note_step1 = '".$result[$data]['his_step_1']."' AND note_status != 0";
							$note = selectSql($sql);
							//echo $note[0]['COUNT(*)'];
							if($note[0]['COUNT(*)']>0){ ?>
							<span title="แสดงหมายเหตุ" class="list-alt"><a onclick="Readnote(<?php echo $result[$data]['his_step'];?>,<?php echo $result[$data]['his_step_1'];?>,<?php echo $result[$data]['his_cp'];?>);"><i class="fa fa-list-alt" style="padding-right:8px"></i></a></span>
							<span class="plus"><a onclick="Addnote(<?php echo $result[$data]['his_step'];?>,<?php echo $result[$data]['his_step_1'];?>,<?php echo $result[$data]['his_cp'];?>)"><i class="fa fa-plus"></i></a></span> <?php
							}else{ ?>
							<span class="plus"><a onclick="Addnote(<?php echo $result[$data]['his_step'];?>,<?php echo $result[$data]['his_step_1'];?>,<?php echo $result[$data]['his_cp'];?>)"><i class="fa fa-plus"></i></a></span> <?php 
							} ?>
						</td>
					<?php } ?>
					</tr>
					<?php } ?>
				</tbody>		
			</table>
		<?php
	}
}
else{
	?>
	<h1 class="hidden-xs hidden-sm" style="text-align:center;font-family:Kanit;color:red;">ไม่พบข้อมูล</h1>
	<h2 class="hidden-md hidden-lg" style="text-align:center;font-family:Kanit;color:red;">ไม่พบข้อมูล</h2>
	<script>$('#showpagebutton').hide();</script>
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
	if($('#select_table').val()==all_table){
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