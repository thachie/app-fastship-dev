@extends('layout')
@section('content')
<div class="conter-wrapper">
<?php if(sizeof($shipment_data) > 0): ?>
	<div class="row">
    	<div class="col-md-7 pad8"><h2>{!! FT::translate('create_pickup.heading') !!}</h2></div>
    	<div class="col-md-5 text-right">
        	<div class="bs-wizard dot-step" style="border-bottom:0;">
                <div class="col-xs-4 bs-wizard-step complete">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                    <p class="text-center">{!! FT::translate('step.step1') !!}</p>
            	</div> 
            	<div class="col-xs-4 bs-wizard-step complete">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">2</span></a>
                    <p class="text-center">{!! FT::translate('step.step2') !!}</p>
                </div>
                <div class="col-xs-4 bs-wizard-step active">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">3</span></a>
                    <p class="text-center">{!! FT::translate('step.step3') !!}</p>
        		</div>       
    		</div>
    	</div>
	</div>	    
        
    <form id="pickup_form" class="form-horizontal" method="post" action="{{url ('pickup/create')}}">
	    {{ csrf_field() }}
	    
        <div class="row">
    		<div class="col-md-12">
                <div class="panel panel-primary hidden-xs">
                    <div class="panel-heading">{!! FT::translate('create_pickup.panel.heading1') !!}</div>
                    <div class="panel-body">
                        <table class="table table-stripe table-hover">
                        <thead>
                            <tr>
                                <th>{!! FT::translate('label.shipment_id') !!}</th>
                                <th>{!! FT::translate('label.receiver') !!}</th>
                                <th>{!! FT::translate('label.destination') !!}</th>
                                <th>{!! FT::translate('label.agent') !!}</th>
                                <th>{!! FT::translate('label.shipping') !!}</th>
                                <th>{!! FT::translate('label.delete') !!}</th>
                                <th>{!! FT::translate('label.copy') !!}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(sizeof($shipment_data) > 0): 
                            foreach($shipment_data as $data): 
                            if($data['ShipmentDetail']['Width'] > 120 || $data['ShipmentDetail']['Height'] > 120 || $data['ShipmentDetail']['Length'] > 120):
                                $oversizeNote = "{!! FT::translate('create_pickup.warning.oversize_charge') !!}";
                            else:
                                $oversizeNote = "";
                            endif;
                            ?>
                            <tr id="shipment_<?php echo $data['ID'];?>">
                                <td>
                                    <a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a>
                                    <input type="hidden" name="shipment_id[]" value="<?php echo $data['ID']?>" />
                                </td>
                                <td><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                                <td><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                                <td><img src="images/agent/<?php echo $data['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width:80px;" /></td>
                                <td><?php echo number_format($data['ShipmentDetail']['ShippingRate'],0);?></td>
                                <td><a href="javascript:cancelShipment(<?php echo $data['ID'];?>);"> <i class="fa fa-trash"></i></a></td>
                            	<td>
                                	<a href="{{ url('/shipment/clone/?shipment_id='.$data['ID']) }}"><button type="button" class="btn btn-xs btn-secondary">{!! FT::translate('button.clone') !!}</button></a>
                                </td>
                            </tr>
                            <?php 
                            endforeach;
                            endif;
                            ?>
                            
                        </tbody>
                        </table>
                        <div class="col-md-12 text-center"><a href="{{ url('calculate_shipment_rate') }}">+ {!! FT::translate('button.add_shipment') !!}</a></div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 visible-xs">
                <div class="panel panel-primary">
                    <div class="panel-heading">{!! FT::translate('create_pickup.panel.heading1') !!}</div>
                    <div class="panel-body">
    	            <?php 
    	            if(sizeof($shipment_data) > 0): 
    	            foreach($shipment_data as $data): 
    	            ?>
    	            <div class="col-xs-12 shipment-list">
    	            	<div class="col-xs-12">
    	                    <div class="pull-left"><h4><a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a></h4></div>
    	                    <div class="pull-right"><h4 style="font-weight: 800; color: #f15a22;"><?php echo number_format($data['ShipmentDetail']['ShippingRate'],0);?> {!! FT::translate('unit.baht') !!}</h4></div>
    	                </div>
    	                <div class="clearfix"></div>
    	                
                        <div class="col-xs-5"><img src="images/agent/<?php echo $data['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width:100px;" /></div>
                        <div class="col-xs-7">
                        	<h4 style="margin-bottom: 5px;"><?php echo $data['ReceiverDetail']['Firstname'];?> </h4>
                        	{!! FT::translate('label.destination') !!} : <?php echo $countries[$data['ReceiverDetail']['Country']];?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 text-right small"> 
                        	<a style="text-decoration: none; font-size: 10px; font-weight: 600;" href="javascript:cancelShipment(<?php echo $data['ID'];?>);"><i class="fa fa-trash"></i> {!! FT::translate('label.delete') !!}</a>
                        </div>
                    </div>
    	            <?php 
    	            endforeach;
    	            endif;
    	            ?>
    	            <div class="col-md-12 text-center"><a href="{{ url('calculate_shipment_rate') }}">+ {!! FT::translate('button.add_shipment') !!}</a></div>
    	            </div>
            	</div>
        	</div>
        </div>
        
        <div class="row"> 
        	<div class="col-md-6">
            	<div class="panel panel-primary">
                    <div class="panel-heading">{!! FT::translate('create_pickup.panel.heading2') !!}</div>
                    <div class="panel-body">
                        <div class="radio text-center pickup-type">     
                            <label><input type="radio" name="type" id="delivery" value="pickup" onclick="showDelivery();" checked>{!! FT::translate('radio.pickup_athome') !!}</label>
                            &nbsp;
                            <label><input type="radio" name="type" id="droppoint" value="drop" onclick="showDroppoint();">{!! FT::translate('radio.dropoff') !!}</label>
                        </div><br />

                        <div id="agentsdrop" style="display: none;">
                            <fieldset>
                            
                            	<label for="drop-fs">
                                    <span class="col-4 col-xs-5"><img src="/images/fastship.png"></span>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>FastShip</h3>
                                        <p class="slogan col-md-6 no-padding">{!! FT::translate('create_pickup.text.free') !!}</p>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalFS">
                                            	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input onchange="hideAddress();" class="selector" type="radio" name="agent" id="drop-fs" value="Drop_AtFastship" checked="checked" />
                                
                                <?php if(isset($rates['Drop_AtThaiPost'])):?>
                                <label for="drop-thaipost">
                                    <div class="col-4 col-xs-5"><img src="/images/thaipost.png" /></div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>{!! FT::translate('radio.dropoff.thaipost') !!}</h3>
                                        <?php if($rates['Drop_AtThaiPost']['AccountRate'] == 0): ?>
                                        <p class="slogan col-md-6 no-padding">{!! FT::translate('create_pickup.text.free') !!}</p>
                                        <?php else: ?>
                                        <p class="slogan col-md-6 no-padding"><?php echo $rates['Drop_AtThaiPost']['AccountRate']; ?> {!! FT::translate('unit.baht') !!}</p>
                                        <?php endif; ?>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalTP">
                                            	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input onchange="hideAddress();" class="selector" type="radio" name="agent" id="drop-thaipost" value="Drop_AtThaiPost"> 
                                <?php endif; ?>
                                
                                <?php if(isset($rates['Drop_AtSkybox']) && false):?>
                                <label for="drop-skybox">
                                    <div class="col-4 col-xs-5"><img src="/images/skybox.png"></div>
                                    <div class="col-8 col-xs-7 text-left">
                                        <h3>SKYBOX</h3>
                                        <p class="slogan col-md-6 no-padding">{!! FT::translate('create_pickup.text.free') !!}</p>
                                        <div class="text-right col-md-6">
                                            <button type="button" class="btn btn-xs btnmodal" data-toggle="modal" data-target="#ModalSKB">
                                            	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                            </button>
                                        </div>
                                    </div>
                                </label>
                                <input onchange="hideAddress();" class="selector" type="radio" name="agent" id="drop-skybox" value="Drop_AtSkybox">
                                <?php endif; ?>

                            </fieldset>
                        </div>
                        
                        <div id="fspickup">
                            <div class="row">
                                <label class="col-md-4 control-label">{!! FT::translate('label.pickup_date') !!}</label>
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
                                <label class="col-md-4 control-label">{!! FT::translate('label.pickup_time') !!}</label>
                                <div class="col-md-6">
                                    <select name="pickuptime" class="form-control required">
                                        <option value="">{!! FT::translate('dropdown.default.pickuptime') !!}</option>
                                        <option value="09:00">9.01-10.00 น.</option>
                                        <option value="10:00">10.01-11.00 น.</option>
                                        <option value="11:00">11.01-12.00 น.</option>
                                        <option value="12:00">12.01-13.00 น.</option>
                                        <option value="13:00">13.01-14.00 น. พัสดุจะส่งในวันถัดไป</option>
                                        <option value="14:00">14.01-15.00 น. พัสดุจะส่งในวันถัดไป</option>
                                        <option value="15:00">15.01-16.00 น. พัสดุจะส่งในวันถัดไป</option>
                                        <option value="16:00">16.01-17.00 น. พัสดุจะส่งในวันถัดไป</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <br />
                      </div>
                      <div id="address_section" class="row">

                                <?php if($customer_data['address1'] != ""):?>
                                <label><input type="radio" name="address_select" checked value="old" onclick="checkAddressSelect()" /> {!! FT::translate('radio.address.account') !!}</label>
                                <div id="old_address">
                                    <div class="col-12 text-left well">
                                        <h4><?php echo $customer_data['firstname'] . " " . $customer_data['lastname']; ?></h4>
                                        <?php if($customer_data['company'] != ""): ?>
                                        <h6><?php echo $customer_data['company']; ?></h6>
                                        <?php endif; ?>
                                        <p>Tel: <?php echo $customer_data['phonenumber'] . " Email: " . $customer_data['email']; ?></p>
                                        <p><?php echo $customer_data['address1'] . " " . $customer_data['address2'] . " " . $customer_data['city']. " " . $customer_data['state']. " " . $customer_data['postcode']. " " . ($customer_data['country']?$countries[$customer_data['country']]:""); ?></p>
                                    </div>
                                 </div>
                                 <div style="clear:both;"></div> 
                                 <label><input type="radio" name="address_select" value="new" onclick="checkAddressSelect()" /> {!! FT::translate('radio.address.new') !!}</label>
                                  <div id="new_address" style="display: none;">
                                  		<div class="col-md-6">
            			                    <label for="firstname" class="col-12 control-label">{!! FT::translate('label.firstname') !!}</label>
            			                    <input type="text" class="form-control required input-count" name="firstname" id="firstname" value="<?php echo $customer_data['firstname']; ?>" maxlength="100" />
            			                </div>
            			                <div class="col-md-6">
            		                   		<label for="lastname" class="col-12 control-label">{!! FT::translate('label.lastname') !!}</label>
            		                    	<input type="text" class="form-control required input-count" name="lastname" id="lastname" value="<?php echo $customer_data['lastname']; ?>" maxlength="100" />
            		                    </div>
            		                    <div class="clearfix"></div>
            		                    
            		                    <div class="col-md-6">
            		                    	<label for="email" class="col-12 control-label">{!! FT::translate('label.email') !!}</label>
            		                    	<input type="text" class="form-control required input-count" name="email" id="email"  value="<?php echo $customer_data['email']; ?>" maxlength="100" />
            		                    </div>
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.telephone') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="telephone" id="telephone"  value="<?php echo $customer_data['phonenumber']; ?>" maxlength="100" />
            		                    </div>
            		                    <div class="clearfix"></div>
        
            			                <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.address1') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="address1" id="address1" value="<?php echo $customer_data['address1']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.address2') !!}</label>
            		                        <input type="text" class="form-control input-count" name="address2" id="address2" value="<?php echo $customer_data['address2']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="clearfix"></div>
            		                    
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.city') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="city" id="city" value="<?php echo $customer_data['city']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.state') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="state" id="state" value="<?php echo $customer_data['state']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="clearfix"></div>
            		                    
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.postcode') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="postcode" id="postcode" value="<?php echo $customer_data['postcode']; ?>" maxlength="20" />
            		                    </div>
            		                    
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.country') !!}</label>
            		                        <div class="form-control"><?php echo ($customer_data['country'])?$countries[$customer_data['country']]:""; ?></div>
            		                    </div>
            		                    <div class="clearfix"></div>
                                  </div>
                                 <?php else: ?>
                                  <label><input type="radio" name="address_select" value="new" checked/> {!! FT::translate('radio.address.new') !!}</label>
                                  <div id="new_address_2">
                                  		<div class="col-md-6">
            			                    <label for="firstname" class="col-12 control-label">{!! FT::translate('label.firstname') !!}</label>
            			                    <input type="text" class="form-control required input-count" name="firstname" id="firstname" value="<?php echo $customer_data['firstname']; ?>" maxlength="100" />
            			                </div>
            			                <div class="col-md-6">
            		                   		<label for="lastname" class="col-12 control-label">{!! FT::translate('label.lastname') !!}</label>
            		                    	<input type="text" class="form-control required input-count" name="lastname" id="lastname" value="<?php echo $customer_data['lastname']; ?>" maxlength="100" />
            		                    </div>
            		                    <div class="clearfix"></div>
            		                    
            		                    <div class="col-md-6">
            		                    	<label for="email" class="col-12 control-label">{!! FT::translate('label.email') !!}</label>
            		                    	<input type="text" class="form-control required input-count" name="email" id="email"  value="<?php echo $customer_data['email']; ?>" maxlength="100" />
            		                    </div>
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.telephone') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="telephone" id="telephone"  value="<?php echo $customer_data['phonenumber']; ?>" maxlength="100" />
            		                    </div>
            		                    <div class="clearfix"></div>
        
            			                <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.address1') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="address1" id="address1" value="<?php echo $customer_data['address1']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.address2') !!}</label>
            		                        <input type="text" class="form-control input-count" name="address2" id="address2" value="<?php echo $customer_data['address2']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="clearfix"></div>
            		                    
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.city') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="city" id="city" value="<?php echo $customer_data['city']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.state') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="state" id="state" value="<?php echo $customer_data['state']; ?>" maxlength="50" />
            		                    </div>
            		                    <div class="clearfix"></div>
            		                    
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.postcode') !!}</label>
            		                        <input type="text" class="form-control required input-count" name="postcode" id="postcode" value="<?php echo $customer_data['postcode']; ?>" maxlength="20" />
            		                    </div>
            		                    
            		                    <div class="col-md-6">
            		                        <label for="telephone" class="col-12 control-label">{!! FT::translate('label.country') !!}</label>
            		                        <div class="form-control"><?php echo ($customer_data['country'])?$countries[$customer_data['country']]:""; ?></div>
            		                    </div>
            		                    <div class="clearfix"></div>
                                  </div>
                                 <?php endif; ?>
                                 
                                 <br />
                                 <div class="help-block small">* {!! FT::translate('create_pickup.pickup_remark') !!}</div>

			        	</div>
			            
                    </div>
                </div>
          	</div>
            <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{!! FT::translate('create_pickup.panel.heading3') !!}</div>
                        <div class="panel-body sumpickbody">
                            <div class="row">
                                <div class="col-md-5 col-xs-6 text-right">{!! FT::translate('label.number_shipment') !!}</div>
                                <div class="col-md-3 col-xs-4 text-right">
                                    <?php
                                        echo sizeof($shipment_data);
                                    ?>
                                </div>
                                <div class="col-md-4 col-xs-2">{!! FT::translate('unit.piece') !!}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6 text-right">{!! FT::translate('label.total_shipping') !!}</div>
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
                                <div class="col-md-4 col-xs-2">{!! FT::translate('unit.baht') !!}</div>
                            </div>
                            <div class="row" id="pickupcost" >
                                <div class="col-md-5 col-xs-6 text-right">{!! FT::translate('label.pickup_cost') !!}</div>
                                <div class="col-md-3 col-xs-4 text-right" id="cost"></div>
                                <div class="col-md-4 col-xs-2">{!! FT::translate('unit.baht') !!}</div>
                            </div>
                            <div class="row" id="couponcode">
                                <div class="col-md-5 col-xs-6 text-right">{!! FT::translate('label.couponcode') !!}</div>
                                <div class="col-md-1 col-xs-0">&nbsp;</div>
                                <div class="col-md-3 col-xs-6 text-right no-padding" >
                                	<input type="text" name="coupon_code" id="coupon_code" class="form-control input-sm" onblur="getCouponDiscount();"/>
                                	<i class="fa fa-times-circle-o xcross" style="display:none;" id="xcross" onclick="cancelCoupon()" ></i>
                                </div>
                                <div class="col-md-3 col-xs-2 "><input type="button" value="{!! FT::translate('create_pickup.form.apply_code') !!}" class="btn btn-sm" onclick="getCouponDiscount();" /></div>
                            </div>
                            <div class="row red small text-center" id="coupon_error"></div>
                            <div class="row" id="pickupdiscount" style="display: none;">
                                <div class="col-md-5 col-xs-6 text-right">{!! FT::translate('label.discount') !!}</div>
                                <div class="col-md-3 col-xs-4 text-right" id="discount">-<?php echo $discount;?></div>
                                <div class="col-md-4 col-xs-2">{!! FT::translate('unit.baht') !!}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6 text-right">{!! FT::translate('label.grand_total') !!}</div>
                                <div class="col-md-3 col-xs-4 text-right " id="totalpickup"></div>
                                <div class="col-md-4 col-xs-2">{!! FT::translate('unit.baht') !!}</div>
                            </div>
                            @if($customer_data['invoice'] == 1)
                                <input type="hidden" name="payment_method" id="invoice" value="Invoice">
                            @else
                                <div class="radio text-center">
                                    <div class="col-md-5 col-xs-12">
                                        <label>{!! FT::translate('label.payment_method') !!}</label>
                                    </div>
                                    <div class="col-md-7 col-xs-12">
                                        <!--<label><input type="radio" name="payment_method" id="Bank_Transfer" value="Bank_Transfer" checked>{!! FT::translate('radio.payment.bank_transfer') !!}</label>-->
                                        <div class="text-left">
                                            <label><input type="radio" name="payment_method" id="QR" value="QR" checked>QR Payment</label>
                                        </div>
                                        <div class="text-left">
                                            <label><input type="radio" name="payment_method" id="Credit_Card" value="Credit_Card" <?php if(!isset($credit) || !$credit){ echo "disabled"; }?>>{!! FT::translate('radio.payment.creditcard') !!}</label>
                                            <label><input type="radio" name="payment_method" id="Credit_Card" value="Credit_Card_New">{!! FT::translate('radio.payment.creditcard') !!} (New)</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <br />
                    @if($oversizeNote != "")
                    <div class="text-center text-danger">{!! $oversizeNote !!}</div><br />
                    @endif
                    
                    <div class="text-center btn-create">
			            <div><label><input type="checkbox" name="condition" id="condition" onclick="acceptTerm()" /> {!! FT::translate('create_pickup.agreement_intro') !!} <a href="http://fastship.co/helps/terms-conditions/" target="_blank">{!! FT::translate('create_pickup.agreement_link') !!}</a></label></div>
			            <br />      
			            <div><button type="submit" id="submit" name="submit" class="btn btn-lg btn-primary" >{!! FT::translate('button.confirm') !!}</button></div>
			            <div id="error_text" class="text-left red col-xs-6 col-xs-offset-3"></div>
			        </div>
                </div>    
        </div>
       
  
        <div class="modal fade" id="ModalFS" tabindex="-1" role="dialog" aria-labelledby="ModalFS_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalFS_Label">{!! FT::translate('modal.drop_fastship.heading') !!}</h4>
                	</div>
                	<div class="modal-body text-center">{!! FT::translate('modal.drop_fastship.content') !!}</div>
            	</div>
        	</div>
        </div>
        <div class="modal fade" id="ModalTP" tabindex="-1" role="dialog" aria-labelledby="ModalTP_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalFS_Label">{!! FT::translate('modal.drop_thaipost.heading') !!}</h4>
                	</div>
                	<div class="modal-body text-center">{!! FT::translate('modal.drop_thaipost.content') !!}</div>
            	</div>
        	</div>
        </div>
	</form>
        
    <form id="delete_form" class="form-horizontal" method="post" action="{{url ('shipment/cancel')}}">
		{{ csrf_field() }}
		<input type="hidden" name="shipmentId" />
	</form>
    <link rel="stylesheet" href="./css/jquery.Thailand.min.css" />
    <script type="text/javascript" src="./js/JQL.min.js"></script>
    <script type="text/javascript" src="./js/typeahead.bundle.js"></script>
    <script type="text/javascript" src="./js/jquery.Thailand.min.js"></script>
	<script type="text/javascript">

    	$.Thailand({
    		$district: $('#pickup_form [name="address2"]'),
    		$amphoe: $('#pickup_form [name="city"]'),
    		$province: $('#pickup_form [name="state"]'),
    		$zipcode: $('#pickup_form [name="postcode"]'),
    	});
	
        function showDroppoint(){

        	$("#fspickup").fadeOut(300).delay(300);
            $("#agentsdrop").fadeIn(300);

            agent = $("input[name=agent]:checked");

            hideAddress();
            
            var total_rate = (($("#total_rate").text()).trim()).replace(",","");
            var discount = parseInt($("#discount").text());
			var cost = 0;
            <?php if(isset($rates['Drop_AtThaiPost'])):?>
            if(agent.val() == "Drop_AtThaiPost"){
                $("#cost").html(<?php echo $rates['Drop_AtThaiPost']['AccountRate']; ?>);
                cost = <?php echo $rates['Drop_AtThaiPost']['AccountRate']; ?>;
			}else{
                $("#cost").html("0");
			}
            <?php else: ?>
            	$("#cost").html("0");
            <?php endif; ?>

            $("#totalpickup").html((Math.max(0,parseInt(total_rate)+discount+cost)).format());
        }

        function showDelivery(){
            $("#agentsdrop").fadeOut(300).delay(300);
            $("#fspickup").fadeIn(300);
            
            $("#address_section").show();
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

        function hideAddress(){
        	agent = $("input[name=agent]:checked");
        	if(agent.val() != "Drop_AtThaiPost"){
        		$("#address_section").hide();
        	}else{
        		$("#address_section").show();
        	}
        }
        function submitForm(){

        	var valid = true;
            if($("#pickup_form input[name='firstname']").val() == ""){
            	$("#pickup_form input[name='firstname']").css("border","1px solid red");
            	valid = false;
            }

            console.log($("#pickup_form").submit());
            if(valid){
                $("#pickup_form").submit();
                $("#submit").addClass("disabled");
                $("#submit").attr("readonly",true);
            }

            
        }

        function acceptTerm(){
    		if($("#condition").is(":checked")){
    			$("#submit").attr("disabled",false);
    		}else{
    			$("#submit").attr("disabled",true);
    		}
        }
        function checkAddressSelect(){
            var selectAddr = $("input[name=address_select]:checked").val();
            if(selectAddr == "new"){
                $("#old_address").hide();
                $("#new_address").show();
            }else{
            	$("#new_address").hide();
            	$("#old_address").show();
            }
        }

        function cancelShipment(shipment_id){

        	if(confirm("คุณต้องการลบพัสดุรายการนี้ใช่หรือไม่")){
            	$("#delete_form input[name=shipmentId]").val(shipment_id);
        		$("#delete_form").submit();
        	}
            
        }

        function getCouponDiscount(){

        	var accountDiscount = <?php echo $discount; ?>;
            if($("#coupon_code").val() == "") {

            	//$("#pickupdiscount").hide();
            	showDiscount();
				$("#coupon_error").hide();
    			$("#discount").html("-" + accountDiscount);

    			var total_rate = parseInt(($("#total_rate").text()).trim().replace(",",""));
                var discount = Math.max(accountDiscount,0);
    			var cost = parseInt(($("#cost").text()).trim().replace(",",""));

            	$("#totalpickup").html((Math.max(0,total_rate+discount+cost)).format());    
            	
            }else{
 
                console.log("{{ session('customer.id') }}");
                
        		$.post("{{url ('pickup/get_coupon')}}",
        		{
        			_token: $("[name=_token]").val(),
        			code: $("#coupon_code").val(),
        			total: $("#totalpickup").text(),
        			payment: $("input[name='payment_method']:checked").val(),
        			sources: "{{ implode(';',$sources) }}",
        			agents: "{{ implode(';',$agents) }}",
        		},function(data){
    
        			console.log("discount: " + data);
        			
        			if(data > accountDiscount){
            			$("#pickupdiscount").show();
            			$("#coupon_error").hide();
            			$("#discount").html("-"+Math.max(accountDiscount,data));
        			}else{
        				showDiscount();
        				$("#coupon_error").text("{!! FT::translate('error.nodiscountcode') !!}");
        				$("#coupon_error").show();
            			$("#discount").html("-" + Math.max(accountDiscount,data));
        			}
    
        			var total_rate = parseInt(($("#total_rate").text()).trim().replace(",",""));
                    var discount = parseInt(($("#discount").text()).trim().replace(",",""));
        			var cost = parseInt(($("#cost").text()).trim().replace(",",""));
    
        			$("#xcross").show();
                	$("#totalpickup").html((Math.max(0,total_rate+discount+cost)).format());             
                    
        		},"json");
    		
            }

        }

        function cancelCoupon(){

        	var accountDiscount = <?php echo $discount; ?>;
        	
        	$("#coupon_code").val("");
        	$("#discount").html("-"+accountDiscount);
        	showDiscount();
			$("#coupon_error").hide();
			$("#xcross").hide();
			
			var total_rate = parseInt(($("#total_rate").text()).trim().replace(",",""));
            var discount = -1*accountDiscount;
			var cost = parseInt(($("#cost").text()).trim().replace(",",""));

        	$("#totalpickup").html((Math.max(0,total_rate+discount+cost)).format());  

        }

        function showDiscount(){
        	<?php if($discount > 0):?>
			$("#pickupdiscount").show();
			<?php else: ?>
			$("#pickupdiscount").hide();
            <?php endif; ?>
        }

        $(document).ready( function() {
            $( ".selector" ).checkboxradio({
                classes: {
                    "ui-checkboxradio": "highlight"
                }
            });

            showDiscount();
            
            showDelivery();
            $("#submit").attr("disabled",true);

            <?php if(isset($rates['Drop_AtThaiPost'])):?>
            $("input[name=agent]").on("click",function(){

            	var total_rate = (($("#total_rate").text()).trim()).replace(",","");
                var discount = parseInt($("#discount").text());
    			var cost = 0;
    			
            	if($(this).val() == "Drop_AtThaiPost"){
                	$("#cost").html(<?php echo $rates['Drop_AtThaiPost']['AccountRate']; ?>);
                	cost = <?php echo $rates['Drop_AtThaiPost']['AccountRate']; ?>;
    			}else{
                    $("#cost").html("0");
    			}

            	$("#totalpickup").html((Math.max(0,parseInt(total_rate)+discount+cost)).format());
            	
            });
            <?php endif; ?>


            $("#pickup_form").on("submit",function(){

            	var valid = true;

            	var error = "";
            	var address_select = $("#pickup_form input[name='address_select']:checked").val();
            	var type = $("#pickup_form input[name='type']:checked").val();
            	var agent = $("#pickup_form input[name='agent']:checked").val();

            	if(type == "pickup" || agent == "Drop_AtThaiPost"){

            		if(type == "pickup"){
    	            	if($("#pickup_form input[name='pickupdate']").val() == ""){
    	                	$("#pickup_form input[name='pickupdate']").css("border","1px solid red");
    	                	error += "- {!! FT::translate('label.pickup_date') !!}<br />";
    	                	valid = false;
    	                }else{
    	                	$("#pickup_form input[name='pickupdate']").css("border","1px solid #cacaca");
    	                }
            		}
	
	            	if(address_select == "new"){
	                    if($("#pickup_form input[name='firstname']").val() == ""){
	                    	$("#pickup_form input[name='firstname']").css("border","1px solid red");
	                    	error += "- {!! FT::translate('label.firstname') !!}<br />";
	                    	valid = false;
	                    }else{
	                    	$("#pickup_form input[name='firstname']").css("border","1px solid #cacaca");
	                    }
	
	                    if($("#pickup_form input[name='telephone']").val() == ""){
	                    	$("#pickup_form input[name='telephone']").css("border","1px solid red");
	                    	error += "- {!! FT::translate('label.telephone') !!}<br />";
	                    	valid = false;
	                    }else{
	                    	$("#pickup_form input[name='telephone']").css("border","1px solid #cacaca");
	                    }
	                    
	                    if($("#pickup_form input[name='address1']").val() == ""){
	                    	$("#pickup_form input[name='address1']").css("border","1px solid red");
	                    	error += "- {!! FT::translate('label.address1') !!}<br />";
	                    	valid = false;
	                    }else{
	                    	$("#pickup_form input[name='address1']").css("border","1px solid #cacaca");
	                    }
	                    
	                    if($("#pickup_form input[name='city']").val() == ""){
	                    	$("#pickup_form input[name='city']").css("border","1px solid red");
	                    	error += "- {!! FT::translate('label.city') !!}<br />";
	                    	valid = false;
	                    }else{
	                    	$("#pickup_form input[name='city']").css("border","1px solid #cacaca");
	                    }
	                    
	                    if($("#pickup_form input[name='state']").val() == ""){
	                    	$("#pickup_form input[name='state']").css("border","1px solid red");
	                    	error += "- {!! FT::translate('label.state') !!}<br />";
	                    	valid = false;
	                    }else{
	                    	$("#pickup_form input[name='state']").css("border","1px solid #cacaca");
	                    }
	                    
	                    if($("#pickup_form input[name='postcode']").val() == ""){
	                    	$("#pickup_form input[name='postcode']").css("border","1px solid red");
	                    	error += "- {!! FT::translate('label.postcode') !!}<br />";
	                    	valid = false;
	                    }else{
	                    	$("#pickup_form input[name='postcode']").css("border","1px solid #cacaca");
	                    }
	                    
	            	}
            	}
            	
                if(valid == false) {

                    $("#error_text").html("{!! FT::translate('error.required') !!}<br />" + error);
                    return false;
                }

                $("#error_text").text("");
                $("#submit").attr("readonly",true);

            });
            
        });

        Number.prototype.format = function(n, x) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
            return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
        };

        $(function () {
        	var someDate = new Date();
        	var numberOfDaysToAdd = 2;
        	someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
        	
            $('.pickup_date').datetimepicker({format: 'YYYY-MM-DD',minDate: new Date(),maxDate: someDate});
        });

        @if(session('msg-type') && session('msg-type') == "shipment-success")
            fbq('track', 'AddToCart', {
            	value: 200,
            	currency: 'THB',
            });
        @endif
	</script>
<?php else: ?>
	<div class="row">
		<div class="col-md-7 pad8"><h2>{!! FT::translate('create_pickup.heading') !!}</h2></div>
    </div>
	<div class="text-center" style="padding-top: 30px;">
		<h4>{!! FT::translate('create_pickup.warning.noshipment') !!}</h4>
		<a href="calculate_shipment_rate" class="btn btn-lg btn-primary">{!! FT::translate('button.create_shipment') !!}</a>
	</div>
<?php endif; //endif shipment size ?> 
</div>
@endsection