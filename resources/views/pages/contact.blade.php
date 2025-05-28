@extends('layouts.master')
@section('title', 'Contact Us')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Contact Us</h1>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-4">Get in Touch</h2>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-4">Contact Information</h2>
                            <div class="mb-4">
                                <h3 class="h6 mb-2">Address</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    123 Street Name, City, Country
                                </p>
                            </div>
                            <div class="mb-4">
                                <h3 class="h6 mb-2">Phone</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    +1 234 567 890
                                </p>
                            </div>
                            <div class="mb-4">
                                <h3 class="h6 mb-2">Email</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    support@example.com
                                </p>
                            </div>
                            <div class="mb-4">
                                <h3 class="h6 mb-2">Working Hours</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    Monday - Friday: 9:00 AM - 6:00 PM
                                </p>
                            </div>
                            <div class="mt-4">
                                <h3 class="h6 mb-2">Follow Us</h3>
                                <div class="social-links">
                                    <a href="#" class="text-primary me-3"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="text-primary me-3"><i class="fab fa-twitter"></i></a>
                                    <a href="#" class="text-primary me-3"><i class="fab fa-instagram"></i></a>
                                    <a href="#" class="text-primary"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 