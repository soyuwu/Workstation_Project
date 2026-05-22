<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'icon',
        'badge',
        'tagline',
        'headline',
        'description',
        'description_2',
        'price',
        'price_unit',
        'capacity',
        'booking_type',
        'booking_desc',
        'hero_image',
        'detail_image',
        'audience_image',
        'features',
        'target_audience',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'target_audience' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope: chỉ lấy dịch vụ đang kích hoạt
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: sắp xếp theo sort_order
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Lấy route name cho booking dựa theo booking_type
     */
    public function getBookingRouteAttribute(): string
    {
        return $this->booking_type === 'monthly' ? 'booking.monthly' : 'booking.hourly';
    }
}
