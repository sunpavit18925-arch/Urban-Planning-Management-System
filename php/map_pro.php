<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script type="text/javascript" src="js/provinces.js"></script>
<div id="framap" style="background-color:#333;border-radius:16px;padding:4px;margin-top:auto;margin-bottom:auto;">
	<div style="height:95%;border-radius:16px" id="map"></div>
</div>
	<div id="subshowX"></div>
<button hidden id="btnLayer" onclick="changeLayer()" style="position:relative;margin:0px auto">Layer</button>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script>
console.log(geoJson);

var map = L.map('map').setView([13.727223580320391, 100.51905745407568],6);
L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
	maxZoom: 20,
	minZoom: 3,
	attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
	 
}).addTo(map);

/*
var map = L.map('map').setView([13.727223580320391, 100.51905745407568],6);
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
	maxZoom: 20,
	minZoom: 3,
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
}).addTo(map);*/
/*
var popup = L.popup();

function onMapClick(e) {
	var text = e.latlng.toString();
	//last = text.length;
	//text = text.substring(6);
	//text = "LatLng"+text;
	var marker = L.marker(e.latlng).addTo(map);		
}
map.on('click', onMapClick);*/
/*
function changeLayer(){
	L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
		maxZoom: 20,
		minZoom: 3,
		attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
	}).addTo(map);
}

var theMarker = {};
map.on('click',function(e){
    lat = e.latlng.lat;
    lon = e.latlng.lng;

    console.log("You clicked the map at LAT: "+ lat+" and LONG: "+lon );
        //Clear existing marker, 

        if (theMarker != undefined) {
              map.removeLayer(theMarker);
        };

    //Add a marker to show where you clicked.
     theMarker = L.marker([lat,lon]).addTo(map);  
});
*/

/*
var layer = L.marker([13.727223580320391, 100.51905745407568]).addTo(map);
marker.bindPopup("Hello world!<br>I am a popup1.", { offset: new L.Point(-1, -41) }).openPopup();
*/

//var popup = L.popup().setLatLng([13.727223580320391, 100.51905745407568]).setContent('<p>Hello world!<br />This is a nice popup.</p>').openOn(map);
	
var geojson = [geoJson];

L.geoJSON(geojson, {
    style: function(feature){
		if(feature.properties.pro_code=='10'){
			//alert(feature.properties.pro_code);
			return {weight: 1,color: "#59A4FF",fillColor:"#59A4FF",fillOpacity:'0.5',className:feature.properties.pro_th};
		}else{
			return {weight: 2,color: "#59A4FF",fillColor:"#ff0000",fillOpacity:'0.2',className:feature.properties.pro_th};
			//return {weight: 2,color: "#59A4FF",dashArray: '10, 10', dashOffset: '10',fillColor:"#ff0000",fillOpacity:'0.2',className:feature.properties.pro_th};
		}
	},
}).addTo(map);


$("path").on( "click", function(e) {
	var province = $(this).attr('id');
	//alert(province); 
	//$('#mapshow').html(province)
	mapProvince(province)
});

var map = L.map('map', {
    renderer: L.svg()
}).addTo(map);
text = '<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.css?<?php echo date('".Ydmhis."')?>">'+
		'<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.css?<?php echo date('".Ydmhis."')?>">'+
		'<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">'+
		'<link rel="stylesheet" href="dist/style.css?<?php echo date('".Ydmhis."')?>">'+
		'<link rel="stylesheet" href="dist/skin-green-light.css?<?php echo date('".Ydmhis."')?>">'+
		'<link rel="stylesheet" href="bower_components/morris.js/morris.css">'+
		'<link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">'+
		'<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">'+
		'<link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">'+
		'<link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">'+
		'<link rel="stylesheet" href="js/jquery.datetimepicker.css">'+
		'<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">'+
		'<link rel="preconnect" href="https://fonts.googleapis.com">'+
		'<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'+
		'<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">'+
		'<style></style>';
	//console.log(text);
function mapProvince(x){
	if(confirm('ต้องการแสดงผังในจังหวัดนี้นี้ใช่หรือไม่ ?')){
		$.post('map/map_province.php',{
			pvn_name : x
		},function(data){
			$('head').html(text);
			$('style').load('map/style.css');
			$('#subshowX').html(data);
			$('#framap').attr('style','display:none');
			//$('body').removeAttr('background-color')
		});
	}
}

</script>