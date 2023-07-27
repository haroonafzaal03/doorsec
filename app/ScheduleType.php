<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleType extends Model
{
    //
    protected $fillable = [
        'type',
        'status'
    ];
}
