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
			<form id="shipment_form" name="shipment_form" role="form"  class="form-horizontal" method="POST" action="{{url ('shipment/create_etsy')}}">
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
		    	    	<option value="fs_add_etsy_channel" class="small text-secondary">+ เพิ่มช่องทางใหม่</option>
		    	    </select>
		    	    <button type="button" class="btn btn-labeled btn-info" id="sync" onclick="syncEtsy('{{ $account }}')" title="Sync from Etsy">
            			<span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span>Sync from Etsy
            		</button>
            		
            		@if(isset($_REQUEST['debug']) && !(substr($channel['Token'], 0, strlen('v^1')) === 'v^1'))
            		<form method="post" action="{{url ('shipment/add_etsy_channel')}}" style="margin-bottom: 0">
            
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
			<img src="{{ url('images/marketplace/etsy.png') }}" style="max-height: 80px;"/>
		</div>
	</div>

	<div class="row">			
	    <div class="col-md-12">
	    	<div class="panel panel-primary">
				<div class="panel-heading">รายการ Order จาก Etsy ที่จะนำเข้า</div>
				<div class="panel-body">
				@if(count($ordersList) > 0)
					<div class="table-responsive">
					<!--<table class="table table-stripe small">-->
					<table class="table table-stripe table-hover" id="etsy-table">
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
	                            	$etsyId = $val['etsy_id'];
	                            	$receiptId = $val['receipt_id'];
	                    ?>
									<tr id="import_form0">
										<td style=""><?=date("Y/m/d", strtotime($val['create_date']));?></td>
										<td style=""><?=$val['order_id']?></td>
										<td style=""><?=$val['full_name']?></td>
										<td style=""><?=$countries[$val['country_code']]?></td>
										<td style=""><?=$val['shipping_method']?></td>
										<td style="">
			                                <div id="etsyId_<?=$etsyId;?>">
				                                <form id="etsy_create_shipment_<?=$val['etsy_id'];?>" class="form-horizontal" method="post" action="{{url ('shipment/etsy/'.$etsyId)}}">
													{{ csrf_field() }}
													<input type="hidden" name="etsyId" value="<?=$val['etsy_id'];?>" />
													<input type="hidden" name="detail" value='' />
													<input type="hidden" name="account" value="<?=$account;?>" />
												</form>
				                                
				                                <button type="button" class="btn btn-labeled btn-success btn-xs small" onclick="createShipment(<?=$val['etsy_id'];?>)" title="Create shipment">
				                                	<span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Create
				                                </button>

				                                <button type="button" class="btn btn-labeled btn-danger btn-xs small" onclick="cancelShipment(<?php echo $etsyId;?>,'<?php echo $account;?>','<?php echo $receiptId;?>')" title="Delete">
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
					<div class="text-center">ไม่พบรายการสั่งซื้อ <button class="btn btn-info btn-xs" onclick="syncEtsy('<?php echo $account;?>')">Sync from Etsy</button></div>
					@endif
				</div>
	        </div>

	    </div>
	    
	</div>
</div>

<form id="etsy_delete_order" class="form-horizontal" method="post" action="{{url ('shipment/etsy-delete')}}">
	{{ csrf_field() }}
	<input type="hidden" name="etsyId" />
	<input type="hidden" name="sellerId" />
	<input type="hidden" name="receiptId" />
	<input type="hidden" name="account" value="<?=$account?>" />
</form>

<form id="sync_etsy" class="form-horizontal" method="post" action="{{url ('shipment/create_etsy')}}">
	{{ csrf_field() }}
	<input type="hidden" name="command" value="GET" />
	<input type="hidden" name="account" />
</form>

<script type="text/javascript">

	function syncEtsy(account){
		$("#sync_etsy input[name=account]").val(account);
		$("#sync_etsy").submit();
    }

	function createShipment(etsyId){
		//$("#etsy_create_shipment input[name=etsyId]").val(etsyId);
		//$("#etsy_create_shipment input[name=detail]").val(detail);
		$("#etsy_create_shipment_"+etsyId).submit();
    }

    /*function delOrder(etsyId){
      if(confirm("คุณต้องการลบรายการสินค้านี้ใช่หรือไม่?")){
        $("#etsy_delete_order input[name=etsyId]").val(etsyId);
        $("#etsy_delete_order").submit();
      }
    }*/

    /*function editAdmin(etsyId){

		if(confirm("คุณต้องการแก้ไขข้อมูลผู้ใช้งานนี้ใช่หรือไม่?")){
			return true;
			$("#etsy_delete_order input[name=etsyId]").val(etsyId);
			$("#etsy_delete_order").submit();
		}else {
			return false;
		}
    }*/

    function cancelShipment(etsyId,sellerId,receiptId){
		if(confirm("คุณต้องการลบรายการสินค้านี้ใช่หรือไม่?")){
			$("#etsy_delete_order input[name=etsyId]").val(etsyId);
			$("#etsy_delete_order input[name=sellerId]").val(sellerId);
			$("#etsy_delete_order input[name=receiptId]").val(receiptId);
			$("#etsy_delete_order").submit();
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
	    $('#etsy-table').dataTable( {
		  "pageLength": 25
		} );
	} );*/
</script>
<script type="text/javascript">
	$(document).ready( function () {
	    //$('#data-table').DataTable();
	    $('#etsy-table').dataTable( {
	    	"pageLength": 50,
        	aaSorting : [[0, 'desc']],
		}); 			
	} );
</script>
<script type="text/javascript">

    
</script>
@endsection