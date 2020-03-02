@extends('layout')
@section('content')
<?php //alert($customer_data); ?>
<?php 
if($customer_data['latitude'] == ""){
	$addressDiv = "col-md-12";
}else{
	$addressDiv = "col-md-5";
}
?>
<div class="conter-wrapper">

	<div class="row">
	    <div class="col-md-8 col-md-offset-2">
    	<h2>{!! FT::translate('myaccount.heading') !!} (เพิ่มบัตรเครดิต)</h2>
    	<div class="panel panel-primary">
			<div class="panel-heading">{!! FT::translate('myaccount.panel.heading2') !!}</div>
		    <div class="panel-body">

		    		<div class="col-md-12">
			    		<h4>{!! FT::translate('myaccount.panel.heading3') !!}</h4>
			    		<form id="checkout" name="creditcard_form"  class="form-horizontal" method="post" action="{{url ('/credit/add_new_creditcard')}}">	
			    			
			    			<div id="token_errors"></div>
							<input type="hidden" name="command" value="collect-card" />
							<input type="hidden" name="back_url" value="" />
							<input type="hidden" name="backToaddCredit" value="" />
							<input type="hidden" name="omise_token">
							<input type="hidden" id="card_number" name="card_number">
							<input type="hidden" id="cvv_number" name="cvv_number">                        
							<input type="hidden" id="pickupId" name="pickupId" value="{{$pickupId}}">   
							                     
	        				{{ csrf_field() }} 
	        				
			    			<div class="col-md-12">
		                    	<label for="holder_name" class="col-12 control-label">{!! FT::translate('label.cardname') !!}</label>
		                    	<input type="text" class="form-control required" name="holder_name" data-omise="holder_name" id="holder_name" required />
		                    </div>
			    			<div class="col-md-12">
		                    	<label for="number" class="col-12 control-label">{!! FT::translate('label.cardnumber') !!}</label>
		                    	<input type="text" class="form-control required" id="number" name="number" data-omise="number" value="" maxlength="16" required placeholder="Card Number">
		                    </div>
		                    <div class="col-md-4">
		                    	<label for="expiration_month" class="col-12 control-label">{!! FT::translate('label.expire_date') !!}</label>
		                    	<input type="text" name="expiration_month" data-omise="expiration_month" class="form-control required" maxlength="2" required placeholder="MM"/>
		                    </div>
		                    <div class="col-md-4">
		                    	<label for="expiration_year" class="col-12 control-label">&nbsp;</label>
		                    	<input type="text" name="expiration_year" data-omise="expiration_year" class="form-control required" maxlength="4" required placeholder="YYYY"/>
		                    </div>
		                    <div class="col-md-4">
		                    	<label for="security_code" class="col-12 control-label">{!! FT::translate('label.cvv') !!}</label>
		                    	<input type="text" id="security_code" name="security_code" data-omise="security_code"  class="form-control required" maxlength="4" required placeholder="CVV"/>
		                    </div>
		                    <div class="clearfix"></div>
		                    <br />
		                    
		                    <div class="text-center ">
		                    	<input id="create_token" name="create_token" type="submit" class="col-md-4 col-md-offset-4 btn btn-primary" value="{!! FT::translate('button.add_card') !!} และชำระเงินทันที" />
		                    </div> 
		                </form>
			    	</div>
		    </div>
		</div>
		<div class="col-md-3"></div>
	    </div>
	</div>
	<div class="clearfix"></div>
	<br />
</div>
<script src="https://cdn.omise.co/omise.js"></script>
<script>
  Omise.setPublicKey("pkey_57wb6hkyv6qi2e4ft0k");
  $("#checkout").submit(function () {

	  var form = $(this);
	  //alert($("#number").val());
	  //alert($("#security_code").val());
	  document.getElementById('card_number').value = $("#number").val();
	  document.getElementById('cvv_number').value = $("#security_code").val();
	  // Disable the submit button to avoid repeated click.
	  form.find("input[type=submit]").prop("disabled", true);
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
	  //
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
	      form.find("input[type=submit]").prop("disabled", false);
	      //form.find("button[type=submit]").prop("disabled", false);
	    } else {
	      // Then fill the omise_token.
	      form.find("[name=omise_token]").val(response.id);

	      // Remove card number from form before submiting to server.
	      form.find("[data-omise=number]").val("");
	      form.find("[data-omise=security_code]").val("");

	      // submit token to server.
	      form.get(0).submit();
	    };
	  });

	  // Prevent the form from being submitted;
	  return false;

	});

</script>
@endsection