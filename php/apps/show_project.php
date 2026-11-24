<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">จัดการข้อมูลโครงการ</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">จัดการข้อมูลโครงการ</h4>
	</div>	
	<br>
	  <?php $sql = "SELECT COUNT(pro_id) FROM tb_project ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-12"> <div style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนโครงการ : <?php echo $row['COUNT(pro_id)'];?></h5></div></div>
	<!--<div class="col-md-6" style="text-align:right;"><button class="btn btn-info" onclick="addProject();">เพิ่มข้อมูล</button></div>-->






<div class="col-12">
<table class="tablex table-bordered " id="mproject" style="overflow-x:auto;" >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-5" scope="col">ชื่อโครงการ</th>
      <th style="text-align:center" class="col-md-1" scope="col">ปีโครงการ</th>
	  <th style="text-align:center" class="col-md-1" scope="col">งบประมาณ</th>
	  <th style="text-align:center" class="col-md-1" scope="col">เลขสัญญา</th>
      <th style="text-align:center" class="col-md-2" scope="col">บริษัท</th>
      <th style="text-align:center" class="col-md-1" scope="col">ราคา</th>
      <!--<th style="text-align:center" class="col-1" scope="col">ระบบ/อุปกรณ์</th>-->
      <th style="text-align:center" class="col-md-1" scope="col">&nbsp;&nbsp;เพิ่มเติม&nbsp;&nbsp;</th>

    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $n = 0;
        $sql = "SELECT * FROM tb_project , ref_company WHERE pro_company = company_id ORDER BY pro_id DESC ";
        $result = selectSql($sql);

        foreach ($result as $row){ 
		$n++;
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
      <td style="text-align:center"> 
		  <span class="plus" onclick="uproject(<?php echo $row['pro_id']; ?>)"> <a id="plus">&nbsp;<i class="fa fa-plus" >  </i>&nbsp;</a></span>
	  </td> 
      </tr>
  <?php } ?>
  </tbody>
  </table>
</div>


  <div class="modal fade" id="modal-uproject">
   <div class="modal-dialog" >
     <div class="modal-content" >
       <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
         <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" style="font-family:Kanit;color:black">ข้อมูลโครงการ</h4>
       </div>
       <div class="modal-body">
			<input type="hidden" id="show_pro_id">
			<!--<input type="text" id="show_pro_name4">
			<input type="text" id="show_pro_year">
			<input type="text" id="show_pro_budget">
			<input type="text" id="show_pro_contact">
			<input type="text" id="show_company_name">
			<input type="text" id="show_pro_price">
			<input type="text" id="show_pro_equipment">
			<input type="text" id="show_pro_test">-->


			<div class="col-md-12" id="show_pro_name"></div>
			
			<div class="col-md-6 col-xs-12" id="show_pro_year"></div>


			<div class="col-md-6 col-xs-12" id="show_pro_budget"></div>

			
			<div class="col-md-12 col-xs-12" id="show_company_name"></div>


			<div class="col-md-6 col-xs-12" id="show_pro_contact"></div>


			<div class="col-md-6" id="show_pro_price"></div>
				
			
			<div class="col-md-12" id="show_pro_test">	</div>
			
			
			<div class="col-md-12 br-sql" id="show_pro_equipment"></div>

			
			<br>&nbsp;<br>
			
			
			<div class="container-fluid">
				<button type="button" class="btn btn-success" data-dismiss="modal">ปิด</button>
			</div>
			
			
		 </div>
       </div>
     </div>
	</div>

<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>

<script type="text/javascript">
	
    $(function () {
      $('#mproject').DataTable({
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

	function uproject(id){
		  $.post('apps/index_x(pro).php',{
		  event:'pro_ru',
		  id : id
		  },function(data) {
		  $('#show_pro_id').val(data.pro_id) ;
		  $('#show_pro_name').html(data.pro_name);
		  $('#show_pro_year').html(data.pro_year);
		  $('#show_pro_budget').html(data.pro_budget);
		  $('#show_pro_contact').html(data.pro_contact);
		  $('#show_company_name').html(data.company_name);
		  $('#show_pro_price').html(data.pro_price);
		  $('#show_pro_equipment').html(data.pro_equipment);
		  $('#show_pro_test').html(data.pro_test);
		  $('#modal-uproject').modal('show');
		  },'json');
		  
	}

</script>