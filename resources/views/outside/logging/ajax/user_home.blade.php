<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\project;
use App\tasks;
use Response;
use App;

$project=project::find($worker->project_id);
$skill=tasks::find($worker->task_id);

?>
<div class="container">

    <h1>Hello {{{$worker->fname}}}</h1>

    @if($worker->logged_in)
        @if($worker->on_lunch)

            <h2>You are currently on Break</h2>
            <div class="row">

                <div class="col-sm-6 btn btn-info btn-lg loggingbutton" method="logon" id="log-on">Back to Work</div>
                <div class="col-sm-6 btn btn-info btn-lg loggingbutton" method="logoff" id="log-off">Log Off</div>
            </div>
        @else
            <h2>You are currently @if(CONFIG['projects']) working on {{{$project->name}}} @endif @if(CONFIG['tasks']) doing {{{$skill->name}}} @endif @if(!CONFIG['projects']&&!CONFIG['tasks'])logged on @endif</h2>

            <div class="row">
                

                @if(CONFIG['projects'] || CONFIG['tasks'])
                    <div class="col-sm-4 btn btn-info btn-lg loggingbutton" method="lunch" id="lunch">Lunch Break</div>
                    <div class="col-sm-4 btn btn-info btn-lg loggingbutton" method="logon" id="log-on">Change Job</div>
                    <div class="col-sm-4 btn btn-info btn-lg loggingbutton" method="logoff" id="log-off">Log Off</div>
                @else
                    <div class="col-sm-6 btn btn-info btn-lg loggingbutton" method="lunch" id="lunch">Lunch Break</div>
                    <div class="col-sm-6 btn btn-info btn-lg loggingbutton" method="logoff" id="log-off">Log Off</div>
                @endif

            </div>
        @endif
    @else
        <h2>You are currently logged off</h2>
        <div class="col-sm-6 btn btn-info btn-lg loggingbutton" method="logon" id="log-on">Log On</div>
        <div class="col-sm-6 btn btn-info btn-lg loggingbutton" method="lunch" id="lunch">Lunch Break</div>

    @endif
<script>
    $('.loggingbutton').click(function() {
        ajaxMethod=$(this).attr('method');
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
    });
</script>
</div>


