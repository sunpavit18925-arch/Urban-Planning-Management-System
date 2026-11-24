<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
?>
<div class="box-body">
	<div id="CP_data_div" class="box box-danger">
		<canvas id="pieChart" style="height:250px"></canvas>
	</div>
</div>
<?php 
	$sql = "SELECT *,COUNT(cp_province) FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id GROUP BY pvn_affiliation ORDER BY aff_id";
	$result = selectSql($sql);
?>

<script src="bower_components/Chart.js/Chart.js"></script>
<script>
  $(function() {
	var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
      {
        value    : '<?php echo $result[0]['COUNT(cp_province)'];?>',
        color    : '#f56954',
        highlight: '#FF1C0D',
        label    : '<?php echo $result[0]['aff_name'];?>'
      },
      {
        value    : '<?php echo $result[1]['COUNT(cp_province)'];?>',
        color    : '#00a65a',
        highlight: '#00B025',
        label    : '<?php echo $result[1]['aff_name'];?>'
      },
      {
        value    : '<?php echo $result[2]['COUNT(cp_province)'];?>',
        color    : '#f39c12',
        highlight: '#F5790C',
        label    : '<?php echo $result[2]['aff_name'];?>'
      },
      {
        value    : '<?php echo $result[3]['COUNT(cp_province)'];?>',
        color    : '#00c0ef',
        highlight: '#0080FF',
        label    : '<?php echo $result[3]['aff_name'];?>'
      }
    ]
    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
  })
  
</script>