<?php
include '../js/function_db.php';
session_name('tutorial');
session_start();
$span_r = [9,4,9,3,3];
$color = ['#979728','#9b487a','#3e6633','#33a1ab','#316aa5'];
$sql = "
	SELECT * 
	FROM tb_cityplan , ref_province , ref_scope , ref_status , ref_stage, ref_effective ,ref_operate_fst ,ref_operate_snd
	WHERE cp_no = '".$_POST['cp_no']."'
	AND cp_province = pvn_id
	AND cp_scope = scope_id
	AND cp_status = status_id
	AND cp_stage = stage_id
	AND cp_effective = eff_id
	AND cp_owner = opt_fst_id
	AND cp_operator = opt_snd_id
	
";
$result = selectSql($sql);
//$title = $result[0]['cp_name'];
$title = 'รายละเอียดผังเมือง';
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
table > thead > tr > th{
	color:white;
	vertical-align:middle;
}
#table_cp_data > tbody > tr:last-child{
	border-bottom-left-radius: 7px;
    border-bottom-right-radius: 7px;
}
#table_cp_data > tbody > tr:last-child > td:first-child{
	border-bottom-left-radius: 7px;
}
#table_cp_data > tbody > tr:last-child > td:last-child{
    border-bottom-right-radius: 7px;
}

#table_cp_data > thead > tr{
	border-top-left-radius: 7px;
    border-top-right-radius: 7px;
}
#table_cp_data > thead > tr > td{
	border-top-left-radius: 7px;
    border-top-right-radius: 7px;
}


* {
  box-sizing: border-box;
}

img {
  vertical-align: middle;
}

/* Position the image container (needed to position the left and right arrows) */
.container {
  position: relative;
}

/* Hide the images by default */
.mySlides {
  display: none;
}

/* Add a pointer when hovering over the thumbnail images */
.cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 40%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: black;
  font-weight: bold;
  font-size: 20px;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.8);
  color:white;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #000000;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* Container for image text */
