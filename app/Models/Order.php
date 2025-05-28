<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        // أضف أي أعمدة أخرى حسب الحاجة
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 