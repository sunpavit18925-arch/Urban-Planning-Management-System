<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ข้อมูลระบบและอุปกรณ์</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">ข้อมูลระบบและอุปกรณ์</h4>
	</div>	
	<br>
	  <?php $sql = "SELECT COUNT(eqm_id) FROM tb_equipment ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
	<div class="col-12" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนระบบและอุปกรณ์ : <?php echo $row['COUNT(eqm_id)'];?></h5></div>
	<!--<div class="col-md-6" style="text-align:right;"><button class="btn btn-warning" onclick="goto(1);">ย้อนกลับ</button></div>-->




<div  class="col-12">
<table class="tablex table-bordered " id="mhardware" >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-1" scope="col">ลำดับ</th>
      <th style="text-align:center" class="col-1" scope="col">ชื่ออุปกรณ์</th>
      <!--<th style="text-align:center" class="col-1" scope="col">รหัสประจำอุปกรณ์</th>
	  <th style="text-align:center" class="col-1" scope="col">จำนวนอุปกรณ์</th>-->
	  <th style="text-align:center" class="col-1" scope="col">ราคาในการจัดซื้อ</th>
      <th style="text-align:center" class="col-1" scope="col">ปีที่จัดซื้อ</th>
      <th style="text-align:center" class="col-1" scope="col">ปีที่จัดซื้อทดแทน</th>
      <th style="text-align:center" class="col-1" scope="col">ราคา MA ต่อปี</th>
	  <th style="text-align:center" class="col-1" scope="col"> ตั้งของบ MA</th>	  
	  <th style="text-align:center" class="col-1" scope="col">ประกัน</th>
	  <th style="text-align:center" class="col-1" scope="col">สิ้นสุดประกัน</th>

    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $n = 0;
        $sql = "SELECT * FROM tb_equipment ORDER BY eqm_id ASC ";
        $result = selectSql($sql);

        foreach ($result as $row){ 
		$n++;
		?>

    <tr class="trcolor">
      <th style="text-align:center" scope="row"><?php echo $n; ?></th>
	  <td><?php echo $row['eqm_name']; ?></td>
      <!--<td style="text-align:center"><?php //echo $row['eqm_krp']; ?></td>
	  <td style="text-align:center"><?php //echo $row['eqm_serial']; ?></td>-->
	  <td style="text-align:center"><?php echo number_format($row['eqm_price']); ?></td>
	  <td style="text-align:center"><?php echo $row['eqm_buy']; ?></td>
	  <td style="text-align:center"><?php if($row['eqm_rebuy'] =='9999'){echo "-";} else{echo $row['eqm_rebuy'];} ?></td>
	  <td style="text-align:center"><?php echo number_format($row['eqm_ma']); ?></td>     
	  <td style="text-align:center"><?php echo number_format($row['eqm_fma']); ?></td>
	  <td style="text-align:center"><?php echo ($row['eqm_guarantee']); ?></td>
	  <td style="text-align:center"><?php 
	  if($row['eqm_gend']== NULL OR $row['eqm_gend']== null OR $row['eqm_gend']== "" OR $row['eqm_gend']== " "){
		echo "Unknow Date";
	  }
	  else{
		$years = substr($row['eqm_gend'],0,4);
		$months = substr($row['eqm_gend'],5,2);
		$days = substr($row['eqm_gend'],8,2);
		switch ($months){
			case "01";$months = "ม.ค.";break;
			case "02";$months = "ก.พ.";break;
			case "03";$months = "มี.ค.";break;
			case "04";$months = "เม.ย";break;
			case "05";$months = "พ.ค.";break;
			case "06";$months = "มิ.ย.";break;
			case "07";$months = "ก.ค.";break;
			case "08";$months = "ส.ค.";break;
			case "09";$months = "ก.ย.";break;
			case "10";$months = "ต.ค.";break;
			case "11";$months = "พ.ย.";break;
			case "12";$months = "ธ.ค.";break;	
		}
		echo $days,' ',$months,' ',$years;
	  }
		//$year = date($row['eqm_gend']);
	 // echo ($row['eqm_gend']); ?></td> 
    </tr>
  <?php } ?>
  </tbody>
  </table>

