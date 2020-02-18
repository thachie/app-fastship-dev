@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
	    <form id="import_form" class="form-horizontal" method="post" action="{{url ('/shipment/upload_amazon')}}" enctype="multipart/form-data">
			
			{{ csrf_field() }}
		    
	        <div class="col-md-6 col-md-offset-3">
	        	<div class="panel panel-primary">
					<div class="panel-heading">Import Amazon Order CSV File</div>
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
						ดาวน์โหลดข้อมูลจาก <a href="https://www.amazon.com/gp/b2b/reports" target="_blank">Order History Report</a><br />
						ไฟล์ตัวอย่าง
						<a href="{{ url("download/AMAZON_IMPORT_ORDERS_TEMPLATE_DATA.txt")}}" style="color:#f1585a;" download> [txt <i class="fa fa-download"></i>]</a>
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
							<td class="bold orange">Order Date</td>
							<td class="small text-left" style="text-align:left;">หมายเลขใบสั่งซื้อ</td>
							<td class="small gray text-left"><i>32514</i></td>
						</tr>
						<tr>
							<td class="bold orange">Order ID</td>
							<td class="small text-left" style="text-align:left;">วันที่สั่งซื้อ</td>
							<td class="small gray text-left"><i>Nov 19,2018 10:08:55 PM</i></td>
						</tr>
						<tr>
							<td class="bold orange">Payment Instrument Type</td>
							<td class="small text-left" style="text-align:left;">ชื่อผู้ซื้อ</td>
							<td class="small gray text-left"><i>Mark Clarke</i></td>
						</tr>
						<tr>
							<td class="bold orange">Website</td>
							<td class="small text-left" style="text-align:left;">ชื่อผู้รับ</td>
							<td class="small gray text-left"><i>Mark Clarke</i></td>
						</tr>
						<tr>
							<td class="bold orange">Purchase Order Number</td>
							<td class="small text-left" style="text-align:left;">ยอดรวม(สกุลเงินหลัก)</td>
							<td class="small gray text-left"><i>US$83.29</i></td>
						</tr>
						<tr>
							<td class="bold orange">Order Customer Email</td>
							<td class="small text-left" style="text-align:left;">ยอดรวม(สกุลเงินที่สั่ง)</td>
							<td class="small gray text-left"><i>US$83.29</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipment Date</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Address Name</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Address Street 1</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Address Street 2</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Address City</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Address State</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Address Zip</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Order Status</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>United States</i></td>
						</tr>
						<tr>
							<td class="bold orange">Carrier Name & Tracking Number</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Subtotal</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Shipping Charge</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Tax Before Promotions</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Total Promotions</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Tax Charged</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Total Charged</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Buyer Name</td>
							<td class="small text-left" style="text-align:left;">สถานะการซื้อ</td>
							<td class="small gray text-left"><i>Processing</i></td>
						</tr>
						<tr>
							<td class="bold orange">Group Name</td>
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