<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersProduct extends Model
{
    use HasFactory;
    protected $table = 'orders_products';
    protected $guarded = [];
    public $timestamps = false;

    public function orderProductDetailsToProduct()
    {
        return $this->belongsTo(product::class, 'product_id', 'id');
    }
}
