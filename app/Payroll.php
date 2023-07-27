<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    //
    protected $fillable = [
        'staff_sch_id',
        'event_id',
        'venue_id',
        'staff_id',
        'total_amount',
        'paid_amount',
        'pending_amount',
        'payment_status',
        'payment_date',
        'staff_status'
    ];

    public function staff(){
        return $this->belongsTo('App\Staff','staff_id');
    }
    public function event()
    {
        return $this->belongsTo('App\Event','event_id');
    }
    public function client(){
        return $this->belongsTo('App\Client','client_id');
    }
     public function venue(){
        return $this->belongsTo('App\Venue','venue_id');
    }
    public function payroll_log(){
        return $this->hasMany('App\PaymentLogs');
    }
    public function payroll(){
        return $this->belongsTo('App\Payroll');
    }
    public function staff_schedule(){
        return $this->hasMany('App\StaffSchedule','id');
    }
}