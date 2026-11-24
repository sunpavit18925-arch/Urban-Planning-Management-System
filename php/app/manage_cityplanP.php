<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$sql = "SELECT COUNT(cp_no) FROM tb_cityplan";
	$result = selectSql($sql);
	?>
	
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">จัดการข้อมูลผังเมือง</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">จัดการข้อมูลผังเมือง</h4>
	</div>
<div class="container-fluid">	
	<div class="row">
		<div class="col-xs-12 col-md-4" style="font-family: Kanit; text-align:left;">
			<h3  style="font-family: Kanit;margin:20px 0px 10px">จำนวน : <b id="value_row"></b> ผังเมือง</h3>
		</div>
		
		<div class="col-xs-8 col-md-8"> 
			<input id="search-name" onkeyup="searchname();" type="text" style="margin:10px 0px 10px;padding-left:5px;border-width:1px;border-style:solid;border-color:black;height:34px;border-radius:4px;width:100%" placeholder="ค้นหาด้วยชื่อผัง">
		</div>
		
		<div class="col-xs-4 col-md-2" style="text-align:right;">
			<!--<button class="btn btn-info pull-right" style="margin:10px 0px 10px" onclick="cpAdd();">เพิ่มข้อมูล</button>-->
		</div>
	</div>	

	<div id="manage_cityplan_tables" class="table-responsive" style="border-color:black">
		<img src="img/loading3.gif" class="center">
	</div>
</div>


