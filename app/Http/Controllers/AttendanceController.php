<?php

namespace App\Http\Controllers;


use App\Payroll;
use App\StaffType;
use App\ClientType;
use App\Staff;
use App\StaffSchedule;
use App\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $client_types = ClientType::all();
        $staff_type = StaffType::all();
        $staffs = Staff::all();
        return view('attendance.index', compact('staff_type', 'staffs', 'client_types'));
    }

    public function store(Request $request)
    {
        $formData = $request->input('formData');
        parse_str($formData, $attendanceList);

        //  dd($attendanceList['array_staff_sch']);
        $status = 0;
        if (sizeof($attendanceList['array_staff_sch']) > 0) {
            foreach ($attendanceList['array_staff_sch'] as $index => $arr) {
                $col['availability'] =  (isset($arr['availability']) && ($arr['availability'] == 'on')) ?  1 : 0;
                $col['updated_by'] = Auth::user()->id;
                $response = StaffSchedule::where('id', '=', $arr['id'])->update($col);
            }

            $status = 1;
        }

        echo json_encode(array('status' => $status));
        exit();
    }

    public function filters(request $request)
    {
        //
        //dd('error');
        $formData = $request->input('formData');
        parse_str($formData, $parsingArray);

        $day = (isset($parsingArray['day'])) ? $parsingArray['day'] : null;
        $client_type_id = $parsingArray['client_type_id'];
        $event_venue_id = $parsingArray['event_venue_id'];



        $view = "";
        $status = 0;

        if ($client_type_id || $event_venue_id) {
            DB::enableQueryLog();
            $response = StaffSchedule::join('staff', 'staff.id', '=', 'staff_schedules.staff_id', 'LEFT')
                ->join('staff_types', 'staff_types.id', '=', 'staff.staff_type_id', 'LEFT')
                ->join('events', 'events.id', '=', 'staff_schedules.event_id', 'LEFT')
                ->join('venues', 'venues.id', '=', 'staff_schedules.venue_id', 'LEFT')
                ->join('clients', 'clients.id', '=', 'venues.client_id', 'LEFT');

            if (($client_type_id == 1) && (!empty($event_venue_id))) {
                $response->where('venues.client_id', '=', $event_venue_id);
            }
            if (($client_type_id == 2) && (!empty($event_venue_id))) {
                $response->where('staff_schedules.event_id', '=', $event_venue_id);
            } else if (($client_type_id == 1) && (empty($event_venue_id))) {
                $response->where('clients.client_type_id', '=', $event_venue_id);
            }

            if ($day) {
                $response->where('staff_schedules.day', '=', date("Y-m-d", strtotime($day)));
            }


            $Data =  $response->select(
                'staff_schedules.id',
                'staff.name',
                'staff.picture',
                'staff_schedules.staff_id',
                'staff_schedules.availability',
                'staff_schedules.status',
                'staff_schedules.sms_status',
                'events.event_name',
                'events.start_date  as  ev_st_date',
                'events.end_date as   ev_end_date',
                'venues.id as   venue_id',
                'venues.start_date as   ven_st_date',
                'venues.end_date   as ven_end_date',
                'clients.property_name',
                'clients.client_type_id'
            )->get();

            $view = view("attendance.ajaxStaffAttendanceResult", compact('Data'))->render();

            if (sizeof($Data) > 0)
                $status = 1;

            //dd(DB::getQuerylog());
        }


        echo json_encode(array('data' => $view, 'status' => $status));
        exit();
    }
}
