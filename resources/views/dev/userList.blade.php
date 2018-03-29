@extends('layouts.layout')

@section('title','User List')



@section('content')



<div class="container">
  <h2>User List</h2>
  <p>
  	@if(session('status'))
  		{{session('status')}}
  	@endif
  </p>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No.</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Manage</th>
      </tr>
    </thead>
    <tbody>
    	@if(count($user_row) > 0)
	    	@php
	    		$i=1;
	    	@endphp
	    	@foreach($user_row as $v)
	    		{{--alert($v)--}}
				<tr>
					<td>{{$i}}</td>
					<td>{{$v->name}}</td>
					<td>{{$v->email}}</td>
					<td><a href='{{ URL("dev/updateUser/{$v->id}") }}'> Update </a> | <a href='{{ URL("dev/deleteUser/{$v->id}") }}'>Delete</a></td>
				</tr>
			@php
	    		$i++;
	    	@endphp
	      	@endforeach
      	@endif
    </tbody>
  </table>
</div>
@stop
@php
  function alert()
  {
    $arg_list = func_get_args();
    foreach ($arg_list as $k => $v){
      print "<pre>";
      print_r( $v );
      print "</pre>";
    }
  }
@endphp