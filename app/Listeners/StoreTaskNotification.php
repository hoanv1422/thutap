<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Models\Notification;

class StoreTaskNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskAssigned  $event
     * @return void
     */
    public function handle(TaskAssigned $event)
    {
        Notification::create([
           'user_id' => $event->userId,
           'task_id' => $event->task->id, 
           'title'   => 'Task Assigned',
           'message' => "Bạn có task mới: " . $event->task->name,
           'type'    => 'info',
           'details' => [
              'description' => $event->task->description,
              'deadline'    => $event->task->deadline,
              'priority'    => $event->task->priority,
              'progress'    => $event->task->progress,
              'notes'       => $event->task->notes,
           ],
           'read'    => false,
        ]);
    }
    
}
