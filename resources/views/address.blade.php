@extends('layout')
@section('content')
<div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">Dependent country state city dropdown</div>
      <div class="panel-body">
        <form id="form-address" role="form" method="post" action="{{url ('api/store')}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="title">Select Country:</label>

                <select class="form-control" id="country" name="country" style="width:350px;">
                  <option value="">-- Choose Country --</option>
                  <?php
                    //$countries = $countryObj;
                    foreach($countries as $key => $value) {
                  ?>
                      <option value="<?= $value['CNTRY_CODE2ISO'] ?>" title="<?= htmlspecialchars($value['CNTRY_NAME']) ?>">
                        <?= htmlspecialchars($value['CNTRY_NAME']) ?>
                      </option>
                  <?php
                    }
                  ?>
                </select>

                {{-- Form::select('country', ['' => 'Select'] +$countries,'',array('class'=>'form-control','id'=>'country','style'=>'width:350px;'));--}}
               
            </div>
            <div class="form-group">
                <label for="title">Select State:</label>
                <div id="state_ajax">
               		<select name="state" id="state" class="form-control" style="width:350px"></select>
                </div>
                <div id="state_ajax_loading" style="display: none;">loading...</div>
                
            </div>
         
            <div class="form-group">
                <label for="title">Select City:</label>
                <div id="city_ajax">
               		<select name="city" id="city" class="form-control" style="width:350px"></select>
                </div>
                <div id="city_ajax_loading" style="display: none;">loading...</div>
                
            </div>

            <div class="form-group">
                <label for="title">Post Code:</label>
                <div id="postcode_ajax">
                	<select name="postcode" id="postcode" class="form-control" style="width:350px"></select>
                </div>
                <div id="postcode_ajax_loading" style="display: none;">loading...</div>
                <!--<input type="text" name="post_code" id="post_code" class="form-control" style="width:350px" />-->
            </div>

            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-2" style="text-align: right; padding-bottom: 10px;">
                <button type="submit" class="btn btn-success">Submit</button>
              </div>
            </div>
          </form>
      </div>

    </div>
</div>
<script type="text/javascript">
  var agent = '<?=$agent?>';
  $('#country').change(function(){
    var countryID = $("#country").val();    
    //alert(countryID);
    if(countryID){

      $("#state_ajax").hide();
      $("#state_ajax_loading").show();
      $.ajax({
        type:"post",
        data:{
        	"_token" : $("[name=_token]").val(),
        	"country_id" : countryID
        },
        url:"{{url('address/states')}}",
        success:function(res){              
        if(res){
        	$("#state_ajax_loading").hide();
            $("#state_ajax").show();
            $("#state").empty();
            $("#city").empty();
            $("#postcode").empty();
            
            $("#state").append('<option>Select State</option>');
            if(res.states != ''){
              $.each(res.states,function(states,key){
                $("#state").append('<option value="'+key.STATE_CODE+'">'+key.STATE_NAME+'</option>');

              });
            }else{
              $("#state").append('<option value=".">no state</option>');
            }
        }else{
           $("#state").empty();
        }
        }
      });
    }else{
      $("#state").empty();
      $("#city").empty();
      $("#postcode").empty();
    }      
  });
  
  $('#state').on('change',function(){
	var countryID = $("#country").val();   
    var stateID = $("#state").val();    
    if(stateID){
        $("#city_ajax").hide();
        $("#city_ajax_loading").show();
        $.ajax({
           type:"post",
           url:"{{url('address/cities')}}",
           data:{
           	"_token" : $("[name=_token]").val(),
           	"country_id" : countryID,
           	"state_id" : stateID
           },
           success:function(res){           
            if(res){
              $("#city_ajax_loading").hide();
              $("#city_ajax").show();
              $("#city").empty();
              $("#postcode").empty();

              $("#city").append('<option>Select City</option>');
              if(res.cities != ''){
                $.each(res.cities,function(cities,key){
                  $("#city").append('<option value="'+key.CITY_NAME_ASCII+'">'+key.CITY_NAME_ASCII+'</option>');
                });
              }else{
                $("#city").append('<option value=".">no city</option>');
              }
            }else{
               $("#city").empty();
            }
           }
        });
    }else{
        $("#city").empty();
        $("#postcode").empty();
    }
  });

  $('#city').on('change',function(){
	var countryID = $("#country").val();   
	var stateID = $("#state").val();   
    var cityName = $("#city").val();   

    if(cityName){
    	$("#postcode_ajax").hide();
        $("#postcode_ajax_loading").show();
        $.ajax({
           type:"post",
           url:"{{url('address/postcodes')}}",
           data:{
              	"_token" : $("[name=_token]").val(),
              	"country_id" : countryID,
              	"city_name" : cityName
              },
           success:function(res){       
            if(res){

              $("#postcode_ajax_loading").hide();
              $("#postcode_ajax").show();
              $("#postcode_ajax").empty();
              $("#postcode").empty();

              if(res.postcodes != '' && res.postcodes != false){
            	$("#postcode_ajax").append('<select id="postcode" name="postcode" class="form-control"></select>');
            	$("#postcode").append('<option>Select Postcode</option>');
                $.each(res.postcodes,function(postcode,key){
                  $("#postcode").append('<option value="'+key.POST_CODE+'">'+key.POST_CODE+'</option>');
                });
              }else{
                $("#postcode_ajax").append('<input id="postcode" type="text" name="postcode" class="form-control" />');
              }
            }else{
               $("#postcode").empty();
            }
           }
        });
    }else{
        $("#postcode").empty();
    }
  });

  /*if(countryID){
        $.ajax({
           type:"GET",
           //url:"{{url('api/get-state-list')}}?country_id="+countryID,
           url:"{{url('fastbox/api/get-state-list')}}?country_id="+countryID,
           success:function(res){               
            if(res){
                $("#state").empty();
                $("#state").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#state").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#state").empty();
            }
           }
        });
    }else{
        $("#state").empty();
        $("#city").empty();
    }      
   });*/

  /*$('#state').on('change',function(){
    var stateID = $(this).val();    
    if(stateID){
        $.ajax({
           type:"GET",
           //url:"{{url('api/get-city-list')}}?state_id="+stateID,
           url:"{{url('fastbox/api/get-city-list')}}?state_id="+stateID,
           success:function(res){               
            if(res){
                $("#city").empty();
                $.each(res,function(key,value){
                    $("#city").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#city").empty();
            }
           }
        });
    }else{
        $("#city").empty();
    }
        
   });*/
</script>
@endsection