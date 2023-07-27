<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable =[
            'client_type_id',
            'property_name',
            'property_lice_name',
            'property_lice_number',
            'property_lice_expiry_date',
            'property_tax_regis_num',
            'property_signatory_id',
            'property_contract_start',
            'property_contract_end',
            'venue_manager_name',
            'venue_manager_number',
            'venue_manager_email',
            'account_manager_name',
            'account_manager_email',
            'account_manager_num',
            'client_address',
            'client_logo',
            'tarde_lice',
            'status'
    ];

    public function client_type(){
        return $this->belongsTo('App\ClientType','client_type_id');
    }

}
