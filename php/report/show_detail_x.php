<?php 
include '../js/function_db.php';
session_name('tutorial');
 session_start();
$park = ['ภาคเหนือ','ภาคตะวันออกเฉียงเหนือ','ภาคกลาง','ภาคตะวันออก','ภาคใต้'];
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

	if($_POST['sub_type']=='' OR $_POST['sub_type']==' ' OR $_POST['sub_type']==Null){
		$_POST['sub_type'] = $_SESSION['sub_type'];
	}
	if($_POST['sub_type']=="park"){
		$title = $park[$_POST['park']-1];
		$sub_type = 'park';
		$park = $_POST['park'];
	}
	elseif($_POST['sub_type']=="keth"){
		$title = "เขตรวจราชการที่ ".$_POST['keth'];
		$sub_type = 'keth';
		$keth = $_POST['keth'];
	}
	elseif($_POST['sub_type']=='province'){
		$sql = "SELECT pvn_id, pvn_name, pvn_park, pvn_keth FROM ref_province WHERE pvn_id = '".$_POST['pvn']."'";
		$result = selectSql($sql);
		$title = $result[0]['pvn_name'];
		$sub_type = 'province';
		$pvn_id = $_POST['pvn'];		
		$keth = $result[0]['pvn_keth'];
		$park = $result[0]['pvn_park'];
	}
	$_SESSION['sub_type'] = $_POST['sub_type'];
	
	
	




?>
<style>
@media print{
	/*
	.info-frame{
		border:transparent;
	}
	.info-title{
		border:transparent;
	}
	*/
	#title_page{
		margin-top:20pt!important;
	}
	#info_bar{
		margin-top:0px!important;
		margin-bottom:30px!important;
	}
	
	
	.printx > tbody> tr > td{
		font-size:11px!important;
		padding:2.25px!important;
	}
	.printx > thead> tr > td{
		font-size:12.5px!important;
		padding:3px!important;
	}
	@page{
		size:A4 landscape;
		margin:10mm 4mm;
	}
}
.value_white ,.value_green ,.value_yellow ,.value_orange ,.value_red,.value_white_2 ,.value_green_2 ,.value_yellow_2 ,.value_orange_2 ,.value_red_2{
	text-align:center;
	width: 100%;
    position: relative;
    display: block;
    margin: 2.5px 0px;
	font-weight:600;
}
.value_green ,.value_red , .value_green_2 ,.value_red_2{
	color:white !important;
}
.value_white ,.value_yellow,.value_orange,.value_white_2 ,.value_yellow_2,.value_orange_2 {
	color : black !important;
}
</style>
<div style="display:inline;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
<div id="title_page" class="col-12">
	<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"><?php echo $title; ?></h2>
	<h3 class="hidden-md hidden-lg GHfont unprintable" style="font-family: Kanit;text-align:center"><?php echo $title; ?></h3>
	<div class="row" id="info_bar">
		<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="cp_value"></h3>
	</div>
	<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
