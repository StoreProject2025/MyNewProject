@extends('layouts.master')

@section('title', 'My Wishlist')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Wishlist</h2>
            
            @if($wishlistItems->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-heart text-muted mb-3" style="font-size: 4rem;"></i>
                    <h3 class="text-muted">Your Wishlist is Empty</h3>
                    <p class="text-muted mb-4">Start adding items to your wishlist!</p>
                    <a href="{{ route('products.shop') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Browse Products
                    </a>
                </div>
            @else
                <div class="row">
                    @foreach($wishlistItems as $item)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <p class="card-text text-primary">${{ number_format($item->price, 2) }}</p>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-outline-primary btn-sm add-to-cart" data-product-id="{{ $item->id }}">
                                            <i class="fas fa-cart-plus me-1"></i>
                                            Add to Cart
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm remove-from-wishlist" data-product-id="{{ $item->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add to cart functionality
    $('.add-to-cart').click(function() {
        const productId = $(this).data('product-id');
        // Add your add to cart logic here
    });

    // Remove from wishlist functionality
    $('.remove-from-wishlist').click(function() {
        const productId = $(this).data('product-id');
        // Add your remove from wishlist logic here
    });
</script>
@endsection 