<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Whatsapp extends Model
{
        protected $fillable = [
        'integrationId',
        'integrationName',
		'response',
		'message_id',
		'contact_number',
		'message_type',
		'sent',
		'receive',
		'staff_id',
		'message',
		'related_id',
		'event_id',
		'venue_id',
		'start_time',
		'start_date'
    ];
}
