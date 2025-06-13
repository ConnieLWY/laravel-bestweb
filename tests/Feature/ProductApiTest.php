<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;


    public function test_it_can_create_a_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'category_id' => $category->id,
            'description' => 'Sample description',
            'price' => 99.99,
            'stock' => 10,
            'enabled' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Product']);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_it_can_update_a_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'category_id' => $product->category_id,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'enabled' => $product->enabled,
        ]);

        $response->assertOk()->assertJsonFragment(['name' => 'Updated Product']);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    public function test_it_soft_deletes_a_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertOk()->assertJson(['message' => 'Product soft-deleted']);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_it_bulk_deletes_products()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $response = $this->postJson('/api/products/bulk-delete', ['ids' => $ids]);

        $response->assertOk()->assertJson(['message' => 'Bulk delete successful']);
        foreach ($ids as $id) {
            $this->assertSoftDeleted('products', ['id' => $id]);
        }
    }
}
