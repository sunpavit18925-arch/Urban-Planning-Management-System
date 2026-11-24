<?php 
	include '../js/function_db.php';
	session_name('tutorial');
	session_start();
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_effective = 0";
	$s1e0 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_effective = 1";
	$s2e1 = selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_effective = 2";
	$s2e2 = selectSql($sql);
	
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 0 ";
	$undef_sta = selectSql($sql);
	/*
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_eec = 'N'";
	$x= selectSql($sql);
	$sql = "SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_eec = 'Y'";
	$y = selectSql($sql);*/
	$text = "ผังที่อยู่ในขั้นตอนดำเนินการ";
	
?>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<script type="text/javascript" src="ChartJs/ChartJs.js"></script>
<script type="text/javascript" src="ChartJs/plugin-label.js"></script>
<script type="text/javascript" src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>



<div>
	<div id="sta_data_div" class="box box-info" style="padding-top:15px;display:inline-block;border-width:7.5px;border-top-color:gray">
		<canvas id="myChart"></canvas>
	</div>
</div>

<script>
	Chart.defaults.font.size = 15;
	Chart.defaults.font.weight = '500';
	Chart.defaults.font.family = 'Kanit';
	<?php 
		if($undef_sta[0]["COUNT(*)"] > 0){ ?>
		var data = {		  
		  labels: [
			['ผังที่มีผลบังคับใช้'],['ผังที่สิ้นสุดบังคับใช้'],['ผังเปิดพื้นที่ใหม่'],['ไม่ระบุสถานะ']
			/*['ผังที่อยู่ในขั้นตอนดำเนินการ'],
			['ผังที่ประกาศบังคับใช้'],
			['ผังที่สิ้นสุดการบังคับใช้']*/
		  ],
		  datasets: [{
			label: ' จำนวน ',
			x :[2,2,1,0],
			y :[1,2,0,0],
			data: ['<?php echo $s2e1[0]["COUNT(*)"];?>','<?php echo $s2e2[0]["COUNT(*)"];?>','<?php echo $s1e0[0]["COUNT(*)"];?>','<?php echo $undef_sta[0]["COUNT(*)"];?>'],
			backgroundColor: [ 'rgb(93, 187, 72)', 'rgb(176, 176, 176)','rgb(255, 99, 132)','rgb(255, 33, 33)' ],
			hoverBackgroundColor: ['rgb(112, 226, 87)','rgb(115, 115, 115)','rgb(255, 36, 82)','rgb(171, 5, 5)' ],
			hoverOffset: 4,
			//cutout :'50%'
		  },
		  /*{
			labels: [' จำนวน ','11'],
			x :[2,2,1],
			y :[1,2,0],
			data: ['100','50','200'],
			backgroundColor: ['red', 'green', 'blue'],
			hoverBackgroundColor: ['rgb(14, 232, 32)','rgb(255, 36, 82)','rgb(0, 121, 250)'],
			hoverOffset: 4,
			//cutout :'50%'
		  }*/
		 ]
		};
	<?php }
		else{ ?>
		var data = {		  
		  labels: [
			['ผังที่มีผลบังคับใช้'],['ผังที่สิ้นสุดบังคับใช้'],['ผังเปิดพื้นที่ใหม่']
			/*['ผังที่อยู่ในขั้นตอนดำเนินการ'],
			['ผังที่ประกาศบังคับใช้'],
			['ผังที่สิ้นสุดการบังคับใช้']*/
		  ],
		  datasets: [{
			label: ' จำนวน ',
			x :[2,2,1,0],
			y :[1,2,0,0],
			data: ['<?php echo $s2e1[0]["COUNT(*)"];?>','<?php echo $s2e2[0]["COUNT(*)"];?>','<?php echo $s1e0[0]["COUNT(*)"];?>'],
			backgroundColor: [ 'rgb(93, 187, 72)', 'rgb(176, 176, 176)','rgb(255, 99, 132)'],
			hoverBackgroundColor: ['rgb(112, 226, 87)','rgb(115, 115, 115)','rgb(255, 36, 82)'],
			hoverOffset: 4,
			//cutout :'50%'
		  },
		  /*{
			labels: [' จำนวน ','11'],
			x :[2,2,1],
			y :[1,2,0],
			data: ['100','50','200'],
			backgroundColor: ['red', 'green', 'blue'],
			hoverBackgroundColor: ['rgb(14, 232, 32)','rgb(255, 36, 82)','rgb(0, 121, 250)'],
			hoverOffset: 4,
			//cutout :'50%'
		  }*/
		 ]
		};
	<?php } ?>
		
		var hoverLabel = {
			id : 'hoverLabel',
			afterDraw(chart, args, options){
				var { ctx, chartArea:{ left, right, top, bottom, width, height}} = chart;
				ctx.save();
				
				console.log(height),
				console.log(top),
				
				ctx.font = 'bolder 60px Arial',
				ctx.fillStyle = 'blue',
				ctx.textAlign = 'center',
				ctx.fillText('609', width / 2, height / 2)
				
			}
		}		
		
		var config = {
		  type: 'bar',
		  data,
		  options: {			  
			responsive:true,
			scales:{
				y: {
				  title: {
					display: true,
					text: 'จำนวน'
				  }
				},
				x: {
				  title: {
					display: false,
					text: 'สถานภาพผัง'
					
				  }
				}
			},
			elements: {
				center: {
					  text: 'Red is 2/3 the total numbers',
					  color: '#FF6384', // Default is #000000
					  fontStyle: 'Arial', // Default is Arial
					  sidePadding: 20, // Default is 20 (as a percentage)
					  minFontSize: 20, // Default is 20 (in px), set to false and text will not wrap.
					  lineHeight: 25 // Default is 25 (in px), used for when text wraps
				}
			},
			onClick: (event, elements, chart) => {
			  if (elements[0]) {            
				 const i = elements[0].index;
				 showStatus(chart.data.datasets[0].x[i],chart.data.datasets[0].y[i]);
			  }
			},
			plugins : {
				legend: {
					display: false,
					position: "top",
					align: "start"
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
	var myChart = new Chart(document.getElementById('myChart'),config,hoverLabel);	
	

	
	
	function showStatus(x,y){
		$.post('report/show_result.php',{
			status_id :x ,
			eff_id : y,
			event :'status'
		},function(data){
			$('#mainshow').html(data);
		})
	}
</script>
 