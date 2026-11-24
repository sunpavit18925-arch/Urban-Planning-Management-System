<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$title= "ผังเมืองรวม/ชุมชน ม.35";
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
$where = "";	
$aff = 0;
if (!isset($_SESSION['data_access']) || $_SESSION['data_access'] == '') {
    $_SESSION['data_access'] = 9;
}
if($_SESSION['data_access']==2){
	if(!isset($_SESSION['intra_czid']) || strlen($_SESSION['intra_czid'])!=13){
		$_SESSION['intra_czid'] = 'demo-user-1';
	}
	{
		$sql = "SELECt CASE WHEN user_affiliation = '071201' THEN 1 WHEN user_affiliation = '071202' THEN 2 WHEN user_affiliation = '071203' THEN 3 WHEN user_affiliation = '071204' THEN 4 ELSE 0 END as aff FROM tb_user WHERE user_id = '".$_SESSION['intra_czid']."'";
		$result = selectSql($sql);
		if($result[0]['aff']>=1 and $result[0]['aff']<=4){
			$aff = $result[0]['aff'];
			$where = " WHERE t3.pvn_affiliation = ".$result[0]['aff'];
		}
		else{
			
		}
	}
}	
$sql = "
SELECT * , 
CASE
	WHEN t6.csd35_step_fst < 1 AND t6.csd35_step_snd < 1 THEN '-' 
	WHEN t6.csd35_step_fst > 1 AND t6.csd35_step_snd < 1 THEN CONCAT(t6.csd35_step_fst,'.0') 
	WHEN t6.csd35_step_fst = 6 AND t6.csd35_petition = 1 THEN  CONCAT(t6.csd35_step_fst,'.',t6.csd35_step_snd,'**') 
	ELSE CONCAT(t6.csd35_step_fst,'.',t6.csd35_step_snd) 
END AS csd35_step
FROM tb_cityplan35 as t1 
LEFT JOIN tb_cityplan_main as t2 ON t1.cp35_no = t2.cp_no 
LEFT JOIN ref_province as t3 ON t2.cp_province = t3.pvn_id 
LEFT JOIN ref_operate_fst as t4 ON t2.cp_owner = t4.opt_fst_id 
LEFT JOIN ref_operate_snd as t5 ON t2.cp_operator = t5.opt_snd_id
LEFT JOIN ref_cityplan_step_detail_m35 as t6 ON t1.cp35_csd = t6.csd35_seq
".$where." ORDER BY cp35_seq DESC
";

//$from = " ref_cityplan_step_detail_m35 , tb_cityplan , ref_province ,ref_scope, ref_status ,ref_stage, ref_operate_fst, ref_operate_snd";
//$join = " csd35_status = 1 AND csd35_cp = cp_no AND cp_province = pvn_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id GROUP BY csd35_cp ASC ORDER BY csd35_seq DESC";
//$sql = "SELECT * , SUM(csd35_step_fst) as sum_csd35 FROM ".$from." WHERE ".$join;
//echo $sql;
$result = selectSql($sql);
$all_data = count($result);


