@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<h4 class=" text-center">คุณยังไม่ได้เชื่อมต่อ LINE กับ Fastship</h4>
	
	<div class="text-center">
		<div id="profile_img"></div>
		<div id="profile_name"></div>
	</div>
	<div class="clearfix"></div>

<!-- 
	<div class="row form-group">
		<div class="col col-12 text-center">
			<a href="{{ url('/liff/signup?return='.$return) }}" style="text-decoration: none;"><button type="button" class="btn btn-block btn-primary btn-lg large">ลงทะเบียนใหม่</button></a>
		</div>
    </div>
 -->  
    <div class="row form-group">
		<div class="col col-12 text-center">
			<a href="{{ url('/liff/login?return='.$return) }}" style="text-decoration: none;"><button type="button" class="btn btn-block btn-primary btn-lg large">เข้าระบบเพื่อเชื่อมต่อ</button></a>
		</div>
    </div>

</div>
<div class="clearfix"></div>
@endsection