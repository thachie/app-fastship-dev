@extends('layout')
@section('content')
<?php 
    $validateEnglish = "^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$";
    $validateDeclare = "^[a-zA-Z0-9 /+&]+$";
?>
<div class="conter-wrapper">
    <div class="row">
        <div class="col-md-7 pad8"><h2>สร้างพัสดุ - กรอกข้อมูลพัสดุ</h2></div>
        <!--<div class="col-md-5 text-right">
            <div class="bs-wizard dot-step" style="border-bottom:0;">
                <div class="col-xs-4 bs-wizard-step active">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                    <p class="text-center">กรอกข้อมูลพัสดุ</p>
                </div> 
                <div class="col-xs-4 bs-wizard-step disabled">
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
        </div>-->
    </div>

    <form id="shipment_form" class="form-horizontal" method="post" action="{{url ('partner/create_shipment')}}">
        <input type="hidden" name="agent" id="shipment_agent" />
        <input type="hidden" name="price" id="shipment_price" />
        <input type="hidden" name="delivery_time" id="shipment_time" />
        <input type="hidden" id="volumnWeightPost" name="volumnWeightPost" />
        <div class="row">
            <!-- Start Colume 1 -->
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">กรอกข้อมูลพัสดุ</div>
                    <div class="panel-body">
                        <div class="row">
                            <label for="weight" class="col-md-4 control-label">น้ำหนัก (กรัม)</label>
                            <div class="col-md-8">
                            <input type="number" class="form-control required" id="weight" name="weight" min="1" max="299999" required onchange="calculateRate(false)" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-4 control-label">บรรจุภัณฑ์</label>
                            <div class="col-md-8">
                                <div class="radio">
                                <label><input type="radio" name="type" id="parcel" value="parcel" onclick="hidedimension()" checked />ซอง</label>
                                &nbsp;
                                <label><input type="radio" name="type" id="box" value="box" onclick="showdimension()" />กล่อง</label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="dimension" style="display: none;">
                            <label for="inputtext" class="col-md-4 control-label">ขนาดพัสดุ (ซม.)</label>
                            <div class="col-md-3">
                            <input type="number" id="width" name="width" class="form-control required" placeholder="กว้าง" onchange="calculateRate(false)" min="0" />
                            </div>
                            <div class="col-md-3">
                            <input type="number" id="length" name="length" class="form-control required" placeholder="ยาว" onchange="calculateRate(false)"  min="0" />
                            </div>
                            <div class="col-md-3">
                            <input type="number" id="height" name="height" class="form-control required" placeholder="สูง" onchange="calculateRate(false)"  min="0" />
                            </div>
                            <div class="clearfix"></div>
                            
                            <label class="col-md- control-label">น้ำหนักปริมาตร (กรัม)</label>
                            <div class="col-md-8"><span id="volumnWeight" style="line-height: 40px;">0</span> กรัม</div>
                        </div>
                        <div id="weight_text" class="text-center small" style="display: none;">กรณีการส่งพัสดุ 20 กิโลกรัมขึ้นไป สามารถสอบถามเจ้าหน้าที่เพิ่มเติมเพื่อขอราคาพิเศษ</div>
                    
                    {{ csrf_field() }}
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">กรอกรายละเอียดพัสดุ</div>
                    <div class="panel-body">
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
                            <tr id='row0'>
                                <td>
                                    <input type="text" name="category[0]" class="category form-control required" />
                                    <div class="red tiny text-left col-md-10 no-padding"><span id="category0-error" class="error-msg"></span></div> 
                                </td>      
                                <td><input type="number" min="1" name="amount[0]" class="form-control required" /></td>
                                <td><input type="number" min="1" name="value[0]" class="form-control declare-value required" /></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row detailpro">
                            <div class="col-md-8 pull-right text-right"><a href="javascript:add();"><i class="fa fa-plus-circle green"></i> เพิ่มประเภทพัสดุใหม่</a></div>
                            
                        </div>

                        <div class="row detailpro" style="padding-top: 15px; padding-bottom: 5px; ">
                            <div class="col-md-8 pull-left hidden-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> สินค้าที่ไม่รับขนส่งไปต่างประเทศ</a></div>
                        </div>
                        <input type="hidden" name="term" id="ddu" value="DDU" >
                        <div class="clearfix"></div><br /> 
                        <div class="col-md-6 pull-right visible-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> สินค้าที่ไม่รับขนส่งไปต่างประเทศ</a></div>
                    </div>
                               
                </div>
            </div>
            <!-- End Colume   1 -->


            <!-- Start Colume 2 -->
            <div class="col-md-4">
                <!-- Start 1 -->
                <div class="panel panel-primary">
                    <div class="panel-heading">ข้อมูลผู้รับ</div>
                    <div class="panel-body row-no-padding">
                    
                        <?php if($default['country_code'] == "USA"): ?>
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

                        <div class="form-group col-md-12">
                            <input name="firstname" type="text" placeholder="Firstname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-12">
                            <input name="lastname" id="lastname" type="text" placeholder="Lastname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="lastname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="lastname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-12">
                            <input name="phonenumber" type="text" placeholder="Phone Number" class="form-control required input-count" maxlength="50" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="phonenumber-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="phonenumber-count">0</span>/50</div> 
                        </div>
                        <div class="form-group col-md-12">
                            <input name="email" type="text" placeholder="Email" title="Email" class="form-control required input-count" maxlength="50" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="email-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="email-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="company" type="text" placeholder="Company Name" class="form-control input-count"  pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="100" value=""/>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="company-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="company-count">0</span>/100</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="address1" placeholder="Address" placeholder="Street Address" type="text" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value=""/>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="address1-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address1-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="address2" placeholder="Address (cont.)" placeholder="Address (continue)" type="text" class="form-control input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value=""/>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="address2-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address2-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-12">
                            <select class="form-control" id="country" name="country" onchange="calculateRate(true)">
                                <option value="">-- Choose Country --</option>
                                  <?php
                                    //$countries = $countryObj;
                                    foreach($countries as $key => $value) {
                                  ?>
                                      <option value="<?= $value['CNTRY_CODE2ISO'].','.$value['CNTRY_CODE'] ?>" title="<?= htmlspecialchars($value['CNTRY_NAME']) ?>">
                                        <?= htmlspecialchars($value['CNTRY_NAME']) ?>
                                      </option>
                                  <?php
                                    }
                                  ?>
                                <!--<option value="">-- Choose Country --</option>
                                <?php
                                    foreach($countries as $key => $c) {
                                ?>
                                <option value="<?= $c['CNTRY_CODE2ISO'].','.$c['CNTRY_CODE'] ?>" title="<?= htmlspecialchars($c['CNTRY_NAME']) ?>">
                                    <?= htmlspecialchars($c['CNTRY_NAME']) ?>
                                </option>-->
                                <?php
                                    }
                                ?>
                            </select>
                            <!--<div class="col-md-12 no-padding">&nbsp;</div>-->
                        </div> 
                        <div class="form-group col-md-12">
                            <select name="state" id="state" class="form-control" required >
                                <option value="">-- Choose State --</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <select name="city" id="city" class="form-control" required >
                                <option value="">-- Choose City --</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <select name="postcode-select" id="postcode-select" class="form-control postcode-select" required>
                                <option value="">-- Choose Postcode --</option>
                            </select>

                            <input id="postcode-input" type="text" name="postcode-input" class="form-control postcode-input" style="display: none;" placeholder="Postcode" required/>
                            <!--<div id="postcode_ajax" style="display: none; padding-top: 5px;">loading...</div>
                            <input type="text" name="postcode" id="postcode" class="form-control required input-count" placeholder="Postcode">
                            <select name="postcode" id="postcode" class="form-control">
                                <option value="">-- Postcode --</option>
                            </select>-->
                        </div>
                          
                        <!--<div class="form-group col-md-12">
                            <input name="note" type="text" placeholder="Remark" class="form-control input-count" maxlength="100" value="">
                            <div class="red tiny text-left col-md-10 no-padding"><span id="note-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="note-count">0</span>/100</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="orderref" type="text" placeholder="Ebay/Amazon Order Ref." class="form-control input-count" maxlength="100" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="orderref-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="orderref-count">0</span>/100</div>
                        </div> -->      
                    </div>
                </div>
                <!-- End 1 -->

                <!-- Start 2 -->
                <div class="panel panel-primary">
                    <div class="panel-heading">ข้อมูลผู้ส่ง</div>
                    <div class="panel-body row-no-padding">
                        <div class="form-group col-md-12">
                            <input name="sender_firstname" type="text" placeholder="Firstname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-12">
                            <input name="sender_lastname" type="text" placeholder="Lastname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="lastname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="lastname-count">0</span>/80</div> 
                        </div>
                         
                    </div>
                </div>
                <!-- End 2 -->
            </div>
            <!-- End Colume   2   -->

            <!-- Start Colume 3 -->
            <div class="col-md-4 panel-fade fade" id="shipping-agents">
                
                <div class="panel panel-primary min-height-290  ">
                    <div class="panel-heading">เลือกวิธีการส่ง</div>
                    <div class="panel-body" id="result-panel">
                        <h4 class="text-center">กรุณากรอกข้อมูลผู้รับและน้ำหนักพัสดุก่อน</h4> 
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
        <!-- End Colume 3 -->

        <div class="text-center btn-create" id="submit-form" style="display: none;"><button type="submit" name="submit" class="btn btn-lg btn-primary minus-margin">สร้างพัสดุ</button></div>
        <div class="clearfix"></div>
    </form>
