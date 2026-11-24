<?php 
	error_reporting(0);
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$title = "ผังตามขอบเขตการปกครอง";
	$park = ['ภาคเหนือ','ภาคตะวันออกเฉียงเหนือ','ภาคกลาง','ภาคตะวันออก','ภาคใต้'];
	$color = ['#979728','#9b487a','#3e6633','#33a1ab','#316aa5'];
	$color2 = ['#5d5d13','#79345e','#2a4722','#1c7179','#1c3e61'];
	$drive_links = [
		'#', // ภาคเหนือ
		'#', // ภาคตะวันออกเฉียงเหนือ
		'#', // ภาคกลาง
		'#', // ภาคตะวันออก
		'#'  // ภาคใต้
	];

	$static_park_provinces = [
		1 => [
			['pvn_id' => 22, 'pvn_name' => 'กำแพงเพชร'],
			['pvn_id' => 23, 'pvn_name' => 'เชียงราย'],
			['pvn_id' => 24, 'pvn_name' => 'เชียงใหม่'],
			['pvn_id' => 25, 'pvn_name' => 'ตาก'],
			['pvn_id' => 26, 'pvn_name' => 'นครสวรรค์'],
			['pvn_id' => 27, 'pvn_name' => 'น่าน'],
			['pvn_id' => 28, 'pvn_name' => 'พะเยา'],
			['pvn_id' => 29, 'pvn_name' => 'พิจิตร'],
			['pvn_id' => 30, 'pvn_name' => 'พิษณุโลก'],
			['pvn_id' => 31, 'pvn_name' => 'เพชรบูรณ์'],
			['pvn_id' => 32, 'pvn_name' => 'แพร่'],
			['pvn_id' => 33, 'pvn_name' => 'แม่ฮ่องสอน'],
			['pvn_id' => 34, 'pvn_name' => 'ลำปาง'],
			['pvn_id' => 35, 'pvn_name' => 'ลำพูน'],
			['pvn_id' => 36, 'pvn_name' => 'สุโขทัย'],
			['pvn_id' => 37, 'pvn_name' => 'อุตรดิตถ์'],
			['pvn_id' => 38, 'pvn_name' => 'อุทัยธานี'],
		],
		2 => [
			['pvn_id' => 39, 'pvn_name' => 'กาฬสินธุ์'],
			['pvn_id' => 40, 'pvn_name' => 'ขอนแก่น'],
			['pvn_id' => 41, 'pvn_name' => 'ชัยภูมิ'],
			['pvn_id' => 42, 'pvn_name' => 'นครพนม'],
			['pvn_id' => 43, 'pvn_name' => 'นครราชสีมา'],
			['pvn_id' => 44, 'pvn_name' => 'บึงกาฬ'],
			['pvn_id' => 45, 'pvn_name' => 'บุรีรัมย์'],
			['pvn_id' => 46, 'pvn_name' => 'มหาสารคาม'],
			['pvn_id' => 47, 'pvn_name' => 'มุกดาหาร'],
			['pvn_id' => 48, 'pvn_name' => 'ยโสธร'],
			['pvn_id' => 49, 'pvn_name' => 'ร้อยเอ็ด'],
			['pvn_id' => 50, 'pvn_name' => 'เลย'],
			['pvn_id' => 51, 'pvn_name' => 'ศรีสะเกษ'],
			['pvn_id' => 52, 'pvn_name' => 'สกลนคร'],
			['pvn_id' => 53, 'pvn_name' => 'สุรินทร์'],
			['pvn_id' => 54, 'pvn_name' => 'หนองคาย'],
			['pvn_id' => 55, 'pvn_name' => 'หนองบัวลำภู'],
			['pvn_id' => 56, 'pvn_name' => 'อำนาจเจริญ'],
			['pvn_id' => 57, 'pvn_name' => 'อุดรธานี'],
			['pvn_id' => 58, 'pvn_name' => 'อุบลราชธานี'],
		],
		3 => [
			['pvn_id' => 1, 'pvn_name' => 'กรุงเทพมหานคร'],
			['pvn_id' => 60, 'pvn_name' => 'กาญจนบุรี'],
			['pvn_id' => 5, 'pvn_name' => 'ชัยนาท'],
			['pvn_id' => 7, 'pvn_name' => 'นครนายก'],
			['pvn_id' => 8, 'pvn_name' => 'นครปฐม'],
			['pvn_id' => 9, 'pvn_name' => 'นนทบุรี'],
			['pvn_id' => 10, 'pvn_name' => 'ปทุมธานี'],
			['pvn_id' => 65, 'pvn_name' => 'ประจวบคีรีขันธ์'],
			['pvn_id' => 12, 'pvn_name' => 'พระนครศรีอยุธยา'],
			['pvn_id' => 69, 'pvn_name' => 'เพชรบุรี'],
			['pvn_id' => 14, 'pvn_name' => 'ลพบุรี'],
			['pvn_id' => 15, 'pvn_name' => 'สมุทรปราการ'],
			['pvn_id' => 16, 'pvn_name' => 'สมุทรสงคราม'],
			['pvn_id' => 17, 'pvn_name' => 'สมุทรสาคร'],
			['pvn_id' => 19, 'pvn_name' => 'สระบุรี'],
			['pvn_id' => 20, 'pvn_name' => 'สิงห์บุรี'],
			['pvn_id' => 76, 'pvn_name' => 'สุพรรณบุรี'],
			['pvn_id' => 21, 'pvn_name' => 'อ่างทอง'],
			['pvn_id' => 73, 'pvn_name' => 'ราชบุรี'],
		],
		4 => [
			['pvn_id' => 2, 'pvn_name' => 'จันทบุรี'],
			['pvn_id' => 3, 'pvn_name' => 'ฉะเชิงเทรา'],
			['pvn_id' => 4, 'pvn_name' => 'ชลบุรี'],
			['pvn_id' => 6, 'pvn_name' => 'ตราด'],
			['pvn_id' => 11, 'pvn_name' => 'ปราจีนบุรี'],
			['pvn_id' => 13, 'pvn_name' => 'ระยอง'],
			['pvn_id' => 18, 'pvn_name' => 'สระแก้ว'],
		],
		5 => [
			['pvn_id' => 59, 'pvn_name' => 'กระบี่'],
			['pvn_id' => 61, 'pvn_name' => 'ชุมพร'],
			['pvn_id' => 62, 'pvn_name' => 'ตรัง'],
			['pvn_id' => 63, 'pvn_name' => 'นครศรีธรรมราช'],
			['pvn_id' => 64, 'pvn_name' => 'นราธิวาส'],
			['pvn_id' => 66, 'pvn_name' => 'ปัตตานี'],
			['pvn_id' => 67, 'pvn_name' => 'พังงา'],
			['pvn_id' => 68, 'pvn_name' => 'พัทลุง'],
			['pvn_id' => 70, 'pvn_name' => 'ภูเก็ต'],
			['pvn_id' => 71, 'pvn_name' => 'ยะลา'],
			['pvn_id' => 72, 'pvn_name' => 'ระนอง'],
			['pvn_id' => 74, 'pvn_name' => 'สงขลา'],
			['pvn_id' => 75, 'pvn_name' => 'สตูล'],
			['pvn_id' => 77, 'pvn_name' => 'สุราษฎร์ธานี'],
		],
	];
?>
<style>
.map-container{
	border:solid 1px black;
	padding:0;
	border-radius:8px;
}
.map-container > img{
	border-radius:8px;
	width:100%;
}
td:hover{
	background:#DEF8FF;
}
h3{
	font-family:Kanit;
	margin:0px;
	padding:0px;
}
.div-h{
	margin:0px;
	padding:0 0 5px 5px;
	
	font-family:Kanit;
	margin:4px 0;
	//font-size: 15px;
	font-weight: 600;
}

.park-pvn-0, .park-pvn-1, .park-pvn-2, .park-pvn-3, .park-pvn-4{
	font-weight:600;
	cursor:pointer;
	text-decoration:none!important;
	display:inline-block;
}
.park-pvn-0{
	color:<?= $color[0];?>!important;
}
.park-pvn-1{
	color:<?= $color[1];?>!important;
}
.park-pvn-2{
	color:<?= $color[2];?>!important;
}
.park-pvn-3{
	color:<?= $color[3];?>!important;
}
.park-pvn-4{
	color:<?= $color[4];?>!important;
}
.park-pvn-0:hover{
	font-weight:600;
	color:<?= $color[0];?>!important;
	border-bottom:solid 2px <?= $color[0];?>;
}
.park-pvn-1:hover{
	font-weight:600;
	color:<?= $color[1];?>!important;
	border-bottom:solid 2px <?= $color[1];?>;
}
.park-pvn-2:hover{
	font-weight:600;
	color:<?= $color[2];?>!important;
	border-bottom:solid 2px <?= $color[2];?>;
}
.park-pvn-3:hover{
	font-weight:600;
	color:<?= $color[3];?>!important;
	border-bottom:solid 2px <?= $color[3];?>;
}
.park-pvn-4:hover{
	font-weight:600;
	color:<?= $color[4];?>!important;
	border-bottom:solid 2px <?= $color[4];?>;
}

#table_detail_status{
	width:100%;
	background:transparent;
}
#table_detail_status > tbody > tr{
	background:transparent!important;
	margin:5px;
}
.tds-color{
	min-width:50px;
	text-align:center;
}
#table_detail_status > tbody {
	display: flex;
    flex-direction: column;
}
#table_detail_status > tbody > tr > td{
	padding: 0px;
}
#table_detail_status > tbody > tr> td:last-child{
	padding-left:5px;
	min-width:170px;
	max-width:170px;
}
#table_detail_status > tbody > tr> td:first-child,
#table_detail_status > tbody > tr> td:last-child{
	background:white;
}
#table_detail_status > tbody > tr> td:first-child{
	text-align:center;
}
#extra-content-area-r1 {
		padding:0px;
	}

@media screen and (max-width: 767px){
	#extra-sideshow{
		padding:0px;
	}
}
@media print{
	@page{
		size:A4 portrait;
		margin-top:15mm; 
		margin-left:4mm; 
		margin-bottom:10mm;
		margin-right:4mm;
	}
	#title_page{
		margin-top:-10pt!important;
	}
	#info_bar{
		margin-top:0px!important;
		margin-bottom:30px!important;
	}
	
	
	#extra-content-area-r1 > .map-container{
		position:absolute;
		border:none;
	}
	#extra-content-area-r1 > .map-container > img{
		width:38%;
		float:left;
		margin-left:25px;
		margin-top:0px;
	}
	#extra-content-area-r1 > div:last-child{
		width:45%;
		float:right;
	}
	#extra-content-area-r1  {
		width:100%;
		margin-bottom:10px;
	}
	#extra-content-area-r1 {
		margin-top:-50px;
		padding:0 15px;
	}
	#extra-sideshow{
		width: 100%;
		//margin-top:-40px;
	}
	
	/*font-size*/
	.extra-sideshow-inner > h3{
		font-size:17px;
	}
	.extra-sideshow-inner > h3{
		font-size:17px;
	}
	.extra-sideshow-inner > h4{
		font-size:12px;
		font-weight:500;
		margin-bottom:-5px;
	}
	.extra-sideshow-inner > .status_bar{
		font-size:14px;
	}
	.extra-sideshow-inner > .table-responsive {
		font-size : 12px;
	}
	.set-td > text{
		font-weight:400;
	}
	/* --- */
	
	.extra-sideshow-inner > .table-responsive{
		margin:0px 0px -14px 0px;	
	}
	
.a-img{
	width: 100%;
}
</style>
<!-- <div  style="display:none;z-index:1;text-align:right;" class="pull-right"></div> -->
<div id="title_page" class="col-12">
	<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"><?= $title;?></h2>
	<h3 class="hidden-md hidden-lg GHfont-mini unprintable" style="font-family: Kanit;text-align:center"><?= $title;?></h3>
	<div class="row" id="info_bar">
		<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_value"></h3>
	</div>
	<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
</div>	
	<br>
	<div class="container-fluid">
		<div class="row unprintable">
		<!--	<div id="div-btn" class="col-xs-5 col-md-6">
				<button id="btn_backy" onclick="goto('a6m');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div> -->
		</div>
	
		<div id="extra-content-area" style="margin-top:8px">
			<div id="extra-content-area-r1" class=" col-xs-12 col-sm-5 col-md-4">
				<div class="map-container">
					<img class="a-img" src="img/map/main.jpg" >
				</div>
				<!--
				<div style="margin:8px 0px;border:solid black 2px; /* display: flex;flex-direction: column;align-items: center;*/background:white;">
					<div style="width:100%;border-bottom:solid black 2px;padding:8px;margin-bottom:8px;background:#dfdfdf">
						<h3>คำอธิบายข้อมูล</h3>
					</div>
					<div class="table-responsive" style="">
					<table id="table_detail_status" class="table"style="width:100%;padding:2px;">
						<tbody>
							<tr>
								<td style="min-width:20px">1</td>
								<td class="tds-color" style="background:white;border : solid red 2px"></td>
								<td>ผังไม่ระบุขั้นตอน</td>
							</tr>
							
							<tr>
								<td style="min-width:20px">2</td >
								<td class="tds-color" style="border:solid #979797 2px;background:#2D882D!important;border solid black 2px"></td>
								<td>ผังมีความก้าวหน้า</td>
							</tr>
							<tr>
								<td style="min-width:20px">3</td >
								<td class="tds-color" style="border:solid #979797 2px;background:yellow!important;border solid black 2px"></td>
								<td>ผังล่าช้าเล็กน้อย</td>
							</tr>
							<tr>
								<td style="min-width:20px">4</td >
								<td class="tds-color"  style="border:solid #979797 2px;background:orange!important;border solid black 2px"></td>
								<td>ผังล่าช้าปานกลาง</td>
							</tr>
							<tr>
								<td style="min-width:20px">5</td >
								<td class="tds-color" style="border:solid #979797 2px;background:red!important;border solid black 2px"></td>
								<td>ผังล่าช้ามาก</td>
							</tr>
							
							<tr>
								<td style="min-width:20px">6</td >
								<td class="tds-color" style="background:white;border: solid black 2px"></td>
								<td>ผังไม่ระบุวัน/เวลาดำเนิการ</td>
							</tr>
							
							<tr>
								<td style="min-width:20px">7</td >
								<td class="tds-color" style="border:solid #979797 2px;background:white;border solid black 2px"><i style="color:red" class="fa fa-exclamation"></i></td>
								<td>ผังไม่ระบุขั้นตอนถัดไป</td>
							</tr>
							<tr>
								<td style="min-width:20px">8</td >
								<td class="tds-color" style="border:solid #979797 2px;background:linear-gradient(90deg,#0000ff 0%,#0000ff 15%,#ffffff 15%,#ffffff 100%) !important;border solid black 2px"></td>
								<td>ผังเสนอหน่วยงานที่เกี่ยวข้อง</td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
				-->
			</div>
			<div id="extra-sideshow" class="col-xs-12 col-sm-7 col-md-8" >
				<?php 
				for($i=1;$i<=5;$i++){
					$sql = "SELECT COUNT(*) FROM tb_cityplan,ref_province WHERE cp_province = pvn_id AND pvn_park = '".$i."'";
					$cp = selectSql($sql);?>
				<div class="extra-sideshow-inner" style="border:solid 2px <?= $color[$i-1];?> ;padding:10px 5px;margin:0 0 5px 0"><?php
					echo "<h3 class='div-h' style='font-weight:600;color:".$color[$i-1]."'><b class='park-name' onclick='showPark(".$i.")'>".$park[$i-1]." </b><!-- (จำนวน <b class='park-value' onclick='showParkDetail(".$i.")' style='color:".$color2[$i-1]."'>".$cp[0]['COUNT(*)']."</b> ผัง) --></h3>";
					?>
					<!--
					<h4 class='div-h' style="">สถานะขั้นตอนดำเนินการ - ขั้นตอนย่อย 1</h4>
					<div class="status_bar" style="width:100%">
						<div style="display:flex;flex-direction: row;justify-content: center;">
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid red 2px;margin:2px"><text onclick="showParkDetail(<?= $i;?>,1)" id="s1p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:#2D882D!important;border:solid #979797 2px;margin:2px"><text onclick="showParkDetail(<?= $i;?>,2)"id="s2p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:yellow!important;border:solid #979797 2px;margin:2px"><text onclick="showParkDetail(<?= $i;?>,3)"id="s3p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:orange!important;border:solid #979797 2px;margin:2px"><text onclick="showParkDetail(<?= $i;?>,4)" id="s4p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:red!important;border:solid #979797 2px;margin:2px"><text onclick="showParkDetail(<?= $i;?>,5)" id="s5p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid black 2px;margin:2px"><text onclick="showParkDetail(<?= $i;?>,6)" id="s6p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid #979797 2px;margin:2px 2px 2px 10px"><i style="margin: 3px 0;float:left;color:red" class="fa fa-exclamation"></i><text onclick="showParkDetail(<?= $i;?>,7)" id="s7p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:linear-gradient(90deg,#0000ff 0%,#0000ff 25%,#ffffff 25%,#ffffff 100%) !important;;border:solid #979797 2px;margin:2px"><text onclick="showParkDetail(<?= $i;?>,8)" id="s8p<?= $i;?>"></text></div>
						</div>
						<div style="display:none;flex-direction: row;justify-content: center;">
							<div style="margin:-2px 2px 0px;text-align:center;width:10%">1</div>
							<div style="margin:-2px 2px 0px;text-align:center;width:10%">2</div>
							<div style="margin:-2px 2px 0px;text-align:center;width:10%">3</div>
							<div style="margin:-2px 2px 0px;text-align:center;width:10%">4</div>
							<div style="margin:-2px 2px 0px;text-align:center;width:10%">5</div>
							<div style="margin:-2px 2px 0px;text-align:center;width:10%">6</div>
							<div style="margin:-2px 2px 0px 10px;text-align:center;width:10%">7</div>
							<div style="margin:-2px 2px 0px;text-align:center;width:10%">8</div>
						</div>
					</div>
					-->
					<div style="margin-top:8px" class="table-responsive">
					<?php
					$sql = "SELECT pvn_id, pvn_name ,COUNT(*) FROM tb_cityplan , ref_province WHERE cp_province = pvn_id AND pvn_park ='".$i."' GROUP BY pvn_id, pvn_name";
					$result = selectSql($sql);
					if (empty($result)) {
						$result = isset($static_park_provinces[$i]) ? $static_park_provinces[$i] : [];
					}
					$all_data = count($result);
					
				?>
					
						<table class="table ">
							<tbody>
								<?php
									$x=0;
									for($data=0;$data<$all_data;){
										echo "<tr style='background:transparent'>";
										for($td=1;$td<=5;$td++,$data++){
												$x++;
												if($x<=count($result)){
													echo "<td class='set-td' style='border:solid ".$color[$i-1]." 1px;'><a href='".$drive_links[$i-1]."' target='_blank' class='park-pvn-".($i-1)."' style='text-decoration:none;'><!-- onclick='showProvince(".$result[$data]['pvn_id'].")' -->".$result[$data]['pvn_name']." <!-- <b style='color:black'>(".$result[$data]['COUNT(*)'].")</b> --></a></td>";
												}else{
													
												}
												
												
											
										}
										echo "</tr>";
									}
								?>
							<tbody>
						</table>
					</div>
				</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div id="load_scricpt"></div>
	<div id="extra" class="table-responsive"></div>
	<script>
	$('.north').click( function(event){
		alert('north');
	});
	
	function showPark(park){
		$.ajax({
			url : 'app/show_page_park-keth.php',
			async : true,
			type : 'POST',
			data : {
				sub_type : 'park',
				park : park
			},
			success : function(data, status, xhr){
				//alert('Loading : '+status+'\n'+data);
				$('#mainshow').html(data);
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	
	function showParkDetail(park,status_type){
		if(status_type>0){
			var status = 'TRUE';
		}else{
			var status = 'FALSE';
		}
		$.ajax({
			url : 'report/show_detail_x.php',
			async : true,
			type : 'POST',
			data : {
				sub_type : 'park',
				park : park,
				status: status,
				status_type : status_type
			},
			success : function(data, status, xhr){
				//alert('Loading : '+status+'\n'+data);
				$('#mainshow').html(data);
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	function showProvince(pvn){
		$.ajax({
			url : 'report/show_detail_x.php',
			async : true,
			type : 'POST',
			data : {
				sub_type : 'province',
				be_type : 'park',
				pvn : pvn
			},
			success : function(data, status, xhr){
				//alert('Loading : '+status+'\n'+data);
				$('#mainshow').html(data);
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	LoadDataStatus();
	function LoadDataStatus(){
		$.ajax({
			url : 'app/show_park.php',
			async : true,
			type : 'POST',
			success : function(data, status, xhr){
				$('#load_scricpt').html(data);
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	
	function statusCityplan(color,step,cp){
		$.post('statusCityplan.php',{
			color: color,
			step:step,
			cp:cp,
			report : 'tree'
		},function(data){
			$('#extra').html(data);
			window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
		});
		
	}
	</script>