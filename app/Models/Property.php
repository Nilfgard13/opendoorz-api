<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'bedrooms',
        'bathrooms',
        'area',
        'floor',
        'address',
        'parking',
        'status',
        'category_type_id',
        'category_location_id',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function categoryType(): BelongsTo
    {
        return $this->belongsTo(CategoryType::class);
    }

    public function categoryLocation(): BelongsTo
    {
        return $this->belongsTo(CategoryLocation::class);
    }
}
