<?php
use App\stockCategory;
$categories = stockCategory::all();
?>

<select id="category-selector" itemID="{{{ $request->get('itemID') }}}">
@foreach($categories as $category)
	<option value="{{{ $category->id }}}" <?php if($category->id == $request->get('targetID')){ echo "selected";} ?> >{{{ $category->name }}}</option>
@endforeach
</select>



<script>


	$('#category-selector').change(function() {
		//ajax it
		newcategoryID=$(this).val();
		$.ajax({
	            url: "{{url('/stock/insert-select-menu')}}",
	            method: 'GET',
	            data: {
	                ajaxmethod: 'categorySelect',
		            targetID:      {{{$request->get('targetID')}}},
		            itemID:      {{{$request->get('itemID')}}},
		            newCategoryID: newcategoryID,

	            },
	            success: function(response) {
	            	changingCategory=false;
	            	changingSubcategory=true;
	            	//now want to trigger the ajax to update the subcategory

            			$.ajax({
					        url: "{{url('/stock/insert-select-menu')}}",
					        method: 'GET',
					        data: {
					            ajaxmethod: 'subcategorySelect',
					            targetID:     0,
					            parentID:    newcategoryID,
		     		            itemID:      {{{$request->get('itemID')}}}
					            
					        },
					        success: function(response) {

					            $("#item-{{{$request->get('itemID')}}}-subcategory-indicator-holder").html(response); 
					        },
					        
					        error: function(response) {
					            console.log('There was an error - it was:');
					            console.dir(response);
					        }
					    });

	            		//carrying on - reset the stuff to do with category and let the user focus on sorting out the subcategory
	            	$("#item-{{{$request->get('itemID')}}}-category-indicator-holder").attr('targetID',newcategoryID);
	                $("#item-{{{$request->get('itemID')}}}-category-indicator-holder").html(response);

	            },
	            error: function(response) {
	                console.log('There was an error - it was:');
	                console.dir(response);
	            }
	        });       
		});

	$('#category-selector').blur(function() {
		//ajax again
		$.ajax({
	            url: "{{url('/stock/insert-select-menu')}}",
	            method: 'GET',
	            data: {
	                ajaxmethod: 'categorySelect',
		            targetID:      {{{$request->get('targetID')}}},
		            itemID:      {{{$request->get('itemID')}}},
		            newCategoryID: $(this).val(),

	            },
	            success: function(response) {
	            	changingCategory=false;
	                $("#item-{{{$request->get('itemID')}}}-category-indicator-holder").html(response);

	            },
	            error: function(response) {
	                console.log('There was an error - it was:');
	                console.dir(response);
	            }
	        });
	});

</script>