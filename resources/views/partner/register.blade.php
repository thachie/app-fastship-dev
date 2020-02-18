@extends('partner/layouts/layout_partner_front')
@section('content')

<style type="text/css">
    .margin50
    {
        margin-top:50px
    }
    .dimentions-width, .dimentions-height
    {
        width:90%;
        display:initial;
    }
    span.arrow {
        margin-left: 5px;
        height:17px;
      }
      label.error {
        color: red;
        height:17px;
        margin-left:9px;
        padding:1px 5px 0px 5px;
        font-size:small;
    }
    /*.error {
		color:red;
		font-family:verdana, Helvetica;
	}*/
</style>

<?php 

if(isset($ref) && $ref != ""){
	$referCode = base64_decode($ref);
}else{
	$referCode = "";
}
?>
<div class="conter-wrapper">     
	<div class="row">      
	    <div class="col-md-6 col-md-offset-3">
	        <form name="register_form" id="login"  class="form-horizontal" method="post" action="{{url ('partner/register-partner')}}">	                        
		        {{ csrf_field() }} 
		        <div class="panel panel-primary">
		            <div class="panel-heading">สมัครสมาชิก <span class="ribbon-right"></span></div>
		            <div class="panel-body">
						
		                    <div class="col-md-6">
		                    	<label for="firstname" class="col-12 control-label">ขื่อจริง/Firstname</label>
		                    	<input type="text" class="form-control required" name="firstname" id="firstname" required>
		                    </div>
		                    
		                    <div class="col-md-6">
		                   		<label for="lastname" class="col-12 control-label">นามสกุล/Lastname</label>
		                    	<input type="text" class="form-control required" name="lastname" id="lastname" required>
		                    </div>
		                    
		                    <div class="col-md-6">
		                    	<label for="email" class="col-12 control-label">อีเมล์ที่ใช้งาน/Email</label>
		                    	<input type="text" class="form-control required" name="email" id="email" required>
		                    </div>
		                                
		                    <div class="col-md-6">
		                        <label for="telephone" class="col-12 control-label">เบอร์ติดต่อ/Telephone</label>
		                        <input type="text" class="form-control required" name="telephone" id="telephone" onkeypress="return isNumberKey(event)" maxlength="11" required>
		                    </div>  
		                            
							<div class="col-md-6">
								<label for="password" class="col-12 control-label">รหัสผ่าน/Password</label>
								<input type="password" class="form-control required" name="password" id="password" required>
							</div>
							<div class="col-md-6">
								<label for="c_password" class="col-12 control-label">ยืนยันรหัสผ่าน/Confirm Password</label>
								<input type="password" class="form-control required" name="c_password" id="c_password" required>
							</div>

							<div class="col-md-12">
								<label for="addressLine1" class="col-12 control-label">ที่อยู่/Address</label>
								<input type="text" class="form-control required" name="addressLine1" id="addressLine2" required>
							</div>

							<div class="col-md-12">
								<label for="addressLine2" class="col-12 control-label">ที่อยู่(ต่อ)/Address(Cont.)</label>
								<input type="text" class="form-control" name="addressLine2" id="addressLine2">
							</div>

							<div class="col-md-6">
		                        <label for="city" class="col-12 control-label">เมือง/City</label>
		                        <input type="text" class="form-control required" name="city" id="city" required>
		                    </div> 

		                    <div class="col-md-6">
		                        <label for="state" class="col-12 control-label">จังหวัด/State</label>
		                        <input type="text" class="form-control required" name="state" id="state" required>
		                    </div> 

		                    <div class="col-md-6">
		                        <label for="postcode" class="col-12 control-label">รหัสไปรษณีย์/Postcode</label>
		                        <input type="text" class="form-control required" name="postcode" id="postcode" onkeypress="return isNumberKey(event)" maxlength="5" required>
		                    </div> 
	                            
							<div class="row">
								<div class="col-md-12" style="margin-top: 10px;">กรอกรหัสผู้แนะนำ (referal code) หรือรหัสคูปอง (coupon code) เพื่อรับสิทธิประโยชน์ (ถ้ามี)</div>
								<div class="col-md-6">
									<input type="text" class="form-control" name="referal_code" id="referal_code" value="<?php echo $referCode; ?>" />
								</div>
							</div>
							
							<div class="text-center "><button type="submit" name="submit" class="col-md-6 col-md-offset-3 btn btn-lg btn-primary">สมัครสมาชิก</button></div> 
							
		            </div>
		        </div>
		    </form>
			<div class="col-md-12 text-center">เป็นสมาชิกอยู่แล้ว <a href="{{url('partner/login')}}">เข้าสู่ระบบ</a></div>
		    <div class="clearfix"></div><br />
		    
	    </div>
	    <div class="clearfix"></div><br />
	    
	</div>
</div>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

<script type="text/javascript">
    $(document).ready(function() { 
        $('#login').validate({ // initialize the plugin
            rules: {
                "firstname": "required",
                "lastname": "required",
                "email": {
			      	required: true,
			      	email: true
			    },
			    "telephone": "required",
                "password": "required",
                "c_password": "required",
                "addressLine1": "required",
                "city": "required",
                "state": "required",
                "postcode": "required",
                "referal_code": "required",
            },
            messages: {
            	"firstname": "Please enter firstname.",
            	"lastname": "Please enter lastname.",
            	"email": "Please enter email or enter a valid email.",
            	"telephone": "Please enter telephone.",
                "password": "Please enter password.",
                "c_password": "Please enter confirm password.",
                "addressLine1": "Please enter address.",
                "city": "Please enter city.",
                "state": "Please enter state.",
                "postcode": "Please enter postcode.",
                "referal_code": "Please enter referal code.",
            }
            
        });
    });

    function isNumberKey(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
      return true;
    }
</script>
@endsection