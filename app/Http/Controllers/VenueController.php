<?php

namespace App\Http\Controllers;

use App\Venue;
use App\Client;
use App\Event;
use App\Staff;
use App\StaffType;
use App\StaffSchedule;
use App\User;
use App\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use JavaScript;
use Carbon\Carbon;
use DB;
use App\EventConfirmation;
use App\Whatsapp;
use App\VenueDetail;
use PDF;

class VenueController extends Controller
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
        $clients = Client::select('property_name','id')->where('status','active')->where('client_type_id','=',1)->where('client_type_id','=',1)->get();
        $clients_ids = Client::all()->pluck('id')->where('status','active')->where('client_type_id','=',1);

        /* get seperate  shifts based on staff */
        $venues = StaffSchedule::leftJoin('staff','staff.id','=','staff_schedules.staff_id')
        ->join('venues','venues.id','=','staff_schedules.venue_id','INNER')
        ->join('clients','clients.id','=','venues.client_id','INNER')
        ->select('staff_schedules.*','staff.name','staff.picture','clients.id as client_id','venues.venue_detail_id')
        ->get();

        //$venues = Venue::all();
       // dd($venues);

        $staff = Staff::all()->where('status','active');
        $staff_types = StaffType::all();
       // dd($staff);
        JavaScript::put([
            'scheduler_clients_ids' => $clients_ids,
            'scheduler_clients_ptj' => $clients,
            'events_ptj' => $venues
        ]);
        return view('club_event.venue.index',compact('staff','staff_types','clients','venues'));
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
        //dd($request);
        /*$venue_data['client_id'] = $request->client_id;
        $venue_data['start_date'] = date("Y-m-d",strtotime($request->start_date));
        $venue_data['end_date'] = date("Y-m-d",strtotime($request->end_date));
        $venue_data['start_time'] = date("H:i:s",strtotime($request->start_time));
        $venue_data['end_time'] = date("H:i:s",strtotime($request->end_time));*/
       // $venue_data['total_staff'] = $request->client_id;;VenueDetail

        if(isset($request->selected_staff)){
            $staff_count = count($request->selected_staff);
            $staff_list = $request->selected_staff;
            $sdate = Carbon::parse($request->start_date);
            $edate = Carbon::parse($request->end_date);
            $diff = $sdate->diffInDays($edate);
            $diff = $diff + 1;
            //dd($diff);
            //dd(count($request->day));
            $count = count($request->day);
            $venue_detail_data['client_id'] = $request->client_id;
            $venue_detail_data['start_date'] = date("Y-m-d",strtotime($request->day[0]));
            $venue_detail_data['end_date'] = date("Y-m-d",strtotime($request->day[$count -1]));
            $venue_detail_data['start_time'] = date("H:i:s",strtotime($request->start_time[0]));
            $venue_detail_data['end_time'] = date("H:i:s",strtotime($request->end_time[$count-1]));
            $venue_detail = VenueDetail::create($venue_detail_data);
            //dd($venue_detail);
            for($j = 0;$j < count($request->day);$j++){
                //$day = $sdate;
                $sdate = $request->day[$j];

              //  for($i=0; $i < sizeof($request->start_time); $i++){
                    $venue_data['client_id'] = $request->client_id;
                    $venue_data['start_date'] = date("Y-m-d",strtotime($sdate));
                    $venue_data['end_date'] = date("Y-m-d",strtotime($sdate));
                    $venue_data['start_time'] = date("H:i:s",strtotime($request->start_time[$j]));
                    $venue_data['end_time'] = date("H:i:s",strtotime($request->end_time[$j]));
                    $venue_data['venue_detail_id'] = $venue_detail->id;
                    $venue = Venue::create($venue_data);

                    $staff = $staff_list[$j];
                    $staff_array = explode(',',$staff);
                    $staff_count = count($staff_array);
                    for($k =0; $k < $staff_count; $k++){
                        $staff_schedule['venue_id'] = $venue->id;
                        $staff_schedule['venue_detail_id'] = $venue_detail->id;
                        $staff_schedule['event_id']  = $request->event_id;
                        $staff_schedule['start_date'] = date("Y-m-d",strtotime($sdate));
                        $staff_schedule['end_date'] = date("Y-m-d",strtotime($sdate));
                        $staff_schedule['start_time'] = date("H:i:s",strtotime($request->start_time[$j]));
                        $staff_schedule['end_time'] = date("H:i:s",strtotime($request->end_time[$j]));
                        $staff_schedule['hours'] = $request->shift_hours[$j];
                        $staff_schedule['rate_per_hour'] = $request->shift_rate_per_hour[$j];
                        $staff_schedule['day'] = date("Y-m-d",strtotime($sdate));
                        $staff_schedule['status'] = 'pending';
                        $staff_schedule['sms_status'] = 'not_sent';
                        $staff_schedule['availability'] = 1;
                        $staff_schedule['staff_id'] = $staff_array[$k];
                        $staff = StaffSchedule::create($staff_schedule);
                    }
                //}
               // $day = $day->addDays(1);
            }
    }
    else
    {
            //dd($request);
            $sdate = Carbon::parse($request->start_date);
            $edate = Carbon::parse($request->end_date);
            $diff = $sdate->diffInDays($edate);
            $diff = $diff + 1;
            //dd($diff);
            for($i=0; $i < $diff; $i++){
                $day = $sdate;
                for($j=0; $j < sizeof($request->start_time); $j++){
                    $venue_data[$j]['client_id'] = $request->client_id;
                    $venue_data[$j]['start_date'] = date("Y-m-d",strtotime($sdate));
                    $venue_data[$j]['end_date'] = date("Y-m-d",strtotime($sdate));
                    $venue_data[$j]['start_time'] = date("H:i:s",strtotime($request->start_time[$j]));
                    $venue_data[$j]['end_time'] = date("H:i:s",strtotime($request->end_time[$j]));
                }
                $venue = Venue::insert($venue_data);
                $day = $day->addDays(1);
            }
        }
        return redirect(route('venue'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venue $venue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue,Request $request)
    {
        $status = 0;
        $venue_id = $request->venue_id;
       //dd($venue_id);
        $venue = Venue::where(['id' => $venue_id])->delete();
        if($venue > 0){
            StaffSchedule::where(['venue_id' => $venue_id])->delete();
            $status = 1;
            $message = 'Venue Shift Deleted Succesfully';
        }
        echo json_encode(array('status'=>$status,'Message' => $message));
        exit();
    }

    public function getShiftScheduledStaff(request $request){
        $data = $request->all();
        $status = 0;
        // Write Query here
        $result = StaffSchedule::join('staff','staff.id','=','staff_schedules.staff_id')->join('staff_types','staff_types.id','=','staff.staff_type_id')->select('staff.*','staff_types.type','staff_schedules.staff_id','staff_schedules.id as ss_id','staff_schedules.status as ss_status','staff_schedules.start_date as ss_date','staff_schedules.start_time','staff_schedules.end_time')->where('venue_id','=',$data['venue_id'])->where('day','=',$data['shiftDate'])->get();
        $current_client_id = $data['client_id'];
        //DB::enableQueryLog();
        $schedule_staff_today = StaffSchedule::where(['day' => $data['shiftDate'], 'start_time' => $data['start_time'],'end_time' => $data['end_time']])->groupBy('staff_id')->pluck('staff_id');
        $staff_not_schedule_today = Staff::whereNotIn('id',$schedule_staff_today)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',staff.block_for_clients)')->where('staff.status','active')->get();
        //dd(DB::getQueryLog());
        //dd($staff_not_schedule_today);
        $view = view("common.staffViewHelper",compact('result'))->render();
        $staffListView = view("common.staffListHelper",compact('staff_not_schedule_today'))->render();
        if(sizeof($result) > 0){
                $status = 1;
        }
        echo json_encode(array('data'=>$view,'status'=>$status,'total_staff_scheduled' => sizeof($result) ,'staffListView' => $staffListView));
        exit();
    }

    public function addStaffToShift(request $request){
        $row_index = $request->row_index;
        //dd($data);

        $venue_data['client_id']         = $request->client_id;
        $venue_data['start_date']        = $request->day;
        $venue_data['end_date']          = $request->day;
        $venue_data['start_time']        = $request->start_time;
        $venue_data['end_time']          = $request->end_time;
        $venue_data['venue_detail_id']   = $request->venue_detail_id;

        $venue = Venue::create($venue_data);


        $data = [
            'day' => $request->day,
            'venue_id' => $venue->id,
            'staff_id' => $request->staff_id,
            'start_date' => $request->day,
            'end_date' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'hours' => $request->hours,
            'rate_per_hour' => 0
        ];
        $data['status'] = 'pending';
        $data['sms_status'] = 'not_sent';


        $staff = StaffSchedule::create($data);
        //$result = Staff::join('staff_types','staff_types.id','=','staff.staff_type_id')->where('id','=',$staff//['staff_id'])->select('staff_types.type,staff.*')->get();

        $result = StaffSchedule::join('staff','staff.id','=','staff_schedules.staff_id')->join('staff_types','staff_types.id','=','staff.staff_type_id')->select('staff.*','staff_types.type','staff_schedules.staff_id','staff_schedules.venue_id','staff_schedules.id as ss_id','staff_schedules.status as ss_status','staff_schedules.sms_status as ss_sms_status','staff_schedules.start_time','staff_schedules.end_time','staff_schedules.start_date','staff_schedules.hours','staff_schedules.rate_per_hour')->where('staff_schedules.id','=',$staff['id'])->get();
   // dd($result);
        $view = view("common.staffViewHelper",compact('result','row_index'))->render();
        if(sizeof($result) > 0){
                $status = 1;
        }
        echo json_encode(array('data'=>$view,'status'=>$status));
        exit();

    }

    public function removeStaffFromShift(request $request){
        $user_id = Auth::user()->id;
        $data = $request->input('formData');
        parse_str($data,$parsingArray);
        $input_password = $parsingArray['input_password'];
        $data_id = $parsingArray['data_id'];
        $user = User::find($user_id);
        $hasher = app('hash');
        $status = 0;
        //dd($parsingArray);
        if($input_password)
        {
            if ($hasher->check($input_password, $user->password)) {
                $response = StaffSchedule::where('id',$data_id)->delete();
                // dd($response);
                if($response){
                    $response = Payroll::where('staff_sch_id',$data_id)->delete();
                    if($response){
                    $status = 1;
                    $message = 'Staff removed from shift';
                    }else{
                        $status = 1;
                        $message = 'Staff removed from shift but not exit in payroll';
                    }
                }else{
                    $status = 0;
                    $message = 'Staff not removed from shift';
                }
            }
            else
            {
                $message =  "Password Doesn't Match!!";
            }
        }
        else
        {
            $status = 0;
            $message =  "Please, Enter you password !!";
        }



        echo json_encode(array('message'=>$message,'status'=>$status));
        exit();
    }

    public function blockStaffFromShift(request $request){
        $data = $request->all();
        //dd($data);
        if($data['staff_schedule_status'] == 'confirmed'){
            $arr['status'] = 'dropout';
        }else if($data['staff_schedule_status'] == 'dropout'){
            $arr['status'] = 'confirmed';
        }else if($data['staff_schedule_status'] == 'pending'){
            $arr['status'] = 'confirmed';
        }

        // $arr['']
        $response = StaffSchedule::where(['id' => $data['staff_schedule_id']])->update($arr);
        //dd($response);
        if($response){
            $status = 1;
            $message = 'Status has been updated!';
            $staff_status = $arr['status'];
        }else{
            $status = 0;
            $message = 'Status updatation failed!';
            $staff_status = '';
        }

        echo json_encode(array('message'=>$message,'status'=>$status,'staff_status' =>$staff_status));
        exit();
    }

    public function updateShiftStaff(request $request){
        $data = $request->input('formData');
        parse_str($data,$parsingArray);
           // dd($parsingArray['arrayC']);

        foreach($parsingArray['arrayC'] as $key=>$dt){
            $arr['start_time'] = $dt['start_time'];
            $arr['end_time'] = $dt['end_time'];
            $arr['hours'] = $dt['hours'];
            $arr['rate_per_hour'] = $dt['rate_per_hour'];
            $arr['status'] = $dt['staff_sch_status'];
            $arr['availability'] = $dt['availability'];


            //dd($dt['start_time']);
            //print_r($dt);
            StaffSchedule::where(['id' => $dt['id']])->update($arr);
        }
        echo json_encode(array('message'=> 'Staff Details Updated Successfully'));
        exit();
    }

    function getAvailableStaffList(request $request){
        $data = $request->all();
        //dd($data);
        $current_client_id = $data['client_id'];
        //DB::enableQueryLog();
        // Write Query here
        //$schedule_staff_today = StaffSchedule::where(['day' => $data['shiftDate'], 'start_time' => $data['start_time'],'end_time' => $data['end_time']])->groupBy('staff_id')->pluck('staff_id');
        //dd(DB::getQueryLog());
        //date("H:i:s",strtotime($data['start_time']));

        $schedule_staff_today = StaffSchedule::where('day','=',$data['shiftDate'])->Where('start_time','<=',$data['start_time'])->Where('end_time','>=',$data['start_time'])->groupBy('staff_id')->pluck('staff_id');
        //dd($schedule_staff_today);
        $staff_not_schedule_today = Staff::whereNotIn('id',$schedule_staff_today)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',staff.block_for_clients)')->where('staff.status','active')->where('staff.staff_type_id',$data['staff_type'])->get();
        $staffListView = view("common.staffListHelper",compact('staff_not_schedule_today'))->render();
        echo json_encode(array('staffListView' => $staffListView));
        exit();
    }

    function getAvailableStaffList1(request $request){
        $data = $request->all();
      //dd($data);
        $selected_staffs = '';
        $current_client_id = $data['client_id'];
        if($data['stafflist'] != null || $data['stafflist'] != ''){
            $selected_staffs = explode(',',$data['stafflist']);
            //dd($selected_staffs);
        }

       // DB::enableQueryLog();
        // Write Query here
        //$schedule_staff_today = StaffSchedule::where(['day' => $data['day'], 'start_time' => $data['start_time'],'end_time' => $data['end_time']])->groupBy('staff_id')->pluck('staff_id');
        //dd(DB::getQueryLog());
        //$schedule_staff_today = StaffSchedule::where('day','=',$data['day'])->where('start_time','<=',$data['start_time'])->Where('end_time','>=',$data['start_time'])->groupBy('staff_id')->pluck('staff_id');
		$schedule_staff_today = StaffSchedule::where('day','=',$data['day'])
												->where(function ($query)use ($data) {
													$query->where(function ($q)use ($data) {
														$q->whereTime('start_time','<=',$data['start_time'])
															  ->whereTime('end_time', '>=', $data['start_time']);
													})->orWhere(function ($qu)use ($data) {
														$qu->whereTime('start_time', '<=', $data['end_time'])
															  ->whereTime('end_time', '>=', $data['end_time']);
													})->orWhere(function ($que) use ($data){
														$que->whereTime('start_time', '>=', $data['start_time'])
															  ->whereTime('end_time', '<=', $data['end_time']);
													});
												})->groupBy('staff_id')->pluck('staff_id');
        //dd(DB::getQueryLog());
        //dd($schedule_staff_today);
        $staff_not_schedule_today = Staff::whereNotIn('id',$schedule_staff_today)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',staff.block_for_clients)')->where('staff.status','active')->orderBy('name','DESC')->get();
       // dd($staff_not_schedule_today);
     //   dd(DB::getQueryLog());

        $html = '';
        $temp = '';
        $result_array= array();
        //dd(count($staff_not_schedule_today));
        if($staff_not_schedule_today){
            foreach($staff_not_schedule_today as $obj){
                if(!empty($selected_staffs)){
                if(in_array($obj["id"], $selected_staffs)){
                        $temp = 'class="selected"';
                        }else{
                        $temp = 'class=""';
                        }
                }
               $image =  img($obj['picture']);
               $switch = ($obj['staff_type_id'] == 1) ? "on" : "off";

               if(!empty($data['runtime_selected_staff'])){
                $runtime_selected_staff = $data['runtime_selected_staff'];
				 $datesARRAY = array_column($runtime_selected_staff, 'day');
                // dd($runtime_selected_staff);
                   foreach($runtime_selected_staff as $rss){
                   //if($rss['day'] == $data['day']){
					   if(in_array($data['day'], $datesARRAY)){
                       // echo "same day ";
                        $result = $this->is_in_array($data['runtime_selected_staff'],'staff',$obj["id"],$data['day']);
                        if(is_array($result)){
                            if($data['start_time'] <= $result['start_time'] && $data['end_time'] >= $result['end_time'] && $data['day'] == $result['day']){
                                 //nothing to do
                            }else{
                                //$obj["id"] id
                                $result_array[$obj['id']] = array(
                                    'obj'=>$obj,
                                    'img'=>$image,
                                    'switch'=>$switch,
                                    'temp'=>$temp
                                );
                                // $list= $this->list_html($obj,$image,$switch,$temp);
                                // $html = $list.$html;
                            }
                         }
                         else{
                            $result_array[$obj['id']] = array(
                                'obj'=>$obj,
                                'img'=>$image,
                                'switch'=>$switch,
                                'temp'=>$temp
                            );
                            // $list= $this->list_html($obj,$image,$switch,$temp);
                            // $html = $list.$html;
                         }
                   }else{
                    $result_array[$obj['id']] = array(
                        'obj'=>$obj,
                        'img'=>$image,
                        'switch'=>$switch,
                        'temp'=>$temp
                    );
                        // echo "alternate day ";
                        // $list= $this->list_html($obj,$image,$switch,$temp);
                        // $html = $list.$html;
                   }
                }


            }else{
                    $result_array[$obj['id']] = array(
                        'obj'=>$obj,
                        'img'=>$image,
                        'switch'=>$switch,
                        'temp'=>$temp
                    );
                    // $list= $this->list_html($obj,$image,$switch,$temp);
                    // $html = $list.$html;
                }
                $html = $this->list_html_r($result_array);
            $html = '<input type="hidden" id="tr_shift_id" value='. $data['tr'].'>'.$html;

        }
    }
        echo json_encode(array('staffList' => $html));
        exit();
    }
    public function list_html_r($result_array){
        $html= '';
        foreach($result_array as $ra){
            $list = $this->list_html($ra['obj'],$ra['img'],$ra['switch'],$ra['temp']);
            $html = $list.$html;
        }
        return $html;
    }
   public function list_html($obj,$image,$switch,$temp){
        $list = '<li id="li-'.$obj["id"].'" data-id="'.$obj["id"].'" data-image="'.$image.'" data-view="'.$switch.'" data-stafftype="'.$obj['staff_type_id'].'" data-exist="false" data-dropout="false" onclick="abbb('.$obj["id"].');" '.$temp.'><img src="'.$image.'" class="custom staff_list_view img-circle" data-staffid="'.$obj["id"].'" data-staffname="'.$obj["name"].'"/> <span class="list_staff_name ">'. $obj["name"] .'</span></li>';
        return $list;
    }
    function is_in_array($array, $key, $key_value, $day=""){
        $within_array = 'no';
        foreach( $array as $k=>$v ){
          if( is_array($v) ){
			  if($v['day'] == $day){
				$within_array = $this->is_in_array($v, $key, $key_value);
				  if( $within_array == 'yes' ){
					  $within_array = $v;
					  break;
				  }
			  }
          } else {
                  if( $v == $key_value && $k == $key ){
                          $within_array = 'yes';
                          break;
                  }
          }

        }
        return $within_array;
  }
    public function getShiftScheduledStaff1(request $request){
        $data = $request->all();
        $status = 0;
        $row_index = '';
        $venue_ids = Venue::select('id')->where('client_id','=',$data['client_id'])->where('start_date','=',$data['shiftDate'])->where('end_date','=',$data['shiftDate'])->pluck('id');
        // Write Query here
        $result = StaffSchedule::join('staff','staff.id','=','staff_schedules.staff_id')->join('staff_types','staff_types.id','=','staff.staff_type_id')->select('staff.*','staff_types.type','staff_schedules.staff_id','staff_schedules.id as ss_id','staff_schedules.status as ss_status','staff_schedules.start_time','staff_schedules.end_time','staff_schedules.sms_status as ss_sms_status','staff_schedules.wa_response','staff_schedules.start_date as ss_date','staff_schedules.rate_per_hour','staff_schedules.availability','staff_schedules.hours','staff_schedules.venue_id')->whereIn('venue_id',$venue_ids)->get();
        // dd($result[0]->venue);
        // $current_client_id = $data['client_id'];
        // //DB::enableQueryLog();
        // $schedule_staff_today = StaffSchedule::where(['day' => $data['shiftDate'], 'start_time' => $data['start_time'],'end_time' => $data['end_time']])->groupBy('staff_id')->pluck('staff_id');
        // $staff_not_schedule_today = Staff::whereNotIn('id',$schedule_staff_today)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',staff.block_for_clients)')->where('staff.status','active')->get();
        // //dd(DB::getQueryLog());
        // //dd($staff_not_schedule_today);
        $view = view("common.staffViewHelper",compact('result','row_index'))->render();
        // $staffListView = view("common.staffListHelper",compact('staff_not_schedule_today'))->render();
        if(sizeof($result) > 0){
                $status = 1;
        }
        //echo json_encode(array('data'=>$view,'status'=>$status,'total_staff_scheduled' => sizeof($result) ,'staffListView' => $staffListView));
        echo json_encode(array('data'=>$view,'status'=>$status,'total_staff_scheduled' => sizeof($result)));
        exit();
    }

    function updateStaffScheduleByJson(request $request){
        parse_str($request->input('formData'),$parseArray);
		// echo "<pre>";
		// print_r($request->all());
		// echo "<pre>";
		// print_r($parseArray);return;
		$phone_number = $parseArray['phone_number'];
		if ($phone_number)
		$phone_numbers = ltrim($phone_number, '0');

		$venueArray['staff_id']					= $parseArray['staff_ids'];
		$venueArray['venue_id']					= $parseArray['venue_ids'];
		// $venueArray['contact_number']			= $parseArray['phone_number'];
		$venueArray['contact_number']			= $phone_numbers;
		$venueArray['location']					= $parseArray['clientName'];
		$venueArray['arrival_time']				= $parseArray['arrving_timeV'];
		$venueArray['briefing']					= $parseArray['briefing_timeV'];
		$venueArray['signingMeetingPt']			= $parseArray['signMeetPt'];
		$venueArray['venue']					= $parseArray['clientName'];
		$venueArray['location_guide']			= $parseArray['localtionGuideV'];
		$venueArray['dress_code']				= $parseArray['dressCodeV'];
		$venueArray['start_date']				= $request->input('data_venue_date');
		$venueArray['start_time']				= '';
		$venueArray['date']						= $parseArray['venueCurrentDate'];
		$venueArray['status']					= 'sent';
		// print_r($venueArray);return;

        $data_venueName 		= $request->input('data_venueNameArr');

		//for whatsapp
        $wsappArray['message_id'] 		= $request->input('MessageId');
        // $wsappArray['contact_number'] 	= $parseArray['phone_number'];
        $wsappArray['contact_number'] 	= $phone_numbers;
		$wsappArray['message_type'] 	= 'business';
		$wsappArray['sent'] 			= 'yes';
		$wsappArray['receive'] 			= 'no';
		$wsappArray['staff_id'] 		= $parseArray['staff_ids'];
		$wsappArray['venue_id'] 		= $parseArray['venue_ids'];
		$wsappArray['start_date'] 		= $request->input('data_venue_date');


        $staff_schedule_pid = $request->input('staff_schedule_pid');
        $resp = null;
        if($staff_schedule_pid)
        {
            $staff_schedule_pids = explode(',',$staff_schedule_pid);
            for($i =0; $i < sizeof($staff_schedule_pids); $i++){

				if (sizeof($staff_schedule_pids)>1) {

					for ($i=0; $i < sizeof($staff_schedule_pids); $i++) {
						$resp = StaffSchedule::where(['id' => $staff_schedule_pids[$i]])->first();
							$contact_numbers = $resp->staff_data->contact_number;
							if ($contact_numbers)
							$contact_numbers = ltrim($contact_numbers, '0');
// print_r($resp);die;
						$clickaArray = array(
						'contact_no'		=> $contact_numbers,
						'start_time'		=> $resp->start_time,
						'venue_id'			=> $resp->venue_id,
						'start_date'		=> $resp->start_date,
						'arrival_time'		=> $venueArray['arrival_time'],
						'briefing_time'		=> $venueArray['briefing'],
						'location_guide'	=> $venueArray['location_guide'],
						'dress_code'		=> $venueArray['dress_code'],
						'venueCurrentDate'	=> $parseArray['venueCurrentDate'],
						'signMeetPt'		=> $parseArray['signMeetPt'],
						'data_venueName'	=> $data_venueName,
						);
						$response = $this->clickatelTest($clickaArray);
						//For EventConfirmation
						$venueArray['staff_id']					= $resp->staff_id;
						$venueArray['venue_id']					= $resp->venue_id;
						$venueArray['contact_number']			= $contact_numbers;
						$venueArray['location']					= $data_venueName;
						$venueArray['arrival_time']				= $venueArray['arrival_time'];
						$venueArray['briefing']					= $venueArray['briefing'];
						$venueArray['venue']					= $data_venueName;
						$venueArray['location_guide']			= $venueArray['location_guide'];
						$venueArray['dress_code']				= $venueArray['dress_code'];
						$venueArray['start_date']				= $resp->start_date;
						$venueArray['start_time']				= $resp->start_time;
						$venueArray['date']						= $parseArray['venueCurrentDate'];
						$venueArray['status']					= 'sent';
						//For Whatsapp
						$wsappArray['message_id'] 		= $response['messageId'];
				        $wsappArray['contact_number'] 	= $contact_numbers;
						$wsappArray['message_type'] 	= 'business';
						$wsappArray['sent'] 			= 'yes';
						$wsappArray['receive'] 			= 'no';
						$wsappArray['staff_id'] 		= $resp->staff_id;
						$wsappArray['venue_id'] 		= $resp->venue_id;
						$wsappArray['start_date'] 		= $resp->start_date;
						$wsappArray['start_time'] 		= $resp->start_time;


							// print_r($response['messageId']);
						$is_exist = StaffSchedule::where('id', '=', $staff_schedule_pids[$i])->first();
		                unset($parseArray['_token']);

		                $data['availability'] = (isset($parseArray['availability']) && ($parseArray['availability'] == 'on')) ? 1 : 0;

		                if($is_exist)
		                {
		                    $data['status'] = "pending";
		                    $data['sms_status'] = "pending";
		                    DB::enableQueryLog();
		                    $resp = StaffSchedule::where(['id' => $staff_schedule_pids[$i]])->update($data);
		               		EventConfirmation::create($venueArray);
		                	Whatsapp::create($wsappArray);

		                    //dd(DB::getQueryLog());
		                    $status = "200";
		                }
					}

				}else{
//commentStart
                $is_exist = StaffSchedule::where('id', '=', $staff_schedule_pids[$i])->first();
                unset($parseArray['_token']);

                $data['availability'] = (isset($parseArray['availability']) && ($parseArray['availability'] == 'on')) ? 1 : 0;

                if($is_exist)
                {
//For Event_Confirmation
					$contact_numbers = $is_exist->staff_data->contact_number;
					if ($contact_numbers)
					$contact_numbers = ltrim($contact_numbers, '0');

					$clickaArray = array(
					'contact_no'		=> $contact_numbers,
					'start_time'		=> $is_exist->start_time,
					'venue_id'			=> $is_exist->venue_id,
					'start_date'		=> $is_exist->start_date,
					'arrival_time'		=> $venueArray['arrival_time'],
					'briefing_time'		=> $venueArray['briefing'],
					'location_guide'	=> $venueArray['location_guide'],
					'dress_code'		=> $venueArray['dress_code'],
					'venueCurrentDate'	=> $parseArray['venueCurrentDate'],
					'signMeetPt'		=> $parseArray['signMeetPt'],
					'data_venueName'	=> $data_venueName,
					);
					$response = $this->clickatelTest($clickaArray);

                    $data['status'] = "pending";
                    $data['sms_status'] = "pending";
                    DB::enableQueryLog();
					$wsappArray['message_id'] 		= $response['messageId'];
					$wsappArray['venue_id'] 		= $is_exist->venue_id;

					$venueArray['venue_id'] 		= $is_exist->venue_id;
					$venueArray['location'] 		= $data_venueName;

                    $resp = StaffSchedule::where(['id' => $staff_schedule_pids[$i]])->update($data);
               		EventConfirmation::create($venueArray);
                	Whatsapp::create($wsappArray);

                    //dd(DB::getQueryLog());
                    $status = "200";
                }
			}
//commentEnd
            }
        } // if staff_schedule_pid found in request
        else
        {
            $status = "404"; // staff_schedule_pid not found
        }

        echo json_encode(array('response'=>$resp,'status'=>$status));
        exit();
    }

    function sendPayroll(request $request){
        $parsingArray = [];
        $data = $request->input('formData');
        parse_str($data,$parsingArray);
        //dd($parsingArray['arrayC']);die;

        $staff_schedule_arr = $parsingArray['arrayC'];
        foreach($staff_schedule_arr as $index => $arr)
            {
                DB::enableQueryLog();


                if($arr['availability'] == 1)
                {
                    $arr['is_payroll_active'] = 1;

                }
                else
                {
                    $arr['is_payroll_active'] = 0;
                }
                $payroll_return = $this->savePayroll($arr['venue_id'],$arr);

                if($payroll_return){
                    $arr['status'] = $arr['staff_sch_status'];
                    $staff_id = $arr['staff_id'];
                    unset($arr['staff_id']);
                    unset($arr['staff_sch_status']);
                    unset($arr['staff_sch_id']);
                    $resp = StaffSchedule::where(['venue_id' => $arr['venue_id']])->where(['staff_id' => $staff_id])->update($arr);
                    if($resp){
                        $resp = 'Payroll active';
                        $status = "200";
                    }
                }else{
                        $resp = 'Payroll not updated!';
                        $status = "404";
                }
            }
            echo json_encode(array('response'=>$resp,'status'=>$status));
    }

    public function savePayroll($id,$sch_arr)
    {
        //dd($sch_arr);
        $staff_id = $sch_arr['staff_id'];

        $is_exist = Payroll::where(['venue_id' => $id])->where('staff_id', '=', $staff_id)->first();

        $pay_roll['staff_status'] = $sch_arr['staff_sch_status'];
        $pay_roll['payment_status'] = 'unpaid';
        $pay_roll['total_amount'] = $sch_arr['hours'] * $sch_arr['rate_per_hour'];
        $pay_roll['pending_amount'] =  $pay_roll['total_amount'];
        $pay_roll['paid_amount'] = 0;
        $pay_roll['staff_sch_id'] =  $sch_arr['staff_sch_id'];
        if($is_exist)
        {
            $result = Payroll::where(['venue_id' => $id])->where(['staff_id' => $staff_id])->update($pay_roll);
        }
        else
        {
            $pay_roll['staff_id'] = $sch_arr['staff_id'];
            $pay_roll['venue_id'] = $id;
            $result =  Payroll::create($pay_roll);
        }
        return $result;
    }

    public function clickatelTest($clickaArray){
// 		echo "<pre>";
// 		print_r($clickaArray);
// return;
        $url = 'https://platform.clickatell.com/v1/message';
        $headers = array(
			"POST /v1/messages",
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: 5dfJ3cO_Riiw62Gv4RmB_g=="
			);
            $data = '{"messages": [{ "channel": "whatsapp", "to": "'.$clickaArray['contact_no'].'","hsm" : {"template":"venue_event_confirmation","parameters" : {"1": "'.$clickaArray['data_venueName'].'","2": "'.$clickaArray['data_venueName'].' Location '.$clickaArray['location_guide'].'","3": "'.$clickaArray['signMeetPt'].'","4": "'.$clickaArray['start_date'].'","5": "'.$clickaArray['arrival_time'].'","6": "'.$clickaArray['briefing_time'].'","7":"'.$clickaArray['start_time'].'","8": "'.$clickaArray['dress_code'].'"}}}]}';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$response = curl_exec($ch);
			curl_close($ch);
            // print_r($response);
			$responses = json_decode($response,true);
			$messageId = $responses['messages'][0]['apiMessageId'];
			$toMessage = $responses['messages'][0]['to'];
			$ArrayData = array(
				'messageId'=>$messageId,
				'toMessage'=>$toMessage
			);
			return json_encode($ArrayData);
        }
        public function schedule_print($id){

            $data_event  = Venue::join('clients', 'venues.client_id', '=', 'clients.id')->leftJoin('staff_schedules', 'venues.id', '=', 'staff_schedules.venue_id')->leftJoin('staff', 'staff_schedules.staff_id', '=', 'staff.id')->leftJoin('staff_types', 'staff.staff_type_id', '=', 'staff_types.id')->select('venues.*', 'clients.property_name', 'staff_schedules.staff_id', 'staff.name', 'staff.picture', 'staff_types.type')->where('venues.status', '!=', 'closed')->where('staff_schedules.staff_id',$id);
            $events= $data_event->get();
            $selected_id = $data_event->groupby('client_id')->pluck('client_id');
            //  dd($selected_id);
            $clients = Client::select('property_name', 'id')->where('status', 'active')->where('client_type_id', '=', 1)->whereIn('id',$selected_id)->get();
            // dd($events);
             //return view('club_event.event.print', compact('clients', 'events'));
            // dd($data);
            $pdf = PDF::loadView('club_event.event.print',compact('clients', 'events'));
            return $pdf->download('schedule_print.pdf');
        }
}