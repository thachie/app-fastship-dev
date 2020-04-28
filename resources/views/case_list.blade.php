@extends('layout')
@section('content')
<div class="conter-wrapper">

	<div class="row">
    	<div class="col-md-12"><h2>Cases ({{ sizeof($cases) }})</h2></div>
	</div>

	@if(sizeof($cases) > 0)
    <div class="row">
		<div class="col-md-12">
            <div class="panel panel-primary ">
            	<div class="panel-heading">รายการ Case</div>
            	<div class="panel-body">

                    <table class="table table-stripe table-hover">
                    <thead>
                        <tr>
                        	<th>Reference</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Issue by</th>
                            <th>Create</th>
                            <th>Modified</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cases as $data)
                    <tr id="case_{{ $data['ID'] }}">
                    	<td><a href="{{ url('/' . strtolower($data['ReferenceType']). '_detail/' . $data['ReferenceId']) }}">{{ $data['ReferenceId'] }}</a></td>
                        <td>
                        	<div class=" text-left">#{{ $data['ID'] }} {{ $data['Category'] }}</div>
                        	<div class="small gray text-left">{{ substr($data['Detail'],0,50) }}</div>
                        </td>
                        <td><span class="badge bagde-sm"><span class="small">{{ $data['Status'] }}</span></span></td>
                        @if($data['CustomerId'] == session('customer.id'))
                        <td>{{ session('customer.name') }}</td>
                        @else
                        <td>Fastship</td>
                        @endif
                        <td><span class="small">{{ date("d/m/Y H:i:s",strtotime($data['CreateDate'])) }}</span></td>
                        <td><span class="small">{{ date("d/m/Y H:i:s",strtotime($data['UpdateDate'])) }}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>

                </div>
            </div>
        </div>
        

    </div>

	@else
	
	<div class="text-center" style="padding-top: 30px;">
		<h4>ไม่พบปัญหา</h4>
	</div>
	
	@endif
	
		<div class="col-md-12 text-center">
        	<a href="{{ url('add_case') }}"><button type="button" class="btn btn-primary btn-lg">+ เพิ่มปัญหาใหม่</button></a>
        </div>
</div>
@endsection