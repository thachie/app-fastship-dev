@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
		<div class="col-md-8 col-xs-12">
			<h1 style="margin-bottom: 0;">Create FBA Shipments - {{ $countries[$country] }}</h1>
			<p class="text-primary">สร้างพัสดุส่งไปที่ Amazon Warehouse {{ $countries[$country] }}</p>
		</div>
		<div class="col-md-4 col-xs-12 text-right">
			<div class="text-right"><strong>Change Country</strong></div>
			<a href="{{ url('create_fba/usa') }}"><img src="{{ url('images/agent/FS_FBA_PLUS.gif') }}" style="height: 48px;" /></a>
			<a href="{{ url('create_fba/jpn') }}"><img src="{{ url('images/agent/FS_FBA_JP.gif') }}" style="height: 48px;" /></a>
			<a href="{{ url('create_fba/sgp') }}"><img src="{{ url('images/agent/FS_FBA_SG.gif') }}" style="height: 48px;" /></a>
		</div>
    </div>

    <form id="shipment_form" name="shipment_form" class="form-horizontal" method="post" action="{{url ('shipment/create_fba')}}">
    
    {{ csrf_field() }}
    
    <input type="hidden" name="country" id="country" value="{{ $country }}" />
    <input type="hidden" name="agent" id="agent" value="{{ $agent }}" />
    
    <div class="row">
    
		<div class="col-md-6 col-xs-12">

			<div class="panel panel-primary">
                <div class="panel-heading">Warehouse/Account Info</div>
            	<div class="panel-body row-no-padding">
            	
            		@if($defaultWarehouse['code'])
        			<div class="form-group">
                    	<label class="col-md-3 control-label" style="margin-top: 0;">Warehouse</label>
                    	<div class="col-md-8" style="margin-top: 8px;"><span class="text-info">{{ $defaultWarehouse['code'] }}</span> {{ $defaultWarehouse['address1'] }} {{ $defaultWarehouse['city'] }} {{ $defaultWarehouse['state'] }} {{ $defaultWarehouse['postcode'] }}</div>
                    </div>
                    <input type="hidden" name="receiver_address1" value="{{ $defaultWarehouse['address1'] }}" />
                	<input type="hidden" name="receiver_city" value="{{ $defaultWarehouse['city'] }}" />
                	<input type="hidden" name="receiver_state" value="{{ $defaultWarehouse['state'] }}" />
                	<input type="hidden" name="receiver_postcode" value="{{ $defaultWarehouse['postcode'] }}" />
        			@else
        			<div class="form-group">
                        <label class="col-md-3 control-label" style="margin-top: 0;">Search WH</label>
                        <div class="col-md-8">
                        	<input type="text" name="warehouse" id="warehouse" placeholder="Search Warehouse By Name Or Warehouse Id" title="" class="form-control" value="{{ old('warehouse','') }}" />
                        </div>
        			</div>
        			<hr />
        			<div class="form-group">
                        <label class="col-md-3 control-label" style="margin-top: 0;">Address</label>
                        <div class="col-md-8">
                        	<input type="text" name="receiver_address1" id="receiver_address1" placeholder="" title="" class="form-control required" value="{{ old('receiver_address1','') }}" required />
                        </div>
                    </div>
                    <div class="form-group">
                        
                        <label class="col-md-3 control-label" style="margin-top: 0;">City</label>
                        <div class="col-md-8">
                        	<input type="text" name="receiver_city" id="receiver_city" placeholder="" title="" class="form-control required" value="{{ old('receiver_city','') }}" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" style="margin-top: 0;">State</label>
                        <div class="col-md-8">
                        	<input type="text" name="receiver_state" id="receiver_state" placeholder="" title="" class="form-control required" value="{{ old('receiver_state','') }}" required />
                        </div>
        			</div>
        			<div class="form-group">
                        <label class="col-md-3 control-label" style="margin-top: 0;">Postal Code</label>
                        <div class="col-md-8">
                        	<input type="text" name="receiver_postcode" id="receiver_postcode" placeholder="" title="" class="form-control required" value="{{ old('receiver_postcode','') }}" required />
                        </div>
        			</div>
        			@endif
        			
            		@if($requireIOR == true)
            		<div class="form-group">
                        <label class="col-md-3 control-label" style="margin-top: 0;">IOR</label>
                        <div class="col-md-8">
                        	<input type="text" name="ior" id="ior" placeholder="" title="IOR" class="form-control required" value="{{ old('ior','') }}" required />
                        </div>
        			</div>
        			<div class="form-group">
                        <label class="col-md-3 control-label" style="margin-top: 0;">Tax ID</label>
                        <div class="col-md-8">
                        	<input type="text" name="taxid" id="taxid" placeholder="" title="TaxID" class="form-control required" value="{{ old('taxid','') }}" required />
                        </div>
        			</div>
        			@endif
        		</div>
        	</div>
		</div>
		
		@if($country == "USA")
		<div class="col-md-6 col-xs-12">
			<a class="hidden-xs" data-toggle="modal" data-target="#ModalTerm"><img src="{{ url('images/fba_us_price_table.jpg') }}" style="max-width: 100%;border: 10px solid #e5e5e5;"/></a>
			<div class="modal fade" id="ModalTerm" tabindex="-1" role="dialog" aria-labelledby="ModalTerm_Label" aria-hidden="true">
            	<div class="modal-dialog"  style="width: 1000px;">
            		<div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title text-left" id="ModalTerm_Label">ตารางราคาการให้บริการขนส่ง FBA United States</h4>
                        </div>
                        <div class="modal-body">
                        	<img src="{{ url('images/fba_us_price_table.jpg') }}" style="max-width: 100%;"/>
                        </div>
                    </div>
            	</div>
            </div>
            <div class="hidden-lg hidden-md"><img src="{{ url('images/fba_us_price_table.jpg') }}" style="max-width: 100%;"/></div>
		</div>
		@else
		<div class="col-md-6 col-xs-12 small well">

			<h4>Amazon FBA คืออะไร</h4>
			<p class="">Amazon FBA หรือ Fulfilment by Amazon คือรูปแบบการขายของผ่านเว็บ Amazon.com โดยเรา (supplier) ส่งสิ้นค้าไปเก็บไว้ที่โกดัง (warehouse) ของ Amazon ที่อเมริกา เมื่อสินค้าขายได้ทางทีมงาน FBA จะแพ๊คและส่งสินค้าให้ด้วย ซึ่งทาง Amazon จะคิดค่าใช้จ่ายหลักๆคือ</p>
			<ul class="">
				<li>ค่าเก็บ (storage) คิดเป็นต่อปริมาตรต่อเดือน</li>
				<li>ค่าหยิบ (FBA Fulfillment Fees) คิดเป็นต่อชิ้น ขึ้นอยู่กับขนากและน้ำหนัก</li>
				<li>ค่าส่ง (shipping) ค่าขนส่ง กรณีไม่ได้ขายผ่าน Amazon.com</li>
			</ul>
			
			<h4>Amazon FBA ดีอย่างไร</h4>
			<ul class="">
				<li>ไม่ต้องวุ่นวายกับการแพ๊ค และมั่นใจได้ว่าสินค้าจะแพ๊คได้รวดเร็วตรงตามมาตรฐานของ Amazon</li>
				<li>ไม่ต้องเสียเวลา ตอบคำถามลูกค้าเกี่ยวกับเรื่องการส่ง การคืน  Amazon  จะมีทีม CS ตอบให้</li>
				<li>ลูกค้าสามารถคืนของไปที่ Amazon ได้เลย</li>
				<li>ลูกค้าจะได้สิทธิส่งฟรี หรือใช้ Amazon Prime ได้</li>
				<li>สินค้าจะได้รับการโปรโมทขึ้นในเว็บดีกว่า ทั่ง Buy Box และ search result</li>
			</ul>
			<br />
			
			
			<div class=" text-center"><a href="https://fastship.co/helps/how-to-send-products-to-amazon-fba/" target="_blank">รายละเอียดเพิ่มเติม</a></div>
			
		</div>
		@endif
		
	</div>
	<div class="row">
	
		<div class="col-md-12 col-xs-12">
		
			<div id="box1" class="panel panel-info box" rel="1">
                <div class="panel-heading">
                	<div class="col-md-6">Box #1 Detail</div>
                	<div class="col-md-6 text-right">
                		<a href="javascript:copyBox(1)"><i class="fa fa-copy"></i></a>
                	</div>
                	<div style="clear:both;"></div>
                </div>
            	<div class="panel-body row-no-padding">

    				<div class="col-md-4 col-xs-12 well">
            			<div class="form-group">
                            <label class="col-md-5 control-label" style="margin-top: 0;">FBA Shipment ID</label>
                            <div class="col-md-7">
                            	<input type="text" name="reference[1]" id="reference1" placeholder="" title="Reference" class="form-control required " value="{{ old('reference','') }}" onchange="addSumBox();" required />
                            </div>
            			</div>
            			<div class="form-group">
                            <label class="col-md-5 control-label" style="margin-top: 0;">Box Weight (g.)</label>
                            <div class="col-md-7">
                            	<input type="number" name="weight[1]" id="weight1" placeholder="" title="Weight" class="form-control required" value="{{ old('weight','') }}" onchange="calculateRate(1);" required/>
                            </div>
            			</div>
            			<div class="form-group">
                            <label class="col-md-5 control-label" style="margin-top: 0;">Width (cm)</label>
                            <div class="col-md-7">
                            	<input type="number" name="width[1]" id="width1" placeholder="" title="Width" class="form-control required" value="{{ old('width','') }}" onchange="calculateRate(1);" required/>
                            </div>
            			</div>
            			<div class="form-group">
                            <label class="col-md-5 control-label" style="margin-top: 0;">Height (cm)</label>
                            <div class="col-md-7">
                            	<input type="number" name="height[1]" id="height1" placeholder="" title="Height" class="form-control required" value="{{ old('height','') }}" onchange="calculateRate(1);" required/>
                            </div>
            			</div>
            			<div class="form-group">
                            <label class="col-md-5 control-label" style="margin-top: 0;">Length (cm)</label>
                            <div class="col-md-7">
                            	<input type="number" name="length[1]" id="length1" placeholder="" title="Length" class="form-control required" value="{{ old('length','') }}" onchange="calculateRate(1);" required/>
                            </div>
            			</div>
            		</div>
            			
            		<div class="col-md-8 col-xs-12">	
            				<div class="form-group">
                				<div class="col-md-7">Product Description</div>
                				<div class="col-md-2 text-center">Qty.</div>
                				<div class="col-md-3 text-center">Total Value (THB)</div>
                			</div>
                			<div id="d1_1" class="form-group" rel="1">
                				<div class="col-md-7">
                                	<input type="text" name="declare_type[1][]" id="declare_type1_1" placeholder="" title="" class="form-control" value="{{ old('declare_type[1]','') }}" required />
                                </div>
                				<div class="col-md-2">
                                	<input type="number" name="declare_qty[1][]" id="declare_qty1_1" placeholder="" title="" class="form-control" value="{{ old('declare_qty[1]','') }}" />
                                </div>
                                <div class="col-md-2">
                                	<input type="number" name="declare_value[1][]" id="declare_value1_1" placeholder="" title="" class="form-control" value="{{ old('declare_value[1]','') }}" />
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-link btn-xs text-danger" onclick="removeDeclareBox(1,1)"><i class="fa fa-trash"></i></button>
                                </div>
                			</div>
                			<div id="d1_2" class="form-group" rel="2">
                				<div class="col-md-7">
                                	<input type="text" name="declare_type[1][]" id="declare_type1_2" placeholder="" title="" class="form-control" value="{{ old('declare_type[2]','') }}" />
                                </div>
                				<div class="col-md-2">
                                	<input type="number" name="declare_qty[1][]" id="declare_qty1_2" placeholder="" title="" class="form-control" value="{{ old('declare_qty[2]','') }}" />
                                </div>
                                <div class="col-md-2">
                                	<input type="number" name="declare_value[1][]" id="declare_value1_2" placeholder="" title="" class="form-control" value="{{ old('declare_value[2]','') }}" />
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-link btn-xs text-danger" onclick="removeDeclareBox(1,2)"><i class="fa fa-trash"></i></button>
                                </div>
                			</div>
                			<div id="d1_3" class="form-group" rel="3">
                				<div class="col-md-7">
                                	<input type="text" name="declare_type[1][]" id="declare_type1_3" placeholder="" title="" class="form-control" value="{{ old('declare_type[3]','') }}" />
                                </div>
                				<div class="col-md-2">
                                	<input type="number" name="declare_qty[1][]" id="declare_qty1_3" placeholder="" title="" class="form-control" value="{{ old('declare_qty[3]','') }}" />
                                </div>
                                <div class="col-md-2">
                                	<input type="number" name="declare_value[1][]" id="declare_value1_3" placeholder="" title="" class="form-control" value="{{ old('declare_value[3]','') }}" />
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-link btn-xs text-danger" onclick="removeDeclareBox(1,3)"><i class="fa fa-trash"></i></button>
                                </div>
                			</div>
                			<div id="declare_box1"></div>
                			<div class="form-group">
                				<div class="col-md-12 text-right">
                					<button type="button" class="btn btn-link btn-xs small" onclick="addDeclareBox(1)">[+ Add Product]</button>
                				</div>
                			</div>
    
            		</div>
        			<input type="hidden" class="agent_rate" id="agent_rate1" />
		
				</div>
			</div>
			
			<div id="boxes"></div>
			
			<div class="row">
                <div class="col-md-12 col-xs-12 text-center">
					<button type="button" class="btn btn-warning btn-sm" onclick="addBox()">+ Add More Box</button>
				</div>
			</div>

			<div id="summary_table" class="col-md-12 col-xs-12 " style="display: none;">
				<div class="panel panel-default">
                	<div class="panel-body row-no-padding text-right">
        				<div class="col-md-6 col-xs-12">
            				<table class="table">
                				<thead>
                				<tr>
                					<th class="text-center small">Box</th>
                					<th class="text-center small">Shipment ID</th>
                					<th class="text-center small">Weight (gram)</th>
                					<th class="text-right small">Shipping (THB)</th>
                				</tr>
                				</thead>
                				<tbody id="summary_box">
                				<tr>
                					<td colspan="4" class="text-center gray small">Please inform box detail</td>
                				</tr>
                				</tbody>
            				</table> 
        				</div>
						<div class="col-md-6 col-xs-12">
                            <h4 class="small">You have <span id="agent_shipment_no">1</span> shipments, send to {{ $countries[$country] }}</h4>
                            <h1 class="text-primary">Total Shipping <span id="agent_rate">0</span> Baht</h1>
                    	</div>
                    </div>
                </div>
            </div>
            
			
			
		</div>
		
		<hr />
		
    </div>
    
    <div class="row">
		<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
			<button type="submit" class="btn btn-lg btn-block btn-primary">Confirm and Create Shipment</button>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1 col-xs-12" style="padding: 10px;background: #eaeafa;">
			<h6>REMARK</h6>
			<p>Shipment will be DDP, shipper will be responsible for import duty and applicable charges at the destinations</p>
		</div>
	</div>

    </form>
