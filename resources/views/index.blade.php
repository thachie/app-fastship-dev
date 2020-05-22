@extends('layout')
@section('content')
<style>
img.rotate_pic{ opacity:1; transition:2s;margin-bottom: -20px; }
img.rotate_pic:hover{ 
  opacity:1;
  margin-bottom: 0px;
  transition:1s; 
  -ms-transform: rotate(10deg); /* IE 9 */
  -webkit-transform: rotate(10deg); /* Safari */
  transform: rotate(10deg); /* Standard syntax */ 
}
</style>
<div class="conter-wrapper">

	<div class="col-12 col-md-10 col-md-offset-1 alert alert-primary" role="alert">
    	ประกาศสำคัญเกี่ยวกับ COVID-19 (Updates Related to COVID-19) <a href="https://fastship.co/announcement/" target="_blank" class="alert-link">โปรดอ่าน (Please read) &gt;&gt;</a>
    </div>

	@if(session('customer.line') == "")
	<div class="col-12 col-md-6 col-md-offset-3 alert" style="top: 25px;background: #fff;margin-bottom: 50px;">
		<div class="col-md-6 col-xs-12">
			<div style="margin-bottom:5px;"><img src="/images/logo-1.png"  style="max-height: 25px;"/> <span style="vertical-align: middle;">✖</span> <img src="https://cdn.worldvectorlogo.com/logos/line.svg" style="max-height: 27px;"/></div>
			<h5>เชื่อมต่อเพื่อรับแจ้งเตือนอย่างรวดเร็ว</h5>
		</div>
		<div class="col-md-6 col-xs-12 text-right">
			<a href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1653805752&redirect_uri=https%3A%2F%2Fapp.fastship.co%2Fliff%2Fconnectline&state={{ uniqid() }}&scope=openid%20profile">
    			<img src="{{ url('images/line_fastship.png') }}" style="max-height: 60px;"/>
    		</a>
		</div>
		<div class="clearfix"></div>
	</div>
	@endif

	@if(isset($sentPickups) && $sentPickups < 3)

	<div class="col-md-10 col-md-offset-1">
		<p class="lead text-center text-primary">สวัสดีค่ะ ขอต้อนรับเข้าสู่ FastShip บริการขนส่งออนไลน์สำหรับผู้ขายออนไลน์ไปต่างประเทศ <br />บุคคลทั่วไปและลูกค้าใหม่กรุณาอ่านวิธีการใช้งานระบบ อย่างละเอียด เพื่อป้องกันข้อผิดพลาดและความล่าช้า ขอบคุณค่ะ 
		</p>
		<h4><b>ข้อแนะนำในการใช้งาน</b></h4>
		<ul style="font-size:1.1em;padding-left:20px;list-style: square;">
    		<li style="margin-bottom: 10px;">กรุณา<span class="text-primary">กรอกน้ำหนัก และขนาดกล่อง</span> ให้ครบถ้วน (ราคาอาจเปลี่ยนแปลง หากแจ้งไม่ตรง)</li>
    		<li style="margin-bottom: 10px;"><span class="text-primary">ระบุรายละเอียดสินค้าโดยละเอียด</span> และเช็ครายการสินค้าห้ามส่งในหน้าจอ (ห้ามระบุสินค้าไม่ตรง)</li>
    		<li style="margin-bottom: 10px;">เลือก Shipping Agent ที่เหมาะกับความต้องการ หากสินค้าส่งด่วนหรือมีมูลค่า <span class="text-primary">ควรเลือก Express</span></li>
    		<li style="margin-bottom: 10px;">เลือกวิธีเข้ารับที่เหมาะกับความต้องการ หากสินค้าส่งด่วนหรือมีมูลค่า <span class="text-primary">ควรเลือก เข้ารับด่วน หรือเข้ารับโดย FastShip</span></li>
    		<li style="margin-bottom: 10px;">หลังสร้างใบรับพัสดุเรียบร้อย สามารถ<span class="text-primary">ชำระเงินได้โดย QR และบัตรเครดิต</span></li>
    		<li style="margin-bottom: 10px;"><span class="text-primary">พิมพ์ใบปะหน้าติดที่พัสดุ</span> (หากรวมกล่อง ต้องติดใบปะหน้าที่กล่องรวมด้วย)</li>
    		<li style="margin-bottom: 10px;">ภายในเวลาที่ระบุ จะมีเจ้าหน้าที่จาก<span class="text-primary">บริษัทขนส่งติดต่อเข้ารับทางเบอร์โทรศัพท์ที่ให้ไว้</span> หากไม่ได้รับการติดต่อ กรุณาเปิดเคส</li>
    		<li style="margin-bottom: 10px;">หลังสินค้ามาถึงบริษัท จะ<span class="text-primary">ใช้เวลา 1-2 วันในการสร้าง AWB และ tracking</span> ท่านจะได้ tracking ทางไลน์ทันทีเมื่อสินค้าส่งออก</li>
    		<li style="margin-bottom: 10px;">สามารถ<span class="text-primary">ติดตามสถานะ</span> ได้ที่ <a href="https://app.fastship.co/add_case">สถานะพัสดุ</a></li> 
    		<li style="margin-bottom: 10px;">หากมีปัญหาหรือ<span class="text-primary">ต้องการความช่วยเหลือ กรุณาเปิดเคส</span> ได้ที่ <a href="https://app.fastship.co/add_case">{!! FT::translate('button.sendusmsg') !!}</a> </li>
    	</ul>
    	<div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
	@endif

	<div class="col-md-10 col-md-offset-1">
    	<div class="row alert alert-primary" style="background: #f15a21;color:#fff;"> 
    		<div class="col-md-8 col-xs-12 no-padding" style="border-right: 1px solid #fff;">
    			<div><h4>คุณอยู่ในระดับ {{ $customer_data['Group'] }} {{ $groupText['current'] }}</h4></div>
    			<div>{{ $groupText['next'] }}</div>
    		</div>
    		<div class="col-md-4 col-xs-12 no-padding text-right" >
    			<h2 style="margin-bottom:0px;color:#fff;line-height: 55px;">Store Credit: {{ $balance }} {!! FT::translate('unit.baht') !!}</h2>
    		</div>
    	</div>
	</div>

	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<div class="col-md-12 no-padding">
				<a class="rmv-line" href="{{url ('/calculate_shipment_rate')}}">
					<div class="col-md-3 block-index text-center fade">
						<img src="/images/dashboard/fs-dshboard-icon-01.png" />
						<h2>{!! FT::translate('menu.create_shipment') !!}</h2>
					</div>
				</a>
				<a class="rmv-line" href="{{url ('/import_shipment')}}">
					<div class="col-md-3 block-index text-center fade">
						<img src="/images/dashboard/fs-dshboard-icon-02.png" />
						<h2>{!! FT::translate('menu.import_shipment') !!}</h2>
					</div>
				</a>
				<a class="rmv-line" href="{{url ('/create_pickup')}}">
					<div class="col-md-3 block-index text-center fade">
						<img src="/images/dashboard/fs-dshboard-icon-03.png" />
						<h2>{!! FT::translate('menu.create_pickup') !!}</h2>
					</div>
				</a>
				<a class="rmv-line" href="{{url ('/pickup_list')}}">
					<div class="col-md-3 block-index text-center fade">
						<img src="/images/dashboard/fs-dshboard-icon-04.png" />
						<h2>{!! FT::translate('menu.pickup_list') !!}</h2>
					</div>
				</a>
			</div>
			<div class="col-md-4 block-index tool-index  fade">
    			<div class="text-center">
                	<img src="{{ url('images/fasty_help.png') }}" style="max-height:83px;" />
                	<a href="{{ url('add_case/') }}" target="_blank" ><button type="button" class="btn btn-primary btn-lg">{!! FT::translate('button.sendusmsg') !!}</button></a>
                </div>
			</div>
			<div class="col-md-8 block-index account-index fade">
				<div class="col-md-3">
					<h3 class="white">{!! FT::translate('menu.myaccount') !!}<h3>
				</div>
				<div class="col-md-9">
					<div class="col-xl-4 col-md-4 col-sm-6 block-item-index">
						<img src="/images/dashboard/fs-dshboard-icon-account-01.png" />
						<a href="{{url ('/account_overview')}}" class="link"> {!! FT::translate('menu.account_overview') !!}</a>
					</div>
					<div class="col-xl-4 col-md-4 col-sm-6 block-item-index" >
						<img src="/images/dashboard/fs-dshboard-icon-account-02.png" />
						<a href="{{url ('/myaccount')}}" class="link"> {!! FT::translate('menu.myinfo') !!}</a>
					</div>
					<div class="col-xl-4 col-md-4 col-sm-6 block-item-index" >
						<img src="/images/dashboard/fs-dshboard-icon-account-05.png" />						
						<a href="{{url ('/channel_list')}}" class="link"> {!! FT::translate('menu.mychannel') !!}</a>
					</div>
					<div class="col-xl-4 col-md-4 col-sm-6 block-item-index" >
						<img src="/images/dashboard/fs-dshboard-icon-account-06.png" />						
						<a href="{{url ('/customer_balance')}}" class="link"> {!! FT::translate('menu.balance') !!}</a>
					</div>
					<div class="col-xl-4 col-md-4 col-sm-6 block-item-index" >
						<img src="/images/dashboard/fs-dshboard-icon-account-03.png" />						
						<a href="{{url ('/change_password')}}" class="link"> {!! FT::translate('menu.change_password') !!}</a>
					</div>
					<div class="col-xl-4 col-md-4 col-sm-6 block-item-index" >
						<img src="/images/dashboard/fs-dshboard-icon-account-04.png" />
						<a href="http://fastship.co/help/" class="link"> {!! FT::translate('menu.help') !!}</a>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
@endsection