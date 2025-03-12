<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Lấy danh sách công việc của người dùng
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->with(['assignedUser', 'owner', 'users', 'comments', 'attachments'])
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $tasks,
        ]);
    }

    // Lấy chi tiết công việc
    public function show($id)
    {
        $task = Task::where('user_id', Auth::id())
            ->with(['assignedUser', 'owner', 'users', 'comments', 'attachments'])
            ->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or unauthorized'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $task
        ]);
    }

    // Tạo công việc mới
public function store(Request $request)
{
    $validated = $request->validate([
        'name'                  => 'required|string|max:255',
        'description'           => 'nullable|string',
        'start_time'            => 'nullable|date|before_or_equal:deadline', 
        'deadline'              => 'nullable|date|after_or_equal:start_time', 
        'status'                => 'required|in:pending,in_progress,completed,canceled',
        'assigned_user_ids'     => 'nullable|array',
        'assigned_user_ids.*'   => 'exists:users,id',
        'priority'              => 'nullable|in:low,medium,high,urgent',
        'progress'              => 'nullable|integer|min:0|max:100',
        'attachment'            => 'nullable|string',
        'notes'                 => 'nullable|string',
    ], [
        'start_time.before_or_equal' => 'Thời gian bắt đầu phải bằng hoặc sớm hơn ngày hết hạn.',
        'deadline.after_or_equal'    => 'Ngày hết hạn phải bằng hoặc muộn hơn thời gian bắt đầu.',
    ]);

    // Gán user_id của người tạo công việc
    $validated['user_id'] = Auth::id();

    // Tạo mới record task với các dữ liệu còn lại
    $task = Task::create($validated);

    // Nếu có danh sách người được phân công, cập nhật bảng pivot task_user
    if (!empty($validated['assigned_user_ids'])) {
        $task->users()->sync($validated['assigned_user_ids']);
    }

    // Load lại mối quan hệ users để trả về trong response (nếu cần)
    $task->load('users');

    return response()->json([
        'success' => true,
        'data'    => $task,
    ], 201);
}

// Cập nhật công việc
public function update(Request $request, $id)
{
    $user = Auth::user();
    $task = Task::where('id', $id)
        ->where('user_id', $user->id)
        ->first();

    if (!$task) {
        return response()->json([
            'success' => false,
            'message' => 'Task not found or unauthorized'
        ], 404);
    }

    $validated = $request->validate([
        'name'                  => 'required|string|max:255',
        'description'           => 'nullable|string',
        'start_time'            => 'nullable|date|before_or_equal:deadline',
        'deadline'              => 'nullable|date|after_or_equal:start_time',
        'status'                => 'required|in:pending,in_progress,completed,canceled',
        'assigned_user_ids'     => 'nullable|array',
        'assigned_user_ids.*'   => 'exists:users,id',
        'priority'              => 'nullable|in:low,medium,high,urgent',
        'progress'              => 'nullable|integer|min:0|max:100',
        'attachment'            => 'nullable|string',
        'notes'                 => 'nullable|string',
    ], [
        'start_time.before_or_equal' => 'Thời gian bắt đầu phải bằng hoặc sớm hơn ngày hết hạn.',
        'deadline.after_or_equal'    => 'Ngày hết hạn phải bằng hoặc muộn hơn thời gian bắt đầu.',
    ]);

    // Cập nhật các trường trong tasks
    $task->update($validated);

    // Nếu key assigned_user_ids tồn tại, đồng bộ bảng pivot task_user
    if (array_key_exists('assigned_user_ids', $validated)) {
        $task->users()->sync($validated['assigned_user_ids'] ?? []);
    }

    // Load lại mối quan hệ users để trả về trong response (nếu cần)
    $task->load('users');

    return response()->json([
        'success' => true,
        'data'    => $task,
    ]);
}


    // Xóa công việc
    public function destroy($id)
    {
        $user = Auth::user();
        $task = Task::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or unauthorized'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
