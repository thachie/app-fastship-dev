@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">      
		<div class="col-md-6 col-md-offset-3">
		    <form name="login_form" class="form-horizontal" method="post" action="{{url ('/customer/login')}}">
				{{ csrf_field() }}
				
				@if (session('return'))
                	<input type="hidden" name="return" value="{{ session('return') }}" />
                @endif
				<div class="panel panel-primary login">
		            <div class="panel-heading">{!! FT::translate('login.panel.heading') !!}</div>
		            <div class="panel-body">
		                <div class="row">
		                    <label for="username" class="col-md-3 control-label">{!! FT::translate('login.form.email') !!}</label>
		                    <div class="col-md-8">
		                    	<input type="text" id="username" class="form-control required" name="username" required />
		                    </div>
		                </div>
		                <div class="row">
		                    <label for="password" class="col-md-3 control-label">{!! FT::translate('login.form.password') !!}</label>
		                    <div class="col-md-8">
		            			<input type="password" id="password" class="form-control required" name="password" required>
		                    </div>
						</div>
						
			            <div class="row text-center">	
							<button type="submit" name="submit" class="col-md-6 col-md-offset-3 btn btn-lg btn-primary">{!! FT::translate('button.login') !!}</button><br />
							<div class="col-md-12 small" style="margin-top: 10px;"><a href="forget_password">{!! FT::translate('login.forgotpassword') !!}</a></div>
						</div>
						<hr />
						<div class="text-center"><a href="/joinus">{!! FT::translate('login.registerlink') !!}</a></div>
		            </div>
		        </div>
			</form>
	    </div>
	    <br />
	    <div class="clearfix"></div>	    
	</div>
</div>
@endsection