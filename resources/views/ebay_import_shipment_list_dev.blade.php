@extends('layout')
@section('content')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>

<style type="text/css">
	.btn-label {position: relative;left: -6px;display: inline-block;padding: 3px 6px;border-radius: 3px 0 0 3px;}
	.btn-labeled {padding-top: 0;padding-bottom: 0; }
	.dataTables_info { margin-top: 12px }
	table.dataTable thead .sorting{ background: none; }
}
</style>
<div class="conter-wrapper">

	<div class="row">
		<div class="col-md-8">
			<form id="shipment_form" name="shipment_form" role="form"  class="form-horizontal" method="POST" action="{{url ('dev/shipment/create_ebay')}}">
    			{{ csrf_field() }}
    			<input type="hidden" name="command" value="PULL" />
    			<input type="hidden" name="filter_type" value="2" />
			   	<div class="form-inline">
			      <!--<div class="form-group">-->
			      <div>
			      	<label>Account:</label>
			      	<select class="form-control form-inline" name="account" onchange="this.form.submit()">
		    	    	@foreach($customerChannels as $channel)
		        	    	@if($account == $channel['AccountName'])
		        	    	<option selected>{{ $channel['AccountName'] }}</option>
		        	    	@else
		        	    	<option>{{ $channel['AccountName'] }}</option>
		        	    	@endif
		    	    	@endforeach
		    	    	<option value="fs_add_ebay_channel" class="small text-secondary">+ เพิ่มช่องทางใหม่</option>
		    	    </select>
		    	    <button type="button" class="btn btn-labeled btn-info" id="sync" onclick="synceBay('{{ $account }}')" title="Sync from eBay">
            			<span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span>Sync from eBay
            		</button>
            		
            		@if(isset($_REQUEST['debug']) && !(substr($channel['Token'], 0, strlen('v^1')) === 'v^1'))
            		<form method="post" action="{{url ('shipment/add_ebay_channel')}}" style="margin-bottom: 0">
            
            			{{ csrf_field() }}
            			
            			<input type="hidden" name="channel" value="<?php echo $channel['AccountName'];?>" />
            			<input type="hidden" name="command" value="update" />
            			
            			<span class="text-danger tiny">กรุณาต่ออายุ </span>
            			<button class="btn btn-success" type="submit"><i class="fa fa-refresh"></i></button>
    	                
                    </form>
            		@endif
			      </div>

			      <!--<div class="form-group">
			      	<label>ZIP</label>
			        <input required type="text" name="zip" id="zip" class="form-control" placeholder="Zip"/>
			      </div>-->
			   </div>
			</form>

			<div class="form-inline">
		      <div class="form-group">
		      	<label>Last Sync:</label>
		      	<?=$latest_day?>
		      </div>

		      <!--<div class="form-group">
		      	<label>ZIP</label>
		        <input required type="text" name="zip" id="zip" class="form-control" placeholder="Zip"/>
		      </div>-->
		   </div>

		</div>
		<div class="col-md-4 text-right">
			<img src="{{ url('images/marketplace/ebay.png') }}" style="max-height: 80px;"/>
		</div>
	</div>

	<div class="row">			
	    <div class="col-md-12">
	    	<div class="panel panel-primary">
				<div class="panel-heading">รายการ Order จาก eBay ที่จะนำเข้า</div>
				<div class="panel-body">
				@if(count($ordersList) > 0)
					<div class="table-responsive">
					<!--<table class="table table-stripe small">-->
					<table class="table table-stripe table-hover" id="ebay-table">
					<thead>
						<tr>
							<th style="" class="text-left col-md-1">วันที่</th>
							<th style="" class="text-left col-md-2">Order ID</th>
							<th style="" class="text-left col-md-3">ข้อมูลผู้รับ</th>
							<th style="" class="text-left col-md-2">ประเทศปลายทาง</th>
							<th style="" class="text-left col-md-2">Shipping Agent</th>
							<th style="" class="text-left col-md-4"></th>
						</tr>
					</thead>
					<tbody>
					<?php 
						$i=1;
						$total=0;
	                            foreach ($ordersList as $key => $val) {
	                            	$ebayId = $val['ebay_id'];
	                            	//$detail = '';
	                            	//$detail2 = json_encode($val);
	                    ?>
									<tr id="import_form0">
										<td style=""><?=date("Y/m/d", strtotime($val['creation_date']));?></td>
										<td style=""><?=$val['order_id']?></td>
										<td style=""><?=$val['full_name']?></td>
										<td style=""><?=$country2iso[$val['country_code']]?></td>
										<td style=""><?=$val['shipping_service_code']?></td>
										<td style="">
			                                <!--<button type="button" class="btn btn-success btn-sm submit-btn-group" onclick="importShipment(0)">สรัางพัสดุ</button>
			                                	<button type="button" class="btn btn-danger btn-sm" onclick="cancelShipment(0)">ลบ</button>-->
			                                <div id="ebayId_<?=$ebayId;?>">
				                                <form id="ebay_create_shipment_<?=$val['ebay_id'];?>" class="form-horizontal" method="post" action="{{url ('dev/shipment/ebay/'.$ebayId)}}">
													{{ csrf_field() }}
													<input type="hidden" name="ebayId" value="<?=$val['ebay_id'];?>" />
													<input type="hidden" name="detail" value='' />
													<input type="hidden" name="account" value="<?=$account;?>" />
													<!--<button type="submit" class="btn btn-success btn-sm submit-btn-group" >สรัางพัสดุ</button>-->
												</form>
				                                <!--<button type="button" class="btn btn-success btn-sm submit-btn-group" onclick="createShipment(<?=$val['ebay_id'];?>)" title="<?=$val['ebay_id'];?>">สรัางพัสดุ</button>-->
				                                <!--<button type="button" class="btn btn-danger btn-sm" onclick="cancelShipment(<?php echo $ebayId;?>,'<?php echo $sellerId;?>')">ลบ</button>-->

				                                <button type="button" class="btn btn-labeled btn-success btn-xs small" onclick="createShipment(<?=$val['ebay_id'];?>)" title="Create shipment">
				                                	<span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Create
				                                </button>

				                                <button type="button" class="btn btn-labeled btn-danger btn-xs small" onclick="cancelShipment(<?php echo $ebayId;?>,'<?php echo $sellerId;?>')" title="Delete">
				                                    <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Delete
				                                </button>
			                            	</div>
			                            </td>
									</tr>
						<?php
									$i++;
								}
                        ?>

					</tbody>
					</table>
					</div>
					@else
					<div class="text-center">ไม่พบรายการสั่งซื้อ <button class="btn btn-info btn-xs" onclick="synceBay('<?php echo $account;?>')">Sync from eBay</button></div>
					@endif
				</div>
	        </div>

	    </div>
	    
	</div>
