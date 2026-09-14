@extends('layouts.app')

@section('content')
<h2>Order Details (#{{ $order->id }})</h2>
<div class="card p-3 mt-3">
    <p><strong>User:</strong> {{ $order->user->name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>
    <p><strong>Date:</strong> {{ $order->created_at }}</p>
    
    <h4>Order Items</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price Each</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ $item->price }}</td>
                <td>${{ $item->quantity * $item->price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection