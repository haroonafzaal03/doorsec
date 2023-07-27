var events = events_ptj;
var clients = scheduler_clients_ptj;
var clients_ids = scheduler_clients_ids;
var rows = clients.length;

function adjustColumnHeight() {
    console.log('adasd' + $('table#scheduler tr').length);
    for (var n = 0; n < $('table#scheduler tr').length; n++) {
        var row_height = $('table#scheduler tr').eq(n).height();
        console.log(row_height);
        if (row_height <= 45) {
            row_height = 50;
        } else {
            row_height = row_height + 44;
        }

        $('table#scheduler tr').eq(n).children('td.headcol').css('height', row_height);
    }

}

$(function () {
    loadTimePicker();
    $('#start_date').datepicker({
        autoclose: true
    });

    //var clients = ['Anthony', 'George', 'Zeeshan', 'Herry', 'Robin'];
    //console.log(clients);
    //console.log(events);
    //Date for the calendar events (dummy data)
    /*var events = [
        {
            title: 'Long Event',
            client: 1,
            start: '2019-07-05',
            end: '2019-07-08',
            timings: '9AM - 10PM',
            backgroundColor: '#f39c12', //yellow
            borderColor: '#f39c12' //yellow
        },
        {
            title: 'Meeting',
            client: 1,
            start: '2019-07-09',
            end: '2019-07-09',
            timings: '9AM - 10PM',
            allDay: false,
            backgroundColor: '#0073b7', //Blue
            borderColor: '#0073b7' //Blue
        },
        {
            title: 'Lunch',
            client: 3,
            start: '2019-07-09',
            end: '2019-07-09',
            timings: '9AM - 10PM',
            allDay: false,
            backgroundColor: '#00c0ef', //Info (aqua)
            borderColor: '#00c0ef' //Info (aqua)
        },
        {
            title: 'Birthday Party',
            client: 3,
            start: '2019-07-10',
            end: '2019-07-10',
            timings: '9AM - 10PM',
            allDay: false,
            backgroundColor: '#00a65a', //Success (green)
            borderColor: '#00a65a' //Success (green)
        },
        {
            title: 'Click for Google',
            client: 4,
            start: '2019-07-06',
            end: '2019-07-07',
            timings: '9AM - 10PM',
            url: 'http://google.com/',
            backgroundColor: '#3c8dbc', //Primary (light-blue)
            borderColor: '#3c8dbc' //Primary (light-blue)
        },
        {
            title: 'Click for Google',
            client: 1,
            start: '2019-07-05',
            end: '2019-07-05',
            timings: '10AM - 1PM',
            url: 'http://google.com/',
            backgroundColor: '#3c8dbc', //Primary (light-blue)
            borderColor: '#3c8dbc' //Primary (light-blue)
        }
    ];*/
    //console.log(events);


    // Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined';

    // Safari 3.0+ "[object HTMLElementConstructor]"
    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));

    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/ false || !!document.documentMode;

    // Edge 20+
    var isEdge = !isIE && !!window.StyleMedia;

    // Chrome 1 - 71
    var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

    // Blink engine detection
    var isBlink = (isChrome || isOpera) && !!window.CSS;
    if (isEdge) {
        $('#scheduler').addClass(' isEdge');
    }

    var new_date = new Date();
    var current_month = ("0" + (new_date.getMonth() + 1)).slice(-2);

    var columns = moment("2019-" + current_month, "YYYY-MM").daysInMonth();
    var ar = getMonths('2019-' + current_month, "YYYY-MM");
    console.log(ar);
    renderSchedule(ar, columns);

    var staffList = $('#staffList_new').html();

    /*  STAFF TYPE SELECTION RADIO BUTTON  */

    $("input.staff_type_radio[type='radio']").change(function () {

        $('.staff_type_labels').removeClass('active');
        $(this).parent('.staff_type_labels').addClass('active');
        var staff_type_id = $(this).attr('data-typeid');
        $("ul#staffList_new li").attr('data-view', 'off');
        if (staff_type_id) {
            $("ul#staffList_new li[data-stafftype=" + staff_type_id + "]").attr('data-view', 'on');
        }
        console.log(staff_type_id);
    });


    $('#staff_list').on('select2:select', function (e) {
        if (e.params.data['id'] != '') {
            if ($('#shift_start_time-hidden').val() != '' && $('#shift_end_time-hidden').val() != '') {
                addStaffInShift(e.params.data['id'], $('#shift_date').text(), $('#venue_id_popup').val(), $('#client_id_popup').val(), $('#shift_start_time-hidden').val(), $('#shift_end_time-hidden').val(), $('.venue_detail_id').val());
            } else {
                alert('Start and End Time is empty!');
            }
        }
    });

    $('#venue_client_id').on('select2:select', function (e) {
        CheckValidation();
    });

    // $('#staff_list').on('select2:opening',function(){
    //     var staff_type_id ='';
    //     $('input.staff_type_radio[type="radio"]').each(function(){
    //         if($(this).parent('.staff_type_labels').hasClass('active')){
    //             staff_type_id  =  $(this).attr('data-typeid');
    //         }
    //     });
    //     var myJsonData = {venue_id:$('#venue_id_popup').val(),shiftDate:$('#shift_date').text(),client_id:$('#client_id_popup').val(),start_time:$('#shift_start_time-hidden').val(),end_time:$('#shift_end_time-hidden').val(),staff_type:staff_type_id};
    //     //console.log(myJsonData);return false;
    //     $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    //     });
    //     $.ajax({
    //     type:'POST',
    //     url:'/getAvailableStaff',
    //     data:myJsonData,
    //     dataType: 'JSON',
    //     beforeSend:function(){
    //         console.log('beforesuccess');
    //     },
    //     success:function(data){
    //         if(data.staffListView != ''){
    //             $('#staff_list').empty();
    //             $('#staff_list').append(data.staffListView);
    //         }else{
    //             $('#staff_list').empty();
    //         }
    //     }
    //     // error:function(){
    //     //     alert('error');
    //     // }
    //     });
    //     //return false;
    // });

    $('#updateShiftStaffBtn').click(function () {
        var formData = $('#edit_venue_shift_form').serialize();
        console.log('formData');
        console.log(formData);
        var myJsonData = { formData: formData };
        $.ajax({
            type: 'POST',
            url: '/updateShiftStaff',
            data: myJsonData,
            dataType: 'JSON',
            success: function (data) {
                //alert(data.message);
                location.reload();
            }
        });
    });
    var count = 1;
    $('#add_shift').click(function () {

        if ($('table#table-venue_shifts tbody#venue_shifts_tbody tr').length > 0) {
            $.confirm({
                title: 'Confirmation!',
                content: 'Do you really want to add more shifts!',
                buttons: {
                    confirm: function () {
                        this.close();
                        loader_open();
                        setTimeout(function () {

                            var number_shifts = $('#number_shifts').val();
                            var start_date = $('#venue_start_date').val();
                            var end_date = $('#venue_end_date').val();
                            var from = $('#outer_from').val();
                            var to = $('#outer_to').val();
                            var hours = $('#hours-hidden').val();
                            var rate = $('#rate_per_hour').val();
                            if (rate == '') {
                                rate = 0;
                            }

                            start_date = moment(start_date);
                            end_date = moment(end_date);
                            var selected_staff = "";
                            var dates = getDates(start_date, end_date);
                            if (number_shifts > 0) {
                                $('table#table-venue_shifts').css('display', 'block');
                                $('#add_staff_shiift-btn').css('display', 'block');
                                $('#venue_shift_section').css('display', 'block');

                                for (var j = 0; j < dates.length; j++) {
                                    for (var i = 1; i <= number_shifts; i++) {
                                        var newRow = $("<tr id=tr_" + count + ">");
                                        var cols = '<td class="remove_staff-td"><i class="fa fa-minus-circle"></i></td>';
                                        cols += '<td><label class="col-md-12 d-block">' + dates[j]['format2'] + '</label><input type="hidden" name="day[]" value="' + dates[j]['format2'] + '"></td>';
                                        cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="start_time[]" class="form-control timepicker" value="' + from + '" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                        cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="end_time[]" class="form-control timepicker" value="' + to + '" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                        cols += '<td class=""><input type="" class="form-control" id="" name="shift_hours[]" data-name="hours" value="' + hours + '" readonly="" autocomplete="off"></td>';
                                        cols += '<td class=""><input type="hidden" class="form-control" id="" name="" value="" data-name="staff_id" readonly=""><input type="hidden" class="form-control" id="" name="" value="" readonly=""><input type="" class="form-control number_only" id="" name="shift_rate_per_hour[]" value="' + rate + '" data-name="rate_per_hour" autocomplete="off"></td>';
                                        cols += '<td class="staff_image_temp"><label class="label bg-red">No Staff</label></td>';
                                        cols += '<td><a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff(\'' + 'tr_' + count + '\')">Select Staff</a><br> <input data-validation="true" type="hidden" id="selected_staff-' + count + '" name="selected_staff[]" /></td>';


                                        newRow.append(cols);

                                        //var staff_row = $("<tr id=st_tr_"+count+" class='hide'>");
                                        //var st_cols = ' <td colspan="5"></td>';
                                        //staff_row.append(st_cols);
                                        //newRow.insertBefore($('.add_cost').closest("tr"));
                                        //$('table #venue_shifts_tbody tr#tr-proceed_staff_btn').before(newRow);
                                        $('table #venue_shifts_tbody').append(newRow);
                                        //$('table #venue_shifts_tbody').append(staff_row);

                                        count++;
                                    }
                                    reinit();
                                }
                                $('#btnnn').css('display', 'initial');
                            }
                            loader_close();
                            $('#number_shifts').val('');
                            $('#outer_from').val('');
                            $('#outer_to').val('');
                            $('#hours-hidden').val('');
                            $('#rate_per_hour').val('');
                        }, 100);

                        return false;
                    },
                    cancel: function () {
                        this.close();
                    }
                }
            });
        } else {
            if (!CheckValidation()) {
                return false;
            }
            loader_open();
            setTimeout(function () {

                var number_shifts = $('#number_shifts').val();
                var start_date = $('#venue_start_date').val();
                var end_date = $('#venue_end_date').val();
                var from = $('#outer_from').val();
                var to = $('#outer_to').val();
                var hours = $('#hours-hidden').val();
                rate_per_hour
                var rate = $('#rate_per_hour').val();
                if (rate == '') {
                    rate = 0;
                }

                start_date = moment(start_date);
                end_date = moment(end_date);
                var selected_staff = "";
                var dates = getDates(start_date, end_date);
                if (number_shifts > 0) {
                    $('table#table-venue_shifts').css('display', 'block');
                    $('#add_staff_shiift-btn').css('display', 'block');
                    $('#venue_shift_section').css('display', 'block');

                    for (var j = 0; j < dates.length; j++) {
                        for (var i = 1; i <= number_shifts; i++) {
                            var newRow = $("<tr id=tr_" + count + ">");
                            var cols = '<td class="remove_staff-td"><i class="fa fa-minus-circle"></i></td>';
                            cols += '<td><label class="col-md-12 d-block">' + dates[j]['format2'] + '</label><input type="hidden" name="day[]" value="' + dates[j]['format2'] + '"></td>';
                            cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="start_time[]" class="form-control timepicker" value="' + from + '" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                            cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="end_time[]" class="form-control timepicker" value="' + to + '" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                            cols += '<td class=""><input type="" class="form-control" id="" name="shift_hours[]" data-name="hours" value="' + hours + '" readonly="" autocomplete="off"></td>';
                            cols += '<td class=""><input type="hidden" class="form-control" id="" name="" value="" data-name="staff_id" readonly=""><input type="hidden" class="form-control" id="" name="" value="" readonly=""><input type="" class="form-control number_only" id="" name="shift_rate_per_hour[]" value="' + rate + '" data-name="rate_per_hour" autocomplete="off"></td>';
                            cols += '<td class="staff_image_temp"><label class="label bg-red">No Staff</label></td>';
                            cols += '<td><a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff(\'' + 'tr_' + count + '\')">Select Staff</a><br> <input data-validation="true" type="hidden" id="selected_staff-' + count + '" name="selected_staff[]" /></td>';


                            newRow.append(cols);

                            //var staff_row = $("<tr id=st_tr_"+count+" class='hide'>");
                            //var st_cols = ' <td colspan="5"></td>';
                            //staff_row.append(st_cols);
                            //newRow.insertBefore($('.add_cost').closest("tr"));
                            //$('table #venue_shifts_tbody tr#tr-proceed_staff_btn').before(newRow);
                            $('table #venue_shifts_tbody').append(newRow);
                            //$('table #venue_shifts_tbody').append(staff_row);

                            count++;
                        }
                        reinit();
                    }
                    $('#btnnn').css('display', 'initial');
                }
                loader_close();
                $('#number_shifts').val('');
                $('#outer_from').val('');
                $('#outer_to').val('');
                $('#hours-hidden').val('');
                $('#rate_per_hour').val('');
            }, 100);
            return false;
        }
        return false;
        // $('.spinnerr').css('display','none');

    });
    // $('#btnnn').click(function(){
    //             if(CheckValidation()){
    //                 alert('true');
    //                 $('#venue_schedule_form').submit();
    //             }else{
    //                 return false;
    //             }
    //         });
    $('#add_staff_shiift-btn').click(function () {
        var arr = [];
        var selected_staff_image_src;
        if ($('#staffList_new li.selected').length > 0) {
            $('#staffList_new li.selected').each(function () {
                arr.push($(this).attr('data-id'));
                selected_staff_image_src = $(this).attr('data-image');
            });
            //$('#'+$('#tr_shift_id').val()).css('background-color','transparent');
            $('#' + $('#tr_shift_id').val()).find('td:nth-child(7)').html('<img src="' + selected_staff_image_src + '" class="img-circle user_image">');
            $('#' + $('#tr_shift_id').val()).find('td:nth-child(8)').find('input[name="selected_staff[]"]').val(arr.join(','));
            CheckValidation();
            iziToast.show({
                title: 'Hey',
                color: 'green', // blue, red, green, yellow
                message: 'Staff added to shift successfully!'
            });
        } else {
            $('#' + $('#tr_shift_id').val()).find('td:nth-child(7)').html('<label class="label bg-red">No Staff</label>');
            $('#' + $('#tr_shift_id').val()).find('td:nth-child(8)').find('input[name="selected_staff[]"]').val('');
            CheckValidation();
        }
        return false;
    });

    $('#deleteShifBtn').click(function () {
        var myJsonData = { venue_id: $('#venue_id_popup').val() };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/destroyVenue',
            data: myJsonData,
            dataType: 'JSON',
            success: function (data) {
                if (data.status == 1) {
                    location.reload();
                }
            }
        });

    });

    $('#add_staff-checkbox').click(function () {
        if ($(this).prop("checked")) {
            $('#shift_start_time-hidden').val('');
            $('#shift_end_time-hidden').val('');
            $('#add_staff_section').css('display', 'block');
        } else {
            $('#add_staff_section').css('display', 'none');
        }
    });

    $('#send_sms_close-btn').click(function () {
        $('#edit_venue_schedule-body').css('display', 'block');
        $('#send_sms_modal-body').css('display', 'none');
    });

    $(".selectAll").change(function () {
        $('input.staff_sch_check:checkbox').not(this).prop('checked', this.checked);
    });

    $('.edit_venue_shift_modal-closebtn').click(function () {
        location.reload();
    });

    $('.send_payroll_btn').click(function () {
        var arr = [];
        //  $("table#shiftstaffDataTable tr td input.staff_sch_check:checked").each(function(ev){
        //     var data_contact = $(this).attr('data-contact');
        //     var staff_id = $(this).attr('data-staff_id');
        //     var venue_id = $(this).attr('data-venue_id');
        //     var staff_status = $(this).attr('data-staff_status');
        //     var tr_id = $(this).parent('td').parent('tr').attr('id');
        //     var temp = tr_id.split("-");
        //     arr.push({
        //     staff_schedule_pid: temp['1'],
        //     staff_id:staff_id,
        //     venue_id:venue_id,
        //     status:staff_status
        //     });

        //  }); // LOOP ENDS
        var formData = $('#edit_venue_shift_form').serialize();

        var myJsonData = { formData: formData };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.post('/sendPayroll', myJsonData, function (response) {
            var obj = JSON.parse(response);
            if (obj.status == '200') {
                iziToast.show({
                    title: 'Success',
                    color: 'green', // blue, red, green, yellow
                    message: 'Payroll activated successfully!'
                });
            }
        });


    });
    venueShiftsMouseActions();

});

