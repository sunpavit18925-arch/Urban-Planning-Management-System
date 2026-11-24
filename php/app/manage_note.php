<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
?>	
	<div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">จัดการหมายเหตุ</h2>
		<h4 class="hidden-md hidden-lg GHfont-mini" style="font-family: Kanit;text-align:center">จัดการหมายเหตุ</h4>
	</div>	
	<br>
	<div class="container-fluid">
	<div class="row">
		<div class="col-xs-9 col-md-6">
			<?php 
				$sql ="SELECT COUNT(note_id) FROM tb_note WHERE note_id > 0";
				$result = selectSql($sql);
			?>
			<span>จำนวนหมายเหตุ : <?php echo $result[0]['COUNT(note_id)']; ?> </span>
		</div>
		<div class="col-xs-3 col-md-6">
			<button onclick="addNote();" class="pull-right btn btn-danger btn-md">เพิ่มหมายเหตุ</button>
		</div>
	</div>
	<div class="table-responsive" style="border-color:black;margin-top:6px">
		<table class="table table-bordered">
			<thead>
				<tr style="background-color: #0AAAFA;color:white">
					<td style="text-align:center">#</td>
					<td style="text-align:center">หมายเหตุ</td>				
					<td style="text-align:center">จัดการ</td>
				</tr>
			</thead>
			<tbody>
		<?php 
			$sql ="SELECT * FROM tb_note WHERE note_id > 0";
			//echo $sql;
			$result = selectSql($sql);
			foreach ($result as $row){ ?>
				<tr>
					<td style="text-align:center"><?php echo $row['note_id'];?></td>	
					<td style="text-align:center"><?php echo $row['note_remark'];?></td>	
					<td style="text-align:center">
						<span class="gear" onclick="editNote(<?php echo $row['note_id'];?>)"><a id="gear"><i class="fa fa-gear"></i></a></span>
						<span class="trash" onclick="delNote(<?php echo $row['note_id'];?>)"><a id="trash"><i class="fa fa-trash"></i></a></span>
					
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	
	<div class="modal fade" id="modal-note">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-family:Kanit;color:black">เพิ่ม/แก้ไขหมายเหตุ</h4>
              </div>
              <div class="modal-body">
				<form>	
				<div class="form-group" id="note_step_show">
					<label for="note_step">
					<select class="form-control" id="note_step" onchange="changeFststep()">
						<?php 
							$sql = "SELECT * FROM ref_up_fst_step WHERE step_id>0";
							$result = selectSql($sql);
							echo "<option value ='0'> เลือกขั้นตอน </option>";
							foreach($result as $row){
								?>
								<option value="<?php echo $row['step_id']; ?>"><?php echo "ขั้นตอนที่ ".$row['step_id']." ".$row['step_name'];?></option>
								<?php
							}
						
						
						?>
					</select>
				</div>
				
				<div class="form-group" id="note_step_1_show">
					<label for="note_step_1">
					<select class="form-control" id="note_step_1" onchange="changeSndtep()">
					</select>
				</div>

				<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>
			 

	
				<input type="hidden" name="note_id" class="form-control" id="note_id" value="" >
                <input type="hidden" id="note_event" value="">
                <input type="hidden" id="note_user_id" value="<?php echo $_SESSION['intra_czid']; ?>">				
				</form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success pull-right" onclick="saveNote();">บันทึก</button>
              </div>
            </div>

          </div>

        </div>
	
	<script>
	$('#note_step_1_show').hide();
		function addNote(){
			$('#note_id').val('');
			$('#note_remark').val('');
			$('#note_event').val('c_note');
			$('#modal-note').modal('show');
		}
		
		function editNote(x){
			$.post('app/manage.php',{
				note_id : x,
				event : 'ru_note'
			},function(data){
				//alert(data);
				$('#note_id').val(data.note_id);
				$('#note_remark').val(data.note_remark);
				$('#note_event').val('u_note');
				$('#modal-note').modal('show');
			},'json');
		}
		
		function saveNote(){
			if($('#note_remark').val()==''){
				alert('กรุณาระบุชื่อหมายเหตุ');
			}else{
				$.post('app/manage.php',{
					event : $('#note_event').val(),
					note_id : $('#note_id').val(),
					note_remark : $('#note_remark').val(),
				},function(data){
					$('#modal-note').modal('hide');
					alert(data);
					$('#mainshow').load('app/manage_note.php');
				});
			}
		}
		
		function delNote(x){
			if(confirm('คุณต้องการลบหมายเหตุนี้ใช่หรือไม่')){
				$.post('app/manage.php',{
					event : 'd_note',
					note_id : x
				},function(data){
					alert(data);
					$('#mainshow').load('app/manage_note.php');
				})		
			}			
		}
		function changeFststep(){
			if($('#note_step').val()==0){
				$('#note_step_1').val(0);
				$('#note_step_1_show').hide();
			}else{
				$.post('app/manage.php',{
					event : 'r_step_1',
					step_id : $('#note_step').val()
				},function(data){
					$('#note_step_1_show').show();
					$('#note_step_1').html(data);
				});
			}
		}
	</script>