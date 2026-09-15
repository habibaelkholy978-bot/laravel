<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(5)->create();
        $categories = Category::factory(3)->create();

        foreach ($categories as $category) {
            $products = Product::factory(4)->create(['category_id' => $category->id]);

            foreach ($users as $user) {
                $order = Order::factory()->create(['user_id' => $user->id]);

                foreach ($products->random(2) as $product) {
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'price' => $product->price,
                    ]);
                }
            }
        }
    }
}