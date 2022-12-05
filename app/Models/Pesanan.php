<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'nama',
        'jumlah',
        'total',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
