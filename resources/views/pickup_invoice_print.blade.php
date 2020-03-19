<!doctype>
<?php 
	//alert($pickup_data); 
	
	function convert($number){
		$txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
		$txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
		$number = str_replace(",","",$number);
		$number = str_replace(" ","",$number);
		$number = str_replace("บาท","",$number);
		$number = explode(".",$number);
		if(sizeof($number)>2){
			return 'ทศนิยมหลายตัวนะจ๊ะ';
			exit;
		}
		$strlen = strlen($number[0]);
		$convert = '';
		for($i=0;$i<$strlen;$i++){
			$n = substr($number[0], $i,1);
			if($n!=0){
				if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; }
				elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; }
				elseif($i==($strlen-2) AND $n==1){ $convert .= ''; }
				else{ $convert .= $txtnum1[$n]; }
				$convert .= $txtnum2[$strlen-$i-1];
			}
		}

		$convert .= 'บาท';
		if($number[1]=='0' OR $number[1]=='00' OR
				$number[1]==''){
					$convert .= 'ถ้วน';
		}else{
			$strlen = strlen($number[1]);
			for($i=0;$i<$strlen;$i++){
				$n = substr($number[1], $i,1);
				if($n!=0){
					if($i==($strlen-1) AND $n==1){$convert
					.= 'เอ็ด';}
					elseif($i==($strlen-2) AND
						$n==2){$convert .= 'ยี่';}
						elseif($i==($strlen-2) AND
							$n==1){$convert .= '';}
							else{ $convert .= $txtnum1[$n];}
							$convert .= $txtnum2[$strlen-$i-1];
				}
			}
			$convert .= 'สตางค์';
		}
		return $convert;
	}

?>
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
        	
	       	<div id="body-container" style="background-color: #fff; padding: 5px 0; font-size: 12px;">
	       	
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
										<h3 class="text-right" style="font-weight: 600; margin: 0;">
											ใบส่งของ
										</h3>
									</div>
								</div>
								<hr style="margin-bottom: 30px; border: 3px solid #f15a22;"> 
								<div class="row">
									<div class="col-md-7 col-print-7">
										<span style="font-size: 14px; font-weight: 600;">Fastship Co., Ltd. (สำนักงานใหญ่)</span><br />
										เลขประจำตัวผู้เสียภาษี <span style="color: #f15a22;">0105561035044</span><br />
										1/269 ซอยแจ้งวัฒนะ 14<br />
										ถนนแจ้งวัฒนะ แขวงทุ่งสองห้อง<br />
										เขตหลักสี่ กรุงเทพฯ 10210<br />
										โทร. 02-080-3999<br /><br />
										
										<span style="font-size: 14px; font-weight: 600;">ลูกค้า</span><br />
										<?php if($customer_data['Company']): ?>
											{{ $customer_data['Company'] }} <br />
											เลขประจำตัวผู้เสียภาษี {{ $customer_data['TaxId'] }} 
										<?php else: ?>
										{{ $customer_data['Firstname'] }} {{ $customer_data['Lastname'] }}
										<?php endif; ?>
										 <br />
										 
										{{ $customer_data['AddressLine1'] }} {{ $customer_data['AddressLine2'] }} 
										{{ $customer_data['City'] }} {{ $customer_data['State'] }} {{ $customer_data['Postcode'] }} <br />
										โทร. {{ $customer_data['PhoneNumber'] }} อีเมล์ {{ $customer_data['Email'] }} <br />
									</div>

									<div class="col-md-5 col-print-5" style="padding-left: 28px;">
										<div class="col-md-5 col-print-5 text-right" style="color: #f15a22;">
											เลขที่ <br />
											วันที่ <br />
											ผู้ขาย <br />
											รหัสอ้างอิง
										</div>
										<div class="col-md-7 col-print-7">
											INV{{ $pickup_data['ID'] }}<br />
											{{ date("d/m/Y",strtotime($pickup_data['CreateDate']['date'])) }}<br />
											Fastship.co<br />
											{{ $pickup_data['ID'] }}<br />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<table class="table pickup-print" style="font-size: 12px;">
											<thead>
												<tr>
													<th>หมายเลขพัสดุ</th>
													<th>รายละเอียดพัสดุ </th>
													<th>ผู้รับ</th>
													<th>วิธีการส่ง </th>
													<th>ยอดรวม (บาท)</th>
												</tr>
											</thead>
											<tbody>
											<?php 
											$cnt= 1;
				                            if(sizeof($pickup_data['ShipmentDetail']['ShipmentIds']) > 0): 
											foreach($pickup_data['ShipmentDetail']['ShipmentIds'] as $data): 
												$displayAgent = str_replace("_"," ",$data['ShipmentDetail']['ShippingAgent']);
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
												
												$DeclareTypes = explode(";",$data['ShipmentDetail']['DeclareType']);
												if($DeclareTypes [sizeof($DeclareTypes)-1] == ""){
													unset($DeclareTypes [sizeof($DeclareTypes)-1]);
												}
												$DeclareQtys = explode(";",$data['ShipmentDetail']['DeclareQty']);
												if($DeclareQtys [sizeof($DeclareQtys)-1] == ""){
													unset($DeclareQtys [sizeof($DeclareQtys)-1]);
												}
												$DeclareValues = explode(";",$data['ShipmentDetail']['DeclareValue']);
												if($DeclareValues [sizeof($DeclareValues)-1] == ""){
													unset($DeclareValues [sizeof($DeclareValues)-1]);
												}
				                            ?>
				                            <tr>
				                            	<td><?php echo $data['ID'];?></td>
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
												<td class="hidden-480 text-left"><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?><br />
														ประเทศ <?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
				                            	<td class="hidden-480"><?php echo $displayAgent; ?></td>
				                            	<td class="text-right"><?php echo number_format($data['ShipmentDetail']['ShippingRate'],0); ?></td>
				                            </tr>
				                            <?php 
				                            $cnt++;
				                            endforeach;
							                endif;
							                ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 invoice-block text-right" style="font-size: 12px;">
										<?php if($pickup_data['PickupCost'] > 0): ?>
											<div class="col-md-10 col-print-10">ค่ารับสินค้า (บาท) : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['PickupCost'],0); ?></div>
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
										<div class="col-md-7 col-print-7 text-center"><?php echo convert(number_format($pickup_data['Amount'],2)); ?></div>
										<div class="col-md-3 col-print-3">จำนวนเงินรวมทั้งสิ้น : </div><div class="col-md-2 col-print-2"><?php echo number_format($pickup_data['Amount'],0); ?> บาท</div>
									</div>
								</div>
								<div class="row" style="margin-top: 50px;">
									<div class="col-md-12 col-print-12 text-right">
										ชำระเงินเรียบร้อยแล้ว ผ่านทาง : <?php echo $paymentMethod[$pickup_data['PaymentMethod']];?>
									</div>
								</div>
								<div class="row" style="margin-top: 50px;">
									<div class="col-md-12 col-print-12 text-right">
										ผู้รับเงิน <br /><div class="row" style="margin: 10px auto;"><span class="col-md-4 col-print-4 pull-right" style="border-bottom: 2px solid black; margin-top: 30px;"></span></div>
										<p>(FastShip.co)</p>
									</div>
								</div>

							</div>
						</div>
						<br>
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


