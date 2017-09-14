<?php
use App\supplier;
$suppliers=supplier::all();
?>
@extends('inside.stock.modal_template')

@section('title')
    Add to Stock Qty
@endsection

@section('content')


        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Qty To add:
                </h5>
            </div>
            <div class="modalInputContainer">

                <input type="number" id="qtyToAddInput" min=0>

            </div>
        </div>

@endsection





@section('action')
<div class="btn btn-border" targetID="{{{ $request->get('targetID') }}}" id="addNewSupplierButton"><h4>Add</h4></div>
@endsection

@section('inline-script')

<script>

    $('#addNewSupplierButton').click(function() {
        itemIDvar=$('#addNewSupplierButton').attr('targetID');
        qtyAdded=$('#qtyToAddInput').val();


        $.ajax({
            url: "{{url('/stock/modal')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'addStockItem',
                itemID:     itemIDvar,   
                qtyToAdd: qtyAdded,

            },
            success: function(response) {
                $('#item-detail-{{{ $request->get('targetID') }}}').html(response);
                $('#modalcontainer').empty();

            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            }
        }); 
    });  

</script>
@endsection