<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<table id="CP_data_tb" class='tablex table-bordereds' style='width:100%'>
	<thead>
		<tr style='background-color: #295DBD;color:white!important'>
			<th style="text-align:center;color:white!important">ส่วนผังเมืองรวม</th>
			<th style="text-align:center;color:white!important">จำนวนจังหวัด</th>				
			<th style="text-align:center;color:white!important">จำนวนผังเมือง</th>				
		</tr>
	</thead>
	<tbody>
		<?php 
			$sql = "SELECT COUNT(aff_id) FROM ref_affiliation";
			$result = selectSql($sql);
			$row = $result[0]['COUNT(aff_id)'];
			for($i = 1 ;$i<=$row;$i++){
			$sql = "SELECT aff_name ,COUNT(pvn_id) FROM ref_province , ref_affiliation WHERE pvn_affiliation = aff_id AND aff_id = '$i'";
			$pvn = selectSql($sql);
			
			$sql="SELECT COUNT(cp_no) FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id AND aff_id = '$i'";
			$cp = selectSql($sql);
		?>
		<tr>
			<td><?php echo $pvn[0]['aff_name']; ?></td>
			<td style="text-align:center"><?php echo $pvn[0]['COUNT(pvn_id)']; ?></td>
			<td style="text-align:center"><a onclick="showCityplan(<?php echo $i;?>)"><?php echo $cp[0]['COUNT(cp_no)']; ?></a></td>
		</tr>
		<?php } ?>
		<tr>
			<?php 
				$sql = 'SELECT COUNT(pvn_id) FROM ref_province,ref_affiliation WHERE pvn_affiliation = aff_id';
				$result = selectSql($sql); 
			?>
			<td style="text-align:center"><b>รวม</b></td>
			<td style="text-align:center"><b><?php echo $result[0]['COUNT(pvn_id)']; ?></b></td>
			<?php	 
				$sql = 'SELECT COUNT(cp_no) FROM tb_cityplan'; 
				$result = selectSql($sql); 
			?>
			<td style="text-align:center"><b><?php echo $result[0]['COUNT(cp_no)']; ?></b></td>
		</tr>		
	</tbody>
</table>

<script>
	function showCityplan(x){
		$.post('report/show_result.php',{
			aff_id :x ,
			event :'cityplan'
		},function(data){
			$('#mainshow').html(data);
			$('#div-select').hide();
			$('#div-btn').removeClass("col-xs-5 col-md-6");
			$('#div-btn').addClass("col-xs-12 col-md-12");
			
			//$('#').removeClass("col-xs-7 col-md-6");
			//$('#select-aff').addClass("col-xs-12 col-md-12");	
			//$('#select-aff').hide();			
			//$('#div-search-pvn').removeClass("col-xs-6 col-md-5");
			//$('#div-search-pvn').addClass("col-xs-7 col-md-5");
			$('#search_value').hide();
		})
	}
</script>