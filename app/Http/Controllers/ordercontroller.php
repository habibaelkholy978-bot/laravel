<?php

namespace App\Http/Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('orders.create', compact('users', 'products'));
    }

    public function store(OrderRequest $request)
    {
        $order = Order::create(['user_id' => $request->user_id]);

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }

        return redirect()->route('orders.index');
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product.category'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        $users = User::all();
        $products = Product::all();
        return view('orders.edit', compact('order', 'users', 'products'));
    }

    public function update(OrderRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['user_id' => $request->user_id]);

        $order->orderItems()->delete();
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }

        return redirect()->route('orders.index');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->route('orders.index');
    }
}