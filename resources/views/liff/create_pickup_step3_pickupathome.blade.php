@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">ให้ Fastship ไปรับที่บ้าน</h3>
		<hr />
	</div>
	
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_pickup_step4') }}">
	
		<input type="hidden" name="latitude" id="map_lat" value="{{ $default['latitude'] }}" />
	    <input type="hidden" name="longitude" id="map_lng" value="{{ $default['longitude'] }}"  />
	    		
		<div class="row">
		
			<div class="col col-12">
				<label for="pickdate" class=" form-control-label">วันที่ให้เข้ารับ*</label>
    			<div class='input-group pickup_date' id='pickupdate'>
                    <input type='text' id="pickdate" class="form-control input-sm" name="pick_date"/>
                    <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                    </span>
                </div>
                <div id="pickdate_help" class="help text-danger small"></div>
            </div>

    		
			<div class="col col-12">
    			<label for="picktime" class=" form-control-label">ช่วงเวลาที่ให้เข้ารับ*</label>
    			<select id="picktime" name="pick_time" class="form-control">
    				<option value="slot0">สะดวกทุกเวลา (9.00 - 15.00 น.)</option>
    				<option value="slot1">ช่วงเช้า (9.00 - 12.00 น.)</option>
    				<option value="slot2">ช่วงบ่าย (12.01 - 15.00 น.)</option>
    			</select>
        		<div id="picktime_help" class="help text-danger small"></div>
    		</div>

    		<div class="col col-12">
    			<label for="searchmap" class=" form-control-label">ปักหมุดที่อยู่ของคุณ <span class="small text-secondary">ลากหมุดเพื่อเปลี่ยนตำแหน่ง</span></label>
            	<input type="text" id="searchmap" name="searchmap" class="form-control input-sm" placeholder="{!! FT::translate('placeholder.search_location') !!}"/>
            </div>

            <div class="col col-12" style="height: 300px;">
            	<div id="map" style="width: 350px;height: 300px;"></div>
            </div>
            
            <div class="col col-12">
    			<label for="firstname" class=" form-control-label">ชื่อผู้ติดต่อ*</label>
    			<input type="text" name="firstname" class="form-control" required />
        		<div id="firstname_help" class="help text-danger small"></div>
    		</div>
    		
    		<div class="col col-12">
    			<label for="telephone" class=" form-control-label">เบอร์ติดต่อ*</label>
    			<input type="number" name="telephone" class="form-control" required />
        		<div id="telephone_help" class="help text-danger small"></div>
    		</div>
    		
            <div class="col col-12">
    			<label for="address1" class=" form-control-label">ที่อยู่*</label>
    			<input type="text" name="address1" class="form-control" required />
        		<div id="address1_help" class="help text-danger small"></div>
    		</div>
    		
    		<div class="col col-12" style="margin-top: 10px;">
    			<input type="text" name="address2" class="form-control" />
        		<div id="address2_help" class="help text-danger small"></div>
    		</div>
    		
    		<div class="col col-12">
    			<label for="city" class=" form-control-label">เขต/อำเภอ*</label>
    			<input type="text" name="city" class="form-control" required />
        		<div id="city_help" class="help text-danger small"></div>
    		</div>

    		<div class="col col-12">
    			<label for="state" class=" form-control-label">จังหวัด*</label>
    			<select id="state" name="state" class="form-control" required onchange="loadPostal(this.value)" >
    			@foreach($states as $state)
    				<option value="{{ $state }}">{{ $state }}</option>
    			@endforeach
    			</select>
        		<div id="state_help" class="help text-danger small"></div>
        	</div>
    		
    		<div class="col col-12"  >
    			<label for="postcode" class=" form-control-label">รหัสไปรษณีย์*</label>
    			<select id="postal" name="postcode" class="form-control" required></select>
        		<div id="postal_help" class="help text-danger small"></div>
    		</div>
    		<!-- 
    		<div class="col col-12">
    			<label for="postcode" class=" form-control-label">รหัสไปรษณีย์*</label>
    			<input type="number" name="postcode" class="form-control" required />
        		<div id="postcode_help" class="help text-danger small"></div>
    		</div>
    		-->
    		
            <div class="col col-12">
    			<label for="memo" class=" form-control-label">ข้อความเพิ่มเติม</label>
    			<input type="text" name="memo" class="form-control" />
        		<div id="memo_help" class="help text-danger small"></div>
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
        	Fastship จะติดต่อก่อนเข้ารับพัสดุ คำอธิบายสั้นๆ ...
    	</div>
	</div>
	
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHHXqNKFCwLxraQmTxe6nkxraT3Okd32Y&libraries=places&region=TH" type="text/javascript"></script>
<script type="text/javascript">
<!--
$(window).on('load',function(){

	$('.pickup_date').datetimepicker({format: 'YYYY-MM-DD',minDate: new Date()});
	
	$("#create_form input[name=pick_date]").val("{{ old('pick_date',$default['pick_date']) }}");
	$("#create_form input[name=pick_time]").val("{{ old('pick_time',$default['pick_time']) }}");
	$("#create_form input[name=firstname]").val("{{ old('firstname',$default['firstname']) }}");
	$("#create_form input[name=telephone]").val("{{ old('telephone',$default['telephone']) }}");
	$("#create_form input[name=address1]").val("{{ old('address1',$default['address1']) }}");
	$("#create_form input[name=address2]").val("{{ old('address2',$default['address2']) }}");
	$("#create_form input[name=city]").val("{{ old('city',$default['city']) }}");
	$("#create_form select[name=state]").val("{{ old('state',$default['state']) }}");
	$("#create_form input[name=memo]").val("{{ old('memo',$default['memo']) }}");

	loadPostal("{{ old('state',$default['state']) }}");
	
});

