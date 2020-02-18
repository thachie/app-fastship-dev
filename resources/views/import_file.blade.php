@extends('layout')
@section('content')

<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-6 col-md-offset-3"><h2></h2></div>
	</div>
	<div class="row">
    <form id="payment_form" class="form-horizontal" method="post" action="{{url ('/import_file')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
	    <div class="col-md-6 col-md-offset-3">
	        @if (session('status'))
			    <div class="alert alert-warning">
			        {{ session('status') }}
			    </div>
			@endif   
		</div> 
        <div class="col-md-6 col-md-offset-3">
        	<div class="panel panel-primary">
				<div class="panel-heading">Import File</div>
				<div class="panel-body">
				<label class="col-md-4 control-label">File:</label>	
				<div class="col-md-8">
					<input type="file" class="form-control" name="upload" />
					<span class="help"></span>
				</div>
				<div class="clearfix"></div>
				<br />

				<div class="text-center btn-create"><button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Upload</button></div>

				</div>
        	</div>
        </div>
        
    </form>
</div>


<script type="text/javascript">
	$(document).ready( function() {
	    
	    
	});

	
	function selectCreditCard(){
		$("#bank_transfer").hide();
		$("#credit_card").fadeIn(1000,function(){
			$('html, body').animate({
				scrollTop: $(this).offset().top
	        }, 500);
		});
	}

	
</script>

@endsection