<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = $user->wishlistItems()->with('product')->get();
        return response()->json($wishlistItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $product_id = $request->product_id;

        // Check if the product is already in the wishlist
        $wishlistItem = $user->wishlistItems()->where('product_id', $product_id)->first();

        if ($wishlistItem) {
            return response()->json(['message' => 'Product already in wishlist'], 409); // Conflict
        }

        $wishlistItem = $user->wishlistItems()->create([
            'product_id' => $product_id,
        ]);

        $wishlistItem->load('product'); // Load product relationship for the response

        return response()->json($wishlistItem, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used for wishlist, as show route is not defined in api.php
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Not used for wishlist, as update route is not defined in api.php
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $user = Auth::user();

        // Find and delete the wishlist item for this user and product
        $wishlistItem = $user->wishlistItems()->where('product_id', $product->id)->first();

        if (!$wishlistItem) {
            return response()->json(['message' => 'Product not in wishlist'], 404);
        }

        $wishlistItem->delete();

        return response()->json(['message' => 'Product removed from wishlist successfully']);
    }
}
