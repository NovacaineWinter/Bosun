<?php
use App\role;

$roles=App\role::all();




?>
<div class="col-md-4 col-md-offset-4">
    <form method="get" action="{{ url('dashboard') }}">
        {!! csrf_field() !!}

        <h1 class="text-center">
            Staff Details
        </h1>

    <!-- First and Last Name -->
        <div class="form-group row form-container">
            <div class="col-sm-12 row">
                <div class="col-xs-6 textbox-holder-left">
                    <input type="text" id="fname" class="form-control required left-textbox" value="{{ old('fname') }}" placeholder="First Name" required />

                </div>
                <div class="col-xs-6 textbox-holder-right ">
                    <input type="text" id="lname" class="form-control required right-textbox" value="{{ old('lname') }}" placeholder="Last Name" required />
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
                    <input type="number" id="dob_day"  class="form-control required left-textbox" placeholder="dd" min="00" max="31" required>
                </div>
                <div class="col-xs-3 textbox-holder-middle">
                    <input type="number" id="dob_month"  class="form-control required middle-textbox" style="border-radius: 0px;" placeholder="mm" min="00" max="12" requried>
                </div>
                <div class="col-xs-3 textbox-holder-right"  style="padding-right:15px;">
                    <input type="number" id="dob_year"  class="form-control required right-textbox" placeholder="yyyy" min="1901" max="3000" requried>
                </div>

            </div>
        </div>
        


    <!-- Address Fields -->
     
        <div class="form-group form-container">
            <div class="row">

                <div class="col-xs-12 center-block">
                    <input type="text" id="addr_line_one" class="form-control required top_textbox verttextboxes" placeholder="Address Line One" required>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="addr_line_two" class="form-control required middle_textbox verttextboxes" placeholder="Address Line Two" required>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="addr_line_three" class="form-control middle_textbox verttextboxes" placeholder="Address Line Three">
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="postcode" class="form-control required bottom_textbox verttextboxes" placeholder="Postcode" required>
                </div>
            </div>
        </div>


    <!--  Contact details  -->
        
        <div class="form-group form-container">

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="contact_number" class="form-control required top_textbox verttextboxes" placeholder="Phone Number" required>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="email" class="form-control middle_textbox required verttextboxes" placeholder="Email Address" required>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" class="form-control  middle_textbox verttextboxes required" id="ice_fullname" placeholder="Emergency Contact Name" required>
                </div>
            </div>

          <div class="row">
                <div class="col-xs-12">
                    <input type="text" class="form-control bottom_textbox verttextboxes required" id="ice_contact_no" placeholder="Emergency Contact Number" required>
                </div>
            </div>
        </div>


    <!--  Start Date  -->

        <div class="form-group row form-container">
            <label class="col-sm-3 dashboard-label control-label">Start date:</label>
            <div class="col-sm-9">    
                <input type="date" class="form-control required" id="employment_start_date" required>
            </div>
        </div>



    <!--  Contractor textbox  -->


        <div class="form-group text-center row form-container">
            <input type="checkbox" id="contractor_check" onchange="employeefilter()" id="contractor" value="1"> Contractor
        </div>

        <div class="form-group text-center row employee form-container">
            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="ni_num" class="form-control verttextboxes" placeholder="NI Number">
                </div>
            </div>
        </div>


    <!--  Student loan  -->
            <!--
        <div class="form-group text-center row employee form-container">
            <input type="checkbox" name="student_loan" value="1">Student Loan
        </div>
            -->


    <!--  Company and VAT details  -->
        <div class="form-group text-center row contractor form-container" style="display:none;">

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="company_no" class="form-control top_textbox verttextboxes" placeholder="Company Number">
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <input type="text" id="vat_number" class="form-control bottom_textbox verttextboxes" placeholder="VAT number">
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
                    <input type="number" min="1" max="7" id="holiday_entitlement" class="form-control top-left-textbox required" placeholder="Annual Leave" required>
                </div>
                <div class="col-xs-4 textbox-holder-middle">
                    <input type="number" id="days_per_week" class="form-control required middle-textbox" style="border-radius: 0px;" placeholder="Days per Week" min="0" max="7" requried>
                </div>
                <div class="col-xs-4 textbox-holder-top-right" style="padding-right:0px;">
                    <input type="number" id="hours_per_week" onchange="changeHours();" id="hours_per_week" class="form-control top-right-textbox required" placeholder="Hours per Week" required>
                </div>

            </div>

            <div class="row ">

                <div class="col-xs-6 textbox-holder-bottom-left input-group">
                    <span class="input-group-addon leftspan">&pound</span>
                    <input type="number" id="hourlyrateid" onchange="calculateEquiv('hourly');;" class="form-control bottom-left-textbox required" placeholder="Hourly Rate" step="0.01" required>
                    <span class="input-group-addon middlespan">Hourly</span>
                </div>
                <div class="col-xs-6 textbox-holder-bottom-right input-group">
                    <span class="input-group-addon middlespan" style="border-left:none;">&pound</span>
                    <input type="number" id="annualrateid" id="annual_rate" onchange="calculateEquiv('annual');" class="form-control bottom-right-textbox required" placeholder="Annual Rate" required>
                    <span class="input-group-addon rightspan">Annually</span>
                </div>

            </div>
        </div>


    <!-- Shift Selector -->
        <div class="form-group row form-container">
            <label class="col-xs-3 dashboard-label control-label" for="selectbasic">User Permissions</label>
            <div class="col-xs-9">
                <select id="shift_select" id="shift_id" class="form-control">
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
                      <input id="rfid_field" id="rfid" class="form-control" placeholder="RFID tag ID" type="text" required>
                      <span id="tag_scan_btn" style="cursor:pointer;border-bottom-right-radius:10px;border-top-right-radius:10px;-webkit-transition: background-color 1s; transition: background-color 1s; background-color:#5bc0de; color:#fafafa;" class="input-group-addon">Scan Tag Now</span>
                </div>  
            </div>
        </div>
        

        <div class="form-group row form-container" style="margin-top:60px; margin-bottom:100px;">
            <div class="pull-right btn btn-lg btn-info" style="display:none;" id="create_staff_btn" value="Save">Create</div>

        </div>

    </form>
    
