<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'shipping_address',
        'payment_method',
        'payment_status',
        'paid_at',
        'payment_notes',
        'delivered_at',
        'delivered_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'paid_at',
        'delivered_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badgeColors = [
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger'
        ];

        $color = $badgeColors[$this->status] ?? 'bg-secondary';
        return '<span class="badge ' . $color . '">' . ucfirst($this->status) . '</span>';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badgeColors = [
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'failed' => 'bg-danger'
        ];

        $status = $this->payment_status ?? 'pending';
        $color = $badgeColors[$status] ?? 'bg-secondary';
        
        $icon = '';
        if ($status === 'paid') {
            $icon = '<i class="fas fa-check-circle me-1"></i>';
        } elseif ($status === 'pending') {
            $icon = '<i class="fas fa-clock me-1"></i>';
        } elseif ($status === 'failed') {
            $icon = '<i class="fas fa-times-circle me-1"></i>';
        }
        
        return '<span class="badge ' . $color . '">' . $icon . ucfirst($status) . '</span>';
    }

    public function getFormattedPaidAtAttribute()
    {
        return $this->paid_at ? Carbon::parse($this->paid_at)->format('M d, Y H:i') : null;
    }

    public function getFormattedDeliveredAtAttribute()
    {
        return $this->delivered_at ? Carbon::parse($this->delivered_at)->format('M d, Y H:i') : null;
    }
} 