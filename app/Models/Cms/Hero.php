<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model {
    use HasFactory;

    protected $fillable = [
        'headline',
        'subheadline',
        'images',
        'is_active',
    ];

    // Cast the 'images' attribute to an array for easier handling
    protected $casts = [
        'images' => 'array',
    ];
}
