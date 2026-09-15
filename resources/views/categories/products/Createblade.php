@extends('layouts.app')

@section('content')
<h2>Create Product</h2>

<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection