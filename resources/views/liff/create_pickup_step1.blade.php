@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">รายการพัสดุรอส่ง</h3>
		<hr />
	</div>

	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_pickup_step2') }}">
	 
    	<input type="hidden" name="line_user_id" class="line_user_id" />
    	 	
    	@if(sizeof($shipments) > 0)
    	@foreach($shipments as $shipment)
    	<div class="row" id="shipment_{{ $shipment['ID'] }}">
    		
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
    			<div class="text-secondary tiny">{{ $shipment['ReceiverDetail']['AddressLine1'] }} {{ $shipment['ReceiverDetail']['AddressLine2'] }} {{ $shipment['ReceiverDetail']['City'] }} {{ $shipment['ReceiverDetail']['State'] }} {{ $shipment['ReceiverDetail']['Postcode'] }} {{ $shipment['ReceiverDetail']['Country'] }}</div>
        	</div>
        	<div class="col col-2 text-right">
        		<div class="text-success"><h4 class="mt-0">{{ $shipment['ShipmentDetail']['ShippingRate'] }}.-</h4></div>
        		<a href="#"><span class="text-danger small"><i class="fa fa-trash-o" onclick="deleteShipment('{{ $shipment['ID'] }}')"></i></span></a>
        	</div>
    		<hr />
       	</div>
    	@endforeach
    	<div class="row">
        	<div class="col col-9 text-right"><input type="text" name="coupon" class="form-control" /></div>
        	<div class="col col-3 text-right text-success"><button type="button" id="submit" class="btn btn-success">ใช้คูปอง</button></div>
    	</div>
    	<div class="row">
        	<div class="col col-9 text-right">ค่าส่งรวม</div>
        	<div class="col col-3 text-right"><h4 class="text-success font-weight-bold mt-0 mb-0">{{ $totalRate }}.-</h4></div>
    
        	<div class="col col-9 text-right">ส่วนลด</div>
        	<div class="col col-3 text-right"><h4 class="text-info font-weight-bold mt-0 mb-0">0.-</h4></div>
        	
        	<div class="col col-9 text-right">รวมทั้งหมด</div>
        	<div class="col col-3 text-right"><h4 class="text-success large font-weight-bold mt-0 mb-0">{{ $totalRate }}.-</h4></div>
        </div>
        
        <div class="row">
        	<div class="col col-12 text-center">
        		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 ">ยืนยันการส่ง</button>
        		<button type="submit" class="btn btn-light bg-light btn-block btn-sm border-0 " formaction="/liff/create_shipment">สร้างพัสดุใหม่</button>
        	</div>
        </div>
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

    	
        
    </form>
	
	

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
function gotoCreateShipment(){
	e.preventDefault();
	$("#create_form").attr("action","/liff/create_shipment").submit();
}
-->
</script>
@endsection