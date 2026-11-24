<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  //echo $_POST['event'];
?>

<script type="text/javascript" src="ChartJs/ChartJs.js"></script>
<script type="text/javascript" src="ChartJs/plugin-label.js"></script>
<script type="text/javascript" src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>


<h4 style="font-family:Kanit ;margin: -4px 0 5px 0;padding:0">ผู้ดำเนินการ</h4>
<div class="box-body" style="padding:0">
	<div id="ssr_owner" class="box box-info" style="padding-top:5px;border-width:7.5px;border-top-color:gray">
		<canvas  id="owner-myChart" style="height:250px"></canvas>
	</div>
</div>

<script>
 	Chart.defaults.font.size = 15;
	Chart.defaults.font.weight = '500';
	Chart.defaults.font.family = 'Kanit';
	

		var data = {		  
		  labels: ['กรม.','อปท.'],
		  datasets: [{
			label: ['จำนวน  '],
			x:[1,2],
			data: ['<?php echo $_POST["dpt"];?>','<?php echo $_POST["opt"];?>'],
			backgroundColor: ['#878787','#ff0000'],
			//hoverBackgroundColor: ['#295DBD'],
			hoverOffset: 4
		  }]
		};

		var config = {
		  type: 'bar',
		  data,
		  options: {
			onClick: (event, elements, chart) => {
			if (elements[0]) {            
				 const i = elements[0].index;
				 showOPT(chart.data.datasets[0].x[i]);
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
					text: 'ผู้ดำเนินการ'
				  }
				}
			  },
		  }
		};	
	var cp_myChart = new Chart(document.getElementById('owner-myChart'),config);

	function showOPT(x){
		/*if(x==1){
			alert('กรมฯ');
		}else{
			alert('อปท.');
		}*/
		$.post('report/show_optx.php',{
			opt:x,
			event : '<?= $_POST["event"];?>'
		},function(data){
			$('#subshow').html(data);
		});
	}
	
</script>