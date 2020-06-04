<!doctype>
<html lang="en" class="no-js">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="/css/vendor.css"/>
        <!-- <link rel="stylesheet" type="text/css" href="/css/app-orange.css"/> -->
        <link rel="stylesheet" type="text/css" href="/css/styles.css"/>
    </head>
    <body>
    
		<script src="/js/app.js" type="text/javascript"></script>
		<script src="/js/custom.js" type="text/javascript"></script>
		<script src="/js/vendor.js" type="text/javascript"></script>
        <script src="/vendor/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <div id="app-container">
        	
	       	<div id="body-container" style="background-color: #fff; padding: 5px 0; font-size: 13px;">
	       	
	       		@if (session('msg'))
				@if (session('msg-type'))
				<div class="col-12 col-md-10 col-md-offset-1 alert alert-{{ session('msg-type') }}" style="margin-top: 30px;">
					{{ session('msg') }}
				</div>
				@else
				<div class="col-12 col-md-10 col-md-offset-1 alert alert-danger" style="margin-top: 30px;">
					{{ session('msg') }}
				</div>
				@endif
				@endif
				<div class="row">
					<div class="col-md-10 col-md-offset-1">

						@if($pickup_data['PickupType'] == "Pickup_AtHomeNextday" || $pickup_data['PickupType'] == "Pickup_ByKerry")
						<div class="col-md-12 col-print-12">
						<?php 
						$cnt= 1;
						$total_shipment = sizeof($pickup_data['ShipmentDetail']['ShipmentIds']);
						if($total_shipment > 0): 
						foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $key => $data): 
						?>
						@if($key % 2 == 0)
						<div class="print-break">&nbsp;</div>
						<h4>กรุณานำใบนี้แปะที่แต่ละกล่อง</h4>
						@endif
						
						<div class="col-md-6 col-md-offet-3 col-print-12 print-break-inside" style="border: 1px solid #ccc;padding: 20px;maring-bottom:10px;">
    						<div class="col-md-7 col-print-7 text-center">
    						
								@if( isset($data['additionBarcode']) && $data['additionBarcode'] != "")
								<div><img src="/images/pickup/kerry_logo.png" style="width: 80px;"></div><br />
								<div>{!! $data['additionBarcode'] !!}</div>
								<hr />
								@endif

							</div> 
							<div class="col-md-5 col-print-5">
											
								<div style="font-size: 8px;"><strong>From: </strong>
								<?php echo $data['SenderDetail']['Firstname'];?> <?php echo $data['SenderDetail']['Lastname'];?><br />	
								Tel. <?php echo $data['SenderDetail']['PhoneNumber']; ?><br />
								<?php echo $data['SenderDetail']['AddressLine1']; ?> <?php echo $data['SenderDetail']['AddressLine2']; ?>
								<?php echo $data['SenderDetail']['City']; ?> <?php echo $data['SenderDetail']['State']; ?>
								<?php  echo $data['SenderDetail']['Postcode']; ?> Thailand<br /><br />
								</div>

								<div style="font-size: 10px;">
								<strong>To: </strong>
								บริษัท ฟาสต์ชิป จำกัด<br />	
								1/269 ซอยแจ้งวัฒนะ 14<br />
								ถนนแจ้งวัฒนะ แขวงทุ่งสองห้อง<br />
								เขตหลักสี่ กรุงเทพฯ 10210<br />
								โทร. 02-080-3999<br /><br />
								</div>
							</div>
							
							<div class="col-md-7 col-print-7 text-center">
								<img src="/images/pickup/fastship_logo.jpg" style="width: 80px;">
								<div style="margin-bottom: 7px; font-size: 11px;">
									<p style="margin-bottom: 7px">{!! $data['barcode'] !!}</p>
								</div>
							</div>
							<div class="col-md-5 col-print-5" style="font-size: 8px;">
								<?php echo $data['ReceiverDetail']['Firstname'];?> - <?php echo $countries[$data['ReceiverDetail']['Country']]; ?> <br />
								PickupID: <?php echo $pickup_data['ID']; ?> (<?php echo $key+1; ?>/<?php echo $total_shipment; ?>) <br />
								Agent: <?php  echo $data['ShipmentDetail']['ShippingAgent']; ?>
							</div>
	
						</div>	
						<?php 
						$cnt++;
						endforeach;
						endif;
						?>
						</div>
						<br>
						
						@elseif($pickup_data['PickupType'] == "Pickup_AtHomeNextdayBulk" || $pickup_data['PickupType'] == "Pickup_ByKerryBulk")
						
						<div class="col-md-12 col-print-12">
						
						<div class="print-break" style="padding-bottom: 30px;">&nbsp;</div>
						
						<h4>กรุณานำใบนี้แปะที่กล่องใหญ่</h4>
						<div class="col-md-6 col-md-offet-3 col-print-12 print-break-inside" style="border: 1px solid #ccc; margin-bottom: 30px;padding: 20px;">
    						
    						<div class="col-md-7 col-print-7 text-center">

								@if( isset($additionalBarcodeImage) && $additionalBarcodeImage != "")
								<div><img src="/images/pickup/kerry_logo.png" style="width: 80px;"></div><br />
								<div>{!! $additionalBarcodeImage !!}</div>
								@endif

							</div> 
							<div class="col-md-5 col-print-5">
											
								<div style="font-size: 8px;"><strong>From: </strong>
								<?php echo $pickup_data['PickupAddress']['Firstname'];?> <?php echo $pickup_data['PickupAddress']['Lastname'];?><br />	
								Tel. <?php echo $pickup_data['PickupAddress']['PhoneNumber']; ?><br />
								<?php echo $pickup_data['PickupAddress']['AddressLine1']; ?> <?php echo $pickup_data['PickupAddress']['AddressLine2']; ?>
								<?php echo $pickup_data['PickupAddress']['City']; ?> <?php echo $pickup_data['PickupAddress']['State']; ?>
								<?php  echo $pickup_data['PickupAddress']['Postcode']; ?> Thailand<br /><br />
								</div>

								<div style="font-size: 10px;">
								<strong>To: </strong>
								บริษัท ฟาสต์ชิป จำกัด<br />	
								1/269 ซอยแจ้งวัฒนะ 14<br />
								ถนนแจ้งวัฒนะ แขวงทุ่งสองห้อง<br />
								เขตหลักสี่ กรุงเทพฯ 10210<br />
								โทร. 02-080-3999<br /><br />
								</div>
								
								<div style="font-size: 10px;">
								PickupID : <?php echo $pickup_data['ID']; ?>
								</div>
								
							</div>

						</div>
						<div class="clearfix" style="clear:both;"></div>

						<?php 
						$cnt= 1;
						$total_shipment = sizeof($pickup_data['ShipmentDetail']['ShipmentIds']);
						if($total_shipment > 0): 
						foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $key => $data): 
						?>
						@if($key % 4 == 0)
						<div class="print-break">&nbsp;</div>
						<h4>กรุณานำใบนี้แปะที่กล่องเล็กแต่ละกล่อง</h4>
						@endif
						<div class="col-md-3 col-print-6 print-break-inside" style="border: 1px solid #ccc;padding: 20px;">
    						<div class="col-md-12 col-print-12 text-center">
    							<img src="/images/pickup/fastship_logo.jpg" style="width: 80px;">
								<div style="margin-bottom: 7px; font-size: 11px;">
									<p style="margin-bottom: 7px">{!! $data['barcode'] !!}</p>
								</div>
								<div style="margin-bottom: 7px; font-size: 8px;">
								PickupID : <?php echo $pickup_data['ID']; ?> (<?php echo $key+1; ?>/<?php echo $total_shipment; ?>)<br />
								Agent: <?php  echo $data['ShipmentDetail']['ShippingAgent']; ?>
								</div>
								<hr />
							</div> 
							<div class="col-md-12 col-print-12">
											
								<div style="font-size: 8px;"><strong>From: </strong>
								<?php echo $data['SenderDetail']['Firstname'];?> <?php echo $data['SenderDetail']['Lastname'];?><br />	
								Tel. <?php echo $data['SenderDetail']['PhoneNumber']; ?><br />
								<?php echo $data['SenderDetail']['AddressLine1']; ?> <?php echo $data['SenderDetail']['AddressLine2']; ?>
								<?php echo $data['SenderDetail']['City']; ?> <?php echo $data['SenderDetail']['State']; ?>
								<?php  echo $data['SenderDetail']['Postcode']; ?> Thailand<br /><br />
								</div>

								<div style="font-size: 10px;">
								<strong>To:</strong><br />
								<?php if(isset($data['ReceiverDetail']['Company'])): ?>
									<?php echo $data['ReceiverDetail']['Company']; ?> <br />
								<?php endif; ?>
								<?php echo $data['ReceiverDetail']['Firstname'] . " " . $data['ReceiverDetail']['Lastname']; ?><br />
								Tel. <?php echo $data['ReceiverDetail']['PhoneNumber']; ?><br />
								<?php echo $data['ReceiverDetail']['AddressLine1']; ?> <?php echo $data['ReceiverDetail']['AddressLine2']; ?>
								<?php echo $data['ReceiverDetail']['City']; ?> <?php echo $data['ReceiverDetail']['State']; ?>
								<?php echo $data['ReceiverDetail']['Postcode']; ?> <?php echo $countries[$data['ReceiverDetail']['Country']]; ?><br />
								</div>
								
							</div>
							
						</div>	
						<?php 
						$cnt++;
						endforeach;
						endif;
						?>
						</div>
						<br>
						
						@elseif($pickup_data['PickupType'] != "Drop_AtThaiPost")
									
						<div class="col-md-12 col-print-12">
								<?php 
								$cnt= 1;
								$total_shipment = sizeof($pickup_data['ShipmentDetail']['ShipmentIds']);
								if($total_shipment > 0): 
									foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $key => $data): 
									?>
									
									
									<div class="col-md-8 col-md-offet-2 col-print-12 print-break-inside" style="border: 1px solid #ccc; margin-bottom: 30px;padding: 20px;">
										
										<div class="col-md-7 col-print-7 text-center">
											@if( isset($data['additionBarcode']) && $data['additionBarcode'] != "")
    										<div>{!! $data['additionBarcode'] !!}</div>
    										<div style="font-size: 8px;">For Kerry only</div>
    										<hr />
											@endif
											
											<img src="/images/pickup/fastship_logo.jpg" style="width: 80px;">
    										<div style="margin-bottom: 7px; font-size: 11px;">
    											<p style="margin-bottom: 7px">{!! $data['barcode'] !!}</p>
    										</div>
		
										</div> 
										<div class="col-md-5 col-print-5">
											
											<div style="font-size: 8px;"><strong>From:</strong><br />
											<?php echo $data['SenderDetail']['Firstname'];?> <?php echo $data['SenderDetail']['Lastname'];?><br />	
											Tel. <?php echo $data['SenderDetail']['PhoneNumber']; ?><br />
											<?php echo $data['SenderDetail']['AddressLine1']; ?> <?php echo $data['SenderDetail']['AddressLine2']; ?>
											<?php echo $data['SenderDetail']['City']; ?> <?php echo $data['SenderDetail']['State']; ?>
											<?php  echo $data['SenderDetail']['Postcode']; ?> Thailand<br /><br />
											</div>

											@if($pickup_data['PickupType'] != 'Pickup_AtHomeNextdayBulk')
											<div style="font-size: 10px;">
											To:<br />
											บริษัท ฟาสต์ชิป จำกัด<br />	
											1/269 ซอยแจ้งวัฒนะ 14<br />
											ถนนแจ้งวัฒนะ แขวงทุ่งสองห้อง<br />
											เขตหลักสี่ กรุงเทพฯ 10210<br />
											โทร. 02-080-3999<br /><br />
											</div>
											<div style="font-size: 10px;">
											<strong>Shipment:</strong><br />
											<?php echo $data['ReceiverDetail']['Firstname'];?> - <?php echo $countries[$data['ReceiverDetail']['Country']]; ?> <br />
    										PickupID : <?php echo $pickup_data['ID']; ?> (<?php echo $key+1; ?>/<?php echo $total_shipment; ?>)
    										</div>
											@else
											<div style="font-size: 10px;">
											<strong>Shipment:</strong><br />
											PickupID : <?php echo $pickup_data['ID']; ?> (<?php echo $key+1; ?>/<?php echo $total_shipment; ?>)<br />
    										<?php if(isset($data['ReceiverDetail']['Company'])): ?>
												<?php echo $data['ReceiverDetail']['Company']; ?> <br />
											<?php endif; ?>
											<?php echo $data['ReceiverDetail']['Firstname'] . " " . $data['ReceiverDetail']['Lastname']; ?><br />
											Tel. <?php echo $data['ReceiverDetail']['PhoneNumber']; ?><br />
											<?php echo $data['ReceiverDetail']['AddressLine1']; ?> <?php echo $data['ReceiverDetail']['AddressLine2']; ?>
											<?php echo $data['ReceiverDetail']['City']; ?> <?php echo $data['ReceiverDetail']['State']; ?>
											<?php echo $data['ReceiverDetail']['Postcode']; ?> <?php echo $countries[$data['ReceiverDetail']['Country']]; ?>
											</div>
											@endif

										</div>
	
									</div>
									<?php if($key % 2 == 1){ ?>
										<div class="print-break" style="padding-bottom: 30px;">&nbsp;</div>
									<?php } ?>
									<?php 
									$cnt++;
									endforeach;
								endif;
								?>
						</div>
						<br>
						@endif
						
						<div class="text-center" >
							<a onclick="javascript:window.print();" class="btn btn-primary btn-lg hidden-print" style="margin-top: 40px;">
								Print <i class="fa fa-print"></i>
							</a>
						</div>
						
						<hr />
						
						<div class="row hidden-print">
						@if($pickup_data['PickupType'] == "Pickup_ByKerry")
							<h4>ขั้นตอนการปริ้นใบปะหน้าสำหรับเข้ารับโดย Kerry</h4>
							<div>1.	หลังจากชำระเงินเรียบร้อยแล้ว กดปุ่ม "พิมพ์ใบปะหน้า"</div>
							<div><img src="{{ url('images/help/kerry_help1.jpg') }}" /></div>
							<div class="text-center">ในกรณีที่ท่านออกจากหน้านี้ไปแล้ว ให้ไปที่ เมนู “ใบรับพัสดุ” > เลือกรายการที่ต้องการปริ้น > พิมพ์ใบปะหน้า</div>
							<br />
							
							<div>2.	นำใบปะหน้าที่มีโลโก้ Kerry ติดลงบนกล่องพัสดุ และแพ็คกล่องให้เรียบร้อย</div>
							<div><img src="{{ url('images/help/kerry_help2.jpg') }}" /></div>
							<br />
							<div class="text-center">สอบถามรายละเอียดเพิ่มเติม Line: @fastship.co</div>
						@elseif($pickup_data['PickupType'] == "Pickup_ByKerryBulk")
							<h4>ขั้นตอนการปริ้นใบปะหน้าสำหรับเข้ารับโดย Kerry</h4>
							<div>1.	หลังจากชำระเงินเรียบร้อยแล้ว กดปุ่ม "พิมพ์ใบปะหน้า"</div>
							<div><img src="{{ url('images/help/kerry_help1.jpg') }}" /></div>
							<div class="text-center">ในกรณีที่ท่านออกจากหน้านี้ไปแล้ว ให้ไปที่ เมนู “ใบรับพัสดุ” > เลือกรายการที่ต้องการปริ้น > พิมพ์ใบปะหน้า</div>
							<br />
							
							<div>2.	หลังจากปริ้นจะได้รับใบปะหน้า 2 แบบ</div>
							<div class="text-center" style="width: 48%;display: inline-block;">
								<img src="{{ url('images/help/kerry_help2.jpg') }}" /><br /> แบบที่ 1 ใบปะหน้าพัสดุ สำหรับติดกล่องใหญ่
							</div>
							<div class="text-center" style="width: 48%;display: inline-block">
								<img src="{{ url('images/help/kerry_help3.jpg') }}" /><br /> แบบที่ 2 ใบปะหน้าพัสดุ สำหรับติดกล่องเล็ก แต่ละกล่อง
							</div>
							<br /><br />
							
							<div>3.	หลังจากติดใบเรียบร้อยแล้ว ให้นำกล่องทั้งหมด(ที่ติดใบปะหน้าแบบที่ 2) รวมใส่กล่องใหญ่ 1 กล่อง และทำการแพ็คให้เรียบร้อย เมื่อแพ็คเรียบร้อยแล้วให้นำใบปะหน้าที่มีโลโก้ Kerry ติดลงบนกล่องใหญ่</div>
							<br />
							
							<div class="text-center">สอบถามรายละเอียดเพิ่มเติม Line: @fastship.co</div>
						@endif
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<script>
			window.onload = function(e){ 
				window.print();
			}
		</script>
	</body>
</html>