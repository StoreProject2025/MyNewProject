@extends('layouts.master')
@section('title', 'Users')
@section('content')
    <div class="row mt-2">
        <div class="col col-10">
            <h1>Users</h1>
        </div>
    </div>
    <form>
        <div class="row">
            <div class="col col-sm-2">
                <input name="keywords" type="text" class="form-control" placeholder="Search Keywords"
                    value="{{ request()->keywords }}" />
            </div>
            <div class="col col-sm-1">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col col-sm-1">
                <button type="reset" class="btn btn-danger">Reset</button>
            </div>
        </div>
    </form>

    <div class="card mt-2">
        <div class="card-body">
            @if(auth()->user()->hasRole('Admin'))
                <a href="{{ route('users.create') }}" class="btn btn-success mb-4">Create New User</a>
            @endif
            
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Roles</th>
                        <th scope="col">Credit</th>
                        @if(auth()->user()->hasRole('Admin'))
                            <th scope="col">Add Credit</th>
                        @endif
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $user->credit }}</td>
                            @if(auth()->user()->hasRole('Admin') && $user->hasRole('Customer'))
                                <td>
                                    <form action="{{ route('add.credit') }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <input type="number" name="credit" class="form-control" placeholder="Add Amount" min="0" style="width: 100px;">
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button type="submit" class="btn btn-success">Add</button>
                                    </form>
                                </td>
                            @elseif(auth()->user()->hasRole('Admin'))
                                <td>-</td>
                            @endif
                            <td>
                                @if(auth()->user()->hasRole('Admin'))
                                    <a class="btn btn-primary" href="{{ route('users_edit', [$user->id]) }}">Edit</a>
                                    <a class="btn btn-info" href="{{ route('edit_password', [$user->id]) }}">Change Password</a>
                                    <a class="btn btn-danger" href="{{ route('users_delete', [$user->id]) }}" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
