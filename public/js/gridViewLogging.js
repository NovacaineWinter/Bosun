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