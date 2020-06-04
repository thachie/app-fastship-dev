@extends('layout')
@section('content')
<?php 
    $validateEnglish = "^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$";
    $validateDeclare = "^[a-zA-Z0-9 /+&]+$";
?>
<div class="conter-wrapper">
        <div class="row">
            <div class="col-md-6 pad8"><h2>{!! FT::translate('create_shipment.heading') !!}</h2></div>
            <div class="col-md-6 text-right">
                <div class="bs-wizard dot-step" style="border-bottom:0;">
                    <div class="col-xs-3 bs-wizard-step complete">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                        <p class="text-center">{!! FT::translate('step.step1') !!}</p>
                    </div> 
                    <div class="col-xs-3 bs-wizard-step active">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">2</span></a>
                        <p class="text-center">{!! FT::translate('step.step2') !!}</p>
                    </div>
                    <div class="col-xs-3 bs-wizard-step disabled">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">3</span></a>
                        <p class="text-center">{!! FT::translate('step.step3') !!}</p>
                    </div>  
                    <div class="col-xs-3 bs-wizard-step disabled">
                        <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">4</span></a>
                        <p class="text-center">{!! FT::translate('step.step4') !!}</p>
            		</div>  
            </div>
        </div>
    </div>
    <form id="shipment_form" name="shipment_form" class="form-horizontal" method="post" action="{{url ('shipment/create')}}" autocomplete="false">
        
        {{ csrf_field() }}
        
        <input type="hidden" name="agent" value="<?php echo $default['agent'];?>">
        <input type="hidden" name="country" value="<?php echo $default['country']; ?>" />
        <input type="hidden" name="weight" value="<?php echo $default['weight'];?>">
        <input type="hidden" name="width" value="<?php echo $default['width'];?>">
        <input type="hidden" name="length" value="<?php echo $default['length'];?>">
        <input type="hidden" name="height" value="<?php echo $default['height'];?>">
        <input type="hidden" name="price" value="<?php echo $default['price'];?>">
        <input type="hidden" name="delivery_time" value="<?php echo $default['delivery_time'];?>">
        
        <div class="row">
          
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">{!! FT::translate('create_shipment.panel.heading1') !!}</div>
                    <div class="panel-body">

                    	<div class="col-md-4 col-xs-4 text-center no-padding"> 
                        	<img src="images/agent/<?php echo $default['agent'];?>.gif" style="max-width: 100px;"/>
                        </div>
                        <div class="col-md-8 col-xs-8"> 
	                        <table class="table-dimension col-md-12 small text-left">
	                        <thead>
		                        <tr>
		                        	<td>{!! FT::translate('label.weight') !!}</td>
		                        	<td class="hidden-xs">{!! FT::translate('label.dimension') !!}</td>
		                        	<td>{!! FT::translate('label.shipping') !!}</td>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	<tr>
		                        	<td><span class="sumresult"><?php echo number_format($default['weight'],0);?></span></td>
		                        	<td class="hidden-xs">
			                        	<?php if($default['width'] != "" && $default['length'] != "" && $default['height'] != ""): ?>
			                        	<span class="sumresult"><?php echo $default['width']."×".$default['length']."×".$default['height']; ?></span>
		                        		<?php else: ?>
		                        		<span class="sumresult">-</span>
		                        		<?php endif; ?>
		                        	</td>
		                        	<td>
		                        	<?php if($default['agent'] == "Quotation"): ?>
		                        	<span class="sumresult">TBC</span>
		                        	<?php else: ?>
		                        	<span class="sumresult"><?php echo number_format($default['price'],0);?></span>
		                        	<?php endif;?>
		                        	</td>
		                        </tr>
		                    </tbody>
	                        </table>
	                    </div>
                        <div class="clearfix"></div>
                        <hr />
                    
                        
                        <table class="table table-hover table-ship">
                            <thead>
                            <tr>
                                <th scope="col" width="60%">{!! FT::translate('label.declare_type') !!} ({!! FT::translate('label.english') !!})</th>
                                <th scope="col">{!! FT::translate('label.declare_qty') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_value') !!}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="product_table">
                            <?php
                            if(is_array($default['category'])):
                            foreach ( $default['category'] as $key => $category):
                            ?>
                            <tr id='row<?php echo $key ?>'>
                                <td>
                                    <input type="text" id="category<?php echo $key ?>" name="category[<?php echo $key ?>]" class="category form-control required"  pattern="<?php echo $validateDeclare; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" value="<?php echo $default['category'][$key];?>" />
                                	<div class="red tiny text-left col-md-10 no-padding"><span id="category<?php echo $key ?>-error" class="error-msg"></span></div> 
                                </td>      
                                <td><input type="number" min="1" name="amount[<?php echo $key ?>]" class="form-control required" value="<?php echo isset($default['amount'][$key])?$default['amount'][$key]:"";?>" /></td>
                                <td><input type="number" min="1" name="value[<?php echo $key ?>]" class="form-control required" value="<?php echo isset($default['value'][$key])?$default['value'][$key]:"";?>" /></td>
                                
                                <?php if ($key > 0){ ?>
                                <td><span class='glyphicon glyphicon-minus-sign text-danger' onclick='rmv(<?php echo $key ?>)'></span></td>
                                <?php } ?>
                            </tr>
                            <?php
                            endforeach;
                            else:
                            ?>
                            <tr id='row0'>
                                <td>
                                   	<input type="text" id="category0" name="category[0]" class="category form-control required" />
                                  	<div class="red tiny text-left col-md-10 no-padding"><span id="category0-error" class="error-msg"></span></div> 
                                </td>      
                                <td><input type="number" min="1" name="amount[0]" class="form-control declare-qty required" /></td>
                                <td><input type="number" min="1" name="value[0]" class="form-control declare-value required" /></td>
                                <td></td>
                            </tr>
                            <?php 
                            endif;
                            ?>
                            </tbody>
                        </table>
                        <div class="row detailpro">
                            <div class="col-md-6 pull-right text-right"><a href="javascript:add();"><i class="fa fa-plus-circle green"></i> {!! FT::translate('create_shipment.add_declare') !!}</a></div>
                        </div>
                        
                        <?php if(false): ?>
                        <label class="col-md-5 control-label label-top">{!! FT::translate('create_shipment.duty_responsibility') !!}: </label>
                        <div class="col-md-7">
                            <div class="radio">
                                <?php 
                                if($default['term'] == "DDP"){ ?>
                                    <label><input type="radio" name="term" id="ddu" value="DDU" > {!! FT::translate('radio.ddu') !!}</label>
                                    &nbsp;
                                    <label><input type="radio" name="term" id="ddp" value="DDP" checked> {!! FT::translate('radio.ddp') !!}</label>
                                <?php }else{ ?>
                                    <label><input type="radio" name="term" id="ddu" value="DDU" checked> {!! FT::translate('radio.ddu') !!}</label>
                                    &nbsp;
                                    <label><input type="radio" name="term" id="ddp" value="DDP" > {!! FT::translate('radio.ddp') !!}</label>
                                <?php } ?>
                                
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(in_array($default['agent'] , array("Ecom_PD","FS_FBA","FS_FBA_PLUS","FS_FBA_JP","FS_FBA_SG","FS_FBA_UK","FS_FBA_AU","FS_FBA_FR"))): ?>
                        <input type="hidden" name="term" id="ddp" value="DDP">
                        <?php else: ?>
                        <input type="hidden" name="term" id="ddu" value="DDU">
                        <?php endif; ?>

                        <div class="well small" style="background: #fefefe;border: 1px solid #fdd;box-shadow: 0 0 0;">
                        	<h5><b>{!! FT::translate('create_shipment.prohibited_item') !!}</b></h5>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item1') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item2') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item3') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item4') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item5') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item6') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item7') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item8') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item9') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item10') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item11') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item12') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item13') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item14') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item15') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item16') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item17') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item18') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item19') !!}</div>
                        	<div><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item.item20') !!}</div>
                        	<div class="text-center"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><button class="btn btn-link btn-sm">{!! FT::translate('create_pickup.text.more_detail') !!}</button></a></div>
                        	<div class="clearfix"></div>
                        </div>
  
                        <div class="clearfix"></div><br /> 
                        
                        <div class="col-md-6 pull-right visible-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> {!! FT::translate('create_shipment.prohibited_item') !!}</a></div>
                    
                    </div>
                               
                </div>
            </div>
            <div class="col-md-6" >
                <div class="panel panel-primary">
                    <div class="panel-heading">{!! FT::translate('create_shipment.panel.heading2') !!}</div>
                    <div class="panel-body row-no-padding">

                        <div class="form-group col-md-6">
                            <input name="firstname" type="text" placeholder="Firstname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['firstname']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-6">
                            <input name="lastname" type="text" placeholder="Lastname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['lastname']; ?>" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="lastname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="lastname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-6">
                            <input name="phonenumber" type="text" placeholder="Phone Number" class="form-control required input-count" maxlength="50" value="<?php echo $default['receiver']['phonenumber']; ?>" />
                       		<div class="red tiny text-left col-md-10 no-padding"><span id="phonenumber-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="phonenumber-count">0</span>/50</div> 
                        </div>
                        <div class="form-group col-md-6">
                            <input name="email" type="text" placeholder="Email" title="Email" class="form-control required input-count" maxlength="50" value="<?php echo $default['receiver']['email']; ?>" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="email-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="email-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="company" type="text" placeholder="Company Name" class="form-control input-count"  pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="100" value="<?php echo $default['receiver']['company']; ?>"/>
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="company-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="company-count">0</span>/100</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="address1" placeholder="Address" placeholder="Street Address" type="text" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['address1']; ?>"/>
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="address1-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address1-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="address2" placeholder="Address (cont.)" placeholder="Address (continue)" type="text" class="form-control input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['address2']; ?>"/>
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="address2-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address2-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-6">
                            <input id="rec_state" name="state" type="text" placeholder="State" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="50" value="<?php echo $default['receiver']['state']; ?>" />
                        	<input id="rec_state_code" name="state_code" type="hidden" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="state-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="state-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-6">
                            <input id="rec_city" name="city" type="text" placeholder="City" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('{!! FT::translate('error.english_only') !!}')" oninput="setCustomValidity('')" maxlength="50" value="<?php echo $default['receiver']['city']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="city-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="city-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-6">
                            <input name="postcode" type="text" placeholder="Postcode" class="form-control required input-count" maxlength="10" value="<?php echo $default['receiver']['postcode']; ?>" />
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="postcode-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="postcode-count">0</span>/10</div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-control"><?php echo $countries[$default['country']]; ?></div>
                            <div class="col-md-12 no-padding">&nbsp;</div>
                        </div>   
                        <div class="form-group col-md-6">
                            <input name="note" type="text" placeholder="Remark" class="form-control input-count" maxlength="100" value="">
                            <div class="red tiny text-left col-md-10 no-padding"><span id="note-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="note-count">0</span>/100</div>
                        </div>
                        <div class="form-group col-md-6">
                            <input name="orderref" type="text" placeholder="Ebay/Amazon Order Ref." class="form-control input-count" maxlength="100" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="orderref-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="orderref-count">0</span>/100</div>
                        </div>       
                    </div>
                </div>
                
                 @if($default['country'] == "CHN")
                <div class="col-md-12 text-warning text-center" style="margin-top:20px; ">
               		 {!! FT::translate('create_shipment.warning.china') !!}<br />
               		 พัสดุที่ส่งไปประเทศจีนปลายทางเป็นบุคคล สามารถส่งได้ไม่เกิน 10 ชิ้น 10 กิโลกรัมต่อกล่อง
                </div>
                @endif

                @if( $default['agent'] == "Aramex" && ($default['country'] == "KOR" || $default['country'] == "HKG") )
                <div class="col-md-12 text-center text-danger">
                	{!! FT::translate('create_shipment.warning.aramex_passport') !!}
                </div>
                <div class="col-md-12 text-warning text-center" style="margin-top:20px; ">
                       	กรุณาแนบสำเนาบัตรประชาชนมาพร้อมกับกล่องพัสดุ หรือส่งอีเมล์เข้ามาที่ <a href="mailto:cs@fastship.co">cs@fastship.co</a>
                </div>
                @endif

            </div>
        </div>
        <div class="text-center btn-create"><button type="submit" name="submit" class="btn btn-lg btn-primary minus-margin">{!! FT::translate('button.create_shipment') !!}</button></div>
    	<div class="clearfix"></div>
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

		if(_country  == "") return false; 

    	$('#rec_state').autocomplete({
            minLength: 3,
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
    	
        if(_country  == "") return false; 
        if(_state == "") return false; 
        
    	$('#rec_city').autocomplete({
            minLength: 3,
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
            minLength: 4,
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

    function add(){
        var table_size = $("#product_table" ).children().length;
        var row = "<tr id='row"+table_size+"'>"+
                        "<td>"+
                            "<input id='category-"+table_size+"' type='text' name='category["+table_size+"]' class='category form-control required' required />"+                                
                            "<span id='category"+table_size+"-error' class='error-msg'></span>" +          
                        "</td>"+
                        "<td><input type='number' min='1' name='amount["+table_size+"]' class='form-control required declare-qty' required /></td>"+
                        "<td><input type='number' min='1' name='value["+table_size+"]' class='form-control required declare-value' required /></td>"+
                        "<td><span class='glyphicon glyphicon-minus-sign text-danger' onclick='rmv("+table_size+")'></span></td>"+
                    "</tr>";
        $( "#product_table" ).append(row);
        $("#category-"+table_size).keyup(validateDeclare);

        autocompleteDeclare($("#row"+table_size+" input.category"));

    }
    
    function rmv(id){
        $( "#row"+id ).remove();
    }

	//validate
	var validateEnglish = new RegExp(/<?php echo $validateEnglish; ?>/);
	
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

	$("#shipment_form").submit( function() {

		var validate = true;
		
		$("#shipment_form .required").each(validateRequired);
		$("#shipment_form input").each(validateOptional);
		$("#shipment_form .category").each(validateDeclare);
		$("input[name=email]").each(validateEmailFormat);

		$(".error-msg").each(function(){
			if($(this).text() != ""){
				validate = false;
			}
		});

		if(!validate) return false;

		$("#shipment_form [name=submit]").attr("disabled",true);

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
	function validateDeclare(){

		var nm = $(this).attr("name");
		var id = $(this).attr("id");
		var val = $(this).val();
		var values = val.split(" ");
		var censors = ["gun","explosive","bomb","sex","fuck","porn","weapon","alcohol","chemical","ash","gift","food","souvenir","medicine","cosmetics","present","food","diet"];

		$('#'+id+"-error").html("");
		values.forEach(function(term){
			var check = censors.indexOf(term.toLowerCase());

			if(check >= 0 ){
				$(this).addClass("error");
				$('#'+id+"-error").text( censors[check] + " ไม่สามารถส่งได้หรือข้อมูลไม่ชัดเจน");
	 			//$('#'+nm+"-error").text("{!! FT::translate('error.required') !!}");
			}
		});

// 		if(val == ""){
// 			$(this).addClass("error");
// 			$('#'+nm+"-error").text("{!! FT::translate('error.required') !!}");
// 		}else if(!validateEnglish.test(val)){
// 			$(this).addClass("error");
// 			$('#'+nm+"-error").text("{!! FT::translate('error.english_only') !!}");
// 		}else{
// 			$('#'+nm+"-error").text("");
// 			$(this).removeClass("error");
// 		}
	}
	
	function inputCount() {
		var nm = $(this).attr("name");
	    var cs = $(this).val().length;
	    $('#'+nm+"-count").text(cs);
	}

	$(document).ready(function() {
		
    	@if(session('shipment.firstname') !== null)
    		
    		$("input[name=firstname]").val("{{ session('shipment.firstname') }}");
    		$("input[name=lastname]").val("{{ session('shipment.lastname') }}");
    		$("input[name=phonenumber]").val("{{ session('shipment.phonenumber') }}");
    		$("input[name=email]").val("{{ session('shipment.email') }}");
    		$("input[name=company]").val("{{ session('shipment.company') }}");
    		$("input[name=address1]").val("{{ session('shipment.address1') }}");
    		$("input[name=address2]").val("{{ session('shipment.address2') }}");
    		$("input[name=city]").val("{{ session('shipment.city') }}");
    		$("input[name=state]").val("{{ session('shipment.state') }}");
    		$("input[name=postcode]").val("{{ session('shipment.postcode') }}");
    		$("input[name=reference]").val("{{ session('shipment.reference') }}");

    		@if(session('shipment.term') == "ddp")
    			$("#ddp").attr('checked', true);
    		@else
    			$("#ddu").attr('checked', true);
        	@endif

    		var declareTypeTxt = "{{ session('shipment.declaretype') }}";
    		var declareValueTxt = "{{ session('shipment.declarevalue') }}";
    		var declareQtyTxt = "{{ session('shipment.declareqty') }}";
    		
    		
    		var declareType = declareTypeTxt.split(";");
    		var declareValue = declareValueTxt.split(";");
    		var declareQty = declareQtyTxt.split(";");
    		console.log(declareQty[0]);
    		console.log(declareValue[0]);
    		declareType.forEach(function(item, index){

        		console.log(index + " : " + item);
        		if(item == "") return false;

        		if(index > 0){
            		var table_size = $("#product_table" ).children().length;
                    var row = "<tr id='row"+table_size+"'>"+
                                    "<td>"+
                                        "<input id='category-"+table_size+"' type='text' name='category["+table_size+"]' class='category form-control required' required value='"+item+"' />"+ 
                                        "<span id='category"+table_size+"-error' class='error-msg'></span>" +                      
                                    "</td>"+
                                    "<td><input type='number' min='1' name='amount["+table_size+"]' class='form-control required declare-qty' required value='"+declareQty[index]+"' /></td>"+
                                    "<td><input type='number' min='1' name='value["+table_size+"]' class='form-control required declare-value' required value='"+declareValue[index]+"' /></td>"+
                                    "<td><span class='glyphicon glyphicon-minus-sign text-danger' onclick='rmv("+table_size+")'></span></td>"+
                                "</tr>";
                    $( "#product_table" ).append(row);
                    
                    $("input[name=category["+table_size+"]]").keyup(validateDeclare);
                    
        		}else{
            		$("#row0 .category").val(item);
            		$("#row0 .declare-qty").val(declareQty[0]);
            		$("#row0 .declare-value").val(declareValue[0]);

            		$("input[name=category[0]]").keyup(validateDeclare);
            		
        		}


        		
        		
    		});
    		

    	@endif
    	
	});
</script>
@endsection