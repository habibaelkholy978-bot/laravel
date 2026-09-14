
@extends('layouts.app')

@section('content')
<h2>Users List</h2>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td><a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">Show</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection