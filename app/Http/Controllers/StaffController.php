<?php

namespace App\Http\Controllers;

use App\Staff;
use App\Client;
use App\StaffType;
use App\StaffSchedule;
use App\User;
use App\SiraType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Session;
use DB;
use App\StaffCertificate;
use Carbon\Carbon;
use DataTables;

class StaffController extends Controller
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
  public function index(Request $request)
  {
    //
    $active_dashboard = session()->get('dashboard');



    if($request->ajax())
    {
      $user=Auth::user();
      $data = Staff::whereHas('stafftypes', function ($query) use ($active_dashboard) {
        $query->where('dashboard', $active_dashboard);
      })->with('stafftypes')
      // ->select('id', 'name', 'contact_number', 'nationality', 'stafftypes.type')
      // ->take(10)
      // ->orderby('created_at','desc')
      // ->take(5)
      ->get();
      // dd($data);
      return DataTables::of($data)
        // ->addIndexColumn()
      ->addColumn('staff_type_id',function($cl){
        return $cl->staff_type_id ? mb_substr($cl->stafftypes['type'], 0, 1) . '-00' . $cl->id : '';
      })
      ->addColumn('staff_image',function($cl){
        return staff_image($cl->id, $cl->name, $cl->picture);
      })
      ->addColumn('staff_type',function($cl){
        return $cl->stafftypes->type;
      })
      ->addColumn('reason',function($cl){
        return '<label class="'. get_label_class_by_key($cl->status).' badge">'. $cl->status.'</label>
                      <br>
                      <small>'.$cl->reason??''.'</small>';
      })
      ->addColumn('action',function($cl) use($user){
        return '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu"  style="background-color:#fff"role="menu">
                        <li><a href="' . route('staff_show', $cl->id) . '">View Details</a></li>
                        '.($user->hasPermission("edit.staff.profile")? '<li><a href="' . route('staff_edit', $cl->id) . '">Edit</a></li>':'').'

                        <li><a href="#" class="updateStatusAnchor" data-id ="' . $cl->id . '"  data-target="#staff_status_popup" data-toggle="" data-status="' . $cl->status . '" > Update Status</a></li>
                        '.($user->hasPermission("block.staff")? '<li><a href="#" class="blockStaffAnchor" data-id ="' . $cl->id . '"   data-target="#BlockStaffPopup" data-toggle="" data-val="' . $cl->block_for_clients . '">Block / Un Block Staff</a></li>':'').'
                        '.($user->hasPermission("delete.staff")? '<li><a  class="removeDataAnchor" data-id ="' . $cl->id . '" data-target="#removeDataPopup"  href="#">Remove</a></li>':'').'
                        </ul>
                      </div>';
      })
      ->rawColumns(['staff_image', 'reason', 'action','staff_type'])->make(true);
    }


    $clients = Client::where('status', 'active')->select('id', 'property_name')->get();

    // return response()->json($data);
    return view('club_event.staff.index', compact( 'clients'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
    $active_dashboard = session()->get('dashboard');
    $staff_type =  StaffType::all();
    $siraTypes = SiraType::all();
    return view('club_event.staff.create', compact('staff_type', 'siraTypes'));
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validation_rules= [
      'passport_number' => 'required|unique:staff',
      'emitrates_id' => 'unique:staff',
      'sira_id_number' => 'required|unique:staff',
      'email'        => 'required|unique:staff',
      'secondary_email'        => 'required|unique:staff',
      'emitrates_id' => 'required|regex:/[0-9]{3}-[0-9]{4}-[0-9]{7}-[0-9]{1}$/'
    ];
    if(!$request->input('secondary_email'))
    {
      unset($validation_rules['secondary_email']);
    }
    $this->validate($request, $validation_rules);
    $staff = $request->all();

    //  dd($staff['tarde_lice']);
    if ($request->hasFile('sira_id_attach')) {
      $file = $request->file('sira_id_attach')->store('staff');
      //  dd($file);
      $staff['sira_id_attach'] = $file;
    }
    // passport_attach
    if ($request->hasFile('passport_attach')) {
      $file_ = $request->file('passport_attach')->store('staff');
      // dd($file_);
      $staff['passport_attach'] = $file_;
    }

    // NOC_attach
    if ($request->hasFile('noc_attach')) {
      $file_ = $request->file('noc_attach')->store('staff');
      // dd($file_);
      $staff['noc_attach'] = $file_;
    }

    // eid_attach
    if ($request->hasFile('emirated_id_attach')) {
      $file_ = $request->file('emirated_id_attach')->store('staff');
      // dd($file_);
      $staff['emirated_id_attach'] = $file_;
    }

    // visa_attach
    if ($request->hasFile('visa_attach')) {
      $file_ = $request->file('visa_attach')->store('staff');
      // dd($file_);
      $staff['visa_attach'] = $file_;
    }
    // picture
    if ($request->hasFile('picture')) {
      $file_logo = $request->file('picture')->store('staff');
      // dd($file_);
      $staff['picture'] = $file_logo;
    }

    $staff['block_for_clients'] = '0';
    $staff['status'] = 'active';

    if (isset($staff['passport_expiry'])) {
      $staff['passport_expiry'] = date('Y-m-d', strtotime($staff['passport_expiry']));
    }

    if (isset($staff['emirates_expiry'])) {
      $staff['emirates_expiry'] = date('Y-m-d', strtotime($staff['emirates_expiry']));
    }

    if (isset($staff['visa_expiry'])) {
      $staff['visa_expiry'] = date('Y-m-d', strtotime($staff['visa_expiry']));
    }

    if (isset($staff['noc_expiry'])) {
      $staff['noc_expiry'] = date('Y-m-d', strtotime($staff['noc_expiry']));
    }

    if (isset($staff['passport_issue'])) {
      $staff['passport_issue'] = date('Y-m-d', strtotime($staff['passport_issue']));
    }

    if (isset($staff['date_of_birth'])) {
      $staff['date_of_birth'] = date('Y-m-d', strtotime($staff['date_of_birth']));
    }

    if (isset($staff['sira_expiry'])) {
      $staff['sira_expiry'] = date('Y-m-d', strtotime($staff['sira_expiry']));
    }



    $staff = Staff::create($staff);
    if ($request->hasFile('staff_certificate')) {
      $files = $request->file('staff_certificate');
      foreach ($files as $file) {
        $file_logo = $file->store('staff');
        $staff_Cert = array(
          'staff_id'=>$staff->id,
          'document_type'=>$file_logo,
          'document_name'=>'certificate'
        );
        StaffCertificate::create($staff_Cert);
      }
    }
    $data = Staff::all();
    $clients = Client::all()->where('status', 'active');

    return view('club_event.staff.index', compact('data', 'clients'))->with('flash_success', 'Staff Details Saved Successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Staff  $staff
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //

    $errors = [];

    $alert = 0;


    $staff = Staff::findorFail($id);
    $clients_list = Client::all()->where('status', 'active');


    $block_clients =  $staff['block_for_clients'];

    DB::enableQueryLog();
    $idsArr = explode(',', $block_clients);

    $BlockClientsList = Client::whereIn('id', $idsArr)->get();


    $today = Carbon::today();
    $siraExpiry = Carbon::parse($staff->sira_expiry);
    $alert = $today->diffInDays($siraExpiry, false);


    return view('club_event.staff.show', compact('staff', 'id', 'BlockClientsList','alert'));
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Staff  $staff
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $errors=[];

    $alert=0;
    $staff = Staff::with('staff_certificate')->findorFail($id);
    $active_dashboard = session()->get('dashboard');
    $staff_type =  StaffType::all();
    $siraTypes = SiraType::all();
    $today = Carbon::today();
    $siraExpiry = Carbon::parse($staff->sira_expiry);
    $alert=$today->diffInDays($siraExpiry, false);


    return view('club_event.staff.edit', compact('staff', 'staff_type', 'siraTypes','alert'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Staff  $staff
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    // dd($request);

    $validation_rules= [
      'passport_number' => 'required|unique:staff,passport_number,' . $id . ',id',
      'emitrates_id' => 'required|regex:/[0-9]{3}-[0-9]{4}-[0-9]{7}-[0-9]{1}$/|unique:staff,emitrates_id,' . $id . ',id',
      'sira_id_number' => 'required|unique:staff,sira_id_number,' . $id . ',id',
      'email'        => 'required|unique:staff,email,' . $id . ',id',
      'secondary_email'        => 'required|unique:staff,secondary_email,' . $id . ',id',
      // 'emitrates_id' => 'regex:/[0-9]{3}-[0-9]{4}-[0-9]{7}-[0-9]{1}$/'
    ];



    if (!$request->input('secondary_email')) {
      unset($validation_rules['secondary_email']);
    }

    $request->validate($validation_rules);

    $staff = Staff::findorFail($id);
    $staff->name = $request->name;
    $staff->contact_number = $request->contact_number;
    $staff->contact_number_home = $request->contact_number_home;
    $staff->passport_number = $request->passport_number;
    $staff->emitrates_id = $request->emitrates_id;
    $staff->uid_number = $request->uid_number;
    $staff->sira_id_number = $request->sira_id_number;
    $staff->passport_expiry = $request->passport_expiry;
    $staff->visa_expiry = $request->visa_expiry;
    $staff->nationality = $request->nationality;
    $staff->height = $request->height;
    $staff->weight = $request->weight;
    $staff->staff_type_id = $request->staff_type_id;
    $staff->sira_type_id = $request->sira_type_id;
    $staff->basic_salary = $request->basic_salary;
    $staff->other_contact_number = $request->other_contact_number;
    $staff->nk_name = $request->nk_name;
    $staff->nk_relation = $request->nk_relation;
    $staff->nk_phone = $request->nk_phone;
    $staff->nk_address = $request->nk_address;
    $staff->nk_nationality = $request->nk_nationality;
    $staff->email = $request->email;
    $staff->secondary_email=$request->secondary_email;
    $staff->general_note= $request->general_note;

    if ($request->hasFile('sira_id_attach')) {
      $file = $request->file('sira_id_attach')->store('staff');
      //  dd($file);
      $staff['sira_id_attach'] = $file;
    }
    // passport_attach
    if ($request->hasFile('passport_attach')) {
      $file_ = $request->file('passport_attach')->store('staff');
      // dd($file_);
      $staff['passport_attach'] = $file_;
    }
    // visa_attach
    if ($request->hasFile('visa_attach')) {
      $file_ = $request->file('visa_attach')->store('staff');
      // dd($file_);
      $staff['visa_attach'] = $file_;
    }

    // NOC_attach
    if ($request->hasFile('noc_attach')) {
      $file_ = $request->file('noc_attach')->store('staff');
      // dd($file_);
      $staff['noc_attach'] = $file_;
    }

    // emirated_id_attach
    if ($request->hasFile('emirated_id_attach')) {
      $file_ = $request->file('emirated_id_attach')->store('staff');
      // dd($file_);
      $staff['emirated_id_attach'] = $file_;
    }
    if ($request->hasFile('staff_certificate')) {
      $files = $request->file('staff_certificate');
      foreach ($files as $file) {
        $file_logo = $file->store('staff');
        $staff_Cert = array(
          'staff_id'=>$id,
          'document_type'=>$file_logo,
          'document_name'=>'certificate'
        );
        StaffCertificate::create($staff_Cert);
      }
    }


    // picture
    if ($request->hasFile('picture')) {
      $file_logo = $request->file('picture')->store('staff');
      // dd($file_);
      $staff['picture'] = $file_logo;
    }

    if ($request->passport_expiry) {
      $staff->passport_expiry = date('Y-m-d', strtotime($request->passport_expiry));
    }

    if ($request->visa_expiry) {
      $staff->visa_expiry = date('Y-m-d', strtotime($request->visa_expiry));
    }

    if ($request->noc_expiry) {
      $staff->noc_expiry = date('Y-m-d', strtotime($request->noc_expiry));
    }

    if ($request->emirates_expiry) {
      $staff->emirates_expiry = date('Y-m-d', strtotime($request->emirates_expiry));
    }

    if ($request->date_of_birth) {
      $staff->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
    }


    if ($request->passport_issue) {
      $staff->passport_issue = date('Y-m-d', strtotime($request->passport_issue));
    }
    if ($request->sira_expiry) {
      // dd(date('Y-m-d', strtotime($request->sira_expiry)));
      $staff->sira_expiry = date('Y-m-d', strtotime($request->sira_expiry));
      // $staff->sira_expiry='2021-07-30';
    }

    $staff->save();
    // dd($staff->sira_expiry);
    return redirect()->back()->with('flash_success', 'Staff Details Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Staff  $staff
   * @return \Illuminate\Http\Response
   */
  public function destroy(request $request)
  {
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
        $response = Staff::where(['id' => $data_id])->delete();
        if ($response) {
          StaffSchedule::where(['staff_id' => $data_id])->delete();
          $status = 1;
          $message =  "Data Successfully Deleted!!";
        } else {
          $status = 0;
          $message =  "Data doesnot Delete. Try Again!!";
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

  public function block_staff(request $request)
  {
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
        $arr['status'] = 'blocked';
        $response = Staff::where(['id' => $data_id])->update($arr);
        if ($response) {
          $status = 1;
          $message =  "Staff  Successfully Blocked!!";
        } else {
          $status = 0;
          $message =  "Staff  not  Blocked. Try Again";
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



  public function update_status(request $request)
  {
    $data = $request->input('formData');
    parse_str($data, $parsingArray);
    $data_id = $parsingArray['data_id'];
    $staff_status = $parsingArray['staff_status'];
    $reason = $parsingArray['reason'];

    $arr['status'] = $staff_status;
    $arr['reason'] = $reason;
    $response = Staff::where(['id' => $data_id])->update($arr);
    if ($response) {
      $status = 1;
      $message =  "Staff  Status Updated !!";
      Session::flash('flash_success', $message);
    } else {
      $status = 0;
      $message =  "Staff  not  updated. Try Again!!";
    }

    echo json_encode(array('message' => $message, 'status' => $status));
    exit();
  }

  public function BlockStaffforClient(request $request)
  {
    $formData = $request->input('formData');

    parse_str($formData, $parsingArray);



    if (isset($parsingArray['clients_list'])) {
      $clients_list = $parsingArray['clients_list'];
      $clients_ids = implode(',', $clients_list);
      $message =  "Staff  has been Block for Selected Clients !!";
    } else {
      $clients_ids = '0';
      $message =  "Staff  has been Un Blocked !!";
    }
    $data_id = $parsingArray['data_id'];
    $input_password = $parsingArray['input_password'];


    $arr['block_for_clients'] = $clients_ids;
    $response = Staff::where(['id' => $data_id])->update($arr);
    if ($response) {
      $status = 1;
    } else {
      $status = 0;
      $message =  "Staff not blocked. Try Again!!";
    }

    echo json_encode(array('message' => $message, 'status' => $status));
    exit();
  }

  public function getStaffJson(request $request)
  {
    $staff_type_id = $request->input('staff_type_id');
    $is_option = $request->input('is_option');

    if ($staff_type_id) {
      $reponse = Staff::where(['staff_type_id' => $staff_type_id])->get();
    } else {
      $reponse = Staff::all();
    }

    if ($is_option) {
      $data = '<option value=""> Select </option>';
      foreach ($reponse as $obj) {
        $data .= '<option value="' . $obj->id . '" data-image="' . img($obj->picture) . '">' . $obj->name . '</option>';
      }
    } else {
      $data = $reponse;
    }

    echo json_encode(array('response' => $data));
    exit();
  }
  public function addCertificate(Request $request){

    if($request->hasFile('staff_certificate')) {
      $files = $request->file('staff_certificate');
      foreach ($files as $file) {
        $file_logo = $file->store('staff');
        $staff_Cert = array(
          'staff_id'=>$request->staff_id,
          'document_type'=>$file_logo,
          'document_name'=>$request->document_name
        );
        StaffCertificate::create($staff_Cert);
      }
    }
    return redirect()->back();
  }
}