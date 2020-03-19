@extends('layout')
@section('content')
<?php 
//alert($pickup_list);die();
$limit = 20;
?>
<div class="conter-wrapper">
	<div class="row">
        <h2 class="col-md-12">{!! FT::translate('pickup_list.heading') !!}</h2>
	</div>

        <div class="row">      
            <div class="col-md-12 ">
                <div class="panel panel-primary hidden-xs">
                    <div class="panel-heading">{!! FT::translate('pickup_list.panel.heading1') !!}</div>
                    <div class="panel-body">

                        <table class="table table-hover table-striped">
                        <thead>
                        	<tr>
                        		<td>{!! FT::translate('label.pickup_id') !!}</td>
                        		<td>{!! FT::translate('label.create_date') !!}</td>
                        		<td>{!! FT::translate('label.status') !!}</td>
                        		<td>วิธีการเข้ารับ</td>
                        		<td>{!! FT::translate('label.grand_total') !!}</td>
                        		<td>{!! FT::translate('label.number_shipment') !!}</td>
                        		<td></td>
                        	</tr>
                        </thead>
                        <tbody>
                        <?php 
                        if(is_array($pickup_list) && sizeof($pickup_list) > 0):
                        foreach($pickup_list as $pickup):
                        	//if($pickup['Status'] == "Cancelled") continue;
                        ?>
                        	<tr>
                        		<td><a href="/pickup_detail/{{ $pickup['ID'] }}">{{ $pickup['ID'] }}</a></td>
                        		<td>{{ date("d/m/Y",strtotime($pickup['CreateDate']['date'])) }}</td>
                        		<td>{{ $pickup['Status'] }}</td>
                        		<td>{{ $pickupType[$pickup['PickupType']] }}</td>
                        		<td>{{ number_format($pickup['Amount']) }}</td>
                        		<td>{{ $pickup['TotalShipment'] }}</td>
                        		<td>
                        			@if($pickup['Status'] == 'Unpaid')
	                        			<a href="{{ url('/pickup_detail_payment/'.$pickup['ID'])}}"><button type="button" class="btn btn-info btn-sm">ชำระเงิน</button></a>
									@else
										<a href="{{ url('pickup_detail_invoice/'.$pickup['ID'])}}" target="_blank"><button type="button" class="btn btn-default btn-sm">{!! FT::translate('pickup_detail.button.print_pickup') !!}</button></a>
									@endif
                        		</td>
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
				
				<div class="row visible-xs">
					<div class="panel panel-primary">
	                    <div class="panel-heading">{!! FT::translate('pickup_list.panel.heading1') !!}</div>
	                    <div class="panel-body">
						<?php 
						if(is_array($pickup_list) && sizeof($pickup_list) > 0):
						foreach($pickup_list as $pickup):
						?>
						<div class="col-xs-12 shipment-list">
							<div class="col-xs-12">
			                    <div class="pull-left"><h3><a href="/pickup_detail/<?php echo $pickup['ID']; ?>"><?php echo $pickup['ID']; ?></a></h3></div>
			                    <div class="pull-right"><h4><?php echo $pickup['Status']; ?></h4></div>
			                </div>
		                	<div class="clearfix"></div>

							<div class="col-xs-6 text-right">{!! FT::translate('label.create_date') !!} : </div>
							<div class="col-xs-6"><?php echo date("d/m/Y",strtotime($pickup['CreateDate']['date'])); ?></div>
							<div class="clearfix"></div>
							
							<div class="col-xs-6 text-right">{!! FT::translate('label.grand_total') !!} : </div>
							<div class="col-xs-6"><?php echo $pickup['Amount']; ?></div>
							<div class="clearfix"></div>
							
							<div class="col-xs-6 text-right">{!! FT::translate('label.number_shipment') !!} : </div>
							<div class="col-xs-6"><?php echo $pickup['TotalShipment']; ?></div>
							<div class="clearfix"></div>
							
							<div class="col-xs-12 text-center" style="margin-top: 10px;">

							@if($pickup['Status'] == 'Unpaid')
								<a href="{{ url('/pickup_detail_payment/'.$pickup['ID'])}}"><button type="button" class="btn btn-info btn-sm">ชำระเงิน</button></a>
							@else
								<a href="{{ url('pickup_detail_print/'.$pickup['ID'])}}" target="_blank"><button type="button" class="btn btn-info btn-sm">{!! FT::translate('pickup_detail.button.print_pickup') !!}</button></a>
								<a href="{{ url('pickup_invoice_print/'.$pickup['ID'])}}" target="_blank"><button type="button" class="btn btn-default btn-sm">{!! FT::translate('pickup_detail.button.print_invoice') !!}</button></a>
							@endif
							</div> 
						</div> 
						<?php 
						endforeach;
						else:
						?>
						<div class="text-center">{!! FT::translate('error.shipment.notfound') !!}</div>
						<?php
						endif;
						?>
						</div>
					</div>
				</div>
				<div class="clearfix text-center">
					<ul class="pagination">
					<?php if($page > 1): ?>
						<li><a href="/pickup_list/<?php echo ($page-1);?>" aria-label="Previous"><span aria-hidden="true">&lt;&lt;</span></a></li>
					<?php endif; ?>
						<li><a href="#"><?php echo $page; ?></a></li>
					<?php if(sizeof($pickup_list) == $limit): ?>
						<li><a href="/pickup_list/<?php echo ($page+1);?>" aria-label="Next"><span aria-hidden="true">&gt;&gt;</span></a></li>	
					<?php endif; ?>
					</ul>
				</div>

        </div>
    </div>
</div>


<script>
	$(document).ready( function() {
	    $( ".selector" ).checkboxradio({
	        classes: {
	            "ui-checkboxradio": "highlight"
	        }
	    }); 

	    $("#amount-100").click();
	    $("#method-Bank_Transfer").click();
	    
	});
</script>

@endsection