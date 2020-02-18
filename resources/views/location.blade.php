@extends('layout')
@section('content')
<style>
	#map-canvas{
		width: 850px;
		height: 330px;
	}
	input:focus {
	    &::-webkit-input-placeholder {
	       color: transparent;
	       -webkit-transition: color 0.2s ease;
	       transition: color 0.2s ease;
	    }

	    &:-moz-placeholder { /* Firefox 18- */
	       color: transparent;
	       -webkit-transition: color 0.2s ease;
	       transition: color 0.2s ease;
	    }

	    &::-moz-placeholder {  /* Firefox 19+ */
	       color: transparent;
	       -webkit-transition: color 0.2s ease;
	       transition: color 0.2s ease;
	    }

	    &:-ms-input-placeholder {  
	       color: transparent;
	       -webkit-transition: color 0.2s ease;
	       transition: color 0.2s ease;
	    }
	}
}
</style>

<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyB6K1CFUQ1RwVJ-nyXxd6W0rfiIBe12Q&libraries=places"
  type="text/javascript"></script>

<div class="container">
	<div class="col-sm-4">
		<!--<h3>Location</h3>-->
		<div style="padding-top:10px"></div>
		<!--<form name="map_form"  method="post" action="{{url ('/vendor/add')}}">
		<form name="map_form"  method="post" action="http://localhost:8000/vendor/add">
			{{ csrf_field() }} -->
			<!--<div class="form-group">
				<label for="">Title</label>
				<input type="text" class="form-control input-sm" name="title">
			</div>-->

			<div class="form-group">
				<label for="">Map Location</label>
				<input type="text" id="searchmap" class="form-control input-sm" placeholder="ค้นหาตำแหน่งที่ตั้ง"/>
				<div style="padding-top:10px"></div>
				<div id="map-canvas"></div>
			</div>

			<div class="form-group">
				<label for="">Lat</label>
				<input type="text" class="form-control input-sm" name="lat" id="lat">
			</div>

			<div class="form-group">
				<label for="">Lng</label>
				<input type="text" class="form-control input-sm" name="lng" id="lng">
			</div>

			<!--<button class="btn btn-sm btn-danger">Save</button>
		</form>-->
	</div>

</div>

<script>


	var map = new google.maps.Map(document.getElementById('map-canvas'),{
		center:{
			lat: 13.7670658,
        	lng: 100.6425883
		},
		zoom:15
	});

	var marker = new google.maps.Marker({
		position: {
			lat: 13.7670658,
        	lng: 100.6425883
		},
		map: map,
		draggable: true
	});

	var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'));

	google.maps.event.addListener(searchBox,'places_changed',function(){

		var places = searchBox.getPlaces();
		var bounds = new google.maps.LatLngBounds();
		var i, place;

		for(i=0; place=places[i];i++){
  			bounds.extend(place.geometry.location);
  			marker.setPosition(place.geometry.location); //set marker position new...
  		}

  		map.fitBounds(bounds);
  		map.setZoom(15);

	});

	google.maps.event.addListener(marker,'position_changed',function(){

		var lat = marker.getPosition().lat();
		var lng = marker.getPosition().lng();

		$('#lat').val(lat);
		$('#lng').val(lng);

	});

	$(window).resize(function(){
	    if ($(window).width() >= 1){  
	        $(':input').removeAttr('placeholder');
	    }   
	});

</script>
@endsection