<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    //
    protected $fillable = [
        'client_id',
        'event_name',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'total_staff',
        'contact_person',
        'contact_no',
        'event_type',
        'schedule_to',
        'schedule_from',
        'location',
        'status',
        'is_deleted'
    ];

    //Each Schdeule Belongs to a CLIENT
    public function client(){
        return $this->belongsTo('App\Client','client_id');
    }
    public function staffschedule(){
        return $this->hasMany('App\StaffSchedule');//->where('status','active');
    }
   
}
