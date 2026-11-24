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

	if($_POST['typeX']=='' OR $_POST['typeX']==' ' OR $_POST['typeX']==Null){
		$_POST['typeX'] = $_SESSION['typeX'];
	}
	if($_POST['typeX']=="cityplan"){
		$title = "ผังเมืองรวมเมือง/ชุมชน";
	}
	elseif($_POST['typeX']=="notice"){
		$title = "ผังเมืองรวมเมือง/ชุมชนผังประกาศใช้บังคับ";
	}
	elseif($_POST['typeX']=="new"){
		$title = "ผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่";
	}
	elseif($_POST['typeX']=="eec"){
		$title = "ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ EEC";
	}
	elseif($_POST['typeX']=="eva"){
		$title = "ประเมินผลผัง";
	}
	elseif($_POST['typeX']=="eva5"){
		$title = "ประเมินผล (ครบ 5  ปี)";
	}
	elseif($_POST['typeX']=="eva10"){
		$title = "ประเมินผล (ครบ 10 ปี)";
	}
	elseif($_POST['typeX']=="edit"){
		$title = "ปรับปรุงผัง";
	}
	elseif($_POST['typeX']=="bbf"){
		$title = "มีผลบังคับใช้";
	}
	elseif($_POST['typeX']=="exp"){
		$title = "สิ้นสุดใช้บังคับ";
	}
	elseif($_POST['typeX']=="editY"){
		$title = "ดำเนินการอยู่ในขั้นตอนการปรับปรุงผังฯ";
	}
	elseif($_POST['typeX']=="bbfY"){
		$title = "กฎกระทรวงผังเมืองรวมมีผลบังคับใช้";
	}
	elseif($_POST['typeX']=="bbfY-1"){
		$title = "กฎกระทรวงผังเมืองรวมมีผลบังคับใช้<br>(ผังของกรมฯ)";
	}
	elseif($_POST['typeX']=="bbfY-2"){
		$title = "กฎกระทรวงผังเมืองรวมมีผลบังคับใช้<br>(ผังของอปท.)";
	}
	elseif($_POST['typeX']=="expY"){
		$title = "กฎกระทรวงผังเมืองรวมสิ้นสุดใช้บังคับ";
	}
	elseif($_POST['typeX']=="expY-1"){
		$title = "กฎกระทรวงผังเมืองรวมสิ้นสุดใช้บังคับ<br>(ผังของกรมฯ)";
	}
	elseif($_POST['typeX']=="expY-2"){
		$title = "กฎกระทรวงผังเมืองรวมสิ้นสุดใช้บังคับ<br>(ผังของอปท.)";
	}
	elseif($_POST['typeX']=="newY-1"){
		$title = "ผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่<br>(ผังของกรมฯ)";
	}
	elseif($_POST['typeX']=="newY-2"){
		$title = "ผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่<br>(ผังของอปท.)";
	}
	elseif($_POST['typeX']=="new_eec"){
		$title = "ดำเนินการอยู่ในขั้นตอนการวางและจัดทำ";
	}
	elseif($_POST['typeX']=="eecY"){
		$title = "ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ ECC";
	}
	elseif($_POST['typeX']=="eecY-1"){
		$title = "ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ ECC<br>(ผังของกรมฯ)";
	}
	elseif($_POST['typeX']=="eecY-2"){
		$title = "ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ ECC<br>(ผังของอปท.)";
	}else{
		
	}
	$_SESSION['typeX'] = $_POST['typeX'];
	
	
	





