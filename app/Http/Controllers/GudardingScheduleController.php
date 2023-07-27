<?php

namespace App\Http\Controllers;

use App\GudardingSchedule;
use Illuminate\Http\Request;

class GudardingScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GudardingSchedule  $gudardingSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(GudardingSchedule $gudardingSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GudardingSchedule  $gudardingSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(GudardingSchedule $gudardingSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GudardingSchedule  $gudardingSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $guarding_schedule = $request->input('formData');
        parse_str($guarding_schedule, $data);
        // dd($data);
        $staff_array = $data['staff_id'];
        $staff_count = count($staff_array);
        for ($i = 0; $i < $staff_count; $i++) {
            $guarding_schedule = GudardingSchedule::where('staff_id',  $staff_array[$i])->where('guarding_id', $id)->first();
            //dd(isset($data['night_' . $staff_array[$i]]) ? json_encode($data['night_' . $staff_array[$i]]) : "");
            $guarding_schedule['day']               = isset($data['day_' . $staff_array[$i]]) ? json_encode($data['day_' . $staff_array[$i]]) : "";
            $guarding_schedule['night']             = isset($data['night_' . $staff_array[$i]]) ? json_encode($data['night_' . $staff_array[$i]]) : "";
            $guarding_schedule['afternoon']         = isset($data['afternoon_' . $staff_array[$i]]) ? json_encode($data['afternoon_' . $staff_array[$i]]) : "";
            $guarding_schedule['late_day']          = isset($data['late_day_' . $staff_array[$i]]) ? json_encode($data['late_day_' . $staff_array[$i]]) : "";
            $guarding_schedule['evening']           = isset($data['evening_' . $staff_array[$i]]) ? json_encode($data['evening_' . $staff_array[$i]]) : "";
            $guarding_schedule['absent']            = isset($data['absent_' . $staff_array[$i]]) ? json_encode($data['absent_' . $staff_array[$i]]) : "";
            $guarding_schedule['sick_leave']        = isset($data['sick_leave_' . $staff_array[$i]]) ? json_encode($data['sick_leave_' . $staff_array[$i]]) : "";
            $guarding_schedule['annual_leave']      = isset($data['annula_leave_' . $staff_array[$i]]) ? json_encode($data['annula_leave_' . $staff_array[$i]]) : "";
            $guarding_schedule['emergency_leave']   = isset($data['emergency_leave_' . $staff_array[$i]]) ? json_encode($data['emergency_leave_' . $staff_array[$i]]) : "";
            $guarding_schedule['unpaid_leave']   	= isset($data['unpaid_leave_' . $staff_array[$i]]) ? json_encode($data['unpaid_leave_' . $staff_array[$i]]) : "";
            $guarding_schedule['day_off']           = isset($data['day_off_' . $staff_array[$i]]) ? json_encode($data['day_off_' . $staff_array[$i]]) : "";
            $guarding_schedule['off_working_night'] = isset($data['off_working_night_' . $staff_array[$i]]) ? json_encode($data['off_working_night_' . $staff_array[$i]]) : "";
            $guarding_schedule['off_working_day']   = isset($data['off_working_day_' . $staff_array[$i]]) ? json_encode($data['off_working_day_' . $staff_array[$i]]) : "";
            $guarding_schedule['training']          = isset($data['trainig_' . $staff_array[$i]]) ? json_encode($data['trainig_' . $staff_array[$i]]) : "";
            $guarding_schedule['overtime']          = isset($data['overtime_' . $staff_array[$i]]) ? json_encode($data['overtime_' . $staff_array[$i]]) : "";
            $guarding_schedule['event_day']         = isset($data['event_day_' . $staff_array[$i]]) ? json_encode($data['event_day_' . $staff_array[$i]]) : "";
            $guarding_schedule['public_holiday']    = isset($data['public_holiday_' . $staff_array[$i]]) ? json_encode($data['public_holiday_' . $staff_array[$i]]) : "";
            //dd($guarding_schedule);
            $status = $guarding_schedule->update();
        }
        echo json_encode($status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GudardingSchedule  $gudardingSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(GudardingSchedule $gudardingSchedule)
    {
        //
    }
}
