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
    
    	@include('left_account_menu')

        <div class="col-md-10">
        	
        	<h2>{!! FT::translate('myaccount.heading') !!}</h2>
    		<hr />
    	
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
    	            
    	            <div class="col-md-12 text-center small"><a href="{{url ('/edit_customer')}}"><i class="fa fa-edit" title="แก้ไข"></i> {!! FT::translate('button.edit') !!}</a></div>
                    <div class="clearfix"></div>
    	            <br />

                </div>
    		</div>
    
        	<div class="panel panel-primary" style="display: none;">
    			<div class="panel-heading">เอกสารที่เกี่ยวข้อง</div>
    		    <div class="panel-body">
    
    				<div class="col-md-8 col-md-offset-2 col-xs-12">
    					<form id="payment_form" class="form-horizontal" method="post" action="{{url ('credit/create')}}" enctype="multipart/form-data">
		
                    		{{ csrf_field() }}

            				<label class="col-md-4 control-label" style="padding-top: 2px;">อัพโหลดเอกสาร</label>	
            				<div class="col-md-8">
            					<input type="file" class="choose-file" name="slip" required />
            					<button type="submit" class="btn btn-info" style="vertical-align: top;">Upload</button>
            				</div>

                        </form>
    				</div>
    				<div class="clearfix"></div><br />
    				
    				<div class="col-md-12">
 
    					<table class="table table-hover table-striped">
                        <thead>
                        	<tr>
                        		<td class="hidden-xs" width="5%">ลำดับ</td>
                        		<td>ชื่อเอกสาร</td>
                        		<td class="hidden-xs" width="15%">สร้างวันที่</td>
                        		<td width="20%"></td>
                        	</tr>
                        </thead>
                        <tbody>
                        	<tr>
                        		<td class="hidden-xs" >1</td>
                        		<td>สำเนาบัตรประชาชน</td>
                        		<td class="hidden-xs">{{ date("d/m/Y") }}</td>
                        		<td>
                        		<button type="button" class="btn btn-success btn-sm"><i class="fa fa-download"></i> ดาวน์โหลด</button>
                        		<button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> ลบ</button>
                        		</td>
                        	</tr>
                        </tbody>
                        </table>
    				</div>

    		    </div>
    		</div>
        </div>
    	<div class="clearfix"></div>
    	<br />
    </div>
</div>
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