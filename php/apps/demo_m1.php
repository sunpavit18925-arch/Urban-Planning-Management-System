<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">แผนการบำรุงรักษา 5 ปี</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">แผนการบำรุงรักษา 5 ปี</h4>
	</div>	
	  <?php $sql = "SELECT COUNT(eqm_id) FROM tb_equipment ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>






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
      <th style="text-align:center"  scope="col">2565</th>
      <th style="text-align:center"  scope="col">2566</th>
      <th style="text-align:center"  scope="col">2567</th>
	  <th style="text-align:center"  scope="col">2568</th>
      <th style="text-align:center"  scope="col">2569</th>
      <th style="text-align:center"  scope="col">2570</th>
	  <th style="text-align:center"  scope="col">หมายเหตุ</th>
    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $n = 0;
        $sql = "SELECT * FROM tb_equipment ORDER BY eqm_id ASC ";
        $result = selectSql($sql);

        foreach ($result as $row){ 
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
		$year = 2565;
		$cony = $row['eqm_rebuy'] - $year;
		$garant = ($row['eqm_buy'] + $row['eqm_guarantee'])- $year;
		
		  if($garant < 0){	
			//2565//
			$year = 2565;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="6"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2566//
			$year = 2566;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2567//
			$year = 2567;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2568//
			$year = 2568;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2569//
			$year = 2569;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2570//
			$year = 2570;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
		  }
		  elseif($garant == 0){
			echo '<td style="text-align:center;color:#098C09" colspan="1"> อยู่ในประกัน  </td>';

			//2566//
			$year = 2566;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="5"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2567//
			$year = 2567;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2568//
			$year = 2568;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2569//
			$year = 2569;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2570//
			$year = 2570;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
		  }
		  elseif($garant == 1){
			echo '<td style="text-align:center;color:#098C09" colspan="2"> อยู่ในประกัน  </td>';

			//2567//
			$year = 2567;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="4"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2568//
			$year = 2568;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2569//
			$year = 2569;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2570//
			$year = 2570;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
		  }
		  elseif($garant == 2){
			echo '<td style="text-align:center;color:#098C09" colspan="3"> อยู่ในประกัน  </td>';

			//2568//
			$year = 2568;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="3"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2569//
			$year = 2569;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2570//
			$year = 2570;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
		  }
		  elseif($garant == 3){
			echo '<td style="text-align:center;color:#098C09" colspan="4"> อยู่ในประกัน  </td>';

			//2569//
			$year = 2569;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="2"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
			
			//2570//
			$year = 2570;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
		  }
		  
		  elseif($garant == 4){
			echo '<td style="text-align:center;color:#098C09" colspan="5"> อยู่ในประกัน  </td>';	
			
		  
			//2570//
			$year = 2750;
			$cony =$row['eqm_rebuy'] - $year;			
			if($cony == 0){
				echo '<td style="text-align:center;color:#8C5A09" colspan="1"> จัดหาทดแทน </td>';
			}
			elseif($cony > 0){
				echo '<td style="text-align:center" >',number_format($row['eqm_ma']),'</td>';
			}
		  }
			
		  elseif($garant >= 5){
			echo '<td style="text-align:center;color:#098C09" colspan="6"> อยู่ในประกัน  </td>';	
		  }
		  
	  
	  
	  
	 ?>
	 <td style="text-align:center" > - </td>

      </tr>
  <?php } ?>
  	  <tr>
	  <?php ?>
		<td style="text-align:center" colspan="6">ราคาบำรุงรักษาต่อปี</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > 2565 AND (eqm_buy+eqm_guarantee) < 2565";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > 2566 AND (eqm_buy+eqm_guarantee) < 2566";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > 2567 AND (eqm_buy+eqm_guarantee) < 2567";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > 2568 AND (eqm_buy+eqm_guarantee) < 2568";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > 2569 AND (eqm_buy+eqm_guarantee) < 2569";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			?>
		</td>
		<td style="text-align:center">
			<?php
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE eqm_rebuy > 2570 AND (eqm_buy+eqm_guarantee) < 2570";
			$result = selectSql($sql);
			echo number_format($result[0]['SUM(eqm_ma)']);
			?>
		</td>
		<td style="text-align:center">-</td>
	  </tr>
  </tbody>
  </table>
 
