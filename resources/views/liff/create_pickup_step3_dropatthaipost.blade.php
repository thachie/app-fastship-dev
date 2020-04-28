@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">นำพัสดุไป Dropoff ที่ไปรษณีย์</h3>
		<hr />
		
	</div>
	
	
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_pickup_step4') }}">
		
		<div class="row">

			<div class="col col-12">
    			<label for="postal" class=" form-control-label">เลือก ปณ. ที่ไปฝากพัสดุ</label>
    			<select id="state" name="state" class="form-control" onchange="loadPostal(this.value)" required>
    			@foreach($states as $state)
    				<option value="{{ $state }}">{{ $state }}</option>
    			@endforeach
    			</select>
        		<div id="state_help" class="help text-danger small"></div>
        	</div>
        	<div class="col col-12"  style="margin-top: 10px;">
    			<select id="postal" name="postal" class="form-control" required>

    			</select>
        		<div id="postal_help" class="help text-danger small"></div>
    		</div>

            <div class="col col-12">
    			<label for="memo" class=" form-control-label">ข้อความเพิ่มเติม</label>
    			<input type="text" name="memo" class="form-control" />
        		<div id="memo_help" class="help text-danger small"></div>
    		</div>

		</div>
		
        <div class="row">
        	<div class="col col-12 text-center">
        		<button type="submit" id="submit" class="btn btn-primary btn-block btn-lg large border-0" formaction="/liff/create_pickup_step4">ต่อไป</button>
        		<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;margin-top: 10px;" onclick="history.back();">ย้อนกลับ</button>
        	</div>
        </div>
        
    </form>
    
    <hr />
    
    <div class="row">
        <div class="col col-12 text-center text-secondary">
        	{!! FT::translate('modal.drop_thaipost.content') !!}
    	</div>
	</div>
	
</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){
	$('#state').val("{{ $default['state'] }}");
	$('#memo').val("{{ $default['memo'] }}");
	loadPostal("{{ $default['state'] }}");
});
function loadPostal(_state){

	$('#postal').empty();
	
	//call ajax
	$.ajax({
    	url: '/liff/ajax/get_postal',
        dataType: 'json',
        type: 'POST',
        data: {'state' : _state},
        success: function(data) {
            $.each(data, function(key, value) {
                $('#postal').append('<option value="'+ key +'">'+ value +'</option>');
            });
            $('#postal').val("{{ $default['postal'] }}");
        }
    });

}
-->
</script>
@endsection