</div>
         
<script type="text/javascript">
$(document).ready(function() {

	calculateRate();

	//get warehouse list
	$('#warehouse').autocomplete({
        minLength: 0,
        source: function( request, response ) {

          $.ajax({
            url: "{{ url('shipment/get_fba_addresses') }}",
            type: "POST",
            dataType: "json",
            data: {
              term : request.term,
              country: "{{ $country }}" ,
              _token: "{{ csrf_token() }}"
            },
            success: function(data) {

				var array = $.map(data, function (item) { //alert(array);
                    return {
                      label: item['code'] + " - " + item['address'] + " " + item['city'] + " " + item['state'],
                      value: item['code'] + " - " + item['address'] + " " + item['city'] + " " + item['state'],
                      data : item
                    }
                });
              	response(array);
              	
            }
          });
        },
        select: function( event, ui ) {

           	var data = ui.item.data;   

           	console.log(data);
           	$("#receiver_address1").val(data.address);
           	$("#receiver_city").val(data.city);
           	$("#receiver_state").val(data.state);
           	$("#receiver_postcode").val(data.postcode);

        }
    });
});

function calculateRate(box){
	
	if($("#weight"+box).val() != ""){
    	$("#weight"+box).val(parseInt($("#weight"+box).val()));
	}else{
		$("#weight"+box).val(0);
	}
	
    if($("#weight"+box).val() < 0){
    	$("#weight"+box).val(0);
    }else if($("#weight"+box).val() > 299999){
    	$("#weight"+box).val(299999);
    } 

	if($("#width"+box).val() != ""){
		$("#width"+box).val(parseInt($("#width"+box).val()));
	}else{
		$("#width"+box).val(0);
	}
	if($("#height"+box).val() != ""){
		$("#height"+box).val(parseInt($("#height"+box).val()));
	}else{
		$("#height"+box).val(0);
	}
	if($("#length"+box).val() != ""){
		$("#length"+box).val(parseInt($("#length"+box).val()));
	}else{
		$("#length"+box).val(0);
	}

    var volWeight = $("#width"+box).val()*$("#height"+box).val()*$("#length"+box).val()/5;
    //$("#volumnWeight").text(volWeight.toFixed(0));

	$.post("{{url ('shipment/get_rate')}}",
	{
		_token: $("[name=_token]").val(),
		weight: $("#weight"+box).val(),
		type: 'box',
		width: $("#width"+box).val() ,
		height: $("#height"+box).val() ,
		length: $("#length"+box).val() ,
		agent: '{{ $agent }}' ,
		source: 'Web_FBA' ,
		country: '{{ $country }}' ,
	},function(data){

		console.log(data[0].AccountRate);
		//$("#agent_rate").text(data[0].AccountRate);
		$("#agent_rate"+box).val(data[0].AccountRate);

		addSumBox();
		
		//calculateTotalRate();
		
        
	},"json");
	
}

