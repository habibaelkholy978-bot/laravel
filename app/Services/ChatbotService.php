<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * @return array{message: string, data: array<int, mixed>}
     */
    public function respond(User $user, string $message): array
    {
        $normalizedMessage = mb_strtolower(trim($message));

        $localResponse = $this->localResponse($user, $normalizedMessage);

        if (! filled(config('services.openai.key'))) {
            return $localResponse;
        }

        return $this->enhanceWithOpenAi($message, $localResponse);
    }

    /**
     * @return array{message: string, data: array<int, mixed>}
     */
    private function localResponse(User $user, string $message): array
    {
        if ($this->isOrderRequest($message)) {
            return $this->ordersResponse($user, $message);
        }

        if ($this->isCategoryRequest($message)) {
            return $this->categoriesResponse();
        }

        if ($this->isProductRequest($message)) {
            return $this->productsResponse();
        }

        return [
            'message' => 'I can help with products, categories, and your orders. Try: "show products" or "my orders".',
            'data' => [],
        ];
    }

    /**
     * @param  array{message: string, data: array<int, mixed>}  $localResponse
     * @return array{message: string, data: array<int, mixed>}
     */
    private function enhanceWithOpenAi(string $message, array $localResponse): array
    {
        try {
            $response = Http::acceptJson()
                ->withToken(config('services.openai.key'))
                ->timeout(30)
                ->post(rtrim(config('services.openai.url'), '/').'/chat/completions', [
                    'model' => config('services.openai.model'),
                    'temperature' => 0.2,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a store assistant. Answer in the language used by the customer. Use only the supplied store data, never invent prices, stock, categories, or orders. Do not reveal private data from another user.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $message."\n\nStore data:\n".json_encode($localResponse['data'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                        ],
                    ],
                ]);

            $generatedMessage = $response->json('choices.0.message.content');

            if ($response->successful() && is_string($generatedMessage) && filled(trim($generatedMessage))) {
                $localResponse['message'] = trim($generatedMessage);
            }
        } catch (ConnectionException|\JsonException $exception) {
            Log::warning('Chatbot provider request failed.', ['exception' => $exception]);
        }

        return $localResponse;
    }

    private function isOrderRequest(string $message): bool
    {
        return str_contains($message, 'order')
            || str_contains($message, 'orders')
            || str_contains($message, 'طلب')
            || str_contains($message, 'طلبات');
    }

    private function isCategoryRequest(string $message): bool
    {
        return str_contains($message, 'categor')
            || str_contains($message, 'تصنيف')
            || str_contains($message, 'تصنيفات');
    }

    private function isProductRequest(string $message): bool
    {
        return str_contains($message, 'product')
            || str_contains($message, 'منتج')
            || str_contains($message, 'منتجات')
            || str_contains($message, 'price')
            || str_contains($message, 'سعر')
            || str_contains($message, 'stock')
            || str_contains($message, 'مخزون');
    }

    /**
     * @return array{message: string, data: array<int, mixed>}
     */
    private function productsResponse(): array
    {
        $products = Product::query()
            ->with('category:id,name')
            ->latest()
            ->limit(20)
            ->get(['id', 'name', 'description', 'price', 'quantity', 'category_id']);

        return [
            'message' => $products->isEmpty() ? 'There are no products yet.' : 'Here are the available products.',
            'data' => $products->toArray(),
        ];
    }

    /**
     * @return array{message: string, data: array<int, mixed>}
     */
    private function categoriesResponse(): array
    {
        $categories = Category::query()
            ->withCount('products')
            ->latest()
            ->limit(20)
            ->get(['id', 'name', 'description']);

        return [
            'message' => $categories->isEmpty() ? 'There are no categories yet.' : 'Here are the available categories.',
            'data' => $categories->toArray(),
        ];
    }

    /**
     * @return array{message: string, data: array<int, mixed>}
     */
    private function ordersResponse(User $user, string $message): array
    {
        preg_match('/\b(?:order|طلب)\s*#?\s*(\d+)/iu', $message, $matches);

        $ordersQuery = Order::query()
            ->where('user_id', $user->id)
            ->with('orderItems.product:id,name')
            ->latest();

        if (isset($matches[1])) {
            $ordersQuery->whereKey((int) $matches[1]);
        } else {
            $ordersQuery->limit(20);
        }

        $orders = $ordersQuery->get(['id', 'user_id', 'created_at', 'updated_at']);

        return [
            'message' => $orders->isEmpty() ? 'No orders were found for your account.' : 'Here are your orders.',
            'data' => $orders->toArray(),
        ];
    }
}
