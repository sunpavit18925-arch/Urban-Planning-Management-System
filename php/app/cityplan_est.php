<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$title= "ผังเมืองรวม/ชุมชนในขั้นตอนงานประเมินผล";
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

$sql = "
	SELECT *,
	CASE 
		WHEN t6.csd_est_step_fst < 1 THEN '-'
		WHEN t6.csd_est_step_fst > 0 AND t6.csd_est_step_snd < 1 THEN CONCAT(t6.csd_est_step_fst,'.0')
		WHEN t6.csd_est_step_fst > 0 AND t6.csd_est_step_snd > 0 THEN CONCAT(t6.csd_est_step_fst,'.',t6.csd_est_step_snd)
	END AS csd_est_step
	FROM tb_cityplan_est as t1
	LEFT JOIN tb_cityplan_main as t2 ON t1.est_cp = t2.cp_no
	LEFT JOIN ref_province as t3 ON t2.cp_province = t3.pvn_id
	LEFT JOIN ref_operate_fst as t4 ON t2.cp_owner = t4.opt_fst_id
	LEFT JOIN ref_operate_snd as t5 ON t2.cp_operator = t5.opt_snd_id
	LEFT JOIN ref_cityplan_step_detail_est as t6 ON t1.est_csd = t6.csd_est_seq
	ORDER BY est_seq DESC
