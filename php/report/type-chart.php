<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<div class="box-body">
	<div id="CP_data_div" class="box box-danger">
	<!--
		<canvas  id="areaChart" style="height:250px"></canvas>
		<canvas  id="lineChart" style="height:250px"></canvas>
		<canvas  id="pieChart" style="height:250px"></canvas>
	-->
		<canvas  id="type-barChart" style="height:250px"></canvas>
	
	</div>
</div>

<?php 
	$sql ="SELECT type_id , type_name , COUNT(*) FROM tb_cityplan , ref_type WHERE cp_type = type_id AND type_id = 1 GROUP BY type_id";
	$type1 = selectSql($sql);
	$sql ="SELECT type_id , type_name , COUNT(*) FROM tb_cityplan , ref_type WHERE cp_type = type_id AND type_id = 2 GROUP BY type_id";
	$type1 = selectSql($sql);
	$sql = "SELECT *,COUNT(cp_province) FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id GROUP BY aff_id";
	$cityplan = selectSql($sql);
?>
<script src="bower_components/Chart.js/Chart.js"></script>
<script>
 $(function () {
	var ChartData = {
      labels  : ['<?php echo $type2[0]["type_name"]; ?>','<?php echo $type1[0]["type_name"]; ?>',],
      datasets: [
	  {
          label               : 'ประเภทผังเมือง',
          fillColor           : 'rgba(210, 214, 222, 0.7)',
          strokeColor         : 'rgba(210, 214, 222, 0.7)',
          pointColor          : 'rgba(210, 214, 222, 0.7)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [<?php if($type2[0]['COUNT(*)'] == NULL){echo "0";}else{echo $type2[0]['COUNT(*)'];}?>,<?php if($type1[0]['COUNT(*)'] == NULL){echo "0";}else{echo $type1[0]['COUNT(*)'];}?>,]
        },
	  /*{
          label               : 'cityplan',
          fillColor           : 'rgba(60,141,188,1)',
          strokeColor         : 'rgba(60,141,188,1)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [<?php echo $cityplan[0]['COUNT(cp_province)'];?>,<?php echo $cityplan[1]['COUNT(cp_province)'];?>,<?php echo $cityplan[2]['COUNT(cp_province)'];?>,<?php echo $cityplan[3]['COUNT(cp_province)'];?>]
        }
       */
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
        value    : 700,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Chrome'
      },
      {
        value    : 500,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'IE'
      },
      {
        value    : 400,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'FireFox'
      },
      {
        value    : 600,
        color    : '#00c0ef',
        highlight: '#00c0ef',
        label    : 'Safari'
      },
      {
        value    : 300,
        color    : '#3c8dbc',
        highlight: '#3c8dbc',
        label    : 'Opera'
      },
      {
        value    : 100,
        color    : '#d2d6de',
        highlight: '#d2d6de',
        label    : 'Navigator'
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

	
    var barChartCanvas                   = $('#type-barChart').get(0).getContext('2d')
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