var defaultLat = 13.9024959;
var defaultLng = 100.56231660000003;

var map = new google.maps.Map(document.getElementById('map'),{
	center:{
		lat: defaultLat,
    	lng: defaultLng
	},
    disableDefaultUI: false,
    clickableIcons: false,
    mapTypeControl: false,
    streetViewControl: false,
    zoom: 15
});

var marker = new google.maps.Marker({
	position: {
		lat: defaultLat,
    	lng: defaultLng
	},
	map: map,
	draggable: true
});

//Try HTML5 geolocation.
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var pos = {
      lat: position.coords.latitude,
      lng: position.coords.longitude
    };
    defaultLat = position.coords.latitude;
    defaultLng = position.coords.longitude;

    @if($default['latitude'] != "" && $default['longitude'] != "")
    	var pos = {
    		lat: {{ $default['latitude'] }},
    	    lng: {{ $default['longitude'] }}
    	};
    	defaultLat = {{ $default['latitude'] }};
    	defaultLng = {{ $default['longitude'] }};
    @else
    	var pos = {
    		lat: position.coords.latitude,
    	    lng: position.coords.longitude
    	};
    	defaultLat = position.coords.latitude;
    	defaultLng = position.coords.longitude;
    @endif
    
    $('#map_lat').val(defaultLat);
	$('#map_lng').val(defaultLng);

    map.setCenter(pos);
    marker.setPosition(pos);
    
  }, function() {
    handleLocationError(true, infoWindow, map.getCenter());
  });
} else {
  // Browser doesn't support Geolocation
  handleLocationError(false, infoWindow, map.getCenter());
}

// Create the search box and link it to the UI element.
var options = {
    componentRestrictions: {country: 'th'}
};

var searchBox = new google.maps.places.Autocomplete(document.getElementById('searchmap'), options);

searchBox.addListener('place_changed', function() {

	var bounds = new google.maps.LatLngBounds();

	var place = searchBox.getPlace();

    if (place.length == 0) {
        return;
    }

    if (!place.geometry) {
        console.log("No details available for input: '" + place.name + "'");
        return;
    }

    bounds.extend(place.geometry.location);
    marker.setPosition(place.geometry.location); //set marker position new...

		map.fitBounds(bounds);
		
		map.setZoom(15);

});

google.maps.event.addListener(marker,'position_changed',function(){

	var lat = marker.getPosition().lat();
	var lng = marker.getPosition().lng();

	$('#map_lat').val(lat);
	$('#map_lng').val(lng);

});

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
                          'Error: The Geolocation service failed.' :
                          'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}
function loadPostal(_state){

	$('#postal').empty();
	
	//call ajax
	$.ajax({
    	url: '/liff/ajax/get_postal',
        dataType: 'json',
        type: 'POST',
        data: {'state' : _state},
        success: function(data) {
            $.each(data, function(key, value) {
                $('#postal').append('<option value="'+ key +'">'+ key +'</option>');
            });
            $('#postal').val("{{ old('postcode',$default['postcode']) }}");
            //$("#create_form select[name=postcode]").val("{{ old('postcode',$default['postcode']) }}");
        }
    });

}
-->
</script>
@endsection