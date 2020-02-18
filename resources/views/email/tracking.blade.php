<?php 
  $root_path = 'http://app.fastship.co/'; 

  $DeclareTypes = explode(";",$shipmentData['ShipmentDetail']['DeclareType']);
  if($DeclareTypes [sizeof($DeclareTypes)-1] == ""){
      unset($DeclareTypes [sizeof($DeclareTypes)-1]);
  }
  $DeclareQtys = explode(";",$shipmentData['ShipmentDetail']['DeclareQty']);
  if($DeclareQtys [sizeof($DeclareQtys)-1] == ""){
    unset($DeclareQtys [sizeof($DeclareQtys)-1]);
  }
  $DeclareValues = explode(";",$shipmentData['ShipmentDetail']['DeclareValue']);
  if($DeclareValues [sizeof($DeclareValues)-1] == ""){
      unset($DeclareValues [sizeof($DeclareValues)-1]);
  }

  $Agent = $shipmentData['ShipmentDetail']['ShippingAgent'];
  if($Agent == "GM_Packet_Economy"){
     $$Agent = "GM Economy";
  }else if($Agent == "GM_Packet"){
     $Agent = "GM Non-Registered";
  }else if($Agent == "GM_Packet_Plus"){
     $Agent = "GM Registered";
  }else if($Agent == "DHL"){
     $Agent = "DHL Express";
  }else if($Agent == "UPS"){
     $Agent = "UPS Express";
  }else if($Agent == "SF"){
     $Agent = "SF Express";
  }
?>

<!doctype html>
<html class="email_html" lang="en" style="min-width: 100%;background-color: #f5f5f5;">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="format-detection" content="date=no">
  <meta name="format-detection" content="address=no">
  <meta name="format-detection" content="email=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="x-apple-disable-message-reformatting">
  <title>Receipt</title>
	<style type="text/css">/* Reset */
.email_body,
.email_section table,
.email_section td,
.email_section a {
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%; }

.email_section table,
.email_section td {
  mso-table-lspace: 0pt;
  mso-table-rspace: 0pt; }

.email_section a,
.email_section a span,
.content_section a:visited,
.content_section a:visited span,
.email_section address {
  text-decoration: none; }

.email_section img {
  -ms-interpolation-mode: bicubic;
  border: 0;
  height: auto;
  line-height: 100%;
  outline: none;
  text-decoration: none;
  -ms-interpolation-mode: bicubic; }

.email_body {
  height: 100% !important;
  margin: 0 !important;
  padding: 0 !important;
  width: 100% !important; }

.email_html,
.email_body {
  min-width: 100%; }

/* Grid: Email Wrapper */
.email_bg {
  font-size: 0;
  text-align: center;
  line-height: 100%; }

/* Grid: Email Section */
.content_section {
  max-width: 800px;
  Margin: 0 auto; }

.content_section_xs {
  max-width: 416px;
  Margin: 0 auto; }

/* Grid: Row */
.content_cell,
.column_row {
  font-size: 0;
  text-align: center; }

.column_row {
  max-width: 624px;
  Margin: 0 auto; }

.column_row:after {
  content: "";
  display: table;
  clear: both; }

/* Grid: Columns */
.column {
  vertical-align: top; }

.column_cell {
  vertical-align: top; }

.column_inline {
  width: auto;
  Margin: 0 auto;
  clear: both; }

/* Grid: Columns */
.col_1,
.col_2,
.col_3,
.col_50,
.col_order,
.col_order_xs {
  vertical-align: top;
  display: inline-block;
  width: 100%; }

.col_1 {
  max-width: 208px; }

.col_2 {
  max-width: 312px; }

.col_3 {
  max-width: 416px; }

.col_50 {
  max-width: 400px; }

.col_order {
  max-width: 156px; }

/* Typography: Fallback font family  */
.email_section a,
.email_section a:visited,
.email_section p,
.email_section li,
.email_section h1,
.email_section h2,
.email_section h3,
.email_section h4,
.email_section h5,
.email_section h6 {
  color: inherit;
  font-family: Arial, Helvetica, sans-serif;
  Margin-top: 0px;
  Margin-bottom: 0px;
  word-break: break-word; }

