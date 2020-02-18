@extends('layout')
@section('content')
<div class="conter-wrapper">

	<div class="row">
    	<div class="col-md-7 pad8"><h2>{!! FT::translate('shipment_list.heading') !!} ({{ $total }})</h2></div>
    	<div class="col-md-5 text-right">
        	
    	</div>
	</div>
	
	<form id="search_form" class="form-horizontal" method="post" action="">
	    
	    {{ csrf_field() }}
	    
	    <div class="row">
	    	<div class="col-md-8 col-md-offset-2">
            	<div class="panel panel-primary">
            		<div class="panel-heading">{!! FT::translate('shipment_list.panel.heading1') !!}</div>
                	<div class="panel-body">
                		<label class="col-md-2 control-label">{!! FT::translate('label.shipment_id') !!}</label>
                        <div class="col-md-3">
                        	<input type='text' class="form-control" name="shipment_id" value="{{ $default['shipment_id'] }}" />
                        </div>
                    	<label class="col-md-2 control-label">{!! FT::translate('label.create_date') !!}</label>
                        <div class="col-md-2">
                            <input type='text' class="form-control  create_date" name="start_create_date" value="{{ $default['start_create_date'] }}" />
                        </div>
                        <label class="inline-block control-label" style="float: left;">{!! FT::translate('label.to') !!}</label>
                        <div class="col-md-2">
                            <input type='text' class="form-control create_date" name="end_create_date" value="{{ $default['end_create_date'] }}" />
                        </div>
                        
                        <div class="clearfix"></div><br />
                        
                        <label class="col-md-2 control-label">{!! FT::translate('label.destination') !!}</label>
                        <div class="col-md-3">
                        	<select name="country" class="form-control country">
                            	<option value="">{!! FT::translate('dropdown.default.country_all') !!}</option>
                            	<?php foreach($countries as $code=>$country): ?>
                            	<option value="{{ $code }}" <?php if($default['country'] == $code):?>selected<?php endif; ?>>{{ $country }}</option>
                            	<?php endforeach; ?>
                            </select>
                        </div>
                        <label class="col-md-2 control-label">{!! FT::translate('label.status') !!}</label>
                        <div class="col-md-4">
                            <select name="status" class="form-control status">
                            	<option value="">{!! FT::translate('dropdown.default.status_all') !!}</option>
                            	<option value="Created" <?php if($default['status'] == "Created"):?>selected<?php endif; ?>>{{ $statuses["Created"] }}</option>
                            	<option value="ReadyToShip" <?php if($default['status'] == "ReadyToShip"):?>selected<?php endif; ?>>{{ $statuses["ReadyToShip"] }}</option>
                            	<option value="Sent" <?php if($default['status'] == "Sent"):?>selected<?php endif; ?>>{{ $statuses["Sent"] }}</option>
                            	<option value="PreTransit" <?php if($default['status'] == "PreTransit"):?>selected<?php endif; ?>>{{ $statuses["PreTransit"] }}</option>
                            	<option value="InTransit" <?php if($default['status'] == "InTransit"):?>selected<?php endif; ?>>{{ $statuses["InTransit"] }}</option>
                            	<option value="OutForDelivery" <?php if($default['status'] == "OutForDelivery"):?>selected<?php endif; ?>>{{ $statuses["OutForDelivery"] }}</option>
                            	<option value="Delivered" <?php if($default['status'] == "Delivered"):?>selected<?php endif; ?>>{{ $statuses["Delivered"] }}</option>
                            	<option value="Return" <?php if($default['status'] == "Return"):?>selected<?php endif; ?>>{{ $statuses["Return"] }}</option>
                            </select>
                        </div>
                        <div class="clearfix"></div><br />
                        
                        
                        <div class="col-md-12 text-center">
                        	<button type="button" class="btn btn-success" onclick="gotoPage(1)">{!! FT::translate('shipment_list.button.search') !!}</button>
                        	<a href="{{ url('/shipment_list') }}"><button type="button" class="btn btn-default">{!! FT::translate('shipment_list.button.reset') !!}</button></a>
                        </div>
                    </div>
                </div>
           	</div>
      	</div>
	</form>
	