function addSumBox(){

	$("#summary_box").empty();

	$(".box").each(function(){
		
		var box = $(this).attr("rel");

		if($("#agent_rate"+box).val() != "" && !$("#box"+box+" .panel-heading").is(":hidden")){

			$("#summary_table").show();

    		var content = '';
    		content = '<tr id="sum_box' + box + '">';
    		content += '<td class="text-center small">Box#' + box + '</td>';
    		content += '<td class="text-center small">' + $("#reference"+box).val() + '</td>';
    		content += '<td class="text-center small">' + $("#weight"+box).val() + '</td>';
    		content += '<td class="text-center small">' + $("#agent_rate"+box).val() + '</td>';
    		content += '</tr>';
    		$("#summary_box").append(content);

    		calculateTotalRate();
		}
		
	});

	

}
function calculateTotalRate(){

	var total = 0;
	var count = 0;
	$(".agent_rate").each(function(){
		
		if(Number.isNaN(parseInt($(this).val()))){
			return true;
		}

		total += parseInt($(this).val());
		count++;
		
	});
	$("#agent_rate").text(total);
	$("#agent_shipment_no").text(count);
	
}
function addBox(){

	var lastBoxNum = $('div.box:last').attr("rel");
	var num = parseInt(lastBoxNum) + 1;
	
	var content = '<div id="box' + num + '" class="panel panel-info box" rel="' + num + '">';
	content += '<div class="panel-heading">';
	content += '<div class="col-md-6">Box #' + num + ' Detail</div>';
	content += '<div class="col-md-6 text-right">';
	content += '	<a href="javascript:copyBox(' + num + ')"><i class="fa fa-copy"></i></a>';
	content += '	<a href="javascript:removeBox(' + num + ')"><i class="fa fa-trash"></i></a>';
	content += '</div>';
	content += '<div style="clear:both;"></div>';
	content += '</div>';
	content += '<div class="panel-body row-no-padding">';

	content += '<div class="col-md-4 col-xs-12 well">';
	content += '<div class="form-group">';
	content += '	<label class="col-md-5 control-label" style="margin-top: 0;">FBA Shipment ID</label>';
	content += '    <div class="col-md-7">';
	content += '    	<input type="text" name="reference[' + num + ']" id="reference' + num + '" placeholder="" title="Reference" class="form-control required " value="" onchange="addSumBox();" required />';
	content += '    </div>';
	content += '</div>';
	content += '<div class="form-group">';
	content += '    <label class="col-md-5 control-label" style="margin-top: 0;">Box Weight (g.)</label>';
	content += '    <div class="col-md-7">';
	content += '    	<input type="number" name="weight[' + num + ']" id="weight' + num + '" placeholder="" title="Weight" class="form-control required weight" value="" onchange="calculateRate('+num+');" required/>';
	content += '    </div>';
	//content += '    <div class="col-md-1 text-left small"><label style="margin-top: 10px;">gram</label></div>';
	content += '</div>';

	content += '<div class="form-group">';
	content += '	<label class="col-md-5 control-label" style="margin-top: 0;">Width (cm)</label>';
	content += '	<div class="col-md-7">';
	content += '		<input type="number" name="width[' + num + ']" id="width' + num + '" placeholder="" title="Width" class="form-control required" onchange="calculateRate(' + num + ');" required/>';
	content += '	</div>';
	content += '</div>';
	content += '<div class="form-group">';
	content += '	<label class="col-md-5 control-label" style="margin-top: 0;">Height (cm)</label>';
	content += '	<div class="col-md-7">';
	content += '		<input type="number" name="height[' + num + ']" id="height' + num + '" placeholder="" title="Height" class="form-control required" onchange="calculateRate(' + num + ');" required/>';
	content += '	</div>';
	content += '</div>';
	content += '<div class="form-group">';
	content += '	<label class="col-md-5 control-label" style="margin-top: 0;">Length (cm)</label>';
	content += '	<div class="col-md-7">';
	content += '		<input type="number" name="length[' + num + ']" id="length' + num + '" placeholder="" title="Length" class="form-control required" onchange="calculateRate(' + num + ');" required/>';
	content += '	</div>';
	content += '</div>';
	content += '</div>';
	
	content += '<div class="col-md-8 col-xs-12">';
	content += '	<div class="form-group">';
	content += '		<div class="col-md-7">Product Description</div>';
	content += '		<div class="col-md-2 text-center">Qty.</div>';
	content += '		<div class="col-md-3 text-center">Total Value (THB)</div>';
	content += '	</div>';
	content += '	<div id="d' + num + '_1" class="form-group declare" rel="1">';
	content += '		<div class="col-md-7">';
	content += '        	<input type="text" name="declare_type[' + num + '][]" id="declare_type' + num + '_1" placeholder="" title="" class="form-control" value="" required />';
	content += '        </div>';
	content += '		<div class="col-md-2">';
	content += '        	<input type="number" name="declare_qty[' + num + '][]" id="declare_qty' + num + '_1" placeholder="" title="" class="form-control" value="" />';
	content += '        </div>';
	content += '        <div class="col-md-2">';
	content += '        	<input type="number" name="declare_value[' + num + '][]" id="declare_value' + num + '_1" placeholder="" title="" class="form-control" value="" />';
	content += '        </div>';
	content += '        <div class="col-md-1">';
	content += '        	<button type="button" class="btn btn-link btn-xs text-danger" onclick="removeDeclareBox(' + num + ',1)"><i class="fa fa-trash"></i></button>';
	content += '        </div>';
	content += '	</div>';
	content += '	<div id="d' + num + '_2" class="form-group declare" rel="2">';
	content += '		<div class="col-md-7">';
	content += '        	<input type="text" name="declare_type[' + num + '][]" id="declare_type' + num + '_2" placeholder="" title="" class="form-control" value="" />';
	content += '        </div>';
	content += '		<div class="col-md-2">';
	content += '       	<input type="number" name="declare_qty[' + num + '][]" id="declare_qty' + num + '_2" placeholder="" title="" class="form-control" value="" />';
	content += '       </div>';
	content += '       <div class="col-md-2">';
	content += '        	<input type="number" name="declare_value[' + num + '][]" id="declare_value' + num + '_2" placeholder="" title="" class="form-control" value="" />';
	content += '        </div>';
	content += '        <div class="col-md-1">';
	content += '        	<button type="button" class="btn btn-link btn-xs text-danger" onclick="removeDeclareBox(' + num + ',2)"><i class="fa fa-trash"></i></button>';
	content += '        </div>';
	content += '	</div>';
	content += '	<div id="d' + num + '_3" class="form-group declare" rel="3">';
	content += '		<div class="col-md-7">';
	content += '        	<input type="text" name="declare_type[' + num + '][]" id="declare_type' + num + '_3" placeholder="" title="" class="form-control" value="" />';
	content += '        </div>';
	content += '		<div class="col-md-2">';
	content += '       	<input type="number" name="declare_qty[' + num + '][]" id="declare_qty' + num + '_3" placeholder="" title="" class="form-control" value="" />';
	content += '       </div>';
	content += '       <div class="col-md-2">';
	content += '       	<input type="number" name="declare_value[' + num + '][]" id="declare_value' + num + '_3" placeholder="" title="" class="form-control" value="" />';
	content += '       </div>';
	content += '       <div class="col-md-1">';
	content += '       	<button type="button" class="btn btn-link btn-xs text-danger" onclick="removeDeclareBox(' + num + ',3)"><i class="fa fa-trash"></i></button>';
	content += '       </div>';
	content += '	</div>';
	content += '    <div id="declare_box' + num + '"></div>';
	content += '	<div class="form-group">';
	content += '		<div class="col-md-12 text-right">';
	content += '			<button type="button" class="btn btn-link btn-xs small" onclick="addDeclareBox(' + num + ')">[+ Add Product]</button>';
	content += '		</div>';
	content += '	</div>';
	content += '</div>';
	content += '<input type="hidden" class="agent_rate" id="agent_rate' + num + '" />';

	content += '</div>'
	content += '</div>'
		
	$("#boxes").append(content);
	
}
function copyBox(stand){

	var lastBoxNum = $('div.box:last').attr("rel");
	var num = parseInt(lastBoxNum) + 1;
	
	addBox();

	$("#weight" + num).val($("#weight" + stand).val());
	$("#width" + num).val($("#width" + stand).val());
	$("#height" + num).val($("#height" + stand).val());
	$("#length" + num).val($("#length" + stand).val());

	$("#agent_rate" + num).val($("#agent_rate" + stand).val());

	$("#declare_type" + num + "_0").val($("#declare_type" + stand + "_0").val());
	$("#declare_qty" + num + "_0").val($("#declare_qty" + stand + "_0").val());
	$("#declare_value" + num + "_0").val($("#declare_value" + stand + "_0").val());
	$("#declare_type" + num + "_1").val($("#declare_type" + stand + "_1").val());
	$("#declare_qty" + num + "_1").val($("#declare_qty" + stand + "_1").val());
	$("#declare_value" + num + "_1").val($("#declare_value" + stand + "_1").val());
	$("#declare_type" + num + "_2").val($("#declare_type" + stand + "_2").val());
	$("#declare_qty" + num + "_2").val($("#declare_qty" + stand + "_2").val());
	$("#declare_value" + num + "_2").val($("#declare_value" + stand + "_2").val());

	addSumBox();
	
}
function removeBox(box){

	$("#box"+box).remove();
	
	/*
	var content = "";
	content += '<div class="row undo">';
	content += '<div class="col-md-12 text-center text-info small">Box#'+box+' removed <a href="javascript:undoBox('+box+');">undo</a></div>';
	content += '</div>';
	$("#box"+box+" .panel-heading").hide();
	$("#box"+box+" .row-no-padding").hide();
	$("#box"+box).append(content);
	$("#agent_rate"+box).val(0);
	//$("#box"+box).remove();
	//$("#sum_box"+box).remove();
	*/
	
	addSumBox();
	
}
function undoBox(box){

	$("#box"+box+" .undo").hide();
	$("#box"+box+" .panel-heading").show();
	$("#box"+box+" .row-no-padding").show();

	calculateRate(box);
	
}
function addDeclareBox(box){

	var lastDecNum = $('#declare_box'+box+' div.declare:last').attr("rel");
	var num = parseInt(lastDecNum) + 1;
	
	var content = '';
	content += '	<div id="d' + box + '_' + num + '" class="form-group declare" rel="' + num + '">';
	content += '		<div class="col-md-7">';
	content += '        	<input type="text" name="declare_type[' + box + '][]" id="declare_type' + box + '_' + num + '" placeholder="" title="" class="form-control" value="" />';
	content += '        </div>';
	content += '		<div class="col-md-2">';
	content += '       	<input type="number" name="declare_qty[' + box + '][]" id="declare_qty' + box + '_' + num + '" placeholder="" title="" class="form-control" value="" />';
	content += '       </div>';
	content += '       <div class="col-md-2">';
	content += '       	<input type="number" name="declare_value[' + box + '][]" id="declare_value' + box + '_' + num + '" placeholder="" title="" class="form-control" value="" />';
	content += '       </div>';
	content += '       <div class="col-md-1">';
	content += '       	<button type="button" class="btn btn-link btn-xs text-danger" onclick="removeDeclareBox(' + box + ',' + num + ')"><i class="fa fa-trash"></i></button>';
	content += '       </div>';
	content += '	</div>';
	
	$("#declare_box"+box).append(content);
	
}
function removeDeclareBox(box,dec){
	$("#d"+box+"_"+dec).remove();
}
</script>
 
@endsection