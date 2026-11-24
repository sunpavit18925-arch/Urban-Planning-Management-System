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
	  <?php $sql = "SELECT COUNT(pro_id) FROM tb_project ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-md-6" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนโครงการ : <?php echo $row['COUNT(pro_id)'];?></h5></div>
	<div class="col-md-6" style="text-align:right;"><button class="btn btn-info" onclick="addProject();">เพิ่มข้อมูล</button></div>







&nbsp;


<div  >
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
      <th style="text-align:center" class="col-md-1" scope="col">&nbsp;&nbsp;จัดการ&nbsp;&nbsp;</th>

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

		  <span class="plus" onclick="project_equipment(<?php echo $row['pro_id']; ?>)"> <a id="plus">&nbsp;<i class="fa fa-plus" >  </i>&nbsp;</a></span>
          <span class="gear" onclick="projectReadEdit(<?php echo $row['pro_id'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
		  <span class="trash" onclick="projectDEL(<?php echo $row['pro_id'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>

	  </td> 
      </tr>
  <?php } ?>
  </tbody>
  </table>
 

       <div class="modal fade" id="modal-project">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มข้อมูล</h4>
              </div>
              <div class="modal-body">
				<form>
           <div class="form-group">
             <label for="pro_name">ชื่อโครงการ</label>
             <input type="text" class="form-control" id="pro_name" value="">
           </div>
		   <div class="row">
		   
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="pro_year">ปีโครงการ</label>
					<input type="number" class="form-control" id="pro_year">
				</div>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="pro_budget">งบประมาณ</label>
					<input type="text" class="form-control" id="pro_budget">
				</div>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="pro_contact">เลขสัญญา</label>
					<input type="text" class="form-control" id="pro_contact">
				</div>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="pro_company">บริษัท</label>
					<select class="form-control" id="pro_company">
						<option selected value="0">เลือกบริษัท</option>
						<?php
						$sql = "SELECT * FROM ref_company ";
						$result = selectSql($sql);
						foreach ($result as $row){
						?>
					   <option value="<?php echo $row['company_id']; ?>"><?php echo $row['campany_name']; ?></option>
						<?php } ?>
                 </select>
				</div>
			  </div>
			  
			  <div class="col-xs-12 col-md-12">
				<div class="form-group">
					<label for="pro_price">ราคา</label>
					<input type="text" class="form-control" id="pro_price">
				</div>
			  </div>
			  <div class="col-xs-12 col-md-12">
				<div class="form-group">
					<label for="pro_equipment">รายละเอียดเพิ่มเติม</label>
					<textarea type="text" class="form-control" id="pro_equipment"></textarea>
				</div>
			  </div>

			  

				
                <input type="hidden" name="pro_id" class="form-control" id="pro_id" value="" >
                <input type="hidden" id="event" value="c">
                <input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="projectAddEdit();">บันทึก</button>
				</form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>

<script type="text/javascript">
	function projectoeqm(id){
		$.post('apps/project_equipment.php',{
			 id : id
			 },function(data) {			 
			 $('#mainshow').html(data);
			 });
		 
	}
	
    $(function () {
      $('#mproject').DataTable({
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


	function addProject(){
		$('#pro_id').val('');
		$('#pro_name').val('');
		$('#pro_year').val('');
		$('#pro_budget').val('');
		$('#pro_contact').val('');
		$('#pro_company').val('0');
		$('#pro_price').val('');
		$('#pro_equipment').val('');
		$('#user_id').val('');
		$('#event').val('c');		
		$('#modal-project').modal('show');
	}
	
	function projectAddEdit() {
		if ( $('#pro_name').val()=='' || $('#pro_year').val()=='' || $('#pro_budget').val()=='' || $('#pro_contact').val()=='' || $('#pro_company').val()=='' || $('#pro_price').val()=='' || $('#pro_equipment').val()==''){
				alert('กรุณากรอกข้อมูลให้ครบ');
		}else{
			$.post('apps/manage_project(pro).php',{
			 pro_name:$('#pro_name').val(),
			 pro_year:$('#pro_year').val(),
			 pro_budget:$('#pro_budget').val(),
			 pro_contact:$('#pro_contact').val(),
			 pro_company:$('#pro_company').val(),
			 pro_price:$('#pro_price').val(),
			 pro_equipment:$('#pro_equipment').val(),
			 pro_id:$('#pro_id').val(),
			 user_id:$('#user_id').val(),
			 event:$('#event').val()
			 },function(data) {
			 $('#modal-project').modal('hide');
			 alert(data);
			 $('#mainshow').load('apps/manage_project.php');
			 });
		}
	}
	
	
    function projectDEL(id){
      if(confirm('คุณต้องการจะลบข้อหารือนี้ ใช่หรือไม่ ?')){
          $.post('apps/manage_project(pro).php',{
            event:'d',
            id : id
          },function(data){
            alert(data);
            $('#mainshow').load('apps/manage_project.php');
          });
        }
      }
	  
	  
	function projectReadEdit(id) {//อ่านก่อนแก้ไข
		  $.post('apps/manage_project(pro).php',{
		  event:'ru',
		  id: id
		  },function(data) {
		  $('#pro_id').val(id) ,
		  $('#pro_name').val(data.pro_name);
		  $('#pro_year').val(data.pro_year);
		  $('#pro_budget').val(data.pro_budget);
		  $('#pro_contact').val(data.pro_contact);
		  $('#pro_company').val(data.pro_company);
		  $('#pro_price').val(data.pro_price);
		  $('#pro_equipment').val(data.pro_equipment);
		  $('#event').val('u');
		  $('#user_id').val(data.user_id);
		  $('#modal-project').modal('show');
		},'json');
	}
	
	
	function project_equipment(id){
		$.post('apps/project_equipment.php',{
            id : id
            },function(data){
            //alert(data);
            $('#mainshow').html(data);
        });
	}
	
//$('#equipment_1').attr('checked',true);






</script>