?>
<style>
@media print{
	@page{
		size:A4 landscape;
		margin:10mm 4mm;
	}
	
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
	
	#date_top{
		position:fixed;
		top:0;
		right:0;
		color:red!important;
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
				<button id="btn_goback" onclick="goto('a6m');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<!--
			<div id="div-select" class="col-xs-7 col-md-6">
				<select id="aff_ids" onchange="searchCP();" style="margin-top:6px;height:34px;width:100%;border-radius:4px">
					<option value="">เลือกส่วนผังเมืองรวม</option>
					
				</select>
			</div>-->
		</div>	
		<!--
		<div class="row unprintable">
			<div id="div-search_pvn" class="col-xs-6 col-md-6">
				<input id="pvn_names" onkeyup="checkInputText(this);" onchange="searchType('<?php //echo $_POST['typeX'];?>');" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยจังหวัด">
			</div>
			<div id="div-search_name" class="col-xs-6 col-md-6">
				<input id="cp_names" onkeyup="checkInputText(this);" onchange="searchType('<?php //echo $_POST['typeX'];?>');" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยชื่อผัง">
			</div>
		</div>
		-->
		
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
		
		<div class="unprintable row search-frame">
			<text class="search-title">ค้นหาข้อมูล</text>
			<div class="search-content-area">
				<div class="search-row">
					<div>ส่วนผังเมืองรวมที่รับผิดชอบ :  </div>
					<select id="aff_ids" onchange="$('#pvn_names').val('');$('#cp_names').val('');searchType('<?php echo $_POST['typeX']; ?>');">
						<option value="" selected>เลือกส่วนผังเมืองรวม</option>
						<option value="1">สำนักผังเมืองรวมส่วนที่ 1</option>
						<option value="2">สำนักผังเมืองรวมส่วนที่ 2</option>
						<option value="3">สำนักผังเมืองรวมส่วนที่ 3</option>
						<option value="4">สำนักผังเมืองรวมส่วนที่ 4</option>
					</select>
				</div>				
				<div class="search-row">
					<div style="">จังหวัด :  </div>
					<select id="pvn_names" onchange="$('#aff_ids').val('');$('#cp_names').val('');searchType('<?php echo $_POST['typeX']; ?>');">
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
					<input id="cp_names" onkeyup="$('#aff_ids').val('');$('#pvn_names').val('');checkInputText(this);searchType('<?php echo $_POST['typeX']; ?>');" placeholder="ระบุชื่อผังเมือง . . . . . . .">
					
				</div>
				<div class="search-note" style="">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น </div>
			</div>
		</div>
		<div id="cityplanshow" class="" style="border-color:transparent;">
			<img src="img/loading3.gif" class="center">
		</div>
</div>


<!--
<div class="modal fade" id="modal-note">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มหมายเหตุ</h4>
              </div>
              <div class="modal-body">
				<form>	
				<div class="form-group" id="note_step_show">
					<span id="note_step"></span>	
				</div>
				
				<div class="form-group" id="note_step_1_show">
					<span id="note_step_1"></span>	
				</div>
				
				<div class="form-group" id="show_note_remark"></div>
				<?php
				/*<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>*/
				?>
				<input type="hidden" id="note_id" value="">
				<input type="hidden" id="note_cp" value="">
				<input type="hidden" name="note_id" class="form-control" id="note_id" value="" >
                <input type="hidden" id="note_uper" value="<?php //echo $_SESSION['intra_czid']; ?>">				
                <input type="hidden" id="note_event" value="">
				<input type="hidden" id="note_step_hidden" value="">
				<input type="hidden" id="note_step_1_hidden" value="">
				</form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="Savenote();">บันทึก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="Delnote();">ลบหมายเหตุ</button>
              </div>
            </div>
          </div>

    </div>
-->

<div class="modal fade" id="modal-consider">
  <div class="modal-dialog">
	<div class="modal-content" style="border-radius:8px">
	  <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
		<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" style="font-family:Kanit;color:black">ระบุเหตุผลผังพิจาณาดำเนินการ</h4>
	  </div>
	  <div class="modal-body">
		<form>	

		<label for="consider">เหตุผลผังพิจาณาดำเนินการ</label>
		<select id="consider" class="form-control">
			<?php 
				$sql = "SELECT * FROM ref_consider WHERE consider_status = 1 ";
				$result = selectSql($sql);
				$i = count($result);
				if($i == 0){
					echo '<option value="0" selected>ไม่ใช่</option>';
					echo '<option value="1">ใช่</option>';
				}else{
					echo '<option value="0" selected>ไม่ระบุ</option>';
					foreach($result as $row){
						echo '<option value="'.$row['consider_id'].'">'.$row["consider_name"].'</option>';	
					}
				}						
			?>
		</select>
		<input type="hidden" id="consider_cp_no" value="">
		<input type="hidden" id="event" value="">				
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
		<button id="saveConsider" type="button" class="btn btn-success pull-right" onclick="saveConsider();">บันทึก</button>
	  </div>
	</div>
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

<script>
	<?php 
	if($_SESSION['cp_name']!='' OR $_SESSION['pvn_name']!=''){
		if($_SESSION['cp_name']!='' AND $_SESSION['pvn_name']!=''){
			echo "$('#cp_names').val('".$_SESSION['cp_name']."');";
			echo "$('#pvn_names').val('".$_SESSION['pvn_name']."');";
		}elseif($_SESSION['cp_name']!=''){
			echo "$('#cp_names').val('".$_SESSION['cp_name']."');";
		}elseif($_SESSION['pvn_name']!=''){
			echo "$('#pvn_names').val('".$_SESSION['pvn_name']."');";
		}
	}
	?>
	//$('#aff_ids').hide();
	//searchCP();
	function searchCP(){
		$('#cityplanshow').html('<img src="img/loading3.gif" class="center">');
		$.post('report/tree-chart-pro_cityplan.php',{
			type : $('#type').val(),
			aff_id :  $('#aff_ids').val(),
			pvn_name : $('#pvn_names').val(),
			cp_name : $('#cp_names').val()
		},function(data){	
			$('#cityplanshow').html(data);
			$('#cp_name').val('');
			$('#pvn_name').val('');
			$('#aff_id').val('');
		});
	}
	searchType("<?php echo $_POST['typeX']; ?>");
	function searchType(type){
		//alert('123');
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
	}
	
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
	
	function statusCityplan(color,step,cp){
		$('#showTable').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
		$.post('statusCityplan.php',{
			color: color,
			step:step,
			cp:cp,
			report : 'tree'
		},function(data){
			$('#showTable').html(data);
		});
		
	}
	function statusLating(cp){
		$('#showTable').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
		$.ajax({
			url : 'statusCityplan.php',
			async : true,
			type : 'POST',
			data : {
				cp : cp,
				report : 'tree',
				lating : 'true'
				
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
	

	
	
	
	
		
	
	
	
	
	
	
	
</script>