@extends('layouts.app')

@section('content')
<h2>Product Details</h2>
<div class="card p-3 mt-3">
    <p><strong>ID:</strong> {{ $product->id }}</p>
    <p><strong>Name:</strong> {{ $product->name }}</p>
    <p><strong>Description:</strong> {{ $product->description }}</p>
    <p><strong>Price:</strong> ${{ $product->price }}</p>
    <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
    <p><strong>Category:</strong> {{ $product->category->name }}</p>
</div>
<a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection