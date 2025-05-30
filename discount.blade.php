@extends('layouts.master')

@section('title', 'Manage Discount - ' . $product->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.shop') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage Discount</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-4">Manage Discount for {{ $product->name }}</h1>

                    <form action="{{ route('products.discount.update', $product->slug) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="discount" class="form-label">Discount Percentage</label>
                            <input type="number" name="discount" id="discount" 
                                   class="form-control @error('discount') is-invalid @enderror"
                                   value="{{ $product->discount }}" min="0" max="100" step="1">
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('products.show', $product->slug) }}" 
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Update Discount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 