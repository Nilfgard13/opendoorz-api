<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryLocation extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'description',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
