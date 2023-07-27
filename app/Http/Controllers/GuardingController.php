<?php

namespace App\Http\Controllers;

use App\Guarding;
use App\Client;
use App\Staff;
use App\StaffType;
use App\User;
use App\SiraType;
use App\StaffSchedule;
use App\GudardingSchedule;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use DB;
use JavaScript;
use Carbon\Carbon;
use DebugBar\DebugBar;
use Session;

class GuardingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedule = Guarding::all();
        //dd($schedule);
        return view('guarding.schedule.index', compact('schedule'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Session::get('dashboard') == 'guarding') {
            $clients = Client::select('property_name', 'id')->where('client_type_id', 3)->where('status', 'active')->get();
        } else {
            $clients = Client::select('property_name', 'id')->where('client_type_id', '!=', 3)->where('status', 'active')->get();
        }

        $siraTypes = SiraType::all();
        return view('guarding.schedule.create', compact('clients', 'siraTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'day_outer_to' => 'required',
            'night_outer_to' => 'required',
            'require_staff_day' => 'required',
            'require_staff_night' => 'required',
            'selected_staff' => 'required',
        ]);
        $data = $request->all();
        $staff_array = $request->selected_staff;
        $guarding_data = [
            'client_id'             => $data['client_id'],
            'start_date'            => date("Y-m-d", strtotime($data['start_date'])),
            'end_date'              => date("Y-m-d", strtotime($data['end_date'])),
            'day_start_time'        => $data['day_outer_from'],
            'day_end_time'          => $data['day_outer_to'],
            'night_start_time'      => $data['night_outer_from'],
            'night_end_time'        => $data['night_outer_to'],
            'require_staff_day'     => $data['require_staff_day'],
            'require_staff_night'   => $data['require_staff_night']
        ];
        $guarding_id = Guarding::create($guarding_data);
        //dd($guarding_id);

        $staff_count = count($staff_array);
        // dd($staff_count);
        if ($staff_count) {
            for ($i = 0; $i < $staff_count; $i++) {
                if ($staff_array[$i]) {
                    //$staff_schedule['client_id']        = $data['client_id'];
                    $staff_schedule['shift_type']       = $request->shift_type[$i];
                    $staff_schedule['assignment_type']  = $request->assignment_type[$i];
                    $staff_schedule['start_date']       = date("Y-m-d", strtotime($data['start_date']));
                    $staff_schedule['end_date']         = date("Y-m-d", strtotime($data['end_date']));
                    $staff_schedule['start_time']       = date("H:i:s", strtotime($request->start_time[$i]));
                    $staff_schedule['end_time']         = date("H:i:s", strtotime($request->end_time[$i]));
                    $staff_schedule['staff_id']         = $staff_array[$i];
                    $staff_schedule['schedule_id']      = $guarding_id->id;
                    $staff                              = StaffSchedule::create($staff_schedule);
                    //guarding schedule save
                    $guarding_sch_date['staff_id']      = $staff_array[$i];
                    $guarding_sch_date['guarding_id']   = $guarding_id->id;
                    $guarding_sch_date['schedule_id']   = $staff->id;
                    $guarding_schedule                  = GudardingSchedule::create($guarding_sch_date);
                }
            }
        }
        return redirect(route('guarding_view'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Guarding  $guarding
     * @return \Illuminate\Http\Response
     */

    //HAROON converting whole guarding screen into PHP
    public function show($id)
    {
        $guarding_id = $id;
        $data = Guarding::with([
            'client' => function ($query) {
                $query->select('id', 'property_name');
            }, 'guarding_schedule' => function ($query) {
                $query->select(
                    'id',
                    'schedule_id',
                    'guarding_id',
                    'day',
                    'night',
                    'afternoon',
                    'late_day',
                    'evening',
                    'absent',
                    'sick_leave',
                    'annual_leave',
                    'emergency_leave',
                    'unpaid_leave',
                    'day_off',
                    'off_working_night',
                    'off_working_day',
                    'training',
                    'overtime',
                    'event_day',
                    'public_holiday'
                );
            }, 'guarding_schedule.staffschedule' => function ($query) {
                $query->select('id', 'id', 'staff_id', 'start_date', 'end_date', 'start_time', 'end_time', 'shift_type', 'assignment_type');
            },
            'guarding_schedule.staffschedule.sira_type' => function ($query) {
                $query->select('id', 'type');
            },
            'guarding_schedule.staffschedule.staff' => function ($query) {
                $query->select('id', 'name', 'gender');
            }
        ])->where('id', $guarding_id)->first();
        // return response()->json($data);
        return view('guarding.schedule.show_', compact('data'));
    }


    //OLD
    public function show_old($id)
    {

        $guarding_id = $id;
        //dd($guarding_id);
        $clients = Client::select('property_name', 'id')->where('status', 'active')->where('client_type_id', '=', 1)->where('client_type_id', '=', 1)->get();
        $clients_ids = Client::all()->pluck('id')->where('status', 'active')->where('client_type_id', '=', 1);

        /* get seperate  shifts based on staff */
        $venues = StaffSchedule::leftJoin('staff', 'staff.id', '=', 'staff_schedules.staff_id')
            ->join('venues', 'venues.id', '=', 'staff_schedules.venue_id', 'INNER')
            ->join('clients', 'clients.id', '=', 'venues.client_id', 'INNER')
            ->select('staff_schedules.*', 'staff.name', 'staff.picture', 'clients.id as client_id')
            ->get();

        //$venues = Venue::all();
        //dd($venues);

        $staff = Staff::all()->where('status', 'active')->where('staff_type_id', '3');
        $staff_types = StaffType::all();

        $active_dashboard = session()->get('dashboard');
        $staff = Staff::join('staff_types', 'staff_types.id', '=', 'staff.staff_type_id')->where('staff_types.dashboard', $active_dashboard)->select('staff.*', 'staff_types.type')->get();

        $staff = StaffSchedule::join('staff', 'staff.id', '=', 'staff_schedules.staff_id')->join('sira_types', 'sira_types.id', '=', 'staff.sira_type_id', 'LEFT')->where('staff_schedules.schedule_id', $guarding_id)->select('staff.*', 'sira_types.type as siraType')->get();
        //dd($data);
        JavaScript::put([
            'scheduler_clients_ids' => $clients_ids,
            'scheduler_staff_ptj' => $staff,
            'events_ptj' => $venues
        ]);
        //return view('club_event.venue.dummy');


        //$schedule  = Schedule::all()->where('is_deleted','0');
        //  dd($schedule);
        //return view('club_event.schedule.index',compact('schedule'));
        return view('guarding.schedule.show', compact('staff', 'staff_types', 'clients'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guarding  $guarding
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $clients = Client::select('property_name', 'id')->where('client_type_id', 3)->where('status', 'active')->get();
        $siraTypes = SiraType::all();
        $guarding = Guarding::findorFail($id);

        return view('guarding.schedule.edit', compact('guarding', 'clients', 'siraTypes'));
    }
    public function duplicate($id)
    {
        $clients = Client::select('property_name', 'id')->where('client_type_id', 3)->where('status', 'active')->get();
        $siraTypes = SiraType::all();
        $guarding = Guarding::findorFail($id);

        return view('guarding.schedule.duplicate', compact('guarding', 'clients', 'siraTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Guarding  $guarding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guarding $guarding)
    {
        //
        //dd($guarding->id);
        $data = $request->all();
        $staff_array = $request->selected_staff;
        //dd($data);
        $guarding_data = [
            'client_id'             => $data['client_id'],
            'start_date'            => date("Y-m-d", strtotime($data['start_date'])),
            'end_date'              => date("Y-m-d", strtotime($data['end_date'])),
            'day_start_time'        => $data['day_outer_from'],
            'day_end_time'          => $data['day_outer_to'],
            'night_start_time'      => $data['night_outer_from'],
            'night_end_time'        => $data['night_outer_to'],
            'require_staff_day'     => $data['require_staff_day'],
            'require_staff_night'   => $data['require_staff_night']
        ];
        $guarding_id = Guarding::find($guarding->id);
        //dd($guarding_id);
        $guarding_id->update($guarding_data);
        //$find = Guarding::find($)->first();
        // dd($data);
        if ($staff_array) {
            $staff_count = count($staff_array);
            for ($i = 0; $i < $staff_count; $i++) {
                $staff_schedule = StaffSchedule::where('id', $request->staff_schedule_id[$i])->first();
                //dd($ss_guard);
                if ($staff_schedule) {
                    //dd($staff_schedule);
                    $staff_schedule['shift_type']       = $request->shift_type[$i];
                    $staff_schedule['assignment_type']  = $request->assignment_type[$i];
                    $staff_schedule['start_date']       = date("Y-m-d", strtotime($data['start_date']));
                    $staff_schedule['end_date']         = date("Y-m-d", strtotime($data['end_date']));
                    $staff_schedule['start_time']       = date("H:i:s", strtotime($request->start_time[$i]));
                    $staff_schedule['end_time']         = date("H:i:s", strtotime($request->end_time[$i]));
                    $staff_schedule['staff_id']         = $staff_array[$i];
                    $staff_schedule['schedule_id']      = $guarding->id;
                    $staff_schedule->save();

                    $guarding_schedule = GudardingSchedule::where('schedule_id', $request->staff_schedule_id[$i])->where('guarding_id', $guarding_id->id)->first();
                    $guarding_schedule['staff_id']      = $staff_array[$i];
                    $guarding_schedule->save();
                } else {
                    $staff_schedule['shift_type']       = $request->shift_type[$i];
                    $staff_schedule['assignment_type']  = $request->assignment_type[$i];
                    $staff_schedule['start_date']       = date("Y-m-d", strtotime($data['start_date']));
                    $staff_schedule['end_date']         = date("Y-m-d", strtotime($data['end_date']));
                    $staff_schedule['start_time']       = date("H:i:s", strtotime($request->start_time[$i]));
                    $staff_schedule['end_time']         = date("H:i:s", strtotime($request->end_time[$i]));
                    $staff_schedule['staff_id']         = $staff_array[$i];
                    $staff_schedule['schedule_id']      = $guarding->id;
                    $staff                              = StaffSchedule::create($staff_schedule);

                    $guarding_sch_date['staff_id']      = $staff_array[$i];
                    $guarding_sch_date['guarding_id']   = $guarding_id->id;
                    $guarding_sch_date['schedule_id']   = $staff->id;
                    $guarding_schedule                  = GudardingSchedule::create($guarding_sch_date);
                }
            }
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guarding  $guarding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarding $guarding)
    {
        //
    }

    function getAvailableStaffList(request $request)
    {
        $data = $request->all();
        // dd($data);
        $selected_staffs = '';
        $current_client_id = $data['client_id'];
        $start_date = date("Y-m-d", strtotime($data['venue_start_date']));
        $end_date = date("Y-m-d", strtotime($data['venue_end_date']));
        if (array_key_exists('stafflist', $data)) {
            if ($data['stafflist'] != null || $data['stafflist'] != '') {
                $selected_staffs = explode(',', $data['stafflist']);
                //dd($selected_staffs);
            }
        }
        $schedule_staff_today = StaffSchedule::join('staff', 'staff.id', '=', 'staff_schedules.staff_id')->whereBetween('start_date', [$start_date, $end_date])->whereBetween('end_date', [$start_date, $end_date])->where('staff.staff_type_id', '=', '3')->where('staff_schedules.assignment_type', '!=', '7')->groupBy('staff_id')->pluck('staff_id');
        //dd($schedule_staff_today);
        $staff_not_schedule_today = Staff::whereNotIn('id', $schedule_staff_today)->whereRaw('NOT FIND_IN_SET(' . $current_client_id . ',staff.block_for_clients)')->where('staff.status', 'active')->where('staff.staff_type_id', '=', '3')->orderBy('name', 'DESC')->get();

        $html = '';
        $temp = '';
        if ($staff_not_schedule_today) {
            foreach ($staff_not_schedule_today as $obj) {
                if (!empty($selected_staffs)) {
                    if (in_array($obj["id"], $selected_staffs)) {
                        $temp = 'class="selected"';
                    } else {
                        $temp = 'class=""';
                    }
                }
                $image =  img($obj['picture']);
                $switch = ($obj['staff_type_id'] == 1) ? "on" : "off";

                if (!empty($data['runtime_selected_staff'])) {
                    $result = $this->is_in_array($data['runtime_selected_staff'], 'staff', $obj["id"]);
                    if (is_array($result)) {
                        if ($data['start_time'] <= $result['start_time'] && $data['end_time'] >= $result['end_time']) {
                            //nothing to do
                        } else {
                            $html = '<li id="li-' . $obj["id"] . '" data-id="' . $obj["id"] . '" data-image="' . $image . '" data-view="' . $switch . '" data-stafftype="' . $obj['staff_type_id'] . '" data-exist="false" data-dropout="false" onclick="abbb(' . $obj["id"] . ');" ' . $temp . '><img src="' . $image . '" class="custom staff_list_view img-circle" data-staffid="' . $obj["id"] . '" data-staffname="' . $obj["name"] . '"/> <span class="list_staff_name ">' . $obj["name"] . '</span></li>' . $html;
                        }
                    } else {
                        $html = '<li id="li-' . $obj["id"] . '" data-id="' . $obj["id"] . '" data-image="' . $image . '" data-view="' . $switch . '" data-stafftype="' . $obj['staff_type_id'] . '" data-exist="false" data-dropout="false" onclick="abbb(' . $obj["id"] . ');" ' . $temp . '><img src="' . $image . '" class="custom staff_list_view img-circle" data-staffid="' . $obj["id"] . '" data-staffname="' . $obj["name"] . '"/> <span class="list_staff_name ">' . $obj["name"] . '</span></li>' . $html;
                    }
                } else {
                    $html = '<li id="li-' . $obj["id"] . '" data-id="' . $obj["id"] . '" data-image="' . $image . '" data-view="' . $switch . '" data-stafftype="' . $obj['staff_type_id'] . '" data-exist="false" data-dropout="false" onclick="abbb(' . $obj["id"] . ');" ' . $temp . '><img src="' . $image . '" class="custom staff_list_view img-circle" data-staffid="' . $obj["id"] . '" data-staffname="' . $obj["name"] . '"/> <span class="list_staff_name ">' . $obj["name"] . '</span></li>' . $html;
                }
            }
            $html = '<input type="hidden" id="tr_shift_id" value=' . $data['tr'] . '>' . $html;
        }
        echo json_encode(array('staffList' => $html));
        exit();
    }

    function is_in_array($array, $key, $key_value)
    {
        $within_array = 'no';
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $within_array = $this->is_in_array($v, $key, $key_value);
                if ($within_array == 'yes') {
                    $within_array = $v;
                    break;
                }
            } else {
                if ($v == $key_value && $k == $key) {
                    $within_array = 'yes';
                    break;
                }
            }
        }
        return $within_array;
    }
    function removeStaff_fromSchedule($id)
    {
        $staffschedule = StaffSchedule::find($id);
        $staffschedule->delete();
        $guardin_staff_id = GudardingSchedule::where('schedule_id', $id)->first();
        $guardin_staff_id->delete();
        echo json_encode(array('sucess' => 1));
    }
}