/* Typography: Default Sizes  */
.email_section p,
.email_section li {
  font-size: 16px;
  line-height: 26px; }

.email_section .text_lead {
  font-size: 19px;
  line-height: 31px; }

.email_section .text_xs {
  font-size: 14px;
  line-height: 22px; }

/* Typography: Headings  */
.email_section h1 {
  font-size: 38px;
  line-height: 48px; }

.email_section h2 {
  font-size: 28px;
  line-height: 38px; }

.email_section h3 {
  font-size: 21px;
  line-height: 28px; }

.email_section h4 {
  font-size: 18px;
  line-height: 24px; }

.email_section h5 {
  font-size: 16px;
  line-height: 21px; }

.email_section h6 {
  font-size: 12px;
  line-height: 14px;
  text-transform: uppercase;
  letter-spacing: 1px; }

/* Typography: Bold  */
.ebutton a,
.ebutton_xs a,
.email_section h1,
.email_section h2,
.email_section h3,
.email_section h4,
.email_section h5,
.email_section h6,
.email_section strong {
  font-weight: bold; }

/* Typography: Display  */
.column_cell .text_display {
  font-weight: lighter;
  color: inherit; }

/* Typography: Buttons  */
.ebutton td {
  font-size: 16px; }

.ebutton_xs td {
  font-size: 14px; }

