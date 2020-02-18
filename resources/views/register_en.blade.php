@extends('layout')
@section('content')
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
        <form name="register_form"  class="form-horizontal" method="post" action="{{url ('/customer/register')}}">	                        
	        {{ csrf_field() }} 
	        <div class="panel panel-primary">
	            <div class="panel-heading">Register <span class="ribbon-right">FREE!!</span></div>
	            <div class="panel-body">
					
	                    <div class="col-md-6">
	                    	<label for="firstname" class="col-12 control-label">Firstname</label>
	                    	<input type="text" class="form-control required" name="firstname" id="firstname" required>
	                    </div>
	                    
	                    <div class="col-md-6">
	                   		<label for="lastname" class="col-12 control-label">Lastname</label>
	                    	<input type="text" class="form-control required" name="lastname" id="lastname" required>
	                    </div>
	                    
	                    <div class="col-md-6">
	                    	<label for="email" class="col-12 control-label">Email</label>
	                    	<input type="text" class="form-control required" name="email" id="email" required>
	                    </div>
	                                
	                    <div class="col-md-6">
	                        <label for="telephone" class="col-12 control-label">Telephone</label>
	                        <input type="text" class="form-control required" name="telephone" id="telephone" required>
	                    </div>  
	                            
						<div class="col-md-6">
							<label for="password" class="col-12 control-label">Password</label>
							<input type="password" class="form-control required" name="password" id="password" required>
						</div>
						<div class="col-md-6">
							<label for="c_password" class="col-12 control-label">Confirm Password</label>
							<input type="password" class="form-control required" name="c_password" id="c_password" required>
						</div>
                            
						<div class="row">
							<div class="col-md-12" style="margin-top: 10px;">Referal code (If any)</div>
							<div class="col-md-6">
								<input type="text" class="form-control" name="referal_code" id="referal_code" value="<?php echo $referCode; ?>" />
							</div>
						</div>
						
						<div class="text-center "><button type="submit" name="submit" class="col-md-6 col-md-offset-3 btn btn-lg btn-primary">Register</button></div> 
						
	            </div>
	        </div>
	    </form>
		<div class="col-md-12 text-center">Already regitered <a href="/">login</a></div>
	    <div class="clearfix"></div><br />
	    
    </div>
    <div class="clearfix"></div><br />
    
</div>
   
</div>
@endsection