</div>

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
        var status = formCheck();

        if(status['status'] == true){
            //send ajax request           
            $.ajax({
                    url: "{{url('/ajax')}}",
                    method: 'GET',
                    data: {
                        userdata: status['data'],
                        fname: status['data']['fname'],
                        lname: status['data']['lname'],
                        dob_day: status['data']['dob_day'],
                        dob_month: status['data']['dob_month'],
                        dob_year: status['data']['dob_year'],
                        addr_line_one: status['data']['addr_line_one'],
                        addr_line_two: status['data']['addr_line_two'],
                        postcode: status['data']['postcode'],
                        contact_number: status['data']['contact_number'],
                        ice_fullname: status['data']['ice_fullname'],
                        ice_contact_no: status['data']['ice_contact_no'],
                        female: status['data']['female'],
                        days_leave: status['data']['days_leave'],
                        days_per_week: status['data']['days_per_week'],
                        hours_per_week: status['data']['hours_per_week'],
                        hourlyrateid: status['data']['hourlyrateid'],
                        shift_select: status['data']['shift_select'],
                        contractor: status['data']['contractor'],
                        vat_number: status['data']['vat_number'],
                        company_no: status['data']['company_no'],
                        employment_start_date: status['data']['employment_start_date'],
                        rfid_field: status['data']['rfid_field'],
                        ajaxmethod: 'newMemberOfStaff',
                        
                    },
                    success: function(response) {
                        $('#dashboard-ajax-container').html(response);                        
                    },
                    error: function(response) {
                        console.log('There was an error - it was:  '+response);
                    }
                });
                }else{
                    alert(status['message']);
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

    function formCheck(){

        data = [];
        info = [];
        info['status'] = true;

        /* Nullable / optional fields */

        if($('#addr_line_three').val()!=''){
            data['addr_line_three']= $('#addr_line_three').val();
        }
        data['contractor'] = $('#contractor').val();
        data['company_no'] = $('#company_no').val();
        data['vat_number'] = $('#vat_number').val();

        if($('input[name=gender]:checked').val() == 'female'){
            data['female'] = 1;
        }else{
            data['female'] = 0;
        }


        /* Todo Gender */

    /* Other Fields */

        /* RFID tag */
        if($('#rfid_field').val()!=''){
            data['rfid_field']= $('#rfid_field').val();
        }else{
            info['status'] = false;
            info['message'] = 'User ID Badge Not scanned';
        }   

        /* Role ID */
        if($('#shift_select').val()!=''){
            data['shift_select']= $('#shift_select').val();
        }else{
            info['status'] = false;
            info['message'] = 'User Role Not Selected';
        }


    /* Employment Specifics */

        /* Hourly Rate */
        if($('#hourlyrateid').val()!=''){
            data['hourlyrateid']= $('#hourlyrateid').val();
        }else{
            info['status'] = false;
            info['message'] = 'Hourly Rate Not filled Out';
        }
        /* Hours Per Week */
        if($('#hours_per_week').val()!=''){
            data['hours_per_week']= $('#hours_per_week').val();
        }else{
            info['status'] = false;
            info['message'] = 'Hours Per Week Not filled Out';
        }
        /* Days Worked per Week */
        if($('#days_per_week').val()!=''){
            data['days_per_week']= $('#days_per_week').val();
        }else{
            info['status'] = false;
            info['message'] = 'Days Worked per Week Not filled Out';
        }
        /* Annual Leave Allowance */
        if($('#holiday_entitlement').val()!=''){
            data['days_leave']= $('#holiday_entitlement').val();
        }else{
            info['status'] = false;
            info['message'] = 'Annual Leave Allowance Not filled Out';
        }


    /* Start Date Field */

        /* Start Date */
        if($('#employment_start_date').val()!=''){
            data['employment_start_date']= $('#employment_start_date').val();
        }else{
            info['status'] = false;
            info['message'] = 'Start Date Not filled Out';
        }

    /* Contact Fields */

        /* Emergency Contact Number */
        if($('#ice_contact_no').val()!=''){
            data['ice_contact_no']= $('#ice_contact_no').val();
        }else{
            info['status'] = false;
            info['message'] = 'Emergency Contact Number Not filled Out';
        }

        /* Emergency Contact Name */
        if($('#ice_fullname').val()!=''){
            data['ice_fullname']= $('#ice_fullname').val();
        }else{
            info['status'] = false;
            info['message'] = 'Emergency Contact Name Not filled Out';
        }

        /* email */
        if($('#email').val()!=''){
            data['email']= $('#email').val();
        }else{
            info['status'] = false;
            info['message'] = 'Email Not filled Out';
        }

        /* Contact Number */
        if($('#contact_number').val()!=''){
            data['contact_number']= $('#contact_number').val();
        }else{
            info['status'] = false;
            info['message'] = 'Contact Number Not filled Out';
        }


    /* Address Fields */

        /* DOB Year */
        if($('#postcode').val()!=''){
            data['postcode']= $('#postcode').val();
        }else{
            info['status'] = false;
            info['message'] = 'Post Code Not filled Out';
        }
        
        /* Address Line Two */
        if($('#addr_line_two').val()!=''){
            data['addr_line_two']= $('#addr_line_two').val();
        }else{
            info['status'] = false;
            info['message'] = 'Address Line Two Not filled Out';
        }        

        /* Address Line One  */
        if($('#addr_line_one').val()!=''){
            data['addr_line_one']= $('#addr_line_one').val();
        }else{
            info['status'] = false;
            info['message'] = 'Address Line One Not filled Out';
        }


    /* DOB fields */

        /* DOB Year */
        if($('#dob_year').val()!=''){
            data['dob_year']= $('#dob_year').val();
        }else{
            info['status'] = false;
            info['message'] = 'Date Of Birth Year Not filled Out';
        }

        /* DOB Month */
        if($('#dob_month').val()!=''){
            data['dob_month']= $('#dob_month').val();
        }else{
            info['status'] = false;
            info['message'] = 'Date Of Birth Month Not filled Out';
        }

        /* DOB Day */
        if($('#dob_day').val()!=''){
            data['dob_day']= $('#dob_day').val();
        }else{
            info['status'] = false;
            info['message'] = 'Date Of Birth Day Not filled Out';
        }


    /* Name Fields */

        /* Last Name */
        if($('#lname').val()!=''){
            data['lname']= $('#lname').val();
        }else{
            info['status'] = false;
            info['message'] = 'Last Name Not filled Out';
        }

        /* First Name */
        if($('#fname').val()!=''){
            data['fname']= $('#fname').val();
        }else{
            info['status'] = false;
            info['message'] = 'First Name Not filled Out';
        }

        info['data'] = data;
        return info;
    }





</script>