<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>From Info Windows to a Database: Saving User-Added Form Data</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map" height="460px" width="600px"></div>
    <input type='button' value='Save' onclick='saveData()'/>
    <div id="message">Location saved</div>
    <script>
      var map;
      var marker;
      var infowindow;
      var messagewindow;

      function initMap() {
          
        var california = {lat: 37.4419, lng: -122.1419};
        map = new google.maps.Map(document.getElementById('map'), {
          center: california,
          zoom: 15
        });

        infowindow = new google.maps.InfoWindow({
          content: document.getElementById('message')
        });

//         messagewindow = new google.maps.InfoWindow({
//           content: document.getElementById('message')
//         });

     	// Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            marker = new google.maps.Marker({
                position: pos,
                map: map
            });

            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }

        google.maps.event.addListener(map, 'click', function(event) {

          marker = new google.maps.Marker({
            position: event.latLng,
            map: map
          });

//           google.maps.event.addListener(marker, 'click', function() {
//             //infowindow.open(map, marker);
//             var markerLat = marker.getPosition().lat();
//             var markerLng = marker.getPosition().lng();
//             console.log(markerLat + " " + markerLng);
//           });
        });
      }

      function saveData() {
    	var markerLat = marker.getPosition().lat();
        var markerLng = marker.getPosition().lng();
    	console.log(markerLat + " " + markerLng);
//         var name = escape(document.getElementById('name').value);
//         var address = escape(document.getElementById('address').value);
//         var type = document.getElementById('type').value;
//         var latlng = marker.getPosition();
//         var url = 'phpsqlinfo_addrow.php?name=' + name + '&address=' + address +
//                   '&type=' + type + '&lat=' + latlng.lat() + '&lng=' + latlng.lng();

//         downloadUrl(url, function(data, responseCode) {

//           if (responseCode == 200 && data.length <= 1) {
//             infowindow.close();
//             messagewindow.open(map, marker);
//           }
//         });
      }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request.responseText, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing () {
      }

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARGo6QU60StUz58XsOHjLs4Dg3UFllE4w&callback=initMap">
    </script>
  </body>
</html>