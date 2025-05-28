@extends('layouts.master')
@section('title', 'My Orders')
@section('content')
    <div class="container mt-4">
        <h2>My Orders</h2>
        @if($orders->isEmpty())
            <div class="alert alert-info mt-3">You haven't placed any orders yet.</div>
        @else
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>{{ $order->product }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>
                                <span class="badge bg-primary">Processing</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info">View Details</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection 