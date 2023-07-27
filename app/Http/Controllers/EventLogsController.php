<?php

namespace App\Http\Controllers;

use App\EventLogs;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Exports\EventLogsExport;
use Excel;
use DB;


class EventLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $event_logs_list =  EventLogs::where('event_id',$id)->get();

        $event_details =  Event::findorFail($id);

        $event_logs_list = $event_logs_list->reverse();

        $last_index = sizeof($event_logs_list);
        $staff_schedule_details = null ;

        return view('club_event.event.event_logs',compact('id','event_logs_list','event_details'));
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

        $formData = $request->input('formData');
        parse_str($formData,$EventLogsArray);

        $EventLogsArray['time'] = date('H:i:s',strtotime(now()));
        $response = EventLogs::create($EventLogsArray);
        if($response)
        {
            $status = 200;
            $data['time'] = date('H:i:s a',strtotime($response->time));
            $data['status'] = $response->status;
        }
        else{
            $data = null;
            $status = 404;
        }

        echo json_encode(array('data'=>$data,'response'=>$status));
        exit();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EventLogs  $eventLogs
     * @return \Illuminate\Http\Response
     */
    public function show(EventLogs $eventLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EventLogs  $eventLogs
     * @return \Illuminate\Http\Response
     */
    public function edit(EventLogs $eventLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EventLogs  $eventLogs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventLogs $eventLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EventLogs  $eventLogs
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventLogs $eventLogs)
    {
        //
    }

    public function export($id)
    {
        //dd($id);

       // $columns = $request->input('form_elements');
        $exporter = app()->makeWith(EventLogsExport::class , compact('id'));

        return Excel::download($exporter  ,'EventLogs.xlsx');
    }

}
