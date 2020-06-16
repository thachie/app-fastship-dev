@extends('layout')
@section('content')
<?php 
//alert($pickup_data); 
if($pickup_data['Status'] == "Unpaid"){
    $PickupStatus = FT::translate('status.pickup.status11');
    $step0 = array("active","timeline-event-success","fa-check");
    $step1 = array("","opacity-4","fa-ellipsis-h");
    $step2 = array("","opacity-4","fa-ellipsis-h");
    $step3 = array("","opacity-4","fa-ellipsis-h");
    $step4 = array("","opacity-4","fa-ellipsis-h");
    $step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "New"){
	$PickupStatus = FT::translate('status.pickup.status1');
	$step0 = array("active","timeline-event-success","fa-check");
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Pickup"){
    $PickupStatus = FT::translate('status.pickup.status2');
    $step0 = array("active","timeline-event-success","fa-check");
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Received"){
    $PickupStatus = FT::translate('status.pickup.status3');
    $step0 = array("active","timeline-event-success","fa-check");
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("active","timeline-event-success","fa-check");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Verified"){
    $PickupStatus = FT::translate('status.pickup.status5');
    $step0 = array("active","timeline-event-success","fa-check");
    $step1 = array("active","timeline-event-success","fa-check");
    $step2 = array("active","timeline-event-success","fa-check");
    $step3 = array("active","timeline-event-success","fa-check");
    $step4 = array("","opacity-4","fa-ellipsis-h");
    $step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Paid"){
    $PickupStatus = FT::translate('status.pickup.status4');
    $step0 = array("active","timeline-event-success","fa-check");
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("active","timeline-event-success","fa-check");
	$step4 = array("active","timeline-event-success","fa-check");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Sent"){
    $PickupStatus = FT::translate('status.pickup.status6');
    $step0 = array("active","timeline-event-success","fa-check");
    $step1 = array("active","timeline-event-success","fa-check");
    $step2 = array("active","timeline-event-success","fa-check");
    $step3 = array("active","timeline-event-success","fa-check");
    $step4 = array("active","timeline-event-success","fa-check");
    $step5 = array("active","timeline-event-success","fa-check");
}else if($pickup_data['Status'] == "Cancelled"){
    $PickupStatus = FT::translate('status.pickup.status100');
    $step0 = array("","opacity-4","fa-ellipsis-h");
	$step1 = array("","opacity-4","fa-ellipsis-h");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}else{
	$PickupStatus = $pickup_data['Status'];
	$step0 = array("","opacity-4","fa-ellipsis-h");
	$step1 = array("","opacity-4","fa-ellipsis-h");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
	$step5 = array("","opacity-4","fa-ellipsis-h");
}
$shipmentStatus = array(
    'Pending' => FT::translate('status.shipment.status1'),
    'Created' => FT::translate('status.shipment.status2'),
    'Imported' => FT::translate('status.shipment.status3'),
    'Cancelled' => FT::translate('status.shipment.status5'),
    'ReadyToShip' => FT::translate('status.shipment.status17'),
    'Sent' => FT::translate('status.shipment.status26'),
    'PreTransit' => FT::translate('status.shipment.status1001'),
    'InTransit' => FT::translate('status.shipment.status1002'),
    'OutForDelivery' => FT::translate('status.shipment.status1003'),
    'Delivered' => FT::translate('status.shipment.status1004'),
    'Return' => FT::translate('status.shipment.status1005'),
    'Onhold' => FT::translate('status.shipment.status1006'),
);
$isSeperateLabel = ($pickup_data['PickupType'] == "Drop_AtThaiPost" || $pickup_data['PickupType'] == "Pickup_AtKerry");

