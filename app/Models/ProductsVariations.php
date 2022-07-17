<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsVariations extends Model
{
    use HasFactory;
    protected $table = "products_variations";
    protected $guarded = [];
}
