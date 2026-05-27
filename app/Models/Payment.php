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
        'reported_at',
        'payment_gateway',
        'gateway_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'reported_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
