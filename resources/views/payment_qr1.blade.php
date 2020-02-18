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
                                <!--<form role="form" class="form-inline">
                                <div class="form-group">
                                    <label for="cardNumber">
                                        Shipment ID:</label> <?=$shipment_id?>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cardNumber" placeholder="Valid Card Number" value="<?=$shipment_id?>" />-->
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="amount">
                                        Amount:</label> <?=$amount?>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="amount" value="<?=$amount?>" />
                                    </div>
                                </div>
                                </form>-->

                                <form class="form-horizontal">
                                    <!--<div class="form-group">
                                        <label class="control-label col-sm-4" for="order_id">Order ID:</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" rows="4" cols="30">
                                                <?=$order_id?>
                                            </textarea>    
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="payment_methods">Payment methods:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?=$payment_methods?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="shipment_id">Shipment ID:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?=$shipment_id?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="amount">Amount:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?=$amount?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active">
                                <a><span class="badge pull-right"><span >THB</span> <?=$amount?></span> Total Payment</a>
                            </li>
                        </ul>
                        <br/>
                        <!--<a href="#" class="btn btn-success btn-lg btn-block" role="button">
                            Pay Now
                        </a>-->
                        <div class="text-right">
                            <form method="POST" action="https://app.fastship.co/kbank/payment_completed">
                                <script type="text/javascript" src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                                data-apikey="pkey_test_20236CnhMARuRRok5PUGOmKZaP1GkYZa9czuS"
                                data-amount="<?php echo $amount;?>"
                                data-currency="THB"
                                data-payment-methods="<?php echo $payment_methods;?>"
                                data-name="Fastship Co., Ltd."
                                data-order-id="<?php echo $order_id;?>"
                                data-description="<?php echo $description;?>"
                                >
                                </script>
                                <!--
                                    data-show-button="false"
                                    <input type="button" class="btn btn-success btn-lg btn-block" role="button" value="Pay Now" onclick="KPayment.show()">-->
                            </form>
                        </div>
                    </div>
                </div>
            <!-- END CODE HERE -->
			</div>
		</div>
		<script type="text/javascript">
            var merchantFunction = function(){ // Add notify action.
                console.log("Close popup!");
                alert('ยังไม่ได้ทำรายการหรือทำรายการไม่สำเร็จ');
            };
            KPayment.onClose(merchantFunction);
        </script>
	</body>
</html>
<!--<script type="text/javascript" src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js" data-apikey="pkey_test_20236CnhMARuRRok5PUGOmKZaP1GkYZa9czuS" data-amount="200.00" data-currency="THB" data-payment-methods="qr" data-name="Fastship Co., Ltd." data-order-id="151234567" data-customer-id="4815" data-description="Test Payment QR"></script>-->