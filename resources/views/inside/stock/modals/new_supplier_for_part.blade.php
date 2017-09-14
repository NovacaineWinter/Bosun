<?php
use App\supplier;
$suppliers=supplier::all();
?>
@extends('inside.stock.modal_template')

@section('title')
	Add Supplier
@endsection

@section('content')

    <!--  new Supplier Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Add Supplier:
                </h5>
            </div>
            <div class="modalInputContainer">

                <select id="supplierToAddID" class="modalInputField">
                    <option value="0">...</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{{ $supplier->id }}}">{{{ $supplier->name}}}</option>
                    @endforeach
                </select> 

            </div>
        </div>

@endsection





@section('action')
<div class="btn btn-border" id="addNewSupplierButton"><h4>Add to Item</h4></div>
@endsection

@section('inline-script')
<script>
	$('#addNewSupplierButton').click(function() {
	 	$.ajax({
            url: "{{url('/stock/modal')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'newSupplierForItem',
                itemID: {{{$request->get('targetID')}}},
                supplierID: $('#supplierToAddID').val(),

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