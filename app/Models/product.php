<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{


    public $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'image',
        'is_active',
    ];
    use HasFactory;
}
