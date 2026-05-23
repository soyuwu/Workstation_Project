<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'workspace_id',
        'image_url',
        'is_primary',
        'display_order',
        'created_at',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
    ];
}
