<script> 
$("#rce_note").hide();
$("#rce_suggestion").hide();
$(document).ready(function(){
  $("#note0").click(function(){
	$("#rce_note").hide();
	$("#rce_note").val('');
  });
  
  $("#note1").click(function(){
	$("#rce_note").show();
  });
  
  $("#suggest0").click(function(){
	$("#rce_suggestion").hide();
	$("#rce_suggestion").val('');
  });
  
  $("#suggest1").click(function(){
	$("#rce_suggestion").show();
  });
  

  

});
</script>
<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">รายงานการเข้าตรวจสอบอุปกรณ์</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">รายงานการเข้าตรวจสอบอุปกรณ์</h4>
	</div>
<br>	
	  <?php $sql = "SELECT COUNT(rce_id) FROM ref_reportcheck ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-12"> 
		<div style="font-family: Kanit; text-align:left;">
			<h5 style="font-family: Kanit;">จำนวนรายงาน : <?php echo $row['COUNT(rce_id)'];?></h5>
		</div>
	</div>









<div >
<table id="tablerce2" class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-5" scope="col">ชื่อโครงการ</th>
      <th style="text-align:center" class="col-md-1" scope="col">เลขสัญญา</th>
	  <th style="text-align:center" class="col-md-2" scope="col">บริษัท</th>
	  <th style="text-align:center" class="col-md-2" scope="col">ผู้ติดต่อ</th>
      <th style="text-align:center" class="col-md-1" scope="col">เบอร์โทร</th>
      <th style="text-align:center" class="col-md-1" scope="col">เพิ่มเติม</th>

    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $n = 0;
        $sql = "SELECT * FROM ref_reportcheck , tb_project , ref_company WHERE rce_pro_id = pro_id AND pro_company = company_id ORDER BY rce_id ASC ";
        $result = selectSql($sql);

        foreach ($result as $row){ 
		$n++;
		?>

    <tr class="trcolor" >

	  <td onclick="rce_data(<?php echo $row['rce_id'];?>);"><?php echo $row['pro_name']; ?></td>
      <td onclick="rce_data(<?php echo $row['rce_id'];?>);" style="text-align:center"><?php echo $row['pro_contact']; ?></td>
	  <td onclick="rce_data(<?php echo $row['rce_id'];?>);" style="text-align:center"><?php echo $row['campany_name']; ?></td>
	  <td onclick="rce_data(<?php echo $row['rce_id'];?>);" style="text-align:center"><?php echo $row['rce_contactor']; ?></td>
	  <td onclick="rce_data(<?php echo $row['rce_id'];?>);" style="text-align:center"><?php echo $row['rce_phone']; ?></td>
      <td style="text-align:center"> 
          -
		  
	  </td> 
      </tr>
  <?php } ?>
  </tbody>
  </table>

<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>
<script>
  $(function () {
    $('#tablerce1').DataTable()
    $('#tablerce2').DataTable({
      'paging'      : true,
      'lengthChange': false,
	  'lengthMenu'  : [[20, 50, 100, -1], [20, 50, 100, "ทั้งหมด"]],
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
	  'processing'  : true,
      'autoWidth'   : false	  
    })
  })
</script>
