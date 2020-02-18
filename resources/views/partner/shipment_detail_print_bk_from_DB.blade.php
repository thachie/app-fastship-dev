<!doctype>
<?php //alert($pickup_data); ?>
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
        <link rel="stylesheet" type="text/css" href="/css/custom.css"/>
		<link rel="stylesheet" type="text/css" href="/css/timeline.css"/>
		<link rel="stylesheet" type="text/css" href="/css/step.css"/>
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
				<div class="col-12 col-md-10 col-md-offset-1 alert alert-<?php echo  session('msg-type'); ?>" style="margin-top: 30px;">
					{{ session('msg') }}
				</div>
				@else
				<div class="col-12 col-md-10 col-md-offset-1 alert alert-danger" style="margin-top: 30px;">
					{{ session('msg') }}
				</div>
				@endif
				@endif

				<div class="row">
					<div class="col-md-12">
						<div class="wellb">
							<div class="invoice">
								<div class="row invoice-logo" style="padding: 0;">
									<div class="col-md-6 col-print-6 dashy-left">
										<img src="/images/logo-1.png">
									</div>
									<div class="col-md-6 col-print-6 dashy-right">
										<h5 class="text-right" style="font-weight: 600; margin: 0;">
											หมายเลขใบพัสดุ <?php echo $res->TRACKING_NUMBER; ?>
										</h5>
										<h6 class="text-right" style="margin: 0;">วันเวลาที่พิมพ์  <?php echo date("d M Y H:i:s"); ?></h6>
									</div>
								</div>
								<hr style="margin-bottom: 30px; border: 3px solid #f15a22;"> 
								<div class="row">
									<div class="col-md-5 col-print-5">
										<h4 style="font-weight: 600;">ที่อยู่ผู้รับ</h4>
										<div class="well">
											<div>
												<?php $ReceivName = $res->RECEIVER_FIRSTNAME. ' '. $res->RECEIVER_LASTNAME;?>
												<p style="margin:0; font-size: 16px;">
												{{ $ReceivName }} </p>
												{{ $res->RECEIVER_ADDRESS_1 }}, 
												{{ $res->RECEIVER_ADDRESS_2 }}
												<br>
												{{ $res->RECEIVER_CITY }} ,
												{{ $res->RECEIVER_STATE }} ,
												{{ $res->RECEIVER_POSTCODE }} 
												<br>
												Phone : {{ $res->RECEIVER_PHONE }} 
												<br />
												E-mail : {{ $res->RECEIVER_EMAIL }}
											</div>
										</div>
									</div>
									<div class="col-md-1 col-print-1">
									</div>
									<div class="col-md-6 col-print-6">
										<h4 style="font-weight: 600;">รายละเอียดใบพัสดุ</h4>
										<div class="col-md-5 col-print-12" style="padding-left: 0;">
											<?php echo $barcode; ?><br /><br />
										</div>
										<!--<div class="col-md-7" style="padding-left: 0;">	
											<div class="col-md-12 col-print-12" style="padding-left: 0;">วันที่สร้าง : {{ date("d/m/Y H:i:s",strtotime($pickup_data['CreateDate']['date'])) }} </div>
										</div>-->
									</div>
								</div>

								<div class="row">
									<div class="col-md-5 col-print-5">
										<h4 style="font-weight: 600;">ที่อยู่ผู้ส่ง</h4>
										<div class="well">
											<div>
												<p style="margin:0; font-size: 16px;">{{ $pickup_data['SenderDetail']['Firstname'] }} {{ $pickup_data['SenderDetail']['Lastname'] }}</p>
												{{ $pickup_data['SenderDetail']['AddressLine1'] }} {{ $pickup_data['SenderDetail']['AddressLine2'] }}
												<br>
												{{ $pickup_data['SenderDetail']['City'] }} {{ $pickup_data['SenderDetail']['State'] }} {{ $pickup_data['SenderDetail']['Postcode'] }} 
												<br>
												Phone : {{ $pickup_data['SenderDetail']['PhoneNumber'] }} 
												<br />
												E-mail : {{ $pickup_data['SenderDetail']['Email'] }}
											</div>
										</div>
									</div>
									<div class="col-md-1 col-print-1">
									</div>
									<div class="col-md-6 col-print-6">
										<div class="col-md-7" style="padding-left: 0;">	
											<div class="col-md-12 col-print-12" style="padding-left: 0;">วันที่สร้าง : {{ date("d/m/Y H:i:s",strtotime($pickup_data['CreateDate']['date'])) }} </div>

										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<table class="table pickup-print" style="font-size: 11px;">
											<thead>
												<tr>
													<th> เลขพัสดุ </th>
													<th> ประเภทสินค้า </th>
													<th class="hidden-480"> ผู้รับ </th>
													<th class="hidden-480"> วิธีการส่ง </th>
													<th> ค่าส่ง (บาท)</th>
												</tr>
											</thead>
											<tbody>
											<?php 
											$cnt= 1;
												$displayAgent = str_replace("_"," ",$pickup_data['ShipmentDetail']['ShippingAgent']);
												if($displayAgent == "GM Packet Economy"){
													$displayAgent = "GM Economy";
												}else if($displayAgent == "GM Packet"){
													$displayAgent = "GM Non-Registered";
												}else if($displayAgent == "GM Packet Plus"){
													$displayAgent = "GM Registered";
												}else if($displayAgent == "DHL"){
													$displayAgent = "FastShip Express";
												}else if($displayAgent == "UPS"){
													$displayAgent = "UPS Express";
												}else if($displayAgent == "SF"){
													$displayAgent = "SF Express";
												}
												
												$DeclareTypes = explode(";",$pickup_data['ShipmentDetail']['DeclareType']);
												if($DeclareTypes [sizeof($DeclareTypes)-1] == ""){
													unset($DeclareTypes [sizeof($DeclareTypes)-1]);
												}
												$DeclareQtys = explode(";",$pickup_data['ShipmentDetail']['DeclareQty']);
												if($DeclareQtys [sizeof($DeclareQtys)-1] == ""){
													unset($DeclareQtys [sizeof($DeclareQtys)-1]);
												}
												$DeclareValues = explode(";",$pickup_data['ShipmentDetail']['DeclareValue']);
												if($DeclareValues [sizeof($DeclareValues)-1] == ""){
													unset($DeclareValues [sizeof($DeclareValues)-1]);
												}
												
				                            ?>
				                            <tr>
				                            	<td><?php echo $pickup_data['ID'];?></td>
				                            	<td>
												<?php 
												if(sizeof($DeclareTypes) > 0):
													if(is_array($DeclareTypes)):
														foreach($DeclareTypes as $key => $Type):
															$dtype = (isset($declareTypes[$Type]))?$declareTypes[$Type]:$Type;
												
												?>
													<?php echo $dtype; ?> ×  
													<?php echo isset($DeclareQtys[$key])?($DeclareQtys[$key]):"1"; ?>
													(<?php echo isset($DeclareValues[$key])?($DeclareValues[$key]):"-"; ?> บาท)
													<br />
												<?php 	
														endforeach;
													endif; 
												endif;
												?>
												</td>
												<td class="hidden-480 text-left"><?php echo $pickup_data['ReceiverDetail']['Firstname'];?> <?php echo $pickup_data['ReceiverDetail']['Lastname'];?><br />
														ประเทศ <?php echo $countries[$pickup_data['ReceiverDetail']['Country']];?></td>
				                            	<td class="hidden-480">
													<?php echo $displayAgent; ?><br />
													<?php echo $pickup_data['Remark']; ?>
												</td>
				                            	<td><?php echo number_format($pickup_data['ShipmentDetail']['ShippingRate'],0); ?></td>
				                            </tr>
				                            <?php 
							                ?>
											</tbody>
										</table>
									</div>
								</div>
								<?php die();?>
								<div class="row">
									<div class="col-md-12 invoice-block text-right" style="font-size: 13px;">
										<div class="col-md-10 col-print-10">รวมค่าส่ง (บาท) : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['ShipmentDetail']['TotalShippingRate'],0); ?></div>
										<?php if($pickup_data['Cost'] > 0): ?>
											<div class="col-md-10 col-print-10">ค่ารับสินค้า (บาท) : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['Cost'],0); ?></div>
										<?php endif; ?>
										<?php if($pickup_data['PackingCost'] > 0): ?>
											<div class="col-md-10 col-print-10">ค่าบริการบรรจุภัณฑ์ (บาท) : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['PackingCost'],0); ?></div>
										<?php endif; ?>
										<?php if($pickup_data['Insurance'] > 0): ?>
											<div class="col-md-10 col-print-10">ค่าประกัน (บาท) : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['Insurance'],0); ?></div>
										<?php endif; ?>
										<?php if($pickup_data['AdditionCost'] != 0): ?>
											<div class="col-md-10 col-print-10">ค่าบริการเพิ่มเติม (บาท) : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['AdditionCost'],0); ?></div>
										<?php endif; ?>
										<?php if($pickup_data['Discount'] > 0): ?>
											<div class="col-md-10 col-print-10">ส่วนลด (บาท) : </div><div class="col-md-2 col-print-2">-<?php echo number_format($pickup_data['Discount'],0); ?></div>
										<?php endif; ?>
										<div class="col-md-10 col-print-10">ยอดชำระทั้งหมด (บาท) : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['Amount'],0); ?></div>
									</div>
								</div>
								<div class="row" style="margin-top: 50px;">
									<div class="col-md-6 col-print-6 text-left">
										ผู้รับสินค้า <br /><div class="row" style="margin: 10px auto;"><span class="col-md-4 col-print-6" style="border-bottom: 2px solid black; margin-top: 30px;"></span></div>
										<p>(Fastship.co)</p>
									</div>
									<div class="col-md-6 col-print-6 text-right">
										ผู้ส่งสินค้า <br /><div class="row" style="margin: 10px auto;"><span class="col-md-4 col-print-6 pull-right" style="border-bottom: 2px solid black; margin-top: 30px;"></span></div>
										<p>({{ $pickup_data['SenderDetail']['Firstname'] }} {{ $pickup_data['SenderDetail']['Lastname'] }})</p>
									</div>
								</div>
								<hr >
								<div class="col-md-12 col-print-12">ข้อความเพิ่มเติม : {{ $pickup_data['Remark'] }}</div>
								<br />

							</div>
						</div>

						<?php if($pickup_data['PickupType'] != "Pickup_ByKerry" && $pickup_data['PickupType'] != "Drop_AtThaiPost"): ?>
									
						<div class="" >
							<div class="col-md-12 col-print-12">
								<?php 
								$cnt= 1;
								$total_shipment = sizeof($pickup_data['ShipmentDetail']['ShipmentIds']);
								if($total_shipment > 0): 
									foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $key => $data): 
									?>
									
									<?php if($key % 3 == 0){ ?>&nbsp;
										<div class="print-break" style="padding-bottom: 30px;">&nbsp;</div>
									<?php } ?>
									<div class="col-md-8 col-md-offset-2 col-print-10 print-break-inside" style="border: 3px dashed #ccc; margin-bottom: 30px; padding: 20px;">
										<div class="col-md-6 col-print-6">
											<span style="font-size: 11px;">
											From : <br />
											<?php echo $data['SenderDetail']['Firstname'];?> <?php echo $data['SenderDetail']['Lastname'];?><br />	
											Tel. <?php echo $data['SenderDetail']['PhoneNumber']; ?><br />
											<?php echo $data['SenderDetail']['AddressLine1']; ?> <?php echo $data['SenderDetail']['AddressLine2']; ?>
											<?php echo $data['SenderDetail']['City']; ?> <?php echo $data['SenderDetail']['State']; ?>
											<?php echo $data['SenderDetail']['Postcode']; ?> <?php echo $countries[$data['SenderDetail']['Country']]; ?><br /><br />
											</span>
											
											
												<h4 style="font-size: 15px;">
												To : <br />
												บริษัท ฟาสต์ชิป จำกัด<br />	
												โทร. 02-080-3999<br />
												1/269 ซอยแจ้งวัฒนะ 14<br />
												ถนนแจ้งวัฒนะ แขวงทุ่งสองห้อง<br />
												เขตหลักสี่ กรุงเทพฯ 10210
												</h4>
												<?php /* ?>
												<?php if(isset($data['ReceiverDetail']['Company'])): ?>
													<?php echo $data['ReceiverDetail']['Company']; ?> <br />
												<?php endif; ?>
												<?php echo $data['ReceiverDetail']['Firstname'] . " " . $data['ReceiverDetail']['Lastname']; ?><br />
												Tel. <?php echo $data['ReceiverDetail']['PhoneNumber']; ?><br />
												<?php echo $data['ReceiverDetail']['AddressLine1']; ?> <?php echo $data['ReceiverDetail']['AddressLine2']; ?>
												<?php echo $data['ReceiverDetail']['City']; ?> <?php echo $data['ReceiverDetail']['State']; ?>
												<?php echo $data['ReceiverDetail']['Postcode']; ?> <?php echo $countries[$data['ReceiverDetail']['Country']]; ?>
												</h4>
												<?php */ ?>

										</div>
										<div class="col-md-6 col-print-6 text-center" >
											<img src="/images/pickup/fastship_logo.jpg">
											<div style="margin-bottom: 7px; font-size: 11px;">
												<p style="margin-bottom: 7px"><?php echo $data['barcode']; ?></p>
												<?php echo $data['ReceiverDetail']['Firstname'];?> - <?php echo $countries[$data['ReceiverDetail']['Country']]; ?> <br />
												PickupID : <?php echo $pickup_data['ID']; ?> (<?php echo $key+1; ?>/<?php echo $total_shipment; ?>)
											</div>
											
											<?php if($pickup_data['PickupType'] == "Drop_AtSkybox"){ ?>
												<img src="/images/pickup/skybox_logo.jpg">
												<div><?php echo $additionalBarcodeImage; ?></div>
											<?php }elseif($pickup_data['PickupType'] == "Drop_AtBox24"){ ?>
												<img src="/images/pickup/box24_logo.jpg">
											<?php } ?>

										</div>		
									</div>
									
									<?php 
									$cnt++;
									endforeach;
								endif;
								?>
							</div>
						</div>
						<br>
						<?php endif; ?>
						
						<div class="text-center">
							<a onclick="javascript:window.print();" class="btn btn-primary btn-lg hidden-print">
								Print <i class="fa fa-print"></i>
							</a>
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