function reinit() {
    $('.blockStaffFromShiftAnchor').click(function () {
        var staff_id = $(this).attr('data-staff-id');
        var staff_schedule_id = $(this).attr('data-ss-id');
        var value = $(this).attr('data-val');
        var myJsonData = { staff_id: staff_id, staff_schedule_id: staff_schedule_id, staff_schedule_status: value };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/blockStaffFromShift',
            data: myJsonData,
            dataType: 'JSON',
            success: function (data) {
                if (data.status == 1) {
                    if (data.staff_status != '') {
                        if (data.staff_status == 'confirmed') {
                            var html = '<label class="label bg-green">' + data.staff_status + '</label>';
                        } else if (data.staff_status == 'dropout') {
                            var html = '<label class="label bg-red">' + data.staff_status + '</label>';
                        }
                        $('#tr-' + staff_schedule_id + ' td.td-ss-status').html(html);
                        $('#tr-' + staff_schedule_id + ' td div.btn-group ul li a').attr('data-val', data.staff_status);
                    }
                }
            }
        });
    });

    /*$('.select_staff').click(function(){
        var client_id = $('#venue_client_id').val();
        var tr = $(this).closest('tr').attr('id');
        var day = $(this).closest('tr').find('td:nth-child(2)').find('input[name="day[]"]').val();
        var start_time = $(this).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val();
        var end_time = $(this).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val();
        var stafflist = $(this).closest('tr').find('td:nth-child(6)').find('input[name="selected_staff[]"]').val();
        console.log(stafflist);
        var myJsonData = {start_time:start_time,end_time:end_time,day:day,client_id:client_id,tr:tr,stafflist:stafflist};

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.ajax({
        type:'POST',
        url:'/getAvailableStaffList1',
        data:myJsonData,
        dataType: 'JSON',
        success:function(data){
            console.log(data);
            $('#staffList_new').html(data.staffList);
        }
        });


        return false;
    });*/

    $('.remove_staff-td').click(function () {
        $(this).closest('tr').remove();
        if ($('table#table-venue_shifts tbody#venue_shifts_tbody tr').length == 0) {
            $('table#table-venue_shifts').css('display', 'none');
            $('#btnnn').css('display', 'none');
            $('#add_staff_shiift-btn').css('display', 'none');
            $('#venue_shift_section').css('display', 'none');

        }
    });

    $(".image_select2").select2({
        templateResult: tmpResultFormat,
        templateSelection: tmpSelectionFormat
    });

    // REMOVE DATA
    $(".removeDataAnchor").click(function () {
        $('#removeDataPopup').modal('show');
        $(".response_message").removeClass('text-red,text-green');
        $("#passDataForm #input_password").val('');

        var data_id = $(this).attr('data-id');
        var target_modal = $(this).attr('data-target');
        var action_on = $(this).attr('data-action');

        if (target_modal) {
            $("#passDataForm .response_message").text('');
            $(target_modal + " .modal-title").text('Remove Staff');
            $(target_modal + " .action_message").text('If You Want to Remove Staff, Please Enter Your Password!');
            $("#passDataForm #delete_data_id").val(data_id);
            $("#passDataForm #action_on").val(action_on);
            $(target_modal + " .deleteBtnTrigger").attr('onClick', 'removeStaffFromShift()');
            $(target_modal).modal();
        }

        return false;
    });

    $(".allow_check").change(function (ev) {
        var id = $(this).attr('data-id');

        if ($(this).val() == true) {
            $("#rph-" + id).removeAttr('readonly');
        } else {
            $("#rph-" + id).attr('readonly', 'readonly');
            // $("#rph-"+id).val();
        }
    });

    $("input.staff_type_labels_evp[type='radio']").change(function () {
        $('.staff_type_labels').removeClass('active');
        $(this).parent('.staff_type_labels').addClass('active');
        var staff_type_id = $(this).attr('data-typeid');
        $("ul#staffList_new li").attr('data-view', 'off');
        if (staff_type_id) {
            $("ul#staffList_new li[data-stafftype=" + staff_type_id + "]").attr('data-view', 'on');
        }

        getAvailableStaffList(staff_type_id);
    });
    venueShiftsMouseActions();
    loadTimePicker();
}