?>
	<style>
		@media screen and (max-width:767px){
			.my-tb{
				margin:0 15px;
				padding: 0;
			}
			
		}
		
		.sub-select-cp-input{
			 margin:0px;
			 border-top:solid #dfdfdf 1px;
			 padding: 4px 5px 4px 10px;
		 }
		 
		.sub-select-cp-input:first-child{
			border:none!important;
		 }
		 
		.div-sub-select-cp-input:hover{
			 background:blue;
			 color:white;
		 }
		.table > tbody > tr > td{
			 border-top:solid #dfdfdf 1px;
		}
		.clickable:hover{
			 border-bottom:solid black 1px;
			 cursor:pointer;
		}
		.lock{
			pointer-events:none;
		}
		.invalid , input:invalid:focus {
			border: #ff6666 2px solid!important;
			background: #ffbdbd73;
		}
	</style>

	<div style="display:inline;z-index:1;text-align:right;" class="pull-right"><?php echo ' <b>ข้อมูล ณ วันที่ '.$dates.'</b>';?></div>
	<div id="title_page" class="col-12">
		<h2 class="hidden-sm hidden-xs GHfont printable" style="font-family: Kanit;text-align:center"><?=$title?></h2>
		<h3 class="hidden-md hidden-lg GHfont-mini unprintable" style="font-family: Kanit;text-align:center"><?=$title?></h3>
		<div class="row" id="info_bar">
			<h3 class="value-show-1" style="font-family:Kanit;text-align:center;margin:0px" id="search_value">จำนวน   <?=$all_data?>  ผัง </h3>
		</div>
		
		<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
	</div>	
	<br>
	<div class="container-fluid">
		<div class="row unprintable" style="margin-bottom:8px;">
			<div id="div-btn" class="col-xs-6 col-md-6">
				<button id="btn_backy" onclick="goto('a6m');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-btn-2" class="col-xs-6 col-md-6">
				<button id="btn_m35" onclick="addM35()" class="pull-right btn btn-primary btn-md" style="margin-top:6px;">เพิ่มผังเมือง</button>
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
		<div class="row">
				<?php
					$row = 20;
					
					
					for($n=0,$page=1;$page<=ceil($all_data/$row);$page++){
				?>
			<div class="table-responsive col-xs-12 my-tb">
				<table id="table_<?=$page?>" class="table table-bordered">
					<thead>
						<tr>
							<td style="text-align:center">ลำดับ</td>
							<td style="text-align:center">จังหวัด</td>
							<td style="text-align:center">ชื่อผังรวม</td>
							<td style="text-align:center">เจ้าของ</td>
							<td style="text-align:center">ผู้ดำเนินการ</td>
							<td style="text-align:center">การดำเนินการ</td>
							<td style="text-align:center">ขั้นตอน</td>
							<td style="text-align:center">รายละเอียด</td>
						</tr>
					</thead>
					<tbody>
					<?php 
					//$n = 0;
					for($i=0;$i<$row;$i++){ 
						if($n>=$all_data){
							break;
						}
						//$status_snd = status_snd('m35',$result[$n]['csd35_step_fst'],$result[$n]['csd35_step_snd'],$result[$n]['csd35_step_trd'],$result[$n]['csd35_petition'],$result[$n]['csd35_start'],$result[$n]['csd35_stop']);
						//$status_trd = status_trd('m35',$result[$n]['csd35_step_fst'],$result[$n]['csd35_step_snd'],$result[$n]['csd35_step_trd'],$result[$n]['csd35_petition'],$result[$n]['csd35_start_2'],$result[$n]['csd35_stop_2']);
						/*
						if($_SESSION['intra_czid']=='demo-user-1'){
							print_r($status_trd);echo '<br>';
						}
						*/
						
					?>
						<tr>
							<td style="text-align:center"><?=$n+1;?></td>
							<td><?=$result[$n]['pvn_name']?></td>
							<td><?=$result[$n]['cp_name']?> (แก้ไขตามม.35 ครั้งที่ <?=$result[$n]['cp35_name']?>)</td>
							<td style="text-align:center"><?=$result[$n]['opt_fst_name']?></td>
							<td style="text-align:center"><?=$result[$n]['opt_snd_name']?></td>	
							<?php 
								$csd35 =  m35_color($result[$n]['csd35_seq'],$result[$n]['cp_owner']);
							?>
							<td style="text-align:center;<?=$csd35['bgstyle']?>" title="<?=$csd35['title']?>"><?=$result[$n]['csd35_step']?></td>
							<td style="text-align:center">
								<?php 
								//echo $result[$n]['csd35_update'];
								$note_class = $result[$n]['csd35_update']==0?'trash':'list';
								?>
									<span class="<?=$note_class?>" onclick="readCsd35(<?=$result[$n]['cp35_seq']?>)"><a><i class="fa fa-list"></i></a></span>
							</td>
							<td style="text-align:center">
								<?php 
									if($result[$n]['cp35_detail']!=''){
										?><span class="note" title="รายละเอียด" onclick="readNote(<?=$result[$n]['cp35_seq']?>)"><a><i class="fa fa-list-alt"></i></a></span><?php
									}else{
										echo "-";
									}
								?>
							</td>
						</tr>
						<?php 
							$n++;
							$step_fst = '';
							$step_snd = '';
							$step_trd = '';
						} ?>
					</tbody>
				</table>
			</div>
			<?php }
				//pagin($row,$all_data);
				pagin($row,$all_data);
			?>
		</div>
	</div>

