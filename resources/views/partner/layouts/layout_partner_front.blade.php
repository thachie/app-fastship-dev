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
      <!-- <script src="/vendor/ckeditor/ckeditor.js" type="text/javascript"></script> -->
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <div id="app-container">
          <nav id="top-navbar" class"navbar navbar-inverse">
            <div class="container-fluid">
          <div class="navbar-header">
            <?php if (session('customer.id') !== null && session('customer.id') !== null) :?>
              <a class="sidenav-toggle" href="#"><span class="brandbar"><i class="fa fa-bars hidd"></i></span></a>
            <?php endif; ?>
            <a class="navbar-brand" href="{{url ('/')}}"><img src="/images/logo-1.png"></a>
          </div>
          <?php if (session('customer.id') !== null && session('customer.id') !== null) :?>
          
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right top-nav">
              <!--<li style="border:none;">
                <a href="{{url ('/calculate_shipment_rate')}}" <?php if($view_name == "shipment_rate") {echo "class='active'";} ?> style="padding: 6px 15px;margin-top: 16px; border: none;">สร้างพัสดุ</a>
              </li>
              <li class="dropdown" style="border:none;margin-right: 16px;">
                <a href="#" class="dropdown-toggle import-toggle <?php if($view_name == "import_shipment" || $view_name == "import_ebay") {echo "active";} ?>" data-toggle="dropdown" role="button" aria-expanded="false">
                  <span class="add">สรัางพัสดุอัตโนมัติ <i class="fa fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu top-dropdown-menu import import-dropdown" role="menu">
                  <li><a href="{{url ('/import_shipment')}}"><i class="fa fa-file"></i> From File (xls)</a></li>
                  <li><a href="{{url ('/import_ebay')}}"><i class="fa fa-level-down"></i> eBay (autofeed)</a></li>
                </ul>
              </li>
              <li style="border:none;">
                <a href="{{url ('/create_pickup')}}" <?php if($view_name == "create_pickup") {echo "class='active'";} ?> style="padding: 6px 15px;margin-top: 16px; border: none;">พัสดุรอส่ง  (<span id="cart_cnt"><?php echo session('pending.shipment'); ?></span>)</a>
              </li>
              <li style="border:none;">
                <a href="{{url ('/pickup_list')}}" <?php if($view_name == "pickup_list") {echo "class='active'";} ?> style="padding: 6px 15px;margin-top: 16px; border: none;">ใบรับพัสดุ</a>
              </li>
              <li class="dropdown" style="border:none;margin-right: 16px;">
                <a href="#" class="dropdown-toggle tools-toggle <?php if($view_name == "tools_track" || $view_name == "tools_deminimis") {echo "active";} ?>" data-toggle="dropdown" role="button" aria-expanded="false">
                <span class="add">เครื่องมือ <i class="fa fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu top-dropdown-menu tools tools-dropdown" role="menu">
                  <li><a href="{{url ('/track')}}"><i class="fa fa-plane"></i> ติดตามพัสดุ</a></li>
                  <li><a href="{{url ('/deminimis')}}"><i class="fa fa-bullseye"></i> คำนวณการเสียภาษี</a></li>
                </ul>
              </li>
              <li class="dropdown admin-toggle-panel">
                <a href="#" class="dropdown-toggle admin-toggle <?php if($view_name == "promotion" || $view_name == "payment_submission" || $view_name == "myaccount" || $view_name == "change_password") {echo "active";} ?>" data-toggle="dropdown" role="button" aria-expanded="false">
                <span class="add">บัญชีของฉัน
                <i class="fa fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu top-dropdown-menu admin admin-dropdown" role="menu">
                  <li role="presentation" class="dropdown-header"><?php echo session('customer.name'); ?></li>
                  <li><a href="{{url ('/account_overview')}}"><i class="fa fa-home"></i> ภาพรวมบัญชี</a></li>
                  <li><a href="{{url ('/payment_submission')}}"><i class="fa fa-money"></i> แจ้งการโอนเงิน</a></li>
                  <li><a href="{{url ('/myaccount')}}"><i class="fa fa-user"></i> ข้อมูลส่วนตัว</a></li>
                  <li><a href="{{url ('/channel_list')}}"><i class="fa fa-cloud"></i> ช่องทางของฉัน</a></li>
                  <li><a href="{{url ('/change_password')}}"><i class="fa fa-key"></i> เปลี่ยนรหัสผ่าน</a></li>
                  <li><a href="http://fastship.co/help/" target="_blank"><i class="fa fa-question-circle"></i> ความช่วยเหลือ</a></li>-->
                  <li><a href="{{url ('partner/logout')}}"><i class="fa fa-power-off"></i> ออกจากระบบ</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <?php else: ?>
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right top-nav">
              <li class=" ">
                <a href="http://fastship.co" target="_blank" class=" " role="button" aria-expanded="false">
                  Fastship คืออะไร
                </a>
              </li>
            </ul>
          </div>
          <?php endif; ?>
        </div>
      </nav>
      
      <?php if (session('customer.id') !== null && session('customer.id') !== null) :?>
      <div id="sidenav" style="display: none;">
        <div role="tabpanel" id="navTabs">
          <div class="sidebar-controllers">
            <div class="">
              <div class="tab-content-scroller tab-content sidebar-section-wrap ps-container ps-active-y" data-ps-id="895ee9e3-c3a3-210b-01ad-c2bd3ed45e97">
                <div role="tabpanel" class="tab-pane active" id="menu">
                  <ul class="nav sidebar-nav ">
                    <li style="border:none;">
                      <a href="{{url ('/calculate_shipment_rate')}}"  style="padding: 6px 15px;margin-top: 16px; border: none;">สร้างพัสดุ</a>
                    </li>
                    
                    <li class="sidenav-dropdown ">
                      <a class="subnav-toggle" href="javascript:;" style="padding: 6px 15px;margin-top: 16px; border: none;">สรัางพัสดุอัตโนมัติ<i class="fa fa-angle-down  pull-right"></i></a>
                      <ul class="nav sidenav-sub-menu">
                        <li><a href="{{url ('/import_shipment')}}"><i class="fa fa-excel"></i> From File (xls)</a></li>
                        <li><a href="{{url ('/import_ebay')}}"><i class="fa fa-level-down"></i> eBay (autofeed)</a></li>
                      </ul>
                    </li>

                    <li style="border:none;">
                      <a href="{{url ('/create_pickup')}}"  style="padding: 6px 15px;margin-top: 16px; border: none;">พัสดุรอส่ง  (<span id="cart_cnt_mob"><?php echo session('pending.shipment'); ?></span>)</a>
                    </li>
                    <li style="border:none;">
                      <a href="{{url ('/pickup_list')}}"  style="padding: 6px 15px;margin-top: 16px; border: none;">ใบรับพัสดุ</a>
                    </li>
                
                    <li class="sidenav-dropdown ">
                      <a class="subnav-toggle" href="javascript:;" style="padding: 6px 15px;margin-top: 16px; border: none;">เครื่องมือ<i class="fa fa-angle-down  pull-right"></i></a>
                      <ul class="nav sidenav-sub-menu">
                        <li><a href="{{url ('/track')}}"><i class="fa fa-plane"></i> ติดตามพัสดุ</a></li>
                        <li><a href="{{url ('/deminimis')}}"><i class="fa fa-bullseye"></i> คำนวณการเสียภาษี</a></li> 
                      </ul>
                    </li>

                    <li class="sidenav-dropdown ">
                      <a class="subnav-toggle" href="javascript:;" style="padding: 6px 15px;margin-top: 20px; border: none;">บัญชีของฉัน<i class="fa fa-angle-down  pull-right"></i></a>
                      <ul class="nav sidenav-sub-menu">
                        <li><a href="{{url ('/account_overview')}}"><i class="fa fa-home"></i> ภาพรวมบัญชี</a></li>
                        <li><a href="{{url ('/payment_submission')}}"><i class="fa fa-money"></i> แจ้งการโอนเงิน</a></li>
                        <li><a href="{{url ('/myaccount')}}"><i class="fa fa-user"></i> ข้อมูลส่วนตัว</a></li>
                        <li><a href="{{url ('/channel_list')}}"><i class="fa fa-cloud"></i> ช่องทางของฉัน</a></li>
                        <li><a href="{{url ('/change_password')}}"><i class="fa fa-key"></i> เปลี่ยนรหัสผ่าน</a></li>
                        <li><a href="http://fastship.co/help/" target="_blank"><i class="fa fa-question-circle"></i> ความช่วยเหลือ</a></li>
                        <li><a href="{{url ('/customer/logout')}}"><i class="fa fa-power-off"></i> ออกจากระบบ</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div id="sidenav-overlay" style="display: none;"></div>
      
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
            <div class="container-fluid">
              Copyright &copy;2018 FastShip.co. All rights reserved, Powered by CloudCommerce.
              <span class="pull-right">
            <i class="fa fa-phone-square"></i> <a href="tel:+6620803999" target="_self">020803999</a> 
            <!-- <a href="#"><i class="fa fa-facebook-square"></i> Fastship.co</a> -->
            <i class="fa fa-envelope"></i> <a href="mailto:cs@fastship.co" target="_self">cs@fastship.co</a> 
                <i class="fa fa-comment"></i> <a href="https://line.me/R/ti/p/%40fastship.co" target="_blank">@fastship.co</a>
              </span>
            </div>
          </div>
        </div>
          
        <script type="text/javascript">
          $(function(){

            $('#navTabs .sidebar-top-nav a').click(function (e) {
                e.preventDefault()
                $(this).tab('show');

                setTimeout(function(){
                    $('.tab-content-scroller').perfectScrollbar('update');        
                }, 10);
            });

            $('.subnav-toggle').click(function() {
                $(this).parent('.sidenav-dropdown').toggleClass('show-subnav');
                $(this).find('.fa-angle-down').toggleClass('fa-flip-vertical');

                setTimeout(function(){
                    $('.tab-content-scroller').perfectScrollbar('update');        
                }, 500);
            });

            $('.sidenav-toggle').click(function() {
              $('#app-container').toggleClass('push-right');
              $('#sidenav-overlay').toggle();
    
                  // setTimeout(function(){
                  //     $('.tab-content-scroller').perfectScrollbar('update');       
                  // }, 500);
            });
            
              $('#sidenav-overlay').click(function() {
                $('#app-container').removeClass('push-right');
                $('#sidenav-overlay').hide();
                
              });


            $('#boxed-layout').click(function() {
                
                $('body').toggleClass('box-section');

                var hasClass = $('body').hasClass('box-section');

                $.get('/api/change-layout?layout='+ (hasClass ? 'boxed': 'fluid'));
            });

            $('.admin-toggle').click(function(){
              $('.dropdown-menu').hide();
              $('.admin-dropdown').show();
            });
            $('.import-toggle').click(function(){
              $('.dropdown-menu').hide();
              $('.import-dropdown').show();
            });
            //$('.noti-toggle').click(function(){
            //$('.dropdown-menu').hide();
            //$('.noti-dropdown').show();
            //});
            $('.tools-toggle').click(function(){
              $('.dropdown-menu').hide();
              $('.tools-dropdown').show();
            });
            $(document).click(function(){
              $('.top-dropdown-menu').hide();
            });
              // $('.tab-content-scroller').perfectScrollbar();
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
