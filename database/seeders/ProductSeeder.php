<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'category_id' => 1,
                'description' => 'Apple phone',
                'price' => 4899.00,
                'stock' => 15,
                'enabled' => true,
            ],
            [
                'name' => 'Samsung Galaxy S23',
                'category_id' => 1,
                'description' => 'Flagship Android phone',
                'price' => 4299.00,
                'stock' => 10,
                'enabled' => true,
            ],
            [
                'name' => 'The Art of War',
                'category_id' => 2,
                'description' => 'Classic military strategy book',
                'price' => 39.90,
                'stock' => 50,
                'enabled' => true,
            ],
            [
                'name' => 'Men\'s Leather Jacket',
                'category_id' => 3,
                'description' => 'Genuine leather for winter',
                'price' => 499.00,
                'stock' => 8,
                'enabled' => false,
            ],
            [
                'name' => 'Lego Classic Set',
                'category_id' => 4,
                'description' => 'Creative building blocks for kids',
                'price' => 119.00,
                'stock' => 30,
                'enabled' => true,
            ],
            [
                'name' => 'MacBook Air M2',
                'category_id' => 1,
                'description' => 'Apple laptop with M2 chip',
                'price' => 5999.00,
                'stock' => 5,
                'enabled' => true,
            ],
            [
                'name' => 'Dell XPS 13',
                'category_id' => 1,
                'description' => 'Premium Windows ultrabook',
                'price' => 5299.00,
                'stock' => 7,
                'enabled' => true,
            ],
            [
                'name' => 'Atomic Habits',
                'category_id' => 2,
                'description' => 'Self-improvement book',
                'price' => 45.00,
                'stock' => 20,
                'enabled' => true,
            ],
            [
                'name' => 'Nike Air Max',
                'category_id' => 3,
                'description' => 'Popular running shoes',
                'price' => 329.00,
                'stock' => 12,
                'enabled' => true,
            ],
            [
                'name' => 'Barbie Dreamhouse',
                'category_id' => 4,
                'description' => 'Deluxe dollhouse playset',
                'price' => 699.00,
                'stock' => 6,
                'enabled' => true,
            ],
            [
                'name' => 'iPad 10th Gen',
                'category_id' => 1,
                'description' => 'Latest Apple tablet',
                'price' => 2399.00,
                'stock' => 9,
                'enabled' => true,
            ],
            [
                'name' => 'HP Pavilion Gaming Laptop',
                'category_id' => 1,
                'description' => 'Mid-range gaming laptop',
                'price' => 3499.00,
                'stock' => 4,
                'enabled' => true,
            ],
            [
                'name' => 'Rich Dad Poor Dad',
                'category_id' => 2,
                'description' => 'Personal finance bestseller',
                'price' => 59.00,
                'stock' => 40,
                'enabled' => true,
            ],
            [
                'name' => 'Uniqlo Down Jacket',
                'category_id' => 3,
                'description' => 'Affordable winter wear',
                'price' => 199.00,
                'stock' => 18,
                'enabled' => true,
            ],
            [
                'name' => 'Hot Wheels Track Set',
                'category_id' => 4,
                'description' => 'Fun toy race track for kids',
                'price' => 89.00,
                'stock' => 25,
                'enabled' => true,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category_id' => 1,
                'description' => 'Noise-cancelling headphones',
                'price' => 1499.00,
                'stock' => 13,
                'enabled' => true,
            ],
            [
                'name' => 'Lenovo IdeaPad 3',
                'category_id' => 1,
                'description' => 'Affordable student laptop',
                'price' => 1899.00,
                'stock' => 16,
                'enabled' => true,
            ],
            [
                'name' => 'The Alchemist',
                'category_id' => 2,
                'description' => 'Inspirational novel by Paulo Coelho',
                'price' => 35.90,
                'stock' => 60,
                'enabled' => true,
            ],
            [
                'name' => 'Adidas Running Shorts',
                'category_id' => 3,
                'description' => 'Lightweight sportswear',
                'price' => 99.00,
                'stock' => 22,
                'enabled' => true,
            ],
            [
                'name' => 'NERF Elite Blaster',
                'category_id' => 4,
                'description' => 'Safe foam dart gun',
                'price' => 129.00,
                'stock' => 10,
                'enabled' => true,
            ],
            [
                'name' => 'Apple Watch Series 9',
                'category_id' => 1,
                'description' => 'Smartwatch with health tracking',
                'price' => 2099.00,
                'stock' => 14,
                'enabled' => true,
            ],
            [
                'name' => 'Think and Grow Rich',
                'category_id' => 2,
                'description' => 'Motivational classic',
                'price' => 42.00,
                'stock' => 33,
                'enabled' => true,
            ],
            [
                'name' => 'Zara Men\'s Blazer',
                'category_id' => 3,
                'description' => 'Casual smart outfit',
                'price' => 279.00,
                'stock' => 11,
                'enabled' => true,
            ],
            [
                'name' => 'Play-Doh Set',
                'category_id' => 4,
                'description' => 'Creative dough for children',
                'price' => 69.00,
                'stock' => 35,
                'enabled' => true,
            ],
            [
                'name' => 'Kindle Paperwhite',
                'category_id' => 1,
                'description' => 'E-reader for book lovers',
                'price' => 649.00,
                'stock' => 10,
                'enabled' => true,
            ],
        ];
        

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
