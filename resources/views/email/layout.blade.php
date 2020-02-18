<?php $root_path = 'http://app.fastship.co/'; ?>
<!doctype>
<html lang="en" class="no-js">
    <head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php $root_path ?>css/styles.css">
		<style>
			@font-face {
				font-family: 'thaisans';
				src: url('<?php echo $root_path; ?>fonts/thaisansneue-regular-webfont.woff2') format('woff2'),
					url('<?php echo $root_path; ?>fonts/thaisansneue-regular-webfont.woff') format('woff');
				font-weight: normal;
				font-style: normal;
			}
			body,html{font-family: 'thaisans',Helvetica,arial,sans-serif; font-weight: 600; color: #43525a;}
			/* .col-md-7 a, .col-md-5 a{font-size: 16px; color: #43525a; margin-left: 38px; line-height: 28px;} */
			.table-content{background-color: #eee; color: #43525a; border-collapse: collapse; font-weight: 400; width: 1000px;}
			.table-content tr td, .table-content tr{padding: 8px 30px; line-height: 30px; border: 1px solid #fff;}
			/* .mobile.h2{font-size: 48px; color: #43525a;} */
			img{margin-bottom: 0px;}
			/* .h2{font-size: 18px;} */
			/* .pickupid{font-size: 40px;} */
			.question{min-height: 300px; float: left; width:100%; background-color: #eee; padding: 20px; margin: 0;}
			a {color: #666699; text-decoration: none;}
			.faq-panel{ display: inline-block; vertical-align: top; width:45%; }
			@media only screen and (max-width: 480px){
				.faq-panel{ width: 48%; }
			}
		</style>
    </head>
    <body style="font-family: 'thaisans',Helvetica,arial,sans-serif; font-size: 16px;">
		<div style=" margin: auto; width: 1000px;">
			<header>
				<div style="text-align:center; margin: 20px;"><img src="<?php echo $root_path; ?>images/email/logo-header.png"></div>
				<hr style="border: 3px solid #f15a22;">
			</header>
			@yield('content')
			<footer>
				<h2 style="font-weight: 600;">Contact US : Line@ : <a href="https://line.me/R/ti/p/%40fastship.co" target="_blank">@fastship.co</a> Tel : <a href="tel:+6620803999" target="_self">02-080-3999 </a> </h2>
				<!-- <img src="<?php //echo $root_path; ?>images/email/fs-footer.png"> -->
			</footer>
		</div>
    </body>
</html>

