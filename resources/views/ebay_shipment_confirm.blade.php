@extends('layout')
@section('content')
<?php 
if(isset($_REQUEST['account'])){
    $account = $_REQUEST['account'];
}else{
    $account = "";
}
?>
<div class="conter-wrapper">
	<div class="row">			
	    <div class="col-md-12">
	    	
	    	<div>
	    	<form id="shipment_form" name="shipment_form" class="form-horizontal" method="get" action="{{url ('import_ebay')}}">
        
        		{{ csrf_field() }}


    	    	Account: 
    	    	<select name="account" onchange="this.form.submit()">
    	    	@foreach($customer_channels as $channel)
    	    	@if($account == $channel->CUST_ACCOUNTNAME)
    	    	<option selected>{{ $channel->CUST_ACCOUNTNAME }}</option>
    	    	@else
    	    	<option>{{ $channel->CUST_ACCOUNTNAME }}</option>
    	    	@endif
    	    	@endforeach
    	    	</select>
	    	</form>
	    	</div>
	    	<br />
	    	
	    	
	    	<div class="panel panel-primary">
				<div class="panel-heading">{!! FT::translate("import_ebay.heading") !!}</div>
				<div class="panel-body">
					
					<table class="table table-stripe table-left small feed-table">
					<thead>
						<tr>
							<td width="2%">#</td>
							<td width="5%">{!! FT::translate('label.create_date') !!}</td>
							<td>{!! FT::translate('label.receiver') !!}</td>
							<td>{!! FT::translate('label.address') !!}</td>
							<td width="15%">{!! FT::translate('label.detail_declare') !!}</td>
							<td width="10%">{!! FT::translate('label.weight_dimension') !!}</td>
							<td width="20%">{!! FT::translate('label.select_agent') !!}</td>
						</tr>
					</thead>
					<tbody>
					<?php 
					if(is_array($upload_data) && sizeof($upload_data)>0):
					$cnt = 0;
					foreach($upload_data as $data):
					?>
						<tr id="import_form<?php echo $cnt; ?>">
							<td style="vertical-align: top !important;"><?php echo ($cnt+1); ?></td>
							<td style="vertical-align: top !important;"><?php echo date("d/m/y",strtotime($data['CreateDate'])); ?></td>
							<td style="vertical-align: top !important;">
								<div class="col-md-6 col-xs-12">
									<label>Firstname *</label>
									<input type="text" name="firstname" value="{{ $data['Receiver_Firstname'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								<div class="col-md-6 col-xs-12">
									<label>Lastname</label>
									<input type="text" name="lastname" value="{{ $data['Receiver_Lastname'] }}" class="form-control input-sm" onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								
								<div class="col-md-6 col-xs-12">
									<label>Company</label>
									<input type="text" name="company" value="{{ $data['Receiver_Company'] }}" class="form-control input-sm" onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								<div class="col-md-6 col-xs-12">
									<label>Tax ID</label>
									<input type="text" name="taxid" value="" class="form-control input-sm" onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								
								<div class="col-md-6 col-xs-12">
									<label>Email *</label>
									<input type="text" name="email" value="{{ $data['Receiver_Email'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);"  />
								</div>
								<div class="col-md-6 col-xs-12">
									<label>Telephone *</label>
									<input type="text" name="phonenumber" value="{{ $data['Receiver_PhoneNumber'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								
								
								<div style="clear:both;"></div><br />
								
								Ref: {{ $data['Reference'] }} <br />
								Account: {{ $data['RefAccount'] }}<br />
								<span class="small">Note: <?php echo nl2br($data['Remark']); ?></span>
							</td>
							<td style="vertical-align: top !important;">
								<div class="col-md-12 col-xs-12">
									<label>Address *</label>
									<input type="text" name="address1" value="{{ $data['Receiver_AddressLine1'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								<div class="col-md-12 col-xs-12" style="margin-top:5px;">
									<input type="text" name="address2" value="{{ $data['Receiver_AddressLine2'] }}" class="form-control input-sm" onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								
								<div class="col-md-6 col-xs-12">
									<label>City *</label>
									<input type="text" name="city" value="{{ $data['Receiver_City'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								<div class="col-md-6 col-xs-12">
									<label>State *</label>
									<input type="text" name="state" value="{{ $data['Receiver_State'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								
								<div class="col-md-6 col-xs-12">
									<label>Postcode *</label>
									<input type="text" name="postcode" value="{{ $data['Receiver_Postcode'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								</div>
								<div class="col-md-6 col-xs-12">
									<label>Country</label><br />
									{{ isset($countries[$data['Receiver_Country']])?$countries[$data['Receiver_Country']]:$data['Receiver_Country'] }}
								</div>
							</td>
							<td style="vertical-align: top !important;">
								<label>Description *</label>
								<input type="text" name="category" value="{{ $data['DeclareType'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								<label>Qty *</label>
								<input type="number" name="amount" value="{{ $data['DeclareQty'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
								<label>Declare value * (THB)</label>
								<input type="text" name="value" value="{{ $data['DeclareValue'] }}" class="form-control input-sm" required onblur="getShippingRate(<?php echo $cnt; ?>);" />
                            </td>
							<td style="vertical-align: top !important;">
								<label>Weight * (gram)</label>
								<input type="number" name="weight" onblur="getShippingRate(<?php echo $cnt; ?>);" class="form-control input-sm" required value="<?php echo $data['Weight']; ?>"  min="1" />
								<label>Width (cm)</label>
								<input type="number" name="width" onblur="getShippingRate(<?php echo $cnt; ?>);" class="form-control input-sm" required value="<?php echo $data['Width']; ?>" min="0" />
								<label>Length (cm)</label>
								<input type="number" name="length" onblur="getShippingRate(<?php echo $cnt; ?>);" class="form-control input-sm" required value="<?php echo $data['Length']; ?>" min="0" />
								<label>Height (cm)</label>
								<input type="number" name="height" onblur="getShippingRate(<?php echo $cnt; ?>);" class="form-control input-sm" required value="<?php echo $data['Height']; ?>" min="0" />
								
							</td>
                            <td style="vertical-align: top !important;">
                            <form class="form-horizontal" method="post" action="{{url ('/shipment/import')}}">
			
								{{ csrf_field() }}
								
	                            <div class="agent-div" style="color:red;" ><?php echo $data['ShippingAgent']; ?></div>
                            <?php /* 
                            	<input type="hidden" name="company" value="<?php echo $data['Receiver_Company']; ?>" />
								<input type="hidden" name="firstname" value="<?php echo $data['Receiver_Firstname']; ?>" />
								<input type="hidden" name="lastname" value="<?php echo $data['Receiver_Lastname']; ?>" />
								<input type="hidden" name="phonenumber" value="<?php echo $data['Receiver_PhoneNumber']; ?>" />
								<input type="hidden" name="email" value="<?php echo $data['Receiver_Email']; ?>" />
								<input type="hidden" name="address1" value="<?php echo $data['Receiver_AddressLine1']; ?>" />
								<input type="hidden" name="address2" value="<?php echo $data['Receiver_AddressLine2']; ?>" />
								<input type="hidden" name="city" value="<?php echo $data['Receiver_City']; ?>" />
								<input type="hidden" name="state" value="<?php echo $data['Receiver_State']; ?>" />
								<input type="hidden" name="postcode" value="<?php echo $data['Receiver_Postcode']; ?>" />
								
								
								<input type="hidden" name="weight" value="<?php echo $data['Weight']; ?>" />
								<input type="hidden" name="width" value="<?php echo $data['Width']; ?>" />
								<input type="hidden" name="height" value="<?php echo $data['Height']; ?>" />
								<input type="hidden" name="length" value="<?php echo $data['Length']; ?>" />
								<input type="hidden" name="category" value="<?php echo $data['DeclareType']; ?>" />
								<input type="hidden" name="value" value="<?php echo $data['DeclareValue']; ?>" />
								
								<input type="hidden" name="amount" value="<?php echo $data['DeclareQty']; ?>" />
								*/ ?>
								<input type="hidden" name="country" value="<?php echo $data['Receiver_Country']; ?>" />
								<input type="hidden" name="term" value="<?php echo $data['TermOfTrade']; ?>" />
								<input type="hidden" name="note" value="<?php echo $data['Remark']; ?>" />
								<input type="hidden" name="orderref" value="<?php echo $data['Reference']; ?>" />
								<input type="hidden" name="refaccount" value="<?php echo $data['RefAccount']; ?>" />
								
								
								<br />
                            
                                <div class="" >
                                	<button type="button" class="btn btn-success btn-sm submit-btn-group" disabled onclick="importShipment(<?php echo $cnt; ?>)">นำเข้า</button>
                                	<button type="button" class="btn btn-danger btn-sm" onclick="cancelShipment(<?php echo $cnt; ?>)">ลบ</button>
                                </div>
                            
                            </form>
                            
                            </td>
							
						</tr>
					<?php 
					$cnt++;
					endforeach;
					endif;
					?>
					</tbody>
					</table>
				</div>
	        </div>
	        
	        <div class="col-md-12 text-center">
