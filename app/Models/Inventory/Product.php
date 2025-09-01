<?php

namespace App\Models\Inventory;

use App\Models\Cms\ProductCategory; // ✅ Perbaikan ada di sini
use App\Models\Inventory\StockMutation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function stockMutations()
    {
        return $this->hasMany(StockMutation::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }
}
