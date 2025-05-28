<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlist()->with('product')->get();
        return view('users.wishlist', compact('wishlistItems'));
    }

    public function add(Product $product)
    {
        try {
            $user = auth()->user();
            
            // Check if product is already in wishlist
            if (!$user->wishlist()->where('product_id', $product->id)->exists()) {
                $user->wishlist()->create([
                    'product_id' => $product->id
                ]);
                
                Log::info('Product added to wishlist', [
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to wishlist successfully!'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Product is already in your wishlist!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error adding product to wishlist', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error adding product to wishlist. Please try again.'
            ], 500);
        }
    }

    public function remove(Product $product)
    {
        try {
            auth()->user()->wishlist()->where('product_id', $product->id)->delete();
            
            Log::info('Product removed from wishlist', [
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);
            
            return redirect()->back()->with('success', 'Product removed from wishlist successfully!');
        } catch (\Exception $e) {
            Log::error('Error removing product from wishlist', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);
            
            return redirect()->back()->with('error', 'Error removing product from wishlist. Please try again.');
        }
    }

    public function toggle(Product $product)
    {
        try {
            $user = auth()->user();
            $wishlistItem = $user->wishlist()->where('product_id', $product->id)->first();
            
            if ($wishlistItem) {
                $wishlistItem->delete();
                $message = 'Product removed from wishlist successfully!';
                $inWishlist = false;
            } else {
                $user->wishlist()->create([
                    'product_id' => $product->id
                ]);
                $message = 'Product added to wishlist successfully!';
                $inWishlist = true;
            }
            
            Log::info('Product wishlist status toggled', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'in_wishlist' => $inWishlist
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'in_wishlist' => $inWishlist
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling product wishlist status', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating wishlist. Please try again.'
            ], 500);
        }
    }
}
