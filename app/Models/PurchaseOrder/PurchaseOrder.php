<?php

namespace App\Models\PurchaseOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Supplier; // Tambahkan ini

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    // Tambahkan relasi ke model Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Mutator lama (masih valid)
    public function setSupplierNameAttribute($value)
    {
        $this->attributes['supplier_name'] = Str::title($value);
    }

    public function setPoNumberAttribute($value)
    {
        $this->attributes['po_number'] = Str::upper($value);
    }
}
