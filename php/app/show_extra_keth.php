<?php 
	error_reporting(0);
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$title = "ผังตามเขตตรวจราชการ";
	$keth = "เขตตรวจราชการที่ ";
	$color = ['#979728','#9b487a','#3e6633','#33a1ab','#316aa5'];
	$color2 = ['#5d5d13','#79345e','#2a4722','#1c7179','#1c3e61'];

	$drive_links = [
		'north'   => '#',
		'isan'    => '#',
		'central' => '#',
		'east'    => '#',
		'south'   => '#'
	];

	$keth_links = [
		1  => $drive_links['central'],
		2  => $drive_links['central'],
		3  => $drive_links['east'],
		4  => $drive_links['central'],
		5  => $drive_links['central'],
		6  => $drive_links['east'],
		7  => $drive_links['south'],
		8  => $drive_links['south'],
		9  => $drive_links['south'],
		10 => $drive_links['isan'],
		11 => $drive_links['isan'],
		12 => $drive_links['isan'],
		13 => $drive_links['isan'],
		14 => $drive_links['isan'],
		15 => $drive_links['north'],
		16 => $drive_links['north'],
		17 => $drive_links['north'],
		18 => $drive_links['north']
	];

	$province_links = [
		// เขตตรวจราชการที่ 1 (ภาคกลาง)
		'กรุงเทพมหานคร' => $drive_links['central'],
		'นนทบุรี'       => $drive_links['central'],
		'ปทุมธานี'      => $drive_links['central'],
		'พระนครศรีอยุธยา' => $drive_links['central'],
		'สระบุรี'       => $drive_links['central'],

		// เขตตรวจราชการที่ 2 (ภาคกลาง)
		'ชัยนาท'        => $drive_links['central'],
		'ลพบุรี'        => $drive_links['central'],
		'สิงห์บุรี'      => $drive_links['central'],
		'อ่างทอง'       => $drive_links['central'],

		// เขตตรวจราชการที่ 3 (ภาคตะวันออก)
		'ฉะเชิงเทรา'    => $drive_links['east'],
		'นครนายก'       => $drive_links['east'],
		'ปราจีนบุรี'     => $drive_links['east'],
		'สมุทรปราการ'   => $drive_links['east'],
		'สระแก้ว'       => $drive_links['east'],

		// เขตตรวจราชการที่ 4 (ภาคกลาง)
		'นครปฐม'        => $drive_links['central'],
		'กาญจนบุรี'     => $drive_links['central'],
		'ราชบุรี'       => $drive_links['central'],
		'สุพรรณบุรี'    => $drive_links['central'],

		// เขตตรวจราชการที่ 5 (ภาคกลาง)
		'สมุทรสงคราม'   => $drive_links['central'],
		'สมุทรสาคร'     => $drive_links['central'],
		'ประจวบคีรีขันธ์' => $drive_links['central'],
		'เพชรบุรี'      => $drive_links['central'],

		// เขตตรวจราชการที่ 6 (ภาคตะวันออก)
		'จันทบุรี'      => $drive_links['east'],
		'ชลบุรี'        => $drive_links['east'],
		'ตราด'          => $drive_links['east'],
		'ระยอง'         => $drive_links['east'],

		// เขตตรวจราชการที่ 7 (ภาคใต้)
		'กระบี่'        => $drive_links['south'],
		'ชุมพร'         => $drive_links['south'],
		'พังงา'         => $drive_links['south'],
		'ภูเก็ต'        => $drive_links['south'],
		'ระนอง'         => $drive_links['south'],

		// เขตตรวจราชการที่ 8 (ภาคใต้)
		'ตรัง'          => $drive_links['south'],
		'นครศรีธรรมราช' => $drive_links['south'],
		'พัทลุง'        => $drive_links['south'],
		'สุราษฎร์ธานี'   => $drive_links['south'],

		// เขตตรวจราชการที่ 9 (ภาคใต้)
		'นราธิวาส'      => $drive_links['south'],
		'ปัตตานี'       => $drive_links['south'],
		'ยะลา'          => $drive_links['south'],
		'สงขลา'         => $drive_links['south'],
		'สตูล'          => $drive_links['south'],

		// เขตตรวจราชการที่ 10 (ภาคตะวันออกเฉียงเหนือ)
		'บึงกาฬ'        => $drive_links['isan'],
		'เลย'           => $drive_links['isan'],
		'หนองคาย'       => $drive_links['isan'],
		'หนองบัวลำภู'   => $drive_links['isan'],
		'อุดรธานี'      => $drive_links['isan'],

		// เขตตรวจราชการที่ 11 (ภาคตะวันออกเฉียงเหนือ)
		'นครพนม'        => $drive_links['isan'],
		'มุกดาหาร'      => $drive_links['isan'],
		'สกลนคร'        => $drive_links['isan'],

		// เขตตรวจราชการที่ 12 (ภาคตะวันออกเฉียงเหนือ)
		'กาฬสินธุ์'     => $drive_links['isan'],
		'ขอนแก่น'       => $drive_links['isan'],
		'มหาสารคาม'     => $drive_links['isan'],
		'ร้อยเอ็ด'      => $drive_links['isan'],

		// เขตตรวจราชการที่ 13 (ภาคตะวันออกเฉียงเหนือ)
		'ชัยภูมิ'       => $drive_links['isan'],
		'นครราชสีมา'    => $drive_links['isan'],
		'บุรีรัมย์'      => $drive_links['isan'],
		'สุรินทร์'      => $drive_links['isan'],

		// เขตตรวจราชการที่ 14 (ภาคตะวันออกเฉียงเหนือ)
		'ยโสธร'         => $drive_links['isan'],
		'ศรีสะเกษ'      => $drive_links['isan'],
		'อำนาจเจริญ'    => $drive_links['isan'],
		'อุบลราชธานี'   => $drive_links['isan'],

		// เขตตรวจราชการที่ 15 (ภาคเหนือ)
		'เชียงใหม่'     => $drive_links['north'],
		'แม่ฮ่องสอน'    => $drive_links['north'],
		'ลำปาง'         => $drive_links['north'],
		'ลำพูน'         => $drive_links['north'],

		// เขตตรวจราชการที่ 16 (ภาคเหนือ)
		'เชียงราย'      => $drive_links['north'],
		'น่าน'          => $drive_links['north'],
		'พะเยา'         => $drive_links['north'],
		'แพร่'          => $drive_links['north'],

		// เขตตรวจราชการที่ 17 (ภาคเหนือ)
		'ตาก'           => $drive_links['north'],
		'พิษณุโลก'      => $drive_links['north'],
		'เพชรบูรณ์'     => $drive_links['north'],
		'สุโขทัย'       => $drive_links['north'],
		'อุตรดิตถ์'     => $drive_links['north'],

		// เขตตรวจราชการที่ 18 (ภาคเหนือ)
		'กำแพงเพชร'     => $drive_links['north'],
		'นครสวรรค์'     => $drive_links['north'],
		'พิจิตร'        => $drive_links['north'],
		'อุทัยธานี'      => $drive_links['north']
	];

	$static_keth_data = [
		1 => [
			'park' => 3,
			'provinces' => [
				['pvn_id' => 1, 'pvn_name' => 'กรุงเทพมหานคร', 'pvn_park' => 3],
				['pvn_id' => 9, 'pvn_name' => 'นนทบุรี', 'pvn_park' => 3],
				['pvn_id' => 10, 'pvn_name' => 'ปทุมธานี', 'pvn_park' => 3],
				['pvn_id' => 12, 'pvn_name' => 'พระนครศรีอยุธยา', 'pvn_park' => 3],
				['pvn_id' => 19, 'pvn_name' => 'สระบุรี', 'pvn_park' => 3],
			]
		],
		2 => [
			'park' => 3,
			'provinces' => [
				['pvn_id' => 5, 'pvn_name' => 'ชัยนาท', 'pvn_park' => 3],
				['pvn_id' => 14, 'pvn_name' => 'ลพบุรี', 'pvn_park' => 3],
				['pvn_id' => 20, 'pvn_name' => 'สิงห์บุรี', 'pvn_park' => 3],
				['pvn_id' => 21, 'pvn_name' => 'อ่างทอง', 'pvn_park' => 3],
			]
		],
		3 => [
			'park' => 4,
			'provinces' => [
				['pvn_id' => 3, 'pvn_name' => 'ฉะเชิงเทรา', 'pvn_park' => 4],
				['pvn_id' => 7, 'pvn_name' => 'นครนายก', 'pvn_park' => 4],
				['pvn_id' => 11, 'pvn_name' => 'ปราจีนบุรี', 'pvn_park' => 4],
				['pvn_id' => 15, 'pvn_name' => 'สมุทรปราการ', 'pvn_park' => 4],
				['pvn_id' => 18, 'pvn_name' => 'สระแก้ว', 'pvn_park' => 4],
			]
		],
		4 => [
			'park' => 3,
			'provinces' => [
				['pvn_id' => 8, 'pvn_name' => 'นครปฐม', 'pvn_park' => 3],
				['pvn_id' => 60, 'pvn_name' => 'กาญจนบุรี', 'pvn_park' => 3],
				['pvn_id' => 73, 'pvn_name' => 'ราชบุรี', 'pvn_park' => 3],
				['pvn_id' => 76, 'pvn_name' => 'สุพรรณบุรี', 'pvn_park' => 3],
			]
		],
		5 => [
			'park' => 3,
			'provinces' => [
				['pvn_id' => 16, 'pvn_name' => 'สมุทรสงคราม', 'pvn_park' => 3],
				['pvn_id' => 17, 'pvn_name' => 'สมุทรสาคร', 'pvn_park' => 3],
				['pvn_id' => 65, 'pvn_name' => 'ประจวบคีรีขันธ์', 'pvn_park' => 3],
				['pvn_id' => 69, 'pvn_name' => 'เพชรบุรี', 'pvn_park' => 3],
			]
		],
		6 => [
			'park' => 4,
			'provinces' => [
				['pvn_id' => 2, 'pvn_name' => 'จันทบุรี', 'pvn_park' => 4],
				['pvn_id' => 4, 'pvn_name' => 'ชลบุรี', 'pvn_park' => 4],
				['pvn_id' => 6, 'pvn_name' => 'ตราด', 'pvn_park' => 4],
				['pvn_id' => 13, 'pvn_name' => 'ระยอง', 'pvn_park' => 4],
			]
		],
		7 => [
			'park' => 5,
			'provinces' => [
				['pvn_id' => 59, 'pvn_name' => 'กระบี่', 'pvn_park' => 5],
				['pvn_id' => 61, 'pvn_name' => 'ชุมพร', 'pvn_park' => 5],
				['pvn_id' => 67, 'pvn_name' => 'พังงา', 'pvn_park' => 5],
				['pvn_id' => 70, 'pvn_name' => 'ภูเก็ต', 'pvn_park' => 5],
				['pvn_id' => 72, 'pvn_name' => 'ระนอง', 'pvn_park' => 5],
			]
		],
		8 => [
			'park' => 5,
			'provinces' => [
				['pvn_id' => 62, 'pvn_name' => 'ตรัง', 'pvn_park' => 5],
				['pvn_id' => 63, 'pvn_name' => 'นครศรีธรรมราช', 'pvn_park' => 5],
				['pvn_id' => 68, 'pvn_name' => 'พัทลุง', 'pvn_park' => 5],
				['pvn_id' => 77, 'pvn_name' => 'สุราษฎร์ธานี', 'pvn_park' => 5],
			]
		],
		9 => [
			'park' => 5,
			'provinces' => [
				['pvn_id' => 64, 'pvn_name' => 'นราธิวาส', 'pvn_park' => 5],
				['pvn_id' => 66, 'pvn_name' => 'ปัตตานี', 'pvn_park' => 5],
				['pvn_id' => 71, 'pvn_name' => 'ยะลา', 'pvn_park' => 5],
				['pvn_id' => 74, 'pvn_name' => 'สงขลา', 'pvn_park' => 5],
				['pvn_id' => 75, 'pvn_name' => 'สตูล', 'pvn_park' => 5],
			]
		],
		10 => [
			'park' => 2,
			'provinces' => [
				['pvn_id' => 44, 'pvn_name' => 'บึงกาฬ', 'pvn_park' => 2],
				['pvn_id' => 50, 'pvn_name' => 'เลย', 'pvn_park' => 2],
				['pvn_id' => 54, 'pvn_name' => 'หนองคาย', 'pvn_park' => 2],
				['pvn_id' => 55, 'pvn_name' => 'หนองบัวลำภู', 'pvn_park' => 2],
				['pvn_id' => 57, 'pvn_name' => 'อุดรธานี', 'pvn_park' => 2],
			]
		],
		11 => [
			'park' => 2,
			'provinces' => [
				['pvn_id' => 42, 'pvn_name' => 'นครพนม', 'pvn_park' => 2],
				['pvn_id' => 47, 'pvn_name' => 'มุกดาหาร', 'pvn_park' => 2],
				['pvn_id' => 52, 'pvn_name' => 'สกลนคร', 'pvn_park' => 2],
			]
		],
		12 => [
			'park' => 2,
			'provinces' => [
				['pvn_id' => 39, 'pvn_name' => 'กาฬสินธุ์', 'pvn_park' => 2],
				['pvn_id' => 40, 'pvn_name' => 'ขอนแก่น', 'pvn_park' => 2],
				['pvn_id' => 46, 'pvn_name' => 'มหาสารคาม', 'pvn_park' => 2],
				['pvn_id' => 49, 'pvn_name' => 'ร้อยเอ็ด', 'pvn_park' => 2],
			]
		],
		13 => [
			'park' => 2,
			'provinces' => [
				['pvn_id' => 41, 'pvn_name' => 'ชัยภูมิ', 'pvn_park' => 2],
				['pvn_id' => 43, 'pvn_name' => 'นครราชสีมา', 'pvn_park' => 2],
				['pvn_id' => 45, 'pvn_name' => 'บุรีรัมย์', 'pvn_park' => 2],
				['pvn_id' => 53, 'pvn_name' => 'สุรินทร์', 'pvn_park' => 2],
			]
		],
		14 => [
			'park' => 2,
			'provinces' => [
				['pvn_id' => 48, 'pvn_name' => 'ยโสธร', 'pvn_park' => 2],
				['pvn_id' => 51, 'pvn_name' => 'ศรีสะเกษ', 'pvn_park' => 2],
				['pvn_id' => 56, 'pvn_name' => 'อำนาจเจริญ', 'pvn_park' => 2],
				['pvn_id' => 58, 'pvn_name' => 'อุบลราชธานี', 'pvn_park' => 2],
			]
		],
		15 => [
			'park' => 1,
			'provinces' => [
				['pvn_id' => 24, 'pvn_name' => 'เชียงใหม่', 'pvn_park' => 1],
				['pvn_id' => 33, 'pvn_name' => 'แม่ฮ่องสอน', 'pvn_park' => 1],
				['pvn_id' => 34, 'pvn_name' => 'ลำปาง', 'pvn_park' => 1],
				['pvn_id' => 35, 'pvn_name' => 'ลำพูน', 'pvn_park' => 1],
			]
		],
		16 => [
			'park' => 1,
			'provinces' => [
				['pvn_id' => 23, 'pvn_name' => 'เชียงราย', 'pvn_park' => 1],
				['pvn_id' => 27, 'pvn_name' => 'น่าน', 'pvn_park' => 1],
				['pvn_id' => 28, 'pvn_name' => 'พะเยา', 'pvn_park' => 1],
				['pvn_id' => 32, 'pvn_name' => 'แพร่', 'pvn_park' => 1],
			]
		],
		17 => [
			'park' => 1,
			'provinces' => [
				['pvn_id' => 25, 'pvn_name' => 'ตาก', 'pvn_park' => 1],
				['pvn_id' => 30, 'pvn_name' => 'พิษณุโลก', 'pvn_park' => 1],
				['pvn_id' => 31, 'pvn_name' => 'เพชรบูรณ์', 'pvn_park' => 1],
				['pvn_id' => 36, 'pvn_name' => 'สุโขทัย', 'pvn_park' => 1],
				['pvn_id' => 37, 'pvn_name' => 'อุตรดิตถ์', 'pvn_park' => 1],
			]
		],
		18 => [
			'park' => 1,
			'provinces' => [
				['pvn_id' => 22, 'pvn_name' => 'กำแพงเพชร', 'pvn_park' => 1],
				['pvn_id' => 26, 'pvn_name' => 'นครสวรรค์', 'pvn_park' => 1],
				['pvn_id' => 29, 'pvn_name' => 'พิจิตร', 'pvn_park' => 1],
				['pvn_id' => 38, 'pvn_name' => 'อุทัยธานี', 'pvn_park' => 1],
			]
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

.keth-pvn-0, .keth-pvn-1, .keth-pvn-2, .keth-pvn-3, .keth-pvn-4{
	cursor:pointer;
	text-decoration:none!important;
	display:inline-block;
}
.keth-pvn-0{
	color:<?= $color[0];?>!important;
}
.keth-pvn-1{
	font-weight:600;
	color:<?= $color[1];?>!important;
}
.keth-pvn-2{
	font-weight:600;
	color:<?= $color[2];?>!important;
}
.keth-pvn-3{
	font-weight:600;
	color:<?= $color[3];?>!important;
}
.keth-pvn-4{
	font-weight:600;
	color:<?= $color[4];?>!important;
}
.keth-pvn-0:hover, .keth-pvn-0:focus{
	color:<?= $color[0];?>!important;
	text-decoration:none!important;
	border-bottom:solid 2px <?= $color[0];?>;
}
.keth-pvn-1:hover, .keth-pvn-1:focus{
	font-weight:600;
	color:<?= $color[1];?>!important;
	text-decoration:none!important;
	border-bottom:solid 2px <?= $color[1];?>;
}
.keth-pvn-2:hover, .keth-pvn-2:focus{
	font-weight:600;
	color:<?= $color[2];?>!important;
	text-decoration:none!important;
	border-bottom:solid 2px <?= $color[2];?>;
}
.keth-pvn-3:hover, .keth-pvn-3:focus{
	font-weight:600;
	color:<?= $color[3];?>!important;
	text-decoration:none!important;
	border-bottom:solid 2px <?= $color[3];?>;
}
.keth-pvn-4:hover, .keth-pvn-4:focus{
	font-weight:600;
	color:<?= $color[4];?>!important;
	text-decoration:none!important;
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
		width:30%;
		float:left;
		margin-left:35px;
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
		margin-top:-20px;
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
	.set-td > text, .set-td > a{
		font-weight:400;
	}
	.set-td{
		min-width:50px!important;
	}
	/* --- */
	
	.extra-sideshow-inner > .table-responsive{
		margin:0px 0px -14px 0px;	
	}
	#keth_3,#keth_9,#keth_15{
		page-break-after:always;;
	}
	
}
.a-img{
	width: 100%;
}
</style>
<!-- <div  style="display:none;z-index:1;text-align:right;" class="pull-right"></div> -->
	<div id="title_page" class="col-xs-12">
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
			<!-- <div id="div-btn" class="col-xs-5 col-md-6">
				<button id="btn_backy" onclick="goto('a6m');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div> -->
		</div>
	
		<div id="extra-content-area" style="margin-top:8px">
			<div id="extra-content-area-r1" class=" col-xs-12 col-sm-5 col-md-4">
				<div class="map-container" style="background:white;padding:10px">
					<img class="a-img" src="img/keth/main.png">
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
				for($i=1;$i<=18;$i++){
					$sql = "SELECT pvn_park, COUNT(*) FROM tb_cityplan,ref_province WHERE cp_province = pvn_id AND pvn_keth = '".$i."' GROUP BY pvn_park";
					$cp = selectSql($sql);
					$fallback_park = isset($static_keth_data[$i]['park']) ? $static_keth_data[$i]['park'] : 1;
					$park_idx = (isset($cp[0]['pvn_park']) && $cp[0]['pvn_park'] > 0) ? ($cp[0]['pvn_park'] - 1) : ($fallback_park - 1);
					$cp_total = isset($cp[0]['COUNT(*)']) ? $cp[0]['COUNT(*)'] : 0;
				?>
				<div id="keth_<?= $i;?>" class="extra-sideshow-inner" style="border:solid 2px;padding:10px 5px;margin:0 0 5px 0"><?php
					echo "<h3 class='div-h' style='font-weight:600;color:".$color[$park_idx]."'><b class='keth-name' onclick='showKeth(".$i.")'>".$keth.$i.' </b><!-- (จำนวน <b class="keth-value" onclick=showKethDetail('.$i.') style="color:'.$color2[$park_idx].'">'.$cp_total.'</b> ผัง) --></h3>';
					?>
					<!--
					<h4 class='div-h' style="">สถานะขั้นตอนดำเนินการ - ขั้นตอนย่อย 1</h4>
					<div style="width:100%">
						<div style="display:flex;flex-direction: row;justify-content: center;">
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid red 2px;margin:2px"><text onclick="showKethDetail(<?= $i;?>,1)"  id="s1p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:#2D882D!important;border:solid #979797 2px;margin:2px"><text onclick="showKethDetail(<?= $i;?>,2)"  id="s2p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:yellow!important;border:solid #979797 2px;margin:2px"><text onclick="showKethDetail(<?= $i;?>,3)"  id="s3p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:orange!important;border:solid #979797 2px;margin:2px"><text onclick="showKethDetail(<?= $i;?>,4)"  id="s4p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:red!important;border:solid #979797 2px;margin:2px"><text onclick="showKethDetail(<?= $i;?>,5)"  id="s5p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid black 2px;margin:2px"><text onclick="showKethDetail(<?= $i;?>,6)"  id="s6p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:white!important;border:solid #979797 2px;margin:2px 2px 2px 10px"><i style="margin: 3px 0;float:left;color:red" class="fa fa-exclamation"></i><text onclick="showKethDetail(<?= $i;?>,7)"  id="s7p<?= $i;?>"></text></div>
							<div style="text-align:center;padding:3px;height:30px;width:10%;background:linear-gradient(90deg,#0000ff 0%,#0000ff 25%,#ffffff 25%,#ffffff 100%) !important;;border:solid #979797 2px;margin:2px"><text onclick="showKethDetail(<?= $i;?>,8)"  id="s8p<?= $i;?>"></text></div>
						</div>
						
					</div>
					-->
					<div style="margin-top:8px" class="table-responsive">
					<?php
					$sql = "SELECT pvn_id, pvn_name, pvn_park, COUNT(*) FROM tb_cityplan , ref_province WHERE cp_province = pvn_id AND pvn_keth ='".$i."' GROUP BY pvn_id, pvn_name, pvn_park";
					$result = selectSql($sql);
					if (empty($result)) {
						$result = isset($static_keth_data[$i]['provinces']) ? $static_keth_data[$i]['provinces'] : [];
					}
					$all_data = count($result);
					$p_idx = (isset($result[0]['pvn_park']) && $result[0]['pvn_park'] > 0) ? ($result[0]['pvn_park'] - 1) : $park_idx;
				?>
					
						<table id="table_keth_<?= $i;?>" class="table" >
							<tbody>
								<?php
									$x=0;
									for($data=0;$data<$all_data;){
										echo "<tr style='background:transparent'>";
										for($td=1;$td<=5;$td++,$data++){
												$x++;
												if($x<=count($result)){
													$pvn_name = trim($result[$data]['pvn_name']);
													$pvn_url = isset($province_links[$pvn_name]) ? $province_links[$pvn_name] : (isset($keth_links[$i]) ? $keth_links[$i] : '#');
													echo "<td class='set-td set-tdx' style='border:solid ".$color[$p_idx]." 1px;background:transparent'><a href='".$pvn_url."' target='_blank' rel='noopener noreferrer' class='keth-pvn-".($result[$data]['pvn_park']-1)."' style='text-decoration:none;'><!-- onclick='showProvince(".$result[$data]['pvn_id'].")' -->".$result[$data]['pvn_name']." <!-- <b style='color:black'>(".$result[$data]['COUNT(*)'].")</b> --></a></td>";
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
				<script>
					$('#keth_<?= $i;?>').css('border','solid 2px <?= $color[$p_idx];?>');
					$('#keth_<?= $i;?> > h3').css('color','<?= $color[$p_idx];?>');
					$('#keth_<?= $i;?> > h3 > b:first-child').css('color','<?= $color[$p_idx];?>');
					$('#keth_<?= $i;?> > h3 > b:last-child').css('color','<?= $color2[$p_idx];?>');
					$('#table_keth_<?= $i;?> > tbody > tr > td').css('border','solid 1px <?= $color[$p_idx];?>');
				</script>
				<?php } ?>
				</div>
			</div>
		
	</div>
	<div id="load_scricpt"></div>
	<div id="extra" class="table-responsive"></div>
	<script>
	$('.north').click( function(event){
		alert('north');
	});
	
	function showProvince(pvn){
		$.ajax({
			url : 'report/show_detail_x.php',
			async : true,
			type : 'POST',
			data : {
				sub_type : 'province',
				be_type : 'keth',
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
	
	function showKeth(keth){
		$.ajax({
			url : 'app/show_page_park-keth.php',
			async : true,
			type : 'POST',
			data : {
				sub_type : 'keth',
				keth : keth
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
	
	
	LoadDataStatus();
	function LoadDataStatus(){
		$.ajax({
			url : 'app/show_keth.php',
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
	
	function showProvince(pvn){
		$.ajax({
			url : 'report/show_detail_x.php',
			async : true,
			type : 'POST',
			data : {
				sub_type : 'province',
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