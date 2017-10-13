@extends('layouts.dashboard-template')


@section('content')

<script>
	$(document).ready(function() {
		$.ajax({
            url: "{{url('/ajax')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'home',
            },
            success: function(response) {  
                $("#dashboard-ajax-container").html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });
	});
</script>
@endsection