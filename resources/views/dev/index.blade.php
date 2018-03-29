@extends('layouts.main')
@section('title')
  {{$title}}
@stop
@section('content')


	@foreach($employees as $v)
		
	@endforeach

	<main id="center" class="column">
		<article>
		
			<h1>Heading</h1>
			<p><script>generateText(50)</script></p>
		
		</article>								
	</main>

	<nav id="left" class="column">
		<h3>Left heading</h3>
		<ul>
			<li><a href="#">Link 1</a></li>
			<li><a href="#">Link 2</a></li>
			<li><a href="#">Link 3</a></li>
			<li><a href="#">Link 4</a></li>
			<li><a href="#">Link 5</a></li>
		</ul>
		<h3>Left heading</h3>
		<ul>
			<li><a href="#">Link 1</a></li>
			<li><a href="#">Link 2</a></li>
			<li><a href="#">Link 3</a></li>
			<li><a href="#">Link 4</a></li>
			<li><a href="#">Link 5</a></li>
		</ul>

	</nav>

	<div id="right" class="column">
		<h3>Right heading</h3>
		<p><script>generateText(1)</script></p>
	</div>
@stop
<?php
	function alert()
	{
		$arg_list = func_get_args();
		foreach ($arg_list as $k => $v){
			print "<pre>";
			print_r( $v );
			print "</pre>";
		}
	}
?>