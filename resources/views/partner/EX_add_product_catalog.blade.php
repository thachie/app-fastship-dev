@extends('front/layout-nomenu')
@section('content')

<style type="text/css">
	.output {
	    font: 1rem 'Fira Sans', sans-serif;
	}

	legend {
	    background-color: #000;
	    color: #fff;
	    padding: 3px 6px;
	}

	.username {
	    margin: 1rem 0;
	}

	label.test {
	    font-size: .8rem;
	}

	input.test:invalid + span:after {
	    content: '✖';
	    color: #f00;
	    padding-left: 5px;
	}

	input.test:valid + span:after {
	    content: '✓';
	    color: #26b72b;
	    padding-left: 5px;
	}

</style>
<div class="col-sm-8">
	<section class="panel panel-default">

		<div class="panel-heading"> 
	       <h2 class="panel-title">Create Product SKU</h2> 
	    </div> 
    
        <div class="panel-body">
    	<form class="form-horizontal" role="form" id="form-sku" method="post" action="{{url ('fastbox/product/add-catalog')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
		    <input type="hidden" name="customerId" value="{{$customerId}}">
		    <input type="hidden" name="customerName" value="{{$customerName}}">
		    <input type="hidden" name="action" value="createProduct">
		  
			<h5 class="with-line">Product Information</h5>

		    <div class="form-group">
		    	
		      	<div class="col-sm-6">
		      		<label class="control-label" for="sku">SKU:</label>	
			    	<!--<input type="text" id="sku" name="sku" maxlength="20" class="form-control name" required="required" onblur="this.value=removeSpaces(this.value);" style="text-transform:uppercase"/>-->

			    	<input class="form-control name" type="text" id="sku" name="sku" maxlength="20" 
			    	  onkeyup="
					  var start = this.selectionStart;
					  var end = this.selectionEnd;
					  this.value = this.value.toUpperCase();
					  this.setSelectionRange(start, end);
					">
			        <span class="help-block">20 characters</span>


			        <!-- https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/text
			        	<fieldset>
					    <legend>Login details:</legend>
					    <div class="username">
					        <label class="test" for="uname">Username:</label>
					        <input class="test" type="text" id="uname" name="uname" required
					               minlength="4" maxlength="8"
					               placeholder="4 to 8 characters long" />
					        <span class="validity"></span>
					    </div>
					</fieldset>-->
			    </div>
			    
			    <div class="col-sm-6">
		      		<label class="control-label" for="category">Category:</label>	
			    	<select class="form-control" id="category" name="category">
			        	<option value="">--- Choose Category ---</option>
						@if(!empty($productTypeObj))
							@foreach($productTypeObj as $key => $cat)
							  	<option value="<?= $cat->TYPE_CODE ?>" title="<?= htmlspecialchars($cat->TYPE_NAME) ?>">
							  	<?= htmlspecialchars($cat->TYPE_NAME) ?>
								</option>
							@endforeach
						@endif
			        </select>
			        <span class="help-block"></span>
			    </div>
			    
		 
		    	<div class="col-sm-12">
		      		<label class="control-label" for="productName">Product name:</label>	
			    	<input type="text" id="productName" name="productName" maxlength="50" class="form-control name" required="required" />
			        <span class="help-block">20 characters</span>
			    </div>
			    
			    <div class="col-sm-12">
		      		<label class="control-label" for="description">Description:</label>	
			    	<textarea class="form-control" rows="3" id="description" name="description" maxlength="255"></textarea>
			    	<span class="help-block"></span>
			    </div>
			    
			    <div class="col-sm-12">
		      		<label class="control-label" for="file">Product image:</label>	
			    	<div><input type="file" name="image" id="file"></div>
		        	<span class="help-block">file size 1000x1000px</span>
			    </div>
			    
		    </div>
		 
		    <br />
		    
		    <h5 class="with-line">Specification</h5>
		    <div class="form-group">
		    	<div class="col-sm-3">
		      		<label class="control-label" for="width">Width</label>
		          	<input class="form-control" id="width" type="text" name="width_cm" placeholder="cm" onkeypress="return onlyNumbersWithDot(event)" value="">
		        	<span class="help-block"></span>
		        </div>
		        <div class="col-sm-3">
		        	<label class="control-label" for="height">Height</label>
		          	<input class="form-control" id="height" type="text" name="height_cm" placeholder="cm" onkeypress="return onlyNumbersWithDot(event)" value="">
		        	<span class="help-block"></span>
		        </div>
		        <div class="col-sm-3">
		        	<label class="control-label" for="length">Length</label>
		          	<input class="form-control" id="length" type="text" name="length_cm" placeholder="cm" onkeypress="return onlyNumbersWithDot(event)" value="">
		        	<span class="help-block"></span>
		        </div>
		        <div class="clearfix">&nbsp;</div>

			    <div class="col-sm-6">
			    	<label class="control-label" for="weight">Weight</label>
			    	<div><input class="form-control" id="weight" type="text" name="weight_cm" placeholder="g" onkeypress="return onlyNumbersWithDot(event)" value=""></div>
			    	<span class="help-block"></span>
			    </div>
			    <div class="clearfix">&nbsp;</div>
			    
		    </div>
		    
		    <br />
		    
		    <h5 class="with-line">Price</h5>
		    <div class="form-group">
		        <div class="col-xs-2">
			        <label class="control-label" for="Cost">Cost</label>
			        <input class="form-control" id="cost" name="cost" type="text" placeholder="THB" onkeypress="return onlyNumbersWithDot(event)" value="">
			    </div>
			    <div class="col-xs-2">
			        <label class="control-label" for="Wholesale">Wholesale</label>
			        <input class="form-control" id="wholesale" name="wholesale" type="text" placeholder="THB" onkeypress="return onlyNumbersWithDot(event)" value="">
			    </div>
			    <div class="col-xs-2">
			        <label class="control-label" for="Retail">Retail</label>
			        <input class="form-control" id="Retail" name="retail" type="text" placeholder="THB" onkeypress="return onlyNumbersWithDot(event)" value="">
			    </div>
		    </div>

			<div class="form-group">
                <div class="col-sm-offset-3 col-sm-9" style="text-align: right; padding-bottom: 10px;">
                  <a href="{{url ('fastbox/catalog-list/')}}"><button type="button" class="btn btn-default">Cancel</button></a>
                  <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </div> 

		</form>
		</div>
	</section>
