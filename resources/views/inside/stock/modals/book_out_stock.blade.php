<?php
use App\supplier;
use App\project;
use App\stock;

$projects=project::where('can_book_parts_to','=','1')->get();
$suppliers=supplier::all();
$item = stock::find($request->get('targetID'));

?>
@extends('inside.stock.modal_template')

@section('title')
    Book Out Stock
@endsection

@section('content')


        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Qty To Book Out:
                </h5>
            </div>
            <div class="modalInputContainer">

                <input type="number" id="qtyToBookOutInput"  value="0" min="0" max="{{{ $item->qtyInStock }}}">

            </div>
        </div>

        <div class="modalInputRow">
            <div class="ModalInputLabel">
                <h5>
                    Project To Book Items To:
                </h5>
            </div>
            <div class="modalInputContainer">

                <select id="projectToBookItemsTo" style="padding:8px;" class="modalInputField">
                    @foreach($projects as $project)
                        <option value="{{{ $project->id }}}">{{{ $project->name }}}</option>
                    @endforeach
                </select>

            </div>
        </div>

@endsection





@section('action')
<div class="btn btn-border" targetID="{{{ $request->get('targetID') }}}" id="bookOutParts"><h4>Book Out</h4></div>
@endsection

@section('inline-script')
<script>


$('#qtyToBookOutInput').change(function() {
    if(parseFloat($(this).val())>parseFloat($(this).attr('max'))){
        $(this).val($(this).attr('max'));
    }
});




    $('#bookOutParts').click(function() {

        itemIDvar=$('#bookOutParts').attr('targetID');

        $.ajax({
            url: "{{url('/stock/modal')}}",
            method: 'GET',
            data: {
                ajaxmethod: 'bookOutItem',
                itemID: itemIDvar ,
                projectID: $('#projectToBookItemsTo').val(),
                qtyToRemove: $('#qtyToBookOutInput').val(),

            },
            success: function(response) {
                $('#item-detail-{{{ $request->get('targetID') }}}').html(response);
                $('#modalcontainer').empty();

            },
            
            error: function(response) {
                console.log('There was an error - it was:');
                console.dir(response);
            },
        }); 
    });

   
</script>
@endsection