<?php

namespace App\Events;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Đổi từ protected sang public để dễ truy cập
    public $task;
    public $userId;

    public function __construct(Task $task, $userId)
    {
        // Lấy dữ liệu mới nhất từ DB
        $freshTask = $task->fresh();

        // Nếu fresh() trả về null, log cảnh báo và sử dụng task gốc
        if (!$freshTask) {
            Log::warning("Không thể lấy fresh task cho task id={$task->id}, sử dụng task gốc.");
            $this->task = $task;
        } else {
            $this->task = $freshTask;
        }

        $this->userId = $userId;
        Log::info('TaskUpdated event constructor: userId=' . $userId);
    }


    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'TaskUpdated';
    }

    public function broadcastWith()
    {
        if (is_null($this->task)) {
            Log::error('BroadcastWith error: Task is null');
            return [
                'error'     => "Task không tồn tại.",
                'title'     => 'Task Updated',
                'type'      => 'error',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        try {
            $taskData = $this->task->toArray();
        } catch (\Exception $e) {
            Log::error('Lỗi chuyển task sang array: ' . $e->getMessage());
            return [
                'error'     => 'Lỗi xử lý task',
                'title'     => 'Task Updated',
                'type'      => 'error',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        if (!empty($taskData['deadline']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $taskData['deadline'])) {
            $taskData['deadline'] .= ' 00:00:00';
        }

        $data = array_merge($taskData, [
            'task_id'   => $this->task->id,
            'message'   => "Công việc cập nhật: " . $this->task->name,
            'title'     => 'Task Updated',
            'type'      => 'info',
            'timestamp' => now()->toDateTimeString(),
        ]);

        Log::info('BroadcastWith data:', $data);
        return $data;
    }
}
