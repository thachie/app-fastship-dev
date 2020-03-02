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
		<div class="col-md-10 col-md-offset-1">
			<h2>{!! FT::translate('myaccount.heading') !!}</h2>
		</div>
	</div>  
	<div class="row">
	    <div class="col-md-5 col-md-offset-1">
		    <div class="panel panel-primary">
				<div class="panel-heading">{!! FT::translate('myaccount.panel.heading1') !!}</div>
		        <div class="panel-body">

	                <div class="<?php echo $addressDiv; ?> col-xs-12 no-padding" style="line-height: 30px;">
	                
		                <?php if(isset($customer_data['company']) && $customer_data['company']): ?>
		                    
		                    <div class="col-md-12"><h3><?php echo $customer_data['company']; ?></h3></div>
		                    <div class="clearfix"></div>
		                    
		                    <?php if(isset($customer_data['taxid']) && $customer_data['taxid']): ?>
			                    <div class="col-md-12"><h4>{!! FT::translate('label.taxid') !!}: <?php echo $customer_data['taxid']; ?></h4></div>
		                    	<div class="clearfix"></div>
	                    	<?php endif; ?>
		                <?php endif; ?>
		                
						<div class="col-md-12">
						<?php if($customer_data['taxid'] == ""): ?>
							<h4><?php echo $customer_data['firstname']." ".$customer_data['lastname']; ?></h4>
						<?php else: ?>
							<?php echo $customer_data['firstname']." ".$customer_data['lastname']; ?>
						<?php endif; ?>
	                    </div>
						
	                    <div class="clearfix"></div>
	                    
	                    <div class="col-md-12">
	                    	<?php echo $customer_data['address1']; ?>
	                    	<?php if(isset($customer_data['address2']) && $customer_data['address2']): ?>
	                    		<?php echo " " . $customer_data['address2']; ?>
	                    	<?php endif; ?>
	                    	<?php echo " " . $customer_data['city'] . " " . $customer_data['state']; ?>
	                    	<?php echo " " . $customer_data['postcode'] . " " . (($customer_data['country'])?$countries[$customer_data['country']]:""); ?>
	                    </div>
	                    <div class="clearfix"></div>

		                <div class="col-md-12">{!! FT::translate('label.email') !!}: <?php echo $customer_data['email']; ?></div>
		                <div class="clearfix"></div>
		                    
	                    <div class="col-md-12">{!! FT::translate('label.telephone') !!}: <?php echo $customer_data['phonenumber']; ?></div>
		                <div class="clearfix"></div>
		                <br />
 
	                </div>
	                <?php if($customer_data['latitude'] != ""): ?>
	                <div class="col-md-7 col-xs-12 no-padding">
	                	<div class="col-md-12 col-xs-12" style="height: 240px;">
			                <div id="map" height="240px" width="480px"></div>
			               	<div id="message"></div>
			             </div>
	                </div>
	                <?php endif; ?>
	                <div class="clearfix"></div>
		            <br />
		            
		            <div class="col-md-12 text-center"><a href="{{url ('/edit_customer')}}"><i class="fa fa-edit" title="แก้ไข"></i> {!! FT::translate('button.edit') !!}</a></div>
	                <div class="clearfix"></div>
		            <br />
		                
		                
	            </div>
			</div>
	    </div>

	    <div class="col-md-5">
	    	<div class="panel panel-primary">
				<div class="panel-heading">{!! FT::translate('myaccount.panel.heading2') !!}</div>
			    <div class="panel-body">

						<div class="col-md-12">
							<h3>{!! FT::translate('myaccount.panel.heading4') !!}</h3>
							@if(sizeof($creditCards) > 0)
							@foreach($creditCards as $card)
							<div class="col-md-6">
								<div class=" block-primary" style="position: relative;">
									<span class="creditbar"></span>
									<span class="close"><a href="javascript:deleteCreditCard('<?php echo $card->ID;?>');"><i class="fa fa-trash-o"></i></a></span>
									<h5 class="accountno">XXXXX-XXX-<?php echo $card->OMISE_LASTDIGITS;?></h5>
									<h6><?php echo $card->OMISE_CARDNAME;?></h6>
									<p><?php echo $card->OMISE_BANK;?></p>
								</div>
							</div>
							@endforeach
							@endif
						</div>
	
			    		<div class="col-md-12">
				    		<h4>{!! FT::translate('myaccount.panel.heading3') !!}</h4>
				    		<form id="checkout" name="creditcard_form"  class="form-horizontal" method="post" action="{{url ('/credit/add_creditcard')}}">	
				    			<div id="token_errors"></div>
								<input type="hidden" name="command" value="collect-card" />
								<input type="hidden" name="back_url" value="" />
								<input type="hidden" name="backToaddCredit" value="" />
								<input type="hidden" name="omise_token">
								<input type="hidden" id="card_number" name="card_number">
								<input type="hidden" id="cvv_number" name="cvv_number">                        
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
			                    	<input id="create_token" name="create_token" type="submit" class="col-md-4 col-md-offset-4 btn btn-primary" value="{!! FT::translate('button.add_card') !!}" />
			                    </div> 
			                </form>
				    	</div>

			    </div>
			</div>
	    </div>
	</div>
	<div class="clearfix"></div>
	<br />
</div>

<form id="delete_form" name="delete_form" method="post" action="{{url ('/credit/delete_creditcard')}}">	                        
	{{ csrf_field() }}
	<input type="hidden" name="card_id" />
</form>
	        				
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARGo6QU60StUz58XsOHjLs4Dg3UFllE4w&callback=initMap">
</script>
<script type="text/javascript">
	var map;
    var marker;
    var infowindow;

    function initMap() {
        
    	<?php if($customer_data['latitude'] == ""): ?>
    	return false;
    	<?php else: ?>
    	
        infowindow = new google.maps.InfoWindow({
          content: document.getElementById('message')
        });

        
        var save_pos = {lat: <?php echo $customer_data['latitude']; ?>, lng: <?php echo $customer_data['longitude']; ?>};
        map = new google.maps.Map(document.getElementById('map'), {
          center: save_pos,
          draggable: false,
          disableDefaultUI: false,
          clickableIcons: false,
          fullscreenControl: false,
          keyboardShortcuts: false,
          mapTypeControl: false,
          scaleControl: false,
          scrollwheel: false,
          streetViewControl: false,
          zoomControl: false,
          zoom: 15
        });
        marker = new google.maps.Marker({
            position: save_pos,
            map: map
        });
        <?php endif; ?>
      }

    function deleteCreditCard(id){
		$("#delete_form input[name=card_id]").val(id);
		$("#delete_form").submit();
    }
    
</script>
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