</div>

<!-- Start Address -->
<script type="text/javascript">
  var agent = '<?=$agent?>';
  $('#country').change(function(){
    //var countryID = $("#country").val();    
    var countryArr = $("#country").val(); 
    var data = countryArr.split(",");
    var countryID = data[0];
    var countryID3 = data[1];
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
            //$("#city").append('<option>-- Choose City --</option>');
            //$("#postcode").append('<option>-- Choose Postcode --</option>');
            $("#state").append('<option value="">-- Choose State --</option>');
            if(res.states != ''){
              $.each(res.states,function(states,key){
                $("#state").append('<option value="'+key.STATE_CODE+'">'+key.STATE_NAME+'</option>');

              });
            }else{
              $("#state").append('<option value="">no state</option>');
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
    //var countryID = $("#country").val();  
    var countryArr = $("#country").val(); 
    var data = countryArr.split(",");
    var countryID = data[0];
    var countryID3 = data[1]; 
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
              //$("#postcode").empty();
              $("#city").append('<option value="">-- Choose City --</option>');
              $("#postcode").empty();
              if(res.cities != ''){
                $.each(res.cities,function(cities,key){
                  $("#city").append('<option value="'+key.CITY_NAME_ASCII+'">'+key.CITY_NAME_ASCII+'</option>');
                });
              }else{
                $("#city").append('<option value="">no city</option>');
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

  $('#city').on('change',function(){
    //var countryID = $("#country").val();  
    var countryArr = $("#country").val(); 
    var data = countryArr.split(",");
    var countryID = data[0];
    var countryID3 = data[1]; 
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

              $(".postcode-input").hide();
              $(".postcode-select").show();
              $("#postcode").empty();

              if(res.postcodes != '' && res.postcodes != false){
                $("#postcode_ajax").append('<select id="postcode" name="postcode" class="form-control" value="">No Data</select>');
                $(".postcode-select").append('<option value="">-- Choose Postcode --</option>');
                $.each(res.postcodes,function(postcode,key){
                  $(".postcode-select").append('<option value="'+key.POST_CODE+'">'+key.POST_CODE+'</option>');
                });
              }else{
                $(".postcode-input").show();
                $(".postcode-select").hide();
                //$("#postcode_ajax").append('<input id="postcode" type="text" name="postcode" class="form-control" value=""/>');
              }
            }else{
               //$("#postcode").empty();
               //$("#postcode").append('<option value="">No Data</option>');
               $(".postcode-input").show();
               $(".postcode-select").hide();
            }
           }
        });
    }else{
        $("#postcode").empty();
    }
  });



  /*if(countryID){
        $.ajax({
           type:"GET",
           //url:"{{url('api/get-state-list')}}?country_id="+countryID,
           url:"{{url('fastbox/api/get-state-list')}}?country_id="+countryID,
           success:function(res){               
            if(res){
                $("#state").empty();
                $("#state").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#state").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#state").empty();
            }
           }
        });
    }else{
        $("#state").empty();
        $("#city").empty();
    }      
   });*/

  /*$('#state').on('change',function(){
    var stateID = $(this).val();    
    if(stateID){
        $.ajax({
           type:"GET",
           //url:"{{url('api/get-city-list')}}?state_id="+stateID,
           url:"{{url('fastbox/api/get-city-list')}}?state_id="+stateID,
           success:function(res){               
            if(res){
                $("#city").empty();
                $.each(res,function(key,value){
                    $("#city").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#city").empty();
            }
           }
        });
    }else{
        $("#city").empty();
    }
        
   });*/
