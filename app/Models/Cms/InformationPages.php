<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InformationPages extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'company_name',
        'company_tagline',
        'whatsapp',
        'email',
        'address',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'youtube_url',
    ];

    protected $table = 'information_pages';

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = Str::title($value);
    }
}
