<!doctype>
<html lang="en" class="no-js">
    <head>
    	
		<title>Fastship</title>
    	
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="shortcut icon" href="/ficon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/ficon/favicon.ico" type="image/x-icon">
		
		<link rel="apple-touch-icon" sizes="57x57" href="/ficon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/ficon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/ficon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/ficon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/ficon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/ficon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/ficon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/ficon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/ficon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/ficon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/ficon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/ficon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/ficon/favicon-16x16.png">
		<link rel="manifest" href="/ficon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ficon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="/css/vendor.css"/>
        <link rel="stylesheet" type="text/css" href="/css/app-orange.css"/>
        <link rel="stylesheet" type="text/css" href="/css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="/css/custom.css?t=<?php echo time(); ?>"/>
		<link rel="stylesheet" type="text/css" href="/css/timeline.css"/>
		<link rel="stylesheet" type="text/css" href="/css/step.css"/>
		
		<!-- Start of fastship Zendesk Widget script -->
        <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=b69e2321-0f90-4c20-8911-733d23083e53"> </script>
        <!-- End of fastship Zendesk Widget script -->
		
		<!-- Facebook Pixel Code -->
        <script>
         !function(f,b,e,v,n,t,s)
         {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
         n.callMethod.apply(n,arguments):n.queue.push(arguments)};
         if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
         n.queue=[];t=b.createElement(e);t.async=!0;
         t.src=v;s=b.getElementsByTagName(e)[0];
         s.parentNode.insertBefore(t,s)}(window, document,'script',
         'https://connect.facebook.net/en_US/fbevents.js');
         fbq('init', '409564113246322');
         fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id=409564113246322&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->

		<style type="text/css">
		h5.seperate {
           width: 80%; 
           text-align: center; 
           border-bottom: 1px solid #aaa;
           line-height: 0.1em;
           margin: 30px 0 20px; 
        } 
        
        h5.seperate span { 
            background:#f5f5f5; 
            padding:0 15px; 
        }
        img.rotate_pic{ opacity:0.8; transition:2s;margin-bottom: -10px; }
        img.rotate_pic:hover{ 
          opacity:1;
          transition:1s;
          -ms-transform: rotate(3deg); /* IE 9 */
          -webkit-transform: rotate(3deg); /* Safari */
          transform: rotate(3deg); /* Standard syntax */ 
          margin-bottom: 0px;
        }
		</style>
    </head>
    <body>
    
 		<script src="/js/app.js" type="text/javascript"></script>
		<script src="/js/custom.js" type="text/javascript"></script>
		<script src="/js/vendor.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

		<div class="col-md-4 hidden-xs" style="background-color: #f15a22;height: 100%;overflow: hidden;">
			<div class="row" style="margin-top: 20%;">
    			<div class=" text-center"><img src="/images/logo-white.png" style="max-width: 90%;"></div>
    			<h3 class="white text-center">Deliver at your fingertips</h3>

    			<div style="position: absolute;bottom: 0;right: 20px;"><img class="rotate_pic" src="/images/login_pic.png"></div>
			</div>
		</div>
		<div class="col-md-8 ">
			<div class="row" style="margin-top: 15%;">
    			<div class="col-md-6 col-md-offset-2">
    			
    				<div class="hidden-md hidden-lg text-center"><img src="/images/logo-header.png" style="max-width: 100%;"></div>
    				<div class="clearfix"></div><br />
    				
    				@if (session('msg'))
    				<div class="alert alert-danger small">
    					{!! session('msg') !!}
    				</div>
    				<div class="clearfix"></div>
    				@endif
    			
        		    <form name="login_form" class="form-horizontal" method="post" action="{{url ('/customer/login')}}">
        				{{ csrf_field() }}
        				
        				@if (session('return'))
                        	<input type="hidden" name="return" value="{{ session('return') }}" />
                        @endif
                        
                        <h2>เข้าสู่ระบบ หรือ สมัครสมาชิกฟรี</h2>
                        <a href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1653844645&redirect_uri=https%3A%2F%2Fapp.fastship.co%2Fliff%2Floginline&state={{ uniqid() }}&scope=openid%20profile">
                    		<img src="{{ url('images/line_login.png') }}" style="max-height: 60px;"/>
                    	</a>
                    	
                    	<h5 class="seperate"><span>หรือ</span></h5>
    
    					<h4>เข้าสู่ระบบด้วยอีเมล์</h4>
    	                <div class="col-md-8">
    	                	<div class="">{!! FT::translate('login.form.email') !!}</div>
    	                	<input type="text" id="username" class="form-control required" name="username" required />
    	                </div>
    	                <div class="clearfix"></div>
    
    	                <div class="col-md-8" style="margin-top: 5px;">
        	                <div class="">{!! FT::translate('login.form.password') !!}</div>
        	                <input type="password" id="password" class="form-control required" name="password" required>
    	                </div>
    	                <div class="clearfix"></div>
    	                <br />
    
    					<div class="col-md-12">
    						<button type="submit" name="submit" class="btn btn-primary">{!! FT::translate('button.login') !!}</button>
    						<span class="small"><a href="forget_password">{!! FT::translate('login.forgotpassword') !!}</a></span>
    					</div>
    					<div class="clearfix"></div>
    					
    					
    
        			</form>
    			
        			<div class="clearfix"></div>
        			<br />
        			
					<span class="small" style="margin-left: 10px;">ยังไม่ได้เป็นสมาชิก? สมัครเลยไม่มีค่าใช้จ่าย <a href="/joinus">สมัครด้วยอีเมล์</a></span>
					
    			</div>
			</div>
	    </div>
	    <div class="clearfix"></div>
        
	    <script type="text/javascript">
	    $(function(){

	                
	    });
	    </script>
	    <script>
	      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	    
	      ga('create', 'UA-85407483-1', 'auto');
	      ga('send', 'pageview');
	    
	    </script>
	    <!-- Google Code for Remarketing Tag -->
	    <!--------------------------------------------------
	    Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
	    --------------------------------------------------->
	    <script type="text/javascript">
	    /* <![CDATA[ */
	    var google_conversion_id = 870452945;
	    var google_custom_params = window.google_tag_params;
	    var google_remarketing_only = true;
	    /* ]]> */
	    </script>
	    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	    </script>
	    <noscript>
	    <div style="display:inline;">
	    <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/870452945/?guid=ON&amp;script=0"/>
	    </div>
	    </noscript>

    </body>
</html>