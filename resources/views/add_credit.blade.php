@extends('layout')
@section('content')

<?php 
$selectAmounts = array(500,1000,2000,3000,5000,10000);
$paymentMethods = array(
    "Bank_Transfer"=>"โอนผ่านธนาคาร",
    "Credit_Card"=>"บัตรเครดิต",
);
if(isset($amount) && $amount != "" && $amount < 500){
    $amount = 500;
}

?>
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-5 col-xs-6 col-md-offset-1"><h2>{!! FT::translate('add_credit.heading') !!}</h2></div>
        <div class="col-md-5 col-xs-6 text-right">
        	<label class="small">{!! FT::translate('label.balance') !!}</label> <span class="price"><?php echo number_format($creditBalance,0); ?></span> {!! FT::translate('unit.credit') !!}
        </div>
	</div>
	<div class="row">
	
		<div class="col-md-6 col-md-offset-1">
       	
       		@if(!isset($amount) || $amount == "")
       		<div class="panel panel-primary">
            	<div class="panel-heading">{!! FT::translate('add_credit.panel.heading2') !!}</div>
            	<div class="panel-body" >
                	<div class="row">
                	
                		<div class="col-md-12 mb-20">1 {!! FT::translate('unit.credit') !!} : 1 {!! FT::translate('unit.baht') !!}</div>
                		<div style="clear:both;"></div><br />
                		
			    		<fieldset id="payamount">
	                        @foreach($selectAmounts as $selectAmount)
	                        <div class="col-md-4 col-xs-6 text-center d-inline-block topup-amount">
	                        <a href="{{ url('/credit/topup_qr/'.$selectAmount) }}">
	                        	<button type="button" class="btn btn-lg btn-block topup-amount">
	                        		{!! number_format($selectAmount,0) !!}.-
	                            </button>
	                        </a>
	                        </div>
	                        @endforeach
	                        <div class="clearfix"></div>
							<br />
	                        	
	                        @if(max(0,$unpaid - $creditBalance) > 0)
	                        <div class="col-md-12 col-xs-12 inline-block text-left">{!! FT::translate('add_credit.text1') !!} </div>
	                        <div  class="col-md-4 col-xs-12 text-center topup-amount">
	                        <a href="{{ url('/credit/topup_qr/'. ($unpaid - $creditBalance) ) }}">
	                        	<button type="button" class="btn btn-lg btn-block topup-amount">
	                        		{!! number_format($unpaid - $creditBalance,0) !!}.-
	                            </button>
	                        </a>
	                        </div>
	                        @endif
	                    </fieldset>
	                    <div class="clearfix"></div>
						<br />
					</div>
				</div>
            </div>
            @else
            
            <div class="panel panel-primary">
            	<div class="panel-heading">{!! FT::translate('add_credit.panel.heading2') !!}</div>
            	<div class="panel-body" >
                	<div class="row">
                		<h2>{{ number_format($amount,0) }} {!! FT::translate('unit.baht') !!} <span class="small"><a href="{{ url('/add_credit/') }}">{!! FT::translate('button.edit') !!}</a></span></h2>
					</div>
				</div>
            </div>
           	<div class="panel panel-primary">
           		<div class="panel-heading">{!! FT::translate('add_credit.panel.heading3') !!}</div>
            	<div class="panel-body" >

    				
    				
    				<div class="row" id="qr_code">
    				
    					<h3><img src="/images/payment/qr.png" style="max-height: 30px;vertical-align: top;"/> {!! FT::translate('radio.payment.qr_code') !!}</h3>
    					
    					<div class="col-md-6 col-xs-12 text-center">
							<img src="https://support.thinkific.com/hc/article_attachments/360042081334/5d37325ea1ff6.png" style="max-width: 100%;"/>
    		    		</div>
    		    		<div class="col-md-6 col-xs-12 text-center" style="padding:80px 10px 0px;  ">
							<p>{!! FT::translate('add_credit.qr_code.text1') !!}</p>
							
							<a href="{{ url('https://support.thinkific.com/hc/article_attachments/360042081334/5d37325ea1ff6.png') }}" download="QR_Code"><button type="button" name="submit" value="submit" class="btn btn-primary">{!! FT::translate('button.download') !!} QR</button></a>
							
						</div>
    					
    					
    				</div>

    				<div class="row" style="display: none;">
        				<div class="col col-md-6" id="credit_card">
        
        	                <h4><img src="/images/payment/credit_card.png" style="max-height: 32px;vertical-align: top;"/> {!! FT::translate('radio.payment.creditcard') !!}</h4>
        					
        					@if(sizeof($creditCards) > 0)
        					<div class="col-md-12 col-xs-12 text-center">
        					@foreach($creditCards as $creditCard)
    							<div class=" block-primary" style="position: relative;">
    								<span class="creditbar"></span>
    								<h5 class="accountno">XXXXX-XXX-X{{ $creditCard['LastDigits'] }}</h5>
    								<h6>{{ $creditCard['CardName'] }}</h6>
    								<p>{{ $creditCard['Bank'] }}</p>
    							</div>
    						@endforeach
        		    		</div>
        		    		<div class="col-md-12 col-xs-12 text-center">
        		    		
