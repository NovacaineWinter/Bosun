<?php
use App\stock;
use App\stockCategory;
use App\supplier;
use App\vatRate;
use App\config;

$config= new config;

$vatRates = vatRate::all();
$suppliers = supplier::all();
$categories= stockCategory::all();

?>
@extends('inside.stock.modal_template')

@section('title')
	Create New Stock Item
@endsection

@section('content')

<div class="row">
    <div class="col-sm-6">
        <!--  Name Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Name:
                </h5>
            </div>
            <div class="modalInputContainer">
                <input type="text" id="newItemName"/>  
            </div>
        </div>


    <!--  Category Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Category:
                </h5>
            </div>
            <div class="modalInputContainer">

                <select id="categoryID" class="modalInputField">
                    <option value="0">...</option>
                    @foreach($categories as $category)
                        <option value="{{{ $category->id }}}">{{{ $category->name }}}</option>
                    @endforeach
                </select> 

            </div>
        </div>


    <!--  Subcategory Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Subcategory:
                </h5>
            </div>
            <div class="modalInputContainer">
                <select id="subcategoryID" class="modalInputField">
                    <option value="0">Select Category First</option>
                </select>
            </div>
        </div>



    <!--  Qty in stock Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                   Qty in stock:
                </h5>
            </div>
            <div class="modalInputContainer">
               <input type="number" id="qtyInStock">
            </div>
        </div>


    <!--  ReOrder Qty Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Re-Order at:
                </h5>
            </div>
            <div class="modalInputContainer">
               <input type="number" id="reorderQty">
            </div>
        </div>


    <!--  orderTo Qty Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Restock to:
                </h5>
            </div>
            <div class="modalInputContainer">
               <input type="number" id="orderToQty">
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        

    <!--  Supplier Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Supplier:
                </h5>
            </div>
            <div class="modalInputContainer">

                <select id="supplierID" class="modalInputField">
                    <option value="0">...</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{{ $supplier->id }}}">{{{ $supplier->name }}}</option>
                    @endforeach
                </select> 

            </div>
        </div>


    <!--  VAT Rate Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    VAT Rate:
                </h5>
            </div>
            <div class="modalInputContainer">



                <select id="vatRateID" onChange="clearPrices();" class="modalInputField">

                    @foreach($vatRates as $vatRate)
                        <option value="{{{ $vatRate->id }}}" multiplier="{{{$vatRate->multiplier}}}" >{{{ $vatRate->name.' ('.$vatRate->text_for_multiplier.')' }}}</option>
                    @endforeach
                </select> 

            </div>
        </div>

    <!--  Price Input  -->
        <div class="modalInputRow">
            <div class="form-group form-container">
                <div class="row">
                    <div class="col-xs-6 textbox-holder-top-left input-group">
                        <span class="input-group-addon topleftspan">&pound</span>
                        <input type="number" id="costEx" onChange="exCostChange()" class="form-control top-left-textbox required" placeholder="0.00">
                        <span class="input-group-addon middlespan">Cost Price Ex VAT</span>
                    </div>

                    <div class="col-xs-6 textbox-holder-top-right input-group">
                        <span class="input-group-addon middlespan" style="border-left:none;">&pound</span>
                        <input type="number" id="costInc" onChange="incCostChange()" class="form-control top-right-textbox required" placeholder="0.00">
                        <span class="input-group-addon toprightspan">Cost Price Inc VAT</span>
                    </div>
                </div>

                <div class="row">

                    <div class="col-xs-6 textbox-holder-bottom-left input-group">
                        <span class="input-group-addon leftspan">&pound</span>
                        <input type="number" id="retailEx" onChange="exRetailChange();" class="form-control bottom-left-textbox required" placeholder="0.00">
                        <span class="input-group-addon middlespan">Retail Price Ex VAT</span>
                    </div>
                    <div class="col-xs-6 textbox-holder-bottom-right input-group">
                        <span class="input-group-addon middlespan" style="border-left:none;">&pound</span>
                        <input type="number" id="retailInc" onChange="incRetailChange();" class="form-control bottom-right-textbox required" placeholder="0.00">
                        <span class="input-group-addon rightspan">Retail Price Inc VAT</span>
                    </div>
                </div>
            </div>
        </div>

    <!--  Stock Code Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Supplier Stock Code:
                </h5>
            </div>
            <div class="modalInputContainer">
                <input type="text" id="supplierStockCode">             
            </div>
        </div>



    <!--  Description Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Description:
                </h5>
            </div>
            <div class="modalInputContainer">
                <textarea  id="itemDescription" rows="4" cols="30"/></textarea>             
            </div>
        </div>

    </div>

</div>




<!--  Location Input  -->

    <div class="modalInputRow">
        <div class="ModalInputLabel" style="width:15%;">
            <h5>
                Location Code:
            </h5>
        </div>
        <div class="modalInputContainer" style="width:70%;">
            @if($config->boolean('location_building')) <input class="col-xs-2" type="text" placeholder="Building" id="locationBuilding"> @endif 
            @if($config->boolean('location_isle'))<input class="col-xs-2" type="text" placeholder="Isle" id="locationIsle"> @endif 
            @if($config->boolean('location_side'))<input class="col-xs-2" type="text" placeholder="Side" id="locationSide"> @endif 
            @if($config->boolean('location_bay'))<input class="col-xs-2" type="text" placeholder="Bay" id="locationBay"> @endif 
            @if($config->boolean('location_shelf'))<input class="col-xs-2" type="text" placeholder="Shelf" id="locationShelf"> @endif 
            @if($config->boolean('location_position')<input class="col-xs-2" type="text" placeholder="Position" id="locationPosition"> @endif 

        </div>
    </div>
@endsection





@section('action')
<div class="btn btn-border" id="createNewStockItem"><h4>CREATE</h4></div>
@endsection

@section('inline-script')

<script>


function exCostChange(){
    exVATCost=$('#costEx').val();

    if(exVATCost!=''){

        incVatCost=exVATCost*$('#vatRateID option:selected').attr('multiplier');
        roundedIncVatCost=(Math.floor(incVatCost*100))/100;
        $('#costInc').val(roundedIncVatCost);

    }
}

function incCostChange(){
    incVATCost=$('#costInc').val();

    if(incVATCost!=''){

        exVatCost=incVATCost/$('#vatRateID option:selected').attr('multiplier');
        roundedExVatCost=(Math.floor(exVatCost*100))/100;
        $('#costEx').val(roundedExVatCost);

    }
}

function incRetailChange(){
    incVATRetail=$('#retailInc').val();

    if(incVATRetail!=''){

        exVatRetail=incVATRetail/$('#vatRateID option:selected').attr('multiplier');
        roundedExVatRetail=(Math.floor(exVatRetail*100))/100;
        $('#retailEx').val(roundedExVatRetail);

    }
}

function exRetailChange(){
    exVATRetail=$('#retailEx').val();

    if(exVATRetail!=''){

        incVatRetail=exVATRetail*$('#vatRateID option:selected').attr('multiplier');
        roundedIncVatRetail=(Math.floor(incVatRetail*100))/100;
        $('#retailInc').val(roundedIncVatRetail);

    }
}

function clearPrices(){
    $('#costEx').val('');
    $('#costInc').val('');
    $('#retailEx').val('');
    $('#retailInc').val('');
}


$('#categoryID').change(function() {
    //the category has changed so now lets populate the subcategory options
    if($(this).val()!="0"){
        target=$(this);
        //make sure it isn't default input for category
        $.ajax({
            url: "{{url('/stock/insert-select-menu')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'getSubcategoryOptionsOnly',
                parentID:      target.val(),
            },
            success: function(response) {                

                $("#subcategoryID").html(response);

            },
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        });       

    }else{
        $("#subcategoryID").html('<option value="0">Select Category First</option>');
    }
});



	$('#createNewStockItem').click(function() {

        stockLocation=[];

        if($('#locationBuilding').length){
            stockLocation['building']=$('#locationBuilding').val();
        }
        if($('#locationIsle').length){
            stockLocation['isle']=$('#locationIsle').val();
        }
        if($('#locationside').length){
            stockLocation['side']=$('#locationside').val();
        }
        if($('#locationBay').length){
            stockLocation['bay']=$('#locationBay').val();
        }
        if($('#locationshelf').length){
            stockLocation['shelf']=$('#locationshelf').val();
        }
        if($('#locationPosition').length){
            stockLocation['position']=$('#locationPosition').val();
        }




	 	$.ajax({
            url: "{{url('/stock/modal')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'newItem',
                dataSubmitted:  '1',
                stockLocation: stockLocation,
                name:               $('#newItemName').val(),
                category_id:        $('#categoryID').val(),
                subcategory_id:     $('#subcategoryID').val(),
                qtyInStock:         $('#qtyInStock').val(),
                reorderQty:         $('#reorderQty').val(),
                orderToQty:         $('#orderToQty').val(),
                retailEx:           $('#retailEx').val(),
                retailInc:          $('#retailInc').val(),
                vatRateID:          $('#vatRateID').val(),
                description:        $('#itemDescription').val(),
                costEx:             $('#costEx').val(),
                costInc:            $('#costInc').val(),
                supplier_id:        $('#supplierID').val(),
                supplierStockCode:  $('#supplierStockCode').val(),

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