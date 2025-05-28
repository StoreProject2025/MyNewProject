<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($cart) {
            Log::info('Cart item created', [
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity
            ]);
        });

        static::updated(function ($cart) {
            Log::info('Cart item updated', [
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity
            ]);
        });

        static::deleted(function ($cart) {
            Log::info('Cart item deleted', [
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'product_id' => $cart->product_id
            ]);
        });
    }
}
