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
        <link rel="stylesheet" type="text/css" href="/css/custom.css"/>
		<link rel="stylesheet" type="text/css" href="/css/timeline.css"/>
		<link rel="stylesheet" type="text/css" href="/css/step.css"/>
		
		<!-- Start of fastship Zendesk Widget script -->
        <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=b69e2321-0f90-4c20-8911-733d23083e53"> </script>
        <!-- End of fastship Zendesk Widget script -->
		
		
    </head>
    <body>
    
 		<script src="/js/app.js" type="text/javascript"></script>
		<script src="/js/custom.js" type="text/javascript"></script>
		<script src="/js/vendor.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <div id="app-container" style="background: #f5f5f5;">

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

                <div class="conter-wrapper">

                <div class="row">
                	<div class="col-md-6 col-md-offset-3">
                		<h2>ติดตามพัสดุ</h2>
                		<div class="panel panel-primary">
                			<div class="panel-body">
                				<div class="row">
                					<div class="hidden-xs col-md-4 text-right"><strong>หมายเลขติดตามพัสดุ</strong><br /><span class="small">tracking number</span></div>
                					<div class="visible-xs col-xs-12"><label><strong>หมายเลขติดตามพัสดุ</strong> (<span class="small">tracking number</span>)</label></div>
                					<div class="col-xs-9 col-md-6"><input type="text" name="tracking" class="form-control form-control-lg required" required value="{{ $paramId }}" /></div>
                					<div class="col-xs-3 col-md-2"><button type="button" class="btn btn-primary btn-lg" onclick="trackShipment()" style="padding: 9px 10px;"><i class="fa fa-search"></i></button></div>		                                
                				</div>
                			</div>
                		</div>
                	</div>
                </div>
                @if(!empty($tracking_data) && sizeof($tracking_data) > 0 && is_array($tracking_data))
                @php
                $firstElem = array_values($tracking_data)[0];
                @endphp
                <div class="row">
                
                	<div class="col-md-6 col-md-offset-3">
                		<div class="panel panel-primary">
                			<div class="panel-heading">การติดตามพัสดุ</div>
                		    <div class="panel-body">
                		    	<div class="row">
                		    		<div class="col-md-12 text-center">
                			        	<h2>{{ (isset($trackingStatus[$firstElem['status']]))?$trackingStatus[$firstElem['status']]:$firstElem['status'] }}</h2>
                			        	<h4 class="green">Tracking: <strong>{{ $paramId }}</strong></h4>
                			        </div>
                		        </div>
                		    </div>
                		</div>
                	</div>
                	
                	<div class="col-md-6 col-md-offset-3"> 
                        <div class="panel panel-primary">
                        	<div class="panel-heading">ประวัติการเคลื่อนไหวของพัสดุ</div>
                            <div class="panel-body">
                            	<div class="timeline timeline-single-column">
                                	<?php 
                                	if(sizeof($tracking_data)>0):

                                	foreach($tracking_data as $event):
                                	
                                	$description = isset($event['description'])?$event['description']:$event['address'];

                                	if($event['status'] == "1004"){
                                		$css = "success";
                                	}else if($event['status'] == "1003"){
                                		$css = "info";
                                	}else if($event['status'] == "1002"){
                                	    $css = "warning";
                                	}else if($event['status'] == "1005" || $event['status'] == "1006"){
                                	    $css = "danger";
                                	}else{
                                		$css = "default";
                                	}
                                	?>
                                	<div class="timeline-item <?php echo $event['status']; ?>">
                                        <div class="timeline-point timeline-point-default">
                                            <i class="fa fa-check"></i>
                                        </div>
                                        <div class="timeline-event upgrade timeline-event-<?php echo $css; ?>">
                                            <div class="timeline-heading">
                                                <h4>{{ $description }}</h4>
                                            </div>
                                            <div class="timeline-body">
                                            
                                            	<p>{{ isset($trackingStatus[$event['status']])?$trackingStatus[$event['status']]:$event['status'] }} {{ ($event['location'])?"at ".$event['location']:"" }}</p>
                    
                                            </div>
                                            <div class="timeline-footer text-right">
                                            @if(isset($event['datetime']))
                                                @if($event['datetime'] != "")
                                                	{{ date("d/m/Y H:i:s",strtotime($event['datetime'])) }}
                                            	@endif
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    endforeach;
                                    endif;
                                    ?>
                            	</div>
                			</div>
                		</div>
                	</div>
                </div>
                @else
                <div class="text-center small text-info">ไม่พบข้อมูล</div>
                
                @endif
                </div>
                <script type="text/javascript">
                function trackShipment(){
                	window.location.href = "/track_st/" + $("input[name=tracking]").val();
                }
                </script>

			</div>
	        
        </div>
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