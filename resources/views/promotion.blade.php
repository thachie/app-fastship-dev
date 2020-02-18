@extends('layout')
@section('content')
<?php 
$refCode = "FGF".$customer_data['ID'];
$paramEncrypted = urlencode(base64_encode($refCode));
$inviteURL = "http://app.fastship.co/register!" . $paramEncrypted;
?>
<div class="conter-wrapper">
      
<div class="row">
	<div class="col-xs-12 col-md-6 col-md-offset-3">
		<h2>โปรโมชั่นสำหรับคุณ</h2>
	</div>
</div>


<div class="row">
    <div class="col-xs-12 col-md-6 col-md-offset-3">
    	<div class="panel panel-primary">
			<div class="panel-heading">ข้อมูลสิทธิประโยชน์</div>
		    <div class="panel-body">
	        	<div class="row">
	            	<div class="col-md-4 col-xs-6 text-right"><strong>ระดับบัญชี</strong></div>
	                <div class="col-md-8 col-xs-6"><?php echo $customer_data['group']; ?></div>
	                <div class="col-md-4 col-xs-6 text-right"><strong>รหัสโปรโมชั่นที่ใช้</strong></div>
	                <div class="col-md-8 col-xs-6"><?php echo $customer_data['refcode']; ?></div>
	            </div>
	            <div class="row" style="margin-bottom:10px;">    
	                <div class="well" style="line-height: 24px;">
	                	เพียงแชร์หรือคัดลอกลิงค์นี้ให้เพื่อนสมัคร <code style="padding: 0; word-wrap: break-word;"><?php echo $inviteURL; ?></code>
	                	ทันทีที่เพื่อนคุณเริ่มใช้บริการ Fastship.co เพื่อนของคุณจะได้รับส่วนลด 300 บาท 
						สำหรับการส่งครั้งแรก และคุณจะได้รับส่วนลด 300 บาท ต่อการใช้งานของเพื่อน 1 คน
	                	<div class="text-right"><a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($inviteURL); ?>" target="_blank"><button type="button" class="btn btn-success">แชร์ไปยัง Facebook</button></a></div>
	                </div>
	           	</div>
	        </div>
    	</div>
    </div>
    <div class="clearfix"></div><br />
</div>


</div>
@endsection