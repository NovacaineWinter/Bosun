@extends('outside.logging.master_template')

@section('pagetitle')
	Logging Homepage
@stop




@section('page_content')
	
	<div >
		<form method="get" action="">
			<input style="opacity: 0;" type="text" name="cardID" id="cardidinput" required>
			<script>
				var focusInterval=setInterval(function(){ document.getElementById("cardidinput").focus();}, 100);

				$('#cardidinput').keypress(function(event) {
				    if (event.keyCode == 13) {
				        
				        event.preventDefault();

				        //if($('#cardidinput').val()!=''){
				        	
				        	
				        	$.ajaxSetup({
						        headers: {
						            'X-CSRF-TOKEN': '{{csrf_token() }}'
						        }
							});
				        	
							$.ajax({
				                url: "{{url('/logging/ajax')}}",
				                method: 'GET',
				                data: {
				                    ajaxmethod: "badgeSubmitted",			                    
				                    badgeID: $('#cardidinput').val(),
				                },
				                success: function(response) {
				                    $('#ajax-target').html(response);
				                    clearInterval(focusInterval);				                    

				                },
				                error: function(response) {
				                    console.log('There was an error - it was:');
				                    console.dir(response);
				                }
				            });
					        
					        $('#cardidinput').val('');
				        //}		            
				    }
				});				
			</script>

		</form>
	</div>
@stop


@section('page_header_1')
	@if(CONFIG['rfid'])
		SCAN &nbspID
	@else
		ENTER USERNAME
	@endif
@stop