<div class="modal fade" id="modal-csd35" style="">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มผังเมืองที่แก้ไขตาม ม.35</h4>
            </div>
            <div class="modal-body">
				<form>
					<div class="row" >
						<div class="form-group col-xs-12 col-md-12">
							<label for="select_cp_name">ระบุชื่อผังเมืองที่ต้องการแก้ไขตาม ม.35 <span style="color:red;"> *</span></label>
							<input id="select_cp_name" class="form-control"  onkeyup="changeCP()"  placeholder="เลือก/ระบุชื่อผังเมือง" autocomplete="off">
							<div id="sub_select_cp" style="    display: flex;flex-direction: column;border: solid #dfdfdf 1px;max-height: 300px;overflow: auto;"></div>
						</div>
					</div>
					<div class="row" id="div-cp35_name" >
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp35_name" style="width:100%;">แก้ไขตามม.35 ครั้งที่ <span style="color:red;"> *</span><span id="cp35_name_valid" style="float:right"></span></label>
							<input id="cp35_name" type="number" min="1" class="form-control"  onfocus="" onkeyup="checkEditM35()"  placeholder="แก้ไขตามม.35 ครั้งที่ ...." autocomplete="off">	
						</div>
						
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp35_detail">รายละเอียด</label>
							<textarea id="cp35_detail" type="text"  class="form-control"  onfocus="" onkeyup=""  placeholder="รายละเอียดการแก้ไขตามม.35" autocomplete="off"></textarea>	
						</div>
						
					</div>
				<input type="hidden" id="m35_event" value="c_m35-new">				
			</form>
			</div>
			<div  class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
				<button id="btn_add35" type="button" class="btn btn-success pull-right" onclick="saveM35()">บันทึก</button>				
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-note35" style="">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">รายละเอียดการแก้ไขตาม ม.35</h4>
            </div>
            <div class="modal-body">
				
					<div class="">
						<div style="font-weight:600">รายละเอียด</div>
						<div id="cp35_detail_show" style="margin:10px 0 0 0"></div>
					</div>
				
			</div>
			<div  class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>				
			</div>
		</div>
	</div>
