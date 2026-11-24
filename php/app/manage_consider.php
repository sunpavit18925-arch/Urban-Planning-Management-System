<?php 
	include '../js/function_php.php';
	session_name('tutorial');
	session_start();
	clear_cp_session();
	?>
	<style>
	@media print{
		.printx > tbody> tr > td{
			font-size:11px!important;
			padding:2.25px!important;
		}
		.printx > thead> tr > td{
			font-size:12.5px!important;
			padding:3px!important;
		}
		.printx > thead> tr > th{
			font-size:12.5px!important;
			padding:3px!important;
			color:white!important;
			background-color:#295DBD!important;
			
		}
		@page{
			size:A4 portrait;
			margin:10mm 4mm;
		}


		
	}
	</style>
	<?php data_date(TRUE);?>
	<div class="container-fluid">
		<div class="row">
			<h2 id="showTitle" class="hidden-sm hidden-xs GHfont unprintable" style="text-align:center;font-family:Kanit">จัดการเหตุผลผังพิจารณาดำเนินการ</h2>
			<h3 id="showTitle2" class="hidden-lg hidden-md GHfont unprintable" style="text-align:center;font-family:Kanit">จัดการเหตุผลผังพิจารณาดำเนินการ</h3>
			<h2 hidden id="printTitle" class="GHfont printable" style="text-align:center;font-family:Kanit">จัดการเหตุผลผังพิจารณาดำเนินการ</h2>
			<!--<h4 class="hidden-md hidden-lg" style="text-align:center;font-family:Kanit">จัดการผู้ดูแลระบบ</h4>-->
		</div>
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-6 ">
				<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn2" class="col-xs-6">
				<button class="btn btn-primary pull-right" onclick="considerAdd()"  style="margin-top:6px;">เพิ่มเหตุผล</button>
			</div>
		</div>

		
		<div class="row">
			<div class="col-xs-10"  style="margin-bottom:-15px">
				<h3 style="font-family: Kanit;">จำนวน : <b id="count_consider"></b> เหตุผล</h3>
			</div>
			<div class="col-xs-2" style="margin-bottom:-15px">
				
			</div>
		</div>
		<div id="considershow" class="" style="border-color:transparent;">
			<img src="img/loading3.gif" class="center">
		</div>
	</div>
	
<div class="modal fade" id="modal-consider">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;pointer-events: auto;">
			<div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" style="font-family:Kanit;color:black"></h4>
			</div>
            <div class="modal-body">
				<div class="row">	
						
						
						<div class="form-group  col-xs-12 col-md-12">
							<label for="consider_name">หมายเหตุ</label>
							<textarea id="consider_name" class="form-control" placeholder="หมายเหตุ ................" onkeyup="checkData();"/>
						</div>				
						<div class="form-group col-xs-12 col-md-12">
							<label for="consider_type">การนำไปใช้ในขั้นตอน</label>
							<select id="consider_type" class="form-control">
								<option value="0">ใช้กับทั้งสองขั้นตอน</option>
								<option value="A">ใช้เฉพาะขั้นตอนการวางและจัดทำผัง</option>
								<option value="B">ใช้เฉพาะขั้นตอนการปรับปรุงผัง</option>
							</select>
						</div>
					<input type="hidden" id="event">
					<input type="hidden" id="x">
					<input type="hidden" id="consider_id">
				</div>	
			</div>
            <div class="modal-footer">
				<button id="btnSave" type="button" class="btn btn-success pull-right" style="margin-left:8px" onclick="considerSave()">บันทึก</button>
				<button  type="button" class="btn btn-danger pull-left" data-dismiss="modal" >ปิด</button>
            </div>
		</div>
    </div>
</div>


<script>
	$('#exportPdfBtn').show();
	
	function checkData(){
		if($('#consider_name').val()==""){
			$('#btnSave').removeAttr('onclick');
			$('#btnSave').addClass('disabled');
		}else{
			$('#btnSave').attr('onclick','considerSave()');
			$('#btnSave').removeClass('disabled');
		}
	}
	
	
	function considerAdd(){
		//alert('Add');
		$('#consider_id').val('');
		$('#consider_name').val('');
		$('#consider_type').val(0);
		$('#event').val('c_consider');		
		$('#modal-consider').modal('show');
		$('.modal-title').html('เพิ่มเหตุผล');
		checkData();
	}
	
	function considerSave(){
		//alert('Save');
		if($('#consider_name').val()==""){
			alert('กรุณากรอกข้อมูลให้ครบ');
		}else{
			$.post('app/manage.php',{
				event : $('#event').val(),
				consider_id : $('#consider_id').val(),
				consider_name : $('#consider_name').val(),
				consider_type : $('#consider_type').val(),
			},function(data){
				//alert($('#event').val());
				//$('#considershow').load('app/pro_consider.php');
				$('#modal-consider').modal('hide');
				alert(data);
				showData();
				
			});
		}
	}
	
	function considerEdit(x){
		$.post('app/manage.php',{
			event : 'ru_consider' ,
			consider_id : x
		},function(data){
			$('#x').html(data);
			$('#event').val('u_consider');	
			$('#modal-consider').modal('show');
			$('.modal-title').html('แก้ไขข้อมูล');
			$('#old_user_id').val($('#user_id').val())
			checkData();
		});
	}
	function considerDel(x){
		//alert('Del');
		if(confirm('คุณต้องการลบข้อมูลใช่หรือไม่')){
			$.post('app/manage.php',{
				event : 'd_consider',
				consider_id : x
			},function(data){
				alert(data);
				//$('#considershow').load('app/pro_consider.php');
				showData();
			});
		}
	}
	
	showData();
	function showData(){
		$.post('app/pro_consider.php',{
		},function(data){
			//alert($('#log_type').val());
			$('#considershow').html(data);
		});
	}


</script>