<!-- 	        	<a href="/create_pickup"><button type="button" class="btn btn-success">จัดการพัสดุรอส่ง</button></a> -->
	        </div>
	    </div>
	    
	</div>
</div>

<script type="text/javascript">

    function getShippingRate(cnt){

    	var valid = true;
    	var errorText ="";
    	
    	var token = $("#import_form" + cnt + " [name=_token]").val();
    	
    	var _weight = $("#import_form" + cnt + " [name=weight]").val();
    	var _width = $("#import_form" + cnt + " [name=width]").val();
    	var _height= $("#import_form" + cnt + " [name=height]").val();
    	var _length = $("#import_form" + cnt + " [name=length]").val();
    	
    	var _firstname = $("#import_form" + cnt + " [name=firstname]").val();
    	var _lastname = $("#import_form" + cnt + " [name=lastname]").val();
    	var _company = $("#import_form" + cnt + " [name=company]").val();
    	var _taxid = $("#import_form" + cnt + " [name=taxid]").val();
    	var _email = $("#import_form" + cnt + " [name=email]").val();
    	var _phonenumber = $("#import_form" + cnt + " [name=phonenumber]").val();
    	var _address1 = $("#import_form" + cnt + " [name=address1]").val();
    	var _address2 = $("#import_form" + cnt + " [name=address2]").val();
    	var _city = $("#import_form" + cnt + " [name=city]").val();
    	var _state = $("#import_form" + cnt + " [name=state]").val();
    	var _postcode = $("#import_form" + cnt + " [name=postcode]").val();
    	var _country = $("#import_form" + cnt + " [name=country]").val();

    	var _category = $("#import_form" + cnt + " [name=category]").val();
    	var _amount = $("#import_form" + cnt + " [name=amount]").val();
    	var _value = $("#import_form" + cnt + " [name=value]").val();
    	
    	if(_weight <= 0 || _width < 0 || _height < 0 || _length < 0){
    		errorText += "{!! FT::translate('error.weight_dimension_required') !!}<br />";
    		valid = false;
        }
        if(_email == ""){
        	errorText += "{!! FT::translate('error.email_required') !!}<br />";
        	valid = false;
        }
        if(_firstname == "" ){
        	errorText += "{!! FT::translate('error.receiver_required') !!}<br />";
        	valid = false;
        }
        if(_phonenumber == "" ){
        	errorText += "{!! FT::translate('error.telephone_required') !!}<br />";
        	valid = false;
        }
        if(_address1 == "" || _city == "" || _state == "" || _postcode == ""){
        	errorText += "{!! FT::translate('error.address_required') !!}<br />";
        	valid = false;
        }
        if(_category == "" || _amount == ""|| _value == ""){
        	errorText += "{!! FT::translate('error.declare_required') !!}<br />";
        	valid = false;
        }
        
        if(valid == false){
            $("#import_form" + cnt + " .agent-div").html(errorText);
            $("#import_form" + cnt + " .submit-btn-group").attr("disabled",true);
            return false;
        }
    	
    	$.post("{{url ('shipment/get_rate')}}",
    	{
    		_token: token,
    		country: _country,
    		weight: _weight,
    		width: _width,
    		height: _height,
    		length: _length,
    		type: 'box' ,
    	},function(data){

    		console.log(data);
    		var content = "";
    		
    		if(data !== false && data != ""){
	            var dataArray = $.map(data, function(value, index) {
	                return [value];
	            });
	            var keyArray = $.map(data, function(value, index) {
	                return [index];
	            });   
    		

        		content += "<select name='agent' class='form-control' required>";
                content += "<option value='' >{!! FT::translate('dropdown.default.shipment_method') !!}</option>";
        		for (key in dataArray) {
                    if (dataArray.hasOwnProperty(key)) {
    
                       var _agent = dataArray[key]['Name'];
                       var _type = dataArray[key]['Type'];
                       var _deliveryTime = dataArray[key]['DeliveryTime'];
                       var _value = dataArray[key]['AccountRate'];
                       var _valueMax = dataArray[key]['StandardRate'];
    
                       var _displayAgent = _agent.replace(/_/g, " ");
                       if(_displayAgent == "GM Packet Economy"){
                    	   _displayAgent = "GM Economy";
                       }else if(_displayAgent == "GM Packet"){
                    	   _displayAgent = "GM Non-Registered";
                       }else if(_displayAgent == "GM Packet Plus"){
                    	   _displayAgent = "GM Registered";
                       }else if(_displayAgent == "DHL"){
                    	   _displayAgent = "FastShip Express";
                       }else if(_displayAgent == "UPS"){
                    	   _displayAgent = "UPS Express";
                       }else if(_displayAgent == "SF"){
                    	   _displayAgent = "SF Express";
                       }else if(_displayAgent == "SF EEP"){
                    	   _displayAgent = "SF Standard";
                       }else if(_displayAgent == "FS"){
                    	   _displayAgent = "FastShip Express";
                       }else if(_displayAgent == "FS Standard"){
                    	   _displayAgent = "FastShip Standard";
                       }else if(_displayAgent == "FS Epacket"){
                    	   _displayAgent = "FastShip E-Packet";
                       }else if(_displayAgent == "Ecom PD"){
                    	   _displayAgent = "Parcel Direct";
                       }
    
                       console.log(_displayAgent+": "+_deliveryTime+" " +_value);
      
       				   content += "<option value='" + _agent + "' ><b>"+ _displayAgent + "</b> (" + _value + " บาท) - " + _deliveryTime + "</option>";
       				
       				
                    }
        		}
        		content += "</select>";

        		$("#import_form" + cnt + " .submit-btn-group").attr("disabled",false);
        		

			}else{
				content = "ไม่พบวิธีการส่ง";
				$("#import_form" + cnt + " .submit-btn-group").attr("disabled",true);
    		}

    		$("#import_form" + cnt + " .agent-div").html(content);

    	},"json");
            return false;
            

    }

	function importShipment(cnt){

		//return false;
		
		var incart = parseInt($("#cart_cnt").text());
		
		if($("#import_form" + cnt + " [name=agent]").val() == "") return false;
		
		$.post("{{url ('shipment/import')}}",
		{
			_token: $("#import_form" + cnt + " [name=_token]").val(),
			company: $("#import_form" + cnt + " [name=company]").val(),
			firstname: $("#import_form" + cnt + " [name=firstname]").val(),
			lastname: $("#import_form" + cnt + " [name=lastname]").val(),
			phonenumber: $("#import_form" + cnt + " [name=phonenumber]").val(),
			email: $("#import_form" + cnt + " [name=email]").val(),
			address1: $("#import_form" + cnt + " [name=address1]").val(),
			address2: $("#import_form" + cnt + " [name=address2]").val(),
			city: $("#import_form" + cnt + " [name=city]").val(),
			state: $("#import_form" + cnt + " [name=state]").val(),
			postcode: $("#import_form" + cnt + " [name=postcode]").val(),
			country: $("#import_form" + cnt + " [name=country]").val(),
			weight: $("#import_form" + cnt + " [name=weight]").val(),
			width: $("#import_form" + cnt + " [name=width]").val(),
			height: $("#import_form" + cnt + " [name=height]").val(),
			length: $("#import_form" + cnt + " [name=length]").val(),
			category: $("#import_form" + cnt + " [name=category]").val(),
			amount: $("#import_form" + cnt + " [name=amount]").val(),
			value: $("#import_form" + cnt + " [name=value]").val(),
			term: $("#import_form" + cnt + " [name=term]").val(),
			note: $("#import_form" + cnt + " [name=note]").val(),
			orderref: $("#import_form" + cnt + " [name=orderref]").val(),
			agent: $("#import_form" + cnt + " [name=agent]").val(),
			refaccount: $("#import_form" + cnt + " [name=refaccount]").val(),
			source: 'EbayFeed',
		},function(data){

			if(data !== false){

				$("#cart_cnt").text(incart+1);
				$("#cart_cnt_mob").text(incart+1);
				
				$("#import_form" + cnt).fadeOut(500);
			}else{
				console.log("error");
			}
			console.log(data); return false;
	            
		},"json");
	}

	function cancelShipment(cnt){
		if(confirm("{!! FT::translate('confirm.delete_shipment') !!}")){

			$.post("{{url ('shipment/cancel_ebay')}}",
			{
				_token: $("#import_form" + cnt + " [name=_token]").val(),
				orderref: $("#import_form" + cnt + " [name=orderref]").val(),
			},function(data){

				if(data !== false){
					$("#import_form" + cnt).fadeOut(500);
				}else{
					console.log("error");
				}
				return false;
		            
			},"json");
			
			
		}
		return false;
	}
</script>

@endsection
