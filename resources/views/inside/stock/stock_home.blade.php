<?php 
//use Illuminate\Foundation\Auth\User as Authenticatable;
use App\supplier;
use App\stockCategory;



//get all stock categories
$categories = stockCategory::all();
$suppliers = supplier::all()->sortBy('name');


//get all suppliers



?>

@extends('layouts.app')

@section('content')
<div id="modalcontainer"></div>
<div class="container">
<a href="{{{ url('projects') }}}" class="pull-right btn-border btn-lg">View Projects</a>
<a href="{{{ url('stock/value') }}}" class="pull-right btn-border btn-lg">View Financials</a>
    <div class="col-md-12">
        <table id="stocktable" style="width:100%;">
            <thead>
                <tr>
                    <th><div class="center-block text-center btn" onclick="openModal('newItem');"><h5>Add New Item</h5></div></th>
                    <th><div class="center-block text-center btn" onclick="openModal('newCategory');"><h5>Add New Category</h5></div></th>
                    <th><div class="center-block text-center btn" onclick="openModal('newSubcategory');"><h5>Add New Subcategory</h5></div></th>
                    <th><div class="center-block text-center btn" onclick="openModal('newSupplier');"><h5>Add New Supplier</h5></div></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>                
                    <!--<th>Thumbnail</th>-->
                    <th class="rightborder topborder"><h4>Name</h4></th>
                    <th class="rightborder topborder"><h4>Category</h4></th>
                    <th class="rightborder topborder"><h4>Subcategory</h4></th>
                    <th class="rightborder topborder"><h4>Supplier</h4></th>
                    <th class="rightborder topborder"><h4>Qty</h4></th>
                    <th class="topborder">All<br><input type="checkbox" id="allCheckBox" onChange="filterFormByAjax();"></th>
                </tr>
                <tr>
                    <!--<th>Thumbnail</th>-->

                    <td class="rightborder text-center bottomborder">
                        <!-- Stock Name Search -->
                        <input id="stocksearch" onkeydown="searchkeydown(event)" style="margin: 5px 0px 20px 0px;"  type="text" name="stock_keyword">
                    </td>

                    <td class="rightborder text-center bottomborder" target="#category-filter">
                        <!--  Category filter  -->
                        <select id="category-filter" class="bosun-select" onchange="categoryOnChange()" name="category">
                            <option value="0">All</option>
                            @foreach($categories as $category)
                                <option value="{{{ $category->id }}}"> {{{ $category->name }}}</option>
                            @endforeach
                        </select>
                        
                    </td>

                    <td class="rightborder text-center bottomborder">
                        <!--  Subcategory filter  -->
                        <select id="subcategory-filter" class="bosun-select" onchange="subcategoryOnChange()" name="subcategory">
                            <option value="0">Select Category</option>                            
                        </select>
                    </td>

                    <td class="rightborder text-center bottomborder">
                        <!--  Supplier filter  -->
                        <select id="supplier-filter" class="bosun-select" onchange="supplierOnChange()" name="category">
                            <option value="0">All</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{{ $supplier->id }}}"> {{{ $supplier->name }}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="rightborder bottomborder"><!-- QTY --></td>
                    <td class="bottomborder"><!-- View button --></td>

                </tr>
            </thead>
            <tbody id="stockSearchResultTarget"><!-- To be populated by Ajax --></tbody>
        </table>
    </div>
</div>


<script>
    //  functions to sort out filters  -->

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{csrf_token() }}'
    }
});

var editSelectTimeout;

$( document ).ready(function(){

    $('body').css('background-color','#fafafa');
    $('#subcategory-filter').css("visibility", "hidden");

   
    //ajax to populate the initial stock list

    $.ajax({
        url: "{{url('/stock/search')}}",
        method: 'GET',
        data: {
            ajaxmethod: 'stockSearch',
        },
        success: function(response) {
            $('#stockSearchResultTarget').html(response);                        

        },
        error: function(response) {
            console.log('There was an error - it was:');
            console.dir(response);
        }
    });

});



    var debounce;

    /*
    function stockSearchDown(){
        //keydown function - kill timeout
        console.log('clear timeout on keydown');
        clearTimeout(debounce);
    }

    function stockSearchUp(){
        //keyup function - set timeout
        keyword=$('#stocksearch').val();
            clearTimeout(debounce);
        if(keyword != ''){
            debounce=setTimeout(function() {
                console.log('Finished Typing - getting search results for keyword :'+keyword);
                filterFormByAjax();
            },400);  
        }else{
             debounce=setTimeout(function() {
                console.log('Finished Typing - getting search results with no keyword');
                filterFormByAjax();
            },2000);  
        }       


    }*/

    function searchkeydown(event) {

        if (event.keyCode == 13) {   

            event.preventDefault();

            filterFormByAjax();
                   
        }
    }


    function categoryOnChange(){

        if($('#category-filter').val()!=0){

            $.ajax({
                url: "{{url('/stock/insert-select-menu')}}",
                method: 'GET',
                data: {
                    ajaxmethod: 'getSubcategoryOptionsOnly',
                    parentID:      $('#category-filter').val(),
                },
                success: function(response) {              

                    //$("#subcategory-filter").html('<option value="">All</option>');
                    $("#subcategory-filter").html(response);

                },
                error: function(response) {
                    console.log('There was an error - it was:');
                    console.dir(response);
                }
            });       
            $('#subcategory-filter').css("visibility", "visible");
        }else{
            $('#subcategory-filter').val("");
            $('#subcategory-filter').css("visibility", "hidden");
        }

        filterFormByAjax();
    }

    function subcategoryOnChange(){
        filterFormByAjax();
    }

    function supplierOnChange(){
        //alert('Supplier change to '+$('#supplier-filter').val());
        filterFormByAjax(); 
    }

    function filterFormByAjax(){

        $('#stockSearchResultTarget').html('');
        
        if($('#stocksearch').val().length>0){
             keywordToSend=$('#stocksearch').val();
        }else{
            keywordToSend='';
        }

        if($('#category-filter').val()!=0){
            categoryFilterToSend = $('#category-filter').val();            
        }else{
            categoryFilterToSend='';
        }

        if($('#subcategory-filter').val()!=0){
            subcategoryFilterToSend = $('#subcategory-filter').val();            
        }else{
            subcategoryFilterToSend='';
        }
        
        if($('#supplier-filter').val()!=0){
            supplierFilterToSend = $('#supplier-filter').val();            
        }else{
            supplierFilterToSend='';
        }

        if($('#allCheckBox').val()!=0){
            all = true;
        }else{
            all = false;
        }

        console.log('ajax going');

        $.ajax({
            url: "{{url('/stock/search')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'stockSearch',
                stockKeyword:      keywordToSend,
                category_filter:    categoryFilterToSend,
                subcategory_filter: subcategoryFilterToSend,
                supplier_filter:    supplierFilterToSend,
                showAll:            all
            },
            success: function(response) {
                $('#stockSearchResultTarget').html(response);
                console.log('ajax success');
            },
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });        
    }


    function openModal(method,targetID=''){

        $.ajax({
            url: "{{url('/stock/modal')}}",
            method: 'GET',
            data: {
                ajaxmethod: method,
                targetID: targetID,
            },
            success: function(response) {
                $('#modalcontainer').html(response);

            },
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });        
    }
</script>
@endsection
