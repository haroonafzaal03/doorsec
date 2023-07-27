<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusLogs extends Model
{
    protected $fillable=[
		'modify_array',
		'message_id',
		'timestamp'
	];
}
