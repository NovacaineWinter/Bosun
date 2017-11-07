<div class="container">
    <div class="col-md-12">
        <table id="stocktable" style="width:100%;">
            <thead>               
                <tr>                
                    <!--<th>Thumbnail</th>-->
                    <th class="rightborder topborder"><h4>Stock Code</h4></th>
                    <th class="rightborder topborder"><h4>Name</h4></th>
                    <th class="rightborder topborder"><h4>Location</h4></th>         
                    <th class="rightborder topborder"><h4>Qty reported</h4></th>           
                    <th class="rightborder topborder"><h4>Qty on Shelf</h4></th>
                    <th class="rightborder topborder"><!-- Update --></th>
                </tr>

            </thead>
            <tbody id="stockSearchResultTarget">

            <!--  This section is the same as list stock items but is not pulled in by ajax here, is it part of pageload -->
            <?php $i=0; ?>
            	@foreach($itemsToCheck as $item)
	                <tr id="row-for-item{{{ $item->id }}}" class="ajaxstockitem topborder <?php if($i%2){echo 'oddrow';}else{echo 'evenrow';} ?>" style="text-align: center;">  

	                    <td class="rightborder">
	                        {{{ $item->supplierCodes->sortByDesc('prefered')->first()->code }}}
	                    </td>  

	                    <td class="rightborder">
	                        {{{ $item->name }}}
	                    </td>

	                    <td class="rightborder"> 
	                    	{{{ $item->building }}}-{{{ $item->bay }}}-{{{ $item->shelf }}}
	                    </td>

	                    <td class="rightborder">
	                        {{{ $item->qtyInStock }}}
	                    </td>

	                    <td class="rightborder">
	                        <input type="number" style="text-align: center;" value="{{{ $item->qtyInStock }}}" min="0" id="actualStockForItem{{{ $item->id }}}">
	                    </td>

	                    <td class="stock-item-detail-button btn btn-info btn-lg" style="cursor:pointer;margin-top:4px" onClick="stockTakeItem({{{ $item->id }}});">Confirm</td>
	                </tr>

	                <?php $i++; ?>
                @endforeach

                


            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript">
	
	function stockTakeItem(id){
		$.ajax({
		    url: "{{url('/stock/check/update')}}",
		    method: 'GET',
		    data: {
		        item: id,
		        qty: $('#actualStockForItem'+id).val(),
		    },
		    success: function(response) {
		    	if(response['status']==1){
		    		$("#row-for-item"+id).css('display','none');
		    	}		        
		    },
		    
		    error: function(response) {
		        console.log('There was an error - it was:');
		        console.dir(response);
		    }
		}); 
	}

</script>

