@extends('layout')
@section('content')
    <div class="conter-wrapper">
        
    <?php if(sizeof($shipment_data) > 0){ ?>
        <div class="row">
            <div class="col-md-7 pad8"><h2>พัสดุรอส่ง</h2></div>
            <div class="col-md-5 text-right">
                <div class="bs-wizard dot-step" style="border-bottom:0;">
                    <div class="col-xs-4 bs-wizard-step complete">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                        <p class="text-center">กรอกข้อมูลพัสดุ</p>
                    </div> 
                    <div class="col-xs-4 bs-wizard-step complete">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">2</span></a>
                        <p class="text-center">กรอกข้อมูลผู้รับ</p>
                    </div>
                    <div class="col-xs-4 bs-wizard-step active">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">3</span></a>
                        <p class="text-center">ส่งพัสดุ</p>
                	</div>       
            	</div>
            </div>
        </div>	    
        
        <form id="pickup_form" class="form-horizontal" method="post" action="{{url ('pickup/create')}}">
	    {{ csrf_field() }}
	    
        <div class="row">
    <div class="col-md-12">
            <div class="panel panel-primary hidden-xs">
                <div class="panel-heading">รายการพัสดุ</div>
                <div class="panel-body">
                    <table class="table table-stripe table-hover">
                        <thead>
                        <tr>
                            <td>หมายเลขพัสดุ</td>
                            <td>ผู้รับ</td>
                            <td>ประเทศปลายทาง</td>
                            <td>เอเจนท์</td>
                            <td>ค่าขนส่ง (บาท)</td>
                            <td>ลบ</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if(sizeof($shipment_data) > 0): 
                        foreach($shipment_data as $data): 
                        ?>
                        <tr id="shipment_<?php echo $data['ID'];?>">
                            <td><a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a></td>
                            <td><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                            <td><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                            <td><img src="images/agent/<?php echo $data['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width:80px;" /></td>
                            <td><?php echo number_format($data['ShipmentDetail']['ShippingRate'],0);?></td>
                            <td><a href="javascript:cancelShipment(<?php echo $data['ID'];?>);"> <i class="fa fa-trash"></i></a></td>
                        <input type="hidden" name="shipment_id[]" value="<?php echo $data['ID']?>" />
                        </tr>
                        <?php 
                        endforeach;
                        endif;
                        ?> 
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

            <div class="col-md-12 visible-xs">
            <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">รายการพัสดุ</div>
                <div class="panel-body">
	            <?php 
	                if(sizeof($shipment_data) > 0): 
	                foreach($shipment_data as $data): 
	            ?>
	            <div class="col-xs-12 shipment-list">
	            	<div class="col-xs-12">
	                    <div class="pull-left"><h4><a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a></h4></div>
	                    <div class="pull-right"><h4 style="font-weight: 800; color: #f15a22;"><?php echo number_format($data['ShipmentDetail']['ShippingRate'],0);?> บาท</h4></div>
	                </div>
	                <div class="clearfix"></div>
	                
                    <div class="col-xs-5"><img src="images/agent/<?php echo $data['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width:100px;" /></div>
                    <div class="col-xs-7">
                            <h4 style="margin-bottom: 5px;"><?php echo $data['ReceiverDetail']['Firstname'];?> </h4>
                           	 ปลายทาง : <?php echo $countries[$data['ReceiverDetail']['Country']];?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="col-xs-12 text-right small"> 
                    	<!-- <a href="/shipment_detail/<?php echo $data['ID'];?>"><i class="fa fa-edit"></i> แก้ไข</a>  -->
                        <a style="text-decoration: none; font-size: 10px; font-weight: 600;" href="javascript:cancelShipment(<?php echo $data['ID'];?>);"><i class="fa fa-trash"></i> ยกเลิก </a>
                    </div>
                </div>
	            <?php 
	                endforeach;
	                endif;
	            ?>
	            </div>
	        </div>
            </div>

        </div>
        <div class="row"> 
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">เลือกวิธีการรับพัสดุ</div>
                        <div class="panel-body">
                            <div class="radio text-center pickup-type">     
                                <label><input type="radio" name="type" id="delivery" value="pickup" onclick="showDelivery();" checked>ให้ Fastship ไปรับ</label>
                                &nbsp;
                                <label><input type="radio" name="type" id="droppoint" value="drop" onclick="showDroppoint();">ส่งจุด DropPoint</label>
                            </div><br />

                            <div id="agentsdrop" style="display: none;">
                            <fieldset>
                                <label for="drop-1">
                                    <div class="col-4 col-xs-5">
                                        <img src="/images/fastship.png">
                                    </div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>FastShip</h3>
                                        <p class="slogan col-md-6 no-padding">ฟรี</p>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalFS">
                                            	<i class="fa fa-info-circle"></i> รายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input class="selector" type="radio" name="agent" id="drop-1" value="Drop_AtFastship" checked="checked"> 
                                <label for="drop-thaipost" style="display: none;">
                                    <div class="col-4 col-xs-5">
                                        <img src="/images/thaipost.png">
                                    </div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>ไปรษณีย์ไทย</h3>
                                        <p class="slogan col-md-6 no-padding">30 บาท</p>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalTP">
                                            	<i class="fa fa-info-circle"></i> รายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input class="selector" type="radio" name="agent" id="drop-thaipost" value="Drop_AtThaipost"> 
                                <label for="drop-2">
                                    <div class="col-4 col-xs-5">
                                        <img src="/images/skybox.png">
                                    </div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>SKYBOX</h3>
                                        <p class="slogan col-md-6 no-padding">ฟรี</p>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalSKB">
                                            	<i class="fa fa-info-circle"></i> รายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input class="selector" type="radio" name="agent" id="drop-2" value="Drop_AtSkybox"> 
                                <label for="drop-3">
                                    <div class="col-4 col-xs-5">
                                        <img src="/images/eedu.png">
                                    </div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>EEDU</h3>
                                        <p class="slogan col-md-6 no-padding">ฟรี</p>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalEEDU">
                                            	<i class="fa fa-info-circle"></i> รายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input class="selector" type="radio" name="agent" id="drop-3" value="Drop_AtEEDU">
                                <!-- <label for="drop-4">
                                    <div class="col-4 col-xs-5">
                                        <img src="/images/box24.png">
                                    </div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>Box24</h3>
                                    </div>
                                </label>
                                <input class="selector" type="radio" name="agent" id="drop-4" value="Drop_AtBox24"> -->
                                <label for="drop-5">
                                    <div class="col-4 col-xs-5">
                                        <img src="/images/chiangmai.png" />
                                    </div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>Chiangmai</h3>
                                        <p class="slogan col-md-6 no-padding">ฟรี</p>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalCM">
                                            	<i class="fa fa-info-circle"></i> รายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input class="selector" type="radio" name="agent" id="drop-5" value="Drop_AtChiangmai">
                            </fieldset>
                            </div>
                            
                            <div id="fspickup">
                                <div class="row">
                                    <label class="col-md-4 control-label">วันที่นัดรับพัสดุ</label>
                                    <div class="col-md-6">
                                        <div class='input-group pickup_date' id='pickupdate'>
                                            <input type='text' class="form-control required" name="pickupdate"/>
                                            <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-4 control-label">เวลาที่นัดรับพัสดุ</label>
                                    <div class="col-md-6">
                                        <select name="pickuptime" class="form-control">
                                            <option value="all">เวลาทำการ FastShip (9.00-17.00น.)</option>
                                            <option value="09:00">ช่วงสาย (9.00-11.00น.)</option>
                                            <option value="11:00">ช่วงกลางวัน (11.00-13.00น.)</option>
                                            <option value="13:00">ช่วงบ่าย (13.00-15.00น.)</option>
                                            <option value="15:00">ช่วงเย็น (15.00-17.00น.)</option>
                                        </select>
                                    </div>
                                </div>
                                <fieldset id="pickup">
                                    <?php if($customer_data['address1'] != "" && $customer_data['city'] != "" && $customer_data['state'] != "" && $customer_data['postcode'] != "" && $customer_data['country'] != "" &&
                                        $customer_data['firstname'] != "" && $customer_data['lastname'] != "" && $customer_data['phonenumber'] != "" && $customer_data['email'] != ""){ ?>

                                        <div class="row">
                                            <div class="col-12 text-left">
                                                <h3><?php echo $customer_data['firstname'] . " " . $customer_data['lastname']; ?></h3>
                                                <?php if($customer_data['company'] != ""): ?>
                                                <h4><?php echo $customer_data['company']; ?></h3>
                                                <?php endif; ?>
                                                <h5>Tel: <?php echo $customer_data['phonenumber'] . " Email: " . $customer_data['email']; ?></h5>
                                                <h5><?php echo $customer_data['address1'] . " " . $customer_data['address2'] . " " . $customer_data['city']. " " . $customer_data['state']. " " . $customer_data['postcode']. " " . ($customer_data['country']?$countries[$customer_data['country']]:""); ?></h5>
                                            </div>
                                        </div>
                                        
                                        <?php if(sizeof($shipment_data) > 0 && $customer_data['latitude'] != ""): ?>
                                        <div style="height: 240px;">
							            	<div id="map" height="240px" width="600px"></div>
							            </div>
							            <div class="clearfix"></div>
                                        <br />
                                        <?php endif; ?>
                                        
                                        <div class="col-md-12 text-center"><a href="/edit_customer?ret=create_pickup">แก้ไขข้อมูลที่อยู่</a></div>
                                        

                                    <?php }else{ ?>

                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                            	<p>คุณยังไม่มีที่อยู่สำหรับรับพัสดุ</p>
                                            	<a href="/edit_customer?ret=create_pickup">แก้ไขข้อมูลที่อยู่</a>   
                                            </div>
                                            
                                        </div>
                                    <?php } ?>
                                </fieldset>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">สรุปการจัดส่งพัสดุ</div>
                        <div class="panel-body sumpickbody">
                            <div class="row">
                                <div class="col-md-5 col-xs-6 text-right">จำนวนพัสดุ</div>
                                <div class="col-md-3 col-xs-4 text-right">
                                    <?php
                                        echo sizeof($shipment_data);
                                    ?>
                                </div>
                                <div class="col-md-4 col-xs-2">ชิ้น</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6 text-right">ค่าขนส่งรวม</div>
                                <div class="col-md-3 col-xs-4 text-right" id="total_rate">
                                    <?php 
                                        $total_rate = 0; 
                                        foreach($shipment_data as $data){
                                            $total = $total_rate + $data['ShipmentDetail']['ShippingRate'];
                                            $total_rate = $total;
                                        }
                                        echo number_format($total_rate,0);
                                    ?>
                                </div>
                                <div class="col-md-4 col-xs-2">บาท</div>
                            </div>
                            <div class="row" id="pickupcost" style="display: none;">
                                <div class="col-md-5 col-xs-6 text-right">ค่ารถรับพัสดุ</div>
                                <div class="col-md-3 col-xs-4 text-right" id="cost"></div>
                                <div class="col-md-4 col-xs-2">บาท</div>
                            </div>
                            <div class="row" id="pickupdiscount" style="display: none;">
                                <div class="col-md-5 col-xs-6 text-right">ส่วนลด</div>
                                <div class="col-md-3 col-xs-4 text-right" id="discount">-<?php echo $discount;?></div>
                                <div class="col-md-4 col-xs-2">บาท</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6 text-right">ยอดรวมทั้งหมด</div>
                                <div class="col-md-3 col-xs-4 text-right " id="totalpickup"></div>
                                <div class="col-md-4 col-xs-2">บาท</div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-5 text-right">คุณมีเครดิต</div>
                                <div class="col-md-3 col-xs-4 text-right">10,000</div><div class="col-md-2"></div>
                                <div class="col-md-3 col-xs-2">บาท</div>
                            </div> -->
                            <div class="radio text-center">
                                <div class="col-md-5 col-xs-12">
                                    <label>วิธีการชำระเงิน</label>
                                </div>
                                <div class="col-md-7 col-xs-12">
                                    <label><input type="radio" name="payment_method" id="Bank_Transfer" value="Bank_Transfer" checked>โอนเงินผ่านธนาคาร</label>
                                    <label><input type="radio" name="payment_method" id="Credit_Card" value="Credit_Card" <?php if(!$credit){ echo "disabled"; }?>>ชำระผ่านบัตรเครดิต</label>
                                </div>
                            </div>
                            <!-- <div class="radio text-center">
                                    <label>วิธีการชำระเงิน </label>
                                    <label><input type="radio" name="payment_method" id="Bank_Transfer" value="Bank_Transfer" onclick="showBank();" checked>โอนเงินผ่านธนาคาร</label>
                                    <label><input type="radio" name="payment_method" id="Credit_Card" value="Credit_Card" onclick="showCredit();">ชำระผ่านบัตรเครดิต</label>
                            </div> -->
                        </div>
                    </div>
                    <br />
                     <div class="text-center btn-create">
			            <input type="checkbox" name="condition" id="condition" onclick="acceptTerm()" /> ข้าพเจ้ายอมรับ <a href="http://fastship.co/helps/terms-conditions/" target="_blank">ข้อตกลงและเงื่อนไขในการใช้บริการขนส่งกับ Fastship</a><br /><br />      
			            <button type="submit" id="submit" name="submit" onclick="submitForm()" class="btn btn-lg btn-primary" >ยืนยันการทำรายการ</button>
			        </div>
                </div>    
        </div>
       
  
        <div class="modal fade" id="ModalFS" tabindex="-1" role="dialog" aria-labelledby="ModalFS_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalFS_Label">ส่งที่ FastShip อย่างไร ?</h4>
                	</div>
                	<div class="modal-body text-center">
						<p>ลูกค้าสามารถนำพัสดุมาส่งได้ที่ Fastship</p>
						<p>1/269 ซอยแจ้งวัฒนะ 14 เขตหลักสี่ กรุงเทพมหานคร 10210</p>
						<br />
                        <p>ในเวลาทำการ <span class="green">วันจันทร์ - ศุกร์ </span> เวลา 09.00-18.00 น.</p>
                        <p>ยกเว้นวันหยุดนักขัตฤกษ์</p>
						<br />
						<p>การตัดรอบรับพัสดุ จะตัดทุก 15.00 น. ในเวลาวันทำการ</p> 
                        <p>หากลูกค้านำพัสดุมาส่งที่ Fastship หลัง 15.00 น. </p>
                        <p>จะถูกจัดส่งในวันทำการถัดไป</p>
						<br />
						<p>สอบถามเพิ่มเติม โทร. <a href="tel:+6620803999" target="_self"> 020803999</a></p>
                	</div>
            	</div>
        	</div>
        </div>
        <div class="modal fade" id="ModalTP" tabindex="-1" role="dialog" aria-labelledby="ModalTP_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalFS_Label">ส่งที่ไปรษณีย์ไทย อย่างไร ?</h4>
                	</div>
                	<div class="modal-body text-center">
						<p>ลูกค้าสามารถนำพัสดุมาส่งได้ที่ไปรษณีย์ไทยทุก ปณ.</p>
						<br />
                        <p>ในเวลาทำการ <span class="green">วันจันทร์ - ศุกร์ </span> เวลา 09.00-18.00 น.</p>
                        <p>ยกเว้นวันหยุดนักขัตฤกษ์</p>
						<br />
						<p>การตัดรอบรับพัสดุ จะตัดทุก 15.00 น. ในเวลาวันทำการ</p> 
                        <p>หากลูกค้านำพัสดุมาส่งที่ Fastship หลัง 15.00 น. </p>
                        <p>จะถูกจัดส่งในวันทำการถัดไป</p>
						<br />
						<p>สอบถามเพิ่มเติม โทร. <a href="tel:+6620803999" target="_self"> 020803999</a></p>
                	</div>
            	</div>
        	</div>
        </div>
  		<div class="modal fade" id="ModalSKB" tabindex="-1" role="dialog" aria-labelledby="ModalSKB_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalSKB_Label">ส่งที่ SKYBOX อย่างไร ?</h4>
                	</div>
                	<div class="modal-body text-center">
						<p>ลูกค้าสามารถนำพัสดุมาส่งได้ที่ Skybox ทั้ง 7 สาขา ได้แก่</p>
						<br />
						<p>อโศก , ช่องนนทรี , สนามกีฬาแห่งชาติ</p>
						<p>อนุเสาวรีย์ชัยสมรภูมิ, อารีย์ , หมอชิต , ทองหล่อ</p>
						<br />
						<p>ในช่วงเวลา วันจันทร์ - ศุกร์ เวลา 09.00-21.00 น.</p>
						<p>และเสาร์ - อาทิตย์ เวลา 10.00-19.00 น.</p>
						<br />
						<p>การตัดรอบรับพัสดุ จะตัดทุก 12.00 น. ในเวลาวันทำการ</p> 
						<p>หากพัสดุมาฝากในวันเสาร์อาทิตย์ หรือ หลัง 12.00 น.</p>
						<p>จะถูกจัดส่งในวันทำการถัดไป</p>
						<br />
						<p>สอบถามเพิ่มเติม โทร. <a href="tel:+6620803999" target="_self"> 020803999</a></p>
                	</div>
            	</div>
        	</div>
        </div>
  		<div class="modal fade" id="ModalEEDU" tabindex="-1" role="dialog" aria-labelledby="ModalEEDU_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
                    	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-left" id="ModalEEDU_Label">ส่งที่ EEDU อย่างไร ?</h4>
                    </div>
                    <div class="modal-body text-center">
                    	<p>ลูกค้าสามารถนำพัสดุมาส่งได้ที่ โรงเรียนสอนคอมพิวเตอร์</p>
						<p>อีคอมเมิร์ซ (eEDU) ชั้น 11 อาคารวรรณสรณ์ พญาไท</p>
						<p>ในช่วงเวลา วันจันทร์ - อาทิตย์ เวลา 09.00-17.00 น.</p>
						<br />
						<p>การตัดรอบรับพัสดุ จะตัดทุก 12.00 น. ในเวลาวันทำการ</p>
						<br />
						<p>หากพัสดุมาฝากในวันเสาร์อาทิตย์ หรือ หลัง 12.00 น.</p>
						<p>จะถูกจัดส่งในวันทำการถัดไป</p>
						<br />
						<p>สอบถามเพิ่มเติม โทร. <a href="tel:+6620803999" target="_self">020803999</a></p>
                    </div>
            	</div>
        	</div>
        </div>
  		<div class="modal fade" id="ModalCM" tabindex="-1" role="dialog" aria-labelledby="ModalCM_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
                    	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-left" id="ModalCM_Label">ส่งที่ Chiangmai อย่างไร ?</h4>
                    </div>
                    <div class="modal-body text-center">
						<p>ลูกค้าสามารถนำพัสดุมาส่งได้ที่ โรงเรียนสอนคอมพิวเตอร์ </p>
						<p>อีคอมเมิร์ซ (eEDU) ชั้น 1 อาคารเพรสทีจ ถ.ห้วยแก้ว จ.เชียงใหม่ </p>
						<p>ในช่วงเวลา <span class="green">วันจันทร์</span> หรือ <span class="green">วันพฤหัสบดี</span></p>
						<p>ยกเว้นวันหยุดนักขัตฤกษ์</p>
						<br />
						<p>การตัดรอบรับพัสดุ จะตัดทุกวันจันทร์ และ วันพฤหัสบดี</p> 
						<p>เวลา 15.00 น. ในเวลาวันทำการ</p>
						<br />
						<p>สอบถามเพิ่มเติม โทร. <a href="tel:+6620803999" target="_self"> 020803999</a></p>
                    </div>
                </div>
             </div>
        </div>
        </form>
        
        <form id="delete_form" class="form-horizontal" method="post" action="{{url ('shipment/cancel')}}">
		    {{ csrf_field() }}
		    <input type="hidden" name="shipmentId" />
		</form>
        
        <script type="text/javascript">

        function showDroppoint(){
            $("#agentsdrop").show();
            $("#fspickup").hide();
            $("#pickupcost").hide();
            var total_rate = (($("#total_rate").text()).trim()).replace(",","");
            var discount = parseInt($("#discount").text());

            $("#totalpickup").html((Math.max(0,parseInt(total_rate)+discount)).format());
        }

        function showDelivery(){
            $("#agentsdrop").hide();
            $("#fspickup").show();
            $("#pickup").show();
            // $("#pickupaddress").hide();
            $("#pickupcost").show();
            
            <?php if($total_rate < 2000){ ?>
                $("#cost").html("200");
            <?php }else{ ?>
                $("#cost").html("0");
            <?php } ?>

            var total_rate = parseInt((($("#total_rate").text()).trim()).replace(",",""));
            var cost = parseInt($("#cost").text());
            var discount = parseInt($("#discount").text());
            var total_pickup = (Math.max(0,cost+total_rate+discount)).format();
            $("#totalpickup").html(total_pickup);
        }

        function submitForm(){
            $("#pickup_form").submit();
            $("#submit").attr("readonly",true);
        }

        function acceptTerm(){
    		if($("#condition").is(":checked")){
    			$("#submit").attr("disabled",false);
    		}else{
    			$("#submit").attr("disabled",true);
    		}
        }
        
        function newaddress(){
            $("#pickupaddress").show();
        }

        function youraddress(){
            $("#pickupaddress").hide();
        }
        function cancelShipment(shipment_id){

        	if(confirm("คุณต้องการลบพัสดุรายการนี้ใช่หรือไม่")){
            	$("#delete_form input[name=shipmentId]").val(shipment_id);
        		$("#delete_form").submit();
        	}
            
        }

        $(document).ready( function() {
            $( ".selector" ).checkboxradio({
                classes: {
                    "ui-checkboxradio": "highlight"
                }
            });
            <?php if($discount > 0):?>
			$("#pickupdiscount").show();
            <?php endif; ?>
            
            showDelivery();
            $("#submit").attr("disabled",true);
        });

        Number.prototype.format = function(n, x) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
            return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
        };

        $(function () {
            $('.pickup_date').datetimepicker({format: 'YYYY-MM-DD'});
        });
        </script>
        
        <?php }else{ ?>
        
            <div class="row">
                <div class="col-md-7 pad8"><h2>พัสดุรอส่ง</h2></div>
            </div>
            <div class="text-center" style="padding-top: 30px;">
                <h4>ไม่พบรายการพัสดุที่สร้างไว้ กรุณาสร้างพัสดุ</h4>
                <a href="calculate_shipment_rate" class="btn btn-lg btn-primary">สร้างพัสดุ</a>
            </div>
        <?php } ?> 

    </div>

<?php if(sizeof($shipment_data) > 0 && $customer_data['latitude'] != ""): ?>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARGo6QU60StUz58XsOHjLs4Dg3UFllE4w&callback=initMap">
</script>
<script type="text/javascript">
	var map;
    var marker;
    var infowindow;

    function initMap() {

        var save_pos = {lat: <?php echo $customer_data['latitude']; ?>, lng: <?php echo $customer_data['longitude']; ?>};
        map = new google.maps.Map(document.getElementById('map'), {
          center: save_pos,
          draggable: false,
          disableDefaultUI: false,
          clickableIcons: false,
          fullscreenControl: false,
          keyboardShortcuts: false,
          mapTypeControl: false,
          scaleControl: false,
          scrollwheel: false,
          streetViewControl: false,
          zoomControl: false,
          zoom: 15
        });
        marker = new google.maps.Marker({
            position: save_pos,
            map: map
        });

      }
</script>
<?php endif; ?>
@endsection