@extends('layout')
@section('content')
<?php 
if(isset($_GET['ret'])){
	$return = $_GET['ret'];
}else{
	$return = "";
}
?>
<div class="conter-wrapper">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h2>{!! FT::translate('edit_customer.heading') !!}</h2>
		</div>
	</div>
	<div class="row">
	    <div class="col-md-8 col-md-offset-2">
	    	<form id="editadd" name="login_form" class="form-horizontal" method="post" action="{{url ('/customer/edit')}}">
	    		
	    		{{ csrf_field() }}
	    		
	    		<input type="hidden" name="latitude" id="map_lat" value="<?php echo $customer_data['latitude']; ?>" />
	    		<input type="hidden" name="longitude" id="map_lng" value="<?php echo $customer_data['longitude']; ?>"  />
	    		<input type="hidden" name="return" value="<?php echo $return; ?>"  />
	    		
			    <div class="panel panel-primary">
					<div class="panel-heading">{!! FT::translate('edit_customer.panel.heading1') !!}</div>
			        <div class="panel-body">
		                <!-- <div class="row"> -->
		                
		                	<div class="col-md-6">
			                    <label for="firstname" class="col-12 control-label">{!! FT::translate('label.firstname') !!}</label>
			                    <input type="text" class="form-control required input-count" name="firstname" id="firstname" required value="<?php echo $customer_data['firstname']; ?>" maxlength="100" />
			                	<div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count"><?php echo strlen($customer_data['firstname']); ?></span>/100</div>
			                </div>
			                <div class="col-md-6">
		                   		<label for="lastname" class="col-12 control-label">{!! FT::translate('label.lastname') !!}</label>
		                    	<input type="text" class="form-control required input-count" name="lastname" id="lastname" required value="<?php echo $customer_data['lastname']; ?>" maxlength="100" />
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="lastname-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="lastname-count"><?php echo strlen($customer_data['lastname']); ?></span>/100</div>
		                    </div>
		                    <div class="clearfix"></div>
		                    
		                    <div class="col-md-6">
		                    	<label for="email" class="col-12 control-label">{!! FT::translate('label.email') !!}</label>
		                    	<div class="form-control"><?php echo $customer_data['email']; ?></div>
		                    </div>
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.telephone') !!}</label>
		                        <input type="text" class="form-control required input-count" name="telephone" id="telephone" required  value="<?php echo $customer_data['phonenumber']; ?>" maxlength="100" />
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="telephone-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="telephone-count"><?php echo strlen($customer_data['phonenumber']); ?></span>/100</div>
		                    </div>
		                    <div class="clearfix"></div>
			                    
			                <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.company') !!}</label>
		                        <input type="text" class="form-control input-count" name="company" id="company" value="<?php echo $customer_data['company']; ?>" maxlength="100"/>
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="company-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="company-count"><?php echo strlen($customer_data['company']); ?></span>/100</div>
		                    </div>
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.taxid') !!}</label>
		                        <input type="text" class="form-control input-count" name="taxid" id="taxid" value="<?php echo $customer_data['taxid']; ?>" maxlength="20"/>
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="taxid-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="taxid-count"><?php echo strlen($customer_data['taxid']); ?></span>/20</div>
		                    </div>
		                    <div class="clearfix"></div>
		
			                <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.address1') !!}</label>
		                        <input type="text" class="form-control required input-count" name="address1" id="address1" required value="<?php echo $customer_data['address1']; ?>" maxlength="100" />
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="address1-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="address1-count"><?php echo strlen($customer_data['address1']); ?></span>/100</div>
		                    </div>
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.address2') !!}</label>
		                        <input type="text" class="form-control input-count" name="address2" id="address2" value="<?php echo $customer_data['address2']; ?>" maxlength="100" />
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="address2-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="address2-count"><?php echo strlen($customer_data['address2']); ?></span>/100</div>
		                    </div>
		                    <div class="clearfix"></div>
		                    
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.city') !!}</label>
		                        <input type="text" class="form-control required input-count" name="city" id="city" required value="<?php echo $customer_data['city']; ?>" maxlength="100" />
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="city-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="city-count"><?php echo strlen($customer_data['city']); ?></span>/100</div>
		                    </div>
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.state') !!}</label>
		                        <input type="text" class="form-control required input-count" name="state" id="state" required value="<?php echo $customer_data['state']; ?>" maxlength="100" />
		                    	<?php /*
		                    	
		                    	<select name="state" class="form-control required" required>
                            		@foreach($provinces as $province)
                            		@if($province->name_th == strtolower($customer_data['state']))
                            		<option value="{{ $province->name_th }}" selected="selected">{{ $province->name_th }} - {{ $province->name_en }}</option>
                            		@else
                            		<option value="{{ $province->name_th }}">{{ $province->name_th }} - {{ $province->name_en }}</option>
                            		@endif
                            		@endforeach
                            	</select>
                            	
		                    	*/?>
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="state-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="state-count"><?php echo strlen($customer_data['state']); ?></span>/100</div>
		                    </div>
		                    <div class="clearfix"></div>
		                    
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.postcode') !!}</label>
		                        <input type="text" class="form-control required input-count" name="postcode" id="postcode" required value="<?php echo $customer_data['postcode']; ?>" maxlength="20" />
		                    	<div class="red tiny text-left col-md-10 no-padding"><span id="postcode-error" class="error-msg"></span></div> 
                            	<div class="gray tiny text-right col-md-2 no-padding"><span id="postcode-count"><?php echo strlen($customer_data['postcode']); ?></span>/20</div>
		                    </div>
		                    
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.country') !!}</label>
		                        <div class="form-control"><?php echo ($customer_data['country'])?$countries[$customer_data['country']]:""; ?></div>
		                    </div>
		                    <div class="clearfix"></div>
		
		                <!-- </div> -->
		                
		                <div class="row">

			                <div class="col-md-12">
			                	<p>{!! FT::translate('label.map_pin') !!}</p>
			                </div>
			                <div class="clearfix"></div>
			                
			                <div class="col-md-12">
			                	<input type="text" id="searchmap" class="form-control input-sm" placeholder="{!! FT::translate('placeholder.search_location') !!}"/>
			                </div>
			                <div class="clearfix"></div>
			                <br />
	
			                <div class="col-md-12" style="height: 300px;">
			                	<div id="map" height="300px" width="600px"></div>
			                </div>
			                <div class="clearfix"></div>
			                <br />

			            </div>

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