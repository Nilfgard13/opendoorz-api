<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nomor extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'nomor'];
}
