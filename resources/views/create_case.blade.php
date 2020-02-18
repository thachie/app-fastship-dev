@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h2>Create Case</h2>
		</div>
	</div>
	<div class="row">
	    <div class="col-md-8 col-md-offset-2">
	    	<form id="case_form" name="case_form" class="form-horizontal" method="post" action="{{url ('/case/create')}}">
	    		
	    		{{ csrf_field() }}

			    <div class="panel panel-primary">
					<div class="panel-heading">Create Case</div>
			        <div class="panel-body">

	                	<div class="col-md-12">
		                    <label for="subject" class="col-12 control-label">หัวข้อ</label>
		                    <input type="text" class="form-control required" name="subject" id="subject" required value="{{ old('subject','') }}" maxlength="100" />
		                </div>
		                <div class="col-md-6">
	                   		<label for="priority" class="col-12 control-label ">ระดับความสำคัญ</label>
	                    	<select name="priority" id="priority" class="form-control required" required>
	                    		<option value="Low">ควรแก้ไข</option>
	                    		<option value="Medium">ปานกลาง</option>
	                    		<option value="High">เร่งด่วน</option>
	                    	</select>
	                    </div>
		                <div class="col-md-6">
	                   		<label for="ship_id" class="col-12 control-label">หมายเลขพัสดุ</label>
	                    	<select name="ship_id" id="ship_id" class="form-control">
	                    	<option value="">-</option>
	                    	@foreach($shipments as $shipment)
	                    	<option value="{{ $shipment['ZohoSalesId'] }}">{{ $shipment['ID'] }}</option>
	                    	@endforeach
	                    	</select>
	                    </div>
	                    
	                    <div class="clearfix"></div>

	                    <div class="col-md-12">
	                        <label for="category" class="col-12 control-label">ประเภท Case</label>
	                        <select name="category" class="form-control required" required>
	                    		<option value="1. Cannot Contact Receiver - ติดต่อผู้รับไม่ได้">ผู้รับไม่ได้รับของ</option>
	                    		<option value="2. Docs - ขอเอกสารเพิ่มเติม">เอกสารที่ใช้ประกอบการส่ง</option>
	                    		<option>3. Invoice - แก้ invoice</option>
	                    		<option>4. Lost - ของสูญหาย</option>
	                    		<option>5. Damage - ของเสียหาย</option>
	                    		<option>6. Taxes and Duties - ภาษี</option>
	                    		<option>0. Others or Unknown - อื่นๆ</option>
	                    	</select>
	                    </div>
	                    <div class="clearfix"></div>
	                    
	                    <div class="col-md-12">
		                    <label for="detail" class="col-12 control-label">รายละเอียด</label>
		                    <textarea class="form-control required" rows="5" name="detail" id="detail" required>{{ old('detail','') }}</textarea>
		                </div>
		                <div class="clearfix"></div>
		                <br />

		                <div class="text-center"><button type="submit" name="submit" class="btn btn-lg btn-primary">{!! FT::translate('button.confirm') !!}</button></div>
		            
		            </div>
				</div>
			</form> 
	    </div>
	</div>
</div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHHXqNKFCwLxraQmTxe6nkxraT3Okd32Y&libraries=places&region=TH"
  type="text/javascript"></script>
	<script type="text/javascript">

	<?php if($customer_data['latitude'] == ""): ?>
	var defaultLat = 13.9024959;
	var defaultLng = 100.56231660000003;
	<?php else: ?>
	var defaultLat = <?php echo $customer_data['latitude']; ?>;
	var defaultLng = <?php echo $customer_data['longitude']; ?>;
	<?php endif; ?>
	
	var map = new google.maps.Map(document.getElementById('map'),{
		center:{
			lat: defaultLat,
        	lng: defaultLng
		},
        disableDefaultUI: false,
        clickableIcons: false,
        mapTypeControl: false,
        streetViewControl: false,
        <?php if($customer_data['latitude'] == ""): ?>
        zoom: 10
        <?php else: ?>
        zoom: 15
        <?php endif; ?>
	});
	var marker = new google.maps.Marker({
		position: {
			lat: defaultLat,
        	lng: defaultLng
		},
		map: map,
		draggable: true
	});

	// Create the search box and link it to the UI element.
    var options = {
        componentRestrictions: {country: 'th'}
    };
    
	//var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'),options);
	var searchBox = new google.maps.places.Autocomplete(document.getElementById('searchmap'), options);
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(document.getElementById('searchmap'));

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
    </script>

<link rel="stylesheet" href="./css/jquery.Thailand.min.css" />
<script type="text/javascript" src="./js/JQL.min.js"></script>
<script type="text/javascript" src="./js/typeahead.bundle.js"></script>
<script type="text/javascript" src="./js/jquery.Thailand.min.js"></script>

<script type="text/javascript">
	$.Thailand({
		$district: $('#editadd [name="address2"]'),
		$amphoe: $('#editadd [name="city"]'),
		$province: $('#editadd [name="state"]'),
		$zipcode: $('#editadd [name="postcode"]'),
/*
		onDataFill: function(data){
			console.info('Data Filled', data);
		},

		onLoad: function(){
			console.info('Autocomplete is ready!');
			$('#loader, .demo').toggle();
		}*/
	});

	/*
	$('#editadd [name="address2"]').change(function(){
		console.log('ตำบล', this.value);
	});
	$('#editadd [name="city"]').change(function(){
		console.log('อำเภอ', this.value);
	});
	$('#editadd [name="state"]').change(function(){
		console.log('จังหวัด', this.value);
	});
	$('#editadd [name="postcode"]').change(function(){
		console.log('รหัสไปรษณีย์', this.value);
	});
*/

	$('.input-count').keyup(inputCount);
	$('.input-count').keydown(inputCount);

	$("input[name=firstname]").keyup(validateRequired);
	$("input[name=lastname]").keyup(validateRequired);
	$("input[name=phonenumber]").keyup(validateRequired);
	$("input[name=address1]").keyup(validateRequired);
	$("input[name=city]").keyup(validateRequired);
	$("input[name=state]").keyup(validateRequired);
	$("input[name=postcode]").keyup(validateRequired);
	
	function inputCount() {
		var nm = $(this).attr("name");
	    var cs = $(this).val().length;
	    $('#'+nm+"-count").text(cs);
	}
	function validateRequired(){
		var nm = $(this).attr("name");
		var val = $(this).val();

		if(val == ""){
			$(this).addClass("error");
			$('#'+nm+"-error").text("{!! FT::translate('error.required') !!}");
		}else{
			$('#'+nm+"-error").text("");
			$(this).removeClass("error");
		}
	}
</script>
@endsection