<style> 

	
</style>
<script>

</script>

<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];

    ?>

    <div class="col-12">
		<h2 class="hidden-xs hidden-sm GHfont" style="font-family: Kanit;text-align:center">โครงการ</h2>
		<h4 class="hidden-md hidden-lg GHfontmini" style="font-family: Kanit;text-align:center">โครงการ</h4>
	</div>	
	  <?php $sql = "SELECT COUNT(pro_id) FROM tb_project ";
        $result = selectSql($sql);
        foreach ($result as $row){} ?>
    <!--<div class="col-md-6" style="font-family: Kanit; text-align:left;"><h5 style="font-family: Kanit;">จำนวนรายงาน : <?php echo $row['COUNT(pro_id)'];?></h5></div>-->

	<input id="showpage" hidden>

&nbsp;


<div class="col-xs-12" hidden style="overflow-x:auto;">
<table id="tablerce2" class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">
	  <td style="text-align:left;" scope="col">ปีงบประมาณ</td>
	  <?php 
		  $starto = 0;
		  $datarow = 5;
		  $sql = "SELECT pro_year FROM tb_project ORDER BY pro_id LIMIT $starto , $datarow ";
		  $result = selectSql($sql);
		  foreach($result as $row){
			  echo '<td style="font-family: Kanit; text-align:center;" class="tcol">',$row['pro_year'],'</td>';
		  }	  
	  ?>
    </tr>
	
    <tr style="background-color:white;color:black">
	  <td style="text-align:left;"  scope="col">โครงการ</td>
	  <?php 
		  $sql = "SELECT pro_name FROM tb_project ORDER BY pro_id LIMIT $starto , $datarow ";
		  $result = selectSql($sql);
		  foreach($result as $row){
			  echo '<td style="font-family: Kanit; text-align:left;" class="tcol">',$row['pro_name'],'</td>';
		  }	  
	  ?>
    </tr>
	
	<tr style="background-color:white;color:black">
	  <td style="text-align:left;"  scope="col">งบประมาณที่ได้รับ</td>
	  <?php 
		  $sql = "SELECT pro_budget FROM tb_project ORDER BY pro_id LIMIT $starto , $datarow ";
		  $result = selectSql($sql);
		  foreach($result as $row){
			  echo '<td style="font-family: Kanit; text-align:left;">',$row['pro_budget'],'</td>';
		  }	  
	  ?>
    </tr>
	
	<tr style="background-color:white;color:black">
	  <td style="text-align:left;"  scope="col">สัญญา</td>
	  <?php 
		  $sql = "SELECT pro_contact FROM tb_project ORDER BY pro_id LIMIT $starto , $datarow ";
		  $result = selectSql($sql);
		  foreach($result as $row){
			  echo '<td style="font-family: Kanit; text-align:left;">',$row['pro_contact'],'</td>';
		  }	  
	  ?>
    </tr>
	
	<tr style="background-color:white;color:black">
	  <td style="text-align:left;"  scope="col">ผู้ขาย/ผู้รับจ้าง</td>
	  <?php 
		  $sql = "SELECT campany_name FROM tb_project,ref_company WHERE pro_company = company_id ORDER BY pro_id LIMIT $starto , $datarow ";
		  $result = selectSql($sql);
		  foreach($result as $row){
			  echo '<td style="font-family: Kanit; text-align:left;">',$row['campany_name'],'</td>';
		  }	  
	  ?>
    </tr>
	
	<tr style="background-color:white;color:black">
	  <td style="text-align:left;"  scope="col">จำนวน (บาท)</td>
	  <?php 
		  $sql = "SELECT pro_price FROM tb_project,ref_company WHERE pro_company = company_id ORDER BY pro_id LIMIT $starto , $datarow ";
		  $result = selectSql($sql);
		  foreach($result as $row){
			  echo '<td style="font-family: Kanit; text-align:left;">',number_format($row['pro_price']),'</td>';
		  }	  
	  ?>
    </tr>
  </thead>
  <tbody> 
	<tr style="background-color:white;color:black">
	<td style="text-align:left;"  scope="col">ระบบ/อุปกรณ์</td>
	<?php 
		$sql = "SELECT pro_id, pro_test FROM tb_project,ref_company WHERE pro_company = company_id ORDER BY pro_id LIMIT $starto , $datarow ";
		$result = selectSql($sql);
		foreach($result as $row){
			echo '<td style="font-family: Kanit; text-align:left;">';
			
			$pro_id = $row['pro_id'];
			$sql = "SELECT pro_test FROM tb_project WHERE pro_id = '$pro_id'";
			$result = selectSql($sql);
			$arramy = explode(',',$result[0]['pro_test']);
			$Carramy = count($arramy);
			if($result[0]['pro_test']==null OR $result[0]['pro_test']==""){
				echo "<center>-</center>";
			}
			else{
				for($i=0 ; $i < $Carramy ; $i++ ){
					$sql2 = "SELECT eqm_name FROM tb_equipment WHERE eqm_id = '$arramy[$i]'";
					$result2 = selectSql($sql2);
					echo $i+1,'. ',$result2[0]['eqm_name'],'<br>';
					}
			}
			
			
			echo '</td>';
		}	
		
	
	?>  
	</tr>
		<tr style="background-color:white;color:black">
	  <td style="text-align:left;"  scope="col">หมายเหตุ</td>
	  <?php 
		  $sql = "SELECT pro_equipment FROM tb_project,ref_company WHERE pro_company = company_id ORDER BY pro_id LIMIT $starto , $datarow ";
		  $result = selectSql($sql);
		  foreach($result as $row){
			  echo '<td class="br-sql" style="font-family: Kanit; text-align:left;">',$row['pro_equipment'],'</td>';
		  }	  
	  ?>
    </tr>

  </tbody>
  </table>  
