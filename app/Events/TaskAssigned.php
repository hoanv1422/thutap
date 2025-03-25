<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $userId;

    public function __construct(Task $task, $userId)
    {
        $this->task = $task;
        $this->userId = $userId;

        Log::info('TaskAssigned event constructor: userId=' . $userId);
    }

    public function broadcastOn()
    {
        Log::info('TaskAssigned broadcastOn: userId=' . $this->userId);
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'TaskAssigned';
    }
    public function broadcastWith()
    {
        $data = [
            'task' => $this->task->toArray(),
            'message' => "Bạn có Công việc  mới: {$this->task->name}",
            'title'   => 'Task Assigned',
            'type'    => 'info',
            'timestamp' => now()->toDateTimeString(),
            'details' => [
                'description' => $this->task->description,
                'deadline' => $this->task->deadline 
                    ? $this->task->deadline->toDateTimeString()
                    : null,
                'priority' => $this->task->priority,
                'progress' => $this->task->progress,
                'notes' => $this->task->notes,
            ]
        ];
        Log::info('BroadcastWith data:', $data);
        return $data;
    }
    
    
    
}
