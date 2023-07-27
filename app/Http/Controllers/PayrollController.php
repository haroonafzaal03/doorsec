<?php

namespace App\Http\Controllers;

use App\Payroll;
use App\Venue;
use App\Client;
use App\StaffType;
use App\ClientType;
use App\Staff;
use App\Event;
use App\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use PDF;
use Carbon\Carbon;
use App\StaffSchedule;

class PayrollController extends Controller
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
        $client_types = ClientType::all();
        $staff_type = StaffType::all();
        $staffs = Staff::all();
        return view('payroll.index',compact('staff_type','staffs','client_types'));
    }

    public function payroll_details($id)
    {
        $payroll_data = Staff::findorFail($id);
        $client_types = ClientType::all();
        $payroll_info = Payroll::leftjoin('staff_schedules','staff_schedules.id','=','payrolls.staff_sch_id')->where('payrolls.staff_id',$id)->where('payrolls.staff_status','confirmed')->get();
        // dd($payroll_info);
        return view('payroll.payroll_details',compact('payroll_data','client_types','payroll_info'));
    }
    public function getEventVenueJson(request $request){

      $type_id = $request->input('type_id');
      $is_option = $request->input('is_option');
      $staff_id = $request->input('staff_id');
      $venue_date = $request->input('venue_date');
    //    dd($request);
      $data = '<option value=""> Select </option>';

      if($type_id == 1)
        {
            $date_ = Carbon::parse($venue_date);
            $date =  date("Y-m-d",strtotime($date_));
            $response = StaffSchedule::where('staff_id',$staff_id)->where('venue_id','!=','')->where('start_date',$date)->where('end_date',$date)->get();
            foreach($response as $obj){
                $data .= '<option value="'.$obj->venue_id.'" >'.$obj->venue->client->property_name.'</option>';
            }

        }
        else
        {
            $response = StaffSchedule::where('staff_id','=',$staff_id)->where('event_id','!=','')->get();
            //dd($response);
            if($response){
                foreach($response as $obj)
                {
                    if($obj->event)
                        $data .= '<option value="'.$obj->event->id.'" >'.$obj->event->event_name.'</option>';
                }
            }
        }

        if($is_option == false)
        {
          $data = $response;
        }

        echo json_encode(array('response'=>$data));
        exit();
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
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $formData = $request->input('form_data');
        parse_str($formData,$parsingArray);
        $staff_id       = $parsingArray['s_id'];
        $event_venue_id = $parsingArray['event_venue_id'];
        $client_type_id = $parsingArray['client_type_id'];
        //event_venue_id client_type_id
        //dd($event_venue_id);
        if($client_type_id==1){
            $payroll_data = Payroll::where('staff_id',$staff_id)->where('venue_id',$event_venue_id)->get();
        }else{
            $payroll_data = Payroll::where('staff_id',$staff_id)->where('event_id',$event_venue_id)->get();
        }
      // dd($payroll_data);
        if(sizeof($payroll_data)>0)
            echo json_encode($payroll_data);
        else
            echo 'record_not_found';die;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = $request->input('payroll_id');
        $hold = $request->input('hold_payment');
         //print_r($id);die;
        if(!$id){
            $response = "Error";
            $flash_message = "Payroll Details not updated";
            echo json_encode(array('response'=> $response,'flash_message'=>$flash_message));
            exit();
        }
       // dd($request->all());
        $payrolls = Payroll::whereIn('staff_sch_id',$id)->where('payment_status','!=','paid');
        $total_pending_amount = $payrolls->sum('pending_amount');
        $payroll_data = $payrolls->get();
        $remaining_amount_for_payroll =0;
        if($payroll_data){
            foreach($payroll_data as $Payroll){

                //$paid_amount = $Payroll->paid_amount;
                $pending_amount = $request->pending_amount;
                $total_amount = $request->total_amount;
                $total_amount_send = $request->paid_amount;

                if(empty($hold)){
                    if($request->paid_amount > 0)
                    {
                        if($request->paid_amount <= $Payroll->pending_amount){
                            $Payroll->paid_amount += $request->paid_amount;
                            $pending_amount =  $Payroll->pending_amount- $request->paid_amount ;
                            $request->paid_amount = 0;
                        }else{
                            $request->paid_amount =  $request->paid_amount - $Payroll->pending_amount ;
                        //dd($pending_amount);
                        $Payroll->paid_amount += ($total_amount_send - $request->paid_amount);
                        $pending_amount = 0;

                        }
                        // $pending_amount =  $Payroll->total_amount - //$paid_amount;
                        $Payroll->pending_amount = $pending_amount;
                        if($Payroll->pending_amount == 0)
                        {
                                $Payroll->payment_status  = 'paid';
                                $Payroll->payment_date = date("Y-m-d");
                        }
                        else
                        {
                                $Payroll->payment_status  = 'partial';
                        }

                        $Payroll->save();
                        $payment_logs = array(
                            'payroll_id' => $Payroll->id,
                            'paid_amount' => $request->paid_amount,
                            'pending_amount' => $Payroll->pending_amount,
                            'payment_date' => date("Y-m-d"),
                            'payment_status' =>  $Payroll->payment_status
                        );
                       PaymentLogs::create($payment_logs);
                        $response = "OK";
                        $flash_message = "Payroll Details has been updated";
                    }
                }else{

                    $Payroll->payment_status  = 'hold';
                    $Payroll->save();
                    $payment_logs = array(
                        'payroll_id' => $Payroll->id,
                        'paid_amount' => ( $request->paid_amount ) ?  $request->paid_amount : 0 ,
                        'pending_amount' => $Payroll->pending_amount,
                        'payment_date' => date("Y-m-d"),
                        'payment_status' =>  $Payroll->payment_status
                        );

                    PaymentLogs::create($payment_logs);

                    $response = "OK";
                    $flash_message = "Payroll Details has been updated";

                }
            }
        }else{
            $response = "Error";
            $flash_message = "Payroll Details has been updated";
        }



        echo json_encode(array('response'=> $response));
        exit();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payroll $payroll)
    {
        //
    }


    /**
     * get payroll details from storage.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */

    public function filters(request $payroll)
    {
        //
      //  dd($payroll);
        $formData = $payroll->input('formData');
        parse_str($formData,$parsingArray);

        $start_date = $parsingArray['start_date'];
        $end_date = $parsingArray['end_date'];
        $staff_type_id = $parsingArray['staff_type_id'];
        $staff_id = $parsingArray['staff_id'];
        $payment_status = $parsingArray['payment_status'];
        $client_type_id = $parsingArray['client_type_id'];
        $event_venue_id = $parsingArray['event_venue_id'];

        $view = "";
        $status = 0;

        if($staff_type_id || $staff_id || $start_date || $end_date || $payment_status || $client_type_id || $event_venue_id)
        {
           DB::enableQueryLog();
            $response = Payroll::
            join('staff', 'staff.id', '=', 'payrolls.staff_id','LEFT')
            ->join('staff_types', 'staff_types.id', '=', 'staff.staff_type_id','LEFT')
            ->join('events', 'events.id', '=', 'payrolls.event_id','LEFT')
            ->join('clients as C' , 'C.id', '=', 'events.client_id','LEFT')
            ->join('venues', 'venues.id', '=', 'payrolls.venue_id','LEFT')
            ->join('clients', 'clients.id', '=', 'venues.client_id','LEFT');

            if(($client_type_id == 1) && (!empty($event_venue_id)))
            {
                $response->where('venues.client_id', '=', $event_venue_id);
            }
            if(($client_type_id == 2) && (!empty($event_venue_id)))
            {
                $response->where('payrolls.event_id', '=', $event_venue_id);
            }
            else if(($client_type_id == 1) && (empty($event_venue_id)))
            {
                $response->where('clients.client_type_id', '=', $client_type_id);
            }


            if($staff_type_id)
                $response->where('staff.staff_type_id', '=', $staff_type_id);


            if($staff_id)
                $response->where('staff.id', '=', $staff_id);


            if($payment_status)
                $response->where('payrolls.payment_status', '=', $payment_status);

            if($start_date || $end_date)
            {
                $start_date =  date("Y-m-d",strtotime($start_date));
                $end_date   =  date("Y-m-t",strtotime($end_date));
                $response->whereBetween('payrolls.created_at', [$start_date, $end_date]);
            }

            $ids =  $response->distinct()->pluck('staff_id')->toArray();


                // $response->where('payrolls.staff_id', '=', $staff_id);
                // $dbRecord =  $response->select('payrolls.*','staff.name','staff.id as staff_id','staff.picture','events.event_name','clients.client_type_id','C.client_type_id as event_type_id','clients.property_name', DB::raw('sum(paid_amount) as paid_amount, sum(total_amount) as total_amount, sum(pending_amount) as pending_amount'))->where('payrolls.staff_id', '=', $ids[0])->get();

            if(sizeof($ids)>0){
            foreach($ids as $id)
                {
                    $dbRecord[] = Payroll::select('payrolls.*',DB::raw('sum(paid_amount) as paid_amount, sum(total_amount) as total_amount, sum(pending_amount) as pending_amount'))->where('staff_id',$id)->first();

                }
            }else{
                $dbRecord = Payroll::select('payrolls.*',DB::raw('sum(paid_amount) as paid_amount, sum(total_amount) as total_amount, sum(pending_amount) as pending_amount'))->where('staff_id',$staff_id)->first();

                if(!$dbRecord->id)
                    $dbRecord=0;
            }


                $view = view("payroll.ajaxPayrollResult",compact('dbRecord'))->render();

            if($dbRecord > 0)
                if(sizeof($dbRecord) > 0)
                    $status = 1;
        }


        echo json_encode(array('data'=>$view,'status'=>$status));
        exit();

    }

    public function getPaymentLogsById( request $request , $payroll_id)
    {
        $event_venue_id = $request->input('event_venue_id');
        $staff_id = $request->input('staff_id');
        $client_type = $request->input('type');

        if($client_type == 1) // Venue
        {
            $EV_Data = Venue::join('clients', 'clients.id', '=', 'venues.client_id','LEFT')
            ->where('venues.id',$event_venue_id)
            ->select('venues.*','clients.property_name')
            ->first();
        }
        else
        {
            $EV_Data = Event::where('id',$event_venue_id)->first();
        }

        $StaffData = Staff::where('id',$staff_id)->first();
        $payment_logs = PaymentLogs::all()->where('payroll_id',$payroll_id);

        $view = view("payroll.ajaxPaymentLogsResult",compact('payment_logs','EV_Data','StaffData','client_type'))->render();

        echo json_encode(array('data'=>$view));
        exit();
    }

    public function print_payment_slip(request $request)
    {
        $staff_id = $request->input('frm_staff_id');
        $staff =Staff::where('id',$staff_id)->first();
        $pdf =  PDF::loadView("payroll.payment_slip",compact('staff'));
        return $pdf->download('PaymentSlip.pdf');
    }
}