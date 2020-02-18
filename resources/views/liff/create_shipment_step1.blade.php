@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">1. ระบุน้ำหนักและขนาดพัสดุ</h3>
		<hr />
	</div>
	
	<div class="col col-12">
		<p class="small text-secondary">กรุณาระบุน้ำหนักและขนาดพัสดุที่ต้องการส่ง</p>
	</div>
	
	 <form id="create_form" name="create_form" method="post" action="{{ url('liff/create_shipment_step2') }}">
	 
	 	<input type="hidden" name="line_user_id" class="line_user_id" />
	 	
		<div class="col col-12">
			<label for="weight" class=" form-control-label">น้ำหนัก (กรัม)</label>
            <input type="number" id="weight" name="weight" min="0"  class="form-control required" placeholder="package weight" onchange="calculateWeight(false,'{{ session('liff.agent') }}')" required/>
            <div id="weight_text" class="text-center small text-danger" style="display: none;">Fastship แนะนำให้แบ่งส่งพัสดุกล่องละไม่เกิน 20 กิโล เพื่อราคาที่ดีที่สุด</div>

			<label for="width" class=" form-control-label">ขนาด (ซม.)</label><br />
			<div class="d-inline-flex">
    			<div class="d-inline" style="margin-right: 10px;">
    				<input type="number" id="length" name="length" min="0" class="form-control" placeholder="length" onchange="calculateWeight(false,'{{ session('liff.agent') }}')"/>
                </div>
    			<div class="d-inline" style="margin-right: 10px;">
    				<input type="number" id="width" name="width" min="0" class="form-control" placeholder="width" onchange="calculateWeight(false,'{{ session('liff.agent') }}')"/>
                </div>
                <div class="d-inline">
    				<input type="number" id="height" name="height" min="0" class="form-control" placeholder="height" onchange="calculateWeight(false,'{{ session('liff.agent') }}')"/>
                </div>
            </div>
            
            <div id="volweight" class="help small"></div>

            <label for="country" class=" form-control-label">ประเทศ</label>
        	<select class="form-control required" id="country" name="country" onchange="calculateWeight(true,'{{ session('liff.agent') }}')">
            	@foreach($countries as $country)
                <option value="{{ $country['CNTRY_CODE'] }}">{{ $country['CNTRY_NAME'] }}</option>
                @endforeach
            </select>
    	</div>
    	
    	<div class="row">
        	<div class="col col-12 text-center">
        		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 ">ต่อไป</button>
        	</div>
        </div>
        
    </form>
	
	

</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){

	@if( session('liff.country') != null )
		$("#country").val("{{ session('liff.country') }}");
	@else
		$("#country").val("USA");
	@endif

	@if( session('liff.weight') != null )
		$("#weight").val("{{ session('liff.weight') }}");
	@endif
	@if( session('liff.width') != null )
		$("#width").val("{{ session('liff.width') }}");
	@endif
	@if( session('liff.height') != null )
		$("#height").val("{{ session('liff.height') }}");
	@endif
	@if( session('liff.length') != null )
		$("#length").val("{{ session('liff.length') }}");
	@endif

});
function calculateWeight(scroll,defaultAgent){

	//adjust weight
	if($("#weight").val() != ""){
    	$("#weight").val(parseInt($("#weight").val()));
    	$("#weight_help").text("");
	}else{
		$("#weight_help").text("กรุณากรอกน้ำหนักค่ะ");
		return false;
	}
    if($("#weight").val() < 0){
    	$("#weight_help").text("กรุณากรอกน้ำหนักใหม่ค่ะ");
    }else if($("#weight").val() > 299999){
    	$("#weight").val(299999);
    } 

    //check weight > 20kg
	if($("#weight").val() > 20000){
        $("#weight_text").show();
    }else{
    	$("#weight_text").hide();
    }

    //adjust dimension
	if($("#width").val() != ""){
		$("#width").val(parseInt($("#width").val()));
	}
	if($("#height").val() != ""){
		$("#height").val(parseInt($("#height").val()));
	}
	if($("#length").val() != ""){
		$("#length").val(parseInt($("#length").val()));
	}

	if($("#width").val() != "" && $("#height").val() != "" && $("#length").val() != ""){
		var volWeight = $("#width").val()*$("#height").val()*$("#length").val()/5;
    	$("#volweight").text("น้ำหนักโดยปริมาตร : " + volWeight.toFixed(0) + " กรัม");
	}else{
		$("#volweight").text("");
	}

}
-->
</script>
@endsection