</script>
<!-- End Address -->


<script>
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
        $("#submit-form").show();
        //$("#shipment_form").submit();
    }

    function calculateRate(scroll){
        var defaultAgent = "";

        if($("#weight").val() < 0){
            $("#weight").val(0);
        }else if($("#weight").val() > 299999){
            $("#weight").val(299999);
        } 

        if($("#weight").val() >= 20000){
            //$("#weight_text").show();
        }else{
            //$("#weight_text").hide();
        }
        //var w = $("#width").text();alert(w);
        var countryArr = $("#country").val(); 
        var data = countryArr.split(",");
        var countryID = data[0];
        var countryID3 = data[1];
        
        var volWeight = $("#width").val()*$("#height").val()*$("#length").val()/5;
        $("#volumnWeight").text(volWeight.toFixed(0));
        $("#volumnWeightPost").val(volWeight.toFixed(0));

        //alert(volWeight);
        $.post('{{url ('shipment/get_rate')}}',
        {
            _token: $("[name=_token]").val(),
            weight: $("#weight").val(),
            width: $("#width").val() ,
            height: $("#height").val() ,
            length: $("#length").val() ,
            type: $("input[name=type]:checked").val() ,
            country: countryID3,
            //country: $("#country").val() ,
        },function(data){

            console.log(data);
            
            $("#result-panel").empty();
            $("#shipping-agents").removeClass("fade");

            if(data !== false){
                var dataArray = $.map(data, function(value, index) {
                    return [value];
                });
                var keyArray = $.map(data, function(value, index) {
                    return [index];
                });   
            }

            if(data !== false && dataArray.length > 0){
                
                var minRate = 9999999;
                var content = "";

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

                       content += '<fieldset>';
                       content += '<label class="label-rate" for="agent-' + _agent + '" onclick="selectAgent(\''+_agent+'\',\''+_value+'\',\''+_deliveryTime+'\')" class="clearfix">';

                       //content += '<label class="label-rate" for="agent-' + _agent + '" onclick="selectAgent(\''+_agent+'\',\''+_value+'\',\''+_deliveryTime+'\')" class="clearfix">';

                       content += '<div class="col-xs-4 col-md-3"><img src="/images/agent/' + _agent.replace(/ /g,"-") + '.gif" style="border-radius: 5px 0 0 5px; position: absolute; left:8; max-width: 108%;  height: 78px;"/></div>';
                       content += '<div class="col-xs-4 col-md-6 width-30 text-left">';
                       content += '<h3>' +  _displayAgent + '</h3>';
                       content += '<h4 class="orange"><span class="hidden-xs">' + _type + ' : </span>' + _deliveryTime + '</h4>';
                       content += '</div>';
                       if(_value != _valueMax){
                           content += '<div class="col-xs-4 col-md-3 width-36 text-right">';
                           content += '<h4 class="retail-price">' + parseInt(_valueMax).format() + ' บาท</h4>';
                           content += '<div><span class="price">' + parseInt(_value).format() + '</span> บาท</div>';
                           content += '</div>';
                       }else{
                           content += '<div class="col-xs-4 col-md-3 width-36 text-right">';
                           content += '<div><span class="price">' + parseInt(_value).format() + '</span> บาท</div>';
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
                content = "ไม่พบวิธีการส่งที่เหมาะสมสำหรับการส่งพัสดุคุณ";
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
            
        },"json");
    }
    Number.prototype.format = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
    };
</script>

<!-- Colum 2 -->
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
                            // "<select name='category["+table_size+"]' class='category form-control category-not-other' onchange='checkOtherType(this.value," + table_size + ");'>"+
                            // "<option value='' >เลือกประเภท</option>"+
                            // <?php //foreach ($declareType as $deId=>$cate): ?>
                            // "<option value='<?php //echo $deId; ?>' ><?php //echo $cate; ?></option>"+
                            // <?php //endforeach; ?>
                            // "</select>"+
                            // "<input type='text' name='other["+table_size+"]' class='form-control other pull-right' style='display: none;' placeholder='โปรดระบุ' />" +
                            "<input id='category-"+table_size+"' type='text' name='category["+table_size+"]' class='category form-control required' required />"+                                
                        "</td>"+
                        "<td><input type='number' min='1' name='amount["+table_size+"]' class='form-control required' required /></td>"+
                        "<td><input type='number' min='1' name='value["+table_size+"]' class='form-control required declare-value' required /></td>"+
                        "<td><span class='glyphicon glyphicon-minus-sign text-danger' onclick='rmv("+table_size+")'></span></td>"+
                    "</tr>";
        $( "#product_table" ).append(row);
        //$("#category-"+table_size ).attr("pattern","<?php echo $validateEnglish; ?>");
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
    $("input[name=sender_firstname]").keyup(validateRequired);
    $("input[name=sender_lastname]").keyup(validateRequired);

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
</script>

<script>
    function checkAddress(){
        if ($("select[name='state']").val() == '') {
            alert(111);
            //$("#shipment_form select[name='state']").val();
        }

        if ($("select[name='city']").val() == '') {
            alert(222);
            //$("#shipment_form select[name='city']").val();
        }
    }

    $( "shipment_form" ).submit(function( event ) {
      if ( $( "#state" ).val() == "" ) {
        alert(111);
        return;
      }
     
      if ( $( "#city" ).val() == "" ) {
        alert(222);
      }
    });
</script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script type="text/javascript">
  /*$(document).ready(function() { 

    $('#shipment_form').validate({ // initialize the plugin
        rules: {
            "country": "required",
            "states": "required",
            "city": "required",
            //"skuname[]": "required",
            //"qty[]": "required",
            //"delivery_type" : "required"
        },
        messages: {
            "country": "Please choose country",
            "states": "Please choose states",
            "city": "Please choose city",
            //"skuname[]": "Please enter you SKU name",
            //"qty[]": "Please enter you quantity",
            //"delivery_type": "Please choose shipping method",
        }
    });
});*/
</script>

@endsection