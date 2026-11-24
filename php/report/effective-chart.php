<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<div class="box-body">
	<div id="sta_data_div" class="box box-danger">
	<!--
		<canvas  id="areaChart" style="height:250px"></canvas>
		<canvas  id="lineChart" style="height:250px"></canvas>
	-->
	<!--
		<canvas  id="pieChart" style="height:250px"></canvas>
	-->
	
		<canvas  id="eff-barChart" style="height:250px"></canvas>
	
	</div>
</div>

<?php 
	$sql = "SELECT COUNT(cp_effective) FROM tb_cityplan WHERE cp_effective = 1";
	$eff1 = selectSql($sql);
	$sql = "SELECT COUNT(cp_effective) FROM tb_cityplan WHERE cp_effective = 2";
	$eff2 = selectSql($sql);

	$sql = "SELECT COUNT(cp_status) FROM tb_cityplan WHERE cp_status = 1 OR cp_status = 2";
	$result = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status IS NULL ";
	$undef = selectSql($sql);
?>
<script src="bower_components/Chart.js/Chart.js"></script>
<script>
 $(function () {
	var ChartData = {
      labels  : ['มีอายุ','หมดอายุ'],
      datasets: [
	  {
          label               : 'มีผลบังคับใช้',
          fillColor           : 'rgba(210, 214, 222, 0.7)',
          strokeColor         : 'rgba(210, 214, 222, 0.7)',
          pointColor          : 'rgba(210, 214, 222, 0.7)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : ['<?php echo $eff1[0]["COUNT(cp_effective)"];?>','<?php echo $eff2[0]["COUNT(cp_effective)"];?>']
        },
	 /* {
          label               : 'cityplan',
          fillColor           : 'rgba(60,141,188,1)',
          strokeColor         : 'rgba(60,141,188,1)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [<?php echo $cityplan[0]['COUNT(cp_province)'];?>,<?php echo $cityplan[1]['COUNT(cp_province)'];?>,<?php echo $cityplan[2]['COUNT(cp_province)'];?>,<?php echo $cityplan[3]['COUNT(cp_province)'];?>]
        }*/
        
      ]
    }

	
    /*
	var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
    var areaChart       = new Chart(areaChartCanvas)
    var areaChartData = ChartData   
    var areaChartOptions = {
		showScale               : true,
		scaleShowGridLines      : false,
		scaleGridLineColor      : 'rgba(0,0,0,.05)',
		scaleGridLineWidth      : 1,
		scaleShowHorizontalLines: true,
		scaleShowVerticalLines  : true,
		bezierCurve             : true,
		bezierCurveTension      : 0.3,
		pointDotRadius          : 4,
		pointDotStrokeWidth     : 1,
		pointHitDetectionRadius : 20,
		datasetStroke           : true,
		datasetStrokeWidth      : 2,
		datasetFill             : true,
		legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
		maintainAspectRatio     : true,
		responsive              : true
    }
    areaChart.Line(areaChartData, areaChartOptions

    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
    var lineChart                = new Chart(lineChartCanvas)
    var lineChartOptions         = areaChartOptions
    lineChartOptions.datasetFill = false
    lineChart.Line(areaChartData, lineChartOptions)
	*/
	
    /*
	var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
      {
        value    : '<?php echo $sta1[0]["COUNT(cp_status)"]; ?>',
        color    : '#f56954',
        highlight: '#f56954',
        label    : 	'ดำเนินการ'
      },
      {
        value    : '<?php echo $sta2[0]["COUNT(cp_status)"]; ?>',
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'ประกาศบังคับใช้'
      }
    ]
    var pieOptions     = {
		segmentShowStroke    : true,
		segmentStrokeColor   : '#fff',
		segmentStrokeWidth   : 2,
		percentageInnerCutout: 50, // This is 0 for Pie charts
		animationSteps       : 100,
		animationEasing      : 'easeOutBounce',
		animateRotate        : true,
		animateScale         : false,
		responsive           : true,     
		maintainAspectRatio  : true,
		legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    pieChart.Doughnut(PieData, pieOptions)
	*/
	
    var barChartCanvas                   = $('#eff-barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = ChartData
    barChartData.datasets[0].fillColor   = '#00a65a'
    barChartData.datasets[0].strokeColor = '#00a65a'
    barChartData.datasets[0].pointColor  = '#00a65a'
    var barChartOptions                  = {      
      scaleBeginAtZero        : true,      
      scaleShowGridLines      : true,      
      scaleGridLineColor      : 'rgba(0,0,0,.05)',      
      scaleGridLineWidth      : 1,      
      scaleShowHorizontalLines: true,      
      scaleShowVerticalLines  : true,      
      barShowStroke           : true,      
      barStrokeWidth          : 2,      
      barValueSpacing         : 5,      
      barDatasetSpacing       : 1,      
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',      
      responsive              : true,
      maintainAspectRatio     : true
    }
    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
	
  })
</script>