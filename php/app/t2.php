<?php 
  include '../js/function_db.php';
  session_name('tutorial');
  session_start();
  unset($_SESSION['sql']);
  unset($_SESSION['sql1']);
  unset($_SESSION['sql2']);
	
	
  unset($_SESSION['aff_id']);
  unset($_SESSION['status_id']);
  unset($_SESSION['eff_id']);

  unset($_SESSION['stage_id']);
  unset($_SESSION['step_id']);
  unset($_SESSION['title']);
  unset($_SESSION['stauts']);
  unset($_SESSION['event']);
  //echo '1',$_SESSION['sql'];
  
  $sql ="SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 1 AND cp_stage = 1";
  $cp1 = selectSql($sql);
  
  $sql ="SELECT COUNT(*) FROM tb_cityplan WHERE cp_status = 2 AND cp_stage = 3";
  $cp2 = selectSql($sql);
  
  $sql = "SELECT COUNT(*) FROM tb_cityplan35, tb_cityplan_main WHERE cp35_current = 1 AND cp35_no = cp_no AND cp_owner = 1";
  $m35a = selectSql($sql);
  
  $sql = "SELECT COUNT(*) FROM tb_cityplan35, tb_cityplan_main WHERE cp35_current = 1 AND cp35_no = cp_no AND cp_owner = 2";
  $m35b = selectSql($sql);
?>
<style>
	
	@media print{
		button{
			display:none!important;
		}
		#page_1{
			page-break-after: always;
		}
		#stg_main{
			margin-top:40pt;
		}
		#step2_main{
			margin-top:-15px!important;
			margin-bottom:-10px!important;
		}
	}
	
	
	
</style>
<div id="page_1" class="row" style="background-color:transparent;margin-top:5px">
	<div class="col-lg-6">
		<div id="sta_main">
			<span style="font-size:26px;font-family:kanit">สถานภาพผังเมืองรวม</span>
			<button onclick="report_show('sta',0,'status')" id="sta_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="sta_data_i" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('sta',1,'status')" id="sta_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="sta_data_i" class="fa fa-list"></i></button>
			<!--
				<button onclick="report_close('sta')" id="sta_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
			<div id="sta_data_div" style="margin-top:3px " ></div>
		</div>
		
		<br id="sta_br">
	</div>
	
	<div class="col-lg-6">	
		<div id="stg_main">
			<span style="font-size:28px;font-family:kanit">ผังที่ดำเนินการในขั้นตอน</span>
			<button onclick="report_show('stg',0,'stage')" id="stg_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="stg_data_i" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('stg',1,'stage')" id="stg_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="stg_data_i" class="fa fa-list"></i></button>
			<!--
				<button onclick="report_close('stg')" id="stg_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
			<div id="stg_data_div" ></div>
		</div>
		
		<br id="stg_br">
	</div>
</div>
<div class="row" style="background-color:transparent;margin-top:5px; ">
		<div class="col-xs-12 col-md-6 r-line">		
			<div id="step_main">
				<span style="font-size:28px;font-family:kanit;">ผังที่อยู่ในขั้นตอนการวางและทำผัง จำนวน <?php echo $cp1[0]['COUNT(*)'];?> ผัง </span>	
					<button onclick="report_show('step',0,'step')" id="step_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="step_data_i" class="fa fa-pie-chart"></i></button>
					<button onclick="report_show('step',1,'step')" id="step_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="step_data_i" class="fa fa-list"></i></button>
				<div id="step_data_div" ></div>
			</div>			
			<br id="step_br">	
			<div id="opt_main" hidden >
				<span style="font-size:28px;font-family:kanit">ผู้ดำเนินการวางและจัดทำ</span>
				<!--<button onclick="report_show('opt',0,'operate')" id="opt_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="opt_data_i" class="fa fa-pie-chart"></i></button>
				<button onclick="report_show('opt',1,'operate')" id="opt_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="opt_data_i" class="fa fa-list"></i></button>-->
				<div id="opt_data_div" ></div>
			</div>		
			<br id="opt_br">		
		</div>
		
		<div class="col-xs-12 col-md-6" >
			<div id="step2_main">
				<span style="font-size:28px;font-family:kanit;">ผังที่อยู่ในขั้นตอนการปรับปรุงผัง จำนวน <?php echo $cp2[0]['COUNT(*)'];?> ผัง </span>
				<button onclick="report_show('step2',0,'step2')" id="step2_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="step2_data_i" class="fa fa-pie-chart"></i></button>
				<button onclick="report_show('step2',1,'step2')" id="step2_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="step2_data_i" class="fa fa-list"></i></button>
				<div id="step2_data_div" ></div>
			</div>
			<br id="step2_br">
			<div id="opt2_main"  hidden >
				<span style="font-size:28px;font-family:kanit">ผู้ดำเนินการวางและจัดทำ</span>
				<!--<button onclick="report_show('opt2',0,'operate2')" id="opt2_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="opt2_data_i" class="fa fa-pie-chart"></i></button>
				<button onclick="report_show('opt2',1,'operate2')" id="opt2_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="opt2_data_i" class="fa fa-list"></i></button>-->
				<div id="opt2_data_div" ></div>
			</div>		
			<br id="opt2_br">			
		</div>
	<div class="col-xs-12 col-md-6" hidden>
		<div id="budget_main">
			<span style="font-size:28px;font-family:kanit">ปีงบประมาณดำเนินการ</span>
			<!--<button onclick="report_show('budget',0,'budget')" id="budget_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="budget_data_i2" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('budget',1,'budget')" id="budget_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="budget_data_i3" class="fa fa-list"></i></button>-->
			<!--
				<button onclick="report_close('CP')" id="CP_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
			<div id="budget_data_div" ></div>
		</div>
		
		<br id="budget_br">
	</div>
	
	<div class="col-xs-12 col-md-6 r-line" hidden >
		<div id="m35a_main">
			<span style="font-size:28px;font-family:kanit">ผังที่อยู่ในขั้นตอน ม.35 จำนวน <?php echo $m35a[0]['COUNT(*)'];?> ผัง (กรมฯ)</span>
			<button onclick="report_show('m35a',0,'m35a')" id="m35a_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="m35a_data_i2" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('m35a',1,'m35a')" id="m35a_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="m35a_data_i3" class="fa fa-list"></i></button>
			<!--<button onclick="report_show('budget',0,'budget')" id="budget_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="budget_data_i2" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('budget',1,'budget')" id="budget_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="budget_data_i3" class="fa fa-list"></i></button>-->
			<!--
				<button onclick="report_close('CP')" id="CP_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
			<div id="m35a_data_div" ></div>
		</div>
	</div>
	
	<div class="col-xs-12 col-md-6" hidden>
		<div id="m35b_main">
			<span style="font-size:28px;font-family:kanit">ผังที่อยู่ในขั้นตอน ม.35 จำนวน <?php echo $m35b[0]['COUNT(*)'];?> ผัง (อปท.)</span>
			<button onclick="report_show('m35b',0,'m35b')" id="m35b_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="m35b_data_i2" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('m35b',1,'m35b')" id="m35b_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="m35b_data_i3" class="fa fa-list"></i></button>
			<!--<button onclick="report_show('budget',0,'budget')" id="budget_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="budget_data_i2" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('budget',1,'budget')" id="budget_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="budget_data_i3" class="fa fa-list"></i></button>-->
			<!--
				<button onclick="report_close('CP')" id="CP_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
			<div id="m35b_data_div" ></div>
		</div>
	</div>
	
	<div class="col-xs-12 col-md-12">
		<div id="CP_main">
			<span style="font-size:28px;font-family:kanit">ส่วนรับผิดชอบผัง</span>
			<button onclick="report_show('CP',0,'cityplan')" id="CP_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="CP_data_i2" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('CP',1,'cityplan')" id="CP_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="CP_data_i3" class="fa fa-list"></i></button>
			<!--
				<button onclick="report_close('CP')" id="CP_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
			<div id="CP_data_div" ></div>
		</div>
		<br id="CP_br" class="unprintable">	
	</div>
	<!--
	<div class="col-md-4">
		<div id="eec_main">
			<span style="font-size:28px;font-family:kanit"></span>
			<!--
			<button onclick="report_show('eec',0,'eec')" id="eec_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="eec_data_i" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('eec',1,'eec')" id="eec_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="eec_data_i" class="fa fa-list"></i></button>
			<!--
				<button onclick="report_close('eec')" id="eec_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
		<!--	<div id="eec_data_div" ></div>
		</div>

		<br id="eec_br">
	</div>
	-->
	
	
	<!--
	<div class="col-md-4">
		<div id="eff_main">
			<span style="font-size:28px;font-family:kanit">มีผลบังคับใช้</span>
			<button onclick="report_show('eff',0,'effective')" id="eff_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="eff_data_i" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('eff',1,'effective')" id="eff_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="eff_data_i" class="fa fa-list"></i></button>
			<!--
				<button onclick="report_close('stg')" id="stg_data_btn_close" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i class="fa fa-times"></i></button>
			-->
			<!--<div id="eff_data_div" ></div>
		</div>
		
		<br id="eff_br">
	</div>
	-->
	<!--
	<div class="col-md-6">	
		<div id="opt_main">
			<span style="font-size:28px;font-family:kanit">ผู้ดำเนินการ</span>
			<button onclick="report_show('opt',0,'operate')" id="opt_data_btn_chart" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="opt_data_i" class="fa fa-pie-chart"></i></button>
			<button onclick="report_show('opt',1,'operate')" id="opt_data_btn_list" class="pull-right btn btn-md btn-default" style="margin-top: 3px;margin-right:3px"><i id="opt_data_i" class="fa fa-list"></i></button>
			<div id="opt_data_div" ></div>
		</div>		
		<br id="opt_br">	
	</div>
	-->
