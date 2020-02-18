<?php 
	$root_path = 'http://app.fastship.co/'; 
?>
@extends('email/layout')
@section('content')

	<h1 class="table-content" style="font-weight: 600; background-color: #fff; color: #888; width: 940px; text-align: center; padding: 30px; margin-bottom: 20px;">ยินดีต้อนรับสู่ Fastship.co บัญชีของคุณถูกสร้างเรียบร้อยแล้ว</h1>
	
	<h2 style="font-weight: 600;">รายละเอียดบัญชีของคุณ มีดังนี้</h2>
	
	<table class="table-content">
		<tr>
			<td>ชื่อ-นามสกุล :</td>
			<td>{{ $customerData['Firstname'] }} {{ $customerData['Lastname'] }}</td>
		</tr>
		<tr>
			<td>เบอร์ติดต่อ :</td>
			<td>{{ $customerData['PhoneNumber'] }}</td>
		</tr>
		<tr>
			<td>Email :</td>
			<td>{{ $customerData['Email'] }}</td>
		</tr>
		<tr>
			<td>รหัสผ่าน :</td>
			<td>{{ $customerData['Password'] }} <span style="color: #f15a22;">(กรุณาเก็บเป็นความลับ)</span></td>
		</tr>
		<tr>
			<td>รหัสอ้างอิง :</td>
			<td>{{ $customerData['ReferCode'] }}</td>
		</tr>
	</table>
	<div style="text-align: center; margin: 30px auto;"><a href="{{url ('/login')}}"><img src="<?php echo $root_path; ?>images/email/btn-login.png"></a></div>
	
	<hr />
	
	<div class="row" style="font-size:14px;">
		<div class="faq-panel">
			<h2 style="color: #f15a22; font-weight: 600;"><img src="<?php echo $root_path; ?>images/email/icon-1.png"> วิธีใช้การใช้งานเว็บไซต์</h2>
			<a href="http://fastship.co/helps/vdo-fastship-co/">VDO สั้นๆ อธิบาย FastShip.co</a><br />
			<a href="http://fastship.co/helps/how-to-register-account/">วิธีการสมัครเปิดบัญชี FastShip.co</a><br />
			<a href="http://fastship.co/helps/add-credit-card/">การเพิ่มบัตรเครดิต</a><br />
			<a href="http://fastship.co/helps/how-to-link-ebay/">การเชื่อมต่อกับบัญชี eBay</a><br />
			<a href="http://fastship.co/helps/how-to-create-shipment/">ขั้นตอนการสร้างพัสดุ (Create Shipment)</a><br />
			<a href="http://fastship.co/helps/how-to-use-cart/">วิธีใช้งานหน้า Cart</a><br />
			<a href="http://fastship.co/helps/how-to-use-my-pickup/">วิธีใช้งานหน้า My Pickup</a><br />
			<a href="http://fastship.co/helps/how-to-use-my-shipments/">วิธีใช้งานหน้า My Shipments</a><br />
			<a href="http://fastship.co/helps/contact-us/">ติดต่อขอความช่วยเหลือ</a><br />
		</div>
		<div class="faq-panel">
			<h2 style="color: #f15a22; font-weight: 600;"><img src="<?php echo $root_path; ?>images/email/icon-2.png"> คำถามที่พบบ่อย</h2>
			<a href="http://fastship.co/helps/shipping-agents/">ส่งผ่านวิธีใดบ้าง</a><br />
			<a href="http://fastship.co/helps/shipping-cost/">ค่าใช้จ่ายในการส่งสินค้า</a><br />
			<a href="http://fastship.co/helps/package-guide/">คู่มือการบรรจุหีบห่อ</a><br />
			<a href="http://fastship.co/helps/terms-conditions/">ข้อตกลงและเงื่อนไขในการขนส่ง</a><br />
			<a href="http://fastship.co/helps/warrantee-and-insurance/">การรับประกันของหาย</a><br />
			<a href="http://fastship.co/helps/prohibited-items/">สินค้าที่ไม่รับขนส่งไปต่างประเทศ</a><br />
			<a href="http://fastship.co/helps/duty-taxes-de-minimis/">ภาษีและการเดินพิธีศุลกากร</a><br />
			<a href="http://fastship.co/helps/payment/">วิธีการชำระเงิน</a><br />
		</div>
		<div class="faq-panel">
			<h2 style="color: #f15a22; font-weight: 600;"><img src="<?php echo $root_path; ?>images/email/icon-3.png"> ความรู้เกี่ยวกับ EBAY, AMAZON ETC</h2>
			<a href="http://fastship.co/helps/how-to-send-products-to-amazon-fba/">ส่งสินค้าไป Amazon FBA ทำยังไง</a>
		</div>
		
	</div>
	<br />
	
	<hr />
	<br />
	
@endsection