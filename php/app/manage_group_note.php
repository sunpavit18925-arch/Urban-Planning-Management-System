<?php 
include '../js/function_db.php';
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
<div class="col-12">
	<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center">จัดการรายการหมวดหมู่หมายเหตุ</h2>
	<h3 class="hidden-md hidden-lg GHfont unprintable" style="font-family: Kanit;text-align:center">จัดการรายการหมวดหมู่หมายเหตุ</h3>
	<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
</div>
<div class="container-fluid">
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-6 col-sm-6">
				<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-select" class="col-xs-6 col-sm-6">
				<button id="btn_addMyhead" onclick="addMyhead();" class="pull-right btn btn-primary btn-md" style="margin-top:6px;">เพิ่มรายการ</button>		
			</div>
		</div>
		
		<div class="row unprintable">
			<div id="div-search_myhead" class="col-xs-12 col-sm-6 col-md-6">
				<select class="form-control" style="margin-top:6px;" id="search_select" onchange="searchMyhead()">
					<option value="" selected>ค้นหาด้วยขั้นตอน ......</option>
					<option value="all" >รายการที่ใช้กับทุกขั้นตอน</option>
					<option value="0" >ขั้นตอนที่ 0 ไม่มีการดำเนินการ</option>
					<?php 
						$sql = "SELECT * FROM ref_up_fst_step WHERE step_id != 0";
						$result = selectSql($sql);
						foreach($result as $row){ ?>
							<option value="<?= $row['step_id'];?>"><?= 'ขั้นตอนที่ '.$row['step_id'].' '.$row['step_name'];?></option>
						<?php } ?>
				</select>
			</div>
			<div id="div-search_myhead" class="col-xs-12 col-sm-6 col-md-6">
				<input id="search_keyword" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหา .....">
				<button id="search_keyword_btn" onclick="searchMyhead()" class="btn btn-success" style="position:absolute;margin-top:6px;margin-left:-74.3px;"><i class="fa fa-search" style="margin-right:4px"></i>ค้นหา</button>
			</div>
		</div>
		<div class="row" id="info_bar">
			<div class="col-xs-12 col-md-3"  style="display:inline;padding-top:0px;margin-bottom:-15px">
				<h3 style="font-family:Kanit" >จำนวน  : <b id="myhead_value"></b> รายการ </h3>
			</div>
		</div>
		<div id="myheadshow" class="" style="border-color:transparent;">
			<img src="img/loading3.gif" class="center">
		</div>
</div>

<div class="modal fade" id="modal-list-myhead" data-bs-backdrop="static">
    <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:16px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black"><text id="display-page">เพิ่มหมวดหมู่</text></h4>
            </div>
            <div class="modal-body" >
				<form>
					<div class="form-group">
						<label for="myhead_name">ชื่อรายการหมวดหมู่หมายเหตุ</label>
						<textarea id="myhead_name" class="form-control" placeholder="ระบุชื่อรายการ" onkeyUp="checkData()"></textarea>
					</div>
					<div class="form-group">
						<label for="myhead_step">แสดงรายการในขั้นตอน</label>
						<select id="myhead_step" class="form-control">
							<option value="all">ใช้กับทุกขั้นตอน</option>
							<option value="0">ขั้นตอนที่ 0 ไม่มีการดำเนินการ</option>
							<?php 
								$sql = "SELECT * FROM ref_up_fst_step WHERE step_id > 0";
								$result = selectSql($sql);
								foreach($result as $row){ ?>
									<option value="<?= $row['step_id'];?>">ขั้นตอนที่ <?= $row['step_id'].' '.$row['step_name'];?></option>
								<?php }
							
							?>
						</select>
					</div>
					<input type="hidden" id="myhead_id">
					<input type="hidden" id="event">
				</form>
			</div>
			<div  class="modal-footer">			
				<button id="myheadSave" onclick="saveMyhead()" type="button" class="btn btn-success btn-md pull-right" >บันทึก</button>
				<button id="myheadClose" type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" >ปิด</button>
			</div>
		</div>
	</div>
</div>


<script>
	//$('#myheadshow').load('app/manage_group_note_pro.php');
	$("#search_keyword").keyup(function(event) {
		if (event.keyCode === 13) {
			searchMyhead();
		}
	});

	function checkData(){
		if($('#myhead_name').val()==null || $('#myhead_name').val()=="" || $('#myhead_name').val()==" "){
			$('#myheadSave').removeAttr('onclick');
			$('#myheadSave').addClass('disabled');
		}else{
			$('#myheadSave').attr('onclick','saveMyhead()');
			$('#myheadSave').removeClass('disabled');
		}
	}
	function addMyhead(){
		$('#myhead_name').val("");
		$('#event').val("c_myhead");
		$('#myhead_step').val('all');
		//$('#myheadSave').hide();
		$('#modal-list-myhead').modal('show');
		$('.modal-title').html('เพิ่มรายการหมวดหมู่');
		checkData();
	}
	

	function editMyhead(x){
		$.post('app/manage.php',{
			myhead_id : x ,
			event : 'ru_myhead'
		},function(data){
			$('#modal-list-myhead').modal('show');
			$('.modal-title').html('แก้ไขข้อมูล');
			checkData();
			$('#myhead_id').val(data.myhead_id);
			$('#myhead_name').val(data.myhead_name);
			$('#event').val("u_myhead");	
			$('#myhead_step').val(data.myhead_step);	
		},'json');
	}
	
	function delMyhead(x){
		if(confirm("คุณต้องการลบรายการนี้ใช่หรือไม่")){
			$.post('app/manage.php',{
				myhead_id : x,
				event: "d_myhead"
			},function(data){
				alert(data);
				searchMyhead();
			});
		}
	}
	
	function saveMyhead(){
		$.post('app/manage.php',{
			event : $('#event').val(),
			myhead_id :$('#myhead_id').val(),
			myhead_name : $('#myhead_name').val(),
			myhead_step : $('#myhead_step').val()
		},function(data){
			alert(data);
			$('#modal-list-myhead').modal('hide');
			searchMyhead()
		});
	}
	
	
	searchMyhead()
	function searchMyhead(){
		var string = $('#search_keyword').val();
		string = string.replace(/\s+/g, " ");
		$('#search_keyword').val(string);
		//alert(string);
		$.post('app/manage_group_note_pro.php',{
			search_select : $('#search_select').val(),
			search_keyword : $('#search_keyword').val()
		},function(data){
			//alert(data);
			$('#myheadshow').html(data);
		});
	}
	
	
	
	
	
	
	
	
	
</script>