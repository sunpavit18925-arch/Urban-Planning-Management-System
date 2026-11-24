<?php 
include '../js/function_php.php';

?>
<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">จัดการข้อมูลผังเมือง</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">จัดการข้อมูลผังเมือง</h4>
</div>
<div class="row" style="margin:8px 0px 12px 0px">
	<div class="col-xs-12 col-sm-2" style="margin:0px 0px 0x 0px"> 
		<h3 style="font-family:Kanit;margin:8px 0px 0px 0px">จำนวน : <b id="cp_value"></b> ผัง</h3>
	</div>
	<div class="col-xs-12 col-sm-10">
		<input id="search_cp"  onchange="mytable();"  class="form-control"><a onclick="mytable();"style="margin:-26px 16px 0px 0px" class="pull-right"><i class="fa fa-search"></i></a>
	</div>
</div>
<div id="divtest" style="margin:0px 16px 0px 16px">
<?php
	if(isset($_POST['x'])){
		//echo $_POST['x'];
		mytable(10,true,$_POST['x']);
	}else{
		mytable(10,true,null);
	}
?>
</div>

<script>
function mytable(){
	if($('#search_cp').val()== null || $('#search_cp').val()==" " ){
		var x = null;
	}
	else{
		var x = "AND cp_name LIKE '%"+$('#search_cp').val()+"%' ";		
	}
	$.post('app/mytest.php',{
		x : x
	},function(data){
		//alert(data);
		$('#mainshow').html(data);
	});
}

</script>