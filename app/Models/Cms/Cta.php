<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cta extends Model
{
    use HasFactory;

    protected $table = 'ctas';

    protected $fillable = [
        'title',
        'image',
        'is_active',
    ];
}
