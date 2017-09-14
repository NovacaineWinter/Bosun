<?php
use App\stockSubcategory;
$subcategories = stockSubcategory::where('category_id','=',$request->get('parentID'))->get();;
?>

<select id="subcategory-selector" itemID="{{{ $request->get('itemID') }}}">
@foreach($subcategories as $subcategory)
	<option value="{{{ $subcategory->id }}}" <?php if($subcategory->id == $request->get('targetID')){ echo "selected";} ?> >{{{ $subcategory->name }}}</option>
@endforeach
</select>







<script>


	$('#subcategory-selector').change(function() {
		//ajax it
		newSubcategoryID=$(this).val();
		$.ajax({
	            url: "{{url('/stock/insert-select-menu')}}",
	            method: 'GET',
	            data: {
	                ajaxmethod: 'subcategorySelect',
		            targetID:      {{{$request->get('targetID')}}},
		            parentID: {{{ $request->get('parentID') }}},
		           	itemID:      {{{ $request->get('itemID') }}},
		            newSubcategoryID: newSubcategoryID,

	            },
	            success: function(response) {
	            	changingSubcategory=false;

	            	$("#item-{{{$request->get('itemID')}}}-subcategory-indicator-holder").attr('targetID',newSubcategoryID);
	            	$("#item-{{{$request->get('itemID')}}}-subcategory-indicator-holder").attr('parentID',{{{ $request->get('parentID') }}});
	                $("#item-{{{$request->get('itemID')}}}-subcategory-indicator-holder").html(response);

	            },
	            error: function(response) {
	                console.log('There was an error - it was:');
	                console.dir(response);
	            }
	        });       
		});

	$('#subcategory-selector').blur(function() {
		//ajax again
		newSubcategoryID=$(this).val();
		$.ajax({
	            url: "{{url('/stock/insert-select-menu')}}",
	            method: 'GET',
	            data: {
	                ajaxmethod: 'subcategorySelect',
		            targetID:      {{{$request->get('targetID')}}},
		            parentID: {{{ $request->get('parentID') }}},
		           	itemID:      {{{ $request->get('itemID') }}},
		            newSubcategoryID: newSubcategoryID,

	            },
	            success: function(response) {
	            	changingSubcategory=false;

	            	$("#item-{{{$request->get('itemID')}}}-subcategory-indicator-holder").attr('targetID',newSubcategoryID);
	            	$("#item-{{{$request->get('itemID')}}}-subcategory-indicator-holder").attr('parentID',{{{ $request->get('parentID') }}});
	                $("#item-{{{$request->get('itemID')}}}-subcategory-indicator-holder").html(response);

	            },
	            error: function(response) {
	                console.log('There was an error - it was:');
	                console.dir(response);
	            }
	        });       
	});

</script>