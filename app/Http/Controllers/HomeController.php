<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Staff;
use Session;


class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
       //dd(session()->all());
       $request->session()->put('dashboard','guarding');
       $request->session()->put('dash_display','Guarding');
        return view('home');
    }

    public function club_event(Request $request)
    {

       // dd(session()->all());
       $request->session()->put('dashboard','club_events');
       $request->session()->put('dash_display','Club & Events');
        return view('club_event.dashboard');
    }

    public function switchDashboard(Request $request)
    {
        if($request->input('guarding'))
        {
            $request->session()->put('dashboard','guarding');
            $request->session()->put('dash_display','Guarding');
            return redirect()->action('HomeController@index');
        }
        else
        {
            $request->session()->put('dashboard','club_events');
            $request->session()->put('dash_display','Club & Events');
            return redirect()->action('HomeController@club_event');
        }

    }
    public function switchDashboard_login($request)
    {
        if($request =='guarding')
        {
            Session::put('dashboard','guarding');
            Session::put('dash_display','Guarding');
            return redirect()->action('HomeController@index');
        }
        else
        {
            Session::put('dashboard','club_events');
            Session::put('dash_display','Club & Events');
            return redirect()->action('HomeController@club_event');
        }
    }

    public function PasswordVerification(Request $request) {

        $user_id = Auth::user()->id;

        $data = $request->input('formData');
        parse_str($data,$parsingArray);
        $input_password = $parsingArray['input_password'];
        $data_id = $parsingArray['data_id'];

        $user = User::find($user_id);


        $hasher = app('hash');
        $status = 0;

        if ($hasher->check($input_password, $user->password)) {
            // Success
            if($data_id){
                $response   = $this->destroy($data_id);
            }else{
                $response = true;
            }
            if($response)
            {
                $status = 1;
                $message =  "Password Successfully Deleted!!";
            }
        }
        else
        {
            $message =  "Password Doesn't Match!!";
        }
        echo json_encode(array('message'=>$message,'status'=>$status));
        exit();

    }

    public function destroy($id)
    {
        $Staff = Staff::find($id)->delete();
        if($Staff)
        {
            Session::flash('flash_success', 'Password Successfully Deleted!');
            return 1;
        }else
        {
            return 0;
        }
    }

}