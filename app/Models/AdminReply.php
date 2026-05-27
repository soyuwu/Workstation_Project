<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminReply extends Model
{
    protected $fillable = [
        'review_id',
        'admin_id',
        'reply_text',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
