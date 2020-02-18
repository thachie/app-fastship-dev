<?php
session_start();

$session = $_REQUEST['sess'];
$contactId = $_REQUEST['contact'];
$returnURL = $_REQUEST['return'];

$_SESSION['hub_cloudcommerce'] = $session;
$_SESSION['hub_contactId'] = $contactId;
$_SESSION['returnURL'] = $returnURL;

header("Location: https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&runame=TUFF_Company-TUFFComp-CloudC-qjqlrnfod&SessID=" . $session);
?>
Loading...