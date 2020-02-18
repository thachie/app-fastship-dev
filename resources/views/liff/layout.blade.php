<!doctype>
<html lang="en" class="no-js">
    <head>
    	
		<title>Fastship</title>
    	
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link href="https://fonts.googleapis.com/css?family=Mitr&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ url('/liff_asset/bootstrap.min.css') }}">
        
        <!-- Primary CSS -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/vendor.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app-orange.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ url('/css/styles.css') }}"/>
<!--         <link rel="stylesheet" type="text/css" href="{{ url('/css/custom.css') }}"/> -->
        <link rel="stylesheet" type="text/css" href="{{ url('/liff_asset/custom.css?t='.time()) }}"/>
        
        <!-- Jquery UI -->
        <link rel="stylesheet" href="{{ url('/liff_asset/jquery-ui.css') }}">
        
        <!-- Main JS-->
        <script src="{{ url('/js/app.js') }}" type="text/javascript"></script>
		<script src="{{ url('/js/custom.js') }}" type="text/javascript"></script>
		<script src="{{ url('/js/vendor.js') }}" type="text/javascript"></script>
		<script src="{{ url('/liff_asset/jquery-ui.js') }}"></script>
		<script src="{{ url('/liff_asset/custom-liff.js?t='.time()) }}"></script>

        <!-- Bootstrap JS-->
        <script src="{{ url('/liff_asset/popper.min.js') }}"></script>
        <script src="{{ url('/liff_asset/bootstrap.min.js') }}"></script>

    </head>
    <body>
    
    	

        <div id="app-container">
        	
        	<a href="{{url ('/')}}"><img src="{{ url('/images/line/liff_header.png') }}" style="width: 100%;"></a>

	       	<div id="body-container">
	       	
	       		@if (session('msg'))
				@if (session('msg-type'))
				<div class="col-12 col-md-10 col-md-offset-1 alert alert-<?php echo  session('msg-type'); ?>" style="top: 30px;">
					{{ session('msg') }}
				</div>
				@else
				<div class="col-12 col-md-10 col-md-offset-1 alert alert-danger" style="top: 30px;">
					{{ session('msg') }}
				</div>
				@endif
				<div class="clearfix"></div><br />
				@endif

	        	@yield('content')
	        	
	        	
	        </div>
	        <div id="footer-wrapper" class="footer">
	        	<div class="container-fluid text-center">
	        		<div><a href="#">เงื่อนไขการให้บริการ</a></div>
	        		<div>Copyright &copy;2018 FastShip.co. </div>
	        		<div>All rights reserved, Powered by CloudCommerce.</div>
	        		<span class="pull-right">
						<i class="fa fa-phone-square"></i> <a href="tel:+6620803999" target="_self">020803999</a> 
						<!-- <a href="#"><i class="fa fa-facebook-square"></i> Fastship.co</a> -->
						<i class="fa fa-envelope"></i> <a href="mailto:cs@fastship.co" target="_self">cs@fastship.co</a> 
	        			<i class="fa fa-comment"></i> <a href="https://line.me/R/ti/p/%40fastship.co" target="_blank">@fastship.co</a>
	        		</span>
	        	</div>
	        </div>
        </div>
        <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
        <script type="text/javascript">
        <!--
        $(window).on('load',function(){
        
        	liff.init(function (data) {
                initializeApp(data);
            });
        });

        function initializeApp(data) {
        	
            //document.getElementById('languagefield').textContent = data.language;
            //document.getElementById('viewtypefield').textContent = data.context.viewType;
            const userId = data.context.userId;
            //document.getElementById('utouidfield').textContent = data.context.utouId;
            const roomId = data.context.roomId;
            const groupId = data.context.groupId;

            $(".line_user_id").val(userId);

            if($("#profile_img")){
            	liff.getProfile().then(function (profile) {

                	const name = profile.displayName;
                	const avatar = profile.pictureUrl;

                	var element =  document.getElementById('profile_img');
                    if (typeof(element) != 'undefined' && element != null){
                        const profilePictureDiv = document.getElementById('profile_img');
                        if (profilePictureDiv.firstElementChild) {
                            profilePictureDiv.removeChild(profilePictureDiv.firstElementChild);
                        }
                        const img = document.createElement('img');
                        img.src = profile.pictureUrl;
                        img.alt = "Profile Picture";
                        img.style = "width:150px;border-radius:50%;";
                        profilePictureDiv.appendChild(img);
        
                        const profileNameDiv = document.getElementById('profile_name');
                        profileNameDiv.innerHTML = name;

                        if($("#firstname")){
                        	$("#firstname").val(name);
                        }
                        if($("#line_id")){
                        	$("#line_id").val(name);
                        }
                           
                    }
                    
                }).catch(function (error) {
                    window.alert("Error getting profile: " + error);
                    alert(err);
                });
            }
           

        }
    	-->
    	</script>

    </body>
</html>

