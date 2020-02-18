@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

   	<div class="col col-12">
    	<h3 class="text-orange">โอนผ่าน QR จำนวน {{ $amount }} บาท</h3>
    </div>
    <hr />

	<div class="row">

		<div class="col col-12 text-center text-secondary">
        	<p>กรุณาแสกน QR ด้วย Application ของธนาคารที่คุณใช้งาน</p>
        </div>
        
        <div class="col col-12 text-center">
        	<img src="https://support.thinkific.com/hc/article_attachments/360042081334/5d37325ea1ff6.png" style="max-width: 100%;"/>
        </div>

		<div class="col col-12 text-center">
			<button type="button" class="btn bg-light btn-block btn-sm border-0" onclick="history.back();">ย้อนกลับ</button>
		</div>
		
    </div>       
	
</div>
<script type="text/javascript">
<!--
$(window).on('load',function(){

});
-->
</script>
@endsection