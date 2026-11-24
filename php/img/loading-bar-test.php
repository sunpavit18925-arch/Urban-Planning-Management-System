<script src="js/jquery-3.5.1.min.js" charset="utf-8"></script>
<div id="">
	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:transparent;display:block;" width="464px" height="464px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
	<defs>
	  <clipPath id="progress-5el61z61xgu-cp" x="0" y="0" width="100" height="100">
		<rect x="0" y="0" width="0" height="100">
		  <animate attributeName="width" repeatCount="indefinite" dur="3.0303030303030303s" values="0;100;100" keyTimes="0;0.5;1"></animate>
		  <animate attributeName="x" repeatCount="indefinite" dur="3.0303030303030303s" values="0;0;100" keyTimes="0;0.5;1"></animate>
		</rect>
	  </clipPath>
	</defs>
	<path fill="none" stroke="#000000" stroke-width="2.79" d="M13.000000000000004 41.894999999999996L86.99999999999999 41.894999999999996A8.105 8.105 0 0 1 95.10499999999999 50L95.10499999999999 50A8.105 8.105 0 0 1 86.99999999999999 58.105000000000004L13.000000000000004 58.105000000000004A8.105 8.105 0 0 1 4.895000000000003 50L4.895000000000003 50A8.105 8.105 0 0 1 13.000000000000004 41.894999999999996 Z"></path>
	<path fill="#00c0ff" clip-path="url(#progress-5el61z61xgu-cp)" d="M13.000000000000004 45.989999999999995L86.99999999999999 45.989999999999995A4.01 4.01 0 0 1 91.00999999999999 49.99999999999999L91.00999999999999 50.00000000000001A4.01 4.01 0 0 1 86.99999999999999 54.010000000000005L13.000000000000004 54.010000000000005A4.01 4.01 0 0 1 8.990000000000004 50.00000000000001L8.990000000000004 49.99999999999999A4.01 4.01 0 0 1 13.000000000000004 45.989999999999995 Z"></path>
	</svg>
</div>

<div style="display:flex;    flex-direction: column;">
	<div>
		Border color : <input id="Loading-border" type="color" value="#000000" >
	</div>
	<div>
		Stroke width : <input id="Stroke-width" type="range"  step="0.1" min="0" max="10" value="2.79" >
	</div>
	<div>
		Loading color : <input id="Loading-bar" type="color" value="#00c0ff" >
	</div>
</div>
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script>
	$("#Loading-border").change(function(){
		$('path:first').attr('stroke',$("#Loading-border").val());
	});
	
	$("#Stroke-width").change(function(){
		$('path:first').attr('stroke-width',$("#Stroke-width").val());
	});
	$("#Loading-bar").change(function(){
		$('path:last').attr('fill',$("#Loading-bar").val());
	});
</script>