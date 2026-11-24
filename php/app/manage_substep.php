<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	if($_POST['x']!=''){
		$_SESSION['x'] = $_POST['x'];
	}if($_POST['x'] ==''){
		$_POST['x'] = $_SESSION['x'];
		//echo $_POST['x'];
	}
?>	
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการรอง</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">ขั้นตอนดำเนินการรอง</h4>
	</div>	
	<br>
	<div class="container-fluid">
	<div class="row">
		<div class="col-xs-3 col-md-6">
			<button onclick="goto('a3');" class="pull-left btn btn-danger btn-md">ย้อนกลับ</button>
		</div>
		<div class="col-xs-9 col-md-6">
			<select id="select-main" onchange="changesub();" class="pull-right" style="height:34px;width:90%;border-radius:4px">
				<?php 
				$sql ="SELECT * FROM ref_up_fst_step WHERE step_id > 0";
				$result = selectSql($sql);
				foreach($result as $row){ ?>
					<option value="<?php echo $row['step_id'];?>"><?php echo $row['step_id'],'. ',$row['step_name'];?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="table-responsive" style="border-color:black;margin-top:6px">
		<table class="table table-bordered">
			<thead>
				<tr style="background-color: #0AAAFA;color:white">
					<td style="text-align:center">ขั้นตอน</td>
					<td style="text-align:center" colspan="2">ระยะเวลา <br>(สัปดาห์)</td>
					<td style="text-align:center">วันที่เริ่มต้น</td>
					<td style="text-align:center">วันที่สิ้นสุด</td>
					<td style="text-align:center">หมายเหตุ</td>
					<td style="text-align:center">จัดการ</td>
					
				</tr>
			</thead>
			<tbody>
		<?php 
			$sql ="SELECT * FROM ref_up_snd_step,ref_up_fst_step,ref_note WHERE sub_step = step_id AND sub_step = '".$_POST['x']."' AND sub_note = note_id";
			//echo $sql;
			$result = selectSql($sql);
			foreach ($result as $row){ ?>
				<tr>
					<td style="text-align:left"><?php echo $row['sub_no'],' ',$row['sub_name'];?></td>
					<?php if($row['sub_time']==0){ ?>
							<td style="text-align:center"><?php	echo $row['sub_time1'];?></td>
							<td style="text-align:center"><?php	echo $row['sub_time2'];?></td>							
					<?php }else{ ?>
							<td style="text-align:center" colspan="2"><?php	echo $row['sub_time'];?></td>
					<?php } ?>
					<td style="text-align:center">
						<?php if($row['sub_start']==''){echo '-';}else{echo $row['sub_start'];}?>
					</td>
					<td style="text-align:center">
						<?php if($row['sub_end']==''){echo '-';}else{echo $row['sub_end'];}?>
					</td>
					<td style="text-align:center"><?php echo $row['note_name'];?></td>
					<td style="text-align:center" class="gear" onclick="editSub(<?php echo $row['sub_id']?>);"><a id="gear"><i class="fa fa-gear"></i></a ></td>
					
				</tr>
			<?php } ?>
			</tbody>
		</table>
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
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="saveSub();">บันทึก</button>
				</form>
              </div>
            </div>

          </div>

        </div>
	
	<script>
		$('#select-main').val('<?php echo $_SESSION["x"];?>');
		
		function changesub(){
			var x = $('#select-main').val();
			$.post('app/manage_substep.php',{
				x : x
			},function(data){
				$('#mainshow').html(data);
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
					$('#mainshow').load('app/manage_substep.php');
					$().val();
					//switchtable(2);		
				});
			
		} 

	
	
	</script>