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
                        <th>Name</th>                        
                        <th>Status</th>
                        <th>Project</th>
                        <th>Task</th>
                    </tr>
                </thead>
                <tbody>
                    @if($config->boolean('has_logging'))
                        @if($workers->count()>0)   
                            
                            @foreach($workers as $worker)
                            <tr class="{{{ $worker->cssStatus }}}">
                                <td>{{{ $worker->fname.' '.$worker->lname }}}</td>
                                <td>@if($worker->logged_in && !$worker->on_lunch) Working @elseif($worker->logged_in && $worker->on_lunch) Lunch @else Logged Off @endif</td>
                                <td>{{{  $worker->hoursOvertimeThisMonth()  }}}</td>
                                <td>{{{  $worker->moneyEarnedThisMonth()  }}}</td>
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