</div>
<div class="container-fluid">
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-5 col-md-6">
				<button id="btn_goback" onclick="goback();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<!--
			<div id="div-select" class="col-xs-7 col-md-6">
				<select id="aff_ids" onchange="searchCP();" style="margin-top:6px;height:34px;width:100%;border-radius:4px">
					<option value="">เลือกส่วนผังเมืองรวม</option>
					
				</select>
			</div>-->
		</div>	
		
		<div id="status_table" style="margin-top:20px" class="row info-frame">
			<text class="info-title">สรุปจำนวนผังตามสถานะขั้นตอนดำเนินการ</text>
			<div>
				<div class="table-responsive" style="">
					<table class="table table-bordereds" style="margin-bottom: 8px;    border: transparent;">
						<thead>
							<tr>
								<th>สถานะ</th>
								<th>ขั้นตอนหลัก</th>
								<th>ขั้นตอนย่อย 1</th>
								<th>ขั้นตอนย่อย 2</th>
							</tr>
						</thead>
						<tbody>
							<tr id="status_white_step">
								<th>ไม่ระบุขั้นตอน</th>
								<td class="white_step">-</td>
								<td class="white_step_1">-</td>
								<td class="white_step_2">-</td>
							</tr>
							
							<tr id="status_x">
								<th style="background:white!important">ผังที่ระบุขั้นตอน</th>
								<td class="x_step">-</td>
								<td class="x_step_1">-</td>
								<td class="x_step_2">-</td>
							</tr>
						
							
							<tr id="status_green">
								<th>มีความก้าวหน้า</th>
								<td class="green">-</td>
								<td class="green_1">-</td>
								<td class="green_2">-</td>
							</tr>
							
							<tr id="status_yellow">
								<th>ล่าช้าเล็กน้อย</th>
								<td class="yellow">-</td>
								<td class="yellow_1">-</td>
								<td class="yellow_2">-</td>
							</tr>
							
							<tr id="status_orange">
								<th>ล่าช้าปานกลาง</th>
								<td class="orange">-</td>
								<td class="orange_1">-</td>
								<td class="orange_2">-</td>
							</tr>
							
							<tr id="status_red">
								<th>ล่าช้ามาก</th>
								<td class="red">-</td>
								<td class="red_1">-</td>
								<td class="red_2">-</td>
							</tr>
							
							<tr id="status_white_date">
								<th>ไม่ระบุวัน/เวลาดำเนินการ</th>
								<td class="white_date">-</td>
								<td class="white_date_1">-</td>
								<td class="white_date_2">-</td>
							</tr>
							
							<tr id="status_all">
								<th>รวม</th>
								<td class="all">-</td>
								<td class="all_1">-</td>
								<td class="all_2">-</td>
							</tr>
							
							<tr style="border:transparent;height:14px;background:transparent">
								<td style="padding:0;    border: transparent;" colspan="4"></td>
							</tr>
							<tr id="status_external">
								<th>เสนอหน่วยที่เกี่ยวข้อง</th>
								<td class="external">-</td>
								<td class="external_1">-</td>
								<td class="external_2">-</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
				<text class="search-note">หมายเหตุ : คลิก ที่จำนวนเหมายเลขเพื่อแสดงรายการผัง</text>
		</div>	
		<div id="lating-bar" style="margin:9px 0 -8px;display:flex;justify-content: space-between;align-items: flex-end;">
			<div style="display:flex;margin:8px 0 0">
				<span style="margin:0 4px;font-size:16px;">
					<a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการดำเนินการในขั้นตอนถัดไป">
						<i class="fa fa-exclamation"></i>
					</a>
				</span>
				<p style="font-size:14px;margin:0">ผังที่ดำเนินการขั้นตอนปัจจุบันเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป จำนวน <b id="lating-value"></b> ผัง ในจำนวน <b id="lating-all"></b> ผัง  (◄ คลิกที่หมายเลขจำนวนผัง)</p>
			</div>
		</div>
		
			<?php 
		if($_POST['sub_type']=='park' OR $_POST['sub_type']=='keth' ){ ?>
		<div class="unprintable row search-frame">
			<text class="search-title">ค้นหาข้อมูล</text>
			<div class="search-content-area">
				<div class="search-row">
					<div>ส่วนผังเมืองรวมที่รับผิดชอบ :  </div>
					<select id="aff_ids" onchange="$('#pvn_names').val('');$('#cp_names').val('');searchType('aff');">
						<option value="" selected>เลือกส่วนผังเมืองรวม</option>
						<?php 
						if($_POST['sub_type']=='park'){
							$sql = "SELECT * FROM ref_province WHERE pvn_park = '".$_POST['park']."' GROUP BY pvn_affiliation ";
						}else{
							$sql = "SELECT * FROM ref_province WHERE pvn_keth = '".$_POST['keth']."' GROUP BY pvn_affiliation ";
						}
						$result = selectSql($sql);
						foreach($result as $row){
							echo '<option value="'.$row['pvn_affiliation'].'">สำนักผังเมืองรวมส่วนที่ '.$row['pvn_affiliation'].'</option>';
						}
						?>
					</select>
				</div>				
				<div class="search-row">
					<div style="">จังหวัด :  </div>
					<select id="pvn_names" onchange="$('#aff_ids').val('');$('#cp_names').val('');searchType('pvn');">
						<option value="" selected>เลือกจังหวัด</option>
						<?php 
						if($_POST['sub_type']=='park'){
							$sql = "SELECT * FROM ref_province WHERE pvn_park = '".$_POST['park']."' ORDER BY pvn_asc ASC";
						}else{
							$sql = "SELECT * FROM ref_province WHERE pvn_keth = '".$_POST['keth']."' ORDER BY pvn_asc ASC";
						}
						//echo $sql;
						//$sql = "SELECT * FROM ref_province ORDER BY pvn_name ASC";
						$result = selectSql($sql);
						foreach($result as $row){
							echo '<option value="'.$row['pvn_name'].'">'.$row['pvn_name'].'</option>';
						}
						?>
					</select>
				</div>
				<div class="search-row">
					<div>ชื่อผัง :  </div>
					<input id="cp_names" onkeyup="$('#aff_ids').val('');$('#pvn_names').val('');checkInputText(this);searchType('cp');" placeholder="ระบุชื่อผังเมือง . . . . . . .">
					
				</div>
				<div class="search-note" style="">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น </div>
			</div>
			
		</div>
		<?php } ?>
		
		<input type="hidden" id="status_type">
		<div id="cityplanshow" class="" style="border-color:transparent;">
			<img src="img/loading3.gif" class="center">
		</div>
