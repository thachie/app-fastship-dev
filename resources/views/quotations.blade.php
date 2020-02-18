@extends('layout')
@section('content')
    <div class="conter-wrapper">
        
    <?php if(sizeof($shipment_data) > 0){ ?>
        <div class="row">
            <div class="col-md-7 pad8"><h2>{!! FT::translate('quotation.heading') !!}</h2></div>
            <div class="col-md-5 text-right">
                <div class="bs-wizard dot-step" style="border-bottom:0;">
                    <div class="col-xs-4 bs-wizard-step complete">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">1</span></a>
                        <p class="text-center">{!! FT::translate('step.step1') !!}</p>
                    </div> 
                    <div class="col-xs-4 bs-wizard-step active">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">2</span></a>
                        <p class="text-center">{!! FT::translate('step.step2') !!}</p>
                    </div>
                    <div class="col-xs-4 bs-wizard-step ">
	                    <div class="progress"><div class="progress-bar"></div></div>
                        <a href="#" class="bs-wizard-dot" style="text-align: center; line-height: 30px;"><span style="z-index: 995; position: relative; color: #fff;">3</span></a>
                        <p class="text-center">{!! FT::translate('step.step3') !!}</p>
                	</div>       
            	</div>
            </div>
        </div>	    

        <div class="row">
    <div class="col-md-12">
            <div class="panel panel-primary hidden-xs">
                <div class="panel-heading">{!! FT::translate('quotation.panel.heading1') !!}</div>
                <div class="panel-body">
                    <table class="table table-stripe table-hover">
                        <thead>
                        <tr>
                            <td>{!! FT::translate('label.shipment_id') !!}</td>
                            <td>{!! FT::translate('label.weight') !!}</td>
                            <td>{!! FT::translate('label.receiver') !!}</td>
                            <td>{!! FT::translate('label.destination') !!}</td>
                            <td>{!! FT::translate('label.delete') !!}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if(sizeof($shipment_data) > 0): 
                        foreach($shipment_data as $data): 
                        ?>
                        <tr id="shipment_<?php echo $data['ID'];?>">
                            <td><a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a></td>
                            <td><?php echo $data['ShipmentDetail']['Weight'];?></td>
                            <td><?php echo $data['ReceiverDetail']['Firstname'];?> <?php echo $data['ReceiverDetail']['Lastname'];?></td>
                            <td><?php echo $countries[$data['ReceiverDetail']['Country']];?></td>
                            <td><a href="javascript:cancelShipment(<?php echo $data['ID'];?>);"> <i class="fa fa-trash"></i></a></td>
                        </tr>
                        <?php 
                        endforeach;
                        endif;
                        ?>
                        
                        </tbody>
                    </table>
                    <div class="col-md-12 text-center"><a href="{{ url('calculate_shipment_rate') }}">+ {!! FT::translate('button.add_shipment') !!}</a></div>
                </div>
            </div>
            </div>

            <div class="col-md-12 visible-xs">
            <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">{!! FT::translate('quotation.panel.heading1') !!}</div>
                <div class="panel-body">
	            <?php 
	            if(sizeof($shipment_data) > 0): 
	            foreach($shipment_data as $data): 
	            ?>
	            <div class="col-xs-12 shipment-list">
	            	<div class="col-xs-12">
	                    <div class="pull-left"><h4><a href="/shipment_detail/<?php echo $data['ID'];?>" target="_blank"><?php echo $data['ID'];?></a></h4></div>
	                    <div class="pull-right"><h4 style="font-weight: 800; color: #f15a22;"><?php echo number_format($data['ShipmentDetail']['Weight'],0);?> {!! FT::translate('unit.gram') !!}</h4></div>
	                </div>
	                <div class="clearfix"></div>
	                
                    <div class="col-xs-7">
                            <h4 style="margin-bottom: 5px;"><?php echo $data['ReceiverDetail']['Firstname'];?> </h4>
                           	 ปลายทาง : <?php echo $countries[$data['ReceiverDetail']['Country']];?>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="col-xs-12 text-right small"> 
                    	<!-- <a href="/shipment_detail/<?php echo $data['ID'];?>"><i class="fa fa-edit"></i> แก้ไข</a>  -->
                        <a style="text-decoration: none; font-size: 10px; font-weight: 600;" href="javascript:cancelShipment(<?php echo $data['ID'];?>);"><i class="fa fa-trash"></i> {!! FT::translate('button.cancel') !!}</a>
                    </div>
                </div>
	            <?php 
	                endforeach;
	                endif;
	            ?>
	            </div>
	        </div>
            </div>

        </div>

        
        <form id="delete_form" class="form-horizontal" method="post" action="{{url ('shipment/cancel')}}">
		    {{ csrf_field() }}
		    <input type="hidden" name="shipmentId" />
		</form>
        

        <?php }else{ ?>
        
            <div class="row">
                <div class="col-md-7 pad8"><h2>{!! FT::translate('quotation.heading') !!}</h2></div>
            </div>
            <div class="text-center" style="padding-top: 30px;">
                <h4>{!! FT::translate('error.quotation.notfound') !!}</h4>
                <a href="calculate_shipment_rate" class="btn btn-lg btn-primary">{!! FT::translate('button.create_shipment') !!}</a>
            </div>
        <?php } ?> 

    </div>
@endsection