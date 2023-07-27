<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    //
    protected $fillable = [
        'client_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'total_staff',
        'status',
        'venue_detail_id'
    ];

    public function getTotalStaffInVenue()
    {
        //return $this->hasMany('App\StaffSchedule')->where('')->count();
    }
    public function client(){
        return $this->belongsTo('App\Client','client_id');
    }
    public function satff_schdedule(){
        return $this->belongsTo('App\StaffSchedule','venue_id','id');
    }
    public function satff_schdedules(){
        return $this->hasMany('App\StaffSchedule','venue_id','id');
    }
}