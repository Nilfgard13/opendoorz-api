<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'reviews';

    protected $fillable = [
        'name',
        'email',
        'nomor',
        'deskripsi',
    ];
}
