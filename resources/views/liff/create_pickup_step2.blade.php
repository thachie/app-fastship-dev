@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
    	<h4 class="text-secondary">รายการพัสดุ</h4>
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

	<div class="col col-12">
		<h3 class="text-orange">เลือกวิธีการเข้ารับ</h3>
		<hr />
	</div>
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_shipment_step3') }}">

    	<div id="pickup_athome" class="rate-result row bg-light" onclick="selectPickupType('pickup_athome')">
    	
        	<div class="col col-1 text-center padding-0">
        		<input type="radio" class="agent_select" id="agent_select_pickup_athome" name="type" value="Pickup_AtHome" style="margin: 10px auto;" />
        	</div>
        	<div class="col col-2 bg-light padding-0">
            	<img src="/images/fastship.png" style="border-radius: 5px 0 0 5px;width: 100%;">
        	</div>
        	<div class="col col-6 rate-right">
        		<h1 class="rate-agent-name">ให้ Fastship ไปรับที่บ้าน</h1>
        		<div class="rate-agent-duration">
        			<span >2-4 ชั่วโมง</span>
        		</div>        		
        	</div>
        	<div class="col col-3 rate-right">
        	@if($total > 2000)
        		<div class="text-success text-right rate-agent-price">ฟรี</div>
        	@else
        		<div class="text-success text-right rate-agent-price">200.-</div>
        	@endif
        	</div>

        </div>
    
    	<div id="drop_atthaipost" class="rate-result row bg-light" onclick="selectPickupType('drop_atthaipost')">
        	<div class="col col-1 text-center padding-0">
        		<input type="radio" class="agent_select" id="agent_select_drop_atthaipost" name="type"  value="Drop_AtThaipost" style="margin: 10px auto;"/>
        	</div>
        	<div class="col col-2 bg-light padding-0">
            	<img src="/images/thaipost.png" style="border-radius: 5px 0 0 5px;width: 100%;">
        	</div>
        	<div class="col col-6 rate-right">
        		<h1 class="rate-agent-name">นำพัสดุไป Dropoff ที่ไปรษณีย์</h1>
        		<div class="rate-agent-duration">
        			<span>1-2 วัน</span>
        		</div>
        	</div>
        	<div class="col col-3 rate-right">
        		<div class="text-success text-right rate-agent-price">ฟรี</div>
        	</div>

        </div>
        
        <div id="drop_atfastship" class="rate-result row bg-light" onclick="selectPickupType('drop_atfastship')">
        	<div class="col col-1 text-center padding-0">
        		<input type="radio" class="agent_select" id="agent_select_drop_atfastship" name="type"  value="Drop_AtFastship" style="margin: 10px auto;"/>
        	</div>
        	<div class="col col-2 bg-light padding-0">
            	<img src="/images/fastship.png" style="border-radius: 5px 0 0 5px;width: 100%;">
        	</div>
        	<div class="col col-6 rate-right">
        		<h1 class="rate-agent-name">นำพัสดุไป Dropoff ที่ Fastship</h1>
        		<div class="rate-agent-duration">
        			<span>Fastship สาขาแจ้งวัฒนะ14 กรุงเทพมหานคร</span>
        		</div>   		
        	</div>
        	<div class="col col-3 rate-right">
        		<div class="text-success text-right rate-agent-price">ฟรี</div>
        	</div>

        </div>

        <div class="row">
        	<div class="col col-12 text-center">
        		<button type="submit" id="submit" class="btn btn-primary btn-block btn-lg large border-0 disabled " formaction="/liff/create_pickup_step3">ต่อไป</button>
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
    		
    		<button type="submit" id="submit" class="btn btn-info btn-block border-0 " formaction="/liff/create_shipment">เริ่มสร้างพัสดุ</button>
        </div>
    </div>
	@endif

</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){

});
function deleteShipment(cnt){
	if(confirm('ต้องการลบพัสดุนี้ใช่หรือไม่')){
    	$("#shipment_"+cnt).fadeOut(250, function(){ $target.remove(); });
	}
}
function selectPickupType(agent){

	$(".rate-right").each(function(){
		$(this).removeClass('active');
	});
	$("#" + agent + " .rate-right").removeClass('bg-light').addClass('active');

	$("input[name=agent_select]:checked").each(function(){
		$(this).prop('checked', false);
	});
	$("#agent_select_"+agent).prop("checked",true);
	
	var cHeight = $('.conter-wrapper')[0].scrollHeight;
	//alert($('.conter-wrapper')[0].scrollHeight);
	
	$('html, body').animate({
    	scrollTop: Math.max(0, cHeight - 530)
    }, 500);

    $("#submit").removeClass("disabled");
}
-->
</script>
@endsection