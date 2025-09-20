<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_clients()
    {
        $user = User::factory()->create();
        Client::factory()->count(3)->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/clients');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'phone_number', 'client_type']
                    ]
                ]);
    }

    public function test_can_create_client()
    {
        $user = User::factory()->create();
        
        $clientData = [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'client_type' => 'regular'
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/clients', $clientData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'name', 'phone_number', 'client_type']
                ]);
        
        $this->assertDatabaseHas('clients', ['name' => $clientData['name']]);
    }

    public function test_validation_fails_when_creating_client_with_invalid_data()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/clients', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'phone_number', 'client_type']);
    }

    public function test_can_show_client()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/clients/{$client->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'name', 'phone_number', 'client_type']
                ]);
    }

    public function test_returns_404_when_client_not_found()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/clients/999');

        $response->assertStatus(404);
    }

    public function test_can_update_client()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $updateData = ['name' => 'Updated Client Name'];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/clients/{$client->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Updated Client Name']);
    }

    public function test_can_delete_client()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