function venueShiftsMouseActions() {
    $(".venue_shift_schedule_section").mouseover(function () {
        if ($(this).children().length > 0) {
            $(this).css({ "background-color": "#95bdd2", "cursor": "pointer", "box-shadow": "0 0 11px rgba(33,33,33,.2)" });
        }
    });

    $(".venue_shift_schedule_section").mouseleave(function () {
        $(this).css({ "background-color": "#eeeeee", "cursor": "auto", "box-shadow": "none" });
    });
}

function refreshSchedule() {
    var start_date = $('#start_date').val();
    if (start_date == '') {
        alert('Please set date!');
        return false;
    }
    var week = $('input:radio[name=week]:checked').val();
    if (typeof (week) == 'undefined') {
        alert('Please select week!');
        return false;
    }
    var ar = duration(start_date, week);
    var start_date = moment(start_date);
    var end_date = moment(start_date).add(week, 'week');
    //console.log(start_date.format('D-ddd-YYYY')+' '+end_date.format('D-ddd-YYYY'));
    end_date = end_date.subtract(1, "days");
    //console.log(start_date.format('MMM D')+' - '+end_date.format('MMM D'));
    $('#date_duration').text(start_date.format('MMM D') + ' - ' + end_date.format('MMM D, YYYY'));
    var columns = ar.length;
    console.log(ar);
    renderSchedule(ar, columns);
    venueShiftsMouseActions();
}

