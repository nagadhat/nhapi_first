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

    // function to create relationship between orders_product table and product table
    public function ordersProductToProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    // function to create relationship between orders_product table and order table
    public function ordersProductToOrder()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
