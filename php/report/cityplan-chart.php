<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>

<script type="text/javascript" src="ChartJs/ChartJs.js"></script>
<script type="text/javascript" src="ChartJs/plugin-label.js"></script>
<script type="text/javascript" src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>



<div class="box-body">
	<div id="CP_data_div" class="box box-danger">
		<!--<canvas  id="areaChart" style="height:250px"></canvas>
		<canvas  id="lineChart" style="height:250px"></canvas>
		<canvas  id="pieChart" style="height:250px"></canvas>-->
		<canvas  id="cp-myChart" style="height:250px"></canvas>
	</div>
</div>

<?php 
	$sql ="SELECT *,COUNT(pvn_id) FROM ref_province,ref_affiliation WHERE pvn_affiliation = aff_id GROUP BY aff_id";
	$province = selectSql($sql);
	$sql = "SELECT *,COUNT(cp_province) FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id GROUP BY aff_id ORDER BY aff_id ASC";
	$cityplan = selectSql($sql);
?>

<script>
 	Chart.defaults.font.size = 15;
	Chart.defaults.font.weight = '500';
	Chart.defaults.font.family = 'Kanit';
	

		var data = {		  
		  labels: ['ส่วนผังเมืองรวมที่ 1','ส่วนผังเมืองรวมที่  2','ส่วนผังเมืองรวมที่ 3','ส่วนผังเมืองรวมที่ 4'],
		  datasets: [{
			label: ['จำนวน  '],
			x:[1,2,3,4],
			data: ['<?php echo $cityplan[0]["COUNT(cp_province)"];?>','<?php echo $cityplan[1]["COUNT(cp_province)"];?>','<?php echo $cityplan[2]["COUNT(cp_province)"];?>','<?php echo $cityplan[3]["COUNT(cp_province)"];?>'],
			backgroundColor: ['rgb(255, 99, 132)','rgb(255,153,87)','rgb(255, 205, 86)','rgb(255, 33, 33)'],
			hoverBackgroundColor: ['rgb(255, 36, 82)','rgb(255, 125, 41)','rgb(255, 162, 0)','rgb(171, 5, 5)'],
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
				 showCityplan(chart.data.datasets[0].x[i]);
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
	var cp_myChart = new Chart(document.getElementById('cp-myChart'),config);	
	
	function showAffiliation(x){
		$.post('report/show_result.php',{
			stage_id :x ,
			event :'affiliation'
		},function(data){
			$('#mainshow').html(data);
		})
	}
</script>