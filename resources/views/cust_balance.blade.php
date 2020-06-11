@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">

        @include('left_account_menu')
        
    	<div class="col-md-10">

			<h2>{!! FT::translate('mybalance.heading') !!}</h2>
			<hr />
		
			<div class="panel panel-primary">
		        <div class="panel-body payment-summary">
		        
		        	<div class="col-md-6 col-xs-12 sumbox">
						<h4 style="margin-bottom: 0;">Store Credit</h4>
						<h1 class="text-success">฿{{ $storecredit }} <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#requestWithdraw" style="margin-top: -15px;">ถอนเงิน</button></h1>
					</div>
					
					<hr class="visible-xs" />

					<div class="col-md-3 col-xs-12 sumbox">
						<h4>บัญชีคืนเงิน</h4>
						@if( $customer_data['RefundBank'] != null && $customer_data['RefundAccount']  != null )
						<div>{{ $customer_data['RefundBank'] }} ({{ $customer_data['RefundBranch'] }}): {{ $customer_data['RefundAccount'] }} - {{ $customer_data['RefundName'] }}</div>
						@else
						<div class="text-danger text-center">ยังไม่ระบุบัญชีคืนเงิน</div>
						@endif
						<div class="text-center small" style="margin-top:15px;"><a href="#" data-toggle="modal" data-target="#editRefundAccount" ><i class="fa fa-edit" title="{!! FT::translate('button.edit') !!}"></i> {!! FT::translate('button.edit') !!}</a></div>
					</div>
					
					<hr class="visible-xs" />
					
					<div class="col-md-3 col-xs-12">
						<h4>บัตรเครดิต</h4>
						<div>คุณมีบัตรเครดิต {{ sizeof($credit_cards) }} บัตร</div>
						<div class="text-center small" style="margin-top:15px;"><a href="#" data-toggle="modal" data-target="#editCreditCard" ><i class="fa fa-edit" title="{!! FT::translate('button.edit') !!}"></i> {!! FT::translate('button.edit') !!}</a></div>
					</div>

				</div>
			</div>

			<div class="panel panel-primary">
				<div class="panel-heading">ประวัติการเงิน</div>
		        <div class="panel-body">
		        	<table id="statements" class="table table-hover table-striped">
                        <thead>
                        	<tr>
                        		<td>{!! FT::translate('label.date') !!}</td>
                        		<td>ประเภท | รายละเอียด</td>
                        		<td class="hidden-xs">หมายเลขอ้างอิง</td>
                        		<td>จำนวน (บาท)</td>
                        		<td class="hidden-xs">{!! FT::translate('label.status') !!}</td>
                        	</tr>
                        </thead>
                        <tbody>
                        @if(sizeof($statements) > 0)
                        @foreach($statements as $statement)
                        	<tr>
                        		<td>{{ $statement['CreateDate'] }}</td>
                        		<td>
                        		@if($statement['Amount'] < 0)
                        			<i class="fa fa-money text-info"></i> 
                        		@else
                        			<i class="fa fa-plus-circle text-success"></i>
                        		@endif
                        		
                    			{{ isset($payment_mapping[$statement['Payment']]) ? $payment_mapping[$statement['Payment']]:$statement['Payment'] }}
                    			
                    			@if( in_array($statement['Payment'],array("QR","Credit_Card","Bank_Transfer","Cash","Invoice")) )
                    				#{{ $statement['PickupId'] }}
                    			@endif

                        		</td>
                        		<td class="hidden-xs"><a href="#">{{ $statement['PickupId'] }}</a></td>
                        		<td>
                        		@if($statement['Amount'] < 0)
                        			<span class="text-danger">{{ $statement['Amount'] }}</span>
                        		@else
                        			<span class="text-success">+{{ $statement['Amount'] }}</span>
                        		@endif
                        		</td>
                        		<td class="hidden-xs">{{ $statement['Status'] }}</td>
                        	</tr>
                       	@endforeach
                       	@else
                       		<tr>
                        		<td colspan="5" class="text-center">ไม่มีประวัติการเงิน</td>
                        	</tr>
                       	@endif
                        </tbody>
                   </table>
                </div>
			</div>

    	</div>
	</div>
</div>

<!-- Modals -->
<div id="requestWithdraw" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<form name="request_form" class="form-horizontal" method="post" action="{{url ('/credit/withdraw')}}">
    
        {{ csrf_field() }}
        
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">ถอนเงิน (Withdraw)</h4>
          </div>
          <div class="modal-body row">

    		@if( $customer_data['RefundBank'] != null && $customer_data['RefundAccount']  != null )

                <p>กรอกจำนวนเงินที่ต้องการขอคืน ทางเราจะโอนคืนไปยังบัญชี {{ $customer_data['RefundBank'] }} {{ $customer_data['RefundAccount'] }} ให้เมื่อได้รับข้อมูลภายใน 5 วันทำการค่ะ</p>

        		<div class="col col-md-4 col-xs-12">
        			<label for="refund_amount" class="col-12 control-label">จำนวนเงิน (บาท)</label>
        			<input type="number" name="amount" id="refund_amount" class="form-control required" required value="{{ $storecredit }}" />
        		</div>

    		@else

    			<p class="text-danger text-center">กรุณากรอกข้อมูลบัญชีคืนเงินก่อน</p>

    		@endif

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
    
    </form>

  </div>
