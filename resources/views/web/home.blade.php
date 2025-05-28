<x-app-layout>
    <!-- Hero Slider Section -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div class="relative h-[600px]">
                        <img src="{{ asset('images/slider/slide1.jpg') }}" alt="Welcome to E-Click" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/40 flex items-center justify-center">
                            <div class="text-center text-white max-w-4xl mx-auto px-4">
                                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in">Welcome to E-Click</h1>
                                <p class="text-2xl mb-10 text-gray-200">Discover our amazing products at competitive prices</p>
                                <a href="{{ route('products.index') }}" 
                                   class="btn-primary inline-block transform hover:scale-105 transition duration-300 shadow-lg">
                                    Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <div class="relative h-[600px]">
                        <img src="{{ asset('images/slider/slide2.jpg') }}" alt="Special Offers" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/40 flex items-center justify-center">
                            <div class="text-center text-white max-w-4xl mx-auto px-4">
                                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in">Special Offers</h1>
                                <p class="text-2xl mb-10 text-gray-200">Up to 50% off on selected items</p>
                                <a href="{{ route('products.index') }}" 
                                   class="btn-primary inline-block transform hover:scale-105 transition duration-300 shadow-lg">
                                    View Offers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next text-white"></div>
            <div class="swiper-button-prev text-white"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <!-- Featured Categories -->
        @if($featuredCategories->count() > 0)
            <section class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="section-title">Featured Categories</h2>
                    <a href="{{ route('categories.index') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                        View All
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($featuredCategories as $category)
                        <a href="{{ route('categories.show', $category) }}" 
                           class="card group block overflow-hidden">
                            <div class="relative h-64">
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end">
                                    <div class="p-6 w-full">
                                        <h3 class="text-white text-2xl font-bold mb-2">{{ $category->name }}</h3>
                                        <p class="text-gray-200 text-sm">{{ $category->products_count }} Products</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Flash Sale -->
        @if($flashSaleProducts->count() > 0)
            <section class="mb-16 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="section-title">Flash Sale</h2>
                        <p class="text-gray-600">Limited time offers with amazing prices</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                        View All
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($flashSaleProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Best Sellers -->
        @if($bestSellers->count() > 0)
            <section class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="section-title">Best Sellers</h2>
                        <p class="text-gray-600">Most popular products from our customers</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                        View All
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($bestSellers as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout> 