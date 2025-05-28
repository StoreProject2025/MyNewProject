@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
    <div class="row">
        <div class="m-4 col-sm-6">
            <table class="table table-striped">
                <tr>
                    <th>Name</th>
                    <td>{{$user->name}}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{$user->email}}</td>
                </tr>
                <tr>
                    <th>Roles</th>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{$role->name}}</span>
                        @endforeach
                        @if($user->roles->isEmpty())
                            <span class="text-muted">No roles assigned</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Permissions</th>
                    <td>
                        @if($permissions->isNotEmpty())
                        @foreach($permissions as $permission)
                                <span class="badge bg-success">{{$permission->display_name ?? $permission->name}}</span>
                        @endforeach
                        @else
                            <span class="text-muted">No permissions assigned</span>
                        @endif
                    </td>
                </tr>
                @if($user->hasRole('Customer'))
                    <tr>
                        <th>Credit</th>
                        <td>{{$user->credit}}</td>
                    </tr>
                @endif
            </table>

            <div class="row mb-3">
                <div class="col col-6">
                    @if(auth()->user()->hasRole('Admin'))
                        <a href="{{ route('users') }}" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="{{ route('users.create') }}" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>Add New User
                        </a>
                    @elseif(auth()->user()->hasPermissionTo('show_users'))
                        <a href="{{ route('users') }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>View Users
                        </a>
                    @endif
                </div>
                @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                    <div class="col col-4">
                        <a class="btn btn-primary" href='{{route('edit_password', $user->id)}}'>Change Password</a>
                    </div>
                @else
                    <div class="col col-4">
                    </div>
                @endif
                @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                    <div class="col col-2">
                        <a href="{{route('users_edit', $user->id)}}" class="btn btn-success form-control">Edit</a>
                    </div>
                @endif
            </div>

            @if(auth()->user()->hasRole('Admin'))
                <div class="row mb-3">
                    <div class="col-12">
                        <h4 class="mb-3">Category Management</h4>
                        @if(auth()->user()->hasPermissionTo('add_category'))
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-folder-plus me-2"></i>Add Category
                            </a>
                        @endif
                        @if(auth()->user()->hasPermissionTo('view_categories'))
                            <a href="{{ route('categories.index') }}" class="btn btn-info">
                                <i class="fas fa-folder me-2"></i>Manage Categories
                            </a>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3">Product Management</h4>
                        @if(auth()->user()->hasPermissionTo('add_product'))
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="fas fa-box-open me-2"></i>Add Product
                            </a>
                        @endif
                        @if(auth()->user()->hasPermissionTo('view_products'))
                            <a href="{{ route('products.index') }}" class="btn btn-info">
                                <i class="fas fa-boxes me-2"></i>Manage Products
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
