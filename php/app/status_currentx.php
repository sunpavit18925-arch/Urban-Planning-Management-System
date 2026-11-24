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
 <style>
 .sub-select-cp-input{
	 margin:0px;
	 border-top:solid #dfdfdf 1px;
	 padding: 4px 5px 4px 10px;
 }
 
 .sub-select-cp-input:first-child{
	border:none!important;
 }
 
 .div-sub-select-cp-input:hover{
	 background:blue;
	 color:white;
 }
 .table > tbody > tr > td{
	 border-top:solid #dfdfdf 1px;
 }
 .clickable:hover{
	 border-bottom:solid black 1px;
	 cursor:pointer;
 }
 
 </style>
 <div style="display:none;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b> <b>เวลา '.substr($result[0]['rp_cs_date'],11,5).' น. </b>';?></div>
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
		</div>
		<!--
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
		-->
		<div id="div_table_tracking"></div>
			
		
		
		
		<div hidden id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px">
			<img src="img/loading3.gif?<?php echo date('Ymdhis');?>" class="center">
		</div>
		<div hidden id="showTable2" class="unprintable" style="margin-left: -15px;">
			<div id="div-btn" class="col-xs-5 col-md-6">
				<button hidden id="btn_backz" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
		</div>
	
		
	</div>

<script>
load_table_tracking();
function load_table_tracking(){
	$.ajax({
		url : 'app/status_currentx_pro.php',
		async : true,
		type : 'POST',
		success : function(data, status, xhr){
			$('#div_table_tracking').html(data);
		},error : function(xhr, status ,data){
			alert('Loading : '+status+'\n'+data);
		}
	});
}
$("#select_status").change(function(){
	$('#select_cp').val('');
	$('#select_operate').val('0');
	$('#select_dflaw').val('0');
	
	$('#sub_select_cp').hide();
	$('#modal_status').val('0');
});

$("#select_cp_name").focusin(function(){
  changeCP();
});


function change_dflaw(){
	$('#select_dflaw').val(0);
	if($('#select_operate').val()=='1.1'){
		$('#div-select_dflaw').show();
	}else{
		$('#div-select_dflaw').hide();
	}
}

$("#select_operate , #select_dflaw").focusin(function(){
	$('#sub_select_cp').hide();
});
function addCS(){
	$('#modal-current_status').modal('show');
	$('#title-current_status').html('เพิ่มข้อมูล');
	$('#div-select_cp').hide();
	$('#div-select_operate').hide();
	$('#div-select_dflaw').hide();
	$('#select_status').val('0');
	$('#select_cp').val('');
	$('#select_operate').val('0');
	$('#select_dflaw').val('0');
	
	$('#sub_select_cp').hide();
	$('#modal_status').val('0');
	$('#event').val('c_cs_cp');
	
}

function change_type_cp(){
	var y = $('#select_status').val();
	if( y==0){
		$('#div-select_cp').hide();
		$('#div-select_operate').hide();
		$('#div-select_dflaw').hide();
		$('#select_cp').val('');
	}else{
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				event : 'select-cp',
				y : y
			},success : function(data, status, xhr){
				//alert('Loading : '+status);
				//alert(y);
				$('#select_cp').html(data);
				$('#sub_select_cp').hide();
				//changeCP();
			},error : function(xhr, status ,data){
				alert('Loading : '+status+'\n'+data);
			}
		});
		$('#div-select_cp').show();
		$('#div-select_operate').show();
		
		
	}
}


function changeCP(){
	$.ajax({
		url : 'app/manage.php',
		async : true,
		type : 'POST',
		data : {
			event : 'select-cp-input',
			cp_name : $('#select_cp_name').val(),
			type : $('#select_status').val()
		},success : function(data, status, xhr){
			//alert($('#select_cp').val());
			//alert('Loading : '+status);
			$('#sub_select_cp').show();
			$('#sub_select_cp').html(data);
		},error : function(xhr, status ,data){
			alert('Loading : '+status+'\n'+data);
		}
	});
}

function sub_select_cp(cp_name){
	$('#select_cp_name').val(cp_name);
	$('#sub_select_cp').hide();
}

function saveCS(){
	$.ajax({
		url : 'app/manage.php',
		async : true,
		type : 'post',
		data : {
			status : $('#select_status').val(),
			cp_name : $('#select_cp_name').val(),
			operate : $('#select_operate').val(),
			dflaw : $('#select_dflaw').val(),
			event : $('#event').val()
		},
		success : function(data, status, xhr){
			var datax = "เพิ่มข้อมูลสำเร็จ";
			if(datax==data){
				alert(datax);
				$('#modal-current_status').modal('hide');
				load_table_tracking();
			}else{
				alert(data);
			}
		},
		error : function(xhr, status ,data){
			alert('Status : '+status+'\n'+data);
		}
		
	});
}







</script>