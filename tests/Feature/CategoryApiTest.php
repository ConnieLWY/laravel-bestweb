<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;


    public function test_it_can_create_a_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $response = $this->postJson('/api/categories', [
            'name' => 'Test Category',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Category']);

        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_it_can_update_a_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);

        $response->assertOk()->assertJsonFragment(['name' => 'Updated Category']);
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    public function test_it_soft_deletes_a_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");
        $response->assertOk()->assertJson(['message' => 'Category soft-deleted']);

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_it_bulk_deletes_products()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $categories = Category::factory()->count(3)->create();
        $ids = $categories->pluck('id')->toArray();

        $response = $this->postJson('/api/categories/bulk-delete', ['ids' => $ids]);

        $response->assertOk()->assertJson(['message' => 'Bulk delete successful']);
        foreach ($ids as $id) {
            $this->assertSoftDeleted('categories', ['id' => $id]);
        }
    }
}
