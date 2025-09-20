<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_articles()
    {
        Article::factory()->count(3)->create();
        
        $response = $this->getJson('/api/articles');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'barcode', 'description', 'manufacturer']
                    ]
                ]);
    }

    public function test_can_create_article()
    {
        $articleData = [
            'barcode' => $this->faker->unique()->ean13,
            'description' => $this->faker->sentence,
            'manufacturer' => $this->faker->company
        ];

        $response = $this->postJson('/api/articles', $articleData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'barcode', 'description', 'manufacturer']
                ]);
        
        $this->assertDatabaseHas('articles', ['barcode' => $articleData['barcode']]);
    }

    public function test_validation_fails_when_creating_article_with_invalid_data()
    {
        $response = $this->postJson('/api/articles', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['barcode', 'description', 'manufacturer']);
    }

    public function test_can_show_article()
    {
        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'barcode', 'description', 'manufacturer']
                ]);
    }

    public function test_returns_404_when_article_not_found()
    {
        $response = $this->getJson('/api/articles/999');

        $response->assertStatus(404);
    }

    public function test_can_update_article()
    {
        $article = Article::factory()->create();
        $updateData = ['description' => 'Updated Description'];

        $response = $this->putJson("/api/articles/{$article->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', ['id' => $article->id, 'description' => 'Updated Description']);
    }

    public function test_can_delete_article()
    {
        $article = Article::factory()->create();

        $response = $this->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
