@extends('layout')
@section('content')
<div class="conter-wrapper">
      
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h2>{!! FT::translate('tracking.heading') !!}</h2>
	</div>
</div>  
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-primary">
			<div class="panel-heading">{!! FT::translate('tracking.panel.heading1') !!}</div>
			<div class="panel-body">
				<div class="row">
					<div class="hidden-xs col-md-4 text-right"><strong>{!! FT::translate('label.tracking') !!}</strong></div>
					<div class="visible-xs col-xs-12"><label><strong>{!! FT::translate('label.tracking') !!}</strong></label></div>
					<div class="col-xs-9 col-md-6"><input type="text" name="tracking" class="form-control required" required value="<?php echo isset($tracking_data['TrackingCode'])?$tracking_data['TrackingCode']:"";?>" /></div>
					<div class="col-xs-3 col-md-2"><button type="button" class="btn btn-primary " onclick="trackShipment()" style="padding: 9px 10px;"><i class="fa fa-search"></i></button></div>		                                
				</div>	
				<div class="row text-center" style="margin-top: 40px;">
					<h5>{!! FT::translate('tracking.panel.content') !!}</h5>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://www.ups.com/tracking/tracking.html" target="_blank"><img src="{{ url('images/agent/FS.gif') }}" style="max-width: 100%;"/></a><span class="small">FS Express</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://www.asendia.com/tracking/" target="_blank"><img src="{{ url('images/agent/FS_Standard.gif') }}" style="max-width: 100%;" /></a><span class="small">FS Non-registered</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://www.asendia.com/tracking/" target="_blank"><img src="{{ url('images/agent/FS_Epacket.gif') }}" style="max-width: 100%;" /></a><span class="small">FS E-Packet</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://www.ups.com/tracking/tracking.html" target="_blank"><img src="{{ url('images/agent/UPS.gif') }}" style="max-width: 100%;" /></a><span class="small">UPS Express</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://www.aramex.com/track/shipments" target="_blank"><img src="{{ url('images/agent/Aramex.gif') }}" style="max-width: 100%;" /></a><span class="small">Aramex</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="http://www.sf-express.com/cn/en/dynamic_function/waybill/" target="_blank"><img src="{{ url('images/agent/SF.gif') }}" style="max-width: 100%;" /></a><span class="small">SF Express</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://ecommerceportal.dhl.com/track/" target="_blank"><img src="{{ url('images/agent/GM_Packet_Plus.gif') }}" style="max-width: 100%;" /></a><span class="small">GM Registered</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://ecommerceportal.dhl.com/track/" target="_blank"><img src="{{ url('images/agent/Ecom_PD.gif') }}" style="max-width: 100%;" /></a><span class="small">Parcel Direct</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://tools.usps.com/go/TrackConfirmAction_input" target="_blank"><img src="{{ url('images/agent/USPS.gif') }}" style="max-width: 100%;" /></a><span class="small">USPS</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://www.fedex.com/en-us/tracking.html" target="_blank"><img src="{{ url('images/agent/FedEx_SmartPost.gif') }}" style="max-width: 100%;" /></a><span class="small">FedEx</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://track.thailandpost.com" target="_blank"><img src="{{ url('images/agent/Thaipost_Epacket.gif') }}" style="max-width: 100%;" /></a><span class="small">Thaipost Epacket</span></div>
					<div class="col-xs-6 col-md-3" style="margin-bottom:10px;"><a href="https://track.thailandpost.com" target="_blank"><img src="{{ url('images/agent/Thaipost_Ems.gif') }}" style="max-width: 100%;" /></a><span class="small">EMS World</span></div>
				</div>
			</div>
		</div>
	</div>