.caption-container {
  text-align: center;
  background-color: #222;
  padding: 2px 16px;
  color: white;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Six columns side by side */
.column {
  float: left;
  width: 16.66%;
}

/* Add a transparency effect for thumnbail images */
.imgix {
  opacity: 0.6;
}

.active,
.imgix:hover {
  opacity: 1;
}
</style>
<div style="display:inline;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
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
<div>
	<div class="col-xs-12" style="margin-bottom:8px">
		<button class="btn btn-danger" id="back" onclick="goto('a6m')">ย้อนกลับ</button>
	</div>
	
	<?php /*
	<div class="col-xs-8 col-md-7" style="margin-bottom:8px;">
		<div style="border:solid black 1px;border-radius:8px;display:flex;flex-direction:column;padding:5px 7px 7px 7px">
			<div style="text-align:center"><h4 style="font-family:Kanit;font-weight:600">ข้อมูลผังปัจจุบัน</h4></div>
			<div>ชื่อผังเมือง : <?= $result[0]['cp_name'];?></div>
			
			<div>ชื่อจังหวัด : <?= $result[0]['pvn_name'];?></div>
			<div>ขอบเขต : <?= $result[0]['scope_name'];?></div>
			<div>สถานะ : <?= $result[0]['status_name'];?></div>
			<div>ขั้นตอน : <?= $result[0]['stage_name'];?></div>
			<div>มีผลบังคับใช้ : <?= $result[0]['eff_name'];?></div>
			
			<div>เจ้าของ : <?= $result[0]['opt_fst_name'];?></div>
			<div>ผู้ดำเนินการ : <?= $result[0]['opt_snd_name'];?></div>
			<div>ขั้นตอนดำเนินการ : <?= $result[0]['cp_step'].'.'.$result[0]['cp_step_1'].'.'.$result[0]['cp_step_2'];?></div>
			<div>วันที่ประกาศบังคับใช้ : <?= $result[0]['cp_date_notice'];?></div>
			<div>วันที่สิ้นสุดบังคับใช้ : <?= $result[0]['cp_date_exp'];?></div>
			
		</div>
	</div>*/ ?>
	

	<?php
	$filename = '../img/cityplan/'.$result[0]['cp_no'].'.png';
	if(file_exists($filename)) {
		//echo "The file $filename exists";
		$cp_img = 1;
		$all_img = 2;
	} else {
		//echo "The file $filename does not exist";
		$cp_img = 0;
		$all_img = 1;
		$_SESSION['cp_no'] = $_POST['cp_no'];
	}
	?>
	<div class="row" style="padding:0 15px">
	<div class="col-xs-12 col-sm-4 col-md-5" style="margin-bottom:8px;text-align:center">
		<?php /*<img src="img/province/<?= $result[0]['cp_province'].'.png'?>" width="auto" style="max-height:420px">*/?>
		<div style="width:100%">
			<div class="mySlides">
				<div class="numbertext">1 / <?= $all_img;?></div>
				<img src="img/province/<?= $result[0]['pvn_id']?>.png" style="width:60%">
			</div>
			<?php if($cp_img==0){ ?>
				
			<?php }else{?>
			<div class="mySlides">
				<div class="numbertext">2 / <?= $all_img;?></div>
				<img src="img/cityplan/<?= $_POST['cp_no']?>.png" style="width:100%">
			</div>
			<?php } ?>
			
			<?php if($all_img<2){ ?>
				
			<?php }else{?>	
				<a class="prev" style="left:15px" onclick="plusSlides(-1)">❮</a>
				<a class="next" style="right:15px" onclick="plusSlides(1)">❯</a>
			<?php } ?>
			<div class="caption-container" style="padding:0px 2px;">
				<p style="padding:2px 0px"id="caption"></p>
			</div>

			<div style="display:flex;justify-content: space-around;flex-direction: row;">
				<?php 
				if($cp_img==0){?>
					<div style="display:none"class="column">
						<img class="imgix cursor" src="img/province/59.png" style="width:100%" onclick="currentSlide(1)" alt="จังหวัด<?= $result[0]['pvn_name'];?>">
					</div>
				<?php if($_SESSION['data_access']==9){ ?>
					<button id="upload_img" class="btn btn-default" onclick="addCPIMG(<?= $_POST['cp_no'];?>)">อัปโหลดรูปภาพผังเมือง</button>
				<?php }
				}
				else{ ?>
					<div class="column">
						<img class="imgix cursor" src="img/province/59.png" style="width:100%" onclick="currentSlide(1)" alt="จังหวัด<?= $result[0]['pvn_name'];?>">
					</div>
					
					<div class="column">
						<img class="imgix cursor" src="img//cityplan/<?= $_POST['cp_no']?>.png" style="width:100%" onclick="currentSlide(2)" alt="<?= $result[0]['cp_name']?>">
					</div><?php 
				}
				?>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-8 col-md-7" style="margin-bottom:8px;">
		<div style="border:solid black 1px;border-radius:9px;display:flex;flex-direction:column;padding:0px">
			<div style="text-align:center"></div>
			<div style="border-radius:8px;">
			<table id="table_cp_data"class="table" style="margin:0px;">
			<thead>
				<tr>
					<td style="text-align:center;<?= 'background:'.$color[$result[0]['pvn_park']-1];?>" colspan="2"><h4 style="font-family:Kanit;font-weight:600"><?= $result[0]['cp_name'];?></h4></td>
				</tr>
			</thead>
			<tbody>
				<tr><td style="max-width:170px;">จังหวัด</td><td style="width:auto;"><?= $result[0]['pvn_name'];?></td></tr>
				<tr><td>ปีงบประมาณ</td><td><?= $result[0]['cp_budget'];?></td></tr>
				<tr><td>ประเภทผังเมือง</td><td><?php if($result[0]['cp_type']==1){echo "ผังนโยบายการใช้ประโยชน์พื้นที่";}else{echo "ผังกำหนดการใช้ประโยชน์ที่ดิน";}?></td></tr>
				<tr><td>ผังกลุ่ม eec</td><td><?php if($result[0]['cp_eec']=='Y'){ echo  'ใช่';}else{echo 'ไม่ใช่';}?></td></tr>
				<tr><td>ขอบเขต</td><td><?= $result[0]['scope_name'];?></td></tr>
				<tr><td>สถานะ</td><td><?= $result[0]['status_name'];?></td></tr>
				<tr><td>ขั้นตอน</td><td><?= $result[0]['stage_name'];?></td></tr>
				<tr><td>มีผลบังคับใช้</td><td><?= $result[0]['eff_name'];?></td></tr>				
				<tr><td>เจ้าของ</td><td><?= $result[0]['opt_fst_name'];?></td></tr>
				<tr><td>ผู้ดำเนินการ</td><td><?= $result[0]['opt_snd_name'];?></td></tr>
				<tr><td>ขั้นตอนดำเนินการ</td><td><?= $result[0]['cp_step'];?></td></tr>
				<tr><td>วันที่ประกาศบังคับใช้</td><td><?php 
				if($result[0]['cp_date_notice']!=null AND $result[0]['cp_date_notice']!=''){ 
					echo substr($result[0]['cp_date_notice'],8,2).' '.IntToMonth(substr($result[0]['cp_date_notice'],5,2),'th').' '.substr($result[0]['cp_date_notice'],0,4);}
				else{
					//echo "ไม่ระบุวันที่";
					echo "-";
				}
				?></td></tr>
				<tr><td>วันที่สิ้นสุดบังคับใช้</td><td><?php 
				if($result[0]['cp_date_exp']!=null AND $result[0]['cp_date_exp']!=''){ 
					echo substr($result[0]['cp_date_exp'],8,2).' '.IntToMonth(substr($result[0]['cp_date_exp'],5,2),'th').' '.substr($result[0]['cp_date_exp'],0,4);}
				else{
					//echo "ไม่ระบุวันที่";
					echo "-";
				}
				?></td></tr>
			</tbody>
			</table>
			</div>

		</div>
	</div>
	</div>
	<?php /*
	<div  class="col-xs-12 col-md-5" style="margin-bottom:8px;">
		<div style="border:solid black 1px;border-radius:8px;display:flex;flex-direction:column;padding:5px 7px 7px 7px">
			<div style="text-align:center">
				<h4 style="font-family:Kanit;font-weight:600">แผนดำเนินการ</h4>
				<button id="btn_switch" onclick="switchTable();" class="btn btn-warning pull-right" style="width:40px;    margin-top: -38px;"><a><i style="color:white" class="fa fa-exchange"></i></a></button>
			</div>
			<?php 
			$sql= "SELECT * FROM ref_sum_plan WHERE splan_cp = '".$_POST['cp_no']."' ORDER BY splan_id DESC";
			$result = selectSql($sql);
			$plan_id = $result[0]['splan_id'];
			$plan = count($result);
			if($plan>0){ ?>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="text-align:center">ขั้นตอนหลัก</th>
							<th style="text-align:center">ขั้นตอนย่อย</th>
							<th style="text-align:center">เริ่ม</th>
							<th style="text-align:center">สิ้นสุด</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$sql = "SELECT * FROM tb_plan WHERE plan_id = '".$plan_id."' AND plan_step < 6";
						$result = selectSql($sql);
						foreach($result as $row){
							$step_fst = floor($row['plan_step']);
							$step_snd = (($row['plan_step']-$step_fst)==0)? '-': $row['plan_step'];
							$style = ($step_snd == '-')? 'background:#dfdfdf!important;font-weight:600':'background:transparent!important';
						?>
						<tr>
						
					
							<td style="text-align:center;<?= $style;?>;"><?= $step_fst?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $step_snd?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $row['plan_start']?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $row['plan_stop']?></td>
						</tr>
						
						<?php } ?>
						<tr id="petition" class="petition_true" style="display:none; border-left: solid 2px transparent;border-right: solid 2px transparent;background:transparent;"><td colspan="4" style="text-align:center;font-weight:600">กรณีที่มีคำร้อง</td></tr>
						<?php
						$sql = "SELECT * FROM tb_plan WHERE plan_id = '".$plan_id."' AND plan_step >= 6 AND plan_petition = 1";
						$result = selectSql($sql);
						foreach($result as $row){ 
							$step_fst = floor($row['plan_step']);
							$step_snd = (($row['plan_step']-$step_fst)==0)? '-': $row['plan_step'];
							$style = ($step_snd == '-')? 'background:#dfdfdf!important;font-weight:600':'background:transparent!important';
						?>
						<tr class="petition_true" style="display:none;">
							<td style="text-align:center;<?= $style;?>;"><?= $step_fst?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $step_snd?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $row['plan_start']?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $row['plan_stop']?></td>
						</tr>
						<?php } ?>
						
						<tr id="petition2" class="petition_false" style="border-left: solid 2px transparent;border-right: solid 2px transparent;background:transparent;">
							<td colspan="4" style="text-align:center;font-weight:600;vertical-align:">
								<text style="font-size:16px">กรณีที่ไม่มีคำร้อง</text>
							<?php 	//<button onclick="switchTable();" class="btn btn-warning pull-right" style="padding:0;height:25px;width:25px;"><a><i style="color:white" class="fa fa-exchange"></i></a></button></td> ?>
						</tr>
						<?php
						$sql = "SELECT * FROM tb_plan WHERE plan_id = '".$plan_id."' AND plan_step >= 6 AND plan_petition = 0";
						$result = selectSql($sql);
						foreach($result as $row){ 
							$step_fst = floor($row['plan_step']);
							$step_snd = (($row['plan_step']-$step_fst)==0)? '-': $row['plan_step'];
							$style = ($step_snd == '-')? 'background:#dfdfdf!important;font-weight:600':'background:transparent!important';
						?>
						
						<tr class="petition_false">
							<td style="text-align:center;<?= $style;?>;"><?= $step_fst?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $step_snd?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $row['plan_start']?></td>
							<td style="text-align:center;<?= $style;?>;"><?= $row['plan_stop']?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php }
			else{?>
				<div style="text-align:center"><h5 style="font-family:Kanit;font-weight:600">ไม่มีข้อมูลแผน</h5></div>
				<script>
					$('#btn_switch').hide();
				</script>
			<?php } ?>
			
			
		</div>
	</div>
	
	<div class="col-xs-12 col-md-7" style="margin-bottom:8px;">
		<div style="border:solid black 1px;border-radius:8px;display:flex;flex-direction:column;padding:5px 7px 7px 7px">
			<div style="text-align:center"><h4 style="font-family:Kanit;font-weight:600">ประวัติขั้นตอนดำเนินการ</h4></div>
			 <?php 
			 $sql = "SELECT * FROM ref_cityplan_step_detail WHERE csd_cp = '".$_POST['cp_no']."'";
			 $reslut = selectSql($sql);
			 ?>
			<div class="">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="text-align:center;vertical-align:middle" rowspan="2">ลำดับ</th>
							<th style="text-align:center" colspan="3">ขั้นตอนดำเนินการ</th>
							<th style="text-align:center" colspan="2">เวลาดำเนินการตามผล</th>
						</tr>
						<tr>
							<th style="text-align:center">ขั้นตอนหลัก</th>
							<th style="text-align:center">ขั้นตอนย่อย 1</th>
							<th style="text-align:center">ขั้นตอนย่อย 2</th>
							<th style="text-align:center">เริ่มต้น</th>
							<th style="text-align:center">สิ้นสุด</th>
						</tr>
							
					</thead>
					<tbody>
						<?php 
						$i = 0;
						foreach($reslut as $row){
						$i++;
						?>
							<tr>
								<td style="text-align:center"><?= $i;?></td>
								<td style="text-align:center"><?= $row['csd_step_fst'];?></td>
								<td style="text-align:center"><?= $row['csd_step_snd'];?></td>
								<td style="text-align:center"><?= $row['csd_step_trd'];?></td>
								<td style="text-align:center"><?php if($row['csd_start']=='' OR $row['csd_start']=='' OR $row['csd_start']==null){echo '-';}else{ echo $row['csd_start'];}?></td>
								<td style="text-align:center"><?php if($row['csd_stop']=='' OR $row['csd_stop']=='' OR $row['csd_stop']==null){echo '-';}else{ echo $row['csd_stop'];}?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			
			
		</div>
	</div>
	*/
	?>
	


</div>
<div class="modal fade" id="modal-cityplan-img" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:16px">
          <div class="modal-header" style="background-color:#8fdef4;border-radius:8px">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
			<h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มภาพผังเมือง</h4>
          </div>
          <div class="modal-body">
			   <form method="post" action="" enctype="multipart/form-data" id="myform">
					<div >
						<input type="file" id="file" name="file"  accept="image/png">
						 					</div>
					<br></br>
               
               <button style="margin-top:-28px;" type="submit"  value="Upload" id="but_upload"  class="btn btn-success pull-right">อัปโหลด</button>
               <button style="margin-top:-28px;" type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
			   </form>
          </div>
        </div>
      </div>
 </div>
<?php /*
<div class="modal fade" id="modal-cityplan-img" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:16px">
          <div class="modal-header" style="background-color:#8fdef4;border-radius:8px">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
			<h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มภาพผังเมือง</h4>
          </div>
          <div class="modal-body">
            <form>
              <div class="item">
                <label for="cp_img">เพิ่มภาพผังเมือง (ไฟล์รูปภาพชนิด .png)</label>
                <input  class="form-control" type="file" name="cp_img[]" id="cp_img" accept = "image/png" multiple="multiple" required>
				<input type="hidden" name="cp_no" id="cp_no" >
              </div><br>
              <br>
                <button style="margin-top:-28px;" type="button" class="btn btn-success pull-right" onclick="saveIMG()">Submit</button>
                <button style="margin-top:-28px;" type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
 </div>*/ ?>

<div class="modal fade" id="modal-cityplan-img" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:16px">
          <div class="modal-header" style="background-color:#8fdef4;border-radius:8px">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
			<h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มภาพผังเมือง</h4>
          </div>
          <div class="modal-body">
            <form method="post" enctype="multipart/form-data" id="add_person" name="add_person" action="app/uploadcityplanimg.php" onSubmit="JavaScript:return fncSubmit();">
              <div class="item">
                <label for="cp_img">เพิ่มภาพผังเมือง (ไฟล์รูปภาพชนิด .png)</label>
                <input  class="form-control" type="file" name="cp_img[]" id="cp_img" accept = "image/png" multiple="multiple" required>
				 <input type="hidden" name="cp_no" id="cp_no" >
              </div><br>
              <br>
                <button style="margin-top:-28px;" type="submit" class="btn btn-success pull-right" href="/">Submit</button>
                <button style="margin-top:-28px;" type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>

            </form>
          </div>
        </div>
      </div>
 </div>
<script>
//delete SI;
var SI = 1;

showSlides(SI);
function plusSlides(n) {
  showSlides(SI += n);
}

function currentSlide(n) {
  showSlides(SI = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("imgix");
  let captionText = document.getElementById("caption");
  if (n > slides.length) {SI = 1}
  if (n < 1) {SI = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[SI-1].style.display = "block";
  dots[SI-1].className += " active";
  captionText.innerHTML = dots[SI-1].alt;
}


function addCPIMG(x){
	$('#modal-cityplan-img').modal('show');
	$('#cp_img').val('');
	$('#cp_no').val(x);
}
function saveIMG(){
	//alert('123123123');
	
	var cp_img  = new FormData();
	var files = $('#cp_img')[0].files[0];
	cp_img.append('file',files);
	$.ajax({
		url : 'app/uploadcityplanimg.php',
		async : true,
		type : 'POST',		
		data : {
			cp_img : cp_img,
			cp_no : $('#cp_no').val(),
		},success : function(data, status ,xhr){
			alert(data);
		},error : function(xhr, status, data){
			alert(status);
		}
	});	
	
}
function switchTable(){
	var base = './';
	var elem = '';
	$('.petition_true').toggle();
	$('.petition_false').toggle();
	if($('#petition').css('display').toLowerCase() == 'none'){
		elem = '#petition2';
	}else{
		elem = '#petition';
		
	}	
	location.href = base+elem;
	 
}
</script>

<script type="text/javascript">
	// เป็นส่วนการทำงานของ Ajax
        $(document).ready(function(){

            $("#myform").on("submit",function(e){ // จะทำงานก็ต่อเมื่อกด submit ฟอร์ม
		e.preventDefault(); // ปิดการใช้งาน submit ปกติ เพื่อใช้งานผ่าน ajax
                var fd = new FormData(); // เตรียมข้อมูล form สำหรับส่งด้วย  FormData Object

                var files = $('#file')[0].files; //เป็นการดึงข้อมูลรูปภาพเพื่อเตรียมเช็คไฟล์ก่อนทำงานส่วน Ajax
			
                // เช็คว่ามีไฟล์รูปภาพอยู่หรือไม่
                if(files.length > 0 ){

                    fd.append('file',files[0]); //ใช้ในการแทรกค่าไฟล์รูปภาพใน element 

                    $.ajax({
                        url:'app/uploadcityplanimgx.php', //ให้ระบุชื่อไฟล์ PHP ที่เราจะส่งค่าไป
                        type:'post',
                        data:fd, //ข้อมูลจาก input ที่ส่งเข้าไปที่ PHP
                        contentType: false,
                        processData: false,
                        success:function(data, status, xhr){ //หากทำงานสำเร็จ จะรับค่ามาจาก JSON หลังจากนั้นก็ให้ทำงานตามที่เรากำหนดได้
							if(true){
							$('#modal-cityplan-img').modal('hide');
							alert(data);
							gotoCityplanDetail(<?= $_POST['cp_no'];?>);
                            
                            }else{
                                alert('File not uploaded');
                            }
                        }
                    });
                }else{
                    alert("โปรดเลือกไฟล์รูปภาพ .png");
                }
            });
        });


    </script>