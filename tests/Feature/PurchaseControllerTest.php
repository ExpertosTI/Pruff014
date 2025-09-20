<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Client;
use App\Models\Placement;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_purchases()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        
        Purchase::factory()->count(3)->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id
        ]);
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/purchases');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'client_id', 'article_id', 'placement_id', 'quantity', 'unit_price', 'total_price', 'purchase_date']
                    ],
                    'links',
                    'meta' => [
                        'current_page',
                        'per_page',
                        'total'
                    ]
                ]);
    }

    public function test_can_create_purchase()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        
        $purchaseData = [
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 5,
            'unit_price' => 100.00
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/purchases', $purchaseData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'client_id', 'article_id', 'placement_id', 'quantity', 'unit_price', 'total_price']
                ]);
        
        $this->assertDatabaseHas('purchases', [
            'client_id' => $purchaseData['client_id'],
            'article_id' => $purchaseData['article_id'],
            'placement_id' => $purchaseData['placement_id'],
            'quantity' => 5,
            'unit_price' => 100.00,
            'total_price' => 500.00
        ]);
    }

    public function test_accumulates_purchases_for_same_client_article_placement()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        
        // Primera compra
        $firstPurchaseData = [
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 3,
            'unit_price' => 100.00
        ];

        $this->actingAs($user, 'sanctum')->postJson('/api/purchases', $firstPurchaseData);

        // Segunda compra con los mismos datos
        $secondPurchaseData = [
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 2,
            'unit_price' => 100.00
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/purchases', $secondPurchaseData);

        $response->assertStatus(201);
        
        // Debe haber solo una compra con cantidad acumulada
        $this->assertDatabaseCount('purchases', 1);
        $this->assertDatabaseHas('purchases', [
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 5, // 3 + 2
            'total_price' => 500.00 // 5 * 100
        ]);
    }

    public function test_validation_fails_when_creating_purchase_with_invalid_data()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/purchases', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['client_id', 'article_id', 'placement_id', 'quantity', 'unit_price']);
    }

    public function test_validation_fails_when_foreign_keys_do_not_exist()
    {
        $user = User::factory()->create();
        
        $purchaseData = [
            'client_id' => 999,
            'article_id' => 999,
            'placement_id' => 999,
            'quantity' => 5,
            'unit_price' => 100.00
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/purchases', $purchaseData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['client_id', 'article_id', 'placement_id']);
    }

    public function test_can_show_purchase()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        $purchase = Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/purchases/{$purchase->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'client_id', 'article_id', 'placement_id', 'quantity', 'unit_price', 'total_price', 'client', 'article', 'placement']
                ]);
    }

    public function test_returns_404_when_purchase_not_found()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/purchases/999');

        $response->assertStatus(404);
    }

    public function test_can_update_purchase()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        $purchase = Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 5,
            'unit_price' => 100.00,
            'total_price' => 500.00
        ]);
        
        $updateData = ['quantity' => 10];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/purchases/{$purchase->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id, 
            'quantity' => 10,
            'total_price' => 1000.00 // 10 * 100
        ]);
    }

    public function test_can_delete_purchase()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        $purchase = Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/purchases/{$purchase->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('purchases', ['id' => $purchase->id]);
    }

    public function test_can_filter_purchases_by_client_id()
    {
        $user = User::factory()->create();
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        
        Purchase::factory()->count(2)->create([
            'client_id' => $client1->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id
        ]);
        Purchase::factory()->create([
            'client_id' => $client2->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson("/api/purchases?client_id={$client1->id}");

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
        
        foreach ($response->json('data') as $purchase) {
            $this->assertEquals($client1->id, $purchase['client_id']);
        }
    }

    public function test_can_filter_purchases_by_date_range()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        
        Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'created_at' => '2024-01-15'
        ]);
        Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'created_at' => '2024-02-15'
        ]);
        Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'created_at' => '2024-03-15'
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/purchases?date_from=2024-02-01&date_to=2024-02-28');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
    }

    public function test_can_filter_purchases_by_quantity_minimum()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        
        Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 5
        ]);
        Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 15
        ]);
        Purchase::factory()->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id,
            'quantity' => 25
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/purchases?quantity_min=10');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
        
        foreach ($response->json('data') as $purchase) {
            $this->assertGreaterThanOrEqual(10, $purchase['quantity']);
        }
    }

    public function test_pagination_returns_15_items_per_page()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $article = Article::factory()->create();
        $placement = Placement::factory()->create(['article_id' => $article->id]);
        
        Purchase::factory()->count(20)->create([
            'client_id' => $client->id,
            'article_id' => $article->id,
            'placement_id' => $placement->id
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/purchases');

        $response->assertStatus(200)
                ->assertJsonPath('meta.per_page', 15)
                ->assertJsonCount(15, 'data');
    }
}