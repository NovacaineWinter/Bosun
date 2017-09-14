<?php
use App\role;

$roles=App\role::all();




?>

<form method="get" action="{{ url('dashboard') }}">
    {!! csrf_field() !!}

    <h1 class="text-center">
        Staff Details
    </h1>

<!-- First and Last Name -->
    <div class="form-group row form-container">
        <div class="col-sm-12 row">
            <div class="col-xs-6 textbox-holder-left">
                <input type="text" name="fname" onChange="updateValidation();" class="form-control required left-textbox" value="{{ old('fname') }}" placeholder="First Name" required />

            </div>
            <div class="col-xs-6 textbox-holder-right ">
                <input type="text" name="lname" onChange="updateValidation();" class="form-control required right-textbox" value="{{ old('lname') }}" placeholder="Last Name" required />
            </div>   
        </div>  
    </div>

<!-- Gender -->

    <div class="form-group text-center form-container">
        <label class="radio-inline">
            <input type="radio"  class="optradio" name="gender" value="male" checked>
            Male
        </label>
        <label class="radio-inline">
            <input type="radio"  class="optradio" name="gender" value="female">
            Female
        </label>
    </div>

<!-- Date of Birth -->

    <div class="form-group form-container">
        <div class="row">

            <label class="col-xs-3 dashboard-label control-label">DOB:</label>
            <div class="col-xs-3 textbox-holder-left">
                <input type="number" name="dob_day"  onChange="updateValidation();" class="form-control required left-textbox" placeholder="dd" min="00" max="31" required>
            </div>
            <div class="col-xs-3 textbox-holder-middle">
                <input type="number" name="dob_month"  onChange="updateValidation();" class="form-control required middle-textbox" style="border-radius: 0px;" placeholder="mm" min="00" max="12" requried>
            </div>
            <div class="col-xs-3 textbox-holder-right"  style="padding-right:15px;">
                <input type="number" name="dob_year"  onChange="updateValidation();" class="form-control required right-textbox" placeholder="yyyy" min="1901" max="3000" requried>
            </div>

        </div>
    </div>
    


<!-- Address Fields -->
 
    <div class="form-group form-container">
        <div class="row">

            <div class="col-xs-12 center-block">
                <input type="text" name="addr_line_one"  onChange="updateValidation();" class="form-control required top_textbox verttextboxes" placeholder="Address Line One" required>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="addr_line_two"  onChange="updateValidation();" class="form-control required middle_textbox verttextboxes" placeholder="Address Line Two" required>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="addr_line_three" class="form-control middle_textbox verttextboxes" placeholder="Address Line Three">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="postcode"  onChange="updateValidation();" class="form-control required bottom_textbox verttextboxes" placeholder="Postcode" required>
            </div>
        </div>
    </div>


<!--  Contact details  -->
    
    <div class="form-group form-container">

        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="contact_number"  onChange="updateValidation();" class="form-control required top_textbox verttextboxes" placeholder="Phone Number" required>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="email"  onChange="updateValidation();" class="form-control middle_textbox required verttextboxes" placeholder="Email Address" required>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <input type="text"  onChange="updateValidation();" class="form-control  middle_textbox verttextboxes required" name="ice_fullname" placeholder="Emergency Contact Name" required>
            </div>
        </div>

      <div class="row">
            <div class="col-xs-12">
                <input type="text"  onChange="updateValidation();" class="form-control bottom_textbox verttextboxes required" name="ice_contact_no" placeholder="Emergency Contact Number" required>
            </div>
        </div>
    </div>


<!--  Start Date  -->

    <div class="form-group row form-container">
        <label class="col-sm-3 dashboard-label control-label">Start date:</label>
        <div class="col-sm-9">    
            <input type="date" onchange="updateValidation();" class="form-control required" name="employment_start_date" required>
        </div>
    </div>



<!--  Contractor textbox  -->


    <div class="form-group text-center row form-container">
        <input type="checkbox" id="contractor_check" onchange="employeefilter()" name="contractor" value="1"> Contractor
    </div>

    <div class="form-group text-center row employee form-container">
        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="ni_num" class="form-control verttextboxes" placeholder="NI Number">
            </div>
        </div>
    </div>


<!--  Student loan  -->
    <div class="form-group text-center row employee form-container">
        <input type="checkbox" name="student_loan" value="1">Student Loan
    </div>



<!--  Company and VAT details  -->
    <div class="form-group text-center row contractor form-container" style="display:none;">

        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="company_no" class="form-control top_textbox verttextboxes" placeholder="Company Number">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <input type="text" name="vat_number" class="form-control bottom_textbox verttextboxes" placeholder="VAT number">
            </div>
        </div>
    </div> 



    <h1 class="text-center" style="margin-top:100px;">
        Job Details
    </h1>
    
