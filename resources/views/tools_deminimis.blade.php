@extends('layout')
@section('content')
<?php // alert($de_minimis_value); ?>
<div class="conter-wrapper">
      
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h2>คำนวณการเสียภาษี </h2>
	</div>
</div>  
<div class="row">
	<form class="form-horizontal" method="post" action="{{url ('deminimis/create')}}">
        {{ csrf_field() }}
        
		<div class="col-md-6 col-md-offset-3">
		    <div class="panel panel-primary">
				<div class="panel-heading">คำนวณมูลค่าสินค้าสูงสุด ที่ส่งโดยไม่ต้องเสียภาษี </div>
		        <div class="panel-body">
	                <div class="row">
						<div class="hidden-xs col-md-5 col-sm-5 text-right"><strong>เลือกประเทศ</strong><br />destination country</div>
		                <div class="visible-xs col-xs-12"><label><strong>เลือกประเทศ</strong> (destination country)</label></div>
		                <div class="col-xs-12 col-md-6 col-sm-5">
		                	<select class="form-control" id="country" name="country"  onchange="calculateTax()">
                                <option value="">- กรุณาเลือกประเทศปลายทาง -</option>
                                <?php
                                    foreach($country as $code=>$name){
                                        echo "<option value='".$code."'>".$name."</option>";
                                    }
                                ?>
                           </select>
		                </div>	                                
	                </div>
	                
	                @if(!empty($de_minimis_value))
	                <div class="row" id="demin">
				    	<div class="col-md-5 col-sm-5 col-xs-12">ประเทศ </div>
				        <div class="col-md-7 col-sm-5 col-xs-12 ">{{ $select_country }}</div>
				        <div class="clearfix"></div>
				        <div class="col-md-5 col-sm-5 col-xs-12">มูลค่าสูงสุดที่ไม่ต้องเสียภาษี </div>
				        <div class="col-md-7 col-sm-5 col-xs-12 ">{{ $de_minimis_value }} {{ $de_minimis_currency }}</div>
				        <div class="clearfix"></div>
				        <br />
				        
				        <!-- Exchange Rates Script - EXCHANGERATEWIDGET.COM -->
				        <?php /* ?>
				        <div class="col-md-6 col-md-offset-3">
							<div style="width:238px;border:1px solid #F15A22;text-align:left;"><div style="text-align:left;background-color:#F15A22;width:100%;border-bottom:0px;height:24px; font-size:12px;font-weight:bold;padding:5px 0px;"><span style="width:100%; height:25px; padding-left:5px;color:#fff;">{{ $de_minimis_currency }} Exchange Rates</span></div><script type="text/javascript" src="//www.exchangeratewidget.com/converter.php?l=en&f={{ $de_minimis_currency }}&t=THB,&a={{ $de_minimis_value }}&d=F0F0F0&n=FFFFFF&o=000000&v=5"></script></div>
						</div>
						<?php */ ?>
						<!-- End of Exchange Rates Script -->

			        </div>
			        @endif
			        
	            </div>
			</div>
	    </div>
</div>

</div>

<script type="text/javascript">
function calculateTax(){
	window.location.href = "/deminimis/" + $("#country").val();
}
$(document).ready(function(){
	$("#country").val('<?php echo $select_country_code; ?>');
});
</script>
@endsection