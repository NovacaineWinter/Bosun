<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Response;
use App;
use App\project;
use App\config;

$config = new config;

if(!empty($user->skills)){

    $projects = project::where('can_log_hours','=',1)

    ->whereHas('tasks', function ($query) use($user) {
        $query->whereIn('skill_id',$user->skills->pluck('id')->toArray());
    })

    ->whereHas('tasks', function ($query){
        $query->where('task_finished','=',0);
    })
    ->where('is_finished','=',0)
    ->get(); 

}else{

    $projects = array();
}
$numProjects = $projects->count();

$padding = ($numProjects-1)*125;
$paddingStr = $padding.'px';

?>


<div class="container" id="activity-selector" style="margin-top:{{{ $paddingStr }}};">
<div id="backbutton" onclick="$('#activity-selector').css('visibility','hidden');location.reload();" style="top:initial;">
    &#171;
</div>

    <h1>Hello {{{$user->fname}}}</h1>

    @if($user->logged_in)
        @if($user->on_lunch)

            <h2>You are currently on Break</h2>
            <div class="row">

                <div class="col-sm-6">
                    <div class=" btn btn-info btn-lg loggingbutton userLoggedOff" method="setStatus" id="log-off" userID="{{{ $user->id }}}" lunch="0" loggedIn="0" taskID="1">
                        Log Off
                    </div>
                </div>


            </div>
        @else
            <h2>Status: @if($config->boolean('projects')) working on {{{ $user->task->project->name }}} @endif @if($config->boolean('tasks')) -  {{{$user->task->name}}} @endif @if(!$config->boolean('projects'))&&!$config->boolean('tasks'))logged on @endif</h2>

            <div class="row">              


                    <div class="col-sm-6">
                        <div class=" btn btn-info btn-lg loggingbutton userOnLunch" method="setStatus" id="lunch" userID="{{{ $user->id }}}" lunch="1" loggedIn="1" taskID="1">
                            Lunch Break
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="btn btn-info btn-lg loggingbutton userLoggedOff" method="setStatus" id="log-off" userID="{{{ $user->id }}}" lunch="0" loggedIn="0" taskID="1">
                            Log Off
                        </div>
                    </div>



            </div>
        @endif
    @else
        <h2>You are currently logged off</h2>


        <div class="col-sm-6">
            <div class="btn btn-info btn-lg loggingbutton userOnLunch" method="setStatus" id="lunch" userID="{{{ $user->id }}}" lunch="1" loggedIn="1" taskID="1">
                Lunch Break
            </div>
        </div>

    @endif

    <div class="col-sm-8 col-sm-offset-2 userLoggedOff" id="job-selection-holder">       
    
        <h1>Choose Job</h1>

        @if($config->boolean('workers_choose_project'))


        <script>     

            $(document).ready(function() {
                $('.accordionhidden').hide(0);
                $('.accordionhidden').attr('folded',1);
            });

            $('.project-accordion').click(function() {

                $('.accordionhidden').slideUp(400);                

                if($(this).attr('folded')==1){                   
                    $('html, body').animate({
                        scrollTop: $("#job-selection-holder").offset().top
                    }, 500);

                    projectID = $(this).attr('projectID');
                    $('#task-accordion-folds-for-project-id-'+projectID).slideDown(400);
                    $(this).attr('folded',0);

                }else{

                    $('html, body').animate({
                        scrollTop: $("#ajax-target").offset().top
                    }, 500);                    
                    projectID = $(this).attr('projectID');
                    $('#task-accordion-folds-for-project-id-'+projectID).slideUp(400);
                    $(this).attr('folded',1);
                }



            });
        </script>

            @if(!empty($projects))

                @foreach($projects as $project)
                    @include('outside.logging.loop_templates.project_accordion', array('project'=> $project,'user'=>$user))
                @endforeach

            @else
                <h3>No Jobs Available</h3>

            @endif
        
        @else
            @if(!empty($projects))

                @foreach($projects->tasks as $task)
                    @include('outside.logging.loop_templates.task_rows', array('task'=> $task,'user'=>$user))
                @endforeach 
 
            @else
                <h3>No Jobs Available</h3>

            @endif
        @endif
    </div>





<script>
    $('.loggingbutton').click(function() {
        ajaxMethod=$(this).attr('method');
        if(ajaxMethod != 'wait'){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token() }}'
                }
            });

            if(ajaxMethod=='setStatus'){

                $.ajax({
                    url: "{{url('/logging/ajax')}}",
                    method: 'GET',
                    data: {
                        ajaxmethod: ajaxMethod,
                        userID:     $(this).attr('userID'),
                        lunch:      $(this).attr('lunch'),
                        loggedIn:   $(this).attr('loggedIn'),
                        taskID:     $(this).attr('taskID'),
                    },
                    success: function(response) {
                        $('#ajax-target').html(response);                                  

                    },
                    error: function(response) {
                        console.log('There was an error - it was:');
                        console.dir(response);
                    }
                });                

            }else{


                $.ajax({
                    url: "{{url('/logging/ajax')}}",
                    method: 'GET',
                    data: {
                        ajaxmethod: ajaxMethod,
                    },
                    success: function(response) {
                        $('#ajax-target').html(response);                                  

                    },
                    error: function(response) {
                        console.log('There was an error - it was:');
                        console.dir(response);
                    }
                });

            }
        }
    });
</script>
</div>