<!--  Pay grade / holiday stuff  -->
    <div class="form-group form-container">
        <div class="row">

            <div class="col-xs-4 textbox-holder-top-left" style="padding-left:0px;">  
                <input type="number" onChange="updateValidation();" name="holiday_entitlement" class="form-control top-left-textbox required" placeholder="Annual Leave" id="days_leave" required>
            </div>
            <div class="col-xs-4 textbox-holder-middle">
                <input type="number" name="days_per_week" onChange="updateValidation();" class="form-control required middle-textbox" style="border-radius: 0px;" placeholder="Days per Week" min="0" max="7" requried>
            </div>
            <div class="col-xs-4 textbox-holder-top-right" style="padding-right:0px;">
                <input type="number" name="hours_per_week" onchange="changeHours();updateValidation();" id="hours_per_week" class="form-control top-right-textbox required" placeholder="Hours per Week" required>
            </div>

        </div>

        <div class="row ">

            <div class="col-xs-6 textbox-holder-bottom-left input-group">
                <span class="input-group-addon leftspan">&pound</span>
                <input type="number" id="hourlyrateid" name="hourly_rate" onchange="calculateEquiv('hourly');updateValidation();" class="form-control bottom-left-textbox required" placeholder="Hourly Rate" required>
                <span class="input-group-addon middlespan">Hourly</span>
            </div>
            <div class="col-xs-6 textbox-holder-bottom-right input-group">
                <span class="input-group-addon middlespan" style="border-left:none;">&pound</span>
                <input type="number" id="annualrateid" name="annual_rate" onchange="calculateEquiv('annual');updateValidation();" class="form-control bottom-right-textbox required" placeholder="Annual Rate" required>
                <span class="input-group-addon rightspan">Annually</span>
            </div>

        </div>
    </div>


<!-- Shift Selector -->
    <div class="form-group row form-container">
        <label class="col-xs-3 dashboard-label control-label" for="selectbasic">Shift</label>
        <div class="col-xs-9">
            <select id="shift_select" name="shift_id" class="form-control">
                @foreach($roles as $role)
                    <option value="{{{$role->id}}}">{{{$role->name}}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row form-container">
        <label class="col-xs-3 dashboard-label control-label" for="rfid">ID Tag</label>
        <div class="col-xs-9">
            <div class="input-group">
                  <input id="rfid_field" name="rfid" class="form-control" placeholder="RFID tag ID" type="text" required>
                  <span id="tag_scan_btn" style="cursor:pointer;border-bottom-right-radius:10px;border-top-right-radius:10px;-webkit-transition: background-color 1s; transition: background-color 1s; background-color:#5bc0de; color:#fafafa;" class="input-group-addon">Scan Tag Now</span>
            </div>  
        </div>
    </div>
    

    <div class="form-group row form-container" style="margin-top:60px; margin-bottom:100px;">
        <input type="submit" class="pull-right btn btn-lg btn-info" style="display:none;" id="create_staff_btn" value="Create Member of Staff">

    </div>

</form>
    



<script>
focusInterval='';
$('#rfid_field').attr('disabled', true);
    $("#tag_scan_btn").click(function() {
        if($('#tag_scan_btn').html()=='Edit'){
            $('#create_staff_btn').hide(400);
            $('#tag_scan_btn').html('Cancel Scan');
            $("#tag_scan_btn").css("background-color","red");  
            $("#tag_scan_btn").css("color","#fafafa");          
            $('#rfid_field').val('');
            focusInterval=setInterval(function(){ document.getElementById("rfid_field").focus(); }, 100);
            $('#rfid_field').attr('disabled', false);

        }else if($('#tag_scan_btn').html()=='Cancel Scan'){
            $('#rfid_field').attr('disabled', true);
            clearInterval(focusInterval);
            $('#tag_scan_btn').html('Scan Tag Now');
            $("#tag_scan_btn").css("background-color","#5bc0de");  
            $("#tag_scan_btn").css("color","#fafafa");
            $('#rfid_field').val('');

        }else{
             $('#rfid_field').attr('disabled', false);
            $('#tag_scan_btn').html('Cancel Scan');
            $("#tag_scan_btn").css("background-color","red");  
            $("#tag_scan_btn").css("color","#fafafa");          
            $('#rfid_field').val('');
            focusInterval=setInterval(function(){ document.getElementById("rfid_field").focus(); }, 100);
    }
    });

    $('#rfid_field').keypress(function(event) {
        if (event.keyCode == 13) {
            clearInterval(focusInterval);
            $('#rfid_field').attr('disabled', true);
            $("#tag_scan_btn").css("color","#fafafa");
            $("#tag_scan_btn").css("background-color","green");
            $('#tag_scan_btn').html('Edit');
            $('#create_staff_btn').show(400);
            event.preventDefault();
        }
    });


    $('#create_staff_btn').click(function() {
        if(formCheck()){
            alert($('#contractor_check').val());

        }
    });

    function employeefilter(){
        if(jQuery('#contractor_check').is(':checked')){
            jQuery('.employee').hide(300);
            jQuery('.contractor').show(500);
        }else{
            jQuery('.contractor').hide(300);
            jQuery('.employee').show(500);                
        }
    }

    pay_input='';
    function calculateEquiv(dominant){
        pay_input=dominant;
        if($('#hours_per_week').val()!=''){        
            hours=parseFloat($('#hours_per_week').val());

            if(dominant=='annual'){
                //do the calculation to work out the hourly rate from the annual rate + the hours worked per week

                annual=parseFloat($('#annualrateid').val());
                hourly=annual/(hours*52)
                $('#hourlyrateid').val(Math.round(hourly*100)/100);

            }else if(dominant=='hourly'){
                //do the calculation to work out the annual rate from the hourly rate + the hours worked per week                    
                $('#annualrateid').val(parseFloat(Math.round(52*parseFloat($('#hourlyrateid').val())*parseFloat($('#hours_per_week').val()))));
            }

        }
    }

    function changeHours(){
        if(pay_input!=''){
            calculateEquiv(pay_input);
        }
    }

    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                if($('#rfid_field').val()==''){
                    //event.preventDefault();
                    return false;   
                }
            }
        });
    });
</script>