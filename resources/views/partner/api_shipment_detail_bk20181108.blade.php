@extends('layout')
@section('content')
<?php 
//alert($ShipmentDetail);

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
    	$ShipmentStatus = "สร้างพัสดุแล้ว";
    	$stepStatus1 = "active";
    	$stepStatus2 = "disabled";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "Created"){
    	$ShipmentStatus = "เรียกรับพัสดุแล้ว";
    	$stepStatus1 = "complete";
    	$stepStatus2 = "active";
    	$stepStatus3 = "disabled";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "ReadyToShip"){
    	$ShipmentStatus = "เตรียมส่งออก";
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "active";
    	$stepStatus4 = "disabled";
    }else if($ShipmentDetail['Status'] == "Sent"){
    	$ShipmentStatus = "ส่งออกแล้ว";
    	$stepStatus1 = "complete";
    	$stepStatus2 = "complete";
    	$stepStatus3 = "complete";
    	$stepStatus4 = "active";
    }else if($ShipmentDetail['Status'] == "Cancelled"){
    	$ShipmentStatus = "ยกเลิก";
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
        <!--<div class="col-md-12"><h2>เลขรายการพัสดุ : <?php echo $ShipmentDetail['ID'];?></h2></div>-->
        <div class="col-md-12"><h2>รายละเอียด - ข้อมูลพัสดุ</h2></div>
    </div>
    <div class="row">
        <!-- Start Colume 2 -->
        <div class="col-md-6">
            <!-- Start 1 -->
            <div class="panel panel-primary">
                <div class="panel-heading">รายละเอียดพัสดุ</div>
                <div class="panel-body ship-detail">
                    <div class=" well" style="margin-bottom:0px;">
                	<div class="col-md-5 col-xs-5 text-right">ชื่อ-นามสกุล : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['Firstname'];?> <?php echo $ShipmentDetail['ReceiverDetail']['Lastname'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">เบอร์โทรศัพท์ : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['PhoneNumber'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">อีเมล์ : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['Email'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">ที่อยู่ : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['AddressLine1'];?><br /><?php echo $ShipmentDetail['ReceiverDetail']['AddressLine2'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">เขต : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['City'];?></div>
                    <div class="clearfix"></div>
                    <div class="col-md-5 col-xs-5 text-right">จังหวัด : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['State'];?></div>
                    <div class="clearfix"></div> 
                    <div class="col-md-5 col-xs-5 text-right">รหัสไปรษณีย์ : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['ReceiverDetail']['Postcode'];?></div>
                    <div class="clearfix"></div>  
                    <div class="col-md-5 col-xs-5 text-right">ประเทศ : </div>
                    <div class="col-md-7 col-xs-7"><?php echo $countries[$ShipmentDetail['ReceiverDetail']['Country']];?></div>
                    <div class="clearfix"></div>   
                    <?php if($ShipmentDetail['Remark'] != ""): ?>
	                    <div class="col-md-5 col-xs-5 text-right">หมายเหตุ : </div>
	                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['Remark'];?></div>
	                    <div class="clearfix"></div>
                    <?php endif; ?>
                    <?php if($ShipmentDetail['Reference'] != ""): ?>
	                    <div class="col-md-5 col-xs-5 text-right">หมายเลข eBay/Amazon Order : </div>
	                    <div class="col-md-7 col-xs-7"><?php echo $ShipmentDetail['Reference'];?></div>
	                    <div class="clearfix"></div>
                    <?php endif; ?>
                    </div>
                	<br />
                	
                	<!--<h3>รายการพัสดุทั้งหมด</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ประเภท</th>
                                <th scope="col">จำนวน</th>
                                <th scope="col">มูลค่า</th>
                            </tr>
                        </thead>
                        <tbody id="product_table">
                            <?php 
                            if(sizeof($DeclareTypes) > 0):
                            	if(is_array($DeclareTypes)):
                                    foreach($DeclareTypes as $key => $Type):
                                    	$dtype = (isset($declareTypes[$Type]))?$declareTypes[$Type]:$Type;
                            
                            ?>
                                    <tr>
                                        <td><?php echo $dtype; ?></td>
                                        <td><?php echo isset($DeclareQtys[$key])?($DeclareQtys[$key]):"1"; ?></td>
                                        <td><?php echo isset($DeclareValues[$key])?($DeclareValues[$key]):"-"; ?></td>
                                    </tr>
                            <?php 	
                            		endforeach;
                           		endif; 
                           	endif;
                           	?>
                            
                        </tbody>
                    </table>

                    <div class="col-md-4 col-xs-4 text-center no-padding"> 
                        <img src="/images/agent/<?php echo $ShipmentDetail['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width: 100px;"/>
                    </div>
                    <div class="col-md-8 col-xs-8"> 
                        <table class="table-dimension col-md-12 small text-left">
                        <thead>
                            <tr>
                                <td>น้ำหนัก (กรัม)</td>
                                <td class="hidden-xs">ขนาด (ซม.)</td>
                                <td>ค่าขนส่ง (บาท)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="sumresult"><?php echo number_format($ShipmentDetail['ShipmentDetail']['Weight'],0);?></span></td>
                                <td class="hidden-xs">
                                    <?php if($ShipmentDetail['ShipmentDetail']['Width'] != ""): ?>
                                    <span class="sumresult"><?php echo $ShipmentDetail['ShipmentDetail']['Width']." × ".$ShipmentDetail['ShipmentDetail']['Length']." × ".$ShipmentDetail['ShipmentDetail']['Height']; ?></span>
                                    <?php else: ?>
                                    <span class="sumresult">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="sumresult"><?php echo number_format($ShipmentDetail['ShipmentDetail']['ShippingRate'],0);?></span></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>-->
                </div>
            </div>
            <!-- End 1 -->
            <div class="clearfix"></div>
            <div style="padding-bottom: 10px;"></div>
            <!-- Start 2 -->
            <div class="panel panel-primary">
                <div class="panel-heading">ข้อมูลผู้ส่ง</div>
                <div class="panel-body row-no-padding">
                    <div class=" well" style="margin-bottom:0px;">
                        <div class="col-md-5 col-xs-5 text-right">ชื่อ-นามสกุล : </div>
                        <div class="col-md-7 col-xs-7">
                            <?=$ShipmentDetail['ReceiverDetail']['Custname'];?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- End 2 -->
        </div>

        <!-- End Colume 1 -->
        <!-- Start Colume 2 -->
        <div class="col-md-6">
            <!-- Start Tracking -->
            <div class="panel panel-primary">
                <div class="panel-heading">สถานะรายการพัสดุ</div>
                <div class="panel-body row-no-padding">
                    <h3>เลขรายการพัสดุ : <?php echo $ShipmentDetail['ID'];?></h3>
                    <h3>Status: <?php echo $ShipmentStatus; ?></h3>
                        <?php if( !($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported")): ?>
                        <h4>หมายเลขติดตามพัสดุ <span class="orange">{{ $ShipmentDetail['ShipmentDetail']['Tracking'] }}</span></h4>
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
                                    
                                        <p><?php echo $trackingStatus[$event['Status']]; ?> <?php echo ($event['Location'])?"at ".$event['Location']:""; ?></p>
            
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
                    <!--<p style="font-weight: bold; font-size: 20px;">TRACKING : </p>
                    <p style="font-weight: bold; font-size: 20px;">STATUS :</p>-->
                
                    <!--<img src="{{ url('images/qr_code.png') }}" alt="Smiley face" height="200" width="200">--> 
                    <?php
                    echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
                    ?>
                </div>
            </div>
            <!-- End Tracking -->

            <!-- Start Shipment Detail -->
            <div class="panel panel-primary">
                <div class="panel-heading">รายการพัสดุทั้งหมด</div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ประเภท</th>
                                <th scope="col">จำนวน</th>
                                <th scope="col">มูลค่า</th>
                            </tr>
                        </thead>
                        <tbody id="product_table">
                            <?php 
                            if(sizeof($DeclareTypes) > 0):
                                if(is_array($DeclareTypes)):
                                    foreach($DeclareTypes as $key => $Type):
                                        $dtype = (isset($declareTypes[$Type]))?$declareTypes[$Type]:$Type;
                            
                            ?>
                                    <tr>
                                        <td><?php echo $dtype; ?></td>
                                        <td><?php echo isset($DeclareQtys[$key])?($DeclareQtys[$key]):"1"; ?></td>
                                        <td><?php echo isset($DeclareValues[$key])?($DeclareValues[$key]):"-"; ?></td>
                                    </tr>
                            <?php   
                                    endforeach;
                                endif; 
                            endif;
                            ?>
                            
                        </tbody>
                    </table>

                    <div class="col-md-4 col-xs-4 text-center no-padding"> 
                        <img src="/images/agent/<?php echo $ShipmentDetail['ShipmentDetail']['ShippingAgent'];?>.gif" style="max-width: 100px;"/>
                    </div>
                    <div class="col-md-8 col-xs-8"> 
                        <table class="table-dimension col-md-12 small text-left">
                        <thead>
                            <tr>
                                <td>น้ำหนัก (กรัม)</td>
                                <td class="hidden-xs">ขนาด (ซม.)</td>
                                <td>ค่าขนส่ง (บาท)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="sumresult"><?php echo number_format($ShipmentDetail['ShipmentDetail']['Weight'],0);?></span></td>
                                <td class="hidden-xs">
                                    <?php if($ShipmentDetail['ShipmentDetail']['Width'] != ""): ?>
                                    <span class="sumresult"><?php echo $ShipmentDetail['ShipmentDetail']['Width']." × ".$ShipmentDetail['ShipmentDetail']['Length']." × ".$ShipmentDetail['ShipmentDetail']['Height']; ?></span>
                                    <?php else: ?>
                                    <span class="sumresult">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="sumresult"><?php echo number_format($ShipmentDetail['ShipmentDetail']['ShippingRate'],0);?></span></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- End Shipment Detail -->
        </div>
        <!-- End Colume 2 -->
        <?php if($ShipmentDetail['Status'] == "Pending" || $ShipmentDetail['Status'] == "Imported"): ?>
        <form id="delete_form" class="form-horizontal" method="post" action="{{url ('partner/cancel-shipment')}}">
		    {{ csrf_field() }}
		    <input type="hidden" name="shipmentId" value="<?php echo $ShipmentDetail['ID'];?>" />
		</form>
	    <div class="col-md-12 text-center">
	    	<a href="javascript:cancelShipment(<?php echo $ShipmentDetail['ID'];?>);"><i class="fa fa-trash-o"></i> ยกเลิกรายการพัสดุ</a>
	    </div>
	    <?php endif; ?>
	    <div class="clearfix"></div>
    	<br />
    
    </div>
    <div class="clearfix"></div>
    <br />
    
    
    
    
    
</div>

<script type="text/javascript">
function cancelShipment(shipment_id){

	if(confirm("คุณต้องการลบพัสดุรายการนี้ใช่หรือไม่")){
		$("#delete_form").submit();
    }
            
}
</script>

@endsection