</div>

<?php 
	$sql = "SELECT *,COUNT(cp_province) FROM tb_cityplan , ref_province , ref_affiliation WHERE cp_province = pvn_id AND pvn_affiliation = aff_id GROUP BY pvn_affiliation ORDER BY aff_id";
	$result = selectSql($sql);
?>
<script>
	report_show("CP",1,'cityplan');
	report_show("type",1,'type');
	report_show("step",0,'step');
	report_show("step2",0,'step2');
	report_show("eec",1,'eec');
	report_show("sta",0,'status');
	report_show("stg",0,'stage');
	report_show("eff",1,'effective');
	report_show("opt",1,'operate');
	report_show("opt2",1,'operate2');
	report_show("budget",1,'budget');
	report_show("m35a",0,'m35a');
	report_show("m35b",0,'m35b');
	function report_show(name,x,file){
		if(x == 1){
			var files = 'report/'+file+'-table.php';
			$('#'+name+'_data_div').empty();
			$('#'+name+'_data_div').load(files);
			$('#'+name+'_data_btn_list').hide();
			$('#'+name+'_br').hide();
			$('#'+name+'_data_btn_chart').show();
		}else{
			var files = 'report/'+file+'-chart.php';
			$.post(files,{},function(data){
				$('#'+name+'_data_div').html();	
				$('#'+name+'_data_btn_list').show();
				$('#'+name+'_br').hide();
				$('#'+name+'_data_btn_chart').hide();
				$('#'+name+'_data_div').html(data);
			})
		}
		//alert(files);
		
		
		/*if(x == 1){			
			$('#'+name+'_data_div').load(files+'-table.php');
			$('#'+name+'_data_btn_list').hide();
			$('#'+name+'_br').hide();
			$('#'+name+'_data_btn_chart').show();
		}
		else if(x == 0){			
			$('#'+name+'_data_div').load(files+'-chart.php');
			$('#'+name+'_data_btn_list').show();
			$('#'+name+'_br').show();			
			$('#'+name+'_data_btn_chart').hide();			
		}
		*/
	}
	/*
	function report_close(name){
		$('#'+name+'_main').hide();
	}*/
	
	/*
	$('#step1x_data_div').load("report/step-table.php")
	$('#step2x_data_div').load("report/step2-table.php");
	*/
</script>