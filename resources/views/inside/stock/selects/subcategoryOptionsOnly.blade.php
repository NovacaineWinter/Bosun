<?php
use App\stockSubcategory;

$subcategories = stockSubcategory::where('category_id','=',$request->get('parentID'))->get();

?>

@foreach($subcategories as $subcategory)
	<option value="{{{ $subcategory->id }}}">
		{{{ $subcategory->name }}}
	</option>
@endforeach