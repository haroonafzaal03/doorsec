<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Permissions extends Model
{
	use notifiable;
	

    protected $fillable = [
    'id',
    'name',
    'slug',
    'description',
	];
}
