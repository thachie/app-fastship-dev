@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
    	<h4 class="text-secondary">ข้อมูลพัสดุ</h4>
    	<hr />
    </div>
	<div class="row">
    	
    	<div class="col col-12">
    		<div class="text-secondary">
    			<i class="fa fa-dropbox"></i>
    			พัสดุหนัก {{ session('liff.weight') }} กรัม 
    			@if(session('liff.width') != '')<span class="text-primary">ขนาด {{ session('liff.width') }}x{{ session('liff.height') }}x{{ session('liff.length') }} ซม.</span>@endif
    		</div>
    	</div>
    	<div class="col col-12">
    		<div class="text-secondary"><i class="fa fa-star"></i> ปลายทาง: {{ $country->name }}</div>
    	</div>
	</div>
	
	<div class="col col-12">
		<h3 class="text-orange">2. เลือกผู้ให้บริการ</h3>
		<hr />
	</div>
	
	<div class="col col-12">
		<p class="small text-secondary">กรุณาเลือกผู้ให้บริการส่งออกที่ต้องการ</p>
	</div>
	
	@if($rates != null)
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_shipment_step3') }}">
	 
<!-- 	 	<input type="hidden" name="weight" id="weight" value="{{ session('liff.weight') }}" /> -->
<!-- 	 	<input type="hidden" name="width" id="width" value="{{ session('liff.width') }}" /> -->
<!-- 	 	<input type="hidden" name="height" id="height" value="{{ session('liff.height') }}" /> -->
<!-- 	 	<input type="hidden" name="length" id="length" value="{{ session('liff.length') }}" /> -->
<!-- 	 	<input type="hidden" name="country" id="country" value="{{ session('liff.country') }}" /> -->
	 	<input type="hidden" name="agent" id="agent" />
	 	<input type="hidden" name="rate" id="rate" />
	 	
	 	
          
	 	@foreach($rates as $rate)
        <div id="rate_result_{{ $rate['code'] }}" class="rate-result row bg-light" onclick="selectAgent('{{ $rate['code'] }}',{{ $rate['rate'] }})">
        	<div class="col col-1 text-center padding-0">
        		<input type="radio" class="agent_select" name="agent_select" id="agent_select_{{ $rate['code'] }}" value="{{ $rate['code'] }}" style="margin: 10px auto;"/>
        	</div>
        	<div class="col col-2 bg-light padding-0">
            	<img src="/images/agent/{{ $rate['code'] }}.gif" style="border-radius: 5px 0 0 5px;width: 100%;">
        	</div>
        	<div class="col col-6 rate-right">
        		<h1 class="rate-agent-name">{{ $rate['name'] }}</h1>
        		<div class="rate-agent-duration">
        			<span >{{ $rate['minTime'] }}-{{ $rate['maxTime'] }} วันทำการ</span>
        		</div>        		
        	</div>
        	<div class="col col-3 rate-right">
        		<div class="text-success text-right rate-agent-price">{{ $rate['rate'] }}.-</div>
        	</div>

        </div>
	 	@endforeach
	 	
	 	<div id="submit_form" class="row">
    		<div class="col col-12 ">
        		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 disabled">ต่อไป</button>
        		<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;margin-top: 10px;" onclick="history.back();">ย้อนกลับ</button>
        	</div>
        </div>
	 	
    </form>
    
    <div class="row">
		<div class="col col-12 text-center">
			<a href="#" target="_blank"><button class="btn btn-sm btn-link btn-light small">ข้อแตกต่างของผู้ให้บริการแต่ละราย คลิ๊ก!</button></a>
		</div>
	</div>
	
	@else
	<div class="row">
		<div class="col col-12 text-center">
			
			<p><img src="{{ url('/images/line/not_found.png') }}" style="width: 80px;"/></p>
			<div class="text-danger">ไม่พบผู้ให้บริการที่ส่งออกได้ </div>
			<div class="text-secondary small">กรุณาเลือกช่วงน้ำหนักหรือประเทศอื่น</div>
			<br />
			
			<button type="button" class="btn bg-orange btn-success btn-block btn-sm border-0" onclick="history.back();">ย้อนกลับ</button>
		</div>
	</div>
	@endif
	
    
    

</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){

});
function selectAgent(agent,rate){

	$("#agent").val(agent);
	$("#rate").val(rate);
	$(".rate-right").each(function(){
		$(this).removeClass('active');
	});
	$("#rate_result_" + agent + " .rate-right").removeClass('bg-light').addClass('active');

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