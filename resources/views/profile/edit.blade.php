@extends('layouts.master')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ auth()->user()->name }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="mt-3">{{ auth()->user()->name }}</h5>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="list-group">
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user me-2"></i> Profile Information
                        </a>
                        <a href="{{ route('profile.password') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-lock me-2"></i> Update Password
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Profile Information</h4>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update', auth()->user()->id) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Roles -->
                        @if(auth()->user()->hasRole('admin'))
                            <div class="mb-3">
                                <label class="form-label">Roles</label>
                                <div class="row">
                                    @foreach(auth()->user()->roles as $role)
                                        <div class="col-md-4">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $role->name }}</h6>
                                                    <p class="card-text text-muted small">{{ $role->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Permissions -->
                            <div class="mb-3">
                                <label class="form-label">Permissions</label>
                                <div class="row">
                                    @foreach(auth()->user()->getAllPermissions() as $permission)
                                        <div class="col-md-4">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $permission->name }}</h6>
                                                    <p class="card-text text-muted small">{{ $permission->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 10px;
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        padding: 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .list-group-item {
        border: none;
        padding: 0.8rem 1.2rem;
        margin-bottom: 0.5rem;
        border-radius: 8px !important;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: var(--light-bg);
        color: var(--primary-color);
    }

    .list-group-item.active {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-control {
        border-radius: 8px;
        padding: 0.8rem 1rem;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(203, 4, 4, 0.25);
    }

    .btn-primary {
        padding: 0.8rem 2rem;
        border-radius: 8px;
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
</style>
@endsection
