@extends('layout')
@section('content')

<div class="conter-wrapper">

	
	<div class="row">      
		<div class="col-md-6 col-md-offset-3">
		
			<div class="col-12 alert alert-success" style="top: 30px;">
				สมัครสมาชิกสำเร็จแล้ว
			</div>
					
			@if (session('marketplace'))
			@if (session('marketplace') == "SOOK")
			<p class="text-center">ระบบจะพาคุณเข้าสู่หน้าการใช้งานใน 5 วินาที หรือกด <a href="{{ url('/import_sook') }}">เริ่มใช้งาน</a></p>
			@endif
			@else
		    <p class="text-center">ระบบจะพาคุณเข้าสู่หน้าการใช้งานใน 5 วินาที หรือกด <a href="{{ url('/') }}">เริ่มใช้งาน</a></p>
		    @endif
		    
	    </div>
	</div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
	// Handler for .ready() called.
	var delay = 5000; 
	@if (session('marketplace'))
	@if (session('marketplace') == "SOOK")
		setTimeout(function(){ window.location = "{{ url('/import_sook') }}"; }, delay);
	@endif
	@else
	setTimeout(function(){ window.location = "{{ url('/') }}"; }, delay);
	@endif
});
//Your delay in milliseconds

fbq('track', 'CompleteRegistration', {
   value: 200,
   currency: 'THB',
});

</script>
@endsection