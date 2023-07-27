<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    //
    protected $fillable = [
        'event_id',
        'venue_id',
        'schedule_id',
        'client_id',
        'shift_type',
        'assignment_type',
        'staff_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'day',
        'hours',
        'rate_per_hour',
        'availability',
        'is_payroll_active',
        'status',
        'sms_status',
        'is_deleted',
        'wa_response',
        'venue_detail_id',
		'updated_by',
		'message_id',
    ];

    //Each staff has many Schedule
    public function staff(){
        return $this->belongsTo('App\Staff', 'staff_id');
    }
    public function schedule_table(){
        return $this->belongsTo('App\Event', 'event_id');
    }
    //Each staff has many Schedule
    public function schedule()
    {
        return $this->hasMany('App\Staff'); //->where('status','active');
    }
    public function staff_data(){
        return $this->belongsTo('App\Staff','staff_id');
    }
    public function venue(){
        return $this->belongsTo('App\Venue','venue_id');
    }

    public function event(){
        return $this->belongsTo('App\Event', 'event_id');
    }

    public function sira_type(){
        return $this->belongsTo('App\SiraType','assignment_type');
    }

    public function guarding_schedule(){
        return $this->belongsTo('App\GudardingSchedule','schedule_id');
    }
}