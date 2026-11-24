<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	
/*
	$sql = "SELECT *,COUNT(*) FROM tb_user WHERE user_id = '".$_SESSION['intra_czid']."'";
	$result = selectSql($sql);
	
	echo 'ID : '.$result[0]['user_id'];
	echo 'status : '.$result[0]['user_status'];
*/
	
?>
<h2 class="GHfont" style="text-align:center;font-family:Kanit">History (ทดสอบ)</h2>

<div class="row" style="margin:0px 0px 8px 0px">
	<div id="record_bar" class="col-xs-6">
		<h3 style="font-family: Kanit;margin:0px;margin-top:7px">จำนวน : <b id="count_data"></b> ผัง</h3>
	</div>
	<div class="col-xs-6" style="text-align:right">
		<input style="margin-top:14px;border-style:solid;border-color:black;border-width:1px;border-radius:8px;width:100%;font-size:18px;padding-left:4px" placeholder="ค้นหาผังเมือง">
	</div>

</div>


<div id="showhistory" class="container-fluid" style="border-style:solid;border-color:transparent;border-width:5px"></div>
<div id="showpagebutton" style="text-align:center;margin-top:8px">
	<button id="btn_first" onclick="table_con('first')" class="btn btn-info" style="margin-top:-8px;">«</button>
	<button id="btn_back" onclick="table_con('back')" class="btn btn-warning" style="margin-top:-8px;"><</button>
	<select id="select_table" onchange="table_change()" style="border-radius:8px;font-size:20px;text-align:center">	
	</select>
	<button id="btn_next" onclick="table_con('next')" class="btn btn-warning" style="margin-top:-8px;">></button>
	<button id="btn_last" onclick="table_con('last')" class="btn btn-info" style="margin-top:-8px;">»</button>	
</div>


	<div class="modal fade" id="modal-note">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:#E6FCC1;border-radius:8px" >
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 id="modal_title" class="modal-title" style="font-family:Kanit;color:black"></h4>
              </div>
              <div class="modal-body">
				<form>	
				<div class="form-group" id="note_step_show">
					<span id="note_step"></span>	
				</div>
				
				<div class="form-group" id="note_step_1_show">
					<span id="note_step_1"></span>	
				</div>
				
				<div class="form-group" id="show_note_remark"></div>
				<div class="form-group" id="edit_note_remark">.</div>
				<?php
				/*<div class="form-group">
						<label for="sub_note">หมายเหตุ</label>
						<textarea style="border-radius:8px;border-color:#ccc;color:#333;width:100%;height:100px;padding-left:10px" id="note_remark"></textarea>
				</div>*/
				?>
				<input type="hidden" id="note_id" value="">
				<input type="hidden" id="note_cp" value="">
				<input type="hidden" name="note_id" class="form-control" id="note_id" value="" >
                <input type="hidden" id="note_uper" value="<?php echo $_SESSION['intra_czid']; ?>">				
                <input type="hidden" id="note_event" value="">
				<input type="hidden" id="note_step_hidden" value="">
				<input type="hidden" id="note_step_1_hidden" value="">
				</form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ปิด</button>
                <button id="addBtn" type="button" class="btn btn-success pull-right" onclick="Addnote_inside();">เพิ่มหมายเหตุ</button>
				<button id="editBtn" type="button" class="btn btn-success pull-right" onclick="Savenote();">บันทึก</button>
				<button id="cancleBtn" type="button" class="btn btn-warning pull-right" onclick="Canclenote();">ยกเลิก</button>
				<button id="delBtn"type="button" class="btn btn-danger pull-right" onclick="Delnote();">ลบหมายเหตุ</button>
              </div>
            </div>

          </div>

    </div>

<script>
historyshow();
function historyshow(){
	$.post('app/pro_history.php',{
		type : 1
		
	},function(data){
		$('#showhistory').html(data);
	});
}


function Addnote_inside(){
	var x = $('#note_step_hidden').val();
	var y = $('#note_step_1_hidden').val();
	var z = $('#note_cp').val();
	//alert (x+" "+y+" "+z);
	Addnote(x,y,z);
	
}
function Addnote(x,y,z){		
	$.post('app/manage.php',{
		event :'ru-c_stepnote',
		step_id : x,
		sub_no : y
	},function(data){
		$('#delBtn').hide();
		$('#note_step_hidden').val(x);
		$('#note_step_1_hidden').val(y);
		$('#note_cp').val(z);
		$('#modal-note').modal('show');
		$('#note_event').val('c_note(his)');
		$('#show_note_remark').html(data);
		$('#note_remark').val("");
		$('#note_id').val("");
	});	
}


