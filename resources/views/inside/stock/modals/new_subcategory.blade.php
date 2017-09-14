<?php
use App\stockCategory;
$categories=stockCategory::all();
?>
@extends('inside.stock.modal_template')

@section('title')
	Create New Subcategory
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-1">&nbsp;</div>
    	<div class="col-md-3 text-right"><h4 style="margin-top:0px; font-weight:600;">Parent Category:</h4></div>
    	<div class="col-md-7">
            <select id="categoryID" style="padding:8px;" class="modalInputField">
            @foreach($categories as $category)
                <option value="{{{ $category->id }}}">{{{ $category->name }}}</option>
            @endforeach
            </select>

        </div>
    	<div class="col-md-1">&nbsp;</div>
    </div>

    <div class="row" style="margin-top:30px;margin-bottom:20px;">
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-3 text-right"><h4 style="margin-top:0px; font-weight:600;">Subcategory Name:</h4></div>
        <div class="col-md-7"><input type="text" id="newCategoryName" class="modalInputField"/></div>
        <div class="col-md-1">&nbsp;</div>  
    </div>

@endsection





@section('action')
<div class="btn btn-border" id="createNewCategoryButton"><h4>CREATE</h4></div>
@endsection

@section('inline-script')
<script>
	$('#createNewCategoryButton').click(function() {
	 	$.ajax({
            url: "{{url('/stock/modal')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'newSubcategory',
                parentCategory: $('#categoryID').val(),
                inputField: $('#newCategoryName').val(),

            },
            success: function(response) {

			location.reload();              

            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });	
	});
</script>
@endsection