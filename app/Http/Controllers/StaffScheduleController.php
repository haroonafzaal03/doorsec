<?php

namespace App\Http\Controllers;

use App\StaffSchedule;
use App\Staff;
use App\Event;
use App\Payroll;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Whatsapp;
use App\EventConfirmation;

class StaffScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $staff = Staff::all();

        $schedule_data = Event::findorFail($id);

        return view('club_event.event.staff_schedule', compact('staff', 'schedule_data'));
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
        $schedule_staff_data = $request->all();
        //$schedule_staff_data['updated_by'] = Auth::user()->id;
        //$schedule_staff_data['array_staff']['schedule_type_id'] = 1;

        StaffSchedule::insert($schedule_staff_data['array_staff']);
        return redirect()->route('event')->with('flash_success', 'Staff has been scheduled successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StaffSchedule  $staffSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(StaffSchedule $staffSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StaffSchedule  $staffSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffSchedule $staffSchedule)
    {
        //
    }


    public function checkStaffAvailable($staff_id,$start_time,$end_time,$event)
    {
        return StaffSchedule::where('event_id','!=',$event->id)->where('staff_id',$staff_id)->whereDate('start_date','>=', $event->end_date)
        ->whereDate('end_date','<=', $event->end_date)
        ->where(function($query)use($start_time,$end_time){
            $query->orWhereBetween('start_time',[$start_time,$end_time]);
            $query->orWhereBetween('end_time', [$start_time, $end_time]);
        })
        ->doesntExist();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StaffSchedule  $staffSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $event_id)
    {
        $unavailable=[];
        //
        // dd($request);
        $staff_schedule = $request->all();
        $send_payroll = null;
        if ($request->input('send_payroll')) {
            $send_payroll = $request->input('send_payroll');
        }

        // dd($staff_schedule);

        // dd($send_payroll);
        if ($event_id) {
            $event=Event::find($event_id);
            $staff_schedule_arr = $staff_schedule['array_staff'];

            //dd($staff_schedule_arr);

            foreach ($staff_schedule_arr as $index => $arr) {

                // dd($this->checkStaffAvailable($arr['staff_id'], $arr['start_time'], $arr['end_time'], $event->start_date, $event->end_date));
                // dd($arr);
                $arr['updated_by'] = Auth::user()->id;
                $is_exist ='';
                if (array_key_exists("staff_id",$arr)){
                    $is_exist = StaffSchedule::where('event_id', '=', $event_id)->where('staff_id', '=', $arr['staff_id'])->first();
                }
                //$arr['availability']

                if ($is_exist) {


                    if($this->checkStaffAvailable($arr['staff_id'],$arr['start_time'],$arr['end_time'], $event)===false)
                    {
                        array_push($unavailable, $arr['staff_id']);
                    }



                    if ($arr['availability'] == 1 && $send_payroll == 1) {
                        //$arr['is_payroll_active'] = 1; // Changing Make Payroll Functionality and switching on Close Event Feature
                        $arr['is_payroll_active'] = 1;
                    } else {
                        $arr['is_payroll_active'] = 0;
                    }

                    $staff_id = $arr['staff_id'];
                    //                    unset($arr['staff_id']);

                    $resp = StaffSchedule::where(['event_id' => $event_id])->where(['staff_id' => $staff_id])->update($arr);
                    $arr['staff_sch_id'] = $is_exist->id;
                    $payroll_return = $this->savePayroll($event_id, $arr);
                } else {
                    if ($this->checkStaffAvailable($arr['staff_id'], $arr['start_time'], $arr['end_time'],$event) === false) {
                        array_push($unavailable, $arr['staff_id']);
                    }
                    $arr['event_id'] = $event_id;
                    $arr['start_date']=$event->start_date;
                    $arr['end_date'] = $event->end_date;
                    // $arr['start_time'] = $event->start_time;
                    // $arr['end_time'] = $event->end_time;

                    $resp = StaffSchedule::create($arr);
                    // echo "<pre>";
                    // print_r($resp);
                    // echo "<pre>";
					if(array_key_exists("status",$arr)){
						if ($arr['status'] == 'confirmed' && $send_payroll == 1) {
							$arr['is_payroll_active'] = 1;
							$arr['staff_sch_id'] = $resp->id;
							$x = $this->savePayroll($event_id, $arr);
						}
					} else {
                        $arr['is_payroll_active'] = 0;
                    }
                }
            }
            // $errors=[];
            if(count($unavailable)===0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Staff Schedule Updated'
                ]);
            }
            $unavailableStaffs= Staff::whereIn('id',$unavailable)->pluck('name')->toArray();


            $unavailableStaffs=implode(' , ', $unavailableStaffs);

            return response()->json([
                'status' => false,
                'message' => $unavailableStaffs.' not available for this time slot'
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'message'=> 'Error! Event has not updated.'
            ]);
        }
    }

    public function updateStaffScheduleByJson(request $request)
    {
		//dd($request);
        parse_str($request->input('formData'), $parseArray);
		// print_r($request->all());
		// print_r($request->input('data_staff_id'));return;

//here we will differentiate bulk sms or single sms
		if ($request->input('data_staff_id')) {
			$eventArray['staff_id']	= $request->input('data_staff_id');
		}else{
			$eventArray['staff_id']	= $parseArray['staff_id'];}
//End bulk sms or single sms

		//for Event_Confirmation
		// $eventArray['staff_id']					= $parseArray['staff_id'];
		$eventArray['event_id']					= $request->input('event_id');
		$eventArray['contact_number']			= $request->input('phone_number');
		$eventArray['location']					= $request->input('event_location_fil');
		$eventArray['arrival_time']				= $request->input('event_arrving_time');
		$eventArray['briefing']					= $request->input('event_briefing');
		$eventArray['venue']					= $request->input('event_location_fil');
		$eventArray['location_guide']			= $request->input('event_loc_guide');
		$eventArray['dress_code']				= $request->input('event_dress_code');
		$eventArray['start_date']				= $request->input('event_start_date');
		$eventArray['start_time']				= $request->input('event_start_time');
		$eventArray['date']						= $request->input('event_date');
		$eventArray['signingMeetingPt']			= $request->input('signMeetPt');
		$eventArray['status']					= 'sent';
		// print_r($eventArray);return;
		$phone_number					= $request->input('phone_number');
		$MessageId						= $request->input('MessageId');
		//for whatsapp
        $wsappArray['message_id'] 		= $request->input('MessageId');
        $wsappArray['contact_number'] 	= $request->input('phone_number');
		$wsappArray['message_type'] 	= 'business';
		$wsappArray['sent'] 			= 'yes';
		$wsappArray['receive'] 			= 'no';
//here we will differentiate bulk sms or single sms
		if ($request->input('data_staff_id')) {
			$wsappArray['staff_id']	= $request->input('data_staff_id');
			$parseArray['staff_id']	= $request->input('data_staff_id');
            $staff_id = $request->input('data_staff_id');
		}else{
			$wsappArray['staff_id']	= $parseArray['staff_id'];
			$parseArray['staff_id']	= $parseArray['staff_id'];
            $staff_id = $parseArray['staff_id'];
			}
//End bulk sms or single sms
        $parseArray['updated_by'] = Auth::user()->id;
		$wsappArray['event_id'] 		= $request->input('event_id');

        $event_id = $request->input('event_id');
        $resp = null;
        if ($event_id) {
            // $staff_id = $parseArray['staff_id'];
            $is_exist = StaffSchedule::where('event_id', '=', $event_id)->where('staff_id', '=', $staff_id)->first();
            unset($parseArray['_token']);

            $parseArray['availability'] = (isset($parseArray['availability']) && ($parseArray['availability'] == 'on')) ? 1 : 0;
            $parseArray['updated_by'] = Auth::user()->id;
            if ($is_exist) {
                unset($parseArray['staff_id']);

                $parseArray['status'] = "pending";
                $parseArray['sms_status'] = "pending";


                $resp = StaffSchedule::where(['event_id' => $event_id])->where(['staff_id' => $staff_id])->update($parseArray);
				Whatsapp::create($wsappArray);
                EventConfirmation::create($eventArray);
				$upwsappArray['message_id'] = $MessageId;
				$upwsappArray['contact_number'] = $phone_number;
                // Whatsapp::where(['contact_number' => $phone_number])->update($upwsappArray);
                $status = "200";
				//tem will update content based upon messageID
                // Whatsapp::create($wsappArray);

            } else {
                $parseArray['event_id'] = $event_id;
                // $parseArray['status'] = ($parseArray['status']) ?  $parseArray['status'] : "pending";
                $parseArray['status'] = "pending";
                $parseArray['sms_status'] = "pending";
                $parseArray['updated_by'] = Auth::user()->id;
                $resp = StaffSchedule::create($parseArray);
                Whatsapp::create($wsappArray);
                EventConfirmation::create($eventArray);

                $status = "200";

            }
        } // if event_id found in request
        else {
            $status = "404"; // event Id not found
        }

        echo json_encode(array('response' => $resp, 'status' => $status));
        exit();
    }

    public function savePayroll($event_id, $sch_arr)
    {
        $staff_id = $sch_arr['staff_id'];

        $is_exist = Payroll::where(['event_id' => $event_id])->where('staff_id', '=', $staff_id)->first();

        $pay_roll['staff_status'] = $sch_arr['status'];
        $pay_roll['payment_status'] = 'unpaid';
        $pay_roll['total_amount'] = $sch_arr['hours'] * $sch_arr['rate_per_hour'];
        $pay_roll['pending_amount'] =  $pay_roll['total_amount'];
        $pay_roll['staff_sch_id'] =  $sch_arr['staff_sch_id'];

        $pay_roll['paid_amount'] = 0;
        if ($is_exist) {
            $result = Payroll::where(['event_id' => $event_id])->where(['staff_id' => $staff_id])->update($pay_roll);
        } else {

            $pay_roll['staff_id'] = $sch_arr['staff_id'];
            $pay_roll['event_id'] = $event_id;
            $result =  Payroll::create($pay_roll);
        }
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaffSchedule  $staffSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request, $id)
    {
        //
        $user_id = Auth::user()->id;

        $data = $request->input('formData');
        parse_str($data, $parsingArray);
        $input_password = $parsingArray['input_password'];
        $data_id = $parsingArray['data_id'];

        $staff_ids = explode(",", $data_id);

        $user = User::find($user_id);


        $hasher = app('hash');
        $status = 0;
        $message = "";
        if ($input_password) {

            if ($hasher->check($input_password, $user->password)) {
                // Success
                $response = StaffSchedule::whereIn('staff_id', $staff_ids)->where('event_id', $id)->delete();
                if ($response) {
                    $payroll_return = Payroll::whereIn('staff_id', $staff_ids)->where('event_id', $id)->delete();

                    $status = 1;
                    $message =  "Data has been removed successfully!!";
                    Session::flash('flash_success', 'Data has been removed successfully!!');
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
}