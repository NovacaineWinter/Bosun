<?php
use App\bookedOutPart;

$bookedOutPart = bookedOutPart::find($request->get('targetID'));
?>
@extends('inside.stock.modal_template')

@section('title')
    Modify Booked Out Stock
@endsection

@section('content')

    <!--  new Supplier Input  -->
        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h3>
                    Qty to Un-Book:
                </h3>
            </div>
            <div class="modalInputContainer">
            <input type="number" id="qtyToUnbookInput" style="width:100%;" placeholder="Qty To Unbook" min="0" max="{{{ $bookedOutPart->qty }}}">
            </div>
        </div>

@endsection





@section('action')
    <div class="btn btn-border" id="unbookParts"><h4>Un-Book</h4></div>
@endsection

@section('inline-script')
<script>

$('#qtyToUnbookInput').change(function() {
    if(parseFloat($(this).val())>parseFloat($(this).attr('max'))){
        $(this).val($(this).attr('max'));
    }
});



    $('#unbookParts').click(function() {

        $.ajax({
            url: "{{url('/stock/modal')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'unBookItem',
                targetID: {{{ $bookedOutPart->id }}} ,
                qtyToRemove: $('#qtyToUnbookInput').val(),

            },
            success: function(response) {                
                location.reload();

            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            },
        }); 
    });

   
</script>
@endsection