<div class="modal fade" id="modal-cityplan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่มข้อมูล</h4>
            </div>
            <div class="modal-body">
				<form>
					<div class="form-group">
						<label for="cp_province" >จังหวัด<span style="color:red;"> *</span></label>
						<select class="form-control" id="cp_province" required>
							<option selected value="">เลือกจังหวัด</option>
							<?php 
							$sql = "SELECT * FROM ref_province ORDER BY pvn_name ASC";
							$result = selectSql($sql);
							foreach($result as $row){ ?>
							<option value="<?php echo $row['pvn_id'];?>"><?php echo $row['pvn_name'];?></option>
							<?php }?>
						</select>
					</div>
		
					<div class="form-group">
						<label for="cp_name">ชื่อผังเมืองรวม<span style="color:red;"> *</span></label>
						<input type="text" class="form-control" id="cp_name" value="">
					</div>
				
					<div class="row">
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_budget">ปีงบประมาณ</label>
							<input type="text" class="form-control" id="cp_budget" value="">
						</div>
						<div class="form-group col-xs-12 col-md-5">
							<label for="cp_type">ประเภทผังเมือง</label>
							<select class="form-control" id="cp_type" >
								<?php
									$sql ="SELECT * FROM ref_type";
									$result_type = selectSql($sql);
									foreach($result_type as $row){
								?>
								<option value="<?php echo $row['type_id']; ?>"><?php echo $row['type_name']; ?></option>
									<?php } ?>
							</select>
						</div>
			
						<div class="form-group col-xs-12 col-md-3">
							<label for="cp_eec">ผัง กลุ่ม EEC</label>
							<select class="form-control" id="cp_eec" >
								<option selected value="N">ไม่ใช่</option>
								<option value="Y">ใช่</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_scope">ขอบเขตผัง<span style="color:red;"> *</span></label>
							<select class="form-control" id="cp_scope" >
								<option selected value="">เลือกขอบเขตผัง</option>
								<?php 
									$sql ="SELECT * FROM ref_scope";
									$result = selectSql($sql);
									foreach($result as $row ){ 
								?>
								<option value="<?php echo $row['scope_id'];?>"><?php echo $row['scope_name'];?></option>
								<?php } ?>
							</select>
						</div>
					
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_status">สถานภาพผัง<span style="color:red;"> *</span></label>
							<select class="form-control" id="cp_status" >
								<option selected value="">เลือกสถานภาพผัง</option>
								<?php 
									$sql ="SELECT * FROM ref_status";
									$result = selectSql($sql);
									foreach($result as $row ){ 
								?>
								<option value="<?php echo $row['status_id'];?>"><?php echo $row['status_name'];?></option>
								<?php } ?>
							</select>
						</div>
				
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_stage">ขั้นตอน<span style="color:red;"> *</span></label>
							<select class="form-control" id="cp_stage" >
								<option selected value="">เลือกขั้นตอน</option>
								<?php 
									$sql ="SELECT * FROM ref_stage";
									$result = selectSql($sql);
									foreach($result as $row ){ 
								?>
								<option value="<?php echo $row['stage_id'];?>"><?php echo $row['stage_name'];?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_owner">เจ้าของผัง</label>
							<select class="form-control" id="cp_owner">
								<?php 
									$sql ="SELECT * FROM ref_operate_fst";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['opt_fst_id'];?>"><?php echo $row['opt_fst_name'];?></option>									
								<?php } ?>
		
							</select>
						</div>					
					
					
					
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_operator">ผู้ดำเนินการ</label>
							<select class="form-control" id="cp_operator">
								<?php 
									$sql ="SELECT * FROM ref_operate_snd";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['opt_snd_id'];?>"><?php echo $row['opt_snd_name'];?></option>									
								<?php } ?>
		
							</select>
						</div>
						
						<div class="form-group col-xs-12 col-md-4">
							<label for="cp_effective">มีผลบังคับใช้</label>
							<select class="form-control" id="cp_effective">
								<?php 
									$sql ="SELECT * FROM ref_effective";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['eff_id'];?>"><?php echo $row['eff_name'];?></option>									
								<?php } ?>
		
							</select>
						</div>
					
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp_step">ขั้นตอนดำเนินการหลัก</label>
							<select class="form-control" id="cp_step" onchange="changeFststep();">
								<option value="0">ไม่ระบุขั้นตอน</option>
								<?php 
									$sql ="SELECT * FROM ref_up_fst_step WHERE step_id > 0";
									$result = selectSql($sql);
									foreach($result as $row){?>
										<option value="<?php echo $row['step_id'];?>"><?php echo 'ขั้นตอนที่ ',$row['step_id'],' ',$row['step_name'];?></option>									
								<?php } ?>
							</select>
						</div>
						
					</div>
					<div class="row" id="div-step-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_start">วันที่เริ่มต้น(ขั้นตอนหลัก)</label>
							<input class="form-control cp_date" type="text" id="cp_step_start" value="" placeholder="ปี-เดือน-วัน">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_stop">วันที่สิ้นสุด (ขั้นตอนหลัก)</label>
							<input class="form-control cp_date" type="text" id="cp_step_stop" value="" placeholder="ปี-เดือน-วัน">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					<div class="row" id="div-step-1">
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp_step_1">ขั้นตอนดำเนินการรอง</label>
							<select class="form-control" id="cp_step_1" onchange="changeSndstep()">
								<option value="0">ไม่ระบุขั้นตอน</option>
							</select>
						</div>
					</div>
					<div class="row" id="div-step-1-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_start">วันที่เริ่มต้น (ขั้นตอนรอง 1)</label>
							<input class="form-control cp_date" type="text" id="cp_step_1_start" value="" placeholder="ปี-เดือน-วัน">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_1_stop">วันที่สิ้นสุด (ขั้นตอนรอง 1)</label>
							<input class="form-control cp_date" type="text" id="cp_step_1_stop" value="" placeholder="ปี-เดือน-วัน">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					
					
					<div class="row" id="div-step-2">
						<div class="form-group col-xs-12 col-md-12">
							<label for="cp_step_2">ขั้นตอนดำเนินการรอง2</label>
							<select class="form-control" id="cp_step_2" onchange="changeTrdstep()">
								<option value="">ไม่ระบุขั้นตอน</option>
							</select>
						</div>
					</div>
					<div class="row" id="div-step-2-date">
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_2_start">วันที่เริ่มต้น ขั้นตอนรอง 2)</label>
							<input class="form-control cp_date" type="text" id="cp_step_2_start" value="" placeholder="ปี-เดือน-วัน">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
						
						<div class="form-group col-xs-6 col-md-6">
							<label for="cp_step_2_stop">วันที่สิ้นสุด ขั้นตอนรอง 2)</label>
							<input class="form-control cp_date" type="text" id="cp_step_2_stop" value="" placeholder="ปี-เดือน-วัน">
							<i class="fa fa-calendar hidden-xs" style="position:absolute;margin-top:-25px;margin-left:80%;margin-right:20%"></i>
						</div>
					</div>
					
					<input type="hidden" name="cp_no" class="form-control" id="step" value="" >					
					<input type="hidden" name="cp_no" class="form-control" id="step_1" value="" >
					<input type="hidden" name="cp_no" class="form-control" id="step_2" value="" >
					<input type="hidden" name="cp_no" class="form-control" id="cp_no" value="" >
					<input type="hidden" name="cp_id" class="form-control" id="cp_id" value="" >
					<input type="hidden" id="event" value="">
					<input type="hidden" id="user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
				</form>
			</div>
            <div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="cpSave();">บันทึก</button>
            </div>
		</div>
    </div>
