<?php 
include '../js/function_db.php';
$sql = "SELECT * FROM tb_cityplan , ref_province , ref_scope , ref_status, ref_stage ,ref_operate_fst , ref_operate_snd , ref_effective WHERE cp_province = pvn_id AND pvn_name LIKE '%".$_POST['pvn_name']."%'  AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_owner = opt_fst_id AND cp_operator =  opt_snd_id  AND cp_effective = eff_id";
$sql = "SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_operate_fst , ref_operate_snd , ref_scope, ref_status , ref_up_fst_step , ref_up_snd_step , ref_cityplan_step_detail	WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id AND cp_scope = scope_id AND cp_status = status_id AND cp_step = step_id AND step_id = sub_step AND cp_step_1 = sub_no AND cp_csd = csd_seq AND pvn_name LIKE '%".$_POST['pvn_name']."%'";
		
$result = selectSql($sql);
$row = 20;
//echo $sql;
?>
<h2 class="hidden-sm hidden-xs GHfont" style="font-family: Kanit;text-align:center"><?= 'จังหวัด <br>'.$result[0]['pvn_name'];?></h2>
<h3 class="hidden-md hidden-lg GHfont" style="font-family: Kanit;text-align:center"><?= 'จังหวัด <br>'.$result[0]['pvn_name'];?></h3>
<div class="container-fluid">
		<div  class="row">
			<div hidden id="div-btn" class="col-xs-12 col-sm-12 col-md-12">
				<button id="btn_goback" onclick="goback()" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
			</div>
			<div id="div-search_myhead" class="col-xs-12 col-sm-12 col-md-12">
				<input id="search_keyword" type="text" style="margin-top:6px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหา .....">
				<button id="search_keyword_btn" onclick="searchMyhead()" class="btn btn-success" style="height:34px;margin-top:-35px;position:absolute;left:auto;right:0px;margin-right:14px"><i class="fa fa-search" style="margin-right:4 px"></i><text class="hidden-xs">ค้นหา</text></button>
			</div>

		</div>
		

		<div class="row">
			<div class="col-xs-12 "  style="display:inline;padding-top:0px;margin-bottom:-7.5px ;">
				<h3 style="font-family:Kanit" >จำนวน  : <b id="map_value"><?php echo count($result);?></b> ผัง </h3>
			</div>
		</div>
</div>
		<div id="contentshow" class="container-fluid" style="border-color:transparent;">
			<img src="img/loading3.gif" class="center">
		</div>

<?php //pagin($row,count($result));?>

<script>
$.post('map/map_province_pro.php',{
	sql : "<?= $sql;?>"	,
	row : "<?= $row;?>"
},function(data){
	$('#contentshow').html(data);	
});


function goback(){
	$('#subshowX').hide();
	$('#framap').removeAttr('style','display:none');
	//$('#framap').load();
}
</script>