<?php
include '../js/function_db.php';
session_name('tutorial');
session_start();
$_SESSION['intra_czid'];
$sdt = $_POST[sdt];
$sql = "SELECT * FROM tb_equipment WHERE eqm_name LIKE '%$sdt%'";	
?>

<?php 

if($_POST[sdt] == "" OR $_POST[sdt] == '' OR $_POST[sdt] == null OR $_POST[sdt] == NULL){
	echo '<script>goto(1);</script>';
}
elseif($_POST[sdt] == "all" OR $_POST[sdt] == "ALL" OR $_POST[sdt] == "ทั้งหมด" OR $_POST[sdt] == "แสดงทั้งหมด" OR $_POST[std] == "&nbsp;" ){
	$sql = "SELECT * FROM tb_equipment ";
}

?>

	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ผลการค้นหา</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">ผลการค้นหา</h4>
	</div>
	
	
	<?php 
			
		//echo $result[0]['COUNT(eqm_name)'];	
	?>
	<h4> </h4>
	<table id="tableeqm3" class="tablex table-bordered">
		<thead>
			<tr style="background-color: #00a65a;color:white">
				<th style="text-align:center">#</th>
				<th style="text-align:center">ชื่อระบบและอุปกรณ์</th>
				<th style="text-align:center">ราคาในการจัดซื้อ</th>
				<th style="text-align:center">ปีที่จัดซื้อ</th>
				<th style="text-align:center">ปีที่จัดซื้อทดแทน	</th>
				<th style="text-align:center">MA ต่อปี</th>
				<th style="text-align:center">ประกัน(ปี)</th>
				<th style="text-align:center">ประกันสิ้นสุดเมื่อ</th>
			</tr>	
		</thead>
		<tbody style="background-color:white">
		<?php
			$n = 1;
			
			$result = selectSql($sql);
			foreach($result as $row){ ?>
			<tr>
				<td style="text-align:center"><?php echo $n; ?></td>
				<td> <?php echo $row['eqm_name'];?></td>
				<td style="text-align:center"><?php echo  number_format($row['eqm_price']);?></td>
				<td style="text-align:center"><?php echo $row['eqm_buy'];?></td>
				<td style="text-align:center"><?php 
				if($row['eqm_rebuy'] == 9999){ echo "-";}else{ echo $row['eqm_rebuy'];}?></td>
				<td style="text-align:center"><?php echo  number_format($row['eqm_ma']);?></td>
				<td style="text-align:center"><?php echo $row['eqm_guarantee'];?></td>
				<td style="text-align:center"><?php echo '-' ?></td>
			
			
			</tr>	
			<?php $n++; }	?>
		</tbody>
	</table>
	
	
	<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>
<script>
  $(function () {
    $('#tablerce1').DataTable()
    $('#tableeqm3').DataTable({
      'paging'      : true,
      'lengthChange': false,
	  'lengthMenu'  : [[10, 50, 100, -1], [20, 50, 100, "ทั้งหมด"]],
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
	  'processing'  : false,
      'autoWidth'   : false	,
	  'stateSave'  	:false,
	  'stateDuration' : 10
    })
  })
</script>