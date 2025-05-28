@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{route('do_register')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
      @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
      @endforeach
      <div class="form-group mb-2">
        <label for="code" class="form-label">Name:</label>
        <input type="text" class="form-control" placeholder="name" name="name" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="email" name="email" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="password" name="password" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Password Confirmation:</label>
        <input type="password" class="form-control" placeholder="Confirmation" name="password_confirmation" required>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>

    <div class="mt-4">
      <p class="text-center">Or register with:</p>
      <div class="d-flex flex-column gap-2">
        <a href="{{ route('google.login') }}" class="btn btn-danger">
          <i class="fab fa-google"></i> Register with Google
        </a>
        <a href="{{ route('facebook.login') }}" class="btn btn-primary">
          <i class="fab fa-facebook"></i> Register with Facebook
        </a>
      </div>
    </div>
    </div>
  </div>
</div>
@endsection
