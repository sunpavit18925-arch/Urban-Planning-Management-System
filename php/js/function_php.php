<?php 

include 'function_db.php';

function mytable($row,$manage,$sql){
	if(!isset($sql)){
		$sql = "SELECT * FROM tb_cityplan,ref_province WHERE cp_province = pvn_id";
	}
	else{
		$sql = "SELECT * FROM tb_cityplan,ref_province WHERE cp_province = pvn_id ".$sql;
	}
	//echo $sql;
	$result = selectSql($sql);
	if(count($result)>0){
		echo '<div class="table-responsive">';
		for($table = 1 , $start = 0 ; $table <= ceil((count($result)/$row)) ;  $table++){ ?>
			<table id="<?php echo 'mytable_'.$table;?>" class="table table-bordered">
				<thead>
					<tr style="background-color: #0AAAFA;color:white">
						<td style="text-align:center">ลำดับ</td>
						<td style="text-align:center">ชื่อผังเมือง</td>					
						<td style="text-align:center">จังหวัด</td>
					<?php if($manage == true){ ?>
						<td style="text-align:center">จัดการ</td> <?php
						}?>
					</tr>
				</thead>
				<tbody> <?php
					for($start , $arow = 1 ; $arow <= $row ; $arow++ , $start++){
						if($start == (count($result))){break;} ?>
						<tr>
							<td style="text-align:center"><?php echo $start+1;?></td>
							<td style="text-align:center"><?php echo $result[$start]['cp_name'];?></td>
							<td style="text-align:center"><?php echo $result[$start]['pvn_name'];?></td>
						<?php if($manage == true){?>
							<td style="text-align:center">
								<span class="gear"><a><i class="fa fa-gear"></i></a></span>
								<span class="trash"><a><i class="fa fa-trash"></i></a></span>
							</td>
						<?php } ?>
						</tr> <?php 
					} 
				?> 
				</tbody>
			</table> <?php
		} ?>
		</div>
		<div style="text-align:center">
			<select id="selectTable" onchange="changetable()" style="font-size:20px;text-align:center;border-radius:8px"> <?php
				for($t = 1 ; $t <= ceil((count($result)/$row)) ; $t++){ ?>
					<option value="<?php echo $t;?>"><?php echo $t;?></option><?php 
				} ?>
			</select>
		</div>	
		<script>
		changetable();
		function changetable(){
			for(let i=1 ; i <= '<?php echo ceil((count($result)/$row)); ?>' ; i++){
				$('#mytable_'+i).hide();
			}
			$('#mytable_'+$('#selectTable').val()).show();
			$('#cp_value').html('<?php echo count($result);?>');
		}		
		</script> <?php
	}
	else{ ?>
		<h2 style="font-family:Kanit;text-align:center">ไม่พบข้อมูล</h2>
		<script>
			$('#cp_value').html('<?php echo count($result);?>');
		</script><?php
	}
}

function accessdata($status){
	if($status == TRUE){
			$sql = "SELECT user_id,user_pmacess FROm tb_user WHERE user_id =  '".$_SESSION['intra_czid']."'";
			$result = selectSql($sql);
			$result[0]['user_pmacess'] = 1;
			if($result[0]['user_pmacess']==1){ //access affiliation 1
				echo'<script> $(".access_2").hide();</script>';
				echo'<script> $(".access_3").hide();</script>';
				echo'<script> $(".access_4").hide();</script>';
			}
			elseif($result[0]['user_pmacess']==2){ //access affiliation 2
				echo'<script> $(".access_1").hide();</script>';
				echo'<script> $(".access_3").hide();</script>';
				echo'<script> $(".access_4").hide();</script>';
			}
			elseif($result[0]['user_pmacess']==3){ //access affiliation 3
				echo'<script> $(".access_1").hide();</script>';
				echo'<script> $(".access_2").hide();</script>';
				echo'<script> $(".access_4").hide();</script>';
			}
			elseif($result[0]['user_pmacess']==4){ //access affiliation 4
				echo'<script> $(".access_1").hide();</script>';
				echo'<script> $(".access_2").hide();</script>';
				echo'<script> $(".access_3").hide();</script>';
			}
			elseif($result[0]['user_pmacess']==9){ //access all data
				
			}
			?>
			<script>
				alert("<?php echo $sql; ?>");
			</script>
			<?php 
	}
	else{
		?>
		<script>
				alert('False');
		</script>
		<?php
	}

}









?>