<table class="bosun-table" id="stock-items-table">
	<thead>
		<tr>
			<th>Name</th>
		</tr>
	</thead>

	<tbody>
		@foreach($suppliers as $supplier)
			<tr>
				<td>{{{  $supplier->name  }}}</td>
			</tr>
		@endforeach

	</tbody>	
</table>