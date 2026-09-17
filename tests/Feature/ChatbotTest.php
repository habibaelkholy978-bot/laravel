<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('chatbot returns products for an authenticated user', function () {
    $user = User::factory()->create();
    $category = Category::create(['name' => 'Books', 'description' => 'Reading']);
    $product = Product::create([
        'name' => 'Laravel Book',
        'description' => 'A practical guide',
        'price' => 25.00,
        'quantity' => 4,
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/chatbot', ['message' => 'show products']);

    $response->assertOk()
        ->assertJsonPath('data.0.id', $product->id)
        ->assertJsonPath('data.0.category.id', $category->id);
});

test('chatbot only returns orders belonging to the authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $category = Category::create(['name' => 'Books']);
    $product = Product::create([
        'name' => 'Laravel Book',
        'price' => 25.00,
        'quantity' => 4,
        'category_id' => $category->id,
    ]);
    $userOrder = Order::create(['user_id' => $user->id]);
    $otherOrder = Order::create(['user_id' => $otherUser->id]);
    OrderItem::create([
        'order_id' => $userOrder->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => 25.00,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/chatbot', ['message' => 'my orders']);

    $response->assertOk()
        ->assertJsonPath('data.0.id', $userOrder->id)
        ->assertJsonMissing(['id' => $otherOrder->id]);
});

test('chatbot validates the message', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/chatbot', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('message');
});

test('chatbot uses the configured openai api key to enhance the response', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://api.openai.com/v1/chat/completions' => Http::response([
            'choices' => [
                ['message' => ['content' => 'Here are your products.']],
            ],
        ]),
    ]);
    config(['services.openai.key' => 'test-api-key']);

    $user = User::factory()->create();
    Category::create(['name' => 'Books']);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/chatbot', ['message' => 'show products']);

    $response->assertOk()->assertJsonPath('message', 'Here are your products.');
    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer test-api-key')
            && $request['model'] === 'gpt-4o-mini';
    });
});
