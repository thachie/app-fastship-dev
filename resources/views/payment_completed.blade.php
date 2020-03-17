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
    </div>
    <div class="clearfix"></div>
</div>
@endsection
<script type="text/javascript">
    fbq('track', 'Purchase', {
    	value: {{ $pickup_data['Amount'] }},
    	currency: 'THB',
    });
</script>