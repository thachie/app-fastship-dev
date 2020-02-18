@extends('partner/layouts/layout_partner_front')
@section('content')
<?php 
    $validateEnglish = "^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$";
    $validateDeclare = "^[a-zA-Z0-9 /+&]+$";
?>
<style>
    h1 {
        text-align: center;
        text-transform: uppercase;
        color: #4CAF50;
    }

    .display-text {
        font-weight: bold;
    }

    p {
        /*text-indent: 50px;*/
        font-weight: bold;
    }

</style>
<!--https://www.kodementor.com/laravel-5-qr-code-generator/-->
<!--https://stackoverflow.com/questions/37825742/qrcode-in-laravel-->
<!--https://www.simplesoftware.io/docs/simple-qrcode-->


<div class="conter-wrapper">
    <div class="row">
        <div class="col-md-7 pad8"><h2>รายละเอียด - ข้อมูลพัสดุ</h2></div>
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

    <!-- Detail -->
    <div class="row" style="display: show;">
        <!-- Start Colume 1 -->

        <div class="col-md-6">
            <!-- Start 1 -->
            <div class="panel panel-primary">
                <div class="panel-heading">ข้อมูลผู้รับ</div>
                <div class="panel-body ship-detail">
                    <div class=" well" style="margin-bottom:0px;">
                        <div class="col-md-3 col-xs-3 text-right">ชื่อ-นามสกุล : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_FIRSTNAME .' '.$res->RECEIVER_LASTNAME; ?></div>
                        <div class="clearfix"></div>
                        <div class="col-md-3 col-xs-3 text-right">เบอร์โทรศัพท์ : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_PHONE;?></div>
                        <div class="clearfix"></div>
                        <div class="col-md-3 col-xs-3 text-right">อีเมล์ : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_EMAIL;?></div>
                        <div class="clearfix"></div>
                        <div class="col-md-3 col-xs-3 text-right">ที่อยู่ : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_ADDRESS_1;?><br /><?=$res->RECEIVER_ADDRESS_2;?></div>
                        <div class="clearfix"></div>
                        <div class="col-md-3 col-xs-3 text-right">เขต : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_CITY;?></div>
                        <div class="clearfix"></div>
                        <div class="col-md-3 col-xs-3 text-right">จังหวัด : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_STATE;?></div>
                        <div class="clearfix"></div> 
                        <div class="col-md-3 col-xs-3 text-right">รหัสไปรษณีย์ : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_POSTCODE;?></div>
                        <div class="clearfix"></div>  
                        <div class="col-md-3 col-xs-3 text-right">ประเทศ : </div>
                        <div class="col-md-7 col-xs-7"><?=$res->RECEIVER_COUNTRY;?></div>
                        <div class="clearfix"></div>   
                        <?php //if($res->REMARK != ""): ?>
                            <div class="col-md-3 col-xs-3 text-right">หมายเหตุ : </div>
                            <div class="col-md-7 col-xs-7"><?=$res->REMARK;?></div>
                            <div class="clearfix"></div>
                        <?php //endif; ?>
                        <?php //if($res->REFERENCE != ""): ?>
                            <!--<div class="col-md-3 col-xs-3 text-right">หมายเลข eBay/Amazon Order : </div>-->
                            <div class="col-md-3 col-xs-3 text-right">หมายเลข : </div>
                            <div class="col-md-7 col-xs-7"><?=$res->REFERENCE;?></div>
                            <div class="clearfix"></div>
                        <?php //endif; ?>
                    </div>
                    <br />
                </div>
            </div>
            <!-- End 1 -->
            <!-- Start 2 -->
            <div class="panel panel-primary">
                <div class="panel-heading">ข้อมูลผู้ส่ง</div>
                <div class="panel-body row-no-padding">
                    <div class=" well" style="margin-bottom:0px;">
                        <div class="col-md-3 col-xs-3 text-right">ชื่อ-นามสกุล : </div>
                        <div class="col-md-7 col-xs-7">
                            <?=$res->SENDER_FIRSTNAME .' '.$res->SENDER_LASTNAME;?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- End 2 -->
        </div>

        
        <!-- End Colume   1 -->

        <!-- Start Colume 2 -->
        <div class="col-md-6">
            <!-- Start Tracking -->
            <div class="panel panel-primary" style="padding-bottom: 0px;">
                <div class="panel-heading">Tracking</div>
                <div class="panel-body ship-detail">
                    <div class=" well" style="margin-bottom:0px;">
                        <div class="col-md-5 col-xs-5 text-right" style="font-weight: bold; font-size: 20px;">TRACKING : </div>
                        <div class="col-md-7 col-xs-7" style="font-weight: bold; font-size: 20px;"><?=$res->TRACKING_NUMBER;?></div>

                        <div class="col-md-5 col-xs-5 text-right" style="font-weight: bold; font-size: 20px;">STATUS : </div>
                        <div class="col-md-7 col-xs-7" style="font-weight: bold; font-size: 20px;">
                            <?php if ($res->SHIPMENT_STATUS== 'SUCCESS') {
                                echo 'สร้างพัสดุแล้ว';
                            }else{ 
                                echo 'สร้างพัสดุไม่สำเร็จ';
                            }?>
                        </div>

                        <div class="col-md-12 col-xs-12 text-center" style="padding-top: 33px;">
                            <?php
                                echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
                            ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <br />
                </div>
                <!--<div class="panel-body row-no-padding">
                    <p style="font-weight: bold; font-size: 20px;">TRACKING : <?=$res->TRACKING_NUMBER;?></p>
                    <p style="font-weight: bold; font-size: 20px;">STATUS : 
                        <?php if ($res->SHIPMENT_STATUS== 'SUCCESS') {
                            echo 'สร้างพัสดุแล้ว';
                        }else{ 
                            echo 'สร้างพัสดุไม่สำเร็จ';
                        }?>
                    </p>
                        <?php
                        echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
                        ?>
                </div>-->
            </div>
            <!-- End Tracking -->

            <!-- Start QR Code -->
            <!--<div class="panel panel-primary">
                <div class="panel-body row-no-padding">
                    <div class="form-group col-md-6">
                        <?php
                        echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
                        ?>
                    </div>
                </div>
            </div>-->
            <!-- End QR Code -->

            <!-- Start Shipment Detail -->
            <div class="panel panel-primary">
                <div class="panel-heading">รายละเอียดพัสดุ</div>
                <div class="panel-body">
                    <div class="col-md-4 col-xs-4 text-center no-padding"> 
                        <img src="/images/agent/<?php echo $res->AGENT;?>.gif" style="max-width: 100px;"/>
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
                                <td><span class="sumresult"><?php echo number_format($res->WEIGHT,0);?></span></td>
                                <td class="hidden-xs">
                                    <?php if($res->WIDTH != "" && $res->LENGTH != "" && $res->HEIGHT != ""): ?>
                                    <span class="sumresult"><?php echo $res->WIDTH."×".$res->LENGTH."×".$res->HEIGHT; ?></span>
                                    <?php else: ?>
                                    <span class="sumresult">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="sumresult"><?php echo number_format($res->PRICE,0);?></span></td>
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
                        if(!empty($category)){
                        foreach ( $category as $key => $cat){
                        ?>
                        <tr id='row<?=$key; ?>'>
                            <td>
                                <p class="text-center col-md-10 no-padding"><?=$cat->CATEGORY;?></p>
                                <!--<input type="text" name="category[<?=$key ?>]" class="category form-control"  pattern="<?=$cat->CATEGORY; ?>" value="<?=$cat->CATEGORY;?>" readonly />-->
                            </td>      
                            <td>
                                <p class="text-right col-md-10 no-padding"><?=number_format($cat->QTY);?></p>
                                <!--<input type="number" min="1" name="amount[<?=$key ?>]" class="form-control" value="<?=$cat->QTY;?>" readonly />-->
                            </td>
                            <td>
                                <p class="text-right col-md-10 no-padding"><?=number_format($cat->VALUE);?></p>
                                <!--<input type="number" min="1" name="value[<?=$key ?>]" class="form-control" value="<?=$cat->VALUE;?>" readonly />-->
                            </td>
                            
                        </tr>
                        <?php
                        }
                        }else{
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
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Shipment Detail -->
            <!-- Start Tracking -->
            <!--<div class="panel panel-primary">
                <div class="panel-heading">Tracking</div>
                <div class="panel-body row-no-padding">
                    <p style="font-weight: bold; font-size: 20px;"><?php echo $res->TRACKING_NUMBER;?></p>
                </div>
            </div>-->
            <!-- End Tracking -->

            <!-- Start QR Code -->
            <!--<div class="panel panel-primary">
                <div class="panel-body row-no-padding">
                    <div class="form-group col-md-6">
                        <img src="{{ url('images/qr_code.png') }}" alt="Smiley face" height="200" width="200"> 
                        <?php
                        echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
                        ?>
                    </div>
                </div>
            </div>-->
            <!-- End QR Code -->
        </div>
        <!-- End Colume   2   -->
    </div>
    <!--<div class="row">
        <div class="col-md-12">
        <div class="col-md-6" style="padding-bottom: 0px;">
            <div class="text-right btn-create" id="submit-form" >
                <a href="{{ url ('partner/create-shipment') }}">
                    <button type="submit" name="submit" class="btn btn-lg btn-primary minus-margin">กลับหน้าสร้างพัสดุ</button>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-6" style="padding-bottom: 0px;">
            <div class="text-left" id="print" >
                <a href="{{ url ('partner/shipment_detail_print/'.$res->TRACKING_NUMBER) }}" target="_blank">
                    <button type="button" name="print" class="btn btn-lg btn-primary minus-margin"><span class="glyphicon glyphicon-print"></span> พิมพ์ใบพัสดุ</button>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
        </div>
    </div>-->

    <div class="container" style="padding-top: 20px; text-align: center;">
        <a href="{{url ('partner/create-shipment')}}"><button type="button" class="btn btn-lg btn-primary minus-margin">กลับหน้าสร้างพัสดุ</button></a>

        <a href="{{url ('partner/shipment_detail_print/'.$customerId.'/'.$res->TRACKING_NUMBER) }}" target="_blank"><button type="button" class="btn btn-lg btn-primary minus-margin"><span class="glyphicon glyphicon-print"></span> พิมพ์ใบพัสดุ</button></a>
    </div>
</div>

<!-- Start Address -->
<script type="text/javascript">
  var agent = '';
  $('#country').change(function(){
    var countryID = $("#country").val();    
    //alert(countryID);
    if(countryID){

      //$("#state_ajax").hide();
      //$("#state_ajax_loading").show();
      $.ajax({
        type:"post",
        data:{
            "_token" : $("[name=_token]").val(),
            "country_id" : countryID
        },
        url:"{{url('api/address/states')}}",
        success:function(res){              
        if(res){
            //$("#state_ajax_loading").hide();
            //$("#state_ajax").show();
            $("#state").empty();
            $("#city").empty();
            $("#postcode").empty();
            $("#state").append('<option>Select State</option>');
            if(res.states != ''){
                $.each(res.states,function(states,key){
                $("#state").append('<option value="'+key.STATE_CODE+'">'+key.STATE_NAME+'</option>');

              });
            }else{
              $("#state").append('<option value=".">no state</option>');
            }
        }else{
           $("#state").empty();
           //$("#city").append('<option value="">-- Choose City --</option>');
        }
        }
      });
    }else{
      $("#state").empty();
      $("#city").empty();
      //$("#city").append('<option value="">-- Choose City --</option>');
      $("#postcode").empty();
    }      
  });
  
  $('#state').on('change',function(){
    var countryID = $("#country").val();   
    var stateID = $("#state").val();    
    if(stateID){
        //$("#city_ajax").hide();
        //$("#city_ajax_loading").show();
        $.ajax({
           type:"post",
           url:"{{url('api/address/cities')}}",
           data:{
            "_token" : $("[name=_token]").val(),
            "country_id" : countryID,
            "state_id" : stateID
           },
           success:function(res){           
            if(res){
                //$("#city_ajax_loading").hide();
                //$("#city_ajax").show();
                //$("#city").empty();
                $("#postcode").empty();
                $("#city").append('<option>Select City</option>');
                if(res.cities != ''){
                $.each(res.cities,function(cities,key){
                  $("#city").append('<option value="'+key.CITY_NAME_ASCII+'">'+key.CITY_NAME_ASCII+'</option>');
                });
                }else{
                $("#city").append('<option value=".">no city</option>');
                }
            }else{
               $("#city").empty();
               //$("#city").append('<option value="">-- Choose City --</option>');
            }
           }
        });
    }else{
        $("#city").empty();
        $("#postcode").empty();
    }
  });

  $('#city').on('change',function(){
    var countryID = $("#country").val();   
    var stateID = $("#state").val();   
    var cityName = $("#city").val();   

    if(cityName){
        //$("#postcode_ajax").hide();
        //$("#postcode_ajax_loading").show();
        $.ajax({
           type:"post",
           url:"{{url('api/address/postcodes')}}",
           data:{
                "_token" : $("[name=_token]").val(),
                "country_id" : countryID,
                "city_name" : cityName
              },
           success:function(res){  
            if(res){
                //$("#postcode_ajax_loading").hide();
                //$("#postcode_ajax").show();
                //$("#postcode_ajax").empty();
                $("#postcode").empty();

                if(res.postcodes != '' && res.postcodes != false){
                $("#postcode_ajax").append('<select id="postcode" name="postcode" class="form-control"></select>');
                $("#postcode").append('<option>Select Postcode</option>');
                $.each(res.postcodes,function(postcode,key){
                  $("#postcode").append('<option value="'+key.POST_CODE+'">'+key.POST_CODE+'</option>');
                });
                }else{
                $("#postcode_ajax").append('<input id="postcode" type="text" name="postcode" class="form-control" />');
                }
            }else{
               $("#postcode").empty();
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
    <?php if($res->RECEIVER_COUNTRY_CODE == "USA"): ?>
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
</script>

@endsection