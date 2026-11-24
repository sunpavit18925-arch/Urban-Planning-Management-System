<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 1";
	$stg1 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 2 OR cp_stage = 4";
	$stg2 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage = 3";
	$stg3 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_stage IS NULL OR cp_stage = 0 ";
	$stg0 = selectSql($sql);
	
	
?>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<script type="text/javascript" src="ChartJs/ChartJs.js"></script>
<script type="text/javascript" src="ChartJs/plugin-label.js"></script>
<script type="text/javascript" src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>



<div>
	<div id="stg_data_div" class="box box-info" style="padding-top:15px;border-width:7.5px;border-top-color:gray">
		<canvas id="stg-myChart"></canvas>
	</div>
</div>



<script>
	Chart.defaults.font.size = 15;
	Chart.defaults.font.weight = '500';
	Chart.defaults.font.family = 'Kanit';
	
	<?php 
		if($stg0[0]["COUNT(*)"] > 0){ ?>
			var data = {		  
			  labels: ['การวางและจัดทำผัง','การประเมินผลผัง','การปรับปรุงผัง','ไม่ระบุขั้นตอน'],
			  datasets: [{
				label: ['จำนวน  '],
				x:[1,2,3,0],
				data: ['<?php echo $stg1[0]["COUNT(*)"];?>','<?php echo $stg2[0]["COUNT(*)"];?>','<?php echo $stg3[0]["COUNT(*)"];?>','<?php echo $stg0[0]["COUNT(*)"];?>'],
				backgroundColor: ['rgb(255, 99, 132)','rgb(255,153,87)','rgb(0, 108, 255)','rgb(255, 33, 33)'],
				hoverBackgroundColor: ['rgb(255, 36, 82)','rgb(255, 125, 41)','rgb(0, 67, 158)','rgb(171, 5, 5)'],
				hoverOffset: 4
			  }]
			};
		<?php }
		else{ ?>
			var data = {		  
			  labels: ['การวางและจัดทำผัง','การประเมินผลผัง','การปรับปรุงผัง'],
			  datasets: [{
				label: ['จำนวน  '],
				x:[1,2,3],
				data: ['<?php echo $stg1[0]["COUNT(*)"];?>','<?php echo $stg2[0]["COUNT(*)"];?>','<?php echo $stg3[0]["COUNT(*)"];?>'],
				backgroundColor: ['rgb(255, 99, 132)','rgb(255,153,87)','rgb(0, 108, 255)'],
				hoverBackgroundColor: ['rgb(255, 36, 82)','rgb(255, 125, 41)','rgb(0, 67, 158)'],
				hoverOffset: 4
			  }]
			};
		<?php }	?>
		
		var config = {
		  type: 'bar',
		  data,
		  options: {
			scales: {
				y: {
				  title: {
					display: true,
					text: 'จำนวน'
				  }
				},
				x: {
				  title: {
					display: false,
					text: 'ขั้นตอน'
				  }
				}
			},			  
			onClick: (event, elements, chart) => {
			  if (elements[0]) {            
				 const i = elements[0].index;
				 showStage(chart.data.datasets[0].x[i]);
			  }
			},			  
			plugins: {
				legend: {
					display: false				
				},
				labels : {
					fontFamily:'Kanit',
					fontSize: 17,
					render : (args) => {
						return args.value
					}
				}
			}
		  }
		};	
	var stg_myChart = new Chart(document.getElementById('stg-myChart'),config);	
	
	function showStage(x){
		$.post('report/show_result.php',{
			stage_id :x ,
			event :'stage'
		},function(data){
			$('#mainshow').html(data);
		})
	}
	
	
	
	
</script>
 