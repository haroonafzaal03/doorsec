<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventConfirmation extends Model
{
    protected $fillable = [
		'location',
		'arrival_time',
		'briefing',
		'venue',
		'location_guide',
		'dress_code',
		'start_date',
		'start_time',
		'date',
		'status',
		'staff_id',
		'event_id',
		'contact_number',
		'venue_id',
		'signingMeetingPt'
	];
	public function satff_schdeule(){
		return $this->belongsTo('App\StaffSchdeule');
	}
}
