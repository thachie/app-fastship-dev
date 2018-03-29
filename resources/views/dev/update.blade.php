@extends('layouts.layout')

@section('title','Register')



@section('content')

<div class="container">
	<form action="{{ URL('dev/editUser') }}" method="POST">
		<input type="hidden" name="id" value="{{$user->id}}">
		<input type="hidden" name="password_old" value="{{$user->password}}">
		{{ csrf_field() }}
		<div class="form-group">
			<label for="name">Name:</label>
			<input name="name" type="name" class="form-control" id="name" value="{{$user->name}}">
		</div>
		<div class="form-group">
			<label for="email">Email address:</label>
			<input name="email" type="email" class="form-control" id="email" value="{{$user->email}}">
		</div>
		<div class="form-group">
			<label for="pwd">Password:</label>
			<input name="password" type="password" class="form-control" id="pwd" value="">
		</div>
		<button type="submit" class="btn btn-default">Update</button>
	</form>
</div>
@stop