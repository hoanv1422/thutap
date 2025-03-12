<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    // Lấy danh sách công việc của người dùng
    public function test_get_tasks()
    {
        $user = User::factory()->create();
        // Tạo 2 task thuộc về user
        Task::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    [
                        'id',
                        'name',
                        'description',
                        'deadline',
                        'status',
                        'user_id',
                        'created_at',
                        'updated_at',
                        'users'  // thay vì assigned_user_id
                    ]
                ]
            ]);
    }

    // Tạo công việc mới
    public function test_create_task_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $taskData = [
            'name'              => 'New Task',
            'description'       => 'Task description',
            'deadline'          => now()->addDays(7)->toDateString(),
            'assigned_user_ids' => [], // sử dụng mảng cho nhiều người được phân công
            'status'            => 'pending',
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'deadline',
                    'status',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'users'  // mảng người được phân công
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'name'    => 'New Task',
            'user_id' => $user->id,
        ]);
    }

    // Cập nhật công việc
    public function test_update_task_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'user_id'     => $user->id,
            'name'        => 'Old Task',
            'description' => 'Old description',
            'deadline'    => now()->addDays(3)->toDateString(),
            'status'      => 'pending',
        ]);

        $updatedData = [
            'name'              => 'Updated Task',
            'description'       => 'Updated description',
            'deadline'          => now()->addDays(5)->toDateString(),
            'status'            => 'in_progress',
            'assigned_user_ids' => [], // Nếu không thay đổi danh sách người được phân công
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'deadline',
                    'status',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'users'
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'id'     => $task->id,
            'name'   => 'Updated Task',
            'status' => 'in_progress',
        ]);
    }

    // Xóa công việc
    public function test_delete_task_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
