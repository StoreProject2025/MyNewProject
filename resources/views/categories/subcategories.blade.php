@extends('layouts.master')
@section('title', $category->name . ' Subcategories')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">{{ $category->name }}</h1>
    
    <div class="row">
        @foreach($subcategories as $subcategory)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($subcategory->image)
                    <img src="{{ asset('storage/' . $subcategory->image) }}" class="card-img-top" alt="{{ $subcategory->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-folder fa-3x text-primary"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $subcategory->name }}</h5>
                    <p class="card-text text-muted">{{ $subcategory->description ?? 'No description available' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('categories.show', $subcategory->slug) }}" class="btn btn-primary">View Products</a>
                        <div class="product-actions">
                            <a href="{{ route('products.show', $subcategory->products->first()->slug ?? '#') }}" class="btn btn-link text-dark" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            @auth
                                <button class="btn btn-link text-danger add-to-wishlist" data-product-id="{{ $subcategory->products->first()->id ?? '' }}" title="Add to Wishlist">
                                    <i class="fas fa-heart"></i>
                                </button>
                            @else
                                <button class="btn btn-link text-danger" data-bs-toggle="modal" data-bs-target="#loginModal" title="Add to Wishlist">
                                    <i class="fas fa-heart"></i>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .card-img-top {
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .product-actions {
        display: flex;
        gap: 0.5rem;
    }

    .product-actions .btn-link {
        padding: 0.25rem;
        font-size: 1.1rem;
        transition: transform 0.2s ease;
    }

    .product-actions .btn-link:hover {
        transform: scale(1.2);
    }

    .product-actions .fa-heart {
        transition: color 0.2s ease;
    }

    .product-actions .btn-link:hover .fa-heart {
        color: #dc3545;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to Wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            if (!productId) return;

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
                    // Show success message
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