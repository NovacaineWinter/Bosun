@extends('layouts.app')


@section('content')

<div style="width:100%;" id="dashboard-container" class="container">
    <div class="col-lg-2" id="dashboard-sidebar">
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
    <div class="col-lg-10" id="ajax-target">
        <!--  Target container for the AJAX requests -->
    </div>
</div>


<script>
    jQuery(' document ').ready(function() {

        //initial lazy load ajax request for the content on the homepage of the dashboard
        $.ajax({
                url: "{{url('/ajax')}}",
                method: 'GET',
                success: function(response) {
                    $('#ajax-target').html(response); 
                },
                error: function(response) {
                    console.log('There was an error - it was:  '+response);
                }
            });


        // Ajax function to deal with buttons being clicked
        $('.ajax-clickable').click(function() {

            clickedMethod=$(this).attr('method');

            $.ajax({
                url: "{{url('/ajax')}}",
                method: 'GET',
                data: {
                    ajaxmethod: clickedMethod,
                },
                beforeSend: function() {           
                    $('#ajax-target').fadeOut();  

                },
                success: function(response) {
                    $('#ajax-target').html(response);
                    $('#ajax-target').fadeIn(200);
                },
                error: function(response) {
                    console.log('There was an error - it was:  '+response);
                }
            });
        });

    });


    function updateValidation(){
        if(already_validated){
            formCheck(false);
        }
    }

    already_validated=false;

    function formCheck(warn=true){
        var $val=0;
        already_validated=true;

        //check text fields
        $("input.required").each(function(){
            if (($(this).val())== ""){
                  $(this).addClass("not-filled");
                  $val = 1
            }
            else{
                $(this).removeClass("not-filled");
            }
          
        });


        // if you want to check select fields
    /*
        $("select.required").each(function(){
            if (($(this).val())== ""){
                  $(this).addClass("error");
                  $val = 1
            }
            else{
                $(this).removeClass("error");
            }
          
        });
    */
        if(warn){
            if ($val > 0) {
                alert("Oops - You've missed some fields");
                return false;
            }else{
                return true;
            }
        }

    }
</script>
@endsection

