<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRightsController extends Controller
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
    public function index()
    {
      $user = User::all();
        return view('user.userrights',compact('user'));
    }
     public function editrole($id)
    {
        /* $role = Role::all();
         return view('user.userrightsedit',compact('role'));*/
         return view('user.userrightsedit')->with(['user'=>User::find($id),'roles'=>Role::all()]);
    }
     public function userRoleUpdate(Request $request,$id)
    {
        $user = User::find($id);
        $user->roles()->sync($request->roles);
        $user->dashboard = $request->dashboard;
        $user->save();
        return redirect()->back();
        //return view('user.userrights',compact('user'));
    }
    public function Roles()
    {
        $roles = Role::all();
        return view('user.roles',compact('roles'));
    }
}