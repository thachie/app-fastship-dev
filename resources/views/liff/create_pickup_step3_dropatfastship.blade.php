@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">นำพัสดุไป Dropoff ที่ Fastship</h3>
		<hr />
	</div>
	
	
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_pickup_step4') }}">
	
		<div class="row">

			<div class="col col-12">
				<div id="map" style="height:300px;width:350px;"></div>
			</div>
			
		</div>
		
        <div class="row">
        	<div class="col col-12 text-center">
        		<button type="submit" id="submit" class="btn btn-primary btn-block btn-lg large border-0" formaction="/liff/create_pickup_step4">ต่อไป</button>
        		<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;margin-top: 10px;" onclick="history.back();">ย้อนกลับ</button>
        	</div>
        </div>
    
    </form>
    
    <hr />
    
    <div class="row">
        <div class="col col-12 text-center text-secondary">
        	{!! FT::translate('modal.drop_fastship.content') !!}
    	</div>
	</div>
    
	
</div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARGo6QU60StUz58XsOHjLs4Dg3UFllE4w&callback=initMap"></script>
<script type="text/javascript">
<!--
$(window).on('load',function(){
	initMap();
});
var map;
var marker;
var infowindow;

function initMap() {

    infowindow = new google.maps.InfoWindow({
      content: document.getElementById('message')
    });


    var defaultLat = 13.9024959;
    var defaultLng = 100.56231660000003;
    
    map = new google.maps.Map(document.getElementById('map'), {
      center:{
    	lat: defaultLat,
        lng: defaultLng
      },
      draggable: true,
      disableDefaultUI: false,
      clickableIcons: true,
      fullscreenControl: false,
      keyboardShortcuts: false,
      mapTypeControl: false,
      scaleControl: true,
      scrollwheel: false,
      streetViewControl: false,
      zoomControl: true,
      zoom: 15
    });
    marker = new google.maps.Marker({
        position: {
        	lat: defaultLat,
            lng: defaultLng
        },
        map: map
    });
  }
-->
</script>
@endsection