@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
		<h3 class="text-orange">5. สรุปข้อมูลพัสดุ</h3>
		<hr />
	</div>
	<div class="row">
    	
    	<div class="col col-4 text-center">
    		<img src="/images/agent/{{ session('liff.agent') }}.gif" style="border-radius: 5px;width: 100%;">
    	</div>
    	<div class="col col-8">
    		<div class="text-secondary">พัสดุหนัก {{ session('liff.weight') }} กรัม </div>
    		@if(session('liff.width') != '')<div class="text-primary">ขนาด {{ session('liff.width') }}x{{ session('liff.height') }}x{{ session('liff.length') }} ซม.</div>@endif
    		
    		<div class="text-success">{{ session('liff.rate') }}.-</div>
    	</div>
    	

   	</div>
	
	<div class="row">
    	<div class="col col-12">
        	<table class="table table-sm table-striped table-bordered table-default small" style="margin-bottom: 0;">
                <thead>
                  <tr>
                    <th class="text-center">ประเภทสินค้า</th>
                    <th class="text-center">จำนวน</th>
                    <th class="text-center">มูลค่ารวม</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($declares as $declare)
                  <tr>
                    <td>{{ (strlen($declare['type']) > 30) ? substr($declare['type'],0,30)." ...":$declare['type'] }}</td>
                    <td class="text-center">{{ $declare['qty'] }}</td>
                    <td class="text-center text-primary">{{ $declare['value'] }}</td>
                  </tr>
                @endforeach
                </tbody>
            </table>
            <span class="small text-secondary">* มูลค่าในหน่วยบาท</span>
    	</div>
    </div>

	<div class="row">
    	<div class="col col-12">
        	<div class="panel panel-warning panel-small">
        		<div class="panel-heading"><span class="small">ข้อมูลผู้รับ</span></div>
        		<div class="panel-body bg-light">
        			@if(session('liff.company'))
        				<div class="text-success"><strong>{{ session('liff.company') }}</strong></div>
        				<div>{{ session('liff.firstname') }} {{ session('liff.lastname') }}</div>
        			@else
        				<div class="text-success"><strong>{{ session('liff.firstname') }} {{ session('liff.lastname') }}</strong></div>
        			@endif
        			<div class="text-secondary">{{ session('liff.address1') }} {{ session('liff.address2') }} {{ session('liff.city') }} {{ session('liff.state') }} {{ session('liff.postcode') }} {{ $country->name }}</div>
        			<div class="text-primary"><i class="fa fa-envelope"></i> {{ session('liff.email') }}</div>
        			<div class="text-info"><i class="fa fa-phone"></i> {{ session('liff.phonenumber') }}</div>

        		</div>
        	</div>
        </div>
    </div>
	
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/action/create_shipment') }}">
		
		<input type="hidden" name="country" id="country" value="{{ session('liff.country') }}" />
		
		<div id="submit_form" class="row">
    		<div class="col col-12 ">
        		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0 ">สร้างพัสดุ</button>
            	<button type="button" class="btn btn-light btn-block btn-sm border-0" onclick="history.back();">ย้อนกลับ</button>
        	</div>
        </div>
    
    </form>

</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){
	

});
-->
</script>
@endsection