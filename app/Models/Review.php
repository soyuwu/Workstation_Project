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
    ];

    public function adminReplies()
    {
        return $this->hasMany(AdminReply::class);
    }
}
