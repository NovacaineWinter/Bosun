<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Response;
use App;
use App\project;

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

?>
<div id="backbutton" onclick="$('#activity-selector').css('visibility','hidden');location.reload();">
    &#171;
</div>
<div class="container" id="activity-selector">

    <h1>Hello {{{$user->fname}}}</h1>

    @if($user->logged_in)
        @if($user->on_lunch)

            <h2>You are currently on Break</h2>
            <div class="row">

                <div class="col-sm-6" >
                    <div class="btn btn-info btn-lg loggingbutton" method="logon" id="log-on">
                        Back to Work
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class=" btn btn-info btn-lg loggingbutton" method="logoff" id="log-off">
                        Log Off
                    </div>
                </div>
            </div>
        @else
            <h2>You are currently @if(CONFIG['projects']) working on {{{ $user->task->project->name }}} @endif @if(CONFIG['tasks']) doing {{{$user->task->name}}} @endif @if(!CONFIG['projects']&&!CONFIG['tasks'])logged on @endif</h2>

            <div class="row">
                

                @if(CONFIG['projects'] || CONFIG['tasks'])

                    <div class="col-sm-4">
                        <div class=" btn btn-info btn-lg loggingbutton userOnLunch" method="lunch" id="lunch">
                            Lunch Break
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="btn btn-info btn-lg loggingbutton userWorking" method="logon" id="log-on">
                            Change Job
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="btn btn-info btn-lg loggingbutton userLoggedOff" method="logoff" id="log-off">
                            Log Off
                        </div>
                    </div>
                @else
                    <div class="col-sm-6">
                        <div class="btn btn-info btn-lg loggingbutton userOnLunch" method="lunch" id="lunch">
                            Lunch Break
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="btn btn-info btn-lg loggingbutton userLoggedOff" method="logoff" id="log-off">
                            Log Off
                        </div>
                    </div>
                @endif

            </div>
        @endif
    @else
        <h2>You are currently logged off</h2>

        <div class="col-sm-6">
            <div class="btn btn-info btn-lg loggingbutton userWorking" method="logon" id="log-on">
                Log On
            </div>
        </div>

        <div class="col-sm-6">
            <div class="btn btn-info btn-lg loggingbutton userOnLunch" method="lunch" id="lunch">
                Lunch Break
            </div>
        </div>

    @endif

    <div class="col-sm-8 col-sm-offset-2 userLoggedOff" id="job-selection-holder">       
    
        <h2>Choose Job</h2>

        @if(CONFIG['workers_choose_project'])


        <script>     

            $(document).ready(function() {
                $('.accordionhidden').hide(0);
            });

            $('.project-accordion').click(function() {
                projectID = $(this).attr('projectID');
                $('#task-accordion-folds-for-project-id-'+projectID).slideToggle(400);
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
    });
</script>
</div>


