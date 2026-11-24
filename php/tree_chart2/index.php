<?php 
	include '../js/function_db.php';
	$sql ="SELECT COUNT(*) FROM tb_cityplan";
	$cityplan = selectSql($sql);
	//echo $cityplan[0]['COUNT(*)'];
	
	$sql ="SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2";
	$notice = selectSql($sql);
	//echo $notice[0]['COUNT(*)'];
	
	$sql ="SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 2";
	$evaluation = selectSql($sql);
	//echo $evaluation[0]['COUNT(*)'];
	
	//$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE ";
	//$eva5year = selectSql($sql);
	//echo $eva5year[0]['COUNT(*)'];
	
	//$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE ";
	//$eva10year = selectSql($sql);
	//echo $eva10year[0]['COUNT(*)'];
	
	$sql ="SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 3 ";
	$maintanence = selectSql($sql);
	//echo $maintanence[0]['COUNT(*)'];
	
	$sql ="SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage =3 AND cp_effective = 1";
	$effective1 = selectSql($sql);
	//echo $effective1[0]['COUNT(*)'];
	
	$sql ="SELECT COUNT(*) FROM tb_cityplan WHERE  cp_stage =3 AND cp_effective = 2";
	$effective2 = selectSql($sql);
	//echo $effective2[0]['COUNT(*)'];
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 1 AND cp_eec = 'N'";
	$newplan = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_eec = 'Y'";
	$eec = selectSql($sql);
	
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css?<?php echo date('Ydmhis')?>">
	<link rel="stylesheet" type="text/css" href="css/fontawesome.css">
	<link rel="stylesheet" type="text/css" href="css/fontawesome.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a70f158623.js" crossorigin="anonymous"></script>
	</head>
	<style>
	input{
		text-align:center;
	}
	</style>
