<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        // Tự động xử lý số lượt sử dụng khi trạng thái thay đổi
        static::created(function ($booking) {
            if ($booking->status === 'confirmed' && $booking->id_discount) {
                $discount = \App\Models\DiscountCode::find($booking->id_discount);
                if ($discount) {
                    $discount->increment('usage_count');
                }
            }
        });

        static::updated(function ($booking) {
            if ($booking->isDirty('status')) {
                // Chuyển sang confirmed -> Tăng usage_count
                if ($booking->status === 'confirmed' && $booking->getOriginal('status') !== 'confirmed') {
                    if ($booking->id_discount) {
                        $discount = \App\Models\DiscountCode::find($booking->id_discount);
                        if ($discount) {
                            $discount->increment('usage_count');
                        }
                    }
                }
                // Hủy confirmed -> Giảm usage_count
                elseif ($booking->status !== 'confirmed' && $booking->getOriginal('status') === 'confirmed') {
                    if ($booking->id_discount) {
                        $discount = \App\Models\DiscountCode::find($booking->id_discount);
                        if ($discount && $discount->usage_count > 0) {
                            $discount->decrement('usage_count');
                        }
                    }
                }
            }
        });
    }

    protected $fillable = [
        'booking_code',
        'user_id',
        'workspace_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration_hours',
        'actual_check_in',
        'actual_check_out',
        'base_price',
        'surcharge',
        'tax',
        'id_discount',
        'total_amount',
        'status',
        'qr_code',
        'notes',
        'cancellation_reason',
        'cancelled_at',
        'cancel_fee_amount',
        'refund_amount',
        'refund_receiver_name',
        'refund_bank_name',
        'refund_bank_account_number',
        'cancellation_reason_code',
        'cancellation_reason_detail',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'actual_check_in' => 'datetime',
        'actual_check_out' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

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
