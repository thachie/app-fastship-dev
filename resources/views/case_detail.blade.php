@extends('layout')
@section('content')
<div class="conter-wrapper">
	<div class="row">
	    <div class="col-md-8 col-md-offset-2">
    	    <div class="panel panel-primary">
    	    	<div class="panel-heading">
    	    		<span class="badge">{{ $case['Status'] }}</span>
    	    		Case #{{ $case['ID'] }} : {{ $case['Category'] }} 
    	    		@if($case['ReferenceId'])
                	<span class="tiny">[Reference: {{ $case['ReferenceId'] }}]</span>
                	@endif
                </div>
            	<div class="panel-body" >
                	
                	<p>{!! $case['Detail'] !!}</p>
    
                	<h6 class="gray" style="margin-top: 0;">
                	Posted: {{ date('d/m/Y H:i:s',strtotime($case['CreateDate'])) }}
                	by {{ session('customer.name') }}
                	</h6>
                	
                	
                </div>
            </div>
            
            @if(sizeof($case['Replies']) > 0)
            @foreach($case['Replies'] as $reply)
            @if(strstr($reply['Detail'],"ปรับปรุง <b>สถานะ</b>") == FALSE && strstr($reply['Detail'],"ปรับปรุงสถานะ") == FALSE)
            <div class="panel panel-default">
            	@if($reply['CustomerId'] == session('customer.id'))
    	    	<div class="panel-body" style="background:#F0F8FF;border:1px solid #ddd;">
                	
                	<p>{!! $reply['Detail'] !!}</p>
                	
    				<h6 class="gray" style="margin: 0;">
    				Posted by {{ session('customer.name') }} ({{ date('d/m/Y H:i:s',strtotime($reply['CreateDate'])) }})
    				
    				</h6>
                	
                </div>
                @else
                <div class="panel-body" style="background:#F5FFFA;border:1px solid #ddd;">
                	
                	<p>{!! $reply['Detail'] !!}</p>
                	
    				<h6 class="gray" style="margin: 0;">
    				Posted by Fastship ({{ date('d/m/Y H:i:s',strtotime($reply['CreateDate'])) }})
    				
    				</h6>
                	
                </div>
                @endif
            </div>
            @endif
            @endforeach
            @endif

	    </div>
	    <div class="clearfix"></div>
	    
	    <div class="col-md-6 col-md-offset-3">
	    	<div class="panel ">
    	    	<div class="panel-heading">พิมพ์ข้อความตอบกลับ</div>
            	<div class="panel-body text-center" >
                <form id="case_form" name="case_form" class="form-horizontal" method="post" action="{{url ('/case/createreply')}}">
	    		
	    			{{ csrf_field() }}	
	    			
	    			<input type="hidden" name="case_id" value="{{ $case['ID'] }}" />
	    			
                	<div><textarea class="form-control required" name="detail" required rows="3" ></textarea></div><br />
                	
                	<button type="submit" class="btn btn-success" style="padding: 8px 40px">ส่งข้อความ</button>
                	
                </form>	
                </div>
            </div>
	    </div>
	    <div class="clearfix"></div>
	    
	    <div class="text-center"><a href="{{ url('/case_list') }}">กลับไปหน้า Case List</a></div>
	    
	</div>
</div>
@endsection