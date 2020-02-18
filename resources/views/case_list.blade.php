@extends('layout')
@section('content')
<div class="conter-wrapper">

	<div class="row">
    	<div class="col-md-12"><h2>Cases ({{ sizeof($cases) }})</h2></div>
	</div>

<?php if(sizeof($cases) > 0): ?>
    <div class="row">
		<div class="col-md-12">
            <div class="panel panel-primary ">
                <div class="panel-body">

                    <table class="table table-stripe table-hover">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Shipment ID</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Create</th>
                            <th>Modified</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(sizeof($cases) > 0): 
                        foreach($cases as $data): 
                        ?>
                        <tr id="case_{{ $data['Case_Number'] }}">
                            <td><div title="{{ $data['Description'] }}">{{ $data['Subject'] }} <span class="fa fa-ellipsis-h small gray"></span></div></td>
                            <td>{{ $data['Shipment_ID'] }}</td>
                            <td>{{ $data['Priority'] }}</td>
                            <td>{{ $data['Status'] }}</td>
                            <td>{{ date("d/m/Y H:i:s",strtotime($data['Created_Time'])) }}</td>
                            <td>{{ date("d/m/Y H:i:s",strtotime($data['Modified_Time'])) }}</td>
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
        <div class="col-md-12 text-center">
        	<a href="{{ url('add_case') }}"><button type="button" class="btn btn-primary">+ add case</button></a>
        </div>
    </div>

<?php else: ?>
	
	<div class="text-center" style="padding-top: 30px;">
		<h4>ไม่พบ Case</h4>
		<a href="add_case" class="btn btn-primary">+ add case</a>
	</div>
<?php endif; //endif shipment size ?>
</div>
@endsection