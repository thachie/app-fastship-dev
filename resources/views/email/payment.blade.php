<?php $root_path = 'http://app2.fastship.co/'; ?>
@extends('email/layout')
@section('content')

	<h2 style="margin: 30px auto; color: #888; font-weight: 600; text-align: center;">ยินดีต้อนรับสู่ Fastship.co บัญชีของคุณถูกสร้างเรียบร้อยแล้ว</h2>
	
	<h3 style="font-weight: 600;"> รายละเอียดบัญชีของคุณ มีดังนี้</h3>
	
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
	
@endsection