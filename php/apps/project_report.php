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
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">โครงการ1</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">โครงการ</h4>
	</div>	
	  <?php $sql = "SELECT COUNT(pro_id) FROM tb_project ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-md-6" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนรายงาน : <?php echo $row['COUNT(pro_id)'];?></h5></div>
	<div class="col-md-6" style="text-align:right;"><button id="addrce" class="btn btn-info" onclick="createPlan();">เพิ่มข้อมูล</button></div>

&nbsp;


<div >
<table id="tablerce2" class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-3" scope="col">ชื่อโครงการ</th>
	  <th style="text-align:center" class="col-md-1" scope="col">งบประมาณ </th>
      <th style="text-align:center" class="col-md-2" scope="col">ผู้ขาย/ผู้รับจ้าง<br>สัญญา</th>
      <th style="text-align:center" class="col-md-1" scope="col">จำนวน <br> (บาท)</th>
	  <th style="text-align:center" class="col-md-2" scope="col">ระบบ/อุปกรณ์</th>
	  <th style="text-align:left" class="col-md-4 " scope="col">+</th>

    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $n = 0;
        $sql = "SELECT * FROM tb_project,ref_company WHERE pro_company = company_id GROUP BY pro_id ORDER BY pro_year ASC";
        $result = selectSql($sql);

        foreach ($result as $row){ 
		$n++;
		?>

    <tr class="trcolor" >

	  <td onclick="plan_data(<?php echo $row['pro_id'];?>);"><?php echo  '<b>[',$row['pro_year'],']</b> ',$row['pro_name']; ?></td>
	  <td onclick="plan_data(<?php echo $row['pro_id'];?>);" style="text-align:center"><?php echo  number_format($row['pro_budget']); ?></td>
	  <td onclick="plan_data(<?php echo $row['pro_id'];?>);" style="text-align:center"><?php echo $row['campany_name'],'<br>',$row['pro_contact']; ?></td>
	  <td onclick="plan_data(<?php echo $row['pro_id'];?>);" style="text-align:center"><?php echo  number_format($row['pro_price']); ?></td>
	  <td onclick="plan_data(<?php echo $pro_id;?>);" style="text-align:left">
	  
		<?php 
			$pro_id = $row['pro_id'];
			$sql = "SELECT pro_test FROM tb_project WHERE pro_id = '$pro_id'";
			$result = selectSql($sql);
			$arramy = explode(',',$result[0]['pro_test']);
			$Carramy = count($arramy);
			
			if($result[0]['pro_test']==null OR $result[0]['pro_test']==""){
				echo "<center>-</center>";
			}
			else{
				for($i=0 ; $i < $Carramy ; $i++ ){
					$sql2 = "SELECT eqm_name FROM tb_equipment WHERE eqm_id = '$arramy[$i]'";
					$result2 = selectSql($sql2);
					echo $i+1,'. ',$result2[0]['eqm_name'],'<br>';
				}
			}  
		?>

	  </td>
	  <td onclick="plan_data(<?php echo $row['pro_id'];?>);" style="text-align:left" class="br-sql"><?php echo $row['pro_equipment']; ?></td>
    </tr>	  
  <?php } ?>
  </tbody>
  </table>
  
  
  
  
</div>