</div>

<form id="ebay_delete_order" class="form-horizontal" method="post" action="{{url ('shipment/ebay-delete')}}">
	{{ csrf_field() }}
	<input type="hidden" name="ebayId" />
	<input type="hidden" name="sellerId" />
	<input type="hidden" name="account" value="<?=$account?>" />
</form>

<form id="sync_eBay" class="form-horizontal" method="post" action="{{url ('shipment/create_ebay')}}">
	{{ csrf_field() }}
	<input type="hidden" name="command" value="GET" />
	<input type="hidden" name="account" />
</form>

<script type="text/javascript">

	function synceBay(account){
		$("#sync_eBay input[name=account]").val(account);
		$("#sync_eBay").submit();
    }

	function createShipment(ebayId){
		//$("#ebay_create_shipment input[name=ebayId]").val(ebayId);
		//$("#ebay_create_shipment input[name=detail]").val(detail);
		$("#ebay_create_shipment_"+ebayId).submit();
    }

    /*function delOrder(ebayId){
      if(confirm("คุณต้องการลบรายการสินค้านี้ใช่หรือไม่?")){
        $("#ebay_delete_order input[name=ebayId]").val(ebayId);
        $("#ebay_delete_order").submit();
      }
    }*/

    /*function editAdmin(ebayId){

		if(confirm("คุณต้องการแก้ไขข้อมูลผู้ใช้งานนี้ใช่หรือไม่?")){
			return true;
			$("#ebay_delete_order input[name=ebayId]").val(ebayId);
			$("#ebay_delete_order").submit();
		}else {
			return false;
		}
    }*/

    function cancelShipment(ebayId,sellerId){
		if(confirm("คุณต้องการลบรายการสินค้านี้ใช่หรือไม่?")){
			$("#ebay_delete_order input[name=ebayId]").val(ebayId);
			$("#ebay_delete_order input[name=sellerId]").val(sellerId);
			$("#ebay_delete_order").submit();
		}else {
			return false;
		}
    }
    
</script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
	/*$(document).ready( function () {
	    //$('#data-table').DataTable();
	    $('#ebay-table').dataTable( {
		  "pageLength": 25
		} );
	} );*/
</script>
<script type="text/javascript">
	$(document).ready( function () {
	    //$('#data-table').DataTable();
	    $('#ebay-table').dataTable( {
	    	"pageLength": 50,
        	aaSorting : [[0, 'desc']],
		}); 			
	} );
</script>
<script type="text/javascript">

    
</script>
@endsection