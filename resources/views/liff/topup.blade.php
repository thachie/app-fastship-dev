@extends('liff/layout')
@section('content')
<?php 
$selectAmounts = array(300,500,1000,2000,5000,10000);
$paymentMethods = array(
    "Bank_Transfer"=>"โอนผ่านธนาคาร",
    "Credit_Card"=>"บัตรเครดิต",
);
?>
<div class="conter-wrapper">

    
    	    
   	<div class="row">
        <div class="col col-4 text-right">
        	<div class="text-secondary">ยอดคงเหลือ <h4 class="text-orange">{{ ($creditBalance>0)?$creditBalance:0 }}</h4></div>
        </div>	
        <div class="col col-4 text-right">
        	<div class="text-secondary">ยอดค้างชำระ <h4 class="text-orange">{{ ($unpaid>0)?$unpaid:0 }}</h4></div>
        </div>	
        <div class="col col-4 text-right">
        	<div class="text-secondary">ยอดรออนุมัติ <h4 class="text-orange">0</h4></div>
        </div>	
	</div>
	
	<div class="col col-12">
    	<h3 class="text-orange">เติมเงิน</h3>
    </div>
    <hr />
    
	<div class="col col-12">
		<p class="small text-secondary">กดจำนวนเงินที่ต้องการเติมเงิน</p>
	</div>	
	
	<div class="row">
		@foreach($selectAmounts as $selectAmount)
        <div id="amount_{{ $selectAmount }}" class="text-center d-inline-block " style="borer:1px solid #ddd;width: 31%;margin: 1%;">       
        	@if($selectAmount == 500)
        	<button type="button" class="btn btn-lg btn-block btn-primary amount" onclick="selectAmount({{ $selectAmount }});" >
        		{!! number_format($selectAmount,0) !!}.-
            </button>
        	@else
        	<button type="button" class="btn btn-lg btn-block btn-light amount" onclick="selectAmount({{ $selectAmount }});" >
        		{!! number_format($selectAmount,0) !!}.-
            </button>
            @endif
        </div>
        @endforeach
        
        @if($unpaid > 0)
        <div id="amount_{{ $unpaid }}" class="text-center d-inline-block" style="borer:1px solid #ddd;width:98%;margin: 1%;">       
        	<button type="button" class="btn btn-lg btn-block btn-light amount" onclick="selectAmount({{ $unpaid }});" >
        		<span class="small">เท่ายอดค้างชำระ</span> {{ $unpaid }}.-
            </button>
        </div>
        @endif
 
        
    </div>
        
    
	<form id="topup_form" name="topup_form" method="post" action="{{ url('liff/topup') }}">
	<div class="row">
	
		<input type="hidden" id="amount" name="amount" value="500" required />		
    	
    	<div class="col col-12 text-center">
    		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 " formaction="/liff/topup_qr">โอนผ่าน QR Code</button>
    		<button type="submit" id="submit" class="btn btn-success btn-block btn-lg large border-0 " formaction="/liff/topup_creditcard">ตัดบัตรเครดิต</button>
    	</div>
        
    </div> 
    </form>
	
</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){

});
function selectAmount(amount){

	$("#amount").val(amount);
	
	$(".amount").each(function(){
		$(this).removeClass('btn-primary').addClass('btn-light');
	});
	$("#amount_" + amount + " .amount").removeClass('btn-light').addClass('btn-primary');

	//var cHeight = $('.conter-wrapper')[0].scrollHeight;
	
	$('html, body').animate({
    	scrollTop: Math.max(0, cHeight - 530)
    }, 500);

    $("#submit").removeClass("disabled");
    
}
-->
</script>
@endsection