<!--     							<p>{!! FT::translate('add_credit.credit_card.text1') !!}</p> -->
    
    							<form id="payment_form" class="form-horizontal" method="post" action="{{url ('credit/topup_creditcard')}}">
        		
            						{{ csrf_field() }}
            						<input type="hidden" name="amount" value="{{ $amount }}" />
            						<input type="hidden" name="omise_id" value="{{ $creditCards[0]['OmiseId']}}" />
            						
        							<a href="#"><button type="submit" name="submit" value="submit" class="btn btn-outline-primary">{!! FT::translate('button.confirm_creditcard') !!}</button></a>
        						
        						</form>
        					</div>
        					@else
        					<div class="col-md-12 col-xs-12 text-center">
    							<a href="{{ url('/myaccount/' ) }}"><button type="button" name="submit" value="submit" class="btn  btn-outline-primary">{!! FT::translate('button.add_card') !!}</button></a>
    						</div>
    						@endif
        	    		</div>
        				<div class="col col-md-6" id="bank_transfer">
                            
                            <h4><img src="/images/payment/bank_transfer.png" style="max-height: 30px;vertical-align: top;"/> {!! FT::translate('radio.payment.bank_transfer') !!}</h4>

                            <div class="col-md-12 col-xs-12 text-center">
                            	<a href="{{ url('/payment_submission/' . $amount ) }}"><button type="button" name="submit" value="submit" class="btn btn-outline-primary">{!! FT::translate('button.payment_submit') !!}</button></a>
                            </div>
        	                <div class="clearfix"></div>
        	                
        				</div>
        				
    	    		</div>
    	    		
    	    	</div>
       		</div>
       		@endif
       	</div>

        <div class="col-md-4">

			@if($unpaid > 0)
			<div class="panel panel-warning">
	        	<div class="panel-heading">{!! FT::translate('add_credit.panel.heading5') !!}</div>
                <div class="panel-body">
                 
                 	<table class="table">
                 	<thead>
                 		<tr>
                 			<th>{!! FT::translate('label.date') !!}</th>
                 			<th>{!! FT::translate('label.pickup_id') !!}</th>
                 			<th>{!! FT::translate('label.status') !!}</th>
                 			<th>{!! FT::translate('label.amount') !!}</th>
                 		</tr>
                 	</thead>
                 	<tbody>
                 	@foreach($unpaidPickups as $pickup)
                 		<tr>
                 			<td class="text-center small">{{ date("d/m/y",strtotime($pickup['create_dt'])) }}</td>
                 			<td class="text-left small"><span class="text-left">{{ $pickup['id'] }}</span></td>
                 			<td class="text-left small"><span class="text-center">{{ $pickupStatus[$pickup['status']] }}</span></td>
                 			<td class="text-right small" ><span class="text-danger">{{ number_format($pickup['total'],0) }}</span></td>
                 		</tr>
                 	@endforeach
                 		<tr>
                 			<td class="text-right small" colspan="2"></td>
                 			<td class="text-right small">รวม</td>
                 			<td class="text-right small"><span class="text-danger">{{ number_format($unpaid,0) }}</span></td>
                 		</tr>
                 	</tbody>
                 	</table>
                </div>
	        </div>
	        @endif
	        
	        <div class="panel panel-warning">
	        	<div class="panel-heading">{!! FT::translate('add_credit.panel.heading4') !!}</div>
                <div class="panel-body">
                 
                 	<table class="table">
                 	<thead>
                 		<tr>
                 			<th>{!! FT::translate('label.date') !!}</th>
                 			<th>{!! FT::translate('label.description') !!}</th>
                 			<th>{!! FT::translate('label.status') !!}</th>
                 			<th>{!! FT::translate('label.amount') !!}</th>
                 		</tr>
                 	</thead>
                 	<tbody>
                 	@foreach($statements as $statement)
                 		<tr>
                 			<td class="text-center small">{!! $statement['create_dt'] !!}</td>
                 			<td class="text-left small"><span class="text-left">{!! $statement['description'] !!}</span></td>
                 			<td class="text-left small"><span class="text-center badge tiny">{!! $statement['status'] !!}</span></td>
                 			<td class="text-right small" >{!! $statement['amount'] !!}</td>
                 		</tr>
                 	@endforeach
                 	</tbody>
                 	</table>
                </div>
	        </div>
	        
	        
	        
		</div>

    </div>
    
</div>


<script type="text/javascript">
	$(document).ready( function() {
// 	    $( ".selector" ).checkboxradio({
// 	        classes: {
// 	            "ui-checkboxradio": "highlight"
// 	        }
// 	    });

	});

</script>

@endsection