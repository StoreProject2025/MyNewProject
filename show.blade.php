@extends('layouts.master')
@section('title', $category->name)

@section('content')
<div class="container py-5">
    <div class="category-header mb-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-3">{{ $category->name }}</h1>
                <p class="text-muted">{{ $category->description }}</p>
            </div>
            <div class="col-md-4">
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <div class="products-grid">
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-md-3">
                <div class="card product-card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="product-actions">
                            @auth
                                <button class="btn btn-light btn-sm rounded-circle add-to-wishlist" data-product-id="{{ $product->id }}" title="Add to Wishlist">
                                    <i class="fas fa-heart"></i>
                                </button>
                            @else
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#loginModal" title="Add to Wishlist">
                                    <i class="fas fa-heart"></i>
                                </button>
                            @endauth
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-light btn-sm rounded-circle" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        @if($product->sale_price)
                        <div class="discount-badge bg-danger text-white position-absolute top-0 start-0 m-2 px-2 py-1 rounded">
                            -{{ $product->discount_percentage }}%
                        </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="card-title mb-1">{{ $product->name }}</h5>
                                <p class="card-text text-muted small mb-0">{{ $category->name }}</p>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    @if($product->sale_price)
                                    <span class="price h5 mb-0">${{ number_format($product->sale_price, 2) }}</span>
                                    <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                    @else
                                    <span class="price h5 mb-0">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            @auth
                                <button class="btn btn-primary w-100 add-to-cart" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            @else
                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No products found in this category.
                </div>
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.category-header {
    background: var(--light-bg);
    padding: 2rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.product-card {
    transition: transform 0.3s;
    margin-bottom: 1rem;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-card img {
    height: 250px;
    object-fit: cover;
}

.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.price {
    color: var(--secondary-color);
}

.rating {
    font-size: 0.875rem;
}

.discount-badge {
    font-weight: bold;
}

.product-actions .btn {
    transition: transform 0.2s ease;
}

.product-actions .btn:hover {
    transform: scale(1.1);
}

.product-actions .fa-heart {
    transition: color 0.2s ease;
}

.product-actions .btn:hover .fa-heart {
    color: #dc3545;
}

.card-body {
    padding: 1.25rem;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    padding: 0.75rem 1rem;
}

.btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.row {
    margin-left: -0.75rem;
    margin-right: -0.75rem;
}

.col-md-3 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('/cart/add/' + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        text: 'The product has been added to your cart.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    // Add to Wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('/wishlist/add/' + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Wishlist!',
                        text: 'The product has been added to your wishlist.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
</script>
@endsection 