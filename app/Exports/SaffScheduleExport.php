<?php

namespace App\Exports;

//use App\ExportStaffSchedule;
use App\Staff;
use App\Event;
use App\StaffSchedule;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;


class SaffScheduleExport implements FromView
{
    /**
     * @return \Illuminate\Support\FromView
     */

    public function __construct($columns, $id)
    {
        //dd($id);
        $this->columns = $columns;
        $this->event_id = $id;
    }

    public function view(): View
    {
        $userid = Auth::user()->id;

        $tableColumns = array();
        $new =  $this->columns;
        $i = 0;

        foreach ($this->columns as $key => $row) {
            $tableColumns[$key] = $key;
            $new[$i] = $key;
            $i++;
        }
        $schedule = Event::findorFail($this->event_id);

        $staff_schedule_details = "";
        $staff_schedule_details =  StaffSchedule::where('event_id', $this->event_id)->get();
        return view('club_event.event.event_schedule_export', compact('tableColumns', 'schedule', 'staff_schedule_details'));
    }
}
