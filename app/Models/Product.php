<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'stock',
        'weight',
        'dimension_x',
        'dimension_y',
        'dimension_z'
    ];

    // image getter modify url
    public function getImageAttribute($value)
    {
        $value = str_replace('public', 'storage', $value);
        $value = url($value);
        return $value;
        // return str_replace('localhost', '192.168.43.12', $value);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
