@extends('layouts.app')

@section('content')
<h2>Products List</h2>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>${{ $product->price }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->category->name }}</td>
            <td><a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">Show</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection