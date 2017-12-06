<?php
use App\stock;
use App\config;

$config = new config;

if(!isset($item)){
    $item=stock::find($request->get('stockID'));
}
?>

<div class="row itemdetailrow">
    <div class="col-sm-8">

        <div class="skinnyrow cleafix">
            <div class="col-md-4 divcell topborder rightborder bottomborder leftborder" style="text-align:right;">Retail Price</div>
            <div class="col-md-4 divcell topborder rightborder bottomborder">
                &pound;
                <input type="number" style="width:40%;text-align:right;" class="updateablenumber" value="{{{ $item->retailEx }}}" targetID="{{{ $item->id }}}" method="retailEx" otherinfo="exvatchange" readonly="readonly">
                Ex VAT
            </div>
            <div class="col-md-4 divcell topborder rightborder bottomborder">
                &pound;
                <input type="number" style="width:40%;text-align:right;" class="updateablenumber" value="{{{ $item->retailInc }}}" targetID="{{{ $item->id }}}" method="retailInc" otherinfo="incvatchange" readonly="readonly">
                Inc VAT
            </div>
        </div> 
        <div class="row" style="min-height:15px;">&nbsp;</div>

        <div class="skinnyrow clearfix">
            
            <div class="col-md-4 divcell topborder rightborder bottomborder leftborder">
                <div class="col-xs-6">In Stock:</div>
                <div class="col-xs-6">{{{ $item->qtyInStock }}}</div>
            </div>
            <div class="col-md-4 divcell topborder rightborder bottomborder">
                <div class="col-xs-6">Reorder At:</div>
                <div class="col-xs-6">
                    <input type="number" class="updateablenumber text-center" targetID="{{{ $item->id }}}" method="reorderQty" value="{{{ $item->reorderQty }}}" readonly="readonly">
                </div>
            </div>
            <div class="col-md-4 divcell topborder rightborder bottomborder">
                <div class="col-xs-7">Restock To:</div>
                <div class="col-xs-5">
                    <input type="number" class="updateablenumber text-center" targetID="{{{ $item->id }}}" method="orderToQty" value="{{{ $item->orderToQty }}}" readonly="readonly">
                </div>
            </div>

        </div>     

    <div class="row" style="min-height:40px;">&nbsp;</div>

    	<div class="row">    		
            <table style="width:100%; margin:10px;border: 1px solid #e7e7e7;">
                <thead>
                    <tr>
                        <th class="rightborder"><h4>Prefered</h4></th>
                        <th class="rightborder"><h4>Part Number</h4></th>
                        <th class="rightborder"><h4>Ex VAT</h4></th>
                        <th class="rightborder"><h4>Inc VAT</h4></th>
                        <th class="rightborder"><h4>Margin</h4></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($item->supplierCodes->sortByDesc('prefered') as $code)
                    <?php 
                        if($item->retailEx>0 && $code->netCost>0){
                            $margin=round((($item->retailEx/$code->netCost)-1)*100);  
                        }else{
                            $margin=0;
                        } 
                        if($code->prefered==1){$selected=true;}else{$selected=false;}
                        
                    ?>
                    <tr>
                        <td class="topborder preferedSupplierRadio rightborder text-center">
                        
                        <input type="radio" onChange="changePreferedSupplier({{{$item->id}}},$(this));" id="prefereSupplierRadio-{{{ $code->id }}}" name="{{'item-'.$item->id.'-prefered'}}" value="{{{$code->id}}}" <?php if($selected){echo 'checked';} ?>>
                            
                        <label for="prefereSupplierRadio-{{{ $code->id }}}">{{{$code->supplier->name }}}</label>

                        </td>

                        <td class="topborder rightborder">
                        <input type="text" class="updateabletextbox" targetID="{{{ $code->id }}}" method="supplierPartNumber" value="{{{ $code->code }}}" readonly />
                        </td>
                        <td class="topborder rightborder">
                            &pound;
                            <input type="number" class="updateablenumber text-center" style="max-width: 75px;" targetID="{{{ $code->id }}}" method="supplierNetCostChange" value="{{{ $code->netCost }}}" readonly="readonly">
                        </td>
                        <td class="topborder rightborder">
                            &pound;
                            <input type="number" class="updateablenumber text-center" style="max-width: 75px;" targetID="{{{ $code->id }}}" method="supplierGrossCostChange" value="{{{ $code->grossCost }}}" readonly="readonly">
                        </td>
                        <td class="topborder text-right rightborder <?php if($margin<0){echo'losspercent';} ?>">{{{ $margin }}} %</td>
                        @if(!$code->prefered)
                            <td class="topborder itemButton" style="color:red;" method="deleteSupplierForItem" targetID="{{{ $code->id }}}" > &#10006;</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <span  class="add-new-supplier btn pull-right" onclick="openModal('newSupplierForItem',{{{ $item->id }}});" stockID="{{{ $item->id }}}">Add New Supplier</span>      
         

    	</div>


        <div id="position-table-holder" class="row" style="padding-left:30px;">
            <div class="row">

                @if($config->boolean('location_building'))
                    <div class="col-xs-2 text-center allborder">Building</div>
                @endif

                @if($config->boolean('location_isle'))
                    <div class="col-xs-2 text-center allborder">Isle</div>
                @endif

                @if($config->boolean('location_side'))
                    <div class="col-xs-2 text-center allborder">Side</div>
                @endif  

                @if($config->boolean('location_bay'))
                    <div class="col-xs-2 text-center allborder">Bay</div>
                @endif  

                @if($config->boolean('location_shelf'))
                    <div class="col-xs-2 text-center allborder">Shelf</div>
                @endif                         

                @if($config->boolean('location_position'))
                    <div class="col-xs-2 text-center allborder">Position</div>
                @endif                   

            </div>

            <div class="row">

                @if($config->boolean('location_building'))
                    <div class="col-xs-2 text-center allborder">
                        <input type="text" class="updateabletextbox text-center" targetID="{{{ $item->id }}}" method="stockLocationBuilding" value="{{{ $item->building }}}" readonly="readonly">
                    </div>
                @endif

                @if($config->boolean('location_isle'))
                    <div class="col-xs-2 text-center allborder">
                        <input type="text" class="updateabletextbox text-center" targetID="{{{ $item->id }}}" method="stockLocationIsle" value="{{{ $item->isle }}}" readonly="readonly">
                    </div>
                @endif

                @if($config->boolean('location_side'))
                    <div class="col-xs-2 text-center allborder">
                        <input type="text" class="updateabletextbox text-center" targetID="{{{ $item->id }}}" method="stockLocationSide" value="{{{ $item->side }}}" readonly="readonly">
                    </div>
                @endif  

                @if($config->boolean('location_bay'))
                    <div class="col-xs-2 text-center allborder">
                        <input type="text" class="updateabletextbox text-center" targetID="{{{ $item->id }}}" method="stockLocationBay" value="{{{ $item->bay }}}" readonly="readonly">
                    </div>
                @endif  

                @if($config->boolean('location_shelf')) 
                    <div class="col-xs-2 text-center allborder">
                        <input type="text" class="updateabletextbox text-center" targetID="{{{ $item->id }}}" method="stockLocationShelf" value="{{{ $item->shelf }}}" readonly="readonly">
                    </div>
                @endif                         

                @if($config->boolean('location_position'))
                    <div class="col-xs-2 text-center allborder">
                        <input type="text" class="updateabletextbox text-center" targetID="{{{ $item->id }}}" method="stockLocationPosition" value="{{{ $item->positon }}}" readonly="readonly">
                    </div>
                @endif                   
                                
            </div>            

        </div>

    </div>
    <div class="col-sm-4">

    <!--  VAT Selector  -->
        <div class="row">
            <div class="col-xs-6">VAT Rate:</div>
            <div class="col-xs-6" id="vatRateInfo" multiplier="{{{ $item->vatRate->multiplier }}}">{{{ $item->vatRate->name }}}</div>
        </div>

    <!--  Description textarea  -->

        <h4>Description</h4>
        <textarea class="updateabletextbox" style="width:100%;" rows="8" targetID="{{{ $item->id }}}" method="stockDescription" readonly="readonly">
        {{{ $item->description }}}
        </textarea>


        <!--  Buttons  -->
        <div class="row" style="padding-left:20px;">
            @if($item->is_highlighted)
                <div class="btn btn-border blue-bg itemButton"  method="dehighlightItem" targetID="{{{ $item->id }}}">De-Highlight</div>
            @else
                <div class="btn btn-border orange-bg itemButton" method="highlightItem" targetID="{{{ $item->id }}}">Highlight</div>
            @endif
            
            <div class="btn btn-border red-bg"   onClick="openModal('bookOutItem',{{{ $item->id }}});"  targetID="{{{ $item->id }}}">Book Out</div>
            <div class="btn btn-border green-bg" onClick="openModal('addStockItem',{{{ $item->id }}});" targetID="{{{ $item->id }}}">Add Stock</div>
        </div>



    </div>
