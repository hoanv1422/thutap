<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Task extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'deadline',
        'status',
        'user_id',
        'priority',
        'progress',
        'attachment',
        'notes',
        
    ];


    // Quan hệ với người tạo 
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ nhiều-nhiều: các thành viên tham gia công việc
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    // Quan hệ với bình luận
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    // Quan hệ với file đính kèm
    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }
}
