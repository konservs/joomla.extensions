var map;
var geocoder;
var marker;

$(document).ready(function(){
	window.console&&console.log('[Regoffices.office] The document is ready!');
	geocoder = new google.maps.Geocoder();
	var lat=parseFloat($('#formfieldmap_lat').val());
	var lng=parseFloat($('#formfieldmap_lng').val());
	if(isNaN(lat)){
		lat=48.35;
		}
	if(isNaN(lng)){
		lng=31.166667;
		}


	window.console&&console.log('[Regoffices.office] Creating map. lat='+lat+', lng='+lng+'.');
	map = new google.maps.Map(document.getElementById('formfieldmap'), {
		center: {lat: lat, lng: lng},
		zoom: 12
		});
        marker =new google.maps.Marker({
		position: new google.maps.LatLng(lat,lng),
		draggable:true,
		map:map
		});
        google.maps.event.addListener(map, "click", function(event) {
		document.getElementById("formfieldmap_lat").value = event.latLng.lat();
		document.getElementById("formfieldmap_lng").value = event.latLng.lng();
		marker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));
		});
        google.maps.event.addListener(marker, "dragend", function() {
		document.getElementById("formfieldmap_lat").value = this.getPosition().lat();
		document.getElementById("formfieldmap_lng").value = this.getPosition().lng();
		});
	
	});