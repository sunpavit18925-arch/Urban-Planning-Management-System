<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>

<script type="text/javascript" src="ChartJs/ChartJs.js"></script>
<script type="text/javascript" src="ChartJs/plugin-label.js"></script>
<script type="text/javascript" src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>


<h4 style="font-family:Kanit;margin:0;padding:0">ขอบเขตดำเนินการ</h4>
<div class="box-body" style="padding:0">
	<div id="ssr_scope" class="box box-info" style="padding-top:5px;border-width:7.5px;border-top-color:gray">
		<canvas  id="scope-myChart" style="height:250px"></canvas>
	</div>
</div>

<script>
 	Chart.defaults.font.size = 15;
	Chart.defaults.font.weight = '500';
	Chart.defaults.font.family = 'Kanit';
	

		var data = {		  
		  labels: ['ไม่เต็มอำเภอ','เต็มอำเภอ'],
		  datasets: [{
			label: ['จำนวน  '],
			x:[1,2],
			data: ['<?php echo $_POST["half"];?>','<?php echo $_POST["full"];?>'],
			backgroundColor: ['#878787'],
			hoverBackgroundColor: ['#295DBD'],
			hoverOffset: 4
		  }]
		};

		var config = {
		  type: 'bar',
		  data,
		  options: {			 
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
			},
			layout: {
				padding: {
					top: 18
				}
			},
			scales: {
				y: {
				  title: {
					display: false,
					text: 'จำนวน'
				  }
				},
				x: {
				  title: {
					display: false,
					text: 'ขอบเขต'
				  }
				}
			  },
		  }
		};	
	var cp_myChart = new Chart(document.getElementById('scope-myChart'),config);	
	
	
</script>