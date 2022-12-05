<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'image',
        'city_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}
