<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'discount_type', 'discount_value', 'max_discount', 'usage_limit', 'usage_count', 'valid_from', 'valid_until', 'applicable_workspaces', 'min_booking_amount', 'status'];
    protected $casts = ['applicable_workspaces' => 'array', 'valid_from' => 'datetime', 'valid_until' => 'datetime'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_discount');
    }
}
