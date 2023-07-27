<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffCertificate extends Model
{
    //
    protected $fillable =[
        'staff_id',
        'document_type',
        'document_name',
    ];
}