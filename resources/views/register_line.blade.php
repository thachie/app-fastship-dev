@extends('layout')
@section('content')
<?php 
if(isset($ref) && $ref != ""){
	$referCode = base64_decode($ref);
}elseif(isset($code) && $code != ""){
    $referCode = $code;
}else{
	$referCode = "";
}
?>
<style>
    #body-container{display: inline;}
    #footer-wrapper{display: none;}
    #body-container .panel-primary{ box-shadow: 0 2px 10px rgba(241, 90, 34,0.3); }
    #body-container .panel-primary .panel-body{ padding: 40px 20px; }
    #body-container .check_mark{ margin-bottom: 10px; }

@media only screen and (max-width: 480px) {
    #body-container .check_mark{ max-width: 18px; }
    #body-container h1{ font-size:28px;text-align: center; }
    #body-container h2{ font-size:26px; }
    #body-container h3{ font-size:18px; }
}   
</style>
<div class="conter-wrapper">     
<div class="row" style="margin-bottom: 0;">     
    <div class="col-md-12 ">

	        <div class="row">
	        	<div class="col-md-4" style="padding: 40px;background-color: #eee;">
	        	    <div class="text-center" style="padding: 0 0 25px;"><img src="../images/joinus/fastship_register.png" style="max-width: 200px;"/></div>
            	
	            	<h2 class="text-center orange">{!! FT::translate('register.content.heading') !!}</h2>
                    <div class="" style="padding: 10px 0 0 0;">
                        <h3><img class="check_mark" src="../images/joinus/check-mark.png" /> {!! FT::translate('register.content.list1') !!}</h3>
                        <h3><img class="check_mark" src="../images/joinus/check-mark.png" /> {!! FT::translate('register.content.list2') !!}</h3>
                        <h3><img class="check_mark" src="../images/joinus/check-mark.png" /> เชื่อมต่อ <span class="orange">Marketplace</span> ง่ายๆ</h3>
                        <h3><img class="check_mark" src="../images/joinus/check-mark.png" /> {!! FT::translate('register.content.list3') !!}</h3>             
                    </div>
            		
	        	</div>
            	<div class="col-md-5" style="background-color: #fff;overflow: hidden;color:#fff;padding: 40px;">
            	<form name="register_form" class="form-horizontal regis" method="post" action="{{url ('/customer/register')}}">
                    
        	        {{ csrf_field() }}
        	        
        	        <input type="hidden" name="line_id" value="{{ $lineId }}" />

            		<h1>เริ่มต้นใช้งานกับเรา เปิดบัญชีฟรี!</h1>
            		<p class="gray">กรุณากรอกข้อมูลให้ครบถ้วน</p>
            		
            		<div class="col-md-6">
            			<input type="text" class="form-control required" placeholder="{!! FT::translate('placeholder.firstname') !!}" name="firstname" id="firstname" required value="{{ old('firstname',$default['firstname']) }}" />
            		</div>
            		<div class="col-md-6">
            			<input type="text" class="form-control required" placeholder="{!! FT::translate('placeholder.lastname') !!}" name="lastname" id="lastname" required value="{{ old('lastname',$default['lastname']) }}" />
            		</div>
            		
            		<div class="col-md-6">
                        <!-- <label for="email" class="col-12 control-label">อีเมล์ที่ใช้งาน/Email</label> -->
                        <input type="text" class="form-control required" placeholder="{!! FT::translate('placeholder.email') !!}" name="email" id="email" required value="{{ old('email',$default['email']) }}" />
                    </div>
                                
                    <div class="col-md-6">
                        <!-- <label for="telephone" class="col-12 control-label">เบอร์ติดต่อ/Telephone</label> -->
                        <input type="number" class="form-control required" placeholder="{!! FT::translate('placeholder.telephone') !!}" name="telephone" id="telephone"  max="9999999999" maxlength="10" required value="{{ old('telephone',$default['telephone']) }}" />
                    </div>  
                    
                    <div class="col-md-12">
                    	<select name="state" class="form-control required" required>
                    		<option value="">----- {!! FT::translate('dropdown.default.province') !!} -----</option>
                    		@foreach($provinces as $province)
                    		@if($province->name_th == old('state'))
                        		@if(session('lang') != null && session('lang') == "en")
                        		<option value="{{ $province->name_th }}" selected>{{ $province->name_en }}</option>
                        		@else
                        		<option value="{{ $province->name_th }}" selected>{{ $province->name_th }}</option>
                        		@endif
                    		@else
                    			@if(session('lang') != null && session('lang') == "en")
                        		<option value="{{ $province->name_th }}">{{ $province->name_en }}</option>
                        		@else
                        		<option value="{{ $province->name_th }}">{{ $province->name_th }}</option>
                        		@endif
                    		@endif
                    		@endforeach
                    	</select>
                    </div>

                    <div class="col-md-6">
                    @if(old('referral',$referCode) == "SOOK")
                    	<select name="for" class="form-control required" required>
                    		<option value="thaitrade">ขายใน Thaitrade</option>
                    	</select>
                    @else
                        <select name="for" class="form-control required" required>
                    		<option value="">--- {!! FT::translate('dropdown.default.for') !!} ---</option>
                    		<option value="ebay">{!! FT::translate('option.for.ebay') !!}</option>
                    		<option value="amazon">{!! FT::translate('option.for.amazon') !!}</option>
                    		<option value="etsy">{!! FT::translate('option.for.etsy') !!}</option>
                    		<option value="thaitrade">{!! FT::translate('option.for.thaitrade') !!}</option>
                    		<option value="sample">{!! FT::translate('option.for.sample') !!}</option>
                    		<option value="personal">{!! FT::translate('option.for.personal') !!}</option>
                    		<option value="other">{!! FT::translate('option.for.other') !!}</option>
                    	</select>
                    @endif
                    </div>
                    
                    <div class="col-md-6">
                        <select name="behavior" class="form-control required" required>
                    		<option value="">--- {!! FT::translate('dropdown.default.behavior') !!} ---</option>
                    		<option value="1_10">{!! FT::translate('option.behavior.low') !!}</option>
                    		<option value="11_50">{!! FT::translate('option.behavior.medium') !!}</option>
                    		<option value="50plus">{!! FT::translate('option.behavior.high') !!}</option>
                    		<option value="0">{!! FT::translate('option.behavior.onetime') !!}</option>
                    	</select>
                    </div>
                    
                    @if(!isset($lineId) || $lineId == "")
                    <div class="col-md-6">
                        <input type="hidden" class="form-control required" placeholder="{!! FT::translate('placeholder.password') !!}" name="password" id="password" required value="" />
                    </div>
                    <div class="col-md-6">
                       <input type="hidden" class="form-control required" placeholder="{!! FT::translate('placeholder.confirm_password') !!}" name="c_password" id="c_password" required value="" />
                    </div>
                    @else
                    	<input type="hidden" name="password" value="{{ $default['password'] }}" />
                    	<input type="hidden" name="c_password" value="{{ $default['password'] }}" />
                    @endif
                    
                    @if(!isset($code) || $code == "")
                    <div class="col-md-12">
                    	<div class="small" style="font-weight: 600;">ใส่รหัสผู้แนะนำเพื่อรับส่วนลด</div>
                    	<input type="text" class="form-control" placeholder="{!! FT::translate('placeholder.referral') !!}" name="referral" id="referral" value="{{ old('referral',$referCode) }}"  />
                    </div>
                    @else
                    	<input type="hidden" name="referral" id="referral" value="{{ old('referral',$referCode) }}" />
                    @endif
                    
                    @if(isset($marketplaceRefId) && $marketplaceRefId != "")
                    	<input type="hidden" name="marketplace_ref_id" id="marketplace_ref_id" value="{{ old('marketplace_ref_id',$marketplaceRefId) }}" />
                    	<input type="hidden" name="marketplace_type" id="marketplace_type" value="{{ old('marketplace_type',$code) }}" />
                    @endif
                    
                    <div class="clearfix"></div><br />
                    
					<div class="col-md-5">
                    	<div class="text-center "><button type="submit" name="submit" class="btn btn-lg btn-block btn-primary">{!! FT::translate('button.register') !!}</button></div> 
					</div>
					<div class="col-md-7">
					    <div>{!! FT::translate('register.form.term') !!}</div>
					    <div class="small" style="cursor: pointer;"><a data-toggle="modal" data-target="#ModalTerm" style="color: #f15a22;">{!! FT::translate('register.form.term_button') !!}</a></div>
					</div>
					
				</form>
            	</div>
            	<div class="col-md-3" style="background-color: #e5e5e5;overflow: hidden;padding: 40px;height: 76.5%;">
            	<form name="login_form" class="form-horizontal regis" method="post" action="{{url ('/customer/login')}}">
                    
        	        {{ csrf_field() }}
        	        
        	        <input type="hidden" name="line_id" value="{{ $lineId }}" />
        	        
            		<h3>เป็นสมาชิกอยู่แล้ว </h3>
            		<p class="gray">เข้าสู่ระบบ</p>
            		
            		<input type="text" class="form-control required" placeholder="{!! FT::translate('placeholder.email') !!}" name="username" id="email" required value="{{ old('email',$default['email']) }}" />

                    <input type="password" class="form-control required" placeholder="{!! FT::translate('placeholder.password') !!}" name="password" id="password" required value="" />

					<div class="text-center "><button type="submit" name="submit" class="btn btn-sm btn-block btn-info">{!! FT::translate('button.login') !!}</button></div> 
            	
            	</form>
            	</div>

	    </div>
	    
	    <div class="clearfix"></div>

    </div>
    <div class="clearfix"></div>
