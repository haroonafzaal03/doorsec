<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VenueDetail extends Model
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
    ];

    public function getTotalStaffInVenue()
    {
        //return $this->hasMany('App\StaffSchedule')->where('')->count();
    }
    public function client(){
        return $this->belongsTo('App\Client','client_id');
    }
    public function venues(){
        return $this->hasMany('App\Venue','venue_detail_id','id');
    }
    public function venue(){
        return $this->belongsTo('App\Venue','venue_detail_id','id');
    }
}