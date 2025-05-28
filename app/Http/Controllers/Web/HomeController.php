<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // تحميل الفئات المميزة
        $featuredCategories = Category::where('is_featured', true)
            ->take(3)
            ->get();

        // تحميل المنتجات في العروض الخاطفة
        $flashSaleProducts = Product::where('is_flash_sale', true)
            ->where('discount_percentage', '>', 0)
            ->where('is_active', true)
            ->take(8)
            ->get();

        // تحميل أفضل المنتجات مبيعاً مع العلاقات المطلوبة
        $bestSellers = Product::where('is_active', true)
            ->with(['category', 'ratings']) // تحميل العلاقات المطلوبة
            ->orderBy('average_rating', 'desc')
            ->take(8)
            ->get();

        return view('web.home', compact('featuredCategories', 'flashSaleProducts', 'bestSellers'));
    }
} 