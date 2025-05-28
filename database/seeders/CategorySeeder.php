<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Electronics Category
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'icon' => 'fa-laptop',
            'image' => 'categories/electronics.jpg',
            'is_active' => true,
            'order' => 1
        ]);

        $electronics->subcategories()->createMany([
            [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => 'Tablets',
                'slug' => 'tablets',
                'is_active' => true,
                'order' => 3
            ],
            [
                'name' => 'Accessories',
                'slug' => 'electronics-accessories',
                'is_active' => true,
                'order' => 4
            ]
        ]);

        // Clothing Category
        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'icon' => 'fa-tshirt',
            'image' => 'categories/clothing.jpg',
            'is_active' => true,
            'order' => 2
        ]);

        $clothing->subcategories()->createMany([
            [
                'name' => 'Men\'s Fashion',
                'slug' => 'mens-fashion',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'Women\'s Fashion',
                'slug' => 'womens-fashion',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => 'Kids\' Fashion',
                'slug' => 'kids-fashion',
                'is_active' => true,
                'order' => 3
            ],
            [
                'name' => 'Accessories',
                'slug' => 'clothing-accessories',
                'is_active' => true,
                'order' => 4
            ]
        ]);

        // Books Category
        $books = Category::create([
            'name' => 'Books',
            'slug' => 'books',
            'icon' => 'fa-book',
            'image' => 'categories/books.jpg',
            'is_active' => true,
            'order' => 3
        ]);

        $books->subcategories()->createMany([
            [
                'name' => 'Fiction',
                'slug' => 'fiction',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'Non-Fiction',
                'slug' => 'non-fiction',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => 'Educational',
                'slug' => 'educational',
                'is_active' => true,
                'order' => 3
            ],
            [
                'name' => 'Children\'s Books',
                'slug' => 'childrens-books',
                'is_active' => true,
                'order' => 4
            ]
        ]);
    }
}
