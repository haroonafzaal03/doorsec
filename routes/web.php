<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
//Route::group( ['middleware' => 'auth' ], function()
//{

//WhatsApp API
Route::get('/whatsapp_module', 'WhatsappController@index')->name('whatsapp_module');
Route::get('/response', 'WhatsappController@response')->name('responses');
Route::get('/status', 'WhatsappController@statusResponse')->name('status');
Route::get('/whatsapp', 'WhatsappController@create');

/**/
Route::get('/', 'HomeController@club_event')->name('home');
//User Rights
Route::post('/Role/Update/{id}', 'RoleController@Update')->name('role_update');
Route::post('/Role/Create', 'RoleController@Create')->name('role_create');

Route::get('/roles/edit/{id}', 'RoleController@edit')->middleware(['auth', 'auth.admin:edit.user.role'])->name('roles_edit');
Route::get('/userrights', 'UserRightsController@index')->middleware(['auth', 'auth.admin:user'])->name('userrights');
Route::get('/rolesrights', 'UserRightsController@roles')->middleware(['auth', 'auth.admin:view.roles'])->name('rolesrights');
Route::get('/userrights/edit/{id}', 'UserRightsController@editrole')->middleware(['auth', 'auth.admin:edit.user.role'])->name('userright_edit');
Route::post('/userRole/Update/{id}', 'UserRightsController@userRoleUpdate')->middleware(['auth', 'auth.admin:edit.role.permissions'])->name('userrole_update');
//Registration
Route::get('/register', 'Auth\RegisterController@index')->middleware(['auth', 'auth.admin:user'])->name('register');

//client
Route::get('/client', 'ClientController@index')->middleware(['auth', 'auth.admin:client'])->name('client');
Route::get('/client/create', 'ClientController@create')->middleware(['auth', 'auth.admin:add.new.client'])->name('client_create');
Route::post('/client/store', 'ClientController@store')->middleware(['auth', 'auth.admin:client'])->name('client_store');
Route::get('/client/{id}', 'ClientController@show')->middleware(['auth', 'auth.admin:client.show']);
Route::get('/client/edit/{id}', 'ClientController@edit')->middleware(['auth', 'auth.admin:edit.client.profile']);
Route::post('/client/update/{id}', 'ClientController@update')->middleware(['auth', 'auth.admin:edit.client.profile'])->name('client_update');
Route::get('/client/remove/{id}', 'ClientController@destroy')->middleware(['auth', 'auth.admin:delete.client'])->name('client_remove');

//guarding staff
Route::get('/staff', 'StaffController@index')->middleware(['auth', 'auth.admin:staff'])->name('staff');
Route::get('/staff/create', 'StaffController@create')->middleware(['auth', 'auth.admin:add.new.staff'])->name('staff_create');
Route::post('/staff/store', 'StaffController@store')->middleware(['auth', 'auth.admin:staff'])->name('staff_store');
Route::get('/staff/{id}', 'StaffController@show')->middleware(['auth', 'auth.admin:staff'])->name('staff_show');
Route::get('/staff/edit/{id}', 'StaffController@edit')->middleware(['auth', 'auth.admin:edit.staff.profile'])->name('staff_edit');
Route::post('/staff/update/{id}', 'StaffController@update')->middleware(['auth', 'auth.admin:edit.staff.profile'])->name('staff_update');
Route::get('/staff/remove/{id}', 'StaffController@destroy')->middleware(['auth', 'auth.admin:delete.staff'])->name('staff_remove');
/*->middleware(['auth','auth.admin:'])*/
//schedule
Route::get('/schedule', 'ScheduleController@index')->middleware(['auth', 'auth.admin:schedule'])->name('schedule');
Route::get('/schedule/create', 'ScheduleController@create')->middleware(['auth', 'auth.admin:schedule.now'])->name('schedule_create');
Route::post('/schedule/store', 'ScheduleController@store')->middleware(['auth', 'auth.admin:schedule'])->name('schedule_store');
Route::get('/schedule_details/{id}', 'ScheduleController@show')->middleware(['auth', 'auth.admin:schedule.show'])->name('schedule_details');
Route::get('/schedule/edit/{id}', 'ScheduleController@edit')->middleware(['auth', 'auth.admin:edit.schedule'])->name('schedule_edit');
Route::get('/schedule/staff_schedule/{id}', 'StaffScheduleController@index')->middleware(['auth', 'auth.admin:add.staff.schedule'])->name('staff_schedule');
Route::post('/schedule/staff_schedule_store/', 'StaffScheduleController@store')->middleware(['auth', 'auth.admin:staff'])->name('staff_schedule_store');
Route::post('/event/staff_schedule_update/{id}', 'StaffScheduleController@update')->middleware(['auth', 'auth.admin:edit.schedule'])->name('event_staff_schedule_update');
Route::get('/export_guardingSchedule/{id}', 'ScheduleController@export_guardingSchedule')->middleware(['auth', 'auth.admin:schedule'])->name('export_guardingSchedule');

