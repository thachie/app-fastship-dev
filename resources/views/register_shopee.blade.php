@extends('layout')
@section('content')
<?php 

if(isset($ref) && $ref != ""){
	$referCode = base64_decode($ref);
}else{
	$referCode = "FSSP399";
}

?>
<div class="conter-wrapper">     
<div class="row row-eq-height">      

	<div class="col-md-6-eq text-center" style="margin-right:1%;">
		<img src="images/shopee_promotion_399.jpg" style="max-width: 100%;border-radius:5px;margin-bottom:20px; "/><br />
		
		<div style="font-size:16px;font-weight:600;">ลูกค้า FastShip รับสิทธิพิเศษจาก Shopee <a href="https://shopee.co.th/m/partnership" target="_blank" style="color:#f74">คลิ๊กเลย</a> </div>
	</div>

    <div class="col-md-6-eq" style="margin-left:1%;">
        <form name="register_form" class="form-horizontal" method="post" action="{{url ('/customer/register')}}">	                        
	        {{ csrf_field() }} 
	        <div class="panel panel-primary" style="box-shadow: 0 0;">
	            <div class="panel-heading">สมัครสมาชิก <span class="ribbon-right">ฟรี!! ไม่มีค่าใช้จ่าย</span></div>
	            <div class="panel-body" style="padding:10px 20px;">
					
	                    <div class="col-md-6">
	                    	<label for="firstname" class="col-12 control-label">ขื่อจริง</label>
	                    	<input type="text" class="form-control required" name="firstname" id="firstname" placeholder="Firstname" required>
	                    </div>
	                    
	                    <div class="col-md-6">
	                   		<label for="lastname" class="col-12 control-label">นามสกุล</label>
	                    	<input type="text" class="form-control required" name="lastname" id="lastname" placeholder="Lastname" required>
	                    </div>
	                    
	                    <div class="col-md-6">
	                    	<label for="email" class="col-12 control-label">อีเมล์ที่ใช้งาน</label>
	                    	<input type="text" class="form-control required" name="email" id="email" placeholder="Email" required>
	                    </div>
	                                
	                    <div class="col-md-6">
	                        <label for="telephone" class="col-12 control-label">เบอร์ติดต่อ</label>
	                        <input type="text" class="form-control required" name="telephone" id="telephone" placeholder="Phonenumber" required>
	                    </div>  
	                            
						<div class="col-md-6">
							<label for="password" class="col-12 control-label">รหัสผ่าน</label>
							<input type="password" class="form-control required" name="password" id="password" placeholder="Password" required>
						</div>
						<div class="col-md-6">
							<label for="c_password" class="col-12 control-label">ยืนยันรหัสผ่าน</label>
							<input type="password" class="form-control required" name="c_password" id="c_password" placeholder="Confirm Password" required>
						</div>
						<div class="clearfix"></div>
                            
						<div class="row text-center" >
							<h4 for="referal_code" class="col-md-12 col-xs-12" style="margin-top: 10px;">ใส่รหัสส่วนลด เพื่อรับสิทธิประโยชน์</h4>
							<div class="col-md-6 col-md-offset-3 col-xs-12">
								<input type="text" class="form-control" name="referal_code" id="referal_code" value="<?php echo $referCode; ?>" placeholder="FSDXXXXX" required onkeyup="checkCode(this.value)" />
							</div><br />
							<div class="text-center" id="error" style="color:red;"></div>
						</div>
						
						<div class="text-center "><button type="submit" name="submit" class="col-md-6 col-md-offset-3 btn btn-lg btn-primary">สมัครสมาชิก</button></div> 
						<div class="clearfix"></div><br />
						
						<h4>เงื่อนไขการใช้สิทธิประโยชน์</h4>     
                		<ul style="padding-left: 0;">
                			<li>- ส่วนลด 399 บาท </li>
                			<li>- จำกัด 1 สิทธิ์ / หมายเลข / แคมเปญ</li>
                			<li>- ระยะเวลาการสมัคร วันที่ 9 กันยายน พ.ศ. 2561</li>
                			<li>- ระยะเวลาการใช้ส่วนลด ภายใน 30 วันนับจากวันที่สมัคร</li>
                			<li>- จำกัด 99 สิทธิ์ท่านแรกเท่านั้น</li>
                			<li>- ไม่มีขั้นต่ำในการสั่งซื้อ</li>
                			<li>- สงวนสิทธิ์สำหรับการสมัครสมาชิก และใช้งาน Fastship ครั้งแรกเท่านั้น</li>
                			<li>- โปรโมชั่นไม่สามารถแลกเปลี่ยนเป็นเงินสด เครดิต หรืออื่นๆได้</li>
                			<li>- ไม่สามารถใช้ร่วมกับส่วนลดหรือโปรโมชั่นอื่นได้</li>
                			<li>- ติดต่อสอบถามเพิ่มเติมได้ที่ Fastship Call Center <a href="tel:02-080-3999">02-080-3999</a> หรือ <a href="https://line.me/R/ti/p/%40fastship.co">@fastship.co</a></li>
                		</ul>
	            </div>
	        </div>
	    </form>
		
		
    </div>
    
    
    
</div>
<div class="clearfix"></div><br />

<div class="text-center small">ร่วมสนับสนุนโดย <a href="https://shopee.co.th" target="_blank">shopee.co.th</a></div>


</div>
<script type="text/javascript">
function checkCode(code){
	var code1 = $("#referal_code").val();
    if(code1 == "FSSP299"){
    	$("#error").html("โค้ดนี้ไม่สามารถใช้ได้");
    }else{
    	$("#error").html("");
    }
}
</script>
@endsection