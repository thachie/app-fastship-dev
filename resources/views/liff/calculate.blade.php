@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">คำนวณค่าส่งไปต่างประเทศ</h3>
		<hr />
	</div>
	
	<div id="form-panel">
    	<form id="calculate_form" name="calculate_form" method="post" action="{{ url('liff/create_shipment') }}">
    
    		<input type="hidden" name="line_user_id" class="line_user_id" />
    		<input type="hidden" name="agent" id="agent" />
    		<input type="hidden" name="width" id="width" />
    		<input type="hidden" name="height" id="height" />
    		<input type="hidden" name="length" id="length" />
    		<span id="volweight" class="d-none"></span>
    		
    		<div class="row form-group">
    			<div class="col col-12">
    				<label for="weight" class=" form-control-label">น้ำหนัก (กรัม)</label>
    			</div>
                <div class="col col-12">
                	<input type="number" id="weight" name="weight" min="0"  class="form-control required" placeholder="น้ำหนักพัสดุตามที่ชั่ง" required/>
                </div>
                <div class="col col-12">
                	<div id="weight_help" class="help text-danger small"></div>
                </div>
         
            </div>
            
            <div class="row form-group">
    			<div class="col col-12">
    				<label for="country" class=" form-control-label">ประเทศ</label>
    			</div>
                <div class="col col-12">
                	<select class="form-control required" id="country" name="country" required>
                    	<option value="">- กรุณาเลือกประเทศปลายทาง -</option>
                    	@foreach($countries as $country)
                        <option value="{{ $country['CNTRY_CODE'] }}">{{ $country['CNTRY_NAME'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row form-group">
    			<div class="col col-12 text-center">
    				<button type="button" class="btn btn-block btn-primary btn-lg large" onclick="calculateRate(true,false)">ตรวจสอบราคา</button>
    			</div>
            </div>
    
    	</form>
	</div>
	<div id="short-form-panel" class="well d-none">
		<div class="col col-12">
    		<div class="text-secondary">
    			<i class="fa fa-dropbox"></i>
    			พัสดุหนัก <span id="text-weight"></span> กรัม 
    		</div>
    	</div>
    	<div class="col col-12">
    		<div class="text-secondary"><i class="fa fa-star"></i> ปลายทาง: <span id="text-country"></span></div>
    	</div>
    	<div class="col col-12">
    		<button type="button" class="btn btn-info btn-block btn-sm border-0" onclick="resetCalculate();">แก้ไข</button>
    	</div>
	</div>
	
	<div id="result-panel" class="d-none"></div>
    		
</div>
<div class="clearfix"></div>

<script type="text/javascript">
<!--
$(window).on('load',function(){
    $("#country").val("USA");
});
/* calculate rate */
function calculateRate(scroll,defaultAgent){
	
	//var defaultAgent = "";

	//adjust weight
	if($("#weight").val() != ""){
    	$("#weight").val(parseInt($("#weight").val()));
    	$("#weight_help").text("");
	}else{
		$("#weight_help").text("กรุณากรอกน้ำหนักค่ะ");
		return false;
		//$("#weight").val(0);
	}
    if($("#weight").val() < 0){
    	//$("#weight").val(0);
    	return false;
    }else if($("#weight").val() > 299999){
    	$("#weight").val(299999);
    } 

    //check weight > 20kg
	if($("#weight").val() > 20000){
        $("#weight_text").show();
    }else{
    	$("#weight_text").hide();
    }

    //adjust dimension
	if($("#width").val() != ""){
		$("#width").val(parseInt($("#width").val()));
	}
	if($("#height").val() != ""){
		$("#height").val(parseInt($("#height").val()));
	}
	if($("#length").val() != ""){
		$("#length").val(parseInt($("#length").val()));
	}

	var volWeight = $("#width").val()*$("#height").val()*$("#length").val()/5;
    $("#volweight").text(volWeight.toFixed(0) + " กรัม");
    
	//call ajax
	$.post('/liff/ajax/get_rate',
	{
		weight: $("#weight").val(),
		width: $("#width").val() ,
		height: $("#height").val() ,
		length: $("#length").val() ,
		country: $("#country").val() ,
	},function(data){

		console.log(data);
		$("#result-panel").removeClass('d-none');

		$("#result-panel").empty();
        //$("#shipping-agents").removeClass("fade");

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
                   
                   var _defClass = "";
                   var _selected = false;

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
                	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:2px;font-size:6px;'>FBA AMAZON</span>";
                   }else if(_displayAgent == "FS FBA JP"){
                	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:2px;font-size:6px;'>FBA AMAZON</span>";
                   }else if(_displayAgent == "FS FBA SG"){
                	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:2px;font-size:6px;'>FBA AMAZON</span>";
                   }else if(_displayAgent == "FS FBA UK"){
                	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:2px;font-size:6px;'>FBA AMAZON</span>";
                   }else if(_displayAgent == "FS FBA AU"){
                	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:2px;font-size:6px;'>FBA AMAZON</span>";
                   }else if(_displayAgent == "FS FBA FR"){
                	   _displayAgent = "FS FBA <span class='badge' style='background: #f00;vertical-align: top;margin-top:2px;font-size:6px;'>FBA AMAZON</span>";
                   }else if(_displayAgent == "SF"){
                	   _displayAgent = "SF Express";
                   }else if(_displayAgent == "FS"){
                	   _displayAgent = "FastShip Express";
                   }else if(_displayAgent == "FS Standard"){
                	   _displayAgent = "FastShip Standard";
                   }else if(_displayAgent == "FS Epacket"){
                	   _displayAgent = "FastShip E-Packet";
                   }else if(_displayAgent == "Ecom PD"){
                	   _displayAgent = "Parcel Direct";
                   }

                   if(defaultAgent == _agent){ _defClass = "active";_selected = true; }
                   else{ _defClass = "bg-light"; }


                   content += '<div id="rate_result_' + _agent + '" class="rate-result row bg-light" onclick="selectAgent(\'' + _agent + '\')">';
                   content += '	<div class="col col-3 bg-light padding-0">';
                   content += '		<img src="/images/agent/' + _agent + '.gif" style="border-radius: 5px 0 0 5px;width: 100%;">';
                   content += '	</div>';
                   content += '	<div class="col col-5 rate-right">';
                   content += '		<h1 class="rate-agent-name">' + _displayAgent + '</h1>';
                   content += '		<div class="rate-agent-duration">';
                   content += '			<span >' + _deliveryTimeMin + '-' + _deliveryTimeMax + ' วันทำการ</span>';
                   content += '		</div>';
                   content += '		<span class="rate-agent-type rate-agent-type-' + _type + '">' + _type + '</span>';
                   content += '	</div>';
                   content += '	<div class="col col-4 rate-right">';
                   content += '		<div class="text-success text-right rate-agent-price">' + parseInt(_value).format() + ' บาท</div>';
                   content += '	</div>';
                   content += '</div>';

                }
            }

            

            
        }else{
        	if(data !== false && data == "No Rate were found that match the specified criteria."){
            	content = "ไม่พบวิธีการส่งที่เหมาะสมสำหรับการส่งพัสดุคุณ";
        	}else{
        		content = "";
        	}
        }

        if($("#weight").val() > 20000 || parseInt($("#volumnWeight").text()) > 20000){

        	content += '<div id="rate_result_quotation" class="rate-result row bg-light" onclick="selectAgent(\'Quotation\')">';
            content += '	<div class="col col-3 bg-light padding-0">';
            content += '		<img src="/images/agent/Quotation.gif" style="border-radius: 5px 0 0 5px;width: 100%;">';
            content += '	</div>';
            content += '	<div class="col col-5 rate-right">';
            content += '		<h1 class="rate-agent-name">ขอใบเสนอราคาพิเศษ</h1>';
            content += '		<div class="rate-agent-duration">';
            content += '			<span >Freight Air/Sea/Truck</span>';
            content += '		</div>';
            content += '	</div>';
            content += '	<div class="col col-4 rate-right">';
            content += '		<div class="text-success text-right rate-agent-price">TBC</div>';
            content += '	</div>';
            content += '</div>';
        }

        $("#result-panel").append(content);

        //setup short-form-panel
        $("#form-panel").addClass('d-none');
        $("#short-form-panel").removeClass('d-none');
        $("#text-weight").text($("#weight").val());
        $("#text-country").text($("#country").val());
        
        if(scroll){
            $('html, body').animate({
            	scrollTop: $("#result-panel").offset().top - 100
            }, 500);
        }
  
	},"json");
}
function resetCalculate(){

	//setup short-form-panel
    $("#form-panel").removeClass('d-none');
    $("#short-form-panel").addClass('d-none');
    $("#result-panel").empty();
    $("#result-panel").addClass('d-none');
    $("#text-weight").text("");
    $("#text-country").text("");

    $('html, body').animate({
    	scrollTop: 0
    }, 500);
    
}
function selectAgent(agent){

	return false;
	
	if(confirm("เริ่มส่งด้วย "+agent+" ใช่หรือไม่")){

		$("#agent").val(agent);
		$("#calculate_form").submit();

	}
}
-->
</script>
@endsection