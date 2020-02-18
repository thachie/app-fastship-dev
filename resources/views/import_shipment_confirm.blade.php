@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">			
	    <div class="col-md-12">
	    	<div class="panel panel-primary">
				<div class="panel-heading">รายการพัสดุที่จะนำเข้า</div>
				<div class="panel-body">
					
					<table class="table table-stripe table-left small">
					<thead>
						<tr>
							<td width="2%">#</td>
							<td width="1%"></td>
							<td>ข้อมูลผู้รับ</td>
							<td width="10%">ประเทศปลายทาง</td>
							<td width="10%">น้ำหนักและขนาด</td>
							<td width="18%">รายละเอียดของที่ส่ง</td>
							<td width="25%">เลือกวิธีการส่ง</td>
						</tr>
					</thead>
					<tbody>
					<?php 
					if(is_array($upload_data) && sizeof($upload_data)>0):
					$cnt = 0;
					foreach($upload_data as $data):
					?>
					
						
						<tr id="import_form<?php echo $cnt; ?>">
							<td><?php echo ($cnt+1); ?></td>
							<td><i class="fa <?php echo $data['IconClass']; ?>" title="<?php echo $data['IconTitle']; ?>"></i></td>
							<td>
								<?php if(isset($data['Receiver_Company'])): ?>
									<?php echo $data['Receiver_Company']; ?><br />
								<?php endif; ?>
								<strong><?php echo $data['Receiver_Firstname'] . " " . $data['Receiver_Lastname']; ?></strong><br />
								Tel. <?php echo $data['Receiver_PhoneNumber']; ?><br />
								Email. <?php echo $data['Receiver_Email']; ?><br />
								<?php echo $data['Receiver_AddressLine1']; ?> <?php echo $data['Receiver_AddressLine2']; ?>
								<?php echo $data['Receiver_City']; ?> <?php echo $data['Receiver_State']; ?>
								<?php echo $data['Receiver_Postcode']; ?><br />
								<?php if(isset($data['Reference'])): ?>
								Ref: <?php echo $data['Reference']; ?>
								<?php endif; ?>
							</td>
							<td><?php echo isset($countries[$data['Receiver_Country']])?$countries[$data['Receiver_Country']]:$data['Receiver_Country']; ?></td>
							<td>
								<strong>น้ำหนัก: <?php echo $data['Weight']; ?></strong> กรัม<br />
								ขนาด: <?php echo $data['Width']; ?>x<?php echo $data['Height']; ?>x<?php echo $data['Length']; ?>
							</td>
							<td>
								<div class="col-md-12 no-padding"><?php echo $data['DeclareQty']; ?> x <?php echo $data['DeclareType']; ?> 
								 <?php echo $data['DeclareValue']; ?> บาท</div>
                            </td>
                            <td>
                            <form class="form-horizontal" method="post" action="{{url ('/shipment/import')}}">
			
								{{ csrf_field() }}
								
	                            <?php echo $data['ShippingAgent']; ?>
                            
                            	<input type="hidden" name="company" value="<?php echo $data['Receiver_Company']; ?>" />
								<input type="hidden" name="firstname" value="<?php echo $data['Receiver_Firstname']; ?>" />
								<input type="hidden" name="lastname" value="<?php echo $data['Receiver_Lastname']; ?>" />
								<input type="hidden" name="phonenumber" value="<?php echo $data['Receiver_PhoneNumber']; ?>" />
								<input type="hidden" name="email" value="<?php echo $data['Receiver_Email']; ?>" />
								<input type="hidden" name="address1" value="<?php echo $data['Receiver_AddressLine1']; ?>" />
								<input type="hidden" name="address2" value="<?php echo $data['Receiver_AddressLine2']; ?>" />
								<input type="hidden" name="city" value="<?php echo $data['Receiver_City']; ?>" />
								<input type="hidden" name="state" value="<?php echo $data['Receiver_State']; ?>" />
								<input type="hidden" name="postcode" value="<?php echo $data['Receiver_Postcode']; ?>" />
								<input type="hidden" name="country" value="<?php echo $data['Receiver_Country']; ?>" />
								<input type="hidden" name="weight" value="<?php echo $data['Weight']; ?>" />
								<input type="hidden" name="width" value="<?php echo $data['Width']; ?>" />
								<input type="hidden" name="height" value="<?php echo $data['Height']; ?>" />
								<input type="hidden" name="length" value="<?php echo $data['Length']; ?>" />
								<input type="hidden" name="category" value="<?php echo $data['DeclareType']; ?>" />
								<input type="hidden" name="amount" value="<?php echo $data['DeclareQty']; ?>" />
								<input type="hidden" name="value" value="<?php echo $data['DeclareValue']; ?>" />
								<input type="hidden" name="term" value="<?php echo $data['TermOfTrade']; ?>" />
								<input type="hidden" name="note" value="<?php echo $data['Remark']; ?>" />
								<input type="hidden" name="orderref" value="<?php echo $data['Reference']; ?>" />
                            
                            </form>
                            <?php if($data['IconClass'] == "fa-check-circle fa-success"): ?>

                            	<button type="button" class="btn btn-success btn-sm" onclick="importShipment(<?php echo $cnt; ?>)">นำเข้า</button>
                            	<button type="button" class="btn btn-danger btn-sm" onclick="cancelShipment(<?php echo $cnt; ?>)">ลบ</button>
                            
                            <?php endif; ?>
                            </td>
							
						</tr>
					
					<?php 
					$cnt++;
					endforeach;
					endif;
					?>
					</tbody>
					</table>
				</div>
	        </div>
	        
	        <div class="col-md-12 text-center">
