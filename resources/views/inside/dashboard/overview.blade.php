<?php
use App\project;
use App\User;
use App\config;
$config = new config;
$workers = User::where('can_log_hours','=',1)->where('is_active','=',1)->get();

$defaultProjectIds =array();
$defaultProjectIds[]=$config->integer('lunchProjectId');

if($config->boolean('projects')){
    $defaultProjectIds[]=$config->integer('genericWorkProjectId');
}


$projects = Project::whereNotIn('id',$defaultProjectIds)->get();
?>




<div class="col-sm-12 dashboard-table-holder">
    <h1>This Month</h1>
    <div class="col-sm-6">
        <div class="col-xs-10 col-xs-offset-1">
            <h4>Projects</h4>
            <table>
                <thead>
                    <th>Project</th>
                    @if($config->boolean('has_logging'))
                        <th>Hours</th>
                        <th>Labour Cost</th>
                    @endif
                    @if($config->boolean('has_stock_control'))
                        <th>Parts Cost</th>
                    @endif
                </thead>
                <tbody>
                    @if($projects->count() > 0)
                        @foreach($projects as $project)
                            <tr>
                                <td>{{{  $project->name  }}}</td>
                                @if($config->boolean('has_logging'))                                    
                                    <td>{{{  $project->hourSpendThisMonth()  }}}</td>
                                    <td>&pound;{{{  $project->labourCostThisMonth()  }}}</td>
                                @endif
                                @if($config->boolean('has_stock_control'))
                                    <td>&pound;{{{  $project->costOfStockBookedOutThisMonth()  }}}</td>
                                @endif
                            <tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">There are no projects. Why not create one?</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-12">
                    @if($config->boolean('projects'))
                        <div method="create_project" class="btn btn-border ajax-clickable">+ Create Project</div>
                    @else
                        <div method="upgrade-subscription" class="btn btn-border ajax-clickable"><h4>Upgrade Bosun</h4></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 fixed-dashboard-scrolling">
        <div class="col-xs-10 col-xs-ofdfset-1">
            <h4>Workers</h4>
            <table>
                <thead>
                    <tr>
                        <th></th>                        
                        <th>Hours</th>
                        <th>Overtime</th>
                        <th>Earnings</th>
                        <th>Timesheet</th>
                    </tr>
                </thead>
                <tbody>
                    @if($config->boolean('has_logging'))
                        @if($workers->count()>0)   
                            
                            @foreach($workers as $worker)
                            <tr>
                                <td>{{{ $worker->fname.' '.$worker->lname }}}</td>
                                <td>{{{  $worker->hoursWorkedThisMonth()  }}}</td>
                                <td>{{{  $worker->hoursOvertimeThisMonth()  }}}</td>
                                <td>{{{  $worker->moneyEarnedThisMonth()  }}}</td>
                                <td><div class="btn text-center">View</div></td>
                            </tr>

                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    There are no workers, Why not create one?
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="5">
                                Logging Module not enabled
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="row">
                <div class="col-sm-12">
                    @if($config->boolean('has_logging'))
                        <div method="worker_detail" class="btn btn-border ajax-clickable">+ Add Worker</div>
                    @else
                        <div method="upgrade-subscription" class="btn btn-border ajax-clickable"><h4>Upgrade Bosun</h4></div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
<script>
 $('.ajax-clickable').click(function() {

    clickedMethod=$(this).attr('method');

    $.ajax({
        url: "{{url('/ajax')}}",
        method: 'GET',
        data: {
            ajaxmethod: clickedMethod,
        },
        success: function(response) {
            $('#dashboard-ajax-container').html(response);
            
        },
        error: function(response) {
            console.log('There was an error - it was:  '+response);
        }
    });
});
</script>
