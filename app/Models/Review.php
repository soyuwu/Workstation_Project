<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'content',
        'author_name',
        'author_role',
        'is_approved',
        'user_id',
        'booking_id',
        'workspace_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function adminReplies()
    {
        return $this->hasMany(AdminReply::class);
    }
}
