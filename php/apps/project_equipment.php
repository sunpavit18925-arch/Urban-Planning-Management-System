<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];
	$_SESSION['eid'] = $_POST['id'];

    ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">จัดการข้อมูลโครงการ</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">จัดการข้อมูลโครงการ</h4>
	</div>	
	  <?php $sql = "SELECT COUNT(pro_id) FROM tb_project ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-md-6" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">เพิ่มระบบ/อุปกรณ์</h5></div>
	<div class="col-md-6" style="text-align:right;"><button class="btn btn-warning" onclick="goto(7);">ย้อนกลับ</button></div>







&nbsp;

<div style="overflow-x:auto;">
<table class="tablex table-bordered " id="mproject" >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-5" scope="col">ชื่อโครงการ</th>
      <th style="text-align:center" class="col-md-1" scope="col">ปีโครงการ</th>
	  <th style="text-align:center" class="col-md-1" scope="col">งบประมาณ</th>
	  <th style="text-align:center" class="col-md-1" scope="col">เลขสัญญา</th>
      <th style="text-align:center" class="col-md-1" scope="col">บริษัท</th>
      <th style="text-align:center" class="col-md-1" scope="col">ราคา</th>
      <!--<th style="text-align:center" class="col-1" scope="col">ระบบ/อุปกรณ์</th>-->


    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $n = 0;
        $sql = "SELECT * FROM tb_project , ref_company WHERE pro_company = company_id AND pro_id = '".$_POST['id']."' ";
        $result = selectSql($sql);
        foreach ($result as $row){ 
		?>

    <tr class="trcolor">
	  <td><?php echo $row['pro_name']; ?></td>
      <td style="text-align:center"><?php echo $row['pro_year']; ?></td>
	  <td style="text-align:center">
		  <?php if($row['pro_budget'] == "-"){
			  echo "-";
		  }else{
		  echo number_format($row['pro_budget']);
		  } ?>
	  </td>
	  <td style="text-align:center"><?php echo $row['pro_contact']; ?></td>
	  <td style="text-align:center"><?php echo $row['campany_name']; ?></td>
	  <td style="text-align:center"><?php echo number_format($row['pro_price']); ?></td>     
	  <!--<td style="text-align:center"><?php //echo $row['pro_equipment']; ?></td>-->
 
      </tr>
  <?php } ?>
  </tbody>
  </table>
  </div>
  <div id="loadproeqm">
  
 </div>
 

<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>

<script type="text/javascript">
$('#loadproeqm').load('apps/project_load_equipment.php');





</script>