</div>
	
 
<script>
	function readNote(x){
		$('#modal-note35').modal('show');
		loadNote(x);
	}
	function loadNote(x){
		$.ajax({
			url : 'app/manage.php',
			async: true,
			type: 'post',
			data:{
				event : 'loadNote_cp35',
				cp35_seq : x
			},
			success: function(data){
				$('#cp35_detail_show').html(data);
			},
			error: function(xhr, status, data){
				alert("เกิดข้อผิดพลาด");
			}
		});
	}
	
	function addM35(){
		$('#modal-csd35').modal('show');
		$('#sub_select_cp').hide();
		$('#btn_add35').attr('disabled','true');
		$('#select_cp_name').val('').removeClass('lock').removeAttr("readonly");
		$('#cp35_name').val('').removeClass('invalid');
		$('#cp35_detail').val('');
		$('#div-cp35_name').hide();
		$('#cp35_name_valid').html('');
	}
	
	function saveM35(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data :{
				cp_name : $('#select_cp_name').val(),
				cp35_name : $('#cp35_name').val(),
				cp35_detail : $('#cp35_detail').val(),
				event : $('#m35_event').val()
			},
			success:function(data, status, xhr,){
				alert(data);
				
				if(data=='false'){
				}else{
					$('#modal-csd35').modal('hide');
					setTimeout(() => {
						goto('m35x');
					}, "250");
				}
				
			},
			error:function(xhr, status, data){
				alert('Status : '+status);
			}
		});
	}
	
	function checkM35name(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				event : 'check-cp35_name',
				cp_name : $('#select_cp_name').val()
			},
			success: function(data, status, xhr){
				$('#cp35_name').val(data).focus();
				$('#div-cp35_name').show();
			},
			error: function(xhr, status ,data){
				$('#cp35_name').val("").focus();
				$('#div-cp35_name').show();
			}
				
		});
		
	}
	
	function checkEditM35(){
		var x = parseInt($('#cp35_name').val());
		$('#cp35_name').val(x)
		if($('#cp35_name').val()<1){
			$('#cp35_name').val('');
			$('#cp35_name').focus().val('').addClass('invalid');
			$('#btn_add35').attr('disabled',true);
			$('#cp35_name_valid').html("โปรดระบุข้อมูลให้ถูกต้อง");
		}
		else if($('#cp35_name').val()>9999){
			$('#cp35_name').val('');
			$('#cp35_name').focus().val('').addClass('invalid');
			$('#btn_add35').attr('disabled',true);
			$('#cp35_name_valid').html("โปรดระบุข้อมูลให้ถูกต้อง");
		}
		else{
			$.ajax({
				url : 'app/manage.php',
				async : true,
				type : 'POST',
				data : {
					event : 'checkEditM35',
					cp_name : $('#select_cp_name').val(),
					cp_edit : $('#cp35_name').val()
				},
				success: function(data, status, xhr){
					if(data){
						$('#btn_add35').attr('disabled',true);
						$('#cp35_name').focus().addClass('invalid');
						$('#cp35_name_valid').html(data);
						
					}else{
						$('#btn_add35').removeAttr('disabled');
						$('#cp35_name').removeClass('invalid');
						$('#cp35_name_valid').html('');
						
					}
				},
				error: function(xhr, status ,data){
					alert("เกิดข้อผิดพลาด");
				}
					
			});
		}
	}
	function sub_select_cp(cp_name){
		$('#select_cp_name').val(cp_name).attr("readonly","true").addClass("lock");
		$('#sub_select_cp').hide();
		
		checkM35name();
		$('#btn_add35').removeAttr('disabled');
	}	
	$("#select_status").change(function(){
		$('#select_cp').val('');
		$('#select_operate').val('0');
		$('#select_dflaw').val('0');
		
		$('#sub_select_cp').hide();
		$('#modal_status').val('0');
	});
	$("#select_cp_name").focusin(function(){
	  changeCP();
	});	
	
	function changeCP(){
		$.ajax({
			url : 'app/manage.php',
			type : 'POST',
			data : {
				event : 'select-cp-input(m35)',
				aff : '<?=$aff?>',
				cp_name : $('#select_cp_name').val(),
				type : $('#select_status').val()
			},success : function(data, status, xhr){
				$('#btn_add35').attr('disabled','true');
				$('#sub_select_cp').show().html(data);
			},error : function(xhr, status ,data){
				console.log('+'+data);
			}
		});
	}
	
	function readCsd35(x){
		$.ajax({
			url : 'app/cityplan_m35X_detail.php',
			async : true,
			type : 'POST',
			data : {
				csd35_cp : x 
			},success : function(data, status , xhr){
				$('#mainshow').html(data);
			},error : function(xhr, status , data){
				alert('Loading'+status+'\n'+data)
			}
		});
	}
</script>
	
	