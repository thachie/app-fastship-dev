<!doctype>
<html lang="en" class="no-js">
    <head>
    	
		<title>สร้างพัสดุสำเร็จแล้ว !!</title>
    	
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link href="https://fonts.googleapis.com/css?family=Mitr&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ url('/liff_asset/bootstrap.min.css') }}">
        
        <!-- Primary CSS -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/vendor.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app-orange.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ url('/css/styles.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ url('/css/custom.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ url('/liff_asset/custom.css?t='.time()) }}"/>

    </head>
    <body style="background: url('/images/line/line_loading_bg.png') #c7ecee bottom no-repeat ;background-size: 100%;">
    
    	<script src="{{ url('/js/app.js') }}" type="text/javascript"></script>
		<script src="{{ url('/js/custom.js') }}" type="text/javascript"></script>
		<script src="{{ url('/js/vendor.js') }}" type="text/javascript"></script>

        <div class="conter-wrapper" style="padding-top: 100px;">
                
        	<form id="redirect_form" name="redirect_form" method="post" action="{{ url('liff/create_shipment') }}">
        
        		<input type="hidden" name="line_user_id" class="line_user_id" />

        		<h3 class="text-center text-orange" >สร้างพัสดุสำเร็จแล้ว !!</h3>
        		
        		<div class="text-center" style="margin-top: 40px;">
            		<div id="profile_img"></div>
        		</div>
        		<div class="clearfix"></div>

        	</form>
        
        </div>
		<div class="clearfix"></div>

        <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
        <script type="text/javascript">
        <!--
        $(window).on('load',function(){
        	liff.init(function (data) {
                initializeApp(data);
            });
        });

        function initializeApp(data) {

            const userId = data.context.userId;

            liff.getProfile().then(function (profile) {

            	const name = profile.displayName;
            	const avatar = profile.pictureUrl;

            	$(".line_user_id").val(userId);
            	
            	var element =  document.getElementById('profile_img');
                if (typeof(element) != 'undefined' && element != null){
                    const profilePictureDiv = document.getElementById('profile_img');
                    if (profilePictureDiv.firstElementChild) {
                        profilePictureDiv.removeChild(profilePictureDiv.firstElementChild);
                    }
                    const img = document.createElement('img');
                    img.src = profile.pictureUrl;
                    img.alt = "Profile Picture";
                    img.style = "width:180px;border-radius:50%;";
                    profilePictureDiv.appendChild(img);

                       
                }
                
            }).catch(function (error) {
                window.alert("Error getting profile: " + error);
                alert(err);
            });
            
            liff.sendMessages([
            	  {
            	    type:'text',
            	    text:'สร้างพัสดุสำเร็จแล้ว เรียกให้เข้ารับเลย'
            	  }
        	])
        	.then(() => {
        	  console.log('message sent');
        	  liff.closeWindow();
        	})
        	.catch((err) => {
        	  console.log('error', err);
        	});

        	
        }
    	-->
    	</script>

    </body>
</html>