<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['booking_code', 'user_id', 'workspace_id', 'booking_date', 'start_time', 'end_time', 'duration_hours', 'actual_check_in', 'actual_check_out', 'base_price', 'surcharge', 'tax', 'id_discount', 'total_amount', 'status', 'qr_code', 'notes', 'cancellation_reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class, 'id_discount');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    } // Quan hệ 1-1
    public function review()
    {
        return $this->hasOne(Review::class);
    } // Quan hệ 1-1
}
