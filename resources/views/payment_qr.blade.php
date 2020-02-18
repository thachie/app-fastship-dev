@extends('layout')
@section('content')
<?php //alert($pickup); ?>
<div class="conter-wrapper">
	
	<div class="row text-center">
		<div class="col-12 col-md-3 col-md-offset-3 text-md-right text-xs-center">
	    	<h2>ยอดชำระ <span class="orange"><?php echo number_format($pickup['Amount'],0); ?></span> {!! FT::translate('unit.baht') !!}</h2>
		</div>
		<div class="col-12 col-md-3 text-md-left text-xs-center">
			<form method="POST" action="https://app.fastship.co/kbank/payment_completed">
                <script type="text/javascript" src="https://kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                data-apikey="pkey_prod_321btQojbQkYbi9bjSTHRpt0T76CxYrHrkw"
                data-amount="{{ $pickup['Amount'] }}"
                data-currency="THB"
                data-payment-methods="qr"
                data-name="Fastship Co., Ltd."
                data-order-id="{{ $kbankOrderId }}"
                data-description="{{ 'Pickup # ' . $pickup['ID'] . ' - Pickup by ' . $pickup['PickupType'] }}"
                data-show-button="false" ></script>
                <input type="button" class="btn btn-success btn-lg" style="padding: 10px 100px;" role="button" value="Pay Now" onclick="KPayment.show()">
            </form>
		</div>
	</div>
		        	<div class="clearfix"></div>
		        	
	<div class="row">
    	<div class="col-md-10 col-md-offset-1">
    		<div class="panel panel-primary">
            	<div class="panel-heading">{!! FT::translate('pickup_detail.panel.heading2') !!} ของใบรับ <?php echo $pickup['ID']; ?></div>
                <div class="panel-body">

				<table class="table table-stripe table-hover">
                    <thead>
                    <tr>
                    	<td>{!! FT::translate('label.shipment_id') !!}</td>
                    	<td class="hidden-xs">{!! FT::translate('label.receiver') !!}</td>
                    	<td>{!! FT::translate('label.destination') !!}</td>
                    	<td>{!! FT::translate('label.agent') !!}</td>
                    	<td>{!! FT::translate('label.shipping') !!}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(sizeof($pickup['ShipmentDetail']['ShipmentIds']) > 0): 
                    foreach($pickup['ShipmentDetail']['ShipmentIds'] as $data):
                    ?>
                    <tr>
                    	<td><?php echo $data['ID'];?></td>
                    	<?php if($data['ReceiverDetail']['Firstname'] != ""): ?>
                    	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                    	<?php else: ?>
                    	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Custname'];?></td>
                    	<?php endif; ?>
                    	<td><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                    	<td><img src="{{ url('/images/agent/' . $data['ShipmentDetail']['ShippingAgent'] . '.gif') }}" title="{{ $data['ShipmentDetail']['ShippingAgent'] }}" style="max-height: 50px;" /></td>
                    	<td><?php echo $data['ShipmentDetail']['ShippingRate'];?></td>
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
    </div>
    <div class="clearfix"></div>

</div>
<script type="text/javascript">
var merchantFunction = function(){ // Add notify action.
    console.log("Close popup!");
    alert('ยังไม่ได้ทำรายการหรือทำรายการไม่สำเร็จ');
};
KPayment.onClose(merchantFunction);
</script>
@endsection