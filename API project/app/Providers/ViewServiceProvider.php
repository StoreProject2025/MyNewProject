<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share only parent categories with all views
        View::composer('*', function ($view) {
            $view->with('categories', Category::where('is_active', true)
                ->whereNull('parent_id')
                ->get());
        });
    }
} 