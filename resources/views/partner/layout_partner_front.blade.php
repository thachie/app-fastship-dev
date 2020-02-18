<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
  {{-- <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">--}}

  @if (isset($style))
  	@foreach ($style as $css)
  		<link rel="stylesheet" href="{{asset($css)}}">
  	@endforeach
  @endif

</head>
<body>{{asset('css/bootstrap.min.css')}}
	<!--@yield('content')-->
	<div id="container">
		<!--<section id="main">
			@yield('content')
		</section>-->
		@yield('content')
	</div>

{{-- Don't put the closing PHP comment */ in your comment --}}
{{-- 
<script type="text/javascript" src="{{asset('js/jquery-3.3.1.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
--}}
<!-- 
<script type="text/javascript" src="{{asset('js/jquery-3.3.1.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
-->

 @if (isset($script))
  	@foreach ($script as $js)
  		<script type="text/javascript" src="{{asset($js)}}"></script>
  	@endforeach
  @endif

</body>
</html>
