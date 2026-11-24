<?php
include '../js/function_db.php';
session_name('tutorial');
session_start();

$years = date('Y')+543;
$months = date('m');
if($months == 1 ){
	$months = 'ม.ค.';
}elseif($months == 2 ){
	$months = 'ก.พ.';
}elseif($months == 3 ){
	$months = 'มี.ค.';
}elseif($months == 4 ){
	$months = 'เม.ย.';
}elseif($months == 5 ){
	$months = 'พ.ค.';
}elseif($months == 6 ){
	$months = 'มิ.ย.';
}elseif($months == 7 ){
	$months = 'ก.ค.';
}elseif($months == 8 ){
	$months = 'ส.ค.';
}elseif($months == 9 ){
	$months = 'ก.ย.';
}elseif($months == 10 ){
	$months = 'ต.ค.';
}elseif($months == 11 ){
	$months = 'พ.ย.';
}else{
	$months = 'ธ.ค.';
}
$days = intval(date('d'));
$dates = $days.' '.$months.' '.$years;

$title = "ค้นหาผังเมือง";
?>
<div style="display:inline;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
	<div id="title_page" class="col-12">
		<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"><?php echo $title;?></h2>
		<h3 class="hidden-md hidden-lg GHfont-mini unprintable" style="font-family: Kanit;text-align:center"><?php echo $title;	?></h3>
		<div class="row" id="info_bar">
			<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_valuex">จำนวน  ผัง </h3>
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
				<div>ส่วนผังเมืองรวมที่รับผิดชอบ :  </div>
				<select id="aff_id" onchange="$('#pvn_name').val('');$('#cp_name').val('');searchAFF()">
					<option value="" selected>เลือกส่วนผังเมืองรวม</option>
					<option value="1">สำนักผังเมืองรวมส่วนที่ 1</option>
					<option value="2">สำนักผังเมืองรวมส่วนที่ 2</option>
					<option value="3">สำนักผังเมืองรวมส่วนที่ 3</option>
					<option value="4">สำนักผังเมืองรวมส่วนที่ 4</option>
				</select>
			</div>				
			<div class="search-row">
				<div style="">จังหวัด :  </div>
				<select id="pvn_name" onchange="$('#aff_id').val('');$('#cp_name').val('');searchPVN()">
					<option value="" selected>เลือกจังหวัด</option>
					<?php 
					$sql = "SELECT * FROM ref_province ORDER BY pvn_name ASC";
					$result = selectSql($sql);
					foreach($result as $row){
						echo '<option value="'.$row['pvn_name'].'">'.$row['pvn_name'].'</option>';
					}
					?>
				</select>
			</div>
			<div class="search-row">
				<div>ชื่อผัง :  </div>
				<input id="cp_name" onkeyup="$('#aff_id').val('');$('#pvn_name').val('');checkInputText(this);searchCP()" placeholder="ระบุชื่อผังเมือง . . . . . . .">
				
			</div>
			<div class="search-note" style="">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น </div>
		</div>
	</div>
	<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px">
		
	</div>
	
<div class="modal fade" id="modal-note" style="">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius:10px;pointer-events: auto;">
			<div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
				<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="font-family:Kanit;color:black">หมายเหตุรวม</h4>
			</div>
			<div class="modal-body">
				<form id="note_area">
				</form>
			</div>
			<div  class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>			
			</div>
		</div>
	</div>
</div>
	
	
</div>
<script>
	<?php 
	if($_SESSION['aff_id']!=''){
			echo '$("#aff_id").val("'.$_SESSION['aff_id'].'");';
			echo 'searchCityplan();';
	}
	elseif($_SESSION['pvn_name']!=''){
			echo '$("#pvn_name").val("'.$_SESSION['pvn_name'].'");';
			echo 'searchCityplan();';
	}
	elseif($_SESSION['cp_name']!=''){
			echo '$("#cp_name").val("'.$_SESSION['cp_name'].'");';
			echo 'searchCityplan();';
	}
	
	
	
	?>
	//$('#subshow').hide();
	$('#search_valuex').hide();
	/*function searchCityplan(){}*/
	
	//loadAjax('test');
	
	function XopenStep(x){
		//var page = $('#select_table').val();
		$.post('report/table_step_noteY.php',{
			report_page : $('#select_table').val(),
			cp_no : x,
			back:'search_cityplan',
			report_page : $('#select_table').val()
		},function(data){
			$('#mainshow').html(data);
		});
	}
	
	function searchAFF(){
		if($('#aff_id').val()!=''){
			searchCityplan();
		}else{
			$("#subshow").html('');
		}
	}
	function searchPVN(){
		if($('#pvn_name').val()!=''){
			searchCityplan();
		}else{
			$("#subshow").html('');
		}
	}
	function searchCP(){
		if($('#cp_name').val()!=''){
			searchCityplan()
		}else{
			$("#subshow").html('');
		}
	}
	
	
	function searchCityplan(){	
		$('#subshow').html('<img src="img/loading3.gif" class="center">');	
		$.ajax({
			url : 'report/tree-chart-pro_cityplan.php',
			async : true,
			type: "POST",
			data: {
				typeX : 'cityplan',
                aff_id : $('#aff_id').val(),	
				pvn_name : $('#pvn_name').val(),
				cp_name: $('#cp_name').val()
            },
			success: function(data,status , xhr){
				//alert('Loading : '+status);
				$("#subshow").html(data).show();
			},
			error: function(xhr,status,error){
				alert('Loadings : '+status);
				$("#subshow").html('').hide();
			}
		});
	}
	function readNoter(cp_no){
		$.post('app/manageX.php',{
			event : 'r_note',
			cp_no : cp_no
		},function(data){
			$('#note_area').html(data);
			$('#modal-note').modal('show');
		});
	}
</script>
