<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    //
    protected $fillable = [
        'name',
        'staff_type_id',
        'contact_number',
        'basic_salary',
        'other_contact_number',
        'picture',
        'gender',
        'date_of_birth',
        'edu_document',
        'contact_number_home',
        'next_to_kin',
        'passport_number',
        'emitrates_id',
        'emirated_id_attach',
        'emirates_expiry',
        'noc_expiry',
        'noc_attach',
        'uid_number',
        'sira_id_number',
        'sira_id_attach',
        'sira_type_id',
        'passport_expiry',
        'passport_issue',
        'visa_expiry',
        'sponsor_details',
        'nationality',
        'height',
        'weight',
        'passport_attach',
        'visa_attach',
        'block_for_clients',
        'nk_name',
        'nk_relation',
        'nk_phone',
        'nk_address',
        'nk_nationality',
        'is_super_staff',
        'status',
        'sira_expiry',
        'reason',
        'email',
        'general_note'
    ];

    public function stafftypes(){
        return $this->belongsTo('App\StaffType','staff_type_id');
    }

    public function payroll(){
        return $this->hasMany('App\Payroll','staff_id');
    }
    public function disciplinary(){
        return $this->hasMany('App\StaffDisciplinary','staff_id')->where('is_deleted','=','0')->orderby('id','DESC');
    }
    public function staff_certificate(){
        return $this->hasMany('App\StaffCertificate','staff_id');
    }

}