function renderSchedule(ar, columns) {
    var shifts = 0;
    var total_hours = 0;
    var newRow = $("<tr>");
    var cols = "";
    cols += '<th class="headcol">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </th>';

    for (var i = 0; i < rows; i++) {
        cols += '<th class="" data-date=""><span data-id="' + clients[i].id + '">' + clients[i].property_name + '</span></th>';
    }

    //for (var i = 0; i < ar.length; i++) {
    //    cols += '<th class="" data-date="' + ar[i] + '"><span>' + ar[i] + '</span></th>';
    //}
    newRow.append(cols);
    $('.zt-head').html(newRow);

    //console.log(columns);

    $('.zt-body').empty();
    console.log(clients);
    for (var j = 0; j < ar.length; j++) {
        var newRow = $('<tr>');
        var cols = "";
        cols += '<td class="headcol">' + ar[j]['format3'] + '</td>';
        newRow.append(cols);
        for (var k = 0; k < clients.length; k++) {
            var cols = "";
            cols += '<td class="venue_shift_schedule_section clientIdz-' + clients[k].id + '" data-cdates="' + ar[j]['format2'] + '" data-clname="' + clients[k].property_name + '" id="' + clients[k].id + '-' + ar[j]['format1'] + '" onclick="schedule_details(\'' + clients[k].id + '\',\'' + ar[j]['format2'] + '\',this.id)"></td>';
            newRow.append(cols);
        }
        //$('.zt-body').append(newRow);
        newRow.appendTo($('.zt-body'));
    }
    for (var e = 0; e < events.length; e++) {
        var client_id = events[e].client_id;
        var start_date = events[e].start_date;
        var end_date = events[e].end_date;
        var staff_name = events[e].name;
        var staff_image = (events[e].picture == null) ? default_img : asset_path + '/' + events[e].picture;
        var event_start_time = moment(events[e].start_time, 'hh:mm A').format('hh:mm A');
        var event_end_time = moment(events[e].end_time, 'hh:mm A').format('hh:mm A');

        //var staff = (events[e].name == null) ? "No Staff" : events[e].name;
        var event_card_background = '';
        //var event_card_background = (events[e].status == 'pending') ? 'card_pending' : 'bg-red';


        switch (events[e].status + '+' + events[e].sms_status) {
            case 'pending+not_sent':
                // code block
                event_card_background = 'card_pending';
                break;
            case 'confirmed+not_sent':
                event_card_background = 'card_pending';
                break;
            case 'pending+pending':
                event_card_background = 'card_sendsms';
                break;
            case 'confirmed+confirmed':
                // code block
                event_card_background = 'card_confirm';
                break;
            case 'dropout+not_sent':
                // code block
                event_card_background = 'card_dropout';
                break;
            case 'dropout+pending':
                // code block
                event_card_background = 'card_dropout';
                break;
            case 'dropout+confirmed':
                // code block
                event_card_background = 'card_dropout';
                break;
            case 'confirmed+pending':
                event_card_background = 'card_confirm';
                break;
            default:
            // code block
        }

        // var event_card_border = (events[e].status == 'booked') ? 'card_border_green' :
        var event_card_border = '';
        start_date = moment(start_date);
        end_date = moment(end_date);

        if (moment(start_date).isSame(end_date)) {
            console.log(client_id + '-' + start_date.format('D-ddd-YYYY') + " " + client_id + '-' + end_date.format('D-ddd-YYYY') + " same");
            var td_id = client_id + '-' + start_date.format('D-ddd-MMM-YYYY');
            if ($('#' + td_id).length > 0) {
                shifts = shifts + 1;
                total_hours = total_hours + getHours(events[e].start_time, events[e].end_time);
                //console.log(total_hours);
            }

            /* event card with staff */
            //var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'"><span class="timings"> '+event_start_time+' - '+event_end_time+'</span><br><span class="client">'+ staff +'</span><br><span class="event-name hide">'+events[e].event_name+'</span></div>';

            /* event card without staff */
            /*var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'" onclick="ShiftDetail(\''+start_date.format('YYYY-MM-D')+'\',\''+events[e].id+'\',\''+events[e].client_id+'\',\''+events[e].start_time+'\',\''+events[e].end_time+'\')"><span class="timings"> '+event_start_time+' - '+event_end_time+'</span></div>';*/

            var event_card = '<div class="event_card ' + event_card_background + ' ' + event_card_border + '"><div class="shift_staff_icons"><img src="' + staff_image + '" class="user-image" alt="User Image"><p>' + staff_name + '</p><div class="timings">' + event_start_time + ' - ' + event_end_time + '</div><div><div class="view_details_icons hide"><i class="fa fa-eye"></i><i class="fa fa-user-plus"></i></div></div>';
            $('#' + td_id).append(event_card);
        } else {
            var dates_array = getDates(start_date, end_date);
            console.log(client_id + '-' + start_date.format('D-ddd-YYYY') + " " + client_id + '-' + end_date.format('D-ddd-YYYY'));
            //console.log(dates_array);
            for (var d = 0; d < dates_array.length; d++) {
                //console.log(client_id + '-' + dates_array[d]['format1']);

                var td_id = client_id + '-' + dates_array[d]['format1'];
                if ($('#' + td_id).length > 0) {
                    shifts = shifts + 1;
                    total_hours = total_hours + getHours(events[e].start_time, events[e].end_time);
                    //console.log(total_hours);
                }

                /* event card with staff */
                //var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'"><span class="timings">'+event_start_time+' - '+event_end_time+'</span><br><span class="client">'+ staff +'</span><br><span class="event-name hide">'+events[e].event_name+'</span></div>';

                /* event card without staff */
                /*var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'" onclick="ShiftDetail(\''+dates_array[d]['format2']+'\',\''+events[e].id+'\',\''+events[e].client_id+'\',\''+events[e].start_time+'\',\''+events[e].end_time+'\')"><span class="timings"> '+event_start_time+' - '+event_end_time+'</span></div>';
                $('#' + td_id).append(event_card);*/

                var event_card = '<div class="event_card ' + event_card_background + ' ' + event_card_border + '"><div class="timings">' + event_start_time + ' - ' + event_end_time + '</div><div class="view_details_icons"></div><div class="shift_staff_icons"><img src="' + staff_image + '" class="user-image" alt="User Image"><p>' + staff_name + '</p><div><div class="view_details_icons hide"><i class="fa fa-eye"></i><i class="fa fa-user-plus"></i></div></div>';
                $('#' + td_id).append(event_card);
            }
        }
    }
    adjustColumnHeight();
    $('#shifts').text(shifts);
    $('#total_hours').text(total_hours);
}

