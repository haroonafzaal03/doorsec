<?php

namespace App\Exports;

use App\Event;
use App\EventLogs;
use App\Staff;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;


class EventLogsExport implements FromView
{
    /**
    * @return \Illuminate\Support\FromView
    */

    public function __construct($id)
    {
        //dd($id);
        $this->event_id = $id;
    }

    public function view(): View
    {
        $userid = Auth::user()->id;
        $event_logs_list =  EventLogs::where('event_id',$this->event_id)->get();
        $event_logs_list = $event_logs_list->reverse();

        $eventData = Event::findorFail($this->event_id);

        return view('club_event.event.event_logs_export', compact('event_logs_list','eventData'));
    }
}
