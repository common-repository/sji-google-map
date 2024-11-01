var map, marker, myLatLng, geocoder, infowindow, center;

function initialize() {
	myLatLng = {lat: 38.898748, lng: -77.037684};
	map = new google.maps.Map(document.getElementById('map-wrap'), { 
		zoom: 4, 
		center: myLatLng, 
		mapTypeId: google.maps.MapTypeId.ROADMAP 
	});
	geocoder = new google.maps.Geocoder();
  	infowindow = new google.maps.InfoWindow({map: map});

	jQuery.ajax({
		type: 'POST',
		url : '/wp-admin/admin-ajax.php',
		data: {
			action: 'get_coordinates'
		},
		success: function(data){
			var coordinates = JSON.parse(data);
			if(coordinates.lattitude != '' && coordinates.longitude != '') {
				myLatLng = {lat: parseFloat(coordinates.lattitude), lng: parseFloat(coordinates.longitude)};
			}

			if (geocoder) {
                  geocoder.geocode({ 'latLng': myLatLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                    	placeMarker(myLatLng);
                      	infowindow.setPosition(myLatLng);
                      	infowindow.setContent(results[0].formatted_address);
                      	infowindow.setOptions({pixelOffset: new google.maps.Size(0,-35)});
                    } else {
                    	infowindow.setContent('Location Address Undefined');
                      	infowindow.setOptions({pixelOffset: new google.maps.Size(0,-35)});
                    }
                });
            }
		}
	});

	google.maps.event.addDomListener(map, 'idle', function() {
	  	calculateCenter();
	});
	google.maps.event.addDomListener(window, 'resize', function() {
		google.maps.event.trigger(map, "resize");
	  	map.setCenter(center);
	});

}

function calculateCenter() {
  center = map.getCenter();
}

function placeMarker(location) {
  if ( marker ) {
    marker.setPosition(location);
  } else {
    marker = new google.maps.Marker({
        position: location, 
        map: map
    });
	map.setCenter(marker.position);
	marker.setMap(map);
	marker.addListener('click', function() {
    infowindow.setOptions({pixelOffset: new google.maps.Size(0, 0)});
		infowindow.open(map, marker);
	});
  }
}

jQuery(document).ready(function(){
	initialize();
});