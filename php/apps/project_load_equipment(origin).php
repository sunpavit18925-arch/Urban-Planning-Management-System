<?php
    include '../js/function_db.php';
	session_name('tutorial');
    session_start();
    //echo $_SESSION['intra_czid'];
	$_SESSION['eid'];

    ?>


<table id="tableeqm" class="tablex table-bordered " >
  <thead>
    <tr style="background-color: #00a65a;color:white">
      <th style="text-align:center" class="col-md-5" scope="col">ระบบ/อุปกรณ์</th>
      <th style="text-align:center" class="col-md-1" scope="col">จัดการ</th>

    </tr>
  </thead>
  <tbody style="background-color:white">
    <?php
        $plan = "plan".$_SESSION['eid'];
        $sql = "SELECT eqm_id, eqm_name FROM tb_equipment ";
        $result = selectSql($sql);
		$crow = count($result);
		
		$sqj = "SELECT pro_test FROM tb_project WHERE pro_id = '".$_SESSION['eid']."'";
		$resultj = selectSql($sqj);
		$pro_test = explode(",",$resultj[0]['pro_test']);
		$cpro = count($pro_test);

        foreach ($result as $row){ 
		
		?>

    <tr class="trcolor" >
	  <td> <?php echo $row['eqm_name']; ?></td>
      <td style="text-align:center" >
	  <input type="hidden" id="<?php echo "rewrita_",$row['eqm_id']?>" value="<?php if(in_array($row['eqm_id'],$pro_test)){ echo $row['eqm_id'];}6 ?>">
	  <?php 
		
	  
	  
		if(in_array($row['eqm_id'],$pro_test)){ ?>
			<svg  onclick="setproequipment(<?php echo $row['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
			  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
			</svg> 	<?php
		}else{ ?>
			<svg onclick="setproequipment(<?php echo $row['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
			  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
			</svg> <?php 
		}
	  
		//echo $cpro;	
		//echo $pro_test[0];
		
	  
	    /*if($row[$plan]=='1' ){ ?>
			<svg  onclick="setequipment(0,<?php echo $row['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
			  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
			</svg> <?php
		}
		else{ ?>
			<svg onclick="setequipment(1,<?php echo $row['eqm_id']; ?>,<?php echo $_SESSION['eid']; ?>);" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
			  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
			</svg> <?php 
		}*/ ?>
	  </td>
    </tr>	  
  <?php } ?>
  </tbody>
  </table>
  
   <script src="bower_components/datatables.net/js/jquery.dataTables.js?<?php echo date('Ydmhis')?>"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js?<?php echo date('Ydmhis')?>"></script>
<script>
	/*
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
	 */
	 
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
		var crow = "<?php echo $crow;?>";
		alert(crow);
		for(let i = 1 ; i <= crow ; i++){
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
	
	/*function setproequipment(x,eqm_id,pro_id){
       $.post('apps/project_equipment(pro).php',{
        event: 'seqm',
		x : x,
        eqm_id : eqm_id,        
        pro_id : pro_id 
        },function(data) {          
         alert(data);
		 //$('#loadpropeqm').load('apps/project_load_equipment.php');
       });
		/*alert(x);
		alert(eqm_id);
		alert(pro_id);
	}*/

/*function setequipment(x,id,pname){
	  name = 'plan'+pname;
      if ( (x==1)) {
        $.post('apps/plan_equipment(pro).php',{
        event: 'seqm',
        id : id,        
        eqm_status : x,
		column_name : name 
        },function(data) {          
        // alert(data);
		 $('#loadpeqm').load('apps/plan_load_eqm.php');

         
       });
      }
      else {
      $.post('apps/plan_equipment(pro).php',{
          event : 'seqm',
          id : id,          
          eqm_status : x,
		  column_name : name
      },function(data) {        
       // alert(data);		
		$('#loadpeqm').load('apps/plan_load_eqm.php');

        });
      }
    }*/
	

</script>