<!-- 	        	<a href="/create_pickup"><button type="button" class="btn btn-success">จัดการพัสดุรอส่ง</button></a> -->
	        	<a href="/import_shipment">ยกเลิก</a>
	        </div>
	    </div>
	    
	</div>
</div>

<script type="text/javascript">

	function importShipment(cnt){

		var incart = parseInt($("#cart_cnt").text());
		
		if($("#import_form" + cnt + " [name=agent]").val() == "") return false;
		
		$.post("{{url ('shipment/import')}}",
		{
			_token: $("#import_form" + cnt + " [name=_token]").val(),
			company: $("#import_form" + cnt + " [name=company]").val(),
			firstname: $("#import_form" + cnt + " [name=firstname]").val(),
			lastname: $("#import_form" + cnt + " [name=lastname]").val(),
			phonenumber: $("#import_form" + cnt + " [name=phonenumber]").val(),
			email: $("#import_form" + cnt + " [name=email]").val(),
			address1: $("#import_form" + cnt + " [name=address1]").val(),
			address2: $("#import_form" + cnt + " [name=address2]").val(),
			city: $("#import_form" + cnt + " [name=city]").val(),
			state: $("#import_form" + cnt + " [name=state]").val(),
			postcode: $("#import_form" + cnt + " [name=postcode]").val(),
			country: $("#import_form" + cnt + " [name=country]").val(),
			weight: $("#import_form" + cnt + " [name=weight]").val(),
			width: $("#import_form" + cnt + " [name=width]").val(),
			height: $("#import_form" + cnt + " [name=height]").val(),
			length: $("#import_form" + cnt + " [name=length]").val(),
			category: $("#import_form" + cnt + " [name=category]").val(),
			amount: $("#import_form" + cnt + " [name=amount]").val(),
			value: $("#import_form" + cnt + " [name=value]").val(),
			term: $("#import_form" + cnt + " [name=term]").val(),
			note: $("#import_form" + cnt + " [name=note]").val(),
			orderref: $("#import_form" + cnt + " [name=orderref]").val(),
			agent: $("#import_form" + cnt + " [name=agent]").val(),
			source: 'FileImport',
		},function(data){

			if(data !== false){

				$("#cart_cnt").text(incart+1);
				$("#cart_cnt_mob").text(incart+1);
				
				$("#import_form" + cnt).fadeOut(500);
			}else{
				console.log("error");
			}
			console.log(data); return false;
	            
		},"json");
	}

	function cancelShipment(cnt){
		if(confirm("คุณต้องการยกเลิกพัสดุรายการนี้ใช่หรือไม่")){
			$("#import_form" + cnt).fadeOut(500);
		}
		return false;
	}
</script>

@endsection