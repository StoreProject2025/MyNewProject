@extends('layouts.master')
@section('title', 'Email Verified')
@section('content')
    <div class="row">
        <div class="m-4 col-sm-6">
            <div class="alert alert-success">
                <strong>Congratulations!</strong><br>
                Dear {{$user->name}}, your email {{$user->email}} is now verified.
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary">Proceed to Login</a>
        </div>
    </div>
@endsection 