<style>
h1,h2,h3,h4,h5,h6 {
	font-family:Kanit !important;
	color:white;
	display:inline;
}
@media print{
	body{
		-webkit-print-color-adjust:exact !important;
		print-color-adjust:exact !important;
		
	}
	.t-val-light {
		border-bottom:transparent !important;
		color:white!important;
	}.t-val-dark{
		border-bottom:transparent !important;
		color:black!important;
	}
	#myhead{
		display:none;
	}
	#mainshow{
		zoom: 50%!important;
	}
	#b6,#b10{
		margin-top:-124px!important;
	}
	#b8{
		margin-top:-259.5px!important;
	}
	#data_date{
		position:absolute!important;
		font-size:24px!important;
		right:0!important;
	}
	#treeshow{
		margin-top: 50px!important;
	}
	#hr1{
		//display:none!important;
		margin:0px 0px 0px 0px!important;
		border:transparent!important;
	}
	@page{
		size:A4 landscape;
		margin:7.5mm 15mm 11mm 15mm!important;
	}
	@pages {
		@bottom-center{
			content:elements(body);
			color:red!important;
		}
	}
}

.box-header{
	text-align:center;
	background-color: #05445E!important;
	border-radius:24px;
	border:solid 1px black;
	margin-bottom:25px;
	padding:15px 0px;
	color:white!important;
}
.box-header > h2 {
	color:white!important;
}
.box-1{
	width:200px;
	background-color: #0099e6;
	border-radius:8px;
	border:solid 3px black;
	padding:12px 4px 12px 4px;
	text-align:center;
	margin-top:234.5px;
	display:inline-block;
}

.root{
	margin-top:284.39px;
	content:"";
	position:absolute;
	border-bottom:solid 5px black;
	width:25px;
}

.root-v1{
	margin-top:-200px;
	position: absolute;
	min-height: 400px;
	border-left: solid 5px black;
	margin-left:20px;
}

.box-2{
	position:absolute;
	width:190px;
	background-color: #0099e6;
	border-radius:8px;
	border:solid 1px black;
	padding:15px 4px 15px 4px;
	text-align:center;
	margin-left:80px;
	
}
.box-3{
	width:500px;
	background-color: #0099e6;
	border-radius:8px;
	border:solid 2px black;
	padding:12px 4px 12px 4px;
	text-align:center;
	display:block;

}
.box-4{
	width:200px;
	background-color: #0099e6;
	border-radius:8px;
	border:solid 1px black;
	padding:15px 4px 15px 4px;
	text-align:center;

}

.box-5{
	width:120px;
	background-color: #0099e6;
	border-radius:8px;
	border:solid 1px black;
	padding:15px 4px 15px 4px;
	text-align:center;
	color:white;
}
.r5-1{
	background-color:;
	margin-left:1px;
}
.r5-2{
	background-color:;
	margin-left:140px;
}
.box-3-container{
	position:absolute;
	text-align:center;
	margin-left:80px;
}
.lv-1{
	margin-left:-52px;
	margin-top:0px;
	position:absolute;
	
}

.lv-2{
	margin-left:-52px;
	margin-top:200px;
	position:absolute;
}

.lv-3{
	margin-left:-52px;
	margin-top:395px;
	position:absolute;
}
.root-box-2{
	margin-left:50px;
	position:absolute;
	border-top:solid 5px black;
	width:40px;
}
.root-box-3{
	margin-left:50px;
	position:absolute;
	border-top:solid 4px black;
	width:40px;
}
.root-box-4{
	margin-left:50px;
	position:absolute;
	border-top:solid 3px black;
	width:40px;
}
.root-box-5{
	margin-left:50px;
	position:absolute;
	border-top:solid 2px black;
	width:40px;
}
.x{
	margin-left:50px;
	border-top:solid 2px black;
}
.root-2{
	margin-left:270px;
	margin-top:0px;
	content:"";
	position:absolute;
	border-top:solid 4px black;
	width:25px;
}
.root-v2{
	margin-top:-160px;
	position: absolute;
	min-height: 320px;
	border-left: solid 4px black;
	margin-left:23px;
}
.root-3{
	margin-left:270px;
	margin-top:0px;
	content:"";
	position:absolute;
	border-top:solid 3px black;
	width:25px;
}
.root-v3{
	margin-top:-1px;
	position: absolute;
	min-height: 274px;
	border-left: solid 3px black;
	margin-left:23px;
}

.root-4{
	margin-left:270px;
	margin-top:0px;
	content:"";
	position:absolute;
	border:solid 1px black;
	width:25px;
}
.root-v4{
	margin-top:-69px;
	position: absolute;
	min-height: 140px;
	border-left: solid 2px black;
	margin-left:23px;
}


.lv-1-2{
	margin-left:-52px;
	margin-top:0px;
	position:absolute;
	
}

.lv-2-2{
	margin-left:-52px;
	margin-top:156px;
	position:absolute;
}

.lv-3-2{
	margin-left:-52px;
	margin-top:316px;
	position:absolute;
}

.lv-1-3{
	margin-left:-52px;
	margin-top:-2px;
	position:absolute;
	
}

.lv-2-3{
	margin-left:-52px;
	margin-top:272px;
	position:absolute;
}

.lv-1-4{
	margin-left:-52px;
	margin-top:-0px;
	position:absolute;
	
}

.lv-2-4{
	margin-left:-52px;
	margin-top:139px;
	position:absolute;
}
.b-pointer:hover{
	cursor: pointer;
}
.b-default:hover{
	cursor: default;
}
@media screen and (max-width: 767px){
	.t-header {
		font-size:20px;
	}
}
@media screen and (max-width: 767px){
	.t-header {
		font-size:20px;
		padding-left:5px;
		padding-right:5px;
		overflow-x:auto;
	}
}
.t-val-light{
	font-weight:600;
}
.t-val-dark{	
	font-weight:600;
}
.t-val-light{
	cursor: pointer;
	border-bottom:solid 2px white;
	font-weight:700;
}

