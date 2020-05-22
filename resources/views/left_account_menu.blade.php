<div class="col-md-2 account-menu-wrapper hidden-xs">
	<ul class="nav account-menu">
		<li><h4><a href="{{url ('/account_overview')}}" @if($view_name == "account_overview") class="active" @endif><i class="fa fa-home"></i> {!! FT::translate('menu.account_overview') !!}</a></h4></li>
		<li><h4><a href="{{url ('/myaccount')}}" @if($view_name == "myaccount") class="active" @endif><i class="fa fa-user"></i> {!! FT::translate('menu.myinfo') !!}</a></h4></li>
		<li><h4><a href="{{url ('/customer_balance')}}" @if($view_name == "cust_balance") class="active" @endif><i class="fa fa-money"></i> {!! FT::translate('menu.balance') !!}</a></h4></li>
		<li><h4><a href="{{url ('/channel_list')}}" @if($view_name == "channel_list") class="active" @endif><i class="fa fa-cloud"></i> {!! FT::translate('menu.mychannel') !!}</a></h4></li>
		<li><h4><a href="{{url ('/change_password')}}" @if($view_name == "change_password") class="active" @endif><i class="fa fa-key"></i> {!! FT::translate('menu.change_password') !!}</a></h4></li>
		<li><h4><a href="{{url ('/case_list')}}" @if($view_name == "case_list") class="active" @endif><i class="fa fa-bullhorn"></i> {!! FT::translate('menu.mycase') !!}</a></h4></li>
<!-- 		<li><h4><a href="http://track.fastship.co" target="_blank" @if($view_name == "tools_track") class="active" @endif><i class="fa fa-plane"></i> {!! FT::translate('menu.tracking') !!}</a></h4></li> -->
<!-- 		<li><h4><a href="{{url ('/tariff/get_cost')}}" @if($view_name == "tools_tariff") class="active" @endif><i class="fa fa-gavel"></i> {!! FT::translate('menu.tariff') !!}</a></h4></li> -->
		<li><h4><a href="http://fastship.co/help/" target="_blank"><i class="fa fa-question-circle"></i> {!! FT::translate('menu.help') !!}</a></h4></li>
	</ul>
</div>