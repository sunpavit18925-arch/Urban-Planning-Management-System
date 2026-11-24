<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
?>
<style>
.inputfield{
          position: relative;
          width: 100px;
      }
     
     input[type=time]::-webkit-calendar-picker-indicator {
          bottom: 0;
          color: transparent;
          cursor: pointer;
          height: auto;
          left: 0;
          opacity: 0;
          position: absolute;
          right: 0;
          top: 0;
          width: 100%
      }
      .clock-img {
          position: absolute;
          right: 0px;
          top: 10px;
          z-index: 0;
      }
</style>
<div class="modal fade" id="modal-status-date" style="pointer-events: none;"> 
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:8px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeStatusDate();">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">สถานภาพผังเมืองปัจจุบัน</h4>
            </div>
            <div class="modal-body">
				<form>
					<div id="div_status_date" class="row">
						<div class="form-group col-xs-12 col-sm-12">
							<label>ระบุวันที่ของข้อมูล</label>
							<input type="text "class="form-control cp_date" id="status_date" placeholder="ปี-เดือน-วัน" autocomplete="off">
							<i class="fa fa-calendar" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
						<div class="form-group col-xs-12 col-md-12" >
							<label >ระบุเวลาของข้อมูล</label>
							<input type="time" id="status_time" class="form-control">
							<i class="fa fa-clock-o" style="position: absolute;right: 10px;margin: -23px 15px 0px 15px;"></i>
						</div>
					</div>
					
					
					
					
					<hr hidden style="border:solid #dfdfdf 1px;margin:0px 0px 8px 0px;border-top:transparent">
					
					
					<input type="hidden" name="status_event" class="form-control" id="status_event" value="">
			
				</form>
			</div>
            <div style="margin-top:-10px" class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" onclick="closeStatusDate();" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="saveStatusDate();">บันทึก</button>
            </div>
		</div>
    </div>
</div>
<script>
	addStatusDate();
	function addStatusDate(){
		$('#extrashow').css('display','block');
		$('#modal-status-date').modal('show');
		$('#status_event').val('c_statusdate');
		$('#status_date').val('');
	}
	async function closeStatusDate(){
		$('#modal-status-date').modal('hide');
		await setTimeout(function() {
			$('#extrashow').html('');
		},500)
	}
	$(function(){		
		$.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.		
		$(".cp_date").datetimepicker({
			timepicker:false,
			format:'Y-m-d',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000			
			lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
			onSelectDate:function(dp,$input){
				var yearT=new Date(dp).getFullYear()-0;  
				var yearTH=yearT+543;
				var fulldate=$input.val();
				var fulldateTH=fulldate.replace(yearT,yearTH);
				$input.val(fulldateTH);
			},
		});       
		// กรณีใช้กับ input ต้องกำหนดส่วนนี้ด้วยเสมอ เพื่อปรับปีให้เป็น ค.ศ. ก่อนแสดงปฏิทิน
		$(".cp_date").on("mouseenter mouseleave",function(e){
			var dateValue=$(this).val();
			if(dateValue!=""){
					var arr_date=dateValue.split("-"); // ถ้าใช้ตัวแบ่งรูปแบบอื่น ให้เปลี่ยนเป็นตามรูปแบบนั้น
					// ในที่นี้อยู่ในรูปแบบ 00-00-0000 เป็น d-m-Y  แบ่งด่วย - ดังนั้น ตัวแปรที่เป็นปี จะอยู่ใน array
					//  ตัวที่สอง arr_date[2] โดยเริ่มนับจาก 0 
					if(e.type=="mouseenter"){
						var yearT=arr_date[0]-543;
					}		
					if(e.type=="mouseleave"){
						var yearT=parseInt(arr_date[0])+543;
					}	
					dateValue=dateValue.replace(arr_date[0],yearT);
					$(this).val(dateValue);													
			}		
		});  
	});


	function saveStatusDate(){
		if($('#status_date').val()=='' || $('#status_date').val()==' ' || $('#status_date').val()=='  ' || $('#status_date').val()==null){
			alert('กรุณาระบุวันที่ของข้อมูล');
		}
		else{
			$.ajax({
				url : 'app/manage.php',
				asyn : true,
				type : 'POST',
				data : {
					event : $('#status_event').val(),
					status_datetime : $('#status_date').val()+' '+$('#status_time').val()+':00',
				},
				success : function(data, status, xhr){
					//alert('Post data : '+status+'\n'+data);
					alert(data);
					closeStatusDate();
				},
				error : function(xhr, status, data){
					alert('Post data : '+status+'\n'+data);
				}
			});
		}
	}

</script>