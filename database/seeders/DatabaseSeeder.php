<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin users
        \App\Models\Admin::factory(3)->create();

        // Create regular users
        \App\Models\User::factory(50)->create();

        // Create brands
        \App\Models\Brand::factory(10)->create();

        // Create parent categories
        CategoryFactory::$usedSlugs = [];

        // Create parent categories first
        $parents = \App\Models\Category::factory(5)->create();

        // Create child categories (3-5 per parent)
        $parents->each(function ($parent) {
            \App\Models\Category::factory(rand(3, 5))
                ->withParent()
                ->create();
        });

        // Create shipping methods
        \App\Models\ShippingMethod::factory(4)->create();

        // Create tags
        \App\Models\Tag::factory(20)->create();

        // Create products
        \App\Models\Product::factory(11)->create();

        // Create product images
        \App\Models\ProductImage::factory(300)->create();

        // Create product-category relationships
        foreach (\App\Models\Product::all() as $product) {
            $product->categories()->attach(
                \App\Models\Category::inRandomOrder()->limit(rand(1, 3))->pluck('id')->toArray()
            );
        }

        // Create product-tag relationships
        foreach (\App\Models\Product::all() as $product) {
            $product->tags()->attach(
                \App\Models\Tag::inRandomOrder()->limit(rand(1, 5))->pluck('id')->toArray()
            );
        }

        // Create coupons
        \App\Models\Coupon::factory(10)->create();

        // Create orders
        \App\Models\Order::factory(30)->create();

        // Create order items
        \App\Models\OrderItem::factory(100)->create();

        // Create payments
        \App\Models\Payment::factory(30)->create();

        // Create carts
        \App\Models\Cart::factory(50)->create();

        // Create wishlists
        \App\Models\Wishlist::factory(50)->create();

        // Create reviews
        \App\Models\Review::factory(80)->create();
    }
}
