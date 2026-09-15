@extends('layouts.app')

@section('content')
<h2>Category: {{ $category->name }}</h2>
<p>{{ $category->description }}</p>

<h4 class="mt-4">Products in this Category</h4>
<table class="table table-bordered mb-4">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @forelse($category->products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></td>
            <td>${{ $product->price }}</td>
            <td>{{ $product->quantity }}</td>
        </tr>
        @empty
        <tr><td colspan="4">No products found.</td></tr>
        @endforelse
    </tbody>
</table>

<h4>Orders Related to this Category</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Product Purchased</th>
        </tr>
    </thead>
    <tbody>
        @php $hasOrders = false; @endphp
        @foreach($category->products as $product)
            @foreach($product->orderItems as $item)
                @php $hasOrders = true; @endphp
                <tr>
                    <td><a href="{{ route('orders.show', $item->order->id) }}">Order #{{ $item->order->id }}</a></td>
                    <td>{{ $item->order->user->name }}</td>
                    <td>{{ $product->name }} (x{{ $item->quantity }})</td>
                </tr>
            @endforeach
        @endforeach
        @if(!$hasOrders)
            <tr><td colspan="3">No orders found for products in this category.</td></tr>
        @endif
    </tbody>
</table>

<a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection