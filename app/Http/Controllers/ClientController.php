<?php

namespace App\Http\Controllers;

use App\ClientType;
use App\Client;
use App\User;
use App\Event;
use App\Location;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Auth;
use Session;

class ClientController extends Controller
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
      if(Session::get('dashboard')=='guarding'){
        $client = Client::where('client_type_id',3)->where('status','active')->get();

      }else{
        $client = Client::where('client_type_id','!=',3)->where('status','active')->get();

      }
        return view('club_event.client.index',compact('client'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Session::get('dashboard')=='guarding'){
          $client_type =  ClientType::where('id',3)->get();
        }else{
          $client_type =  ClientType::where('id','!=',3)->get();
        }
        return view('club_event.client.create',compact('client_type'));
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
        // $this->validate($request, [
        //     'property_name' => 'required',
        //     'property_lice_name' =>'required',
        //     'property_lice_number'=>'required',
        //     'property_lice_expiry_date'=>'required',
        //     'property_tax_registration_number'=>'required',
        //     'property_signatory_id'=>'required',
        //     'property_contract_start'=>'required',
        //     'property_contract_end'=>'required',
        //     'venue_manager_name'=>'required',
        //     'venue_manager_number'=>'required',
        //     'venue_manager_email'=>'required',
        //     'account_manager_name'=>'required',
        //     'account_manager_email'=>'required',
        //     'account_manager_number'=>'required',
        // ]);

        //try {

            $Client = $request->all();
          //  dd($Client['tarde_lice']);
            if($request->hasFile('property_signatory_id')) {
                $file = $request->file('property_signatory_id')->store('client');
              //  dd($file);
                $Client['property_signatory_id'] = $file;
           }
           // trade_lice
           if($request->hasFile('tarde_lice')) {
            $file_ = $request->file('tarde_lice')->store('client');
              // dd($file_);
            $Client['tarde_lice'] = $file_;
            }
            // client_logo
            if($request->hasFile('client_logo')) {
             $file_logo = $request->file('client_logo')->store('client');
               // dd($file_);
             $Client['client_logo'] = $file_logo;
             }
            $Client['status'] = 'active';

            if(isset($Client['property_lice_expiry_date']))
            {
              $Client['property_lice_expiry_date'] = date('Y-m-d',strtotime($Client['property_lice_expiry_date']));
            }
            if(isset($Client['property_contract_start']))
            {
              $Client['property_contract_start'] = date('Y-m-d',strtotime($Client['property_contract_start']));
            }
            if(isset($Client['property_contract_end']))
            {
              $Client['property_contract_end'] = date('Y-m-d',strtotime($Client['property_contract_end']));
            }

            Client::create($Client);

            $location = $Client['client_address'];
            $is_location_exist = Location::where('location_name',$location)->first();
            if(!$is_location_exist)
            {
              $locArr = array(
                'location_name'=>$location
              );

              Location::create($locArr);
            }

            $client = Client::all()->where('status','active');
            return view('club_event.client.index',compact('client'))->with('flash_success','Client Details Saved Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $client = Client::findorFail($id);
        return view('club_event.client.show',compact('client','id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findorFail($id);
        if(Session::get('dashboard')=='guarding'){
          $client_type =  ClientType::where('id',3)->get();
        }else{
          $client_type =  ClientType::where('id','!=',3)->get();
        }
        return view('club_event.client.edit',compact('client','client_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      //  dd($request);
      // $this->validate($request, [
        //     'property_name' => 'required',
        //     'property_lice_name' =>'required',
        //     'property_lice_number'=>'required',
        //     'property_lice_expiry_date'=>'required',
        //     'property_tax_registration_number'=>'required',
        //     'property_signatory_id'=>'required',
        //     'property_contract_start'=>'required',
        //     'property_contract_end'=>'required',
        //     'venue_manager_name'=>'required',
        //     'venue_manager_number'=>'required',
        //     'venue_manager_email'=>'required',
        //     'account_manager_name'=>'required',
        //     'account_manager_email'=>'required',
        //     'account_manager_number'=>'required',
        // ]);


        $data = Client::findorFail($id);
        //dd($client);
        $data->property_name = $request->property_name;
        $data->property_lice_name = $request->property_lice_name;
        $data->property_lice_number = $request->property_lice_number;
        $data->property_tax_regis_num = $request->property_tax_regis_num;
        $data->venue_manager_name = $request->venue_manager_name;
        $data->venue_manager_number = $request->venue_manager_number;
        $data->venue_manager_email = $request->venue_manager_email;
        $data->account_manager_name = $request->account_manager_name;
        $data->account_manager_email = $request->account_manager_email;
        $data->account_manager_num = $request->account_manager_num;
        $data->client_address = $request->client_address;
        $data->client_type_id = $request->client_type_id;

        if($request->property_lice_expiry_date)
        {
          $data->property_lice_expiry_date= date('Y-m-d',strtotime($request->property_lice_expiry_date));
        }
        if($request->property_contract_start)
        {
          $data->property_contract_start = date('Y-m-d',strtotime($request->property_contract_start));
        }
        if($request->property_contract_end)
        {
          $data->property_contract_end = date('Y-m-d',strtotime($request->property_contract_end));
        }




        if($request->hasFile('property_signatory_id')) {
            $file = $request->file('property_signatory_id')->store('client');
          //  dd($file);
            $data->property_signatory_id = $file;
       }
       // trade_lice
       if($request->hasFile('tarde_lice')) {
         $file_ = $request->file('tarde_lice')->store('client');
          // dd($file_);
            $data->tarde_lice = $file_;
        }
        // client_logo
        if($request->hasFile('client_logo')) {
         $file_logo = $request->file('client_logo')->store('client');
           // dd($file_);
           $data->client_logo = $file_logo;
         }
        $data->save();

        $location = $request->client_address;
        $is_location_exist = Location::where('location_name',$location)->first();
        if(!$is_location_exist)
        {
          $locArr = array(
            'location_name'=>$location
          );

          Location::create($locArr);
        }

        return back()->with('flash_success','Client Details Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */

    public function destroy(request $request)
    {
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
              $arr['status'] = 'deleted';
              $response = CLient::where(['id' => $data_id])->update($arr);
              if($response)
              {
                $sch_arr['is_deleted'] = 1;
                Event::where(['client_id' => $data_id])->update($sch_arr);
                $status = 1;
                $message =  "Data Successfully Deleted!!";
              }
              else
              {
                $status = 0;
                $message =  "Data doesnot Delete. Try Again!!";
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

    public function getDataByidJson($id)
    {
        $Client = Client::findorFail($id);
        echo json_encode($Client);
        exit();
    }

    public function get_all_locations(request $request)
    {
        if($request->input('keyword'))
        {
          $locations  = $request->input('keyword');
          $data = Location::where('location_name','like','%'.$locations.'%')->get();
          echo json_encode($data);
          exit();
        }
    }



    public function getEventVenueJson(request $request)
    {
      $type_id = $request->input('type_id');
      $is_option = $request->input('is_option');

      $data = '<option value=""> Select </option>';

      if($type_id == 1)
        {
          $response = Client::where(['client_type_id' => $type_id])->get();
          foreach($response as $obj)
          {
              $data .= '<option value="'.$obj->id.'" >'.$obj->property_name.'</option>';
          }

        }
        else
        {
          $response = Event::select('id','event_name')->get();

          foreach($response as $obj)
          {
              $data .= '<option value="'.$obj->id.'" >'.$obj->event_name.'</option>';
          }

        }

        if($is_option == false)
        {
          $data = $response;
        }

        echo json_encode(array('response'=>$data));
        exit();
    }




}