</div>
@if(!empty($tracking_data) && isset($shipment_data))
<?php 
$DeclareTypes = explode(";",$shipment_data['ShipmentDetail']['DeclareType']);
if($DeclareTypes [sizeof($DeclareTypes)-1] == ""){
	unset($DeclareTypes [sizeof($DeclareTypes)-1]);
}
$DeclareValues = explode(";",$shipment_data['ShipmentDetail']['DeclareValue']);
if($DeclareValues [sizeof($DeclareValues)-1] == ""){
	unset($DeclareValues [sizeof($DeclareValues)-1]);
}
?>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">{!! FT::translate('tracking.panel.heading2') !!}</div>
		    <div class="panel-body">
		    	<div class="row">
			    	<div class="col-md-3"><img src="/images/agent/<?php echo $shipment_data['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width:100px;" /></div>
			        <div class="col-md-9">
			        	<h4 class="green">Tracking: <strong><?php echo $tracking_data['TrackingCode'];?></strong></h4>
			        	<h1><?php echo $trackingStatus[$tracking_data['Status']]; ?></h1>
			        </div>
		        </div>
		    </div>
		</div>
		<div class="panel panel-primary">
		    <div class="panel-heading">{!! FT::translate('tracking.panel.heading3') !!} <?php echo $shipment_data['ID']; ?></div>
		    <div class="panel-body">
				<table class="col-xs-12 col-md-8 col-md-offset-2 text-center">
                        <thead>
                            <tr>
                                <th scope="col">{!! FT::translate('label.declare_type') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_qty') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_value') !!}า</th>
                            </tr>
                        </thead>
                        <tbody id="product_table">
                            <?php 
                                if(sizeof($DeclareTypes) > 0){
                                    foreach($DeclareTypes as $key => $Type) { ?>
                                    <tr>
                                        <td><?php echo $Type; ?></td>
                                        <td>1</td>
                                        <td><?php echo $DeclareValues[$key]; ?></td>
                                    </tr>
                            <?php } 
                            } ?>
                            
                        </tbody>
                    </table>
                    
                    <table class="col-md-8 col-md-offset-2 text-center">
                	<thead>
	                <tr>
	                	<td>{!! FT::translate('label.weight') !!}</td>
	                    <td>{!! FT::translate('label.dimension') !!}</td>
	                    <td>{!! FT::translate('label.shipping') !!}</td>
	                </tr>
	                </thead>
	                <tbody>
	                <tr>
	                	<td><span class="sumresult"><?php echo number_format($shipment_data['ShipmentDetail']['Weight'],0);?></span></td>
                      	<td>
	                        <?php if($shipment_data['ShipmentDetail']['Width'] != ""): ?>
                        	<span class="sumresult"><?php echo $shipment_data['ShipmentDetail']['Width']."×".$shipment_data['ShipmentDetail']['Length']."×".$shipment_data['ShipmentDetail']['Height']; ?></span>
                        		<?php else: ?>
                        		<span class="sumresult">-</span>
                        		<?php endif; ?>
                        	</td>
                       <td><span class="sumresult"><?php echo number_format($shipment_data['ShipmentDetail']['ShippingRate'],0);?></span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
       </div>
       <div class="panel panel-primary">
		    <div class="panel-heading">{!! FT::translate('tracking.panel.heading4') !!}</div>
		    <div class="panel-body">  
               	
	                        
		        <div class="row">
		        	<div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.fullname') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['ReceiverDetail']['Firstname'];?> <?php echo $shipment_data['ReceiverDetail']['Lastname'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.telephone') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['ReceiverDetail']['PhoneNumber'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.email') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['ReceiverDetail']['Email'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.address') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['ReceiverDetail']['AddressLine1'];?><br /><?php echo $shipment_data['ReceiverDetail']['AddressLine2'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.city') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['ReceiverDetail']['City'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.state') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['ReceiverDetail']['State'];?></div>
                    <div class="clearfix"></div> 
                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.postcode') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['ReceiverDetail']['Postcode'];?></div>
                    <div class="clearfix"></div>  
                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.country') !!}: </div>
                    <div class="col-md-8 col-xs-7"><?php echo $countries[$shipment_data['ReceiverDetail']['Country']];?></div>
                    <div class="clearfix"></div>   
                    <?php if($shipment_data['Remark'] != ""): ?>
	                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.remark') !!}: </div>
	                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['Remark'];?></div>
	                    <div class="clearfix"></div>
                    <?php endif; ?>
                    <?php if($shipment_data['Reference'] != ""): ?>
	                    <div class="col-md-4 col-xs-5 text-right">{!! FT::translate('label.reference') !!}: </div>
	                    <div class="col-md-8 col-xs-7"><?php echo $shipment_data['Reference'];?></div>
	                    <div class="clearfix"></div>
                    <?php endif; ?>
			        
		        </div>
	        </div>
		</div>
	</div>
	<div class="col-md-6"> 
        <div class="panel panel-primary">
        	<div class="panel-heading">{!! FT::translate('tracking.panel.heading5') !!}</div>
            <div class="panel-body">
            	<div class="timeline timeline-single-column">
                    	<?php 
                    	if(sizeof($tracking_data['Events'])>0):
                    	$descEvents = $tracking_data['Events'];
                    	krsort($descEvents);
                    	foreach($descEvents as $event):
                    	if($event['Status'] == "delivered"){
                    		$css = "success";
                    	}else if($event['Status'] == "in_transit"){
                    		$css = "info";
                    	}else{
                    		$css = "warning";
                    	}
                    	?>
                    	<div class="timeline-item <?php echo $event['Status']; ?>">
                            <div class="timeline-point timeline-point-default">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="timeline-event upgrade timeline-event-<?php echo $css; ?>">
                                <div class="timeline-heading">
                                    <h4><?php echo $event['Description']; ?></h4>
                                </div>
                                <div class="timeline-body">
                                
                                	<p><?php echo $trackingStatus[$event['Status']]; ?> <?php echo ($event['Location'])?"at ".$event['Location']:""; ?></p>
        
                                </div>
                                <div class="timeline-footer text-right">
                                    <?php echo date("d/m/Y H:i:s",strtotime($event['Datetime'])); ?>
                                </div>
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
</div>
@else
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h4 class=" text-center text-danger">
		ไม่สามารถตรวจสอบได้ หรือคุณไม่ใช่เจ้าของพัสดุ
		</h4>
	</div>
</div>
@endif
</div>
<script type="text/javascript">
function trackShipment(){
	window.location.href = "/track/" + $("input[name=tracking]").val();
}
</script>
@endsection