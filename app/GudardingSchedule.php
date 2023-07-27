<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GudardingSchedule extends Model
{
    //
    protected $fillable = [
        'schedule_id',
        'staff_id',
        'guarding_id',
        'day',
        'night',
        'afternoon',
        'late_day',
        'evening',
        'absent',
        'sick_leave',
        'annual_leave',
        'emergency_leave',
        'unpaid_leave',
        'day_off',
        'off_working_night',
        'off_working_day',
        'training',
        'overtime',
        'event_day',
        'public_holiday',
    ];
    public function staffschedule()
    {
        return $this->belongsTo('App\StaffSchedule', 'schedule_id');
    }




    // protected $casts = [
    //     'day' => 'array',
    //     'night' => 'array',
    //     'afternoon' => 'array',
    //     'late_day' => 'array',
    //     'evening' => 'array',
    //     'absent' => 'array',
    //     'sick_leave' => 'array',
    //     'annual_leave' => 'array',
    //     'emergency_leave' => 'array',
    //     'unpaid_leave' => 'array',
    //     'day_off' => 'array',
    //     'off_working_night' => 'array',
    //     'off_working_day' => 'array',
    //     'training' => 'array',
    //     'overtime' => 'array',
    //     'event_day' => 'array',
    //     'public_holiday'=> 'array',
    // ];
}
