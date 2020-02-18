<!doctype>
<?php //alert($pickup_data); ?>
<html lang="en" class="no-js">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="/css/vendor.css"/>
        <!-- <link rel="stylesheet" type="text/css" href="/css/app-orange.css"/> -->
        <link rel="stylesheet" type="text/css" href="/css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="/css/custom.css"/>
		<link rel="stylesheet" type="text/css" href="/css/timeline.css"/>
		<link rel="stylesheet" type="text/css" href="/css/step.css"/>
    </head>
    <style type="text/css">
        body { margin-top:20px; }
        .panel-title {display: inline;font-weight: bold;}
        .checkbox.pull-right { margin: 0; }
        .pl-ziro { padding-left: 0px; }
    </style>
    <body>
    
		<script src="/js/app.js" type="text/javascript"></script>
		<script src="/js/custom.js" type="text/javascript"></script>
		<script src="/js/vendor.js" type="text/javascript"></script>
        <script src="/vendor/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <div id="app-container">
	       	<div id="body-container" style="background-color: #fff; padding: 5px 0; font-size: 13px;">
            <!-- START CODE HERE -->
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    Payment Details
                                </h3>
                                <!--<div class="checkbox pull-right">
                                    <label>
                                        <input type="checkbox" />
                                        Remember
                                    </label>
                                </div>-->
                            </div>
                            <div class="panel-body">
                                <label class="label">Card name</label>
                                <div class="field"><div id="card-name"></div></div>
                                <label class="label">Card number</label>
                                <div class="field"><div id="card-number"></div><div>
                                <label class="label">Card expiry</label>
                                <div class="field"><div id="card-expiry"></div></div>
                                <label class="label">Card cvv</label>
                                <div class="field"><div id="card-cvv"></div></div>
                                <div>
                                    <form method="POST" action="https://app.fastship.co/kbank/payment_completed">
                                        <script
                                            src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kinlinepayment.min.js"
                                            data-apikey="pkey_test_20236CnhMARuRRok5PUGOmKZaP1GkYZa9czuS"
                                            data-amount="20"
                                            data-currency="THB">
                                        </script>
                                        <button id="pay-button" >Pay now</button>
                                    </form>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- END CODE HERE -->
                </div>
            </div>
        </div>
		<script type="text/javascript">
            
        </script>
	</body>
</html>
<!--<script type="text/javascript" src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js" data-apikey="pkey_test_20236CnhMARuRRok5PUGOmKZaP1GkYZa9czuS" data-amount="200.00" data-currency="THB" data-payment-methods="qr" data-name="Fastship Co., Ltd." data-order-id="151234567" data-customer-id="4815" data-description="Test Payment QR"></script>-->