<style> 

	
</style>
<script>
  

</script>

<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">แผนการบำรุงรักษา</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">แผนการบำรุงรักษา</h4>
	</div>	
	<br>
	  <?php $sql = "SELECT COUNT(plan_id) FROM tb_plan ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-12" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนรายงาน : <?php echo $row['COUNT(plan_id)'];?></h5></div>


<div class="col-12">
<?php
	$sql = "SELECT * FROM tb_plan ";
	$result = selectSql($sql);
	$plan_row = count($result);
	//echo $plan_row;
	echo "<table id='tableshowplan2' class='tablex table-bordered'>";
	echo "<thead>";
	echo "<tr style='background-color: #00a65a;color:white'>";
	echo "<td style='text-align:center'><b>ชื่อแผนบำรุงรักษา</b></td>";
	echo "<td style='text-align:center'><b>จำนวนปี</b></td>";
	echo "<td style='text-align:center'><b>ระยะเวลา</b></td>";
	echo "<td style='text-align:center'><b>หมายเหตุ</b></td>";
	echo "<td style='text-align:center'><b>เพิ่มเติม</b></td>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody style='background-color:white'>";
	


	for($i = 0 ; $i < $plan_row ; $i++){
		echo "<tr class='trcolor'>";
		echo "<td style='text-align:left'>",$result[$i]['plan_id'],' . ',$result[$i]['plan_name'],"</td>";
		echo "<td style='text-align:center'>",$result[$i]['plan_year'],"</td>";
		echo "<td style='text-align:center'>",$result[$i]['plan_start'],' - ',$result[$i]['plan_end'],"</td>";
		echo "<td style='text-align:center'>";
		if($result[$i]['plan_note'] == ""){
			echo '-';
		}
		echo "</td>";
		echo "<td style='text-align:center'>";
			?><span class="desktop" onclick="plan(<?php echo $result[$i]['plan_id']; ?>)"> <a id="plus">&nbsp;<i class="fa fa-desktop" >  </i>&nbsp;</a></span> <?php
		echo "</td>";
		echo "</tr>";
		
		
	}
	echo "</tbody>";
	echo "<table>";
	
?>
  
  
  
  
</div>
 
 

   




<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>
<script>
  $(function () {
    $('#tableshowplan1').DataTable()
    $('#tableshowplan2').DataTable({
      'paging'      : true,
      'lengthChange': false,
	  'lengthMenu'  : [[10, 50, 100, -1], [20, 50, 100, "ทั้งหมด"]],
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
	  'processing'  : true,
      'autoWidth'   : false	  
    })
  })
</script>

<script type="text/javascript">	

	function plan(x){
		
		$.post('apps/plan_show(user).php',{       
			id : x  
        },function(data) {          
			$('#mainshow').html(data);
        });	
	}

	

</script>