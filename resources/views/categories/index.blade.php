@extends('layouts.master')
@section('title', 'Categories')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Categories</h2>
            </div>
            <div class="col-md-6 text-end">
                @if(auth()->user()->hasPermissionTo('add_category'))
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Category
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text">{{ $category->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
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
.categories-header {
    text-align: center;
    padding: 2rem 0;
}

.category-card {
    transition: transform 0.3s;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-card img {
    height: 300px;
    object-fit: cover;
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.category-card:hover .category-overlay {
    opacity: 1;
}

.category-overlay .btn {
    padding: 0.75rem 2rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}
</style>
@endsection 