@extends('layout')
@section('content')
<?php
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
	<div class="row">
		
		@include('left_account_menu')
	
    	<div class="col-md-10">
    		
    		<h2>{!! FT::translate('account_overview.heading') !!}</h2>
        	<hr />
        			
    		<div class="row">
    
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
        						<div><i class="fa fa-money" style="font-size: 35px;margin-bottom: 10px;"></i></div>
        						<h5 style="margin:0;">{!! FT::translate('status.pickup.status11') !!}</h5>
        						<h3>{{ $pickupCount['unpaid'] }}</h3>
        					</div>
        				</div>
    				</div>
    
    				<div class="col-md-3 text-center">
        				<div class="panel panel-primary">
        					<div class="panel-body">
        						<div><i class="fa fa-truck" style="font-size: 35px;margin-bottom: 10px;"></i></div>
        						<h5 style="margin:0;">{!! FT::translate('status.pickup.status2') !!}</h5>
        						<h3>{{ $pickupCount['pick'] }}</h3>
        					</div>
        				</div>
    				</div>
    				
    				<div class="col-md-3 text-center">
        				<div class="panel panel-primary">
        					<div class="panel-body">
        						<div><i class="fa fa-exclamation-circle" style="font-size: 35px;margin-bottom: 10px;"></i></div>
        						<h5 style="margin:0;">{!! FT::translate('status.pickup.status5') !!}</h5>
        						<h3>{{ $pickupCount['verified'] }}</h3>
        					</div>
        				</div>
    				</div>
    				<div class="col-md-3 text-center">
        				<div class="panel panel-primary">
        					<div class="panel-body">
        						<div><i class="fa fa-plane" style="font-size: 35px;margin-bottom: 10px;"></i></div>
        						<h5 style="margin:0;">{!! FT::translate('status.pickup.status6') !!}</h5>
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
                                @if(is_array($pickup_list) && sizeof($pickup_list) > 0)
                                @foreach($pickup_list as $pickup)
                            	<tr>
                            		<td><a href="/pickup_detail/<?php echo $pickup['ID']; ?>"><?php echo $pickup['ID']; ?></a></td>
                            		<td><?php echo date("d/m/Y",strtotime($pickup['CreateDate']['date'])); ?></td>
                            		<td class="hidden-xs"><?php echo $pickup['Status']; ?></td>
                            		<td><?php echo number_format($pickup['Amount']); ?></td>
                            		<td class="hidden-xs"><?php echo $pickup['TotalShipment']; ?></td>
                            	</tr>
                                @endforeach
                                @else
                                <tr><td colspan="6" class="text-center">{!! FT::translate('error.pickup.notfound') !!}</td></tr>
                                @endif
                                </tbody>
                                </table>
                                <div class="text-right"><a href="{{ url('pickup_list') }}">{!! FT::translate('account_overview.viewall_link') !!}</a></div>
    					</div>
    				</div>
    				<div class="clearfix"></div><br />

    				<div class="panel panel-primary">
        				<div class="panel-heading">ประวัติการเงิน 10 รายการล่าสุด</div>
        		        <div class="panel-body">
        		        	<table id="statements" class="table table-hover table-striped">
        		        	<thead>
                        	<tr>
                        		<td>{!! FT::translate('label.date') !!}</td>
                        		<td>ประเภท | รายละเอียด</td>
                        		<td class="hidden-xs">หมายเลขอ้างอิง</td>
                        		<td>จำนวน (บาท)</td>
                        	</tr>
                            </thead>
                            <tbody>
                            @if(sizeof($statements) > 0)
                            @foreach($statements as $statement)
                            	<tr>
                            		<td>{{ $statement['CreateDate'] }}</td>
                            		<td>
                            		@if($statement['Amount'] < 0)
                            			<i class="fa fa-money text-info"></i> 
                            		@else
                            			<i class="fa fa-plus-circle text-success"></i>
                            		@endif
                            		
                        			{{ isset($payment_mapping[$statement['Payment']]) ? $payment_mapping[$statement['Payment']]:$statement['Payment'] }}
                        			
                        			@if( in_array($statement['Payment'],array("QR","Credit_Card","Bank_Transfer","Cash","Invoice")) )
                        				#{{ $statement['PickupId'] }}
                        			@endif
    
                            		</td>
                            		<td class="hidden-xs"><a href="#">{{ $statement['PickupId'] }}</a></td>
                            		<td>
                            		@if($statement['Amount'] < 0)
                            			<span class="text-danger">{{ $statement['Amount'] }}</span>
                            		@else
                            			<span class="text-success">+{{ $statement['Amount'] }}</span>
                            		@endif
                            		</td>
                            	</tr>
                           	@endforeach
                           	@else
                           		<tr>
                            		<td colspan="4" class="text-center">ไม่มีประวัติการเงิน</td>
                            	</tr>
                           	@endif
                            </tbody>
                            </table>
                           <div class="text-right"><a href="{{ url('customer_balance') }}">{!! FT::translate('account_overview.viewall_link') !!}</a></div>
                        </div>
    				</div>
    
    			</div>
    			<div class="clearfix"></div>
    		</div>
    	</div>
    	<div class="clearfix"></div>
    </div>
</div>
@endsection