@extends('layout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.20/css/uikit.css">
<link rel="stylesheet" href="./css/jquery.Thailand.min.css">

<script type="text/javascript" src="./js/JQL.min.js"></script>
<script type="text/javascript" src="./js/typeahead.bundle.js"></script>
<script type="text/javascript" src="./js/jquery.Thailand.min.js"></script>

<script type="text/javascript">
	$.Thailand({
		database: './db.json', 

		$district: $('#demo1 [name="district"]'),
		$amphoe: $('#demo1 [name="amphoe"]'),
		$province: $('#demo1 [name="province"]'),
		$zipcode: $('#demo1 [name="zipcode"]'),

		onDataFill: function(data){
			console.info('Data Filled', data);
		},

		onLoad: function(){
			console.info('Autocomplete is ready!');
			$('#loader, .demo').toggle();
		}
	});

	$('#demo1 [name="district"]').change(function(){
		console.log('ตำบล', this.value);
	});
	$('#demo1 [name="amphoe"]').change(function(){
		console.log('อำเภอ', this.value);
	});
	$('#demo1 [name="province"]').change(function(){
		console.log('จังหวัด', this.value);
	});
	$('#demo1 [name="zipcode"]').change(function(){
		console.log('รหัสไปรษณีย์', this.value);
	});
</script>

@endsection