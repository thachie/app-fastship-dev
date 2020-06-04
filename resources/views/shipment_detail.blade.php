@extends('layout')
@section('content')
<?php 

    $DeclareTypes = explode(";",$ShipmentDetail['ShipmentDetail']['DeclareType']);
    if($DeclareTypes [sizeof($DeclareTypes)-1] == ""){
        unset($DeclareTypes [sizeof($DeclareTypes)-1]);
    }
    $DeclareQtys = explode(";",$ShipmentDetail['ShipmentDetail']['DeclareQty']);
    if($DeclareQtys [sizeof($DeclareQtys)-1] == ""){
    	unset($DeclareQtys [sizeof($DeclareQtys)-1]);
    }
    $DeclareValues = explode(";",$ShipmentDetail['ShipmentDetail']['DeclareValue']);
    if($DeclareValues [sizeof($DeclareValues)-1] == ""){
        unset($DeclareValues [sizeof($DeclareValues)-1]);
    }
    
    if($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported"){
    	$ShipmentStatus = FT::translate('status.shipment.status1');
    	$stepStatus1 = "active";
    	$stepStatus2 = "disabled";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "Created"){
    	$ShipmentStatus = FT::translate('status.shipment.status2');
    	$stepStatus1 = "complete";
    	$stepStatus2 = "active";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "ReadyToShip" || $ShipmentDetail['Status'] == "Verify"){
    	$ShipmentStatus = FT::translate('status.shipment.status17');
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "active";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "Sent"){
    	$ShipmentStatus = FT::translate('status.shipment.status26');
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "complete";
    	$stepStatus4 = "active";
    }else if($ShipmentDetail['Status'] == "Cancelled"){
    	$ShipmentStatus = FT::translate('status.shipment.status5');
    	$stepStatus1 = "disabled";
    	$stepStatus2 = "disabled";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else{
    	$ShipmentStatus = $ShipmentDetail['Status'];
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "complete";
    	$stepStatus4 = "active";
    }
?>
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-12"><h2>{!! FT::translate('shipment_detail.heading') !!}: <?php echo $ShipmentDetail['ID'];?></h2></div>
    </div>
       
    @if(sizeof($cases)>0)
	@foreach($cases as $case)
	<div class="alert m-t-20 mt-b-20 text-left bg-white alert-case alert-{{ strtolower($case['Priority']) }}">
    	<div class="text-center alert-left">
    		<div class="case">Case #{{ $case['ID'] }}</div>
    	</div>
    	<div class="alert-detail">
    		@if($case['IsPrivate'] == 1)
    		<span class=""><strong>{{ $case['Category'] }}</strong> | </span>
    		<span class="small">by Fastship {{ date('d/m H:i',strtotime($case['CreateDate'])) }}</span>
    		@else
    		<span class=""><strong>{{ $case['Detail'] }}</strong> | </span>
    		<span class="small">on {{ date('d/m H:i',strtotime($case['CreateDate'])) }}</span>

    		@if(sizeof($case['Replies']) > 0)
    		@foreach($case['Replies'] as $reply)
    		@if(strstr($reply['Detail'],"ปรับปรุง <b>สถานะ</b>") == FALSE && strstr($reply['Detail'],"ปรับปรุงสถานะ") == FALSE && strstr($reply['Detail'],"ปรับปรุง Case") == FALSE)
	
    		<hr style="margin: 5px;">
    		@if($reply['CustomerId'] == session('customer.id'))
    		<div class="small" style="margin-left: 20px;">{{ $reply['Detail'] }} | by {{ session('customer.name') }} {{ date('d/m H:i',strtotime($reply['CreateDate'])) }}</div>
    		@else
    		<div class="small" style="margin-left: 20px;">{{ $reply['Detail'] }} | by Fastship {{ date('d/m H:i',strtotime($reply['CreateDate'])) }}</div>
    		@endif
    		
    		@endif
    		@endforeach
    		@endif
    		
    		@endif		 
    	</div>
    	@if($case['IsPrivate'] == 0)
    	<div class="alert-reply">
    	<form id="case_form" name="case_form" class="form-horizontal" method="post" action="{{url ('/case/createreply')}}">
    		
    		{{ csrf_field() }}	
    			
    		<input type="hidden" name="case_id" value="{{ $case['ID'] }}" />

    		<textarea class="form-control" name="detail" placeholder="Reply here" required></textarea>
    		<button type="submit" class="btn btn-sm btn-primary" style="position: absolute;right: 20px;top: 20px;" >Send</button> 
    		
    	</form>	
    	</div>
    	@endif
    	<div class="alert-right text-center ">
    		<div class="circle-status alert-status-{{ strtolower($case['Status']) }}"></div>
    		<div class="small">Status: {{ $case['Status'] }}</div>
    	</div>
    </div>
	@endforeach
	@endif

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">{!! FT::translate('shipment_detail.panel.heading1') !!}ข้อมูลผู้รับ</div>
                <div class="panel-body ship-detail">
                
                	<h4>{{ $ShipmentDetail['ReceiverDetail']['Firstname'] }} {{ $ShipmentDetail['ReceiverDetail']['Lastname'] }}</h4>
                	<div class="text-info"><i class="fa fa-phone"></i> <b>{{ $ShipmentDetail['ReceiverDetail']['PhoneNumber'] }}</b></div>
                	<div class="text-dark"><i class="fa fa-envelope"></i> <b>{{ $ShipmentDetail['ReceiverDetail']['Email'] }}</b></div>
                	<div class="text-secondary">
                		<i class="fa fa-home"></i> 
                		{{ $ShipmentDetail['ReceiverDetail']['AddressLine1'] }} {{ $ShipmentDetail['ReceiverDetail']['AddressLine2'] }}
                    	{{ $ShipmentDetail['ReceiverDetail']['City'] }} {{ $ShipmentDetail['ReceiverDetail']['State'] }} 
                    	{{ $ShipmentDetail['ReceiverDetail']['Postcode'] }} {{ $countries[$ShipmentDetail['ReceiverDetail']['Country']] }} 
                	</div>
                    <div class="clearfix"></div>
                    
                    @if($ShipmentDetail['Reference'] != "")
	                    <div class="text-center">Reference: {{ $ShipmentDetail['Reference'] }}</div>
	                    <div class="clearfix"></div>
                    @endif
                    
                    @if(trim($ShipmentDetail['Remark']) != "")
	                    <div class="text-center">{{ $ShipmentDetail['Remark'] }}</div>
	                    <div class="clearfix"></div>
                    @endif


                </div>
            </div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">{!! FT::translate('shipment_detail.panel.heading2') !!}รายละเอียดพัสดุ</div>
                <div class="panel-body ship-detail">
                
                	<table class="table table-hover small">
                        <thead>
                            <tr>
                                <th scope="col">{!! FT::translate('label.declare_type') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_qty') !!}</th>
                                <th scope="col">{!! FT::translate('label.declare_value') !!}</th>
                            </tr>
                        </thead>
                        <tbody id="product_table">
                            @if(sizeof($DeclareTypes) > 0)
                            @if(is_array($DeclareTypes))
                            @foreach($DeclareTypes as $key => $Type)
                            <tr>
                                <td>{{ $Type }}</td>
                                <td>{{ isset($DeclareQtys[$key])?($DeclareQtys[$key]):"1" }}</td>
                                <td>{{ isset($DeclareValues[$key])?($DeclareValues[$key]):"-" }}</td>
                            </tr>
                            @endforeach
                           	@endif
                           	@endif
                            
                        </tbody>
                    </table>
                    
                </div>
            </div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">ข้อมูลพัสดุ</div>
                <div class="panel-body ship-detail">
                
                	<div class="col-md-12 col-xs-12 text-center no-padding"> 
                        <img src="../images/agent/{{ $ShipmentDetail['ShipmentDetail']['ShippingAgent'] }}.gif" style="max-width: 100px;"/>
                    </div>
                    <br /><hr /><br />
                    

                    <h5 style="border-bottom: 1px solid #eee;">น้ำหนักที่กรอก</h5>
                    <div class="col-md-6 col-xs-8 text-right sumresult">
                    	ลูกค้ากรอก (กรัม)
                    </div>
                    <div class="col-md-6 col-xs-4 text-right text-info sumresult">
                    	{{ number_format($ShipmentDetail['ShipmentDetail']['CustomerWeight'],0) }}
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-md-6 col-xs-8 text-right sumresult">
                    	หลังตรวจสอบ (กรัม)
                    </div>
                    <div class="col-md-6 col-xs-4 text-right text-primary sumresult">
                    @if(!in_array($ShipmentDetail['Status'],array("Pending","Created","Verify")))
                    	{{ number_format($ShipmentDetail['ShipmentDetail']['ActualWeight'],0) }}
                    @else
                    	-
                    @endif
                    </div>
                    <div style="clear: both;"></div>
                    
                    <h5 style="border-bottom: 1px solid #eee;">ขนาดที่กรอก</h5>
                    <div class="col-md-6 col-xs-8 text-right sumresult">
                    	ลูกค้ากรอก (ซม.)
                    </div>
                    <div class="col-md-6 col-xs-4 text-right text-primary sumresult">
                    @if($ShipmentDetail['ShipmentDetail']['CustomerWidth'] != "")
                    	{{ $ShipmentDetail['ShipmentDetail']['CustomerWidth']."×".$ShipmentDetail['ShipmentDetail']['CustomerLength']."×".$ShipmentDetail['ShipmentDetail']['CustomerHeight'] }}
                    @else
                    	-
                    @endif
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-md-6 col-xs-8 text-right">
                    	น้ำหนักปริมาตร (กรัม)
                    </div>
                    <div class="col-md-6 col-xs-4 text-right">
                    @if($ShipmentDetail['ShipmentDetail']['CustomerWidth'] != "")
                    	{{ number_format($ShipmentDetail['ShipmentDetail']['CustomerWidth']*$ShipmentDetail['ShipmentDetail']['CustomerLength']*$ShipmentDetail['ShipmentDetail']['CustomerHeight']/5,0) }}
                    @else
                    	-
                    @endif
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-md-6 col-xs-8 text-right sumresult">
                    	หลังตรวจสอบ (ซม.)
                    </div>
                    <div class="col-md-6 col-xs-4 text-right text-primary sumresult">
                    @if(!in_array($ShipmentDetail['Status'],array("Pending","Created","Verify")))
                        @if($ShipmentDetail['ShipmentDetail']['Width'] != "")
                        	{{ $ShipmentDetail['ShipmentDetail']['Width']."×".$ShipmentDetail['ShipmentDetail']['Length']."×".$ShipmentDetail['ShipmentDetail']['Height'] }}
                        @else
                        	-
                        @endif
                    @else
                    	-
                    @endif
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-md-6 col-xs-8 text-right">
                    	น้ำหนักปริมาตร (กรัม)
                    </div>
                    <div class="col-md-6 col-xs-4 text-right">
                    @if(!in_array($ShipmentDetail['Status'],array("Pending","Created","Verify")))
                    	{{ number_format($ShipmentDetail['ShipmentDetail']['Width']*$ShipmentDetail['ShipmentDetail']['Length']*$ShipmentDetail['ShipmentDetail']['Height']/5,0) }}
                    @else
                    	-
                    @endif
                    </div>
                    <div style="clear: both;"></div>
                    
                    <h5 style="border-bottom: 1px solid #eee;">ค่าส่ง</h5>
                    <div class="col-md-6 col-xs-8 text-right sumresult">
                    	ลูกค้าสร้างมา (บาท)
                    </div> 
                    <div class="col-md-6 col-xs-4 text-right text-info sumresult">
                    	{{ number_format($ShipmentDetail['ShipmentDetail']['CustomerRate'],0) }}
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-md-6 col-xs-8 text-right sumresult">
                    	หลังตรวจสอบ (บาท)
                    </div>
                    <div class="col-md-6 col-xs-4 text-right text-primary sumresult">
                    @if(!in_array($ShipmentDetail['Status'],array("Pending","Created","Verify")))
                    	{{ number_format($ShipmentDetail['ShipmentDetail']['ShippingRate'],0) }}
                    @else
                    	-
                    @endif
                    </div>
                    <div style="clear: both;"></div>

                </div>
            </div>

        </div>
        <div class="col-md-6">
        	<div class="panel panel-primary">
                <div class="panel-body">
                	<h2>{!! FT::translate('label.status') !!}: <?php echo $ShipmentStatus; ?></h2>
                	
                	<div class="bs-wizard dot-no-padding" style="border-bottom:0;">
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus1; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status1') !!}</p>
	                    </div> 
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus2; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status2') !!}</p>
	                    </div>
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus3; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status17') !!}</p>
	                    </div>    
	                    <div class="col-xs-3 bs-wizard-step <?php echo $stepStatus4; ?>">
		                    <div class="progress"><div class="progress-bar"></div></div>
		                    <a href="#" class="bs-wizard-dot"></a>
		                  	<p class="text-center">{!! FT::translate('status.shipment.status26') !!}</p>
	                    </div>     
                	</div>
                	<div class="clearfix"></div><br />
                	
                	
                	
                    <?php if( !($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported" || $ShipmentDetail['Status'] == "Quotation" || $ShipmentDetail['Status'] == "Created")): ?>
                    	<h2>{!! FT::translate('shipment_detail.panel.heading3') !!}</h2>
                        <h4>{!! FT::translate('label.tracking') !!} <span class="orange">{{ $ShipmentDetail['ShipmentDetail']['Tracking'] }}</span></h4>
                        <div class="timeline timeline-single-column">
                            <?php 
                            if(isset($tracking_data['Events']) && sizeof($tracking_data['Events'])>0):
                            $descEvents = $tracking_data['Events'];
                            krsort($descEvents);
                            foreach($descEvents as $event):
                            if($event['Status'] == "delivered"){
                                $css = "success";
                            }else if($event['Status'] == "in_transit"){
                                $css = "info";
                            }else{
                                $css = "warning";
                            }
                            ?>
                            <div class="timeline-item <?php echo $event['Status']; ?>">
                                <div class="timeline-point timeline-point-default">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="timeline-event upgrade timeline-event-<?php echo $css; ?>">
                                    <div class="timeline-heading">
                                        <h4><?php echo $event['Description']; ?></h4>
                                    </div>
                                    <div class="timeline-body">
                                    
                                        <p><?php echo (isset($trackingStatus[$event['Status']]))?$trackingStatus[$event['Status']]:$event['Status']; ?> <?php echo ($event['Location'])?"at ".$event['Location']:""; ?></p>
            
                                    </div>
                                    <div class="timeline-footer text-right">
                                        <?php echo date("d/m/Y H:i:s",strtotime($event['Datetime'])); ?>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            endforeach;
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php if($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported"  || $ShipmentDetail['Status'] == "Quotation"): ?>
        <form id="delete_form" class="form-horizontal" method="post" action="{{url ('shipment/cancel')}}">
		    {{ csrf_field() }}
		    <input type="hidden" name="shipmentId" value="<?php echo $ShipmentDetail['ID'];?>" />
		</form>
	    <div class="col-md-12 text-center">
	    	<a href="javascript:cancelShipment(<?php echo $ShipmentDetail['ID'];?>);"><i class="fa fa-trash-o"></i> {!! FT::translate('shipment_detail.cancel_link') !!}</a>
	    </div>
	    <?php endif; ?>
	    <div class="clearfix"></div>
    	<br />
    
    </div>
    <div class="clearfix"></div>
      
    @if(sizeof($cases) == 0)
    <div class="row">
	    <div class="col-md-6 col-md-offset-3">
	    
	    	<form id="case_form" name="case_form" class="form-horizontal" method="post" action="{{url ('/case/create')}}">
	    		
	    		{{ csrf_field() }}
	    		
	    		<input type="hidden" name="ref_id" value="{{ $ShipmentDetail['ID'] }}" />

			    <div class="panel panel-primary">
					<div class="panel-heading"><img src="{{ url('images/fasty_help.png') }}" style="max-height:40px;" /> <span style="line-height:40px;">{!! FT::translate('button.sendusmsg') !!}</span></div>
			        <div class="panel-body">

	                	<div class="col-md-12">
	                        <label for="category" class="col-12 control-label">ประเภท Case</label>
	                        
	                        <select name="category" class="form-control required" required>
	                        	<option value="">--- กรุณาเลือก ---</option>
	                    		<option>ปัญหาการเข้ารับพัสดุ</option>
	                    		<option>ติดตามสถานะ Tracking</option>
	                    		<option>สอบถามรายละเอียดยอดชำระ</option>
	                    		<option>ปัญหาการจ่ายเงิน</option>
	                    		<option>คืนเงิน / สถานะการคืนเงิน</option>
	                    		<option>คืนสินค้า/ สถานะการคืนสินค้า</option>
	                    		<option>ขอเอกสาร หัก ณ ที่จ่าย</option>
	                    		<option>อื่นๆ</option>
	                    	</select>
	                    	
	                    </div>
	                    <div class="col-md-12">
		                    <label for="detail" class="col-12 control-label">รายละเอียด</label>
		                    <textarea class="form-control required" rows="5" name="detail" id="detail" required>{{ old('detail','') }}</textarea>
		                </div>
		                <div class="clearfix"></div>
		                <br />

		                <div class="text-center"><button type="submit" name="submit" class="btn btn-lg btn-primary">{!! FT::translate('button.confirm') !!}</button></div>
		            
		            </div>
				</div>
			</form> 
	    </div>
	    
	</div>
	<div class="clearfix"></div>
    <br />
	@endif

</div>

<script type="text/javascript">
function cancelShipment(shipment_id){

	if(confirm("{!! FT::translate('confirm.delete_shipment') !!}")){
		$("#delete_form").submit();
    }
            
}
</script>

@endsection