<body>
	<section >
		<div class="container" >
			<div class="head1">
				ผังกำหนดการใช้ประโยชน์ที่ดิน
			</div>
			<div id="wrapper" style="margin: 0 0 50px;">
				<div class="label0">ผังเมืองรวม</div>
				  <div class="branch lv1">
				    <div class="entry">
				    	<div class="label1">
				    		ผังเมืองรวมเมือง/ชุมชน <br>
				    		เปิดพื้นที่ใหม่<br>
						    จำนวน <input type="text" name="" id="number" style="width: 100px; height: 25px;" value="<?php echo $cityplan[0]['COUNT(*)'];?>"> ผัง
						</div>
						<!-- ----------------------------- -->
				      <div class="branch1 lv2">
				        <div class="entry1">
				        	<div class="label" style="background-color:#00ace6; color:#FFF">
				        		ประกาศใช้บังคับ <br>
						        จำนวน <input type="text" name="" id="number" style="width: 100px; height: 25px;" value="<?php echo $notice[0]['COUNT(*)'];?>"> ผัง
				        	</div>
				        	<div class="branch2 lv3">
				        		<div class="entry2">
				        			<div class="label4" style="background-color:#0099e6; color:#FFF">
				        				ประเมินผลผัง <br>
						        		จำนวน <input type="text" name="" id="number" style="width: 100px; height: 25px;" value="<?php echo $evaluation[0]['COUNT(*)'];?>"> ผัง
				        			</div>
				        			<div class="branch3 lv4">
					                		<div class="entry3">
					                			<div class="label5" style="background-color:#0099e6; color:#FFF">
						                			ประเมินผล <br>
						                			(ครบ 5 ปี) <br>
							        				จำนวน <input readonly style="background-color:gray;width:90px" type="text" name="" id="number"  style="width: 10px; height: 25px;" value="-"> ผัง
					                			</div>
					                		</div>
					                		<div class="entry3">
					                			<div class="label6" style="background-color:#0099e6; color:#FFF">
						                			ประเมินผล <br>
						                			(ครบ 10 ปี) <br>
							        				จำนวน <input readonly style="background-color:gray;width:90px" type="text" name="" id="number" style="width: 100px; height: 25px;" value="-"> ผัง
					                			</div>
					                		</div>
					              	</div><!-- branch lv4 -->
				        		</div><!-- entry -->
				        		<!-- ------------------------------------- -->
				        		<div class="entry2">
				        			<div class="label4" style="background-color:#408000; color:#FFF">
				        				ปรับปรุงผัง <br>
						        		จำนวน <input type="text" name="" id="number" style="width: 100px" value="<?php echo $maintanence[0]['COUNT(*)'];?>"> ผัง
				        			</div>
					        				<div class="branch4 lv4">
						                		<div class="entry4">
						                			<div class="label7" style="background-color:#408000; color:#FFF">
							                			มีผลบังคับใช้ <br>
								        				จำนวน <input type="text" name="" id="number" style="width: 100px; height: 25px;" value="<?php echo $effective1[0]['COUNT(*)'];?>"> ผัง
						                			</div>
						                		</div>
						                		<div class="entry4">
						                			<div class="label8" style="background-color:#b3b3b3; color:#000099">
						                				สิ้นสุดบังคับใช้ <br>
							        					จำนวน <input type="text" name="" id="number" style="width: 100px; height: 25px;" value="<?php echo $effective2[0]['COUNT(*)'];?>"> ผัง
						                			</div>
						                		</div>
						              		</div>
					           	</div>				        
					          </div><!-- branch lv3 -->
				        </div><!-- entry -->
				        <!-- ------------------------------- -->
				        <div class="entry1">
				        	<div class="label2" style="background-color:#4da6ff; color:#FFF">
				        		ผังเมืองรวมเมือง/ชุมชน<br>
						        เปิดพื้นที่ใหม่<br>
							    จำนวน <input type="text" name="" id="number" style="width: 100px; height: 25px;" value="<?php echo $newplan[0]['COUNT(*)']; ?>"> ผัง
				        	</div>
				        </div>
				        <!-- ------------------------------------------ -->
				        <div class="entry1">
				        	<div class="label3" style="background-color:#4da6ff; color:#FFF">
				        		ผังเมืองรวมเมือง/ชุมชน<br>
						        ในเขตพื้นที่ EEC<br>
							    จำนวน <input type="text" name="" id="number" style="width: 100px; height: 25px;"  value="<?php echo $eec[0]['COUNT(*)'];?>"> ผัง
				        	</div>
				        </div>
				      </div><!-- branch lv2 -->
				    </div><!-- entry -->
				    <!-- ---------------------------- -->
				    <div class="entry">
				    	<div class="label1" style="background-color:#00b300 ;">
				    		ผังเมืองรวมจังหวัด <br>
						    จำนวน <input readonly style="background-color:gray" type="text" name="" id="number" style="width: 100px; height: 25px;font;" value="70"> ผัง
						</div>
					</div>
					<!-- ------------------------------------------ -->
				    <div class="entry">
				    	<div class="label1" style="background-color: #0066cc;">
				    		ผังเมือง EEC <br>
						    จำนวน <input readonly style="background-color:gray" type="text" name="" id="number" style="width: 100px; height: 25px;" value="1"> ผัง
				    	</div>
				    </div> <!-- entry -->
				    <!-- -------------------------------------- -->
				  </div> <!-- branch lv1 -->
			</div><!-- wrapper -->
		</div>
	</section>
	<!-- --------------------------------------------------------------- -->
	<div class="container"> 
		<hr>
	</div>
	<!-- ------------------------------------------------------------------------- -->
	<section>
		<div class="container " align="center">
				<div class="header" align="center">ผังเมืองรวมเมือง/ชุมชน อยู่ในขั้นตอนการวางและจัดทำ
					<br>ที่ต้องดำเนินการติดตามผัง จำนวน <input readonly style="background-color:gray" type="text" name="" id="number"> ผัง
				 </div>
					  <ul class="tree">
					        	<li>
					        		<div class="make">ดำเนินการอยู่ในขั้นตอนการวางและทำ</div>
					        		<ul>
					        			<li>
					        				<div class="city">ผังเมืองรวมเมือง/ชุมชน <br>
					        					เปิดพื้นที่ใหม่<br>
					        					จำนวน <input readonly style="background-color:gray" type="text" name="" id="number"> ผัง
					        				</div>
					        			</li>
					        			<li>
					        				<div class="ecc">ผังเมืองในเขตพื้นที่ ECC <br>
					                          จำนวน <input readonly style="background-color:gray" type="text" name="" id="number"> ผัง
					                        </div>
					          
					                  	</li>
					                </ul>
					            </li>
					            <li>
					                <div class="adjust">ดำเนินการอยู่ในขั้นตอนการปรับปรุงผังฯ</div>
					                <ul>
					                  <li>
					                    <div class="effective">กฎกระทรวงผังเมืองรวม<br> มีผลบังคับใช้<br>
					                    จำนวน <input readonly style="background-color:gray" type="text" name="" id="number"> ผัง</div>
					                   
					                  </li>
					                  <li>
					                  	<div class="end">กฎกระทรวงผังเมืองรวม<br> สิ้นสุดใช้บังคับ<br>
					                          จำนวน <input readonly style="background-color:gray" type="text" name="" id="number"> ผัง
					                    </div>
					          
					                  </li>
					                </ul>
					            </li>
					   </ul>

			<table class="tree-view table-responsive" >
				<tr>
					<td>จำนวน <input readonly style="background-color:gray" type="text" name="" id="number"> ผัง</td>
					<td>จำนวน <input readonly style="background-color:gray" type="text" name="" id="number"> ผัง</td>
				</tr>
				
			</table>
		</div>
	</section>
</body>
</html>

<script>
function gotest(){
	window.close();
	/*$.post('new1.php',{
		link : 'a'
	},function(data){
		alert('hi');
	});*/
	
}

</script>