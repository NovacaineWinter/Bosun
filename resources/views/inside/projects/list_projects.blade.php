<?php 
//use Illuminate\Foundation\Auth\User as Authenticatable;
use App\supplier;
use App\project;


//get all projects
$projects = project::where('can_book_parts_to','=',1)->get();

?>

@extends('layouts.app')





@section('content')

<div id="modalcontainer"></div>
    <div class="container">
    <div class="row">
        <a href="{{{ url('stock') }}}" class="btn btn-lg btn-border pull-left">Back</a>
    </div>
    @foreach($projects as $project)
        <a href="{{{ url('stock/booked-out-stock?project_id='.$project->id) }}}" class="btn-border btn btn-lg center-block">{{{ $project->name }}}</a><br><br>
    @endforeach
    </div>
</div>


<script>
    //nothing to see here
</script>

@endsection
