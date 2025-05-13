<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'cost',
        'stock_quantity',
        'brand_id',
        'category_id',
        'status',
        'featured',
        'thumbnail'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'featured' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }



    public function Images()
    {
        return $this->hasMany(ProductImage::class);
    }



    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function getProfitMarginAttribute()
    {
        $sellingPrice = $this->sale_price ?? $this->price;
        if ($this->cost == 0) return 0;

        return (($sellingPrice - $this->cost) / $sellingPrice) * 100;
    }



public function scopeFeatured($query)
{
    return $query->where('featured', true);
}

public function scopeInStock($query)
{
    return $query->where('status', 'in_stock');
}
public function getCurrentPrice()
    {
        return $this->sale_price ? $this->sale_price : $this->price;
    }
}
