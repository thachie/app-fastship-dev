<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<div class="content" style="font-family: 'Kanit',Arial;color:#36393D;font-size: 12px;width: 800px;padding: 30px 10px 10px 10px;background: #F9F7ED;">

	<table style="width: 800px;margin: auto;border: 0;">
		<tr>
			<td></td>
			<td style="width: 200px;text-align: right;">
				<img src="http://app.fastship.co/img/fastship_logo_black.png" style="max-width: 200px;"/>
				<br /><br />
			</td>
		</tr>
		
		<tr>
			<td style="width: 200px;">
				<h1 style="font-weight: 700;font-size:18px;">ที่อยู่ผู้ส่ง</h1>
				<div style="">
					{{ $pickupData['PickupAddress']['Firstname'] }}<br />
					เบอร์ติดต่อ: {{ $pickupData['PickupAddress']['PhoneNumber'] }} <br />
					Email: {{ $pickupData['PickupAddress']['Email'] }} <br />
					{{ $pickupData['PickupAddress']['AddressLine1'] }} {{ $pickupData['PickupAddress']['AddressLine2'] }}<br />
					{{ $pickupData['PickupAddress']['City'] }} {{ $pickupData['PickupAddress']['State'] }} {{ $pickupData['PickupAddress']['Postcode'] }}<br />
				</div>
			</td>
		</tr>
		
	</table>
	
	<br />
	
	<h1 style="font-weight: 700;text-transform: uppercase;">หมายเลขใบรับของ {{ $pickupData['ID'] }}</h1>
	
	<table style="width: 800px; border-collapse: collapse;border: 1px solid #ccc;margin: auto;">
		<tr>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: left;width: 150px;"><b>วันที่:</b></td>
			<td style="padding: 3px; border: 1px solid #ccc;">{{ $pickupData['ScheduleDate'] }}</td>
		</tr>
		<tr>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: left;width: 150px;"><b>วิธีการรับพัสดุ:</b></td>
			<td style="padding: 3px; border: 1px solid #ccc;">{{ $pickupData['PickupType'] }}</td>
		</tr>
		<tr>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: left;width: 150px;"><b>วิธีการชำระเงิน:</b></td>
			<td style="padding: 3px; border: 1px solid #ccc;">{{ $pickupData['PaymentMethod'] }}</td>
		</tr>
	</table>
	<br />
	
	<h1 style="font-weight: 700;">รายละเอียดพัสดุ</h1>
	<table style="width: 800px; border-collapse: collapse;border: 1px solid #ccc;margin: auto;text-align:center;">
		<tr>
			<td style="padding: 3px; border: 1px solid #ccc;">เลขพัสดุ</td>
			<td style="padding: 3px; border: 1px solid #ccc;">ผู้รับ</td>
			<td style="padding: 3px; border: 1px solid #ccc;">น้ำหนักและขนาด</td>
			<td style="padding: 3px; border: 1px solid #ccc;">วิธีการส่ง</td>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: right;">ค่าส่ง</td>
		</tr>		
		@foreach($shipmentData as $shipment)
		<tr>
			<td style="padding: 3px; border: 1px solid #ccc;">{{ $shipment['ID'] }}</td>
			<td style="padding: 3px; border: 1px solid #ccc;">{{ $shipment['ReceiverDetail']['Firstname'] }}</td>
			<td style="padding: 3px; border: 1px solid #ccc;">{{ $shipment['ShipmentDetail']['Weight'] }} ({{ $shipment['ShipmentDetail']['Width'] }}x{{ $shipment['ShipmentDetail']['Height'] }}x{{ $shipment['ShipmentDetail']['Length'] }})</td>
			<td style="padding: 3px; border: 1px solid #ccc;">{{ $shipment['ShipmentDetail']['ShippingAgent'] }}</td>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: right;">{{ $shipment['ShipmentDetail']['ShippingRate'] }}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="4" style="padding: 3px; border: 1px solid #ccc;text-align: right;width: 150px;">ค่าขนส่งรวม(บาท)</td>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: right;">{{ $pickupData['ShipmentDetail']['TotalShippingRate'] }}</td>
		</tr>
		<tr>
			<td colspan="4" style="padding: 3px; border: 1px solid #ccc;text-align: right;width: 150px;">ค่ารถรับพัสดุ(บาท)</td>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: right;">{{ $pickupData['Cost'] }}</td>
		</tr>
		<tr>
			<td colspan="4" style="padding: 3px; border: 1px solid #ccc;text-align: right;width: 150px;">ส่วนลด(บาท)</td>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: right;">{{ $pickupData['Discount'] }}</td>
		</tr>
		<tr>
			<td colspan="4" style="padding: 3px; border: 1px solid #ccc;text-align: right;width: 150px;"><b>ยอดรวม(บาท)</b></td>
			<td style="padding: 3px; border: 1px solid #ccc;text-align: right;">{{ $pickupData['Amount'] }}</td>
		</tr>
	</table>
	<br />
	
	<p>
		ขอบคุณที่ใช้บริการกับ <a href="http://www.fastship.co">fastship.co</a>
	</p>
	<img src="http://app.fastship.co/img/fastship_logo_black.png" style="max-width: 200px;"/>
	
</div>