Route::post('/schedule/update/{id}', 'ScheduleController@update')->middleware(['auth', 'auth.admin:edit.schedule'])->name('schedule_update');
Route::get('/schedule/remove/{id}', 'ScheduleController@destroy')->middleware(['auth', 'auth.admin:edit.schedule'])->name('schedule_remove');
Route::get('/edit_schedule/{id}', 'ScheduleController@edit')->middleware(['auth', 'auth.admin:edit.schedule'])->name('edit_schedule');


//schedule
// Route::get('/event', 'EventController@index')->name('event');
// Route::get('/event/create', 'EventController@create')->name('event_create');
// Route::post('/event/store', 'EventController@store')->name('event_store');
// Route::get('/event_detail/{id}', 'EventController@show')->name('event_details');
// Route::get('/event/edit/{id}', 'EventController@edit')->name('event_edit');
// Route::post('/event/update/{id}', 'EventController@update')->name('event_update');
// Route::get('/event/remove/{id}', 'EventController@destroy')->name('event_remove');
// Route::get('/edit_event/{id}', 'EventController@edit')->name('edit_event');
Route::post('/check_document_for_export/{id}', 'EventController@export_staff_by_event_id')->name('export_staff_by_event_id');
Route::get('/event', 'EventController@index')->middleware(['auth', 'auth.admin:event'])->name('event');
Route::get('/event/create', 'EventController@create')->middleware(['auth', 'auth.admin:add.new.event'])->name('event_create');
Route::post('/event/store', 'EventController@store')->middleware(['auth', 'auth.admin:event'])->name('event_store');
Route::get('/event_detail/{id}', 'EventController@show')->middleware(['auth', 'auth.admin:event.show'])->name('event_details');
Route::get('/event/edit/{id}', 'EventController@edit')->middleware(['auth', 'auth.admin:event.edit'])->name('event_edit');
Route::post('/event/update/{id}', 'EventController@update')->middleware(['auth', 'auth.admin:event.edit'])->name('event_update');
Route::get('/event/remove/{id}', 'EventController@destroy')->middleware(['auth', 'auth.admin:event.remove'])->name('event_remove');
Route::get('/edit_event/{id}', 'EventController@edit')->middleware(['auth', 'auth.admin:event.edit'])->name('edit_event');
Route::get('/duplicate_event/{id}', 'EventController@duplicate')->name('duplicate_event');
Route::post('/store_duplicate', 'EventController@store_duplicate')->name('store_duplicate');


// Auth::routes();

// Guarding
// Route::get('/guarding', 'HomeController@index')->name('guarding');
// Route::post('/switch_board', 'HomeController@switchDashboard')->name('switch_board');
// Route::get('/guarding/create', 'GuardingController@create')->name('guarding_create');
// Route::get('/guarding/schedule/{id}', 'GuardingController@show')->name('guarding_schedule');
// Route::post('/guarding/store', 'GuardingController@store')->name('guarding_store');

