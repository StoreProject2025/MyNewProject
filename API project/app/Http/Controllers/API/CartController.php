<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Validator; 

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();
        return response()->json($cartItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();
        $product_id = $request->product_id;
        $quantity = $request->quantity;

        $cartItem = $user->cartItems()->where('product_id', $product_id)->first();

        if ($cartItem) {
            // Product exists in cart, update quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Product does not exist in cart, create new item
            $cartItem = $user->cartItems()->create([
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
        }

        $cartItem->load('product'); // Load product relationship for the response

        return response()->json($cartItem, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cartItem->load('product'); // Load product relationship for the response

        return response()->json($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart successfully']);
    }
}
