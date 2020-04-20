@extends('layout')
@section('content')
<?php 
$total_rate = 0; 
foreach($shipment_data as $data){
    $total = $total_rate + $data['ShipmentDetail']['ShippingRate'];
    $total_rate = $total;
}
?>
<div class="conter-wrapper">
@if(sizeof($shipment_data) > 0)
	<div class="row">
    	<div class="col-md-6 pad8"><h2>{!! FT::translate('create_pickup.heading') !!}</h2></div>
    	<div class="col-md-6 text-right">
        	<div class="bs-wizard dot-step" style="border-bottom:0;">
                <div class="col-xs-3 bs-wizard-step complete">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                    <p class="text-center">{!! FT::translate('step.step1') !!}</p>
            	</div> 
            	<div class="col-xs-3 bs-wizard-step complete">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">2</span></a>
                    <p class="text-center">{!! FT::translate('step.step2') !!}</p>
                </div>
                <div class="col-xs-3 bs-wizard-step active">
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

                        <div class="col-xs-12">
                        	<a href="javascript:cancelShipment(<?php echo $data['ID'];?>);"><i class="fa fa-trash"></i> {!! FT::translate('label.delete') !!}</a> | 
                        	<a href="{{ url('/shipment/clone/?shipment_id='.$data['ID']) }}">{!! FT::translate('button.clone') !!}</a>
                        </div>

                    </div>
    	            <?php 
    	            endforeach;
    	            endif;
    	            ?>
    	            <div class="clearfix"></div><br />
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

                    	<h3>{!! FT::translate('create_pickup.panel.subheading1') !!}</h3>

                        <fieldset>

                            @if(isset($rates['Pickup_ByKerryBulk']))
                            <label for="pick-kerrybulk">
                                <div class="col-md-2 hidden-xs"><img src="/images/pickup/kerry.png"></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                    
                                    <h5 style="margin-bottom:0">{!! FT::translate('option.pickup.bykerrybulk') !!}</h5>
                                    
                                    <div class="text-left">
                                        <a href="https://fastship.co/pickup_shipment" target="_blank">
                                        <button type="button" class="btn btn-xs btnmodal" style="font-size: 10px;">
                                        	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                        </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                	@if($rates['Pickup_ByKerryBulk']['AccountRate'] == 0)
                                    <h2 class="slogan text-success no-padding">{!! FT::translate('create_pickup.text.free') !!}</h2>
                                    @else
                                    <h2 class="slogan text-info no-padding">{{ $rates['Pickup_ByKerryBulk']['AccountRate'] }}.-</h2>
                                    @endif
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="pick-kerrybulk" value="Pickup_ByKerryBulk" checked="checked" />
                            @endif
                            
                            @if(isset($rates['Pickup_ByKerry']))
                            <label for="pick-kerry">
                                <div class="col-md-2 hidden-xs"><img src="/images/pickup/kerry.png"></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                    
                                    <h5  style="margin-bottom:0">{!! FT::translate('option.pickup.bykerry') !!}</h5>
                                    
                                    <div class="text-left">
                                        <a href="https://fastship.co/pickup_helps" target="_blank">
                                        <button type="button" class="btn btn-xs btnmodal" style="font-size: 10px;">
                                        	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                        </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                	@if($rates['Pickup_ByKerry']['AccountRate'] == 0)
                                    <h2 class="slogan text-success no-padding">{!! FT::translate('create_pickup.text.free') !!}</h2>
                                    @else
                                    <h2 class="slogan text-info no-padding">{{ $rates['Pickup_ByKerry']['AccountRate'] }}.-</h2>
                                    @endif
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="pick-kerry" value="Pickup_ByKerry" checked="checked" />
                            @endif
                            
                            @if(isset($rates['Pickup_ByFlash']))
                            <label for="pick-flash">
                                <div class="col-md-2 hidden-xs"><img src="/images/pickup/flash.png"></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                    
                                    <h5  style="margin-bottom:0">{!! FT::translate('option.pickup.byflash') !!}</h5>
                                    
                                    <div class="text-left">
                                        <a href="https://fastship.co/pickup_helps" target="_blank">
                                        <button type="button" class="btn btn-xs btnmodal" style="font-size: 10px;">
                                        	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                        </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                	@if($rates['Pickup_ByFlash']['AccountRate'] == 0)
                                    <h2 class="slogan text-success no-padding">{!! FT::translate('create_pickup.text.free') !!}</h2>
                                    @else
                                    <h2 class="slogan text-info no-padding">{{ $rates['Pickup_ByFlash']['AccountRate'] }}.-</h2>
                                    @endif
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="pick-flash" value="Pickup_ByFlash" />
                            @endif

                            @if($isBangkok)
                            <label for="pick-standard-fs">
                                <div class="col-md-2 hidden-xs"><img src="/images/pickup/fastship.png"></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                
                                    <h5 style="margin-bottom:0">{!! FT::translate('option.pickup.athome_standard') !!}</h5>
                                    
                                    <div class="text-left">
                                        <a href="https://fastship.co/pickup_dropoff" target="_blank">
                                            <button type="button" class="btn btn-xs btnmodal" style="font-size: 10px;">
                                            	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                            </button>
                                        </a>
                                    </div>
                                    
                                    <span class="text-info tiny">{!! FT::translate('create_pickup.text.bkk_only') !!}</span>
                                    
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                @if($total_rate > 2000)
                                	<h2 class="slogan text-success no-padding">{!! FT::translate('create_pickup.text.free') !!}</h2>
                                @else
                                	<h2 class="slogan text-info no-padding">200.-</h2>
                                @endif
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="pick-standard-fs" value="Pickup_AtHomeStandard" />

                            <label for="pick-express-fs">
                                <div class="col-md-2 hidden-xs"><img src="/images/pickup/skootar_lalamove.png" style="max-width:100%;" /></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                    
                                    <h5 style="margin-bottom:0">{!! FT::translate('option.pickup.athome_express') !!}</h5>

                                    <div class="text-left">
                                        <a href="https://fastship.co/pickup_dropoff" target="_blank">
                                            <button type="button" class="btn btn-xs btnmodal" style="font-size: 10px;">
                                            	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!} 
                                            </button>
                                        </a>
                                    </div>
                                    
                                    <span class="text-info tiny">{!! FT::translate('create_pickup.text.bkk_only') !!}</span>
                                    
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                	<h2 class="slogan text-info no-padding">350.-</h2>
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="pick-express-fs" value="Pickup_AtHomeExpress" />

                        	<label for="drop-fs">
                                <div class="col-md-2 hidden-xs"><img src="/images/pickup/fastship.png"></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                    
                                    <h5 style="margin-bottom:0">{!! FT::translate('option.pickup.drop_fastship') !!}</h5>
                                    
                                    <div class="text-left">
                                        <button type="button" class="btn btn-xs btnmodal"  style="font-size: 10px;" data-toggle="modal" data-target="#ModalFS">
                                        	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                        </button>
                                    </div>
                                    
                                    <span class="text-info tiny">{!! FT::translate('create_pickup.text.bkk_only') !!}</span>
                                    
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                	<h2 class="slogan text-success no-padding">{!! FT::translate('create_pickup.text.free') !!}</h2>
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="drop-fs" value="Drop_AtFastship" />
                            @endif
                            
                            <?php if(isset($rates['Drop_AtThaiPostBulk'])):?>
                            <label for="drop-thaipostbulk">
                                <div class="col-md-2 hidden-xs"><img src="/images/pickup/thaipost.png" /></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                
                                    <h5 style="margin-bottom:0">{!! FT::translate('option.pickup.drop_thaipost') !!}</h5>

                                    <div class="text-left">
                                        <button type="button" class="btn btn-xs btnmodal" style="font-size: 10px;" data-toggle="modal" data-target="#ModalTPB">
                                        	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                	@if($rates['Drop_AtThaiPostBulk']['AccountRate'] == 0)
                                    <h2 class="slogan text-success no-padding">{!! FT::translate('create_pickup.text.free') !!}</h2>
                                    @else
                                    <h2 class="slogan text-info no-padding">{{ $rates['Drop_AtThaiPostBulk']['AccountRate'] }}.-</h2>
                                    @endif
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="drop-thaipostbulk" value="Drop_AtThaiPostBulk"> 
                            <?php endif; ?>
                            
                            <?php if(isset($rates['Drop_AtThaiPost'])):?>
                            <label for="drop-thaipost">
                                <div class="col-md-2 hidden-xs"><img src="/images/thaipost.png" /></div>
                                <div class="col-md-8 col-xs-8 text-left">
                                
                                    <h5 style="margin-bottom:0">{!! FT::translate('option.pickup.drop_thaipost') !!}</h5>

                                    <div class="text-left">
                                        <button type="button" class="btn btn-xs btnmodal" style="font-size: 10px;" data-toggle="modal" data-target="#ModalTP">
                                        	<i class="fa fa-info-circle"></i> {!! FT::translate('create_pickup.text.more_detail') !!}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4 text-right">
                                	@if($rates['Drop_AtThaiPost']['AccountRate'] == 0)
                                    <h2 class="slogan text-success no-padding">{!! FT::translate('create_pickup.text.free') !!}</h2>
                                    @else
                                    <h2 class="slogan text-info no-padding">{{ $rates['Drop_AtThaiPost']['AccountRate'] }}.-</h2>
                                    @endif
                                </div>
                            </label>
                            <input onchange="selectPickup(this.value);" class="selector" type="radio" name="agent" id="drop-thaipost" value="Drop_AtThaiPost"> 
                            <?php endif; ?>
                                


                        </fieldset>
                        
                        <div class="clearfix"></div><br />
                        
                        <div id="fspickup">
                        
                        	<h3>{!! FT::translate('create_pickup.panel.subheading2') !!}</h3>
                            <div class="row">
                                <label class="col-md-3 control-label">{!! FT::translate('label.pickup_date') !!}</label>
                                <div class="col-md-4">
                                    <div id="date-result-panel"><span class="text-light small" style="line-height: 40px;">เลือกวิธีเข้ารับก่อน</span></div>
                                </div>
                                <div class="col-md-5">
                            		<div id="time-result-panel"><span class="text-light small" style="line-height: 40px;">เลือกวันที่นัดรับก่อน</span></div>
                                </div>
                                <div class="clearfix"></div><br />

                                <div class="col-md-12 text-center">
                                	<span class="small text-info" id="pickup-remark"></span>
                                </div>
                            </div>
        
                      </div>
                      
                      <div class="clearfix"></div><br />
                      
                      <div id="address_section">
                      
                      		<h3>{!! FT::translate('create_pickup.panel.subheading3') !!}</h3>

                                <?php if($customer_data['address1'] != ""):?>
                                <label><input type="radio" name="address_select" checked value="old" onclick="checkAddressSelect()" /> {!! FT::translate('radio.address.account') !!}</label>
                                <div id="old_address">
                                    <div class="col-12 text-left well">
                                        <h4>{{ $customer_data['firstname'] . " " . $customer_data['lastname'] }}</h4>
                                        @if($customer_data['company'] != "")
                                        <h6>{{ $customer_data['company'] }}</h6>
                                        @endif
                                        <p>Tel: {{ $customer_data['phonenumber'] . " Email: " . $customer_data['email'] }}</p>
                                        <p>{{ $customer_data['address1'] . " " . $customer_data['address2'] . " " . $customer_data['city']. " " . $customer_data['state']. " " . $customer_data['postcode']. " " . ($customer_data['country']?$countries[$customer_data['country']]:"") }}</p>
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
                            	<div class="row text-center small text-success">ชำระโดยการวางบิล</div>
                                <input type="hidden" name="payment_method" id="invoice" value="Invoice" />
                            @else
                                <div class="radio text-center">
                                    <div class="col-md-5 col-xs-12">
                                        <label>{!! FT::translate('label.payment_method') !!}</label>
                                    </div>
                                    <div class="col-md-7 col-xs-12">
                                        <!--<label><input type="radio" name="payment_method" id="Bank_Transfer" value="Bank_Transfer" checked>{!! FT::translate('radio.payment.bank_transfer') !!}</label>-->
                                        <div class="text-left">
                                            <label><input type="radio" name="payment_method" id="QR" value="QR" required checked>QR Payment</label>
                                        </div>
                                        @if(sizeof($creditCards) > 0)
                                        @foreach($creditCards as $card)
                                        <div class="text-left">
                                            <label><input type="radio" name="payment_method" value="Credit_Card_{{ $card->OMISE_LASTDIGITS }}" required checked>{!! FT::translate('radio.payment.creditcard') !!} - XXXX-{{ $card->OMISE_LASTDIGITS }}</label>
                                        </div>
                                        @endforeach
                                        @endif
