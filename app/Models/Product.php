<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /*Some change by MAS*/
    use HasFactory;
    protected $table = "products";
    protected $guarded = ['id'];
}
