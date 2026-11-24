<?php 
include '../js/function_db.php';
	$sql = "SELECT * FROM ref_myhead WHERE myhead_status = 1";
	$order_by = " ORDER BY myhead_step ASC";

if(isset($_POST['search_select']) AND $_POST['search_select']!=""){
	$sql = $sql." AND myhead_step = '".$_POST['search_select']."'";
}
if(isset($_POST['search_keyword']) AND  $_POST['search_keyword']!="" AND $_POST['search_keyword']!=" "){
	$sql = $sql." AND myhead_name LIKE '%".$_POST['search_keyword']."%'";
}
$sql = $sql.$order_by;
//echo $sql;
$result = selectSql($sql);
$data = count($result);
$row = 20;
if($data>0){ ?>
	<table hidden class="table table-bordereds printx" style="margin-top:8px">
		<thead>
			<tr style='background-color: #295DBD;color:white'>
				<th style='text-align:center'> ลำดับ </th>
				<th style='text-align:center'> ชื่อรายการหมวดหมู่ </th>
				<th style='text-align:center;min-width:60px'> ขั้นตอน </th>
						
			</tr>
		</thead>
		<tbody> <?php
			$z = 0;
			foreach($result as $bow){ 
				$z++;?>
				<tr>
					<td style="text-align:center"><?php echo $z;?></td>
					<td style="text-align:left"><?php echo $bow['myhead_name'];?></td>
					<td style="text-align:center"><?php if($bow['myhead_step']=="all"){ echo "ใช้กับทุกขั้นตอน";}else{ echo $bow['myhead_step'];}?></td>
					
				</tr><?php
			} ?>
		</tbody>
	</table>
	
	<div class="table-responsive unprintable" style="border-color:transparent;margin-top:8px"> <?php
		for($t=1,$i=0 ; $t<=ceil($data/$row) ; $t++){ ?>
			<table id="table_<?php echo $t;?>" class="table table-bordered">
				<thead>
					<tr style='background-color: #295DBD;color:white'>
						<th style='text-align:center'> ลำดับ </th>
						<th style='text-align:center'> ชื่อรายการหมวดหมู่ </th>
						<th style='text-align:center;min-width:60px'> ขั้นตอน </th>
						<th hidden style='text-align:center'> เจ้าของ </th>
						<th style='text-align:center'> จัดการ </th>
					</tr>
				</thead>
				<tbody> <?php
					for($i,$arow = 1 ; $arow<=$row ; $arow++,$i++){ 
						if($i == $data){break; } ?>
						<tr>
							<td style="text-align:center"><?php echo $i+1;?></td>
							<td style="text-align:left"><?php echo $result[$i]['myhead_name'];?></td>
							<td style="text-align:center"><?php if($result[$i]['myhead_step']=="all"){ echo "-";}else{ echo $result[$i]['myhead_step'];}?></td>
							<td style="text-align:center">
								<span style="display:flex;justify-content:center">
								<span class="gear" onclick="editMyhead(<?php echo  $result[$i]['myhead_id'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
								<span class="trash" onclick="delMyhead(<?php echo $result[$i]['myhead_id'];?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>
								</span>
							</td>
						</tr><?php
					} ?>
				</tbody>
			</table> 
		<?php } ?>
	</div>
	
 <?php
 pagin($row,$data);	
}else{
	?><h1 style="font-family:Kanit;text-align:center">ไม่มีข้อมูล</h1><?php 
}
$print_search = '';
	if(isset($_POST['search_select']) AND $_POST['search_select']!='' AND $_POST['search_select']!=' '){
		if($_POST['search_select']=='all'){
			$_POST['search_select'] = "รายการที่ใช้กับทุกขั้นตอน";
		}
		elseif($_POST['search_select']>='0' OR $_POST['search_select']<='8'){
			$_POST['search_select'] = "รายการที่ใช้กับขั้นตอนที่ ".$_POST['search_select'];
		}
	}
	if($_POST['search_select']!="" AND $_POST['search_keyword']!=""){
		$print_search = '('.$_POST['search_select'].' , ข้อความที่ค้นหา '.$_POST['search_keyword'].')';	
	}elseif($_POST['search_select']!=""){
		$print_search = '('.$_POST['search_select'].')';
	}elseif($_POST['search_keyword']!=""){
		$print_search = '(ข้อความที่ค้นหา '.$_POST['search_keyword'].')';
	}else{
		$print_search = '';
	}
?>
<script>
$('#myhead_value').html('<?php echo $data;?>');
$('#print_search').html('<?php echo $print_search?>');

</script>