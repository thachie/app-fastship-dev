@extends('layout')
@section('content')

<div class="conter-wrapper">

    <div class="row">
    	
    	<div class="col-md-6 col-md-offset-3">
    		<h2>ประเมินภาษีสินค้า</h2>
    	</div>
    	
    	<div class="col-md-6 col-md-offset-3">
    		<div class="panel panel-primary">
    			<div class="panel-heading">ค้นหาประเภทสินค้า</div>
    			<div class="panel-body">
    				<div class="row">
    				
    				<form id="verify_form" action="{{ url('/tariff/get_cost/') }}" method="post" class="form-horizontal">
    	
                		{{ csrf_field() }}
                		
                		<input type="hidden" name="carrier" value="UPS" />
                		<input type="hidden" name="weight" value="500" />
    
    <?php /* ?>
    					<div class="hidden-xs col-md-4 text-right"><strong>หมวดสินค้า</strong></div>
    					<div class="visible-xs col-xs-12"><label><strong>หมวดสินค้า</strong></label></div>
    					<div class="col-xs-12 col-md-8">
    					<select id="category" name="category" class="form-control required" >
    					<option value="">ค้นหาทุกหมวด</option>
    					@foreach($categories as $cat)
    					@if( old('category',$default['category']) == $cat->code)
    					<option value="{{ trim($cat->code) }}" selected>{{ $cat->desc }}</option>
    					@else
    					<option value="{{ trim($cat->code) }}">{{ $cat->desc }}</option>
    					@endif
    					@endforeach
    					</select>
    					</div>
    					<div style="clear:both;"></div>
   <?php */ ?>
    					<div class="hidden-xs col-md-4 text-right"><strong>สินค้าของคุณคือ</strong><br /></div>
    					<div class="visible-xs col-xs-12"><label><strong>สินค้าของคุณคือ</strong></label></div>
    					<div class="col-xs-12 col-md-8">
    						<input type="text" id="declare" name="declare" class="form-control required" required value="{{ old('declare',$default['declare']) }}" />
    						<div id="translation" class="small text-info" style="margin: 10px 0;"></div>
    					</div>
    					<div style="clear:both;"></div>
  
    					<div class="hidden-xs col-md-4 text-right"><strong>HS Code</strong></div>
    					<div class="visible-xs col-xs-12"><strong>HS Code</strong></div>
    					<div class="col-xs-12 col-md-8"><input type="text" id="hs_code" name="hs_code" class="form-control" readonly required value="{{ old('hs_code',$default['hs_code']) }}"  /></div>		                                
    					<div style="clear:both;"></div>
    					
    					<div class="hidden-xs col-md-4 text-right"><strong>มูลค่าสินค้า (บาท)</strong></div>
    					<div class="visible-xs col-xs-12"><label><strong>มูลค่าสินค้า (บาท)</strong></label></div>
    					<div class="col-xs-12 col-md-8"><input type="number" min="1" id="price" name="price" class="form-control required" required value="{{ old('price',$default['price']) }}" /></div>
    					<div style="clear:both;"></div>
    					
    					<div class="hidden-xs col-md-4 text-right"><strong>ค่าส่ง (บาท)</strong></div>
    					<div class="visible-xs col-xs-12"><label><strong>ค่าส่ง (บาท)</strong></label></div>
    					<div class="col-xs-12 col-md-8"><input type="number" min="1" id="shipping" name="shipping" class="form-control required" required value="{{ old('shipping',$default['shipping']) }}" /></div>
    					<div style="clear:both;"></div>
    					
    					<div class="hidden-xs col-md-4 text-right"><strong>ประเทศปลายทาง</strong></div>
    					<div class="visible-xs col-xs-12"><label><strong>ประเทศปลายทาง</strong></label></div>
    					<div class="col-xs-12 col-md-8">
    					<select id="country" name="country" class="form-control required" required>
    					@foreach($countries2iso as $ccode => $cname)
    					@if( old('country',$default['country']) == $ccode)
    					<option value="{{ $ccode }}" selected>{{ $cname }}</option>
    					@else
    					<option value="{{ $ccode }}">{{ $cname }}</option>
    					@endif
    					@endforeach
    					</select>
    					</div>
    					<div style="clear:both;"></div>
    		
    					<br />
    					<div class="col-xs-12"><button type="submit" class="btn btn-block btn-success" style="padding: 9px 10px;"><i class="fa fa-calculator"></i> ประเมินราคาภาษี</button></div>		                                
    					
    				</form>
    				
    				</div>
    
    			</div>
    		</div>
    	</div>
    </div>
    
    @if($result)
    <div id="result-panel" class="row">
    	<div class="col-md-6 col-md-offset-3">
    		<div class="panel panel-primary">
    			<div class="panel-heading">ผลลัพท์การประเมิน</div>
    			<div class="panel-body">
    
    				<div class="col-xs-12 col-md-9 text-right">
    					<strong>{{ $result->items[0]->description }} <span class="small text-info">(HS: {{ $result->items[0]->hsCode }})</span></strong>
    				</div>
    				<div class="col-xs-12 col-md-2 text-right">{{ $result->subTotal }} </div>
    				<div class="hidden-xs col-md-1">USD</div>
    				<div style="clear:both;"></div>
    				
    				<div class="col-xs-9 col-md-9 text-right">Shipping cost to {{ $countries2iso[$result->addresses[1]->countryCode] }}</div>
    				<div class="col-xs-3 col-md-2 text-right">{{ $result->shippingCostTotal }} </div>
    				<div class="hidden-xs col-md-1">USD</div>
    				<div style="clear:both;"></div>
    				
    				<div class="col-xs-9 col-md-9 text-right">Duties</div>
    				<div class="col-xs-3 col-md-2 text-right">{{ $result->dutiesTotal }} </div>
    				<div class="hidden-xs col-md-1">USD</div>
    				<div style="clear:both;"></div>
    				
    <!-- 				<div class="hidden-xs col-md-9 text-right"><strong>Taxes</strong></div> -->
    <!-- 				<div class="col-xs-12 col-md-2 text-right">{{ $result->taxesTotal }} </div> -->
    <!-- 				<div class="col-xs-12 col-md-1">USD</div> -->
    				<div style="clear:both;"></div>
    				@foreach($result->taxes as $tax)
    				<div class="col-xs-9 col-md-9 text-right">{{ $tax->name }} </div>
    				<div class="col-xs-3 col-md-2 text-right">{{ $tax->amount }} </div>
    				<div class="hidden-xs col-md-1">USD</div>
    				<div style="clear:both;"></div>
            		@endforeach
            		
    <!-- 				<div class="hidden-xs col-md-9 text-right"><strong>Fees</strong></div> -->
    <!-- 				<div class="col-xs-12 col-md-2 text-right">{{ $result->feesTotal }} </div> -->
    <!-- 				<div class="col-xs-12 col-md-1">USD</div> -->
    				<div style="clear:both;"></div>
    				@foreach($result->fees as $fee)
    				<div class="col-xs-9 col-md-9 text-right">{{ $fee->name }} </div>
    				<div class="col-xs-3 col-md-2 text-right">{{ $fee->amount }} </div>
    				<div class="hidden-xs col-md-1">USD</div>
    				<div style="clear:both;"></div>
            		@endforeach
    				
    <!-- 				<div class="hidden-xs col-md-9 text-right"><strong>รวม</strong></div> -->
    <!-- 				<div class="col-xs-12 col-md-2 text-right">{{ $result->grandTotal }} </div> -->
    <!--                <div class="col-xs-12 col-md-1">USD</div> -->
    <!--                <div style="clear:both;"></div> -->
    				<hr />
    				<div class="col-xs-9 col-md-9 text-right text-success"><strong>Total Landed Cost</strong></div>
    				<div class="col-xs-3 col-md-2 text-right">{{ $result->grandTotal }} </div>
    				<div class="hidden-xs col-md-1">USD</div>
    				<div style="clear:both;"></div>
    				
    				<hr />
    				
    			</div>
    		</div>
    	</div>
    	<div class="clearfix"></div>
    	
    </div>
    @endif
    
    

