<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        // تحميل العلاقات المطلوبة
        $product->load(['category', 'ratings.user']);
        
        // حساب متوسط التقييم
        $averageRating = $product->ratings->avg('rating') ?? 0;
        
        // تحميل منتجات مشابهة من نفس الفئة
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('web.products.show', compact('product', 'averageRating', 'relatedProducts'));
    }
} 