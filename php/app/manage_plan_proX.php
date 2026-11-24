<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();

$step1 = array();
$time1 = array();
$pstart1 = array();
$pstop1 = array();


if($_POST['event']=='setPlan'){
	$sql = "SELECT * FROM ref_up_fst_step WHERE step_id>0";
	$result = selectSql($sql);
	$all_step = count($result);
	$starter = (substr($_POST['starter'],0,4)-543).substr($_POST['starter'],4);
	$start_plan = date_create($starter);
	$stop_plan = date_create($starter);
	//echo date_format($start_plan,'Y-m-d');
	//echo '<br>';
	?>
	<h3 style="text-align:left;font-family:Kanit">แผนดำเนินการ (กรณีมีคำร้อง) </h3>
	<table id="plan_1" class="table table-bordereds" style="margin:16px 0 0">
	<thead>
	<tr style="font-weight:600">
		<th style="text-align:center;background-color: #295DBD!important;color: white!important;vertical-align: middle;">ขั้นตอน</th>
		<th style="text-align:center;background-color: #295DBD!important;color: white!important;vertical-align: middle;">ระยะเวลา</th>
		<th style="text-align:center;background-color: #295DBD!important;color: white!important;vertical-align: middle;">เริ่ม</th>
		<th style="text-align:center;background-color: #295DBD!important;color: white!important;vertical-align: middle;">สิ้นสุด</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0;$i<$all_step;$i++){
		echo "<tr style='background:#b0b0b0!important;font-weight:600'>";
		if($i>0){	
			$start_plan = $stop_plan;
			$start_plan = date_add($start_plan,date_interval_create_from_date_string('1 days'));
			$start = (date_format($start_plan,'Y')+543).date_format($start_plan,'-m-d');
		}else{
			$start = (date_format($start_plan,'Y')+543).date_format($start_plan,'-m-d');
		}
			
		if($result[$i]['step_id']==6){
			if(FALSE){
				$result[$i]['step_ot1'] = $result[$i]['step_time1']*7;
			}else{
				$result[$i]['step_ot1'] = $result[$i]['step_time2']*7;
			}
		}
		$stop_plan = date_add($stop_plan,date_interval_create_from_date_string(($result[$i]['step_ot1']-1).' days'));
		//echo date_format($stop_plan,'Y-m-d');
		//echo "ขั้นตอนหลักที่ : ".$result[$i]['step_id']."<br>";
		//echo 'เริ่ม : '.$start.' , สิ้นสุด : '.(date_format($stop_plan,'Y')+543).date_format($stop_plan,'-m-d').'<br>';
		echo "<td style='background:#b0b0b0!important;'>ขั้นตอนหลักที่ : ".$result[$i]['step_id']."</td>";
		echo "<td style='text-align:center;background:#b0b0b0!important;'>".($result[$i]['step_ot1']/7)."</td>";
		echo "<td style='text-align:center;background:#b0b0b0!important;'>".$start."</td>";
		echo "<td style='text-align:center;background:#b0b0b0!important;'>".(date_format($stop_plan,'Y')+543).date_format($stop_plan,'-m-d')."</td>";
		echo "</tr>";	
		
		array_push($step1,$result[$i]['step_id']);
		array_push($time1,($result[$i]['step_ot1']/7));
		array_push($pstart1,$start);
		array_push($pstop1,(date_format($stop_plan,'Y')+543).date_format($stop_plan,'-m-d'));
		if($result[$i]['step_id']==6){
			if(FALSE){
				$sql = "SELECT * , sub_time1*7 as ot1 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['step_id']."' AND sub_no != 0 AND (sub_no = 4 OR sub_no = 5)";
			}else{
				$sql = "SELECT * , sub_time2*7 as ot1 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['step_id']."' AND sub_no != 0";
			}
		}
		else{
			$sql = "SELECT * , sub_ot1 as ot1 FROM ref_up_snd_step WHERE sub_step = '".$result[$i]['step_id']."' AND sub_no != 0";
		}
		
		$result_snd = selectSql($sql);
		$count_snd = count($result_snd);
		//echo 'all sub : '.$count_snd.'<br>';
		for($j=0;$j<$count_snd;$j++){
			$ready = 'FALSE';
			
			if($optx == $result_snd[$j]['sub_operate']){
				$ready = 'TRUE';
			}

			if($j==0 OR $ready=='TRUE'){
				if($j==0){
					$sub_start = date_create((substr($start,0,4)-543).substr($start,4));
					$sub_end = date_create((substr($start,0,4)-543).substr($start,4));
				}
				else{
					$sub_start = date_format($sub_start,'Y-m-d');
					$sub_start = date_create($sub_start);
					$sub_end = date_format($sub_start,'Y-m-d');
					$sub_end = date_create($sub_end);
					

				}
			}
			else{
				$sub_start = date_format($sub_end,'Y-m-d');
				$sub_start = date_create($sub_start);
				$sub_start = date_add($sub_start,date_interval_create_from_date_string('1 days'));
				
				$sub_end = date_create(date_format($sub_start,'Y-m-d'));
			}
			$sub_end = date_add($sub_end,date_interval_create_from_date_string(($result_snd[$j]['ot1']-1).' days'));
			$show_sub_start = (date_format($sub_start,'Y')+543).date_format($sub_start,'-m-d');
			$show_sub_end = (date_format($sub_end,'Y')+543).date_format($sub_end,'-m-d');
			echo '<tr>';
			echo '<td style="background: #e8e8e8!important;">ขั้นตอนย่อย 1  : '.$result[$i]['step_id'].'.'.$result_snd[$j]['sub_no'].'</td>';
			echo "<td style='text-align:center;background: #e8e8e8!important;'>".($result_snd[$j]['ot1']/7)."</td>";
			echo '<td style="text-align:center;background: #e8e8e8!important;">'.$show_sub_start.'</td>';
			echo '<td style="text-align:center;background: #e8e8e8!important;">'.$show_sub_end.'</td>';
			echo '</tr>';
			
			array_push($step1,($result[$i]['step_id'].'.'.$result_snd[$j]['sub_no']));
			array_push($time1,($result_snd[$j]['ot1']/7));
			array_push($pstart1,$show_sub_start);
			array_push($pstop1,$show_sub_end);
			/*
			$sql = "SELECT trd_fst, trd_snd, trd_step, trd_name, trd_ot1 as ot FROM ref_up_trd_step WHERE trd_fst = '".$result[$i]['step_id']."' AND trd_snd = '".$result_snd[$j]['sub_no']."'";
			$result_trd  = selectSql($sql);
			$trd_start = (substr($show_sub_start,0,4)-543).substr($show_sub_start,4,3).substr($show_sub_start,7);
			$trd_stop = (substr($show_sub_start,0,4)-543).substr($show_sub_start,4,3).substr($show_sub_start,7);
			$trd_start_date = date_create($trd_start);
			$trd_stop_date = date_create($trd_stop);
			$all_trd = count($result_trd);
			if($all_trd > 0){
				for($trd=0;$trd < $all_trd ;$trd++){
					$trd_stop_date = date_add($trd_stop_date,date_interval_create_from_date_string(($result_trd[$trd]['ot']-1).' days'));
					$trd_stop = (date_format($trd_stop_date,'Y')+543).date_format($trd_stop_date,'-m-d');
					
					echo '<tr>';
					echo '<td>ขั้นตอนย่อย 2  : '.$result_trd[$trd]['trd_fst'].'.'.$result_trd[$trd]['trd_snd'].'.'.$result_trd[$trd]['trd_step'].' '.$result_trd[$trd]['trd_name'].'</td>';
					echo '<td style="text-align:center">'.($result_trd[$trd]['ot']/7).'</td>';
					echo '<td style="text-align:center">'.$trd_start.'</td>';
					echo '<td style="text-align:center">'.$trd_stop.'</td>';
					echo '</tr>';
					$trd_stop_date =  date_add($trd_stop_date,date_interval_create_from_date_string('1 days'));
					$trd_start = (date_format($trd_stop_date,'Y')+543).date_format($trd_stop_date,'-m-d');
				}
			}*/
			
			$optx = $result_snd[$j]['sub_operate'];
		}
		//echo "<hr>";

	}
	?>
		</tbody>
	</table>

	
	
	
	
	

	
	<?php 
	$plan = array('step' => $step1, 'time' => $time1, 'start' => $pstart1 , 'stop' => $pstop1);
	$plan = json_encode($plan);
	?>
	<textarea style="display:none" id="ta_plan_2"><?= $plan;?></textarea>		
	<?php
}




//print_r($plan['step']);




?>
