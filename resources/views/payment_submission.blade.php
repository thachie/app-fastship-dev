@extends('layout')
@section('content')
<?php  //alert($pickup_list); ?>
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-10 col-md-offset-1"><h2>{!! FT::translate('payment_submission.heading') !!}</h2></div>
	</div>
	<div class="row">
    <form id="payment_form" class="form-horizontal" method="post" action="{{url ('credit/create')}}" enctype="multipart/form-data">
		
		{{ csrf_field() }}
		
		<input type="hidden" name="bank" value="ksk" />
		<input type="hidden" name="transfer_type" value="Bank_Transfer" />

        <div class="col-md-6 col-md-offset-1">
	        <div class="panel panel-primary">
	        	<div class="panel-heading">{!! FT::translate('payment_submission.panel.heading1') !!}</div>
	            <div class="panel-body">
	               
	              <?php if(isset($amount) && $amount != ""): ?> 
	              	
					<input type="hidden" id="transfer_no" name="transfer_no" value="" />
					<input type="hidden" id="amount_input" name="amount" value="{{ $amount }}" required />
						
					<label class="col-md-4 control-label">{!! FT::translate('label.topup_amount') !!}</label>	
					<div class="col-md-8"><h3>{{ $amount }} {!! FT::translate('unit.baht') !!}</h3></div>
					<div class="clearfix"></div><br />
					
					<label class="col-md-4 control-label">{!! FT::translate('label.transfer_date') !!}</label>	
					<div class="col-md-8">
						<input type='text' id="transfer_date" class="form-control required" name="tranfer_date" required="required"/>
					</div>
					<div class="clearfix"></div><br />
									
					<label class="col-md-4 control-label">{!! FT::translate('label.slip') !!}</label>	
					<div class="col-md-8">
						<input type="file" class="form-control choose-file" name="slip" required style="border: 0px;margin-top: 8px;"/>
						
					</div>
					<div class="clearfix"></div>
					<br />
						
					<div class="text-center btn-create"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary btn-lg">{!! FT::translate('button.payment_submit') !!}</button></div>
	              <?php else: ?>
                  <?php if(is_array($pickup_list) && sizeof($pickup_list)>0): ?>
	            	<label class="col-md-4 control-label">{!! FT::translate('label.payment_pickup') !!}</label>	
					<div class="col-md-8">
					<?php 
					if(is_array($pickup_list) && sizeof($pickup_list)>0):
					foreach($pickup_list as $pickup):
					?>
						<label for="trans_<?php echo $pickup['ID'];?>"><input type="radio" name="transfer_no" value="<?php echo $pickup['ID'];?>" id="trans_<?php echo $pickup['ID'];?>" onclick="selectPickup(<?php echo $pickup['Amount'];?>)" /> <?php echo $pickup['ID'] . " (" . date("d/m/Y",strtotime($pickup['CreateDate']['date'])) . ") <strong class='orange'>". number_format($pickup['Amount'],2) ." บาท</strong>";?></label> <br />
					<?php 
					endforeach;
					else: ?>
					<span style="line-height: 40px;">{!! FT::translate('error.payment_pickup.notfound') !!}</span>
					<?php
					endif; 
					?>
					</div>
					<div class="clearfix"></div>
					<br />
					
					<input type="hidden" id="amount_input" class="form-control" name="amount" required/>
						
					<label class="col-md-4 control-label">{!! FT::translate('label.transfer_date') !!}</label>	
					<div class="col-md-8">
						<input type='text' id="transfer_date" class="form-control required" name="tranfer_date" required="required"/>
					</div>
					<div class="clearfix"></div><br />
									
					<label class="col-md-4 control-label">{!! FT::translate('label.slip') !!}</label>	
					<div class="col-md-8">
						<input type="file" class="form-control choose-file" name="slip" required style="border: 0px;margin-top: 8px;"/>
						
					</div>
					<div class="clearfix"></div>
					<br />
						
					<div class="text-center btn-create"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary btn-lg">{!! FT::translate('button.payment_submit') !!}</button></div>
                  <?php else: ?>
                  	<div class="text-center"><span style="line-height: 40px;">{!! FT::translate('error.payment_pickup.notfound') !!}</span></div>
                  <?php endif; ?>
                  <?php endif; ?>
	            </div>
	        </div>
	    </div>
	        
        <div class="col-md-4">
	        <div class="panel panel-primary">
	        	<div class="panel-heading">{!! FT::translate('payment_submission.panel.heading2') !!}</div>
	            <div class="panel-body">
	                    <div class="col-md-12 col-xs-12 text-center">
		                    <div><img src="/images/kbank-logo.jpg" title="{!! FT::translate('label.kasikorn') !!}" style="max-width: 90%;"/></div>
		                    <div class="clearfix"></div>
		                    <br />
		                    
		                    <div>{!! FT::translate('label.account_number') !!}</div>
		                    <h2 class="orange">{!! FT::translate('payment_submission.panel.content1') !!}</h2>
		                    <div class="clearfix"></div>
		                    
		                    <div>{!! FT::translate('label.account_name') !!}</div>
		                    <h4 style="font-weight: 600;">{!! FT::translate('payment_submission.panel.content2') !!}</h4>
		                    <div class="clearfix"></div>
		                    
		                    <div>{!! FT::translate('label.branch') !!}</div>
		                    <h4 style="font-weight: 600;">{!! FT::translate('payment_submission.panel.content3') !!}</h4>
		                    <div class="clearfix"></div>
	                    </div>
	                    <div class="clearfix"></div>
	                        
	                 </div>
	        	</div>
	        </div>

    </form>
</div>


<script type="text/javascript">
	$(document).ready( function() {
	    $( ".selector" ).checkboxradio({
	        classes: {
	            "ui-checkboxradio": "highlight"
	        }
	    });

	    $(function () {
            $('#transfer_date').datetimepicker({
           		defaultDate: new Date(),
            	format:'YYYY-MM-DD HH:mm:ss'
            });
        });

        <?php if(is_array($pickup_list) && sizeof($pickup_list)>0): ?>
		$("#trans_<?php echo $pickup_list[0]['ID'];?>").click();
        <?php endif; ?>

	    //$("#amount-100").click();
	    //$("#method-Bank_Transfer").click();
	    
	});

	function selectPickup(amount){
		$("#amount_input").val(amount);
		$("#amount_input").attr("readonly",true);
	}

	function selectOther(){
		$("#amount_input").val("");
		$("#amount_input").attr("readonly",false);
		$("#amount_input").focus();
	}

	
</script>

@endsection