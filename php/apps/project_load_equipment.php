<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];
	$_SESSION['eid'];

    $sql = "SELECT eqm_id,eqm_name FROM tb_equipment";
	$result = selectSql($sql);
	$eqm_row = count($result);
	$eqm_show = 10;
	
	
	$sqj = "SELECT pro_test FROM tb_project WHERE pro_id = '".$_SESSION['eid']."'";
	$resultj = selectSql($sqj);
	$pro_test = explode(",",$resultj[0]['pro_test']);
	
	
	?><input id="showpage_eqm" hidden><?php
	for($h = 0 , $i = 0 ; $i < $eqm_row ; $h+=0){	
		$h++; ?>
		<table id="<?php echo 'eqm_pagepro_',$h; ?>" class="tablex table-bordered">	
		<thead>
			<tr style="background-color: #00a65a;color:white">
			  <th style="text-align:center" class="col-md-5" scope="col">ระบบ/อุปกรณ์</th>
			  <th style="text-align:center" class="col-md-1" scope="col">จัดการ <?php echo $h;  ?></th>
			</tr>
		 </thead>
		 <tbody style="background-color:white">
		<?php
		for($i ,$row = 1 ; $row <= $eqm_show ; $i++ ,$row++){ 
		
		if($i == $eqm_row){
			break;			
		}
		
		
		
		?>
			<tr>
			<td> <?php echo $result[$i]['eqm_id'],' : ',$result[$i]['eqm_name']; ?></td>
			<td style="text-align:center" >
				<input type="hidden" id="<?php echo "rewrita_",$result[$i]['eqm_id']?>" value="<?php if(in_array($result[$i]['eqm_id'],$pro_test)){ echo $result[$i]['eqm_id'];} ?>">
				<?php 
				if(in_array($result[$i]['eqm_id'],$pro_test)){ ?>
					<svg  onclick="setproequipment(<?php echo $result[$i]['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
					  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
					  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
					</svg> 	<?php
				}
				else{ ?>
					<svg onclick="setproequipment(<?php echo $result[$i]['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
					  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
					  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
					</svg> <?php 
				} ?>
			</td>	
			</tr> <?php 
		}		
		echo '</table> ';
	}	?>

	<div class="col-xs-12" style="background-color:transparent">
		<center>
			<ul class="pagination">
			<?php $page = 1;?>
				<li class="pagiantion_button" id="eqm_btn_previous" onclick="conpage('back');">
					<a>◄</a>
				</li>
				
				<li class="pagiantion_button" id="<?php echo 'eqm_page_first';?>" onclick="epage(<?php echo $page;?>);">
							<a><?php echo '1'; ?> </a>
				</li> 
				<li class="pagiantion_button disabled" id="eqm_page_disp">
							<a>...</a>
				</li>
				<?php 
					$page+=1;
					for($page; $page <$h ; $page++){ 
					?>
						<li class="pagiantion_button" id="<?php echo 'eqm_page_',$page;?>" onclick="epage(<?php echo $page;?>);">
							<a><?php echo $page; ?> </a>
						</li> <?php
					}
				
				
				?>
				<li class="pagiantion_button disabled" id="eqm_page_disn">
							<a>...</a>
				</li> 
				<li class="pagiantion_button" id="<?php echo 'eqm_page_last';?>" onclick="epage(<?php echo $page;?>);">
							<a><?php echo $h; ?> </a>
				</li> 
				<li class="pagiantion_button " id="eqm_btn_next" onclick="conpage('next');">
					<a>►</a>
				</li>

			</ul>
		</center>
	</div>
	

	