function Savenote(){
	$.post('app/manage.php',{
		event : $('#note_event').val(),
		note_remark : $('#note_remark').val(),
		note_cp : $('#note_cp').val(),
		note_step : $('#note_step_hidden').val(),
		note_step_1 : $('#note_step_1_hidden').val(),
	},function(data){	
		$('#modal-note').modal('hide');
		alert(data);
		$('#mainshow').load('app/history_data.php')
	});
}

	
	/*
	function Addnote(x,y,z){	
		$.post('app/manage.php',{
			event :'ru-c_stepnote',
			step_id : x,
			sub_no : y
		},function(data){
			$('#delBtn').hide();
			$('#cancleBtn').hide();
			$('#editBtn').show();
			$('#note_step_hidden').val(x);
			$('#note_step_1_hidden').val(y);
			$('#note_cp').val(z);
			$('#modal-note').modal('show');
			$('#note_event').val('c_note+u_cp');
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();
			$('#edit_note_remark').html('');
			$('#edit_note_remark').show();
			$('#modal_title').html("เพิ่มหมายเหตุ");
			$('#note_remark').val("");
			$('#note_id').val("");
		});	
	}
	
	
	function Savenote(){
		if($('#note_remark').val()=="" || $('#note_remark').val()==null){
			alert("กรุณาระบุหมายเหตุ");
		}else{
			$.post('app/manage.php',{
				event : $('#note_event').val(),
				note_remark : $('#note_remark').val(),
				note_step : $('#note_step_hidden').val(),
				note_step_1 : $('#note_step_1_hidden').val(),
				note_cp : $('#note_cp').val(),
				note_id : $('#note_id').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				$('#mainshow').load('report/table_step_note.php');
				console.log(data);
			});
		}
	}
*/

	function Readnote(x,y,z){
		//alert('step : '+x+', sub : '+y+'cp_no : '+z);
		$.post('app/manage.php',{
			event : 'ru_stepnotex',
			step_id :  x,
			sub_no : y,
			cp_no : z
		},function(data){
			$('#delBtn').hide();
			$('#editBtn').hide();
			$('#cancleBtn').hide();
			//console.log(data);
			$('#show_note_remark').html(data);
			$('#show_note_remark').show();
			$('#edit_note_remark').hide();
			$('#note_event').val('u_note+u_cp');
			$('#modal_title').html("แก้ไข/ลบหมายเหตุ");
			$('#modal-note').modal('show');
		});
	}
	function Editnote(x,y,z){
		var a = $('#div_note_'+x).html();
		$('#show_note_remark').hide();
		$.post('app/manage.php',{
			event : 'ru_selectnote',
			note_id : y,
			cp_no : z
		},function(data){
			$('#edit_note_remark').show();
			$('#edit_note_remark').html(data);
			$('#note_remark').val(a);
			$('#note_id').val(y);
			$('#note_cp').val(z);
			$('#editBtn').show();
			$('#cancleBtn').show();
			$('#note_event').val('u_note+u_cp');
		});
		/*$('.input_note').hide();
		$('.div_note').show();
		$('#div_note_'+x).hide();
		var a = $('#div_note_'+x).html();
		$('#note_'+x).show();
		$('#note_'+x).val(a);
		$('#editBtn').show();
		$('#cancleBtn').show();*/
		//alert(a);
	}
	
	function Canclenote(){
		$('.input_note').hide();
		$('.div_note').show();
		$('#editBtn').hide();
		$('#cancleBtn').hide();
		$('#show_note_remark').show();
		$('#edit_note_remark').hide();
	}
	/*
	function Readnote(x){
		$.post('app/manage.php',{
			event : 'ru_note2',
			cp_no : x
		},function(data){
			$('#delBtn').show();
			$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_remark').val(data.note_remark);
			$('#note_cp').val(data.cp_no);
			$('#note_event').val('u_note+u_cp');
			$('#note_id').val(data.note_id);
			//alert(data);
			//console.log(data);
			$('#modal-note').modal('show');
		},'json');
	}
	*/
	/* //Origin read edit note// 
	function Readnote(x){
		$.post('app/manage.php',{
			event : 'ru_stepnotex',
			note_id : x
		},function(data){
			$('#delBtn').show();
			$('#note_step').html(data.step_name);
			$('#note_step_1').html(data.sub_name);
			$('#note_remark').val(data.note_remark);
			$('#note_cp').val(data.note_cp);
			$('#note_event').val('u_note+u_cp');
			$('#note_id').val(data.note_id);
			//alert(data);
			//console.log(data);
			$('#modal-note').modal('show');
		},'json');
	}
	*/

	
	function Delnote(x,y){
		if(confirm("คุณต้องการลบหมายเหตุใช่หรือไม่ ? ")){
			$.post('app/manage.php',{
				event : 'd_stepnote',
				note_id : x,
				cp_no : y
				//cp_no : $('#note_cp').val()
			},function(data){
				$('#modal-note').modal('hide');
				alert(data);
				//console.log(data);
				$('#mainshow').load('report/table_step_note.php');
			});
		}
	}







</script>