
@extends('inside.stock.modal_template')

@section('title')
	Create New Supplier
@endsection

@section('content')
	<div class="col-md-1">&nbsp;</div>
	<div class="col-md-3 text-right"><h4 style="margin-top:0px; font-weight:600;">Supplier Name:</h4></div>
	<div class="col-md-7"><input type="text" id="newCategoryName" class="modalInputField"/></div>
	<div class="col-md-1">&nbsp;</div>
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
                ajaxmethod: 'newSupplier',
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