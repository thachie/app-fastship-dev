@extends('liff/layout')
@section('content')
<div class="conter-wrapper">
        
	<form id="signup_form" name="signup_form" method="post" action="{{ url('liff/action/login') }}">

		<input type="hidden" name="line_user_id" class="line_user_id" />
		<input type="hidden" name="line_id" id="line_id" />
		<input type="hidden" name="return" value="{{ $return }}" />

		<div class="text-center">
    		<div id="profile_img"></div>
    		<div id="profile_name"></div>
		</div>
		<div class="clearfix"></div>

		<div class="row form-group">
			
			<div class="col col-12 text-center">
				<p>กรอกข้อมูลเพื่อเชื่อมต่อระบบ Fastship และ LINE </p>
			</div>
		
			<div class="col col-12">
				<label for="firstname" class=" form-control-label">email</label>
			</div>
            <div class="col col-12">
            	<input type="text" id="email" name="email" class="form-control required" placeholder="email" required />
            </div>  
            
            <div class="col col-12">
				<label for="lastname" class=" form-control-label">password</label>
			</div>
            <div class="col col-12">
            	<input type="password" id="password" name="password" class="form-control required" placeholder="password" required/>
            </div>

        </div>

        <div class="row form-group">
			<div class="col col-12 text-center">
				<button type="submit" class="btn btn-block btn-primary btn-lg large">เข้าสู่ระบบ</button>
				<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;" onclick="history.back();">ย้อนกลับ</button>
			</div>
        </div>

	</form>

</div>
<div class="clearfix"></div>
@endsection