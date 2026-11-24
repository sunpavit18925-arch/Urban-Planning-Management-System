<style>
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
@media print{
	@page{
		size:A3 portrait;
		margin:10mm 4mm;
	}
	table,thead,tbody,tr,td{
		border:solid black 1px !important;
	}
	
	#title_page{
		margin-top:20pt!important;
	}
	#info_bar{
		margin-top:0px!important;
		
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
	.unprintable{
		display:none;
	}
}


</style>
<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  //echo 	$_COOKIE['origin'];
 
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
	
	$title = 'นำออกข้อมูล';

?>	
	<div style="display:inline;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
	<div id="title_page" class="col-12">
		<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center">
			<?php echo $title;
				/*if($_POST['step_id']==0){
					echo "ยังไม่ได้กำหนดขั้นตอนดำเนินการ";	
				}
				else{
					echo 'ขั้นตอนที่ ',$result[0]['step_id'],' ',$result[0]['step_name']; 
				}*/
			?>
		</h2>
		<h3 class="hidden-md hidden-lg GHfont-mini unprintable" style="font-family: Kanit;text-align:center"><?php echo $title;	?></h3>
	</div>	
	<br>
	<div class="container-fluid">
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_backy" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>	
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_iexport" onclick="setExport();" class="pull-right btn btn-primary btn-md" style="margin-top:6px;">ระบุข้อมูล</button>
			</div>	
		</div>

		
		<div id="subshow" class="" style="border-color:transparent;margin-top:8px">
			<!--<img src="img/loading3.gif" class="center">-->
		</div>
		
		

	

		
		<div class="modal fade" id="modal-export">
          <div class="modal-dialog">
            <div class="modal-content" style="border-radius:8px">
              <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 id="title-export" class="modal-title" style="font-family:Kanit;color:black">Export Data</h4>
              </div>
              <div class="modal-body">
				<form>	
					<?php 
					$all_dc = 9;
					for($n = 1 ; $n <= $all_dc ; $n++){ ?>
					
					<div id="div_dc_<?=$n?>"class="form-group dc">
						<label for="dc_<?=$n?>">ข้อมูลที่แสดงในคอลัมน์ที่ <?=$n?></label>
						<select id="dc_<?=$n?>" class="form-control" onchange="changeDC(<?=$n?>)">
							<option value="">เลือกข้อมูล</option>
							<option value="cp_id">1. รหัสผังเมือง</option>
							<option value="cp_name">2. ชื่อผังเมือง</option>
							<option value="pvn_name">3. จังหวัด</option>
							<option value="status_name">4. สถานะ</option>
							<option value="stage_name">5. ขั้นตอน</option>
							<option value="eff_name">6. มีผลบังคับใช้</option>
							<option value="csd_step_fst">7. ขั้นตอนดำเนินการ (หลัก)</option>
							<option value="csd_step_snd">8. ขั้นตอนดำเนินการ (ย่อย 1)</option>
							<option value="csd_step_trd">9. ขั้นตอนดำเนินการ (ย่อย 2)</option>
						</select>
					</div>
					
					
					<?php } ?>
				
					
					
					<div id="div_set_as_default">
						<label for="set_as_default">ใช้เป็นค่าเริ่มตั้งต้น</label>
						<input id="set_as_default" type="checkbox">
					</div>
					
					
					<div id="export-content"></div>
					<input type="hidden" id="dc">
				</form>
				
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button id="btn_confirm" type="button" class="btn btn-primary pull-right" onclick="showExport()">ยืนยัน</button>
              </div>
            </div>
          </div>
		</div>
	</div>
	<script type="text/javascript" src="js/export.js"></script>

	
	<script>
		function setExport(){
			$('#modal-export').modal('show');
			$('#export-content').html(' ');
			$('#set_as_default').prop('checked',false);
			$('.dc').hide();
			$('.dc > select').val('');
			
			def_dc(1);
		}
		
		function def_dc(x){
				$('#div_dc_'+x).show();
				$('#dc_'+x).val('');
				$('#dc').val(x-1);
				checkDC();
		}
		
		function showExport(){
			let x = '';
			let y = $('#set_as_default').prop('checked');
			for(let i = 1 ; i <= $('#dc').val() ; i++){
				if(i == 1){
					x = $('#dc_'+i).val();
				}else{
					x = x+','+$('#dc_'+i).val();
				}
			}
			$.ajax({
				url : 'app/export_page_pro.php',
				async : true,
				type : 'POST',	
				data : {
					event : 'set_data',
					data_post : x,
					set_as_default : y
				},
				success : function(data){
					$('#modal-export').modal('hide');
					$('#subshow').html(data);
				},
				error : function(xhr, status, data){
					alert(status);
				}
			});
		}
		
		function changeDC(dc){
			if($('#dc_'+dc).val()!=''){
				$('#div_dc_'+(dc+1)).show();
				$('#dc').val(dc);
				for(let i = dc+1 ; i <= <?=$n?> ; i++){
					$('#div_dc_'+(i+1)).hide();
					$('#dc_'+i+' option').show();
					for(let j = 1 ; j <= dc ; j++){
						$('#dc_'+i+' option[value="'+$('#dc_'+j).val()+'"]').hide();
					}
					$('#dc_'+i).val('');
				}
			}else{
				$('#dc').val((dc-1));
				for(let n = dc+1 ; n <= <?=$n?> ; n++){
					$('#div_dc_'+(n)).hide();
					$('#dc_'+n).val('');
					

					
			
					
				}
			}
			checkDC();
		}
		function checkDC(){
			if($('#dc').val()>=1){
				$('#btn_confirm').removeAttr('disabled');
				$('#btn_confirm').attr('onclick','showExport()');
			}else{
				$('#btn_confirm').attr('disabled','true');
				$('#btn_confirm').removeAttr('onclick','showExport()');
			}
		}
	
	</script>
	<?php 
	
	if($_COOKIE['origin']!='' AND $_COOKIE['origin']!=' ' AND $_COOKIE['origin']!=null){?> 
		<script>
			$.ajax({
				url : 'app/export_page_pro.php',
				async : true,
				type : 'POST',
				data : {
					event : 'set_data',
					data_post : '<?=$_COOKIE["origin"];?>',
					set_as_default : 'true',
				},
				success : function(data){
					$('#subshow').html(data);
				},
				error : function(xhr, status, data){
					alert(status);
				}
			});
		</script><?php
	}else{ ?>
		<script>
			setExport();
		</script><?php 
	}
	
	
	?>