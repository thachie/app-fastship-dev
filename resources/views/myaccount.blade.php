@extends('layout')
@section('content')
<?php //alert($customer_data); ?>
<?php 
if($customer_data['latitude'] == ""){
	$addressDiv = "col-md-12";
}else{
	$addressDiv = "col-md-5";
}
?>
<div class="conter-wrapper">
    <div class="row">
    
    	@include('left_account_menu')

        <div class="col-md-10">
        	
        	<h2>{!! FT::translate('myaccount.heading') !!}</h2>
    		<hr />
    	
    	    <div class="panel panel-primary">
    			<div class="panel-heading">{!! FT::translate('myaccount.panel.heading1') !!}</div>
    	        <div class="panel-body">
    
                    <div class="<?php echo $addressDiv; ?> col-xs-12 no-padding" style="line-height: 30px;">
                    
    	                <?php if(isset($customer_data['company']) && $customer_data['company']): ?>
    	                    
    	                    <div class="col-md-12"><h3><?php echo $customer_data['company']; ?></h3></div>
    	                    <div class="clearfix"></div>
    	                    
    	                    <?php if(isset($customer_data['taxid']) && $customer_data['taxid']): ?>
    		                    <div class="col-md-12"><h4>{!! FT::translate('label.taxid') !!}: <?php echo $customer_data['taxid']; ?></h4></div>
    	                    	<div class="clearfix"></div>
                        	<?php endif; ?>
    	                <?php endif; ?>
    	                
    					<div class="col-md-12">
    					<?php if($customer_data['taxid'] == ""): ?>
    						<h4><?php echo $customer_data['firstname']." ".$customer_data['lastname']; ?></h4>
    					<?php else: ?>
    						<?php echo $customer_data['firstname']." ".$customer_data['lastname']; ?>
    					<?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="col-md-12">
                        	<?php echo $customer_data['address1']; ?>
                        	<?php if(isset($customer_data['address2']) && $customer_data['address2']): ?>
                        		<?php echo " " . $customer_data['address2']; ?>
                        	<?php endif; ?>
                        	<?php echo " " . $customer_data['city'] . " " . $customer_data['state']; ?>
                        	<?php echo " " . $customer_data['postcode'] . " " . (($customer_data['country'])?$countries[$customer_data['country']]:""); ?>
                        </div>
                        <div class="clearfix"></div>
    
    	                <div class="col-md-12">{!! FT::translate('label.email') !!}: <?php echo $customer_data['email']; ?></div>
    	                <div class="clearfix"></div>
    
                        <div class="col-md-12">{!! FT::translate('label.telephone') !!}: <?php echo $customer_data['phonenumber']; ?></div>
    	                <div class="clearfix"></div>
    
    	                
                    </div>
                    <?php if($customer_data['latitude'] != ""): ?>
                    <div class="col-md-7 col-xs-12 no-padding">
                    	<div class="col-md-12 col-xs-12" style="height: 240px;">
    		                <div id="map" height="240px" width="480px"></div>
    		               	<div id="message"></div>
    		             </div>
                    </div>
                    <?php endif; ?>

                    <div class="clearfix"></div>
    	            <br />
    	            
    	            <div class="col-md-12 text-center small"><a href="{{url ('/edit_customer')}}"><i class="fa fa-edit" title="แก้ไข"></i> {!! FT::translate('button.edit') !!}</a></div>
                    <div class="clearfix"></div>
    	            <br />

                </div>
    		</div>
    
    		@if(session('customer.id') == 5223 || session('customer.id') == 43037 || (session('customer.id') >= 48828 && session('customer.id') % 2 == 1))
        	<div class="panel panel-primary">
    			<div class="panel-heading">เอกสาร - สำเนาบัตรประชาชน</div>
    		    <div class="panel-body">
    
    				<div class="col-md-8 col-md-offset-2 col-xs-12">
    				
    					<h3 class="text-center"><span class="text-dark">สถานะการตรวจสอบ:</span> 
    					@if($approval['ApprovedStatus'] == "Pending")
    					<span class="text-info">กำลังตรวจสอบ</span>
    					@elseif($approval['ApprovedStatus'] == "Rejected")
    					<span class="text-danger">ไม่ผ่าน</span>
    					@elseif($approval['ApprovedStatus'] == "Approved")
    					<span class="text-success">อนุมัติแล้ว</span>
    					@else
    					<span class="text-secondary">ต้องการเอกสารเพิ่มเติม</span>
    					@endif
    					</h3>

						@if($approval['ApprovedStatus'] != "Pending" && $approval['ApprovedStatus'] != "Approved" )
    					<form id="upload_form" class="form-horizontal" method="post" action="{{url ('customer/upload')}}" enctype="multipart/form-data">
		
                    		{{ csrf_field() }}

            				<label class="col-md-4 control-label" style="padding-top: 2px;">อัพโหลดเอกสาร</label>	
            				<div class="col-md-8">
            					<input type="file" class="choose-file" name="document" required />
            					<button type="submit" class="btn btn-info" style="vertical-align: top;">Upload</button>
            				</div>
            				<div class="clearfix"></div>
            				<p class="small text-center text-secondary">jpeg,png,jpg,gif,svg,pdf / 2 MB</p>

                        </form>
                        
                        <div class="well">
                        <ul>
                        	<li>เอกสารจะต้องเป็นเอกสารที่ออกโดยราชการ และต้องแสดงข้อมูลต่อไปนี้<br />
                            - ชื่อ <br />
                            - วัน เดือน ปีเกิด<br />
                            - ที่อยู่</li>
                            <li>ชื่อบนบัตรจะต้องตรงกับ ชื่อเจ้าของบัญชีที่สมัครกับ Fastship </li>
                            <li>เอกสารต้องถูกต้องสมบูรณ์ เอกสารที่หมดอายุจะไม่สามารถใช้ในขั้นตอนการยืนยันนี้ได้</li>
                        </ul>
                        <br />
                        <p>*ระบบยืนยันตัวตนดังกล่าว เป็นไปตามมาตรป้องกันและปราบปรามยาเสพติด และของผิดกฎหมายอื่นๆ <br />
                        	ซึ่งมีสาระสำคัญกำหนดให้ผู้ประกอบกิจการขนส่งสินค้าหรือพัสดุภัณฑ์ เป็นสถานประกอบการที่อยู่ภายใต้บังคับ<br />
                        	ของมาตรการป้องกันและปราบปรามการกระทำความผิดเกี่ยวกับยาเสพติดในสถานประกอบการ และต้องให้ความร่วมมือดำเนินการ
                        	โดยผู้ใช้บริการจะต้องแสดงหลักฐานประจำตัว อาทิ บัตรประจำตัวประชาชนของผู้ฝากส่ง</p>
                        </div>
                        @endif
                        
    				</div>
    				<div class="clearfix"></div>

    		    </div>
    		</div>
        </div>
    	<div class="clearfix"></div>
    	<br />
    	@endif
    	
    </div>
</div>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARGo6QU60StUz58XsOHjLs4Dg3UFllE4w&callback=initMap">
</script>
<script type="text/javascript">
	var map;
    var marker;
    var infowindow;

    function initMap() {
        
    	@if($customer_data['latitude'] == "")
    	return false;
    	@else
    	
        infowindow = new google.maps.InfoWindow({
          content: document.getElementById('message')
        });

        
        var save_pos = {lat: <?php echo $customer_data['latitude']; ?>, lng: <?php echo $customer_data['longitude']; ?>};
        map = new google.maps.Map(document.getElementById('map'), {
          center: save_pos,
          draggable: false,
          disableDefaultUI: false,
          clickableIcons: false,
          fullscreenControl: false,
          keyboardShortcuts: false,
          mapTypeControl: false,
          scaleControl: false,
          scrollwheel: false,
          streetViewControl: false,
          zoomControl: false,
          zoom: 15
        });
        marker = new google.maps.Marker({
            position: save_pos,
            map: map
        });
        @endif
      }

</script>
@endsection