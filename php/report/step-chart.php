<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 1 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp1 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 2 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp2 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 3 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp3 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 4 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp4 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 5 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp5 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 6 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp6 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 7 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp7 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step = 8 AND cp_step_1 > 0 AND cp_status = 1 AND cp_stage = 1 AND (cp_consider = 0)";
	$stp8 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_status = 1 AND cp_stage = 1) AND ((cp_step != 0 AND cp_step_1 = 0) OR (cp_step = 0) OR (cp_consider != 0))";
	$stp0 = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_step != 0 AND cp_step_1 = 0 AND cp_status = 1 AND cp_stage = 1";
	$oth = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_consider = 0 AND cp_step = 0)AND cp_status = 1 AND cp_stage = 1";  //กรณี พิจารณา = 0 , ขั้นตอน  = 0  
	$consider =  selectSql($sql); 

	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_consider = 1 AND cp_step = 0) AND cp_status = 1 AND cp_stage = 1";		//กรณี พิจารณา = 1 , ขั้นตอน  = 0
	$consider2 =  selectSql($sql);	
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE (cp_consider = 1 AND cp_step != 0) AND cp_status = 1 AND cp_stage = 1";			//กรณี พิจารณา = 1 , ขั้นตอน != 0 
	$consider3 = selectSql($sql);
?>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<script type="text/javascript" src="ChartJs/ChartJs.js"></script>
<script type="text/javascript" src="ChartJs/plugin-label.js"></script>
<script type="text/javascript" src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>



<div>
	<div id="stp_data_div" class="box" style="border-width:7.5px;border-top-color:rgb(255, 99, 132)">
		<canvas style="margin-top:0px"id="stp-myChart"></canvas>
	</div>
</div>



<script>
	Chart.defaults.font.size = 16;
	Chart.defaults.font.family = 'Kanit';
		var data = {		  
		  labels: [
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'อื่นๆ'
			/*'ไม่ระบุ',			//ผังไม่ระบุการพิจารณาการดำเนินการ
			'ใช่', 			//ผังพิจารณาการดำเนินการ = ใช่ 
			'ไม่ระบุขั้นตอน' 		//ผังไม่ระบุขัเนตอน */
		  ],
		  datasets: [{
			label: ['จำนวน '],
			x: [1,2,3,4,5,6,7,8,0],
			data: [
				'<?php echo $stp1[0]["COUNT(*)"];?>',
				'<?php echo $stp2[0]["COUNT(*)"];?>',
				'<?php echo $stp3[0]["COUNT(*)"];?>',
				'<?php echo $stp4[0]["COUNT(*)"];?>',
				'<?php echo $stp5[0]["COUNT(*)"];?>',
				'<?php echo $stp6[0]["COUNT(*)"];?>',
				'<?php echo $stp7[0]["COUNT(*)"];?>',
				'<?php echo $stp8[0]["COUNT(*)"];?>',
				'<?php echo $stp0[0]["COUNT(*)"];?>'
				//'<?php echo $consider[0]["COUNT(*)"]+$consider2[0]["COUNT(*)"]+$consider3[0]["COUNT(*)"]+$oth[0]["COUNT(*)"];?>'
				
			],
			/*borderColor: [
				'rgb(255,153,87,0.4)', 
				'rgb(255,209,87,0.4)',
				'rgb(166,255,87,0.4)',
				'rgb(87,255,189,0.4)', 
				'rgb(87,232,255,0.4)',
				'rgb(87,156,255,0.4)',
				'rgb(97,87,255,0.4)', 
				'rgb(153,87,255,0.4)',
				'rgb(255,0,0,0.4)'
			],*/
			/*backgroundColor:[
				'rgb(255,153,87)', 
				'rgb(255,209,87)',
				'rgb(166,255,87)',
				'rgb(87,255,189)', 
				'rgb(87,232,255)',
				'rgb(87,156,255)',
				'rgb(97,87,255)', 
				'rgb(153,87,255)',
				'rgb(255,0,0)'
			],
			hoverBackgroundColor: [
				'rgb(255, 125, 41)',
				'rgb(255, 185, 0)',
				'rgb(129, 255, 18)',
				'rgb(0, 255, 179)',
				'rgb(0, 208, 255)',
				'rgb(0, 105, 255)',
				'rgb(67, 55, 250)',
				'rgb(100, 0, 255)',
				'rgb(207, 0, 0)',
			],*/
			//borderWidth:2,
			backgroundColor:[
				'rgb(255, 223, 236 )',	
				'rgb(255, 179, 203)', 				
				'rgb(255, 154, 186 )', 	
				'rgb(255, 126, 166)', 	
				'rgb(255, 106, 153)', 	
				'rgb(255, 84, 138)', 		
				'rgb(255, 43, 110)', 	
				'rgb(226, 0, 75 )',
				'rgb(255, 0, 0)'
			],
			/*hoverBackgroundColor: [
				'rgb(207, 0, 0)',
			],*/
			hoverOffset: 4
		  }]
		};
		
		var config = {
		  type: 'bar',
		  data,
		  options: {
			layout: {
				padding: {                     
					top: 25
				}
			},  
			scales: {
				y: {
				  title: {
					display: true,
					text: 'จำนวน'
				  }
				},
				x: {
				  title: {
					display: true,
					text: 'ขั้นตอน'
				  }
				}
			  },
			onClick: (event, elements, chart) => {
			if (elements[0]) {            
				 const i = elements[0].index;
				 showStep(chart.data.datasets[0].x[i]);
			  }
			},
			plugins: {
				legend: {
					display: false,
					position: 'top',
					align: 'center',
					
				},
				labels : {
					fontFamily:'Kanit',
					fontSize: 17,
					render : (args) => {
						return args.value
					}
				}
				/*,tooltip: {
					callbacks: {
						label: function(context) {
							let label = context.dataset.label || '';

							if (label) {
								label += '----';
							}
							return label;
						}
					}
				}*/
			}
		  }
		};		
	var stp_myChart = new Chart(document.getElementById('stp-myChart'),config);	
	
	function showStep(x){
		$.post('report/show_result.php',{
			step_id :x ,
			event :'step'
		},function(data){
			$('#mainshow').html(data);
		})
	}
</script>
 
 