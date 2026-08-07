<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password'), 'is_admin' => true]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Customer User', 'password' => Hash::make('password'), 'is_admin' => false]
        );

        $categories = collect([
            ['name' => 'Electronics', 'description' => 'Phones, audio, and useful tech accessories.'],
            ['name' => 'Fashion', 'description' => 'Everyday apparel and accessories.'],
            ['name' => 'Home', 'description' => 'Practical home and living products.'],
        ])->mapWithKeys(function (array $category) {
            $model = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category + ['is_active' => true]
            );

            return [$model->slug => $model];
        });

        $products = [
            ['category' => 'electronics', 'name' => 'Wireless Headphones', 'price' => 89.99, 'stock' => 30],
            ['category' => 'electronics', 'name' => 'Smart Watch', 'price' => 149.99, 'stock' => 18],
            ['category' => 'fashion', 'name' => 'Classic Backpack', 'price' => 44.50, 'stock' => 42],
            ['category' => 'fashion', 'name' => 'Cotton Hoodie', 'price' => 39.99, 'stock' => 25],
            ['category' => 'home', 'name' => 'Desk Lamp', 'price' => 27.99, 'stock' => 35],
            ['category' => 'home', 'name' => 'Ceramic Mug Set', 'price' => 19.99, 'stock' => 50],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'category_id' => $categories[$product['category']]->id,
                    'name' => $product['name'],
                    'description' => 'A reliable '.$product['name'].' for everyday shopping demos.',
                    'image' => null,
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'is_active' => true,
                ]
            );
        }
    }
}