Route::get('/guarding/index', 'GuardingController@index')->name('guarding_view');
Route::post('/getAvailableStaffList', 'GuardingController@getAvailableStaffList');
Route::get('/guarding', 'HomeController@index')->middleware(['auth', 'auth.admin:guarding'])->name('guarding');
Route::post('/switch_board', 'HomeController@switchDashboard')->middleware(['auth', 'auth.admin:guarding'])->name('switch_board');
Route::get('/switch_board/{id}', 'HomeController@switchDashboard_login')->middleware(['auth', 'auth.admin:guarding'])->name('switch_board_login');
Route::get('/guarding/create', 'GuardingController@create')->middleware(['auth', 'auth.admin:add.guard'])->name('guarding_create');
Route::get('/guarding/schedule/{id}', 'GuardingController@show')->middleware(['auth', 'auth.admin:guard.schedule'])->name('guarding_schedule');
Route::post('/guarding/store', 'GuardingController@store')->middleware(['auth', 'auth.admin:guard.schedule'])->name('guarding_store');
Route::get('/guarding/edit/{id}', 'GuardingController@edit')->name('guarding_edit');
Route::get('/guarding/duplicate/{id}', 'GuardingController@duplicate')->name('guarding_duplicate');
Route::post('/guarding/update/{guarding}', 'GuardingController@update')->name('guarding_update');
Route::get('/guarding/remove/{id}', 'GuardingController@removeStaff_fromSchedule')->name('guarding_removeStaff');
Route::post('/disciplinaries', 'StaffDisciplinaryController@store')->name('disciplinaries_store');
Route::post('/disciplinaries/{id}/delete', 'StaffDisciplinaryController@destroy')->name('disciplinaries_delete');
Route::post('/disciplinaries/{id}/update', 'StaffDisciplinaryController@update')->name('disciplinaries_update');
// Route::post('/guarding_schedule/update/{gudardingSchedule}', 'GudardingScheduleController@update')->name('guarding_schedule_update');

// Club Event
Route::get('/club_event', 'HomeController@club_event')->name('club_event');
// Route::get('/club_event', 'HomeController@club_event')->name('club_event');

//Venue
Route::get('/venue', 'VenueController@index')->middleware(['auth', 'auth.admin:venue'])->name('venue');
Route::get('/venue_detail', 'VenueDetailController@index')->middleware(['auth', 'auth.admin:venue'])->name('venue_detail');
Route::get('/venue_sendsms/{id}', 'VenueDetailController@send_sms_view')->name('venue_sendsms');
Route::post('/venue/store', 'VenueController@store')->middleware(['auth', 'auth.admin:venue'])->name('venue_store');
Route::get('/event/schedule', 'EventController@schedule')->middleware(['auth', 'auth.admin:schedule.event.view'])->name('event_schedule');
Route::post('/sendwhatsappmessage/{id}', 'VenueDetailController@sendwhatsappmessage')->name('venue_whatsapp');

//Payroll
// Route::get('/payroll', 'PayrollController@index')->name('payroll');
// Route::post('/payroll_update', 'PayrollController@update')->name('payroll_update');
// Route::post('/print_slip', 'PayrollController@print_payment_slip')->name('print_slip');
// Route::get('/payroll_details', 'PayrollController@payroll_details')->name('payroll_details');
Route::get('/payroll/edit/{id}', 'PayrollController@payroll_details')->name('payroll_details');
Route::get('/payroll', 'PayrollController@index')->middleware(['auth', 'auth.admin:payroll'])->name('payroll');
Route::post('/payroll_update', 'PayrollController@update')->middleware(['auth', 'auth.admin:edit.payroll'])->name('payroll_update');
Route::post('/print_slip', 'PayrollController@print_payment_slip')->middleware(['auth', 'auth.admin:print.payment.slip'])->name('print_slip');


