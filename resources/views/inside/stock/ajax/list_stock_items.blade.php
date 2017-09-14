<?php $i=0; ?>
@if(!empty($items)) 
    @foreach($items->sortByDesc('is_highlighted') as $item)
    <?php $i++; ?>
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
            <td class="rightborder text-right">{{{ $item->qtyInStock }}}</td>
            <td class="stock-item-detail-button" style="cursor:pointer;" clickStatus="collapsed" stockID="{{{ $item->id }}}">&#9668;</td>
        </tr>
        
        <tr class="rightborder">
        	<td colspan="7" class="text-centered to-reveal itemdetailcontainer" id="item-detail-{{{$item->id}}}">   		
        		
        	</td>
        </tr>

    @endforeach
@endif

<script>

changingCategory=false;
changingSubcategory=false;

$('.categoryIndicator').click(function() {
	if(!changingCategory){
		changingCategory=true;
		target=$(this);
	    $.ajax({
	        url: "{{url('/stock/insert-select-menu')}}",
	        method: 'GET',
	        data: {
	            ajaxmethod: 'categorySelect',
	            targetID:      $(this).attr('targetID'),
	            itemID:      $(this).attr('itemID'),

	        },
	        success: function(response) {
	            target.html(response); 
	        },
	        
	        error: function(response) {
	            console.log('There was an error - it was:');
	            console.dir(response);
	        }
	    });
	}

});

$('.subcategoryIndicator').click(function() {
	if(!changingSubcategory){	
		changingSubcategory=true;
		target=$(this);
		$.ajax({
	        url: "{{url('/stock/insert-select-menu')}}",
	        method: 'GET',
	        data: {
	            ajaxmethod: 'subcategorySelect',
	            targetID:      $(this).attr('targetID'),
	            parentID:    $(this).attr('parentID'),
	            itemID:      $(this).attr('itemID'),
	        },
	        success: function(response) {

	            target.html(response); 
	        },
	        
	        error: function(response) {
	            console.log('There was an error - it was:');
	            console.dir(response);
	        }
	    });
	}
	
});




$(".stock-item-detail-button").click(function() {

    if($(this).attr('clickStatus')=='collapsed'){
        
        //clear everything that is currently open
        $(".stock-item-detail-button").html('&#9668;');  
        $('.itemdetailcontainer').html('');
        $(".itemdetailcontainer").hide(100);

        $(this).html('&#9660;');                //set to downward pointing arrow to show menu open
        $(this).attr('clickStatus','expanded');

        itemID=$(this).attr('stockID');



        $.ajax({
            url: "{{url('/stock/itemdetail')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'stockItemDetail',
                stockID:      itemID,

            },
            success: function(response) {

                $('#item-detail-'+itemID).html(response);                             

                $('#item-detail-'+itemID).show(100);                

            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });

    }else{
        $(this).html('&#9668;');                //set to left pointing arrow to show menu closed
        $(this).attr('clickStatus','collapsed');
        $('#item-detail-'+itemID).hide(100);
        $('.itemdetailcontainer').empty();
        $('.itemdetailcontainer').hide(10);

    }


}); 





/* * * *   This code is also printed out on the item detail ajax call as it is requred there   * * * */

$('.updateabletextbox').dblclick(function() {
    $(this).prop('readonly',false);
});
$('.updateabletextbox').blur(function(){
    ajaxTargetID=$(this).attr('targetID');
    $.ajax({
        url: "{{url('/stock/update')}}",
        method: 'GET',
        data: {
            ajaxmethod: $(this).attr('method'),
            targetID:   ajaxTargetID,
            value:      $(this).val(),

        },
        success: function(response) {
            $('.updateabletextbox').attr('readonly','readonly');
            $("#item-detail-"+ajaxTargetID).html(response);
        },
        
        error: function(response) {
            console.log('There was an error - it was:');
            console.dir(response);
        }
    });
});


$('.updateabletextbox').keypress(function(event) {

    if (event.keyCode == 13) {                                    
        ajaxTargetID=$(this).attr('targetID');
        event.preventDefault();

        //detected enter on the input textbox
            //send off an ajax request to update the model
                //set input to readonly on success

        $.ajax({
            url: "{{url('/stock/update')}}",
            method: 'GET',
            data: {
                ajaxmethod: $(this).attr('method'),
                targetID:   ajaxTargetID,
                value:      $(this).val(),

            },
            success: function(response) {
                $('.updateabletextbox').attr('readonly','readonly');
                $("#item-detail-"+ajaxTargetID).html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });
    }
});



</script>
