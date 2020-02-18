@extends('layout')
@section('content')
<?php 
$errors = json_decode($exception->getMessage());
?>
<style>
<!--
body{ background: #f15a22; }
-->
</style>
<div class="conter-wrapper" style="background: #f15a22;min-height: 99.9%;">
	<!--<div class="row">      
		<div class="col-md-12">
			<div class="panel panel-primary login">
				<div class="panel-heading">Blank Page</div>
		        <div class="panel-body">
		        	<div class="text-center btn-create">
		        		404 not found
		        	</div>
		        	<div class="text-center btn-create">
		        		<button type="button" name="back" class="btn btn-lg btn-primary minus-margin" onclick="window.history.back()" >กลับ</button>
		        	</div>
		        </div>
			</div>
		</div>
	</div>-->

	<div class="col-md-6 col-md-offset-3">
		<div class="row">
	        	<div class="panel panel-primary">
	            	 <div class="panel-body">
	                 	<?php if($exception->getMessage() == ""): ?>
	                 	<div class="text-center"><img src="{{ url('images/404.png') }}" /></div><br />
						<div class="text-center"><h2>ไม่พบหน้าที่ต้องการ</h2></div>
						<?php else: ?>
						<div class="text-center"><img src="{{ url('images/fastship_error.png') }}" /></div><br />
						<div class="text-center">
    						<h2>ขออภัย เกิดปัญหาบางประการ</h2>
    						<h3>กรุณาลองใหม่อีกครั้ง</h3>
    						<code><?php echo (isset( $errors->data )) ? $errors->data : ""; ?></code>
    						<div style="display: none;">{{ $exception->getTraceAsString() }}</div>
						</div>
						<!-- <div class="text-center"><h3>{{ $exception->getMessage() }}</h3></div>  -->
						<?php endif; ?>
						<div class="clearfix"></div>
						<br />
						
						<div class="text-center btn-create">
							<button type="submit" id="submit" name="back" value="submit" class="btn btn-primary" onclick="window.history.back()">ไปหน้าที่แล้ว</button>
							<a href="{{ url('/') }}"><button type="button" id="submit" name="home" value="submit" class="btn btn-info">ไปหน้าแรก</button></a>
						</div>
  
	                 </div>
	        	</div>
	        </div>
	  </div>
</div>
@endsection