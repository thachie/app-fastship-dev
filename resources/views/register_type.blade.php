@extends('layout')
@section('content')
<?php 

if(isset($ref) && $ref != ""){
	$referCode = base64_decode($ref);
}else{
	$referCode = "";
}

?>

	<?php if($type == "marketplace"){  $referal_code = $type; ?>
	<!-- <div class="conter-wrapper"> -->
		<div class="row" id="regis" style="padding: 15px; padding-bottom: 0;">    
			<div class="col-md-6 join" >
				<div class="panel panel-primary">
					<div class="panel-heading" style="padding: 0px;">
						<img src="../images/joinus/pain-logis.jpg" style="max-width: 100%; border-radius: 4px 5px 0 0;" />
					</div>
					<div class="panel-body" style="background: #ffffff; border-radius: 5px; text-align: center;">
						<!-- <h2 class="orange" style="margin-bottom: 0; text-align: center;">Amazon Ebay Etsy<span class="darkgray" style="font-size: 20px;"> ขายของผ่านช่องทางไหนดี ?</span></h2> -->
						<img src="../images/logo-1.png" style="max-width: 180px; margin-top: 10px;" />
						<h4 class="darkgray" style="margin-top: 10px; font-size: 20px;">บริการส่งสินค้าระหว่างประเทศ สำหรับธุรกิจ E-Commerce</h4>
						<img src="../images/joinus/logo_marketplace-big.png" style="max-width: 68%;" />
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<form name="register_form"  class="form-horizontal" method="post" action="{{url ('/customer/register')}}">	                        
					{{ csrf_field() }} 
					<div class="panel panel-primary">
						<div class="panel-heading">สมัครสมาชิก <span class="ribbon-right">ฟรี!! ไม่มีค่าใช้จ่าย</span></div>
						<div class="panel-body">
							
							<div class="col-md-6">
								<label for="firstname" class="col-12 control-label">ขื่อจริง</label>
								<input type="text" class="form-control required" name="firstname" id="firstname" required>
							</div>
							
							<div class="col-md-6">
								<label for="lastname" class="col-12 control-label">นามสกุล</label>
								<input type="text" class="form-control required" name="lastname" id="lastname" required>
							</div>
							
							<div class="col-md-6">
								<label for="email" class="col-12 control-label">อีเมล์ที่ใช้งาน</label>
								<input type="text" class="form-control required" name="email" id="email" required>
							</div>
										
							<div class="col-md-6">
								<label for="telephone" class="col-12 control-label">เบอร์ติดต่อ</label>
								<input type="text" class="form-control required" name="telephone" id="telephone" required>
							</div>  
									
							<div class="col-md-6">
								<label for="password" class="col-12 control-label">รหัสผ่าน</label>
								<input type="password" class="form-control required" name="password" id="password" required>
							</div>
							<div class="col-md-6" style="margin-bottom: 6%;">
								<label for="c_password" class="col-12 control-label">ยืนยันรหัสผ่าน</label>
								<input type="password" class="form-control required" name="c_password" id="c_password" required>
							</div>
								
							<!-- <div class="row">
								<div class="col-md-12">กรอกรหัสผู้แนะนำ (referal code) หรือรหัสคูปอง (coupon code) เพื่อรับสิทธิประโยชน์ (ถ้ามี)</div>
								<div class="col-md-6">
									<input type="text" class="form-control" name="referal_code" id="referal_code" value="marketplace" />
								</div>
							</div> -->
	
							<div class="text-center "><button type="submit" style=""name="submit" class="col-md-6 col-md-offset-3 btn btn-lg btn-primary">สมัครสมาชิก</button></div> 
							
							<div class="clearfix"></div><br />
							<div class="col-md-12 text-center">เป็นสมาชิกอยู่แล้ว <a href="/">เข้าสู่ระบบ</a></div>
							<div class="col-md-12 text-center" style="margin: 8% 0 2% 0;">
								<a href="tel:+6620803999" class="btn btn-lg btn-default" style="margin: 2% 0"><span class="glyphicon glyphicon-earphone"></span> 02-080-3999</a>
								<a href="https://line.me/R/ti/p/%40fastship.co" class="btn btn-lg btn-default" target="_blank"><span class="glyphicon glyphicon-comment"></span> @fastship.co</a>
							</div>
						</div>
					</div>
				</form>
			</div>	
		</div>
	
		<!-- <div class="col-md-12 market" style="text-align: center; padding: 0 30px;">
			<div class="col-md-4 bleft">
				<img src="../images/joinus/amazon.png" style="max-height: 40px;" /> 
				<p style="margin-top: 16px; line-height: 24px;">Amazon เป็นเว็บไซต์ขายของอันดับ 1 ของโลก เป็นตลาดที่มีผู้ซื้อกว่าสิบร้อยล้านคน หากคุณเป็นเจ้าของแบรนด์ที่คิดจะเพิ่มยอดขาย การขายของในอเมซอนก็เป็นช่องทางไม่ควรมองข้าม เพราะเป็น e-commerce ที่คนมีกำลังซื้อสูง และเป็นที่นิยมโดยเฉพาะตลาดอเมริกา amazon ถือเป็นขุมทรัพย์ของการขายออนไลน์และโดยเฉพาะเจ้าของแบรนด์ที่อยากพาแบรนด์ไประดับโลก การขายของผ่านอเมซอนจึงเป็นช่องทางที่น่าสนใจมากๆ</p>
			</div>
			<div class="col-md-4 bcenter">
				<img src="../images/joinus/ebay.png" style="max-height: 40px;" /> 
				<p style="margin-top: 16px; line-height: 24px;">หากใครมองหาช่องทางขยายแบรนด์ไปยังตลาดระดับโลก ebay คือทางเลือกที่น่าสนใจมากๆาำหรับคุณ เพราะเป็น e-commerce อันดับสองลองลงมาจากอเมซอน ซึ่งมีมูลค่าตลาดสูงและผู้ซื้อมีอำนาจในการซื้อสูงไม่แพ้ Amazon หากต้องการเพิ่มช่องทางและเร่งสปีดยอดขาย อีเบย์คือหนึ่งในตลาดเป้าหมายหลักๆ ของเจ้าของแบรนด์ ใครที่อยากขาย เพิ่มยอดขาย ขยายตลาด อีเบย์เป็น e-commerce ที่ ไม่ควรพลาดอย่างยิ่ง</p>
			</div>
			<div class="col-md-4 bright">
				<img src="../images/joinus/etsy.png" style="max-height: 40px;" />
				<p style="margin-top: 16px; line-height: 24px;">Etsy ถือเป็นตลาดที่ช่วยให้นักช็อปค้นหาสินค้าเฉพาะกลุ่มที่ไม่สามารถหาจากที่ไหนได้ ถ้าคุณคือผู้ขาย คุณสามารถทำการตลาดสินค้าของคุณได้อย่างเจาะจงเฉพาะกลุ่ม โดยตลาดนี้จะเน้นสินค้าประเภทงานฝีมือ ซึ่งคนไทยมีจุดเด่นในด้านนี้อยู่แล้ว จึงไม่ใช่เรื่องยากที่จะทำการบุกตลาดนี้ สำหรับเจ้าของแบรนด์สินค้าเฉพาะกลุ่มหรือมีเอกลักษณ์โดดเด่น Etsy คือตลาดที่เหมาะกับคุณ ในการส่งออกสินค้าไปทั้งตลาดระดับโลก</p>
			</div>
		</div>
		<div class="clearfix"></div><br /> -->
		<div class="row" style="background: url('../images/joinus/bg_map.jpg'); padding: 50px 0;">
			<div class="col-md-10 col-md-offset-1" style="padding: 30px 0;">
				<div class="col-md-6" style="background-color: white; padding: 30px;">
					<h2 class="orange">FastShip <span class="darkgray" style="font-size: 24px;">ช่วยคุณได้อย่างไร</span></h2>
					<p style="line-height: 24px;">เราจะช่วยเป็นตัวกลางให้คุณเข้าถึงบริการส่งสินค้าไปต่างประเทศกับบริษัท Logistic ชั้นนำได้ง่ายขึ้น เเละยิ่งถ้าคุณคือผู้ประกอบการออนไลน์ สินค้าของคุณจะถึงมือลูกค้าปลายทางได้ทันถ่วงที ด้วยระบบเชื่อมต่อกับ Marketplace ชั้นนำจากเรา</p>
					<div class="row" style="margin: 0;">
						<div class="col-md-2 col-xs-3 no-padding" style="margin: 5px 0!important;"><img style="vertical-align: middle;" src="http://13.250.102.169/wp-content/uploads/2018/02/icon-01.png"></div>
						<div class="col-md-4 col-xs-9 no-padding" style="margin: 5px 0!important;"><b style="line-height: 50px; font-size: 15px;">ค่าส่งที่ราคาถูก</b></div>
						<div class="clearfix hidden-lg"></div>
						<div class="col-md-2 col-xs-3 no-padding" style="margin: 5px 0!important;"><img style="vertical-align: middle;" src="http://13.250.102.169/wp-content/uploads/2018/02/icon-02.png"></div>
						<div class="col-md-4 col-xs-9 no-padding" style="margin: 5px 0!important;"><b style="line-height: 50px; font-size: 15px;">เชื่อมต่อ Marketplaces</b></div>
						<div class="clearfix hidden-lg"></div>
					</div>
					<div class="row" style="margin: 0;">
						<div class="col-md-2 col-xs-3 no-padding" style="margin: 5px 0!important;"><img style="vertical-align: middle;" src="http://13.250.102.169/wp-content/uploads/2018/02/icon-03.png"></div>
						<div class="col-md-4 col-xs-9 no-padding" style="margin: 5px 0!important;"><b style="line-height: 50px; font-size: 15px;">บริการรับพัสดุถึงบ้าน</b></div>
						<div class="clearfix hidden-lg"></div>
						<div class="col-md-2 col-xs-3 no-padding" style="margin: 5px 0!important;"><img style="vertical-align: middle;" src="http://13.250.102.169/wp-content/uploads/2018/02/icon-04.png"></div>
						<div class="col-md-4 col-xs-9 no-padding" style="margin: 5px 0!important;"><b style="line-height: 50px; font-size: 15px;">มีหลายบริการให้เลือก</b></div>
						<div class="clearfix hidden-lg"></div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="youtube" id="mpU0k3JhZsA" src="../images/joinus/cover.jpg" style="width:560px; height:300px;"></div>
					<script type="text/javascript" src="https://codegena.com/assets/js/youtube-embed.js"></script>
				</div>
			</div>
		</div>
		<div class="clearfix"></div><br />
		<div class="text-center" style="padding: 0 35px;">
			<h2 class="orange">บริการส่งออกด่วนสำหรับ E-Commerce</h2>
			<p>FastShip คือ Platform ที่เชื่อมต่อผู้ประกอบการเเละบุคคลทั่วไปกับบริการขนส่งด่วนระหว่างประเทศ บริการของเราประกอบไปด้วย</p>
		</div>
		<div class="clearfix"></div><br />
		<div class="col-md-10 col-md-offset-1 market" style="text-align: center; padding: 0 30px;">
			<div class="col-md-3 bleft">
				<img src="../images/joinus/services_icon01.png" style="margin-bottom: 16px;" />
				<h4 class="orange">เชื่อมต่อ Logistic</h4> 
				<p style="margin-top: 16px; line-height: 24px;">เรารวบรวมบริษัท Logistic ชั้นนำ ได้แก่ DHL, UPS, ARAMEX และ FedEx ไว้ในที่เดียว ให้คุณได้เลือกว่าจะใช้บริการเจ้าไหนด้วยราคาที่ถูกกว่า</p>
			</div>
			<div class="col-md-3 bcenter">
				<img src="../images/joinus/services_icon02.png" style="margin-bottom: 16px;" /> 
				<h4 class="orange">เชื่อมต่อ Marketplace</h4> 
				<p style="margin-top: 16px; line-height: 24px;">เรามีระบบเชื่อมต่อ Marketplace ชั้นนำอย่าง eBay, Amazon, Etsy เพื่อให้ผู้ขายออนไลน์สะดวกมากยิ่งขึ้น</p>
			</div>
			<div class="col-md-3 bcenter">
				<img src="../images/joinus/services_icon03.png" style="margin-bottom: 16px;" />
				<h4 class="orange">บริการรับของถึงบ้าน</h4> 
				<p style="margin-top: 16px; line-height: 24px;">เมื่อกดยืนยันรับพัสดุหน้าเว็บของเรา FastShip จะไปรับถึงที่ภายในวันเดียว</p>
			</div>
			<div class="col-md-3 bright">
				<img src="../images/joinus/services_icon04.png" style="margin-bottom: 16px;" />
				<h4 class="orange">พิมพ์ AWB ได้ทันที</h4> 
				<p style="margin-top: 16px; line-height: 24px;">ทันทีที่คุณยืนยันให้เราไปรับสินค้า คุณก็สามารถพิมพ์ Air Way Bill ได้พร้อมส่งออกนอก</p>
			</div>
		</div>
		<div class="col-md-10 col-md-offset-1 market gap" style="text-align: center; padding: 0 30px;">
			<div class="col-md-3 bleft">
				<img src="../images/joinus/services_icon05.png" style="margin-bottom: 16px;" />
				<h4 class="orange">เช็คสถานะได้ Realtime</h4> 
				<p style="margin-top: 16px; line-height: 24px;">เรารวบรวมบริษัท Logistic ชั้นนำ ได้แก่ DHL, UPS, ARAMEX และ FedEx ไว้ในที่เดียว ให้คุณได้เลือกว่าจะใช้บริการเจ้าไหนด้วยราคาที่ถูกกว่า</p>
			</div>
			<div class="col-md-3 bcenter">
				<img src="../images/joinus/services_icon06.png" style="margin-bottom: 16px;" /> 
				<h4 class="orange">ที่ปรึกษาพร้อมให้บริการ</h4> 
				<p style="margin-top: 16px; line-height: 24px;">เรามีระบบเชื่อมต่อ Marketplace ชั้นนำอย่าง eBay, Amazon, Etsy เพื่อให้ผู้ขายออนไลน์สะดวกมากยิ่งขึ้น</p>
			</div>
			<div class="col-md-3 bcenter">
				<img src="../images/joinus/services_icon07.png" style="margin-bottom: 16px;" />
				<h4 class="orange">มีประกันพัสดุ</h4> 
				<p style="margin-top: 16px; line-height: 24px;">เมื่อกดยืนยันรับพัสดุหน้าเว็บของเรา FastShip จะไปรับถึงที่ภายในวันเดียว</p>
			</div>
			<div class="col-md-3 bright">
				<img src="../images/joinus/services_icon08.png" style="margin-bottom: 16px;" />
				<h4 class="orange">API & Widget</h4> 
				<p style="margin-top: 16px; line-height: 24px;">ทันทีที่คุณยืนยันให้เราไปรับสินค้า คุณก็สามารถพิมพ์ Air Way Bill ได้พร้อมส่งออกนอก</p>
			</div>
		</div>
		<div class="clearfix"></div><br />
		<div class="text-center">
			<a href="#regis"><img src="../images/joinus/register-btn.png" style="margin-bottom: 30px;" /></a>
		</div> 
		<div class="clearfix"></div><br />
	<!-- </div> -->
	<?php } ?>
@endsection