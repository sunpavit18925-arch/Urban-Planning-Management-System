<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();

if($_POST['event']=='set_data'){ 

	if($_POST['set_as_default']=='true'){
	
		
		setcookie('origin',$_POST['data_post'],time() + (86400 * 365) , "/");
	}else{
		unset($_SESSION['origin']);
		setcookie('origin','',-1 , "/");
		unset($_COOKIE['origin']);
	}


?>
	<style>
	@media screen and (max-width:766px){
		.melect{
			max-width:calc(100% - 44px)
		}
	}
	@media screen and (min-width:767px){
		.melect{
			max-width:calc(100% - 88px)
		}
	}
	</style>

	<div class="row unprintable" style="margin-bottom:8px">
		<div class="col-xs-12 col-sm-6 col-md-6" style="margin:4px 0px">
			<select id="data_type" class="form-control" onchange="generate_data()">
				<option value="all">แสดงผังทั้งหมด</option>
				<option value="new">ผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่</option>
				<option value="not">ผังเมืองรวมเมือง/ชุมชนประกาศใช้บังคับ</option>
				<option value="cpe">ผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ EEC</option>
				<option value="eva">ประเมินผลผัง</option>
				<option value="edi">ปรับปรุงผัง</option>
			</select>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6" style="display: flex;flex-direction: row;justify-content: space-between;margin:4px 0px">
			<select id="data_order" class="form-control melect" onchange="generate_data()">
				<option value="pvn_asc ASC">เรียงข้อมูลตามชื่อจังหวัด (ก - ฮ)</option>
				<option value="pvn_asc DESC">เรียงข้อมูลตามชื่อจังหวัด (ฮ - ก)</option>
				<option value="cp_asc ASC , cp_name ASC">เรียงข้อมูลตามชื่อผังเมือง (ก -  ฮ)</option>
				<option value="cp_asc DESC , cp_name ASC">เรียงข้อมูลตามชื่อผังเมือง (ฮ - ก)</option>
				<option value="cp_consider DESC">เรียงข้อมูลเหตุผลพิจารณาดำเนินการ </option>
			</select>
			<button id="btn_export" class="btn btn-success" disabled><i class="fa fa-download"><span class="hidden-xs" style="font-family:Kanit"> Export</span></i></button>
		</div>
	</div>
	<div id="showresult"></div>
	<script>
		generate_data()
		function generate_data(){
			$('#data_type , #data_order').removeAttr('onchange').attr('disabled',true);
			$('#btn_export').attr('disabled',true);
			$('#showresult').html('<img src="img/loading3.gif" class="center">');
			//$('#data_order').ttr('onchange');
			$.ajax({
				url : 'app/export_page_pro.php',
				async : true,
				type : 'POST',
				data : {
					event : 'generate_data',
					type : $('#data_type').val(),
					order : $('#data_order').val(),
					origin : '<?=$_POST["data_post"]?>'
				},
				success :function(data){
					$('#showresult').html(data)
				},
				error : function(xhr,status,data){
					alert(status);
				}
			});
		}
	</script>
<?php 
}
elseif($_POST['event']=='generate_data'){
	$current = date();
	$current = date_create($current);
	
	$data_date = date('d').'-'.date('m').'-'.(date('Y')+543);
	//echo $data_date;
	$title = [
		'all'=>'ข้อมูลผังเมืองทั้งหมด',
		'new'=>'ข้อมูลผังเมืองรวมเมือง/ชุมชนเปิดพื้นที่ใหม่',
		'not'=>'ข้อมูลผังเมืองรวมเมือง/ชุมชนประกาศใช้บังคับ',
		'cpe'=>'ข้อมูลผังเมืองรวมเมือง/ชุมชนในเขตพื้นที่ EEC',
		'eva'=>'ข้อมูลประเมินผลผัง',
		'edi'=>'ข้อมูลปรับปรุงผัง'
		
	];
	$filename = [
		'all'=>'ข้อมูลผังเมืองทั้งหมด',
		'new'=>'ข้อมูลผังเมืองรวมเมือง-ชุมชนเปิดพื้นที่ใหม่',
		'not'=>'ข้อมูลผังเมืองรวมเมือง-ชุมชนประกาศใช้บังคับ',
		'cpe'=>'ข้อมูลผังเมืองรวมเมือง-ชุมชนในเขตพื้นที่ EEC',
		'eva'=>'ข้อมูลประเมินผลผัง',
		'edi'=>'ข้อมูลปรับปรุงผัง'
		
	];
	$x = array();
	$x = explode(',',$_POST['origin']);
	$dc = [
		'cp_id'=>'รหัสผังเมือง',
		'cp_name'=>'ชื่อผังเมือง',
		'pvn_name'=>'จังหวัด',
		'status_name'=>'สถานะ',
		'stage_name'=>'ขั้นตอน',
		'eff_name'=>'มีผลบังคับใช้',
		'csd_step_fst'=>'ขั้นตอนหลัก',
		'csd_step_snd'=>'ขั้นตอนย่อย 1',
		'csd_step_trd'=>'ขั้นตอนย่อย 2'
	];
	for($i=0;$i<count($x);$i++){
		if($i==0){
			if($x[0]=='csd_step_fst' OR $x[0]=='csd_step_snd' OR $x[0]=='csd_step_trd'){
				$select = 'true';
			}else{
				$select = $x[0];
				//$select = $x[0].' as dc_1';
			}
		}else{
			if($x[$i]=='csd_step_fst' OR $x[$i]=='csd_step_snd' OR $x[$i]=='csd_step_trd'){}else{
				$select = $select.' , '.$x[$i];
				//$select = $select.' , '.$x[$i].' as dc_'.($i+1);
			}
		}
	}

	$from = " tb_cityplan , ref_province , ref_status , ref_stage , ref_effective , ref_cityplan_step_detail ";
	$join = " cp_province = pvn_id AND cp_status = status_id AND cp_stage = stage_id AND cp_effective = eff_id AND cp_csd = csd_seq ";
	$order = $_POST['order'];
	$order = "cp_no ASC";
	if($_POST['type']=='all'){
		$sql = "SELECT ".$select.",csd_step_fst, csd_step_snd, csd_step_trd, csd_start, csd_stop, csd_start_2, csd_stop_2 ,cp_consider FROM ".$from." WHERE ".$join." ORDER BY ".$order;
	}
	else{
		if($_POST['type']=="new"){
			$condition = " cp_status = 1 AND cp_stage = 1 AND cp_eec != 'Y' ";
		}
		elseif($_POST['type']=="not"){
			$condition = " cp_status =2 AND cp_eec != 'Y' ";
		}
		elseif($_POST['type']=="cpe"){
			$condition = " cp_status = 2 AND cp_stage = 3 AND cp_eec != 'Y' ";
		}
		elseif($_POST['type']=="eva"){
			$condition = " cp_status = 2 AND (cp_stage = 2 OR cp_stage = 4) AND cp_eec != 'Y' ";
		}
		elseif($_POST['type']=="edi"){
			$condition = " cp_status = 2 AND cp_stage = 3 AND cp_eec != 'Y' ";
		}
		$sql = "SELECT ".$select.",csd_step_fst, csd_step_snd, csd_step_trd, csd_start, csd_stop, csd_start_2, csd_stop_2 ,cp_consider FROM ".$from." WHERE ".$join." AND ".$condition." ORDER BY ".$order;
	}
	//echo $sql;
	$result = selectSql($sql);
	?>
	
	<div>
		<h4 style="text-align:left;font-family:Kanit;padding-bottom: 1px;"><text class="pull-left"><?=$title[$_POST['type']]?></text><text class="pull-right">จำนวน : <?=count($result)?> ผัง</text></h4>
	</div>
	<div id="Exportable"class="table table-responsive" style="margin-top:24px">
		<table id="MT"class="table table-bordered">
			<thead>
				<tr style="background:transparent!important">
					<td style="text-align:center;background:#295DBD;color:white">ลำดับ</td>
					<?php for($td=0; $td<count($x);$td++){?>
					<td style="text-align:center;background:#295DBD;color:white"><?=$dc[$x[$td]]?></td>
					<?php } ?>
					<td style="text-align:center;background:#295DBD;color:white">สถานะ(การดำเนินการ)</td>
				</tr>
			</thead>
			<tbody>
			<?php 
			$data_row = 1;
			foreach($result as $row){  
				$step = '-';
				$step_fst = '-';
				$step_snd = '-';
				$step_trd = '-';
			?>
				<tr style="background:transparent">
					<td style="text-align:center"><?=$data_row;?></td>
					<?php 
						if($row['csd_step_fst']>0){
							if($row['csd_step_snd']>0 AND $row['cp_consider']<1){
								if(strlen($row['csd_start'])==10){
									if($row['csd_step_fst']!=6){
										$sql_step = "SELECT sub_time as time, sub_ot1 as ot , sub_ot2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$row['csd_step_fst']."' AND sub_no = '".$row['csd_step_snd']."'";
									}
									else{
										if($row['csd_step_fst']==6){
											if($row['csd_petition']!=1){
												$sql_step = "SELECT sub_time1 as time , sub_time1*7 as ot , sub_time1*7*2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$row['csd_step_fst']."' AND sub_no = '".$row['csd_step_snd']."'";
											}
											else{
												$sql_step = "SELECT sub_time2 as time , sub_time2*7 as ot , sub_time2*7*2 as ot2 FROM ref_up_snd_step WHERE sub_step = '".$row['csd_step_fst']."' AND sub_no = '".$row['csd_step_snd']."'";
											}
										}
									}
									$result_step = selectSql($sql_step);
									
									$date0 = (substr($row['csd_start'],0,4)-543).substr($row['csd_start'],4);
									$date1=date_create($date0);
									$deadline = date_add($date1,date_interval_create_from_date_string(($result_step[0]['ot']-1)." day"));
									if($row['csd_stop']=='' OR $row['csd_stop']==' ' OR $row['csd_stop']==null){
										$diff = date_diff($deadline,$current);
									}
									else{
										$csd_stop = (substr($row['csd_stop'],0,4)-543).substr($row['csd_stop'],4);
										$csd_stop = date_create($csd_stop);
										$diff = date_diff($deadline,$csd_stop);
									}
									$day = $diff->format("%R%a");
									$day = intval($day);
																		
									if($day <= 0){
										$status = "มีความก้าวหน้า";
										$style1 = "background:#2D882D!important ;color:white!important";
									}
									elseif($day <= $result_step[0]['ot']){
										$status = "ล่าช้าเล็กน้อย";
										$style1 = "background:#FFE841!important ;color:black!important";
									}
									elseif($day <= $result_step[0]['ot2']){
										$status = "ล่าช้าปานกลาง";
										$style1 = "background:orange!important;color:black!important";
									}
									else{
										$status = "ล่าช้ามาก";
										$style1 = "background:red!important;color:white!important";
									}
									
									
									if(strlen($row['csd_stop'])==10){
										if($csd_stop < $current){
											$status = '<img src="./img/alert.png"> '. $status;
											$status = 'ไม่มีการดำเนินการในขั้นตอนถัดไป ';
										}
									}
									
								}
								else{
									$status = 'ไม่ระบุวันที่';
								}
							}
							else{
								$status = "ไม่มีการดำเนินการ";
							}
						}
						else{
							$status = "ไม่มีการดำเนินการ";
						}
						
						
						if($row['csd_step_trd']>0 AND $row['cp_consider']<1){
							if(strlen($row['csd_start_2'])==10){
								$sql_step_2 = "SELECT trd_time as time , trd_ot1 as ot , trd_ot2 as ot2 FROM ref_up_trd_step WHERE trd_fst = '".$row['csd_step_fst']."' AND trd_snd = '".$row['csd_step_snd']."' AND trd_step = '".$row['csd_step_trd']."'";
								$result_step_2 = selectSql($sql_step_2);
								
								
								
								$deadline2 = dead_line($row['csd_start_2'],$result_step_2[0]['ot'],'th');
								
							
								if($row['csd_stop_2']=='' OR $row['csd_stop_2']==' ' OR $row['csd_stop_2']==NULL){
									$diff2 = date_diff($deadline2,$current);
								}else{
									$csd_stop_2 = (substr($row['csd_stop_2'],0,4)-543).substr($row['csd_stop_2'],4);
									$csd_stop_2 = date_create($csd_stop_2);
									$diff2 = date_diff($deadline2,$csd_stop_2);
								}	
								
								
								$day2 = $diff2->format("%R%a");
								$day2 = intval($day2);
								if($day2 <= 0){
									$status = "มีความก้าวหน้า";
									$style2 = "background:#2D882D!important ;color:white!important";
								}
								elseif($day2 <= $result_step_2[0]['ot']){
									$status = "ล่าช้าเล็กน้อย";
									$style2 = "background:#FFE841!important ;color:black!important";
								}
								elseif($day2 <= $result_step_2[0]['ot2']){
									$status = "ล่าช้าปานกลาง";
									$style2 = "background:orange!important;color:black!important";
								}
								else{
									$status = "ล่าช้ามาก";
									$style2 = "background:red!important;color:white!important";
								}
								
							}
							else{
								$style2 = "";
								$status = "ไม่ระบุวันที่";
							}
						}
						
						if($row['csd_step_fst']>0){
							if($row['csd_step_snd']>0){
								if($row['csd_step_trd']>0){
									$step = 'trd';
									$step_fst = $row['csd_step_fst'];
									$step_snd = $row['csd_step_fst'].'.'.$row['csd_step_snd'];
									$step_trd = $row['csd_step_fst'].'.'.$row['csd_step_snd'].'.'.$row['csd_step_trd'];
								}else{
									$step = 'snd';
									$step_fst = $row['csd_step_fst'];
									$step_snd = $row['csd_step_fst'].'.'.$row['csd_step_snd'];
								}
							}else{
								$step = 'fst';
								$step_fst = $row['csd_step_fst'];
							}
						}else{
							$step = '-';
							$step_fst = '-';
							
						}
						
						for($td1=0; $td1<count($x);$td1++){
							if($x[$td1]=='csd_step_fst' OR $x[$td1]=='csd_step_snd' OR $x[$td1]=='csd_step_trd'){
								if($row[$x[$td1]]<1){
								  $row[$x[$td1]] = '-';
								}
							}
							if($x[$td1]=='csd_step_fst'){?><td style="text-align:center;border:solid black 1px"><?=$row['csd_step_fst']?></td><?php }
							elseif($x[$td1]=='csd_step_snd'){?><td style="text-align:center;border:solid black 1px;<?=$style1?>"><?=$step_snd?></td><?php }
							elseif($x[$td1]=='csd_step_trd'){?><td style="text-align:center;border:solid black 1px;<?=$style2?>"><?=$step_trd?></td><?php }
							else{?><td style="text-align:center;"><?=$row[$x[$td1]]?></td><?php }
						} 
					?>
					<td style="text-align:center;"><?=$status;?></td>
				</tr>
			<?php 
				
				$data_row++;
				$sql_step = '-';
				
				$result_step = '-';
				$result_step_2 = '-';
				
				$status = '';
				
				$day = '';
				$day2 = '';
				
				$date0 = '';
				$date1 = '';
				
				$deadline = '';
				$deadline2 = '';
				
				$diff = '';
				$diff2 = '';
				
				$csd_stop = '';
				$csd_stop2 = '';
				
				$style1 = '';
				$style2 = '';
			} ?>
			</tbody>
		</table>
	</div>
	<script src="js/jquery.table2excel.js"></script>

	<script>
		$('#data_type , #data_order').removeAttr('disabled').attr('onchange','generate_data()');
		$('#btn_export').removeAttr('disabled').attr('onclick','exportable()');
		//$('#btn_export').attr('onclick','exportable("xlsx")');
		
		/*
		function exportable(type, fn, dl) {
		   var elt = document.getElementById('Exportable');
		   var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
		   return dl ?
			 XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
			 XLSX.writeFile(wb, fn || ('<?=$filename[$_POST["type"]]?>(<?=$data_date?>).' + (type || 'xlsx')));
		}
		*/
		
		/*
		 $("#btn_export").click(function(){
			$("#MT").table2excel({	
				exclude:".noExl",
				name:"Worksheet Name",
				filename:"<?=$filename[$_POST['type']]?>(<?=$data_date?>)",//do not include extension
				fileext:".xls", // file extension
				preserveColors:true

			});
		});
		*/
		
		function exportable(){
			$("#MT").table2excel({	
				exclude:".noExl",
				name:"Worksheet Name",
				filename:"<?=$filename[$_POST['type']]?>(<?=$data_date?>)",//do not include extension
				fileext:".xls", // file extension
				preserveColors:true,
				exclude_img:false,
				exclude_links:true,
				exclude_inputs:false
			});
		}
		

		
	</script>
	<?php 
} 
?>