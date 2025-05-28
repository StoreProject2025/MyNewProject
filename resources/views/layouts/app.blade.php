<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>E-Click - Your Online Shopping Destination</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

        <!-- Custom Styles -->
        <style>
            :root {
                --primary-color: #309898;
                --secondary-color: #FF9F00;
                --accent-color: #F4631E;
                --danger-color: #CB0404;
                --light-color: #FBF3C1;
            }
            
            body {
                font-family: 'Figtree', sans-serif;
                background-color: #F3F4F6;
            }

            .nav-dropdown {
                position: relative;
            }

            .nav-dropdown-content {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                min-width: 200px;
                background: white;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                border-radius: 0.5rem;
                z-index: 50;
            }

            .nav-dropdown:hover .nav-dropdown-content {
                display: block;
            }

            .nav-dropdown-item {
                padding: 0.75rem 1rem;
                color: #374151;
                transition: all 0.2s;
            }

            .nav-dropdown-item:hover {
                background-color: var(--light-color);
                color: var(--primary-color);
            }

            .swiper-container {
                width: 100%;
                height: 600px;
            }
            
            .swiper-slide {
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .swiper-button-next,
            .swiper-button-prev {
                color: white;
                background: rgba(0, 0, 0, 0.3);
                width: 50px;
                height: 50px;
                border-radius: 50%;
                transition: all 0.3s ease;
            }
            
            .swiper-button-next:hover,
            .swiper-button-prev:hover {
                background: rgba(0, 0, 0, 0.5);
            }
            
            .swiper-pagination-bullet {
                width: 12px;
                height: 12px;
                background: white;
                opacity: 0.5;
            }
            
            .swiper-pagination-bullet-active {
                opacity: 1;
                background: white;
            }
            
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .animate-fade-in {
                animation: fade-in 1s ease-out;
            }

            .btn-primary {
                background-color: var(--primary-color);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background-color: var(--secondary-color);
                transform: translateY(-2px);
            }

            .section-title {
                color: var(--primary-color);
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }

            .price {
                color: var(--accent-color);
                font-weight: 600;
            }

            .card {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }

            .discount-badge {
                background-color: var(--danger-color);
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.875rem;
                font-weight: 700;
            }
        </style>

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts -->
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Swiper('.swiper-container', {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>
