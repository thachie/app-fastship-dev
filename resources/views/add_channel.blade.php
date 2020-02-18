@extends('layout')
@section('content')
<div class="conter-wrapper">

    <div class="row">

        <div class="col-md-10 col-md-offset-1">
        	<div class="panel panel-primary">
    			<div class="panel-heading">Select Marketplace</div>
    		    <div class="panel-body">
    		    
    		    @foreach($marketplaces as $key => $marketplace)
    		    <a href="{{ url('/add_channel_ebay/'.$key) }}" style="text-decoration: none;">
    		    	<div class="col text-center channel-block">
    		    		<div class=""><img src="{{ url('images/marketplace/ebay.png') }}" style="width:80px;margin-right: 10px;" /><img src="https://www.countryflags.io/{{ $marketplace['country'] }}/flat/32.png" ></div>
    		    		<h4>{{ $marketplace['name'] }}</h4>
    		    	</div>
    		    </a>
    		    @endforeach

    		    </div>
    		</div>
        </div>

    </div>
</div>
@endsection