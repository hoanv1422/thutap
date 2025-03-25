<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_register_user_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_logout_successfully()
    {
        $user = User::factory()->create();
        $token = $user->createToken('sanctum_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out']);
    }

    public function test_token_expires_middleware_returns_401_for_expired_token()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Tạo token thủ công bằng cách khởi tạo đối tượng và sử dụng save()
        $token = new \Laravel\Sanctum\PersonalAccessToken();
        $token->tokenable_id = $user->id;
        $token->tokenable_type = get_class($user);
        $token->name = 'Test Token';
        $token->token = hash('sha256', 'test-token');
        $token->abilities = ['*'];
        $token->created_at = \Carbon\Carbon::now()->subHours(2); // Giả lập token đã hết hạn (60 phút)
        $token->updated_at = \Carbon\Carbon::now()->subHours(2);
        $token->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer test-token',
        ])->getJson('/api/tasks');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'đăng nhập đã hết hạn, vui lòng đăng nhập lại.'
            ]);
    }
}
