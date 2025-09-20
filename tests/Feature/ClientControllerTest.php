<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_clients()
    {
        Client::factory()->count(3)->create();
        
        $response = $this->getJson('/api/clients');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'phone_number', 'client_type']
                    ]
                ]);
    }

    public function test_can_create_client()
    {
        $clientData = [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'client_type' => 'regular'
        ];

        $response = $this->postJson('/api/clients', $clientData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'name', 'phone_number', 'client_type']
                ]);
        
        $this->assertDatabaseHas('clients', ['name' => $clientData['name']]);
    }

    public function test_validation_fails_when_creating_client_with_invalid_data()
    {
        $response = $this->postJson('/api/clients', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'phone_number', 'client_type']);
    }

    public function test_can_show_client()
    {
        $client = Client::factory()->create();

        $response = $this->getJson("/api/clients/{$client->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'name', 'phone_number', 'client_type']
                ]);
    }

    public function test_returns_404_when_client_not_found()
    {
        $response = $this->getJson('/api/clients/999');

        $response->assertStatus(404);
    }

    public function test_can_update_client()
    {
        $client = Client::factory()->create();
        $updateData = ['name' => 'Updated Client Name'];

        $response = $this->putJson("/api/clients/{$client->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Updated Client Name']);
    }

    public function test_can_delete_client()
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
