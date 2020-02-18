@extends('partner/layouts/layout_partner_front')
@section('content')
<style type="text/css">
	.error {
		color:red;
		font-family:verdana, Helvetica;
	}
</style>
<div class="conter-wrapper">
	<div class="row">      
		<div class="col-md-6 col-md-offset-3">
		    <form name="login_form" id="login" class="form-horizontal" method="post" action="{{url ('partner/check-login')}}">
				{{ csrf_field() }}
				
				@if (session('return'))
                	<input type="hidden" name="return" value="{{ session('return') }}" />
                @endif
				<div class="panel panel-primary login">
		            <div class="panel-heading">เข้าสู่ระบบ</div>
		            <div class="panel-body">
		                <div class="row">
		                    <label for="username" class="col-md-3 control-label">อีเมล์ที่ใช้งาน</label>
		                    <div class="col-md-8">
		                    	<input type="text" id="username" class="form-control required" name="username" required />
		                    </div>
		                </div>
		                <div class="row">
		                    <label for="password" class="col-md-3 control-label">รหัสผ่าน</label>
		                    <div class="col-md-8">
		            			<input type="password" id="password" class="form-control required" name="password" required>
		                    </div>
						</div>
						<div class="row">
							<!--<label class="radio-inline">
								<input type="radio" name="optradio" checked>Option 1
						    </label>
						    <label class="radio-inline">
						    	<input type="radio" name="optradio">Option 2
						    </label>-->
						    <label for="display_page" class="col-md-3 control-label"></label>
		                    <div class="col-md-8">
							    <div class="radio">
									<label><input type="radio" name="display_page" value="front" checked>Front end</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="display_page" value="back">Back end</label>
								</div>
							</div>

						</div>
						
			            <div class="row text-center">	
							<button type="submit" name="submit" id="btn_login_form" class="col-md-6 col-md-offset-3 btn btn-lg btn-primary">เข้าสู่ระบบ</button><br />
							<div class="col-md-12 small" style="margin-top: 10px;"><a href="forget_password">ลืมรหัสผ่าน ?</a></div>
						</div>
						<hr />
						<div class="text-center"><a href="{{url('partner/register')}}">สมัครสมาชิก</a></div>
		            </div>
		        </div>
			</form>
	    </div>
	    <br />
	    <div class="clearfix"></div><br />
<!-- 	    <div class="text-center"><a href="https://app1.fastship.co">คลิ๊กที่นี่</a>  เพื่อใช้ Fastship เวอร์ชั่นเก่า</div> -->
	    
	</div>
</div>

<script type="text/javascript">
	/*$(document).ready(function(){
		$("#btn_login_form").click(function(){
			var email = $("#username").val();
			var password = $("#password").val();
			if( email =='' || password ==''){
				$('input[type="text"],input[type="password"]').css("border","2px solid red");
				$('input[type="text"],input[type="password"]').css("box-shadow","0 0 3px red");
				alert("Please fill fields.");
			}else {
			}
		});
	});*/
</script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

<script type="text/javascript">
    $(document).ready(function() { 
        $('#login').validate({ // initialize the plugin
            rules: {
                //"username": { required: true, email: true },
                "username": {
			      	required: true,
			      	email: true
			    },
                "password": "required",
                "display_page": "required",
            },
            messages: {
                "username": "Please enter username or enter a valid email.",
                "password": "Please enter password.",
                "display_page": "Please select page.",
            }
            
        });
    });
</script>

@endsection