function tmpResultFormat(opt) {
    if (!opt.id) {
        return opt.text;
    }

    var option_image = $(opt.element).attr('data-image');
    if (!option_image) {
        return opt.text;
    } else {
        var $opt = $(
            '<span><img class="" width="50px" src="' + option_image + '"  /> ' + opt.text + '</span>'
        );
        return $opt;

    }
}

function tmpSelectionFormat(opt) {
    if (!opt.id) {
        return opt.text;
    }

    var option_image = $(opt.element).attr('data-image');
    if (!option_image) {
        return opt.text;
    } else {
        var $opt = $(
            '<span><img class="" width="25px" src="' + option_image + '"  /> ' + opt.text.toUpperCase() + '</span>'
        );
        return $opt;

    }
}

function schedule_now() {
    loader_open();
    setTimeout(function () {

        $('#selected_staff').empty();

        var value = $('#staffList_new > li.selected > img');
        if (value.length) {
            value.each(function () {
                console.log($(this).data());
                var image_src = $(this).attr('src');
                var staff_name = $(this).data('staffname');
                var staff_id = $(this).data('staffid');
                var html = '<li data-id="" data-view="on"class="abbc label badge selected_lable"><img src="' + image_src + '" class="custom staff_list_view img-circle" data-staffid="' + $(this).data('staffid') + '" data-img-src="' + image_src + '"/> <span class="list_staff_name ">' + staff_name + '</span> <input type="hidden" name="staff_id[]" value="' + staff_id + '"/><i class="fa fa-times pull-right pointer absolute"></i></li>';
                $('#selected_staff').append(html);
            });
        }

        $(".image_select2").select2({
            templateResult: tmpResultFormat,
            templateSelection: tmpSelectionFormat
        });

        $("#start_date").datepicker({
            todayBtn: 1,
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#end_date').datepicker('setStartDate', minDate);
        });

        $("#end_date").datepicker()
            .on('changeDate', function (selected) {
                var maxDate = new Date(selected.date.valueOf());
                $('#start_date').datepicker('setEndDate', maxDate);
            });
        //selected_staff
        //Timepicker
        loadTimePicker();
        $('.badge').click(function () {
            $(this).remove();
        });
        $('#schedule_venue_modal').modal({ backdrop: 'static', keyboard: false });
        loader_close();
    }, 100);
    //  } // check if any staff is selected
    //  else
    //  {
    //      alert("Please Select Staff To Proceed");
    // }
}

function ShiftDetail(shiftDate, venue_id, client_id, start_time, end_time) {
    //console.log(shiftDate);
    $('#shift_selected_staff').empty();
    $('#staff_list').empty();
    //$('#shiftstaffDataTable').DataTable();
    var myJsonData = { shiftDate: shiftDate, venue_id: venue_id, client_id: client_id, start_time: start_time, end_time: end_time };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".image_select2").select2({
        templateResult: tmpResultFormat,
        templateSelection: tmpSelectionFormat
    });

    $.ajax({
        type: 'POST',
        url: '/getShiftScheduleStaff',
        data: myJsonData,
        dataType: 'JSON',
        success: function (data) {
            $('#venue_id_popup').val(venue_id);
            $('#client_id_popup').val(client_id);
            $('#shift_start_time').text(moment(start_time, 'hh:mm A').format('hh:mm A'));
            $('#shift_start_time-hidden').val(start_time);
            $('#shift_end_time-hidden').val(end_time);
            $('#shift_end_time').text(moment(end_time, 'hh:mm A').format('hh:mm A'));
            $('#shift_date').text(shiftDate);
            if (data.status == 1) {
                $('#number_staff_scheduled').text(data.total_staff_scheduled);
                $('#staffDataTable_body').html(data.data);
            } else {
                $('#number_staff_scheduled').text('');
                $('#staffDataTable_body').empty();
            }
            $('#shiftstaffDataTable').DataTable();

            if (data.staffListView != '') {
                $('#staff_list').append(data.staffListView);
            } else {
                $('#staff_list').empty();
            }
            reinit();
        }
    });
    $('#edit_venue_shift_modal').modal({ backdrop: 'static', keyboard: false });
}

function addStaffInShift(staff_id, shiftDate, venue_id, client_id, start_time, end_time, venue_detail_id = '') {
    var hours = getHours(start_time, end_time);
    var myJsonData = { day: shiftDate, venue_id: venue_id, staff_id: staff_id, start_time: start_time, end_time: end_time, row_index: $('#shiftstaffDataTable tr:last').attr('data-index'), client_id: client_id, hours: hours, venue_detail_id: venue_detail_id };
    //console.log(myJsonData);return false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/addStaffToShift',
        data: myJsonData,
        dataType: 'JSON',
        success: function (data) {
            if (data.status == 1) {
                $('#staffDataTable_body').append(data.data);
                $("#staff_list option[value='" + staff_id + "']").remove();
                reinit();
            }
        }
    });
}

function removeStaffFromShift(id) {
    //var myJsonData = {staff_schedule_id: staff_schedule_id};
    var formData = $("#passDataForm").serialize();
    var myJsonData = { formData: formData, staff_row_id: id };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/removeStaffFromShift',
        data: myJsonData,
        dataType: 'JSON',
        success: function (data) {
            $(".response_message").text(data.message);

            if (data.status) {

                // setTimeout(function(){
                // location.reload(true);
                // },1000);

                $(".response_message").addClass('text-green');
                $(".response_message").removeClass('text-red');
                $('table#shiftstaffDataTable tr#tr-' + $('#delete_data_id').val()).remove();

            } else {

                $(".response_message").addClass('text-red');
                $(".response_message").removeClass('text-green');

            }
            // return false;
            // if(data.status == 1){
            //  $('#staffDataTable_body > #tr-'+staff_schedule_id).remove();
            // }
        }
    });
}

function abbb(id) {
    if ($('#staffList_new li#li-' + id + '').hasClass('selected')) {
        $('#staffList_new li#li-' + id + '').removeClass('selected');
    } else {
        $('#staffList_new li').removeClass('selected');
        $('#staffList_new li#li-' + id + '').addClass('selected');

    }
    //$('#staffList_new li#li-'+id+'').css('display','none');
}

