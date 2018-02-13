<?php 
	use App\User;
	use App\config;
	$config = new config;
	$users = User::where('can_log_hours','=',1)->get();
?>

@extends('outside.logging.master_template')

@section('pagetitle')
	Logging Homepage
@stop




@section('page_content')
	


	@if($config->boolean('rfid'))

	<form method="get" action="">
		<input style="opacity: 0;" type="text" name="cardID" id="cardidinput" required>		

		<script>
			var focusInterval=setInterval(function(){ document.getElementById("cardidinput").focus();}, 100);
			var keyupTimeout;
			$('#cardidinput').keypress(function(event) {
				clearTimeout(keyupTimeout);
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
			    }else{
			    	keyupTimeout = setTimeout(function() {
			    		$('#cardidinput').val('');
			    	},100);
			    }
			});				
		</script>

	</form>

	@elseif($config->boolean('grid'))

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

<div class="clock">
	<div id="Date"></div>
	  <ul>
	      <li id="hours"></li>
	      <li id="point">:</li>
	      <li id="min"></li>
	      <li id="point">:</li>
	      <li id="sec"></li>
	  </ul>
</div>



<style>

.clock {
    width: 800px;
    margin: 0 auto;
    padding: 30px;
    color: #fff;
}

#Date {
    font-family: 'BebasNeueRegular', Arial, Helvetica, sans-serif;
    font-size: 36px;
    text-align: center;
    text-shadow: 0 0 5px #00c6ff;
        padding-left: 53px;
}

#point {
    position: relative;
    -moz-animation: mymove 1s ease infinite;
    -webkit-animation: mymove 1s ease infinite;
    padding-left: 10px;
    padding-right: 10px;
}
.clock ul {
    width: 800px;
    margin: 0 auto;
    padding: 0px;
    list-style: none;
    text-align: center;
}

.clock ul li {
    display: inline;
    font-size: 1em;
    text-align: center;
    font-family: 'BebasNeueRegular', Arial, Helvetica, sans-serif;
    text-shadow: 0 0 5px #00c6ff;
}

/* Simple Animation */
@-webkit-keyframes mymove {
    0% {opacity: 1.0;
    text-shadow: 0 0 20px #00c6ff;
}

50% {
    opacity: 0;
    text-shadow: none;
}

100% {
    opacity: 1.0;
    text-shadow: 0 0 20px #00c6ff;
}	
}

@-moz-keyframes mymove {
    0% {
        opacity: 1.0;
        text-shadow: 0 0 20px #00c6ff;
    }

    50% {
        opacity: 0;
        text-shadow: none;
    }

    100% {
        opacity: 1.0;
        text-shadow: 0 0 20px #00c6ff;
    };
}
</style>



<script type="text/javascript">
$(document).ready(function() {
// Create two variable with the names of the months and days in an array
var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

// Create a newDate() object
var newDate = new Date();
// Extract the current date from Date object
newDate.setDate(newDate.getDate());
// Output the day, date, month and year   
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval( function() {
	// Create a newDate() object and extract the seconds of the current time on the visitor's
	var seconds = new Date().getSeconds();
	// Add a leading zero to seconds value
	$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
	},1000);
	
setInterval( function() {
	// Create a newDate() object and extract the minutes of the current time on the visitor's
	var minutes = new Date().getMinutes();
	// Add a leading zero to the minutes value
	$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
    },1000);
	
setInterval( function() {
	// Create a newDate() object and extract the hours of the current time on the visitor's
	var hours = new Date().getHours();
	// Add a leading zero to the hours value
	$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
    }, 1000);	
});
</script>


	@if($config->boolean('rfid'))

		SCAN &nbspID

	@elseif($config->boolean('grid'))

		<!--Create grid view of users -->

	@else

		ENTER USERNAME

	@endif





@stop


