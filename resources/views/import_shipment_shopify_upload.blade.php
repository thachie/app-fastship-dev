@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
	    <form id="import_form" class="form-horizontal" method="post" action="{{url ('/shipment/upload_shopify')}}" enctype="multipart/form-data">
			
			{{ csrf_field() }}
		    
	        <div class="col-md-6 col-md-offset-3">
	        	<div class="panel panel-primary">
					<div class="panel-heading">Import Shopify Order CSV File</div>
					<div class="panel-body">
    					<label class="col-md-4 control-label">File:</label>	
    					<div class="col-md-8">
    						<input type="file" class="form-control" name="upload" />
    						<span class="help"></span>
    					</div>
    					<div class="clearfix"></div>
    					<br />
    	
    					<div class="text-center btn-create">
    						<button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Upload and Review</button>
    					</div>
    					<div class="clearfix"></div>
    					<br />
    					
    					<h4 class="text-center">
    						ดาวน์โหลดตัวอย่างไฟล์
    						<a href="{{ url("download/SHOPIFY_IMPORT_ORDERS_TEMPLATE_DATA.csv")}}" style="color:#f1585a;" download> [csv <i class="fa fa-download"></i>]</a>
    					</h4>
    					
	
					</div>
	        	</div>
	        </div>
	        
	    </form>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-body">
            		<h3>Export from Shopify Steps:</h3>
                    <ol>
                    <li><p><a href="https://help.shopify.com/en/manual/orders/en/manual/orders/search-view-print-orders#sort-your-orders-list">Sort and filter your orders</a>.</p></li>
                    <li><p>From the <a href="//www.shopify.com/admin/orders"><strong>Orders</strong></a> page, click <strong>Export</strong>.</p></li>
                    <li>
                    <p>In the <strong>Export orders</strong> window:</p>
                    <ol>
                    <li>Select the option for the orders that you want to export. For example, if you want to export your orders by date, then click <strong>Export orders by date</strong> and set the start and end dates for the orders that you want to export.
                    </li>
                    <li>Choose the format that you want to export your orders to: <figure class="figure"><img src="https://help.shopify.com/assets/manual/orders/export-modal-search-excel-4b2b6328d73d064f84c9129a6301c9c879831985f7800742f86b4841492f1d63.png" width="467" height="321"></figure>
                    </li>
                    </ol>
                    </li>
                    <li><p>If you want to download all information about your orders, then click <strong>Export orders</strong>. If you want to download your transaction information only, then click <strong>Export transaction histories</strong>.</p></li>
                    </ol>
                    <div class="clearfix"></div>
    				<br />
    				<div class="text-center">
    					<a href="https://help.shopify.com/en/manual/orders/export-orders" target="_blank">Learn more</a>
    				</div>
    					
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-primary">
			
				<div class="panel-body">
					
					<h3>Order import concern fields</h3>

					<table class="table table-stripe table-responsive">
					<thead>
						<tr>
							<th width="30%">Header</th>
							<th>Definition</th>
						</tr>
					</thead>
					<tbody>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Name</td>
                          <td class="small" style="text-align:left;">The order number as it appears in your store admin</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Phone</td>
                          <td class="small" style="text-align:left;">The customer's phone number</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Email</td>
                          <td class="small" style="text-align:left;">The customer's email address</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Subtotal</td>
                          <td class="small" style="text-align:left;">The order's subtotal before shipping and taxes.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping</td>
                          <td class="small" style="text-align:left;">The total cost of shipping for the order.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Taxes</td>
                          <td class="small" style="text-align:left;">The amount of taxes charged on an order.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Total</td>
                          <td class="small" style="text-align:left;">The total cost of the order.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Discount Code</td>
                          <td class="small" style="text-align:left;">The discount code that was applied to the order.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Discount Amount</td>
                          <td class="small" style="text-align:left;">The amount of the discount applied to an order.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Method</td>
                          <td class="small" style="text-align:left;">The shipping method used to ship the order.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Created at</td>
                          <td class="small" style="text-align:left;">When the order was completed by the customer.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Lineitem quantity</td>
                          <td class="small" style="text-align:left;">The quantity of a line item (product/variant from your products menu).</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Lineitem name</td>
                          <td class="small" style="text-align:left;">The name of a line item.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Lineitem price</td>
                          <td class="small" style="text-align:left;">The price of the line item.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Lineitem SKU</td>
                          <td class="small" style="text-align:left;">The line item SKU.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Name</td>
                          <td class="small" style="text-align:left;">The first and last name of the customer.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Street</td>
                          <td class="small" style="text-align:left;">The name of the street entered for the shipping address.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Address1</td>
                          <td class="small" style="text-align:left;">The full first line of the shipping address -- for example, <i>150 Elgin St</i>.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Address2</td>
                          <td class="small" style="text-align:left;">The full second line of the shipping address -- for example, <i>Suite 800</i>. This column is often empty.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Company</td>
                          <td class="small" style="text-align:left;">The customer's company name. This column often empty.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping City</td>
                          <td class="small" style="text-align:left;">The customer's shipping address city.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Zip</td>
                          <td class="small" style="text-align:left;">The customer's shipping address ZIP or postal code.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Province</td>
                          <td class="small" style="text-align:left;">The customer's shipping state or province.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Country</td>
                          <td class="small" style="text-align:left;">The customer's shipping country.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Shipping Phone</td>
                          <td class="small" style="text-align:left;">The customer's shipping phone number.</td>
                        </tr>
                        <tr>
                          <td class="bold orange" translate="no" style="text-align:left;">Notes</td>
                          <td class="small" style="text-align:left;">The notes included on the order.</td>
                        </tr>
                      </tbody>
					</table>
					
				</div>
				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready( function() {
	    
	    
	});
</script>
@endsection