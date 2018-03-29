@extends('layouts.layout')

@section('title','DHL API Test')



@section('content')

  <div class="container">
    <h2>DHL API Form</h2>
    <hr noshade>
    {{--<form action="shipmentValidate_pro.php" method="POST"> --}}
    <form action="{{ URL('dev/post') }}" method="POST">
      {{-- <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" /> --}}
      {{ csrf_field() }}
      {{-- alert('') --}}
      <h3>Ship To</h3>
      <div class="form-group">
          <label for="shipTo_CompanyName">CompanyName: <font color="red">*Required <= 35 characters</font></label>
          <input type="shipTo_CompanyName" class="form-control" id="shipTo_CompanyName" placeholder="Enter CompanyName" name="shipTo_CompanyName" maxlength="35">
        </div>
      <div class="form-group">
          <label for="shipTo_Name">Name: <font color="red">*Required <= 35 characters</font></label>
          <input type="shipTo_Name" class="form-control" id="shipTo_Name" placeholder="Enter Name" name="shipTo_Name">
        </div>
      <div class="form-group">
          <label for="shipTo_Address1">Address:  <font color="red">*Required <= 35 characters</font></label>
          <input type="shipTo_Address1" class="form-control" id="shipTo_Address1" placeholder="Enter Address 35 Character" name="shipTo_Address1" maxlength="35">
        <div style="padding-top:10px"></div>
          <input type="shipTo_Address2" class="form-control" id="shipTo_Address2" placeholder="Enter Address2 35 Character" name="shipTo_Address2" maxlength="35">
        <div style="padding-top:10px"></div>
          <input type="shipTo_Address3" class="form-control" id="shipTo_Address3" placeholder="Enter Address2 35 Character" name="shipTo_Address3" maxlength="35">
        </div>
        <div class="form-group">
          <label for="city">City: <font color="red">*Required <= 35 characters</font></label>
          <input type="city" class="form-control" id="city" placeholder="Sample NEW YORK / NY" name="city" maxlength="35">
        </div>
      <div class="form-group">
          <label for="StateProvinceCode">StateProvinceCode:</label>
          <input type="StateProvinceCode" class="form-control" id="StateProvinceCode" placeholder="Sample NY" name="StateProvinceCode" maxlength="30">
        </div>
      <div class="form-group">
          <label for="CountryCode">CountryCode: <font color="red">*Required 2 characters</font></label>
          <input type="CountryCode" class="form-control" id="CountryCode" placeholder="Sample US" name="CountryCode" maxlength="2">
        </div>
        <div class="form-group">
          <label for="PostalCode">PostalCode: <font color="red">*Required </font></label>
          <input type="PostalCode" class="form-control" id="PostalCode" placeholder="Sample 10001" name="PostalCode">
        </div>
      <div class="form-group">
          <label for="CountryName">CountryName: <font color="red">*Required <= 35 characters</font></label>
          <input type="CountryName" class="form-control" id="CountryName" placeholder="Sample United States of America" name="CountryName">
        </div>
      <div class="form-group">
          <label for="shipTo_PhoneNumber">PhoneNumber: <font color="red">*Required <= 25 characters</font></label>
          <input type="shipTo_PhoneNumber" class="form-control" id="shipTo_PhoneNumber" placeholder="Enter PhoneNumber" name="shipTo_PhoneNumber">
        </div>
      
      <h3>Dimensions</h3>
      <div class="form-group">
          <label for="Depth">Depth: <font color="red">*Required </font></label>
          <input type="Depth" class="form-control" id="Depth" placeholder="Enter Depth" name="Depth">
        </div>
      <div class="form-group">
          <label for="Width">Width: <font color="red">*Required </font></label>
          <input type="Width" class="form-control" id="Width" placeholder="Enter Width" name="Width">
        </div>
       <div class="form-group">
          <label for="Height">Height: <font color="red">*Required </font></label>
          <input type="Height" class="form-control" id="Height" placeholder="Enter Height" name="Height">
        </div>
      <div class="form-group">
          <label for="Weight">Weight: <font color="red">*Required </font></label>
          <input type="Weight" class="form-control" id="Weight" placeholder="Enter Weight" name="Weight">
        </div>
      
      <div class="form-group">
          <label for="DeclaredValue">DeclaredValue: <font color="red">*Required </font></label>
          <input type="text" class="form-control" id="DeclaredValue" placeholder="Sample 100.00" name="DeclaredValue">
        </div>
      
      <h3>Ship From</h3>
      <div class="form-group">
          <label for="shipFrom_CompanyName">CompanyName:</label>
          <input type="shipFrom_CompanyName" class="form-control" id="shipFrom_CompanyName" placeholder="Enter CompanyName" name="shipFrom_CompanyName">
        </div>
      <div class="form-group">
          <label for="shipFrom_Name">Name: <font color="red">*Required </font></label>
          <input type="shipFrom_Name" class="form-control" id="shipFrom_Name" placeholder="Enter Name" name="shipFrom_Name">
        </div>
      <div class="form-group">
          <label for="shipFrom_Address">Address:</label>
          <input type="shipFrom_Address" class="form-control" id="shipFrom_Address" placeholder="Enter Address 35 Character" name="shipFrom_Address" maxlength="35">
        <div style="padding-top:10px"></div>
          <input type="shipFrom_Address2" class="form-control" id="shipFrom_Address2" placeholder="Enter Address2 35 Character" name="shipFrom_Address2" maxlength="35">
      </div>
        <div class="form-group">
          <label for="shipFrom_City">City:</label>
          <input type="shipFrom_City" class="form-control" id="shipFrom_City" placeholder="Sample BANGKOK" name="shipFrom_City">
        </div>
      <div class="form-group">
          <label for="shipFrom_StateProvinceCode">StateProvinceCode:</label>
          <input type="shipFrom_StateProvinceCode" class="form-control" id="shipFrom_StateProvinceCode" placeholder="Sample BKK" name="shipFrom_StateProvinceCode">
        </div>
      <div class="form-group">
          <label for="shipFrom_CountryCode">CountryCode:</label>
          <input type="shipFrom_CountryCode" class="form-control" id="shipFrom_CountryCode" placeholder="Sample TH" name="shipFrom_CountryCode">
        </div>
      <div class="form-group">
          <label for="shipFrom_PostalCode">PostalCode:</label>
          <input type="shipFrom_PostalCode" class="form-control" id="shipFrom_PostalCode" placeholder="Sample 10210" name="shipFrom_PostalCode">
        </div>
      <div class="form-group">
          <label for="shipFrom_CountryName">CountryName:</label>
          <input type="shipFrom_CountryName" class="form-control" id="shipFrom_CountryName" placeholder="Sample THAILAND" name="shipFrom_CountryName">
        </div>
      <div class="form-group">
          <label for="shipFrom_PhoneNumber">PhoneNumber:</label>
          <input type="shipFrom_PhoneNumber" class="form-control" id="shipFrom_PhoneNumber" placeholder="Enter PhoneNumber" name="shipFrom_PhoneNumber">
        </div>
      <div class="form-group">
          <label for="shipFrom_FaxNumber">FaxNumber:</label>
          <input type="shipFrom_FaxNumber" class="form-control" id="shipFrom_FaxNumber" placeholder="Enter FaxNumber" name="shipFrom_FaxNumber">
        </div>
        <div class="form-group">
          <label for="shipFrom_Email">Email: NULL</label>
          <input type="shipFrom_Email" class="form-control" id="shipFrom_Email" placeholder="Enter Email" name="shipFrom_Email">
        </div>
      
        <button type="submit" class="btn btn-default">Submit</button>
      
      <div class="form-group"></div>
    </form>
</div>




@stop
@php
  function alert()
  {
    $arg_list = func_get_args();
    foreach ($arg_list as $k => $v){
      print "<pre>";
      print_r( $v );
      print "</pre>";
    }
  }
@endphp
