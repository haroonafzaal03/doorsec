<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLogs extends Model
{
    //
    protected $fillable = [
        'payroll_id',
        'paid_amount',
        'pending_amount',
        'payment_date',
        'payment_status'
    ];

    public function payroll_log(){
        return $this->belongsTo('App\PaymentLogs','payroll_id');
    }
}
