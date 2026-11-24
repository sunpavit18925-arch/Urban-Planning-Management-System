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
	  <?php $sql = "SELECT COUNT(rce_id) FROM ref_reportcheck ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-md-6" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนรายงาน : <?php echo $row['COUNT(rce_id)'];?></h5></div>
	<div class="col-md-6" style="text-align:right;"><button id="addrce" class="btn btn-info" onclick="addRce();">เพิ่มข้อมูล</button></div>







&nbsp;


<div >
<table id="tablerce2" class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-5" scope="col">ชื่อโครงการ</th>
      <th style="text-align:center" class="col-md-1" scope="col">เลขสัญญา</th>
	  <th style="text-align:center" class="col-md-2" scope="col">บริษัท</th>
	  <th style="text-align:center" class="col-md-2" scope="col">ผู้ติดต่อ</th>
      <th style="text-align:center" class="col-md-1" scope="col">เบอร์โทร</th>
      <th style="text-align:center" class="col-md-1" scope="col">จัดการ</th>

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
          <span class="gear" onclick="rceReadEdit(<?php echo $row['rce_id'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
		  <span class="trash" onclick="rceDEL(<?php echo $row['rce_id'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>
		  
	  </td> 
      </tr>
  <?php } ?>
  </tbody>
  </table>
 

       <div class="modal fade" id="modal-rce">
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
                 <label for="rce_pro_id">ชื่อโครงการ</label>
                 <select class="form-control" onchange="proption();" id="rce_pro_id">
                    <option selected value="0">เลือกโครงการ</option>
                    <?php
                    $sql = "SELECT pro_id , pro_name FROM tb_project ";
                    $result = selectSql($sql);
                    foreach ($result as $row){
                    ?>
                   <option value="<?php echo $row['pro_id']; ?>"><?php echo $row['pro_name']; ?></option>
                    <?php } ?>
                 </select>
               </div>
		   <div class="row">
		   
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="rce_contact">เลขสัญญา</label> 
					<input readonly type="text" class="form-control" id="rce_contact">					
				</div>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="rce_company">บริษัท</label>
					<input readonly type="text" class="form-control" id="rce_company">
				</div>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="rce_contactor">ชื่อผู้ติดต่อ</label>
					<input type="text" class="form-control" id="rce_contactor" placeholder="ชื่อ-นามสกุล">
				</div>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="rce_phone">เบอร์โทร</label>
					<input type="text" class="form-control" id="rce_phone" placeholder="">
				</div>
			  </div>
			  
			  <div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="rce_date">วันที่ดำเนินการ* </label>

					<input type="text" class="form-control" name="rce_date" id="rce_date" value="" placeholder="ปี-เดือน-วัน" >
					
				</div>
			  </div>
			  
			  <div class="col-xs-12 col-md-6">
			    <div class="form-group">
					 <label for="rce_pro_id">การทำงาน</label>
					 <select class="form-control"  id="rce_working">
						<option selected value="">-</option>
						<option value="0">ไม่ปกติ</option>
						<option value="1">ปกติ</option>
						
					 </select>
                </div>			  
			   </div>
			  
			  <div class="col-xs-12 col-md-12">
				<div class="form-group">
					<label for="rce_result1">ระบบอุปกรณ์</label>
					<input type="text" class="form-control" id="rce_result1">
				</div>
			  </div>
			  
			  <div class="col-xs-12 col-md-12">
				<div class="form-group">
					<label for="rce_note">หมายเหตุ : &nbsp;&nbsp;</label>					
					<input checked type="radio" id="note0" name="note" value="0">&nbsp;
					<label for="note0">ไม่มี</label>&nbsp;&nbsp;
					<input type="radio" id="note1" name="note" value="1">&nbsp;
					<label for="note1">มี </label>&nbsp;&nbsp;
					<textarea type="text" class="form-control" id="rce_note"></textarea>
				</div>
			  </div>

			  <div class="col-xs-12 col-md-12">
				<div class="form-group">
					<label for="rce_suggestion">ข้อเสนอแนะ : &nbsp;&nbsp;</label>
					<input checked type="radio" id="suggest0" name="suggest" value="0">&nbsp;
					<label  for="suggest0">ไม่มี</label>&nbsp;&nbsp;
					<input type="radio" id="suggest1" name="suggest" value="1">&nbsp;
					<label for="suggest1">มี </label>&nbsp;&nbsp;
					<textarea type="text" class="form-control" id="rce_suggestion"></textarea>
				</div>
			  </div>
			  

				
                <input type="hidden" name="rce_id" class="form-control" id="rce_id" value="" >
                <input type="hidden" id="event" value="c">
                <input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="rceAddEdit();">บันทึก</button>
				</form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
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

	function addRce(){
		$('#rce_pro_id').val('');
		$('#rce_contact').val('');
		$('#rce_company').val('');
		$('#rce_contactor').val('Goku');
		$('#rce_phone').val('0892336349');
		$('#rce_date').val('2566-06-21');	
		$('#rce_result1').val('-');
		$('#rce_result2').val('');
		$('#rce_result3').val('');
		$('#rce_result4').val('');
		$('#rce_result5').val('');
		$('#rce_result6').val('');
		$('#rce_working').val('1');
		$('#rce_id').val('');
		$('#user_id').val();
		$('#event').val('c');		
		$('#modal-rce').modal('show');
		
		var note = document.getElementsByName("note");
			note[0].checked = true;
			$("#rce_note").hide();
			$("#rce_note").val('');
		var suggest = document.getElementsByName("suggest");
			suggest[0].checked = true;
			$("#rce_suggestion").hide();
			$("#rce_suggestion").val('');
				
		}
	
	function rceAddEdit() {
		if ( $('#rce_pro_id').val()==''){
				alert('กรุณาเลือกโครงการ');
		}else{
			$.post('apps/manage_rce(pro).php',{
			 rce_pro_id:$('#rce_pro_id').val(),
			 rce_contactor:$('#rce_contactor').val(),
			 rce_phone:$('#rce_phone').val(),
			 rce_date:$('#rce_date').val(),
			 rce_result1:$('#rce_result1').val(),
			 rce_working:$('#rce_working').val(),
			 rce_note:$('#rce_note').val(),
			 rce_suggestion:$('#rce_suggestion').val(),
			 rce_id:$('#rce_id').val(),
			 user_id:$('#user_id').val(),
			 event:$('#event').val()
			 },function(data) {
			 $('#modal-rce').modal('hide');
			 alert(data);
			 $('#mainshow').load('apps/manage_rce.php');
			 });
		}
	}
	
	
      function rceDEL(id){
      if(confirm('คุณต้องการจะลบข้อมูลนี้ ใช่หรือไม่ ?')){
          $.post('apps/manage_rce(pro).php',{
            event:'d',
            id : id
          },function(data){
            alert(data);
            $('#mainshow').load('apps/manage_rce.php');
          });
        }
      }
	  
	  
	  	function rceReadEdit(id) {//อ่านก่อนแก้ไข
		  $.post('apps/manage_rce(pro).php',{
		  event:'ru',
		  id: id
		  },function(data) {  
		  $('#rce_pro_id').val(data.rce_pro_id);
		  $('#rce_contactor').val(data.rce_contactor);
		  $('#rce_phone').val(data.rce_phone);
		  $('#rce_date').val(data.rce_date);
		  $('#rce_working').val(data.rce_working);
		  $('#rce_result1').val(data.rce_result1);
		  $('#rce_note').val(data.rce_note);
		  $('#rce_suggestion').val(data.rce_suggestion);
		  $('#rce_id').val(data.rce_id);
		  $('#rce_contact').val(data.rce_contact);
		  $('#rce_company').val(data.rce_company);
		  $('#event').val('u');
		  $('#user_id').val();

			var note = document.getElementsByName("note");
			if($('#rce_note').val() == ""){			
				note[0].checked = true;
				$("#rce_note").hide();
				$("#rce_note").val('');
			}else if ($('#rce_note').val() != ""){
				note[1].checked = true;
				$("#rce_note").show();
				$("#rce_note").val();
			}
			
			var suggest = document.getElementsByName("suggest");
			if($('#rce_suggestion').val() == ""){			
				suggest[0].checked = true;
				$("#rce_suggestion").hide();
				$("#rce_suggestion").val('');
			}else if ($('#rce_suggestion').val() != ""){
				suggest[1].checked = true;
				$("#rce_suggestion").show();
				$("#rce_suggestion").val();
			}
		
		$('#modal-rce').modal('show');	  		  
		},'json',);
		
  }

  
  
	  	function rceReadEdit2(id) {//อ่านก่อนแก้ไข
		  $.post('apps/manage_rce(pro).php',{
		  event:'ru',
		  id: id
		  },function(data) {
		  alert(data);
			 
		  $('#modal-rce').modal('show');		  
		});
  }
	  
	  
	    function proption() {//อ่านก่อนแก้ไข
		  $.post('apps/manage_rce(pro).php',{
		  event:'oru',
		  id: $('#rce_pro_id').val()
		  },function(data) {		  
		  $('#rce_contact').val(data.rce_contact);
		  $('#rce_company').val(data.rce_company);		  
		  $('#modal-rce').load();
		},'json');
  }
  
  
  	$(function(){  
		var thaiYear = function (ct) {
			var leap=3;  
			var dayWeek=["พฤ.", "ศ.", "ส.", "อา.","จ.", "อ.", "พ."];  
			if(ct){  
				var yearL=new Date(ct).getFullYear()-543;  
				leap=(((yearL % 4 == 0) && (yearL % 100 != 0)) || (yearL % 400 == 0))?2:3;  
				if(leap==2){  
					dayWeek=["ศ.", "ส.", "อา.", "จ.","อ.", "พ.", "พฤ."];  
				}  
			}              
			this.setOptions({  
				i18n:{ th:{dayOfWeek:dayWeek}},dayOfWeekStart:leap,  
			})                
		}; 
		$("#rce_date").datetimepicker({
			timepicker:false,
			format:'Y-m-d',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
			lang:'th',  // แสดงภาษาไทย
			onChangeMonth:thaiYear,          
			onShow:thaiYear,                  
			yearOffset:543,  // ใช้ปี พ.ศ. บวก 543 เพิ่มเข้าไปในปี ค.ศ
			closeOnDateSelect:true,
		});       
	   
	});
	
	
	function rce_data(id){
		$.post('apps/rce_data.php',{
            id : id
            },function(data){
            //alert(data);
            $('#mainshow').html(data);
        });
	}
	
	
	

</script>