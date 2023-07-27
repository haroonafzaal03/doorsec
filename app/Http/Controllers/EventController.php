<?php

namespace App\Http\Controllers;

use App\Event;
use App\Client;
use App\Staff;
use App\StaffType;
use App\User;
use App\StaffSchedule;
use App\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Exports\SaffScheduleExport;
use App\Exports\TimeSheetExport;
use Excel;
use DB;
use App\Helper\Helper;
use JavaScript;
use Storage;
use File;
use ZipArchive;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use App\Whatsapp;
use App\EventConfirmation;
use Carbon\Carbon;
use PDF;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        $schedule  = Event::all()->where('is_deleted', '0');
        //  dd($schedule);
        return view('club_event.event.index', compact('schedule'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client  = Client::all()->where('status', 'active')->where('client_type_id', '=', 2);
        return view('club_event.event.create', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //schdeule_to schdeule_from client_id	start_date	end_date	start_time	end_time	location total_staff	contact_person	event_type	status
        $this->validate($request, [
            'client_id' => 'required',
            'start_date' => 'required',
            'event_name' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'total_staff' => 'required',
            'event_type' => 'required',
        ]);
        $schedule = $request->all();
        $schedule['start_date'] =   date("Y-m-d", strtotime($schedule['start_date']));
        $schedule['end_date'] =   date("Y-m-d", strtotime($schedule['end_date']));
        $schedule['start_time'] =   date("H:i:s", strtotime($schedule['start_time']));
        $schedule['end_time'] =   date("H:i:s", strtotime($schedule['end_time']));

        //  dd($schedule);

        $schedule = Event::create($schedule);

        return redirect()->route('edit_event',  $schedule->id)->with('flash_success', 'Event Details Saved Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule =  Event::findorFail($id);
        $staff_schedule_details =  StaffSchedule::where('event_id', $id)->get();
        $client  = Client::all()->where('status', 'active');

        $columns = Schema::getColumnListing('staff');
        $excluded_columns = getExcludedColums();

        foreach ($columns  as $index => $arr) {
            if (in_array($arr, $excluded_columns)) {
                unset($columns[$index]);
            }
        }
        $columns_document = Schema::getColumnListing('staff');
        $excluded_document_columns = getExcludedColumsforDocuments();

        foreach ($columns_document  as $index => $cd) {
            if (in_array($cd, $excluded_document_columns)) {
                unset($columns_document[$index]);
            }
        }
        //  dd($columns);
        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', 'pending')->get();
        $staff_status['total_pending'] = $staffschedule->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', '=', 'confirmed')->get();
        $staff_status['total_confirmed'] = $staffschedule->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', 'dropout')->get();
        $staff_status['total_dropout'] = $staffschedule->count();

        return view('club_event.event.show', compact('id', 'schedule', 'client', 'staff_schedule_details', 'columns', 'staff_status', 'columns_document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $client  =  Client::all()->where('status', 'active')->where('client_type_id', '=', 2);
        $staff =    Staff::all();
        $staff_types =    StaffType::all();
        $schedule_data = Event::findorFail($id);
        $staff_schedule_details =  StaffSchedule::where('event_id', $id)->get();

        $staff_IDS = array();
        $result = array();
        $staff_schedule_IDS = array();

        $scheduled_staff_ids = StaffSchedule::where('event_id', $id)->pluck('staff_id');

        //dd($scheduled_staff_ids);
        // $remainingStaff = Staff::whereNotIn('id', $scheduled_staff_ids)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',block_for_clients)')->where('status','active')->orWhere('block_for_clients',NULL)->select('*')->get();

        $sdate = $schedule_data['start_date'];
        $edate = $schedule_data['end_date'];
        $current_client_id =  $schedule_data['client_id'];


        DB::enableQueryLog();

        $response = Staff::join('staff_schedules', 'staff_schedules.staff_id', '=', 'staff.id', 'LEFT')
            ->join('events', 'events.id', '=', 'staff_schedules.event_id', 'LEFT')
            ->whereNotIn('staff.id', $scheduled_staff_ids)
            ->whereRaw('NOT FIND_IN_SET(' . $current_client_id . ',staff.block_for_clients)')
            ->where('staff.status', 'active')
            //            ->where('schedules.start_date', '!=',$sdate)
            ->whereRaw('COALESCE("' . $sdate . '" NOT BETWEEN events.start_date AND events.end_date, TRUE)')
            ->whereRaw('COALESCE("' . $edate . '" NOT BETWEEN events.start_date AND events.end_date, TRUE)')
            ->groupBy('staff.id');

        if ($sdate == $edate) {
            if (empty($scheduled_staff_ids)) {
                $response->Where('events.start_date', NULL);
            }
        }

        $remainingStaff = $response->orWhere('block_for_clients', NULL)->select('staff.*', 'staff_schedules.staff_id', 'staff_schedules.sms_status', 'staff_schedules.status')->get();

        // dd($remainingStaff);

        $staffschedule = StaffSchedule::where('event_id', $id)->where('availability', '=', '1')->get();
        $confirmedStaff = $staffschedule->count();

        $total_sms_status = StaffSchedule::where('event_id', $id)->where('sms_status', '=', 'not_sent')->get();
        $sms_not_sent = $total_sms_status->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', 'pending')->get();
        $staff_status['total_pending'] = $staffschedule->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', '=', 'confirmed')->get();
        $staff_status['total_confirmed'] = $staffschedule->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', 'dropout')->get();
        $staff_status['total_dropout'] = $staffschedule->count();



        return view('club_event.event.edit', compact('id', 'schedule_data', 'client', 'staff', 'staff_schedule_details', 'remainingStaff', 'staff_types', 'current_client_id', 'confirmedStaff', 'sms_not_sent', 'staff_status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'client_id' => 'required',
            'start_date' => 'required',
            'event_name' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'total_staff' => 'required',
            'event_type' => 'required',
        ]);
        $schedule = $request->all();
        //dd($schedule);

        $schedule['start_date'] =   date("Y-m-d", strtotime($schedule['start_date']));
        $schedule['end_date'] =   date("Y-m-d", strtotime($schedule['end_date']));
        $schedule['start_time'] =   date("H:i:s", strtotime($schedule['start_time']));
        $schedule['end_time'] =   date("H:i:s", strtotime($schedule['end_time']));

        $DBSchedule =  Event::findorFail($id);
        $DBSchedule->client_id = $schedule['client_id'];
        $DBSchedule->event_name = $schedule['event_name'];
        $DBSchedule->start_date = $schedule['start_date'];
        $DBSchedule->end_date = $schedule['end_date'];
        $DBSchedule->start_time = $schedule['start_time'];
        $DBSchedule->end_time = $schedule['end_time'];
        $DBSchedule->total_staff = $schedule['total_staff'];
        $DBSchedule->contact_person = $schedule['contact_person'];
        $DBSchedule->contact_no = $schedule['contact_no'];
        $DBSchedule->location = $schedule['location'];
        $DBSchedule->event_type = $schedule['event_type'];
        $DBSchedule->status = $schedule['status'];

        $DBSchedule->save();
        return back()->with('flash_success', 'Schedule has been  updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
        //
        $user_id = Auth::user()->id;

        $data = $request->input('formData');
        parse_str($data, $parsingArray);
        $input_password = $parsingArray['input_password'];
        $data_id = $parsingArray['data_id'];

        $user = User::find($user_id);


        $hasher = app('hash');
        $status = 0;
        if ($input_password) {


            if ($hasher->check($input_password, $user->password)) {
                // Success

                //$Event = Event::find($data_id)->delete(); { OLD == WHEN LOGIC WAS TO DELETE WHOLE RECORD }

                $sch_arr['is_deleted'] = 1;
                $Event = Event::where(['id' => $data_id])->update($sch_arr);
                if ($Event) {
                    $Event = StaffSchedule::where(['event_id' => $data_id])->update($sch_arr);
                    // DB::table('staff_schedules')->where('event_id', $data_id)->delete(); { OLD == WHEN LOGIC WAS TO DELETE WHOLE RECORD }
                    $status = 1;
                    $message =  "Event  Successfully Deleted!!";
                } else {
                    $status = 1;
                    $message =  "Event  doesnot Delete. Try Again!!";
                }
            } else {
                $message =  "Password Doesn't Match!!";
            }
        } else {
            $status = 0;
            $message =  "Please, Enter you password !!";
        }
        echo json_encode(array('message' => $message, 'status' => $status));
        exit();
    }

    public function close_event(request $request)
    {
        //
        $user_id = Auth::user()->id;

        $data = $request->input('formData');
        parse_str($data, $parsingArray);
        $input_password = $parsingArray['input_password'];
        $event_id = $parsingArray['event_id'];

        $user = User::find($user_id);


        $hasher = app('hash');
        $status = 0;
        if ($input_password) {


            if ($hasher->check($input_password, $user->password)) {
                // Success

                $data_arr['status'] = 'closed';
                $Event = Event::where(['id' => $event_id])->update($data_arr);
                if ($Event) {
                    // code here
                    $staff_list = StaffSchedule::where('event_id', $event_id)->get();
                    foreach ($staff_list as $index => $arr) {

                        $mod_arr['is_payroll_active'] = 1;
                        $payroll_return = $this->savePayroll($event_id, $arr);

                        $staff_id = $arr->staff_id;

                        $resp = StaffSchedule::where(['event_id' => $event_id])->where(['staff_id' => $staff_id])->update($mod_arr);
                    }

                    // dd($staff_list);

                    $status = 1;
                    $message =  "Event  Successfully Closed!!";
                } else {
                    $status = 1;
                    $message =  "Event  Doestnot Close. Try Again!!";
                }
            } else {
                $message =  "Password Doesn't Match!!";
            }
        } else {
            $status = 0;
            $message =  "Please, Enter you password !!";
        }
        echo json_encode(array('message' => $message, 'status' => $status));
        exit();
    }

    public function export(request $request, $id)
    {
        $columns = $request->input('form_elements');
        $exporter = app()->makeWith(SaffScheduleExport::class, compact('columns', 'id'));

        return Excel::download($exporter, 'StaffSchedule.xlsx');
    }

    public function export_staff_timesheet(request $request, $id)
    {
        $exporter = app()->makeWith(TimeSheetExport::class, compact('id'));

        return Excel::download($exporter, 'StaffTimeSheet.xlsx');
    }

    public function savePayroll($event_id, $sch_arr)
    {

        $staff_id = $sch_arr['staff_id'];

        $is_exist = Payroll::where(['event_id' => $event_id])->where('staff_id', '=', $staff_id)->first();

        $pay_roll['staff_status'] = $sch_arr['status'];
        $pay_roll['payment_status'] = 'unpaid';
        $pay_roll['payment_date'] = date("Y-m-d");
        $pay_roll['total_amount'] = $sch_arr['hours'] * $sch_arr['rate_per_hour'];

        if ($is_exist) {
            $result = Payroll::where(['event_id' => $event_id])->where(['staff_id' => $staff_id])->update($pay_roll);
        } else {

            $pay_roll['staff_id'] = $sch_arr['staff_id'];
            $pay_roll['event_id'] = $event_id;
            //$pay_roll['schedule_type_id'] = 1;
            $result =  Payroll::create($pay_roll);
        }
        //dd($result);
        return $result;
    }
    public function schedule()
    {
        $clients = Client::select('property_name', 'id')->where('status', 'active')->where('client_type_id', '=', 2)->get();
        $clients_ids = Client::all()->pluck('id')->where('status', 'active');

        $events  = Event::join('clients', 'events.client_id', '=', 'clients.id')->leftJoin('staff_schedules', 'events.id', '=', 'staff_schedules.event_id')->leftJoin('staff', 'staff_schedules.staff_id', '=', 'staff.id')->leftJoin('staff_types', 'staff.staff_type_id', '=', 'staff_types.id')->select('events.*', 'clients.property_name', 'staff_schedules.staff_id', 'staff.name', 'staff.picture', 'staff_types.type')->where('events.is_deleted', '=', '0')->where('events.status', '!=', 'closed')->get();
        //dd($events);

        $staff = Staff::all()->where('status', 'active');
        $staff_types = StaffType::all();

        JavaScript::put([
            'scheduler_clients_ids' => $clients_ids,
            'scheduler_clients_ptj' => $clients,
            'events_ptj' => $events
        ]);
        return view('club_event.event.schedule', compact('staff', 'staff_types'));
    }
    public function export_staff_by_event_id(Request $request, $event_id)
    {
        $parsingArray = [];
        $data = $request->input();
        unset($data['_token']);
        ini_set('max_execution_time', '0');
        ini_set('set_time_limit', '0');

        $Event = Event::find($event_id);
        //  //Delete Folder
        Storage::disk('local')->deleteDirectory($Event->event_name);

        foreach ($Event->staffschedule as $key => $ss) {
            //create Folder
            foreach ($data as $key => $value) {
                $file_exist            =  explode('/', $ss->staff_data->$key);
                $file_extention        = explode('.', $ss->staff_data->$key);
                if (count($file_exist) > 0) {
                    if (Storage::disk('local')->exists($ss->staff_data->$key)) {
                        Storage::disk('local')->makeDirectory($Event->event_name);
                        Storage::disk('local')->makeDirectory($Event->event_name . '/' . $ss->staff_data->name);
                        $sourceFilePath = public_path() . "/storage/staff//" . $file_exist[1];
                        $destinationPath = public_path() . "/storage//" . $Event->event_name . '/' . $ss->staff_data->name . "//" . $key . "." . $file_extention[1];
                        $success = \File::copy($sourceFilePath, $destinationPath);
                    }
                }
            }
        }

        if (Storage::disk('local')->exists($Event->event_name)) {
            $file_name  = $Event->event_name . ".zip";
            $zip = new ZipArchive;
            $rootPath = public_path() . '/storage//' . $Event->event_name;
            //dd($rootPath);
            $zip->open($file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $files = new \RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            // $files = File::files(public_path()."\storage\\".$Event->event_name);
            //dd($files);
            foreach ($files as $key => $file) {
                if (!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) - strlen($Event->event_name) + 3); //str_replace(' ','',$Event->event_name);//substr($filePath, strlen($rootPath) );// basename($filePath);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
                // $relativeNameInZipFile = basename($value);
                // $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();


            //download file
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $file_name);
            header('Content-Length: ' . filesize($file_name));
            readfile(public_path($file_name));
            unlink(public_path($file_name));
            Storage::disk('local')->deleteDirectory($Event->event_name);
            return redirect()->back()->with('flash_success', 'Staff document Extracted');
        }
        return redirect()->back()->with('flash_error', 'Staff document not found');
    }
    public function check_document_for_export(Request $request, $event_id)
    {
        $data = $request->input('formData');
        $parsingArray = [];
        parse_str($data, $parsingArray);
        unset($parsingArray['_token']);
        $staff = [];
        $Event = Event::find($event_id);
        foreach ($Event->staffschedule as $key => $ss) {
            $missing = '';
            $available = '';
            foreach ($parsingArray as $key => $value) {

                if (Storage::disk('local')->exists($ss->staff_data->$key)) {
                    //Available
                    $available = getStaffDocumentLabels($key) . '<br>' . $available;
                } else {
                    //Missing
                    $missing = getStaffDocumentLabels($key) . '<br>' . $missing;
                }
            }
            $staff[$ss->staff_data->id] = array(
                'id' => $ss->staff_data->id,
                'missing' => $missing,
                'available' => $available,
                'staff_data' => '<p>' . staff_image($ss->staff_data->id, $ss->staff_data->name, $ss->staff_data->picture) . '</p>'
            );
        }
        //dd($staff);
        return json_encode($staff);
    }

    public function duplicate($id)
    {
        // dd($id);
        $client  =  Client::all()->where('status', 'active')->where('client_type_id', '=', 2);
        $staff =    Staff::all();
        $staff_types =    StaffType::all();
        $schedule_data = Event::findorFail($id);
        $staff_schedule_details =  StaffSchedule::where('event_id', $id)->get();

        $staff_IDS = array();
        $result = array();
        $staff_schedule_IDS = array();

        $scheduled_staff_ids = StaffSchedule::where('event_id', $id)->pluck('staff_id');

        //dd($scheduled_staff_ids);
        // $remainingStaff = Staff::whereNotIn('id', $scheduled_staff_ids)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',block_for_clients)')->where('status','active')->orWhere('block_for_clients',NULL)->select('*')->get();

        $sdate = $schedule_data['start_date'];
        $edate = $schedule_data['end_date'];
        $current_client_id =  $schedule_data['client_id'];


        DB::enableQueryLog();

        $response = Staff::join('staff_schedules', 'staff_schedules.staff_id', '=', 'staff.id', 'LEFT')
            ->join('events', 'events.id', '=', 'staff_schedules.event_id', 'LEFT')
            ->whereNotIn('staff.id', $scheduled_staff_ids)
            ->whereRaw('NOT FIND_IN_SET(' . $current_client_id . ',staff.block_for_clients)')
            ->where('staff.status', 'active')
            //            ->where('schedules.start_date', '!=',$sdate)
            ->whereRaw('COALESCE("' . $sdate . '" NOT BETWEEN events.start_date AND events.end_date, TRUE)')
            ->whereRaw('COALESCE("' . $edate . '" NOT BETWEEN events.start_date AND events.end_date, TRUE)')
            ->groupBy('staff.id');

        if ($sdate == $edate) {
            if (empty($scheduled_staff_ids)) {
                $response->Where('events.start_date', NULL);
            }
        }

        $remainingStaff = $response->orWhere('block_for_clients', NULL)->select('staff.*', 'staff_schedules.staff_id', 'staff_schedules.sms_status', 'staff_schedules.status')->get();

        // dd($remainingStaff);

        $staffschedule = StaffSchedule::where('event_id', $id)->where('availability', '=', '1')->get();
        $confirmedStaff = $staffschedule->count();

        $total_sms_status = StaffSchedule::where('event_id', $id)->where('sms_status', '=', 'not_sent')->get();
        $sms_not_sent = $total_sms_status->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', 'pending')->get();
        $staff_status['total_pending'] = $staffschedule->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', '=', 'confirmed')->get();
        $staff_status['total_confirmed'] = $staffschedule->count();

        $staffschedule = StaffSchedule::where('event_id', $id)->where('status', 'dropout')->get();
        $staff_status['total_dropout'] = $staffschedule->count();



        return view('club_event.event.duplicate', compact('id', 'schedule_data', 'client', 'staff', 'staff_schedule_details', 'remainingStaff', 'staff_types', 'current_client_id', 'confirmedStaff', 'sms_not_sent', 'staff_status'));
    }

    public function store_duplicate(Request $request)
    {
        //schdeule_to schdeule_from client_id   start_date  end_date    start_time  end_time    location total_staff    contact_person  event_type  status
        $this->validate($request, [
            'client_id' => 'required',
            'start_date' => 'required',
            'event_name' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'total_staff' => 'required',
            'event_type' => 'required',
        ]);
        $schedule = $request->all();
        $schedule['start_date'] =   date("Y-m-d", strtotime($schedule['start_date']));
        $schedule['end_date'] =   date("Y-m-d", strtotime($schedule['end_date']));
        $schedule['start_time'] =   date("H:i:s", strtotime($schedule['start_time']));
        $schedule['end_time'] =   date("H:i:s", strtotime($schedule['end_time']));

        $duplicate_id = $request->duplicate_id;

        //dd($duplicate_id);

        $schedule = Event::create($schedule);

        $staffschedule = StaffSchedule::where('event_id', '=', $duplicate_id)->get();
        //dd($staffschedule);

        foreach ($staffschedule as $key) {
            $duplicate_staffschedule = $key->replicate();
            $duplicate_staffschedule->event_id              = $schedule->id;
            $duplicate_staffschedule->availability          = 0;
            $duplicate_staffschedule->is_payroll_active     = 0;
            $duplicate_staffschedule->status                = "pending";
            $duplicate_staffschedule->sms_status            = "not_sent";
            $duplicate_staffschedule->wa_response           = "";
            $duplicate_staffschedule->save();
        }

        return redirect()->route('edit_event',  $schedule->id)->with('flash_success', 'Event Details Saved Successfully');
    }
    public function clickatelTest(Request $request)
    {
        //echo "<pre>";
        //dd($request->ph_number);
        // return;
        //Information send to staff will remain same and only number change so we could update staff here as well

        $data                 = $request->parameters;
        $event_id             = $request->event_id;
        $information         = $request->ph_number;

        $location             = $request->location;
        $location_guide     = $request->location_guide;
        $start_date         = $request->start_date;
        $end_date             = $request->end_date;
        $ch = curl_init();
        foreach ($information as $key => $info) {

            //echo $key+1 ."  ";//.$info['number'];
            $staff_id             = $info['staff_id'];
            //$ph_number = 923324288876;
            $ph_number          = preg_replace('/^0+/', '', $info['number']); //$info['number'];
            $url = 'https://platform.clickatell.com/v1/message';
            $headers = array(
                "POST /v1/messages",
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: 5dfJ3cO_Riiw62Gv4RmB_g=="
            );
            $curl_data = '{
						"messages": [
										{
										"channel": "whatsapp",
										"to": "' . $ph_number . '",
										"hsm" : {
													"template":"venue_event_confirmation",
													"parameters" : {
																	"1": "' . $data[1] . '",
																	"2": "' . $data[2] . '",
																	"3": "' . $data[3] . '",
																	"4": "' . $data[4] . '",
																	"5": "' . $data[5] . '",
																	"6": "' . $data[6] . '",
																	"7": "' . $data[7] . '",
																	"8": "' . $data[8] . '"
																	}
												}
										}
									]
					}';

            // $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $response_info = curl_getinfo($ch);
            if (($response_info["http_code"] >= 200) and ($response_info["http_code"] <= 299)) {
                // print_r($response);
                //echo $key+1 ."  ";
                $responses = json_decode($response, true);
                //echo "  OK -->".$responses['messages'][0]['apiMessageId']."<br>";
                $submit_data['event_data']        = $data;
                $submit_data['curl_data']        = $responses;
                $submit_data['event_id']        = $event_id;
                $submit_data['ph_number']        = $ph_number;
                $submit_data['message_id']        = $responses['messages'][0]['apiMessageId'];
                $submit_data['staff_id']        = $staff_id;
                $submit_data['location']        = $location;
                $submit_data['location_guide']    = $location_guide;
                $submit_data['start_date']        = $start_date;
                $submit_data['end_date']        = $end_date;
                $done                             = array('color' => 'green', 'message' => $this->update_send_Message_data($submit_data));
            } else {
                $responses = json_decode($response, true);
                $done = array('color' => 'red', 'message' => $responses['messages'][0]['error']['description']);
                //print_r($responses);
            }
        }
        curl_close($ch);
        echo json_encode($done);
        exit();
    }
    public function update_send_Message_data($data)
    {
        //defined variables
        DB::enableQueryLog();
        //$date 									= Carbon::now();
        $event_id                                 = $data['event_id'];
        $phone_number                            = $data['ph_number'];
        $MessageId                                = $data['message_id'];
        $staff_id                                = $data['staff_id'];

        //for Event_Confirmation
        $eventArray['staff_id']                    = $data['staff_id'];
        $eventArray['event_id']                    = $event_id;
        $eventArray['contact_number']            = $phone_number;
        $eventArray['location']                    = $data['location'];
        $eventArray['arrival_time']                = $data['event_data'][5];
        $eventArray['briefing']                    = $data['event_data'][6];
        $eventArray['venue']                    = $data['event_data'][2];
        $eventArray['location_guide']            = $data['location_guide'];
        $eventArray['dress_code']                = $data['event_data'][8];
        $eventArray['start_date']                = $data['start_date'];
        $eventArray['start_time']                = $data['end_date'];
        //$eventArray['date']						= now()->toDateTimeString('Y-m-d');
        $eventArray['signingMeetingPt']            = $data['event_data'][3];
        $eventArray['status']                    = 'sent';
        //print_r($eventArray);return;

        //for whatsapp
        $wsappArray['message_id']                 = $MessageId;
        $wsappArray['contact_number']             = $phone_number;
        $wsappArray['message_type']             = 'business';
        $wsappArray['sent']                     = 'yes';
        $wsappArray['receive']                     = 'no';

        if ($staff_id) {
            $wsappArray['staff_id']                = $staff_id;
            $parseArray['staff_id']                = $staff_id;
            $parseArray['start_time']            = date("H:i:s", strtotime($data['event_data'][7]));
        }
        //End bulk sms or single sms

        $wsappArray['event_id']         = $event_id;
        $parseArray['message_id']         = $MessageId;
        $parseArray['updated_by']         = Auth::user()->id;

        $resp = null;
        if ($event_id) {
            $is_exist = StaffSchedule::where('event_id', '=', $event_id)->where('staff_id', '=', $staff_id)->first();


            $parseArray['availability'] = (isset($parseArray['availability']) && ($parseArray['availability'] == 'on')) ? 1 : 0;

            if ($is_exist) {
                unset($parseArray['staff_id']);

                $parseArray['status']                     = "pending";
                $parseArray['sms_status']                 = "pending";


                // save & update data
                $resp                                     = StaffSchedule::where(['event_id' => $event_id])->where(['staff_id' => $staff_id])->update($parseArray);
                //print_r(DB::getQueryLog);
                Whatsapp::create($wsappArray);
                EventConfirmation::create($eventArray);
                $upwsappArray['message_id']             = $MessageId;
                $upwsappArray['contact_number']         = $phone_number;
                $status                                 = "200";
                //tem will update content based upon messageID


            } else {
                $parseArray['event_id'] = $event_id;

                $parseArray['status']         = "pending";
                $parseArray['sms_status']     = "pending";

                $resp                         = StaffSchedule::create($parseArray);
                //  //save data
                Whatsapp::create($wsappArray);
                EventConfirmation::create($eventArray);
                $status = "200";
            }
        } // if event_id found in request
        else {
            $status = "404"; // event Id not found
        }
        return "sent successfully";
    }
    public function  cron()
    {
        $whatsapp = EventConfirmation::all();
        //dd($whatsapp);
        $key = 0;
        foreach ($whatsapp as  $ww) {
            $asd = StaffSchedule::where('event_id', $ww->event_id)->where('staff_id', $ww->staff_id)->first();

            if ($asd) {
                if ($asd->wa_response == "RECIPIENT_DOES_NOT_EXIST") {
                    $asd['sms_status'] = 'incorrect_number';
                } else if (strtolower($asd->wa_response) == 'ok' || strtolower($asd->wa_response) == 'confirm' || strtolower($asd->wa_response) == 'yes') {
                    $asd['sms_status'] = 'confirmed';
                    $asd['status'] = 'confirmed';
                } else if (strtolower($asd->wa_response) == 'no' || strtolower($asd->wa_response) == 'sorry' || strtolower($asd->wa_response) == 'not available') {
                    $asd['sms_status'] = 'declined';
                    $asd['status'] = 'dropout';
                } else {
                    $asd['sms_status'] = 'pending';
                    $asd['status'] = 'pending';
                }
                $asd['updated_by'] = 'cron';
                $asd->save();
            } else {
                //echo "No ". $ww->id ."<br>";
                $key = $key + 1;
            }
        }
    }
    public function  update_message($id)
    {
        $whatsapp = EventConfirmation::where('event_id', $id)->get();
        // dd($whatsapp);
        $key = 0;
        foreach ($whatsapp as  $ww) {
            $asd = StaffSchedule::where('event_id', $ww->event_id)->where('staff_id', $ww->staff_id)->first();

            if ($asd) {
                if ($asd->wa_response == "RECIPIENT_DOES_NOT_EXIST") {
                    $asd['sms_status'] = 'incorrect_number';
                } else if (strtolower($asd->wa_response) == 'ok' || strtolower($asd->wa_response) == 'confirm' || strtolower($asd->wa_response) == 'yes') {
                    $asd['sms_status'] = 'confirmed';
                    $asd['status'] = 'confirmed';
                } else if (strtolower($asd->wa_response) == 'no' || strtolower($asd->wa_response) == 'sorry' || strtolower($asd->wa_response) == 'not available') {
                    $asd['sms_status'] = 'declined';
                    $asd['status'] = 'dropout';
                } else {
                    $asd['sms_status'] = 'pending';
                    $asd['status'] = 'pending';
                }
                $asd['updated_by'] = 'cron';
                $asd->save();
            } else {
                //echo "No ". $ww->id ."<br>";
                $key = $key + 1;
            }
        }
        return redirect()->back();
    }
    public function schedule_print($id){

        $data_event  = Event::join('clients', 'events.client_id', '=', 'clients.id')->leftJoin('staff_schedules', 'events.id', '=', 'staff_schedules.event_id')->leftJoin('staff', 'staff_schedules.staff_id', '=', 'staff.id')->leftJoin('staff_types', 'staff.staff_type_id', '=', 'staff_types.id')->select('events.*', 'clients.property_name', 'staff_schedules.staff_id', 'staff.name', 'staff.picture', 'staff_types.type')->where('events.is_deleted', '=', '0')->where('events.status', '!=', 'closed')->where('staff_schedules.staff_id',136);
        $events= $data_event->get();
        $selected_id = $data_event->groupby('client_id')->pluck('client_id');
        //  dd($selected_id);
        $clients = Client::select('property_name', 'id')->where('status', 'active')->where('client_type_id', '=', 2)->whereIn('id',$selected_id)->get();
        // dd($events);
        // return view('club_event.event.print', compact('clients', 'events'));
        // dd($data);
        $pdf = PDF::loadView('club_event.event.print',compact('clients', 'events'));
        return $pdf->download('schedule_print.pdf');
    }
}