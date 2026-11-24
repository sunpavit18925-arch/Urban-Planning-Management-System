<?php 
include '../js/function_db.php';
$title = "แผนดำเนินการ";
?>

<style>
	@media print{
		@page{
			size:A4 portrait;
			margin: 15mm 10mm;
		}
		td{
			font-size:10px;
			padding: 0px;
		}
		
		.table > tbody > tr > td{
			line-height: 0;
		}
		#title_print{
			margin-top:-7.5mm;
		}
		
	}
	
</style>

<div class="container-fluid">
	<h2 class="GHfont unprintable" style="text-align:center;font-family:Kanit"><?= $title;?></h2>
	<h2 id="title_print" hidden class="GHfont printable" style="text-align:center;font-family:Kanit"><?= $title;?></h2>
	<div class="row unprintable">
		<div id="div-btn" class="col-xs-12 ">
			<button id="btn_goback" onclick="goto('a5');" class="pull-left btn btn-danger btn-md" style="margin-top:6px;">ย้อนกลับ</button>
		</div>
		
	</div>	
	
	<div id="div_select_cp" style="margin:12px 0;" class="row unprintable">
		<div class="col-xs-12 col-sm-12" style="padding:0 0 8px 0">
			<label for="select_cp">เลือกผังเมือง</label>
			<select id="select_cp" class="form-control" onchange="selectCP()">
				<option value="0" selected>เลือกผังเมือง</option>
				<?php 
					$sql = "SELECT cp_no , cp_name FROM tb_cityplan ORDER BY cp_name";
					$result = selectSql($sql);
					foreach($result as $row){
						echo '<option value="'.$row['cp_no'].'">'.$row['cp_name'].'</option>';
					}
				?>
			</select>
		</div>
		<div id="div_input_date" hidden class="row unprintable">
			<div class="col-xs-12 col-sm-7 col-md-9">
				<label for="cp_date">ระบุวันที่เริ่มดำเนินการ</label>
				<input id="cp_date" class="form-control cp_date" placeholder="yyyy-mm-dd" onchange="setPlan()">
			</div>
			<div class="col-xs-12 col-sm-5 col-md-3" style="padding-top:25px;    display: flex;flex-direction: row;justify-content: space-between;flex-wrap: nowrap;">			
				<button id="btnSwitch" class="btn btn-primary pull-right" style="display:none;" onclick="switchTable()">สลับตาราง</button>
				<button id="btnSave" onclick="savePlan()" style="display:none;" class="btn btn-success">บันทึกข้อมูล</button>
			</div>
		
		</div>
		<div id="div_show_date_1"></div>
		<div id="div_show_date_2"></div>
			
					
		
	</div>



<script>
	function switchTable(){
		$('#div_show_date_1').toggle();
		$('#div_show_date_2').toggle();
	}
	function selectCP(){
		$('#cp_date').val('');
		$('#cp_petition').val(0);
		if($('#select_cp').val()>0){
			$('#div_input_date').show();
		}else{
			$('#div_input_date').hide();
		}
		$('.btn-success').hide();
		$('#div_show_date_1').html(' ');
		$('#div_show_date_2').html(' ');
	}
	
	function setPlan(){
		$.ajax({
			url : 'app/manage_plan_pro.php',
			async : true,
			type : 'POST',
			data : {
				event : 'setPlan',
				starter : $('#cp_date').val()},
			success : function(data, status, xhr){
				//console.log(data);
				$('#div_show_date_1').html(data);
				$('#div_show_date_1').show();
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
		$.ajax({
			url : 'app/manage_plan_proX.php',
			async : true,
			type : 'POST',
			data : {
				event : 'setPlan',
				starter : $('#cp_date').val(),
				petition : $('#cp_petition').val()
			},
			success : function(data, status, xhr){
				//console.log(data);
				$('#div_show_date_2').html(data);
				$('#div_show_date_2').hide();
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
		$('button').show();
		
	}
	
	function savePlan(){
		$.ajax({
			url : 'app/manage.php',
			async : true,
			type : 'POST',
			data : {
				event : 'c_plan',
				cp_no : $('#select_cp').val(),
				plan : $('#ta_plan_1').val(),
				plan2 : $('#ta_plan_2').val()
	
			},
			success : function(data, status, xhr){
				//alert(data);
				console.log(data);
			},
			error : function(xhr, status, data){
				alert('Loading : '+status+'\n'+data);
			}
		});
	}
	
</script>