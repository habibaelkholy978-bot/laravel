@extends('layouts.app')

@section('content')
<h2>Category Details</h2>
<div class="card p-3 mt-3">
    <p><strong>ID:</strong> {{ $category->id }}</p>
    <p><strong>Name:</strong> {{ $category->name }}</p>
    <p><strong>Description:</strong> {{ $category->description }}</p>
    
    <h4>Products in this Category</h4>
    <ul>
        @forelse($category->products as $product)
            <li><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a> - ${{ $product->price }}</li>
        @empty
            <li>No products found.</li>
        @endforelse
    </ul>
</div>
<a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection