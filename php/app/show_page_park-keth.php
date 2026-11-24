<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$color = ['#979728','#9b487a','#3e6633','#33a1ab','#316aa5'];
$color2 = ['#5d5d13','#79345e','#2a4722','#1c7179','#1c3e61'];
$park = ['ภาคเหนือ','ภาคตะวันออกเฉียงเหนือ','ภาคกลาง','ภาคตะวันออก','ภาคใต้'];

if($_POST['sub_type']=='park'){
	$title = 'ผังตามขอบเขตการปกครอง';
	$title_2 = $park[$_POST['park']-1];
}elseif($_POST['sub_type']=='pvn'){
	$title = $_POST['pvn_name'];
	$title_2 = $_POST['pvn_name'];
}else{
	$title = 'ผังตามเขตตรวจราชการ';
	$title_2 = 'เขตตรวจราชการที่ '.$_POST['keth'];
}
?>

<style>
h1,h2,h3,h4,h5,h6{
	font-family:Kanit;
}

.div-h{
	margin:0px;
	padding:0 0 5px 5px;
	font-weight: 600;
}
.div-h{
	font-weight: 600;
}
.map-container{
	border:solid 1px black;
	padding:0;
	border-radius:8px;
}
.map-container > img{
	border-radius:8px;
	width:100%;
}
h3{
	font-family:Kanit;
	margin:0px;
	padding:0px;
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
@media screen and (max-width: 767px){
	#extra-sideshow{
		padding:0px;
	}
}
.link-pvn,.link-parketh{
	cursor:pointer;
}
.link-pvn:hover{
	border-bottom : solid 2px black;
}
.link-parketh:hover{
	border-bottom : solid 2px black;	
}
#extra-content-area-r1 {
	padding:0px;
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
		width:40%;
		float:left;
		margin-left:25px;
		margin-top:45px;
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
	.extra-sideshow-inner > h3 > text{
		font-size:17px;
	}
	.extra-sideshow-inner > h4 {
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
	.set-td{
		min-width:50px!important;
	}
	/* --- */
	#pk_5,#pk_16{
		page-break-after:always;;
	}
}
.a-img{
	width: 100%;
}
</style>
<div  style="display:none;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b> <b>เวลา '.substr($result[0]['rp_cs_date'],11,5).' น. </b>';?></div>
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
		<div id="div-btn" class="col-xs-5 col-md-6">
			<button id="btn_backy" onclick="<?php if($_POST['sub_type']=='park'){?>goto('s4');<?php }else{?>goto('s5')<?php } ?>" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
		</div>
	</div>

	<div id="extra-content-area" style="margin-top:8px">
		<div id="extra-content-area-r1" class=" col-xs-12 col-sm-5 col-md-4">
			<div class="map-container" style="background:white;padding:10px">
				<?php 
					if($_POST['sub_type']=='park'){ ?>
						<img class="a-img" src="img/map/<?= $_POST['park']?>.jpg?<?php echo date('Ymdhis');?>" alt="<?= $park[$_POST['park']-1];?>">
				<?php 
					}
					else{ ?>
						<img class="a-img" src="img/keth/<?= $_POST['keth']?>.png?<?php echo date('Ymdhis');?>" alt="<?= $title_2;?>">
				<?php } ?>
			</div>
			<div style="margin:8px 0px;border:solid black 2px;   /* display: flex;flex-direction: column;align-items: center;*/background:white;">
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
							<td class="tds-color" style="background:white!important;border: solid black 2px"></td>
							<td>ผังไม่ระบุวัน/เวลาดำเนิการ</td>
						</tr>
						
						<tr>
							<td style="min-width:20px">7</td >
							<td class="tds-color" style="border:solid #979797 2px;background:white!important;border solid black 2px"><i style="color:red" class="fa fa-exclamation"></i></td>
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
		</div>
		<div id="extra-sideshow" class="col-xs-12 col-sm-7 col-md-8" >
			<?php 
			if($_POST['sub_type']=='park'){
				$sql = "SELECT pvn_id, pvn_name, pvn_park , pvn_keth, COUNT(*) FROM tb_cityplan,ref_province WHERE cp_province = pvn_id AND pvn_park = '".$_POST['park']."' GROUP BY pvn_id, pvn_name, pvn_park, pvn_keth ORDER BY pvn_asc ASC , pvn_name ASC";
			}elseif($_POST['sub_type']=='pvn'){
				$sql = "SELECT pvn_id, pvn_name, pvn_park , pvn_keth FROM tb_cityplan,ref_province WHERE cp_province = pvn_id AND pvn_id = '".$_POST['pvn']."' ORDER BY pvn_asc ASC , pvn_name ASC";
			}else{
				$sql = "SELECT pvn_id, pvn_name, pvn_park , pvn_keth, COUNT(*) FROM tb_cityplan,ref_province WHERE cp_province = pvn_id AND pvn_keth = '".$_POST['keth']."' GROUP BY pvn_id, pvn_name, pvn_park, pvn_keth ORDER BY pvn_asc ASC , pvn_name ASC";
			}
			$result = selectSql($sql);
			$all_data = count($result);
			//echo $sql.'<br>';
			//echo $all_data;
			?>
			<div class="extra-sideshow-inner" style="border:solid 2px <?php if($_POST['sub_type']=='park'){ echo $color[$_POST['park']-1];}else{ echo $color[$result[0]['pvn_park']-1];}?> ;padding:10px 5px;margin:0 0 5px 0">
					<h3 class="div-h" onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',0)';}else{ echo 'showKethDetail('.$_POST['keth'].',0)';}?>" style='font-weight:600;color:<?php if($_POST['sub_type']=='park'){ echo $color[$_POST['park']-1];}else{ echo $color[$result[0]['pvn_park']-1];}?>'><text class="link-parketh"><b onclick=''><?= $title_2;?> </b>(จำนวน <b style="color:<?= $color2[$_POST['park']-1];?>" id="park_all_cp"></b> ผัง)</text></h3>
					<h4 class='div-h' style="">สถานะขั้นตอนดำเนินการ - ขั้นตอนย่อย 1</h4>
					<div style="width:100%">
						<div style="display:flex;flex-direction: row;justify-content: center;">
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid red 2px;margin:2px"><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',1)';}else{ echo 'showKethDetail('.$_POST['keth'].',1)';}?>" id="s1p"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:#2D882D!important;border:solid #979797 2px;margin:2px"><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',2)';}else{ echo 'showKethDetail('.$_POST['keth'].',2)';}?>" id="s2p"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:yellow!important;border:solid #979797 2px;margin:2px"><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',3)';}else{ echo 'showKethDetail('.$_POST['keth'].',3)';}?>" id="s3p"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:orange!important;border:solid #979797 2px;margin:2px"><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',4)';}else{ echo 'showKethDetail('.$_POST['keth'].',4)';}?>" id="s4p"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:red!important;border:solid #979797 2px;margin:2px"><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',5)';}else{ echo 'showKethDetail('.$_POST['keth'].',5)';}?>" id="s5p"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid black 2px;margin:2px"><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',6)';}else{ echo 'showKethDetail('.$_POST['keth'].',6)';}?>" id="s6p"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid #979797 2px;margin:2px 2px 2px 10px"><i style="margin: 3px 0;float:left;color:red" class="fa fa-exclamation"></i><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',7)';}else{ echo 'showKethDetail('.$_POST['keth'].',7)';}?>" id="s7p"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:linear-gradient(90deg,#0000ff 0%,#0000ff 25%,#ffffff 25%,#ffffff 100%) !important;;border:solid #979797 2px;margin:2px"><text onclick="<?php if($_POST['sub_type']=='park'){ echo 'showParkDetail('.$_POST['park'].',8)';}else{ echo 'showKethDetail('.$_POST['keth'].',8)';}?>" id="s8p"></text></div>
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
				</div>
			<style>
				.link-parketh:hover{
					border-bottom:solid 2px <?= $color[$result[0]['pvn_park']-1];?>;
				}
			</style>
			<?php
			$park_all_cp = 0;
			for($i=0;$i<$all_data;$i++){ ?>
			<div id="pk_<?= $i+1;?>" class="extra-sideshow-inner" style="border:solid 2px black ;padding:10px 5px;margin:0 0 5px 0"><?php
				echo "<h3 class='div-h' style='font-weight:600;'><text class='link-pvn'><b onclick='showProvince(".$result[$i]['pvn_id'].")'>".$result[$i]['pvn_name']." </b>(<b onclick=''>".$result[$i]['COUNT(*)']."</b>)</text></h3>";
				?>
				<div style="width:100%">
					<div style="display:flex;flex-direction: row;justify-content: center;">
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid red 2px;margin:2px"><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,1)" id="s1p<?= $i;?>"></text></div>
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:#2D882D!important;border:solid #979797 2px;margin:2px"><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,2)"  id="s2p<?= $i;?>"></text></div>
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:yellow!important;border:solid #979797 2px;margin:2px"><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,3)"  id="s3p<?= $i;?>"></text></div>
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:orange!important;border:solid #979797 2px;margin:2px"><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,4)"  id="s4p<?= $i;?>"></text></div>
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:red!important;border:solid #979797 2px;margin:2px"><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,5)"  id="s5p<?= $i;?>"></text></div>
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid black 2px;margin:2px"><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,6)"  id="s6p<?= $i;?>"></text></div>
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid #979797 2px;margin:2px 2px 2px 10px"><i style="margin: 3px 0;float:left;color:red" class="fa fa-exclamation"></i><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,7)"  id="s7p<?= $i;?>"></text></div>
						<div style="text-align:center;padding:3px;height:30px;width:10%;background:linear-gradient(90deg,#0000ff 0%,#0000ff 25%,#ffffff 25%,#ffffff 100%) !important;;border:solid #979797 2px;margin:2px"><text onclick="showProvince(<?= $result[$i]['pvn_id']?>,8)"  id="s8p<?= $i;?>"></text></div>
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
			</div>
			<?php 
				$park_all_cp = $park_all_cp+$result[$i]['COUNT(*)'];
			} ?>
		</div>
	</div>
</div>
<div id="load_scricpt"></div>
<script>
	$('#park_all_cp').html(<?= $park_all_cp;?>);
	LoadDataStatus();
	function LoadDataStatus(){
		$.ajax({
			url : 'app/show_page_park-keth_pro.php',
			async : true,
			type : 'POST',
			data : {
				<?php 
					echo 'sub_type : "'.$_POST["sub_type"].'",';
					if($_POST['sub_type']=='park'){
						echo 'park : "'.$_POST["park"].'"';
					}else{
						echo 'keth : "'.$_POST["keth"].'"';
					}
				?>
			},
			success : function(data, status, xhr){
				$('#load_scricpt').html(data);
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
	function showProvince(pvn,status_type){
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
				sub_type : 'province',
				be_type : '<?= $_POST["sub_type"];?>',
				pvn : pvn,
				status: status,
				status_type : status_type,
				sub_page : 'TRUE'
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
	
	function showKethDetail(keth,status_type){
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
				sub_type : 'keth',
				keth : keth,
				status: status,
				status_type : status_type,
				sub_page : 'TRUE'
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
				status_type : status_type,
				sub_page : 'TRUE'
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
</script>

