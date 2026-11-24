<?php 
include '../js/function_db.php';
session_name('tutorial');
session_start();
$date = date('Y-m-d');
$current = date('Y-m-d');
$current = date_create($current);
$park = ['ภาคเหนือ','ภาคตะวันออกเฉียงเหนือ','ภาคกลาง','ภาคตะวันออก','ภาคใต้'];



for($p=1;$p<=18;$p++){

$a1 = '';
$a2 = '';
$a3 = '';
$a4 = '';
$a5 = '';
$a6 = '';
$a7 = '';
$a8 = '';


$a1 = array();
$a2 = array();
$a3 = array();
$a4 = array();
$a5 = array();
$a6 = array();
$a7 = array();
$a8 = array();

$sp = 0;
$ud = 0;
$days = '';
$green = 0;
$yellow = 0;
$orange = 0;
$red = 0;
$lating = 0;
$external = 0;
//$sql = "SELECT * FROM tb_cityplan, ref_province, ref_cityplan_step_detail, ref_up_snd_step WHERE cp_province = pvn_id AND cp_csd = csd_seq AND csd_step_fst = sub_step AND csd_step_snd = sub_no AND pvn_park = '".$p."'";
$sql = "SELECT * FROM tb_cityplan, ref_province, ref_cityplan_step_detail, ref_up_snd_step WHERE cp_province = pvn_id AND cp_csd = csd_seq AND cp_step = sub_step AND cp_step_1 = sub_no AND pvn_keth = '".$p."'";
$result = selectSql($sql);
$all_data = count($result);
//echo '<br>'.$park[$p-1].' ('.$all_data.')<br>';
//echo $sql;

for($i=0;$i<$all_data;$i++){
$days = 0;	
	if($result[$i]['cp_step']>0){
		if($result[$i]['cp_step_1']>0){
			if($result[$i]['csd_start']!=''){
				$csd_start_ad = (substr($result[$i]['csd_start'],0,4)-543).substr($result[$i]['csd_start'],4);
				//echo $csd_start_ad.'<br>';
				
				
				if($result[$i]['cp_step']==6){
					if($result[$i]['cp_petition']==1){
						$ot1 = $result[$i]['sub_time2']*7;
						$ot2 = $result[$i]['sub_time2']*7*2;
					}else{
						$ot1 = $result[$i]['sub_time1']*7;
						$ot2 = $result[$i]['sub_time1']*7*2;
					}
					
				}else{
					$ot1 = $result[$i]['sub_ot1'];
					$ot2 = $result[$i]['sub_ot2'];
				}
				//echo 'cp : '.$result[$i]['cp_no'].' , step : '.$result[$i]['csd_step_fst'].' : '.$ot1."-".$ot2."<br>";
				
				
				$deadline = date_create($csd_start_ad);
				$deadline = date_add($deadline,date_interval_create_from_date_string(($ot1-1)." day"));
				if($result[$i]['csd_stop']=='' OR $result[$i]['csd_stop']==' ' OR $result[$i]['csd_stop']==null){
					$days = date_diff($deadline,$current);
				}else{
					$csd_stop_ad = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
					$csd_stop_ad = date_create($csd_stop_ad);
					$days = date_diff($deadline,$csd_stop_ad);
				}
				$days = $days->format("%R%a");
				$days = intval($days);
				//echo $days;
				
				if($days<=0){
					$green++;
					array_push($a2,$result[$i]['cp_no']);
				}elseif($days<=$ot1){
					$yellow++;
					array_push($a3,$result[$i]['cp_no']);
				}elseif($days<=$ot2){
					$orange++;
					array_push($a4,$result[$i]['cp_no']);
				}else{
					$red++;
					array_push($a5,$result[$i]['cp_no']);
				}
				
				//$d++;
			}else{
				$ud++;
				array_push($a6,$result[$i]['cp_no']);
			}
		}else{
			$sp++;
			array_push($a1,$result[$i]['cp_no']);
		}
	}else{
		$sp++;
		array_push($a1,$result[$i]['cp_no']);
	}
	if($result[$i]['csd_step_fst']==8){
		if($result[$i]['csd_step_snd']==2 OR $result[$i]['csd_step_snd']==4 OR $result[$i]['csd_step_snd']==5 OR $result[$i]['csd_step_snd']==6 OR $result[$i]['csd_step_snd']==8){
			$external++;
			array_push($a8,$result[$i]['cp_no']);
		}
	}
	if($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']>0){
		if($result[$i]['csd_stop_2']!='' AND $result[$i]['csd_stop_2']!=null){
			$csd_stop_ad = (substr($result[$i]['csd_stop_2'],0,4)-543).substr($result[$i]['csd_stop_2'],4);
			if($csd_stop_ad < $date){
				$lating++;
				array_push($a7,$result[$i]['cp_no']);
			}
		}
	}elseif($result[$i]['csd_step_fst']>0 AND $result[$i]['csd_step_snd']>0 AND $result[$i]['csd_step_trd']<1){
		if($result[$i]['csd_stop']!='' AND $result[$i]['csd_stop']!=null){
			$csd_stop_ad = (substr($result[$i]['csd_stop'],0,4)-543).substr($result[$i]['csd_stop'],4);
			if($csd_stop_ad < $date){
				$lating++;
				array_push($a7,$result[$i]['cp_no']);
			}
		}
	}else{
		
	}
}

$a1 = json_encode($a1);
$a2 = json_encode($a2);
$a3 = json_encode($a3);
$a4 = json_encode($a4);
$a5 = json_encode($a5);
$a6 = json_encode($a6);
$a7 = json_encode($a7);
$a8 = json_encode($a8);
?>
<script>
$(<?php echo "'#s1p".$p."'";?>).html('<?= $sp;?>')/*.attr('onclick','statusCityplan(1,1,<?= $a1;?>)')*/.css('cursor','pointer');
$(<?php echo "'#s2p".$p."'";?>).html('<?= $green;?>')/*.attr('onclick','statusCityplan(3,1,<?= $a2;?>)')*/.css('cursor','pointer');
$(<?php echo "'#s3p".$p."'";?>).html('<?= $yellow;?>')/*.attr('onclick','statusCityplan(4,1,<?= $a3;?>)')*/.css('cursor','pointer');
$(<?php echo "'#s4p".$p."'";?>).html('<?= $orange;?>')/*.attr('onclick','statusCityplan(5,1,<?= $a4;?>)')*/.css('cursor','pointer');
$(<?php echo "'#s5p".$p."'";?>).html('<?= $red;?>')/*.attr('onclick','statusCityplan(6,1,<?= $a5;?>)')*/.css('cursor','pointer');
$(<?php echo "'#s6p".$p."'";?>).html('<?= $ud;?>')/*.attr('onclick','statusCityplan(7,1,<?= $a6;?>)')*/.css('cursor','pointer');
$(<?php echo "'#s7p".$p."'";?>).html('<?= $lating;?>')/*.attr('onclick','statusCityplan(8,1,<?= $a7;?>)')*/.css('cursor','pointer');
$(<?php echo "'#s8p".$p."'";?>).html('<?= $external;?>')/*.attr('onclick','statusCityplan(9,1,<?= $a8;?>)')*/.css('cursor','pointer');
</script>
<?php } ?>
