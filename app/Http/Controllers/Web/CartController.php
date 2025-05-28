<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            Log::info('Accessing cart page', [
                'user_id' => $user->id,
                'user_role' => $user->roles->pluck('name'),
                'is_authenticated' => auth()->check()
            ]);
            
            $cartItems = $user->cart()->with('product')->get();
            $total = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
            
            Log::info('Cart items retrieved', [
                'user_id' => $user->id,
                'items_count' => $cartItems->count(),
                'total' => $total
            ]);
            
            return view('users.cart', compact('cartItems', 'total'));
        } catch (\Exception $e) {
            Log::error('Error accessing cart', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('login');
        }
    }

    public function add(Request $request, $slug)
    {
        try {
            $user = auth()->user();
            $product = Product::where('slug', $slug)->firstOrFail();
            $quantity = $request->input('quantity', 1);

            // Add to cart logic
            $cartItem = $user->cart()->updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => DB::raw('quantity + ' . $quantity)]
            );

            // Get updated cart count
            $cartCount = $user->cart()->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cartCount' => $cartCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart'
            ], 500);
        }
    }

    public function remove(Product $product)
    {
        auth()->user()->cart()->where('product_id', $product->id)->delete();
        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = auth()->user()->cart()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $cartItem->update([
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function clear()
    {
        try {
            $user = auth()->user();
            $user->cart()->delete();
            
            \Illuminate\Support\Facades\Log::info('Cart cleared', [
                'user_id' => $user->id
            ]);
            
            return redirect()->route('cart')->with('success', 'Cart cleared successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error clearing cart', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('cart')->with('error', 'Failed to clear cart');
        }
    }

    public function checkout()
    {
        $cartItems = auth()->user()->cart()->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('users.checkout', compact('cartItems', 'total'));
    }

    public function count()
    {
        try {
            $user = auth()->user();
            $cartCount = $user->cart()->sum('quantity');
            
            return response()->json(['count' => $cartCount]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0]);
        }
    }
}