<?php if(sizeof($shipment_data) > 0): ?>
    <div class="row">
    	
    	<form id="export_form" class="form-horizontal" method="post" action="{{ url('/shipment/export') }}">
	    
    	    {{ csrf_field() }}
    	    
    	    <input type="hidden" name="shipment_id" value="{{ $default['shipment_id'] }}" />
    	    <input type="hidden" name="start_create_date" value="{{ $default['start_create_date'] }}" />
    	    <input type="hidden" name="end_create_date" value="{{ $default['end_create_date'] }}" />
    	    <input type="hidden" name="country" value="{{ $default['country'] }}" />
    	    <input type="hidden" name="status" value="{{ $default['status'] }}" />

        	<div class="col-md-12 text-right" style="margin-bottom: 10px;">
               	<button type="submit" class="btn btn-default btn-sm" >{!! FT::translate('shipment_list.button.export') !!}</button>
            </div>
        
        </form>
        
        <div class="clearfix"></div>
           	
		<div class="col-md-12 hidden-xs">
            <div class="panel panel-primary ">
                <div class="panel-body">

                    <table class="table table-stripe table-hover">
                    <thead>
                        <tr>
                            <th>{!! FT::translate('label.shipment_id') !!}</th>
                            <th>{!! FT::translate('label.pickup_id') !!}</th>
                            <th>{!! FT::translate('label.create_date') !!}</th>
                            <th>{!! FT::translate('label.receiver') !!}</th>
                            <th>{!! FT::translate('label.destination') !!}</th>
                            <th>{!! FT::translate('label.agent') !!}</th>
                            <th>{!! FT::translate('label.tracking') !!}</th>
                            <th>{!! FT::translate('label.status') !!}</th>
                            <th>{!! FT::translate('label.copy') !!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(sizeof($shipment_data) > 0): 
                        foreach($shipment_data as $data): 
                        $status = (isset($statuses[$data['Status']]))?$statuses[$data['Status']]:$data['Status'];
                        $progress = "";
                        if($status == "cancelled" || $status == "return to sender" || $status == "onhold"){
                            $progress = "progress-0";
                        }else if($status == "quoted"){
                            $progress = "progress-1";
                        }else if($status == "new"){
                            $progress = "progress-2";
                        }else if($status == "ready"){
                            $progress = "progress-3";
                        }else if($status == "agent collected"){
                            $progress = "progress-4";
                        }else if($status == "pre-transit"){
                            $progress = "progress-5";
                        }else if($status == "in-transit"){
                            $progress = "progress-6";
                        }else if($status == "out for delivery"){
                            $progress = "progress-7";
                        }else if($status == "delivered"){
                            $progress = "progress-8";
                        }
                        
                        $duration = ceil((time() - strtotime($data['CreateDate']['date']))/86400);
                        ?>
                        <tr id="shipment_{{ $data['ID'] }}">
                            <td><a href="/shipment_detail/{{ $data['ID'] }}" target="_blank" alt="{{ $duration }}">{{ $data['ID'] }}</a></td>
                            <td><a href="/pickup_detail/{{ $data['PickupID'] }}" target="_blank">{{ $data['PickupID'] }}</a></td>
                            <td>
                            	<span class="small">{{ date("d/m/Y",strtotime($data['CreateDate']['date'])) }}</span>
                            	<?php if($status != "delivered" && false): ?>
                            	<br /><span class="tiny gray">{!! FT::translate('shipment_list.duration.text1') !!} {{ $duration }} {!! FT::translate('shipment_list.duration.text2') !!}</span>
                            	<?php endif; ?>
                            </td>
                            <td>{{ $data['ReceiverDetail']['Firstname'] }} {{ $data['ReceiverDetail']['Lastname'] }}</td>
                            <td>{{ $countries[$data['ReceiverDetail']['Country']] }}</td>
                            <td><img src="{{ url('/images/agent/' . $data['ShipmentDetail']['ShippingAgent'] . '.gif') }}" style="max-width:80px;" /></td>
                            <td class="small">{{ $data['ShipmentDetail']['Tracking'] }}</td>
                            <td>
                            	<div class="ship-progress {{ $progress }}">
                            		<div class="done">
                            			<i class="fa fa-cube"></i>
                            		</div>
                            		<div class="undone"></div>
                            	</div>
                            	<span class="small">{{ $status }}</span>
                            	
                            </td>
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

                </div>
            </div>
        </div>

        <div class="col-xs-12 visible-xs">
            <div class="panel panel-primary">
                <div class="panel-body">
	            <?php 
	            if(sizeof($shipment_data) > 0): 
	            foreach($shipment_data as $data): 
	            $status = (isset($statuses[$data['Status']]))?$statuses[$data['Status']]:$data['Status'];
	            ?>
	            <div class="col-xs-12 shipment-list">
	            	<div class="col-xs-12">
	                    <div class="pull-left"><h4><a href="/shipment_detail/{{ $data['ID'] }}" target="_blank">{{ $data['ID'] }}</a></h4></div>
	                    <div class="pull-right"><h4 style="font-weight: 800; color: #f15a22;">{{ $status }}</h4></div>
	                </div>
	                <div class="clearfix"></div>
	                
                    <div class="col-xs-5"><img src="{{ url('/images/agent/' . $data['ShipmentDetail']['ShippingAgent'] . '.gif') }}" style="max-width:100px;" /></div>
                    <div class="col-xs-7">
                    	<h4 style="margin-bottom: 0px;">{{ $data['ReceiverDetail']['Firstname'] }}</h4>
                    	{{ $countries[$data['ReceiverDetail']['Country']] }}
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php 
	            endforeach;
	            endif;
	            ?>
	            </div>
        	</div>
        	<div class="clearfix" style="margin-bottom: 40px;"></div>
    	</div>

    	<div id="paging" class="text-center">
		<?php 
		// How many pages will there be
		$pages = ceil($total / $limit);
		
		// What page are we currently on?
		$cpage = min($pages, $page);
		
		// Calculate the offset for the query
		$offset = ($cpage - 1)  * $limit;
		
		// Some information to display to the user
		$start = $offset + 1;
		$end = min(($offset + $limit), $total);
		
		// The "back" link
		$prevlink = ($cpage > 1) ? '<a href="javascript:gotoPage(1);" title="First page">&laquo;</a> <a href="javascript:gotoPage('. ($cpage - 1) . ');" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
		
		// The "forward" link
		$nextlink = ($cpage < $pages) ? '<a href="javascript:gotoPage('.($cpage + 1) .');" title="Next page">&rsaquo;</a> <a href="javascript:gotoPage('. $pages  .');" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
		
		// The "current" link
		$currentlink = ($cpage - 3 > 0) ? '<a href="javascript:gotoPage('. ($cpage - 3) . ');" title="">' . ($cpage - 3) . '</a>' : '';
		$currentlink .= ($cpage - 2 > 0) ? '<a href="javascript:gotoPage('. ($cpage - 2) . ');" title="">' . ($cpage - 2) . '</a>' : '';
		$currentlink .= ($cpage - 1 > 0) ? '<a href="javascript:gotoPage('. ($cpage - 1) . ');" title="">' . ($cpage - 1) . '</a>' : '';
		$currentlink .= '<a class="current" href="#" title="" style="background:#ddd;">' . $cpage . '</a>';
		$currentlink .= ($cpage + 1 <= $pages) ? '<a href="javascript:gotoPage('. ($cpage + 1) . ');" title="">' . ($cpage + 1) . '</a>' : '';
		$currentlink .= ($cpage + 2 <= $pages) ? '<a href="javascript:gotoPage('. ($cpage + 2) . ');" title="">' . ($cpage + 2) . '</a>' : '';
		$currentlink .= ($cpage + 3 <= $pages) ? '<a href="javascript:gotoPage('. ($cpage + 3) . ');" title="">' . ($cpage + 3) . '</a>' : '';
		
		echo '<p>Page ', $cpage, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results</p>';
		
		?>
		
		<ul class="pagination">
			<li><?php echo $prevlink; ?></li>
			<li><?php echo $currentlink; ?></li>
			<li><?php echo $nextlink; ?></li>
		</ul>
		</div>
		
    </div>

	
	
<?php else: ?>
	
	<div class="text-center" style="padding-top: 30px;">
		<h4>{!! FT::translate('error.shipment.notfound') !!}</h4>
		<a href="calculate_shipment_rate" class="btn btn-primary">{!! FT::translate('menu.create_shipment') !!}</a>
	</div>
<?php endif; //endif shipment size ?>
</div>
<script type="text/javascript">
    function gotoPage(page){

    	$("#search_form").attr("action","{{ url('/shipment_list') }}/" + page);
    	$("#search_form").submit();
    }
    $(function () {
        $('.create_date').datetimepicker({format: 'YYYY-MM-DD'});
    });
</script>
@endsection