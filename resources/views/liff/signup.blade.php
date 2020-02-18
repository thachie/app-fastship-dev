@extends('liff/layout')
@section('content')
<div class="conter-wrapper">
        
	<form id="signup_form" name="signup_form" method="post" action="{{ url('liff/action/signup') }}">

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
				<label for="firstname" class=" form-control-label">ชื่อจริง</label>
			</div>
            <div class="col col-12">
            	<input type="text" id="firstname" name="firstname" class="form-control required" placeholder="firstname" required />
            </div>  
            
            <div class="col col-12">
				<label for="lastname" class=" form-control-label">นามสกุล</label>
			</div>
            <div class="col col-12">
            	<input type="text" id="lastname" name="lastname" class="form-control required" placeholder="lastname" required/>
            </div>
            
            <div class="col col-12">
				<label for="email" class=" form-control-label">อีเมล์</label>
			</div>
            <div class="col col-12">
            	<input type="text" id="email" name="email" class="form-control required" placeholder="email" required />
            </div>

            <div class="col col-12">
				<label for="telephone" class=" form-control-label">เบอร์ติดต่อ</label>
			</div>
            <div class="col col-12">
            	<input type="tel" id="telephone" name="telephone" class="form-control required" placeholder="phone number" required/>
            </div> 

        </div>

        <div class="row form-group">
			<div class="col col-12 text-center">
				<button type="submit" class="btn btn-block btn-primary btn-lg large">ลงทะเบียน</button>
				<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;" onclick="history.back();">ย้อนกลับ</button>
			</div>
        </div>

	</form>

</div>
<div class="clearfix"></div>
@endsection