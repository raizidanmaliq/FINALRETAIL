<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Relasi ke tabel carts
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    /**
     * Relasi ke tabel orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