// EXPORT
Route::post('/export_event/{id}', 'EventController@export')->name('export_event');
Route::get('/export_staff_timesheet/{id}', 'EventController@export_staff_timesheet')->name('export_staff_timesheet');
//Attendance
Route::get('/attendance', 'AttendanceController@index')->middleware(['auth', 'auth.admin:mark.attendance'])->name('attendance');

//Event Logs
Route::get('/event_logs/{id}', 'EventLogsController@index')->name('event_logs');;
Route::get('/export_event_logs/{id}', 'EventLogsController@export')->name('export_event_logs');;



//});

// Route::group(['middleware' => ['auth:api']], function () {
// Ajax Routes
Route::post('/DeleteRecord', 'HomeController@PasswordVerification');
Route::post('/RemoveStaffDetails', 'StaffController@destroy');
Route::post('/RemoveClient', 'ClientController@destroy');
Route::post('/BlockStaff_off', 'StaffController@block_staff');
Route::post('/DeleteSchedule', 'ScheduleController@destroy');
Route::post('/DeleteEvent', 'EventController@destroy');
Route::post('/DeleteStaffSchedule/{id}', 'StaffScheduleController@destroy');
Route::get('/get_client_data_json/{id}', 'ClientController@getDataByidJson');
Route::post('/update_staff_status', 'StaffController@update_status');
Route::post('/BlockStaff', 'StaffController@BlockStaffforClient');
Route::post('/getPayrollDetails', 'PayrollController@filters');
Route::post('/update_event_status', 'EventController@close_event');
Route::post('/getLocations', 'ClientController@get_all_locations');
Route::post('/getShiftScheduleStaff', 'VenueController@getShiftScheduledStaff');
Route::post('/addStaffToShift', 'VenueController@addStaffToShift');
Route::post('/removeStaffFromShift', 'VenueController@removeStaffFromShift');
Route::post('/update_staff_schedule_json', 'StaffScheduleController@updateStaffScheduleByJson');
Route::post('/get_payment_logs_json/{id}', 'PayrollController@getPaymentLogsById');
Route::post('/getStaffByJSON', 'StaffController@getStaffJson');
Route::post('/get_event_venue_list', 'ClientController@getEventVenueJson');
Route::post('/blockStaffFromShift', 'VenueController@blockStaffFromShift');
Route::post('/updateShiftStaff', 'VenueController@updateShiftStaff');
Route::post('/destroyVenue', 'VenueController@destroy');
Route::post('/getAvailableStaff', 'VenueController@getAvailableStaffList');
Route::post('/getStaffAttendance', 'AttendanceController@filters');
Route::post('/saveAttendance', 'AttendanceController@store');
Route::post('/saveEventLogs', 'EventLogsController@store');
Route::post('/getAvailableStaffList1', 'VenueController@getAvailableStaffList1');
Route::post('/getShiftScheduleStaff1', 'VenueController@getShiftScheduledStaff1');
Route::post('/update_staff_schedule_venue', 'VenueController@updateStaffScheduleByJson');
Route::post('/sendPayroll', 'VenueController@sendPayroll');
Route::get('/clickatelTest', 'VenueController@clickatelTest');
Route::post('/event/clickatelTest', 'EventController@clickatelTest');
Route::post('/get_event_venue_lists', 'PayrollController@getEventVenueJson');
Route::post('/get_pay_roll', 'PayrollController@show');
Route::post('/check_documents/{id}', 'EventController@check_document_for_export');
Route::post('/guarding_scheduleupdate/{id}', 'GudardingScheduleController@update');
Route::get('/update_message_status/{id}', 'EventController@update_message')->name('update_message');
Route::get('/event_print_schedule_event/{id}', 'EventController@schedule_print')->name('event_schedule_print');
Route::get('/venue_print_schedule_event/{id}', 'VenueController@schedule_print')->name('venueschedule_print');
Route::post('/add_certificate', 'StaffController@addCertificate')->name('staff_certificate_add');


// });