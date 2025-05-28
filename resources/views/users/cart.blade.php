@extends('layouts.master')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <div class="alert alert-info">
            <h4 class="alert-heading">Your Cart is Empty</h4>
            <p>Start adding items to your cart!</p>
            <hr>
            <a href="{{ route('products.shop') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-2">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="img-fluid rounded">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h5 class="mb-1">{{ $item->product->name }}</h5>
                                    <p class="text-muted mb-0">${{ number_format($item->product->price, 2) }}</p>
                                </div>
                                <div class="col-md-3">
                                    <form action="{{ route('cart.update', $item->product->slug) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               class="form-control form-control-sm" 
                                               style="width: 70px">
                                        <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-0 fw-bold">${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                                </div>
                                <div class="col-md-1">
                                    <form action="{{ route('cart.remove', $item->product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span class="fw-bold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5">Total</span>
                            <span class="h5">${{ number_format($total, 2) }}</span>
                        </div>
                        <form action="{{ route('cart.clear') }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Clear Cart
                            </button>
                        </form>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.25);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style> 