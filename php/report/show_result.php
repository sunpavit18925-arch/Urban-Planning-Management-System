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
		size:A4 landscape;
		margin:10mm 4mm;
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
}
</style>
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
	
	



	
	if($_POST['aff_id']!=NULL){
		$title = "ส่วนผังเมืองรวมที่ "." ".$_POST['aff_id'];
	}
	elseif($_POST['step_id']!=NULL){		
		if($_POST['step_id']==0){
			//$title = "ยังไม่ได้กำหนดขั้นตอนดำเนินการ";	
			$title = "อื่นๆ";
		}else{
			$sql = "SELECT step_id, step_name FROM ref_up_fst_step WHERE step_id = '".$_POST['step_id']."'";
			$result = selectSql($sql);			
			$title = 'ขั้นตอนที่ '.$result[0]['step_id'].'<br>'.$result[0]['step_name'];
		}
	}
	elseif($_POST['status_id']!=NULL){
		//echo $_POST['status_id']; 
		if($_POST['status_id']==0){
			$title = "ยังไม่ได้กำหนดสถานะ";	
		}
		elseif($_POST['status_id']==2 AND $_POST['eff_id']==1){
			$title = 'สถานะผัง : ผังที่มีผลบังคับใช้';
		}
		elseif($_POST['status_id']==2 AND $_POST['eff_id']==2){
			$title = 'สถานะผัง : ผังสิ้นสุดบังคับใช้';	
		}
		elseif($_POST['status_id']==1 AND $_POST['eff_id']==0){
			$title = 'สถานะผัง : ผังเปิดพื้นที่ใหม่';	
		}
	}
	elseif($_POST['stage_id']!=NULL){
		if($_POST['stage_id']==0){
			$title = "ยังไม่ได้กำหนดขั้นตอน";	
		}
		elseif($_POST['stage_id']==1){
			$title = "ขั้นตอน : การวางและจัดทำผัง";
		}
		elseif($_POST['stage_id']==2 OR $_POST['step_id']==4){
			$title = "ขั้นตอน : การประเมินผลผัง";
		}
		elseif($_POST['stage_id']==3){
			$title = "ขั้นตอน : การปรับปรุงผัง";
		}
	}
	elseif($_POST['eff_id']!=NULL){
		if($_POST['eff_id']==0){
			$title = "ยังไม่ได้กำหนดผลบังคับใช้";	
		}else{
			$sql = "SELECT eff_id, eff_name FROM ref_effective WHERE eff_id = '".$_POST['eff_id']."'";
			$result = selectSql($sql);			
			$title = $result[0]['eff_name'];
		}
	}
	elseif($_POST['own_id']!=NULL AND $_POST['opt_id']!=NULL){
		if($_POST['own_id']==1){
			$title = "กรมฯ";		
			$sql = "SELECT * FROM ref_operate_snd WHERe opt_snd_id =  '".$_POST['opt_id']."' ";
			$result = selectSql($sql);
			if($result[0]['opt_snd_id']>0 AND $result[0]['opt_snd_id'] <=5){
				$result[0]['opt_snd_name'] = $result[0]['opt_snd_name'].' ดำเนินการ';
			}
			$title = $title.'<br>('.$result[0]['opt_snd_name'].')';			
		}elseif($_POST['own_id']==2){
			$title = "อปท.";	
			$sql = "SELECT * FROM ref_operate_snd WHERE opt_snd_id = '".$_POST['opt_id']."'";
			$result = selectSql($sql);
			if($result[0]['opt_snd_id']>0 AND $result[0]['opt_snd_id']<=4){
				$result[0]['opt_snd_name'] =  "ขอกรมฯดำเนินการ"; 
			}
			if($result[0]['opt_snd_id']==5){
				$result[0]['opt_snd_name'] = $result[0]['opt_snd_name']." ดำเนินการ";
				
			}
			$title = $title."<br>(".$result[0]['opt_snd_name'].")";
		}
		
	}
	elseif($_POST['opt_id']!=NULL){
		if($_POST['opt_id']==1){
			$title = "กรมฯ";	
		}elseif($_POST['opt_id']==2){
			$title = "อปท.";
		}
	}
	if(!isset($title)){
		$title = $_SESSION['title'];
	}else{
		$_SESSION['title'] = $title;
	}


	if(isset($_POST['event'])){ $_SESSION['event'] = $_POST['event']; }
	if(isset($_POST['aff_id'])){ $_SESSION['aff_id'] = $_POST['aff_id']; }
	if(isset($_POST['type_id'])){ $_SESSION['type_id'] = $_POST['type_id']; }
	if(isset($_POST['step_id'])){ $_SESSION['step_id'] = $_POST['step_id']; }
	if(isset($_POST['status_id'])){ $_SESSION['status_id'] = $_POST['status_id']; }
	if(isset($_POST['stage_id'])){ $_SESSION['stage_id'] = $_POST['stage_id']; }
	if(isset($_POST['eff_id'])){ $_SESSION['eff_id'] = $_POST['eff_id']; }
	if(isset($_POST['opt_id'])){ $_SESSION['opt_id'] = $_POST['opt_id']; }
	if(isset($_POST['own_id'])){ $_SESSION['own_id'] = $_POST['own_id']; }


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
		<div class="row" id="info_bar">
			<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_value">จำนวน    . . . . .  ผัง </h3>
		</div>
		
		<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
	</div>	
	<br>
	<div class="container-fluid">
		<div class="row unprintable">
			<div id="div-btn" class="col-xs-5 col-md-6">
				<button id="btn_backy" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<!--
			<div id="div-select" class="col-xs-7 col-md-6">
				<select id="select-aff" onchange="searchtype();" style="margin-top:6px;height:34px;width:100%;border-radius:4px">
					<option value="">เลือกส่วนผังเมืองรวม</option>
				</select>
			</div>-->
			<!--
			<div id="div-search_pvn" class="col-xs-6 col-md-6">
				<input id="search-pvn-x" onkeyup="checkInputText(this);searchpvn();" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยจังหวัด">
			</div>
			<div id="div-search_name" class="col-xs-6 col-md-6">
				<input id="search-name-x" onkeyup="checkInputText(this);searchname();" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยชื่อผัง">
			</div>
			-->
		</div>
		
		<div hidden id="g-affiliation" class="printable hidden-xs hidden-sm"><br>
			<div id="G-aff" class="unprintable" style=";width:100%;">
				<div class="row" >
					<div id="g1" class="col-md-4 "></div>
					<div id="g2" class="col-md-4 "></div>
					<div id="g3" class="col-md-4 "></div>
				</div>
			</div>
		</div>
		
		
		
		
		<?php
		if(TRUE){ 
			if(($_SESSION['event']=='step' OR $_SESSION['event']=='step2') AND $_SESSION['step_id']=='0'){
				if($_SESSION['event']=='step'){
					$condx = " cp_stage = 1 AND cp_status = 1 ";
				}else{
					$condx = " cp_stage = 3 AND cp_status = 2 ";
				}?>
	
		<div id="N-barx" style="display:flex;justify-content: space-between;align-items: flex-end;margin:8px 0 -4px">
			<div class=>
			  <span style="margin-right:5px;cursor: default;" >ระบุเหตุผล  <a style="color:#697d8e"><i style="transform: scaleX(-1);" class="fa fa-comment"></i></a></span> <b>|</b>
			  <span style="margin-left:5px;cursor: default;" >ไม่ระบุเหตุผล  <a style="color:#fe4040"><i style="transform: scaleX(-1);" class="fa fa-comment"></i></a></span> 
			</div>
			<div style="margin-bottom:0px;/*display: flex;align-items: flex-end;justify-content: space-between;*/">
				<button id="returnSSR" class="btn btn-primary" style="font-family:Kanit;float:right;cursor:pointer;margin-bottom:4px;color:white;display:none" onclick="returnSSR()";>แสดง<text class="hidden-xs">จำนวนผัง</text>ทั้งหมด</button>
			</div>
		</div><?php 
			}
			else{ ?>
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
								<th >ไม่ระบุขั้นตอน</th>
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
								<th >รวม</th>
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
					<a style="color:red" title="ผังที่ดำเนินการเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป">
						<i class="fa fa-exclamation"></i>
					</a>
				</span>
				<p style="font-size:14px;margin:0">ผังที่ดำเนินการขั้นตอนปัจจุบันเสร็จสิ้น แต่ไม่มีการระบุการดำเนินการในขั้นตอนถัดไป จำนวน <b id="lating-value"></b> ผัง ในจำนวน <b id="lating-all"></b> ผัง (◄ คลิกที่หมายเลขจำนวนผัง)</p>
			</div>
		</div>

			
			
			<?php
			}
		}?>
		
		<div class="unprintable row search-frame">
			<text class="search-title">ค้นหาข้อมูล</text>
			<div class="search-content-area">
				<div class="search-row">
					<div>ส่วนผังเมืองรวมที่รับผิดชอบ :  </div>
					<select id="select-aff" onchange="searchtype();">
						<option value="" selected>เลือกส่วนผังเมืองรวม</option>
						<option value="1">สำนักผังเมืองรวมส่วนที่ 1</option>
						<option value="2">สำนักผังเมืองรวมส่วนที่ 2</option>
						<option value="3">สำนักผังเมืองรวมส่วนที่ 3</option>
						<option value="4">สำนักผังเมืองรวมส่วนที่ 4</option>
					</select>
				</div>				
				<div class="search-row">
					<div style="">จังหวัด :  </div>
					<select id="search-pvn" onchange="searchpvn();" >
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
					<input id="search-name" onkeyup="checkInputText(this);searchname();" placeholder="ระบุชื่อผังเมือง . . . . . . .">
					
				</div>
				<div class="search-note" style="">หมายเหตุ : สามารถเลือกสืบค้นได้เพียง 1 เงื่อนไขเท่านั้น </div>
			</div>
		</div>
		
		<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px">
			<img src="img/loading3.gif" class="center">
		</div>
		<?php if(($_SESSION['event']=='step' OR $_SESSION['event']=='step2') AND $_SESSION['step_id']=='0'){ ?>
			<div class="unprintable row" id="consider_group" style="margin-top:8px;min-width:280px;">
			<div class="col-xs-12 col-sm-12 col-md-6">
				<div class="table-responsive">
					<table id="consider_table" class="table table-bordereds">
						<thead>
							<tr>
								<td style="text-align:center;color:white">เหตุผลผังพิจารณาดำเนินการ</td>
								<td style="text-align:center;color:white">จำนวนผังเมือง</td>
							</tr>
						</thead>
						<tbody>
						<?php
							if($_SESSION['event']=='step'){
								$sql = "SELECT consider_id,  consider_name  FROM ref_consider WHERE consider_status = 1 AND (consider_type = 'A' OR consider_type = '0') ORDER BY consider_id ASC";
								//$sql = "SELECT consider_id,  consider_name  FROM ref_consider WHERE consider_status = 1 AND (consider_id = 3 OR consider_id = 4 OR consider_id = 5) ORDER BY consider_id ASC";
								//$sql = "SELECT consider_id,  consider_name  FROM ref_consider WHERE consider_status = 1 AND consider_id > 0 ORDER BY consider_id ASC";
							}else{
								$sql = "SELECT consider_id,  consider_name  FROM ref_consider WHERE consider_status = 1 AND (consider_type = 'B' OR consider_type = '0') ORDER BY consider_id ASC";
								//$sql = "SELECT consider_id,  consider_name  FROM ref_consider WHERE consider_status = 1 AND (consider_id = 2 OR consider_id = 5 OR consider_id = 6) ORDER BY consider_id ASC";
								//$sql = "SELECT consider_id,  consider_name  FROM ref_consider WHERE consider_status = 1 AND consider_id > 0 ORDER BY consider_id ASC";
							}
						//$sql = "SELECT consider_id,  consider_name  FROM ref_consider WHERE consider_status = 1 ORDER BY consider_id ASC";
						$result = selectSql($sql);
						$i = 1;
						foreach($result as $row){
							$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_consider = '".$row['consider_id']."' AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_step > 0 AND cp_step_1 > 0 AND cp_consider >0)) AND ".$condx;
							$result = selectSql($sql);
							?>
							<tr>
								<td><?= $i.'. '.$row['consider_name'];?></td>
								<td style="text-align:center">
									<?php if($result[0]['COUNT(*)']>0){ ?>
											<a style="cursor:pointer" onclick="considerType('<?= $_SESSION['event'];?>',<?=$row['consider_id'];?>)"><?= $result[0]['COUNT(*)'];?></a>
									<?php }else{ ?>
											<a style="cursor:default;color:black"></a> 
									<?php } ?>
								</td>
							</tr>
							<?php
							$i++;
						}
						$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_consider = 0 AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0)) AND ".$condx;
						$result = selectSql($sql);
						?>
							<tr>
								<td style="color:red"><?= $i.'. ไม่ระบุเหตุผล';?></td>
								<td style="text-align:center">
									<?php 
										//if(FALSE){
										if($result[0]['COUNT(*)']>0){?>
											<a style="cursor:pointer;color:red" onclick="considerType('<?= $_SESSION['event'];?>',0)"><?= $result[0]['COUNT(*)'];?></a> <?php
										}else{ ?>
											<a style="cursor:default;color:black">0</a> 
									<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="showTable2" class="unprintable" style="margin-left: -15px;">
					<div id="div-btn" class="col-xs-5 col-md-6">
						<button hidden id="btn_backz" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div id="gx">
				</div>
			</div>
			<!--<script>$('#colorbar').hide();</script>-->
		</div>
		<?php }else{ ?>
		<div id="showTable2" class="unprintable" style="margin-left: -15px;">
			<div id="div-btn" class="col-xs-5 col-md-6">
				<button hidden id="btn_backz" onclick="goBack();" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
		</div>
		<?php } ?>
		
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
	
		<div class="modal fade" id="modal-consider">
          <div class="modal-dialog">
            <div class="modal-content" style="border-radius:8px">
              <div class="modal-header" style="background-color:#8fdef4;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">ระบุเหตุผลผังพิจารณาดำเนินการ</h4>
              </div>
              <div class="modal-body">
				<form>	
	
				<label for="consider">เหตุผลผังพิจารณาดำเนินการ</label>
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
	
				<label for="consider">เหตุผลผังพิจารณาดำเนินการ</label>
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
	</div>
	<script>
	//console.log('<?php echo "event : ".$_POST["event"];?>');
	//console.log('<?php echo "step_id : ".$_POST["step_id"];?>');
	//console.log('<?php echo "own_id : ".$_POST["own_id"];?>');
	//console.log('<?php echo "opt_id : ".$_POST["opt_id"];?>');
	//console.log('<?php echo "step_id : ".$_POST["step_id"];?>');
	//console.log('<?php echo "step_id : ".$_POST["step_id"];?>');
	$('.myBtn2').show();
	<?php 
	if($_SESSION['report_cp']=='' AND $_SESSION['report_pvn']=='' AND $_SESSION['report_aff']==''){
		echo "searchtype(0);";
		//echo "-----";
	}else{
		if($_SESSION['report_pvn']!=""){
			echo "$('#search-pvn').val('".$_SESSION['report_pvn']."');searchpvn();";
			unset($_SESSION['report_pvn']);
		}
		elseif($_SESSION['report_cp']!=""){
			echo "$('#search-name').val('".$_SESSION['report_cp']."');searchname();";
			unset($_SESSION['report_cp']);
		}
		elseif($_SESSION['report_aff']>0){
			echo "$('#select-aff').val('".$_SESSION['report_aff']."');searchtype();";
			unset($_SESSION['report_aff']);
		}
	}
	?>
	//searchtype(0);
	$('#info_bar').hide();
		function goBack(){
			$('#mainshow').load('app/report.php');
		}
		function searchpvn(){
			//$('#subshow').html('<img src="img/loading3.gif" class="center">');
			$('#select-aff').val('');
			$('#search-name').val('');
			if($('#search-pvn').val()==null || $('#search-pvn').val()=='' || $('#search-pvn').val()==' ' || $('#search-pvn').val()=='  ' || $('#search-pvn').val()=='   ' || $('#search-pvn').val()=='    ' || $('#search-pvn').val()=='     ' || $('#search-pvn').val()=='      ' || $('#search-pvn').val()=='      '){
				$('#search-pvn').val(null);	
			}
			$.post('report/pro_result.php',{
				event : "<?php echo $_SESSION['event'];?>",
				province : $('#search-pvn').val(),
				aff_id : "<?php echo $_SESSION['aff_id'];?>",
				type_id : "<?php echo $_SESSION['type_id'];?>",
				step_id : "<?php echo $_SESSION['step_id'];?>",
				status_id : "<?php echo $_SESSION['status_id'];?>",
				stage_id : "<?php echo $_SESSION['stage_id'];?>",
				eff_id : "<?php echo $_SESSION['eff_id'];?>",
				opt_id : "<?php echo $_SESSION['opt_id'];?>",
				own_id : "<?php echo $_SESSION['own_id'];?>"
			},function(data){
				$('#subshow').html(data);
				if($('#search-pvn').val()==''){
					//$('#search_value').hide();
				}else{
					$('#search_value').show();
				}
			});
		}
		
		function searchname(){
			$('#select-aff').val('');
			$('#search-pvn').val('');
			if($('#search-name').val()=='' || $('#search-name').val() ==null || $('#search-name').val() ==" "){
				$('#search-name').val(null);
			}
			$.post('report/pro_result.php',{
				event : "<?php echo $_SESSION['event'];?>",
				name : $('#search-name').val(),
				aff_id : "<?php echo $_SESSION['aff_id'];?>",
				type_id : "<?php echo $_SESSION['type_id'];?>",
				step_id : "<?php echo $_SESSION['step_id'];?>",
				status_id : "<?php echo $_SESSION['status_id'];?>",
				stage_id : "<?php echo $_SESSION['stage_id'];?>",
				eff_id : "<?php echo $_SESSION['eff_id'];?>",
				opt_id : "<?php echo $_SESSION['opt_id'];?>",
				own_id : "<?php echo $_SESSION['own_id'];?>"
			},function(data){
				$('#subshow').html(data);
				if($('#search-pvn').val()==''){
					//$('#search_value').hide();
				}else{
					$('#search_value').show();
				}
			});
		}
		
		function searchtype(x){
			$('#search-pvn').val('');
			$('#search-name').val('');			
			$.post('report/pro_result.php',{
				event : "<?php echo $_SESSION['event'];?>",
				affiliation : $('#select-aff').val(),
				aff_id : "<?php echo $_SESSION['aff_id'];?>",
				type_id : "<?php echo $_SESSION['type_id'];?>",
				step_id : "<?php echo $_SESSION['step_id'];?>",
				status_id : "<?php echo $_SESSION['status_id'];?>",
				stage_id : "<?php echo $_SESSION['stage_id'];?>",
				eff_id : "<?php echo $_SESSION['eff_id'];?>",
				opt_id : "<?php echo $_SESSION['opt_id'];?>",
				own_id : "<?php echo $_SESSION['own_id'];?>"
			},function(data){
				$('#subshow').html(data);
	
			});
			if(x==0){
				//$('#search_value').hide();
			}
			else{
				$('#search_value').show();
			}
		}
		
		function considerType(type,consider){
			$.post('report/pro_result.php',{
				event : type,
				sub_event : "consider",
				consider : consider
				},function(data){
				$('#subshow').html(data);
				$('#returnSSR').show();
			});
		}
		
		function Addnote(x,y,z){		
		$.post('app/manage.php',{
			event :'ru-c_stepnote',
			step_id : x,
			sub_no : y
		},function(data){
			$('#delBtn').hide();
			$('#note_step_hidden').val(x);
			$('#note_step_1_hidden').val(y);
			$('#note_cp').val(z);
			$('#modal-note').modal('show');
			$('#note_event').val('c_note+u_cp');
			$('#show_note_remark').html(data);
			$('#note_remark').val("");
			$('#note_id').val("");
		});	
	}
	
	
	function Savenote(){
		if($('#note_remark').val()=="" || $('#note_remark').val()==null){
			alert("กรุณาระบุหมายเหตุ");
		}else{
			let x = $('#note_cp').val();
			$.post('app/manage.php',{
				event : $('#note_event').val(),
				note_remark : $('#note_remark').val(),
				note_step : $('#note_step_hidden').val(),
				note_step_1 : $('#note_step_1_hidden').val(),
				note_cp : $('#note_cp').val(),
				note_id : $('#note_id').val()
			},function(data){
				$('#modal-note').modal('hide');
				$('#Addbtn_'+x).hide();
				$('#loading_'+x).show();
				alert(data);
				$('#subshow').load('report/pro_result.php');
				console.log(data);
			});
		}
	}
	
	function Readnote(x){
		$.post('app/manage.php',{
			event : 'ru_note2',
			cp_no : x
		},function(data){
			$('#delBtn').hide();
			$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_remark').val(data.note_remark);
			$('#note_cp').val(data.cp_no);
			$('#note_event').val('u_note+u_cp');
			$('#note_id').val(data.note_id);
			//alert(data);
			//console.log(data);
			$('#modal-note').modal('show');
		},'json');
	}
	
	function Readstepnote(x){
		$('#reader_'+x).hide();
		$('#loading_'+x).show();
		$.post('report/table_step_note.php',{
			cp_no : x
		},function(data){
			//console.log(data);
			$('#mainshow').html(data);
		});
	}
	
	function Readstepnotes(x){
		$.post('report/user_step_note.php',{
			cp_no : x
		},function(data){
			//console.log(data);
			$('#mainshow').html(data);
		});
	}
	
	function Delnote(){
		if(confirm("คุณต้องการลบหมายเหตุใช่หรือไม่ ? ")){
			$.post('app/manage.php',{
				event : 'd_note+u_cp',
				note_id : $('#note_id').val(),
				cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				$('#subshow').load('report/pro_result.php');
			});
		}
	}
	
	function checkConsider(){
		if($("#consider").val()!=0){
			$('#saveConsider').removeClass('disabled');
			$('#saveConsider').attr('onclick','saveConsider()');
		}else{
			$('#saveConsider').addClass('disabled');
			$('#saveConsider').removeAttr('onclick')
		}
	}
	function setConsider(x,y){
		$('#modal-consider').modal('show');
		$("#consider").val(y);	
		$("#consider_cp_no").val(x);	
		$("#event").val('u_cp-consider');
		//checkConsider();
	}	
	function saveConsider(){
		//alert('Error');
		$.post('app/manage.php',{
			event : $("#event").val(),
			consider : $('#consider').val(),
			cp_no : $('#consider_cp_no').val()
		},function(data){
			searchtype(0);
			$('#modal-consider').modal('hide');
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
	function XopenStep(x){
		//var page = $('#select_table').val();
		$.post('report/table_step_noteY.php',{
			report_page : $('#select_table').val(),
			cp_no : x,
			back:'show_result'
		},function(data){
			$('#mainshow').html(data);
		});
	}
	
	
	function statusCityplan(color,step,cp){
		//alert('true');
		$('#showTable').html('<div id="subshow"class="table-responsive" style="border-color:transparent;margin-top:8px"><img src="img/loading3.gif" class="center"></div>');
		$.post('statusCityplan.php',{
			color: color,
			step:step,
			cp:cp,
			report : 'chart'
		},function(data){
			$('#showTable').html('');
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
				cp : cp
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