</div>


<div class="modal fade" id="modal-note-consider">
  <div class="modal-dialog">
	<div class="modal-content" style="border-radius:8px">
	  <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
		<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 id="title-note-consider" class="modal-title" style="font-family:Kanit;color:black"></h4>
	  </div>
	  <div class="modal-body">
		<form>	

		<label for="consider">เหตุผลผังพิจาณาดำเนินการ</label>
		<div id="note-consider-content">
		</div>
	
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
	  </div>
	</div>
  </div>
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
<?php //print_r($_POST); ?>

<?php 
if($_POST['status']=='TRUE'){
	$sub_script =  '$("#s'.$_POST['status_type'].'").click();';
	//echo $sub_script;
}
if($_POST['sub_status']=='TRUE'){
	$sub_script =  '$("#'.$_POST['status_type'].'").click();';
	//echo $sub_script;
}

if($_POST['sub_status']=='TRUE'){
	$sub_script = '$("#'.$_POST['status_type'].'").click();';
}
?>
<script>

<?php 

if($sub_type=='park' OR $sub_type=='keth'){
	echo 'loadData();';
}else{
	echo 'loadData2();';
}


?>
function loadData(x){
	$('#aff_ids').val('');
	$('#pvn_names').val('');
	$('#cp_names').val('');
	var sub_script = '<?= $sub_script;?>'
	if(x=='false'){
		sub_script = '';
		$('#status_type').val('');
	}
	$('#cityplanshow').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
	$.ajax({
		url : 'report/show_detail_x_pro.php',
		async : true,
		type : 'POST',
		data : {
			sub_type : '<?= $sub_type;?>',
			park : '<?= $park;?>',
			keth : '<?= $keth;?>',
			sub_script : sub_script
		},
		success : function(data, status, xhr){
			//alert('Loading : '+status+'\n');
			$('#cityplanshow').html(data);
		},
		error : function(xhr, status, data){
			alert('Loading : '+status+'\n'+data);
		}
	});
}
function loadData2(x){
	$('aff_ids').val('');
	$('pvn_names').val('');
	$('cp_names').val('');
	var sub_script = '<?= $sub_script;?>'
	if(x=='false'){
		sub_script = '';
		$('#status_type').val('');
	}
	$('#cityplanshow').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
	$.ajax({
		url : 'report/show_detail_x_pro.php',
		async : true,
		type : 'POST',
		data : {
			sub_type : '<?= $sub_type;?>',
			pvn : '<?= $pvn_id;?>',
			sub_script : sub_script
		},
		success : function(data, status, xhr){
			//alert('Loading : '+status+'\n');
			$('#cityplanshow').html(data);
		},
		error : function(xhr, status, data){
			alert('Loading : '+status+'\n'+data);
		}
	});
}
	
	
	function statusCityplan(color,step,cp){
		$('#cityplanshow').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
		var st = '';
		if(step==0){
			st = 'r';
		}else if(step==1){
			st = 's';
		}else if(step==2){
			st = 't';
		}
		
		var cl = '';
		if(color==1){
			cl = 1;
		}else if(color==3){
			cl = 2;
		}else if(color==4){
			cl = 3;
		}else if(color==5){
			cl = 4;
		}else if(color==6){
			cl = 5;
		}else if(color==7){
			cl = 6;
		}else if(color==9){
			cl = 8;
		}
		
		$('#status_type').val(st+cl);
		//alert($(this).attr('id'));
		$.post('statusCityplan.php',{
			color: color,
			step:step,
			cp:cp,
			report : '<?= $sub_type;?>',
		},function(data){
			$('#cityplanshow').html(data);
		});
	}
	
	function statusLating(cp){
		$('#showTable').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
		$('#status_type').val('s7');
		$.ajax({
			url : 'statusCityplan.php',
			async : true,
			type : 'POST',
			data : {
				cp : cp,
				report : 'tree'
			},
			success : function(data, status, xhr){
				//alert('Loading : '+status);
				$('#showTable').html(data)
			},
			error : function(xhr, status, error){
				alert('Loading : '+status+'\n'+error);
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
	/*
	function searchType(type){
		$('#cityplanshow').html('<img src="img/loading3.gif" class="center">');
		$.post('report/tree-chart-pro_cityplan.php',{
			typeX : type,
			type : $('#type').val(),
			aff_id :  $('#aff_ids').val(),
			pvn_name : $('#pvn_names').val(),
			cp_name : $('#cp_names').val()
		},function(data){	
			$('#cityplanshow').html(data);
			//$('#cp_name').val('');
			//$('#pvn_name').val('');
			//$('#aff_id').val('');
		});
	
	}*/
	function searchType(stype){
		if($('#aff_ids').val()<1 && $('#pvn_names').val()<1 && $('#cp_names').val()==''){
			loadData();
		}else{
			if(stype=='aff'){
				var datax = $('#aff_ids').val();
			}else if(stype=='pvn'){
				var datax = $('#pvn_names').val();
			}else{
				var datax = $('#cp_names').val();			
			}
			$.ajax({
				url : 'report/show_detail_x_pro.php',
				async : true,
				type : 'POST',
				data : {
					sub_type : '<?= $sub_type;?>',
					park : '<?= $park;?>',
					keth : '<?= $keth;?>',
					stype : stype,
					data : datax
				},success : function(data , status , xhr){
					$('#cityplanshow').html(data)
				},error : function(xhr, status, data){
					alert('Loading : '+status+'\n'+error);
				}
			});
		}
	}
	
	
	function readConsider(x){
		$.post('app/manage.php',{
			event:'ru_note-consider',
			cp_no: x
		},function(data){
			$('#note-consider-content').html(data);
			$('#modal-note-consider').modal('show');
		});
	}
	/*
	function XopenStep(x){
		//var page = $('#select_table').val();
		$.post('report/table_step_noteY.php',{
			report_page : $('#select_table').val(),
			cp_no : x,
			back:'tree-chart-cityplan',
			report_page : $('#select_table').val()
		},function(data){
			$('#mainshow').html(data);
		});
	}*/
	
	function XopenStep(x){
		var status = 'FALSE';
		if($('#status_type').val()!='' && $('#status_type').val()!=' ' & $('#status_type').val()!=null){
			status = "TRUE";
		}
		$.ajax({
			url : 'report/table_step_noteY.php',
			async : true,
			type : 'POST',
			data : {
				cp_no : x, 
				back: 'show_detail_x',
				sub_type : '<?= $sub_type;?>',
				park : '<?= $park;?>',
				keth : '<?= $keth;?>',
				pvn : '<?= $pvn_id;?>',
				sub_status : status,
				status_type : $('#status_type').val(),
				sub_page : '<?= $_POST["sub_page"];?>',
				report_page : $('#select_table').val()
			},
			success :function(data, status, xhr){
				$('#mainshow').html(data);
			},
			error :function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	function goback(){
		<?php 
		if($_POST['sub_page']=='TRUE'){ 
		?>
			$.ajax({
				url : 'app/show_page_park-keth.php',
				async : true,
				type : 'POST',
				data : {
					sub_type : '<?= $sub_type;?>',
					park : '<?= $park;?>',
					keth : '<?= $keth;?>'
				},
				success : function(data, status, xhr){
					$('#mainshow').html(data);
				},
				error : function(xhr, status, data){
					alert('Loading : '+status+'\n'+data);
				}
			}); 
		<?php }
		else{
			if($sub_type=='park'){
				echo 'goto("s4");';
				
			}
			elseif($sub_type=='keth'){
				echo 'goto("s5");';
			}
			else{
				if($_POST['be_type']=='park'){
					echo 'goto("s4");';
				}else{
					echo 'goto("s5");';
				}
			}
		}
		?>
	}
	
</script>
<?php 
//print_r($_POST);
?>