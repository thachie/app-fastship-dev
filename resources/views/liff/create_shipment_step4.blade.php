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
    	<div class="col col-12">
    		<div class="text-secondary"><i class="fa fa-plane"></i> ส่งโดย: {{ $agent->name }} {{ session('liff.rate') }} บาท</div>
    	</div>
    	<div class="col col-12">
    		<div class="text-secondary"><i class="fa fa-gift"></i> สินค้า: {{ sizeof($declares) }} ประเภท</div>
    	</div>

   	</div>

<?php /* ?>
	<div class="row">
    	<div class="col col-12">
        	<table class="table table-sm table-striped table-bordered table-default small" style="margin-bottom: 0;">
                <thead>
                  <tr>
                    <th>ประเภทสินค้า</th>
                    <th>จำนวน</th>
                    <th>มูลค่ารวม</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($declares as $declare)
                  <tr>
                    <td>{{ (strlen($declare['type']) > 30) ? substr($declare['type'],0,30)." ...":$declare['type'] }}</td>
                    <td class="text-center">{{ $declare['qty'] }}</td>
                    <td class="text-center text-primary">{{ $declare['value'] }}</td>
                  </tr>
                @endforeach
                </tbody>
            </table>
            <span class="small text-secondary">* มูลค่าในหน่วยบาท</span>
    	</div>
    </div>
<?php */ ?>

	<div class="col col-12">
		<h3 class="text-orange">4. ข้อมูลผู้รับ</h3>
		<hr />
	</div>

	<div class="col col-12">
    	
    	<p class="small text-secondary">กรุณาระบุข้อมูลผู้รับพัสดุเป็นภาษาอังกฤษอย่างครบถ้วน</p>
    	
    	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_shipment_step5') }}">
	 
    	 	<input type="hidden" name="country" id="country" value="{{ session('liff.country') }}" />
    
    		<label for="firstname" class=" form-control-label">ชื่อจริง / firstname</label>
    		<input type="text" id="firstname" name="firstname" class="form-control required" placeholder="" required maxlength="50"/>
    		<div id="firstname_help" class="help text-danger small"></div>
    		
    		<label for="lastname" class=" form-control-label">นามสกุล / surname</label>
    		<input type="text" id="lastname" name="lastname" class="form-control" placeholder="" maxlength="50"/>
    		<div id="lastname_help" class="help text-danger small"></div>
    		
    		<label for="company" class=" form-control-label">บริษัท / company</label>
    		<input type="text" id="company" name="company" class="form-control" placeholder="" maxlength="50"/>
    		<div id="company_help" class="help text-danger small"></div>
    		
    		<label for="email" class=" form-control-label">อีเมล์ / email</label>
    		<input type="email" id="email" name="email" class="form-control required" placeholder="" required maxlength="50"/>
    		<div id="email_help" class="help text-danger small"></div>
    		
    		<label for="phonenumber" class=" form-control-label">เบอร์โทรศัพท์ / phone number</label>
    		<input type="tel" id="phonenumber" name="phonenumber" class="form-control required" placeholder="" required maxlength="50"/>
    		<div id="phonenumber_help" class="help text-danger small"></div>
    		
    		<label for="address1" class=" form-control-label">ที่อยู่ / street address</label>
    		<input type="text" id="address1" name="address1" class="form-control required" placeholder="" required maxlength="50"/>
    		<input type="text" id="address2" name="address2" class="form-control" placeholder="" maxlength="50" style="margin-top: 5px;"/>
    		<div id="address_help" class="help text-danger small"></div>
    		
    		<label for="state" class=" form-control-label">รัฐ / state</label>
    		<input type="text" id="state" name="state" class="form-control required" placeholder="" required />
    		
    		<label for="city" class=" form-control-label">เมือง / city</label>
    		<input type="text" id="city" name="city" class="form-control required" placeholder="" required />
    		
    		<label for="postcode" class=" form-control-label">รหัสไปรษณีย์ / postal code</label>
    		<input type="text" id="postcode" name="postcode" class="form-control required" placeholder="" required />
    		
    		<label for="note" class=" form-control-label">ข้อความเพิ่มเติม</label>
        	<input type="text" id="note" name="note" class="form-control" placeholder="" />
        	
        	<div id="submit_form" class="row">
        		<div class="col col-12 ">
            		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 ">ต่อไป</button>
                	<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;margin-top: 10px;" onclick="history.back();">ย้อนกลับ</button>
            	</div>
            </div>
    
        </form>

   	</div>

</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){
	
	autocompleteState();

	//validate form
 	var validateEnglish = new RegExp(/^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$/);
 	$('#firstname').on('change',function(){
 		if(!validateEnglish.test($(this).val())){
 	 		$("#firstname_help").text("กรุณากรอกเป็นภาษาอังกฤษ");
 		}else if($(this).val().length > 50){
 			$("#firstname_help").text("ความยาวไม่ควรเกิน 50 ตัวอักษร");
 		}else{
 			$("#firstname_help").text("");
 		}
 	});
 	$('#lastname').on('change',function(){
 		if(!validateEnglish.test($(this).val())){
 	 		$("#lastname_help").text("กรุณากรอกเป็นภาษาอังกฤษ");
 		}else if($(this).val().length > 50){
 			$("#lastname_help").text("ความยาวไม่ควรเกิน 50 ตัวอักษร");
 		}else{
 			$("#lastname_help").text("");
 		}
 	});
 	$('#company').on('change',function(){
 		if(!validateEnglish.test($(this).val())){
 	 		$("#company_help").text("กรุณากรอกเป็นภาษาอังกฤษ");
 		}else if($(this).val().length > 50){
 			$("#company_help").text("ความยาวไม่ควรเกิน 50 ตัวอักษร");
 		}else{
 			$("#company_help").text("");
 		}
 	});
 	$('#email').on('change',function(){
 		if(!validateEnglish.test($(this).val())){
 	 		$("#email_help").text("กรุณากรอกเป็นภาษาอังกฤษ");
 		}else if(!validateEmailFormat($(this).val())){
 			$("#email_help").text("รูปแบบอีเมล์ไม่ถูกต้อง");
 		}else if($(this).val().length > 50){
 			$("#email_help").text("ความยาวไม่ควรเกิน 50 ตัวอักษร");
 		}else{
 			$("#email_help").text("");
 		}
 	});

 	$('html, body').animate({
    	scrollTop: 300
    }, 500);
    
});
function autocompleteState(){

	var _country = $('#country').val();
	
	$('#state').autocomplete({
        minLength: 0,
        source: function( request, response ) {
          $.ajax({
            url: "{{ url('/liff/states_query') }}",
            type: "POST",
            dataType: "json",
            data: {
              term : request.term,
              country: _country
            },
            success: function(data) {
            	
				var array = $.map(data, function (item) { //alert(array);
                    return {
                      label: item['name'],
                      value: item['name'],
                      data : item
                    }
                });
              	response(array);
            }
          });
        },
        select: function( event, ui ) {
           	var data = ui.item.data;   
    		$(this).val(data.code);
    		$("#state_desc").text("state code: " + data.code);
    		$("#admin_state_hidden").val(data.code);
        }
      });
}
-->
</script>
@endsection