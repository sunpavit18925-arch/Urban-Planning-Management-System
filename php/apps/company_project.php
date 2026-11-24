<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    $sql = "SELECT * FROM ref_company WHERE company_id = '".$_POST['id']."' ";
    $result = selectSql($sql);
    foreach ($result as $row){} ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center"><?php echo $row['campany_name']; ?></h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center"><?php echo $row['campany_name']; ?></h4>
	</div>	
	  
	





&nbsp;


<div style="background-color:white">
<table class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="hidden-xs hidden-sm col-md-1" scope="col">ลำดับ</th>
      <th style="text-align:center" class="col-xs-4 col-md-4" scope="col">ชื่อโครงการ</th>
      <th style="text-align:center" class="col-xs-1 col-md-1" scope="col">ปีโครงการ</th>
	  <th style="text-align:center" class="col-xs-1 col-md-1" scope="col">งบประมาณ</th>
	  <th style="text-align:center" class="col-xs-1 col-md-1" scope="col">เลขสัญญา</th>
      <th style="text-align:center" class="col-xs-1 col-md-1" scope="col">ราคา</th>
      <th style="text-align:center" class="col-xs-4 col-md-3" scope="col">ระบบ/อุปกรณ์</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $n = 0;
        $sql = "SELECT * FROM tb_project WHERE pro_company ='".$_POST['id']."'";
        $resulti = selectSql($sql);	
        foreach ($resulti as $row){ 
		$n++;
		?>

    <tr class="trcolor">
      <th class="hidden-xs hidden-sm" style="text-align:center" scope="row"><?php echo $n; ?></th>
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

	  <td style="text-align:center"><?php echo number_format($row['pro_price']); ?></td>     
	  <td style="text-align:center"><?php echo $row['pro_equipment']; ?></td>
      </tr>
  <?php } ?>
  </tbody>
  </table>