</div>
<div class="col-sm-4">
	<section class="panel panel-default">
    	<div class="panel-body">
        	<h4>Instruction</h4>
        	<p>
        	sadsadasdsadsad
        	</p>
        	<ul>
        	<li>teasfasf</li>
        	<li>teasfasf</li>
        	<li>teasfasf</li>
        	</ul>
        </div>
    </section>
</div>

<script type="text/javascript">
	var method = '';
	$(document).ready(function(){

		$('#metric').show();
		$("#us").hide();
		$('#measurement').change(function(){
		  var optionValue = $("#measurement").val();
		  if(optionValue == '' || optionValue == 'METRIC'){
		      $('#metric').show();
		      $("#us").hide();
		  }else if(optionValue == 'US'){
		      $("#us").show();
		      $('#metric').hide();
		  }else{
		      $('#metric').show();
		      $("#us").hide();
		  }
		});

		$("#sku").on("keypress", function(event) {
		    // Disallow anything not matching the regex pattern (A to Z uppercase, a to z lowercase and white space)
		    // For more on JavaScript Regular Expressions, look here: https://developer.mozilla.org/en-US/docs/JavaScript/Guide/Regular_Expressions
		    var englishAlphabetAndWhiteSpace = /[A-Za-z0-9]/g;
		    // Retrieving the key from the char code passed in event.which
		    // For more info on even.which, look here: http://stackoverflow.com/q/3050984/114029
		    var key = String.fromCharCode(event.which);
		    //alert(event.keyCode);
		    
		    // For the keyCodes, look here: http://stackoverflow.com/a/3781360/114029
		    // keyCode == 8  is backspace
		    // keyCode == 37 is left arrow
		    // keyCode == 39 is right arrow
		    // keyCode == 45 is -
		    // keyCode == 47 is /
		    // englishAlphabetAndWhiteSpace.test(key) does the matching, that is, test the key just typed against the regex pattern
		    if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 45 || event.keyCode == 47 || englishAlphabetAndWhiteSpace.test(key)) {
		        return true;
		    }
		    // If we got this far, just return false because a disallowed key was typed.
		    return false;
		});

		$('#skuX').on("paste",function(e)
		{
		    e.preventDefault();
		});


		$(function(){
		    $("#sku-test").keypress(function(event){
		        var ew = event.which;
		        alert(ew);
		        if(ew == 32)
		            return true;
		        //if(48 <= ew && ew <= 57)
		        //    return true;
		        //if(65 <= ew && ew <= 90)
		        //    return true;
		        if(97 <= ew && ew <= 122)
		            return true;
		        return false;
		    });
		});

	});

	function removeSpaces(string) {
		return string.split(' ').join('');
	}

	function fncCal(){
		var min = 2000;
		var max = 100000000;
		var amount = parseInt(eval($("#amount").val()));
		var totalMax = amount*(100);
		if(totalMax >= min && totalMax <= max){
		  return 1;
		}else{
		  return 2;
		}
	}

	function onlyNumbersWithDot(e) {           
		var charCode;
		if (e.keyCode > 0) {
		  charCode = e.which || e.keyCode;
		}
		else if (typeof (e.charCode) != "undefined") {
		  charCode = e.which || e.keyCode;
		}
		if (charCode == 46)
		  return true
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		  return false;
		return true;
	}

	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		  return false;
		return true;
	}
       
</script>
<script type="text/javascript">

	function forceInputUppercase(e)
	{
		var start = e.target.selectionStart;
		var end = e.target.selectionEnd;
		e.target.value = e.target.value.toUpperCase();
		e.target.setSelectionRange(start, end);
	}

	document.getElementById("sku").addEventListener("keyup", forceInputUppercase, false);

</script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" ></script>

<script type="text/javascript">
$(document).ready(function() { 
  	$('.btnUpdate').on('click',function(){
      if(confirm("Are you sure you update product?")){
        $('#form-sku-update').submit();
      }else{
        return false;
      }
    });

    $('#form-sku').validate({ // initialize the plugin
      rules: {
        "sku": "required",
        "productName": "required",
        "category": "required",
        "length_cm": "required",
        "width_cm": "required",
        "height_cm": "required",
        "weight_cm": "required",
        "cost": "required",
        "retail": "required",
      },
      messages: {
        "sku": "Please enter SKU",
        "productName": "Please enter product name",
        "category": "Please choose category",
        "length_cm": "Enter length",
        "width_cm": "Enter width",
        "height_cm": "Enter height",
        "weight_cm": "Enter weight",
        "cost": "Enter cost",
        "retail": "Enter retail",
      }
    });

});
</script>
@endsection