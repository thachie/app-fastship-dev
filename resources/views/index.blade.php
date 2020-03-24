@extends('layout')
@section('content')
<div class="conter-wrapper">

	<div class="col-12 col-md-10 col-md-offset-1 alert alert-primary" role="alert">
        Updates Related to COVID-19 <a href="https://fastship.co/annoucement/" target="_blank" class="alert-link">More ...</a>
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
			<div class="col-md-4 block-index tool-index fade">
				<div class="col-md-5"><h3>{!! FT::translate('menu.tools') !!}</h3></div>
				<div class="col-md-7">
					<div class="col-md-12 block-item-index">
						<img src="/images/dashboard/fs-dshboard-icon-tool-01.png" />
						<a href="{{url ('/track')}}" class="link"> {!! FT::translate('menu.tracking') !!}</a><br />
					</div>
					<div class="col-md-12 block-item-index">
						<img src="/images/dashboard/fs-dshboard-icon-tool-02.png" /> 
						<a href="{{url ('/tariff/get_cost')}}" class="link"> {!! FT::translate('menu.tariff') !!}</a>
					</div>
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
					<div class="col-xl-4 col-md-4 col-sm-6 block-item-index">
						<img src="/images/dashboard/fs-dshboard-icon-account-06.png" />
						<a href="{{url ('/payment_submission')}}" class="link"> {!! FT::translate('menu.payment_submission') !!}</a>
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
</div>
@endsection