function select_staff(id) {
    var runtime_selected_staff = [];
    var client_id = $('#venue_client_id').val();
    var tr = $('#' + id).closest('tr').attr('id');
    var day = $('#' + id).closest('tr').find('td:nth-child(2)').find('input[name="day[]"]').val();
    var start_time = $('#' + id).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val();
    var end_time = $('#' + id).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val();
    var stafflist = $('#' + id).closest('tr').find('td:nth-child(8)').find('input[name="selected_staff[]"]').val();
    $('input[name="selected_staff[]"]').each(function () {
        if ($(this).val() != '') {
            runtime_selected_staff.push({
                staff: $(this).val(),
                start_time: $(this).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val(),
                end_time: $(this).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val(),
                day: $(this).closest('tr').find('td:nth-child(2)').find('input[name="day[]"]').val()
            });
        }
    });
    //console.log(runtime_selected_staff);
    $('tbody#venue_shifts_tbody tr').css('background-color', 'transparent');
    $('#' + id).css('background-color', '#48a3d8');
    //console.log(stafflist);
    var myJsonData = { start_time: start_time, end_time: end_time, day: day, client_id: client_id, tr: tr, stafflist: stafflist, runtime_selected_staff: runtime_selected_staff };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/getAvailableStaffList1',
        data: myJsonData,
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            $('#staffList_new').html(data.staffList);
            $('.staff_type_radio').removeAttr('checked');
            $('.staff_type_labels').removeClass('active');
            $('#staff_type_1').click();
            $('#staff_type_label-1').addClass(' active');
        }
    });


    return false;
}

function CheckValidation() {
    var form_id = "venue_schedule_form";
    var validation = true;
    form_id = form_id;
    $('.error_span').remove();
    $('input[data-validation="true"]').each(function () {
        if (this.value == null || this.value == '' || this.value == " ") {
            var error_span = '<span class="error_span">Required</span>';
            //$("#"+this.id).addClass('error');
            $("#" + this.id).after(error_span);
            validation = false;
        } else {
            $("#" + this.id).removeClass('error');
        }
    });

    $('select[data-validation="true"]').each(function () {
        if (this.value == null || this.value == '' || this.value == " ") {
            var error_span = '<span class="error_span">Required</span>';
            //$("#"+this.id).addClass('error');
            $("#" + this.id).closest('div').append(error_span);
            validation = false;
        } else {
            $("#" + this.id).removeClass('error');
        }
    });

    return validation;
}

function schedule_details(client_id, shiftDate, element_id) {
    //alert(client_id+' '+shiftDate);
    $('table#shiftstaffDataTable').DataTable({
        'ordering': false
    });

    if ($('#' + element_id).children().length > 0) {
        var myJsonData = { shiftDate: shiftDate, client_id: client_id };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".image_select2").select2({
            templateResult: tmpResultFormat,
            templateSelection: tmpSelectionFormat
        });

        $.ajax({
            type: 'POST',
            url: '/getShiftScheduleStaff1',
            data: myJsonData,
            dataType: 'JSON',
            success: function (data) {
                $('#shiftstaffDataTable').dataTable().fnDestroy();

                // $('#venue_id_popup').val(venue_id);
                $('#client_id_popup').val(client_id);
                // $('#shift_start_time').text(moment(start_time, 'hh:mm A').format('hh:mm A'));
                // $('#shift_start_time-hidden').val(start_time);
                // $('#shift_end_time-hidden').val(end_time);
                // $('#shift_end_time').text(moment(end_time, 'hh:mm A').format('hh:mm A'));
                $('#shift_date').text(shiftDate);
                if (data.status == 1) {
                    $('#number_staff_scheduled').text(data.total_staff_scheduled);
                    $('#staffDataTable_body').html();
                    $('#staffDataTable_body').html(data.data);
                    $('#edit_venue_shift_modal').modal({ backdrop: 'static', keyboard: false });

                } else {
                    $('#number_staff_scheduled').text('');
                    $('#staffDataTable_body').empty();
                }

                $('#staff_list').select2('open');
                // if(data.staffListView != ''){
                //     $('#staff_list').append(data.staffListView);
                // }else{
                //     $('#staff_list').empty();
                // }
                $('#shiftstaffDataTable').DataTable({
                    "paging": true,
                    "ordering": false,
                    "info": true,
                    "bFilter": true
                });

                reinit();
            }
        });
    }
}

function addNewStaffRow() {
    var newRow = $('<tr><input type="hidden" name="arrayC[][id]" value="">');
    var cols = '<td class="remove_staff-td sorting_1"><i onclick="removeStaffFromShift();" class="fa fa-minus-circle"></i></td>';
    cols += '<td><div class="form-group row"><div class="col-md-12"><select name="" id="staff_list" class="form-control image_select2 custom_css" style="width:100%"></select></div></div></td>';
    cols += '<td></td>';
    cols += '<td></td>';
    cols += '<td> <div class="input-group"><input type="text" readonly="" value="" name="arrayC[][start_time]" class="form-control timepicker shift_start_time"><div class="input-group-addon"><i class="fa fa-clock-o"><i></div></div></td>';
    cols += '<td> <div class="input-group"><input type="text" readonly="" value="" name="arrayC[][end_time]" class="form-control timepicker shift_end_time"><div class="input-group-addon"><i class="fa fa-clock-o"><i></div></div></td>';
    cols += '<td class="td-ss-status"><label class="label bg-yellow"></label></td>';
    cols += '<td><div class="btn-group"><button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" style="background-color:#fff" role="menu"><li><a href="#" class="blockStaffFromShiftAnchor" data-staff-id="" data-ss-id="" data-val="pending" data-toggle="">Block / Un Block Staff From Shift</a></li></ul></div></td>';
    newRow.append(cols);
    $('#staffDataTable_body').append(newRow);
    reinit();

}

function initializeSmsTrigger(btnID) {
    var temp = btnID.split("-");
    $("form#send_sms_form-venue .bulk_staff_temp_list").html('');
    $('form#send_sms_form-venue .send_sms_submit').removeAttr('onClick');
    $('form#send_sms_form-venue')[0].reset();
    $('form#send_sms_form-venue input[name="phone_number"]').tagsinput('removeAll');
    var data_contact = $("#" + btnID).attr('data-contact');
    var staff_id = $("#" + btnID).attr('data-staff_id');
    var staff_name = $("#" + btnID).attr('data-staff_name');
    var clientID = $('#client_id_popup').val();
    var staff_image_temp = '<span class="staff_tags_temp bold">' + staff_name + '</span>';
    $("form#send_sms_form-venue .bulk_staff_temp_list").html(staff_image_temp);
    $('form#send_sms_form-venue input[name="phone_number"]').tagsinput('add', data_contact);
    $('form#send_sms_form-venue .bootstrap-tagsinput input').css('width', '0'); // for non edit able
    $('form#send_sms_form-venue .bootstrap-tagsinput input').attr('readonly', 'readonly'); // for non edit able
    $('form#send_sms_form-venue input[name="staff_ids"]').val(staff_id);
    $('form#send_sms_form-venue input[name="client_ids"]').val($('#client_id_popup').val());
    $('form#send_sms_form-venue input[name="venue_ids"]').val($("#staff_checkbox-" + temp['1']).attr('data-venue_id'));
    $('form#send_sms_form-venue input[name="clientName"]').val($(".clientIdz-" + clientID).attr('data-clname'));
    $("form#send_sms_form-venue .bootstrap-tagsinput span[data-role='remove']").removeAttr('data-role');
    $(".response_message").text('');
    // $('form#send_sms_form-venue .send_sms_submit').attr('onClick', 'SendSmstoStaff("' + temp['1'] + '","ghg678687t")');
    $('form#send_sms_form-venue .send_sms_submit').attr('onClick', 'sentWhatsappMsg("' + temp['1'] + '","' + staff_id + '")');
    // alert($('#client_id_popup').val());
    $('#edit_venue_schedule-body').css('display', 'none');
    $('#send_sms_modal-body').css('display', 'block');

}

function openBulkSmsPopup() {
    //$('form#send_sms_form-venue')[0].reset();
    $(".response_message").text('');
    $('form#send_sms_form-venue input[name="phone_number"]').tagsinput('removeAll');
    $("form#send_sms_form-venue .bulk_staff_temp_list").html('');

    var ret = getTotalCheckedStaff();
    if (ret == false) {
        return ret;
    }
    var staff_ids = "";
    var arr = [];
    var vntime = [];
    var venueId = [];
    $("table#shiftstaffDataTable tr td input.staff_sch_check:checked").each(function (ev) {
        var data_contact = $(this).attr('data-contact');
        var tr_id = $(this).parent('td').parent('tr').attr('id');
        var venueStartD = $(this).parent('td').parent('tr').attr('data-start_time');
        var venueid = $(this).parent('td').parent('tr').attr('data-venue_id');
        var temp = tr_id.split("-");
        arr.push(temp['1']);
        vntime.push(venueStartD);
        venueId.push(venueid);

        var staff_image_temp = '<span class="staff_tags_temp ">' + $(this).parent('td').parent('tr').find('td.staff_image_temp').html() + '</span>';
        staff_ids += $(this).attr('data-staff_id') + ",";
        var data_contact = $(this).attr('data-contact');
        $('form#send_sms_form-venue input[name="phone_number"]').tagsinput('add', data_contact);
        $('form#send_sms_form-venue .bulk_staff_temp_list.sms').append(staff_image_temp);
    }); // LOOP ENDS
    // return false;

    var staff_ids = staff_ids.replace(/^,|,$/g, '');
    $('form#send_sms_form-venue input[name="staff_ids"]').val(staff_ids);

    $('form#send_sms_form-venue .bootstrap-tagsinput input').css('width', '0'); // for non edit able
    $('form#send_sms_form-venue .bootstrap-tagsinput input').attr('readonly', 'readonly'); // for non edit able
    $("form#send_sms_form-venue .bootstrap-tagsinput span[data-role='remove']").removeAttr('data-role');
    $('form#send_sms_form-venue .send_sms_submit').attr('onClick', 'SendSmstoStaff("' + arr.join(',') + '","' + '' + '","' + vntime.join(',') + '","' + venueId.join(',') + '")');
    // $('form#send_sms_form-venue .send_sms_submit').attr('onClick', 'sentWhatsappMsg("' + arr.join(',') + '","'+''+'","'+vntime.join(',')+'")');
    $('#edit_venue_schedule-body').css('display', 'none');
    $('#send_sms_modal-body').css('display', 'block');
}
function sentWhatsappMsg(sendSmsStaffId, staff_id, vndate) {
    // alert(vndate);return;
    var event_arr_timeReq = $('form#send_sms_form-venue input[name="arrving_timeV"]').val();
    var eventBrie_timeReq = $('form#send_sms_form-venue input[name="briefing_timeV"]').val();
    var event_loc_guideReq = $('form#send_sms_form-venue input[name="localtionGuideV"]').val();
    var event_dress_codeReq = $('form#send_sms_form-venue textarea[name="dressCodeV"]').val();
    var VenueName = $('form#send_sms_form-venue input[name="clientName"]').val();
    var phone_number = $('form#send_sms_form-venue input[name="phone_number"]').val();
    var data_venue_date = $("table#shiftstaffDataTable #bulk_sms_data_" + staff_id).attr('data-venue_date');
    var start_time = $('#bulk_sms_data_' + staff_id).attr('data-start_time');
    var signMeetPt = $('form#send_sms_form-venue input[name="signMeetPt"]').val();
    // if (data_venue_date == undefined) {
    // 	data_venue_date = '2019-11-27';
    // 	}
    var phone_number = parseInt(phone_number, 10);
    if (event_arr_timeReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#arrving_timeV'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Arriving Time!!!'
        });
        return false;
    }
    if (eventBrie_timeReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#briefing_timeV'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Briefing Time!!!'
        });
        return false;
    }
    if (event_loc_guideReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#localtionGuideV'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Location guide!!!'
        });
        return false;
    }
    if (event_dress_codeReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#dressCodeV'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Dress Code!!!'
        });
        return false;
    }
    if (signMeetPt == '') {
        $('#signMeetPt').jqListbox('transferSelectedTo', $('#signMeetPt'));
        iziToast.show({
            title: 'Required',
            color: 'red',
            message: 'Siging Area/Meeting point!!!'
        });
        return false;
    }
    var currentDate = new Date();
    var currentDates = (currentDate.getFullYear() + '-' + (currentDate.getMonth() + 1) + '-' + currentDate.getDate());
    var ph_number = phone_number;
    var xhr = new XMLHttpRequest(),
        body = JSON.stringify(
            {
                "messages": [
                    {
                        "to": ph_number,
                        "channel": "whatsapp",
                        "hsm": {
                            "template": "venue_event_confirmation",
                            "parameters": {
                                "1": VenueName,
                                "2": VenueName + ' Location ' + event_loc_guideReq,
                                "3": signMeetPt,
                                "4": data_venue_date,
                                "5": event_arr_timeReq,
                                "6": eventBrie_timeReq,
                                "7": start_time,
                                "8": event_dress_codeReq
                            }
                        }
                    }
                ]
            }
        );
    xhr.open('POST', 'https://platform.clickatell.com/v1/message', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('Authorization', '5dfJ3cO_Riiw62Gv4RmB_g==');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 202) {
            var data = JSON.parse(this.response);
            // console.log(data);
            if (data.messages[0]['accepted'] == true) {
                var MessageId = data.messages[0]['apiMessageId'];
                console.log('text' + MessageId);
                SendSmstoStaff(sendSmsStaffId, MessageId, vndate);

            } else {
                alert('Something_Wrong');
                return false;
            }
        }
    };

    xhr.send(body);
}
function getTotalCheckedStaff() {
    var total = $("#shiftstaffDataTable").find('input.staff_sch_check:checked').length;
    if (total == 0) {
        alert("Please Select Staff To Send Bulk Sms!!! ")
        return false;
    } else {
        return total;
    }
}

