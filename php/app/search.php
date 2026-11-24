<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$sql = "SELECT COUNT(cp_no) FROM tb_cityplan";
	$result = selectSql($sql);
?>
<div class="container-fluid" style="background-color:whitse;border-radius:8px">
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ค้นหา</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">ค้นหา</h4>
	</div>	
<div class="row">
	<div class="col-xs-12 col-md-10">
		<input id="search_bar" class="ssearch" type="text" placeholder="ค้นหา......................." style="width:100%;">
	</div>
	<div class="col-xs-12 col-md-2">
		<select id="select_bar" class="ssearch" onchange="gosearch()" style="width:100%">
			<option selected value="">เลือกประเภทการค้นหา</option>
			<option value="cityplan">ชื่อผังเมือง</option>
			<option value="province">จังหวัด</option>
			<option value="eec">ผังEEC</option>
		</select>
	</div>
</div>
<!--<div class="row">
	<div class="col-sm-3">
		<button style="margin-left:50%;margin-right:50%" class="btn btn-success">Search1</button>
	</div>
	<div class="col-sm-3">
		<button style="margin-left:50%;margin-right:50%" class="btn btn-success">Search2</button>
	</div>
	<div class="col-sm-3">
		<button style="margin-left:50%;margin-right:50%" class="btn btn-success">Search3</button>
	</div>
	<div class="col-sm-3">
		<button style="margin-left:50%;margin-right:50%" class="btn btn-success">Search4</button>
	</div>
</div>-->
	<br>	
		

	<div id="searchshow" style="background-color:transparent;">
		
	</div>
</div>
<script>
	 function search(){
		 $.post('app/search_data.php',{
				key:$('#search_bar').val(),	
				type:$('#select_bar').val(),
			},function(data){
				$('#searchshow').html(data);
			});
	 }

	function gosearch(){
		$('#search_bar').attr("onkeyup","search()");
		search();
	
	}
	
</script>