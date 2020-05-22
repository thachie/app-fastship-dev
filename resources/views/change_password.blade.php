@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
		
		@include('left_account_menu')

	    <div class="col-md-10">
	    
	    	<h2>{!! FT::translate('change_password.heading') !!}</h2>
        	<hr />
        
	    	<form name="password_form" class="form-horizontal" method="post" action="{{url ('/customer/change_password')}}">
	    		
	    		{{ csrf_field() }}
	    		
			    <div class="panel panel-primary">
					<div class="panel-heading">{!! FT::translate('change_password.panel.heading1') !!}</div>
			        <div class="panel-body">
		               	<div class="row">
                            <label for="currentpassword" class="col-md-4 control-label">{!! FT::translate('label.current_password') !!}</label>
                            <div class="col-md-6">
                            	<input class="form-control" type="password" id="currentpassword" name="currentpassword" required />
							</div>
                
						</div>
						<div class="row">
                            <label for="newpassword" class="col-md-4 control-label">{!! FT::translate('label.new_password') !!}</label>
                            <div class="col-md-6">
                            	<input class="form-control" type="password" id="newcurrentpassword" name="newcurrentpassword" required />
                            </div>
							<i class="fa fa-question-circle hidden-xs" style="margin-top: 9px;" title="{!! FT::translate('info.password_format') !!}"></i>
						</div>
						<div class="row">
                            <label for="repassword" class="col-md-4 control-label">{!! FT::translate('label.confirm_password') !!}</label>
                            <div class="col-md-6">
                            	<input class="form-control" type="password" id="repassword" name="repassword" required />
                            </div>
						</div>
						<div class="text-center"><button type="submit" name="submit" class="btn btn-primary">{!! FT::translate('button.change_password') !!}</button></div>
				
					</div>
					</div>
		           
		            
			</form>
	    </div>
	</div>
</div>
@endsection