function SendSmstoStaff(staff_schedule_pid = false, MessageId, VnStartTime, venueId) {
    // if(!temp){
    //     console.log(temp);
    //     return temp;
    // }else{
    //     console.log(temp);

    // }
    // alert(staff_schedule_pid);
    // alert(MessageId);
    // alert(VnId);return;
    if ($("#message_body_evp").val() != ' ' && $("#message_body_evp").val() != " ") {
        $('form#send_sms_form-venue .response_message').text(' ');
        var staff_id = $('form#send_sms_form-venue input[name="staff_ids"]').val();
        updateStaffScheduleJson(staff_id, staff_schedule_pid, MessageId, VnStartTime, venueId);
        var ss_pids_array = staff_schedule_pid.split(',');
        for (var i = 0; i < ss_pids_array.length; i++) {
            $('#shiftstaffDataTable tr#tr-' + ss_pids_array[i] + ' td.td-ss-sms-status > label').text('sent');
            $('#shiftstaffDataTable tr#tr-' + ss_pids_array[i] + ' td.td-ss-sms-status > label').removeClass('bg-blue').addClass(' bg-yellow');
        }
        //$("form#edit_venue_shift_form #sms_status-"+staff_id).val('pending');
        //$('#shiftstaffDataTable tr#tr-'+staff_schedule_pid+' td.td-ss-sms-status > label').text('sent');
        // $("#SendSMSPopup").modal('hide');
    } else {
        $('form#send_sms_form-venue .response_message').text('Type Your Message');
    }
    return false;
}

function updateStaffScheduleJson(staff_id, staff_schedule_pid, MessageId, VnStartTime, venueIds) {
    //var event_id = $("#event_id").val();
    // alert(staff_id);
    // alert(staff_schedule_pid);
    // alert(MessageId);
    // alert(VnId);
    // return;
    var formData = $('#send_sms_form-venue').serialize();
    var data_venue_date = $("#bulk_sms_data_" + staff_id).attr('data-venue_date');
    var data_venueName = $("#bulk_sms_data_" + staff_id).attr('data-venue_name');
    var data_venue_name = $('form#send_sms_form-venue input[name="clientName"]').val();
    var phone_number = $('form#send_sms_form-venue input[name="phone_number"]').val();

    var myJsonData = { formData: formData, MessageId: MessageId, phone_number: phone_number, data_venueNameArr: data_venueName, venueIdsArr: venueIds, VnStartTimeArr: VnStartTime, data_venue_date: data_venue_date, data_venue_name: data_venue_name, staff_schedule_pid: staff_schedule_pid, is_bulk_sms: 0 };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.post('/update_staff_schedule_venue', myJsonData, function (response) {
        //console.log(response);return false;
        var obj = JSON.parse(response);
        $(".response_message").text(obj.message);

        // return false;
        if (obj.status == "200") {
            var ss_pids_array = staff_schedule_pid.split(',');

            $(".response_message").text('Message successfully sent');
            $(".response_message").addClass('text-green');
            $(".response_message").removeClass('text-red');
            for (var i = 0; i < ss_pids_array.length; i++) {
                $("tr#tr-" + ss_pids_array[i]).addClass('disable_sms');
                $("tr#tr-" + ss_pids_array[i] + " td.td_contact_no").removeClass('enable_sms');
                $("tr#tr-" + ss_pids_array[i] + " td.td_contact_no").addClass('disable_sms');
                $("#sms_status_btn-" + ss_pids_array[i]).remove();
            }
            $("form#send_sms_form")[0].reset();
            $('#edit_venue_schedule-body').css('display', 'block');
            $('#send_sms_modal-body').css('display', 'none');
            iziToast.show({
                title: 'Confirm',
                color: 'green', // blue, red, green, yellow
                message: 'Sms send sucessfully!'
            });
            // $("#SendSMSPopup").modal('hide');
        } else {

            $(".response_message").addClass('text-red');
            $(".response_message").removeClass('text-green');

        }

    });
    return false;
}

function calculateStaffHours(id = '', temp1 = '') {
    if (id != '' && temp1 == '') {
        var from = $('#' + id).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val();
        var to = $('#' + id).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val();
    } else if (id != '' && temp1 == 1) {
        var from = $('#' + id).find('input.shift_start_time').val();
        var to = $('#' + id).find('input.shift_end_time').val();
    } else {
        var from = $('#outer_from').val();
        var to = $('#outer_to').val();
    }
    var start = moment.utc(from, "HH:mm");
    var end = moment.utc(to, "HH:mm");
    var hour = moment.utc(end.diff(start)).format("HH");
    var minute = moment.utc(end.diff(start)).format("mm");
    var duration = parseFloat(minute / 60) + parseFloat(hour) || 0;

    if (id != '' && temp1 == '') {
        $('#' + id).closest('tr').find('td:nth-child(5)').find('input[name="shift_hours[]"]').val(duration.toFixed(2));
    } else if (id != '' && temp1 == 1) {
        $('tr#' + id).find('input.hours').val(duration.toFixed(2));
    } else {
        $('#hours-hidden').val(duration.toFixed(2));
    }

}

function CheckTotalConfirmation() {
    var count = 0;
    $("select.staff_status_select").each(function (ev) {

        if ($(this).val() == 'confirmed') {
            count++;
        }

    });
    if (count == 0) {
        $(".send_payroll_btn").hide();
    } else {
        $(".send_payroll_btn").show();
    }
}

function getAvailableStaffList(staff_type_id) {
    var myJsonData = { venue_id: $('#venue_id_popup').val(), shiftDate: $('#shift_date').text(), client_id: $('#client_id_popup').val(), start_time: $('#shift_start_time-hidden').val(), end_time: $('#shift_end_time-hidden').val(), staff_type: staff_type_id };
    //console.log(myJsonData);return false;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: '/getAvailableStaff',
        data: myJsonData,
        dataType: 'JSON',
        beforeSend: function () {
            console.log('beforesuccess');
        },
        success: function (data) {
            if (data.staffListView != '') {
                $('#staff_list').empty();
                $('#staff_list').append(data.staffListView);
            } else {
                $('#staff_list').empty();
            }
        }
        // error:function(){
        //     alert('error');
        // }
    });
    //return false;
}

jQuery("#search").keyup(function () {
    var filter = jQuery(this).val();
    jQuery("ul#staffList_new li").each(function () {
        if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            jQuery(this).hide();
        } else {
            jQuery(this).show();
        }
    });
});
jQuery("#search").blur(function () {
    var filter = jQuery(this).val();
    jQuery("ul#staffList_new li").each(function () {
        if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            jQuery(this).hide();
        } else {
            jQuery(this).show();
        }
    });
});
//VueJs
let vm = new Vue({
    el: "#appss",
    data: {
        localtionGuideV: '',
        dressCodeV: '',
        briefing_timeV: '',
        arrving_timeV: '',
        signMeetPt: '',
    },
    // mounted: function(){
    // 	var self = this;
    // 	$("#arrving_time").datepicker({
    // 		onSelect: function(selectedDate, datePicker){
    // 			self.arrving_time = selectedDate;
    // 		}
    // 	});
    // }
})
jQuery("#search_client").keyup(function () {

    var filter = jQuery(this).val();
    jQuery("table#scheduler th span").each(function () {

        var id = jQuery(this).attr('data-id');
        if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            jQuery(this).parent().hide();
            jQuery('.clientIdz-' + id).addClass('hide');
        } else {
            jQuery(this).parent().show();
            jQuery('.clientIdz-' + id).removeClass('hide');
        }

    });
});
$('.send_message').on('click', function () {
    id = $('.venue_detail_id').val();
    document.location.href = 'venue_sendsms/' + id;
});