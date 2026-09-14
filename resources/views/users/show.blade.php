@extends('layouts.app')

@section('content')
<h2>Categories List</h2>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->description }}</td>
            <td><a href="{{ route('categories.show', $category->id) }}" class="btn btn-info btn-sm">Show</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection