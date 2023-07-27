<?php

namespace App\Http\Controllers;

use Session;

use App\Schedule;
use App\Client;
use App\Staff;
use App\StaffType;
use App\User;
use App\StaffSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Exports\SaffScheduleExport;
use App\Exports\doorSecSecurity;
use Excel;
use DB;
use JavaScript;

class ScheduleController extends Controller
{
       /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $clients = Client::select('property_name','id')->where('status','active')->where('client_type_id','=',1)->where('client_type_id','=',1)->get();
        $clients_ids = Client::all()->pluck('id')->where('status','active')->where('client_type_id','=',1);

        /* get seperate  shifts based on staff */
        $venues = StaffSchedule::leftJoin('staff','staff.id','=','staff_schedules.staff_id')
        ->join('venues','venues.id','=','staff_schedules.venue_id','INNER')
        ->join('clients','clients.id','=','venues.client_id','INNER')
        ->select('staff_schedules.*','staff.name','staff.picture','clients.id as client_id')
        ->get();

        //$venues = Venue::all();
        //dd($venues);

        $staff = Staff::all()->where('status','active');
        $staff_types = StaffType::all();

        $active_dashboard = session()->get('dashboard');
        $staff = Staff::join('staff_types','staff_types.id','=','staff.staff_type_id')->where('staff_types.dashboard',$active_dashboard)->select('staff.*','staff_types.type')->get();

       // dd($staff);
        JavaScript::put([
            'scheduler_clients_ids' => $clients_ids,
            'scheduler_staff_ptj' => $staff,
            'events_ptj' => $venues
        ]);
        //return view('club_event.venue.index');


        //$schedule  = Schedule::all()->where('is_deleted','0');
      //  dd($schedule);
      //return view('club_event.schedule.index',compact('schedule'));
      return view('club_event.venue.dumy',compact('staff','staff_types','clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client  = Client::all()->where('status','active');
        return view('club_event.schedule.create',compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // schdeule_to schdeule_from client_id	start_date	end_date	start_time	end_time	location total_staff	contact_person	event_type	status

        $this->validate($request, [
            'client_id'=>'required',
            'start_date'=>'required',
            'event_name'=>'required',
            'end_date'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'location'=>'required',
            'total_staff'=>'required',
            'event_type'=>'required',
            ]);
        $schedule = $request->all();
        $schedule['start_date'] =   date("Y-m-d",strtotime($schedule['end_date'])) ;
        $schedule['end_date'] =   date("Y-m-d",strtotime($schedule['end_date'])) ;
        $schedule['start_time'] =   date("H:i:s",strtotime($schedule['start_time'])) ;
        $schedule['end_time'] =   date("H:i:s",strtotime($schedule['end_time'])) ;

      //  dd($schedule);

        $schedule = Schedule::create($schedule);

        return redirect()->route('edit_schedule',  $schedule->id)->with('flash_success','Schedule Details Saved Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule =  Schedule::findorFail($id);
        $staff_schedule_details =  StaffSchedule::where('event_id',$id)->get();
        $client  = Client::all()->where('status','active');

        $columns = Schema::getColumnListing('staff');
        $excluded_columns = getExcludedColums();

        foreach($columns  as $index => $arr)
        {
            if(in_array($arr,$excluded_columns))
            {
                unset($columns[$index]);
            }
        }

        return view('club_event.schedule.show',compact('id','schedule','client','staff_schedule_details','columns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $client  =  Client::all()->where('status','active');
        $staff =    Staff::all();
        $staff_types =    StaffType::all();
        $schedule_data = Schedule::findorFail($id);
        $staff_schedule_details =  StaffSchedule::where('event_id',$id)->get();


        $staff_IDS = array();
        $result = array();
        $staff_schedule_IDS = array();

        $scheduled_staff_ids = StaffSchedule::where('event_id',$id)->pluck('staff_id','start_date','end_date');

        $sdate = $schedule_data['start_date'];
        $edate = $schedule_data['end_date'];
        $current_client_id =  $schedule_data['client_id'];

        DB::enableQueryLog();
        if($sdate == $edate)
        {
            $remainingStaff = Staff::
            join('staff_schedules', 'staff_schedules.staff_id', '=', 'staff.id','LEFT')
            ->join('schedules', 'schedules.id', '=', 'staff_schedules.event_id','LEFT')
            ->whereNotIn('staff.id', $scheduled_staff_ids)
            ->whereRaw('NOT FIND_IN_SET('.$current_client_id.',block_for_clients)')
            ->where('staff.status','active')
//            ->where('schedules.start_date', '!=',$sdate)
            ->whereRaw('COALESCE("'.$sdate.'" NOT BETWEEN schedules.start_date AND schedules.end_date, TRUE)')
            ->whereRaw('COALESCE("'.$edate.'" NOT BETWEEN schedules.start_date AND schedules.end_date, TRUE)')
            ->Where('schedules.start_date',NULL)
            ->orWhere('block_for_clients',NULL)
            ->get();
        }
        else
        {
            $remainingStaff = Staff::
            join('staff_schedules', 'staff_schedules.staff_id', '=', 'staff.id','LEFT')
            ->join('schedules', 'schedules.id', '=', 'staff_schedules.event_id','LEFT')
            ->whereNotIn('staff.id', $scheduled_staff_ids)
            ->whereRaw('NOT FIND_IN_SET('.$current_client_id.',block_for_clients)')
            ->where('staff.status','active')
            ->whereRaw('COALESCE("'.$sdate.'" NOT BETWEEN schedules.start_date AND schedules.end_date, TRUE)')
            ->whereRaw('COALESCE("'.$edate.'" NOT BETWEEN schedules.start_date AND schedules.end_date, TRUE)')
            ->orWhere('block_for_clients',NULL)
            ->get();

        }

      // dd(DB::getQueryLog()); // Show results of log

        // ORIGINAL - 01
        // $remainingStaff = Staff::join('staff_schedules','staff_schedules.staff_id','=','staff.id','left')->whereNotIn('staff.id', $scheduled_staff_ids)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',block_for_clients)')->whereRaw("'2019-08-05' NOT BETWEEN staff_schedules.start_date AND staff_schedules.end_date")->whereRaw("'2019-08-07' NOT BETWEEN staff_schedules.start_date AND staff_schedules.end_date")->where('staff.status','active')->orWhere('block_for_clients',NULL)->select('Staff.*')->get();




       // dd(DB::getQueryLog()); // Show results of log
     // dd($remainingStaff);

// RAW QUERY - 01

        // $current_client_id =  $schedule_data['client_id'];
        // $remainingStaff = Staff::join('staff_schedules','staff_schedules.staff_id','=','staff.id','left')->whereNotIn('staff.id', $scheduled_staff_ids)->whereRaw('NOT FIND_IN_SET('.$current_client_id.',block_for_clients)')->where('staff.status','active')->orWhere('block_for_clients',NULL)->whereNotBetween('start_date', [$sdate,$edate])->select('*')->whereNotBetween('end_date', [$sdate,$edate])->select('Staff.*')->get();




      // dd(DB::getQueryLog()); // Show results of log


        //dd($remainingStaff);
         //dd($schedule_data['start_date']);

        return view('club_event.schedule.edit',compact('id','schedule_data','client','staff','staff_schedule_details','remainingStaff','staff_types','current_client_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , $id)
    {
        //
        $this->validate($request, [
            'client_id'=>'required',
            'start_date'=>'required',
            'event_name'=>'required',
            'end_date'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'location'=>'required',
            'total_staff'=>'required',
            'event_type'=>'required',
        ]);
        $schedule = $request->all();
        //dd($schedule);

        $schedule['start_date'] =   date("Y-m-d",strtotime($schedule['start_date'])) ;
        $schedule['end_date'] =   date("Y-m-d",strtotime($schedule['end_date'])) ;
        $schedule['start_time'] =   date("H:i:s",strtotime($schedule['start_time'])) ;
        $schedule['end_time'] =   date("H:i:s",strtotime($schedule['end_time'])) ;

        $DBSchedule =  Schedule::findorFail($id);
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
        return back()->with('flash_success','Schedule has been  updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
        //
        $user_id = Auth::user()->id;

        $data = $request->input('formData');
        parse_str($data,$parsingArray);
        $input_password = $parsingArray['input_password'];
        $data_id = $parsingArray['data_id'];

        $user = User::find($user_id);


        $hasher = app('hash');
        $status = 0;
        if($input_password)
        {


            if ($hasher->check($input_password, $user->password)) {
                // Success

                //$Schedule = Schedule::find($data_id)->delete(); { OLD == WHEN LOGIC WAS TO DELETE WHOLE RECORD }

                $sch_arr['is_deleted'] = 1;
                $Schedule =Schedule::where(['id' => $data_id])->update($sch_arr);
                if($Schedule)
                {
                    $Schedule = StaffSchedule::where(['event_id' => $data_id])->update($sch_arr);
                   // DB::table('staff_schedules')->where('event_id', $data_id)->delete(); { OLD == WHEN LOGIC WAS TO DELETE WHOLE RECORD }
                    $status = 1;
                    $message =  "Schedule  Successfully Deleted!!";
                }
                else
                {
                    $status = 1;
                    $message =  "Schedule  doesnot Delete. Try Again!!";
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

    public function export(request $request , $id)
    {
        $columns = $request->input('form_elements');
        $exporter = app()->makeWith(SaffScheduleExport::class , compact('columns','id'));

        return Excel::download($exporter  ,'EventSchedule.xlsx');
    }

	public function export_guardingSchedule($id)
	{
		return Excel::download(new doorSecSecurity($id), 'doorSecExcelTemplate'.'.xlsx');
	}






}
