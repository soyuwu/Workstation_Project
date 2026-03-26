<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    // Tắt timestamps mặc định vì chúng ta chỉ cần created_at
    public $timestamps = false;

    // Khai báo khóa chính là email và kiểu dữ liệu là string
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];
}
