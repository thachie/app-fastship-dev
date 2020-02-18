@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
	    <form id="import_form" class="form-horizontal" method="post" action="{{url ('/shipment/upload_ebay')}}" enctype="multipart/form-data">
			
			{{ csrf_field() }}
		    
	        <div class="col-md-6 col-md-offset-3">
	        	<div class="panel panel-primary">
					<div class="panel-heading">Import Ebay Order CSV File</div>
					<div class="panel-body">
					<label class="col-md-4 control-label">File:</label>	
					<div class="col-md-8">
						<input type="file" class="form-control" name="upload" />
						<span class="help"></span>
					</div>
					<div class="clearfix"></div>
					<br />
	
					<div class="text-center btn-create"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Upload</button></div>
	
					</div>
	        	</div>
	        </div>
	        
	    </form>
	</div>
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-primary">
			
				<div class="panel-body">
					<h4 class="text-center">
						ดาวน์โหลดตัวอย่างไฟล์
						<a href="{{ url("download/EBAY_IMPORT_ORDERS_TEMPLATE_DATA.csv")}}" style="color:#f1585a;" download> [csv <i class="fa fa-download"></i>]</a>
					</h4>
					
					<table class="table table-stripe table-responsive">
					<thead>
						<tr>
							<th>Field</th>
							<th>Description</th>
							<th>Example</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="bold orange">Sale Record Number</td>
							<td class="small text-left" style="text-align:left;">หมายเลขใบสั่งซื้อ</td>
							<td class="small gray text-left"><i>32514</i></td>
						</tr>
						<tr>
							<td class="bold orange">User Id</td>
							<td class="small text-left" style="text-align:left;">วันที่สั่งซื้อ</td>
							<td class="small gray text-left"><i>Nov 19,2018 10:08:55 PM</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Fullname</td>
							<td class="small text-left" style="text-align:left;">ชื่อผู้ซื้อ</td>
							<td class="small gray text-left"><i>Mark Clarke</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Phone Number</td>
							<td class="small text-left" style="text-align:left;">ชื่อผู้รับ</td>
							<td class="small gray text-left"><i>Mark Clarke</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Email</td>
							<td class="small text-left" style="text-align:left;">ยอดรวม(สกุลเงินหลัก)</td>
							<td class="small gray text-left"><i>US$83.29</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Address 1</td>
							<td class="small text-left" style="text-align:left;">ยอดรวม(สกุลเงินที่สั่ง)</td>
							<td class="small gray text-left"><i>US$83.29</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Address 2</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer City</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer State</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Zip</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Coutry</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>United States</i></td>
						</tr>
						<tr>
							<td class="bold orange">Item Number</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Item Title</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Custom Label</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Quantity</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Sale Price</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping and Handling</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">US Tax</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Insurance</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Cash on delivery fee</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Total Price</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Payment Method</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Sale Date</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Checkout Date</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Paid on Date</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipped on Date</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Feedback received</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Note to yourself</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Unique Product Id</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Private Field</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">ProductIDType</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">ProductIDValue</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">ProductIDValue-2</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Paypal Transaction ID</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Service</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Cash on delivery option</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Transaction ID</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Order ID</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Variation Details</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Global Shipping Program</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Global Shipping Reference ID</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Ship To Address 1</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Ship To Address 2</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Ship To City</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Ship To State</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Ship To Zip</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Ship To Country</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
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