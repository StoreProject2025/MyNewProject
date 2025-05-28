<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <!-- صورة المنتج -->
                <div class="space-y-4">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-auto object-contain max-h-[500px]">
                    </div>
                    
                    @if($product->gallery)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->gallery as $image)
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-24 object-contain">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- تفاصيل المنتج -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $product->name }}
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            الفئة: {{ $product->category->name }}
                        </p>
                    </div>

                    <!-- التقييم -->
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            ({{ $product->ratings->count() }} تقييم)
                        </span>
                    </div>

                    <!-- السعر -->
                    <div class="flex items-center space-x-4">
                        @if($product->discount_price)
                            <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                {{ number_format($product->discount_price, 2) }} ريال
                            </span>
                            <span class="text-xl text-gray-500 dark:text-gray-400 line-through">
                                {{ number_format($product->price, 2) }} ريال
                            </span>
                            <span class="bg-green-100 text-green-800 text-sm font-semibold px-2.5 py-0.5 rounded">
                                {{ $product->discount_percentage }}% خصم
                            </span>
                        @else
                            <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                {{ number_format($product->price, 2) }} ريال
                            </span>
                        @endif
                    </div>

                    <!-- الوصف -->
                    <div class="prose dark:prose-invert max-w-none">
                        <h3 class="text-lg font-semibold mb-2">الوصف</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- المخزون -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock > 0 ? 'متوفر في المخزون' : 'غير متوفر' }}
                        </span>
                        @if($product->stock > 0)
                            <span class="text-sm text-gray-500">
                                ({{ $product->stock }} قطعة متبقية)
                            </span>
                        @endif
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="flex space-x-4">
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                                    إضافة إلى السلة
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-2 px-6 rounded-lg transition duration-200">
                                {{ auth()->user() && auth()->user()->wishlist->contains($product) ? 'إزالة من المفضلة' : 'إضافة إلى المفضلة' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- التقييمات -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">التقييمات</h2>
            
            @if($product->ratings->count() > 0)
                <div class="space-y-6">
                    @foreach($product->ratings as $rating)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        بواسطة {{ $rating->user->name }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $rating->created_at->diffForHumans() }}
                                </span>
                            </div>
                            @if($rating->comment)
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $rating->comment }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">لا توجد تقييمات بعد</p>
            @endif
        </div>

        <!-- المنتجات المشابهة -->
        @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">منتجات مشابهة</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout> 