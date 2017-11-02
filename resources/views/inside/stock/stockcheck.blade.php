@extends('layouts.app')

@section('content')
<div class="container">
	
	<div class="col-sm-12" id="stockcheckAjaxTarget"> 

	</div>
</div>

<script>
	$(document).ready(function() {
		$.ajax({
	        url: "{{url('/stock/stockcheck')}}",
	        method: 'GET',
	        data: {
	            building: $('#buildingSelect').val(),
	        },
	        success: function(response) {
	            $("#stockcheckAjaxTarget").html(response);
	        },
	        
	        error: function(response) {
	            console.log('There was an error - it was:');
	            console.dir(response);
	        }
		}); 	
	});

</script>
@endsection