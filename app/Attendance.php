<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    protected $fillable = [
        'event_id',
        'venue_id',
        'staff_sch_id',
        'staff_id',
        'is_marked'
    ];
}
