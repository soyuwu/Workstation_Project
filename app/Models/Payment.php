<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'discount',
        'tax',
        'final_amount',
        'payment_method',
        'payment_status',
        'transaction_code',
        'transaction_reference',
        'paid_at',
        'payment_gateway',
        'gateway_response',
    ];
}
