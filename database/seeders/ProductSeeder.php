<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        $products = [
            [
                'name' => 'iPhone 14 Pro',
                'description' => 'Latest iPhone with advanced camera system and A16 Bionic chip',
                'price' => 999.99,
                'discount_price' => 899.99,
                'stock' => 50,
                'image' => 'products/iphone-14-pro.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra',
                'description' => 'Premium Android smartphone with S Pen and 200MP camera',
                'price' => 1199.99,
                'discount_price' => 1099.99,
                'stock' => 45,
                'image' => 'products/samsung-s23-ultra.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'MacBook Pro M2',
                'description' => 'Powerful laptop with M2 chip and stunning Retina display',
                'price' => 1999.99,
                'discount_price' => 1899.99,
                'stock' => 30,
                'image' => 'products/macbook-pro.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-cancelling headphones with exceptional sound quality',
                'price' => 399.99,
                'discount_price' => 349.99,
                'stock' => 60,
                'image' => 'products/sony-headphones.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'Nintendo Switch OLED',
                'description' => 'Gaming console with vibrant OLED screen and enhanced audio',
                'price' => 349.99,
                'discount_price' => 299.99,
                'stock' => 40,
                'image' => 'products/nintendo-switch.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'DJI Mini 3 Pro',
                'description' => 'Compact drone with 4K camera and intelligent flight modes',
                'price' => 759.99,
                'discount_price' => 699.99,
                'stock' => 25,
                'image' => 'products/dji-drone.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'iPad Pro M2',
                'description' => 'Powerful tablet with M2 chip and ProMotion display',
                'price' => 799.99,
                'discount_price' => 749.99,
                'stock' => 35,
                'image' => 'products/ipad-pro.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'Samsung 65" QLED TV',
                'description' => '4K Smart TV with Quantum Dot technology and HDR',
                'price' => 1499.99,
                'discount_price' => 1299.99,
                'stock' => 20,
                'image' => 'products/samsung-tv.jpg',
                'is_featured' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'discount_price' => $product['discount_price'],
                'discount_percentage' => round((($product['price'] - $product['discount_price']) / $product['price']) * 100),
                'stock' => $product['stock'],
                'category_id' => $categories->random()->id,
                'image' => $product['image'],
                'is_active' => true,
                'is_featured' => $product['is_featured'],
                'is_flash_sale' => rand(0, 1),
            ]);
        }
    }
}
