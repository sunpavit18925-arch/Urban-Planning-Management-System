<?php
include '../js/function_db.php';
session_name('tutorial');
session_start();
$_SESSION['intra_czid'];

?>


    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">จัดการข้อมูลบริษัท</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">จัดการข้อมูลบริษัท</h4>
	</div>

<?php
  $C_comp = "SELECT COUNT(company_id)  FROM ref_company";
  $countc = selectSql($C_comp);
  foreach ($countc as $row) {}
?>


  <div class="col-md-6" style="text-align:left;color:black"><h5 style="font-family: Kanit;">จำนวนบริษัท : <?php echo $row['COUNT(company_id)'];?></h5></div>
  <div class="col-md-6" style="text-align:right;color:black"><button class="btn btn-info" onclick="addCompany();">เพิ่มบริษัท</button><br></div>

&nbsp;

<div class="col-xs-12">
<table class="tablex table-bordered " id="mcompany" >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-1" scope="col">ลำดับ</th>
      <th style="text-align:center" class="col-md-5" scope="col">ชื่อบริษัท </th>
	  <th style="text-align:center" class="col-md-3" scope="col">เบอร์ติดต่อ</th>
	  <th style="text-align:center" class="col-md-1" scope="col">จำนวนโครงการ</th>
      <th style="text-align:center" class="col-md-1" scope="col">จัดการ</th>
      
    </tr>
</thead >
<tbody style="background-color:white">
        <?php
		
				
		
        $n = 0;
        $sql = "SELECT *, COUNT(tb_project.pro_company) FROM ref_company LEFT JOIN tb_project on ref_company.company_id = tb_project.pro_company GROUP BY ref_company.company_id ORDER BY ref_company.company_id";
        $result = selectSql($sql);
        foreach ($result as $row) { $n++;	

		//$csql = "SELECT COUNT(pro_company) FROM tb_project WHERE pro_company = company_id"
		?>
    <tr class="trcolor" >
      <th style="text-align:center" scope="row"><?php echo $n; ?></th>
      <td style="text-align:center" onclick="company_project(<?php echo $row['company_id'];?>);"><?php echo $row['campany_name']; ?></td>
	  <td style="text-align:center"><?php echo $row['campany_phone']; ?></td>
	  <td style="text-align:center"><?php echo $row['COUNT(tb_project.pro_company)']; ?></td>


      <td style="text-align:center"> 
          <span class="gear" onclick="companyReadEdit(<?php echo $row['company_id'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
		  <span class="trash" onclick="companyDEL(<?php echo $row['company_id'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>
		  
		 
      </td>
    </tr>
      <?php } ?>
      </tbody>
    </table>
    </div>

    <div class="modal fade" id="modal-company">
   <div class="modal-dialog" >
     <div class="modal-content" >
       <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
         <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" style="font-family:Kanit;color:black">จัดการข้อมูลบริษัท</h4>
       </div>
       <div class="modal-body">
	    
			
         <form>
           <div class="form-group">
             <label for="campany_name"></h6>ชื่อบริษัท</label>
             <input type="text" class="form-control" id="campany_name">
           </div>
		   
		   <div class="form-group">
             <label for="campany_phone"></h6>เบอร์ติดต่อบริษัท</label>
             <input type="text" class="form-control" id="campany_phone">
           </div>
		  
		   <input type="hidden" class="form-control" id="company_id" >
           <input type="hidden" id="event" value="c">
				
           <button type="button" class="btn btn-success pull-right" onclick="companyAddEdit();">บันทึก</button>
           <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
             </div>
           </form>
		 </div>
       </div>
     </div>
	 




<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>

<script type="text/javascript">
  $(function () {
    $('#mcompany').DataTable({
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
  
	function addCompany(){
		 $('#company_id').val('');
		 $('#campany_name').val('');
		 $('#campany_phone').val('');
		$('#modal-company').modal('show');
	}
	
	function companyDEL(id){
      if(confirm('คุณต้องการจะลบข้อหารือนี้ ใช่หรือไม่ ?')){
          $.post('apps/manage_company(pro).php',{
            event:'d',
            id : id
          },function(data){
            alert(data);
            $('#mainshow').load('apps/manage_company.php');
          });
        }
      }
	
	function companyAddEdit(){
		if($('#campany_name').val()==null){
			alert('กรุณาระบุชื่อบริษัท');
		}else{
			$.post('apps/manage_company(pro).php',{
			 campany_name:$('#campany_name').val(),
			 campany_phone:$('#campany_phone').val(),
			 company_id:$('#company_id').val(),
			 event:$('#event').val()			 			
			 },function(data) {
			 $('#modal-company').modal('hide');
			 alert(data);
			 $('#mainshow').load('apps/manage_company.php');
			 });
			
		}
	}
	
	function companyReadEdit21r(id) {//อ่านก่อนแก้ไข
		  $.post('apps/manage_company(pro).php',{
		  event:'ru',
		  company_id: id
		  },function(data) {
		  $('#company_id').val(company_id);
		  $('#campany_name').val(data.campany_name);
		  $('#campany_phone').val(data.campany_phone);
		  $('#event').val('u');
		  $('#user_id').val(data.user_id);
		  $('#modal-company').modal('show');
		},'json');
  }
	
	function companyReadEdit(id) {//อ่านก่อนแก้ไข
		  $.post('apps/manage_company(pro).php',{
		  event:'ru',
		  company_id: id
		  },function(data) {
		  $('#company_id').val(data.company_id);
		  $('#campany_name').val(data.campany_name);
		  $('#campany_phone').val(data.campany_phone);		 
		  $('#event').val('u');		 
		  $('#modal-company').modal('show');
		},'json');
    }
	
	
	function company_project(id){
          $.post('apps/company_project.php',{
            id : id
          },function(data){
            //alert(data);
            $('#mainshow').html(data);
          });
        }
   
	
	
	
	
	
</script>