<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #CB0404;
            --secondary-color: #F4631E;
            --accent-color: #FF9F00;
            --light-color: #309898;
            --dark-color: #06202B;
            --light-bg: #F5EEDD;
            --accent-bg: #7AE2CF;
            --secondary-bg: #077A7D;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--light-bg);
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.5rem 0;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .navbar-brand span {
            color: var(--primary-color);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-left: 0.5rem;
        }

        @media (max-width: 767.98px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .navbar-brand i {
                font-size: 1.5rem;
            }
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0, 0, 0, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                margin-top: 1rem;
            }
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            transition: all 0.3s ease;
            border-width: 2px;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(244, 99, 30, 0.2);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            transition: all 0.3s ease;
            border-width: 2px;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(203, 4, 4, 0.2);
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .btn:active {
            transform: translateY(0);
        }

        .language-dropdown .dropdown-menu {
            min-width: 150px;
            padding: 0.5rem 0;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .language-dropdown .dropdown-item {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .language-dropdown .dropdown-item:hover {
            background-color: var(--light-bg);
        }

        .language-dropdown .dropdown-item.active {
            background-color: var(--primary-color);
            color: white;
        }

        .language-dropdown .dropdown-item img {
            width: 20px;
            height: 15px;
            object-fit: cover;
            border-radius: 2px;
            margin-right: 8px;
        }

        .language-dropdown .btn-link {
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .language-dropdown .btn-link:hover {
            background-color: var(--light-bg);
        }

        .auth-required {
            cursor: pointer;
        }

        .auth-required:hover {
            color: var(--primary-color);
        }

        .nav-item.dropdown {
            position: relative;
        }

        .nav-item.dropdown .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 0.5rem;
            margin-top: 0.5rem;
            min-width: 220px;
        }

        .dropdown-item {
            padding: 0.7rem 1rem;
            border-radius: 0.3rem;
            transition: all 0.3s ease;
            color: var(--dark-color);
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-item:active {
            background-color: var(--primary-color);
            color: white;
        }

        .dropdown-item.dropdown-toggle {
            position: relative;
            padding-right: 2rem;
        }

        .dropdown-item.dropdown-toggle::after {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            border-top: 0.3em solid transparent;
            border-right: 0;
            border-bottom: 0.3em solid transparent;
            border-left: 0.3em solid;
            transition: all 0.3s ease;
        }

        .dropend:hover > .dropdown-item.dropdown-toggle::after {
            transform: translateY(-50%) rotate(180deg);
        }

        @media (max-width: 991.98px) {
            .dropdown-submenu {
                position: static;
                margin-left: 1rem;
                box-shadow: none;
                border-left: 2px solid var(--light-bg);
                padding-left: 1rem;
            }

            .dropdown-item.dropdown-toggle::after {
                transform: rotate(90deg);
            }

            .dropend:hover > .dropdown-item.dropdown-toggle::after {
                transform: rotate(-90deg);
            }

            .dropdown-item:hover {
                transform: none;
            }
        }

        .dropdown-submenu {
            position: absolute;
            left: 100%;
            top: 0;
            margin-top: 0;
            display: none;
            z-index: 1000;
            background: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 0.5rem;
            min-width: 220px;
        }

        .dropend:hover > .dropdown-submenu {
            display: block;
        }

        .dropdown-item {
            padding: 0.7rem 1rem;
            border-radius: 0.3rem;
            transition: all 0.3s ease;
            color: var(--dark-color);
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-item:active {
            background-color: var(--primary-color);
            color: white;
        }

        .dropdown-item.dropdown-toggle {
            position: relative;
            padding-right: 2rem;
        }

        .dropdown-item.dropdown-toggle::after {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            border-top: 0.3em solid transparent;
            border-right: 0;
            border-bottom: 0.3em solid transparent;
            border-left: 0.3em solid;
            transition: all 0.3s ease;
        }

        .dropend:hover > .dropdown-item.dropdown-toggle::after {
            transform: translateY(-50%) rotate(180deg);
        }

        @media (max-width: 991.98px) {
            .dropdown-submenu {
                position: static;
                margin-left: 1rem;
                box-shadow: none;
                border-left: 2px solid var(--light-bg);
                padding-left: 1rem;
            }

            .dropdown-item.dropdown-toggle::after {
                transform: rotate(90deg);
            }

            .dropend:hover > .dropdown-item.dropdown-toggle::after {
                transform: rotate(-90deg);
            }

            .dropdown-item:hover {
                transform: none;
            }
        }

        .submenu {
            position: absolute;
            left: 100%;
            top: 0;
            background: white;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
            min-width: 200px;
            z-index: 1000;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            white-space: nowrap;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .submenu .dropdown-item {
            padding: 0.5rem 1.5rem;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <i class="fas fa-store"></i>
                <span>E-Click</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $category)
                                    <li class="dropend">
                                        <a class="dropdown-item dropdown-toggle" href="#" data-category="{{ $category->id }}">
                                            {{ $category->name }}
                                        </a>
                                        <ul class="dropdown-submenu" id="submenu-{{ $category->id }}">
                                            @if(isset($category->subcategories) && count($category->subcategories) > 0)
                                                @foreach($category->subcategories as $subcategory)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('categories.show', $subcategory) }}">
                                                            {{ $subcategory->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                @endforeach
                            @else
                                <li><a class="dropdown-item" href="{{ route('categories.index') }}">All Categories</a></li>
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.shop') }}">Products</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('cart') }}" class="nav-link">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wishlist') }}">
                                <i class="fas fa-heart"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile', ['user' => auth()->id()]) }}">Profile</a></li>
                                @if(Auth::user()->hasRole('admin'))
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Manage Users</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}">Manage Roles</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.permissions.index') }}">Manage Permissions</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Manage Categories</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">Manage Products</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Manage Orders</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('orders') }}">Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">About Us</h5>
                    <p>Your trusted online shopping destination for quality products and excellent service.</p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('welcome') }}" class="text-light">Home</a></li>
                        <li><a href="{{ route('categories.index') }}" class="text-light">Categories</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-light">Products</a></li>
                        <li><a href="{{ route('about') }}" class="text-light">About</a></li>
                        <li><a href="{{ route('contact') }}" class="text-light">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">Customer Service</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light">Shipping Policy</a></li>
                        <li><a href="#" class="text-light">Return Policy</a></li>
                        <li><a href="#" class="text-light">FAQ</a></li>
                        <li><a href="#" class="text-light">Privacy Policy</a></li>
                        <li><a href="#" class="text-light">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">Newsletter</h5>
                    <p>Subscribe to our newsletter for updates and offers.</p>
                    <form>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Your email">
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} Your Store. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="{{ asset('images/payment-methods.png') }}" alt="Payment Methods" height="30">
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Please login or register to access this feature.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl, {
                    autoClose: 'outside'
                });
            });

            // Handle nested dropdowns
            var dropendElements = document.querySelectorAll('.dropend');
            dropendElements.forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var submenu = this.querySelector('.dropdown-submenu');
                    if (submenu) {
                        submenu.classList.toggle('show');
                    }
                });
            });

            // Handle auth-required clicks
            document.querySelectorAll('.auth-required').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    var modal = new bootstrap.Modal(document.getElementById('loginModal'));
                    modal.show();
                });
            });

            // Check if wishlist/cart is empty and show message
            @auth
                if (window.location.pathname === '{{ route("wishlist") }}' && {{ auth()->user()->wishlist()->count() }} === 0) {
                    Swal.fire({
                        title: 'Your Wishlist is Empty',
                        text: 'Start adding items to your wishlist!',
                        icon: 'info',
                        confirmButtonText: 'Browse Products',
                        confirmButtonColor: '#CB0404'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("products.index") }}';
                        }
                    });
                }

                if (window.location.pathname === '{{ route("cart") }}' && {{ auth()->user()->cart()->count() }} === 0) {
                    Swal.fire({
                        title: 'Your Cart is Empty',
                        text: 'Start shopping to add items to your cart!',
                        icon: 'info',
                        confirmButtonText: 'Browse Products',
                        confirmButtonColor: '#CB0404'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("products.index") }}';
                        }
                    });
                }
            @endauth
        });
    </script>
    <script src="{{ asset('js/app.js') }}"></script>

    @section('scripts')
    <script>
        // Category dropdown functionality
        $('.dropdown-item[data-category]').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const categoryId = $(this).data('category');
            const submenu = $(`#submenu-${categoryId}`);
            
            // Hide all other submenus
            $('.submenu').not(submenu).hide();
            
            // Toggle current submenu
            submenu.toggle();
        });

        // Close submenu when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.dropdown-menu').length) {
                $('.submenu').hide();
            }
        });

        // Add to Cart
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                if (this.classList.contains('auth-required')) {
                    return;
                }

                const productId = this.dataset.productId;
                // Add AJAX call to add product to cart
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            title: 'Success!',
                            text: 'Product added to cart successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding product to cart');
                });
            });
        });
    </script>
    @endsection
</body>
</html>
