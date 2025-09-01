<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Testimonial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'testimonials';

    public function setCustomerNameAttribute($value)
    {
        $this->attributes['customer_name'] = Str::title($value);
    }
}
