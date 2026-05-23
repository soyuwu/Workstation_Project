<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'room_type_id',
        'code',
        'name',
        'capacity',
        'description',
        'amenities',
        'price_per_hour',
        'price_per_month',
        'min_booking_hours',
        'status',
    ];
    protected $casts = ['amenities' => 'array']; // Ép kiểu JSON

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
    public function images()
    {
        return $this->hasMany(WorkspaceImage::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function availabilities()
    {
        return $this->hasMany(RoomAvailability::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
