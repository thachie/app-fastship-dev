<?php 
	$root_path = 'http://app.fastship.co/'; 
?>
@extends('email/layout')
@section('content')

	<h1 class="table-content" style="font-weight: 600; background-color: #fff; color: #888; width: 940px; text-align: center; padding: 30px; margin-bottom: 20px;">รายละเอียดพัสดุของคุณ</h1>

	<h2 style="font-weight: 600;">หมายเลขใบรับพัสดุ : <span class="pickupid">{{ $pickupData['ID'] }}</span></h2>
	<table class="table-content" style="margin-bottom: 30px;">
		<tr style="background-color: #f15a22; color: #fff;">
			<td>ข้อมูลผู้ส่ง</td>
		</tr>
		<tr>
			<td><img src="<?php echo $root_path; ?>images/email/lcon-info-02.png"> &nbsp; {{ $pickupData['PickupAddress']['Firstname'] }} {{ $pickupData['PickupAddress']['Lastname'] }}</td>
		</tr>
		<?php if($pickupData['PickupAddress']['AddressLine1'] != ""){ ?>
		<tr>
			<td><img src="<?php echo $root_path; ?>images/email/lcon-info-03.png"> &nbsp; {{ $pickupData['PickupAddress']['AddressLine1'] }} {{ $pickupData['PickupAddress']['AddressLine2'] }}
					{{ $pickupData['PickupAddress']['City'] }} {{ $pickupData['PickupAddress']['State'] }} {{ $pickupData['PickupAddress']['Postcode'] }}</td>
		</tr>
		<?php }else{ echo ""; } ?>
		<tr>
			<td><img src="<?php echo $root_path; ?>images/email/lcon-info-04.png"> &nbsp; {{ $pickupData['PickupAddress']['PhoneNumber'] }}</td>
		</tr>
		<tr>
			<td><img src="<?php echo $root_path; ?>images/email/lcon-info-05.png"> &nbsp; {{ $pickupData['PickupAddress']['Email'] }}</td>
		</tr>
	</table>
	<table class="table-content">
		<tr>
			<td style="background-color: #f15a22; color: #fff;">วิธีการรับพัสดุ : </td>
			<td>{{ $pickupType[$pickupData['PickupType']] }}</td>
		</tr>
		<tr>
			<td style="background-color: #f15a22; color: #fff;">วิธีการชำระเงิน : </td>
			<td>{{ $paymentMethod[$pickupData['PaymentMethod']] }}</td>
		</tr>
	</table>
	<h2 style="font-weight: 600;">รายละเอียด</h2>
	<table class="table-content" style="text-align: center; margin-bottom: 30px;">
		<tr style="background-color: #f15a22; color: #fff;">
			<td>เลขพัสดุ</td>
			<td>ที่อยู่ผู้รับ</td>
			<td>รายละเอียดพัสดุ</td>
			<td>วิธีการส่ง</td>
			<td>ค่าส่ง (บาท)</td>
		</tr>
		@foreach($shipmentData as $shipment)
		<tr>
			<td>{{ $shipment['ID'] }}</td>
			<td style="text-align: left;">{{ $shipment['ReceiverDetail']['Firstname'] }} {{ $shipment['ReceiverDetail']['Lastname'] }}<br />{{ $shipment['ReceiverDetail']['AddressLine1'] }} {{ $shipment['ReceiverDetail']['AddressLine2'] }}
					{{ $shipment['ReceiverDetail']['City'] }} {{ $shipment['ReceiverDetail']['State'] }} {{ $shipment['ReceiverDetail']['Postcode'] }} {{ $shipment['ReceiverDetail']['Country'] }}</td>
			<td style="text-align: left;">{{ $shipment['ShipmentDetail']['Weight'] }} 
			<?php if($shipment['ShipmentDetail']['Width'] > 0 && $shipment['ShipmentDetail']['Height'] > 0 && $shipment['ShipmentDetail']['Length'] > 0 ){ ?> 
				({{ $shipment['ShipmentDetail']['Width'] }}x{{ $shipment['ShipmentDetail']['Height'] }}x{{ $shipment['ShipmentDetail']['Length'] }})
			<?php }else{ echo ""; } ?>
			</td>
			<td>{{ $shipment['ShipmentDetail']['ShippingAgent'] }}<br />Term: {{ $shipment['ShipmentDetail']['TermOfTrade'] }}</td>
			<td>{{ number_format($shipment['ShipmentDetail']['ShippingRate']) }}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="4" style="text-align: right;">ค่าขนส่งรวม (บาท)</td>
			<td>{{ number_format($pickupData['ShipmentDetail']['TotalShippingRate']) }}</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: right;">ค่ารถรับพัสดุ (บาท)</td>
			<td>{{ number_format($pickupData['Cost']) }}</td>
		</tr>	
		<tr>
			<td colspan="4" style="text-align: right;">ส่วนลด (บาท)</td>
			<td>{{ number_format($pickupData['Discount']) }}</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: right; font-size: 20px; font-weight: 600;">ยอดรวม (บาท)</td>
			<td style="font-size: 22px; font-weight: 600;">{{ number_format($pickupData['Amount']) }}</td>
		</tr>
	</table>

@endsection