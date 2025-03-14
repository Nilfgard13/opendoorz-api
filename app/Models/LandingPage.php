<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LandingPage extends Model
{
    use HasFactory;

    protected $table = 'landing_page';

    protected $fillable = [
        'address',
        'number',
        'email',
        'slogan',
        'images',
        'url',
        'url_ig',
        'thumbnails',
        'experience',
        'gmap',
    ];

    protected $casts = [
        'images' => 'array',
        'thumbnails' => 'array',
    ];
}