</div>

<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>
<script>
	function changeFststep(){
		if($('#cp_step').val()==0){
			dateFststep('hide');
			changeSndstep();		
		}else{
			$.post('app/manage.php',{
				event : 'r_step_1',
				step_id : $('#cp_step').val()
			},function(data){
				$('#cp_step_1').html(data);
				dateFststep('show');
				changeSndstep();
			});
		}
	}
	function changeSndstep(){
		if($('#cp_step').val()==0){
			$('#div-step-1').hide();
			dateSndstep('hide');
			changeTrdstep();
		}else{
			$.post('app/manage.php',{
				event : 'r_step_2',
				step_id : $('#cp_step').val(),
				sub_id : $('#cp_step_1').val()
			},function(data){
				$('#cp_step_2').html(data);			
				$('#div-step-1').show();
				if($('#cp_step_1').val()==0){
					dateSndstep('hide');
					changeTrdstep();
				}
				else{
					dateSndstep('show');
					changeTrdstep();
				}
			});		
		}
	}
	function changeTrdstep(){
		if($('#cp_step_1').val()==0 || $('#cp_step_2').val()=="" ){
			$('#div-step-2').hide();
			dateTrdstep('hide');
			//alert('a');
		}else{
			$('#div-step-2').show();
			if($('#cp_step_2').val()>0){
				dateTrdstep('show');
			}else{
				dateTrdstep('hide');
			}
			//alert('b');
		}
	}

	function dateFststep(x){
		$('#cp_step_start').val("");
		$('#cp_step_stop').val("");
		if(x=="hide"){
			$('#div-step-date').hide();
		}else if(x=="show"){
			$('#div-step-date').show();
		}
	}

	function dateSndstep(x){
		$('#cp_step_1_start').val("");
		$('#cp_step_1_stop').val("");
		if(x=="hide"){
			$('#div-step-1-date').hide();
		}else if(x=="show"){
			$('#div-step-1-date').show();
		}
	}

	function dateTrdstep(x){
		$('#cp_step_2_start').val("");
		$('#cp_step_2_stop').val("");
		if(x=="hide"){
			$('#div-step-2-date').hide();
		}else if(x=="show"){
			$('#div-step-2-date').show();
		}
	}


	/*function openSubstep(){
		if($('#cp_step').val()==0){
			$('#div-step-date').hide();
			$('#cp_step_start').val("");
			$('#cp_step_stop').val("");
			$('#cp_step_1').val(0);
			$('#div-step-1').hide();
			openTrdstep();
		}else{
			$('#div-step-date').show();
			$.post('app/manage.php',{
				event : 'r_step_1',
				step_id : $('#cp_step').val()
			},function(data){
				$('#div-step-1').show();
				$('#cp_step_1').html(data);
			});
		}
	}

	function openTrdstep(){
		if($('#cp_step_1').val()==0){		
			$('#div-step-1-date').hide();
			$('#cp_step_1_start').val("");
			$('#cp_step_1_stop').val("");
			$('#cp_step_2').val(0);
			$('#div-step-2').hide();
			openFthstep();
		}else{
			$('#div-step-1-date').show();
			$.post('app/manage.php',{
				event : 'r_step_2',
				step_id : $('#cp_step').val(),
				sub_id : $('#cp_step_1').val()
			},function(data){
				$('#div-step-2').show();
				$('#cp_step_2').html(data);
				if($('#cp_step_2').val()==""){
					$('#div-step-2').hide();
					$('#div-step-2-date').hide();
					openFthstep();
				}
			});
		}
	}
	function openFthstep(){
		if($('#cp_step_1').val()==0 || $('#cp_step_2').val()==null || $('#cp_step_2').val()==""){
			$('#div-step-2-date').hide();
			$('#cp_step_2_start').val("");
			$('#cp_step_2_stop').val("");
			//alert('a');
		}

		else{
			$('#div-step-2-date').show();	
			//alert($('#cp_step_2').val());
			//alert('b');
		}
	}
	*/

	/*function openSubstep(){
		$.post('app/manage.php',{
			event : 'r_step_1',
			step_id : $('#cp_step').val()
		},function(data){
			//$('#cp_step1').removeAttr('readonly');			
			$('#cp_step_1').html(data);
			if($('#cp_step').val() == '0'){
				$('#div-step-date').hide();
				$('#cp_step_start').val("");
				$('#cp_step_stop').val("");
				
				$('#div-step-1').hide();
				$('#cp_step_1').val("");
				$('#cp_step_1_start').val("");
				$('#cp_step_1_stop').val("");
				$('#div-step-1-date').hide();
				
				$('#div-step-2').hide();
				$('#cp_step_2').val("");
				$('#cp_step_2_start').val("");
				$('#cp_step_2_stop').val("");				
				$('#div-step-2-date').hide();
			}else{
				$('#div-step-date').show();
				$('#div-step-1').show();
			}
		});					
	}
	function openTrdstep(){
		if($('#cp_step_1').val()=='0'){
			$('#div-step-1-date').hide();
			$('#cp_step_1_start').val("");
			$('#cp_step_1_stop').val("");
		}
		$.post('app/manage.php',{
			event : 'r_step_2',
			step_id : $('#cp_step').val(),
			sub_id : $('#cp_step_1').val() 
		},function(data){
			$('#cp_step_2').html(data);
			if($('#cp_step_2').val()=="" || $('#cp_step_2').val()=="0" ){			
				
				$('#div-step-2').hide();
				$('#cp_step_2').val("");
				$('#cp_step_2_start').val("");
				$('#cp_step_2_stop').val("");	
			}else{
				$('#div-step-2').show();
				$('#div-step-1-date').show();
			}
		});			
		
	}
	
	function openFth(){
		if($('#cp_step_2').val()==0 || $('#cp_step_2').val()==""){
			$('#div-step-2-date').hide();
			$('#cp_step_2_start').val("");
			$('#cp_step_2_stop').val("");
		}else{
			$('#div-step-2-date').show();
		}
	}*/
	$(function () {
		$('#manage_cityplan_table').DataTable({
			'paging'      : true,
			'lengthChange': false,
			'lengthMenu'  : [[4, 50, 100, -1], [20, 50, 100, "ทั้งหมด"]],
			'searching'   : false,
			'ordering'    : false,
			'info'        : true,
			'processing'  : true,
			'autoWidth'   : false
		})
    })
	function cpAdd(){
		$('#cp_province').val("");
		$('#cp_id').val("");
		$('#cp_name').val("");
		$('#cp_budget').val("");
		$('#cp_type').val("0");
		$('#cp_eec').val("N");
		$('#cp_scope').val("");
		$('#cp_status').val("");		
		$('#cp_stage').val("");
		$('#event').val("c_cp");
		$('#div-step-date').hide();
		$('#div-step-1-date').hide();
		$('#div-step-2-date').hide();
		$('#div-step-1').hide();
		$('#div-step-2').hide();
		$('#modal-cityplan').modal('show');
	}
	function cpSave(){
		if ( $('#cp_name').val()=='' || $('#cp_province').val()=='' || $('#cp_scope').val()==''|| $('#cp_status').val()=='' || $('#cp_stage').val()==''){
				alert('กรุณากรอกข้อมูลให้ครบ');			
		}
		/*else if($('#cp_step').val()>0){if($('#cp_step_start').val() =="" || $('#cp_step_stop').val()==""){alert('กรุณากรอกวันที่เริ่มต้นและวันที่สิ้นสุด');}
		}
		else if($('#cp_step_1').val()>0){if($('#cp_step_1_start').val() =="" || $('#cp_step_1_stop').val()==""){alert('กรุณากรอกวันที่เริ่มต้นและวันที่สิ้นสุด');}
		}
		else if($('#cp_step_2').val()>0){if($('#cp_step_2_start').val() =="" || $('#cp_step_2_stop').val()==""){alert('กรุณากรอกวันที่เริ่มต้นและวันที่สิ้นสุด');}
		}	*/			
		else{
			$.post('app/manage.php',{
			cp_no:$('#cp_no').val(),
			cp_id:$('#cp_id').val(),
			cp_type:$('#cp_type').val(),
			cp_eec:$('#cp_eec').val(),
			cp_province:$('#cp_province').val(),	
			cp_name:$('#cp_name').val(),
			cp_budget:$('#cp_budget').val(),
			cp_owner:$('#cp_owner').val(),
			cp_operator:$('#cp_operator').val(),
			cp_scope:$('#cp_scope').val(),
			cp_status:$('#cp_status').val(),
			cp_stage:$('#cp_stage').val(),
			cp_effective:$('#cp_effective').val(),
			cp_step:$('#cp_step').val(),
			cp_step_start:$('#cp_step_start').val(),
			cp_step_stop:$('#cp_step_stop').val(),
			cp_step_1:$('#cp_step_1').val(),
			cp_step_1_start:$('#cp_step_1_start').val(),
			cp_step_1_stop:$('#cp_step_1_stop').val(),
			cp_step_2:$('#cp_step_2').val(),
			cp_step_2_start:$('#cp_step_2_start').val(),
			cp_step_2_stop:$('#cp_step_2_stop').val(),
			event:$('#event').val(),
			user_id:$('#user_id').val()
			 },function(data) {
				$('#modal-cityplan').modal('hide');
				alert(data);
				$('#mainshow').load('app/manage_cityplan.php');
			 console.log(data);
			 //$('#mainshow').html(data);
			 });
		}
	}
	function cpEdit(x){
		$.post('app/manage.php',{
		  event:'ru_cp',
		  cp_no: x
		  },function(data) {
			  //alert(data);
		  $('#cp_no').val(data.cp_no);
		  $('#cp_id').val(data.cp_id);
		  $('#cp_type').val(data.cp_type);
		  $('#cp_eec').val(data.cp_eec);
		  $('#cp_province').val(data.cp_province);
		  $('#cp_name').val(data.cp_name);
		  $('#cp_budget').val(data.cp_budget);
		  $('#cp_owner').val(data.cp_owner);
		  $('#cp_operator').val(data.cp_operator);
		  $('#cp_scope').val(data.cp_scope);
		  $('#cp_status').val(data.cp_status);
		  $('#cp_stage').val(data.cp_stage);
		  $('#cp_effective').val(data.cp_effective);
		  $('#cp_step').val(data.cp_step);
		  $('#cp_step_start').val(data.cp_step_start);
		  $('#cp_step_stop').val(data.cp_step_stop); 
		  $('#cp_step_1').val(data.cp_step_1);
  		  $('#cp_step_1_start').val(data.cp_step_1_start);
		  $('#cp_step_1_stop').val(data.cp_step_1_stop);
		  $('#cp_step_2').val(data.cp_step_2);
		  $('#cp_step_2_start').val(data.cp_step_2_start);
		  $('#cp_step_2_stop').val(data.cp_step_2_stop);	
		  $('#event').val('u_cp');
		  $('#user_id').val(data.user_id);
		  $('#step').val(data.cp_step);
		  $('#step_1').val(data.cp_step_1);
		  $('#step_2').val(data.cp_step_2);
		  
		  var w = $('#step').val();
		  var x = $('#step_1').val();
		  //alert(x);
		  if($('#cp_step').val()!=0){
			  $('#div-step-date').show();
			  $('#div-step-1').show();
			  $.post('app/manage.php',{
				  event : 'r_step_1',
				  step_id:$('#cp_step').val()
			  },function(data){
				  $('#cp_step_1').html(data).val(x);  
			  });
		  }
		  var y = $('#step_2').val();
		  //alert(y);
		  if($('#step_1').val()!=0){
			  //alert(y);
			  $('#div-step-1-date').show();
			  $('#div-step-2').show();
			  $('#div-step-2-date').show();
			  $.post('app/manage.php',{
				  event : 'r_step_2',
				  step_id : w,
				  sub_id : x
			  },function(data){
				  //alert("w : "+w+" , x : "+x);
				  $('#cp_step_2').html(data).val(y);
			  }); 
		  }	
			if($('#cp_step').val()==0){
				$('#div-step').hide();
				$('#div-step-date').hide();
				$('#div-step-1').hide();
				$('#div-step-1-date').hide();
				$('#div-step-2').hide();
				$('#div-step-2-date').hide();
			}
			
			if($('#step_1').val()==0){
				$('#div-step-1-date').hide();
				$('#div-step-2').hide();
				$('#div-step-2-date').hide();
			}	
			//alert($('#cp_step_2').val());
			if($('#step_2').val()==0 && ($('#cp_step_2').val()=="" || $('#cp_step_2').val()==null || $('#cp_step_2').val()==0)){ 
				$('#div-step-2').hide();
				//alert('hi');
				//changeTrdstep();
			}
			if($('#step_2').val()==0){
				$('#div-step-2-date').hide();
			}
		  
		  $('#modal-cityplan').modal('show');
		},'json');
	}
	function cpDel(x){
		if(confirm('คุณต้องการจะลบข้อมูลนี้ ใช่หรือไม่ ?')){
			$.post('app/manage.php',{
				cp_no:x,
				event:'d_cp',
				user_id:$('#user_id').val()
			},function(data){
				$('#modal-cityplan').modal('hide');
				alert(data);
				$('#mainshow').load('app/manage_cityplan.php');
			});
		}
	}
	searchname();
	function searchname(){
		$.post('app/search_data.php',{
			name : $('#search-name').val()
		},function(data){
			$('#manage_cityplan_tables').html(data);
		});
	}
	
	$(function(){  
		var thaiYear = function (ct) {
			var leap=3;  
			var dayWeek=["พฤ.", "ศ.", "ส.", "อา.","จ.", "อ.", "พ."];  
			if(ct){  
				var yearL=new Date(ct).getFullYear()-543;  
				leap=(((yearL % 4 == 0) && (yearL % 100 != 0)) || (yearL % 400 == 0))?2:3;  
				if(leap==2){  
					dayWeek=["ศ.", "ส.", "อา.", "จ.","อ.", "พ.", "พฤ."];  
				}  
			}              
			this.setOptions({  
				i18n:{ th:{dayOfWeek:dayWeek}},dayOfWeekStart:leap,  
			})                
		}; 
		$(".cp_date").datetimepicker({
			timepicker:false,
			format:'Y-m-d',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
			lang:'th',  // แสดงภาษาไทย
			onChangeMonth:thaiYear,          
			onShow:thaiYear,                  
			yearOffset:543,  // ใช้ปี พ.ศ. บวก 543 เพิ่มเข้าไปในปี ค.ศ
			closeOnDateSelect:true,
		});       
	   
	});
	
</script>