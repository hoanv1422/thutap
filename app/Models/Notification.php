<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'task_id', 
        'message',
        'type',
        'details',
        'read'
        
    ];

    protected $casts = [
        'details' => 'array',
        'read' => 'boolean',
    ];
}
