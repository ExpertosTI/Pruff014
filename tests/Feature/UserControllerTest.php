<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_users()
    {
        $user = User::factory()->create();
        User::factory()->count(3)->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/users');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'email', 'cedula', 'phone_number', 'blood_type']
                    ]
                ]);
    }

    public function test_can_create_user()
    {
        $user = User::factory()->create();
        
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'cedula' => $this->faker->numerify('###########'),
            'phone_number' => $this->faker->phoneNumber,
            'blood_type' => 'O+'
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'name', 'email', 'cedula', 'phone_number', 'blood_type']
                ]);
        
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function test_validation_fails_when_creating_user_with_invalid_data()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/users', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password', 'cedula']);
    }

    public function test_can_show_user()
    {
        $authUser = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($authUser, 'sanctum')->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'name', 'email', 'cedula', 'phone_number', 'blood_type']
                ]);
    }

    public function test_returns_404_when_user_not_found()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/users/999');

        $response->assertStatus(404);
    }

    public function test_can_update_user()
    {
        $authUser = User::factory()->create();
        $user = User::factory()->create();
        $updateData = ['name' => 'Updated Name'];

        $response = $this->actingAs($authUser, 'sanctum')->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_user()
    {
        $authUser = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($authUser, 'sanctum')->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
