<!doctype>
<html lang="en" class="no-js">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="/css/vendor.css"/>
        <link rel="stylesheet" type="text/css" href="/css/app-orange.css"/>
        <link rel="stylesheet" type="text/css" href="/css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="/css/custom.css"/>
		<link rel="stylesheet" type="text/css" href="/css/timeline.css"/>
		<link rel="stylesheet" type="text/css" href="/css/step.css"/>
		<style>
			@font-face{
				font-family: 'THSarabunNew';
				font-style: normal;
				font-weight: normal;
				src: url("{{ public_path('fonts/THSarabunNew.ttf')}}") format('truetype');
			}
			@font-face{
				font-family: 'THSarabunNew';
				font-style: normal;
				font-weight: bold;
				src: url("{{ public_path('fonts/THSarabunNew Bold.ttf')}}") format('truetype');
			}
			@font-face{
				font-family: 'THSarabunNew';
				font-style: italic;
				font-weight: normal;
				src: url("{{ public_path('fonts/THSarabunNew Italic.ttf')}}") format('truetype');
			}
			@font-face{
				font-family: 'THSarabunNew';
				font-style: italic;
				font-weight: bold;
				src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf')}}") format('truetype');
			}
			
			body {
			    font-family: "THSarabunNew", sans-serif;
			    font-size: 20px;
			    font-weight: bold;
			}
		</style>
</head>
<body>
<div class="conter-wrapper">
	<?php date_default_timezone_set("Asia/Bangkok");?>
	<a href="">PDF</a> {{ date("Y-m-d H:i:s") }} 
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td>ซื่อ(Name)</td>
				<td>อีเมลล์(Email)</td>
			</tr>
			<tr>
				<td>{{$name}} ทดสอบ</td>
				<td>{{$email}} ส่งอีเมล</td>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>