.t-val-dark{
	cursor: pointer;
	border-bottom:solid 2px black;
	font-weight:700;
}
.t-val-light:hover{
	font-size:20px;
	animation: light-color-change 1s infinite
}
@keyframes light-color-change {
  0% { color: white;border-bottom:white 2px solid }
  50% { color: black;border-bottom:black 2px solid}
  100% { color: white;border-bottom:white 2px solid}
}
.t-val-dark:hover{
	font-size:20px;
	animation: dark-color-change 1s infinite
}
@keyframes dark-color-change {
  0% { color: black;border-bottom:black 2px solid }
  50% { color: white;border-bottom:white 2px solid}
  100% { color: black;border-bottom:black 2px solid}
}
text{
	font-size:18px;
}
.pre-value{
	font-size:15px;
}
.pre-value-h{
	font-size:18px
}
.pre-value-h > text{
	font-size:24px
}

h3 > .t-val-light:hover{
	font-size:28px;
	animation: light-color-change 1s infinite;
}
.myhead:hover{
	animation: myhead-h 1s infinite;
	cursor:pointer;
}
@keyframes myhead-h {
	0%{color:black}
	50%{color:#0AAAFA}
	100%{color:black}
	
}
.vmainbox{
	position:relative;
	display:block;
	margin:10px auto 0px auto;
	border:solid 4px black;
	background-color:blue;
	border-radius:8px;
	padding:15px 10px;
	width:auto;
	min-width:190px;
	max-width:250px;
	text-align:center;
}

.veroot{
	border: solid 2px black;height: 25px;width: 0px;margin: 0 auto;
	position:relative;
	height:30px;
	
}
.vebar{
	margin:0 auto;
	border:solid 2px black;
	width:614px;
}

.seq-l{
	margin-top:0px;
	margin-left:0px;
}
.seq-c{
	margin-top:-2px;
	margin-left: auto;
	margin-right: auto;
}
.seq-r{
	margin-top:-106px;
	margin-left:610px;
}
.verbox{
	position: relative; 
	width: 190px;  
	background-color: #0099e6;    
	border-radius: 8px;    
	border: solid 1px black;    
	padding: 15px 4px 15px 4px;    
	text-align: center;    
	margin-left: 0px;
	display:block;
}
.vshow{
	//background-color:blue;
	width:fit-content;
	display:block;
}

@media screen and (max-width: 550px){
	.short-550:after{		
		content:'คลิกเพื่อแสดงหมายเหตุ';
	}
	.short-550 text{
		display:none;
	}
	
}
@media screen and (max-width: 332px){
	.short-550:after{		
		content:'';
	}
	.short-550 text{
		display:none;
	}
	
}

</style>

<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
clear_cp_session();
$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 2 OR cp_stage = 4 AND cp_eec != 'Y'";
$eva = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 2 AND cp_eec !='Y'"; 
$eva5 = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 4 AND cp_eec !='Y'"; 
$eva10 = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_eec !='Y'";
$edit = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 3 AND cp_effective = 1 AND cp_eec != 'Y'";
$bbf = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 3 AND cp_effective = 2 AND cp_eec != 'Y'";
$exp = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND (cp_stage = 2 OR cp_stage = 3 OR cp_stage = 4) AND cp_eec != 'Y'";
$notice = selectSql($sql);

$sql = 'SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 1 AND cp_eec !="Y" ';
$new = selectSql($sql);


$sql = 'SELECT COUNT(*) FROM tb_cityplan WHERE cp_eec = "Y" ';
$eec = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan";
$cityplan = selectSql($sql);


$sql = "SELECT cp_owner , cp_effective ,COUNT(*) FROM tb_cityplan WHERE cp_stage = 3 AND cp_effective = 1 AND cp_eec !='Y' GROUP BY cp_owner ASC";
$bbfY = selectSql($sql);

$sql = "SELECT cp_owner , cp_effective ,COUNT(*) FROM tb_cityplan WHERE cp_stage = 3 AND cp_effective = 2 AND cp_eec !='Y' GROUP BY cp_owner ASC";
$expY = selectSql($sql);

$sql = "SELECT cp_owner, COUNT(*) FROM tb_cityplan WHERE cp_stage = 1 AND cp_eec !='Y' GROUP BY cp_owner ASC";
$newY = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 1 OR cp_eec = 'Y'";
$new_eec = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_eec = 'Y' AND cp_owner = '1'";
$eecY_1 = selectSql($sql);

$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_eec = 'Y' AND cp_owner = '2'";
$eecY_2 = selectSql($sql);

// ดึงข้อมูลแยกหน่วยงานสำหรับ: สิ้นสุดบังคับใช้
$sql_exp = "SELECT cp_owner, COUNT(*) as cnt FROM tb_cityplan WHERE cp_stage = 3 AND cp_effective = 2 AND cp_eec != 'Y' GROUP BY cp_owner";
$res_exp = selectSql($sql_exp);
$exp_dept = ['1' => 0, '2' => 0];
foreach($res_exp as $r) { $exp_dept[$r['cp_owner']] = $r['cnt']; }

// ดึงข้อมูลแยกหน่วยงานสำหรับ: ปรับปรุงผัง
$sql_edit = "SELECT cp_owner, COUNT(*) as cnt FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3 AND cp_eec != 'Y' GROUP BY cp_owner";
$res_edit = selectSql($sql_edit);
$edit_dept = ['1' => 0, '2' => 0];
foreach($res_edit as $r) { $edit_dept[$r['cp_owner']] = $r['cnt']; }

$sub_eec = 1;
$sub_province = 70;
$mainbox = $cityplan[0]['COUNT(*)']+$sub_province+$sub_eec;
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
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap');

    /* Container หลักสำหรับการเลื่อนบนมือถือ */
    .tree-outer-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        background-color: #f4f9f7;
        padding-bottom: 30px;
    }

    .tree-wrapper {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        padding: 60px 40px;
        font-family: 'Kanit', sans-serif;
        position: relative;
        min-width: 1250px; /* เพิ่มความกว้างเพื่อรองรับระยะห่างที่มากขึ้น */
        margin: 0 auto;
    }

    .column { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        position: relative;
        flex-shrink: 0;
    }

    /* คอลัมน์ที่ 4 (ประเมิน/ปรับปรุง) ขยับขวาเพิ่มเพื่อเว้นระยะ */
    .column-last {
        margin-left: 60px; 
    }

    .column-1 { align-items: flex-start; padding-left: 60px; }

    /* Node Style */
    .node {
        width: 200px;
        padding: 15px 10px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        z-index: 5;
        position: relative;
        transition: transform 0.2s;
    }
    .node h4 { margin: 0; font-size: 16px; font-weight: 500; line-height: 1.4; }
    .node p { margin: 8px 0 0; font-weight: bold; font-size: 17px; }

    /* เส้นเชื่อมแนวตั้ง Column 1 */
    .column-1::before {
        content: '';
        position: absolute;
        top: 60px; 
        left: 35px; 
        width: 2px;
        height: 285px; 
        background: #333;
        z-index: 1;
    }

    .side-arrow {
        position: absolute;
        left: -25px; 
        top: 52%;
        width: 25px;
        height: 2px;
        background: #333;
    }
	
	.side-arrow::after {
        content: '';
        position: absolute;
        right: -2px;
        top: -4px;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 8px solid #333;
    }
	
	  .side-arrow2 {
        position: absolute;
        left: -25px; 
        top: 49%;
        width: 25px;
        height: 2px;
        background: #333;
    }
	
    .side-arrow2::after {
        content: '';
        position: absolute;
        right: -2px;
        top: -4px;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 8px solid #333;
    }

    .arrow-down {
        width: 2px;
        height: 35px;
        background: #333;
        position: relative;
    }
    .arrow-down::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: -4px;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 8px solid #333;
    }

    /* ลูกศรเชื่อมคอลัมน์หลัก */
    .arrow-right-main {
        flex-shrink: 0;
        align-self: center;
        width: 50px;
        height: 2px;
        background: #333;
        position: relative;
        margin-top: -315px; 
		margin-left: -15px;
    }
	 .arrow-right-main::after {
        content: '';
        position: absolute;
        right: -2px;
        top: -4px;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 8px solid #333;
    }
