<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total',
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'payment_status',
        'shipping_status',
        'shipping_method_id',
        'payment_method',
        'shipping_address',
        'billing_address',
        'notes'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_address' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}


    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function shippingMethod()
{
    return $this->belongsTo(ShippingMethod::class);
}



}
