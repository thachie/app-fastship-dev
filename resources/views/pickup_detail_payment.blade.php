@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">

    	<div class="col-md-6 col-md-offset-3" style="background: #fff;padding: 40px;margin-bottom: 40px;">
    		
    		<div class=" text-right">
				สร้าง {{ date("H:i น. วันที่ d/m/Y",strtotime($pickup_data['CreateDate']['date'])) }}
			</div>
			<div class="clearfix"></div>
		
        	<div class="row text-center">
        		<h2>ชำระเงิน ใบรับพัสดุ {{ $pickup_data['ID'] }}</h2>
				<div class="col-12">
			    	<h1>ยอดชำระ <span class="orange"><?php echo number_format($amount,0); ?></span> {!! FT::translate('unit.baht') !!}</h1>
				</div>
				<div class="col-12 col-md-8 col-md-offset-2">
					<form method="POST" action="https://app.fastship.co/pickup_detail/{{ $pickup_data['ID'] }}">
		                <script type="text/javascript" src="https://kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
		                data-apikey="pkey_prod_321btQojbQkYbi9bjSTHRpt0T76CxYrHrkw"
		                data-amount="{{ $amount }}"
		                data-currency="THB"
		                data-payment-methods="qr"
		                data-name="Fastship Co., Ltd."
                        data-order-id="{{ $kbankOrderId }}"
		                data-ref-number="{{ $reference }}"
		                data-description="{{ $pickup_data['ID'] }}"
		                data-show-button="false" ></script>
		                <input type="button" class="btn btn-success btn-lg btn-block" role="button" value="กดเพื่อชำระเงินผ่าน QR" onclick="KPayment.show()">
		            </form>
				</div>
				<div class="clearfix"></div>
				
				<!--<div class="col-md-12 text-center small">
		    		<a href="{{url ('/payment_submission')}}" target="_blank"><i class="fa fa-bank"></i> โอนผ่านธนาคาร</a>
		    	</div>
		    	<div class="clearfix"></div>-->

		    	<div class="col-md-10 col-md-offset-1" style="border: 1px solid #eee;padding: 20px;margin-top:20px;margin-bottom:20px;">
    		    	<table class="table table-condensed" style="margin-bottom: 0;">
    		    	<thead>
    		    		<tr>
    		    			<th class="text-left small" style="text-align: left;">สรุปค่าใช้จ่าย</th>
    		    			<th class="text-right small" style="text-align: right;">บาท</th>
    		    		</tr>
    		    	</thead>
    		    	<tbody>
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;">ค่าส่ง</td>
    		    			<td class="text-right text-info" style="text-align: right;">{{ $pickup_data['ShipmentDetail']['TotalShippingRate'] }}</td>
    		    		</tr>
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;">ค่าเข้ารับ ({{ (isset($pickupType[$pickup_data['PickupType']]))?$pickupType[$pickup_data['PickupType']]:$pickup_data['PickupType'] }})</td>
    		    			<td class="text-right text-info" style="text-align: right;">{{ $pickup_data['PickupCost'] }}</td>
    		    		</tr>
    		    		@if($pickup_data['PackingCost'] > 0)
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;">ค่าหีบห่อพัสดุ</td>
    		    			<td class="text-right text-info" style="text-align: right;">{{ $pickup_data['PackingCost'] }}</td>
    		    		</tr>
    		    		@endif
    		    		@if($pickup_data['Insurance'] > 0)
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;">ค่าประกันพัสดุ</td>
    		    			<td class="text-right text-info" style="text-align: right;">{{ $pickup_data['Insurance'] }}</td>
    		    		</tr>
    		    		@endif
    		    		@if($pickup_data['AdditionCost'] > 0)
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;">ค่าบริการเพิ่มเติม</td>
    		    			<td class="text-right text-info" style="text-align: right;">{{ $pickup_data['AdditionCost'] }}</td>
    		    		</tr>
    		    		@endif
    		    		@if($pickup_data['Discount'] > 0)
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;">ส่วนลด</td>
    		    			<td class="text-right text-danger" style="text-align: right;">-{{ $pickup_data['Discount'] }}</td>
    		    		</tr>
    		    		@endif
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;">รวม</td>
    		    			<td class="text-right text-success" style="text-align: right;"><h4 class="text-success">{{ $pickup_data['Amount'] }}</h4></td>
    		    		</tr>
    		    		@if($unpaid['Paid'] > 0)
    		    		<tr>
    		    			<td class="text-left" style="text-align: left;"><strong>ชำระแล้ว</strong></td>
    		    			<td class="text-right text-danger" style="text-align: right;"><h3 class="text-danger"><strong>{{ $unpaid['Paid'] }}</strong></h3></td>
    		    		</tr>
    		    		@endif
    		    		
    		    	</tbody>
    		    	</table>
    		    </div>
    		    <div class="clearfix"></div>
    		    
                <?php if($pickup_data['PickupType'] == "Pickup_AtHome"): ?>
                <div class="col-md-8 col-md-offset-2 well" style="margin-bottom: 20px;">
                
                	<h4>ที่อยู่ที่ให้เข้ารับ</h4>

    				<div class="col-xs-12 text-center">
    				<?php echo $pickup_data['PickupAddress']['Firstname'];?> <?php echo $pickup_data['PickupAddress']['Lastname'];?>
    				</div>
    				<div class="clearfix"></div>
    				
    				<div class="col-xs-12 text-center">
    				<?php echo $pickup_data['PickupAddress']['PhoneNumber'];?>
    				</div>
    				<div class="clearfix"></div>
    				
    				<div class="col-xs-12 text-center">
    				<?php echo $pickup_data['PickupAddress']['AddressLine1'];?>
    				<?php echo $pickup_data['PickupAddress']['AddressLine2'];?>
    				<?php echo $pickup_data['PickupAddress']['City'];?>
    				<?php echo $pickup_data['PickupAddress']['State'];?>
    				<?php echo $pickup_data['PickupAddress']['Postcode'];?>
    				Thailand
    				</div>
    			</div>
    			<div class="clearfix"></div>
    			<?php endif; ?>

    			<h3>{!! FT::translate('pickup_detail.panel.heading2') !!}</h3>
                <table class="table table-stripe table-hover">
                    <thead>
                        <tr>
                        	<td>{!! FT::translate('label.shipment_id') !!}</td>
                        	<td class="hidden-xs">{!! FT::translate('label.receiver') !!}</td>
                        	<td class="hidden-xs">{!! FT::translate('label.destination') !!}</td>
                        	<!--<td>{!! FT::translate('label.reference') !!}</td>-->
                        	<td>{!! FT::translate('label.agent') !!}</td>
                        	<td>{!! FT::translate('label.shipping') !!}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(sizeof($pickup_data['ShipmentDetail']['ShipmentIds']) > 0): 
                        foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $data):
                            
                        ?>
                            <tr>
                            	<td><?php echo $data['ID'];?></td>
                            	<?php if($data['ReceiverDetail']['Firstname'] != ""): ?>
                            	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                            	<?php else: ?>
                            	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Custname'];?></td>
                            	<?php endif; ?>
                            	<td class="hidden-xs"><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                            	<td><img src="{{ url('/images/agent/' . $data['ShipmentDetail']['ShippingAgent'] . '.gif') }}" style="max-width: 80px;"/></td>
                            	<td><?php echo $data['ShipmentDetail']['ShippingRate']; ?></td>
                            	
                            </tr>
                        <?php 
                        endforeach;
    	                endif;
    	                ?> 
    	            </tbody>
                </table>
                <div class="clearfix"></div>

            </div>
        </div>
	</div>   
</div>
<script>

    function cancelPickup(pick_id){

    	if(confirm("{!! FT::translate('confirm.delete_pickup') !!}")){
    		$.post('{{url ("pickup/cancel")}}',
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
    	value: {{ $pickup_data['Amount'] }},
    	currency: 'THB',
    });
    @endif
    
</script>
 
@endsection