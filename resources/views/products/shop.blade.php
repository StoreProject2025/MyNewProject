@extends('layouts.master')
@section('title', 'Shop')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <form action="{{ route('products.shop') }}" method="GET">
                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Min</span>
                                <input type="number" class="form-control" name="min_price" value="{{ request('min_price') }}" min="0" step="0.01">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Max</span>
                                <input type="number" class="form-control" name="max_price" value="{{ request('max_price') }}" min="0" step="0.01">
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $category->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <select class="form-select" name="sort">
                                <option value="">Latest</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            @if($products->isEmpty())
            <div class="alert alert-info">
                No products found matching your criteria.
                @if(isset($debug))
                <hr>
                <small class="text-muted">
                    Debug Info:<br>
                    Total Active Products: {{ $debug['total_active_products'] }}<br>
                    Applied Filters: {{ json_encode($debug['applied_filters']) }}<br>
                    SQL Query: {{ $debug['sql_query'] }}<br>
                    SQL Bindings: {{ json_encode($debug['sql_bindings']) }}
                </small>
                @endif
            </div>
            @else
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($products as $product)
                <div class="col">
                    <div class="card h-100">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text">
                                <strong>Price: ${{ number_format($product->price, 2) }}</strong>
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary flex-grow-1">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                                @if($product->stock > 0)
                                    @auth
                                        <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                            </button>
                                        </form>
                                        <button class="btn btn-outline-danger wishlist-btn" data-product-id="{{ $product->id }}">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#loginModal">
                                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                        </button>
                                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#loginModal">
                                            <i class="fas fa-heart"></i>
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

            <div class="mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
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
                            title: 'تم!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Update button appearance
                        if (response.in_wishlist) {
                            button.removeClass('btn-outline-danger').addClass('btn-danger');
                        } else {
                            button.removeClass('btn-danger').addClass('btn-outline-danger');
                        }
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'تنبيه!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Wishlist error:', {xhr, status, error});
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء إضافة المنتج إلى المفضلة.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });
</script>
@endsection 