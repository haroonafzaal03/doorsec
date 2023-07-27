<?php

namespace App\Exports;


use App\Event;
use App\StaffSchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Auth;

class TimeSheetExport implements FromView, ShouldAutoSize, WithColumnFormatting
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
        $schedule = Event::findorFail($this->event_id);
        $staff_schedule_details = "";
        $staff_schedule_details =  StaffSchedule::where('event_id', $this->event_id)->get();
        return view('club_event.event.staff_schedule_export', compact('schedule', 'staff_schedule_details'));
    }

    public function columnFormats(): array
    {
        return [
            //'D' => NumberFormat::FORMAT_NUMBER,
            'D15:D1000' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
