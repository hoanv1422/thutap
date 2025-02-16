<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_user() {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_fillable() {
        $user = new User();
        $fillable = ['name', 'email', 'password'];
        $this->assertEquals($fillable, $user->getFillable());
    }

    public function test_hidden_fields() {
        $user = User::factory()->make();
        $this->assertArrayNotHasKey('password', $user->toArray());
    }
}
