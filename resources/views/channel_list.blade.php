@extends('layout')
@section('content')
<div class="conter-wrapper">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">
        	<div class="panel panel-primary">
    			<div class="panel-heading">
        			<div class="col-md-9">{!! FT::translate('channel_list.heading') !!}</div>
        			<div class="col-md-3 text-right" style="padding: 0;">
        				<a href="{{ url('add_channel') }}"><button type="submit" class="btn btn-warning btn-sm">+ {!! FT::translate('button.add_channel') !!}</button></a>
        			</div>
    				<div class="clearfix"></div>
    			</div>
    		    <div class="panel-body">
    		    	
    		    	@if(sizeof($channels) > 0)
    		    	<table class="table table-stripe table-hover">
                        <thead>
                        <tr>
                            <td>{!! FT::translate('label.channel_name') !!}</td>
                            <td class="hidden-xs">marketplace</td>
                            <td class="hidden-xs">site</td>
                            <td class="hidden-xs">วันที่หมดอายุ</td>
                            <td style="width: 5%;">{!! FT::translate('label.delete') !!}</td>
                        </tr>
                        </thead>
                        <tbody>
                        
                        @foreach($channels as $channel)
                        @if($channel['CreateDate']['date'] != "0000-00-00 00:00:00")
                        	@php $createDate = date("d/m/Y",strtotime($channel['CreateDate']['date'])); @endphp
                        @endif
                        <tr id="shipment_<?php echo strtolower($channel['ChannelType']);?>_<?php echo strtolower($channel['AccountName']);?>">
                            <td><?php echo $channel['AccountName'];?></td>
                            <td><img src="{{ url('images/marketplace/' . strtolower($channel['ChannelType']) . '.png') }}" style="width:75px;" /></td>
                            <td><span class="badge badge-info"><?php echo $channel['Marketplace'];?></span></td>
                            <td class="hidden-xs">
                            	
                            	@if(!(substr($channel['Token'], 0, strlen('v^1')) === 'v^1'))
    
                                <form method="post" action="{{url ('shipment/add_ebay_channel')}}" style="margin-bottom: 0">
                
                        			{{ csrf_field() }}
                        			
                        			<input type="hidden" name="channel" value="<?php echo $channel['AccountName'];?>" />
                        			<input type="hidden" name="command" value="update" />
                        			
                        			<span class="text-danger tiny">กรุณาต่ออายุ </span>
                        			<button class="btn btn-success" type="submit"><i class="fa fa-refresh"></i></button>
                	                
                                </form>
                                @else
                                <?php echo date("d M Y",strtotime($channel['CreateDate']['date']) + 365*24*3600);?>
                    			@endif
                    			
                            </td>
                            <td>
                            <form name="delete_form" class="form-horizontal" method="post" action="{{url ('/customer/remove-channel')}}"  style="margin-bottom: 0;">
    	    		
    	    					{{ csrf_field() }}
    	    					
    	    					<input type="hidden" name="cc_id" value="{{ $channel['ID'] }}" />
    	    					
                            	<button class="btn btn-default" type="submit"><i class="fa fa-trash"></i></button>
                            	
                           	</form>
                           	</td>
                           	
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    
                    @else
                    
                    <div class="text-center">
                    	เพิ่มร้านค้า/ช่องทางใหม่เพื่อเริ่มต้นใช้งาน <a href="{{ url('add_channel') }}"><button type="submit" class="btn btn-primary ">+ {!! FT::translate('button.add_channel') !!}</button></a>
                    </div>
                    @endif
                    
    		    </div>
    		</div>
        </div>

    </div>
</div>
@endsection