<!--                                         <div class="text-left"> -->
<!--                                             <label><input type="radio" name="payment_method" value="Credit_Card_New" required >{!! FT::translate('radio.payment.creditcard') !!} - เพิ่มบัตรใหม่</label> -->
<!--                                         </div> -->
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
			            <div><label><input type="checkbox" name="condition2" id="condition2" onclick="acceptTerm()" /> {!! FT::translate('create_pickup.agreement_intro') !!} <a href="https://fastship.co/announcement/" target="_blank">ข้อตกลงและเงื่อนไขพิเศษเนื่องในสถานการณ์ COVID-19</a></label></div>
			            <br />
			               
			            <div><button type="submit" id="submit" name="submit" class="btn btn-lg btn-primary" >{!! FT::translate('button.confirm') !!}</button></div>
			            <div id="error_text" class="text-left red col-xs-6 col-xs-offset-3"></div>
			        </div>
                </div>    
        </div>
       
  
  		<div class="modal fade" id="ModalPUN" tabindex="-1" role="dialog" aria-labelledby="ModalPUN_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalPUN_Label">Fastship ไปรับพัสดุอย่างไร</h4>
                	</div>
                	<div class="modal-body text-center">สอบถามเพิ่มเติม โทร. 020803999</div>
            	</div>
        	</div>
        </div>
        
        <div class="modal fade" id="ModalPUE" tabindex="-1" role="dialog" aria-labelledby="ModalPUE_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalPUE_Label">{!! FT::translate('modal.drop_fastship.heading') !!}</h4>
                	</div>
                	<div class="modal-body text-center">{!! FT::translate('modal.drop_fastship.content') !!}</div>
            	</div>
        	</div>
        </div>
        
        <div class="modal fade" id="ModalPUS" tabindex="-1" role="dialog" aria-labelledby="ModalPUS_Label" aria-hidden="true">
        	<div class="modal-dialog">
            	<div class="modal-content">
                	<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-left" id="ModalPUS_Label">{!! FT::translate('modal.drop_fastship.heading') !!}</h4>
                	</div>
                	<div class="modal-body text-center">{!! FT::translate('modal.drop_fastship.content') !!}</div>
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

    	function getPickupDate(){
    
    		$.post("{{url ('pickup/get_date')}}",
    		{
    			_token: $("[name=_token]").val(),
    			agent: $("[name=agent]:checked").val(),
    			is_bangkok: '{{ $isBangkok }}'
    		},function(data){
    
        		var content = "";
        		$("#date-result-panel").empty();
        		
        		if(data !== false){
    	            var dataArray = $.map(data, function(value, index) {
    	                return [value];
    	            });
    	            var keyArray = $.map(data, function(value, index) {
    	                return [index];
    	            });   
        		}
    
                if(data !== false && dataArray.length > 0){
    
                    for (key in dataArray) {
                    	content += '<label for="pick-date-' + keyArray[key] + '" style="padding:5px;min-height: 30px;">';
                       	content += dataArray[key]; 
                        content += '</label>';
                       	content += '<input class="selector pick-date" type="radio" name="pickupdate" id="pick-date-' + keyArray[key] + '" value="' + keyArray[key] + '" onchange="getPickupTime()" >';
                    }
                    
                }else{
                    
                    content = "";
                    
                }
                
                $("#date-result-panel").append(content).delay(5);
            	$( ".selector" ).checkboxradio({
                    classes: { "ui-checkboxradio": "highlight" }
                });

            	$('#pick-date-'+keyArray[0]).attr('checked', true).change();
            	
            	getPickupTime();
                
            	//$('#pick-time-00').attr('checked', true).change();
    
        	},"json");
        }
    
    	function getPickupTime(){

    		$.post("{{url ('pickup/get_time')}}",
    		{
    			_token: $("[name=_token]").val(),
    			pick_date: $("[name=pickupdate]:checked").val(),
    			agent: $("[name=agent]:checked").val(),
    			is_bangkok: '{{ $isBangkok }}'
    		},function(data){

        		var content = "";
        		$("#time-result-panel").empty();
        		
        		if(data !== false){
    	            var dataArray = $.map(data, function(value, index) {
    	                return [value];
    	            });
    	            var keyArray = $.map(data, function(value, index) {
    	                return [index];
    	            });   
        		}
    
                if(data !== false && dataArray.length > 0){

                    for (key in dataArray) {
                    	var keyArrayKey = keyArray[key];
                        if(keyArray[key] < 10){
                            keyArrayKey = "0" + keyArray[key];
                        }
                        if(keyArray[key] == "all"){
                        	keyArrayKey = "00";
                        }
                    	content += '<label for="pick-time-' + keyArrayKey + '" style="padding:5px;min-height: 30px;">';
                       	content += dataArray[key];
                        content += '</label>';
                       	content += '<input class="selector" type="radio" name="pickuptime" rel="' + dataArray[key] + '" id="pick-time-' + keyArrayKey + '" value="' + keyArrayKey + ':00" onchange="selectPickupTime()">';
                    }
                    
                }else{
                    
                    content = "";
                    
                }
                
                $("#time-result-panel").append(content).delay(5);
            	$( ".selector" ).checkboxradio({
                    classes: { "ui-checkboxradio": "highlight" }
                });

            	$('#pick-time-00').attr('checked', true).change();

        	},"json");
        }

    	function selectPickupTime(){

    		$.post("{{url ('pickup/get_remark')}}",
    		{
    			_token: $("[name=_token]").val(),
    			pick_date: $("[name=pickupdate]:checked").val(),
    			pick_time: $("[name=pickuptime]:checked").val(),
    			agent: $("[name=agent]:checked").val(),
    			is_bangkok: '{{ $isBangkok }}'
    		},function(data){

                $("#pickup-remark").html(data);
                
        	},"json");
        }

    	$.Thailand({
    		$district: $('#pickup_form [name="address2"]'),
    		$amphoe: $('#pickup_form [name="city"]'),
    		$province: $('#pickup_form [name="state"]'),
    		$zipcode: $('#pickup_form [name="postcode"]'),
    	});

    	function selectPickup(type){

    		var total_rate = (($("#total_rate").text()).trim()).replace(",","");
            var discount = parseInt($("#discount").text());
			var cost = 0;
			
    		if(type == 'Pickup_ByKerry'){
    			$("#address_section").show();
    			$("#fspickup").hide();
    			@if(isset($rates['Pickup_ByKerry']))
    				cost = {{ $rates['Pickup_ByKerry']['AccountRate'] }};
    			@else
        			cost = 0;
    				$("#pick-kerry").hide();
    			@endif
    			$("#pickup_form input[name='state']").val("");
    			$("#pickup_form input[name='state']").attr("disabled",false);
    		}else if(type == 'Pickup_ByKerryBulk'){
    			$("#address_section").show();
    			$("#fspickup").hide();
    			@if(isset($rates['Pickup_ByKerryBulk']))
    				cost = {{ $rates['Pickup_ByKerryBulk']['AccountRate'] }};
    			@else
        			cost = 0;
    				$("#pick-kerrybulk").hide();
    			@endif
    			$("#pickup_form input[name='state']").val("");
    			$("#pickup_form input[name='state']").attr("disabled",false);
    		}else if(type == 'Pickup_ByFlash'){
    			$("#address_section").show();
    			$("#fspickup").hide();
    			@if(isset($rates['Pickup_ByFlash']))
    				cost = {{ $rates['Pickup_ByFlash']['AccountRate'] }};
    			@else
        			cost = 0;
    				$("#pick-flash").hide();
    			@endif
    			$("#pickup_form input[name='state']").val("");
    			$("#pickup_form input[name='state']").attr("disabled",false);
    		}else if(type == 'Pickup_AtHomeStandard'){
    			$("#address_section").show();
    			//$("#fspickup").show();
    			$("#fspickup").hide();
    			if(total_rate > 2000){
    				cost = 0;
    			}else{
    				cost = 200;
    			}
    			$("#pickup_form input[name='state']").val("กรุงเทพมหานคร");
    			$("#pickup_form input[name='state']").attr("disabled",true);
    		}else if(type == 'Pickup_AtHomeExpress'){
    			$("#address_section").show();
    			//$("#fspickup").show();
    			$("#fspickup").hide();
    			cost = 350;
    			$("#pickup_form input[name='state']").val("กรุงเทพมหานคร");
    			$("#pickup_form input[name='state']").attr("disabled",true);
    		}else if(type == 'Drop_AtFastship'){
    			$("#address_section").hide();
    			$("#fspickup").hide();
    			$("#pickup_form input[name='state']").val("");
    			$("#pickup_form input[name='state']").attr("disabled",false);
    		}else if(type == 'Drop_AtThaiPost'){
    			$("#address_section").show();
    			$("#fspickup").hide();
    			@if(isset($rates['Drop_AtThaiPost']))
    				cost = {{ $rates['Drop_AtThaiPost']['AccountRate'] }};
    			@else
        			cost = 0;
    				$("#drop-thaipost").hide();
    			@endif
    			$("#pickup_form input[name='state']").val("");
    			$("#pickup_form input[name='state']").attr("disabled",false);
    		}else if(type == 'Drop_AtThaiPostBulk'){
    			$("#address_section").show();
    			$("#fspickup").hide();
    			@if(isset($rates['Drop_AtThaiPostBulk']))
    				cost = {{ $rates['Drop_AtThaiPostBulk']['AccountRate'] }};
    			@else
        			cost = 0;
    				$("#drop-thaipostbulk").hide();
    			@endif
    			$("#pickup_form input[name='state']").val("");
    			$("#pickup_form input[name='state']").attr("disabled",false);
    		}

    		$("#cost").html(cost);
        	$("#totalpickup").html((Math.max(0,parseInt(total_rate)+discount+cost)).format());

        	getPickupDate()
        	

    	}

        function acceptTerm(){
    		if($("#condition").is(":checked")){

    			if($("#condition2").is(":checked")){
    				$("#submit").attr("disabled",false);
    			}else{
    				$("#submit").attr("disabled",true);
    			}
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

        		$.post("{{url ('pickup/get_coupon')}}",
        		{
        			_token: $("[name=_token]").val(),
        			code: $("#coupon_code").val(),
        			total: $("#totalpickup").text(),
        			payment: $("input[name='payment_method']:checked").val(),
        			sources: "{{ implode(';',$sources) }}",
        			agents: "{{ implode(';',$agents) }}",
        		},function(data){

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
        	@if($discount > 0)
			$("#pickupdiscount").show();
			@else
			$("#pickupdiscount").hide();
            @endif
        }

        $(document).ready( function() {

            $( ".selector" ).checkboxradio({
                classes: {
                    "ui-checkboxradio": "highlight"
                }
            });

            showDiscount();

            $("#submit").attr("disabled",true);

            $('#pick-kerry').attr('checked', true).change();
            
            $("#pickup_form").on("submit",function(){

            	var valid = true;

            	var error = "";
            	var address_select = $("#pickup_form input[name='address_select']:checked").val();
            	var agent = $("#pickup_form input[name='agent']:checked").val();
            	var payment = $("#pickup_form input[name='payment_method']:checked").val();
            	var amount = $("#totalpickup").text();

            	if(payment.startsWith("Credit_Card")){
					if(!confirm("ยืนยันการตัดบัตรเครดิต ยอดชำระ " + amount + " บาท")){
						return false;
					}
            	}
            	if(agent != "Drop_AtFastship"){

            		if($("#pickup_form input[name='payment_method']").val() == ""){
                    	$("#pickup_form input[name='payment_method']").css("border","1px solid red");
                    	error += "- {!! FT::translate('label.payment_method') !!}<br />";
                    	valid = false;
                    }else{
                    	$("#pickup_form input[name='payment_method']").css("border","1px solid #ffffff");
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
                $("#submit").attr("disabled",true);

            });
            
        });

        Number.prototype.format = function(n, x) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
            return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
        };

        @if(session('msg-type') && session('msg-type') == "shipment-success")
            fbq('track', 'AddToCart', {
            	value: 1,
            	currency: 'THB',
            });
        @endif
        
	</script>
	
@else

	<div class="row">
		<div class="col-md-7 pad8"><h2>{!! FT::translate('create_pickup.heading') !!}</h2></div>
    </div>
	<div class="text-center" style="padding-top: 30px;">
		<h4>{!! FT::translate('create_pickup.warning.noshipment') !!}</h4>
		<a href="calculate_shipment_rate" class="btn btn-lg btn-primary">{!! FT::translate('button.create_shipment') !!}</a>
	</div>
	
	<div class="row">      
    	<div class="col-md-8 col-md-offset-2 col-xs-12" style="margin-top: 50px;">
        	<div class="panel panel-danger">
            	<div class="panel-heading">ใบรับพัสดุที่รอชำระ</div>
            	<div class="panel-body">
                	<table class="table table-hover table-striped">
                    <thead>
                    	<tr>
                    		<td>{!! FT::translate('label.pickup_id') !!}</td>
                    		<td>{!! FT::translate('label.create_date') !!}</td>
                    		<td>วิธีการเข้ารับ</td>
                    		<td>{!! FT::translate('label.grand_total') !!}</td>
                    		<td>{!! FT::translate('label.number_shipment') !!}</td>
                    		<td></td>
                    	</tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(is_array($unpaidPickups) && sizeof($unpaidPickups) > 0):
                    foreach($unpaidPickups as $pickup):
                    ?>
                    	<tr>
                    		<td><a href="/pickup_detail/{{ $pickup['ID'] }}" target="_blank">{{ $pickup['ID'] }}</a></td>
                    		<td>{{ date("d/m/Y",strtotime($pickup['CreateDate']['date'])) }}</td>
                    		<td>{{ $pickupType[$pickup['PickupType']] }}</td>
                    		<td>{{ number_format($pickup['Amount']) }}</td>
                    		<td>{{ $pickup['TotalShipment'] }}</td>
                    		<td><a href="{{ url('/pickup_detail_payment/'.$pickup['ID'])}}"><button type="button" class="btn btn-info btn-sm">ชำระเงิน</button></a></td>
                    	</tr>
                    <?php 
                    endforeach;
                    else:
                    ?>
                    <tr><td colspan="6" class="text-center">{!! FT::translate('error.shipment.notfound') !!}</td></tr>
                    <?php
                    endif;
                    ?>
                    </tbody>
                    </table>
                </div>
        	</div>
    	</div>
    </div>
	
@endif
</div>
@endsection