?>
    <div class="conter-wrapper">

		@if ($pickup_data['Status'] == 'Unpaid')
		<div class="row">
            <div class="col-md-6 col-md-offset-3 text-center" style="padding: 40px 0;background: #F3F8ED;border:1px solid #72b92e">
	    		<h3 class="text-success">สร้างใบรับพัสดุเรียบร้อยแล้ว</h3>
	    		<p style="margin-bottom: 20px;">กรุณาชำระเงิน โดยกดปุ่มด้านล่าง</p>
            	<a href="{{ url('/pickup_detail_payment/'.$pickupID)}}" > <button type="button" class="btn btn-lg btn-primary">กดเพื่อชำระเงินผ่าน QR</button></a>
                <br />
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/payment-2" target="_blank">คู่มือการชำระเงิน</a></p>
        	</div>
        </div>
		@elseif ($pickup_data['Status'] == 'New')
		<div class="row">
            <div class="col-md-6 col-md-offset-3 text-center" style="padding: 40px 0;background: #F3F8ED;border:1px solid #72b92e">
            
	    		<h3 class="text-success">ชำระเงินเรียบร้อยแล้ว</h3>
	    		<p style="margin-bottom: 20px;">กรุณาพิมพ์ใบปะหน้า และเตรียมพัสดุ</p>
	    		
	    		@if($isSeperateLabel)
	    		@if(sizeof($pickup_data['ShipmentDetail']['ShipmentIds']) > 0) 
                @foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $data)
	    			@if(isset($data) && isset($labels[$data['ID']]) && isset($labels[$data['ID']]['barcode']))
                    <a href="https://app.fastship.co/thaipost/label/{{ $labels[$data['ID']]['barcode'] }}" target="_blank" style="display: inline-block;margin: 10px auto; "><button type="button" class="btn btn-lg btn-primary"><i class="fa fa-print"></i> {!! FT::translate('pickup_detail.print_label') !!} {{ $data['ID'] }}</button></a>
                	@endif
                @endforeach
                @endif
                @else          	
            	<a href="/pickup_detail_print/{{ $pickupID }}" target="_blank"><button type="button" class="btn btn-lg btn-primary"><i class="fa fa-print"></i> {!! FT::translate('pickup_detail.button.print_pickup') !!}</button></a>
                @endif
                <br />
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/package-guide-2" target="_blank">คู่มือการบรรจุหีบห่อ</a></p>
                
                <div class="col-md-8 col-md-offset-2">
                @if($pickup_data['PickupType'] == "Drop_AtFastship")
                <h4 class="text-left">ขั้นตอนการส่งที่ FastShip</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>หากไม่มีเครื่องพิมพ์ กรุณาเขียนเลข Pickup ID ({{ $pickupID }}) และ Shipment ID (15xxxxxxxx) พร้อมชื่อผู้รับปลายทาง</li>
                	<li>นำสินค้ามาดรอปไว้ที่ บริษัท FastShip ซอยแจ้งวัฒนะ 14 (<a href="https://goo.gl/maps/m3VHsWzPK5d1FuCn9" target="_blank">แผนที่</a>)</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/pickup_dropoff/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @elseif($pickup_data['PickupType'] == "Drop_AtThaiPost")
                <h4 class="text-left">ขั้นตอนการส่งที่ไปรษณีย์</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>นำพัสดุไปฝากไว้ที่ ไปรษณีย์ โดยเข้าไปที่ช่องธุรกิจ (ไม่ต้องจ่ายค่าส่ง) อ่านรายละเอียด วิธีการส่งที่จุด Drop off ไปรษณีย์ไทย</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/thaipost-dropoff/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @elseif($pickup_data['PickupType'] == "Pickup_AtHomeNextday")
                <h4 class="text-left">ขั้นตอนการเข้ารับด้วย Kerry/Flash</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>เจ้าหน้าที่ Kerry/Flash จะโทรติดต่อทางเบอร์มือถือที่ให้ไว้ ภายใน 1-3 วัน</li>
                	<li>หลังจากเจ้าหน้าที่ Kerry/Flash รับพัสดุไปแล้ว พัสดุจะถึงบริษัท FastShip ในวันถัดไป และ ส่งออกภายใน 1-2 วันทำการ</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/pickup_helps/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @else
                <h4 class="text-left">ขั้นตอนการเข้ารับ</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>เจ้าหน้าที่จะโทรติดต่อทางเบอร์มือถือที่ให้ไว้ ก่อนการเข้ารับ</li>
                	<li>หลังจากพัสดุจะถึงบริษัท FastShip ส่งออกภายใน 1-2 วันทำการ</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/pickup_dropoff/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @endif
                </div>

        	</div>
        </div>
       @elseif ($pickup_data['Status'] == 'Pickup')
		<div class="row">
            <div class="col-md-6 col-md-offset-3 text-center" style="padding: 40px 0;background: #F3F8ED;border:1px solid #72b92e">
            
	    		<h3 class="text-success">ยืนยันการเข้ารับแล้ว</h3>
	    		<p style="margin-bottom: 20px;">กรุณาพิมพ์ใบปะหน้า และเตรียมพัสดุ</p>
	    		
	    		@if($isSeperateLabel)
	    		@if(sizeof($pickup_data['ShipmentDetail']['ShipmentIds']) > 0) 
                @foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $data)
	    			@if(isset($data) && isset($labels[$data['ID']]) && isset($labels[$data['ID']]['barcode']))
                    <a href="https://app.fastship.co/thaipost/label/{{ $labels[$data['ID']]['barcode'] }}" target="_blank" style="display: inline-block;margin: 10px auto; "><button type="button" class="btn btn-lg btn-primary"><i class="fa fa-print"></i> {!! FT::translate('pickup_detail.print_label') !!} {{ $data['ID'] }}</button></a>
                	@endif
                @endforeach
                @endif
                @else          	
            	<a href="/pickup_detail_print/<?php echo $pickupID; ?>" target="_blank"><button type="button" class="btn btn-lg btn-primary"><i class="fa fa-print"></i> {!! FT::translate('pickup_detail.button.print_pickup') !!}</button></a>
                @endif
                <br />
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/package-guide-2" target="_blank">คู่มือการบรรจุหีบห่อ</a></p>
                
                <div class="col-md-8 col-md-offset-2">
                @if($pickup_data['PickupType'] == "Drop_AtFastship")
                <h4 class="text-left">ขั้นตอนการส่งที่ FastShip</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>หากไม่มีเครื่องพิมพ์ กรุณาเขียนเลข Pickup ID ({{ $pickupID }}) และ Shipment ID (15xxxxxxxx) พร้อมชื่อผู้รับปลายทาง</li>
                	<li>นำสินค้ามาดรอปหรือส่งมาที่ บริษัท FastShip ซอยแจ้งวัฒนะ 14 แขวงทุ่งสองห้อง เขตหลักสี่ กทม 10210 (<a href="https://goo.gl/maps/m3VHsWzPK5d1FuCn9" target="_blank">แผนที่</a>)</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/pickup_dropoff/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @elseif($pickup_data['PickupType'] == "Drop_AtThaiPost")
                <h4 class="text-left">ขั้นตอนการส่งที่ไปรษณีย์</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>นำพัสดุไปฝากไว้ที่ ไปรษณีย์ โดยเข้าไปที่ช่องธุรกิจ (ไม่ต้องจ่ายค่าส่ง) อ่านรายละเอียด วิธีการส่งที่จุด Drop off ไปรษณีย์ไทย</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/thaipost-dropoff/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @elseif($pickup_data['PickupType'] == "Pickup_AtHomeNextday")
                <h4 class="text-left">ขั้นตอนการเข้ารับด้วย Kerry/Flash</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>เจ้าหน้าที่ Kerry/Flash จะโทรติดต่อทางเบอร์มือถือที่ให้ไว้ ภายใน 1-3 วัน</li>
                	<li>หลังจากเจ้าหน้าที่ Kerry/Flash รับพัสดุไปแล้ว พัสดุจะถึงบริษัท FastShip ในวันถัดไป และ ส่งออกภายใน 1-2 วันทำการ</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/pickup_helps/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @else
                <h4 class="text-left">ขั้นตอนการเข้ารับ</h4>
                <ol class="text-secondary text-left">
                	<li>กรุณาพิมพ์ใบปะหน้า โดยกดลิงค์ข้างต้น</li>
                	<li>แปะใบปะหน้าลงบนพัสดุ อ่านรายละเอียด คู่มือการบรรจุหีบห่อ</li>
                	<li>เจ้าหน้าที่จะโทรติดต่อทางเบอร์มือถือที่ให้ไว้ ก่อนการเข้ารับ</li>
                	<li>หลังจากพัสดุจะถึงบริษัท FastShip ส่งออกภายใน 1-2 วันทำการ</li>
                </ol>
                <p style="margin-top: 20px;"><a href="https://fastship.co/helps/pickup_dropoff/" target="_blank">รายละเอียดเพิ่มเติม</a></p>
                @endif
                </div>
                
        	</div>
        </div>
        @endif

        @if(sizeof($cases)>0)
    	@foreach($cases as $case)
    	<div class="alert m-t-20 mt-b-20 text-left bg-white alert-case alert-{{ strtolower($case['Priority']) }}">
        	<div class="text-center alert-left">
        		<div class="case">Case #{{ $case['ID'] }}</div>
        	</div>
        	<div class="alert-detail">
        		@if($case['IsPrivate'] == 1)
        		<span class=""><strong>{{ $case['Category'] }}</strong> | </span>
        		<span class="small">by Fastship {{ date('d/m H:i',strtotime($case['CreateDate'])) }}</span>
        		@else
        		<span class=""><strong>{{ $case['Detail'] }}</strong> | </span>
        		<span class="small">on {{ date('d/m H:i',strtotime($case['CreateDate'])) }}</span>

        		@if(sizeof($case['Replies']) > 0)
        		@foreach($case['Replies'] as $reply)
        		@if(strstr($reply['Detail'],"ปรับปรุง <b>สถานะ</b>") == FALSE && strstr($reply['Detail'],"ปรับปรุงสถานะ") == FALSE && strstr($reply['Detail'],"ปรับปรุง Case") == FALSE)
		
        		<hr style="margin: 5px;">
        		@if($reply['CustomerId'] == session('customer.id'))
        		<div class="small" style="margin-left: 20px;">{{ $reply['Detail'] }} | by {{ session('customer.name') }} {{ date('d/m H:i',strtotime($reply['CreateDate'])) }}</div>
        		@else
        		<div class="small" style="margin-left: 20px;">{{ $reply['Detail'] }} | by Fastship {{ date('d/m H:i',strtotime($reply['CreateDate'])) }}</div>
        		@endif
        		
        		@endif
        		@endforeach
        		@endif
        		
        		@endif		 
        	</div>
        	@if($case['IsPrivate'] == 0)
        	<div class="alert-reply">
        	<form id="case_form" name="case_form" class="form-horizontal" method="post" action="{{url ('/case/createreply')}}">
	    		
	    		{{ csrf_field() }}	
	    			
	    		<input type="hidden" name="case_id" value="{{ $case['ID'] }}" />

        		<textarea class="form-control " name="detail" placeholder="Reply here" required></textarea>
        		<button type="submit" class="btn btn-sm btn-primary" style="position: absolute;right: 20px;top: 20px;" >Send</button> 
        		
        	</form>	
        	</div>
        	@endif
        	<div class="alert-right text-center ">
        		<div class="circle-status alert-status-{{ strtolower($case['Status']) }}"></div>
        		<div class="small">Status: {{ $case['Status'] }}</div>
        	</div>
        </div>
    	@endforeach
    	@endif
        	
        <div class="row">
        
        	<div class="col-md-12">
            	<h2>{!! FT::translate('pickup_detail.panel.heading1') !!} {{ $pickupID }}</h2>
        		<hr />
    		</div>
    		
        	<div class="col-md-7">
        	
        		<div class="panel panel-primary">
                	<div class="panel-heading">{!! FT::translate('pickup_detail.panel.heading2') !!}</div>
                    <div class="panel-body">

                        <table class="table table-stripe table-hover small">
                        <thead>
                        <tr>
                        	<td>{!! FT::translate('label.shipment_id') !!}</td>
                        	<td class="hidden-xs">{!! FT::translate('label.receiver') !!}</td>
                        	<td class="hidden-xs">{!! FT::translate('label.destination') !!}</td>
                        	<td>{!! FT::translate('label.status') !!}</td>
                        	<td>{!! FT::translate('label.copy') !!}</td>
                        </tr>
                        </thead>
                        <tbody>

                        @if(sizeof($pickup_data['ShipmentDetail']['ShipmentIds']) > 0)
                        @foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $data)
                        <tr>
                        	<td>
                            	<a href="/shipment_detail/{{ $data['ID'] }}" target="_blank"><i class="fa fa-search"></i></a>
                            	<a href="/shipment_detail/{{ $data['ID'] }}" target="_blank">{{ $data['ID'] }}</a>
                        	</td>
                        	<?php if($data['ReceiverDetail']['Firstname'] != ""): ?>
                        	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                        	<?php else: ?>
                        	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Custname'];?></td>
                        	<?php endif; ?>
                        	<td class="hidden-xs"><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                        	<td><?php echo isset($shipmentStatus[$data['Status']])?$shipmentStatus[$data['Status']]:$data['Status']; ?></td>
                        	<td>
                        		<a href="{{ url('/shipment/clone/?shipment_id='.$data['ID']) }}"><button type="button" class="btn btn-xs btn-secondary">clone</button></a>
                        	</td>
                        </tr>
                        @endforeach
		                @endif
		                	<tr>
		                		<td class="hidden-xs"></td>
                        		<td colspan="2"><div class="text-right">ค่าส่งพัสดุ ({{ $pickup_data['ShipmentDetail']['TotalWeight'] }} กรัม)</div></td>
                        		<td colspan="2" ><span class="text-dark">{{ $pickup_data['ShipmentDetail']['TotalShippingRate'] }} <span class="hidden-xs">{!! FT::translate('unit.baht') !!}</span></span></td>
                        	</tr>
                        	@if($pickup_data['PickupCost'] > 0)
                        	<tr>
                        		<td class="hidden-xs"></td>
                        		<td colspan="2"><div class="text-right">ค่าเข้ารับพัสดุ</div></td>
                        		<td colspan="2" ><span class="text-dark">{{ $pickup_data['PickupCost'] }} <span class="hidden-xs">{!! FT::translate('unit.baht') !!}</span></span></td>
                        	</tr>
                        	@endif
                        	@if($pickup_data['PackingCost'] > 0)
                        	<tr>
                        		<td class="hidden-xs"></td>
                        		<td colspan="2"><div class="text-right">ค่าบรรจุภัณฑ์</div></td>
                        		<td colspan="2" ><span class="text-dark">{{ $pickup_data['PackingCost'] }} <span class="hidden-xs">{!! FT::translate('unit.baht') !!}</span></span></td>
                        	</tr>
                        	@endif
                        	@if($pickup_data['Insurance'] > 0)
                        	<tr>
                        		<td class="hidden-xs"></td>
                        		<td colspan="2"><div class="text-right">ค่าประกันพัสดุ</div></td>
                        		<td colspan="2" ><span class="text-dark">{{ $pickup_data['Insurance'] }} <span class="hidden-xs">{!! FT::translate('unit.baht') !!}</span></span></td>
                        	</tr>
                        	@endif
                        	@if($pickup_data['AdditionCost'] != 0)
                        	<tr>
                        		<td class="hidden-xs"></td>
                        		<td colspan="2"><div class="text-right">ค่าใช้จ่ายอื่นๆ</div></td>
                        		<td colspan="2" ><span class="text-dark">{{ $pickup_data['AdditionCost'] }} <span class="hidden-xs">{!! FT::translate('unit.baht') !!}</span></span></td>
                        	</tr>
                        	@endif
                        	@if($pickup_data['Discount'] > 0)
                        	<tr>
                        		<td class="hidden-xs"></td>
                        		<td colspan="2"><div class="text-right">ส่วนลด</div></td>
                        		<td colspan="2" ><span class="text-dark">-{{ $pickup_data['Discount'] }} <span class="hidden-xs">{!! FT::translate('unit.baht') !!}</span></span></td>
                        	</tr>
                        	@endif
		                </tbody>
                        </table>
		                <div class="row text-center">
		                    <div class="col-md-12"><h4>{!! FT::translate('pickup_detail.pickup_total') !!} <span class="orange"><?php echo number_format($pickup_data['Amount'],0); ?></span> {!! FT::translate('unit.baht') !!}</h4></div>
		                    <?php if ($pickup_data['Status'] != 'Unpaid' && $pickup_data['Status'] != 'Cancelled') { ?>
								<a href="/pickup_invoice_print/{{ $pickupID }}" target="_blank"><button type="button" class="btn btn-default">พิมพ์ใบส่งของ</button></a>
							<?php }?>
		                </div>
		                <div class="clearfix"></div><br />

                	</div>
                </div>
                
        		<div class="panel panel-primary">
                	<div class="panel-heading">รายละเอียดการเข้ารับ</div>
                    <div class="panel-body">
                    	
                    	<div class="col-md-2 col-xs-4 text-center">
                    	@if(in_array($pickup_data['PickupType'],array("Pickup_BySkootar","Pickup_ByLalamove")))
                    		<img src="{{ url('images/pickup/skootar_lalamove.png') }}" />
                    	@elseif(in_array($pickup_data['PickupType'],array("Pickup_ByKerry","Pickup_ByKerryBulk")))
                    	<img src="{{ url('images/pickup/kerry.png') }}" />
                    	@elseif(in_array($pickup_data['PickupType'],array("Drop_AtThaiPost")))
                    	<img src="{{ url('images/pickup/thaipost.png') }}" />
                    	@else
                    	<img src="{{ url('images/pickup/fastship.png') }}" />
                    	@endif
                    	</div>
                    	<div class="col-md-8 col-xs-8">
                    		
                    		<h4 class="orange" style="margin-bottom: 0;">
                    			{{ (isset($pickupType[$pickup_data['PickupType']]))?$pickupType[$pickup_data['PickupType']]:$pickup_data['PickupType'] }}
                    		</h4>
                    		<div style="margin-bottom: 5px;"><strong>{{ $pickup_data['Tracking'] }}</strong></div>

                    		@if($pickup_data['PickupType'] != "Drop_AtFastship")
                    		
                        		@if($pickup_data['PickupType'] != "Drop_AtThaiPost")
                        		
                        		<div>
        							<span class="text-dark small">{!! FT::translate('label.contact') !!}: </span>
        							<span class="small">
        								{{ $pickup_data['PickupAddress']['Firstname'] }} {{ $pickup_data['PickupAddress']['Lastname'] }}
        							</span>
        						</div>
        						<div class="clearfix"></div>
        						
        						<div>
        							<span class="text-dark small">{!! FT::translate('label.telephone') !!}: </span>
        							<span class="small">
        								{{ $pickup_data['PickupAddress']['PhoneNumber'] }}
        							</span>
        						</div>
    							<div class="clearfix"></div>

                        		@endif

    							<div>
        							<span class="text-dark small">ที่อยู่ผู้ส่ง: </span>
        							<span class="small">
        							{{ $pickup_data['PickupAddress']['AddressLine1'] }}
        							{{ $pickup_data['PickupAddress']['AddressLine2'] }}
        							{{ $pickup_data['PickupAddress']['City'] }}
        							{{ $pickup_data['PickupAddress']['State'] }}
        							{{ $pickup_data['PickupAddress']['Postcode'] }}
        							</span>
    							</div>
    							<div class="clearfix"></div>
    						@else
    						<div>
    						 ซอยแจ้งวัฒนะ 14 แขวงทุ่งสองห้อง เขตหลักสี่ กทม 10210 (<a href="https://goo.gl/maps/m3VHsWzPK5d1FuCn9" target="_blank">แผนที่</a>)
    						</div>
                    		@endif
                    	</div>
                    	<div class="col-md-2 hidden-xs text-center">
                    		<span class="text-dark">ค่าเข้ารับ</span>
                    		<h3>{{ $pickup_data['PickupCost'] }}.-</h3>
                    	</div>
                    	<div class="clearfix"></div><br />
                    	@if(isset($trackings) && sizeof($trackings) > 0)
                        	@foreach($trackings as $tracking)
                            	@if(isset($tracking['TrackingCode']))
                            		@if($pickup_data['PickupType'] == "Pickup_ByKerry" || $pickup_data['PickupType'] == "Pickup_ByKerryBulk")
                                	<h4>{{ $tracking['TrackingCode'] }}</h4>
                                	@endif
                                	@if($pickup_data['PickupType'] == "Drop_AtThaiPost")
                                	<h5 class="" style="padding: 5px;background: #eee;">
                                    	<strong>{{ $tracking['TrackingCode'] }}</strong> ({{ $tracking['ShipmentId'] }})
                                    	<span class="pull-right">
                                    		<span id="loading{{ $tracking['TrackingCode'] }}" style="display: none;height: 20px;"><img src="{{ url('/images/loading.gif') }}" style="width: 20px;"/></span>
                                    		<button class="btn btn-xs btn-info" onclick="trackThaipost('{{ $tracking['TrackingCode']  }}')"><i class="fa fa-search"></i> ตรวจสอบสถานะ</button>
                                    	</span>
                                	</h5>
                                	<div id="thaipost_tracking{{ $tracking['TrackingCode'] }}"></div>
                                	@endif
                                	@if($pickup_data['PickupType'] != "Pickup_BySkootar" && $pickup_data['PickupType'] != "Drop_AtThaiPost")
                                	<table class="table table-stripe table-hover small">
                                        <tbody>
                                        @if(isset($tracking['Events']) && sizeof($tracking['Events']) > 0)
                                        @foreach($tracking['Events'] as $event)
                                        <tr>
                                        	<td width="25%">{{ isset($event['Datetime'])?$event['Datetime']:"" }}</td>
                                        	<td style="text-align:left;">{{ isset($event['Description'])?$event['Description']:"" }} <span class="text-info"><b>{{ isset($event['Location'])?$event['Location']:"" }}</b></span></td>
                                        </tr>
                                        @endforeach
                                        @endif
            			                </tbody>
                                    </table>
                                    @endif
                                    @if($tracking['Url'])
                                    <div class="text-dark small">Tracking URL: <a href="{{ $tracking['Url'] }}" target="_blank">{{ $tracking['Url'] }}</a></div>
                                	<br />
                                	@endif
                                @endif
                            @endforeach
                        @endif
                	</div>
                </div>

                <div class="panel panel-primary">
                	<div class="panel-heading">การชำระเงิน</div>
                    <div class="panel-body">

                    	<table id="statements" class="table table-hover table-striped small">
                        <thead>
                        	<tr>
                        		<td>{!! FT::translate('label.date') !!}</td>
                        		<td>ประเภท | รายละเอียด</td>
                        		<td>จำนวน (บาท)</td>
                        	</tr>
                        </thead>
                        <tbody>
                        @if(isset($statements) && sizeof($statements) > 0)
                        @foreach($statements as $statement)
                        	<tr>
                        		<td>{{ $statement['CreateDate'] }}</td>
                        		<td>
                        		@if($statement['Amount'] < 0)
                        			<i class="fa fa-money text-info"></i> 
                        		@else
                        			<i class="fa fa-plus-circle text-success"></i>
                        		@endif
                        		
                    			{{ isset($payment_mapping[$statement['Payment']]) ? $payment_mapping[$statement['Payment']]:$statement['Payment'] }}
                    			
                        		</td>
                        		<td>
                        		@if($statement['Amount'] < 0)
                        			<span class="text-danger">{{ $statement['Amount'] }}</span>
                        		@else
                        			<span class="text-success">+{{ $statement['Amount'] }}</span>
                        		@endif
                        		</td>
                        	</tr>
                       	@endforeach
                       	@else
                       		<tr>
                        		<td colspan="5" class="text-center">ไม่มีประวัติการเงิน</td>
                        	</tr>
                       	@endif
                        </tbody>
                        </table>

                	</div>
                </div>

        	</div>
            <div class="col-md-5"> 
            	<div class="panel panel-primary">
                	<div class="panel-body">
                		<h2>{!! FT::translate('label.status') !!}: {{ $PickupStatus }}</h2>
                		<h3>{!! FT::translate('label.pickup_id') !!} : <span style="color: #f15a22;">{{ $pickupID }}</span></h3>
		                    <div class="timeline timeline-single-column">
		                    	
		                    	<div class="timeline-item active">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa fa-check"></i>
		                            </div>
		                            <div class="timeline-event upgrade timeline-event-success">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.stepN') !!}</h4>
		                                    <div>{!! FT::translate('pickup_detail.track.stepN_1') !!} {{ date("Y-m-d H:i:s",strtotime($pickup_data['CreateDate']['date'])) }}</div>
		                                </div>
		                                <div class="timeline-body">
		                                </div>
		                                <div class="timeline-footer"></div>
		                            </div>
		                        </div>
		                        
		                    	<div class="timeline-item {{ $step0[0] }}">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa {{ $step0[2] }}"></i>
		                            </div>
		                            <div class="timeline-event upgrade {{ $step0[1] }}">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step0') !!}</h4>
		                                    <div>{!! FT::translate('pickup_detail.track.step0_1') !!} {{ $pickup_data['PaymentMethod'] }} </div>
		                                </div>
		                                <div class="timeline-body">
		                                </div>
		                                <div class="timeline-footer"></div>
		                            </div>
		                        </div>
		                        
		                    	<div class="timeline-item {{ $step1[0] }}">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa {{ $step1[2] }}"></i>
		                            </div>
		                            <div class="timeline-event upgrade {{ $step1[1] }}">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step1') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                @if(strstr($pickup_data['PickupType'],"Pickup_"))
		                                    <p>{!! FT::translate('pickup_detail.track.step1_1') !!}</p>
		                                @else
		                                	<p>{!! FT::translate('pickup_detail.track.step1_2') !!}</p>
		                                @endif

		                                </div>
		                                <div class="timeline-footer">
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        
		                        @if(strstr($pickup_data['PickupType'],"Pickup_"))
		                        <div class="timeline-item {{ $step2[0] }}">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa {{ $step2[2] }}"></i>
		                            </div>
		                            <div class="timeline-event upgrade {{ $step2[1] }}">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step2') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                	<p>{{ $pickup_data['PickupAddress']['Firstname'] }}</p>
		                                    <p>	{{ $pickup_data['PickupAddress']['AddressLine1'] }}
		                                    	{{ $pickup_data['PickupAddress']['AddressLine2'] }}
		                                    	{{ $pickup_data['PickupAddress']['City'] }}
							                	{{ $pickup_data['PickupAddress']['State'] }}
							                	{{ $pickup_data['PickupAddress']['Postcode'] }}
							                </p>
		                                    <p class="text-right"></p>
		                                </div>
		                                <div class="timeline-footer">
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        @endif
		                        
		                        <div class="timeline-item {{ $step3[0] }}">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa {{ $step3[2] }}"></i>
		                            </div>
		                            <div class="timeline-event upgrade {{ $step3[1] }}">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step3') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                    <p>{!! FT::translate('pickup_detail.track.step3_1') !!}</p>
		                                </div>
		                                <div class="timeline-footer">
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        
		                        <div class="timeline-item {{ $step4[0] }}">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa {{ $step4[2] }}"></i>
		                            </div>
		                            <div class="timeline-event upgrade {{ $step4[1] }}">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step4') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                	<p>{!! FT::translate('pickup_detail.track.step4_1') !!}</p>
		                                </div>
		                                <div class="timeline-footer">
		
		                                </div>
		                            </div>
		                        </div>
		                        
		                        <div class="timeline-item {{ $step5[0] }}">
		                            <div class="timeline-point timeline-point-default">
		                                <i class="fa {{ $step5[2] }}"></i>
		                            </div>
		                            <div class="timeline-event upgrade {{ $step5[1] }}">
		                                <div class="timeline-heading">
		                                    <h4>{!! FT::translate('pickup_detail.track.step5') !!}</h4>
		                                </div>
		                                <div class="timeline-body">
		                                    <p></p>
		                                </div>
		                                <div class="timeline-footer">
		
		                                </div>
		                            </div>
		                        </div>
		                    </div>

                    </div>
                </div>
                
                <?php if($pickup_data['Status'] == "New" || $pickup_data['Status'] == "Unpaid" ): ?>
			    <form id="pickup_form" class="form-horizontal" method="post">
				    {{ csrf_field() }}
				</form>
			    <div class="row">
			    	<div class="col-md-12 text-center">
			    		<a href="javascript:cancelPickup(<?php echo $pickupID;?>);"><i class="fa fa-trash-o"></i> {!! FT::translate('pickup_detail.cancel_link') !!}</a>
			    	</div>
			    </div>
			    <?php endif; ?>

            </div>
        </div>
        <div class="clearfix"></div>
		<br />
		
		@if(sizeof($cases) == 0)
        <div class="row">
    	    <div class="col-md-6 col-md-offset-3">
    	    
    	    	<form id="case_form" name="case_form" class="form-horizontal" method="post" action="{{url ('/case/create')}}">
    	    		
    	    		{{ csrf_field() }}
    	    		
    	    		<input type="hidden" name="ref_id" value="{{ $pickup_data['ID'] }}" />
    
    			    <div class="panel panel-primary">
    					<div class="panel-heading"><img src="{{ url('images/fasty_help.png') }}" style="max-height:40px;" /> <span style="line-height:40px;">{!! FT::translate('button.sendusmsg') !!}</span></div>
    			        <div class="panel-body">
    
    	                	<div class="col-md-12">
    	                        <label for="category" class="col-12 control-label">ประเภท Case</label>
    	                        
    	                        <select name="category" class="form-control required" required>
    	                        	<option value="">--- กรุณาเลือก ---</option>
    	                    		<option>ปัญหาการเข้ารับพัสดุ</option>
    	                    		<option>ติดตามสถานะ Tracking</option>
    	                    		<option>สอบถามรายละเอียดยอดชำระ</option>
    	                    		<option>ปัญหาการจ่ายเงิน</option>
    	                    		<option>คืนเงิน / สถานะการคืนเงิน</option>
    	                    		<option>คืนสินค้า/ สถานะการคืนสินค้า</option>
    	                    		<option>ขอเอกสาร หัก ณ ที่จ่าย</option>
    	                    		<option>อื่นๆ</option>
    	                    	</select>
    	                    	
    	                    </div>
    	                    <div class="col-md-12">
    		                    <label for="detail" class="col-12 control-label">รายละเอียด</label>
    		                    <textarea class="form-control required" rows="5" name="detail" id="detail" required>{{ old('detail','') }}</textarea>
    		                </div>
    		                <div class="clearfix"></div>
    		                <br />
    
    		                <div class="text-center"><button type="submit" name="submit" class="btn btn-lg btn-primary">{!! FT::translate('button.confirm') !!}</button></div>
    		            
    		            </div>
    				</div>
    			</form> 
    	    </div>
    	    
    	</div>
    	@endif

			    
    </div>