</div>

  <?php
	echo '<div>';
	$sql = "SELECT COUNT(pro_id) FROM tb_project";
	$result = selectSql($sql);
	$project_row = $result[0]['COUNT(pro_id)'];
	$sql = "SELECT * FROM tb_project,ref_company WHERE pro_company = company_id GROUP BY pro_id DESC ORDER BY pro_year DESC";
	$result = selectSql($sql);
	$idpage = 'pagepro_';
	$pagepro = 0;
	$col = 5;
	for($h = 0 , $i = 0 ; $i < $project_row ; $i+=$col){
		$starts = $i;
		$h++;
		$pagepro++;
		$pageid = $idpage.$pagepro;
		
		?> <table id ="<?php echo $pageid; ?>"class="tablex table-bordered "> <?php
		//echo '<tr><td>',$pageid,'</td></tr>';
		echo '<tbody style="background-color:white;color:black">';
		echo '<tr style="background-color: #00a65a;color:white">';
		echo '<th> ปีงบประมาณ </th>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
				echo '<td style="font-family:Kanit;text-align:center">',$result[$starts]['pro_year'],'</td>';
			}
		}
		echo '</tr>';
		
		echo '<tr>';
		echo '<td> ชื่อโครงการ</td>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
				echo '<td style="font-family:Kanit;text-align:center">',$result[$starts]['pro_name'],'</td>';
			}
		}
		echo '</tr>';
		
		echo '<tr>';
		echo '<td>งบประมาณที่ได้รับ</td>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
			?> <td style="font-family:Kanit;text-align:center"> 
			<?php if ($result[$starts]['pro_budget'] == null or $result[$starts]['pro_budget'] == "-"){
					echo '-';
				}else{
					echo number_format($result[$starts]['pro_budget']);
				} ?> </td> <?php 
			}
		}
		echo '</tr>';
		
		echo '<tr>';
		echo '<td>เลขสัญญา</td>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
				echo '<td style="font-family:Kanit;text-align:center">',$result[$starts]['pro_contact'],'</td>';
			}
		}
		echo '</tr>';
		
		echo '<tr>';
		echo '<td>ผู้ขาย/ผู้รับจ้าง</td>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
				echo '<td style="font-family:Kanit;text-align:center">',$result[$starts]['campany_name'],'</td>';
			}
		}
		echo '</tr>';
		
		
		echo '<tr>';
		echo '<td>จำนวน(บาท)</td>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
				echo '<td style="font-family:Kanit;text-align:center">',number_format($result[$starts]['pro_price']),'</td>';
			}
		}
		echo '</tr>';
		


		echo '<tr>';
		echo '<td>รายละเอียด<br>เพิ่มเติม</td>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
				echo '<td class="br-sql" style="font-family:Kanit;text-align:left">',$result[$starts]['pro_equipment'],'</td>';
			}
		}
		echo '</tr>';	

		echo '<tr>';
		echo '<td>ระบบ/อุปกรณ์</td>';
		for($starts = $i, $j=0;$j<$col;$j++,$starts++){				
			if($starts<$project_row){
				$pro_test = explode(',',$result[$starts]['pro_test']);
				echo '<td class="br-sql" style="font-family:Kanit;text-align:left">';
				$count_pro_test =  count($pro_test);
				
				if($pro_test[0] == null){
						echo '<center>-</center>';
				}
				else{
					for($g = 0 ; $g < $count_pro_test ; $g++){
						$sqi = "SELECT eqm_name FROM tb_equipment WHERE eqm_id = '$pro_test[$g]'";
						$resuli = selectSql($sqi);
						echo '- ',$resuli[0]['eqm_name'],'<br>';
					}
				}
				
				
				echo '</td>';
			}
		}
		echo '</tr>';				
		
		echo '</tbody>';
		echo '</table>';	
		
	}

 	echo '</div>'; 
	?>
	<div class="col-xs-12" style="background-color:transparent">
		<center>
			<ul class="pagination">
			<?php $page = 1;?>
				<li class="pagiantion_button" id="btn_previous" onclick="conpage('back');">
					<a>ก่อนหน้า</a>
				</li>
				
				<li class="pagiantion_button" id="<?php echo 'page_',$page;?>" onclick="ipage(<?php echo $page;?>);">
							<a><?php echo '1'; ?> </a>
				</li> 
				<li class="pagiantion_button disabled" id="page_disp">
							<a>...</a>
				</li>
				<?php 
					$page+=1;
					for($page; $page <$h ; $page++){ 
					?>
						<li class="pagiantion_button" id="<?php echo 'page_',$page;?>" onclick="ipage(<?php echo $page;?>);">
							<a><?php echo $page; ?> </a>
						</li> <?php
					}
				
				
				?>
				<li class="pagiantion_button disabled" id="page_disn">
							<a>...</a>
				</li> 
				<li class="pagiantion_button" id="<?php echo 'page_',$h;?>" onclick="ipage(<?php echo $page;?>);">
							<a><?php echo $h; ?> </a>
				</li> 
				<li class="pagiantion_button " id="btn_next" onclick="conpage('next');">
					<a>ถัดไป</a>
				</li>

			</ul>
		</center>
	</div>

  
  
  
  	<script> 
	
	
	x = ipage(1);
	var jpage =  x;
	//alert(jpage);
	function ipage(x){
		var pagename = '#pagepro_';
		var pageid = 1;
		var pager = pagename+pageid;
		var coldata = "<?php echo $col;?>";
		var allpage = "<?php echo $h; ?>";
		for(pageid = 1; pageid <= allpage; pageid++ ){
			pager = pagename+pageid;
			$('#page_'+pageid).removeClass('active');		
			$(pager).hide(); 
			//alert(pager);
		}

		if(x == 1 && coldata == <?php echo $project_row;?>){
			$('#btn_previous').addClass('disabled');
			$('#btn_previous').removeAttr('onclick');
			$('#btn_next').addClass('disabled');
			$('#btn_next').removeAttr('onclick');
		}
		else if(x == 1){
			$('#btn_previous').addClass('disabled');
			$('#btn_previous').removeAttr('onclick');
		}
		else if(x == <?php echo $h;?>){
			$('#btn_next').addClass('disabled');
			$('#btn_next').removeAttr('onclick');
		}		
		else{
			$('#btn_previous').removeClass('disabled');
			$('#btn_next').removeClass('disabled');
			$('#btn_previous').attr('onclick','conpage("back")');
			$('#btn_next').attr('onclick','conpage("next")');
		}
		
		$(pagename+x).show();
		
		$('#page_'+x).addClass('active');
		
		$('#showpage').val(x);
		conbtn(x);
		return x;
	}
	 function conpage(x){
		 if(x=='first'){
			ipage(1);
		 }
		 else if(x=='last'){
			 var lastpage = "<?php echo $h; ?>";
			 ipage(lastpage);
		 }
		 else if(x=='back'){
			 var backpage = $('#showpage').val();
			 backpage = parseInt(backpage);
			 ipage(backpage-=1);
			
		 }
		 else if(x=='next'){
			 var nextpage = $('#showpage').val();
			nextpage = parseInt(nextpage);
			ipage(nextpage+=1);
			
		 }
	 }
	 
	 
	function conbtn(x){
		var first = parseInt(1);
		var last = parseInt(<?php echo $h;?>);
		for(let h=2 ; h < last ; h++){
			$('#page_'+h).hide();
		}
		$('#page_disp').hide();
		$('#page_disn').hide();
		if(x>=1 && last<=6){
			for (let i = 2 ; i <= 5 ; i++){
				$('#page_'+i).show();
			}
		}
		else if(x >= 1 && x <5 ){  			
			for (let i = 2 ; i <= 5 ; i++){
				$('#page_'+i).show();
			}
			$('#page_disn').show();
		}
		else if(x <= last && x > (last-4)){	
			$('#page_disp').show();
			for (let i = (last-1) ; i >= (last-4) ; i--){
				$('#page_'+i).show();
			}
		}
		else if(x => 5 && x <= (last-4)){
			//alert(x);
			$('#page_disp').show();
			$('#page_'+(x-2)).show();
			$('#page_'+(x-1)).show();
			$('#page_'+x).show();
			$('#page_'+(x+1)).show();
			$('#page_'+(x+2)).show();
			$('#page_disn').show();
		}
		 
	 }
	 

	 

	

	</script>