<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>

<script type="text/javascript">
  $(function () {
    $('#mhardware').DataTable({
      'paging'      : true,
      'lengthChange': false,
	  'lengthMenu'  : [[10, 50, 100, -1], [20, 50, 100, "ทั้งหมด"]],
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
	  'processing'  : true,
      'autoWidth'   : false,
	  'stateSave'   : false,
	  

    })
  })


	function addEquipment(){
		$('#eqm_id').val('');
		$('#eqm_name').val('');
		$('#eqm_krp').val('');
		$('#eqm_serial').val('');
		$('#eqm_value').val('');
		$('#eqm_price').val('');
		$('#eqm_buy').val('');
		$('#eqm_rebuy').val('');
		$('#eqm_ma').val('');
		$('#eqm_fma').val('');
		$('#eqm_pro').val('');
		$('#eqm_guarantee').val('');
		$('#eqm_gend').val('');
		$('#user_id').val();
		$('#event').val('c');	
		
		$('#modal-equipment').modal('show');
	}
	
	function equipmentAddEdit() {
		if ( $('#eqm_name').val()=='' || $('#eqm_price').val()=='' || $('#eqm_buy').val()=='' || $('#eqm_rebuy').val()=='' || $('#eqm_ma').val()=='' ){
				alert('กรุณากรอกข้อมูลให้ครบ');
		}else{
			$.post('apps/manage_hardware(pro).php',{
			 eqm_name:$('#eqm_name').val(),
			 eqm_price:$('#eqm_price').val(),
			 eqm_buy:$('#eqm_buy').val(),
			 eqm_rebuy:$('#eqm_rebuy').val(),
			 eqm_ma:$('#eqm_ma').val(),
			 eqm_guarantee:$('#eqm_guarantee').val(),
			 eqm_gend:$('#eqm_gend').val(),
			 eqm_fma:$('#eqm_fma').val(),
			 eqm_pro:$('#eqm_pro').val(),
			 eqm_id:$('#eqm_id').val(),
			 user_id:$('#user_id').val(),
			 event:$('#event').val()
			 },function(data) {
			 $('#modal-equipment').modal('hide');
			 alert(data);
			 $('#mainshow').load('apps/manage_hardware.php');
			 });
		}
	}
	
	
      function equipmentDEL(id){
      if(confirm('คุณต้องการจะลบข้อมูลนี้ ใช่หรือไม่ ?')){
          $.post('apps/manage_hardware(pro).php',{
            event:'d',
            id : id
          },function(data){
            alert(data);
            $('#mainshow').load('apps/manage_hardware.php');
          });
        }
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
		$("#eqm_gend").datetimepicker({
			timepicker:false,
			format:'Y-m-d',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
			lang:'th',  // แสดงภาษาไทย
			onChangeMonth:thaiYear,          
			onShow:thaiYear,                  
			yearOffset:543,  // ใช้ปี พ.ศ. บวก 543 เพิ่มเข้าไปในปี ค.ศ
			closeOnDateSelect:true,
		});       
	   
	});
	  
	  
	    function equipmentReadEdit(id) {//อ่านก่อนแก้ไข
		  $.post('apps/manage_hardware(pro).php',{
		  event:'ru',
		  id: id
		  },function(data) {
		  $('#eqm_id').val(id) ,
		  $('#eqm_name').val(data.eqm_name);
		  //$('#eqm_krp').val(data.eqm_krp);
		  //$('#eqm_serial').val(data.eqm_serial);
		  //$('#eqm_value').val(data.eqm_value);
		  $('#eqm_price').val(data.eqm_price);
		  $('#eqm_buy').val(data.eqm_buy);
		  $('#eqm_rebuy').val(data.eqm_rebuy);
		  $('#eqm_ma').val(data.eqm_ma);
		  $('#eqm_fma').val(data.eqm_fma);
		  $('#eqm_guarantee').val(data.eqm_guarantee); 
		  $('#eqm_gend').val(data.eqm_gend);
		  $('#eqm_pro').val(data.eqm_pro);
		  $('#event').val('u');
		  $('#user_id').val(data.user_id);
		  $('#modal-equipment').modal('show');
		},'json');
  }
	
</script>