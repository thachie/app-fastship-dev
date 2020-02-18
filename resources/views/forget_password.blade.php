@extends('layout')
@section('content')
<div class="conter-wrapper">
	<!-- <div class="row">
		<div class="col-md-6 col-md-offset-3">
			<h2>เปลี่ยนรหัสผ่าน</h2>
		</div>
	</div> -->
	<div class="row">
	    <div class="col-md-6 col-md-offset-3">
	    	<form name="password_form" class="form-horizontal" method="post" action="{{url ('/customer/forget_password')}}">
	    		
	    		{{ csrf_field() }}
	    		
			    <div class="panel panel-primary">
					<div class="panel-heading">จำรหัสผ่านไม่ได้ :(</div>
			        <div class="panel-body">
			        	
			        	<p class="text-center">ระบบจะทำการส่งรหัสผ่านใหม่ไปยังอีเมล์ที่ท่านระบุ</p>
			        	
			        	<div class="row">
                            <label for="currentpassword" class="col-md-4 control-label">อีเมล์ที่ใช้สมัคร Fastship.co</label>
                            <div class="col-md-6">
                            	<input class="form-control" type="email" name="email" required />
							</div>
                
						</div>
						
						<div class="text-center"><button type="submit" name="submit" class="btn btn-primary">ส่งรหัสผ่านชั่วคราวไปที่อีเมล์ที่ระบุ</button></div>
						
						<hr />
						<div class="col-md-12 text-center">จำรหัสได้แล้ว ? <a href="/">เข้าสู่ระบบ</a></div>
	   					<div class="clearfix"></div><br />
	   					
					</div>
				</div>
   
			</form>
	    </div>
	</div>
</div>
@endsection