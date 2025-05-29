<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Get cart items
        $cartItems = $user->cart()->with('product')->get();
        
        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate total
            $total = $cartItems->sum(function($item) {
                $price = $item->product->discount > 0 
                    ? $item->product->discounted_price 
                    : $item->product->price;
                return $price * $item->quantity;
            });
            
            // Prepare shipping address
            $shippingAddress = json_encode([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'notes' => $request->notes
            ]);
            
            // Create order
            $order = new Order();
            $order->user_id = $user->id;
            $order->total = $total;
            $order->status = 'pending';
            $order->shipping_address = $shippingAddress;
            $order->payment_method = $request->payment_method;
            
            // Set initial payment status based on payment method
            $order->payment_status = $request->payment_method === 'credit' ? 'paid' : 'pending';
            
            $order->save();
            
            // Create order items
            foreach ($cartItems as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cartItem->product_id;
                $orderItem->quantity = $cartItem->quantity;
                $orderItem->price = $cartItem->product->discount > 0 
                    ? $cartItem->product->discounted_price 
                    : $cartItem->product->price;
                $orderItem->save();
                
                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }
            
            // Clear cart
            $user->cart()->delete();
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart')->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        // Make sure the user can only see their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Make sure the user can only cancel their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Can only cancel pending orders
        if ($order->status !== 'pending') {
            return back()->with('error', 'You can only cancel pending orders');
        }
        
        try {
            DB::beginTransaction();
            
            // Update order status
            $order->status = 'cancelled';
            $order->save();
            
            // Restore product stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order cancelled successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
} 