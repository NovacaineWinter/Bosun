<?php
use App\project;
use App\config;
$config = new config;

$defaultProjectIds =array();
$defaultProjectIds[]=$config->integer('lunchProjectId');

if($config->boolean('projects')){
    $defaultProjectIds[]=$config->integer('genericWorkProjectId');
}


$projects = Project::whereNotIn('id',$defaultProjectIds)->orderBy('created_at')->where('is_finished','=',0)->get();
?>




<h1>Projects</h1>
    <div class="col-sm-12 dashboard-table-holder">
        <div class="col-xs-10 col-xs-offset-1">
            <table>
            	<div class="row">
	                <div class="col-sm-12">
	                    @if($config->boolean('projects'))
	                        <div method="create_project" class="btn btn-border ajax-clickable">+ Create New Project</div>
	                    @else
	                        <div method="upgrade-subscription" class="btn btn-border ajax-clickable"><h4>Upgrade Bosun</h4></div>
	                    @endif
	                </div>
	            </div>
                <thead>
                	<tr>
	                    <th>Project</th>
	                    @if($config->boolean('has_logging'))
	                        <th>Hours</th>
	                        <th>Labour Cost</th>
	                    @endif
	                    @if($config->boolean('has_stock_control'))
	                        <th>Parts Cost</th>
	                    @endif
	                    <th></th>
                	</tr>
                </thead>
                <tbody>
                    @if($projects->count() > 0)
                        @foreach($projects as $project)
                            <tr>
                                <td class="dashboard-clickable" method="project-summary" target="{{{ $project->id }}}">{{{ $project->name  }}}</td>
                                @if($config->boolean('has_logging'))                                    
                                    <td>{{{  $project->totalHourSpend()  }}}</td>
                                    <td>&pound;{{{  $project->totalLabourCost()  }}}</td>
                                @endif
                                @if($config->boolean('has_stock_control'))
                                    <td>&pound;{{{  $project->totalCostOfStockBookedOut()  }}}</td>
                                @endif
                                <td><h3 class="dashboard-clickable" method="project-summary" target="{{{ $project->id }}}">Detail</h3></td>
                            <tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">There are no projects. Why not create one?</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            
        </div>
    </div>


<script>
	 $('.dashboard-clickable').click(function() {

        clickedMethod=$(this).attr('method');

        $.ajax({
            url: "{{url('/ajax')}}",
            method: 'GET',
            data: {
                ajaxmethod: clickedMethod,
                target: $(this).attr('target'),
            },
            success: function(response) {
                $('#dashboard-ajax-container').html(response);
                switch(clickedMethod) {
                    case 'project-summary':
                        $('.dashboard-nav-btn').removeClass('nav-selected');                        
                        break;
                }
            },
            error: function(response) {
                console.log('There was an error - it was:  '+response);
            }
        });
    });
</script>