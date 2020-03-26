@extends('layout')
@section('content')
<div class="conter-wrapper">
    <div class="row">
    	<div class="col-md-12"><h2>{!! FT::translate('create_shipment.heading') !!}</h2></div>
    </div>
    <form id="shipment_form" name="shipment_form" class="form-horizontal" method="post" action="{{url ('shipment/create')}}">
        
        {{ csrf_field() }}
        
        
        <input type="hidden" name="term" id="ddu" value="DDU" />
        <input type="hidden" name="agent" id="agent" />
        <input type="hidden" name="country" value="{{ $default['country_code'] }}" />
        <input type="hidden" name="ebay_id" value="{{ $default['ebay_id'] }}" />
        <input type="hidden" name="account" value="{{ $default['account'] }}" />
        <input name="company" type="hidden" value="" />
        <input name="orderref" type="hidden" value="{{ $default['orderref'] }}" />
                            
        
        <div class="row">
          
            
            <div class="col-md-7" >
                <div class="panel panel-primary">
                    <div class="panel-heading">{!! FT::translate('create_shipment.panel.heading2') !!}</div>
                    <div class="panel-body row-no-padding">

                        <div class="form-group col-md-6">
                        	<label class="gray small">First Name</label>
                            <input name="firstname" type="text" placeholder="Firstname" class="form-control required input-count" pattern="{{ $validateEnglish }}" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="{{ old('firstname',$default['firstname']) }}" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-6">
                        	<label class="gray small">Last Name</label>
                            <input name="lastname" type="text" placeholder="Lastname" class="form-control required input-count" pattern="{{ $validateEnglish }}" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="{{ old('lastname',$default['lastname']) }}" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="lastname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="lastname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-6">
                       		<label class="gray small">Phone Number</label>
                            <input name="phonenumber" type="text" placeholder="Phone Number" class="form-control required input-count" maxlength="50" value="{{ $default['phone'] }}" />
                       		<div class="red tiny text-left col-md-10 no-padding"><span id="phonenumber-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="phonenumber-count">0</span>/50</div> 
                        </div>
                        <div class="form-group col-md-6">
                        	<label class="gray small">Email</label>
                            <input name="email" type="text" placeholder="Email" title="Email" class="form-control required input-count" maxlength="50" value="{{ $default['email'] }}" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="email-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="email-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-12">
                        	<label class="gray small">Address</label>
                            <input name="address1" placeholder="Address" placeholder="Street Address" type="text" class="form-control required input-count" pattern="{{ $validateEnglish }}" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="{{ old('address1',$default['address1']) }}" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="address1-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address1-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="address2" placeholder="Address (cont.)" placeholder="Address (continue)" type="text" class="form-control input-count" pattern="{{ $validateEnglish }}" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="{{ old('address2',$default['address2']) }}" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="address2-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address2-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-6">
                        	<label class="gray small">City</label>
                            <input name="city" type="text" placeholder="City" class="form-control required input-count" pattern="{{ $validateEnglish }}" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="50" value="{{ old('city',$default['city']) }}" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="city-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="city-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-6">
                        	<label class="gray small">State</label>
                            <input name="state" type="text" placeholder="State" class="form-control required input-count" pattern="{{ $validateEnglish }}" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="50" value="{{ old('state',$default['state']) }}" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="state-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="state-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-6">
                        	<label class="gray small">Post Code</label>
                            <input name="postcode" type="text" placeholder="Postcode" class="form-control required input-count" maxlength="10" value="{{ old('postcode',$default['postcode']) }}" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="postcode-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="postcode-count">0</span>/10</div>
                        </div>
                        <div class="form-group col-md-6">
                        	<label class="gray small">&nbsp;</label>
                        	<div class="form-control">{{ $default['country'] }}</div>
                            <div class="col-md-12 no-padding">&nbsp;</div>
                        </div>   
                        <div class="form-group col-md-12">
                        	<label class="gray small">Note to Fastship (Not visible to a buyer)</label>
                            <input name="note" type="text" placeholder="" class="form-control input-count" maxlength="100" value="{{ old('remark','') }}">
                            <div class="red tiny text-left col-md-10 no-padding"><span id="note-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="note-count">0</span>/100</div>
                        </div>
                        <div class="form-group col-md-12 well">
                        	<h4>eBay Information</h4>
                        	<div class=""><span class="text-info">Order ID:</span> {{ $default['orderref'] }}</div>
                        	@if($default['remark'])
                            	<div class=""><span class="text-info">{{ $default['buyer'] }}:</span> {{ $default['remark'] }}</div>
                            @endif
                            <div class=""><span class="text-info">Agent:</span> {{ $default['agent_code'] }}</div>
                        </div>
                        
                              
                    </div>
                </div>
                
                <div class="panel panel-primary">
                    <div class="panel-heading">ประเภทสินค้า <span style="display:none;">{!! FT::translate('create_shipment.panel.heading2') !!}</span></div>
                    <div class="panel-body row-no-padding">
                		<table class="table table-hover table-ship">
                            <thead>
                            <tr>
                                <th scope="col" width="70%">{!! FT::translate('label.declare_type') !!} ({!! FT::translate('label.english') !!})</th>
                                <th scope="col" width="10%">{!! FT::translate('label.declare_qty') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_value') !!}</th>
                                <th> </th>
                            </tr>
                            </thead>
                            <tbody id="product_table">
                            @foreach($items as $key => $item)
                            <tr id='row{{ $key }}'>
                                <td>
                                   	<input type="text" name="category[{{ $key }}]" class="category form-control required" value="{{ $item['type'] }}" />
                                  	<div class="red tiny text-left col-md-10 no-padding"><span id="category0-error" class="error-msg"></span></div> 
                                </td>      
                                <td><input type="number" min="1" name="amount[{{ $key }}]" class="form-control declare-qty required text-right" value="{{ $item['qty'] }}" /></td>
                                <td><input type="number" min="1" name="value[{{ $key }}]" class="form-control declare-value required text-right" value="{{ $item['value'] }}" /></td>
                                @if($key == 0)
                                <td></td>
                                @else
                                <td><span class="glyphicon glyphicon-minus-sign text-danger" onclick="rmv({{ $key }})"></span></td>
                                @endif
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row detailpro">
                            <div class="col-md-6 pull-right text-right"><a href="javascript:add();"><i class="fa fa-plus-circle green"></i> {!! FT::translate('create_shipment.add_declare') !!}</a></div>
                            <div class="col-md-6 pull-left hidden-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item') !!}</a></div>
                        </div>
                   </div>
            	</div>
            	
            </div>
            <div class="col-md-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">ข้อมูลพัสดุ <span style="display:none;">{!! FT::translate('create_shipment.panel.heading2') !!}</span></div>
                    <div class="panel-body">
                    
                    	<div class="row" style="margin: 0;">
                            <label for="weight" class="col-md-3 control-label">{!! FT::translate('label.weight') !!}</label>
                            <div class="col-md-6">
                            	<input type="number" class="form-control required" id="weight" name="weight" min="1" max="299999" required onkeyup="calculateRate(false)" value="{{ old('weight','') }}" />
                            	<div class="red tiny text-left col-md-10 no-padding"><span id="weight-error" class="error-msg"></span></div> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 0;">
                            <label class="col-md-3 control-label">{!! FT::translate('label.packaging') !!}</label>
                            <div class="col-md-8">
                                <div class="radio">
                                <label><input type="radio" name="type" id="parcel" value="parcel" onclick="hidedimension()" checked />{!! FT::translate('radio.bag') !!}</label>
                                &nbsp;
                                <label><input type="radio" name="type" id="box" value="box" onclick="showdimension()" />{!! FT::translate('radio.carton') !!}</label>
                                </div>
                                <div class="small">{!! FT::translate('warning.bag') !!}</div>
                            </div>
                        </div>
                        <div class="row" id="dimension" style="display: none;">
                            <label for="inputtext" class="col-md-4 control-label">{!! FT::translate('label.dimension') !!}</label>
                            <div class="col-md-2">
                            <input type="number" id="width" name="width" class="form-control required" placeholder="{!! FT::translate('placeholder.width') !!}" onkeyup="calculateRate(false)" min="0" value="{{ old('width',$default['width']) }}" />
                            </div>
                            <div class="col-md-2">
                            <input type="number" id="length" name="length" class="form-control required" placeholder="{!! FT::translate('placeholder.length') !!}" onkeyup="calculateRate(false)"  min="0" value="{{ old('height',$default['height']) }}" />
                            </div>
                            <div class="col-md-2">
                            <input type="number" id="height" name="height" class="form-control required" placeholder="{!! FT::translate('placeholder.height') !!}" onkeyup="calculateRate(false)"  min="0" value="{{ old('length',$default['length']) }}" />
                            </div>
                            <div class="clearfix"></div>
                            
                            <label class="col-md-4 control-label">{!! FT::translate('label.volweight') !!}</label>
                            <div class="col-md-8"><span id="volumnWeight" style="line-height: 40px;">0</span> {!! FT::translate('unit.gram') !!}</div>
                        </div>
                        
                        <div id="weight_text" class="text-center small red" style="display: none;">{!! FT::translate('info.shipment_rate.weight_over_20kg') !!}</div>

                        <div class="clearfix"></div><br /> 
                        
                        <div class="col-md-6 pull-right visible-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item') !!}</a></div>

                        <div class="col-md-12 panel-fade fade" id="shipping-agents">
                            <div id="result-panel">
                                <h4 class="text-center">{!! FT::translate('shipment_rate.warning.required') !!}</h4> 
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/UPS.gif">
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/DHL.gif">
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/SF.gif">
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/Aramex.gif">
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/GM_Packet_Plus.gif">
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/USPS.gif">
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/FedEx_SmartPost.gif"> 
                                <img class="img-fade col-md-3 col-sm-6" src="/images/agent/GM_Packet_Economy.gif">
                            </div>
                        </div>
                    </div>
                               
                </div>
                <div class="text-center"><button type="submit" id="submit" name="submit" class="btn btn-lg btn-block btn-primary disabled" disabled>{!! FT::translate('button.create_shipment') !!}</button></div>
    	
            </div>
        </div>

    </form>

</div>
<script type="text/javascript">

    $(document).ready(function() {
        
    	autocompleteState();
    	//autocompleteCity();
    	
    	autocompleteDeclare($("#row0 input.category"));
    
    	$(window).keydown(function(event){
    	    if(event.keyCode == 13) {
    	      event.preventDefault();
    	      return false;
    	    }
    	});
    	
    });
    
    $('#rec_state').on('change',function(){
    	$('#rec_city').val("");
    	autocompleteCity();
    });
    
    function autocompleteState(){
    
    	var _country = "{{ $default['country'] }}";
    
    	$('#rec_state').autocomplete({
            minLength: 0,
            source: function( request, response ) {
              $.ajax({
                url: "{{ url('/address/states') }}",
                type: "POST",
                dataType: "json",
                data: {
                  term : request.term,
                  agent : "{{ $default['agent'] }}",
                  country_id: _country,
                  _token: "{{ csrf_token() }}"
                },
                success: function(data) {
    
    				var array = $.map(data['states'], function (item) { 
                        return {
                          label: item['stateName'],
                          value: item['stateName'],
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
               		$(this).val(data.stateName);
               		$("#rec_state_code").val(data.stateCode);
               	}
        		
        		//$("#state_desc").text("state code: " + data.code);
        		//$("#admin_state_hidden").val(data.code);
            }
          });
    }
    function autocompleteCity(){
    
    	var _country = "{{ $default['country'] }}";
    	var _state = $("#rec_state_code").val();
    	
    	$('#rec_city').autocomplete({
            minLength: 0,
            source: function( request, response ) {
              $.ajax({
                url: "{{ url('/address/cities') }}",
                type: "POST",
                dataType: "json",
                data: {
                  term : request.term,
                  agent : "{{ $default['agent'] }}",
                  country_id: _country,
                  state_id: _state,
                  _token: "{{ csrf_token() }}"
                },
                success: function(data) {
    
    				var array = $.map(data['cities'], function (item) {
                        return {
                          label: item['cityName'],
                          value: item['cityName'],
                          data : item
                        }
                    });
    
                  	response(array);
                }
              });
            },
            select: function( event, ui ) {
               	var data = ui.item.data;   
               	console.log(data.cityId);
               	if(data.cityId === 0){
               		$(this).val("");
               	}else{
               		$(this).val(data.cityName);
               	}
            }
    	});
    }
    
    function autocompleteDeclare(elem){
    
    	
    	$(elem).autocomplete({
            minLength: "2",
            source: function( request, response ) {
              $.ajax({
            	url: "{{ url('/shipment/declarations') }}",
                type: "POST",
                dataType: "json",
                data: {
                  term : request.term,
                  _token: "{{ csrf_token() }}"
                },
                success: function(data) {
    
    				var array = $.map(data['declares'], function (item) { 
                        return {
                          label: item['desc'],
                          value: item['code'],
                          data : item
                        }
                    });
                    
                    console.log(array);
                  	response(array);
                }
              });
            },
            select: function( event, ui ) {
               	var data = ui.item.data;   
        		$(this).val(data.code);
            }
        });
    }


    function checkZero(valueElement){
        if((valueElement.value) <= 0){valueElement.focus();}
    }
    function calculateInsurance(value){
        if((value) <= 0){ alert('กรุณากรอกตัวเลขค่ามากกว่า 0'); value='';}
        var total = 0;
        var insure = 0;
        
        $(".declare-value").each(function(){
            if($(this).val() != ""){
                total+=parseInt($(this).val());
            }
        });
        insure = total * 0.022;
        insure = Math.max(500,insure).toFixed(2);
        
        $('#insurance_yes').val(insure);
        $('#value-insurance').text(insure);
    }

    function hidedimension(){
        $("#dimension").hide();
    }

    function showdimension(){
        $("#dimension").show();
    }

    function add(){
        var table_size = $("#product_table" ).children().length;
        var row = "<tr id='row"+table_size+"'>"+
                        "<td>"+
                            "<input id='category-"+table_size+"' type='text' name='category["+table_size+"]' class='category form-control required' required />"+                                
                        "</td>"+
                        "<td><input type='number' min='1' name='amount["+table_size+"]' class='form-control required declare-qty text-right' required /></td>"+
                        "<td><input type='number' min='1' name='value["+table_size+"]' class='form-control required declare-value text-right' required /></td>"+
                        "<td><span class='glyphicon glyphicon-minus-sign text-danger' onclick='rmv("+table_size+")'></span></td>"+
                    "</tr>";
        $( "#product_table" ).append(row);
        //$("#category-"+table_size ).attr("pattern","{{ $validateEnglish }}");
    }
    function rmv(id){
        $( "#row"+id ).remove();
        calculateInsurance();
    }
    
	function checkOtherType(type,key){

		if(type != "OTHERS") {
			$("#row"+key+" .category").removeClass("category-other");
			$("#row"+key+" .category").addClass("category-not-other");
            $("#row"+key+" .other").attr("required",false);
			$("#row"+key+" .other").hide();
		}else{
			$("#row"+key+" .category").addClass("category-other");
			$("#row"+key+" .category").removeClass("category-not-other");
            $("#row"+key+" .other").attr("required",true);
			$("#row"+key+" .other").show();
		}
	}

	$(document).ready(function() {
		$(window).keydown(function(event){
		    if(event.keyCode == 13) {
		      event.preventDefault();
		      return false;
		    }
		});

		validateRequired.call($("input[name=firstname]"));
		validateRequired.call($("input[name=lastname]"));
		validateRequired.call($("input[name=phonenumber]"));
		validateEmailFormat.call($("input[name=email]"));
		validateRequired.call($("input[name=address1]"));
		validateOptional.call($("input[name=address2]"));
		validateRequired.call($("input[name=city]"));
		validateRequired.call($("input[name=state]"));
		validateRequired.call($("input[name=postcode]"));
		validateOptional.call($("input[name=note]"));
		validateOptional.call($("input[name=orderref]"));

		validateRequired.call($("input[name=weight]"));

	});

	//validate
	var validateEnglish = new RegExp(/{!! $validateEnglish !!}/);
	
	$("input[name=firstname]").keyup(validateRequired);
	$("input[name=lastname]").keyup(validateRequired);
	$("input[name=phonenumber]").keyup(validateRequired);
	$("input[name=email]").keyup(validateEmailFormat);
	$("input[name=address1]").keyup(validateRequired);
	$("input[name=address2]").keyup(validateOptional);
	$("input[name=city]").keyup(validateRequired);
	$("input[name=state]").keyup(validateRequired);
	$("input[name=postcode]").keyup(validateRequired);
	$("input[name=note]").keyup(validateOptional);
	$("input[name=orderref]").keyup(validateOptional);

	function submitForm(){
		var validate = true;
		
		$("#shipment_form .required").each(validateRequired);
		$("#shipment_form input").each(validateOptional);
		$("input[name=email]").each(validateEmailFormat);

		$(".error-msg").each(function(){
			if($(this).text() != ""){
				validate = false;
			}
		});
   
		if(validate){
			$("#shipment_form").submit();
		}
	}

	$("#shipment_form").submit( function() {
		var validate = true;
		
		$("#shipment_form .required").each(validateRequired);
		$("#shipment_form input").each(validateOptional);
		$("input[name=email]").each(validateEmailFormat);

		$(".error-msg").each(function(){
			if($(this).text() != ""){
				validate = false;
			}
		});

		if(!validate) return false;
   
	});
	
	$('.input-count').keyup(inputCount);
	$('.input-count').keydown(inputCount);

	function validateRequired(){
		var nm = $(this).attr("name");
		//nm = nm.replace("[","");
		//nm = nm.replace("]","");
		var val = $(this).val();

		if(val == ""){
			$(this).addClass("error");
			$('#'+nm+"-error").text("{!! FT::translate('error.required') !!}");
		}else if(!validateEnglish.test(val)){
			$(this).addClass("error");
			$('#'+nm+"-error").text("{!! FT::translate('error.english_only') !!}");
		}else{
			$('#'+nm+"-error").text("");
			$(this).removeClass("error");
		}
	}
	function validateOptional(){
		var nm = $(this).attr("name");

		var val = $(this).val();
		if(val == ""){
			
		}else if(!validateEnglish.test(val)){
			$(this).addClass("error");
			 $('#'+nm+"-error").text("{!! FT::translate('error.english_only') !!}");
		}else{
			$('#'+nm+"-error").text("");
		}
	}
	function validateEmailFormat(){
		
		var nm = $(this).attr("name");
		var val = $(this).val();

		//check email
		var atpos = val.indexOf("@");
		var lastAtpos = val.lastIndexOf("@");
		var cmpos = val.indexOf(",");
	    var dotpos = val.lastIndexOf(".");
	    var validEmail = !(atpos<1 || dotpos<atpos+2 || dotpos+2>=val.length);
	    
		if(val == ""){
			$(this).addClass("error");
			$('#'+nm+"-error").text("{!! FT::translate('error.required') !!}");
		}else if(!validateEnglish.test(val)){
			$(this).addClass("error");
			 $('#'+nm+"-error").text("{!! FT::translate('error.english_only') !!}");
		}else if(!validEmail){
			$(this).addClass("error");
			$('#'+nm+"-error").text("{!! FT::translate('error.invalid_format') !!}");
		}else if(atpos != lastAtpos || cmpos>0){
			$(this).addClass("error");
			$('#'+nm+"-error").text("{!! FT::translate('error.oneemail') !!}");
		}else{
			$('#'+nm+"-error").text("");
		}
	}
	
	function inputCount() {
		var nm = $(this).attr("name");
	    var cs = $(this).val().length;
	    $('#'+nm+"-count").text(cs);
	}

	$(document).ready(function() {
		selectAgent("{{ $default['agent'] }}");
	});
	Number.prototype.format = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
    };
	function calculateRate(scroll){

    	var defaultAgent = "";

    	if($("#weight").val() != ""){
        	$("#weight").val(parseInt($("#weight").val()));
    	}else{
			$("#weight").val(0);
		}
		
        if($("#weight").val() < 0){
        	$("#weight").val(0);
        }else if($("#weight").val() > 299999){
        	$("#weight").val(299999);
        } 

		if($("#weight").val() > 20000){
            $("#weight_text").show();
        }else{
        	$("#weight_text").hide();
        }

		if($("#width").val() != ""){
			$("#width").val(parseInt($("#width").val()));
		}else{
			$("#width").val(0);
		}
		if($("#height").val() != ""){
			$("#height").val(parseInt($("#height").val()));
		}else{
			$("#height").val(0);
		}
		if($("#length").val() != ""){
			$("#length").val(parseInt($("#length").val()));
		}else{
			$("#length").val(0);
		}

        var volWeight = $("#width").val()*$("#height").val()*$("#length").val()/5;
        $("#volumnWeight").text(volWeight.toFixed(0));

		$.post('{{url ('shipment/get_rate')}}',
		{
			_token: $("[name=_token]").val(),
			weight: $("#weight").val(),
			width: $("#width").val(),
			height: $("#height").val(),
			length: $("#length").val(),
			type: $("input[name=type]:checked").val(),
			country: "{{ $default['country_code'] }}",
			source: 'EbayFeed',
		},function(data){

			console.log(data);
			
    		$("#result-panel").empty();
            $("#shipping-agents").removeClass("fade");

    		if(data !== false && data != "No Rate were found that match the specified criteria."){
	            var dataArray = $.map(data, function(value, index) {
	                return [value];
	            });
	            var keyArray = $.map(data, function(value, index) {
	                return [index];
	            });   
    		}

    		var content = "";
            if(data !== false && data != "No Rate were found that match the specified criteria." && dataArray.length > 0){
                
            	var minRate = 9999999;
	            content = "";

                for (key in dataArray) {
                    if (dataArray.hasOwnProperty(key)) {

                       var _agent = dataArray[key]['Name'];
                       var _type = dataArray[key]['Type'];
                       var _deliveryTimeMin = dataArray[key]['DeliveryMinTime'];
                       var _deliveryTimeMax = dataArray[key]['DeliveryMaxTime'];
                       var _deliveryTime = _deliveryTimeMin + "-" + _deliveryTimeMax + " business days";
                       var _value = dataArray[key]['AccountRate'];
                       var _valueMax = dataArray[key]['StandardRate'];

                       if(_agent == "FS_FBA" || _agent == "FS_FBA_PLUS"){
							continue;
                      }
                       
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
                       }else if(_displayAgent == "FS FBA"){
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.5em;'>ส่งเข้าคลัง Amazon</span>";
                       }else if(_displayAgent == "FS FBA JP"){
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.5em;'>ส่งเข้าคลัง Amazon</span>";
                       }else if(_displayAgent == "FS FBA SG"){
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.5em;'>ส่งเข้าคลัง Amazon</span>";
                       }else if(_displayAgent == "FS FBA UK"){
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.5em;'>ส่งเข้าคลัง Amazon</span>";
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

                       if(_agent == "{{ $default['agent'] }}"){
                           agentClass = "ui-state-active";
                       }else{
                    	   agentClass = "";
                       }
                       content += '<fieldset>';
                       content += '<label class="label-rate '+agentClass+'" for="agent-' + _agent + '" onclick="selectAgent(\''+_agent+'\')" style="min-height:46px;">';
                	   content += '<div class="col-xs-4 col-md-2"><img src="/images/agent/' + _agent.replace(/ /g,"-") + '.gif" style="border-radius: 5px 0 0 5px; position: absolute; left:0;width:100%;"/></div>';
                	   content += '<div class="col-xs-4 col-md-6 width-30 text-left">';
                	   content += '<h4>' +  _displayAgent + '</h4>';
                	   //content += '<h4 class="orange"><span class="hidden-xs">' + _type + ' : </span>' + _deliveryTime + '</h4>';
                	   content += '</div>';
                	   if(_value != _valueMax){
	                	   content += '<div class="col-xs-4 col-md-4 width-36 text-right">';
	                	   //content += '<h4 class="retail-price">' + parseInt(_valueMax).format() + ' {!! FT::translate("unit.baht") !!}</h4>';
	                	   content += '<div><span class="text-danger" style="text-decoration: line-through;">' + parseInt(_valueMax).format() + '.-</span><span class="price">' +  parseInt(_value).format() + '</span>.-</div>';
	                	   content += '</div>';
                	   }else{
                		   content += '<div class="col-xs-4 col-md-4 width-36 text-right">';
                		   content += '<div><span class="price">' + parseInt(_value).format() + '</span> {!! FT::translate("unit.baht") !!}</div>';
	                	   content += '</div>';
                	   }
                	   if(_type == 'express'){
                    	   typeClass = '#5f5';
                	   }else if(_type == 'standard'){
                		   typeClass = '#55f';
                	   }else{
                		   typeClass = '#555';
                	   }
                	   content += '<div class="badge" style="font-size:0.6em;position: absolute;right: -10px;top: -5px;background-color: ' + typeClass + ';"><span class="hidden-xs">' + _type + '</div>';
                	   content += '</label>';
                	   content += '<input class="selector" type="radio" id="agent-' + _agent + '" value="' + _agent + '" >';

                       content += '</fieldset>';

                       if(parseInt(minRate) > parseInt(_value)){
                    	   minRate = _value;
                    	   defaultAgent = _agent;
                       }
                    }
                }

                

                
            }else{
            	if(data !== false && data == "No Rate were found that match the specified criteria."){
                	content = "{!! FT::translate('error.shipment_rate.notfound') !!}";
            	}else{
            		content = "";
            	}
            }

            if($("#weight").val() > 20000 || parseInt($("#volumnWeight").text()) > 20000){
                content += '<fieldset>';
                content += '<label class="label-rate" for="agent-quotation" onclick="selectAgent(\'Quotation\',\'0\',\'\')" class="clearfix">';
         	   	content += '<div class="col-xs-4 col-md-3"><img src="images/agent/Quotation.gif" style="border-radius: 5px 0 0 5px; position: absolute; left:0;"/></div>';
         	   	content += '<div class="col-xs-4 col-md-6 width-30 text-left">';
         	   	content += '<h3>ขอใบเสนอราคาพิเศษ</h3>';
         	    content += '<h4 class="orange">Freight Air/Sea/Truck</h4>';
         	   	content += '</div>';

         		content += '<div class="col-xs-4 col-md-3 width-36 text-right">';
         		content += '<div><span class="price">TBC</span></div>';
             	content += '</div>';
         	   	content += '</label>';
         	   	content += '<input class="selector" type="radio" id="agent-quotation" value="quotation" >';
               	content += '</fieldset>';
            }

            $("#result-panel").append(content);
/*
            if(defaultAgent != ""){
           		$("#agent-"+defaultAgent).attr("checked",true);
            }
            */
            $( ".selector" ).checkboxradio({
                classes: { "ui-checkboxradio": "highlight" }
            });

            if(scroll){
            	$('html, body').animate({
                    scrollTop: $("#result-panel").offset().top
                }, 500);
            }

            $("#submit").removeClass("disabled");
    		$("#submit").attr("disabled",false);
            
		},"json");
    }
    function selectAgent(agent){
		$("#agent").val(agent);
		$(".label-rate").each(function(){
			$(this).removeClass("ui-state-active");
		});
		$(".label-rate[for=agent-"+agent+"]").addClass("ui-state-active");

		
		
    }
    
</script>
@endsection