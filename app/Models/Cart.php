<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductVariant;
class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ✅ Relasi baru untuk ProductVariant
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
