<?php

namespace App\Http\Controllers;

use App\Permissions;
use App\Role;
use App\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function create(Request $data)
    {
             Role::create([
            'name' => $data['name'],
            'slug' =>strtolower($data['name']),
            'description' => $data['description'],
        ]);
             return redirect('rolesrights');
    }
    
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
     public function edit($id)
    {
         return view('user.rolesedit')->with(['role'=>Role::find($id),'permissions'=>Permissions::all()]);
    }
      public function Update(Request $request,$id)
    {
        $role = Role::find($id);
        $role->permissions()->sync($request->permissions);
        return redirect('rolesrights');
        //return view('user.userrights',compact('user'));
    }
    
   
}