/* Colors: Backgrounds */
.bg_primary {
  background-color: #f15a22; }

/*.bg_secondary {
  background-color: #e5e9ee; }*/

.email_html,
.email_body,
.bg_light {
  background-color: #f5f5f5; }

.bg_dark {
  background-color: #212121; }

.bg_white {
  background-color: #ffffff; }

.bg_facebook {
  background-color: #3664a2; }

.bg_twitter {
  background-color: #1a8bf0; }

.bg_instagram {
  background-color: #cf2896; }

.bg_youtube {
  background-color: #e62d28; }

.bg_google {
  background-color: #de5347; }

.bg_pinterest {
  background-color: #be2026; }

/* Colors: Text */
.text_primary,
.content_cell .text_primary,
.text_primary span,
.text_primary a {
  color: #f15a22; }

.text_secondary,
.content_cell .text_secondary,
.text_secondary span,
.text_secondary a {
  color: #959ba0; }

.text_light,
.content_cell .text_light,
.text_light span,
.text_light a {
  color: #f5f5f5; }

.text_dark,
.content_cell .text_dark,
.text_dark span,
.text_dark a {
  color: #333333; }

.text_white,
.content_cell .text_white,
.text_white span,
.text_white a,
.ebutton a,
.ebutton span,
.ebutton_xs a,
.ebutton_xs span {
  color: #ffffff; }

/* Colors: Border */
.bt_primary {
  border-top: 4px solid #f15a22; }

.bt_dark {
  border-top: 4px solid #212121; }

.bt_white {
  border-top: 4px solid #ffffff; }

.bb_white {
  border-bottom: 1px solid #ffffff; }

.bg_primary .bb_white,
.bg_dark .bb_white {
  opacity: .25; }

/*.bb_light {
  border-bottom: 1px solid #dee0e1; }*/

.bt_light {
  border-top: 1px solid #dee0e1; }

/* Colors: Shadow */
.shadow_primary,
.ebutton .bg_primary:hover,
.ebutton_xs .bg_primary:hover {
  -webkit-box-shadow: 0 0 12px 0 #f15a22;
  box-shadow: 0 0 12px 0 #f15a22; }

.shadow_dark,
.ebutton .bg_dark:hover,
.ebutton_xs .bg_dark:hover {
  -webkit-box-shadow: 0 0 12px 0 #212121;
  box-shadow: 0 0 12px 0 #212121; }

/* Backgrounds: Image Default */
.bg_center {
  background-repeat: no-repeat;
  background-position: 50% 0; }

/* Extras: Email Summary */
.email_summary {
  display: none;
  font-size: 1px;
  line-height: 1px;
  max-height: 0px;
  max-width: 0px;
  opacity: 0;
  overflow: hidden; }

/* Rounded Corners */
.brounded_top {
  border-radius: 4px 4px 0 0; }

.brounded_bottom {
  border-radius: 0 0 4px 4px; }

.brounded_left {
  border-radius: 0 0 0 4px; }

.brounded_right {
  border-radius: 0 0 4px 0; }

.brounded,
.ebutton td,
.ebutton_xs td {
  border-radius: 4px; }

.brounded_circle {
  border-radius: 50%; }

/* Text: Alignment */
.text_center {
  text-align: center; }

.text_center .imgr img {
  margin-left: auto;
  margin-right: auto; }

.text_left {
  text-align: left; }

.text_right {
  text-align: right; }

/* Text: Links */
.text_del {
  text-decoration: line-through; }

.text_link a {
  text-decoration: underline; }

.text_link .text_adr {
  text-decoration: none; }

/* Padding Utilities */
.py {
  padding-top: 16px;
  padding-bottom: 16px; }

.py_xs {
  padding-top: 8px;
  padding-bottom: 8px; }

.py_md {
  padding-top: 32px;
  padding-bottom: 32px; }

.py_lg {
  padding-top: 64px;
  padding-bottom: 64px; }

.px {
  padding-left: 16px;
  padding-right: 16px; }

.px_xs {
  padding-left: 8px;
  padding-right: 8px; }

.px_md {
  padding-left: 32px;
  padding-right: 32px; }

.px_lg {
  padding-left: 64px;
  padding-right: 64px; }

.pt {
  padding-top: 16px; }

.pt_xs {
  padding-top: 8px; }

.pt_md {
  padding-top: 32px; }

.pt_lg {
  padding-top: 64px; }

.pb {
  padding-bottom: 16px; }

.pb_xs {
  padding-bottom: 8px; }

.pb_md {
  padding-bottom: 32px; }

.pb_lg {
  padding-bottom: 64px; }

.pl {
  padding-left: 16px; }

.pl_xs {
  padding-left: 8px; }

.pl_lg {
  padding-left: 32px; }

.pr {
  padding-right: 16px; }

.pr_xs {
  padding-right: 8px; }

.pr_lg {
  padding-right: 32px; }

/* Margin Utilities */
.content_cell .mt_0 {
  margin-top: 0px; }

.content_cell .mt_xs {
  margin-top: 8px; }

.content_cell .mt {
  margin-top: 16px; }

.content_cell .mt_md {
  margin-top: 32px; }

.content_cell .mt_lg {
  margin-top: 64px; }

.content_cell .mb_0 {
  margin-bottom: 0px; }

.content_cell .mb_xs {
  margin-bottom: 8px; }

.content_cell .mb {
  margin-bottom: 16px; }

.content_cell .mb_md {
  margin-bottom: 32px; }

.content_cell .mb_lg {
  margin-bottom: 64px; }

/* Images */
.content_cell .img_inline,
.content_cell .img_full {
  clear: both;
  line-height: 100%; }

.content_cell .img_full {
  font-size: 0 !important; }

.img_full img {
  display: block;
  width: 100%;
  Margin: 0px auto;
  height: auto; }

/* Buttons */
.ebutton td,
.ebutton_xs td {
  line-height: normal;
  text-align: center;
  font-weight: bold;
  -webkit-transition: box-shadow .25s;
  transition: box-shadow .25s; }

.ebutton[align=center],
.ebutton_xs[align=center] {
  margin: 0 auto; }

.ebutton td {
  padding: 13px 24px; }

.ebutton_xs td {
  padding: 10px 20px; }

@media only screen {
  /* Web fonts (latin only) */
  @font-face {
    font-family: 'Open Sans';
    font-style: normal;
    font-weight: 300;
    src: local("Open Sans Light"), local("OpenSans-Light"), url(https://fonts.gstatic.com/s/opensans/v14/DXI1ORHCpsQm3Vp6mXoaTegdm0LZdjqr5-oayXSOefg.woff2) format("woff2");
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215; }
  @font-face {
    font-family: 'Open Sans';
    font-style: normal;
    font-weight: 400;
    src: local("Open Sans Regular"), local("OpenSans-Regular"), url(https://fonts.gstatic.com/s/opensans/v14/cJZKeOuBrn4kERxqtaUH3VtXRa8TVwTICgirnJhmVJw.woff2) format("woff2");
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215; }
  @font-face {
    font-family: 'Open Sans';
    font-style: normal;
    font-weight: 700;
    src: local("Open Sans Bold"), local("OpenSans-Bold"), url(https://fonts.gstatic.com/s/opensans/v14/k3k702ZOKiLJc3WVjuplzOgdm0LZdjqr5-oayXSOefg.woff2) format("woff2");
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215; }
  .column_cell a,
  .column_cell p,
  .column_cell li,
  .column_cell h1,
  .column_cell h2,
  .column_cell h3,
  .column_cell h4,
  .column_cell h5,
  .column_cell h6 {
    font-family: "Open Sans", sans-serif !important; }
  .column_cell .text_display {
    font-weight: 300 !important; }
  .column_cell p,
  .column_cell li {
    font-weight: 400 !important; }
  .ebutton a,
  .ebutton span,
  .ebutton_xs a,
  .ebutton_xs span,
  .column_cell h1,
  .column_cell h2,
  .column_cell h3,
  .column_cell h4,
  .column_cell h5,
  .column_cell h6,
  .column_cell strong {
    font-weight: 700 !important; }
  /* Button Enhancement */
  .ebutton td,
  .ebutton_xs td {
    padding: 0 !important; }
  .ebutton a {
    padding: 13px 24px !important; }
  .ebutton_xs a {
    padding: 10px 20px !important; }
  .ebutton a,
  .ebutton_xs a {
    display: block !important; }
  /* Grid Fix */
  .col_50 {
    max-width: 50% !important;
    float: left; } }

@media (max-width: 689px) {
  /* Columns */
  .col_1,
  .col_2,
  .col_3,
  .col_50,
  .col_order {
    display: block !important;
    max-width: 100% !important; }
  /* Content Alignment */
  .mobile_center {
    text-align: center !important; }
  .mobile_center .ebutton,
  .mobile_center .ebutton_xs,
  .mobile_center .column_inline {
    float: none !important;
    margin: 0 auto !important;
    width: auto !important;
    min-width: 0 !important;
    display: inline-block !important; }
  /* Mobile Padding */
  .mob_py {
    padding-top: 16px !important;
    padding-bottom: 16px !important; }
  .mob_py_0 {
    padding-top: 0 !important;
    padding-bottom: 0 !important; }
  .mob_py_md {
    padding-top: 32px !important;
    padding-bottom: 32px !important; }
  .mob_pt_0 {
    padding-top: 0 !important; }
  .mob_pt {
    padding-top: 16px !important; }
  .mob_pb {
    padding-bottom: 16px !important; }
  .mobile_hide {
    max-height: 0 !important;
    display: none !important;
    mso-hide: all !important;
    overflow: hidden !important;
    font-size: 0 !important; }
  /* Rounded Corners */
  .brounded_left {
    border-radius: 0 !important; }
  .brounded_right {
    border-radius: 0 0 4px 4px !important; }
  /* Images */
  .img_inline img {
    width: 100% !important;
    height: auto !important; } }
</style>

</head>
<body class="email_body" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;min-width: 100%;background-color: #f5f5f5;height: 100% !important;margin: 0 !important;padding: 0 !important;width: 100% !important;">
  <!-- preview_text -->
  <div class="email_summary" style="display: none;font-size: 1px;line-height: 1px;max-height: 0px;max-width: 0px;opacity: 0;overflow: hidden;">Email summary</div>
  <div class="email_summary" style="display: none;font-size: 1px;line-height: 1px;max-height: 0px;max-width: 0px;opacity: 0;overflow: hidden;">&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;</div>
  <!-- header_primary_nav -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px pt_md" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;padding-top: 32px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_primary brounded_top bt_white px py" style="font-size: 0;text-align: center;background-color: #f15a22;border-top: 4px solid #ffffff;border-radius: 4px 4px 0 0;padding-top: 16px;padding-bottom: 16px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="624" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td width="208" align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_1" style="vertical-align: top;display: inline-block;width: 100%;max-width: 208px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell px py_xs text_white text_left mobile_center" style="vertical-align: top;color: #ffffff;text-align: left;padding-top: 8px;padding-bottom: 8px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <p class="img_inline" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 100%;clear: both;"><a href="#" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;text-decoration: none;color: #ffffff;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;"><img src="https://app.fastship.co/images/logo-white.png" width="200" height="" alt="FastShip.co" style="max-width: 200px;-ms-interpolation-mode: bicubic;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;"></a></p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                          <td width="416" align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_3" style="vertical-align: top;display: inline-block;width: 100%;max-width: 416px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell px py_xs text_right mobile_center" style="vertical-align: top;text-align: right;padding-top: 8px;padding-bottom: 8px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <p class="text_white" style="color: #ffffff;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 26px;">
                                </p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
  <!-- spacer -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white" height="32" style="font-size: 0;text-align: center;background-color: #ffffff;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp;</td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
  <!-- title_primary_lg -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white px py" style="font-size: 0;text-align: center;background-color: #ffffff;padding-top: 16px;padding-bottom: 16px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="624" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tbody>
                              <p class="mb_xs" style="color: #959ba0;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 8px;word-break: break-word;font-size: 16px;line-height: 26px;">พัสดุหมายเลข {{ $shipmentData['ID'] }} ส่งออกเรียบร้อยแล้ว</p>
                          <tr>
                            <td class="column_cell px text_primary text_center" style="vertical-align: top;color: #f15a22;text-align: center;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                              <h2 style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 28px;line-height: 42px;font-weight: bold;">เลขติดตามพัสดุ:<br>{{ $shipmentData['ShipmentDetail']['Tracking'] }}</h2>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tkbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>

   <!-- button_dark_full -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white px py_xs" style="font-size: 0;text-align: center;background-color: #ffffff;padding-top: 8px;padding-bottom: 8px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="312" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_2" style="vertical-align: top;display: inline-block;width: 100%;max-width: 312px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell px text_dark text_center" style="vertical-align: top;color: #333333;text-align: center;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <table role="presentation" width="100%" class="ebutton" align="center" border="0" cellspacing="0" cellpadding="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;margin: 0 auto;">
                                  <tbody>
                                    <tr>
                                      <td class="bg_dark" style="background-color: #f15a22;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-size: 16px;padding: 13px 24px;border-radius: 4px;line-height: normal;text-align: center;font-weight: bold;-webkit-transition: box-shadow .25s;transition: box-shadow .25s;"><a href="https://app.fastship.co/track/<?php echo $shipmentData['ShipmentDetail']['Tracking'];?>" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;text-decoration: none;color: #ffffff;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-weight: bold;"><span style="color: #ffffff;text-decoration: none;">ติดตามพัสดุ</span></a></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>


  <!-- receipt_summary -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white px" style="font-size: 0;text-align: center;background-color: #ffffff;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="624" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tbody>
                          <tr>
                            <td class="column_cell px pt pb_md text_secondary text_link text_center" style="vertical-align: top;color: #959ba0;text-align: center;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 32px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                              <p class="mb_xs" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 8px;word-break: break-word;font-size: 16px;line-height: 26px;">ใบรับพัสดุ: {{ $shipmentData['PickupID'] }}</p>
                              <img src="https://app.fastship.co/images/agent/<?php echo $shipmentData['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width: 100px;"/>
                              <p style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 26px;">Term: {{ $shipmentData['ShipmentDetail']['TermOfTrade'] }}</p>
                             
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
  <!-- sender & receiver address-->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white px pb_md" style="font-size: 0;text-align: center;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-bottom: 32px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="624" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td width="312" align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_2" style="vertical-align: top;display: inline-block;width: 100%;max-width: 312px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="min-height: 250px; vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                              <td class="column_cell" height="32" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                            </tr>
                            <tr>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                              <td class="column_cell px_md py_md text_dark text_left" style="vertical-align: top;color: #333333;background-color: #f5f5f5;color: #959ba0;border-radius: 4px;text-align: left;padding-top: 32px;padding-bottom: 32px;padding-left: 32px;padding-right: 32px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <h6 class="mb_xs" style="color: #333333;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 8px;word-break: break-word;font-size: 16px;line-height: 14px;text-transform: uppercase;letter-spacing: 1px;font-weight: bold;">ผู้ส่ง</h6>
                                <p class="text_xs mb" style="color: #959ba0;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 16px;word-break: break-word;font-size: 14px;line-height: 22px;">
                                  {{ $shipmentData['SenderDetail']['Firstname'] }} {{ $shipmentData['SenderDetail']['Lastname'] }}<br>
                                  {{ $shipmentData['SenderDetail']['PhoneNumber'] }} <br>
                                  {{ $shipmentData['SenderDetail']['Email'] }}
                                                </p>

                                <?php if($shipmentData['SenderDetail']['AddressLine1'] != ""){ ?>
                                <p class="text_xs" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 14px;line-height: 22px;">
                                  {{ $shipmentData['SenderDetail']['AddressLine1'] }} {{ $shipmentData['SenderDetail']['AddressLine2'] }}
                                  {{ $shipmentData['SenderDetail']['City'] }} {{ $shipmentData['SenderDetail']['State'] }} {{ $shipmentData['SenderDetail']['Postcode'] }} <br>    
                                </p>
                                <?php } ?>
                              </td>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                          <td width="312" align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_2" style="vertical-align: top;display: inline-block;width: 100%;max-width: 312px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="min-height: 250px; vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                              <td class="column_cell" height="32" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                            </tr>
                            <tr>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                              <td class="column_cell bg_secondary brounded px_md py_md text_secondary text_left" style="vertical-align: top;background-color: #f5f5f5;color: #959ba0;border-radius: 4px;text-align: left;padding-top: 32px;padding-bottom: 32px;padding-left: 32px;padding-right: 32px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <h6 class="text_dark mb_xs" style="color: #333333;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 8px;word-break: break-word;font-size: 16px;line-height: 14px;text-transform: uppercase;letter-spacing: 1px;font-weight: bold;">ผู้รับ</h6>
                                <p class="text_xs mb" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 16px;word-break: break-word;font-size: 14px;line-height: 22px;">
                                  {{ $shipmentData['ReceiverDetail']['Firstname'] }} {{ $shipmentData['ReceiverDetail']['Lastname'] }}<br>
                                  {{ $shipmentData['ReceiverDetail']['PhoneNumber'] }} <br>
                                  {{ $shipmentData['ReceiverDetail']['Email'] }}
                                                </p>
                                <p class="text_xs" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 14px;line-height: 22px;">
                                  {{ $shipmentData['ReceiverDetail']['AddressLine1'] }} {{ $shipmentData['ReceiverDetail']['AddressLine2'] }}
                                  {{ $shipmentData['ReceiverDetail']['City'] }} {{ $shipmentData['ReceiverDetail']['State'] }} {{ $shipmentData['ReceiverDetail']['Postcode'] }} <br>    
                                </p>
                                
                              </td>
                              <td class="column_cell" width="16" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp; </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>

  <!-- receipt_details -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white px py" style="font-size: 0;text-align: center;background-color: #ffffff;padding-top: 16px;padding-bottom: 16px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="416" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_3" style="vertical-align: top;display: inline-block;width: 100%;max-width: 500px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell bg_secondary brounded px py_xs text_dark text_left mobile_center" style="vertical-align: top;color: #333333;border-radius: 4px;text-align: left;padding-top: 8px;padding-bottom: 8px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                  <tbody>

                                    <?php  
                                      if(sizeof($DeclareTypes) > 0):
                                        if(is_array($DeclareTypes)):
                                          // $count = 0;
                                          foreach($DeclareTypes as $key => $Type):    
                                            if( $key == sizeof($DeclareTypes)-1 ){
                                              $linebottom = "";
                                            }else{
                                              $linebottom = "border-bottom: 1px solid #dee0e1;";
                                            }
                                    ?>
                                    <tr>
                                      <td class="column_cell pl py bb_light text_dark text_left" style="vertical-align: top;color: #333333;<?php echo $linebottom; ?>text-align: left;padding-top: 16px;padding-bottom: 16px;padding-left: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                        <h5 class="mb_xs" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 8px;word-break: break-word;font-size: 16px;line-height: 21px;font-weight: bold;"><?php echo isset($declareType[$Type])?($declareType[$Type]):$Type; ?></h5>
                                        <p class="text_xs text_secondary" style="color: #959ba0;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 14px;line-height: 22px;">จำนวน <?php echo isset($DeclareQtys[$key])?($DeclareQtys[$key]):"1"; ?> ชิ้น </p>
                                      </td>
                                      <td width="72" class="column_cell pr py bb_light text_dark text_right" style="vertical-align: top;color: #333333;<?php echo $linebottom; ?>text-align: right;padding-top: 16px;padding-bottom: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                        <p style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 26px;"><?php echo isset($DeclareValues[$key])?($DeclareValues[$key]):"-"; ?> บาท</p>
                                      </td>
                                    </tr>
                                    <?php   
                                        endforeach;
                                      endif; 
                                    endif;
                                    ?>
                          
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>

  <!-- content -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white px py_md" style="font-size: 0;text-align: center;background-color: #ffffff;padding-top: 32px;padding-bottom: 32px;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="624" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tbody>
                          <tr>
                            <td class="column_cell px text_dark text_link text_center" style="vertical-align: top;color: #333333;text-align: center;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                              <p class="text_link" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 26px;">หมายเลขติดตามพัสดุ จะสามารถตรวจสอบสถานะการส่งได้ หลังจากทางลูกค้าได้รับอีเมล์ประมาณ 24 ชั่วโมง</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
  <!-- spacer -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_white" height="32" style="font-size: 0;text-align: center;background-color: #ffffff;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">&nbsp;</td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
  <!-- footer_dark -->
  <table class="email_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td class="email_bg bg_light px pb_md" style="font-size: 0;text-align: center;line-height: 100%;background-color: #f5f5f5;padding-left: 16px;padding-right: 16px;padding-bottom: 32px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
          <!--[if (mso)|(IE)]>
          <table role="presentation" width="800" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
            <tbody>
              <tr>
                <td align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
          <![endif]-->
          <table class="content_section" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 800px;margin: 0 auto;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <tbody>
              <tr>
                <td class="content_cell bg_dark brounded_bottom px pt pb_md" style="font-size: 0;text-align: center;background-color: #43525a;border-radius: 0 0 4px 4px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 32px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                  <div class="column_row" style="font-size: 0;text-align: center;max-width: 624px;margin: 0 auto;">
                    <!--[if (mso)|(IE)]>
                    <table role="presentation" width="624" border="0" cellspacing="0" cellpadding="0" align="center" style="vertical-align:top;Margin:0 auto;">
                      <tbody>
                        <tr>
                          <td width="208" align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_1" style="vertical-align: top;display: inline-block;width: 100%;max-width: 208px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell px pt_md pb text_primary text_left mobile_center" style="vertical-align: top;color: #f15a22;text-align: left;padding-left: 16px;padding-right: 16px;padding-top: 32px;padding-bottom: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <p class="img_inline" style="color: inherit;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 100%;clear: both;"><a href="http://fastship.co" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;text-decoration: none;color: #f15a22;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;"><img src="https://app.fastship.co/images/logo-orange.png" width="180" height="" alt="FastShip.co" style="max-width: 180px;-ms-interpolation-mode: bicubic;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;"></a></p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                          <td width="416" align="center" style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;vertical-align:top;">
                    <![endif]-->
                      <div class="col_3" style="vertical-align: top;display: inline-block;width: 100%;max-width: 416px;">
                        <table class="column" role="presentation" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align: top;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                          <tbody>
                            <tr>
                              <td class="column_cell px pt text_left mobile_center" style="vertical-align: top;text-align: left;padding-left: 16px;padding-right: 16px;padding-top: 16px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                <p class="text_link text_secondary" style="color: #ffffff;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;font-size: 16px;line-height: 26px;">
                                  &copy;2017-2018 FastShip Co., Ltd. <br>
                                  <a class="text_adr" href="#" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;text-decoration: none;color: #ffffff;font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 0px;word-break: break-word;"><span style="color: #ffffff;text-decoration: none;">1/269 ซอยแจ้งวัฒนะ 14 ถนนแจ้งวัฒนะ แขวงทุ่งสองห้อง เขตหลักสี่ กรุงเทพมหานคร 10210</span></a><br>
                                </p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <!--[if (mso)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <![endif]-->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <!--[if (mso)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
          <![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>
