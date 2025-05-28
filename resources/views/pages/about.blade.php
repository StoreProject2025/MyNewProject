@extends('layouts.master')
@section('title', 'About Us')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">About Us</h1>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3">Our Story</h2>
                    <p class="text-muted">
                        Welcome to our online store! We are dedicated to providing you with the best shopping experience.
                        Our journey began with a simple idea: to make quality products accessible to everyone.
                    </p>

                    <h2 class="h4 mb-3 mt-4">Our Mission</h2>
                    <p class="text-muted">
                        Our mission is to provide our customers with high-quality products at competitive prices,
                        while ensuring excellent customer service and a seamless shopping experience.
                    </p>

                    <h2 class="h4 mb-3 mt-4">Why Choose Us?</h2>
                    <div class="row mt-4">
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                                <h3 class="h5">Fast Shipping</h3>
                                <p class="text-muted">Quick and reliable delivery to your doorstep</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                                <h3 class="h5">Easy Returns</h3>
                                <p class="text-muted">Hassle-free return policy for your peace of mind</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                                <h3 class="h5">24/7 Support</h3>
                                <p class="text-muted">Round-the-clock customer service support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 