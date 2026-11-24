<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$sql = "SELECT COUNT(pvn_id) FROM ref_province";
	$result = selectSql($sql);
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
			text-align:center;
			 
		}
		@page{
			size:A4 portrait;
			margin:10mm 4mm;
		}
		


		
	}
	

	</style>
	<?php data_date(TRUE);?>
	<div class="container-fluid">
		<h2 class="GHfont unprintable" style="text-align:center;font-family:Kanit">จัดการข้อมูลจังหวัด<br class="hidden-md hidden-lg">และส่วนผังเมืองรวม</h2>
		<h2 hidden class="GHfont printable" style="text-align:center;font-family:Kanit">ข้อมูลจังหวัดและส่วนผังเมืองรวม</h2>
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-6 ">
				<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn2" class="col-xs-6">
				<button id="btn_goback" onclick="pvnAdd()" class="pull-right btn btn-primary btn-md" style="margin-top:6px">เพิ่มจังหวัด</button>
			</div>
		</div>	
		
		<div class="row" id="info_bar">
			<div class="col-xs-12 col-md-12"  style="display:inline;padding-top:0px;margin-bottom:-15px">
				<h3 style="font-family:Kanit" >จำนวน  : <b id="pvn_value"></b> จังหวัด </h3>
			</div>
		</div>
	
		<div id="pvnshow" style="padding-top:0px;margin-bottom:0px">
		</div>
	</div>
	

	<div class="modal fade" id="modal-province">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:16px">
				<div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
					<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มข้อมูล</h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label for="pvn_name">ชื่อจังหวัด</label>
							<input type="text" class="form-control" id="pvn_name" onkeyup="checkData()" onchange="checkData()" value="">
						</div>
						<div class="form-group">
							<label for="pvn_affiliation" >ส่วนผังเมืองรวม</label>
							<select onchange="checkData()" class="form-control" id="pvn_affiliation" required>
								<option selected value="">เลือกส่วนผังเมืองรวม</option>
								<option value="1">ส่วนผังเมืองรวมที่ 1</option>
								<option value="2">ส่วนผังเมืองรวมที่ 2</option>
								<option value="3">ส่วนผังเมืองรวมที่ 3</option>
								<option value="4">ส่วนผังเมืองรวมที่ 4</option>
							</select>
						</div>
						<input type="hidden" name="pvn_id" class="form-control" id="pvn_id" value="" >
						<input type="hidden" id="event" value="">
						<input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
					<button id="btnSave" type="button" class="btn btn-success pull-right" onclick="pvnSave();">บันทึก</button>
				</div>
            </div>          
        </div>
    </div>

<script>
	$('#exportPdfBtn').show();
	$('#pvnshow').load('app/pro_province.php');
	function checkData(){
		if($('#pvn_name').val()=='' || $('#pvn_affiliation').val()==''){
			$('#btnSave').removeAttr('onclick');
			$('#btnSave').addClass('disabled');
		}else{
			$('#btnSave').attr('onclick','pvnSave()');
			$('#btnSave').removeClass('disabled');
		}
	}
	function pvnEdits(){
		alert("ไม่สามารถแก้ไขได้");
	}
	function pvnDels(){
		alert("ไม่สามารถลบได้");
	}
	function pvnAdd(){
		$('#pvn_id').val("");
		$('#pvn_name').val("");
		$('#pvn_affiliation').val("");
		$('#event').val("c_pvn");
		$('#modal-province').modal('show');
		$('.modal-title').html('เพิ่มจังหวัด');
		checkData();
	}
	function pvnSave(){
		if ( $('#pvn_name').val()=='' || $('#pvn_affiliation').val()=='' ){
				alert('กรุณากรอกข้อมูลให้ครบ');
		}else{
			$.post('app/manage.php',{
			pvn_id:$('#pvn_id').val(),	
			pvn_name:$('#pvn_name').val(),
			pvn_affiliation:$('#pvn_affiliation').val(),
			event:$('#event').val(),
			user_id:$('#user_id').val()
			 },function(data) {
			 $('#modal-province').modal('hide');
			 alert(data);
			 $('#mainshow').load('app/manage_province.php');
			 });
		}
	}
	function pvnEdit(x){
		$.post('app/manage.php',{
		  event:'ru_pvn',
		  pvn_id: x
		  },function(data) {
			  //alert(data);
		  $('#pvn_id').val(data.pvn_id);
		  $('#pvn_name').val(data.pvn_name);
		  $('#pvn_affiliation').val(data.pvn_affiliation);
		  $('#event').val('u_pvn');
		  //$('#user_id').val(data.user_id);
		  $('#modal-province').modal('show');
		  $('.modal-title').html('แก้ไขข้อมูล');
		  checkData();
		},'json');
	}
	function pvnDel(x){
		if(confirm('คุณต้องการจะลบข้อมูลนี้ ใช่หรือไม่ ?')){
			$.post('app/manage.php',{
				pvn_id:x,
				event:'d_pvn',
				user_id:$('#user_id').val()
			},function(data){
				$('#modal-province').modal('hide');
				alert(data);
				$('#mainshow').load('app/manage_province.php');
			});
		}
	}
</script>