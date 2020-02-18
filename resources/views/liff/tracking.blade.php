@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">ติดตามพัสดุ</h3>
		<hr />
	</div>
	
	<div class="col col-12">
		<p class="small text-secondary">รายการสถานะพัสดุของคุณ</p>
	</div>
	
	@if(sizeof($shipments) > 0)
	<div class="col col-12">
    	<table class="table">
    	<thead>
    	<tr>
    		<th class="text-center small">หมายเลขพัสดุ</th>
    		<th class="text-center small">ปลายทาง</th>
    		<th class="text-center small">สถานะ</th>
    	</tr>
    	</thead>
    	<tbody>
        @foreach($shipments as $shipment)
        <tr>
        	<td class="text-center small"><a href="{{ url('liff/tracking_result/?tracking=' . $shipment['ID']) }}">{{ $shipment['ID'] }}</a></td>
        	<td class="text-center small">{{ $shipment['ReceiverDetail']['Country'] }}</td>
        	<td class="text-center small"><span class="badge orange-bg tiny">{{ $shipment['Status'] }}</span></td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    @else
    <div class="row">
        <div class="col col-12">
    		<p class="small text-danger text-center">คุณไม่มีพัสดุที่อยู่ระหว่างส่งออก</p>
    	</div>
    </div>
    @endif
    
    <div class="col col-12">
		<p class="small text-secondary">หรือค้นหาจากรหัสติดตาม</p>
	</div>
	
	<form id="tracking_form" name="tracking_form" method="post" action="{{ url('liff/tracking_result/') }}">

		<div class="col col-12">
			
			<label for="tracking" class=" form-control-label">รหัสติดตาม/หมายเลขพัสดุ</label>
            <input type="text" id="tracking" name="tracking" min="0"  class="form-control required" placeholder="tracking number" required/>

    	</div>
    	
    	<div class="row">
        	<div class="col col-12 text-center">
        		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 ">ค้นหา</button>
        	</div>
        </div>
        
    </form>

	
</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){

});
-->
</script>
@endsection