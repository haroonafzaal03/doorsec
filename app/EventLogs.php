<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventLogs extends Model
{
    //
    protected $fillable = [
        'event_id',
        'name',
        'to',
        'action',
        'responded_by_in',
        'action_taken',
        'status',
        'time',
        'color_code'
    ];
}
