@extends('layout')
@section('content')
<div class="conter-wrapper">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

        	<div class="panel panel-primary">
    			<div class="panel-body">
    		    
    		    	<div class="col-md-6">
    		    		<div class="mb-10">
    		    			<img src="/images/logo-1.png"  style="max-height: 40px;"/> 
    		    			<span style="vertical-align: middle;">✖</span>
    		    			<img src="{{ url('images/marketplace/ebay.png') }}" style="max-width:120px;margin-right: 10px;" /><img src="https://www.countryflags.io/{{ $marketplace['country'] }}/flat/48.png" >
    		    			</div>
    		    		<br />
    		    		
    		    		<div>
    		    		<form id="add_form" name="add_form" class="form-horizontal" method="post" action="{{url ('shipment/add_ebay_channel')}}">

                    		{{ csrf_field() }}
                    		
                    		<input type="hidden" name="marketplace" value="{{ $site }}" />
                    		
        		    		<label>Store Name (ชื่อเรียกร้านค้า)</label>
        		    		<input type="text" class="form-control required" name="channel" placeholder="your eBay {{ $marketplace['name'] }} store name" required/>
        		    		
            		    	<div class="text-right" style="margin: 5px 0;"><button type="submit" class="btn btn-sm btn-primary">Add Channel</button></div>
        		    	
        		    	</form>
    		    		</div>
    		    		
    		    		<div style="margin-top: 50px;">
    		    			<a href="{{ url('/add_channel') }}" class="small gray">&lt; กลับหน้าที่แล้ว</a>
    		    		</div>
    		    		
    		    	</div>
    		    	<div class="col-md-6 well">
    		    		<h2>ebay {{ $marketplace['name'] }}</h2>
    		    		<p class="lead">การเชื่อมต่อ จะช่วยให้ท่านกรอกข้อมูลที่อยู่ลูกค้าได้โดยอัตโนมัติและอัพเดต Tracking number และ Mark shipped โดยอัตโนมัติ</p>
    		    		
    		    		<ol class="large" style="font-size: 1.1em;">
        		    		<li>ใส่ชื่อเรียกร้านค้า ไม่จำเป็นต้องเป็นชื่อบัญชี eBay</li>
        		    		<li>กดปุ่ม Add Channel</li>
        		    		<li>ระบบจะส่งไปหน้า login ของ eBay เพื่ออนุญาตการเชื่อมต่อ (สำหรับท่านที่มีบัญชี eBay หลายบัญชี กรุณา logout ออกจาก eBay เดิมก่อน)</li>
    		    		</ol>
    		    	</div>
    		    	
    		    </div>
    		</div>
        </div>

    </div>
</div>
@endsection