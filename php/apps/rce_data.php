<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    $sql = "SELECT * FROM ref_reportcheck ,tb_project , ref_company WHERE rce_id = '".$_POST['id']."' AND rce_pro_id = pro_id AND pro_company = company_id";
    $result = selectSql($sql);
    foreach ($result as $row){} ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">แบบรายงานการเข้าตรวจสอบอุปกรณ์</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">แบบรายงานการเข้าตรวจสอบอุปกรณ์</h4>
	</div>	
	<br>
	<div ><h4 style="font-family:Kanit">รายละเอียดสัญญา  </h4></div>
	<div class="col-xs-12">	<b>ชื่อโครงการ&nbsp;&nbsp; : &nbsp;&nbsp;</b> <?php echo $row['pro_name'];?><br ></div>
	<div class="col-xs-6">	<b>สัญญาที่&nbsp;&nbsp;  : &nbsp;&nbsp; </b> <?php echo $row['pro_contact']; ?><br>	</div>
	<div class="col-xs-6">	<b>บริษัท&nbsp;&nbsp; : &nbsp;&nbsp;</b><?php echo $row['campany_name'];?><br>	</div>
	<div class="col-xs-6"> <b>ชื่อผู้ติดต่อ&nbsp;&nbsp; : &nbsp;&nbsp;</b><?php echo $row['rce_contactor'];?><br></div>
	<div class="col-xs-6"> <b>เบอร์โทร&nbsp;&nbsp; : &nbsp;&nbsp;</b><?php echo $row['rce_phone'];?><br></div>
	<div class="col-xs-6">	<b>วันดำเนินการ&nbsp;&nbsp;  :  &nbsp;&nbsp;</b><?php echo $row['rce_date'];?><br> </div>

	
	
	
	<div class="container">
<table class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">

      <th style="text-align:center" rowspan="2" class="col-xs-5" scope="col">รายละเอียดอถุปกรณ์</th>
	  <th style="text-align:center" colspan="2" class="col-xs-2" scope="col">การทำงาน</th>
      <th style="text-align:center" rowspan="2" class="col-xs-2" scope="col">หมายเหตุ</th>
    </tr>
	    <tr style="background-color: #00a65a;color:white">
      <th class="col-xs-1" style="text-align:center">ปกติ</th>
      <th class="col-xs-1" style="text-align:center" >ไม่ปกติ</th>
    </tr>
  </thead>
  <tbody style="background-color:white">
    <tr class="trcolor">
	  <td><?php echo $row['rce_result1']; ?></td>
      <td rowspan="6" style="text-align:center"><?php if($row['rce_working'] == 1) {echo '✔'; }?></td>
	  <td rowspan="6" style="text-align:center"><?php if($row['rce_working'] == 0) {echo '✘'; }?></td>
	  <td rowspan="6" style="text-align:center"><?php if($row['rce_note']== null OR $row['rce_note'] == ''){echo "-";}else{echo $row['rce_note'];} ?></td>
    </tr>
	<?php if($row['rce_result2'] != null){
				echo '<tr><td>',$row['rce_result2'],'</td></tr>';
			}
		  if($row['rce_result3'] != null){
				echo '<tr><td>',$row['rce_result3'],'</td></tr>';
			}
		  if($row['rce_result4'] != null){
				echo '<tr><td>',$row['rce_result4'],'</td></tr>';
			}
		  if($row['rce_result5'] != null){
				echo '<tr><td>',$row['rce_result5'],'</td></tr>';
			}
		  if($row['rce_result6'] != null){
				echo '<tr><td>',$row['rce_result6'],'</td></tr>';
			}
			
			
			
			
			?>

  </tbody>
  </table>
</div>
<div>
	<label>ข้อเสนอแนะ</label>
	

</div>

	





&nbsp;

