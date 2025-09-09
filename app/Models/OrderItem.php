<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Product;

class OrderItem extends Model
{
    use HasFactory;

    // `guarded` sudah mengizinkan semua kolom kecuali 'id' untuk diisi,
    // jadi tidak perlu menambahkan 'color' dan 'size' di sini.
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
