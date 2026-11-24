<?php 
	include '../js/function_db.php';
	session_start();
	if($_POST['name']=="" OR $_POST['name']== NULL ){
		$sql = "SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_type , ref_scope , ref_operate_fst , ref_operate_snd , ref_status , ref_stage ,ref_effective , ref_up_fst_step WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_type = type_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_effective = eff_id AND cp_step = step_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id ORDER BY cp_no ASC";
	}else{
		$sql = "SELECT * FROM tb_cityplan , ref_province , ref_affiliation , ref_type , ref_scope , ref_operate_fst , ref_operate_snd , ref_status , ref_stage , ref_effective ,ref_up_fst_step WHERE cp_name LIKE '%".$_POST['name']."%' AND cp_province = pvn_id AND pvn_affiliation = aff_id AND cp_type = type_id AND cp_scope = scope_id AND cp_status = status_id AND cp_stage = stage_id AND cp_effective = eff_id AND cp_step = step_id AND cp_owner = opt_fst_id AND cp_operator = opt_snd_id ORDER BY cp_no ASC";
	}
	$result = selectSql($sql);
	$row = 10;
	$start=0;
	$count_row =count($result);
	$t=1;
	
	?>
	
	<?php
	if($count_row == 0){
		echo "<h2 style='font-family:Kanit;text-align:center'>ไม่พบข้อมูล </h2>";
		$i = 0;
		//echo "<script> $('#tableshow').hide();</script>";
	}else{
	for($t=1,$r=1;$r <=$count_row;$t++){ ?>
		<!--<h1><?php //echo 'table id = table_'.$t;?></h1>-->
	<div id="<?php echo 'table_'.$t; ?>"  class="table-responsive" style="border-color:black">
		<table class="table table-bordered">	
			<thead>
				<tr style='background-color: #0AAAFA;color:white'>
					<td style='text-align:center'>ลำดับ</td>
					<!--echo "<th style='text-align:center'>รหัสผังเมือง</th>-->
					<!-- //echo "<th style='text-align:center'>ปีงบประมาณ</th>-->
					<td style='text-align:center;'>จังหวัด</td>
					<td style='text-align:center;'>ชื่อผังเมืองรวม</td>
					<!-- echo "<th style='text-align:center;width:'>ประเภทผัง</th>-->
					<!-- echo "<th style='text-align:center'>ผัง EEC</th>-->
					<td style='text-align:center'>ขอบเขต</td>
					<td style='text-align:center'>สถานะผัง</td>
					<td style='text-align:center'>ขั้นตอน</td>
					<td style='text-align:center'>เจ้าของผัง</td>
					<td style='text-align:center'>ผู้ดำเนินการ</td>
					<td style='text-align:center'>มีผลบังคับใช้</td>
					<td style='text-align:center'>ขั้นตอนดำเนินการหลัก</td>
					<td style='text-align:center'>จัดการ </td>
				</tr>
			</thead>
			<tbody>	
				<?php 			
				for($j=0,$i=$start;$j<$row;$j++,$i++,$start++){
					if($i==$count_row){
						break;
					}
				?>
				<tr>
					<td style="text-align:center"><?php echo $i+1;?></td>
					<td style="text-align:center"><?php echo $result[$i]['pvn_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['cp_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['scope_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['status_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['stage_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_fst_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['opt_snd_name'];?></td>
					<td style="text-align:center"><?php echo $result[$i]['eff_name'];?></td>
					<td style="text-align:center"><?php if($result[$i]['cp_step']==0){echo '-';}else{echo 'ขั้นที่ ',$result[$i]['cp_step'];}?></td>
					<td style="text-align:center">
						<span class="gear" onclick="cpEdit(<?php echo $result[$i]['cp_no'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
						<span hidden class="trash" onclick="cpDel(<?php echo $result[$i]['cp_no'] ;?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>	
					</td>
				</tr>
				<?php $r++;
				} ?>
			
			</tbody>	
		</table>
	</div>
		
	<?php } ?>
	<!--<div class="row pull-right" style="display:inline;text-align:center;margin-right:5px">-->
	
	<div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center">
	<div class="div-center" style="height:36px;margin:0px -25px 0px -25px">
	
		<button id="first_page" title="หน้าแรก" class="btn btn-info" onclick="tableshow(1);" style="border-color:transparent;font-size:20px;padding-top:3px;margin: -7.5px 0px 0px 0px;height:36px">«</button>
		<button id="back_page" title="ย้อนกลับ" class="btn btn-warning" onclick="conpage('back')" style="border-color:transparent;font-size:16px;padding-top:6px;margin: -7.5px 0px 0px 0px;height:36px"><</button>
		<select id="select-pages" style="border-radius:8px;font-size:20px;text-align:center;margin: 0px 0px 0px 0px;height:36px" class="" onchange="minishow()">
			<?php
				
				for($x=1;$x<$t;$x++){
					?><option value="<?php echo $x; ?>"> <?php echo $x; ?> </option> <?php
				}
				
			?>
		</select>
		
		<select id="select-page" style="margin-top:15px;border-radius:16px;font-size:24px;text-align:center;height:36px" >
			<?php
				
				for($x=1;$x<$t;$x++){
					?><option value="<?php echo $x; ?>"> <?php echo $x; ?> </option> <?php
				}
				
			?>
		</select>
		<button id="next_page" title="ถัดไป" class="btn btn-warning" onclick="conpage('next')" style="border-color:transparent;font-size:16px;padding-top:6px;margin: -7.5px 0px 0px 0px;height:36px">></button>
		<button id="last_page" title="หน้าสุดท้าย" class="btn btn-info" onclick="tableshow(<?php echo $t-1;?>);" style="border-color:transparent;font-size:20px;padding-top:3px;margin: -7.5px 0px 0px 0px;height:36px">»</button>
	</div>
	</div>
	<?php } ?>
<div id="div1"></div>
	
	<script> 
		$('#select-page').hide();
		$('#value_row').html('จำนวน : '+ <?php echo $i; ?> +' ผัง');
		tableshow(1);
		function minishow(){
			let x = $('#select-pages').val();
			//alert(x);
			tableshow(x);
		}
		
		function tableshow(x){
			$('#first_page').removeClass('disabled');
			$('#last_page').removeClass('disabled');
			$('#back_page').removeClass('disabled');
			$('#next_page').removeClass('disabled');
			$('#back_page').attr('onclick','conpage("back")');
			$('#next_page').attr('onclick','conpage("next")');
			let table = "<?php echo $t-1; ?>";
			for(let i = 1 ; i <= table ; i++){
				$('#table_'+i).hide();
			}			
			if(x==1){
				$('#first_page').addClass('disabled');
				$('#back_page').addClass('disabled');
				$('#back_page').removeAttr('onclick');
			}else if(x==table){
				$('#last_page').addClass('disabled');
				$('#next_page').addClass('disabled');
				$('#next_page').removeAttr('onclick');
			}
			if(x==1 && x==table){
				$('#back_page').removeAttr('onclick');
				$('#next_page').removeAttr('onclick');
				$('#last_page').addClass('disabled');
				$('#next_page').addClass('disabled');
				
				$('#first_page').hide();
				$('#last_page').hide();
				$('#back_page').hide();
				$('#next_page').hide();
				$('#select-pages').hide();
			}
			$('#table_'+x).show();
			$('#select-page').val(x);
			$('#select-pages').val(x);			
			topFunction();
		}
		
		
		
		
		function conpage(x){
			let y = 0;
			if(x=='back'){
				y = -1;	
			}
			else if(x=='next'){
				y = 1;	
			}
			x = $('#select-pages').val();
			x = y+parseInt(x);			
			tableshow(x)
		}
		
		


		
	</script>
	