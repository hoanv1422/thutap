<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTaskNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskUpdated $event)
    {
        if (!$event->task) {
            Log::error('TaskUpdated event has null task');
            return;
        }
    
        // Tiếp tục xử lý tạo/cập nhật thông báo...
        $oldNotification = Notification::where('task_id', $event->task->id)
            ->where('user_id', $event->userId)
            ->first();
    
        if ($oldNotification) {
            $oldNotification->update([
                'title'   => 'Task Updated',
                'message' => "Công việc mới cập nhật: " . $event->task->name,
                'type'    => 'info',
                'read'    => false,
            ]);
        } else {
            Notification::create([
                'task_id' => $event->task->id,
                'user_id' => $event->userId,
                'title'   => 'Task Updated',
                'message' => "Công việc mới cập nhật: " . $event->task->name,
                'type'    => 'info',
                'read'    => false,
            ]);
        }
    }
}
