@extends('layouts.master')
@section('title', 'Products List')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Products List</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 50px;">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" 
                                             alt="No Image" 
                                             class="img-thumbnail" 
                                             style="max-width: 50px;">
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name }}</td>
<<<<<<< HEAD
                                <td>
                                    @if($product->discount > 0)
                                        <span class="text-muted text-decoration-line-through">{{ number_format($product->price, 2) }}</span>
                                        <span class="text-danger">{{ number_format($product->discounted_price, 2) }}</span>
                                        <span class="badge bg-danger">{{ $product->discount }}%</span>
                                    @else
                                        {{ number_format($product->price, 2) }}
                                    @endif
                                </td>
=======
                                <td>{{ number_format($product->price, 2) }}</td>
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
<<<<<<< HEAD
                                        @if(auth()->user()->hasPermissionTo('manage_discounts'))
                                        <a href="{{ route('products.edit', $product) }}#discount" 
                                           class="btn btn-sm btn-warning" 
                                           title="Add Discount">
                                            <i class="fas fa-percent"></i>
                                        </a>
                                        @endif
=======
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
                                        <form action="{{ route('products.destroy', $product) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 