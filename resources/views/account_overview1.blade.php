@extends('layout')
@section('content')
<?php 
$refCode = "FGF".$customer_data['ID'];
$paramEncrypted = urlencode(base64_encode($refCode));
$inviteURL = "http://app.fastship.co/register!" . $paramEncrypted;

if($current_sale < 30000 && $customer_data['group'] == "Standard"){
    $sale_suggest = "*ใช้งานเพิ่มอีก  " . number_format(30000 - $current_sale,0) . " บาท เพื่อรับส่วนลด 3% ทุกการส่ง";
}else if($current_sale < 50000 && $customer_data['group'] == "Bronze"){
    $sale_suggest ="ใช้งานเพิ่มอีก  " . number_format(50000 - $current_sale,0) . " บาท เพื่อรับส่วนลด 5% ทุกการส่ง";
}else if($current_sale < 10000 && $customer_data['group'] == "Silver"){
    $sale_suggest = "*ใช้งานเพิ่มอีก  " . number_format(10000 - $current_sale,0) . " บาท เพื่อรับส่วนลด 10% ทุกการส่ง";
}else{
    $sale_suggest = "";
}
?>
<div class="conter-wrapper">
	<div class="col-md-10 col-md-offset-1">
		
		<div class="row">
			<div class="col-md-12"><h2>ภาพรวมบัญชีของคุณ</h2></div>
			<div class="col-md-4">
			
				<div class="panel panel-primary">
					<div class="panel-heading">ข้อมูลผู้ส่ง</div>
			        <div class="panel-body">
    					<h4>ชื่อ-สกุล</h4>
    					<div>{{ $customer_data['firstname'] . " " .$customer_data['lastname'] }}</div>
    					<br />
    					
    					<h4>ระดับ</h4>
    					<div>{{ $customer_data['group'] }}</div>
    					<br />
    					
    					<h4>รหัสแนะนำโดย</h4>
    					<div>{{ $customer_data['refcode'] }}</div>
					</div>
				</div>
				
				<div class="panel panel-primary" style="display:none;">
					<div class="panel-heading">แต้มสะสม</div>
			        <div class="panel-body">
    					<h1>0 คะแนน</h1>
					</div>
				</div>
				
				<div class="panel panel-primary">
					<div class="panel-heading">ยอดจัดส่ง</div>
			        <div class="panel-body">
			        	<div><strong>เดือนนี้</strong></div>
    					<h1>{{ number_format($current_sale,0) }} บาท</h1>
    					<div class="text-left danger small" style="color:green;">{{ $sale_suggest }}</div>
    					<hr />
    					
    					<div><strong>เดือนที่แล้ว</strong></div>
    					<h1>{{ number_format($previous_sale,0) }} บาท</h1>
    					<hr />
    					
    					<div><strong>2 เดือนที่แล้ว</strong></div>
    					<h1>{{ number_format($twomonthago_sale,0) }} บาท</h1>
    					
					</div>
				</div>

				
			</div>
			
			<div class="col-md-8">
				
				<div class="panel panel-primary">
					<div class="panel-heading">รายการออเดอร์ล่าสุด</div>
			        <div class="panel-body">
			        	<table class="table table-hover table-striped">
                            <thead>
                            	<tr>
                            		<td>ใบรับพัสดุ</td>
                            		<td>วันที่</td>
                            		<td class="hidden-xs">สถานะ</td>
                            		<td>ยอดชำระ</td>
                            		<td class="hidden-xs">จำนวนพัสดุ</td>
                            	</tr>
                            </thead>
                            <tbody>
                            <?php 
                            if(is_array($pickup_list) && sizeof($pickup_list) > 0):
                            foreach($pickup_list as $pickup):
                            	//if($pickup['Status'] == "Cancelled") continue;
                            ?>
                            	<tr>
                            		<td><a href="/pickup_detail/<?php echo $pickup['ID']; ?>"><?php echo $pickup['ID']; ?></a></td>
                            		<td><?php echo date("d/m/Y",strtotime($pickup['CreateDate']['date'])); ?></td>
                            		<td class="hidden-xs"><?php echo $pickup['Status']; ?></td>
                            		<td><?php echo number_format($pickup['Amount']); ?></td>
                            		<td class="hidden-xs"><?php echo $pickup['TotalShipment']; ?></td>
                            	</tr>
                            <?php 
                            endforeach;
                            else:
                            ?>
                            <tr><td colspan="6" class="text-center">ไม่พบใบรับพัสดุ</td></tr>
                            <?php
                            endif;
                            ?>
                            </tbody>
                            </table>
                            <div class="text-right"><a href="{{ url('pickup_list') }}">ดูออเดอร์ทั้งหมด</a></div>
					</div>
				</div>
				
				<div class="panel panel-primary">
        			<div class="panel-heading">รหัสแนะนำเพื่อน</div>
        		    <div class="panel-body">
        	            <div class="row" style="margin-bottom:10px;">
        	            	<h3>Referal Code: {{ $refCode }}</h3>
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
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
@endsection