</div>
</div>
<div class="modal fade" id="ModalTerm" tabindex="-1" role="dialog" aria-labelledby="ModalTerm_Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-left" id="ModalTerm_Label">ข้อตกลงในการให้บริการและเงื่อนไขในการขนส่ง</h4>
            </div>
            <div class="modal-body">
                <div style="overflow-y: scroll;height: 345px;padding-bottom: 20px;font-size: 12px; line-height: 24px;">
                    <p style="text-indent: 40px;">ฟาสต์ชิป (Fastship) ให้บริการจัดส่งเอกสารหรือพัสดุไปต่างประเทศ ผ่านบริษัทขนส่งต่างๆ เช่น  UPS, Aramex, SF Express, FedEx หรือบริษัทขนส่งอื่นๆ เมื่อท่านเลือกใช้บริการของบริษัทดังกล่าวในฐานะ “บริษัทผู้นำส่ง” ผู้จัดส่งจะต้องตกลงยินยอมในนามของผู้จัดส่งหรือในนามของผู้ใดก็ตามที่เกี่ยวข้องกับการจัดส่ง ว่าข้อตกลงและเงื่อนไขต่อไปนี้จะมีผลบังคับใช้เมื่อ ฟาสต์ชิปหรือบริษัทขนส่ง ที่เลือกได้ตอบรับทำการจัดส่ง ยกเว้นจะมีการตกลงทางลายลักษณ์อักษรกับทางเจ้าหน้าที่ที่ได้รับมอบหมายจาก ฟาสต์ชิป</p>
                    <p style="text-indent: 40px;">การจัดส่งสินค้า หมายถึง เอกสารและพัสดุภัณฑ์ทั้งหมด ซึ่งส่งไปในใบกำกับการจัดส่งโดยทาง ฟาสต์ชิป สามารถเลือกส่งด้วยวิธีใดก็ได้ ไม่ว่าทางอากาศ ทางถนน หรือด้วยพาหนะขนส่งชนิดอื่นๆ โดย ฟาสต์ชิป ทำหน้าที่เป็นนายหน้าการขนส่ง ระหว่างผู้จัดส่งกับบริษัทขนส่งใดๆ หรือเรียกต่อไปว่า“บริษัทผู้นำส่ง” ที่ผู้จัดส่งกำหนด หรือตามที่ ฟาสต์ชิป เห็นสมควร การขนส่งสิ่งของทุกชนิดจะดำเนินการภายใต้ความรับผิดชอบเบื้องต้น ซึ่งจำกัดไว้ตามที่ได้ระบุ ณ ที่นี้ โดยจะมีค่าชดเชยในมูลค่าที่จำกัดตามแต่กรณี หากผู้จัดส่งต้องการความคุ้มครองที่เพิ่มขึ้นในการจัดส่ง สามารถติดต่อชำระเงินเพิ่มเติมสำหรับการทำประกันได้ (กรุณาอ่านข้อมูลเพิ่มเติมทางด้านล่าง)</p>
                    <p style="text-indent: 40px;">
                    1. พิธีศุลกากร การนำเข้าและส่งออก
                        ฟาสต์ชิป ได้มอบหมายให้บริษัทผู้นำส่งได้ดำเนินการ ดังต่อไปนี้เพื่อให้บริการผู้จัดส่งในนามของผู้จัดส่ง (1) กรอกเอกสาร, ปรับปรุงแก้ไขรหัสสินค้าหรือบริการ จ่ายค่าภาษีอากรตามข้อบังคับใช้ทางกฎหมาย (2) เป็นเสมือนตัวแทนของผู้ส่งสินค้าในการดำเนินพิธีการต่างๆในทางศุลกากร และการควบคุมการส่งออก และในฐานะของผู้รับของ ในกรณีที่มีจุดประสงค์เพื่อกำหนดโบรกเกอร์ทางด้านภาษีเพื่อให้กระทำพิธี และรายการทางศุลกากร และ (3) เชื่อมโยงการขนส่งไปยังนายหน้านำเข้าของผู้รับ หรือตามที่อยู่อื่นๆ ตามความต้องการของบุคคลใดๆก็ตามที่ทาง บริษัทผู้นำส่งเห็นสมควร
                    </p>
                    <p style="text-indent: 40px;">
                    2. การขนส่งสินค้าที่ไม่สามารถนำส่งได้
                        ผู้จัดส่งยอมรับว่าการจัดส่งของผู้จัดส่งนั้นเป็นที่ยอมรับในการดำเนินการขนส่งได้ และการจัดส่งจะไม่เป็นที่ยอมรับสำหรับการให้บริการ หากพบว่า:
                        – สิ่งของจัดส่งจัดอยู่ในกลุ่ม วัตถุที่มีอันตราย วัตถุอันตราย สิ่งของต้องห้ามตามข้อบังคับของสมาคมขนส่งทางอากาศระหว่างประเทศ ไอเอทีเอ (องค์การขนส่งทางอากาศสากล) ไอซีเอโอ (องค์การการบินพลเรือนสากล) เอดีอาร์ (ข้อบังคับทางการขนส่งสินค้าอันตรายบนเส้นทางยุโรป) หรือตามกฎของหน่วยงานรัฐบาลใดๆ หรือ องค์กรอื่นๆที่เกี่ยวข้อง
                        – ไม่ได้ทำการสำแดงรายละเอียดต่อศุลกากรเมื่อเข้าเกณฑ์ที่จะต้องกระทำ ตามกฎระเบียบของกรมศุลกากรที่บังคับใช้
                        – การจัดส่งนั้นประกอบไปด้วยสินค้าปลอมแปลง สัตว์ ทองคำแท่ง เงินตรา ป้ายตราภาษีอากร เอกสารนิติกรรมเฉพาะผู้ถือที่ตกลงกัน โลหะหรือหินอันมีค่า อาวุธปืนทั้งจริงและเลียนแบบ และส่วนประกอบต่างๆของอาวุธยุทธภัณฑ์ วัตถุระเบิด และเครื่องกระสุนปืน ชิ้นส่วนร่างกายมนุษย์ วัตถุลามกอนาจาร หรือ สิ่งเสพติด/ยาที่ผิดกฎหมาย
                        -การจัดส่งนั้นประกอบด้วยวัตถุอื่นใดที่ ฟาสต์ชิป ตัดสินว่าไม่สามารถทำการจัดส่งได้อย่างปลอดภัยหรือถูกต้องตามกฎหมาย
                        – หีบห่อบรรจุภัณฑ์ชำรุด หรือไม่ได้มาตรฐาน
                    </p>
                    <p style="text-indent: 40px;">
                    3. การนำส่งสินค้าถึงที่หมายและสินค้าที่ไม่สามารถนำส่งได้
                        สินค้าไม่สามารถจัดส่งไปถึงตู้ไปรษณีย์ หรือรหัสไปรษณีย์ได้ การจัดส่งสินค้าจะส่งถึงตามที่อยู่ของผู้รับที่ได้รับการระบุโดยผู้ส่งสินค้าเท่านั้น (ซึ่งในกรณีที่เป็นบริการส่งทางไปรษณีย์ สามารถถือว่าเป็นการจัดส่งไปยังบริการไปรษณีย์แห่งแรกที่ได้รับก็ได้เช่นกัน) แต่ไม่จำเป็นต้องระบุชื่อผู้รับเป็นการส่วนตัวก็ได้ หรือหากไม่ผ่านการตรวจของศุลกากร หรือ ชื่อ ที่อยู่ ผู้รับไม่สามารถระบุได้ชัดแจ้ง ผู้รับปฏิเสธการรับของหรือการชำระเงินเพื่อการรับสินค้า ฟาสต์ชิป หรือ และบริษัทผู้นำส่งจะใช้ความพยายามที่สมเหตุสมผลในการคืนสินค้ากับผู้ส่งสินค้าตามค่าใช้จ่ายของผู้ส่งสินค้า ซึ่งหากไม่สามารถดำเนินการดังกล่าวได้ บริษัทผู้นำส่ง อาจกำจัดหรือกระจายสินค้าออกไปหรือขายทอดตลาดโดยไม่ต้องจ่ายค่าชดเชยให้กับผู้ส่งสินค้า หรือผู้อื่น โดยคิดค่าใช้จ่ายในการบริการและค่าธรรมเนียมอื่น ๆ ที่เกี่ยวข้องและส่วนที่เหลือจากการขายทอดตลาดจะถูกตีกลับไปให้ผู้ส่งสินค้า
                    </p>
                    <p style="text-indent: 40px;">
                    4. การตรวจสอบ
                        ฟาสต์ชิป หรือ และบริษัทผู้นำส่ง มีสิทธิในการเปิดตรวจสอบสิ่งจัดส่งโดยไม่ต้องแจ้งให้ทราบ
                    </p>
                    <p style="text-indent: 40px;">
                    5. ค่าจัดส่ง
                        ค่าจัดส่งของฟาสต์ชิป จะคำนวณตามน้ำหนักที่ชั่งได้จริง หรือการคำนวณน้ำหนักตามปริมาตรจริง (volumetric weight) อย่างใดอย่างหนึ่งที่สูงกว่าจะนำมาคำนวณอัตราค่าบริการ ซึ่งฟาสต์ชิปจะต้องทำการชั่งน้ำหนักหรือวัดขนาดของการจัดส่งทุกชนิดอีกครั้งได้ ผู้จัดส่งจะต้องชำระเงิน หรือชำระเงินคืนให้กับ ฟาสต์ชิป สำหรับค่าใช้จ่ายในการขนส่งสินค้า ค่าใช้จ่ายในการเก็บสินค้า ค่าภาษีและอากรต่างๆ สำหรับการบริการใด หรือการกระทำใดที่ ฟาสต์ชิปได้กระทำลงไปในนามของผู้จัดส่ง หรือผู้รับ หรือบุคคลที่สาม รวมทั้งค่าเรียกร้อง ค่าเสียหาย ค่าปรับ และค่าใช้จ่ายที่เกิดขึ้น หากพบว่าสิ่งของที่จัดส่งนั้นไม่ผ่านการยอมรับสำหรับการให้บริการ ดังที่กำหนดไว้ในข้อ 2 ในกรณีที่พัสดุด้านใดด้านนึงเกิน 120 เซนติเมตร อาจจะมีค่า Oversize charge เพิ่มเติมในการขนส่ง 2,000 บาท
                    </p>
                    <p style="text-indent: 40px;">
                    6. ความรับผิดของฟาสต์ชิป
                        ความรับผิดของ ฟาสต์ชิป จำกัดเฉพาะในกรณีความเสียหาย หรือการสูญหายที่เกิดขึ้นโดยตรงเท่านั้น สำหรับความสูญหายและความเสียหายนอกเหนือจากนี้ถือว่าไม่มีผล เช่น การสูญเสียกำไร รายได้ ดอกเบี้ย และโอกาสทางธุรกิจในอนาคต ไม่ว่าการสูญหายและการเสียหายดังกล่าวจะอยู่ในกรณีพิเศษ หรือเกิดโดยทางอ้อมก็ตาม หรือแม้แต่เมื่อความเสี่ยงของการสูญหาย และเสียหายดังกล่าวนั้นได้ถูกรับรู้โดย ฟาสต์ชิป ไม่ว่าทั้งก่อนหรือหลังการยินยอมจัดส่งก็ตาม ความรับผิดของฟาสต์ชิปในส่วนของการขนส่งสิ่งจัดส่งใดๆก็ตาม โดยไม่มีผล กระทบต่อข้อ 7-11 จะอยู่ภายใต้มูลค่าที่ที่แท้จริงของสินค้าที่ระบุและไม่เกิน 2,000 บาท สำหรับบริการขนส่งด่วนทางอากาศเท่านั้น
                        ข้อเรียกร้องจะถูกจำกัดอยู่ที่จำนวน 1 ข้อเรียกร้องต่อ 1 การจัดส่ง ตามข้อตกลงที่สมบูรณ์และเป็นข้อตกลงสุดท้ายเกี่ยวกับการสูญหายและความเสียหายที่เกี่ยวข้อง ถ้าผู้จัดส่งเห็นว่าข้อจำกัดเหล่านี้ไม่เพียงพอ ก็สามารถทำเป็นประกาศที่แจกแจงเรื่องมูลค่า และขอการทำการประกันการจัดส่งขึ้นมาเป็นพิเศษตามที่กำหนดไว้ในข้อ 8 (ประกันการจัดส่ง) หรือจัดเตรียมการประกันการจัดส่งของตนเอง มิเช่นนั้น ผู้จัดส่งจะต้องเป็นผู้รับความเสี่ยงจากการสูญหายและความเสียหายทั้งหมด
                    </p>
                    <p style="text-indent: 40px;">
                    7. ระยะเวลาในการเรียกร้องความเสียหาย
                        การเรียกร้องความเสียหายทุกชนิดจะต้องเป็นลายลักษณ์อักษร และส่งถึง ฟาสต์ชิป ภายใน 15 วัน หลังจากวันที่ฟาสต์ชิปตอบรับสินค้าเพื่อการจัดส่ง หากไม่เป็นไปตามนั้น ฟาสต์ชิป จะไม่รับผิดชอบใดๆทั้งสิ้น
                    </p>
                    <p style="text-indent: 40px;">
                    8. การรับประกันการจัดส่ง
                    ฟาสต์ชิปสามารถจัดการประกันการจัดส่งให้กับผู้จัดส่งที่ประสงค์ซื้อประกันจากบริษัทผู้นำส่งเพิ่มเติม ซึ่งประกันนี้จะครอบคลุมมูลค่าเงินสดแท้จริงในกรณีที่เกิดการสูญหาย หรือความเสียหายของสิ่งของที่จัดส่ง โดยผู้จัดส่งจะต้องกรอกข้อมูลในส่วนของประกันทางด้านบนของใบกำกับการจัดส่งให้ครบ และจ่ายค่าเบี้ยประกันสำหรับการประกันดังกล่าว ประกันการจัดส่งนั้นไม่รวมการสูญหาย หรือความเสียหายโดยทางอ้อม หรือการสูญหาย หรือความเสียหายที่เกิดขึ้นจากความล่าช้า
                    </p>
                    <p style="text-indent: 40px;">
                    9. การจัดส่งล่าช้าและการการันตีคืนเงิน
                        ฟาสต์ชิปทำการพยายามอย่างเหมาะสมในทุกกรณีในการดำเนินการจัดส่งให้ตรงตามหมายกำหนดการปกติของฟาสต์ชิป โดยตารางเวลานี้จะไม่ถือเป็นข้อผูกพันและไม่ใช่ส่วนหนึ่งของข้อตกลง ทั้งนี้ ฟาสต์ชิปจะไม่รับผิดชอบต่อการสูญหาย หรือความเสียหายใดๆทั้งสิ้นที่เกิดจากความล่าช้า
                    </p>
                    <p style="text-indent: 40px;">
                    10. สถานการณ์นอกเหนือความควบคุมของฟาสต์ชิป
                        ฟาสต์ชิปจะไม่รับผิดต่อการสูญหายหรือ ความเสียหายที่เกิดจากเหตุการณ์อื่นใดที่นอกเหนือความควบคุมของฟาสต์ชิป รวมถึง ความเสียหายทางไฟฟ้าและแม่เหล็กหรือ การลบเลือนที่เกิดขึ้นกับภาพอิเลคโทรนิคส์และภาพถ่าย ข้อมูล ข้อมูลบันทึกอัดเก็บ ความบกพร่องหรือลักษณะอื่นใดที่เกี่ยวข้องตามธรรมชาติ ของสิ่งของที่จัดส่ง ถึงแม้ว่าฟาสต์ชิปจะรับทราบถึงเหตุการณ์ดังกล่าวก็ตาม รวมทั้งการกระทำหรือการละเว้นการกระทำของบุคคลอื่นใดที่ไม่ได้รับการว่าจ้างหรือทำสัญญากับฟาสต์ชิป ตัวอย่างเช่น ผู้จัดส่ง ผู้รับ บุคคลที่สาม หน่วยงานภาษีอากรและหน่วยงานรัฐอื่นๆ และเหตุสุดวิสัยต่างๆ เช่น แผ่นดินไหว ไซโคลน พายุ น้ำท่วม หมอก สงคราม เครื่องบินตก การห้ามส่งสินค้าออกท่า การประท้วงหรือความวุ่นวายกลางเมือง การกระทำต่างๆของภาคอุตสาหกรรม
                    </p>
                    <p style="text-indent: 40px;">
                    11. การคุ้มครองภายใต้อนุสัญญาระหว่างประเทศ
                        ถ้าการจัดส่งนั้นเป็นการขนส่งทางอากาศ และไปยังที่หมายสุดท้ายหรือหยุดในประเทศอื่นๆนอกจากประเทศที่สินค้าถูกส่งออกให้มีการจัดการและบังคับใช้ตามหลักอนุสัญญามอนทรีออล หรืออนุสัญญาวอร์ซอว์ สำหรับการขนส่งระหว่างชาติอาจมีการใช้ข้อตกลงการขนส่งสินค้าทางถนน (ซีเอ็มอาร์) ทั้งนี้ข้อตกลงและอนุสัญญาเหล่านี้ จะจำกัดความรับผิดชอบในความสูญหายหรือความเสียหายที่เกิดขึ้น
                    </p>
                    <p style="text-indent: 40px;">
                    12. การรับประกันและการคุ้มครองของผู้จัดส่ง
                        ผู้จัดส่งจะต้องให้ความคุ้มครองและไม่ก่อให้เกิดภัยใดๆแก่ฟาสต์ชิปในความสูญหายและความเสียหายที่เกิดขึ้นจากการที่ผู้จัดส่งที่ไม่ปฏิบัติตามกฎหมายหรือข้อบังคับต่างๆ ที่บังคับใช้รวมถึงในการที่ผู้ส่งละเมิดการประกันและกระทำดังต่อไปนี้
                    – ข้อมูลทั้งหมดที่ได้จากผู้จัดส่ง หรือตัวแทนของผู้จัดส่งจะต้องสมบูรณ์และถูกต้อง
                    – การจัดส่งจัดเตรียมขึ้นในสถานที่ที่ปลอดภัยโดยพนักงานของผู้จัดส่ง
                    – ผู้จัดส่งใช้พนักงานที่ไว้วางใจได้ในการเตรียมการจัดส่ง
                    – ผู้จัดส่งปกป้องการจัดส่งจากการแทรกแซงที่ไม่ได้รับอนุญาตในระหว่างการเตรียมการจัดส่ง การจัดเก็บ และการขนส่งไปยังฟาสต์ชิป
                    – การกำหนดและแจ้งที่อยู่ปลายทางของการจัดส่งอย่างถูกต้อง และมีการบรรจุหีบห่ออย่างเหมาะสมเพื่อให้แน่ใจได้ถึงความปลอดภัยในการขนส่งที่มีการดูแลรักษาและจัดการตามปกติ
                    </p>
                    <p style="text-indent: 40px;">
                    13. การจัดเส้นทาง
                        ผู้ส่งสินค้ายอมรับเส้นทาง และการเปลี่ยนแปลงเส้นทางทุกเส้นทาง รวมถึงความเป็นไปได้ในการที่สินค้าจะถูกส่ง โดยเส้นทางที่ต้องมีการหยุดหรือพักระหว่างเส้นทาง
                    </p>
                    <p style="text-indent: 40px;">
                    14. การรับคืนสิ่งจัดส่ง
                        สำหรับสิ่งจัดส่งที่ไม่สามารถดำเนินการจัดส่งได้ ไม่ว่าด้วยเหตุอันใด ผู้ส่งต้องรับคืนสิ่งจัดส่งภายใน 14 วัน นับจากวันที่ฟาสต์ชิปตอบรับการจัดส่ง หากเลยเวลากำหนด ฟาสต์ชิปจะไม่รับผิดชอบต่อ ความสูญหายหรือเสียหายใดๆที่อาจเกิดขึ้น
                    </p>
                    <p style="text-indent: 40px;">
                    15. กฎหมายที่บังคับใช้ 
                        เพื่อสิทธิประโยชน์ของฟาสต์ชิป การข้อพิพาทใดๆที่เกิดขึ้นภายใต้หรือในแนวทางที่เกี่ยวข้องกับข้อตกลงและเงื่อนไขดังกล่าว จะต้องมีการดำเนินการในเขตอำนาจนอกการตัดสินของศาล และบังคับใช้กฎหมายของประเทศที่มาของการจัดส่ง และผู้ส่งจะต้องปฏิบัติตามการตัดสินดังกล่าว เว้นเสียแต่ว่าเป็นการขัดกับกฏหมายที่บังคับใช้
                    </p>
                    <p style="text-indent: 40px;">
                    16. การเป็นโมฆะ
                        ความเป็นโมฆะหรือการไม่มีผลบังคับใช้ของข้อกำหนดใดๆก็ตาม จะไม่มีผลกับส่วนอื่นๆของข้อตกลงและเงื่อนไขนี้
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection