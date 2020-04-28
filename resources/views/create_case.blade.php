@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
	    <div class="col-md-7">
	    	<form id="case_form" name="case_form" class="form-horizontal" method="post" action="{{url ('/case/create')}}">
	    		
	    		{{ csrf_field() }}

			    <div class="panel panel-primary">
					<div class="panel-heading">Create Case</div>
			        <div class="panel-body">

	                	<div class="col-md-12">
	                        <label for="category" class="col-12 control-label">ประเภท Case</label>
	                        
	                        <select name="category" class="form-control required" required>
	                        	<option value="">--- กรุณาเลือก ---</option>
	                    		<option @if(old('category') == 'ปัญหาการเข้ารับพัสดุ') selected @endif >ปัญหาการเข้ารับพัสดุ</option>
	                    		<option @if(old('category') == 'ติดตามสถานะ Tracking') selected @endif >ติดตามสถานะ Tracking</option>
	                    		<option @if(old('category') == 'สอบถามรายละเอียดยอดชำระ') selected @endif >สอบถามรายละเอียดยอดชำระ</option>
	                    		<option @if(old('category') == 'ปัญหาการจ่ายเงิน') selected @endif >ปัญหาการจ่ายเงิน</option>
	                    		<option @if(old('category') == 'คืนเงิน / สถานะการคืนเงิน') selected @endif >คืนเงิน / สถานะการคืนเงิน</option>
	                    		<option @if(old('category') == 'คืนสินค้า/ สถานะการคืนสินค้า') selected @endif >คืนสินค้า/ สถานะการคืนสินค้า</option>
	                    		<option @if(old('category') == 'ขอเอกสาร หัก ณ ที่จ่าย') selected @endif >ขอเอกสาร หัก ณ ที่จ่าย</option>
	                    		<option @if(old('category') == 'อื่นๆ') selected @endif >อื่นๆ</option>
	                    	</select>
	                    </div>
		                <div class="col-md-12">
	                   		<label for="ref_id" class="col-12 control-label">หมายเลขพัสดุ/ใบรับพัสดุ</label>
	                   		<input type="text" name="ref_id" id="ref_id" class="form-control required" value="{{ old('ref_id') }}" />
	                   		
	                    </div>
	                    
	                    <div class="clearfix"></div>

	                    <div class="col-md-12">
		                    <label for="detail" class="col-12 control-label">รายละเอียด</label>
		                    <textarea class="form-control required" rows="5" name="detail" id="detail" required>{{ old('detail','') }}</textarea>
		                </div>
		                <div class="clearfix"></div>
		                <br />

		                <div class="text-center"><button type="submit" name="submit" class="btn btn-lg btn-primary">{!! FT::translate('button.confirm') !!}</button></div>
		            
		            </div>
				</div>
			</form> 
	    </div>
	    <div class="col-md-5">
	    	<div class="panel panel-info">
				<div class="panel-heading">FAQS</div>
			    <div class="panel-body">
			        <h4><i class="fa fa-circle-info red"></i> 1. หลังจากเปิดเคสแล้ว จะมีเจ้าหน้าที่ติดต่อกลับภายใน 24 ชม</h4>
			        <h4><i class="fa fa-circle-info red"></i> 2. ท่านสามารถตรวจสอบสถานะเคสได้ที่หน้าพัสดุ หรือ รายการปัญหา</h4>
			        <h4><i class="fa fa-circle-info red"></i> 3. เมื่อเคสแก้ไขแล้ว สถานะจะเปลียนเป็น Solved</h4>
			    </div>
			</div>
	    </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	autocompleteReference();
	$("#ref_id").val("{{ $reference }}");
});
function autocompleteReference(){

	$('#ref_id').autocomplete({
        minLength: 0,
        source: function( request, response ) {
            $.ajax({
            	url: "{{ url('/case/get_ref') }}",
            	type: "POST",
            	dataType: "json",
            	data: {
              		term : request.term,
              		_token: "{{ csrf_token() }}"
            	},
            	success: function(data) {
    				var array = $.map(data['ref'], function (item) { 
                        return {
                          label: item['value'],
                          value: item['key'],
                          data : item
                        }
                    });
                  	response(array);
              	
            	}
        	});
        },
        select: function( event, ui ) {
            
           	var data = ui.item.data;   

           	if(data.stateCode === 0){
           		$(this).val("");
           	}else{
           		$(this).val(data.key);
           	}

        }
      });
}
</script>
@endsection