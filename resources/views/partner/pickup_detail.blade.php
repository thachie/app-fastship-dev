@extends('partner/layouts/layout_partner_back')
@section('content')
<?php 
//alert($pickup_data); 
if($pickup_data['Status'] == "New"){
	$PickupStatus = "ใบรับพัสดุใหม่";
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Pickup"){
	$PickupStatus = "กำลังไปรับ";
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Received"){
	$PickupStatus = "รับพัสดุแล้ว";
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("active","timeline-event-success","fa-check");
	$step4 = array("","opacity-4","fa-ellipsis-h");
}else if($pickup_data['Status'] == "Paid"){
	$PickupStatus = "ชำระเงินแล้ว";
	$step1 = array("active","timeline-event-success","fa-check");
	$step2 = array("active","timeline-event-success","fa-check");
	$step3 = array("active","timeline-event-success","fa-check");
	$step4 = array("active","timeline-event-success","fa-check");
}else if($pickup_data['Status'] == "Cancelled"){
	$PickupStatus = "ยกเลิก";
	$step1 = array("","opacity-4","fa-ellipsis-h");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
}else{
	$PickupStatus = $pickup_data['Status'];
	$step1 = array("","opacity-4","fa-ellipsis-h");
	$step2 = array("","opacity-4","fa-ellipsis-h");
	$step3 = array("","opacity-4","fa-ellipsis-h");
	$step4 = array("","opacity-4","fa-ellipsis-h");
}
$shipmentStatus = array(
    'Pending' => "รอการสร้างใบรับ",
    'Created' => "สร้างแล้ว",
    'Imported' => "นำเข้าแล้ว",
    'Cancelled' => "ยกเลิกแล้ว",
    'ReadyToShip' => "พร้อมส่ง",
    'Sent' => "ส่งออกแล้ว",
    'PreTransit' => "PreTransit",
    'InTransit' => "InTransit",
    'OutForDelivery' => "Out For Delivery",
    'Delivered' => "Delivered",
    'Return' => "Return to Sender",
);
$isSeperateLabel = ($pickup_data['PickupType'] == "Drop_AtThaiPost" || $pickup_data['PickupType'] == "Pickup_AtKerry");

