@extends('layout')
@section('content')
<?php 
//alert($pickup_list);die();
$limit = 20;
?>
<div class="conter-wrapper">
	<div class="row">
        <div class="col-md-12"><h2>ใบรับพัสดุของฉัน</h2></div>
	</div>

        <div class="row">      
            <div class="col-md-12 ">
                <div class="panel panel-primary hidden-xs">
                    <div class="panel-heading">รายการใบรับพัสดุ</div>
                    <div class="panel-body">
                        
                        <table class="table table-hover table-striped">
                        <thead>
                        	<tr>
                        		<td>หมายเลขใบรับพัสดุ</td>
                        		<td>วันที่สร้าง</td>
                        		<td>สถานะ</td>
                        		<td>ยอดชำระทั้งหมด (บาท)</td>
                        		<td>จำนวนพัสดุ</td>
                        		<td></td>
                        	</tr>
                        </thead>
                        <tbody>
                        <?php 
                        if(is_array($pickup_list) && sizeof($pickup_list) > 0):
                        foreach($pickup_list as $pickup):
                        	//if($pickup['Status'] == "Cancelled") continue;
                        ?>
                        	<tr>
                        		<td><a href="/pickup_detail/<?php echo $pickup['ID']; ?>"><?php echo $pickup['ID']; ?></a></td>
                        		<td><?php echo date("d/m/Y",strtotime($pickup['CreateDate']['date'])); ?></td>
                        		<td><?php echo $pickup['Status']; ?></td>
                        		<td><?php echo number_format($pickup['Amount']); ?></td>
                        		<td><?php echo $pickup['TotalShipment']; ?></td>
                        		<td><a href="/pickup_detail/<?php echo $pickup['ID']; ?>"><button class="btn btn-primary btn-sm">จัดการสินค้า</button></a></td>
                        	</tr>
                        <?php 
                        endforeach;
                        else:
                        ?>
                        <tr><td colspan="6" class="text-center">ไม่พบใบรับพัสดุ</td></tr>
                        <?php
                        endif;
                        ?>
                        </tbody>
                        </table>
                        
<!--                         <div class="clearfix text-center"> -->
<!--                         	<ul class="pagination"> -->
<!--                         		<li><a href="#" aria-label="Previous"><span aria-hidden="true">&lt;&lt;</span></a></li> -->
<!--                         		<li><a href="#">1</a></li> -->
<!--                         		<li><a href="#">2</a></li> -->
<!--                         		<li><a href="#">3</a></li> -->
<!--                         		<li><a href="#">4</a></li> -->
<!--                         		<li><a href="#">5</a></li> -->
<!--                         		<li><a href="#">6</a></li> -->
<!--                         		<li><a href="#" aria-label="Next"><span aria-hidden="true">&gt;&gt;</span></a></li> -->
<!--                         	</ul> -->
<!-- 						</div> -->
                    </div>
				</div>
				
				<div class="row visible-xs">
					<div class="panel panel-primary">
	                    <div class="panel-heading">รายการใบรับพัสดุ</div>
	                    <div class="panel-body">
						<?php 
						if(is_array($pickup_list) && sizeof($pickup_list) > 0):
						foreach($pickup_list as $pickup):
						?>
						<div class="col-xs-12 shipment-list">
							<div class="col-xs-12">
			                    <div class="pull-left"><h3><a href="/pickup_detail/<?php echo $pickup['ID']; ?>"><?php echo $pickup['ID']; ?></a></h3></div>
			                    <div class="pull-right"><h4><?php echo $pickup['Status']; ?></h4></div>
			                </div>
		                	<div class="clearfix"></div>

							<div class="col-xs-6 text-right">วันที่สร้าง : </div>
							<div class="col-xs-6"><?php echo date("d/m/Y",strtotime($pickup['CreateDate']['date'])); ?></div>
							<div class="clearfix"></div>
							
							<div class="col-xs-6 text-right">ยอดชำระ : </div>
							<div class="col-xs-6"><?php echo $pickup['Amount']; ?></div>
							<div class="clearfix"></div>
							
							<div class="col-xs-6 text-right">จำนวนพัสดุ : </div>
							<div class="col-xs-6"><?php echo $pickup['TotalShipment']; ?></div>
							<div class="clearfix"></div>
							
							<div class="col-xs-12 text-center" style="margin-top: 10px;">
								<a href="/pickup_detail/<?php echo $pickup['ID']; ?>">
									<button type="button" class="btn btn-primary">ดูรายละเอียด</button>
								</a>
							</div> 
						</div> 
						<?php 
						endforeach;
						else:
						?>
						<div class="text-center">ไม่พบใบรับพัสดุ</div>
						<?php
						endif;
						?>
						</div>
					</div>
				</div>
				<div class="clearfix text-center">
					<ul class="pagination">
					<?php if($page > 1): ?>
						<li><a href="/pickup_list/<?php echo ($page-1);?>" aria-label="Previous"><span aria-hidden="true">&lt;&lt;</span></a></li>
					<?php endif; ?>
						<li><a href="#"><?php echo $page; ?></a></li>
<!-- 						<li><a href="#">2</a></li> -->
<!-- 						<li><a href="#">3</a></li> -->
<!-- 						<li><a href="#">4</a></li> -->
<!-- 						<li><a href="#">5</a></li> -->
<!-- 						<li><a href="#">6</a></li> -->
					<?php if(sizeof($pickup_list) == $limit): ?>
						<li><a href="/pickup_list/<?php echo ($page+1);?>" aria-label="Next"><span aria-hidden="true">&gt;&gt;</span></a></li>	
					<?php endif; ?>
					</ul>
				</div>

        </div>
    </div>
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