</div>

<script>



$('.updateabletextbox').dblclick(function() {
    $(this).prop('readonly',false);
});

$('.updateabletextbox').blur(function(){
    $.ajax({
        url: "{{url('/stock/update')}}",
        method: 'GET',
        data: {
            ajaxmethod: $(this).attr('method'),
            targetID:   $(this).attr('targetID'),
            value:      $(this).val(),

        },
        success: function(response) {
            $('.updateabletextbox').attr('readonly','readonly');
            $("#item-detail-{{{$item->id}}}").html(response);
        },
        
        error: function(response) {
            console.log('There was an error - it was:');
            console.dir(response);
        }
    });
});


$('.updateabletextbox').keypress(function(event) {
    if (event.keyCode == 13) {                                    
        event.preventDefault();

        //detected enter on the input textbox
            //send off an ajax request to update the model
                //set input to readonly on success

        $.ajax({
            url: "{{url('/stock/update')}}",
            method: 'GET',
            data: {
                ajaxmethod: $(this).attr('method'),
                targetID:   $(this).attr('targetID'),
                value:      $(this).val(),

            },
            success: function(response) {
                $('.updateabletextbox').attr('readonly','readonly');
                $("#item-detail-{{{$item->id}}}").html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });
    }
});


$('.itemButton').click(function() {

ajmethod=$(this).attr('method');
target=$(this).attr('targetID');

    $.ajax({
            url: "{{url('/stock/update')}}",
            method: 'GET',
            data: {
                ajaxmethod: ajmethod,
                targetID:   {{{ $item->id }}},  
                value:      1,
            },
            success: function(response) {  
                $("#item-detail-{{{$item->id}}}").html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });
});


$('.updateablenumber').dblclick(function() {
    if($(this).attr('readonly')=='readonly'){
        $(this).removeAttr('readonly');
        
    }                                
});


$('.updateablenumber').keypress(function(event) {
    if (event.keyCode == 13) {                                    
        event.preventDefault();

        //detected enter on the input textbox
            //send off an ajax request to update the model
                //set input to readonly on success

        $.ajax({
            url: "{{url('/stock/update')}}",
            method: 'GET',
            data: {
                ajaxmethod: $(this).attr('method'),
                targetID:   $(this).attr('targetID'),
                value:      $(this).val(),

            },
            success: function(response) {
                $('.updateablenumber').attr('readonly','readonly');
                $("#item-detail-{{{$item->id}}}").html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });
    }
});

$('.updateablenumber').blur(function() {
    $.ajax({
            url: "{{url('/stock/update')}}",
            method: 'GET',
            data: {
                ajaxmethod: $(this).attr('method'),
                targetID:   $(this).attr('targetID'),
                value:      $(this).val(),

            },
            success: function(response) {
                $('.updateablenumber').attr('readonly','readonly'); 
                $("#item-detail-{{{$item->id}}}").html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        }); 
});

    function changePreferedSupplier(itemid,inputclicked){

        $.ajax({
            url: "{{url('/stock/update')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'changePreferedSupplier',
                targetID:   itemid,
                value:      inputclicked.val(),

            },
            success: function(response) {               
                //update the item detail with the refreshed info
                $("#item-detail-{{{$item->id}}}").html(response);
            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        }); 
    }
</script>