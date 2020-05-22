@extends('liff/layout')
@section('content')
<div class="conter-wrapper" >

	<div class="row">
		<div class="col col-12">
			<h3 class="text-orange">ประวัติสถานะการส่งพัสดุ</h3>
			<hr />
		</div>
		<div class="col col-12">
			<p>หมายเลขติดตามพัสดุ <span class="text-primary">{{ $tracking }}</span></p>
		</div>
	</div>
	
    @if($trackingResult)
    	@if(sizeof($trackingResult)>0)
    	@php
            krsort($trackingResult);
            $currentDate = "";
        @endphp
        @foreach($trackingResult as $event)
        
        	@php
            $description = isset($event['description'])?$event['description']:$event['address'];
            @endphp
            
        	@if($currentDate != date("d/m/Y",strtotime($event['datetime'])))
           	@php
            	$currentDate = date("d/m/Y",strtotime($event['datetime']));
            @endphp
            <div class="row" style="margin-top:10px;margin-bottom:10px;">
	            <div class=" col-xs-12 text-center">
	            	<h6><span class="text-dark" style="padding: 5px 40px;background: #eaeaea;">{{ $currentDate }}</span></h6>
		        </div>
            </div>
            @endif
            
            <div class="row" style="margin:0px;">
            	<div class="col-xs-2 text-right">{{ date("H:i",strtotime($event['datetime'])) }}</div>
	            <div class="col-xs-10">
		            @if($event['status'] == 1001)
		            	<span class="text-secondary">{{ $description }}</span>
		            @elseif($event['status'] == 1002)
		            	<span class="text-info">{{ $description }}</span>
		            @elseif($event['status'] == 1003)
		            	<span class="text-warning">{{ $description }}</span>
		            @elseif($event['status'] == 1004)
		            	<span class="text-success"><i class="fa fa-check"></i> {{ $description }}</span>
		            @elseif($event['status'] == 1005 || $event['status'] == 1006)
		            	<span class="text-danger"><i class="fa fa-alert"></i> {{ $description }}</span>
		            @else
		            	<span class="text-dark">{{ $description }}</span>
		            @endif
		        	<p class="small">
		        		{{ isset($trackingStatus[$event['status']])?$trackingStatus[$event['status']]:"" }} {{ ($event['location'])?"at ".$event['location']:"" }}
		        	</p>
	        	</div>
        	</div>
        	
        @endforeach
       	@endif
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