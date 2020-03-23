@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
    	<h2 class="col-md-10 col-md-offset-1 text-center ">
    	@if($transaction_state == 'Authorized')
    		<span class="text-success">ชำระเงิน ใบรับพัสดุหมายเลข {{ $pickupId }} เรียบร้อยแล้ว</span>
    	@else
    		<span class="text-danger">ชำระเงินไม่สำเร็จ กรุณาติดต่อ  <a href="tel:020803999">020803999</a> หรือ <a href="mailto:cs@fastship.co">cs@fastship.co</a></span>
    	@endif
    	</h2>
    	<div class="clearfix"></div>
    	
    	<h4 class="text-center">กรุณาพิมพ์ใบปะหน้า ระบบกำลังเปลียนหน้าใน 5 วินาที หรือกด <a href="{{ url('/pickup_detail/' . $pickupId) }}">ที่นี่</a></h4>
    	
    </div>
    <div class="clearfix"></div>
</div>
@endsection
<script type="text/javascript">
$( document ).ready(function() {
    var delay = 5000;
    setTimeout(function(){ window.location = "{{ url('/pickup_detail/' . $pickupId) }}"; }, delay);
});
    
fbq('track', 'Purchase', {
	value: {{ $amount }},
	currency: 'THB'
});
</script>