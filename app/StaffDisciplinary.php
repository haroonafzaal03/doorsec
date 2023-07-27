<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffDisciplinary extends Model
{
    //
    protected $fillable = [
        'staff_id',
        'letter_type',
        'document_name',
        'document_path',
        'admin_notes',
        'created_by',
        'is_deleted'
    ];
    public function satff(){
        return $this->belongsTo('App\Staff','staff_id');
    }
}
