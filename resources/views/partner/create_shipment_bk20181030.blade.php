@extends('layout')
@section('content')
<?php 
    $validateEnglish = "^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$";
    $validateDeclare = "^[a-zA-Z0-9 /+&]+$";
?>
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-7 pad8"><h2>สร้างพัสดุ - กรอกข้อมูลพัสดุ</h2></div>
        <div class="col-md-5 text-right">
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
        </div>
	</div>

    <form id="partnerShipment_form" class="form-horizontal" method="post" action="{{url ('partner/create_shipment')}}">
        <input type="hidden" name="agent" id="shipment_agent" />
        <input type="hidden" name="price" id="shipment_price" />
        <input type="hidden" name="delivery_time" id="shipment_time" />
        <div class="row">
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
                            <input name="firstname" type="text" placeholder="Firstname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['firstname']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-12">
                            <input name="lastname" type="text" placeholder="Lastname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['lastname']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="lastname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="lastname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-12">
                            <input name="phonenumber" type="text" placeholder="Phone Number" class="form-control required input-count" maxlength="50" value="<?php echo $default['receiver']['phonenumber']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="phonenumber-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="phonenumber-count">0</span>/50</div> 
                        </div>
                        <div class="form-group col-md-12">
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
                        <div class="form-group col-md-12">
                            <select class="form-control" id="country" name="country" onchange="calculateRate(true)">
                                <option value="">- กรุณาเลือกประเทศปลายทาง -</option>
                                <?php
                                    //$countries = $countryObj;
                                    foreach($countries as $key => $value) {
                                ?>
                                <option value="<?= $value->CNTRY_CODE.','.$value->CODE3 ?>" title="<?= htmlspecialchars($value->CNTRY_NAME) ?>">
                                    <?= htmlspecialchars($value->CNTRY_NAME) ?>
                                </option>
                                <?php
                                    }
                                ?>
                            </select>
                            <!--<div class="col-md-12 no-padding">&nbsp;</div>-->
                        </div> 
                        <div class="form-group col-md-12">
                            <select name="state" id="state" class="form-control"></select>
                        </div>
                        <div class="form-group col-md-12">
                            <select name="city" id="city" class="form-control"></select>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <select name="postcode" id="postcode" class="form-control"></select>
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
                            <input name="Sender_Firstname" type="text" placeholder="Firstname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['firstname']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="firstname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="firstname-count">0</span>/80</div> 
                        </div>
                        <div class="form-group col-md-12">
                            <input name="Sender_Lastname" type="text" placeholder="Lastname" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['lastname']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="lastname-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="lastname-count">0</span>/80</div> 
                        </div>
                        <!--<div class="form-group col-md-6">
                            <input name="Sender_PhoneNumber" type="text" placeholder="Phone Number" class="form-control required input-count" maxlength="50" value="<?php echo $default['receiver']['phonenumber']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="phonenumber-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="phonenumber-count">0</span>/50</div> 
                        </div>
                        <div class="form-group col-md-6">
                            <input name="Sender_Email" type="text" placeholder="Email" title="Email" class="form-control required input-count" maxlength="50" value="<?php echo $default['receiver']['email']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="email-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="email-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="Sender_Company" type="text" placeholder="Company Name" class="form-control input-count"  pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="100" value="<?php echo $default['receiver']['company']; ?>"/>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="company-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="company-count">0</span>/100</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="Sender_AddressLine1" placeholder="Address" placeholder="Street Address" type="text" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['address1']; ?>"/>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="address1-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address1-count">0</span>/80</div>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="Sender_AddressLine2" placeholder="Address (cont.)" placeholder="Address (continue)" type="text" class="form-control input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="80" value="<?php echo $default['receiver']['address2']; ?>"/>
                            <div class="red tiny text-left col-md-10 no-padding"><span id="address2-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="address2-count">0</span>/80</div>
                        </div>
                         
                        <div class="form-group col-md-6">
                            <input name="Sender_State" type="text" placeholder="State" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="50" value="<?php echo $default['receiver']['state']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="state-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="state-count">0</span>/50</div>
                        </div>
                        <div class="form-group col-md-6">
                            <input name="Sender_City" type="text" placeholder="City" class="form-control required input-count" pattern="<?php echo $validateEnglish; ?>" oninvalid="this.setCustomValidity('กรุณากรอกข้อมูลเป็นภาษาอังกฤษ')" oninput="setCustomValidity('')" maxlength="50" value="<?php echo $default['receiver']['city']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="city-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="city-count">0</span>/50</div>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <input name="Sender_Postcode" type="text" placeholder="Postcode" class="form-control required input-count" maxlength="10" value="<?php echo $default['receiver']['postcode']; ?>" />
                            <div class="red tiny text-left col-md-10 no-padding"><span id="postcode-error" class="error-msg"></span></div> 
                            <div class="gray tiny text-right col-md-2 no-padding"><span id="postcode-count">0</span>/10</div>
                        </div>

                        <div class="form-group col-md-6">
                            <select class="form-control" id="country" name="country" >
                                <option value="">- กรุณาเลือกประเทศต้นทาง -</option>
                                <?php
                                    foreach($country as $code=>$name){
                                        echo "<option value='".$code."'>".$name."</option>";
                                    }
                                ?>
                            </select>
                            <div class="col-md-12 no-padding">&nbsp;</div>
                        </div>-->      
                    </div>
                </div>
                <!-- End 2 -->

            </div>
            <!-- End Colume   1   -->

            <!-- Start Colume 2 -->
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">กรอกข้อมูลพัสดุ</div>
                    <div class="panel-body">
                        <div class="row">
                            <label for="weight" class="col-md-4 control-label">น้ำหนัก (กรัม)</label>
                            <div class="col-md-8">
                            <input type="number" class="form-control required" id="weight" name="weight" min="1" max="299999" required onkeyup="calculateRate(false)" />
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
                            <div class="col-md-2">
                            <input type="number" id="width" name="width" class="form-control required" placeholder="กว้าง" onkeyup="calculateRate(false)" min="0" />
                            </div>
                            <div class="col-md-2">
                            <input type="number" id="length" name="length" class="form-control required" placeholder="ยาว" onkeyup="calculateRate(false)"  min="0" />
                            </div>
                            <div class="col-md-2">
                            <input type="number" id="height" name="height" class="form-control required" placeholder="สูง" onkeyup="calculateRate(false)"  min="0" />
                            </div>
                            <div class="clearfix"></div>
                            
                            <label class="col-md- control-label">น้ำหนักปริมาตร (กรัม)</label>
                            <div class="col-md-8"><span id="volumnWeight" style="line-height: 40px;">0</span> กรัม</div>
                        </div>
                        <!--<div class="row">
                        <label for="inputtext" class="col-md-4 control-label">ประเทศปลายทาง</label>
                            <div class="col-md-12">
                                <select class="form-control" id="country" name="country"  onchange="calculateRate(true)">
                                <option value="">- กรุณาเลือกประเทศปลายทาง -</option>
                                <?php
                                    foreach($country as $code=>$name){
                                        echo "<option value='".$code."'>".$name."</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div>-->
                        <div id="weight_text" class="text-center small" style="display: none;">กรณีการส่งพัสดุ 20 กิโลกรัมขึ้นไป สามารถสอบถามเจ้าหน้าที่เพิ่มเติมเพื่อขอราคาพิเศษ</div>
                    
                    {{ csrf_field() }}
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">กรอกรายละเอียดพัสดุ</div>
                    <div class="panel-body">

                        <div class="col-md-4 col-xs-4 text-center no-padding"> 
                            <!--<img src="/images/agent/<?php echo $default['agent'];?>.gif" style="max-width: 100px;"/>-->
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
                            <div class="col-md-8 pull-right text-right"><a href="javascript:add();"><i class="fa fa-plus-circle green"></i> เพิ่มประเภทพัสดุใหม่</a></div>
                            
                        </div>

                        <div class="row detailpro" style="padding-top: 15px; padding-bottom: 5px; ">
                            <div class="col-md-8 pull-left hidden-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> สินค้าที่ไม่รับขนส่งไปต่างประเทศ</a></div>
                        </div>
                        
                            <label class="col-md-4 control-label label-top">ผู้รับผิดชอบภาษี : </label>
                            <div class="col-md-8">
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
                        <div class="clearfix"></div><br /> 
                        
                        <div class="col-md-6 pull-right visible-xs"><a href="http://fastship.co/helps/prohibited-items/" target="_blank"><i class="fa fa-info-circle red"></i> สินค้าที่ไม่รับขนส่งไปต่างประเทศ</a></div>
                    </div>
                               
                </div>
            </div>
            <!-- End Colume   2 -->
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
            <!-- End Colume 3 -->
    </form>
  
</div>

<!-- Start Address -->
<script type="text/javascript">
  var agent = '';
  $('#country').change(function(){
    var countryArr = $(this).val(); 
    var data = countryArr.split(",");
    var countryID = data[0];
    var countryID3 = data[1];

    if(countryID){
      $.ajax({
        type:"GET",
        //url:"{{url('api/get-state-list')}}?country_id="+countryID,
        url:"{{url('api/get-state-list')}}?country_id="+countryID,
        success:function(res){               
        if(res){//alert(res.states);
            $("#state").empty();
            $("#state").append('<option>Select</option>');
            if(res.states != ''){
              $.each(res.states,function(states,key){
                $("#state").append('<option value="'+key.COUNTRY_CODE+','+key.STATE_CODE+','+key.STATE_NAME+'">'+key.STATE_NAME+'</option>');

              });
            }else{
              $("#state").append('<option value="">No Data</option>');
            }
        }else{
           $("#state").empty();
        }
        }
      });
    }else{
      $("#state").empty();
      $("#city").empty();
    }      
  });
  
  $('#state').on('change',function(){
    //var stateID = $(this).val();    
    var stateArr = $(this).val();    
    var data = stateArr.split(",");
    var countryCode = data[0];
    var stateID = data[1]
    //alert(countryCode);alert(stateID);
    if(stateID){
        $.ajax({
           type:"GET",
           //url:"{{url('api/get-city-list')}}?state_id="+stateID,
           //url:"{{url('api/get-city-list')}}?state_id="+stateID,
           url:"{{url('api/get-city-list')}}?countryCode="+countryCode+"&state_id="+stateID,
           success:function(res){           
            if(res){
              $("#city").empty();

              if(res.cities != ''){
                $.each(res.cities,function(cities,key){
                  //$("#city").append('<option value="'+key+'">'+value+'</option>');
                  //$("#city").append('<option value="'+key.CITY_NAME+'">'+key.CITY_NAME+'</option>');
                  $("#city").append('<option value="'+agent+','+key.COUNTRY_CODE+','+key.STATE_CODE+','+key.CITY_NAME+'">'+key.CITY_NAME+'</option>');
                });
              }else{
                $("#city").append('<option value="">No Data</option>');
              }
            }else{
               $("#city").empty();
            }
           }
        });
    }else{
        $("#city").empty();
    }
  });

  $('#city').on('change',function(){
    //var stateID = $(this).val();    
    
    var cityArr = $(this).val();    
    var data = cityArr.split(",");
    var agent = data[0];
    var countryCode = data[1];
    var stateCode = data[2]
    var cityName = data[3]
    //alert(countryCode);alert(stateCode);die();
    if(agent){
        $.ajax({
           type:"GET",
           //url:"{{url('api/get-city-list')}}?state_id="+stateID,
           //url:"{{url('api/get-city-list')}}?state_id="+stateID,
           url:"{{url('api/postcode')}}?agent="+agent+"&countryCode="+countryCode+"&stateCode="+stateCode+"&cityName="+cityName,
           success:function(res){       
            if(res){
              $("#postcode").empty();

              if(res.postcode != ''){
                $.each(res.postcode,function(postcode,key){
                  //$("#city").append('<option value="'+key+'">'+value+'</option>');
                  $("#postcode").append('<option value="'+key.POST_CODE+'">'+key.POST_CODE+'</option>');
                });
              }else{
                $("#postcode").append('<option value="">No Data</option>');
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
    	$("#shipment_form").submit();
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

        var countryArr = $("#country").val() 
        var data = countryArr.split(",");
        var countryID = data[0];
        var countryID3 = data[1];
        //alert(countryID3);die();
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