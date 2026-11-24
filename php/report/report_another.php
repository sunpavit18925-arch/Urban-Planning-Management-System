<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();

if($_POST['type']==1){
	$title = "ผังที่อยู่ในขั้นตอนการวางและทำผัง";
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 1 AND cp_stage = 1 ) AND (cp_step = 0)";
	$step0 = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 1 AND cp_stage = 1 ) AND (cp_step != 0 AND cp_step_1 = 0)";
	$stepx = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 1 AND cp_stage = 1 ) AND cp_consider != 0";
	$cond = selectSql($sql);

	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 1 AND cp_stage = 1 ) AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))";
	$result =  selectSql($sql);
	
	$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status , ref_affiliation WHERE (cp_status = 1 AND cp_stage = 1 ) AND (cp_step = 0 OR (cp_step != 0 AND cp_step = 0) OR cp_consider != 0 OR ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))) AND (cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND pvn_affiliation = aff_id) AND cp_status = 2";
	
	
}elseif($_POST['type']==2){
	$title = "ผังที่อยู่ในขั้นตอนการปรับปรุงผัง";
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 2 AND cp_stage = 3 ) AND (cp_step = 0)";
	$step0 = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 2 AND cp_stage = 3 ) AND (cp_step != 0 AND cp_step_1 = 0)";
	$stepx = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 2 AND cp_stage = 3 ) AND cp_consider != 0";
	$cond = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 2 AND cp_stage = 3 ) AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))";
	$result =  selectSql($sql);


	$sql = "SELECT * FROM tb_cityplan, ref_province, ref_operate_fst , ref_operate_snd, ref_status , ref_affiliation WHERE (cp_status = 2 AND cp_stage = 3 ) AND (cp_step = 0 OR (cp_step != 0 AND cp_step = 0) OR cp_consider != 0 OR ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))) AND (cp_province = pvn_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_status = status_id AND pvn_affiliation = aff_id) AND cp_status = 2";
	
}else{
	$title = ".";
}
$value_green = 0;
$value_yellow = 0;
$value_orange = 0;
$value_red = 0;



$data = $result[0]['COUNT(*)'];
?>
<?php data_date(TRUE);?>
<style>
	.r-bar{
		border :solid black 2px;
		border-radius:4px;
		width:100%;
		height:20px;
	}
	.value_green ,.value_yellow ,.value_orange ,.value_red{
		text-align:center;
		width: 100%;
		position: relative;
		display: block;
		margin: 2.5px 0px;
		font-weight:600;
	}
	.value_green ,.value_red{
		color:white !important;
	}
	.value_yellow,.value_orange {
		color : black !important;
	}
</style>
<div class="col-12">
	<h2 class="hidden-sm hidden-xs GHfont unprintable" style="font-family: Kanit;text-align:center"><?= $title;?></h2>
	<h2 hidden class="GHfont printable" style="font-family: Kanit;text-align:center"><?= $title;?></h2>
	<h3 class="hidden-md hidden-lg GHfont unprintable" style="font-family: Kanit;text-align:center"><?= $title;?></h3>
	<h4 id="" style="font-family:Kanit;text-align:center">(อื่นๆ)</h4>
	<h4 id="print_search" style="font-family:Kanit;text-align:center"></h4>
</div>
<div class="container-fluid">		
		<div class="row" id="info_bar"hidden >
			<div class="col-xs-12 col-md-12"  style="display:inline;padding-top:0px;margin-bottom:-15px">
				<h3 style="font-family:Kanit" >จำนวน  : <b id="cp_value"><?= $data;?></b> ผัง </h3>
			
			</div>
		</div>
		
		<div hidden class="printable" style="margin-top:8px">
			<table style="width:100%">
				<tr>
					<td style="text-align:center;width:25%;background-color:green!important;"><text class="value_green"></text></td>
					<td style="text-align:center;width:25%;background-color:#FFE841!important;"><text class="value_yellow"></text></td>
					<td style="text-align:center;width:25%;background-color:orange!important;"><text class="value_orange"></text></td>
					<td style="text-align:center;width:25%;background-color:red!important;"><text class="value_red"></text></td>
				</tr>

				<tr style="text-align:center;background:transparent;">
					<td style="vertical-align: top;padding:5px 0px 0px 0px">เป็นไปตามแผน</td>
					<td style="vertical-align: top;padding:5px 0px 0px 0px">ล่าช้าเล็กน้อย</td>
					<td style="vertical-align: top;padding:5px 0px 0px 0px">ล่าช้าปานกลาง</td>
					<td style="vertical-align: top;padding:5px 0px 0px 0px">ล่าช้ามาก</td>
				</tr>
			</table>
		</div>
		<div id="reportshow" class="" style="border-color:transparent;">
			<div hidden ><h3 style="font-family:Kanit">test</div>
			<div hidden class="r-bar" style="background-image:linear-gradient(90deg ,gray 0%, gray <?php echo ($cond[0]['COUNT(*)']/$data*100).'%'; ?>, red <?php echo ($cond[0]['COUNT(*)']/$data*100).'%';?>, red <?php echo (($stepx[0]['COUNT(*)']+$step0[0]['COUNT(*)'])/$data*100)+($cond[0]['COUNT(*)']/$data*100).'%'; ?>);height:35px;"></div>
		
			<div><h3 style="font-family:Kanit">ระบุเหตุผล : <b><?= $cond[0]['COUNT(*)'];?></b> ผัง</h3></div>
			<div class="r-bar" style="background-image:linear-gradient(90deg ,gray <?php echo ($cond[0]['COUNT(*)']/$data*100).'%'; ?>,transparent 0);height:35px;"></div>
			<div id="report-another-a"></div>
			<div><h3 style="font-family:Kanit">ไม่ระบุเหตุผล : <b><?= $stepx[0]['COUNT(*)']+$step0[0]['COUNT(*)'];?></b> ผัง</h3></div>				
			<div class="r-bar" style="background-image:linear-gradient(90deg ,red <?php echo (($stepx[0]['COUNT(*)']+$step0[0]['COUNT(*)'])/$data*100).'%'; ?>,transparent 0);height:35px;"></div>
			<!--<img src="img/loading3.gif" class="center">-->
		</div>
	<div id="report-another-b">
	</div>
</div>

<script>
	ranother(2,3,1);
	function ranother(status , stage , consider){
		$.post('report/report_another_pro.php',{
			type : '<?php echo $_POST["type"];?>',
			status : status,
			stage : stage,
			consider : consider
		},function(data){
			if(consider == 1){
				$('#report-another-a').html(data);
			}else{
				$('#report-another-b').html(data);
			}
		});
	}
	$('.value_green').html(<?php echo $value_green; ?>);
	$('.value_yellow').html(<?php echo $value_yellow; ?>);
	$('.value_orange').html(<?php echo $value_orange; ?>);
	$('.value_red').html(<?php echo $value_red; ?>);
</script>