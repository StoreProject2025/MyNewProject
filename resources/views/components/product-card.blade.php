@props(['product'])

<div class="card group">
    <div class="relative">
        <a href="{{ route('products.show', $product) }}">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-64 object-cover group-hover:scale-110 transition duration-300">
        </a>
        @if($product->discount_percentage > 0)
            <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                -{{ $product->discount_percentage }}%
            </div>
        @endif
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
            <h3 class="text-white text-lg font-bold mb-1 truncate">{{ $product->name }}</h3>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    @if($product->discount_percentage > 0)
                        <span class="text-gray-300 line-through text-sm">EGP {{ number_format($product->price, 2) }}</span>
                        <span class="text-white font-bold">EGP {{ number_format($product->discount_price, 2) }}</span>
                    @else
                        <span class="text-white font-bold">EGP {{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                <div class="flex items-center text-yellow-400">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-sm ml-1">{{ number_format($product->rating, 1) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between">
            <span class="text-gray-600 text-sm">{{ $product->category->name }}</span>
            <span class="text-gray-600 text-sm">{{ $product->stock }} in stock</span>
        </div>
        <div class="mt-4">
            <a href="{{ route('products.show', $product) }}" 
               class="btn-primary block w-full text-center">
                View Details
            </a>
        </div>
    </div>
</div> 