</div>
<script type="text/javascript">
$(window).on('load',function(){
	autocompleteHS();
	$(document).on('click','#declare',function(){ this.select(); });

	//translate("fast", document.getElementById("translation"));
	
});
function translate(sentences, targetDiv, from_lang ='en', to_lang='th'){

	  sentences = sentences.replace(/\n/g, '<br>');
	  var endPoint = "https://translate.googleapis.com/translate_a/single?client=gtx&sl="+from_lang+"&tl="+to_lang+"&dt=t&ie=UTF-8&oe=UTF-8&q="+sentences;

	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
			var jsonText = JSON.parse(this.responseText);
			text = jsonText[0][0][0];
			text = text.replace(/<br>/g, '\n');
	      targetDiv.innerHTML = "&nbsp;" + text;	  
	    }
	  };
	  
	  xhttp.open("GET", endPoint, true);
	  xhttp.send();
}
function autocompleteHS(){

	$('#declare').autocomplete({
        minLength: 0,
        source: function( request, response ) {

          var _category = $("#category").val();
          $("#hs_code").val("");

          $.ajax({
            url: "{{ url('/tariff/get_hscodes') }}",
            type: "POST",
            dataType: "json",
            data: {
              term : request.term,
              category : _category,
              _token: "{{ csrf_token() }}"
            },
            success: function(data) {
            	console.log(data);
				var array = $.map(data, function (item) { //alert(array);
                    return {
                      label: item['code'] + " - " + item['desc'],
                      value: item['code'] + " - " + item['desc'],
                      data : item
                    }
                });
              	response(array);
              	
            }
          });
        },
        select: function( event, ui ) {
           	var data = ui.item.data;   
    		//$(this).val( data.code + " - " + data.desc);
    		$("#hs_code").val(data.code);
    		translate(data.desc, document.getElementById("translation"));

    		$("#result-panel").hide();
        }
      });
}

</script>
@endsection