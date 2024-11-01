var map, marker, geocoder, elevator, infowindow, center;

function initialize() {
  var lat = jQuery('input[name="sji_pro_google_map_settings[lattitude]"]').attr('value');
  var lng = jQuery('input[name="sji_pro_google_map_settings[longitude]"]').attr('value');
  var myLatlng = new google.maps.LatLng(lat, lng);
  var myOptions = {
    zoom: 4,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById("map"), myOptions);
  geocoder = new google.maps.Geocoder();
  elevator = new google.maps.ElevationService;
  infowindow = new google.maps.InfoWindow({map: map});

  displayLocationElevation(myLatlng, elevator, infowindow);
  google.maps.event.addListener(map, 'click', function(event) {
    displayLocationElevation(event.latLng, elevator, infowindow);
  });

  google.maps.event.addDomListener(map, 'idle', function() {
      calculateCenter();
  });
  google.maps.event.addDomListener(window, 'resize', function() {
    google.maps.event.trigger(map, "resize");
      map.setCenter(center);
  });
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

function calculateCenter() {
  center = map.getCenter();
}

function displayLocationElevation(location, elevator, infowindow) {
        // Initiate the location request
        elevator.getElevationForLocations({
          'locations': [location]
        }, function(results, status) {
          infowindow.setPosition(location);
          if (status === 'OK') {
            // Retrieve the first result
            if (results[0]) {
              // Open the infowindow indicating the elevation at the clicked position.
              if(results[0].elevation > 0) {
                if (geocoder) {
                  geocoder.geocode({ 'latLng': location}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                      placeMarker(location);
                      infowindow.setPosition(location);
                      infowindow.setContent(results[0].formatted_address);
                      infowindow.setOptions({pixelOffset: new google.maps.Size(0,-35)});
                    } else {
                      infowindow.setContent('Location Set');
                      infowindow.setOptions({pixelOffset: new google.maps.Size(0,-35)});
                    }
                  });
                }
                jQuery('input[name="sji_pro_google_map_settings[lattitude]"]').attr('value', location.lat());
                jQuery('input[name="sji_pro_google_map_settings[longitude]"]').attr('value', location.lng());
              } else {
                infowindow.setContent('The elevation at this point <br>is ' +
                    results[0].elevation + ' meters. This cannot be marked as your location');
              }
            } else {
              infowindow.setContent('No results found');
            }
          } else {
            infowindow.setContent('Elevation service failed due to: ' + status);
          }
        });
      }

jQuery(document).ready(function(){
	initialize();
  jQuery('#submit').on('click', function(){
    initialize();
  });

  // Checkbox default API
  jQuery('#default_api_key').on('change', function(){
  if(jQuery(this).is(':checked'))
    jQuery('#api').hide();
  else
    jQuery('#api').show();
  })
});