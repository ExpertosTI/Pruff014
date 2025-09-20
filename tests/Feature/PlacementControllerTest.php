<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Placement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlacementControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_placements()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        Placement::factory()->count(3)->create(['article_id' => $article->id]);
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/placements');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'article_id', 'name', 'price', 'location', 'created_at', 'updated_at']
                    ],
                    'links',
                    'meta' => [
                        'current_page',
                        'per_page',
                        'total'
                    ]
                ]);
    }

    public function test_can_create_placement()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        
        $placementData = [
            'article_id' => $article->id,
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'location' => $this->faker->address
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/placements', $placementData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'article_id', 'name', 'price', 'location']
                ]);
        
        $this->assertDatabaseHas('placements', [
            'article_id' => $placementData['article_id'],
            'name' => $placementData['name']
        ]);
    }

    public function test_validation_fails_when_creating_placement_with_invalid_data()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/placements', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['article_id', 'name', 'price', 'location']);
    }

    public function test_validation_fails_when_article_does_not_exist()
    {
        $user = User::factory()->create();
        
        $placementData = [
            'article_id' => 999,
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'location' => $this->faker->address
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/placements', $placementData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['article_id']);
    }

    public function test_can_show_placement()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/placements/{$placement->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'article_id', 'name', 'price', 'location', 'article']
                ]);
    }

    public function test_returns_404_when_placement_not_found()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/placements/999');

        $response->assertStatus(404);
    }

    public function test_can_update_placement()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        $updateData = ['name' => 'Updated Placement Name'];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/placements/{$placement->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('placements', [
            'id' => $placement->id, 
            'name' => 'Updated Placement Name'
        ]);
    }

    public function test_can_delete_placement()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/placements/{$placement->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('placements', ['id' => $placement->id]);
    }

    public function test_can_filter_placements_by_article_id()
    {
        $user = User::factory()->create();
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();
        
        Placement::factory()->count(2)->create(['article_id' => $article1->id]);
        Placement::factory()->create(['article_id' => $article2->id]);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson("/api/placements?article_id={$article1->id}");

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
        
        foreach ($response->json('data') as $placement) {
            $this->assertEquals($article1->id, $placement['article_id']);
        }
    }

    public function test_can_filter_placements_by_location()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        
        Placement::factory()->create(['article_id' => $article->id, 'location' => 'Warehouse A']);
        Placement::factory()->create(['article_id' => $article->id, 'location' => 'Warehouse B']);
        Placement::factory()->create(['article_id' => $article->id, 'location' => 'Warehouse A Section 1']);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/placements?location=Warehouse A');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
        
        foreach ($response->json('data') as $placement) {
            $this->assertStringContainsString('Warehouse A', $placement['location']);
        }
    }

    public function test_can_filter_placements_by_price_range()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        
        Placement::factory()->create(['article_id' => $article->id, 'price' => 50.00]);
        Placement::factory()->create(['article_id' => $article->id, 'price' => 150.00]);
        Placement::factory()->create(['article_id' => $article->id, 'price' => 250.00]);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/placements?price_min=100&price_max=200');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
        
        $placement = $response->json('data.0');
        $this->assertEquals(150.00, $placement['price']);
    }

    public function test_pagination_returns_15_items_per_page()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        Placement::factory()->count(20)->create(['article_id' => $article->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/placements');

        $response->assertStatus(200)
                ->assertJsonPath('meta.per_page', 15)
                ->assertJsonCount(15, 'data');
    }
}