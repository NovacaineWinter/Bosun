<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BOSUN DASHBOARD</title>


        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!--  Master Stylesheet  -->
        <link href="{{ url('css/style.css')}}" rel="stylesheet" type="text/css">

        <!--  git master Stylesheet  -->
        <link href="{{ url('css/data-output.css')}}" rel="stylesheet" type="text/css">

        <!--  jQuery from Google CDN  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!-- Bootstrap CSS  -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- Bootstrap JS  -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- jQuery UI CSS -->
        <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">-->

        <!-- jQuery UI JS -->
        <!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
        
    
</head>
<body>
    <div id="app" class="row no-gutters">
        <div class="col-xs-2" id="dashboard-sidebar">
            <div class="panel-group" id="accordion">


                <div method="workers" class="panel panel-default ajax-clickable">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a>Workers</a>
                        </h4>
                    </div>
                </div>


                <div method="projects" class="panel panel-default ajax-clickable">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Projects</a>
                        </h4>
                    </div>
                </div>



                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Add New...</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <ul class="list-group">
                            <li method="worker_detail" class="ajax-clickable list-group-item">New Member of Staff</li>
                            <li method="worker_detail" class="ajax-clickable list-group-item">New Shift</li>
                            <li method="worker_detail" class="ajax-clickable list-group-item">New Project</li>
                        </ul>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Settings</a>
                        </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                        <ul class="list-group">
                            <li method="worker_detail" class="ajax-clickable list-group-item">User Permissions</li>
                            <li method="worker_detail" class="ajax-clickable list-group-item">Bosun Configuration</li>
                            <li method="worker_detail" class="ajax-clickable list-group-item">Three</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-xs-10" id="dashboard-container">
            <nav id="dashboard-nav">

                <div class="col-xs-3 adjacentcells">
                    <div class="dashboard-nav-btn">
                        <h3>This Month</h3>
                    </div>
                </div>

                <div class="col-xs-3 adjacentcells">
                    <div class="dashboard-nav-btn">
                        <h3>Projects</h3>
                    </div>
                </div>

                <div class="col-xs-3 adjacentcells">
                    <div class="dashboard-nav-btn nav-selected">
                        <h3>Payroll</h3>
                    </div>
                </div>

                <div class="col-xs-3 adjacentcells">
                    <div class="dashboard-nav-btn">
                        <h3>Logging</h3>
                    </div> 
                </div>
            </nav>
            <div id="dashboard-ajax-container" class="col-sm-12">
               @yield('content') 
            </div>
            
        </div>
    </div>
</body>
</html>
