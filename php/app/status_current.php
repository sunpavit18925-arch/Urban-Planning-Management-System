<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  $title = "สถานภาพผังเมืองปัจจุบัน";
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
			<div id="div-btn" class="col-xs-5 col-md-6">
				<button id="btn_backy" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
		</div>
		<div class="unprintable row search-frame">
			<text class="search-title">ค้นหาข้อมูล</text>
			<div class="search-content-area">
				<div class="search-row">
					<div>สถานภาพผัง :  </div>
					<select id="status_type" onchange="status_search();">
						<option value="0" selected>เลือกสถานภาพผัง</option>
						<option value="1">พื้นที่เปิดใหม่</option>
						<option value="2">มีอายุบังคับใช้</option>
						<option value="3">หมดอายุบังคับใช้</option>
					</select>
				</div>				
				<div class="search-row">
					<div>ชื่อผัง :  </div>
					<input id="cp_name" onkeyup="checkInputText(this);status_search();" placeholder="ระบุชื่อผังเมือง . . . . . . .">
					
				</div>
				<div class="search-note" style="">หมายเหตุ : สามารถเลือกสืบค้นได้มากกว่า 1 เงื่อนไข </div>
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
	
		<div class="modal fade" id="modal-unnamed">
          <div class="modal-dialog">
            <div class="modal-content" style="border-radius:8px">
              <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 id="title-unnamed" class="modal-title" style="font-family:Kanit;color:black"></h4>
              </div>
              <div class="modal-body">
				<form>	
					
				</form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
              </div>
            </div>
          </div>
		</div>
	</div>
<script>
status_search();
function status_search(){
	$.ajax({
		url : 'app/status_current_pro.php',
		async : true,
		type : 'POST',
		data : {
			search_type : $('#status_type').val(),
			cp_name :  $('#cp_name').val(),
			order_by : '--' 
		},success : function(data, status, xhr){
			//alert('Loading : '+status);
			$('#subshow').html(data);
		},error : function(xhr, status ,data){
			alert('Loading : '+status+'\n'+data);
		}
		
		
	});
}






</script>