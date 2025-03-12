<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'deadline',
        'status',
        'user_id',
        'priority',
        'progress',
        'attachment',
        'notes',
    ];

    // Quan hệ với người dùng được phân công
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // Quan hệ với người tạo (owner)
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
