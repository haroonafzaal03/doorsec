<?php

namespace App\Http\Controllers;

use App\StaffDisciplinary;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class StaffDisciplinaryController extends Controller
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
        $data = $request->all();
       // dd($data);
        if ($request->hasFile('document_name')) {
            $filenameWithExt = $request->file('document_name')->getClientOriginalName();
            $data['document_name'] = $filenameWithExt;

            $file_logo = $request->file('document_name')->store('staff');
            $data['document_path'] = $file_logo;
          }
          $data['created_by']= Auth::user()->id;
          $create = StaffDisciplinary::create($data);
          return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StaffDisciplinary  $staffDisciplinary
     * @return \Illuminate\Http\Response
     */
    public function show(StaffDisciplinary $staffDisciplinary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StaffDisciplinary  $staffDisciplinary
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffDisciplinary $staffDisciplinary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StaffDisciplinary  $staffDisciplinary
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request, StaffDisciplinary $staffDisciplinary)
    {
        $staffDisciplinary= StaffDisciplinary::find($id);
        if ($request->hasFile('document_name')) {
            $filenameWithExt = $request->file('document_name')->getClientOriginalName();
            $staffDisciplinary->document_name = $filenameWithExt;

            $file_logo = $request->file('document_name')->store('staff');
            $staffDisciplinary->document_path = $file_logo;
        }
        $staffDisciplinary->letter_type=$request->letter_type;
        $staffDisciplinary->admin_notes=$request->admin_notes;
        // $data->created_by = Auth::user()->id;

        $staffDisciplinary->save();

        return response()->json([
            'status' => true,
            'message' => 'Disciplinary Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaffDisciplinary  $staffDisciplinary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        parse_str($request->formData, $data);
        $userId=Auth::id();
        $user=User::find($userId);

        if(Hash::check($data['input_password'],$user->password)===false)
        {
            return response()->json([
                'status'=>false,
                'message'=>'Password Incorrect'
            ]);
        }
        $data = StaffDisciplinary::find($id);
        $data->is_deleted = '1';
        $data->save();
        return response()->json([
            'status' => true,
            'message' => 'Disciplinary Deleted'
        ]);
    }
}
