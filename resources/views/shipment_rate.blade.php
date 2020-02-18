@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-7 pad8"><h2>{!! FT::translate('shipment_rate.heading') !!}</h2></div>
        <div class="col-md-5 text-right">
            <div class="bs-wizard dot-step" style="border-bottom:0;">
                <div class="col-xs-4 bs-wizard-step active">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                    <p class="text-center">{!! FT::translate('step.step1') !!}</p>
                </div> 
                <div class="col-xs-4 bs-wizard-step disabled">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">2</span></a>
                    <p class="text-center">{!! FT::translate('step.step2') !!}</p>
                </div>
                <div class="col-xs-4 bs-wizard-step disabled">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">3</span></a>
                    <p class="text-center">{!! FT::translate('step.step3') !!}</p>
                </div>
            </div>
        </div>
	</div>
    <form id="shipment_form" class="form-horizontal" method="post" action="{{url ('create_shipment')}}">
		<input type="hidden" name="agent" id="shipment_agent" />
        <input type="hidden" name="price" id="shipment_price" />
        <input type="hidden" name="delivery_time" id="shipment_time" />
        <div class="row">      
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">{!! FT::translate('shipment_rate.panel.heading1') !!}</div>
                    <div class="panel-body">
                        <div class="row">
                            <label for="weight" class="col-md-4 control-label">{!! FT::translate('label.weight') !!}</label>
                            <div class="col-md-6">
                            <!-- <input type="text" pattern="\d*" maxlength="6" class="form-control required" id="weight" name="weight" required onkeyup="calculateRate(false)" /> -->
                            <input type="number" class="form-control required" id="weight" name="weight" min="1" max="299999" required onkeyup="calculateRate(false)" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-4 control-label">{!! FT::translate('label.packaging') !!}</label>
                            <div class="col-md-6">
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
                            <input type="number" id="width" name="width" class="form-control required" placeholder="{!! FT::translate('placeholder.width') !!}" onchange="calculateRate(false)" min="0" />
                            </div>
                            <div class="col-md-2">
                            <input type="number" id="length" name="length" class="form-control required" placeholder="{!! FT::translate('placeholder.length') !!}" onchange="calculateRate(false)"  min="0" />
                            </div>
                            <div class="col-md-2">
                            <input type="number" id="height" name="height" class="form-control required" placeholder="{!! FT::translate('placeholder.height') !!}" onchange="calculateRate(false)"  min="0" />
                            </div>
                            <div class="clearfix"></div>
                            
                            <label class="col-md-4 control-label">{!! FT::translate('label.volweight') !!}</label>
                            <div class="col-md-8"><span id="volumnWeight" style="line-height: 40px;">0</span> {!! FT::translate('unit.gram') !!}</div>
                        </div>
                        <div class="row">
                        <label for="inputtext" class="col-md-4 control-label">{!! FT::translate('label.destination') !!}</label>
                            <div class="col-md-6">
                                <select class="form-control" id="country" name="country"  onchange="calculateRate(true)">
                                <option value="">- {!! FT::translate('dropdown.default.country') !!} -</option>
                                <?php
                                    foreach($country as $code=>$name){
                                        echo "<option value='".$code."'>".$name."</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div id="weight_text" class="text-center small red" style="display: none;">{!! FT::translate('info.shipment_rate.weight_over_20kg') !!}</div>
                    
                    {{ csrf_field() }}
                    </div>
                </div>
            </div>

            <div class="col-md-6 panel-fade fade" id="shipping-agents">   
                <div class="panel panel-primary min-height-290">
                    <div class="panel-heading">{!! FT::translate('shipment_rate.panel.heading2') !!}</div>
                    <div class="panel-body" id="result-panel">
                        <h4 class="text-center">{!! FT::translate('shipment_rate.warning.required') !!}</h4> 
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/UPS.gif">
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/DHL.gif">
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/SF.gif">
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/Aramex.gif">
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/GM_Packet_Plus.gif">
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/USPS.gif">
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/FedEx_SmartPost.gif"> 
                        <img class="img-fade col-md-3 col-sm-6" src="images/agent/GM_Packet_Economy.gif">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function hidedimension(){
        $("#dimension").hide();
        calculateRate();
    }

    function showdimension(){
        $("#dimension").show();
        calculateRate();
    }

    function selectAgent(agent,price,delivery_time){
    	$("#shipment_agent").val(agent);
        $("#shipment_price").val(price);
        $("#shipment_time").val(delivery_time);
    	$("#shipment_form").submit();
    }
    
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
			width: $("#width").val() ,
			height: $("#height").val() ,
			length: $("#length").val() ,
			type: $("input[name=type]:checked").val() ,
			country: $("#country").val() ,
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
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.4em;'>ส่งเข้าคลัง Amazon</span>";
                       }else if(_displayAgent == "FS FBA JP"){
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.4em;'>ส่งเข้าคลัง Amazon</span>";
                       }else if(_displayAgent == "FS FBA SG"){
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.4em;'>ส่งเข้าคลัง Amazon</span>";
                       }else if(_displayAgent == "FS FBA UK"){
                    	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:5px;font-size:0.4em;'>ส่งเข้าคลัง Amazon</span>";
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
                       }else if(_displayAgent == "Thaipost Ems"){
                    	   _displayAgent = "EMS World";
                       }else if(_displayAgent == "Thaipost Epacket"){
                    	   _displayAgent = "ThailandPost ePACKET";
                       }

                       content += '<fieldset>';
                       content += '<label class="label-rate" for="agent-' + _agent + '" onclick="selectAgent(\''+_agent+'\',\''+_value+'\',\''+_deliveryTime+'\')" class="clearfix">';
                	   content += '<div class="col-xs-4 col-md-3"><img src="images/agent/' + _agent.replace(/ /g,"-") + '.gif" style="border-radius: 5px 0 0 5px; position: absolute; left:0;"/></div>';
                	   content += '<div class="col-xs-4 col-md-6 width-30 text-left mac-margin-left" >';
                	   content += '<h3>' +  _displayAgent + '</h3>';
                	   content += '<h4 class="orange"><span class="hidden-xs">' + _type + ' : </span>' + _deliveryTime + '</h4>';
                	   content += '</div>';
                	   if(_value != _valueMax){
	                	   content += '<div class="col-xs-4 col-md-3 width-36 text-right">';
	                	   content += '<h4 class="retail-price">' + parseInt(_valueMax).format() + ' {!! FT::translate("unit.baht") !!}</h4>';
	                	   content += '<div><span class="price">' + parseInt(_value).format() + '</span> {!! FT::translate("unit.baht") !!}</div>';
	                	   content += '</div>';
                	   }else{
                		   content += '<div class="col-xs-4 col-md-3 width-36 text-right">';
                		   content += '<div><span class="price">' + parseInt(_value).format() + '</span> {!! FT::translate("unit.baht") !!}</div>';
	                	   content += '</div>';
                	   }
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

            var ua = navigator.userAgent.toLowerCase(); 
    		if (ua.indexOf('safari') != -1) { 
    		  if (ua.indexOf('chrome') <= -1) {
    		    $('.mac-margin-left').css('margin-left','140px');
    		  }
    		}
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
            
		},"json");
    }
    Number.prototype.format = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
    };
    $(document).ready(function() {

		$("#weight").focus();

		@if(session('shipment.weight') !== null)
			
			$("#weight").val("{{ session('shipment.weight') }}");

    		@if(session('shipment.width') > 0)
    
    			$("#box").attr('checked', true);
    			showdimension();
    		    
    			$("#width").val("{{ session('shipment.width') }}");
    			$("#height").val("{{ session('shipment.height') }}");
    			$("#length").val("{{ session('shipment.length') }}");
    			
    			var volWeight = $("#width").val()*$("#height").val()*$("#length").val()/5;
    	        $("#volumnWeight").text(volWeight.toFixed(0));
    	        
    		@else
    			$("#parcel").attr('checked', true);
    			hidedimension();
    		@endif
		
			$("#country").val("{{ session('shipment.country') }}");

    		calculateRate();
    		
		@endif

		var isMac = navigator.platform.toUpperCase().indexOf('MAC')>=0;
		if (isMac) {
		    $('.mac-margin-left').css('margin-left','140px');
		}
		
    });
</script>
@endsection