?>
    <div class="conter-wrapper">	
		<div class="row">
	        <div class="col-md-12"><h2>รายละเอียดการขนส่ง</h2></div>	
	    </div>
        <div class="row">
        	<div class="col-md-7">
        		<div class="panel panel-primary">
                	<div class="panel-heading">รายละเอียดใบรับ <?php echo $pickupID; ?></div>
                    <div class="panel-body">
                        
	                        <div class=" well" style="margin-bottom:0px;">
								<div class="col-xs-6 col-md-4 text-right clearfix">วันที่สร้าง : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo date("d/m/Y H:i:s",strtotime($pickup_data['CreateDate']['date']));?></div>
								<div class="clearfix"></div>
							
								<div class="col-xs-6 col-md-4 text-right clearfix">วิธีการรับพัสดุ : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo (isset($pickupType[$pickup_data['PickupType']]))?$pickupType[$pickup_data['PickupType']]:$pickup_data['PickupType']; ?></div>
								<div class="clearfix"></div>
							
								<?php if($pickup_data['PickupType'] == "Pickup_AtHome"): ?>
								
									<div class="col-xs-6 col-md-4 text-right clearfix">วันที่ให้เข้ารับพัสดุ : </div>
									<div class="col-xs-6 col-md-8 text-left"><?php echo date("d/m/Y H:i:s",strtotime($pickup_data['ScheduleDate']));?></div>
									<div class="clearfix"></div>
									
									<div class="col-xs-6 col-md-4 text-right clearfix">ชื่อผู้ติดต่อ : </div>
									<div class="col-xs-6 col-md-8 text-left">
									<?php echo $pickup_data['PickupAddress']['Firstname'];?> <?php echo $pickup_data['PickupAddress']['Lastname'];?>
									</div>
									<div class="clearfix"></div>
									
									<div class="col-xs-6 col-md-4 text-right clearfix">เบอร์ติดต่อ : </div>
									<div class="col-xs-6 col-md-8 text-left">
									<?php echo $pickup_data['PickupAddress']['PhoneNumber'];?>
									</div>
									<div class="clearfix"></div>
									
									<div class="col-xs-6 col-md-4 text-right clearfix">ที่อยู่ที่ให้ไปรับพัสดุ : </div>
									<div class="col-xs-6 col-md-8 text-left">
									<?php echo $pickup_data['PickupAddress']['AddressLine1'];?>
									<?php echo $pickup_data['PickupAddress']['AddressLine2'];?>
									<?php echo $pickup_data['PickupAddress']['City'];?>
									<?php echo $pickup_data['PickupAddress']['State'];?>
									<?php echo $pickup_data['PickupAddress']['Postcode'];?>
									Thailand
									</div>
									<div class="clearfix"></div>
			
								<?php endif; ?>
							
								<div class="col-xs-6 col-md-4 text-right clearfix">วิธีการชำระเงิน : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo $paymentMethod[$pickup_data['PaymentMethod']];?></div>
								<div class="clearfix"></div>
							
								<?php if(isset($pickup_data['Remark']) && $pickup_data['Remark']): ?>
								<div class="col-xs-6 col-md-4 text-right clearfix">หมายเหตุ : </div>
								<div class="col-xs-6 col-md-8 text-left"><?php echo $pickup_data['Remark'];?></div>
								<div class="clearfix"></div>
								<?php endif; ?>
							
			                </div>

							
                            <h3 style="padding-top: 30px;">รายการพัสดุ</h3>
                            <table class="table table-stripe table-hover">
                            <thead>
                            <tr>
                            	<td>หมายเลขพัสดุ</td>
                            	<td class="hidden-xs">ผู้รับ</td>
                            	<td class="hidden-xs">ประเทศปลายทาง</td>
                            	<td>สถานะ</td>
                            	<?php if($isSeperateLabel): ?>
                            	<td>พิมพ์ใบปะหน้า</td>
                            	<?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            
                           
                            <?php 
                            if(sizeof($pickup_data['ShipmentDetail']['ShipmentIds']) > 0): 
                            foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $data):
                                
                            ?>
                            <tr>
                            	<td>
                                	<a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><i class="fa fa-search"></i></a>
                                	<a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a>
                            	</td>
                            	<?php if($data['ReceiverDetail']['Firstname'] != ""): ?>
                            	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                            	<?php else: ?>
                            	<td class="hidden-xs"><?php echo $data['ReceiverDetail']['Custname'];?></td>
                            	<?php endif; ?>
                            	<td class="hidden-xs"><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                            	<td><?php echo isset($shipmentStatus[$data['Status']])?$shipmentStatus[$data['Status']]:$data['Status']; ?></td>
                            	<?php 
                            	if($isSeperateLabel): 
                            	   $barcodeURL = "http://app.fastship.co/thaipost/label/" . $labels[$data['ID']]['barcode'];
                            	?>
                            	<td class="small" >
                                	<a href="<?php echo $barcodeURL; ?>" target="_blank"><i class="fa fa-print"></i></a> 
                                	<a href="<?php echo $barcodeURL; ?>" target="_blank" style="font-weight: 100;"><?php echo $labels[$data['ID']]['barcode']; ?></a>
                            	</td>
                            	<?php endif; ?>
                            </tr>
                            <?php 
                            endforeach;
			                endif;
			                ?> 
			                </tbody>
                            </table>
			                <div class="row text-center">
			                    <div class="col-md-12"><h4>ยอดรวมทั้งหมด <span class="orange"><?php echo number_format($pickup_data['Amount'],0); ?></span> บาท</h4></div>
								<a href="partner/pickup_detail_print/<?php echo $pickupID; ?>" target="_blank"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">พิมพ์ใบรับพัสดุ</button></a>
								<a href="partner/pickup_invoice_print/<?php echo $pickupID; ?>" target="_blank"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">พิมพ์ใบกำกับภาษี</button></a>
			                </div>
			                <div class="clearfix"></div><br />
			                
			                <?php 
			                if($isSeperateLabel): 
    			                if($pickup_data['PickupType'] == "Drop_AtThaiPost") $droppointName = "ไปรษณีย์";
    			                else if($pickup_data['PickupType'] == "Pickup_AtKerry") $droppointName = "Kerry";
    			                else $droppointName = "จุดดรอป";
			                ?>
			                <p class="text-center">กรุณาพิมพ์ใบปะหน้าของพัสดุแต่ละชิ้น และนำไปส่งที่<strong><?php echo $droppointName; ?></strong>ใกล้บ้านท่าน</p>
			                <?php endif; ?>

                        </div>
                </div>
        	</div>
            <div class="col-md-5"> 
            	<div class="panel panel-primary">
                	<div class="panel-body">
                		<h2>Status: <?php echo $PickupStatus; ?></h2>
                		
                		<h3>เลขที่ใบรับพัสดุ : <span style="color: #f15a22;"><?php echo $pickupID; ?></span></h3>
	                    <div class="timeline timeline-single-column">
	                    	<div class="timeline-item <?php echo $step1[0]; ?>">
	                            <div class="timeline-point timeline-point-default">
	                                <i class="fa <?php echo $step1[2]; ?>"></i>
	                            </div>
	                            <div class="timeline-event upgrade <?php echo $step1[1]; ?>">
	                                <div class="timeline-heading">
	                                    <h4>ใบรับพัสดุใหม่ วันที่ <?php echo date("d/m/Y H:i:s",strtotime($pickup_data['CreateDate']['date']));?></h4>
	                                </div>
	                                <div class="timeline-body">
	                                <?php if($pickup_data['PickupType'] == "Pickup_AtHome"):?>
	                                    <p>เจ้าหน้าที่จะติดต่อเพื่อยืนยันการรับพัสดุ</p>
	                                <?php else: ?>
	                                	<p>กรุณานำพัสดุไปยังจุดส่งสินค้าที่เลือก</p>
	                                <?php endif;?>
	                                </div>
	                                <div class="timeline-footer">
	                                    
	                                </div>
	                            </div>
	                        </div>
	                        <?php if($pickup_data['PickupType'] == "Pickup_AtHome"):?>
	                        <div class="timeline-item <?php echo $step2[0]; ?>">
	                            <div class="timeline-point timeline-point-default">
	                                <i class="fa <?php echo $step2[2]; ?>"></i>
	                            </div>
	                            <div class="timeline-event upgrade <?php echo $step2[1]; ?>">
	                                <div class="timeline-heading">
	                                    <h4>กำลังไปรับ ในวันที่ <?php echo date("d/m/Y H:i:s",strtotime($pickup_data['ScheduleDate']));?></h4>
	                                    
	                                </div>
	                                <div class="timeline-body">
	                                	<p><?php echo $pickup_data['PickupAddress']['Firstname'];?></p>
	                                    <p><?php echo $pickup_data['PickupAddress']['AddressLine1'];?>
						                	<?php echo $pickup_data['PickupAddress']['AddressLine2'];?>
						                	<?php echo $pickup_data['PickupAddress']['City'];?>
						                	<?php echo $pickup_data['PickupAddress']['State'];?>
						                	<?php echo $pickup_data['PickupAddress']['Postcode'];?>
						                	Thailand</p>
	                                    <p class="text-right"></p>
	                                </div>
	                                <div class="timeline-footer">
	                                    
	                                </div>
	                            </div>
	                        </div>
	                        <?php endif; ?>
	                        
	                        <div class="timeline-item <?php echo $step3[0]; ?>">
	                            <div class="timeline-point timeline-point-default">
	                                <i class="fa <?php echo $step3[2]; ?>"></i>
	                            </div>
	                            <div class="timeline-event upgrade <?php echo $step3[1]; ?>">
	                                <div class="timeline-heading">
	                                    <h4>รับพัสดุ</h4>
	                                </div>
	                                <div class="timeline-body">
	                                    <p>เจ้าหน้าที่จะตรวจสอบพัสดุและคำนวณค่าส่งอีกครั้ง</p>
	                                </div>
	                                <div class="timeline-footer">
	                                    
	                                </div>
	                            </div>
	                        </div>
	                        
	                        <div class="timeline-item <?php echo $step4[0]; ?>">
	                            <div class="timeline-point timeline-point-default">
	                                <i class="fa <?php echo $step4[2]; ?>"></i>
	                            </div>
	                            <div class="timeline-event upgrade <?php echo $step4[1]; ?>">
	                                <div class="timeline-heading">
	                                    <h4>ชำระเงิน</h4>
	                                </div>
	                                <div class="timeline-body">
	                                    <p><?php echo $paymentMethod[$pickup_data['PaymentMethod']]; ?> จำนวนเงิน <?php echo number_format($pickup_data['Amount'],0); ?> บาท</p>
	                                </div>
	                                <div class="timeline-footer">
	
	                                </div>
	                            </div>
	                        </div>
	                    </div>
                    </div>
                </div>
                
                <?php if($pickup_data['Status'] == "New" ): ?>
			    <form id="pickup_form" class="form-horizontal" method="post">
				    {{ csrf_field() }}
				</form>
			    <div class="row">
			    	<div class="col-md-12 text-center">
			    		<a href="javascript:cancelPickup(<?php echo $pickupID;?>);"><i class="fa fa-trash-o"></i> ยกเลิกใบรับพัสดุ</a>
			    	</div>
			    </div>
			    <?php endif; ?>
			    
			    
			    
            </div>
        </div>
        <div class="clearfix"></div>
		<br />
			    
    </div>

<script>

    function cancelPickup(pick_id){

    	if(confirm("คุณต้องการลบใบรับพัสดุนี้ใช่หรือไม่")){
    		$.post('{{url ('partner/pickup/cancel')}}',
    	    {
    	    	_token: $("[name=_token]").val(),
    	        pickupId: pick_id
    	    },function(data){
    		    window.location.href = 'partner/pickup_list';
    		},"json");
        }
                
    }
</script>
 
@endsection