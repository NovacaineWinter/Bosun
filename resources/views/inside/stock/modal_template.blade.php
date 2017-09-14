<div id="main-modal">
	 <div id="modal-close-btn">&#128473;</div>
	<div id="modal-title">
		<h4>@yield('title')</h4>
	</div>


	<div id="modal-content-holder">
		 @yield('content')
	</div>


	<div id="modal-actions">
		@yield('action')
	</div>


	 @yield('inline-script')
	 <script>	 	
	    $('#modal-close-btn').click(function() {
	        $('#modalcontainer').empty();
	    });
	    $('#modal-background-blur').click(function() {
	    	$('#modalcontainer').empty();
	    });
	 </script>


</div>
<div id="modal-background-blur"></div>