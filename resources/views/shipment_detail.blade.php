@extends('layout')
@section('content')
<?php 

    $DeclareTypes = explode(";",$ShipmentDetail['ShipmentDetail']['DeclareType']);
    if($DeclareTypes [sizeof($DeclareTypes)-1] == ""){
        unset($DeclareTypes [sizeof($DeclareTypes)-1]);
    }
    $DeclareQtys = explode(";",$ShipmentDetail['ShipmentDetail']['DeclareQty']);
    if($DeclareQtys [sizeof($DeclareQtys)-1] == ""){
    	unset($DeclareQtys [sizeof($DeclareQtys)-1]);
    }
    $DeclareValues = explode(";",$ShipmentDetail['ShipmentDetail']['DeclareValue']);
    if($DeclareValues [sizeof($DeclareValues)-1] == ""){
        unset($DeclareValues [sizeof($DeclareValues)-1]);
    }
    
    if($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported"){
    	$ShipmentStatus = FT::translate('status.shipment.status1');
    	$stepStatus1 = "active";
    	$stepStatus2 = "disabled";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "Created"){
    	$ShipmentStatus = FT::translate('status.shipment.status2');
    	$stepStatus1 = "complete";
    	$stepStatus2 = "active";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "ReadyToShip"){
    	$ShipmentStatus = FT::translate('status.shipment.status17');
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "active";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "Sent"){
    	$ShipmentStatus = FT::translate('status.shipment.status26');
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "complete";
    	$stepStatus4 = "active";
    }else if($ShipmentDetail['Status'] == "Cancelled"){
    	$ShipmentStatus = FT::translate('status.shipment.status5');
    	$stepStatus1 = "disabled";
    	$stepStatus2 = "disabled";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else{
    	$ShipmentStatus = $ShipmentDetail['Status'];
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "complete";
    	$stepStatus4 = "active";
    }
?>
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-12"><h2>{!! FT::translate('shipment_detail.heading') !!}: <?php echo $ShipmentDetail['ID'];?></h2></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">{!! FT::translate('shipment_detail.panel.heading1') !!}</div>
                <div class="panel-body ship-detail">
                    <div class=" well" style="margin-bottom:0px;">
                	<div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.fullname') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['Firstname'];?> <?php echo $ShipmentDetail['ReceiverDetail']['Lastname'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.telephone') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['PhoneNumber'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.email') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['Email'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.address') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['AddressLine1'];?><br /><?php echo $ShipmentDetail['ReceiverDetail']['AddressLine2'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.city') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['City'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.state') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['State'];?></div>
                    <div class="clearfix"></div> 
                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.postcode') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['Postcode'];?></div>
                    <div class="clearfix"></div>  
                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.country') !!}: </div>
                    <div class="col-md-7 col-xs-7"><?php echo $countries[$ShipmentDetail['ReceiverDetail']['Country']];?></div>
                    <div class="clearfix"></div>   
                    <?php if($ShipmentDetail['Remark'] != ""): ?>
	                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.remark') !!}: </div>
	                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['Remark'];?></div>
	                    <div class="clearfix"></div>
                    <?php endif; ?>
                    <?php if($ShipmentDetail['Reference'] != ""): ?>
	                    <div class="col-md-5 col-xs-5 text-right">{!! FT::translate('label.reference') !!}: </div>
	                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['Reference'];?></div>
	                    <div class="clearfix"></div>
                    <?php endif; ?>
                    </div>
                	<br />
                	
                	<h3>{!! FT::translate('shipment_detail.panel.heading2') !!}</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{!! FT::translate('label.declare_type') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_qty') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_value') !!}</th>
                            </tr>
                        </thead>
                        <tbody id="product_table">
                            <?php 
                            if(sizeof($DeclareTypes) > 0):
                            	if(is_array($DeclareTypes)):
                                    foreach($DeclareTypes as $key => $Type):
                                    	$dtype = (isset($declareTypes[$Type]))?$declareTypes[$Type]:$Type;
                            
                            ?>
                                    <tr>
                                        <td><?php echo $dtype; ?></td>
                                        <td><?php echo isset($DeclareQtys[$key])?($DeclareQtys[$key]):"1"; ?></td>
                                        <td><?php echo isset($DeclareValues[$key])?($DeclareValues[$key]):"-"; ?></td>
                                    </tr>
                            <?php 	
                            		endforeach;
                           		endif; 
                           	endif;
                           	?>
                            
                        </tbody>
                    </table>

                    <div class="col-md-4 col-xs-4 text-center no-padding"> 
                        <img src="../images/agent/<?php echo $ShipmentDetail['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width: 100px;"/>
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
                                <td><span class="sumresult"><?php echo number_format($ShipmentDetail['ShipmentDetail']['Weight'],0);?></span></td>
                                <td class="hidden-xs">
                                    <?php if($ShipmentDetail['ShipmentDetail']['Width'] != ""): ?>
                                    <span class="sumresult"><?php echo $ShipmentDetail['ShipmentDetail']['Width']." × ".$ShipmentDetail['ShipmentDetail']['Length']." × ".$ShipmentDetail['ShipmentDetail']['Height']; ?></span>
                                    <?php else: ?>
                                    <span class="sumresult">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                <?php if($ShipmentDetail['ShipmentDetail']['ShippingAgent'] == "Quotation"): ?>
                                	<span class="sumresult">TBC</span>
                                <?php else: ?>
                                	<span class="sumresult"><?php echo number_format($ShipmentDetail['ShipmentDetail']['ShippingRate'],0);?></span>
                                <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
        	<div class="panel panel-primary">
                <div class="panel-body">
                	<h2>{!! FT::translate('label.status') !!}: <?php echo $ShipmentStatus; ?></h2>
                	
                	<div class="bs-wizard dot-no-padding" style="border-bottom:0;">
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus1; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status1') !!}</p>
	                    </div> 
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus2; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status2') !!}</p>
	                    </div>
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus3; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status17') !!}</p>
	                    </div>    
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus4; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status26') !!}</p>
	                    </div>     
                	</div>
                	<div class="clearfix"></div><br />
                	
                	
                	
                    <?php if( !($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported" || $ShipmentDetail['Status'] == "Quotation" || $ShipmentDetail['Status'] == "Created")): ?>
                    	<h2>{!! FT::translate('shipment_detail.panel.heading3') !!}</h2>
                        <h4>{!! FT::translate('label.tracking') !!} <span class="orange">{{ $ShipmentDetail['ShipmentDetail']['Tracking'] }}</span></h4>
                        <div class="timeline timeline-single-column">
                            <?php 
                            if(isset($tracking_data['Events']) && sizeof($tracking_data['Events'])>0):
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
                                    
                                        <p><?php echo (isset($trackingStatus[$event['Status']]))?$trackingStatus[$event['Status']]:$event['Status']; ?> <?php echo ($event['Location'])?"at ".$event['Location']:""; ?></p>
            
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
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php if($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported"  || $ShipmentDetail['Status'] == "Quotation"): ?>
        <form id="delete_form" class="form-horizontal" method="post" action="{{url ('shipment/cancel')}}">
		    {{ csrf_field() }}
		    <input type="hidden" name="shipmentId" value="<?php echo $ShipmentDetail['ID'];?>" />
		</form>
	    <div class="col-md-12 text-center">
	    	<a href="javascript:cancelShipment(<?php echo $ShipmentDetail['ID'];?>);"><i class="fa fa-trash-o"></i> {!! FT::translate('shipment_detail.cancel_link') !!}</a>
	    </div>
	    <?php endif; ?>
	    <div class="clearfix"></div>
    	<br />
    
    </div>
    <div class="clearfix"></div>
    <br />
    
    
    
    
    
</div>

<script type="text/javascript">
function cancelShipment(shipment_id){

	if(confirm("{!! FT::translate('confirm.delete_shipment') !!}")){
		$("#delete_form").submit();
    }
            
}
</script>

@endsection