@extends('layouts.master')
@section('title', $product->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.shop') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image Gallery -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="product-gallery">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded-top main-image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="h2 mb-3">{{ $product->name }}</h1>

                    <!-- Price -->
                    <div class="mb-4">
                        @if($product->discount > 0)
                            <span class="text-muted text-decoration-line-through h5">${{ number_format($product->price, 2) }}</span>
                            <span class="text-danger h3 fw-bold ms-2">${{ number_format($product->discounted_price, 2) }}</span>
                            <span class="badge bg-danger ms-2">{{ $product->discount }}% OFF</span>
                        @else
                            <span class="h3 fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                        @endif
<<<<<<< HEAD
                        
                        @if(auth()->user() && auth()->user()->can('manage_discounts'))
                            <div class="mt-3">
                                <a href="{{ route('products.discount.edit', $product->slug) }}" 
                                   class="btn btn-warning">
                                    <i class="fas fa-percent me-2"></i>Add/Edit Discount
                                </a>
                            </div>
                        @endif
=======
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @if($product->stock > 0)
                            <span class="badge bg-success">In Stock</span>
                            <small class="text-muted ms-2">{{ $product->stock }} units available</small>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="mb-3">Description</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <h5 class="mb-3">Category</h5>
                        <a href="{{ route('products.shop', ['categories' => [$product->category_id]]) }}" 
                           class="text-decoration-none">
                            <span class="badge bg-light text-dark border">
                                {{ $product->category->name }}
                            </span>
                        </a>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        @if($product->stock > 0)
                            @auth
<<<<<<< HEAD
                                <button type="button" class="btn btn-primary btn-lg add-to-cart-btn" data-product-slug="{{ $product->slug }}">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
=======
                                <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                    </button>
                                </form>
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                                <button class="btn btn-outline-danger btn-lg wishlist-btn" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-heart me-2"></i>Add to Wishlist
                                </button>
                            @else
                                <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                                <button class="btn btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="fas fa-heart me-2"></i>Add to Wishlist
                                </button>
                            @endauth
                        @else
                            <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach($product->category->products()->where('id', '!=', $product->id)->where('is_active', true)->take(4)->get() as $relatedProduct)
            <div class="col">
                <div class="card h-100">
                    @if($relatedProduct->image)
                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        <p class="card-text">
                            <strong>Price: ${{ number_format($relatedProduct->price, 2) }}</strong>
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-outline-primary flex-grow-1">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                            @if($relatedProduct->stock > 0)
                                @auth
<<<<<<< HEAD
                                    <button type="button" class="btn btn-primary w-100 add-to-cart-btn" data-product-slug="{{ $relatedProduct->slug }}">
                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                    </button>
=======
                                    <form action="{{ route('cart.add', $relatedProduct->slug) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                        </button>
                                    </form>
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                                @else
                                    <button class="btn btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                    </button>
                                @endauth
                            @else
                                <button class="btn btn-secondary flex-grow-1" disabled>Out of Stock</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .product-gallery {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
    }

    .main-image {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }

    .product-gallery:hover .main-image {
        transform: scale(1.05);
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: var(--dark-color);
    }

    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .btn {
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<<<<<<< HEAD
<!-- Debug script only included for development, remove in production -->
{{-- <script src="{{ asset('js/cart-debug.js') }}"></script> --}}
<script>
    $(document).ready(function() {
        // Wishlist functionality
=======
<script>
    $(document).ready(function() {
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
        $('.wishlist-btn').on('click', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            const button = $(this);
            
            console.log('Wishlist button clicked for product:', productId);
            
            $.ajax({
                url: `/wishlist/toggle/${productId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Wishlist response:', response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
<<<<<<< HEAD
                            title: 'Success!',
=======
                            title: 'تم!',
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
<<<<<<< HEAD
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
=======
                        
                        // Update button appearance
                        if (response.in_wishlist) {
                            button.removeClass('btn-outline-danger').addClass('btn-danger');
                            button.html('<i class="fas fa-heart me-2"></i>Remove from Wishlist');
                        } else {
                            button.removeClass('btn-danger').addClass('btn-outline-danger');
                            button.html('<i class="fas fa-heart me-2"></i>Add to Wishlist');
                        }
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'تنبيه!',
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
<<<<<<< HEAD
                error: function(error) {
                    console.error('Wishlist error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error adding product to wishlist',
=======
                error: function(xhr, status, error) {
                    console.error('Wishlist error:', {xhr, status, error});
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء إضافة المنتج إلى المفضلة.',
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });
</script>
@endsection 