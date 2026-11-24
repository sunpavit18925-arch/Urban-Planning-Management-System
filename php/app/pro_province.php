<?php 
	include '../js/function_db.php';
	$sql = "SELECT pvn.pvn_id AS pvn_id , pvn.pvn_name AS pvn_name , pvn.pvn_affiliation AS pvn_affiliation ,COUNT(cp.cp_province) AS pvn_cityplan FROM ref_province pvn LEFT JOIN tb_cityplan cp ON pvn.pvn_id = cp.cp_province GROUP BY pvn.pvn_id";
	//echo $sql;
	$result = selectSql($sql);
	$data = count($result);
	$row = 20;
	//echo $data;
	if($data > 0){ ?>
		<div class="table-responsive" style="border-color:transparent;margin-top:8px"> <?php
			?>
			<table class="table table-bordereds printx" hidden>
				<thead>
					<tr>
						<th>ลำดับ</th>
						<th>จังหวัด</th>
						<th>ส่วนผังเมืองรวมที่</th>
						<th>จำนวนผังเมือง</th>
					</tr>
				</thead>
				<tbody>
				<?php $z = 0;
				foreach($result as $bow){
					$z++; ?>
					<tr>
						<td style="text-align:center"><?= $z; ?></td>
						<td style="text-align:center"><?= $bow['pvn_name']; ?></td>
						<td style="text-align:center"><?= $bow['pvn_affiliation']; ?></td>
						<td style="text-align:center"><?= $bow['pvn_cityplan'];?></td> 
					</tr>
				<?php }?>	
				</tbody>
			</table>
			<?php
			//$sux = 0; 
			for($t=1,$i=0 ; $t<=ceil($data/$row) ; $t++){ ?>
				<table id="table_<?php echo $t;?>" class="table table-bordered unprintable">
					<thead>
						<tr style='background-color: #295DBD;color:white'>
							<th style='text-align:center'> #</th>
							<th style='text-align:center'> จังหวัด </th>
							<th style='text-align:center'> ส่วนผังเมืองรวมที่ </th>
							<th style='text-align:center'> จำนวนผังเมือง </th>
							<th style='text-align:center'> จัดการ </th>
						</tr>
					</thead>
					<tbody> <?php						
						for($i,$arow = 1 ; $arow<=$row ; $arow++,$i++){ 
							//$sux = $sux + $result[$i]['COUNT(*)'];
							if($i == $data){break; } ?>
							<tr>
								<td style="text-align:center"><?php echo $i+1;?></td>
								<td style="text-align:center"><?php echo $result[$i]['pvn_name'];?></td>							
								<td style="text-align:center"><?php echo $result[$i]['pvn_affiliation'];?></td>
								<td style="text-align:center"><?php echo $result[$i]['pvn_cityplan'];?></td> 
								<td style="text-align:center">
									<span class="gear" onclick="pvnEdit(<?php echo  $result[$i]['pvn_id'];?>)"> <a id="gear">&nbsp;<i class="fa fa-gear" >  </i>&nbsp;</a></span>
									<span hidden class="trash" onclick="pvnDel(<?php echo $result[$i]['pvn_id'];?>)"> <a id="trash">&nbsp;<i class="fa fa-trash" ></i>&nbsp;</a></span>
								</td>
							</tr><?php
						} ?>
					</tbody>
				</table> 
			<?php } ?>
		</div>
		 <?php	
		pagin($row,$data);		
		//echo $sux;
	}
	else{ 
		?>
			<h1 style="font-family:Kanit;text-align:center">ไม่มีข้อมูล</h1>
		<?php
	}
?>
	
	<script>
		$('#pvn_value').html('<?php echo $data;?>');
	</script>
