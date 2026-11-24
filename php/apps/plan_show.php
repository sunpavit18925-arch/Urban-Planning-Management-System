<?php
	include '../js/function_db.php';
	session_name('tutorial');
    session_start();
	
	
	$plan = 'plan'.$_POST['id']; //tb_equipment > plam = (plan)+id tb_plan , tb_plan > plan_id (plan_name,plan_year,plan_start,plan_end)
	
	
	
	$sql = "SELECT * FROM tb_plan WHERE plan_id = '".$_POST['id']."'";
    $result = selectSql($sql);
    foreach ($result as $row){}


?>
    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center"><?php echo $row['plan_name'];?></h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center"><?php echo $row['plan_name'];?></h4>
	</div>
	
	<div>
		<div class="col-md-12 pull-right" style="text-align:right;"><button  class="btn btn-warning" onclick="planEqm(<?php echo $_POST['id']; ?>);">ย้อนกลับ</button></div>
	</div>
	<br>


<?php 
	$years = $row['plan_year'];
	$ystart = $row['plan_start'];
	$ystari = $row['plan_start']; //for row condition//
	$yend = $row['plan_end'];
	$ydift = $yend - $ystart;


?>

&nbsp;

<div style="overflow-x:auto;">
<table class="tablex table-bordered " style="font-size:12px">
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th class="centre" scope="col">ลำดับ</th>
      <th style="text-align:center"  scope="col">รายการระบบและอุปกรณ์</th>
      <th style="text-align:center"  scope="col">ราคาในการจัดซื้อ</th>
	  <th style="text-align:center"  scope="col">ปีที่จัดซื้อ</th>
	  <th style="text-align:center"  scope="col">ปีที่จัดซื้อทดแทน</th>
      <th style="text-align:center" scope="col">MA ต่อปี</th>
	  <?php for ($years; $ystart <= $yend ; $ystart++){ 
		echo' <th style="text-align:center"  scope="col">';
		echo $ystart;
		echo '</th>';
	  }?>      
	  <th style="text-align:center"  scope="col">หมายเหตุ</th>
    </tr>
  </thead>
  <tbody style="background-color:white" id="planshow_eqm">
    <?php
		
        $n = 0;
        $sql = "SELECT * FROM tb_equipment WHERE $plan = 1 ORDER BY eqm_id ASC ";
        $result = selectSql($sql);

        foreach ($result as $row){ 
		$ystar =$ystari;
		$n++;	
	
	?>
	 <tr class="trcolor">
      <th style="text-align:center;" scope="row"><?php echo $n; ?></th>
	  <td><?php echo $row['eqm_name']; ?></td>
	  <td style="text-align:center"><?php echo number_format($row['eqm_price']); ?></td>
	  <td style="text-align:center"><?php echo $row['eqm_buy']; ?></td>
	  <td style="text-align:center"><?php if($row['eqm_rebuy'] == 9999){ echo "-"; }else{ echo $row['eqm_rebuy']; }?></td>
	  <td style="text-align:center"><?php echo number_format($row['eqm_ma']); ?></td>
	<?php
	$cony = $row['eqm_rebuy'] - $ystar;
	$garant = ($row['eqm_buy'] + $row['eqm_guarantee'])- $ystar;
	$conb = $row['eqm_buy'] - $ystar;
	//echo $cony.'---'.$garant.'---'.$years.'---';
	if($years == 3){
		if ($row['eqm_buy'] > $ystar){
			if($conb>=4){
				echo '<td style="text-align:center;color:red" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
				
			}
			elseif($conb==3){
				echo '<td style="text-align:center;color:red" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($conb==2){
				echo '<td style="text-align:center;color:red" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($conb==1){
				echo '<td style="text-align:center;color:red" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;

			}
			$cony = $row['eqm_rebuy'] - $ystar;
			$garant = ($row['eqm_buy'] + $row['eqm_guarantee'])- $ystar;
		}
		if($row['eqm_buy'] <= $ystar){
			if($garant < 0){ // ในกรณีที่ไม่มีประกัน / ประกันหมดอายุ
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony <= 0 ){
					echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
					$ystar+=4;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
				
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0 ){
					echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
					$ystar+=3;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
				
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0 ){
					echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
					$ystar+=2;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
				
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0 ){
					echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
					$ystar+=1;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}		

			}
			 elseif($garant == 0 AND $row['eqm_buy'] <= $ystar){
				echo '<td style="text-align:center;color:#098C09" colspan="1"> อยู่ในประกัน  </td>';
				$ystar+=1;	

				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0){
					echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
					$ystar+=3;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
				
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0){
					echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
					$ystar+=2;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
				
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0){
					echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
					$ystar+=1;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
			}
			elseif($garant == 1 AND $row['eqm_buy'] <= $ystar){ 
				echo '<td style="text-align:center;color:#098C09" colspan="2"> อยู่ในประกัน  </td>';	
				$ystar+=2;
				
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0){
					echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
					$ystar+=2;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
				
				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0){
					echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
					$ystar+=1;
				}
				elseif($cony > 0 ){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}
			}
			elseif($garant == 2 AND $row['eqm_buy'] <= $ystar){
				echo '<td style="text-align:center;color:#098C09" colspan="3"> อยู่ในประกัน  </td>';
				$ystar+=3;

				$cony =$row['eqm_rebuy'] - $ystar;			
				if($cony == 0){
					echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
					$ystar+=1;
				}
				elseif($cony > 0){
					echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
					$ystar+=1;
				}			
			}
			elseif($garant >= 3 AND $row['eqm_buy'] <= $ystar ){
				echo '<td style="text-align:center;color:#098C09" colspan="4"> อยู่ในประกัน  </td>';	
				$ystar+=4;
			}
		}
	}
	elseif($years == 4){ 
	
		if($garant < 0){ // ในกรณีที่ไม่มีประกัน / ประกันหมดอายุ
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony <= 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}		

		}
		 elseif($garant == 0){
			echo '<td style="text-align:center;color:#098C09" colspan="1"> อยู่ในประกัน  </td>';
		    $ystar+=1;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}			
		}
		elseif($garant == 1){ 
			echo '<td style="text-align:center;color:#098C09" colspan="2"> อยู่ในประกัน  </td>';	
			$ystar+=2;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
		}
		elseif($garant == 2){
			echo '<td style="text-align:center;color:#098C09" colspan="3"> อยู่ในประกัน  </td>';
			$ystar+=3;

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}	
			
		
			
		}
		elseif($garant == 3 ){
			echo '<td style="text-align:center;color:#098C09" colspan="4"> อยู่ในประกัน  </td>';	
			$ystar+=4;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
				
		}
		
		elseif($garant >= 4){
			echo '<td style="text-align:center;color:#098C09" colspan="5"> อยู่ในประกัน  </td>';	
			$ystar+=5;
		}
	
	}
	elseif($years == 5){ 
		if($garant < 0){ // ในกรณีที่ไม่มีประกัน / ประกันหมดอายุ
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony <= 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="6"> จัดหาทดแทน </td>';
				$ystar+=6;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}		

		}
		elseif($garant == 0){
			echo '<td style="text-align:center;color:#098C09" colspan="1"> อยู่ในประกัน  </td>';
		    $ystar+=1;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		 elseif($garant == 1){
			echo '<td style="text-align:center;color:#098C09" colspan="2"> อยู่ในประกัน  </td>';
		    $ystar+=2;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 2){ 
			echo '<td style="text-align:center;color:#098C09" colspan="3"> อยู่ในประกัน  </td>';	
			$ystar+=3;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 3){
			echo '<td style="text-align:center;color:#098C09" colspan="4"> อยู่ในประกัน  </td>';
			$ystar+=4;

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}			
		}
		elseif($garant == 4){
			echo '<td style="text-align:center;color:#098C09" colspan="5"> อยู่ในประกัน  </td>';	
			$ystar+=5;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}	
		}		
		elseif($garant >= 5){
			echo '<td style="text-align:center;color:#098C09" colspan="6"> อยู่ในประกัน  </td>';	
			$ystar+=6;
		}	
	}
	elseif($years == 6){ 
	if($garant < 0){ // ในกรณีที่ไม่มีประกัน / ประกันหมดอายุ
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony <= 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="7"> จัดหาทดแทน </td>';
				$ystar+=7;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="6"> จัดหาทดแทน </td>';
				$ystar+=6;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}		

		}
		elseif($garant == 0){
			echo '<td style="text-align:center;color:#098C09" colspan="1"> อยู่ในประกัน  </td>';
		    $ystar+=1;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="6"> จัดหาทดแทน </td>';
				$ystar+=6;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 1){
			echo '<td style="text-align:center;color:#098C09" colspan="2"> อยู่ในประกัน  </td>';
		    $ystar+=2;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		 elseif($garant == 2){
			echo '<td style="text-align:center;color:#098C09" colspan="3"> อยู่ในประกัน  </td>';
		    $ystar+=3;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 3){ 
			echo '<td style="text-align:center;color:#098C09" colspan="4"> อยู่ในประกัน  </td>';	
			$ystar+=4;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 4){
			echo '<td style="text-align:center;color:#098C09" colspan="5"> อยู่ในประกัน  </td>';
			$ystar+=5;

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}			
		}
		elseif($garant == 5){
			echo '<td style="text-align:center;color:#098C09" colspan="6"> อยู่ในประกัน  </td>';	
			$ystar+=6;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}	
		}		
		elseif($garant >= 6){
			echo '<td style="text-align:center;color:#098C09" colspan="7"> อยู่ในประกัน  </td>';	
			$ystar+=7;
		}	
		
	}
	elseif($years == 7){ 
		if($garant < 0){ // ในกรณีที่ไม่มีประกัน / ประกันหมดอายุ
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony <= 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="8"> จัดหาทดแทน </td>';
				$ystar+=8;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="7"> จัดหาทดแทน </td>';
				$ystar+=7;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="6"> จัดหาทดแทน </td>';
				$ystar+=6;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}		

		}
		elseif($garant == 0){
			echo '<td style="text-align:center;color:#098C09" colspan="1"> อยู่ในประกัน  </td>';
		    $ystar+=1;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="7"> จัดหาทดแทน </td>';
				$ystar+=7;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="6"> จัดหาทดแทน </td>';
				$ystar+=6;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 1){
			echo '<td style="text-align:center;color:#098C09" colspan="2"> อยู่ในประกัน  </td>';
		    $ystar+=2;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="6"> จัดหาทดแทน </td>';
				$ystar+=6;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 2){
			echo '<td style="text-align:center;color:#098C09" colspan="3"> อยู่ในประกัน  </td>';
		    $ystar+=3;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
				$ystar+=5;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		 elseif($garant == 3){
			echo '<td style="text-align:center;color:#098C09" colspan="4"> อยู่ในประกัน  </td>';
		    $ystar+=4;	

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
				$ystar+=4;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 4){ 
			echo '<td style="text-align:center;color:#098C09" colspan="5"> อยู่ในประกัน  </td>';	
			$ystar+=5;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
				$ystar+=3;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
		}
		elseif($garant == 5){
			echo '<td style="text-align:center;color:#098C09" colspan="6"> อยู่ในประกัน  </td>';
			$ystar+=6;

			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
				$ystar+=2;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}			
		}
		elseif($garant == 6){
			echo '<td style="text-align:center;color:#098C09" colspan="7"> อยู่ในประกัน  </td>';	
			$ystar+=7;
			
			$cony =$row['eqm_rebuy'] - $ystar;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
				$ystar+=1;
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
				$ystar+=1;
			}	
		}		
		elseif($garant >= 7){
			echo '<td style="text-align:center;color:#098C09" colspan="8"> อยู่ในประกัน  </td>';	
			$ystar+=8;
		}
		
	}
	else{
		echo 'Error!!;';
	} 	
	?>  
	<td style="text-align:center">-</td>
		</tr> <?php } ?>
		<tr>
	  <?php ?>
		<td style="text-align:center" colspan="6">ราคาบำรุงรักษาต่อปี</td>
		<?php 
		$ystarj =$ystari;
		if($years == 3){ ?>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>


			
		<?php }
		elseif($years == 4){ ?>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		
		<?php }
		elseif($years == 5){ ?>
			<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>

		
		<?php }
		elseif($years == 6){ ?>
			<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<?php }
		elseif($years == 7){ ?>
			<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > $ystarj AND (eqm_buy+eqm_guarantee) < $ystarj AND $plan = 1";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			$ystarj+=1;
			?>
		</td>
		<?php } ?>		
		<td style="text-align:center">-</td>
	  </tr>
  </tbody>
 </table>
&nbsp;

<input type="hidden" value="<?php echo $years;?>">
<script>
	function plandata(){
	$.post('apps/plan_equipment(pro).php',{
        year : $().val(),
        id : id,        
        
        },function(data) {          
        // alert(data);
		 $('#planshow_eqm').load('apps/plan_show_data.php');

         
       });
	}





</script>