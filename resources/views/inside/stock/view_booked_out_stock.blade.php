<?php 
//use Illuminate\Foundation\Auth\User as Authenticatable;
use App\supplier;
use App\stockCategory;
use App\project;



$project = project::find($request->get('project_id'));

?>

@extends('layouts.app')

@section('content')

<div id="modalcontainer"></div>
<div class="container">
    <div class="row">
        <a href="{{{ url('stock') }}}" class="btn btn-lg btn-border pull-left">Back</a>
    </div>
    <div class="col-md-12">
        <table id="stocktable" style="width:100%;">
            <thead>               
                <tr>                
                    <!--<th>Thumbnail</th>-->
                    <th class="rightborder topborder"><h4>Name</h4></th>
                    <th class="rightborder topborder"><h4>Category</h4></th>
                    <th class="rightborder topborder"><h4>Subcategory</h4></th>
                    <th class="rightborder topborder"><h4>Supplier</h4></th>
                    <th class="rightborder topborder"><h4>Qty Booked Out</h4></th>
                    <th class="topborder"><!-- View button --></th>
                </tr>

            </thead>
            <tbody id="stockSearchResultTarget">

            <!--  This section is the same as list stock items but is not pulled in by ajax here, is it part of pageload -->
            <?php $i=0; ?>
            @if(!empty($project))
                @if(!empty($project->bookedOutParts)) 
                    @foreach($project->bookedOutParts as $itemCollection)
                    <?php $i++; 
                    $item = $itemCollection->item;
                    ?>
                        <tr id="row-for-item{{{ $item->id }}}" class="ajaxstockitem topborder <?php if($i%2){if($item->is_highlighted){echo 'darkorange-bg';}else{echo 'oddrow';}}else{if($item->is_highlighted){echo 'orange-bg';}else{echo 'evenrow';}} ?>">                
                            <!--<th>Thumbnail</th>-->
                            <td class="rightborder">
                                <input type="text" style="width:100%;" class="updateabletextbox" value="{{{ $item->name }}}" targetID="{{{ $item->id }}}" method="changeItemName" readonly>
                            </td>

                            <td id="item-{{{$item->id}}}-category-indicator-holder" class="rightborder categoryIndicator" itemID="{{{ $item->id }}}" targetID="{{{ $item->category->id }}}">
                                {{{ $item->category->name }}}
                            </td>

                            <td id="item-{{{$item->id}}}-subcategory-indicator-holder" class="rightborder subcategoryIndicator" itemID="{{{ $item->id }}}" parentID="{{{ $item->category->id }}}" targetID="{{{ $item->subCategory->id }}}">
                                {{{ $item->subCategory->name }}}
                            </td>

                            <td class="rightborder">@if(!empty($item->supplierCodes->sortByDesc('prefered')->first()->supplier->name)) {{{ $item->supplierCodes->sortByDesc('prefered')->first()->supplier->name }}}  @endif</td>
                            <td class="rightborder text-right">{{{ $itemCollection->qty }}}</td>
                            <td class="stock-item-detail-button btn" style="cursor:pointer;" onClick="openModal('unBookItem',{{{ $itemCollection->id }}});">Un-Book</td>
                        </tr>
                        
                        <tr class="rightborder">
                            <td colspan="7" class="text-centered to-reveal itemdetailcontainer" id="item-detail-{{{$item->id}}}">           
                                
                            </td>
                        </tr>

                    @endforeach
                @endif
            @endif
                


            </tbody>
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

    $( document ).ready(function(){

        $('body').css('background-color','#fafafa');
        $('#subcategory-filter').css("visibility", "hidden");
       
    });


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
