@extends('layout')
@section('content')
<?php 
//alert($pickup_data); 
if($pickup_data['Status'] == "New"){
	$PickupStatus = FT::translate('status.pickup.status1');
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Pickup"){
    $PickupStatus = FT::translate('status.pickup.status2');
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Received"){
    $PickupStatus = FT::translate('status.pickup.status3');
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("active","timeline-event-success","fa-check");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Unpaid"){
    $PickupStatus = FT::translate('status.pickup.status5');
    $step1 = array("active","timeline-event-success","fa-check");
    $step2 = array("active","timeline-event-success","fa-check");
    $step3 = array("active","timeline-event-success","fa-check");
    $step4 = array("","opacity-4","fa-ellipsis-h");
    $step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Paid"){
    $PickupStatus = FT::translate('status.pickup.status4');
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("active","timeline-event-success","fa-check");
	$step4 = array("active","timeline-event-success","fa-check");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Sent"){
    $PickupStatus = FT::translate('status.pickup.status6');
    $step1 = array("active","timeline-event-success","fa-check");
    $step2 = array("active","timeline-event-success","fa-check");
    $step3 = array("active","timeline-event-success","fa-check");
    $step4 = array("active","timeline-event-success","fa-check");
    $step5 = array("active","timeline-event-success","fa-check");
}else if($pickup_data['Status'] == "Cancelled"){
    $PickupStatus = FT::translate('status.pickup.status100');
	$step1 = array("","opacity-4","fa-ellipsis-h");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else{
	$PickupStatus = $pickup_data['Status'];
	$step1 = array("","opacity-4","fa-ellipsis-h");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}
$shipmentStatus = array(
    'Pending' => FT::translate('status.shipment.status1'),
    'Created' => FT::translate('status.shipment.status2'),
    'Imported' => FT::translate('status.shipment.status3'),
    'Cancelled' => FT::translate('status.shipment.status5'),
    'ReadyToShip' => FT::translate('status.shipment.status17'),
    'Sent' => FT::translate('status.shipment.status26'),
    'PreTransit' => FT::translate('status.shipment.status1001'),
    'InTransit' => FT::translate('status.shipment.status1002'),
    'OutForDelivery' => FT::translate('status.shipment.status1003'),
    'Delivered' => FT::translate('status.shipment.status1004'),
    'Return' => FT::translate('status.shipment.status1005'),
    'Onhold' => FT::translate('status.shipment.status1006'),
);
$isSeperateLabel = ($pickup_data['PickupType'] == "Drop_AtThaiPost" || $pickup_data['PickupType'] == "Pickup_AtKerry");

?>
    <div class="conter-wrapper">	
		<div class="row">
	        <div class="col-md-12"><h2>{!! FT::translate('pickup_detail.heading') !!}</h2></div>	
	    </div>
        <div class="row">
        	<div class="col-md-7">
        		<div class="panel panel-primary">
                	<div class="panel-heading">{!! FT::translate('pickup_detail.panel.heading1') !!} <?php echo $pickupID; ?></div>
                    <div class="panel-body">
                        
	                        <div class=" well" style="margin-bottom:0px;">
								<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.create_date') !!} : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo date("d/m/Y H:i:s",strtotime($pickup_data['CreateDate']['date']));?></div>
								<div class="clearfix"></div>
							
								<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.pickup_type') !!} : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo (isset($pickupType[$pickup_data['PickupType']]))?$pickupType[$pickup_data['PickupType']]:$pickup_data['PickupType']; ?></div>
								<div class="clearfix"></div>
							
								<?php if($pickup_data['PickupType'] == "Pickup_AtHome"): ?>
								
									<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.pickup_date') !!} : </div>
									<div class="col-xs-6 col-md-8 text-left"><?php echo date("d/m/Y H:i:s",strtotime($pickup_data['ScheduleDate']));?></div>
									<div class="clearfix"></div>
									
									<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.contact') !!} : </div>
									<div class="col-xs-6 col-md-8 text-left">
									<?php echo $pickup_data['PickupAddress']['Firstname'];?> <?php echo $pickup_data['PickupAddress']['Lastname'];?>
									</div>
									<div class="clearfix"></div>
									
									<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.telephone') !!} : </div>
									<div class="col-xs-6 col-md-8 text-left">
									<?php echo $pickup_data['PickupAddress']['PhoneNumber'];?>
									</div>
									<div class="clearfix"></div>
									
									<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.address1') !!} : </div>
									<div class="col-xs-6 col-md-8 text-left">
									<?php echo $pickup_data['PickupAddress']['AddressLine1'];?>
									<?php echo $pickup_data['PickupAddress']['AddressLine2'];?>
									<?php echo $pickup_data['PickupAddress']['City'];?>
									<?php echo $pickup_data['PickupAddress']['State'];?>
									<?php echo $pickup_data['PickupAddress']['Postcode'];?>
									Thailand
									</div>
									<div class="clearfix"></div>
			
								<?php endif; ?>
							
								<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.payment_method') !!} : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo $paymentMethod[$pickup_data['PaymentMethod']];?></div>
								<div class="clearfix"></div>
							
								<?php if(isset($pickup_data['Remark']) && $pickup_data['Remark']): ?>
								<div class="col-xs-6 col-md-4 text-right clearfix">{!! FT::translate('label.remark') !!} : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo $pickup_data['Remark'];?></div>
								<div class="clearfix"></div>
								<?php endif; ?>
							
			                </div>

							
                            <h3 style="padding-top: 30px;">{!! FT::translate('pickup_detail.panel.heading2') !!}</h3>
                            <table class="table table-stripe table-hover">
                            <thead>
                            <tr>
                            	<td>{!! FT::translate('label.shipment_id') !!}</td>
                            	<td class="hidden-xs">{!! FT::translate('label.receiver') !!}</td>
                            	<td class="hidden-xs">{!! FT::translate('label.destination') !!}</td>
                            	<td>{!! FT::translate('label.status') !!}</td>
                            	<td>{!! FT::translate('label.print_label') !!}</td>
                            	<td>{!! FT::translate('label.copy') !!}</td>
                            </tr>
                            </thead>
                            <tbody>
                            
                           
                            <?php 
                            if(sizeof($pickup_data['ShipmentDetail']['ShipmentIds']) > 0): 
                            foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $data):
                                
                            ?>
                            <tr>
                            	<td>
                                	<a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><i class="fa fa-search"></i></a>
                                	<a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a>
                            	</td>
                            	<?php if($data['ReceiverDetail']['Firstname'] != ""): ?>
                            	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                            	<?php else: ?>
                            	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Custname'];?></td>
                            	<?php endif; ?>
                            	<td class="hidden-xs"><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                            	<td><?php echo isset($shipmentStatus[$data['Status']])?$shipmentStatus[$data['Status']]:$data['Status']; ?></td>
                            	<?php 
                            	if($isSeperateLabel && isset($data) && isset($labels[$data['ID']]) && isset($labels[$data['ID']]['barcode'])): 
                            	   $barcodeURL = "https://app.fastship.co/thaipost/label/" . $labels[$data['ID']]['barcode'];
                            	?>
                            	<td class="small" >
                                	<a href="<?php echo $barcodeURL; ?>" target="_blank"><i class="fa fa-print"></i></a> 
                                	<a href="<?php echo $barcodeURL; ?>" target="_blank" style="font-weight: 100;">{!! FT::translate('pickup_detail.print_label') !!}</a>
                            	</td>
                            	<?php else: ?>
                            	<td class="small" ></td>
                            	<?php endif; ?>
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
			                <div class="row text-center">
			                    <div class="col-md-12"><h4>{!! FT::translate('pickup_detail.pickup_total') !!} <span class="orange"><?php echo number_format($pickup_data['Amount'],0); ?></span> {!! FT::translate('unit.baht') !!}</h4></div>
			                    <?php if ($pickup_data['Status'] != 'Pending') { ?>
									<a href="/pickup_detail_print/<?php echo $pickupID; ?>" target="_blank"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">{!! FT::translate('pickup_detail.button.print_pickup') !!}</button></a>
									<a href="/pickup_invoice_print/<?php echo $pickupID; ?>" target="_blank"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">{!! FT::translate('pickup_detail.button.print_invoice') !!}</button></a>
								<?php }?>
			                </div>
			                <div class="clearfix"></div><br />
			                
			                <?php 
			                if($isSeperateLabel): 
    			                if($pickup_data['PickupType'] == "Drop_AtThaiPost") $droppointName = "{!! FT::translate('radio.dropoff.thaipost') !!}";
    			                else if($pickup_data['PickupType'] == "Pickup_AtKerry") $droppointName = "Kerry";
    			                else $droppointName = "{!! FT::translate('radio.pickup.droppoint') !!}";
			                ?>
			                <p class="text-center">{!! FT::translate('pickup_detail.dropoff.text1') !!} <strong><?php echo $droppointName; ?></strong> {!! FT::translate('pickup_detail.dropoff.text2') !!}</p>
			                <?php endif; ?>

                        </div>
                </div>
        	</div>
            <div class="col-md-5"> 
            	<div class="panel panel-primary">
                	<div class="panel-body">
                		<h2>{!! FT::translate('label.status') !!}: <?php echo $PickupStatus; ?></h2>
                		<h3>{!! FT::translate('label.pickup_id') !!} : <span style="color: #f15a22;"><?php echo $pickupID; ?></span></h3>
                		<?php if ($pickup_data['Status'] == 'Pending') { ?>
                			<div class="timeline timeline-single-column">
                				<div class="timeline-item">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa"></i>
		                            </div>
		                            <div class="timeline-event upgrade">
		                                <div class="timeline-heading">
		                                    <h4>รอชำระเงิน</h4>
		                                </div>
		                                <div class="timeline-body">
		                                    <a href="{{ url('/pickup_detail_payment/'.$pickupID)}}" >กดไปหน้าชำระเงิน</a>
		                                </div>
		                                <!--<div class="timeline-body">
		                                <?php if($pickup_data['PickupType'] == "Pickup_AtHome"):?>
		                                    <p>{!! FT::translate('pickup_detail.track.step1_1') !!}</p>
		                                <?php else: ?>
		                                	<p>{!! FT::translate('pickup_detail.track.step1_2') !!}</p>
		                                <?php endif;?>
		                                </div>-->
		                                <div class="timeline-footer"></div>
		                            </div>
		                        </div>
                			</div>
                		<?php }else{ ?>
		                    <div class="timeline timeline-single-column">
		                    	<div class="timeline-item <?php echo $step1[0]; ?>">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa <?php echo $step1[2]; ?>"></i>
		                            </div>
		                            <div class="timeline-event upgrade <?php echo $step1[1]; ?>">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step1') !!} <?php echo date("d/m/Y H:i:s",strtotime($pickup_data['CreateDate']['date']));?></h4>
		                                </div>
		                                <div class="timeline-body">
		                                <?php if($pickup_data['PickupType'] == "Pickup_AtHome"):?>
		                                    <p>{!! FT::translate('pickup_detail.track.step1_1') !!}</p>
		                                <?php else: ?>
		                                	<p>{!! FT::translate('pickup_detail.track.step1_2') !!}</p>
		                                <?php endif;?>
		                                </div>
		                                <div class="timeline-footer">
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        <?php if($pickup_data['PickupType'] == "Pickup_AtHome"):?>
		                        <div class="timeline-item <?php echo $step2[0]; ?>">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa <?php echo $step2[2]; ?>"></i>
		                            </div>
		                            <div class="timeline-event upgrade <?php echo $step2[1]; ?>">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step2') !!} <?php echo date("d/m/Y H:i:s",strtotime($pickup_data['ScheduleDate']));?></h4>
		                                    
		                                </div>
		                                <div class="timeline-body">
		                                	<p><?php echo $pickup_data['PickupAddress']['Firstname'];?></p>
		                                    <p><?php echo $pickup_data['PickupAddress']['AddressLine1'];?>
							                	<?php echo $pickup_data['PickupAddress']['AddressLine2'];?>
							                	<?php echo $pickup_data['PickupAddress']['City'];?>
							                	<?php echo $pickup_data['PickupAddress']['State'];?>
							                	<?php echo $pickup_data['PickupAddress']['Postcode'];?>
							                	Thailand</p>
		                                    <p class="text-right"></p>
		                                </div>
		                                <div class="timeline-footer">
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        <?php endif; ?>
		                        
		                        <div class="timeline-item <?php echo $step3[0]; ?>">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa <?php echo $step3[2]; ?>"></i>
		                            </div>
		                            <div class="timeline-event upgrade <?php echo $step3[1]; ?>">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step3') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                    <p>{!! FT::translate('pickup_detail.track.step3_1') !!}</p>
		                                </div>
		                                <div class="timeline-footer">
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        
		                        <div class="timeline-item <?php echo $step4[0]; ?>">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa <?php echo $step4[2]; ?>"></i>
		                            </div>
		                            <div class="timeline-event upgrade <?php echo $step4[1]; ?>">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step4') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                    <p><?php echo $paymentMethod[$pickup_data['PaymentMethod']]; ?> {!! FT::translate('pickup_detail.track.step4_1') !!} <?php echo number_format($pickup_data['Amount'],0); ?> {!! FT::translate('unit.baht') !!}</p>
		                                </div>
		                                <div class="timeline-footer">
		
		                                </div>
		                            </div>
		                        </div>
		                        
		                        <div class="timeline-item <?php echo $step5[0]; ?>">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa <?php echo $step5[2]; ?>"></i>
		                            </div>
		                            <div class="timeline-event upgrade <?php echo $step5[1]; ?>">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step5') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                    <p></p>
		                                </div>
		                                <div class="timeline-footer">
		
		                                </div>
		                            </div>
		                        </div>
		                    </div>
	                    <?php }?>
                    </div>
                </div>
                
                <?php if($pickup_data['Status'] == "New" ): ?>
			    <form id="pickup_form" class="form-horizontal" method="post">
				    {{ csrf_field() }}
				</form>
			    <div class="row">
			    	<div class="col-md-12 text-center">
			    		<a href="javascript:cancelPickup(<?php echo $pickupID;?>);"><i class="fa fa-trash-o"></i> {!! FT::translate('pickup_detail.cancel_link') !!}</a>
			    	</div>
			    </div>
			    <?php endif; ?>

            </div>
        </div>
        <div class="clearfix"></div>
		<br />
			    
    </div>

<script>

    function cancelPickup(pick_id){

    	if(confirm("{!! FT::translate('confirm.delete_pickup') !!}")){
    		$.post('{{url ('pickup/cancel')}}',
    	    {
    	    	_token: $("[name=_token]").val(),
    	        pickupId: pick_id
    	    },function(data){
    		    window.location.href = '/pickup_list';
    		},"json");
        }
                
    }

    @if(session('msg-type') && session('msg-type') == "success")
    fbq('track', 'Purchase', {
    	value: 200,
    	currency: 'THB',
    });
    @endif
    
</script>
 
@endsection