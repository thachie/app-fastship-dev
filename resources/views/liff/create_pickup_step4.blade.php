@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">สรุปการเข้ารับพัสดุ</h3>
		<hr />
	</div>
	
	@if(sizeof($shipments) > 0)
	<div class="row">
	@foreach($shipments as $shipment)
	@if($shipmentListFormat == "single")
		<div class="col col-3 text-center">
    		<div><img src="/images/agent/{{ $shipment['ShipmentDetail']['ShippingAgent'] }}.gif" style="border-radius: 5px;width: 100%;"></div>
    		<div class="text-secondary small">{{ $shipment['ShipmentDetail']['Weight'] }} กรัม </div>
    		@if($shipment['ShipmentDetail']['Width'] != '')<div class="text-primary small">{{ $shipment['ShipmentDetail']['Width'] }}x{{ $shipment['ShipmentDetail']['Height'] }}x{{ $shipment['ShipmentDetail']['Length'] }} ซม.</div>@endif
    	
    	</div>
    	<div class="col col-7">
    		@if($shipment['ReceiverDetail']['Company'])
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Company'] }}</strong></div>
				<div>{{ $shipment['ReceiverDetail']['Firstname'] }} {{ $shipment['ReceiverDetail']['Lastname'] }}</div>
			@else
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Firstname'] }} {{ $shipment['ReceiverDetail']['Lastname'] }}</strong></div>
			@endif
			<div class="text-secondary">{{ $shipment['ReceiverDetail']['AddressLine1'] }} {{ $shipment['ReceiverDetail']['AddressLine2'] }} {{ $shipment['ReceiverDetail']['City'] }} {{ $shipment['ReceiverDetail']['State'] }} {{ $shipment['ReceiverDetail']['Postcode'] }} {{ $shipment['ReceiverDetail']['Country'] }}</div>
    	</div>
    	<div class="col col-2 text-right">
    		<div class="text-success"><h4>{{ $shipment['ShipmentDetail']['ShippingRate'] }}.-</h4></div>
    	</div>

	@elseif($shipmentListFormat == "divide-2")
		<div class="col col-6 text-center">
			<div><img src="/images/agent/{{ $shipment['ShipmentDetail']['ShippingAgent'] }}.gif" style="border-radius: 5px;width: 100%;max-width: 80px;"></div>
    		@if($shipment['ReceiverDetail']['Company'])
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Company'] }}</strong></div>
			@else
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Firstname'] }}</strong></div>
			@endif
			<div class="text-secondary small">{{ $shipment['ReceiverDetail']['Country'] }}</div>
    	</div>
	@elseif($shipmentListFormat == "divide-4")
		<div class="col col-3 text-center">
			<div><img src="/images/agent/{{ $shipment['ShipmentDetail']['ShippingAgent'] }}.gif" style="border-radius: 5px;width: 100%;max-width: 80px;"></div>
    		@if($shipment['ReceiverDetail']['Company'])
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Company'] }}</strong></div>
			@else
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Firstname'] }}</strong></div>
			@endif
			<div class="text-secondary small">{{ $shipment['ReceiverDetail']['Country'] }}</div>
    	</div>
	@else
		<div class="col col-4 text-center">
			<div><img src="/images/agent/{{ $shipment['ShipmentDetail']['ShippingAgent'] }}.gif" style="border-radius: 5px;width: 100%;max-width: 80px;"></div>
    		@if($shipment['ReceiverDetail']['Company'])
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Company'] }}</strong></div>
			@else
				<div class="text-success"><strong>{{ $shipment['ReceiverDetail']['Firstname'] }}</strong></div>
			@endif
			<div class="text-secondary small">{{ $shipment['ReceiverDetail']['Country'] }}</div>
    	</div>
	@endif
	@endforeach
	</div>
	
	<div class="row">
	
		<div class="col col-9 text-right">ค่าส่งรวม</div>
    	<div class="col col-3 text-right"><h4 class="text-success font-weight-bold mt-0 mb-0">{{ $total }}.-</h4></div>
	
		@if(session('liff.coupon') != null && session('liff.coupon') != "")
    	<div class="col col-9 text-right">ส่วนลด ({{ session('liff.coupon') }})</div>
    	<div class="col col-3 text-right"><h4 class="text-info font-weight-bold mt-0 mb-0">0.-</h4></div>
    	@endif
		
		<div class="col col-9 text-right">ค่าเข้ารับ</div>
    	<div class="col col-3 text-right"><h4 class="text-success font-weight-bold mt-0 mb-0">ฟรี</h4></div>
    	
	</div>
	
	<hr />

	@if(session('liff.type') == "Pickup_AtHome")
	<div class="row">
		<div class="col col-12">
			<h4>ให้ Fastship ไปรับที่บ้าน</h4>
		</div>
		<div class="col col-3 text-right">
			<p>ติดต่อ:</p>
		</div>
		<div class="col col-9">
			<p><span class="text-primary">{{ $data['firstname'] }}</span> <span class="text-dark text-xs small"><i class="fa fa-phone"></i> {{ $data['telephone'] }}</span></p>
		</div>
		<div class="col col-3 text-right">
			<p>เวลาเข้ารับ:</p>
		</div>
		<div class="col col-9">
			<p>{{ $data['pickDate'] . " ช่วง " . $pickupTimes[$data['pickTime']] }}</p>
		</div>
		<div class="col col-3 text-right">
			<p>ที่อยู่:</p>
		</div>
		<div class="col col-9">
			<p class="text-secondary">{{ $data['address1'] }} {{ $data['address2'] }} {{ $data['city'] }} {{ $data['state'] }} {{ $data['postcode'] }}</p>
		</div>
		@if($data['memo'])
		<div class="col col-3 text-right">
			<div>ข้อความ:</div>
		</div>
		<div class="col col-9">
			<p class="text-secondary">{{ $data['memo'] }}</p>
		</div>
		@endif
		<div class="col col-12">
			<div id="map" style="height:300px;width:350px;"></div>
		</div>
	</div>
	<div class="row">
		<div class="col col-12 text-center">
			<p class="text-danger ">หลังจากยืนยันแล้ว Fastship จะติดต่อที่เบอร์ <span class="text-info">{{ $data['telephone'] }}</span></p>
		</div>
	</div>
	@elseif(session('liff.type') == "Drop_AtThaipost")
	<div class="row">
		<div class="col col-12">
			<h4>นำพัสดุไป Dropoff ที่ไปรษณีย์</h4>
			<p class="text-secondary">{{ $arg3 }}</p>
		</div>
		<div class="col col-3 text-right">
			<p>ปณ.:</p>
		</div>
		<div class="col col-9">
			<p><i class="fa fa-paper-plane"></i> <span class="text-primary">{{ $data['postal'] }} {{ $data['post_name'] }}</span> <span class="text-dark text-xs small">{{ $data['state'] }}</span></p>
		</div>
		@if($data['memo'])
		<div class="col col-3 text-right">
			<div>ข้อความ:</div>
		</div>
		<div class="col col-9">
			<p class="text-secondary">{{ $data['memo'] }}</p>
		</div>
		@endif
	</div>
	@elseif(session('liff.type') == "Drop_AtFastship")
	<div class="row">
		<div class="col col-12">
			<h4>นำพัสดุไป Dropoff ที่ Fastship</h4>
		</div>
		<div class="col col-12">
			<div id="map" style="height:300px;width:350px;"></div>
		</div>
	</div>
	<div class="row">
		<div class="col col-12 text-center text-secondary">
        	{!! FT::translate('modal.drop_fastship.content') !!}
    	</div>
	</div>
	@endif
	
	
	
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/action/create_pickup') }}">

        <div class="row">
        	<div class="col col-12 text-center">
        		<button type="submit" id="submit" class="btn btn-primary btn-block btn-lg large border-0">ยืนยันการเข้ารับ</button>
        		<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;margin-top: 10px;" onclick="history.back();">ย้อนกลับ</button>
        	</div>
        </div>
    
    </form>
    
    @else
    	<div class="row">
    		<div class="col col-12 text-center">
            	<p><img src="{{ url('/images/line/not_found.png') }}" style="width: 80px;"/></p>
    			<div class="text-danger">ไม่พบพัสดุรอส่ง</div>
    			<br />
        		
        		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block border-0 " formaction="/liff/create_shipment">เริ่มสร้างพัสดุ</button>
            </div>
        </div>
    @endif
	
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

    @if(session('liff.type') == "Pickup_AtHome")
    var defaultLat = {{ $data['latitude'] }};
    var defaultLng = {{ $data['longitude'] }};
    @else
    var defaultLat = 13.9024959;
    var defaultLng = 100.56231660000003;
    @endif

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