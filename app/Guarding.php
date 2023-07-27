<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guarding extends Model
{
    // protected $with=['guarding_schedule'];
    protected $fillable = [
        'client_id',
        'start_date',
        'end_date',
        'day_start_time',
        'day_end_time',
        'night_start_time',
        'night_end_time',
        'require_staff_day',
        'require_staff_night',
    ];
    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }
    public function staffschedule()
    {
        return $this->hasMany('App\StaffSchedule', 'schedule_id');
    }
    public function sira_type()
    {
        return $this->belongsTo('App\SiraType', 'assignment_type');
    }
    public function guarding_schedule()
    {
        return $this->hasMany('App\GudardingSchedule', 'guarding_id');
    }
}
