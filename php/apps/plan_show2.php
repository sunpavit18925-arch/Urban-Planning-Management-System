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
	
	<div class="col-12">
		<div class="pull-right" style="text-align:center;"><button  class="btn btn-warning" onclick="planEqm(<?php echo $_POST['id']; ?>);">ย้อนกลับ</button></div>
		<div class="pull-left" style="text-align:center;"><button class="btn btn-primary" onclick="ExportToExcel('xlsx');">Export</button></div>
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
<table id="plantable" class="tablex table-bordered " style="font-size:12px">
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
	$ystar = $ystari;
	$cony = $row['eqm_rebuy'] - $ystar;
	$garant = ($row['eqm_buy'] + $row['eqm_guarantee'])- $ystar;
	$conb = $row['eqm_buy'] - $ystar;
	
	
	for($ycol = $years ; $ycol >= 0 ; $ycol--){
		if($conb>0){
			$pcol = $conb;
			if($pcol>$years){
				$pcol=$years+1;
			}
			?><td style="text-align:center;color:#8C5A09" colspan="<?php echo $pcol;?>"> จัดหาทดแทน </td><?php
			$ycol++;
			$ycol-=$conb;
			$conb-=$pcol;
			$cony-=$pcol;
			$ystar+=$pcol;
			$garant = ($row['eqm_buy'] + $row['eqm_guarantee'])- $ystar;
		}
		elseif($garant >=0 AND $row['eqm_guarantee'] > 0){
			$pcol = $garant+1;
			if($pcol>$ycol){
				$pcol=$ycol+1;
			}
			?><td style="text-align:center;color:#098C09" colspan="<?php echo $pcol;  ?>">อยู่ในประกัน </td><?php
			$ycol++;
			$ycol-=$pcol;
			$garant-=$pcol;
			$ystar+=$pcol;
			$cony-=$pcol;
			
		}
		elseif($cony ==0 OR $cony < 0){
			?><td style="text-align:center;color:#8C5A09" colspan="<?php echo $ycol+1;?>"> จัดหาทดแทน </td><?php
			$ycol-=$ycol;
		}
		else{
			?><td style="text-align:center;color;red"><?php echo number_format($row['eqm_ma']);?></td><?php
			$cony--;
		}
	}
	
 	
	?>  
	<td style="text-align:center"><?php echo $row['eqm_guarantee']; ?></td>
		</tr> <?php } ?>
		<tr>
	  <?php ?>
		<td style="text-align:center" colspan="6">ราคาบำรุงรักษาต่อปี</td>
	  <?php
	    $ys=10;
		for($ystartj = $ystari; $ystartj <= $yend ; $ys++){
			$sql = "SELECT SUM(eqm_ma) FROM tb_equipment WHERE $plan = 1 AND eqm_rebuy > $ystartj  AND eqm_buy <= $ystartj AND ((eqm_buy + eqm_guarantee - $ystartj) < 0 OR eqm_guarantee <= 0) ";
			$result = selectSql($sql);
			echo '<td style="text-align:center" >',number_format($result[0]['SUM(eqm_ma)']),'</td>';
			$ystartj++;
		}
		?>
			
		<td style="text-align:center">-</td>
	  </tr>
	  <tr>
	 <td style="text-align:center" colspan="6">-</td>
	 <td style="text-align:center">=SUM(G2:G21)</td>
	  </tr>
  </tbody>
 </table>
&nbsp;

<input type="hidden" value="<?php echo $years;?>">
<script type="text/javascript" src="js/export.js"></script>
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




	function ExportToExcel(type, fn, dl) {
		   var elt = document.getElementById('plantable');
		   var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
		   return dl ?
			 XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
			 XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
		}
</script>