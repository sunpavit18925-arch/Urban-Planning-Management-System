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
	  <?php $sql = "SELECT COUNT(plan_id) FROM tb_plan ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-md-6" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนรายงาน : <?php echo $row['COUNT(plan_id)'];?></h5></div>
	<div class="col-md-6" style="text-align:right;"><button id="addrce" class="btn btn-info" onclick="createPlan();">เพิ่มข้อมูล</button></div>







&nbsp;

<div >
<table id="tablerce2" class="tablex table-bordered " >
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
        $n = 0;
        $sql = "SELECT * FROM tb_plan ";
        $result = selectSql($sql);

        foreach ($result as $row){ 
		$n++;
		?>

    <tr class="trcolor" >

	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);"><?php echo $row['plan_name']; ?></td>
      <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php echo $row['plan_year']; ?></td>
	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php echo $row['plan_start']; ?></td>
	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php echo $row['plan_end']; ?></td>
	  <td onclick="plan_data(<?php echo $row['plan_id'];?>);" style="text-align:center"><?php if($row['plan_note']==""){ echo "-" ;} else { echo $row['plan_note']; } ?></td>
      <td style="text-align:center"> 
	      <span class="plus" onclick="planEqm(<?php echo $row['plan_id'];?>)"> <a id="plus">&nbsp;<i class="fa fa-plus" >  </i>&nbsp;</a></span>
          <span class="gear" onclick="planReadEdit(<?php echo $row['plan_id'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
		  <span class="trash" onclick="planDEL(<?php echo $row['plan_id'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>
		  
	  </td> 
      </tr>
	  
  <?php } ?>
  </tbody>
  </table>
  
  
  
  
</div>
 

   




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

<script type="text/javascript">	
      function planDEL(id){
      if(confirm('คุณต้องการจะลบข้อมูลนี้ ใช่หรือไม่ ?')){
          $.post('apps/index_x(pro).php',{
            event:'d',
            id : id
          },function(data){
            alert(data);
            $('#mainshow').load('apps/manage_plan.php');
          });
        }
      }  
	  
	  function planReadEdit(id) {//อ่านก่อนแก้ไข
		  $.post('apps/index_x(pro).php',{
		  event : 'ru',
		  id: id
		  },function(data) {  		  
		  $('#plan_id').val(data.plan_id);
		  $('#plan_name').val(data.plan_name);
		  $('#plan_year').val(data.plan_year);
		  $('#plan_start').val(data.plan_start);
		  $('#plan_end').val(data.plan_end);
		  $('#plan_note').val(data.plan_note);
		  $('#event').val('u');
		  $('#user_id').val();
		  var pnote = document.getElementsByName("pnote");
			if($('#plan_note').val() == ""){			
				pnote[0].checked = true;
				$("#plan_note").hide();
				$("#plan_note").val('');
			}else if ($('#plan_note').val() != ""){
				pnote[1].checked = true;
				$("#plan_note").show();
				$("#plan_note").val();
			}
		  $('#PlanIT').modal('show');	  
		  },'json');	
  }
  
  	  	function planReadEdit2(id) {//อ่านก่อนแก้ไข
		  $.post('apps/index_x(pro).php',{
		  event:'ru',
		  id: id
		  },function(data) {  
		  $('#plan_id').val(data.plan_id);
		  $('#plan_name').val(data.plan_name);
		  $('#plan_year').val(data.plan_year);
		  $('#plan_start').val(data.plan_start);
		  $('#plan_end').val(data.plan_end);
		  $('#plan_note').val(data.plan_note);
		  $('#event').val('u');
		  $('#user_id').val();
		  var note = document.getElementsByName("note");
			if($('#plan_note').val() == ""){			
				pnote[0].checked = true;
				$("#plan_note").hide();
				$("#plan_note").val('');
			}else if ($('#plan_note').val() != ""){
				pnote[1].checked = true;
				$("#plan_note").show();
				$("#plan_note").val();
			}
		$('#createPlan').modal('show');	  		  
		},'json',);		
  }

  
	function planEqm(id){
		$.post('apps/plan_equipment.php',{
		 id : id
		},function(data){
			//alert(data);
			$('#mainshow').html(data);
			
		});
	}
	
	
	  function dragonball(){
	alert('1231231231');
  }

	
	

</script>