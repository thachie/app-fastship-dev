@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

   	<div class="col col-12">
    	<h3 class="text-orange">ตัดบัตรเครดิต จำนวน {{ $amount }} บาท</h3>
    </div>
    <hr />

	@if(sizeof($creditCards) > 0)
	<div class="row">

		<div class="col col-12 text-center text-secondary">
        	<p>กรุณากดยืนยันการตัดบัตร</p>
        </div>

		<div class="col-12 col text-center">
		@foreach($creditCards as $creditCard)
			<div class=" block-primary" style="position: relative;">
				<span class="creditbar"></span>
				<h5 class="accountno">XXXXX-XXX-X{{ $creditCard['LastDigits'] }}</h5>
				<h6>{{ $creditCard['CardName'] }}</h6>
				<p>{{ $creditCard['Bank'] }}</p>
			</div>
		@endforeach
		</div>
		

		<div class="col col-12 text-center">
		<form id="topup_form" name="topup_form" method="post" action="{{ url('liff/action/topup_creditcard') }}">
	
			<input type="hidden" id="amount" name="amount" required />
			
			<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 ">ยืนยันการตัดบัตร</button>
    		<button type="button" class="btn bg-light btn-block btn-sm border-0" onclick="history.back();">ย้อนกลับ</button>
    		
    	</form>
		</div>
		
    </div> 
    @else
    <div class="row">
		<div class="col-12 col text-center">
			<p class="text-danger">คุณยังไม่ได้เพิ่มบัตรเครดิต</p>
			
			<a href="{{ url('liff/add_creditcard') }}"><button type="button" class="btn bg-link btn-block btn-info btn-sm border-0">เพิ่มบัตร</button></a>
    		
		</div>
	</div>
	@endif      
	
</div>
<script type="text/javascript">
<!--
$(window).on('load',function(){

});
-->
</script>
@endsection