</div>

<div id="editRefundAccount" class="modal fade" role="dialog">
  <div class="modal-dialog">
  
	<form name="account_form" class="form-horizontal" method="post" action="{{url ('/credit/update_refund')}}">
    
    {{ csrf_field() }}
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">แก้ไขบัญชีคืนเงิน (Edit Refund Account)</h4>
      </div>
      <div class="modal-body row">
        <p>กรอกเลขบัญชีสำหรับรับเงินคืน</p>

		<div class="col col-md-5 col-xs-12">
			<label for="refund_bank" class="col-12 control-label">ธนาคาร</label>
			<select name="refund_bank" id="refund_bank" class="form-control required" required>
				<option value="">-- กรุณาเลือกธนาคาร --</option>
				<option value="SCB">ไทยพาณิชย์ (SCB)</option>
                <option value="Kbank">กสิกรไทย (Kbank)</option>
                <option value="KTB">กรุงไทย (KTB)</option>
                <option value="BBL">กรุงเทพ (BBL)</option>
                <option value="BAY">กรุงศรี (BAY)</option>
                <option value="Thanachart">ธนชาติ (Thanachart)</option>
                <option value="TMB">ทหารไทย (TMB)</option>
                <option value="Kiatnakin">เกียรตินาคิน (Kiatnakin)</option>
                <option value="Standard Chartered">แสตนดาร์ดชาร์เตอร์ด (Standard Chartered)</option>
                <option value="UOB">ยูโอบี (UOB)</option>
                <option value="TISCO">ทิสโก้ (TISCO)</option>
                <option value="CIMB">ซีไอเอ็มบี (CIMB)</option>
                <option value="ICBC">ไอซีบีซี (ICBC)</option>
                <option value="BAAC">ธนาคารเพื่อการเกษตร (BAAC)</option>
                <option value="GSB">ธนาคารออมสิน (GSB)</option>
                <option value="GHB">ธนาคารอาคารสงเคราห์ (GHB)</option>
                <option value="CITIBANK">ซิตี้แบงก์ (CITIBANK)</option>
			</select>
		</div>
		<div class="col col-md-7 col-xs-12">
			<label for="refund_branch" class="col-12 control-label">สาขา</label>
			<input type="text" name="refund_branch" id="refund_branch" maxlength="100" class="form-control required" required />
		</div>
		<div class="col col-md-5 col-xs-12">
			<label for="refund_account" class="col-12 control-label">เลขที่บัญชี</label>
			<input type="text" name="refund_account" id="refund_account" maxlength="100" class="form-control required" required />
		</div>
		<div class="col col-md-7 col-xs-12">
			<label for="refund_name" class="col-12 control-label">ชื่อบัญชี</label>
			<input type="text" name="refund_name" id="refund_name" maxlength="100" class="form-control required" required />
		</div>
		<div class="clearfix" style="clear: both;"></div><br />
		<div class="col col-md-12 col-xs-12 small text-secondary">
			*กรุณาตรวจสอบข้อมูลที่กรอกให้ถูกต้อง หากต้องการแก้ไขกรุณาติดต่อฝ่ายบริการลูกค้า
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
    
    </form>

  </div>
