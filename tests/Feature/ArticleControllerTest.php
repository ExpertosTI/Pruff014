<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_articles()
    {
        $user = User::factory()->create();
        Article::factory()->count(3)->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/articles');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'barcode', 'description', 'manufacturer']
                    ],
                    'links',
                    'meta' => [
                        'current_page',
                        'per_page',
                        'total'
                    ]
                ]);
    }

    public function test_can_create_article()
    {
        $user = User::factory()->create();
        
        $articleData = [
            'barcode' => $this->faker->unique()->ean13,
            'description' => $this->faker->sentence,
            'manufacturer' => $this->faker->company
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/articles', $articleData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'barcode', 'description', 'manufacturer']
                ]);
        
        $this->assertDatabaseHas('articles', ['barcode' => $articleData['barcode']]);
    }

    public function test_validation_fails_when_creating_article_with_invalid_data()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/articles', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['barcode', 'description', 'manufacturer']);
    }

    public function test_can_show_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'barcode', 'description', 'manufacturer']
                ]);
    }

    public function test_returns_404_when_article_not_found()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/articles/999');

        $response->assertStatus(404);
    }

    public function test_can_update_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $updateData = ['description' => 'Updated Description'];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/articles/{$article->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', ['id' => $article->id, 'description' => 'Updated Description']);
    }

    public function test_can_delete_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_pagination_returns_15_items_per_page()
    {
        $user = User::factory()->create();
        Article::factory()->count(20)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/articles');

        $response->assertStatus(200)
                ->assertJsonPath('meta.per_page', 15)
                ->assertJsonCount(15, 'data');
    }

    public function test_can_filter_articles_by_manufacturer()
    {
        $user = User::factory()->create();
        Article::factory()->create(['manufacturer' => 'Sony']);
        Article::factory()->create(['manufacturer' => 'Samsung']);
        Article::factory()->create(['manufacturer' => 'Sony']);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/articles?manufacturer=Sony');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
        
        foreach ($response->json('data') as $article) {
            $this->assertEquals('Sony', $article['manufacturer']);
        }
    }

    public function test_can_filter_articles_by_description()
    {
        $user = User::factory()->create();
        Article::factory()->create(['description' => 'Smartphone Android']);
        Article::factory()->create(['description' => 'iPhone Apple']);
        Article::factory()->create(['description' => 'Tablet Android']);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/articles?description=Android');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
        
        foreach ($response->json('data') as $article) {
            $this->assertStringContainsString('Android', $article['description']);
        }
    }

    public function test_can_combine_filters()
    {
        $user = User::factory()->create();
        Article::factory()->create(['manufacturer' => 'Sony', 'description' => 'Smartphone Android']);
        Article::factory()->create(['manufacturer' => 'Samsung', 'description' => 'Smartphone Android']);
        Article::factory()->create(['manufacturer' => 'Sony', 'description' => 'iPhone Apple']);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/articles?manufacturer=Sony&description=Android');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
        
        $article = $response->json('data.0');
        $this->assertEquals('Sony', $article['manufacturer']);
        $this->assertStringContainsString('Android', $article['description']);
    }
}
