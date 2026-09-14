@extends('layouts.app')

@section('content')
<h2>Orders List</h2>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->created_at }}</td>
            <td><a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">Show</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection