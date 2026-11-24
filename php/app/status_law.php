<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  $title = "ผังยกร่างกฎหมาย";
  //echo 'status_current.php';
  $sql = "SELECT * FROM tref_rp_cs_date";
  $result = selectSql($sql);
  $dates = substr($result[0]['rp_cs_date'],8,2).' '.InttoMonth(substr($result[0]['rp_cs_date'],5,2),'th').' ' .substr($result[0]['rp_cs_date'],0,4);
 ?>
 
 <div style="display:inline;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b> <b>เวลา '.substr($result[0]['rp_cs_date'],11,5).' น. </b>';?></div>
	<div id="title_page" class="col-12">
		<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"><?= $title;?>
		</h2>
		<h3 class="hidden-md hidden-lg GHfont-mini unprintable" style="font-family: Kanit;text-align:center"><?= $title;?></h3>
		<div class="row" id="info_bar">
			<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_value"></h3>
		</div>
		<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
	</div>	
	<br>
	<div class="container-fluid">
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_backy" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_add" onclick="addDflaws();" class="pull-right btn btn-primary btn-md" style="margin-top:6px;">เพิ่มข้อมูล</button>
			</div>
		</div>
		
		<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px">
			<img src="img/loading3.gif?<?php echo date('Ymdhis');?>" class="center">
		</div>
		<div hidden id="showTable2" class="unprintable" style="margin-left: -15px;">
			<div id="div-btn" class="col-xs-5 col-md-6">
				<button hidden id="btn_backz" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
		</div>
	
		<div class="modal fade" id="modal-rp_cs_dflaws">
          <div class="modal-dialog">
            <div class="modal-content" style="border-radius:8px">
              <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 id="title-rp_cs_dflaws" class="modal-title" style="font-family:Kanit;color:black">เพิ่มข้อมูล</h4>
              </div>
              <div class="modal-body">
				<form>	
					<div id="div_rp_cs_dflaws" class="row">
						<div class="form-group col-xs-12 col-sm-12">
							<label>หมายเลขข้อ</label>
							<input  type="number" min="0" max="9999" class="form-control rp_cs_dflaws" id="rp_cs_dflaws_id" placeholder="ระบุหมายเลขข้อ . . . ." autocomplete="off">
							
						</div>
						<div class="form-group col-xs-12 col-sm-12">
							<label>รายละเอียดดำเนินงาน</label>
							<textarea class="form-control rp_cs_dflaws" id="rp_cs_dflaws_title" placeholder="ระบุรายละเอียดดำเนินงาน . . . ." autocomplete="off"></textarea>
							
						</div>
						<div class="form-group col-xs-12 col-sm-12">
							<label>หมายเลขลำดับแสดงผล</label>
							<input class="form-control rp_cs_dflaws" id="rp_cs_dflaws_asc" placeholder="ระบุหมายเลขลำดับแสดงผล . . . ." autocomplete="off">
							
						</div>
						
						
					</div>
				</form>
				<input type="hidden" class="form-control" id="event" value="">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
				<button id="saveBtn" type="button" class="btn btn-success pull-right" onclick="saveDflaws();">บันทึก</button>
              </div>
            </div>
          </div>
		</div>
	</div>
<script>
showTable('dflaw');
function showTable(type){
	$.ajax({
		url : 'app/status_operate-law_pro.php',
		async : true,
		type : 'POST',
		data : {
			type : type,
			defualt : 'true'
		},success : function(data, status, xhr){
			//alert('Loading : '+status);
			$('#subshow').html(data);
		},error : function(xhr, status ,data){
			alert('Loading : '+status+'\n'+data);
		}
	});
}


function addDflaws(type){
	$('#modal-rp_cs_dflaws').modal('show');
	$('#rp_cs_dflaws_id').val('');
	$('#rp_cs_dflaws_title').val('');
	$('#rp_cs_dflaws_asc').val(''); 
	$('#event').val('c_rp_cs_dflaws');
	checkData();
}

function checkData(){
	if($('#rp_cs_dflaws_id').val()!='' && $('#rp_cs_dflaws_title').val()!='' && $('#rp_cs_dflaws_asc').val()!=''){
		$('#saveBtn').removeClass('disabled').attr('onclick','saveDflaws();');
	}else{
		$('#saveBtn').addClass('disabled').removeAttr('onclick');
	}
}

function saveDflaws(){
	if($('#rp_cs_dflaws_id').val()=='' || $('#rp_cs_dflaws_title').val()=='' || $('#rp_cs_dflaws_asc').val()==''){
		alert('กรุณากรอกข้อมูลให้ครบ');
	}else{
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				id : $('#rp_cs_dflaws_id').val(),
				title : $('#rp_cs_dflaws_title').val(),
				asc : $('#rp_cs_dflaws_asc').val(),
				event : $('#event').val()
			},success : function(data, status, xhr){
				alert(status+'\n'+data);
				$('#modal-rp_cs_dflaws').modal('hide');
				showTable('dflaw');
			},error : function(xhr, status ,data){
				alert(status+'\n'+data);
			}
		});
	}
}

$('.rp_cs_dflaws').on("keydown keyup", function(){
	checkData();
});
</script>