.arrow-right-main2 {
    flex-shrink: 0;
    align-self: center;
    width: 55px;
    height: 2px;
    background: #333;
    position: relative;
    margin-top: -315px;
    margin-left: -7px;
}
    .arrow-right-main2::after {
        content: '';
        position: absolute;
        right: -5px;
		
        top: -4px;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 8px solid #333;
    }

    /* เส้นเชื่อมโยงไปทางขวาตามสั่ง */
    .connector-to-next {
        position: absolute;
        top: 50%;
        left: 100%;
        width: 78px; /* เพิ่มระยะห่างลูกศร */
        height: 2px;
        background: #333;
        z-index: 1;
    }
    .connector-to-next::after {
        content: '';
        position: absolute;
        right: -2px;
        top: -4px;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 8px solid #333;
    }

    /* ตกแต่งหน่วยงาน */
    .dept-node {
        width: 105px;
        padding: 8px 4px;
        border-radius: 8px;
        font-size: 12px;
        text-align: center;
        background: #fff;
        margin-top: 5px;
    }
    .border-black { border: 2px dashed #000 !important; color: #000; }
    .border-blue { border: 2px dashed #0056b3 !important; color: #0056b3; }

    .split-box { display: flex; flex-direction: column; align-items: center; width: 100%; }
    .stem { width: 2px; height: 15px; background: #333; }
    .bar { width: 120px; height: 2px; background: #333; }
    .legs { display: flex; justify-content: space-between; width: 120px; height: 10px; }
    .leg { width: 2px; height: 100%; background: #333; position: relative; }
    .leg::after { 
        content: ''; position: absolute; bottom: -2px; left: -4px;
        border-left: 5px solid transparent; border-right: 5px solid transparent;
        border-top: 7px solid #333;
    }

    .t-val-light { cursor: pointer; border-bottom: 2px solid rgba(255,255,255,0.6); }
    .t-val-dark { cursor: pointer; border-bottom: 2px solid rgba(0,0,0,0.3); }
    .sub-note { font-size: 11px; color: #444; margin-top: 5px; font-weight: normal; text-align: left; }

    @media (max-width: 768px) {
        .instruction-scroll { display: block; text-align: center; font-size: 12px; color: #888; margin: 10px 0; }
    }
    @media (min-width: 769px) { .instruction-scroll { display: none; } }
	@media (min-width: 1500px) {
	.margin {
		margin-left:220px;
	}
	}
	
	
</style>

<div class="box-header b-default"><h2>ผังกำหนดการใช้ประโยชน์ที่ดิน</h2></div>
<div id="myhead" style="position:relative; width:100%; max-width:1177px; padding:0px; margin-top:2px">
    
    <h3 class="hidden-xs hidden-sm" style="color:black; margin:0px; margin-left:10px; margin-top:10px; display: flex; align-items: center;">
        <i onclick="MyHead();" title="คลิกเพื่อแสดงตัวอย่าง" class="myhead fa fa-info-circle" style="cursor:pointer;"></i> 
        <span style="margin-left:15px;">หมายเหตุ : คลิกที่จำนวนผัง เพื่อเปิดลิงค์ไปสู่หน้าแสดงผลย่อย</span>
    </h3>

    <h4 class="hidden-md hidden-lg" style="color:black; margin:0px; margin-top:10px !important; display: flex; align-items: flex-start; padding-left: 15px; line-height: 1.4;">
        <i class="myhead fa fa-info-circle" 
           style="cursor:pointer; margin-right: 10px; margin-top: 3px;" 
           onclick="MyHead();"></i> 
        
        <span class="short-550">
            หมายเหตุ : คลิกที่จำนวนผัง เพื่อเปิดลิงค์ไปสู่หน้าแสดงผลย่อย
        </span>
    </h4>
</div>
    <br>
<div class="tree-outer-container">
<style>
    /* ซ่อนเมื่อหน้าจอกว้างกว่า 1400px (ขนาดคอมพิวเตอร์ทั่วไป) */
    @media (min-width: 1400px) {
        .instruction-scroll {
            display: none !important;
        }
    }
    
    /* แสดงผลเมื่อหน้าจอเล็กกว่า 1300px (มือถือและแท็บเล็ต) */
    @media (max-width: 1300px) {
        .instruction-scroll {
            display: block;
            text-align: center;
			margin-top:15px;
            margin-bottom: 10px;
            color: #555;
        }
    }
</style>

<div class="instruction-scroll">
    <i class="fa fa-arrows-h"></i> เลื่อนซ้าย-ขวา เพื่อดูแผนผังทั้งหมด
</div>

 <div id="treeshow" class="tree-wrapper">
   
        <div class="column column-1 margin">
            <div class="node" style="background-color: #6d4c41; color: white; width: 220px; margin-left: -25px; margin-top:-9px;">
                <h4>ผังเมืองรวม</h4>
                <p>จำนวน <?php echo number_format($mainbox); ?> ผัง</p>
            </div>
            <div class="node" style="background-color: #ffb74d; color: #1a237e; margin-top: 37px;">
                <div class="side-arrow2"></div>
                <h4>ผังเมืองรวมจังหวัด*</h4>
                <p>จำนวน <?php echo number_format($sub_province); ?> ผัง</p>
                <span class="sub-note">*ยกเว้น กรุงเทพฯ นนทบุรี ภูเก็ต สมุทรปราการ ฉะเชิงเทรา ชลบุรี และระยอง</span>
            </div>
            <div class="node" style="background-color: #ffb74d; color: #1a237e; margin-top: 40px;">
                <div class="side-arrow"></div>
                <h4>ผังเมือง EEC</h4>
                <p>จำนวน <?php echo number_format($sub_eec); ?> ผัง</p>
            </div>
        </div>

        <div class="arrow-right-main"></div>

        <div class="column">
            <div class="node" style="background-color: #e65100; color: white;">
                <h4>ผังเมืองรวมเมือง/ชุมชน</h4>
                <p>จำนวน <span class="t-val-light" onclick="showCityplanX('cityplan')"><?php echo number_format($cityplan[0]['COUNT(*)']); ?></span> ผัง</p>
            </div>
            <div class="arrow-down"></div>
            <div class="node" style="background-color: #ffe0b2; color: #333;">
                <h4>เปิดพื้นที่ใหม่</h4>
                <p>จำนวน <span class="t-val-dark" onclick="showCityplanX('new')"><?php echo number_format($new[0]['COUNT(*)']); ?></span> ผัง</p>
            </div>
            <div class="arrow-down"></div>
            <div class="node" style="background-color: #ffe0b2; color: #333;">
                <h4>ในเขตพื้นที่ EEC</h4>
                <p>จำนวน <span class="t-val-dark" onclick="showCityplanX('eec')"><?php echo number_format($eec[0]['COUNT(*)']); ?></span> ผัง</p>
            </div>
        </div>

        <div class="arrow-right-main2"></div>

        <div class="column">
            <div class="node" style="background-color: #66bb6a; color: #1b5e20; margin-left:-10px;">
                <h4>ประกาศบังคับใช้</h4>
                <p>จำนวน <span class="t-val-light" onclick="showCityplanX('notice')"><?php echo $bbf[0]['COUNT(*)']; ?></span> ผัง</p>
                <div class="connector-to-next"></div>
            </div>
            
            <div class="arrow-down"></div> 

            <div class="node" style="background-color: #9e9e9e; color: white;">
                <h4>สิ้นสุดบังคับใช้</h4>
                <p>จำนวน <span class="t-val-light" onclick="showCityplanX('exp')"><?php echo number_format($exp[0]['COUNT(*)']); ?></span> ผัง</p>
                <div class="connector-to-next"></div>
            </div>

            <div class="split-box">
                <div class="stem"></div><div class="bar"></div>
                <div class="legs"><div class="leg"></div><div class="leg"></div></div>
            </div>
            <div style="display: flex; gap: 10px;">
                <div class="dept-node border-black">ดำเนินการโดยกรม.<br>จำนวน <?php echo number_format($exp_dept['1']); ?> ผัง</div>
                <div class="dept-node border-blue">ดำเนินการโดยอปท.<br>จำนวน <?php echo number_format($exp_dept['2']); ?> ผัง</div>
            </div>
        </div>

        <div class="column column-last">
            <div class="node" style="background-color: #66bb6a; color: #1b5e20; margin-left:-10px;">
                <h4>ประเมินผลผัง</h4>
                <p>จำนวน <span class="t-val-dark" onclick="showEvaX('eva')"><?php echo number_format($eva[0]['COUNT(*)']); ?></span> ผัง</p>
            </div>
            
            <div class="arrow-down"></div>

            <div class="node" style="background-color: #66bb6a; color: #1b5e20;">
                <h4>ปรับปรุงผัง</h4>
                <p>จำนวน <span class="t-val-dark" onclick="showCityplanX('edit')"><?php echo number_format($edit[0]['COUNT(*)']); ?></span> ผัง</p>
            </div>

            <div class="split-box">
                <div class="stem"></div><div class="bar"></div>
                <div class="legs"><div class="leg"></div><div class="leg"></div></div>
            </div>
            <div style="display: flex; gap: 10px;">
                <div class="dept-node border-black">ดำเนินการโดยกรม.<br>จำนวน <?php echo number_format($edit_dept['1']); ?> ผัง</div>
                <div class="dept-node border-blue">ดำเนินการโดยอปท.<br>จำนวน <?php echo number_format($edit_dept['2']); ?> ผัง</div>
            </div>
        </div>
    </div>
</div>


		<hr id="hr1" style="border:solid 4px gray;width:100%;margin:35px 0px" >
	<div class="box-header b-default"><h2 class="t-header">ผังเมืองรวมเมือง/ชุมชนอยู่ในขั้นตอนการวางและจัดทำที่ต้องดำเนินการติดตามผัง</h2></div>
	<div style="overflow-x: auto;width:100%;padding:0"> 
		<div style="white-space: nowrap;position:relative ;width:1177px;height:372px;margin:0px auto 0px auto;padding-left:auto;padding-right:auto">
			<div style="position:relative;width:50%;padding-top:15px;float:left">			
				<span class="box-3 b-default" style="position:relative;margin:0px auto;background-color:#477300!important;"><h3 style="color:white!important">ดำเนินการอยู่ในขั้นตอนการปรับปรุงผังฯ</h3><br><h3 class="pre-value-h" style="color:white!important">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('editY')" class="t-val-light"><?php echo $edit[0]['COUNT(*)'];?></text> ผัง</h3></span>
				<div style="margin-top:0px;position:relative;height:25px;border-right:solid black 3px;width:50%"></div>
				<div style="border:solid black 3px; border-bottom:transparent;height:25px;width:294.5px;margin:0 auto;"></div>
				<div style="width:100%">
					<div style="width:294.25px;float:left">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#477300!important" ><h4 style="color:white!important">กฎกระทรวงผังเมืองรวม<br>มีผลบังคับใช้</h4><br><h4 class="pre-value" style="color:white!important">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('bbfY')" class="t-val-light"><?php echo $bbf[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;float:left;display:block;background-color:#79ca4c!important" ><h4 style="color:black">ผังของกรมฯ</h4><br><h4 class="pre-value" style="color:black">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('bbfY-1')" class="t-val-dark"><?= $bbfY[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#79ca4c!important" ><h4 style="color:black">ผังของอปท.</h4><br><h4 class="pre-value" style="color:black">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('bbfY-2')" class="t-val-dark"><?= $bbfY[1]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						</div>
					</div>
					
					<div style="width:294.25px;float:right">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#9f9f9f!important"><h4 style="color:black">กฎกระทรวงผังเมืองรวม<br>สิ้นสุดใช้บังคับ</h4><br><h4 class="pre-value" style="color:black">จำนวน  <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('expY')" class="t-val-dark"><?php echo $exp[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;float:left;display:block;background-color:#c5c5c5!important" ><h4 style="color:black">ผังของกรมฯ</h4><br><h4 style="color:black" class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('expY-1')" class="t-val-dark"><?= $expY[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#c5c5c5!important" ><h4 style="color:black">ผังของอปท.</h4><br><h4 style="color:black" class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('expY-2')" class="t-val-dark"><?= $expY[1]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="position:relative;width:50%;float:right;padding-top:15px">
				<span class="box-3 b-default" style="position:relative;margin:0px auto;background:#0b7cd3!important"><h3 style="color:white!important">ดำเนินการอยู่ในขั้นตอนการวางและจัดทำ</h3><br><h3 class="pre-value-h" style="color:white!important">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('new_eec')"class="t-val-light"><?php echo $new_eec[0]['COUNT(*)'];?></text> ผัง</h3></span>
				<div style="margin-top:0px;position:relative;height:25px;border-right:solid black 3px;width:50%"></div>
				<div style="border:solid black 3px; border-bottom:transparent;height:25px;width:294.5px;margin:0 auto;"></div>
				<div style="width:100%">
					<div style="width:294.25px;float:left">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#0b7cd3!important;"><h4 style="color:white!important">ผังเมืองรวมเมือง/ชุมชน<br>เปิดพื้นที่ใหม่</h4><br><h4 class="pre-value" style="color:white!important">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('new')" class="t-val-light"><?php echo $new[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;float:left;display:block;background-color:#54b5ff!important" ><h4 style="color:black">ผังของกรมฯ</h4><br><h4 class="pre-value" style="color:black">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('newY-1')" class="t-val-dark"><?= $newY[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#54b5ff!important" ><h4 style="color:black">ผังของอปท.</h4><br><h4 class="pre-value" style="color:black">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('newY-2')" class="t-val-dark"><?= $newY[1]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						</div>
					</div>
					
					<div style="width:294.25px;float:right">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#0b7cd3!important;"><h4 style="color:white!important">	ผังเมืองรวมเมือง/ชุมชน</h4><br><h4 class="pre-value" style="color:white!important">ในเขตพื้นที่ ECC<br>จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY')" class="t-val-light"><?php echo $eec[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
						<?php if($eecY_2[0]['COUNT(*)']==0){ ?>
							<div style="border-right:solid black 2px;width:50%;height:50px"></div>
							<div style="display:inline;width:100%;">
								<span class="box-5 b-default" style="position:relative;margin:0px auto;display:block;background-color:#54b5ff!important"><h4 style="color:black">ผังของกรมฯ</h4><br><h4 class="pre-value" style="color:black">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY-1')" class="t-val-dark"><?php echo $eecY_1[0]['COUNT(*)'];?></text> ผัง</h4></span>
						<?php }else{ ?>
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;;float:left;margin:0px auto;display:block;background-color:#54b5ff!important "><h4 style="color:black">ผังของกรมฯ</h4><br><h4 class="pre-value" style="color:black">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY-1')" class="t-val-dark"><?php echo $eec[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#54b5ff!important" ><h4 style="color:black">ผังของอปท.</h4><br><h4 class="pre-value" style="color:black">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY-2')" class="t-val-dark"><?= $eecY_2[0]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	
	<br>
	<div id="old-tree" hidden>
	<div class="box-header b-default"><h2>ผังกำหนดการใช้ประโยชน์ที่ดิน</h2></div>
	<div style="overflow-x: auto;width:100%;padding:0px;"> 		
		<div style="position:relative;width:1177px;padding:0px 0px;margin-top:2px">
			<h3 class="hidden-xs hidden-sm" style="color:black;margin:0px"><i onclick ="MyHead();" title="คลิกเพื่อแสดงตัวอย่าง" class="myhead fa fa-info-circle"></i> <span style="margin-left:4px;margin-top:-2px;position:absolute"> หมายเหตุ : คลิกที่จำนวนผัง เพื่อเปิดลิงค์ไปสู่หน้าแสดงผลย่อย</span></h3>
			<h4 class="hidden-md hidden-lg" style="color:black;margin:0px"><i onclick ="MyHead();" title="คลิกเพื่อแสดงตัวอย่าง" class="myhead fa fa-info-circle"></i> <span style="margin-left:4px;margin-top:-1px;position:absolute"> หมายเหตุ : คลิกที่จำนวนผัง เพื่อเปิดลิงค์ไปสู่หน้าแสดงผลย่อย</span></h4>
		</div>
		<div style="white-space: nowrap;position:relative ;width:1177px;height:532px;margin-left:auto;margin-right:auto">
			<span class="box-1 b-default" style="background-color:#023E86"><h3 style="display:inline-block;margin-top:10px;margin-bottom:10px">ผังเมืองรวม</h3><br><h3 class="pre-value-h">จำนวน <text title="ไม่มีลิงค์" style="font-weight:600;">680</text> ผัง</h3></span>
			<span class="root">
				<span class="root-v1">	
					<span class="lv-2">
						<span class="root-box-2" ></span>
						<span class="box-2 b-default" style="margin-top:-38px;background-color:#57896A"><h4>ผังเมืองรวมเมือง/ชุมชน</h4>
							<br>
							<span><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  class="t-val-light" onclick="showCityplanX('cityplan')" ><?php echo $cityplan[0]['COUNT(*)']; ?></text> ผัง</h4></span>	
						</span>
						<span class="root-2">
							<span class="root-v2">					
							<span class="lv-1-2"> 
								<span class="root-box-3"></span>
								<span class="box-2 b-default" style="margin-top:-49px;background-color:#A0C003; "><h4 style="color:#183003">ผังเมืองรวมเมือง/ชุมชน<br>ประกาศใช้บังคับ</h4>
									<br>
									<span><h4 class="pre-value" style="color:#183003;">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('notice')" class="t-val-dark" style="color:#183003"><?php echo $notice[0]['COUNT(*)'];?></text> ผัง</h4></span>	
								</span>
								<span class="root-3">
								<span class="root-v3">
									<span class="lv-1-3"> 
										<span class="root-box-4"></span>
										<span class="box-2 b-default" style="margin-top:-38px;background-color:#DEBB0C;"><h4 style="color:#573B06 ">ประเมินผลผัง</h4>
											<br>
											<span><h4 class="pre-value" style="color:#573B06 ">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showEvaX('eva')" class="t-val-dark" style="color:#573B06 "><?php echo $eva[0]['COUNT(*)'];?></text> ผัง</h4></span>	
										</span>
										<span class="root-4">
											<span class="root-v4">
												<span class="lv-1-4"> 
													<span class="root-box-5"></span>
													<span class="box-2 b-default" style="margin-top:-48px;background-color:#de830c;"><h4 style="color:white">ประเมินผล<br>(ครบ 5 ปี)</h4>
														<br>
														<span><h4 class="pre-value" style="color:white">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showEvaX('eva5')" class="t-val-light"><?php echo $eva5[0]['COUNT(*)'];?></text> ผัง</h4></span>	
													</span>
												</span>
												
												<span class="lv-2-4"> 
													<span class="root-box-5"></span>
													<span class="box-2 b-default" style="margin-top:-48px;background-color:#ad6807;"><h4 style="color:white">ประเมินผล<br>(ครบ 10 ปี)</h4>
														<br>
														<span><h4 class="pre-value" style="color:white">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showEvaX('eva10')" class="t-val-light"><?php echo $eva10[0]['COUNT(*)'];?></text> ผัง</h4></span>	
													</span>
												</span>
											</span>
										</span>
									</span>							
									
									<span class="lv-2-3"> 
										<span class="root-box-4" ></span>
										<span class="box-2 b-default" style="margin-top:-38px;background-color:#a3ab01;"><h4 style="color:white ">ปรับปรุงผัง</h4>
											<br>
											<span><h4 class="pre-value" style="color:white ">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('edit')" class="t-val-light"><?php echo $edit[0]['COUNT(*)'];?></text> ผัง</h4></span>	
										</span>
										<span class="root-4">
											<span class="root-v4">						
												<span class="lv-1-4"> 
													<span class="root-box-5"></span>
													<span class="box-2 b-default" style="margin-top:-38px;background-color:#7c8200;"><h4 style="color:white">มีผลบังคับใช้</h4>
														<br>
														<span><h4 class="pre-value" style="color:white">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('bbf')" class="t-val-light"><?php echo $bbf[0]['COUNT(*)'];?></text> ผัง</h4></span>	
													</span>
												</span>
												
												<span class="lv-2-4"> 
													<span class="root-box-5"></span>
													<span class="box-2 b-default" style="margin-top:-38px;background-color:#585937;"><h4 style="color:white">สิ้นสุดใช้บังคับ</h4>
														<br>
														<span><h4 class="pre-value" style="color:white">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('exp')" class="t-val-light"><?php echo $exp[0]['COUNT(*)'];?></text> ผัง</h4></span>	
													</span>
												</span>
											</span>
										</span>
									</span>
								</span>
								
								
							</span>	
							</span>
							
							
							<span class="lv-2-2"> 
								<span class="root-box-3"></span>
								<span class="box-2 b-default" style="margin-top:-49px;background-color:#59981A;"><h4>ผังเมืองรวมเมือง/ชุมชน<br>เปิดพื้นที่ใหม่</h4>
									<br>
									<span><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('new')" class="t-val-light"><?php echo $new[0]['COUNT(*)']; ?></text> ผัง</h4></span>	
								</span>
							</span>
							<span class="lv-3-2"> 
								<span class="root-box-3"></span>
								<span class="box-2 b-default" style="margin-top:-49px;background-color:#175404;"><h4>ผังเมืองรวมเมือง/ชุมชน<br>ในเขตพื้นที่ EEC</h4>
									<br>
									<span><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eec')" class="t-val-light"><?php echo $eec[0]['COUNT(*)'];?></text> ผัง</h4></span>	
								</span>
							</span>
						</span>
						</span>
					</span>
				
					<span class="lv-1"> 
						<span class="root-box-2"></span>
						<span class="box-2 b-default" style="margin-top:-38px;background-color:#167D7E;"><h4>ผังเมืองรวมจังหวัด</h4>
							<br>
							<span><h4 class="pre-value">จำนวน <text title="ไม่มีลิงค์" style="font-weight:600;">70</text> ผัง</h4></span>	
						</span>
					</span>	
					
					<span class="lv-3">
						<span class="root-box-2"></span>
						<span class="box-2 b-default" style="margin-top:-38px;background-color:#283708;"><h4>ผังเมือง EEC</h4>
							<br>
							<span><h4 class="pre-value">จำนวน <text title="ไม่มีลิงค์" style="font-weight:600;">1</text> ผัง</h4></span>	
						</span>
					</span>
				</span>
			</span> 
		</div>
	</div>
	<hr style="border:solid 4px gray;width:100%;margin:35px 0px" >
	<div class="box-header b-default"><h2 class="t-header">ผังเมืองรวมเมือง/ชุมชนอยู่ในขั้นตอนการวางและจัดทำที่ต้องดำเนินการติดตามผัง</h2></div>
	<div style="overflow-x: auto;width:100%;padding:0"> 
		<div style="white-space: nowrap;position:relative ;width:1177px;height:372px;margin:0px auto 0px auto;padding-left:auto;padding-right:auto">
			<div style="position:relative;width:50%;padding-top:15px;float:left">			
				<span class="box-3 b-default" style="position:relative;margin:0px auto;background-color:#a3ab01;"><h3>ดำเนินการอยู่ในขั้นตอนการปรับปรุงผังฯ</h3><br><h3 class="pre-value-h">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('editY')" class="t-val-light"><?php echo $edit[0]['COUNT(*)'];?></text> ผัง</h3></span>
				<div style="margin-top:0px;position:relative;height:25px;border-right:solid black 3px;width:50%"></div>
				<div style="border:solid black 3px; border-bottom:transparent;height:25px;width:294.5px;margin:0 auto;"></div>
				<div style="width:100%">
					<div style="width:294.25px;float:left">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#7c8200" ><h4>กฎกระทรวงผังเมืองรวม<br>มีผลบังคับใช้</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('bbfY')" class="t-val-light"><?php echo $bbf[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;float:left;display:block;background-color:#767a2f" ><h4>ผังของกรมฯ</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('bbfY-1')" class="t-val-light"><?= $bbfY[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#4c5000" ><h4>ผังของอปท.</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('bbfY-2')" class="t-val-light"><?= $bbfY[1]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						</div>
					</div>
					
					<div style="width:294.25px;float:right">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#585937"><h4>กฎกระทรวงผังเมืองรวม<br>สิ้นสุดใช้บังคับ</h4><br><h4 class="pre-value">จำนวน  <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('expY')" class="t-val-light"><?php echo $exp[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;float:left;display:block;background-color:#77784b" ><h4>ผังของกรมฯ</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('expY-1')" class="t-val-light"><?= $expY[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#3f4023" ><h4>ผังของอปท.</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('expY-2')" class="t-val-light"><?= $expY[1]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="position:relative;width:50%;float:right;padding-top:15px">
				<span class="box-3 b-default" style="position:relative;margin:0px auto;background:linear-gradient(to right , #538D18 50%, #175404 51%)"><h3>ดำเนินการอยู่ในขั้นตอนการวางและจัดทำ</h3><br><h3 class="pre-value-h">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('new_eec')"class="t-val-light"><?php echo $new_eec[0]['COUNT(*)'];?></text> ผัง</h3></span>
				<div style="margin-top:0px;position:relative;height:25px;border-right:solid black 3px;width:50%"></div>
				<div style="border:solid black 3px; border-bottom:transparent;height:25px;width:294.5px;margin:0 auto;"></div>
				<div style="width:100%">
					<div style="width:294.25px;float:left">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#538D18;"><h4>ผังเมืองรวมเมือง/ชุมชน<br>เปิดพื้นที่ใหม่</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('new')" class="t-val-light"><?php echo $new[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;float:left;display:block;background-color:#70A254" ><h4>ผังของกรมฯ</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('newY-1')" class="t-val-light"><?= $newY[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#548833" ><h4>ผังของอปท.</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('newY-2')" class="t-val-light"><?= $newY[1]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						</div>
					</div>
					
					<div style="width:294.25px;float:right">
						<div class="box-4 b-default" style="position:relative;margin:0px auto;top:0px;display:block;background-color:#175404;"><h4>	ผังเมืองรวมเมือง/ชุมชน</h4><br><h4 class="pre-value">ในเขตพื้นที่ ECC<br>จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY')" class="t-val-light"><?php echo $eec[0]['COUNT(*)'];?></text> ผัง</h4></div>
						<div style="width:260px;margin:0px auto">
						<?php if($eecY_2[0]['COUNT(*)']==0){ ?>
							<div style="border-right:solid black 2px;width:50%;height:50px"></div>
							<div style="display:inline;width:100%;">
								<span class="box-5 b-default" style="position:relative;margin:0px auto;display:block;background-color:#2a4d1f"><h4>ผังของกรมฯ</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY-1')" class="t-val-light"><?php echo $eecY_1[0]['COUNT(*)'];?></text> ผัง</h4></span>
						<?php }else{ ?>
							<div style="border-right:solid black 2px;width:50%;height:25px"></div>
							<div style="position:relative;border:solid black 2px; width:147.125px ; height:25px;border-bottom:transparent;margin:0px auto"></div>
							<div style="display:inline;width:100%">
								<span class="box-5 b-default" style="position:relative;;float:left;margin:0px auto;display:block;background-color:#2a4d1f"><h4>ผังของกรมฯ</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY-1')" class="t-val-light"><?php echo $eec[0]['COUNT(*)'];?></text> ผัง</h4></span>
								<span class="box-5 b-default" style="position:relative;float:right;display:block;background-color:#1e3517" ><h4>ผังของอปท.</h4><br><h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์"  onclick="showCityplanX('eecY-2')" class="t-val-light"><?= $eecY_2[0]['COUNT(*)'];?></text> ผัง</h4></span>
							</div>
						<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" id="modal-swipe" data-bs-backdrop="static" data-bs-keyboard="false" style="pointer-events: none;">
    <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">แนะนำการใช้งาน <text id="display-page"></text> </h4>
            </div>
            <div class="modal-body"  >
				<div id="page-1">
					<div style="text-align:center;">
						<h3 class="hidden-xs hidden-sm" style="color:black" style="margin-top: 25px">กรุณาเลื่อนหน้าจอเพื่อแสดงรายละเอียด</h3><br>
						<h4 class="hidden-xs hidden-sm" style="color:black" style="margin-top: 5px">(สำหรับการแสดงผลบนอุปกรณ์ที่มีหน้าจอขนาดเล็ก)</h4>
						<h4 class="hidden-md hidden-lg" style="color:black" style="margin-top: 15px">กรุณาเลื่อนหน้าจอเพื่อแสดงรายละเอียด</h4><br>
						<h5 class="hidden-md hidden-lg" style="color:black" style="margin-top: 5px">(สำหรับการแสดงผลบนอุปกรณ์ที่มีหน้าจอขนาดเล็ก)</h5>
					</div>
					<img style="margin-top:10px" class="center" src="img/swipe-1.gif">
				</div>
				
				<div id="page-2">
					<div style="text-align:center;">
						<h3 class="hidden-xs hidden-sm" style="color:black" style="margin-top: 25px">คลิกที่จำนวนผัง <br>เพื่อเปิดเข้าไปสู่หน้ารายการแสดงผลผัง</h3>
						<h4 class="hidden-md hidden-lg" style="color:black" style="margin-top: 15px">คลิกที่จำนวนผัง <br>เพื่อเปิดเข้าไปสู่หน้ารายการแสดงผลผัง</h4>
					</div>
					<div style="">
						<div class="box-2 b-default" style="position:relative;margin:25px auto;background-color:#57896A"><h4>ผังเมืองรวมเมือง/ชุมชน</h4>
							<br>
							<span>
								<h4 class="pre-value">จำนวน <text title="คลิกเพื่อเปิดลิงค์" style="border-bottom:solid white 3px ;font-weight:600"><?php echo $cityplan[0]['COUNT(*)']; ?></text> ผัง</h4>
							</span>	
						</div>
						<div style="position:relative;padding:0px auto;width:100%;">
								<!--<img class="center" src="img/click(1).png" style="margin-top:-50px;left:20px;position:relative;z-index:1;width:80px;height:80px;background-color:transparent;">-->
								<img class="center" src="img/clickX.gif" style="margin-top:-50px;left:20px;position:relative;z-index:1;width:80px;height:80px;background-color:transparent;">
						</div>
					</div>
				</div>
				
			</div>
        <div  class="modal-footer">
			<button id="tree-back" onclick="tree('back')" type="button" class="btn btn-warning btn-md pull-left" ><i style="margin:3px 5px 0px 0px ;font-size:16px;" class="fa fa-angle-left"></i>ย้อนกลับ</button>
			<button id="tree-next" onclick="tree('next')"type="button" class="btn btn-success btn-md pull-right" >ถัดไป<i style="margin:3px 0px 0px 5px ;font-size:16px;" class="fa fa-angle-right"></i></button>
			<button id="tree-close" type="button" class="btn btn-danger btn-md pull-right" data-dismiss="modal" ><i style="margin:3px 5px 0px 0px ;font-size:16px;" class="fa fa-times"></i>ปิด</button>
        </div>
		</div>
	</div>
</div>

<script>
topFunction();
tree('back');
$('#exportPdfBtn').show();
$('.myBtn2').hide();
function MyHead(){
	tree('next');
	$('#modal-swipe').modal('show');
}
function tree(action){
	if(action == 'next'){
		$('#tree-back').show();
		$('#tree-close').show();
		$('#tree-next').hide();
		$('#page-1').hide();
		$('#page-2').show();
		$('#display-page').html('(2/2)');
	}else if(action == 'back'){
		$('#tree-back').hide();
		$('#tree-close').hide();
		$('#tree-next').show();
		$('#page-1').show();
		$('#page-2').hide();
		$('#display-page').html('(1/2)');
	}
}
<?php 
		 if(!isset($_SESSION['modal-swipe'])){
			$_SESSION['modal-swipe'] = 1;?>
			$('body').removeClass('sidebar-open');
			setTimeout(function(){
				$('#modal-swipe').modal('show');
			}, 1000);
<?php 	}else{
?>
			$('body').removeClass('sidebar-open');<?php 
		} 
?>
	//alert(<?php echo $_SESSION['modal-swipe'];?>);
	function showCityplanX(type){
		$.post('report/tree-chart-cityplan.php',{
			typeX : type
		},function(data){
			$('#mainshow').html(data);
		});
	}
	function showEvaX(type){
		$.post('report/tree-chart-eva.php',{
			typeX : type
		},function(data){
			$('#mainshow').html(data);
		});
	}
	function changeTree(){
		$('#old-tree').toggle();
		$('#new-tree').toggle();
	}
</script>