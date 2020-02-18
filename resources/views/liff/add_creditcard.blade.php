@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

   	<div class="col col-12">
    	<h3 class="text-orange">{!! FT::translate('myaccount.panel.heading3') !!}</h3>
    </div>
    <hr />

	<form id="checkout" name="creditcard_form" class="form-horizontal" method="post" action="{{url ('/liff/action/add_creditcard')}}">	
    	<div class="row">
    		
    		<input type="hidden" name="command" value="collect-card" />
    		<input type="hidden" name="back_url" value="" />
    		<input type="hidden" name="backToaddCredit" value="" />
    		<input type="hidden" name="omise_token">
    		<input type="hidden" id="card_number" name="card_number">
    		<input type="hidden" id="cvv_number" name="cvv_number">
    				
    		<div class="col-12 col">
    				
    			<div id="token_errors" class="text-danger"></div>
    
    			<label for="holder_name" class=" form-control-label">{!! FT::translate('label.cardname') !!}</label>
    			<input type="text" id="holder_name" name="holder_name" data-omise="holder_name" class="form-control required" placeholder="" required maxlength="50" />
    			<div id="holder_name_help" class="help text-danger small"></div>
    
    			<label for="number" class=" form-control-label">{!! FT::translate('label.cardnumber') !!}</label>
    			<input type="number" id="number" name="number" data-omise="number" class="form-control required" placeholder="" required maxlength="16" />
    			<div id="number_help" class="help text-danger small"></div>
    			
    			<label for="expiration_month" class=" form-control-label">{!! FT::translate('label.expire_date') !!}</label>
    			<div class="d-inline-flex">
        			<div class="d-inline" style="margin-right: 10px;">
        				<input type="number" id="expiration_month" name="expiration_month" data-omise="expiration_month" min="0" maxlength="2" class="form-control" placeholder="MM" required/>
                    </div>
        			<div class="d-inline">
        				<input type="number" id="expiration_year" name="expiration_year" data-omise="expiration_year" min="0" maxlength="4" class="form-control" placeholder="YYYY" required/>
                    </div>
                </div>
                
                <label for="security_code" class=" form-control-label">{!! FT::translate('label.cvv') !!}</label>
    			<input type="number" id="security_code" name="security_code" data-omise="security_code" class="form-control required" placeholder="CVV" required maxlength="4" />
    			<div id="number_help" class="help text-danger small"></div>
    
    		</div>
    
        </div> 
        <div class="row mt-20">
        	<div class="col col-12 text-center">
    			<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 ">เพิ่มบัตร</button>
        		<button type="button" class="btn bg-light btn-block btn-sm border-0" onclick="history.back();">ย้อนกลับ</button>
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
<script src="https://cdn.omise.co/omise.js"></script>
<script>
<!--
  Omise.setPublicKey("pkey_57wb6hkyv6qi2e4ft0k");
  $("#checkout").submit(function () {

	  var form = $(this);
	  //alert($("#number").val());
	  //alert($("#security_code").val());
	  document.getElementById('card_number').value = $("#number").val();
	  document.getElementById('cvv_number').value = $("#security_code").val();
	  // Disable the submit button to avoid repeated click.
	  form.find("button[type=submit]").prop("disabled", true);
	  //form.find("button[type=submit]").prop("disabled", true);

	  // Serialize the form fields into a valid card object.
	  var card = {
	    "name": form.find("[data-omise=holder_name]").val(),
	    "number": form.find("[data-omise=number]").val(),
	    "expiration_month": form.find("[data-omise=expiration_month]").val(),
	    "expiration_year": form.find("[data-omise=expiration_year]").val(),
	    "security_code": form.find("[data-omise=security_code]").val()
	  };
	  
	  // Send a request to create a token then trigger the callback function once
	  // a response is received from Omise.

	  // Note that the response could be an error and this needs to be handled within
	  // the callback.
	  Omise.createToken("card", card, function (statusCode, response) {

	    //if (response.object == "error" || !response.card.security_code_check) {
	    if (response.object == "error" ) {
	      // Display an error message.
	      var message_text = "SET YOUR SECURITY CODE CHECK FAILED MESSAGE";
	      if(response.object == "error") {
	        message_text = response.message;
	      }
	      $("#token_errors").html(message_text);

	      // Re-enable the submit button.
	      //form.find("input[type=submit]").prop("disabled", false);
	      form.find("button[type=submit]").prop("disabled", false);
	      
	    } else {
		    
	      // Then fill the omise_token.
	      form.find("[name=omise_token]").val(response.id);
alert(response.id);
	      // Remove card number from form before submiting to server.
	      //form.find("[data-omise=number]").val("");
	      //form.find("[data-omise=security_code]").val("");

	      // submit token to server.
	      form.get(0).submit();
	      //form.submit();
	    };
	  });

	  // Prevent the form from being submitted;
	  return false;

	});
  -->
</script>
@endsection