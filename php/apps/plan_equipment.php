<script>
//$('#tableeqm').hide();
//$('#tableeqm').show(1000);

</script>
<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];
	$_SESSION['eid'] = $_POST['id'];

?>


    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">แผนการบำรุงรักษา</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">แผนการบำรุงรักษา</h4>
	</div>	
	  <?php $sql = "SELECT COUNT(plan_id) FROM tb_plan ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-md-6" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">เพิ่มอุปกรณ์ในแผนการบำรุงรักษา</h5></div>
	<div class="col-md-6" style="text-align:right;"><button  class="btn btn-warning" onclick="goto(9);">ย้อนกลับ</button></div>



&nbsp;
<?php
$sql = "SELECT * FROM tb_plan WHERE plan_id = '".$_SESSION['eid']."' ";
        $result = selectSql($sql);?>
<table id="tableeqm2" class="tablex table-bordered "  >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-5" scope="col">ชื่อแผนการบำรุงรักษา</th>
      <th style="text-align:center" class="col-md-1" scope="col">จำนวนปี </th>
	  <th style="text-align:center" class="col-md-2" scope="col">ปีเริ่มต้น </th>
	  <th style="text-align:center" class="col-md-2" scope="col">ปีสิ้นสุด </th>
      <th style="text-align:center" class="col-md-1" scope="col">หมายเหตุ</th>
      <th style="text-align:center" class="col-md-1" scope="col">จัดการ</th>

    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        
		
        
        foreach ($result as $row){ 
		
		?>

    <tr class="trcolor" >

	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);"><?php echo $row['plan_name']; ?></td>
      <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php echo $row['plan_year']; ?></td>
	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php echo $row['plan_start']; ?></td>
	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php echo $row['plan_end']; ?></td>
	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php if($row['plan_note']==""){ echo "-" ;} else { echo $row['plan_note']; } ?></td>
      <td style="text-align:center"> 
		  <span class="desktop" onclick="plan(<?php echo $_SESSION['eid']; ?>)"> <a id="plus">&nbsp;<i class="fa fa-desktop" >  </i>&nbsp;</a></span>
	      <span class="plus" onclick="goto(9)"> <a id="plus">&nbsp;<i class="fa fa-times" >  </i>&nbsp;</a></span>
          <span class="gear" onclick="planReadEdit(<?php echo $row['plan_id'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
		  
	  </td> 
      </tr>
	  
  <?php } ?>
  </tbody>
  </table>
   
   
   
<div id="loadpeqm">

  </div>
  
  
 <script>

	$('#loadpeqm').load('apps/plan_load_eqm.php'); 
	
	function plan(x){
		
		$.post('apps/plan_show2.php',{       
			id : x  
        },function(data) {          
			$('#mainshow').html(data);
        });	
	}

 
 
 </script>
 