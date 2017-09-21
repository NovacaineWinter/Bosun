@extends('layouts.app')

@section('content')
<div id="modalcontainer"></div>
<div class="container">

	<table class="data-table">
		<tr>
			<th>Chandlery Value&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&pound;{{{  $data['storesValue']  }}}</th>
		</tr>
	</table>

	<table class="data-table">
		<tr>
			<th>Project Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;Value</th>
		</tr>
		@foreach($data['projects'] as $project)
		<tr>
			<td>{{{  $project['project']->name  }}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td>&pound; {{{  $project['wip']  }}}</td>
		</tr>
		@endforeach
	</table>


</div>



@endsection
