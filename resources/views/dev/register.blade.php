@extends('layouts.layout')

@section('title','Register')



@section('content')

<div class="container">
	<form action="{{ URL('dev/chkRegister') }}" method="POST">
		{{ csrf_field() }}
		<div class="form-group">
			<label for="name">Name:</label>
			<input name="name" type="name" class="form-control" id="name">
		</div>
		<div class="form-group">
			<label for="email">Email address:</label>
			<input name="email" type="email" class="form-control" id="email">
		</div>
		<div class="form-group">
			<label for="pwd">Password:</label>
			<input name="password" type="password" class="form-control" id="pwd">
		</div>
		<button type="submit" class="btn btn-default">Submit</button>
	</form>
</div>
@stop