<script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>
<script>
	epage(1);
	function epage(x){
		var pagename = '#eqm_pagepro_';
		var pageid = 1;
		//var lastpage = 1;
		var lastpage = '<?php echo $h;?>';
		var pager = "";
		$('#eqm_page_first').removeClass('active');
		$('#eqm_page_last').removeClass('active');
		for($pageid = 1 ; pageid <= lastpage ; pageid++){						
			$('#eqm_pagepro_'+pageid).hide();
			$('#eqm_page_'+pageid).removeClass('active');
		}
		
		$('#eqm_page_disp').hide();
		$('#eqm_page_disn').hide();
		$('#eqm_btn_previous').removeClass('disabled');
		$('#eqm_btn_next').removeClass('disabled');
		
		if(x == 1 && x == lastpage){
			$('#eqm_btn_previous').addClass('disabled');
			$('#eqm_btn_previous').removeAttr('onclick');
			$('#eqm_btn_next').addClass('disabled');
			$('#eqm_btn_next').removeAttr('onclick');			
			$('#eqm_page_disp').hide();
			$('#eqm_page_last').hide();
			$('#eqm_page_disn').hide();
		}
		else if(x == 1){
			$('#eqm_btn_previous').addClass('disabled');
			$('#eqm_btn_previous').removeAttr('onclick');
			
			$('#eqm_btn_next').attr('onclick','conpage("next")');
		}
		else if(x == <?php echo $h;?>){
			$('#eqm_btn_previous').removeClass('disabled');
			$('#eqm_btn_previous').attr('onclick','conpage("back")');
			$('#eqm_btn_next').addClass('disabled');
			$('#eqm_btn_next').removeAttr('onclick');
		}		
		else{
			$('#eqm_btn_previous').removeClass('disabled');
			$('#eqm_btn_next').removeClass('disabled');
			$('#eqm_btn_previous').attr('onclick','conpage("back")');
			$('#eqm_btn_next').attr('onclick','conpage("next")');
		}


		if(x==1){
			$('#showpage_eqm').val(x);
			$('#eqm_page_first').addClass('active');
			$('#eqm_pagepro_'+x).show();
		}
		else if(x==lastpage){
			$('#showpage_eqm').val(x);
			$('#eqm_page_last').addClass('active');
			$('#eqm_pagepro_'+x).show();
		}else{
			$('#showpage_eqm').val(x);
			$('#eqm_page_'+x).addClass('active');
			$('#eqm_pagepro_'+x).show();
		}
		
		conbtn(x);
	}


	function conbtn(x){
		var first = parseInt(1);
		var last = parseInt(<?php echo $h;?>);
		for(let h=2 ; h < last ; h++){
			$('#eqm_page_'+h).hide();
		}
		$('#eqm_page_disp').hide();
		$('#eqm_page_disn').hide();
		if(x>=1 && last<=6){
			for (let i = 2 ; i <= 5 ; i++){
				$('#eqm_page_'+i).show();
			}
		}
		else if(x >= 1 && x <5 ){  			
			for (let i = 2 ; i <= 5 ; i++){
				$('#eqm_page_'+i).show();
			}
			$('#eqm_page_disn').show();
		}
		else if(x <= last && x > (last-4)){	
			$('#eqm_page_disp').show();
			for (let i = (last-1) ; i >= (last-4) ; i--){
				$('#eqm_page_'+i).show();
			}
		}
		else if(x => 5 && x <= (last-4)){
			//alert(x);
			$('#eqm_page_disp').show();
			$('#eqm_page_'+(x-2)).show();
			$('#eqm_page_'+(x-1)).show();
			$('#eqm_page_'+x).show();
			$('#eqm_page_'+(x+1)).show();
			$('#eqm_page_'+(x+2)).show();
			$('#eqm_page_disn').show();
		}
		 
	 }
	 
	 function conpage(x){
		 if(x=='first'){
			epage(1);
		 }
		 else if(x=='last'){
			 var lastpage = "<?php echo $h; ?>";
			 epage(lastpage);
		 }
		 else if(x=='back'){
			 var backpage = $('#showpage_eqm').val();
			 backpage = parseInt(backpage);
			 epage(backpage-=1);
			
		 }
		 else if(x=='next'){
			 var nextpage = $('#showpage_eqm').val();
			nextpage = parseInt(nextpage);
			epage(nextpage+=1);
			
		 }
	 }

	
	/*x = epage(1);
	var jpage =  x;
	//alert(jpage);
	function epage(x){
		var pagename = '#eqm_pagepro_';
		var pageid = 1;
		var pager = pagename+pageid;
		var coldata = "<?php echo $eqm_row;?>";
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
		
		$('#showpage_eqm').val(x);
		conbtn(x);
		return x;
	}*/
	
	
	
	/* function conpage(x){
		 if(x=='first'){
			epage(1);
		 }
		 else if(x=='last'){
			 var lastpage = "<?php echo $h; ?>";
			 epage(lastpage);
		 }
		 else if(x=='back'){
			 var backpage = $('#showpage_eqm').val();
			 backpage = parseInt(backpage);
			 epage(backpage-=1);
			
		 }
		 else if(x=='next'){
			 var nextpage = $('#showpage_eqm').val();
			nextpage = parseInt(nextpage);
			epage(nextpage+=1);
			
		 }
	 }*/
	
	 
	/*function conbtn(x){
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
		 
	 }*/







</script>
<script>	
	function setproequipment (eqm_id,pro_id){
		if($('#rewrita_'+eqm_id).val() != ''){
			$('#rewrita_'+eqm_id).val('');
		}
		else{
			$('#rewrita_'+eqm_id).val(eqm_id);
		}
		var pro_test = "";
		var eqm_row = "<?php echo $eqm_row;?>";
		//alert(eqm_row);
		for(let i = 1 ; i <= eqm_row ; i++){
			if($('#rewrita_'+i).val() != ''){
				if(pro_test != ""){
					pro_test +=',';
				}
				pro_test += $('#rewrita_'+i).val();	
			}
		}
		//alert(pro_test);
		$.post('apps/project_equipment(pro).php',{
        eqm_id : eqm_id,        
        pro_id : pro_id,
		pro_test : pro_test
        },function(data) {          
         alert(data);
		 $('#loadproeqm').load('apps/project_load_equipment.php');
       });

		
	}

</script>