@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
	    <form id="import_form" class="form-horizontal" method="post" action="{{url ('/shipment/upload')}}" enctype="multipart/form-data">
			
			{{ csrf_field() }}
		    
	        <div class="col-md-6 col-md-offset-3">
	        	<div class="panel panel-primary">
					<div class="panel-heading">{!! FT::translate('import_file.heading') !!}</div>
					<div class="panel-body">
					<label class="col-md-4 control-label">{!! FT::translate('label.file') !!}:</label>	
					<div class="col-md-8">
						<input type="file" class="form-control" name="upload" />
						<span class="help"></span>
					</div>
					<div class="clearfix"></div>
					<br />
	
					<div class="text-center btn-create"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">{!! FT::translate('button.upload') !!}</button></div>
	
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
						{!! FT::translate('import_file.download') !!}
						<a href="{{ url("download/FS_IMPORT_ORDERS_TEMPLATE.xlsx")}}" style="color:#f1585a;" download> [xlsx <i class="fa fa-download"></i>]</a>
					</h4>
					
					<table class="table table-stripe table-responsive">
					<thead>
						<tr>
							<th>{!! FT::translate('label.field') !!}</th>
							<th>{!! FT::translate('label.description') !!}</th>
							<th>{!! FT::translate('label.example') !!}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="bold orange">Firstname</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.firstname') !!}</td>
							<td class="small gray text-left"><i>Tommy</i></td>
						</tr>
						<tr>
							<td class="bold orange">Lastname</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.lastname') !!}</td>
							<td class="small gray text-left"><i>Green</i></td>
						</tr>
						<tr>
							<td class="bold orange">Email</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.email') !!}</td>
							<td class="small gray text-left"><i>tommy.green@gmail.com</i></td>
						</tr>
						<tr>
							<td class="bold orange">Phone</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.telephone') !!}</td>
							<td class="small gray text-left"><i>8123456789</i></td>
						</tr>
						<tr>
							<td class="bold orange">Address Line 1</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.address1') !!}</td>
							<td class="small gray text-left"><i>445 Mount Eden Road,</i></td>
						</tr>
						<tr>
							<td class="bold orange">Address Line 2</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.address2') !!}</td>
							<td class="small gray text-left"><i>-</i></td>
						</tr>
						<tr>
							<td class="bold orange">City</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.city') !!}</td>
							<td class="small gray text-left"><i>Mount Eden</i></td>
						</tr>
						<tr>
							<td class="bold orange">State</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.state') !!}</td>
							<td class="small gray text-left"><i>Auckland</i></td>
						</tr>
						<tr>
							<td class="bold orange">Postcode</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.postcode') !!}</td>
							<td class="small gray text-left"><i>5022</i></td>
						</tr>
						<tr>
							<td class="bold orange">Country</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.destination') !!}</td>
							<td class="small gray text-left"><i>New Zealand</i></td>
						</tr>
						<tr>
							<td class="bold orange">Width</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.width') !!}</td>
							<td class="small gray text-left"><i>10</i></td>
						</tr>
						<tr>
							<td class="bold orange">Height</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.height') !!}</td>
							<td class="small gray text-left"><i>20</i></td>
						</tr>
						<tr>
							<td class="bold orange">Length</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.length') !!}</td>
							<td class="small gray text-left"><i>30</i></td>
						</tr>
						<tr>
							<td class="bold orange">Weight</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.weight') !!}</td>
							<td class="small gray text-left"><i>1500</i></td>
						</tr>
						<tr>
							<td class="bold orange">Category</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.declare_type') !!}</td>
							<td class="small gray text-left"><i>Jewellery</i></td>
						</tr>
						<tr>
							<td class="bold orange">Qty</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.declare_qty') !!}</td>
							<td class="small gray text-left"><i>2</i></td>
						</tr>
						<tr>
							<td class="bold orange">Declare Value</td>
							<td class="small text-left" style="text-align:left;">{!! FT::translate('import_file.desc.declare_value') !!}</td>
							<td class="small gray text-left"><i>4500</i></td>
						</tr>
					</tbody>
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>
@endsection