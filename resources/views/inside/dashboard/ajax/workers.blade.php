<?php

use App\User;
use App\config;
$config = new config;
$workers = User::where('can_log_hours','=',1)->where('is_active','=',1)->get();

?>
<h1>Workers</h1>
<div class="col-sm-12 dashboard-table-holder">
        <div class="col-xs-10 col-xs-offset-1">

            <table>
	            <div class="row">
	                <div class="col-sm-12">
	                    @if($config->boolean('has_logging'))
	                        <div method="worker_detail" class="btn btn-border ajax-clickable">+ Add Worker</div>
	                    @else
	                        <div method="upgrade-subscription" class="btn btn-border ajax-clickable"><h4>Upgrade Bosun</h4></div>
	                    @endif
	                </div>
	            </div>            	
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