</div>
<div id="editCreditCard" class="modal fade" role="dialog">
  <div class="modal-dialog">
  
	<form id="creditcard_form" name="creditcard_form" class="form-horizontal" method="post" action="{{url ('/credit/add_creditcard')}}">
    
    {{ csrf_field() }}
    
    <div id="token_errors"></div>
	<input type="hidden" name="command" value="collect-card" />
	<input type="hidden" name="back_url" value="" />
	<input type="hidden" name="backToaddCredit" value="" />
	<input type="hidden" name="omise_token">
	<input type="hidden" id="card_number" name="card_number">
	<input type="hidden" id="cvv_number" name="cvv_number">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">แก้ไขข้อมูลบัตรเครดิต (Edit Credit Card)</h4>
      </div>
      <div class="modal-body row">
      
      	<div class="col-md-12 col-xs-12">
			<h3>{!! FT::translate('myaccount.panel.heading4') !!}</h3>

			@if(sizeof($credit_cards) > 0)
            @foreach($credit_cards as $card)
			<div class="col-md-6">
				<div class=" block-primary" style="position: relative;">
					<span class="creditbar"></span>
					<span class="close"><a href="javascript:deleteCreditCard('{{ $card['OmiseId'] }}');"><i class="fa fa-trash-o"></i></a></span>
					<h5 class="accountno">XXXXX-XXX-{{ $card['LastDigits'] }}</h5>
					<h6>{{ $card['CardName'] }}</h6>
					<p>{{ $card['Bank'] }}</p>
				</div>
			</div>
			@endforeach
            @endif

    	</div>
    	
    	<hr />
    				
    	<div class="col-md-12 col-xs-12">
        	<h4>เพิ่มบัตรเครดิตใหม่</h4>
		</div>
		<div class="col-md-12 col-xs-12">
        	<label for="holder_name" class="col-12 control-label">{!! FT::translate('label.cardname') !!}</label>
        	<input type="text" class="form-control required" name="holder_name" data-omise="holder_name" id="holder_name" required />
        </div>
		<div class="col-md-12 col-xs-12">
        	<label for="number" class="col-12 control-label">{!! FT::translate('label.cardnumber') !!}</label>
        	<input type="text" class="form-control required" id="number" name="number" data-omise="number" value="" maxlength="16" required placeholder="Card Number">
        </div>
        <div class="col-md-4 col-xs-12">
        	<label for="expiration_month" class="col-12 control-label">{!! FT::translate('label.expire_date') !!}</label>
        	<select name="expiration_month" data-omise="expiration_month" class="form-control required" required>
        		<option value="">---- Month ----</option>
        		<option>01</option>
        		<option>02</option>
        		<option>03</option>
        		<option>04</option>
        		<option>05</option>
        		<option>06</option>
        		<option>07</option>
        		<option>08</option>
        		<option>09</option>
        		<option>10</option>
        		<option>11</option>
        		<option>12</option>
        	</select>
        </div>
        <div class="col-md-4 col-xs-12">
        	<label for="expiration_year" class="col-12 control-label">&nbsp;</label>
        	<select name="expiration_year" data-omise="expiration_year" class="form-control required" required>
        		<option value="">---- Year ----</option>
        		<option>2020</option>
        		<option>2021</option>
        		<option>2022</option>
        		<option>2023</option>
        		<option>2024</option>
        		<option>2025</option>
        		<option>2026</option>
        		<option>2027</option>
        		<option>2028</option>
        		<option>2029</option>
        		<option>2030</option>
        	</select>
        </div>
        <div class="col-md-4 col-xs-12">
        	<label for="security_code" class="col-12 control-label">{!! FT::translate('label.cvv') !!}</label>
        	<input type="text" id="security_code" name="security_code" data-omise="security_code"  class="form-control required" maxlength="4" required placeholder="CVV"/>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
    
    </form>
    <form id="delete_form" name="delete_form" method="post" action="{{url ('/credit/delete_creditcard')}}">	                        
    	{{ csrf_field() }}
    	<input type="hidden" name="card_id" />
    </form>

  </div>
</div>
<script src="https://cdn.omise.co/omise.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	
	$("#refund_bank").val("{{ $customer_data['RefundBank'] }}");
	$("#refund_account").val("{{ $customer_data['RefundAccount'] }}");
	$("#refund_branch").val("{{ $customer_data['RefundBranch'] }}");
	$("#refund_name").val("{{ $customer_data['RefundName'] }}");

	$('#statements').dataTable( {
    	pageLength: 50,
    	order: [[0, 'desc']],
    	searching: false,
    	lengthChange: false,
    	fixedHeader: true,
	}); 
});

Omise.setPublicKey("pkey_57wb6hkyv6qi2e4ft0k");
$("#creditcard_form").submit(function () {

	var form = $(this);

    document.getElementById('card_number').value = $("#number").val();
    document.getElementById('cvv_number').value = $("#security_code").val();
    
    // Disable the submit button to avoid repeated click.
    form.find("input[type=submit]").prop("disabled", true);
    
    // Serialize the form fields into a valid card object.
    var card = {
        "name": form.find("[data-omise=holder_name]").val(),
        "number": form.find("[data-omise=number]").val(),
        "expiration_month": form.find("[data-omise=expiration_month]").val(),
        "expiration_year": form.find("[data-omise=expiration_year]").val(),
        "security_code": form.find("[data-omise=security_code]").val()
    };
    
    // Send a request to create a token then trigger the callback function once
    // a response is received from Omise.
    //
    // Note that the response could be an error and this needs to be handled within
    // the callback.
    Omise.createToken("card", card, function (statusCode, response) {
    
    //if (response.object == "error" || !response.card.security_code_check) {
    if (response.object == "error" ) {
      // Display an error message.
      var message_text = "SET YOUR SECURITY CODE CHECK FAILED MESSAGE";
      if(response.object == "error") {
        message_text = response.message;
      }
      $("#token_errors").html(message_text);
    
      // Re-enable the submit button.
      form.find("input[type=submit]").prop("disabled", false);
      //form.find("button[type=submit]").prop("disabled", false);
      
    } else {
      // Then fill the omise_token.
      form.find("[name=omise_token]").val(response.id);
    
      // Remove card number from form before submiting to server.
      form.find("[data-omise=number]").val("");
      form.find("[data-omise=security_code]").val("");
    
      // submit token to server.
      form.get(0).submit();
    };
    });
    
    // Prevent the form from being submitted;
    return false;

});

function deleteCreditCard(id){
	$("#delete_form input[name=card_id]").val(id);
	$("#delete_form").submit();
}

</script>
@endsection