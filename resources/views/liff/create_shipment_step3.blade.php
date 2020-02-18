@extends('liff/layout')
@section('content')
<div class="conter-wrapper">

	<div class="col col-12">
    	<h4 class="text-secondary">ข้อมูลพัสดุ</h4>
    	<hr />
    </div>
	<div class="row">
    	
    	<div class="col col-12">
    		<div class="text-secondary">
    			<i class="fa fa-dropbox"></i>
    			พัสดุหนัก {{ session('liff.weight') }} กรัม 
    			@if(session('liff.width') != '')<span class="text-primary">ขนาด {{ session('liff.width') }}x{{ session('liff.height') }}x{{ session('liff.length') }} ซม.</span>@endif
    		</div>
    	</div>
    	<div class="col col-12">
    		<div class="text-secondary"><i class="fa fa-star"></i> ปลายทาง: {{ $country->name }}</div>
    	</div>
    	<div class="col col-12">
    		<div class="text-secondary"><i class="fa fa-plane"></i> ส่งโดย: {{ $agent->name }} {{ session('liff.rate') }} บาท</div>
    	</div>

   	</div>

	<div class="col col-12">
		<h3 class="text-orange">3. ระบุประเภทสินค้า</h3>
		<hr />
	</div>

	<div class="col col-12">
	<form id="create_form" name="create_form" method="post" action="{{ url('liff/create_shipment_step4') }}">
	
    	<div class="panel panel-warning panel-small product-declare" style="margin-bottom: 10px;">
  			<div class="panel-body bg-light">

  				<label for="type0" class=" form-control-label">ประเภทสินค้า</label>
        		<input type="text" id="type_0" name="category[]" class="form-control required" placeholder="declaration" required />
        		
        		<label for="qty0" class=" form-control-label">จำนวน</label>
        		<input type="number" id="qty_0" name="amount[]" min="0" class="form-control required" placeholder="qty." required />
        		
        		<label for="value0" class=" form-control-label">มูลค่ารวม (บาท)</label>
        		<input type="number" id="value_0" name="value[]" min="0" class="form-control required" placeholder="total declare value" required />

  			</div>
		</div>
		
		<div id="declare_list"></div>
		
		<div class="text-center">
        	<button type="button" class="btn btn-link text-secondary small" onclick="addProduct()">+ เพิ่มประเภทสินค้า</button>
        </div>

    	<div id="submit_form" class="row">
    		<div class="col col-12 ">
        		<button type="submit" id="submit" class="btn bg-orange btn-success btn-block btn-lg large border-0">ต่อไป</button>
            	<button type="button" class="btn btn-light btn-block btn-sm border-0" style="font-size:14px;margin-top: 10px;" onclick="history.back();">ย้อนกลับ</button>
        	</div>
        </div>

	</form>	
   	</div>

</div>

<script type="text/javascript">
<!--
$(window).on('load',function(){
	autocompleteHS('new_type');
});
function checkProductEmpty(){
	
	var productCount = $(".product-declare").length;

	if(productCount == 0){
		$("#declare_list").html('<p class="small text-danger text-center"><i>คุณยังไม่ได้เพิ่มประเภทสินค้า</i></p>');
		$("#submit").addClass("disabled");
	}else{
		$("#declare_list .text-danger").remove();
		$("#submit").removeClass("disabled");
	}
}

function addProduct(){

	var productCount = $(".product-declare").length;

	var content = '';
 	content += '<div id="product' + productCount + '" class="panel panel-warning panel-small product-declare" style="margin-bottom: 10px;">';
 	content += '  <div class="panel-body bg-light" >';
 	content += '    <div style="position: absolute;top:10px;right: 10px;"><i class="fa fa-trash-o" onclick="deleteProduct(' + productCount + ')"></i></div>';
 	content += '    <label for="type_' + productCount + '" class=" form-control-label">ประเภทสินค้า</label>';
 	content += '    <input type="text" name="category[]" id="type_' + productCount + '" class="form-control" placeholder="declaration" />';
 	content += '    <label for="new_qty" class=" form-control-label">จำนวน</label>';
 	content += '    <input type="number" name="amount[]" min="0" id="qty_' + productCount + '" class="form-control" placeholder="qty." />';
 	content += '    <label for="new_value" class=" form-control-label">มูลค่ารวม (บาท)</label>';
 	content += '    <input type="number" name="value[]" min="0" id="value_' + productCount + '" class="form-control" placeholder="total declare value" />';
 	content += '  </div>';
 	content += '</div>';

	$("#declare_list").append(content);
	
	autocompleteHS('type_'+productCount);
	checkProductEmpty();
	
}
function deleteProduct(cnt){
	if(confirm('ต้องการลบประเภทสินค้านี้ใช่หรือไม่')){
    	$("#declare_list #product"+cnt).fadeOut(250, function(){ $target.remove(); });
    	checkProductEmpty();
	}
}
function autocompleteHS(elemId){

	$('#'+elemId).autocomplete({
        minLength: 0,
        source: function( request, response ) {

          $.ajax({
            url: "{{ url('/liff/get_hscodes') }}",
            type: "POST",
            dataType: "json",
            data: {
              term : request.term
            },
            success: function(data) {
            	console.log(data);
				var array = $.map(data, function (item) { //alert(array);
                    return {
                      label: item['desc'],
                      value: item['desc'],
                      data : item
                    }
                });
              	response(array);
              	
            }
          });
        },
        select: function( event, ui ) {
           	var data = ui.item.data;   
    		$(this).val(data.desc);
        }
      });
}
-->
</script>
@endsection