";

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
				<button id="btn_est" onclick="addEST()" class="pull-right btn btn-primary btn-md" style="margin-top:6px;">เพิ่มผังเมือง</button>
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
					?>
						<tr>
							<td style="text-align:center"><?=$n+1;?></td>
							<td><?=$result[$n]['pvn_name']?></td>
							<td><?=$result[$n]['cp_name']?> (ประเมิน ครั้งที่ <?=$result[$n]['est_edit']?>)</td>
							<td style="text-align:center"><?=$result[$n]['opt_fst_name']?></td>
							<td style="text-align:center"><?=$result[$n]['opt_snd_name']?></td>	
							<?php 
								$est =  m35_color($result[$n]['est_seq'],$result[$n]['cp_owner']);
							?>
							<td style="text-align:center;<?=$est['bgstyle']?>" title="<?=$est['title']?>"><?=$result[$n]['csd_est_step']?></td>
							<td style="text-align:center">
								<?php 
								$note_class = $result[$n]['csd_est_update']==0?'trash':'list';
								?>
									<span class="<?=$note_class?>" onclick="readEst(<?=$result[$n]['est_seq']?>)"><a><i class="fa fa-list"></i></a></span>
							</td>
							<td style="text-align:center">
								<?php 
									if($result[$n]['est_detail']!=''){
										?><span class="note" title="รายละเอียด" onclick="readNote(<?=$result[$n]['est_seq']?>)"><a><i class="fa fa-list-alt"></i></a></span><?php
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

<div class="modal fade" id="modal-csd_est" style="">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มผังเมืองในขั้นตอนงานประเมินผล</h4>
            </div>
            <div class="modal-body">
				<form>
					<div class="row" >
						<div class="form-group col-xs-12 col-md-12">
							<label for="select_cp_name">ระบุชื่อผังเมืองในขั้นตอนงานประเมินผล <span style="color:red;"> *</span></label>
							<input id="select_cp_name" class="form-control"  onkeyup="changeCP()"  placeholder="เลือก/ระบุชื่อผังเมือง" autocomplete="off">
							<div id="sub_select_cp" style="    display: flex;flex-direction: column;border: solid #dfdfdf 1px;max-height: 300px;overflow: auto;"></div>
						</div>
					</div>
					<div class="row" id="div-est_edit" >
						<div class="form-group col-xs-12 col-md-12">
							<label for="est_edit" style="width:100%;">ประเมิน ครั้งที่ <span style="color:red;"> *</span><span id="est_edit_valid" style="float:right"></span></label>
							<input id="est_edit" type="number" min="1" class="form-control"  onfocus="" onkeyup="CheckEstimate()"  placeholder="ประเมินครั้งที่ ครั้งที่ ...." autocomplete="off">	
						</div>
						
						<div class="form-group col-xs-12 col-md-12">
							<label for="est_detail">รายละเอียด</label>
							<textarea id="est_detail" type="text"  class="form-control"  onfocus="" onkeyup=""  placeholder="รายละเอียดการดำเนินการ" autocomplete="off"></textarea>	
						</div>
						
					</div>
				<input type="hidden" id="est_event" value="c_est-new">				
			</form>
			</div>
			<div  class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">ปิด</button>
				<button id="btn_addEst" type="button" class="btn btn-success pull-right" onclick="saveEST()">บันทึก</button>				
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-noteEST" style="">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;pointer-events: auto;">
            <div class="modal-header" style="background-color:#8fdef4;border-radius:10px;border-bottom-right-radius:0px;border-bottom-left-radius:0px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">รายละเอียดการดำเนินการ</h4>
            </div>
            <div class="modal-body">
				
					<div class="">
						<div style="font-weight:600">รายละเอียด</div>
						<div id="est_detail_show" style="margin:10px 0 0 0"></div>
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
		$('#modal-noteEST').modal('show');
		loadNote(x);
	}
	function loadNote(x){
		$.ajax({
			url : 'app/manage.php',
			async: true,
			type: 'post',
			data:{
				event : 'loadNote_est',
				est_seq : x
			},
			success: function(data){
				$('#est_detail_show').html(data);
			},
			error: function(xhr, status, data){
				alert("เกิดข้อผิดพลาด");
			}
		});
	}
	
	function addEST(){
		$('#modal-csd_est').modal('show');
		$('#sub_select_cp').hide();
		$('#btn_addEst').attr('disabled','true');
		$('#select_cp_name').val('').removeClass('lock').removeAttr("readonly");
		$('#est_edit').val('').removeClass('invalid');
		$('#est_detail').val('');
		$('#div-est_edit').hide();
		$('#est_edit_valid').html('');
	}
	
	function saveEST(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data :{
				cp_name : $('#select_cp_name').val(),
				est_edit : $('#est_edit').val(),
				est_detail : $('#est_detail').val(),
				event : $('#est_event').val()
			},
			success:function(data, status, xhr,){
				alert(data);
				
				if(data=='false'){
				}else{
					$('#modal-csd_est').modal('hide');
					setTimeout(() => {
						goto('est');
					}, "250");
				}
				
			},
			error:function(xhr, status, data){
				alert('Status : '+status);
			}
		});
	}
	
	function checkEstname(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				event : 'check-est_name',
				cp_name : $('#select_cp_name').val()
			},
			success: function(data, status, xhr){
				$('#est_edit').val(data).focus();
				$('#div-est_edit').show();
			},
			error: function(xhr, status ,data){
				$('#est_edit').val("").focus();
				$('#div-est_edit').show();
			}
				
		});
		
	}
	
	function CheckEstimate(){
		var x = parseInt($('#est_edit').val());
		$('#est_edit').val(x)
		if($('#est_edit').val()<1){
			$('#est_edit').val('');
			$('#est_edit').focus().val('').addClass('invalid');
			$('#btn_addEst').attr('disabled',true);
			$('#est_edit_valid').html("โปรดระบุข้อมูลให้ถูกต้อง");
		}
		else if($('#est_edit').val()>9999){
			$('#est_edit').val('');
			$('#est_edit').focus().val('').addClass('invalid');
			$('#btn_addEst').attr('disabled',true);
			$('#est_edit_valid').html("โปรดระบุข้อมูลให้ถูกต้อง");
		}
		else{
			$.ajax({
				url : 'app/manage.php',
				async : true,
				type : 'POST',
				data : {
					event : 'CheckEstimate',
					cp_name : $('#select_cp_name').val(),
					cp_estimate : $('#est_edit').val()
				},
				success: function(data, status, xhr){
					if(data){
						$('#btn_addEst').attr('disabled',true);
						$('#est_edit').focus().addClass('invalid');
						$('#est_edit_valid').html(data);
						
					}else{
						$('#btn_addEst').removeAttr('disabled');
						$('#est_edit').removeClass('invalid');
						$('#est_edit_valid').html('');
						
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
		
		checkEstname();
		$('#btn_addEst').removeAttr('disabled');
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
				event : 'select-cp-input(est)',
				aff : '<?=$aff?>',
				cp_name : $('#select_cp_name').val(),
				type : $('#select_status').val()
			},success : function(data, status, xhr){
				$('#btn_addEst').attr('disabled','true');
				$('#sub_select_cp').show().html(data);
			},error : function(xhr, status ,data){
				console.log('+'+data);
			}
		});
	}
	
	function readEst(x){
		$.ajax({
			url : 'app/cityplan_est_detail.php',
			async : true,
			type : 'POST',
			data : {
				est_seq : x 
			},success : function(data, status , xhr){
				$('#mainshow').html(data);
			},error : function(xhr, status , data){
				alert('Loading'+status+'\n'+data)
			}
		});
	}
</script>
	
	