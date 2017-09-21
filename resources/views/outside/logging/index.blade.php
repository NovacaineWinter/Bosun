<?php 
	use App\User;
	$users = User::where('can_log_hours','=',1)->get();
?>

@extends('outside.logging.master_template')

@section('pagetitle')
	Logging Homepage
@stop




@section('page_content')
	


	@if(CONFIG['rfid'])

	<form method="get" action="">
		<input style="opacity: 0;" type="text" name="cardID" id="cardidinput" required>		

		<script>
			var focusInterval=setInterval(function(){ document.getElementById("cardidinput").focus();}, 100);

			$('#cardidinput').keypress(function(event) {
			    if (event.keyCode == 13) {
			        
			        event.preventDefault();

			        if($('#cardidinput').val()!=''){
			        	
			        	
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
			        }		            
			    }
			});				
		</script>

	</form>

	@elseif(CONFIG['grid'])

	<script>
		$( document ).ready(function() {

			$('.clickable-staff-grid').click(function() {

				$.ajaxSetup({
			        headers: {
			            'X-CSRF-TOKEN': '{{csrf_token() }}'
			        }
				});
		    	
				$.ajax({
		            url: "{{ url('/logging/ajax' )}}",
		            method: 'GET',
		            data: {
		                ajaxmethod: "userGridClicked",			                    
		                user_id: $(this).attr('staffID'),
		            },
		            success: function(response) {
		                $('#ajax-target').html(response);   
		            },
		            error: function(response) {
		                console.log('There was an error - it was:');
		                console.dir(response);
		            }
		        });
			}); //end of clickable staff grid function

		});		//end of doc ready function
	</script>

	<div class="container">
	 	@if($users->count() > 0)
	 	<?php $n=0; ?>
	 	<div class="row grid-view-row">
			@foreach($users as $user)
				<?php $n++; ?>

				@include('outside.logging.loop_templates.grid_view', array('user'=> $user))

				@if($n==4)
					</div><div class="row grid-view-row">
				@endif
			@endforeach
		</div>	
		@else
			Oops - there are no staff in the Database
		@endif
	</div>

	@else

		ENTER USERNAME

	@endif
	
	
	
@stop


@section('page_header_1')

	@if(CONFIG['rfid'])

		SCAN &nbspID

	@elseif(CONFIG['grid'])

		<!--Create grid view of users -->

	@else

		ENTER USERNAME

	@endif

@stop


