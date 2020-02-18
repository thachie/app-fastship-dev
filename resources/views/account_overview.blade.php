@extends('layout')
@section('content')
<?php 
$refCode = "FGF".$customer_data['ID'];
$paramEncrypted = urlencode(base64_encode($refCode));
$inviteURL = "http://app.fastship.co/register!" . $paramEncrypted;

if($current_sale < 30000 && $customer_data['group'] == "Standard"){
    $sale_suggest = "*" . FT::translate('account_overview.privilege.text1') . " " . number_format(30000 - $current_sale,0) . FT::translate('account_overview.privilege.text2') . " 3% " . FT::translate('account_overview.privilege.text3');
}else if($current_sale < 50000 && $customer_data['group'] == "Bronze"){
    $sale_suggest ="*" . FT::translate('account_overview.privilege.text1') . " " . number_format(30000 - $current_sale,0) . FT::translate('account_overview.privilege.text2') . " 5% " . FT::translate('account_overview.privilege.text3');
}else if($current_sale < 100000 && $customer_data['group'] == "Silver"){
    $sale_suggest = "*" . FT::translate('account_overview.privilege.text1') . " " . number_format(30000 - $current_sale,0) . FT::translate('account_overview.privilege.text2') . " 10% " . FT::translate('account_overview.privilege.text3');
}else{
    $sale_suggest = "";
}
?>
<div class="conter-wrapper">
	<div class="col-md-10 col-md-offset-1">
		
		<div class="row">
			<div class="col-md-12"><h2>{!! FT::translate('account_overview.heading') !!}</h2></div>
			<div class="col-md-4">
			
				<div class="panel panel-primary">
					<div class="panel-heading">{!! FT::translate('account_overview.panel.heading1') !!}</div>
			        <div class="panel-body">
    					<h4>{!! FT::translate('label.fullname') !!}</h4>
    					<div>{{ $customer_data['firstname'] . " " .$customer_data['lastname'] }}</div>
    					<br />
    					
    					<h4>{!! FT::translate('label.level') !!}</h4>
    					<div>{{ $customer_data['group'] }}</div>
    					<br />
    					
    					<?php if($customer_data['refcode'] != ""): ?>
    					<h4>{!! FT::translate('label.referral') !!}</h4>
    					<div>{{ $customer_data['refcode'] }}</div>
    					<?php endif; ?>
    					
					</div>
				</div>
				
				<div class="panel panel-primary" style="display:none;">
					<div class="panel-heading">{!! FT::translate('label.credit_balance') !!}</div>
			        <div class="panel-body">
    					<h1>0 {!! FT::translate('unit.point') !!}</h1>
					</div>
				</div>
				
				<div class="panel panel-primary">
					<div class="panel-heading">{!! FT::translate('account_overview.panel.heading2') !!}</div>
			        <div class="panel-body">
			        	<div><strong>{!! FT::translate('label.thismonth') !!}</strong></div>
    					<h1>{{ number_format($current_sale,0) }} {!! FT::translate('unit.baht') !!}</h1>
    					<div class="text-left danger small" style="color:green;">{{ $sale_suggest }}</div>
    					<hr />
    					
    					<div><strong>{!! FT::translate('label.lastmonth') !!}</strong></div>
    					<h1>{{ number_format($previous_sale,0) }} {!! FT::translate('unit.baht') !!}</h1>
    					<hr />
    					
    					<div><strong>{!! FT::translate('label.last2month') !!}</strong></div>
    					<h1>{{ number_format($twomonthago_sale,0) }} {!! FT::translate('unit.baht') !!}</h1>
    					
					</div>
				</div>

				
			</div>
			
			<div class="col-md-8">
				
				<div class="col-md-3 text-center">
    				<div class="panel ">
    					<div class="panel-body">
    						<div><i class="fa fa-star" style="font-size: 35px;margin-bottom: 10px;"></i></div>
    						<h4>{!! FT::translate('status.pickup.status1') !!}</h4>
    						<h3>{{ $pickupCount['new'] }}</h3>
    					</div>
    				</div>
				</div>
				
				<div class="col-md-3 text-center">
    				<div class="panel panel-primary">
    					<div class="panel-body">
    						<div><i class="fa fa-truck" style="font-size: 35px;margin-bottom: 10px;"></i></div>
    						<h4>{!! FT::translate('status.pickup.status2') !!}</h4>
    						<h3>{{ $pickupCount['pick'] }}</h3>
    					</div>
    				</div>
				</div>
				
				<div class="col-md-3 text-center">
    				<div class="panel panel-primary">
    					<div class="panel-body">
    						<div><i class="fa fa-money" style="font-size: 35px;margin-bottom: 10px;"></i></div>
    						<h4>{!! FT::translate('status.pickup.status5') !!}</h4>
    						<h3>{{ $pickupCount['unpaid'] }}</h3>
    					</div>
    				</div>
				</div>
				
				<div class="col-md-3 text-center">
    				<div class="panel panel-primary">
    					<div class="panel-body">
    						<div><i class="fa fa-plane" style="font-size: 35px;margin-bottom: 10px;"></i></div>
    						<h4>{!! FT::translate('status.pickup.status6') !!}</h4>
    						<h3>{{ $pickupCount['completed'] }}</h3>
    					</div>
    				</div>
				</div>
				<div class="clearfix"></div><br />
				
				<div class="panel panel-primary">
					<div class="panel-heading">{!! FT::translate('account_overview.panel.heading3') !!}</div>
			        <div class="panel-body">
			        	<table class="table table-hover table-striped">
                            <thead>
                            	<tr>
                            		<td>{!! FT::translate('label.pickup_id') !!}</td>
                            		<td>{!! FT::translate('label.date') !!}</td>
                            		<td class="hidden-xs">{!! FT::translate('label.status') !!}</td>
                            		<td>{!! FT::translate('label.grand_total') !!}</td>
                            		<td class="hidden-xs">{!! FT::translate('label.number_shipment') !!}</td>
                            	</tr>
                            </thead>
                            <tbody>
                            <?php 
                            if(is_array($pickup_list) && sizeof($pickup_list) > 0):
                            foreach($pickup_list as $pickup):
                            	//if($pickup['Status'] == "Cancelled") continue;
                            ?>
                            	<tr>
                            		<td><a href="/pickup_detail/<?php echo $pickup['ID']; ?>"><?php echo $pickup['ID']; ?></a></td>
                            		<td><?php echo date("d/m/Y",strtotime($pickup['CreateDate']['date'])); ?></td>
                            		<td class="hidden-xs"><?php echo $pickup['Status']; ?></td>
                            		<td><?php echo number_format($pickup['Amount']); ?></td>
                            		<td class="hidden-xs"><?php echo $pickup['TotalShipment']; ?></td>
                            	</tr>
                            <?php 
                            endforeach;
                            else:
                            ?>
                            <tr><td colspan="6" class="text-center">{!! FT::translate('error.pickup.notfound') !!}</td></tr>
                            <?php
                            endif;
                            ?>
                            </tbody>
                            </table>
                            <div class="text-right"><a href="{{ url('pickup_list') }}">{!! FT::translate('account_overview.viewall_link') !!}</a></div>
					</div>
				</div>
				
				<div class="panel panel-primary">
        			<div class="panel-heading">{!! FT::translate('account_overview.panel.heading4') !!}</div>
        		    <div class="panel-body">
        	            <div class="row" style="margin-bottom:10px;">
        	            	<h3>Referal Code: {{ $refCode }}</h3>
        	                <div class="well" style="line-height: 24px;">
        	                	{!! FT::translate('account_overview.referral.text1') !!} <code style="padding: 0; word-wrap: break-word;"><?php echo $inviteURL; ?></code>
        	                	{!! FT::translate('account_overview.referral.text2') !!}
        	                	<div class="text-right"><a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($inviteURL); ?>" target="_blank"><button type="button" class="btn btn-success">{!! FT::translate('button.fb_share') !!}</button></a></div>
        	                </div>
        	           	</div>
        	        </div>
            	</div>
				

			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
@endsection