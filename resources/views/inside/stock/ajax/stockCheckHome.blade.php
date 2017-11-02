

<?php 
//use Illuminate\Foundation\Auth\User as Authenticatable;
use App\stock;


//get all stock categories

$stale_cutoff = time() - (60 * 60 * 24 * 14);

$buildings = stock::all()->pluck('building')->unique();

if($request->has('all')){
	$itemsToCheck=stock::where('last_stock_check_timestamp','<',$stale_cutoff)->get();

}else{

	if($request->has('building')){
		$allBays = stock::where('building','=',$request->get('building'))->get();
		$bays= $allBays->pluck('bay')->unique();
	}
	if($request->has('bay')){
		$itemsToCheck = stock::where('bay','=',$request->get('bay'))->where('building','=',$request->get('building'))->where('last_stock_check_timestamp','<',$stale_cutoff)->get();
	}

}
?>

<div class="row">
	<div class="col-sm-4">
		<h2>Building</h2>
		<select id="buildingSelect" onChange="buildingChange()" >
			<option value="0"></option>
			@foreach($buildings as $building)
			<option value="{{{ $building }}}" @if($request->get('building')==$building) selected @endif >{{{ $building }}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-sm-4">
		<H2>Bay</H2>
		@if($request->has('building'))
			<select id="baySelect" onChange="bayChange()" >
				<option value="0"></option>
				@foreach($bays as $bay)
					<option value="{{{ $bay }}}" @if($request->get('bay')==$bay) selected @endif >{{{ $bay }}}</option>
				@endforeach
			</select>
		@endif
	</div>
	<div class="col-sm-4">
		<div class="btn btn-info btn-lg pull-right" style="cursor:pointer;" onClick="allButton()">All</div>
	</div>

</div>

<br><br><br><br>
	@if($request->has('bay') && $request->has('building') || $request->has('all'))
		@include('inside.stock.ajax.stockCheckItemLoop', ['itemsToCheck'=>$itemsToCheck])
	@endif



<script>
	function buildingChange(){
		$('#baySelect').val(0);			
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

	}

	function bayChange(){

	    	$.ajax({
	            url: "{{url('/stock/stockcheck')}}",
	            method: 'GET',
	            data: {
	                building: $('#buildingSelect').val(),
	                bay: $('#baySelect').val(),
	            },
	            success: function(response) {
	                $("#stockcheckAjaxTarget").html(response);
	            },
	            
	            error: function(response) {
	                console.log('There was an error - it was:');
	                console.dir(response);
	            }
	    	}); 

	}

	function allButton(){
		$.ajax({
            url: "{{url('/stock/stockcheck')}}",
            method: 'GET',
            data: {
            	all:1,
            },
            success: function(response) {
                $("#stockcheckAjaxTarget").html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
    	}); 
	}

</script>
