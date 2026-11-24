<?php 
	include '../js/function_php.php';
	session_name('tutorial');
	session_start();
	clear_cp_session();
	$x = 0;
	if($x==0){ ?>
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
			<h2 id="showTitle" class="GHfont unprintable" style="text-align:center;font-family:Kanit">จัดการผู้ดูแลระบบ</h2>
			<h2 hidden id="printTitle" class="GHfont printable" style="text-align:center;font-family:Kanit">รายชื่อผู้ดูแลระบบ</h2>
			<!--<h4 class="hidden-md hidden-lg" style="text-align:center;font-family:Kanit">จัดการผู้ดูแลระบบ</h4>-->
		</div>
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-6 ">
				<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn2" class="col-xs-6">
				<button class="btn btn-primary pull-right" onclick="adminAdd()"  style="margin-top:6px;">เพิ่มผู้ดูแล</button>
			</div>
		</div>
<!--
	<div class="row">
			<div id="div-search_myhead" class="col-xs-12 col-md-12">
				<input id="search_keyword" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหา">
				<button id="search_keyword_btn" onclick="searchMyhead()" class="btn btn-success" style="position:absolute;margin-top:6px;margin-left:-73.9px;"><i class="fa fa-search" style="margin-right:4px"></i>ค้นหา</button>
			</div>
		</div>		-->
		
		<div class="row">
			<div class="col-xs-10"  style="margin-bottom:-15px">
				<h3 style="font-family: Kanit;">จำนวน : <b id="count_admin"></b> คน</h3>
			</div>
			<div class="col-xs-2" style="margin-bottom:-15px">
				
			</div>
		</div>
		<div id="adminshow" class="" style="border-color:transparent;">
			<img src="img/loading3.gif" class="center">
		</div>
	</div>
		



		<script>
		showLog();
			function showLog(){
				$.post('app/pro_admin.php',{
				},function(data){
					//alert($('#log_type').val());
					$('#adminshow').html(data);
				});
			}
		</script>
		<?php
	}else{ ?>
		<script>
			$('#modal-alert').modal('show');
		</script> <?php
	} ?>
	
	
<div class="modal fade" id="modal-user">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;pointer-events: auto;">
			<div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่ม/แก้ไข</h4>
			</div>
            <div class="modal-body">
				<div class="row">	
						<div class="form-group col-xs-12 col-sm-6 col-md-8">
							<label for="user_id">เลขประจำตัวประชาชน</label>
							<input id="user_id" class="form-control" placeholder="เลขประจำตัวประชาชน 13 หลัก" onkeyup="checkData();" maxlength="13">
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-4">
							<label for="user_affiliation">สังกัด</label>
							<select id="user_affiliation" class="form-control" onkeyup="checkData();">
									<option value="" selected>เลือกสังกัด</option>
								<?php
								$sql = "SELECT * FROM ref_user_affiliation";
								$result = selectSql($sql);
								foreach($result as $row){?>
									<option value="<?= $row['use_aff_id'];?>"><?= $row['use_aff_name'];?></option>
								<?php } ?>
							</select>
						</div>
						
						<div class="form-group col-xs-12 col-md-6">
							<label for="user_fname">ชื่อผู้ใช้งาน</label>
							<input id="user_fname" class="form-control" placeholder="ชื่อ ................" onkeyup="checkData();">
						</div>
						<div class="form-group  col-xs-12 col-md-6">
							<label for="user_lname">นามสกุลผู้ใช้งาน</label>
							<input id="user_lname" class="form-control" placeholder="นามสกุล ................" onkeyup="checkData();">
						</div>				
					
					<input type="hidden" id="event">
					<input type="hidden" id="x">
					<input type="hidden" id="old_user_id">
				</div>	
			</div>
            <div class="modal-footer">
				<button id="btnSave" type="button" class="btn btn-success pull-right" style="margin-left:8px" onclick="adminSave()">บันทึก</button>
				<button  type="button" class="btn btn-danger pull-left" data-dismiss="modal" >ปิด</button>
            </div>
		</div>
    </div>
</div>


<script>
	$('#exportPdfBtn').show();
	
	function checkData(){
		if($('#user_id').val()=="" || $('#user_fname').val()=="" || $('#user_lname').val()==""){
			$('#btnSave').removeAttr('onclick');
			$('#btnSave').addClass('disabled');
		}else{
			$('#btnSave').attr('onclick','adminSave()');
			$('#btnSave').removeClass('disabled');
		}
	}
	
	
	function adminAdd(){
		//alert('Add');
		$('#user_id').attr('readonly',false);
		$('#user_id').val('');
		$('#user_fname').val('');
		$('#user_lname').val('');
		$('#user_affiliation').val('');
		$('#event').val('c_user');		
		$('#modal-user').modal('show');
		$('.modal-title').html('เพิ่มผู้ดูแลระบบ');
		checkData();
	}
	
	function adminSave(){
		//alert('Save');
		if($('#user_id').val()=="" || $('#user_fname').val()=="" || $('#user_lname').val()==""){
			alert('กรุณากรอกข้อมูลให้ครบ');
		}else{
			$.post('app/manage.php',{
				event : $('#event').val(),
				user_id : $('#user_id').val(),
				user_fname : $('#user_fname').val(),
				user_lname : $('#user_lname').val(),
				user_affiliation : $('#user_affiliation').val(),
				user_pmaccess : '0',
				user_status : '2',
				old_user_id : $('#old_user_id').val()
			},function(data){
				//alert($('#event').val());
				//$('#adminshow').load('app/pro_admin.php');
				$('#modal-user').modal('hide');
				alert(data);
				goto('a9');
				
			});
		}
	}
	
	function adminEdit(x){
		$.post('app/manage.php',{
			event : 'ru_user' ,
			user_id : x
		},function(data){
			$('#x').html(data);
			$('#event').val('u_user');	
			$('#modal-user').modal('show');
			$('.modal-title').html('แก้ไขข้อมูล');
			$('#old_user_id').val($('#user_id').val());
			checkData();
		});
	}
	function adminDel(x){
		//alert('Del');
		if(confirm('คุณต้องการลบข้อมูลใช่หรือไม่')){
			$.post('app/manage.php',{
				event : 'd_user',
				user_id : x
			},function(data){
				alert(data);
				//$('#adminshow').load('app/pro_admin.php');
				goto('a9');
			});
		}
	}


</script>