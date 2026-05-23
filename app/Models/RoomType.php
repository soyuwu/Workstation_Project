<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_capacity',
        'default_hourly_rate',
        'amenities',
    ];

    protected $casts = [
        'amenities' => 'array',
    ];
}