<script>

    function trackThaipost(_barcode){

    	$("#loading" + _barcode).css("display","inline-block");
    	
    	$.post('{{url ("pickup/track_thaipost")}}',
    	{
    		_token: $("[name=_token]").val(),
    		barcode: _barcode,
    	},function(data){
    
    		console.log(data);

    		
    		
    		if (!$.trim(data)){ 
				$("#thaipost_tracking" + _barcode).html("ไม่พบข้อมูล");
    		}else{
    			
    			var content = "";
    			content += '<table class="table table-stripe table-hover small"><tbody>';
				$.each(data['Events'], function(index,history) {
					console.log(history);
					content += '<tr>';
					content += '<td width="25%">' + history['Datetime'] + '</td>';
					content += '<td style="text-align:left;">' + history['Description'] + ' <span class="text-info"><b>' + history['Location'] + '</b></span></td>';
					content += '</tr>';
				});
    			content += '</tbody></table>';

    			$("#thaipost_tracking" + _barcode).html(content);
    		}
    		/* 
    		
            
            @if(isset($tracking['Events']) && sizeof($tracking['Events']) > 0)
            @foreach($tracking['Events'] as $event)
            <tr>
            	<td width="25%">{{ isset($event['Datetime'])?$event['Datetime']:"" }}</td>
            	<td style="text-align:left;">{{ isset($event['Description'])?$event['Description']:"" }} <span class="text-info"><b>{{ isset($event['Location'])?$event['Location']:"" }}</b></span></td>
            </tr>
            @endforeach
            @endif
            
        */
    		$("#loading" + _barcode).hide();
    		
    	},"json");
    }
    Number.prototype.format = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
    };
    $(document).ready(function() {
    
    	$("#weight").focus();
    
    	@if(session('shipment.weight') !== null)
    		
    		$("#weight").val("{{ session('shipment.weight') }}");
    
    		@if(session('shipment.width') > 0)
    
    			$("#box").attr('checked', true);
    			showdimension();
    		    
    			$("#width").val("{{ session('shipment.width') }}");
    			$("#height").val("{{ session('shipment.height') }}");
    			$("#length").val("{{ session('shipment.length') }}");
    			
    			var volWeight = $("#width").val()*$("#height").val()*$("#length").val()/5;
    	        $("#volumnWeight").text(volWeight.toFixed(0));
    	        
    		@else
    			$("#parcel").attr('checked', true);
    			hidedimension();
    		@endif
    	
    		$("#country").val("{{ session('shipment.country') }}");
    
    		calculateRate();
    		
    	@endif
    
    	var isMac = navigator.platform.toUpperCase().indexOf('MAC')>=0;
    	if (isMac) {
    	    $('.mac-margin-left').css('margin-left','140px');
    	}
    	
    });

    function cancelPickup(pick_id){

    	if(confirm("{!! FT::translate('confirm.delete_pickup') !!}")){
    		$.post('{{url ('pickup/cancel')}}',
    	    {
    	    	_token: $("[name=_token]").val(),
    	        pickupId: pick_id
    	    },function(data){
    		    window.location.href = '/pickup_list';
    		},"json");
        }
                
    }

    @if(session('msg-type') && session('msg-type') == "success")
    fbq('track', 'Purchase', {
    	value: 200,
    	currency: 'THB',
    });
    @endif
    
</script>
 
@endsection