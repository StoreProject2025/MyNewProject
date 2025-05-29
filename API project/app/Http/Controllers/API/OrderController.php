<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.product')->get();
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty'], 400);
        }

        DB::beginTransaction();

        try {
            // Create the order
            $order = $user->orders()->create([
                'total' => 0, // Will calculate total later
                'status' => 'pending', // Default status
                // Add other relevant order fields here (e.g., shipping_address if applicable)
            ]);

            $totalPrice = 0;
            $orderItemsData = [];

            foreach ($cartItems as $cartItem) {
                // Ensure product exists and has a price
                if (!$cartItem->product || $cartItem->product->price === null) {
                    DB::rollBack();
                    return response()->json(['message' => 'Invalid product in cart'], 400);
                }

                $itemTotalPrice = $cartItem->quantity * $cartItem->product->price;
                $totalPrice += $itemTotalPrice;

                $orderItemsData[] = [
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price, // Price at the time of order
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Create order items
            $order->orderItems()->createMany($orderItemsData);

            // Update order total price
            $order->total = $totalPrice;
            $order->save();

            // Clear the user's cart
            $user->cartItems()->delete();

            DB::commit();

            $order->load('orderItems.product'); // Load relationships for response

            return response()->json($order, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the exception for debugging
            // Log::error('Failed to create order: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->load('orderItems.product'); // Load order items and their products

        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
