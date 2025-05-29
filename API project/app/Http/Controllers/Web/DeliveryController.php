<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckDeliveryRole;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(CheckDeliveryRole::class);
    }
    
    /**
     * Display a listing of orders for delivery
     */
    public function index()
    {
        // Get all pending and processing orders
        $pendingOrders = Order::where('status', 'pending')
            ->orWhere('status', 'processing')
            ->latest()
            ->get();
            
        return view('delivery.index', compact('pendingOrders'));
    }
    
    /**
     * Show order details for delivery
     */
    public function show(Order $order)
    {
        return view('delivery.show', compact('order'));
    }
    
    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,delivered'
        ]);
        
        $previousStatus = $order->status;
        $order->status = $request->status;
        
        try {
            DB::beginTransaction();
            
            // If order is cash and being delivered, mark as payment received
            if ($request->status === 'delivered' && $request->has('payment_received') && $order->payment_method === 'cash') {
                // Check if payment was already marked as paid
                if ($order->payment_status !== 'paid') {
                    // Get both customer and delivery manager
                    $customer = $order->user;
                    $deliveryManager = Auth::user();
                    
                    $oldCustomerCredit = $customer->credit;
                    $oldDeliveryCredit = $deliveryManager->credit;
                    
                    if ($customer->credit < $order->total) {
                        return back()->with('error', 'Customer has insufficient credit balance. Current balance: $' . number_format($customer->credit, 2));
                    }
                    
                    // Deduct the amount from customer's credit
                    $customer->credit -= $order->total;
                    $customer->save();
                    
                    // Add the amount to delivery manager's credit
                    $deliveryManager->credit += $order->total;
                    $deliveryManager->save();
                    
                    // Mark order as paid
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                    $order->payment_notes = 'Payment processed during delivery by: ' . Auth::user()->name . 
                                           ' on ' . now()->format('Y-m-d H:i:s') . 
                                           '. Amount deducted from customer and added to delivery manager credit.';
                    
                    // Log the transaction
                    \Log::info('Payment processed during delivery', [
                        'order_id' => $order->id,
                        'customer_id' => $customer->id,
                        'customer_old_credit' => $oldCustomerCredit,
                        'customer_new_credit' => $customer->credit,
                        'delivery_manager_id' => $deliveryManager->id,
                        'delivery_manager_old_credit' => $oldDeliveryCredit,
                        'delivery_manager_new_credit' => $deliveryManager->credit,
                        'amount' => $order->total,
                        'date' => now()
                    ]);
                }
            }
            
            // If changing to delivered status, add delivery timestamp
            if ($request->status === 'delivered' && $previousStatus !== 'delivered') {
                $order->delivered_at = now();
                $order->delivered_by = Auth::user()->id;
            }
            
            $order->save();
            
            DB::commit();
            
            $successMessage = 'Order status updated successfully';
            if ($request->status === 'delivered' && $request->has('payment_received') && $order->payment_status === 'paid') {
                $successMessage .= ' and payment processed. $' . number_format($order->total, 2) . 
                                  ' added to your credit balance.';
            }
            
            return redirect()->route('delivery.show', $order)
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order status update failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }
    
    /**
     * Record cash payment collection
     */
    public function collectCash(Request $request, Order $order)
    {
        // Ensure this is a cash order
        if ($order->payment_method !== 'cash') {
            return back()->with('error', 'This is not a cash payment order');
        }
        
        // Ensure the order hasn't been paid already
        if ($order->payment_status === 'paid') {
            return back()->with('error', 'This order has already been paid');
        }
        
        try {
            DB::beginTransaction();
            
            // Check if customer has sufficient credit
            $customer = $order->user;
            $deliveryManager = Auth::user(); // Get the delivery manager who is processing the payment
            
            $oldCustomerCredit = $customer->credit;
            $oldDeliveryCredit = $deliveryManager->credit;
            
            if ($customer->credit < $order->total) {
                return back()->with('error', 'Customer has insufficient credit balance. Current balance: $' . number_format($customer->credit, 2));
            }
            
            // Deduct the amount from customer's credit
            $customer->credit -= $order->total;
            $customer->save();
            
            // Add the amount to delivery manager's credit
            $deliveryManager->credit += $order->total;
            $deliveryManager->save();
            
            // Mark order as paid
            $order->payment_status = 'paid';
            $order->paid_at = now();
            $order->payment_notes = 'Cash payment processed by: ' . Auth::user()->name . ' on ' . now()->format('Y-m-d H:i:s') . 
                                   '. Amount deducted from customer and added to delivery manager credit.';
            $order->save();
            
            // Log the transaction
            \Log::info('Payment processed', [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'customer_old_credit' => $oldCustomerCredit,
                'customer_new_credit' => $customer->credit,
                'delivery_manager_id' => $deliveryManager->id,
                'delivery_manager_old_credit' => $oldDeliveryCredit,
                'delivery_manager_new_credit' => $deliveryManager->credit,
                'amount' => $order->total,
                'date' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('delivery.show', $order)
                ->with('success', 'Cash payment confirmed. $' . number_format($order->total, 2) . 
                       ' deducted from customer credit and added to your account. Your new balance: $' . 
                       number_format($deliveryManager->credit, 2));
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment processing failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to process customer payment: ' . $e->getMessage());
        }
    }
} 