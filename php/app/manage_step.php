<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	//$sql = "SELECT COUNT(cp_no) FROM tb_cityplan";
	//$result = selectSql($sql);
	?>
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการ</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการ</h4>
	</div>	
	<br>
	<div class="container-fluid">
	<button id="substep" onclick="switchtable();" class="pull-right btn btn-warning btn-md">แสดงขั้นตอนรอง</button>
	<br>&nbsp;
	<!--<button class="btn btn-warning btn-md">แสดงขั้นตอนรองและขั้นย่อย</button>-->
		<div class="table-responsive" style="border-color:black">
			<table id="main_step" class="table table-bordered" >
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center">ขั้นตอน</td>
						<td style="text-align:center" colspan="2">ระยะเวลา<br>(สัปดาห์)</td>
						<td style="text-align:center">วันที่เริ่ม</td>
						<td style="text-align:center">วันที่สิ้นสุด</td>
						<td style="text-align:center">หมายเหตุ</td>
						<td style="text-align:center">จัดการ</td>
					</tr>
				</thead>
				<tbody style="background-color:white">
					<?php 
						$sql ="SELECT * FROM ref_up_fst_step , ref_note WHERE step_id > 0 AND step_note = note_id";
						$result = selectSql($sql);
						foreach($result as $row){ ?>
						<tr>
						<td style="text-align:left"><a onclick="substep(<?php echo $row['step_id'];?>)"><b>[<?php echo $row['step_id']?>]</b> </a><?php echo $row['step_name'];?></td>
						<?php 
							if($row['step_time']==0){
								echo '<td style="text-align:center">',$row['step_time1'],'</td>';
								echo '<td style="text-align:center">',$row['step_time2'],'</td>';
							}else{
								echo '<td style="text-align:center" colspan="2">',$row['step_time'],'</td>';							
							}
						?>
						<td style="text-align:center"><?php if($row['step_start']==NULL){ echo "-";}else{echo $row['step_start'];}?></td>
							<td style="text-align:center"><?php if($row['step_end']==NULL){ echo "-";}else{echo $row['step_end'];}?></td>
							<td style="text-align:center">
								<?php 
									///////step_note to array , array compare sql , sql to array////////
									if($row['step_note'] ==NULL){
										echo '-';
									}else{
										$note_array = explode(",",$row['step_note']);								
										$count_array = count($note_array);
										?><span class="list-ol" onclick="goNote(<?php echo $row['step_id'];?>);"><a id="list-ol"><i class="fa fa-list-ol"></i></a></span><?php
									}
									/*
									$note = "";
									for($i=0 ; $i<$count_array;$i++){
										$sql = "SELECT * FROM ref_note WHERE note_id = '$note_array[$i]'";
										$note_result =  selectSql($sql);
										//if($i>0){ echo '<br>';}
										//echo $note_result[0]['note_name'];
										if($i==0){
											$note = $note_result[0]['note_name'];
										}else{
											$note = $note." , ".$note_result[0]['note_name'];
											}
									}	
									echo $note;
									*/
								?>
							</td>
							<td style="text-align:center;" onclick="editMain(<?php echo $row['step_id']?>);" class="gear"><a id="gear"><i class="fa fa-gear"></i></a></td>
						</tr> 
					<?php } ?>
				</tbody>	
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="modal-main">
        <div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
					<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" style="font-family:Kanit;color:black">แก้ไขข้อมูล</h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<b><span id="step_name"></span></b>
						</div>
				
						<div class="form-group" id="step_normal">
							<label for="step_time">ระยะเวลา(สัปดาห์)</label>
							<input type="number" class="form-control" id="step_time" value="">
						</div>
				
						<div class="row" id="step_request">
							<div class="col-xs-6">
								<div class="form-group">
									<label for="step_time1">กรณีไม่มีคำร้อง</label>
									<input type="number" class="form-control" id="step_time1" value="">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="step_time2">กรณีมีคำร้อง</label>
									<input type="number" class="form-control" id="step_time2" value="">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label for="step_start">เริ่มต้น</label>
									<input type="date" class="form-control" id="step_start" >
								</div>
							</div>
							
							<div class="col-xs-6">
								<div class="form-group">
									<label for="step_end">สิ้นสุด</label>
									<input type="date" class="form-control" id="step_end">
								</div>
							</div>
						</div>
						
						<div class="form-group">
								<label for="step_note">หมายเหตุ</label>
								<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px" id="step_note"></textarea>
						</div>
			 			<input type="hidden" name="step_id" class="form-control" id="step_id" value="" >
						<input type="hidden" id="main_event" value="">
						<input type="hidden" id="main_user_id" value="<?php echo $_SESSION['intra_czid']; ?>">	
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
					<button type="button" class="btn btn-success pull-right" onclick="saveMain();">บันทึก</button>
				</div>
            </div>
        </div>
        
    </div>
		
		
		
	<div class="modal fade" id="modal-sub">
        <div class="modal-dialog">
            <div class="modal-content">
				<div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
					<button  type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="font-family:Kanit;color:black">แก้ไขข้อมูล</h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<b><span id="sub_name"></span></b>
						</div>
				
						<div class="form-group" id="sub_normal">
							<label for="sub_time">ระยะเวลา(สัปดาห์)</label>
							<input type="number" class="form-control" id="sub_time" value="">
						</div>
				
						<div class="row" id="sub_request">
							<div class="col-xs-6">
								<div class="form-group">
									<label for="sub_time1">กรณีไม่มีคำร้อง</label>
									<input type="number" class="form-control" id="sub_time1" value="">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="sub_time2">กรณีมีคำร้อง</label>
									<input type="number" class="form-control" id="sub_time2" value="">
								</div>
								
							</div>
						</div>

						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label for="sub_start">เริ่มต้น</label>
									<input type="date" class="form-control" id="sub_start" value="0">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="sub_end">สิ้นสุด</label>
									<input type="date" class="form-control" id="sub_end" value="0">
								</div>
								
							</div>
						</div>
						
						<div class="form-group">
								<label for="sub_note">หมายเหตุ</label>
								<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px" id="sub_note"></textarea>
						</div>
						<input type="hidden" name="sub_id" class="form-control" id="sub_id" value="" >
						<input type="hidden" id="sub_event" value="">
						<input type="hidden" id="sub_user_id" value="<?php echo $_SESSION['intra_czid']; ?>">	
					</form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="saveSub();">บันทึก</button>
              </div>
            </div>
        </div>
    </div>
		
		
		
	<script>		
		function switchtable(){
			$('#mainshow').load('app/manage_stepsub.php');
		}
		
		function substep(x){
			$.post('app/manage_substep.php',{
			x : x		
			},function(data) {
			  //alert(data);	  
			  $('#mainshow').html(data);
			});
		}
		
		function editMain(x){
			//alert('Main : '+x);
			$.post('app/manage.php',{
				step_id : x,
				event : 'ru_step'
			},function(data){
				$('#step_id').val(data.step_id);
				$('#step_name').html('ขั้นตอนที่ '+data.step_id+' '+data.step_name);
				$('#step_time').val(data.step_time);
				$('#step_time1').val(data.step_time1);
				$('#step_time2').val(data.step_time2);
				$('#step_start').val(data.step_start);
				$('#step_end').val(data.step_end);
				$('#step_note').val(data.step_note);
				$('#modal-main').modal('show');
				if($('#step_time').val()=="0"){
					$('#step_normal').hide();
					$('#step_request').show();
				}else{
					$('#step_request').hide();
					$('#step_normal').show();
				}
			},'json');
		}
		

		function saveMain(){
			if( $('#step_time').val() == ''){$('#step_time').val(0);}
			if( $('#step_time1').val() == ''){$('#step_time1').val(0);}
			if( $('#step_time2').val() == ''){$('#step_time2').val(0);}
			//if( $('#step_note').val() == ''){$('#step_note').val(0);}
				$.post('app/manage.php',{
					event : 'u_step',
					step_id : $('#step_id').val(),
					//step_name : $('#step_name').val(),
					step_time : $('#step_time').val(),
					step_time1 : $('#step_time1').val(),
					step_time2 : $('#step_time2').val(),
					step_start : $('#step_start').val(),
					step_end : $('#step_end').val(),
					step_note : $('#step_note').val(),
				},function(data){
					$('#modal-main').modal('hide');
					alert(data);
					$('#mainshow').load('app/manage_step.php');
				});
			
		} 
		
		function editSub(x){
			$.post('app/manage.php',{
				sub_id : x,
				event : 'ru_sub'
			},function(data){
				//alert(data);
				$('#sub_id').val(data.sub_id);
				$('#sub_name').html('ขั้นตอนที่ '+data.sub_no+' '+data.sub_name);
				$('#sub_time').val(data.sub_time);
				$('#sub_time1').val(data.sub_time1);
				$('#sub_time2').val(data.sub_time2);
				$('#sub_start').val(data.sub_start);
				$('#sub_end').val(data.sub_end);
				$('#sub_note').val(data.sub_note);
				$('#modal-sub').modal('show');
				if($('#sub_time').val()=="0"){
					$('#sub_normal').hide();
					$('#sub_request').show();
				}else{
					$('#sub_request').hide();
					$('#sub_normal').show();
				}
			},'json');
		}
		
		function saveSub(){
			if( $('#sub_time').val() == ''){$('#sub_time').val(0);}
			if( $('#sub_time1').val() == ''){$('#sub_time1').val(0);}
			if( $('#sub_time2').val() == ''){$('#sub_time2').val(0);}
			if( $('#sub_note').val() == ''){$('#sub_note').val(0);}
				$.post('app/manage.php',{
					event : 'u_sub',
					sub_id : $('#sub_id').val(),
					//step_name : $('#step_name').val(),
					sub_time : $('#sub_time').val(),
					sub_time1 : $('#sub_time1').val(),
					sub_time2 : $('#sub_time2').val(),
					sub_start : $('#sub_start').val(),
					sub_end : $('#sub_end').val(),
					sub_note : $('#sub_note').val(),
				},function(data){
					$('#modal-sub').modal('hide');
					alert(data);
					$('#mainshow').load('app/manage_step.php'+'#mainsub_step');
					//switchtable(2);		
				});
			
		} 
		function goNote(x){
			$.post('app/note.php',{
				step_id : x
			},function(data){
				$('#mainshow').html(data);
			});
		}
	</script>