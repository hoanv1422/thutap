<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Events\TaskUpdated;
use App\Events\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        // Yêu cầu người dùng đăng nhập để truy cập các route trong controller này
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        // Lấy tất cả các task, kèm theo thông tin người tạo và các thành viên được phân công
        $tasks = Task::with(['owner', 'users'])->get();

        return response()->json([
            'success' => true,
            'data'    => $tasks,
        ]);
    }

    public function show($id)
    {
        // Cho phép xem task của bất kỳ user nào (không giới hạn theo user_id)
        $task = Task::with(['owner', 'users'])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $task
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'deadline'            => 'nullable|date',
            'status'              => 'required|in:pending,in_progress,completed,canceled',
            'assigned_user_ids'   => 'nullable|array',
            'assigned_user_ids.*' => 'exists:users,id',
            'priority'            => 'nullable|in:low,medium,high,urgent',
            'progress'            => 'nullable|integer|min:0|max:100',
            'notes'               => 'nullable|string',
        ]);

        // Lưu lại thông tin người tạo (đây vẫn là thông tin owner, tuy nhiên mọi người dùng đều được xem và chỉnh sửa)
        $validated['user_id'] = Auth::id();

        // Tạo task
        $task = Task::create($validated);

        // Nếu có danh sách người được phân công, cập nhật pivot và gửi event
        $assignedUserIds = $validated['assigned_user_ids'] ?? [];
        if (!empty($assignedUserIds)) {
            $task->users()->sync($assignedUserIds);

            // Gửi event TaskAssigned cho từng user được phân công
            foreach ($assignedUserIds as $userId) {
                event(new TaskAssigned($task, $userId));
            }
        }

        $task->load('users');
        return response()->json([
            'success' => true,
            'data'    => $task,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
    
        // Validate dữ liệu gửi lên
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'deadline'            => 'nullable|date',
            'status'              => 'required|in:pending,in_progress,completed,canceled',
            'assigned_user_ids'   => 'nullable|array',
            'assigned_user_ids.*' => 'exists:users,id',
            'priority'            => 'nullable|in:low,medium,high,urgent',
            'progress'            => 'nullable|integer|min:0|max:100',
            'notes'               => 'nullable|string',
        ], [
            'name.required'       => 'Tên công việc không được để trống.',
            'name.max'            => 'Tên công việc tối đa 255 ký tự.',
            'deadline.date'       => 'Ngày hết hạn không hợp lệ.',
            'status.in'           => 'Trạng thái không hợp lệ.',
            'assigned_user_ids.*.exists' => 'Một trong các thành viên được phân công không tồn tại.',
            'priority.in'         => 'Ưu tiên phải là: low, medium, high, urgent.',
            'progress.min'        => 'Tiến độ tối thiểu là 0%.',
            'progress.max'        => 'Tiến độ tối đa là 100%.',
        ]);
    
        // Cập nhật task
        $task->update($validated);
    
        // Đồng bộ danh sách thành viên được phân công nếu có dữ liệu
        if (array_key_exists('assigned_user_ids', $validated)) {
            $task->users()->sync($validated['assigned_user_ids'] ?? []);
            $task->load('users');
        }
    
        // Bắn event TaskUpdated cho từng user được phân công
        foreach ($task->users as $user) {
            try {
                event(new TaskUpdated($task, $user->id));
            } catch (\RuntimeException $e) {
                Log::error("Không gửi được sự kiện cho user {$user->id}: " . $e->getMessage());
            }
        }
    
        $task->load('users');
        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }
    


    public function destroy($id)
    {
        // Cho phép xóa task của bất kỳ ai
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $task->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
