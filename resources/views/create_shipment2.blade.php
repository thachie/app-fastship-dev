@extends('layout')
@section('content')
<?php 
    $validateEnglish = "^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$";
    $validateDeclare = "^[a-zA-Z0-9 /+&]+$";
?>
<div class="conter-wrapper">
        <div class="row">
            <div class="col-md-7 pad8"><h2>สร้างพัสดุ - กรอกที่อยู่ผู้รับ</h2></div>
            <div class="col-md-5 text-right">
                <div class="bs-wizard dot-step" style="border-bottom:0;">
                    <div class="col-xs-4 bs-wizard-step complete">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                        <p class="text-center">กรอกข้อมูลพัสดุ</p>
                    </div> 
                    <div class="col-xs-4 bs-wizard-step active">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">2</span></a>
                        <p class="text-center">กรอกข้อมูลผู้รับ</p>
                    </div>
                    <div class="col-xs-4 bs-wizard-step disabled">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">3</span></a>
                        <p class="text-center">ส่งพัสดุ</p>
                    </div>    
            </div>
        </div>
    </div>
    <form id="shipment_form" name="shipment_form" class="form-horizontal" method="post" action="{{url ('shipment/create')}}">
        
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
                    <div class="panel-heading">กรอกรายละเอียดพัสดุ</div>
                    <div class="panel-body">

                    	<div class="col-md-4 col-xs-4 text-center no-padding"> 
                        	<img src="images/agent/<?php echo $default['agent'];?>.gif" style="max-width: 100px;"/>
                        </div>
                        <div class="col-md-8 col-xs-8"> 
	                        <table class="table-dimension col-md-12 small text-left">
	                        <thead>
		                        <tr>
		                        	<td>น้ำหนัก (กรัม)</td>
		                        	<td class="hidden-xs">ขนาด (ซม.)</td>
		                        	<td>ค่าขนส่ง (บาท)</td>
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
		                        	<td><span class="sumresult"><?php echo number_format($default['price'],0);?></span></td>
		                        </tr>
		                    </tbody>
	                        </table>
	                    </div>
                        <div class="clearfix"></div>
                        <hr />
                    
                        
                        <table class="table table-hover table-ship">
                            <thead>
                            <tr>
                                <th scope="col" width="60%">ประเภท (ภาษาอังกฤษเท่านั้น)</th>
                                <th scope="col">จำนวน(ชิ้น)</th>
                                <th scope="col">มูลค่ารวม(บาท)</th>
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
                                    <input type="text" name="category[<?php echo $key ?>]" class="category form-control required"  pattern="<?php echo $validateDeclare; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" value="<?php echo $default['category'][$key];?>" />
                                </td>      
                                <td><input type="number" min="1" name="amount[<?php echo $key ?>]" class="form-control required" value="<?php echo $default['amount'][$key];?>" /></td>
                                <td><input type="number" min="1" name="value[<?php echo $key ?>]" class="form-control required" value="<?php echo $default['value'][$key];?>" /></td>
                                
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
                                   	<input type="text" name="category[0]" class="category form-control required" />
                                  	<div class="red tiny text-left col-md-10 no-padding"><span id="category0-error" class="error-msg"></span></div> 
                                </td>      
                                <td><input type="number" min="1" name="amount[0]" class="form-control required" /></td>
                                <td><input type="number" min="1" name="value[0]" class="form-control declare-value required" /></td>
                                <td></td>
                            </tr>
                            <?php 
                            endif;
                            ?>
                            </tbody>
                        </table>
                        <div class="row detailpro">
                            <div class="col-md-6 pull-right text-right"><a href="javascript:add();"><i class="fa fa-plus-circle green"></i> เพิ่มประเภทพัสดุใหม่</a></div>
                            <div class="col-md-6 pull-left hidden-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> สินค้าที่ไม่รับขนส่งไปต่างประเทศ</a></div>
                        </div>
                        
                            <label class="col-md-5 control-label label-top">ผู้รับผิดชอบภาษี : </label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <?php 
                                    if($default['term'] == "DDP"){ ?>
                                        <label><input type="radio" name="term" id="ddu" value="DDU" > ผู้รับ (DDU)</label>
                                        &nbsp;
                                        <label><input type="radio" name="term" id="ddp" value="DDP" checked> ผู้ส่ง (DDP)</label>
                                    <?php }else{ ?>
                                        <label><input type="radio" name="term" id="ddu" value="DDU" checked> ผู้รับ (DDU)</label>
                                        &nbsp;
                                        <label><input type="radio" name="term" id="ddp" value="DDP" > ผู้ส่ง (DDP)</label>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- <label class="col-md-6 control-label label-top">ซื้อประกันเพิ่ม : </label>
                            <div class="col-md-6">
                                <div class="radio">
                                <?php 
                                    //if($default['insurance'] == "0"){ ?>
                                        <label><input type="radio" name="insurance" id="insurance_no" value="0" checked> ไม่ซื้อ</label>
                                        &nbsp;
                                        <label><input type="radio" name="insurance" id="insurance_yes" value="<?php //echo $default['insurance'] ?>"> ซื้อเพิ่ม (<span id="value-insurance"><?php //echo $default['insurance'] ?></span> บาท)</label>
                                    <?php //}else{ ?>
                                        <label><input type="radio" name="insurance" id="insurance_no" value="0"> ไม่ซื้อ</label>
                                        &nbsp;
                                        <label><input type="radio" name="insurance" id="insurance_yes" value="<?php //echo $default['insurance'] ?>" checked> ซื้อเพิ่ม (<span id="value-insurance"><?php //echo $default['insurance'] ?></span> บาท)</label>
                                    <?php //} ?>
                                </select>
                                </div>
                            </div>

                        <label class="col-md-6 control-label label-top">มูลค่าสูงสุด ที่ส่งโดยไม่ต้องเสียภาษี : </label>
                        <label class="col-md-6 control-label" style="text-align: left;">{{ $deminimis }}</label>
                       -->
                        <div class="clearfix"></div><br /> 
                        
                        <div class="col-md-6 pull-right visible-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> สินค้าที่ไม่รับขนส่งไปต่างประเทศ</a></div>
                    </div>
                               
                </div>
            </div>
            <div class="col-md-6" >
                <div class="panel panel-primary">
                    <div class="panel-heading">ที่อยู่ผู้รับ</div>
                    <div class="panel-body row-no-padding">
                    
                    	<?php if($default['country'] == "USA"): ?>
                    	<div class="form-group col-md-12 text-right">
                            <button type="button" class="btn btn-xs btn-default" onclick="openFBA();">
                            	 <img src="{{ url('images/FBA.jpg') }}" />
                            </button>
                        </div>
                        
                        <div id="fba" class="text-center" style="display: none;">
                			
                			<h4>เลือกที่อยู่สำหรับส่ง Amazon Fulfillment Center</h4>
						
							<div class="col-md-6 col-md-offset-2"><input type="text" id="fba_input" class="form-control text-center" placeholder="ใส่รหัส FBA ที่ต้องการส่ง" /></div>
							<div class="col-md-2 text-left"><button type="button" class="btn btn-info" onclick="getFBAAddress();">ค้นหา</button></div>
							<div class="clearfix"></div><br />
							
    						<div id="fba_detail" class="col-md-10 col-md-offset-1 text-center well" style="display:none;">
    							
    						</div>
    						<input type="hidden" id="tmp_code" />
    						<input type="hidden" id="tmp_address" />
    						<input type="hidden" id="tmp_city" />
    						<input type="hidden" id="tmp_state" />
    						<input type="hidden" id="tmp_postcode" />
    						
                        	<div class="clearfix"></div>
                    	
                    		<hr />
                		</div>
                		<?php endif; ?>

                        <div class="form-group col-md-6">
                            <input name="firstname" type="text" placeholder="Firstname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['firstname']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-6">
                            <input name="lastname" type="text" placeholder="Lastname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['lastname']; ?>" />
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
                            <input name="company" type="text" placeholder="Company Name" class="form-control input-count"  pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="100" value="<?php echo $default['receiver']['company']; ?>"/>
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="company-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="company-count">0</span>/100</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="address1" placeholder="Address" placeholder="Street Address" type="text" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['address1']; ?>"/>
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="address1-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address1-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="address2" placeholder="Address (cont.)" placeholder="Address (continue)" type="text" class="form-control input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['address2']; ?>"/>
                        	<div class="red tiny text-left col-md-10 no-padding"><span id="address2-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address2-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-6">
                            <div id="state_ajax">
                           		<select name="state" id="state" class="form-control required" required></select>
                            </div>
                            <div id="state_ajax_loading" class="form-control-static" style="display: none;">loading...</div>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="state-error" class="error-msg"></span></div> 
                            
                        </div>
                        <div class="form-group col-md-6">
                            <div id="city_ajax">
                           		<select name="city" id="city" class="form-control required" required></select>
                            </div>
                            <div id="city_ajax_loading" class="form-control-static" style="display: none;">loading...</div>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="city-error" class="error-msg"></span></div> 
                            
                        </div>
                        <div class="form-group col-md-6">
                            <div id="postcode_ajax">
<!--                             	<select name="postcode" id="postcode" class="form-control required" required></select> -->
								<input id="postcode" type="text" name="postcode" class="form-control required" required />
                            </div>
                            <div id="postcode_ajax_loading" class="form-control-static" style="display: none;">loading...</div>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="postcode-error" class="error-msg"></span></div> 
                            
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
            </div>
        </div>
        <div class="text-center btn-create"><button type="submit" name="submit" class="btn btn-lg btn-primary minus-margin">สร้างพัสดุ</button></div>
    	<div class="clearfix"></div>
    </form>

</div>
<div class="modal fade" id="fba1" tabindex="-1" role="dialog" aria-labelledby="ModalFBA_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	
                	
            	</div>
        	</div>
        </div>
<script type="text/javascript">
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
                        "<td><input type='number' min='1' name='amount["+table_size+"]' class='form-control required' required /></td>"+
                        "<td><input type='number' min='1' name='value["+table_size+"]' class='form-control required declare-value' required /></td>"+
                        "<td><span class='glyphicon glyphicon-minus-sign text-danger' onclick='rmv("+table_size+")'></span></td>"+
                    "</tr>";
        $( "#product_table" ).append(row);
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
	<?php if($default['country'] == "USA"): ?>
	function openFBA(){
		$('#fba').fadeIn(500);
		$('#fba_input').focus();
	}
	function getFBAAddress(){

		var _code = $("#fba_input").val();
		$.post("{{url ('shipment/get_fba_address')}}",
		{
			_token: $("[name=_token]").val(),
			code: _code,
		},function(data){

			console.log(data);

    		content = "";
    		content += "<h3>" + data.Code + " <img src='{{ url('images/FBA.jpg') }}' style='vertical-align:top;' /></h3>";
    		content += "<h4>" + data.Address + "</h4>";
    		content += "<h4>" + data.City + " " + data.State + "</h4>";
    		content += "<div class='clearfix'></div><br /><input type='button' class='btn btn-success ' value='เลือกที่อยู่นี้' onclick='selectFBAAddress();' />";

    		$("#tmp_code").val(data.Code);
    		$("#tmp_address").val(data.Address);
    		$("#tmp_city").val(data.City);
    		$("#tmp_state").val(data.State);
    		$("#tmp_postcode").val(data.Postcode);
    		
    		$("#fba_detail").show();
            $("#fba_detail").html(content);

		},"json");
	}
	function selectFBAAddress(){

		$("#shipment_form input[name=company]").val("Amazon Fulfillment Center [" + $("#tmp_code").val()+"]");
		$("#shipment_form input[name=address1]").val( $("#tmp_address").val() );
		$("#shipment_form input[name=city]").val( $("#tmp_city").val() );
		$("#shipment_form input[name=state]").val( $("#tmp_state").val() );
		$("#shipment_form input[name=postcode]").val( $("#tmp_postcode").val() );

		$("#fba_input").val("");
		$("#fba_detail").html("");
		$("#fba_detail").hide();
		$("#fba").slideUp(500);

	}
	<?php endif; ?>
	
	$(document).ready(function() {
		$(window).keydown(function(event){
		    if(event.keyCode == 13) {
		      event.preventDefault();
		      return false;
		    }
		  });
	});

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

	function submitForm(){
		var validate = true;
		$("#shipment_form [name=email]").each(validateEmailFormat);
		$("#shipment_form .required").each(validateRequired);
		$("#shipment_form input").each(validateOptional);

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
		$("#shipment_form [name=email]").each(validateEmailFormat);
		$("#shipment_form .required").each(validateRequired);
		$("#shipment_form input").each(validateOptional);

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
			$('#'+nm+"-error").text("กรุณากรอกข้อมูล ไม่สามารถเว้นว่างได้");
		}else if(!validateEnglish.test(val)){
			 $('#'+nm+"-error").text("กรุณากรอกเป็นภาษาอังกฤษ");
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
			 $('#'+nm+"-error").text("กรุณากรอกเป็นภาษาอังกฤษ");
		}else{
			$('#'+nm+"-error").text("");
		}
	}
	function validateEmailFormat(){
		
		var nm = $(this).attr("name");
		var val = $(this).val();

		//check email
		var atpos = val.indexOf("@");
	    var dotpos = val.lastIndexOf(".");
	    var validEmail = !(atpos<1 || dotpos<atpos+2 || dotpos+2>=val.length);

		if(val == ""){
			$('#'+nm+"-error").text("กรุณากรอกข้อมูล ไม่สามารถเว้นว่างได้");
		}else if(!validateEnglish.test(val)){
			 $('#'+nm+"-error").text("กรุณากรอกเป็นภาษาอังกฤษ");
		}else if(!validEmail){
			$('#'+nm+"-error").text("กรุณากรอกในรูปแบบที่ถูกต้อง");
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
	    var countryID = "{{ $country_2iso }}";    
	    if(countryID){

	      $("#state_ajax").hide();
	      $("#state_ajax_loading").show();
	      $.ajax({
	        type:"post",
	        data:{
	        	"_token" : $("[name=_token]").val(),
	        	"country_id" : countryID
	        },
	        url:"{{url('address/states')}}",
	        success:function(res){              
	        if(res){
	        	$("#state_ajax_loading").hide();
	            $("#state_ajax").show();
	            $("#state").empty();
	            $("#city").empty();
	            $("#postcode").empty();
	            
	            $("#state").append('<option value="">Select State</option>');
	            if(res.states != ''){
	              $.each(res.states,function(states,key){
	                $("#state").append('<option value="'+ key.STATE_CODE+'">'+ key.STATE_CODE + " - " + key.STATE_NAME +'</option>');

	              });
	            }else{
	              $("#state").append('<option value=".">no state</option>');
	            }
	        }else{
	           $("#state").empty();
	        }
	        }
	      });
	    }else{
	      $("#state").empty();
	      $("#city").empty();
	      $("#postcode").empty();
	    }      
	  });
	  
	  $('#state').on('change',function(){
		var countryID = "{{ $country_2iso }}";  
	    var stateID = $("#state").val();    
	    if(stateID){
	        $("#city_ajax").hide();
	        $("#city_ajax_loading").show();
	        $.ajax({
	           type:"post",
	           url:"{{url('address/cities')}}",
	           data:{
	           	"_token" : $("[name=_token]").val(),
	           	"country_id" : countryID,
	           	"state_id" : stateID
	           },
	           success:function(res){           
	            if(res){
	              $("#city_ajax_loading").hide();
	              $("#city_ajax").show();
	              $("#city").empty();
	              $("#postcode").empty();

	              $("#city").append('<option value="">Select City</option>');
	              if(res.cities != ''){
	                $.each(res.cities,function(cities,key){
	                  $("#city").append('<option value="'+key.CITY_NAME_ASCII+'">'+key.CITY_NAME_ASCII+'</option>');
	                });
	              }else{
	                $("#city").append('<option value=".">no city</option>');
	              }
	            }else{
	               $("#city").empty();
	            }
	           }
	        });
	    }else{
	        $("#city").empty();
	        $("#postcode").empty();
	    }
	  });

	  /*
	  $('#city').on('change',function(){
		var countryID = "{{ $country_2iso }}";  
		var stateID = $("#state").val();   
	    var cityName = $("#city").val();   

	    if(cityName){
	    	$("#postcode_ajax").hide();
	        $("#postcode_ajax_loading").show();
	        $.ajax({
	           type:"post",
	           url:"{{url('address/postcodes')}}",
	           data:{
	              	"_token" : $("[name=_token]").val(),
	              	"country_id" : countryID,
	              	"city_name" : cityName
	              },
	           success:function(res){       
	            if(res){

	              $("#postcode_ajax_loading").hide();
	              $("#postcode_ajax").show();
	              $("#postcode_ajax").empty();
	              $("#postcode").empty();

	              if(res.postcodes != '' && res.postcodes != false){
	            	$("#postcode_ajax").append('<select id="postcode" name="postcode" class="form-control required" required></select>');
	            	$("#postcode").append('<option value="">Select Postcode</option>');
	                $.each(res.postcodes,function(postcode,key){
	                  $("#postcode").append('<option value="'+key.POST_CODE+'">'+key.POST_CODE+'</option>');
	                });
	              }else{
	                $("#postcode_ajax").append('<input id="postcode" type="text" name="postcode" class="form-control required" required />');
	              }
	            }else{
	               $("#postcode").empty();
	            }
	           }
	        });
	    }else{
	        $("#postcode").empty();
	    }
	    
	  });*/
</script>
 
@endsection