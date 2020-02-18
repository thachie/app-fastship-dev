@extends('layout')
@section('content')
<?php 
//alert($shipment_data);
?>
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-12"><h2>สร้างพัสดุอัตโนมัติ</h2></div>
	</div>

	<div class="col-md-12 hidden-xs">    
		<div class="panel panel-primary">
			<div class="panel-heading">รายการพัสดุ</div>
			<div class="panel-body">  
				<table class="table table-hover table-striped">
				<thead>
					<tr>
						<td>หมายเลขพัสดุ</td>
						<td>หมายเลขอ้างอิง</td>
						<td>วันที่สร้าง</td>
						<td>ชื่อผู้รับ</td>
						<td>ประเทศปลายทาง</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
				<?php 
				if(sizeof($shipment_data) > 0):
				foreach($shipment_data as $shipment):
				?>
					<tr>
						<td>{{ $shipment['ID'] }}</td>
						<td>{{ $shipment['Reference'] }}</td>
						<td>{{ date("d/m/Y",strtotime($shipment['CreateDate']['date'])) }}</td>
						<td>{{ $shipment['ReceiverDetail']['Firstname'] }} {{ $shipment['ReceiverDetail']['Lastname'] }}</td>
						<td>{{ $countries[$shipment['ReceiverDetail']['Country']] }} </td>
						<td><a href="#"><button class="btn btn-primary btn-sm">สร้างพัสดุ</button></a></td>
					</tr>
				<?php 
				endforeach;
				else:
				?>
				<tr>
					<td colspan="5" class="text-center">ไม่พบรายการพัสดุ</td>
				</tr>
				<?php
				endif;
				?>
				</tbody>
				</table>
				
<!-- 				<div class="clearfix text-center"> -->
<!-- 					<ul class="pagination"> -->
<!-- 						<li><a href="#" aria-label="Previous"><span aria-hidden="true">&lt;&lt;</span></a></li> -->
<!-- 						<li><a href="#">1</a></li> -->
<!-- 						<li><a href="#">2</a></li> -->
<!-- 						<li><a href="#">3</a></li> -->
<!-- 						<li><a href="#">4</a></li> -->
<!-- 						<li><a href="#">5</a></li> -->
<!-- 						<li><a href="#">6</a></li> -->
<!-- 						<li><a href="#" aria-label="Next"><span aria-hidden="true">&gt;&gt;</span></a></li>		 -->
<!-- 					</ul> -->
<!-- 				</div> -->
			</div>
		</div>
    </div>
			
							
	<div class="col-md-12 visible-xs"> 
		<div class="panel panel-primary">
			<div class="panel-heading">รายการพัสดุ</div>
			<div class="panel-body">  
			<?php 
				if(sizeof($shipment_data) > 0):
				foreach($shipment_data as $shipment):
			?>
			<div class="col-xs-12 shipment-list">
				<div class="col-xs-12">
			    	<div class="pull-left"><h3>{{ $shipment['ID'] }}</h3></div>
			       	<div class="pull-right"><h4>{{ date("d/m/Y",strtotime($shipment['CreateDate']['date'])) }}</h4></div>
			    </div>
		        <div class="clearfix"></div>

				<div class="col-xs-4">{{ $shipment['ReceiverDetail']['Firstname'] }} {{ $shipment['ReceiverDetail']['Lastname'] }}</div>
				<div class="col-xs-8">
					<h4>{{ $shipment['Reference'] }}</h4>
				</div>
				<div class="clearfix"></div>
				
				<div class="col-xs-12 text-center">
					<a href="#"><button type="button" class="btn btn-primary">สร้างพัสดุ</button></a>
				</div>
				<div class="clearfix"></div>
				
			</div>
			<?php 
				endforeach;
				else: 
			?>
			<div class="col-xs-12 shipment-list">ไม่พบรายการพัสดุ</div>
			<?php
				endif;
			?>
		</div>
	</div>
</div>

<div class="col-md-12 col-xs-12 text-center">
		<a href="channel_list"><button type="button" class="btn btn-primary">จัดการช่องทางของฉัน</button></a>
	</div>

<script>
	$(document).ready( function() {
	    $( ".selector" ).checkboxradio({
	        classes: {
	            "ui-checkboxradio": "highlight"
	        }
	    }); 

	    $("#amount-100").click();
	    $("#method-Bank_Transfer").click();
	    
	});
</script>

@endsection