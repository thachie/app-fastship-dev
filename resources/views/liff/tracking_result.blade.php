@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">ประวัติสถานะการส่งพัสดุ</h3>
		<hr />
	</div>
	
	<div class="col col-12">
		<p>หมายเลขติดตามพัสดุ <span class="text-primary">{{ $tracking }}</span></p>
	</div>
	
    @if($trackingResult)
    <div class="row">
    	<div class="col col-12">
    		@if(sizeof($trackingResult['Events'])>0)
    		@php
            	$descEvents = $trackingResult['Events'];
            	krsort($descEvents);
            @endphp
            @foreach($descEvents as $event)
            @if($event['Status'] == 1001)
            	<h4 class="text-secondary"><i class="fa fa-edit"></i> {{ $event['Description'] }}</h4>
            @elseif($event['Status'] == 1002)
            	<h4 class="text-info"><i class="fa fa-plane"></i> {{ $event['Description'] }}</h4>
            @elseif($event['Status'] == 1003)
            	<h4 class="text-warning"><i class="fa fa-road"></i> {{ $event['Description'] }}</h4>
            @elseif($event['Status'] == 1004)
            	<h3 class="text-success"><i class="fa fa-smile-o"></i> {{ $event['Description'] }}</h3>
            @elseif($event['Status'] == 1005 || $event['Status'] == 1006)
            	<h3 class="text-danger"><i class="fa fa-smile-o"></i> {{ $event['Description'] }}</h3>
            @else
            	<h4><i class="fa fa-check"></i> {{ $event['Description'] }}</h4>
            @endif
        	<p class="text-secondary">
        		<span class="badge bg-light text-dark" title="{{ date("H:i:s  d/m/Y",strtotime($event['Datetime'])) }}">{{ date("d/m/Y",strtotime($event['Datetime'])) }}</span>
        		{{ $trackingStatus[$event['Status']] }} {{ ($event['Location'])?"at ".$event['Location']:"" }}
        	</p>
        	<hr />
            @endforeach
            @endif
    	</div>
	</div>
	@else
	<div class="row">
    	<div class="col col-12">
    		ไม่พบสถานะการส่ง
    	</div>
	</div>
	@endif
	
	<div class="row">
    	<div class="col col-12 text-center">
    		<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;margin-top: 10px;" onclick="history.back();">ย้อนกลับ</button>
    	</div>
    </div>
	
</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){

});
-->
</script>
@endsection